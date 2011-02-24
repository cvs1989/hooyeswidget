<?php
require_once("global.php");
require_once("../php168/all_helpfid.php");

//print_r($_POST);exit;
$linkdb=array("添加文档"=>"?job=addnews","栏目管理"=>"news.php","文档内容管理"=>"news.php?job=news");

//类目管理
if(!$job){

	$fup_select=choose_newssort(0,0,0,$ctype);
	$listdb=array();
	list_allsort(0,0,$ctype);
	require("head.php");
	require("template/news/sort.htm");
	require("foot.php");	
//}elseif($job=='addsort'){//添加类目
	//此页面已经集成
}elseif($job=='saveaddsort'){ //保存添加类目

	//if(!$Type&&!$mid){showerr("创建栏目,必须要选择一个模型");}
	$detail=explode("\r\n",$name);
	foreach( $detail AS $key=>$name){
	if(!$name){	continue;}		
		$name=filtrate($name);
		$db->query("INSERT INTO {$_pre}newssort (name,fup,sons,type,allowcomment,mid) VALUES ('$name','$fid','$sons','$Type',1,'$mid') ");
	}
	$db->query("update {$_pre}newssort set `sons`=`sons`+".count($detail)." where fid='$fid'");//更新子栏目数量
	newsfid_cache();
	refreshto("?","创建成功");
	
}elseif($job=="setbest"){

	$rs=$db->get_one("SELECT * FROM {$_pre}newssort WHERE fid='$fid' ");
	$db->query("update {$_pre}newssort set `best`='".($rs[best]?0:1)."' where fid='$fid'");
	newsfid_cache();
	refreshto("?","设置成功");
	
}elseif($job=='editsort'){ //编辑类目
	
	$rsdb=$db->get_one("SELECT S.* FROM {$_pre}newssort S  WHERE S.fid='$fid'");
	if($rsdb[type]){ $smallsort='none;';}else{ $bigsort='none;';}
	$group_post=group_box("postdb[allowpost]",explode(",",$rsdb[allowpost]));
	$typedb[$rsdb[type]]=" checked ";
	$index_show[$rsdb[index_show]]=" checked ";

	$forbidshow[intval($rsdb[forbidshow])]=" checked ";
	$allowcomment[intval($rsdb[allowcomment])]=" checked ";

	$listorder[$rsdb[listorder]]=" selected ";

	$tpl=unserialize($rsdb[template]);

	$select_style=select_style('postdb[style]',$rsdb[style]);

	$array=unserialize($rsdb[config]);

	$_array=array_flip($array[is_html]);

	foreach( $array[field_db] AS $key=>$rs){
		if(in_array($key,$_array)){
			$array[field_value][$key]=En_TruePath($array[field_value][$key],0);
		}
		$TempLate.=make_post_sort_table($rs,$array[field_value][$key]);
	}

	$fup_select=choose_newssort(0,0,$rsdb[fup],$ctype);

	require("head.php");
	require("template/news/editsort.htm");
	require("foot.php");
		
}elseif($job=='saveeditsort'){ //保存修改类目

	if($postdb[type]&&$db->get_one(" SELECT * FROM {$_pre}news WHERE fid='$postdb[fid]' limit 1 ")){
		 showerr("当前栏目已经有内容了,你要修改成分类的话,请先删除本栏目里的内容或把内容移走");
	}

	$rs_fid=$db->get_one("SELECT * FROM {$_pre}newssort WHERE fid='$postdb[fid]'");

	if($postdb[mid]!=$rs_fid[mid]&&$db->get_one(" SELECT * FROM {$_pre}news WHERE fid='$postdb[fid]' limit 1 ")){
		 showerr("当前栏目已经有内容了,你要修改成其他模型的话,请先删除本栏目里的内容或把内容移走");
	}

	//检查父栏目是否有问题
	//check_fup("{$_pre}newssort",$postdb[fid],$postdb[fup]);
	$postdb[allowpost]=@implode(",",$postdb[allowpost]);
	//$postdb[allowviewtitle]=@implode(",",$postdb[allowviewtitle]);
	//postdb[allowviewcontent]=@implode(",",$postdb[allowviewcontent]);
	//$postdb[allowdownload]=@implode(",",$postdb[allowdownload]);
	$postdb[template]=@serialize($postdb[tpl]);
	unset($SQL);

	$postdb[admin]=str_Replace("，",",",$postdb[admin]);
	if($postdb[admin])
	{
		$detail=explode(",",$postdb[admin]);
		foreach( $detail AS $key=>$value){
			if(!$value){
				unset($detail[$key]);
				continue;
			}
			if(!$db->get_one("SELECT * FROM $TB[table] WHERE $TB[username]='$value'")){
				showerr("你设置的栏目管理员帐号不存在:$value");
			}
		}
		$admin_str=implode(",",$detail);
		if($admin_str){
			$postdb[admin]=",$admin_str,";
		}else{
			$postdb[admin]='';
		}
	}
	
	$_sql='';
	foreach( $Together AS $key=>$value ){
		$_sql.="`$key`='{$postdb[$key]}',";
	}
	if($_sql){
		$_sql.="sons=sons";
		$db->query("UPDATE {$_pre}newssort SET $_sql WHERE fup='$postdb[fid]'");
	}

	

	$m_config=unserialize($rs_fid[config]);

	foreach( $m_config[is_html] AS $key=>$value){
		$cpostdb[$key]=En_TruePath($cpostdb[$key]);
	}
	
	$_array=array_flip($m_config[is_html]);

	foreach( $cpostdb AS $key=>$value){
		$cpostdb[$key]=stripslashes($cpostdb[$key]);
		if(is_array($value))
		{
			$cpostdb[$key]=implode("/",$value);
		}
		elseif(!@in_array($key,$_array))
		{
			//$postdb[$key]=filtrate($value);
		}
	}
	$m_config[field_value]=$cpostdb;
	$postdb[config]=addslashes(serialize($m_config));

	$postdb[name]=filtrate($postdb[name]);

	$db->query("UPDATE {$_pre}newssort SET mid='$postdb[mid]',fup='$postdb[fup]',name='$postdb[name]',type='$postdb[type]',admin='$postdb[admin]',passwd='$postdb[passwd]',logo='$postdb[logo]',descrip='$postdb[descrip]',style='$postdb[style]',template='$postdb[template]',jumpurl='$postdb[jumpurl]',listorder='$postdb[listorder]',maxperpage='$postdb[maxperpage]',allowcomment='$postdb[allowcomment]',allowpost='$postdb[allowpost]',allowviewtitle='$postdb[allowviewtitle]',allowviewcontent='$postdb[allowviewcontent]',allowdownload='$postdb[allowdownload]',forbidshow='$postdb[forbidshow]',config='$postdb[config]',index_show='$postdb[index_show]'$SQL WHERE fid='$postdb[fid]' ");

	//修改栏目名称之后,内容的也要跟着修改
	if($rs_fid[name]!=$postdb[name])
	{
		$db->query(" UPDATE {$_pre}news SET fname='$postdb[name]' WHERE fid='$postdb[fid]' ");
	}
	newsfid_cache();
	refreshto("$FROMURL","修改成功");
	
}elseif($job=='deletesort'){ //删除类目
	
	if($fid){
		$fiddb[$fid]=$fid;
	}else{
		foreach( $fiddb AS $key=>$value){
			$i++;
			$fiddb[$key]=$i;
		}
	}
	arsort($fiddb);
	foreach( $fiddb AS $fid=>$value){
		$_rs=$db->get_one("SELECT * FROM `{$_pre}newssort` WHERE fup='$fid'");
		if($_rs){
			showerr("分类有子栏目你不能删除,请先删除或移走子栏目,再删除分类");
		}
		$__rs=$db->get_one("SELECT * FROM `{$_pre}newssort` WHERE fid='$fid'");
		$db->query(" DELETE FROM `{$_pre}newssort` WHERE fid='$fid' ");
		$db->query("update {$_pre}newssort set `sons`=`sons`-1 where fid='".$__rs[fup]."'");//更新子栏目数量
	}


	//$db->query(" DELETE FROM `{$_pre}content` WHERE fid='$fid' ");
	//$rs[mid] && $db->query(" DELETE FROM `{$_pre}content_$rs[mid]` WHERE fid='$fid' ");
	
	newsfid_cache();
	refreshto("?","删除成功");
}elseif($job=='editorder'){ //保存修改类目	

	foreach( $order AS $key=>$value){
		$db->query("UPDATE {$_pre}newssort SET list='$value' WHERE fid='$key' ");
	}
	newsfid_cache();
	refreshto("$FROMURL","修改成功",1);
///新闻内容管理
}elseif($job=='news'){

	$fup_select=choose_newssort(0,0,$fid,$ctype);
	$listdb=array();
	$where="";$rows=20;
	list_allnews($fid);
	$showpage=getpage("{$_pre}news",$where ,"?job=news&fid=$fid&keyword=".urlencode($keyword),$rows,"");
	require("head.php");
	require("template/news/list.htm");
	require("foot.php");

}elseif($job=='addnews'){

	$fup_select=choose_newssort(0,0,0,$ctype);
	$select_sort=choose_sort(0,0,0,1);
	require("head.php");
	require("template/news/list.htm");
	require("foot.php");

}elseif($job=='saveaddnews'){ //保存添加新闻
	
	if(!$fid) showerr("请选择栏目进行发表");
	$newfiddb=$db->get_one("select * from `{$_pre}newssort` where fid='$fid'");
	if($newfiddb[type]) showerr("此分类下不能发布内容");
	if(!$postdb[title]) showerr("请输入标题");
	//if(strlen($postdb[smalltitle])>strlen($post[title])) showerr("简短标题应该比较简短");
	if($fid2){//如果有关联
		$fid_all2=getFidAll2($fid2);
		$fname2=$Fid_db[name][$fid2];
	}
	$fid_all=getFidAll3($fid);
	$fname=$helpFid_db[name][$fid];
	$postdb[title]=htmlspecialchars($postdb[title]);
	if($postdb[picurl]) $ispic=1;
	//暂时用简单插入
	$db->query("insert into `{$_pre}news`(id,title,content,fid,fname,fid_all,fid2,fname2,fid_all2,posttime,picurl,ispic,yz,jumpurl,author,copyfromurl) 
	values('',
	'$postdb[title]','$postdb[content]','$fid','$fname','$fid_all','$fid2','$fname2','$fid_all2','".time()."','$postdb[picurl]','$ispic','1','$postdb[jumpurl]','$postdb[author]','$postdb[copyfromurl]'
	);");
	refreshto("?job=news","发布成功",1);
	
}elseif($job=='setlevels'){ 
	
	$rs=$db->get_one("SELECT * FROM {$_pre}news WHERE id='$fid' ");
	$db->query("update {$_pre}news set `levels`='".($rs[levels]?0:1)."' where id='$id'");
	refreshto("$FROMURL","设置成功");
	
}elseif($job=='editnews'){

	$rsdb=$db->get_one("SELECT * FROM {$_pre}news WHERE id='$id'");
	$fup_select=choose_newssort(0,0,$rsdb[fid],$ctype);
	$select_sort=choose_sort(0,0,$rsdb[fid2],1);
	require("head.php");
	require("template/news/list.htm");
	require("foot.php");
	
}elseif($job=='saveeditnews'){ //保存修改新闻

	//简单更新后续完善
	if(!$fid) showerr("请选择栏目进行发表");
	$newfiddb=$db->get_one("select * from `{$_pre}newssort` where fid='$fid'");
	if($newfiddb[type]) showerr("此分类下不能发布内容");
	if(!$postdb[title]) showerr("请输入标题");
	if($fid2){//如果有关联
		$fid_all2=getFidAll2($fid2);
		$fname2=$Fid_db[name][$fid2];
	}
	$fid_all=getFidAll3($fid);
	$fname=$helpFid_db[name][$fid];
	$postdb[title]=htmlspecialchars($postdb[title]);
	if($postdb[picurl]) $ispic=1;
	//暂时用简单插入
	$db->query("update `{$_pre}news` set 
	`title`='$postdb[title]',
	`content`='$postdb[content]',
	`fid`='$fid',
	`fname`='$fname',
	`fid_all`='$fid_all',
	`fid2`='$fid2',
	`fname2`='$fname2',
	`fid_all2`='$fid_all2',
	`posttime`='".time()."',
	`picurl`='$postdb[picurl]',
	`ispic`='$ispic',
	`yz`='1',
	`jumpurl`='$postdb[jumpurl]',
	`author`='$postdb[author]',
	`copyfromurl`='$postdb[copyfromurl]'
	where id='$id'");

	refreshto("?job=news","保存成功",1);
	exit;
	
}elseif($job=='deletenews'){ 
	
	$rs=$db->get_one("SELECT * FROM {$_pre}news WHERE id='$fid' ");
	$db->query("delete from {$_pre}news where id='$id'");
	@unlink($webdb[updir]."/".$rs[picurl]);
	//如果有评论
	
	//OK 
	refreshto("$FROMURL","删除成功");
}


//functions
/*栏目列表*/
function list_allsort($fid,$Class,$ctype){
	global $db,$_pre,$listdb;
	$Class++;
	
	$query=$db->query("SELECT S.* FROM {$_pre}newssort S  where S.fup='$fid'  ORDER BY S.list DESC");
	while( $rs=$db->fetch_array($query) ){
		$icon="";
		for($i=1;$i<$Class;$i++){
			$icon.="&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;";
		}
		if($rs['class']!=$Class){
			$db->query("UPDATE {$_pre}newssort SET class='$Class' WHERE fid='$rs[fid]'");
		}
		if($icon){
			$icon=substr($icon,0,-24);
			$icon.="--";
		}
		//$rs[config]=unserialize($rs[config]);
		$rs[icon]=$icon;
		if($rs[type]){
			$rs[_type]="分类";
			$rs[_alert]="";
			$rs[color]="red";
			$rs[_ifcontent]="onclick=\"alert('分类下不能有内容,也不能发表内容,但栏目下可以有内容');return false;\" style='color:#ccc;'";
		}else{
			//$rs[_type]="<A HREF='class.php?job=listsort&ctype=$ctype&classid=$rs[fid]' style='color:blue;'>子栏目</A>";
			$rs[_type]="<A HREF='news.php?classid=$rs[fid]' style='color:blue;'>子栏目</A>";
			$rs[_alert]="onclick=\"alert('栏目下不能有栏目,但分类下可以有栏目');return false;\" style='color:#ccc;'";
			$rs[_ifcontent]="";
			$rs[color]="";
		}
		$rs[best]=$rs[best]?"是":"<font color='#676767'>否</font>";
		$listdb[]=$rs;
		list_allsort($rs[fid],$Class,$ctype);
	}
}
//类目俄列表
function choose_newssort($fid,$class,$ck=0,$ctype)
{
	global $db,$_pre;
	for($i=0;$i<$class;$i++){
		$icon.="&nbsp;&nbsp;&nbsp;&nbsp;";
	}
	$class++;          //AND type=1
	$query = $db->query("SELECT * FROM {$_pre}newssort WHERE fup='$fid' ORDER BY list DESC LIMIT 500");
	while($rs = $db->fetch_array($query)){
		$ckk=$ck==$rs[fid]?' selected ':'';
		$fup_select.="<option value='$rs[fid]' $ckk >$icon|-$rs[name]</option>";
		$fup_select.=choose_newssort($rs[fid],$class,$ck,$ctype);
	}
	return $fup_select;
}

//得到资讯列表
function list_allnews($fid){
	global $db,$_pre,$listdb,$page,$keyword,$where,$rows;
	$rows=$rows?$rows:20;
	if($page<1) $page=1;
	$min=($page-1)*$rows;
	$where=" where 1";
	if($fid) $where.=" and (fid='$fid' or concat(',',fid_all,',') like('%,$fid,%') )";
	if($keyword) $where.=" and title like('%$keyword%')";
	$query=$db->query("select * from `{$_pre}news` $where limit $min,$rows");
	while($rs=$db->fetch_array($query)){
		$rs[ispic]=$rs[ispic]?"<font color=red>[图]</font>":"&nbsp;";
		$rs[levels]=$rs[levels]?"<font color='red'>是</font>":"否";
		$listdb[]=$rs;
	}
}

/**
**通过子栏目得到全栏目-关联
**/
function getFidAll2($fid){
	global $Fid_db;
	if(!$fid) return '';	
	if($Fid_db[fup][$fid]>0){
		$fid_all=getFidAll2($Fid_db[fup][$fid]).",".$fid;
	}else{
		$fid_all=$Fid_db[fup][$fid].",".$fid;
	}
	return $fid_all;
}
/**
**通过子栏目得到全栏目 新闻
**/
function getFidAll3($fid){
	global $helpFid_db;
	if(!$fid) return '';	
	if($helpFid_db[fup][$fid]>0){
		$fid_all=getFidAll3($helpFid_db[fup][$fid]).",".$fid;
	}else{
		$fid_all=$helpFid_db[fup][$fid].",".$fid;
	}
	return $fid_all;
}
?>
