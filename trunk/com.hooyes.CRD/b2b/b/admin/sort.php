<?php
require_once("global.php");
require_once("../inc/categories.php");
set_time_limit(120);

if(isset($_GET[ctype])){
	setcookie('ctype',$_GET[ctype]);
}else{
	$ctype=$_COOKIE[ctype];
}
$linkdb=array(
			  "栏目管理"=>"sort.php?job=listsort&ctype=$ctype"
			);

/*if($fid){
	$linkdb["修改栏目"]="sort.php?job=editsort&fid=$fid&ctype=$ctype";
	$linkdb["全部字段"]="?job=listfield&fid=$fid&ctype=$ctype";
	$linkdb["增加字段"]="?job=addfield&fid=$fid&ctype=$ctype";
}*/

if($job=="listsort")
{
	$fup_select=choose_sort(0,0,0,$ctype);

	$listdb=array();
	$pagesize = 3;
	$showpage=getpage("{$_pre}sort","WHERE fup = 0","?job=listsort",$pagesize,"");
	
	list_allsort(0,0,$ctype, $page-1);
	
	//$module_select=select_module($name="mid",$rsdb[mid]);
	//$gudie=get_guide($fid,"?job=listsort&fid=");

	$parameters_module=get_parameters('',"mid");

	require("head.php");
	require("template/sort/sort.htm");
	require("foot.php");
}elseif($job=="setbest")
{
	$rs=$db->get_one("SELECT * FROM {$_pre}sort WHERE fid='$fid' ");
	$db->query("update {$_pre}sort set `best`='".($rs[best]?0:1)."' where fid='$fid'");
	$bcategory->cache_write();
	//fid_cache();
	refreshto("?job=listsort","设置成功");
}
elseif($action=="addsort")
{


	$detail=explode("\r\n",$name);
	foreach( $detail AS $key=>$name){
		if(!$name){
			continue;
		}
		
		$name=filtrate($name);
		$db->query("INSERT INTO {$_pre}sort (name,fup,sons,type,allowcomment,mid,ctype) VALUES ('$name','$fid','$sons','$Type',1,'$mid','$ctype') ");
	}

	$db->query("update {$_pre}sort set `sons`=`sons`+".count($detail)." where fid='$fid'");//更新子栏目数量
	//fid_cache();
	$bcategory->cache_write();
	refreshto("?job=listsort","创建成功");
}

