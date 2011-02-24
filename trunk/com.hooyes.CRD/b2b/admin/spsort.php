<?php
!function_exists('html') && exit('ERR');
if($job=="listsort"&&$Apower[spsort_listsort])
{
	$fid=intval($fid);
	
	$sortdb=array();
	list_allsort($fid,$table='spsort');

	if($fid){
		$rsdb=$db->get_one(" SELECT * FROM {$pre}spsort WHERE fid='$fid' ");
	}
	$sort_fup=$Guidedb->Select("{$pre}spsort","fup",$fid);

	require(dirname(__FILE__)."/"."head.php");
	require(dirname(__FILE__)."/"."template/spsort/menu.htm");
	require(dirname(__FILE__)."/"."template/spsort/sort.htm");
	require(dirname(__FILE__)."/"."foot.php");
}
elseif($action=="addsort"&&$Apower[spsort_listsort])
{
	if($fup){
		$rs=$db->get_one("SELECT name,class FROM {$pre}spsort WHERE fid='$fup' ");
		$class=$rs['class'];
		$db->query("UPDATE {$pre}spsort SET sons=sons+1 WHERE fid='$fup'");
		$type=0;
	}else{
		
		$class=0;
	}
	$type=1;	/*分类标志*/
	$class++;
	$db->query("INSERT INTO {$pre}spsort (name,fup,class,type,allowcomment) VALUES ('$name','$fup','$class','$type',1) ");
	@extract($db->get_one("SELECT fid FROM {$pre}spsort ORDER BY fid DESC LIMIT 0,1"));
	
	mod_sort_class("{$pre}spsort",0,0);		//更新class
	mod_sort_sons("{$pre}spsort",0);			//更新sons
	/*更新导航缓存*/
	cache_guide();
	jump("创建成功","index.php?lfj=$lfj&job=editsort&fid=$fid");
}

