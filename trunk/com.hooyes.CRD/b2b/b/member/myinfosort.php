<?php
require(dirname(__FILE__)."/"."global.php");

if(!$action){
	$query=$db->query("select * from {$_pre}mysort where uid='$lfjuid' order by listorder desc;");
	while($rs=$db->fetch_array($query)){
		$mysort[$rs[ctype]][]=$rs;
	}
	
}elseif($action=='add'){
	if(!$sortname || strlen($sortname)>20)	showerr("分类名称不能为空，且小于20个字符");
	extract($db->get_one("select count(*) as maxnum from {$_pre}mysort where uid='$lfjuid' "));
	$webdb[maxMysort]=$webdb[maxMysort]?$webdb[maxMysort]:10;
	if($maxnum > $webdb[maxMysort])howerr("抱歉，您只能添加{$webdb[maxMysort]}个信息分类");
	$db->query("INSERT INTO `{$_pre}mysort` ( `ms_id` , `uid` , `sortname` , `fup` , `listorder` , `ctype` , `hits` , `best` )
	VALUES ('', '$lfjuid', '$sortname', '$fup', '$listorder', '$ctype', '0', '0');");
	refreshto("?","添加成功",1);
	
}elseif($action=='update'){
	if(!$sortname || strlen($sortname)>20)	showerr("分类名称不能为空，且小于20个字符");
	if(!$edit_ms_id) showerr("非法操作");
	$db->query("update `{$_pre}mysort` set 	`sortname`='$sortname',	`listorder`='$listorder'	where ms_id='$edit_ms_id' and uid='$lfjuid'");
	refreshto("?","编辑成功",1);
	
}elseif($action=='del'){
	
	if(!$del_ms_id) showerr("非法操作");
	/* $hava=$db->get_one("select count(*) as  maxnum from {$_pre}content where ms_id='$del_ms_id'");
	if($hava[maxnum]>0){
	showerr("非空分类，不能删除,先处理此分类中的信息");
	} */
	$db->query("delete from `{$_pre}mysort` where ms_id='$del_ms_id'");
	refreshto("?","删除成功",1);
}


require(dirname(__FILE__)."/"."head.php");
require(dirname(__FILE__)."/"."template/myinfosort.htm");
require(dirname(__FILE__)."/"."foot.php");
?>