//修改栏目信息
elseif($job=="editsort")
{

	//$rsdb=$db->get_one("SELECT S.*,M.name AS m_name FROM {$_pre}sort S LEFT JOIN {$_pre}module M ON S.mid=M.id WHERE S.fid='$fid'");
	$rsdb=$db->get_one("SELECT S.* FROM {$_pre}sort S  WHERE S.fid='$fid'");
	if($rsdb[type]){
		 $smallsort='none;';
	}else{
		 $bigsort='none;';
	}
	
	$parameters_module=get_parameters($rsdb);

	
	


	$group_post=group_box("postdb[allowpost]",explode(",",$rsdb[allowpost]));
	//$group_viewtitle=group_box("postdb[allowviewtitle]",explode(",",$rsdb[allowviewtitle]));
	//$group_viewcontent=group_box("postdb[allowviewcontent]",explode(",",$rsdb[allowviewcontent]));
	//$group_download=group_box("postdb[allowdownload]",explode(",",$rsdb[allowdownload]));
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

	$fup_select=choose_sort(0,0,$rsdb[fup],$ctype);

	require("head.php");
	require("template/sort/editsort.htm");
	require("foot.php");
}
elseif($action=="editsort")
{
	/* if($postdb[type]&&$db->get_one(" SELECT * FROM {$_pre}content WHERE fid='$postdb[fid]' limit 1 ")){
		 showerr("当前栏目已经有内容了,你要修改成分类的话,请先删除本栏目里的内容或把内容移走");
	} */

	$rs_fid=$db->get_one("SELECT * FROM {$_pre}sort WHERE fid='$postdb[fid]'");

	/* if($postdb[mid]!=$rs_fid[mid]&&$db->get_one(" SELECT * FROM {$_pre}content WHERE fid='$postdb[fid]' limit 1 ")){
		 showerr("当前栏目已经有内容了,你要修改成其他模型的话,请先删除本栏目里的内容或把内容移走");
	} */

	//检查父栏目是否有问题
	check_fup("{$_pre}sort",$postdb[fid],$postdb[fup]);
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
		$db->query("UPDATE {$_pre}sort SET $_sql WHERE fup='$postdb[fid]'");
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

	$db->query("UPDATE {$_pre}sort SET mid='$postdb[mid]',fup='$postdb[fup]',name='$postdb[name]',type='$postdb[type]',admin='$postdb[admin]',passwd='$postdb[passwd]',logo='$postdb[logo]',descrip='$postdb[descrip]',style='$postdb[style]',template='$postdb[template]',jumpurl='$postdb[jumpurl]',listorder='$postdb[listorder]',maxperpage='$postdb[maxperpage]',allowcomment='$postdb[allowcomment]',allowpost='$postdb[allowpost]',allowviewtitle='$postdb[allowviewtitle]',allowviewcontent='$postdb[allowviewcontent]',allowdownload='$postdb[allowdownload]',forbidshow='$postdb[forbidshow]',config='$postdb[config]',index_show='$postdb[index_show]'$SQL WHERE fid='$postdb[fid]' ");

	//修改栏目名称之后,内容的也要跟着修改
	if($rs_fid[name]!=$postdb[name])
	{
		$db->query(" UPDATE {$_pre}content_sell SET fname='$postdb[name]' WHERE fid='$postdb[fid]' ");
		$db->query(" UPDATE {$_pre}content_buy SET fname='$postdb[name]' WHERE fid='$postdb[fid]' ");
	}
	//fid_cache();
	$bcategory->cache_write();
	refreshto("$FROMURL","修改成功");
}
elseif($action=="delete")
{
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
		$_rs=$db->get_one("SELECT * FROM `{$_pre}sort` WHERE fup='$fid'");
		if($_rs){
			showerr("分类有子栏目你不能删除,请先删除或移走子栏目,再删除分类");
		}
		$__rs=$db->get_one("SELECT * FROM `{$_pre}sort` WHERE fid='$fid'");
		$db->query(" DELETE FROM `{$_pre}sort` WHERE fid='$fid' ");
		$db->query("update {$_pre}sort set `sons`=`sons`-1 where fid='".$__rs[fup]."'");//更新子栏目数量
	}


	//$db->query(" DELETE FROM `{$_pre}content` WHERE fid='$fid' ");
	//$rs[mid] && $db->query(" DELETE FROM `{$_pre}content_$rs[mid]` WHERE fid='$fid' ");
	$bcategory->cache_write();
	//fid_cache();
	refreshto("?job=listsort","删除成功");
}
elseif($action=="editlist")
{
	foreach( $order AS $key=>$value){
		$db->query("UPDATE {$_pre}sort SET list='$value' WHERE fid='$key' ");
	}
	//fid_cache();
	$bcategory->cache_write();
	refreshto("$FROMURL","修改成功",1);
}
elseif($action=="parameters")
{
	if($mid){
		foreach($fiddb AS $key=>$value){
			$db->query("UPDATE {$_pre}sort SET mid='$mid' WHERE fid='$key' ");
		}
	}else{
		showerr("请选择一个参数模型");
	}
	//fid_cache();
	$bcategory->cache_write();
	refreshto("$FROMURL","修改成功",1);
}
elseif($job=="listfield")
{
	$rsdb=$db->get_one("SELECT * FROM {$_pre}sort WHERE fid='$fid'");

	$array=unserialize($rsdb[config]);

	$listdb=$array[field_db];
	
	//$gudie=get_guide($fid,"?job=listsort&fid=");

	require("head.php");
	require("template/sort/listfield.htm");
	require("foot.php");
}
elseif($job=="addfield")
{
	$rsdb=$db->get_one("SELECT * FROM {$_pre}sort WHERE fid='$fid' ");
	//$gudie=get_guide($fid,"?job=listsort&fid=");
	require("head.php");
	require("template/sort/editfield.htm");
	require("foot.php");
}
elseif($action=="addfield")
{
	if(!ereg("^([a-z])([a-z0-9_]+)",$postdb[field_name])){
		showerr("-字段ID不符合规则");
	}
	$rsdb=$db->get_one("SELECT * FROM {$_pre}sort WHERE fid='$fid' ");
	$field_name=$postdb[field_name];
	$array=unserialize($rsdb[config]);
	$array[field_db][$field_name]=$postdb;

	if($postdb[form_type]=='ieedit'){
		$array[is_html][$field_name]=$postdb[title];
	}else{
		unset($array[is_html][$field_name]);
	}
	if($postdb[form_type]=='upfile'){
		$array[is_upfile][$field_name]=$postdb[title];
	}else{
		unset($array[is_upfile][$field_name]);
	}
	$config=addslashes(serialize($array));
	$db->query("UPDATE {$_pre}sort SET config='$config' WHERE fid='$fid' ");
	refreshto("?job=editfield&fid=$fid&field_name=$field_name","添加成功");
}
elseif($job=="editfield")
{
	$rsdb=$db->get_one("SELECT * FROM {$_pre}sort WHERE fid='$fid' ");
	//$gudie=get_guide($fid,"?job=listsort&fid=");
	$array=unserialize($rsdb[config]);
	$_rs=$array[field_db][$field_name];
	$form_type[$_rs[form_type]]=" selected ";
	$field_type[$_rs[field_type]]=" selected ";
	require("head.php");
	require("template/sort/editfield.htm");
	require("foot.php");
}