//修改栏目信息
elseif($job=="editsort"&&$Apower[spsort_listsort])
{
	$postdb[fid] && $fid=$postdb[fid];
	$rsdb=$db->get_one("SELECT * FROM {$pre}spsort WHERE fid='$fid'");
	$rsdb[config]=unserialize($rsdb[config]);
	$sort_fid=$Guidedb->Select("{$pre}spsort","postdb[fid]",$fid,"index.php?lfj=$lfj&job=$job");
	$sort_fup=$Guidedb->Select("{$pre}spsort","postdb[fup]",$rsdb[fup]);
	$style_select=select_style('postdb[style]',$rsdb[style]);
	$group_post=group_box("postdb[allowpost]",explode(",",$rsdb[allowpost]));
	$group_viewtitle=group_box("postdb[allowviewtitle]",explode(",",$rsdb[allowviewtitle]));
	$group_viewcontent=group_box("postdb[allowviewcontent]",explode(",",$rsdb[allowviewcontent]));
	$group_download=group_box("postdb[allowdownload]",explode(",",$rsdb[allowdownload]));
	$typedb[$rsdb[type]]=" checked ";

	$forbidshow[intval($rsdb[forbidshow])]=" checked ";
	$allowcomment[intval($rsdb[allowcomment])]=" checked ";

	$tpl=unserialize($rsdb[template]);
	$tpl_head=select_template("",7,$tpl[head]);
	$tpl_head=str_replace("<select","<select onChange='get_obj(\"tpl_head\").value=this.options[this.selectedIndex].value;'",$tpl_head);

	$tpl_foot=select_template("",8,$tpl[foot]);
	$tpl_foot=str_replace("<select","<select onChange='get_obj(\"tpl_foot\").value=this.options[this.selectedIndex].value;'",$tpl_foot);

	$tpl_list=select_template("",10,$tpl['list']);
	$tpl_list=str_replace("<select","<select onChange='get_obj(\"tpl_list\").value=this.options[this.selectedIndex].value;'",$tpl_list);

	$tpl_bencandy=select_template("",11,$tpl[bencandy]);
	$tpl_bencandy=str_replace("<select","<select onChange='get_obj(\"tpl_bencandy\").value=this.options[this.selectedIndex].value;'",$tpl_bencandy);

	$listorder[$rsdb[listorder]]=" selected ";


	$sonListorder[$rsdb[config][sonListorder]]=" selected ";


	require(dirname(__FILE__)."/"."head.php");
	require(dirname(__FILE__)."/"."template/spsort/menu.htm");
	require(dirname(__FILE__)."/"."template/spsort/editsort.htm");
	require(dirname(__FILE__)."/"."foot.php");
}
elseif($action=="editsort"&&$Apower[spsort_listsort])
{
	//检查父栏目是否有问题
	check_fup("{$pre}spsort",$postdb[fid],$postdb[fup]);
	$postdb[allowpost]=@implode(",",$postdb[allowpost]);
	$postdb[allowviewtitle]=@implode(",",$postdb[allowviewtitle]);
	$postdb[allowviewcontent]=@implode(",",$postdb[allowviewcontent]);
	$postdb[allowdownload]=@implode(",",$postdb[allowdownload]);
	$postdb[template]=@serialize($postdb[tpl]);
	unset($SQL);

	$rs_fid=$db->get_one("SELECT * FROM {$pre}spsort WHERE fid='$postdb[fid]'");
	//这样处理是其他地方也修改过这个值.比如标签里
	$rs_fid[config]=unserialize($rs_fid[config]);
	$rs_fid[config][sonTitleRow]=$sonTitleRow;
	$rs_fid[config][sonTitleLeng]=$sonTitleLeng;
	$rs_fid[config][cachetime]=$cachetime;
	$rs_fid[config][sonListorder]=$sonListorder;
	$postdb[config]=addslashes( serialize($rs_fid[config]) );

	if($rs_fid[fup]!=$postdb[fup])
	{
		$rs_fup=$db->get_one("SELECT class FROM {$pre}spsort WHERE fup='$postdb[fup]' ");
		$newclass=$rs_fup['class']+1;
		$db->query("UPDATE {$pre}spsort SET sons=sons+1 WHERE fup='$postdb[fup]' ");
		$db->query("UPDATE {$pre}spsort SET sons=sons-1 WHERE fup='$rs_fid[fup]' ");
		$SQL=",class=$newclass";
	}
	
	$db->query("UPDATE {$pre}spsort SET fup='$postdb[fup]',name='$postdb[name]',type='$postdb[type]',admin='$postdb[admin]',passwd='$postdb[passwd]',logo='$postdb[logo]',descrip='$postdb[descrip]',style='$postdb[style]',template='$postdb[template]',jumpurl='$postdb[jumpurl]',listorder='$postdb[listorder]',maxperpage='$postdb[maxperpage]',allowcomment='$postdb[allowcomment]',allowpost='$postdb[allowpost]',allowviewtitle='$postdb[allowviewtitle]',allowviewcontent='$postdb[allowviewcontent]',allowdownload='$postdb[allowdownload]',forbidshow='$postdb[forbidshow]',metakeywords='$postdb[metakeywords]',config='$postdb[config]'$SQL WHERE fid='$postdb[fid]' ");

	mod_sort_class("{$pre}spsort",0,0);		//更新class
	mod_sort_sons("{$pre}spsort",0);			//更新sons
	/*更新导航缓存*/
	cache_guide();
	jump("修改成功","$FROMURL");
}
elseif($action=="delete"&&$Apower[spsort_listsort])
{
	$db->query(" DELETE FROM `{$pre}spsort` WHERE fid='$fid' ");
	
	mod_sort_class("{$pre}spsort",0,0);		//更新class
	mod_sort_sons("{$pre}spsort",0);			//更新sons
	/*更新导航缓存*/
	cache_guide();
	jump("删除成功",$FROMURL);
}
elseif($action=="editlist"&&$Apower[spsort_listsort])
{
	foreach( $order AS $key=>$value){
		$db->query("UPDATE {$pre}spsort SET list='$value' WHERE fid='$key' ");
	}
	mod_sort_class("{$pre}spsort",0,0);		//更新class
	mod_sort_sons("{$pre}spsort",0);			//更新sons
	/*更新导航缓存*/
	cache_guide();
	jump("修改成功","$FROMURL",1);
}
/**
*修复网站栏目
**/
elseif($job=='save'&&$Apower[spsort_listsort])
{
	$errsort=sort_error("{$pre}spsort",'fid');
 	$sort_fup=$Guidedb->Select("{$pre}spsort","fup",$rsdb[fup]);
	require(dirname(__FILE__)."/"."head.php");
	require(dirname(__FILE__)."/"."template/spsort/menu.htm");
	require(dirname(__FILE__)."/"."template/spsort/save.htm");
	require(dirname(__FILE__)."/"."foot.php");
}

/**
*处理修复出错栏目
**/
elseif($action=='save'&&$Apower[spsort_listsort]){
	if(!$fid){
		showmsg("请选择一个栏目");
	}
	$db->query("UPDATE {$pre}spsort SET fid='$fup' WHERE fid='$fid' ");
	mod_sort_class("{$pre}spsort",0,0);			//更新class
	mod_sort_sons("{$pre}spsort",0);			//更新sons
	/*更新导航缓存*/
	cache_guide();
	jump("本栏目修正成功","$FROMURL",1);
}

/**
*更新导航缓存
**/
function cache_guide(){
	global $Guidedb,$pre;
	//$Guidedb->FidSonCache("{$pre}spsort","../php168/guideSP_fid.php",1);
	$Guidedb->GuideFidCache("{$pre}spsort","../php168/guideSP_fid.php",1);
}


?>