elseif($action=="editfield")
{
	$rsdb=$db->get_one("SELECT * FROM {$_pre}sort WHERE fid='$fid' ");

	$array=unserialize($rsdb[config]);

	$field_array=$array[field_db][$field_name];

	if(!ereg("^([a-z])([a-z0-9_]+)",$postdb[field_name])){
		showerr("字段ID不符合规则");
	}
	unset($array[field_db][$field_name]);
	$array[field_db]["{$postdb[field_name]}"]=$postdb;

	if($postdb[form_type]=='ieedit'){
		$array[is_html][$field_name]=$postdb[title];
	}else{
		unset($array[is_html][$field_name]);
	}
	if($postdb[form_type]=='upfile'){
		$array[is_upfile][$field_name]=$postdb[title];
	}else{
		unset($array[is_upfile][$field_name]);
	}
	$config=addslashes(serialize($array));
	$db->query("UPDATE {$_pre}sort SET config='$config' WHERE fid='$fid' ");
	refreshto("?job=editfield&fid=$fid&field_name=$postdb[field_name]","修改成功",10);
}
elseif($action=="delfield")
{
	$rsdb=$db->get_one("SELECT * FROM {$_pre}sort WHERE fid='$fid' ");
	$array=unserialize($rsdb[config]);
	unset($array[field_db][$field_name]);
	unset($array[field_value][$field_name]);
	$config=addslashes(serialize($array));
	$db->query("UPDATE {$_pre}sort SET config='$config' WHERE fid='$fid' ");
	refreshto($FROMURL,"删除成功");
}
elseif($action=="editorder")
{
	$rsdb=$db->get_one("SELECT * FROM {$_pre}sort WHERE fid='$fid' ");
	$array=unserialize($rsdb[config]);
	$field_db=$array[field_db];

	foreach( $field_db AS $key=>$value){
		$field_db[$key][orderlist]=$postdb[$key];
		$_listdb[$postdb[$key]]=$field_db[$key];
	}
	krsort($_listdb);
	foreach( $_listdb AS $key=>$rs){
		$listdb[$rs[field_name]]=$rs;
	}
	if(is_array($listdb)){
		$field_db=$listdb+$field_db;
	}
	$array[field_db]=$listdb;


	$config=addslashes(serialize($array));
	$db->query("UPDATE {$_pre}sort SET config='$config' WHERE fid='$fid' ");
	refreshto("sort.php?job=listfield&fid=$fid","修改成功",10);
}
elseif($action=="copyfield")
{
	$rs=$db->get_one("SELECT * FROM {$_pre}sort WHERE fid='$fid' ");
	$ofid=str_replace("，",",",$ofid);
	$detail=explode(",",$ofid);
	$rs[config]=addslashes($rs[config]);
	foreach( $detail AS $key=>$value){
		$db->query("UPDATE {$_pre}sort SET config='$rs[config]' WHERE fid='$value' ");
	}
	
	refreshto("sort.php?job=listfield&fid=$fid","复制成功",10);
}







function make_post_sort_table($rs,$cvalue){
	if($rs[form_type]=='text')
	{
		$show="<tr> <td >{$rs[title]}:<br>{$rs[form_title]}</td> <td > <input type='text' name='cpostdb[{$rs[field_name]}]' id='atc_{$rs[field_name]}' size='50' value='$cvalue'> </td></tr>";
	}
	elseif($rs[form_type]=='upfile')
	{
		$show="<tr> <td >{$rs[title]}:<br>{$rs[form_title]}</td> <td > <input type='text' name='cpostdb[{$rs[field_name]}]' id='atc_{$rs[field_name]}' size='50' value='$cvalue'> <br><iframe frameborder=0 height=23 scrolling=no src='../../upfile.php?fn=upfile&dir=info$fid&label=atc_{$rs[field_name]}' width=310></iframe> </td></tr>";
	}
	elseif($rs[form_type]=='textarea')
	{
		$show="<tr><td >{$rs[title]}:<br>{$rs[form_title]}</td><td ><textarea name='cpostdb[{$rs[field_name]}]' id='atc_{$rs[field_name]}' cols='70' rows='8'>$cvalue</textarea></td></tr>";
	}
	elseif($rs[form_type]=='ieedit')
	{
		$cvalue=str_replace("'","&#39;",$cvalue);
		$show="<tr><td >{$rs[title]}:<br>{$rs[form_title]}</td><td ><iframe id='eWebEditor1' src='../../ewebeditor/ewebeditor.php?id=atc_{$rs[field_name]}&style=standard' frameborder='0' scrolling='no' width='100%' height='350'></iframe><input name='cpostdb[{$rs[field_name]}]' id='atc_{$rs[field_name]}' type='hidden' value='$cvalue'></td></tr>";
	}
	elseif($rs[form_type]=='select')
	{
		$detail=explode("\r\n",$rs[form_set]);
		foreach( $detail AS $key=>$value){
			if(!$value){
				continue;
			}
			list($v1,$v2)=explode("|",$value);
			$v2 || $v2=$v1;
			$cvalue==$v1?$ckk=" selected ":$ckk="";
			$_show.="<option value='$v1' $ckk>$v2</option>";
		}
		$show="<tr> <td >{$rs[title]}:<br>{$rs[form_title]}</td><td > <select name='cpostdb[{$rs[field_name]}]' id='atc_{$rs[field_name]}'>$_show</select> </td> </tr>";
	}
	elseif($rs[form_type]=='radio')
	{
		$detail=explode("\r\n",$rs[form_set]);
		foreach( $detail AS $key=>$value){
			if(!$value){
				continue;
			}
			list($v1,$v2)=explode("|",$value);
			$v2 || $v2=$v1;
			$cvalue==$v1?$ckk=" checked ":$ckk="";
			$_show.="<input type='radio' name='cpostdb[{$rs[field_name]}]' value='$v1' $ckk>$v2";
		}
		$show="<tr> <td >{$rs[title]}:<br>{$rs[form_title]}</td> <td >$_show</td></tr>";
	}
	elseif($rs[form_type]=='checkbox')
	{
		$detail=explode("\r\n",$rs[form_set]);
		foreach( $detail AS $key=>$value){
			if(!$value){
				continue;
			}
			list($v1,$v2)=explode("|",$value);
			$v2 || $v2=$v1;
			$_d=explode("/",$cvalue);
			@in_array($v1,$_d)?$ckk=" checked ":$ckk="";
			$_show.="<input type='checkbox' name='cpostdb[{$rs[field_name]}][]' value='$v1' $ckk>$v2";
		}
		$show="<tr> <td >{$rs[title]}:<br>{$rs[form_title]}</td> <td >$_show</td></tr>";
	}
	return $show;
}


/*栏目列表*/
function list_allsort($fid,$Class,$ctype, $offset = 0){
	global $db,$_pre,$listdb,$pagesize;
	$Class++;
	//$query=$db->query("SELECT S.*,M.name AS m_name FROM {$_pre}sort S LEFT JOIN {$_pre}module M ON S.mid=M.id where S.fup='$fid' AND ctype='$ctype' ORDER BY S.list DESC");
	
	if($fid == 0){
		$offset = $offset * $pagesize;
		$limit = $pagesize;
	}else{
		$offset = 0;
		$limit = 9999;
	}
	
	$query=$db->query("SELECT S.* FROM {$_pre}sort S  where S.fup='$fid' AND ctype='$ctype' ORDER BY S.list DESC LIMIT $offset,$limit");
	while( $rs=$db->fetch_array($query) ){
		$icon="";
		for($i=1;$i<$Class;$i++){
			$icon.="&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;";
		}
		if($rs['class']!=$Class){
			$db->query("UPDATE {$_pre}sort SET class='$Class' WHERE fid='$rs[fid]'");
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
			$rs[_type]="<A HREF='class.php?job=listsort&ctype=$ctype&classid=$rs[fid]' style='color:blue;'>子栏目</A>";
			$rs[_alert]="onclick=\"alert('栏目下不能有栏目,但分类下可以有栏目');return false;\" style='color:#ccc;'";
			$rs[_ifcontent]="";
			$rs[color]="";
		}
		$rs[best]=$rs[best]?"是":"<font color='#676767'>否</font>";
		$listdb[]=$rs;
		list_allsort($rs[fid],$Class,$ctype);
	}
}

function get_parameters($rsdb='',$name="postdb[mid]"){
	global $db,$_pre;
	$parameters_module="<select name='$name'><option value=''>选择参数模型</option>";
	$query = $db->query("SELECT * FROM {$_pre}parameters_module ORDER BY listorder DESC");
	while($rs = $db->fetch_array($query)){
		$ckk=$rs[mid]==$rsdb[mid]?' selected ':'';
		$parameters_module.="<option value='$rs[mid]' $ckk>$rs[name]</option>";
	}
	$parameters_module.="</select>";
	return $parameters_module;
}
?>