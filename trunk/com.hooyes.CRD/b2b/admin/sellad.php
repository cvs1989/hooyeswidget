<?php
!function_exists('html') && exit('ERR');

//列出所有广告
if($job=="listad"&&$Apower[sellad]){
	$query = $db->query("SELECT * FROM `{$pre}sellad` ORDER BY list DESC");
	while($rs = $db->fetch_array($query)){
		$_s1=$db->get_one("SELECT COUNT(*) AS Num FROM `{$pre}sellad_user` WHERE id='$rs[id]'");
		$rs[AllAdNum]=$_s1[Num];
		$_s2=$db->get_one("SELECT COUNT(*) AS Num FROM `{$pre}sellad_user` WHERE id='$rs[id]' AND endtime>$timestamp");
		$rs[AdNum]=$_s2[Num];
		$rs[isclose]=$rs[isclose]?'关闭':'开放';
		$listdb[]=$rs;
	}
	require(dirname(__FILE__)."/"."head.php");
	require(dirname(__FILE__)."/"."template/sellad/menu.htm");
	require(dirname(__FILE__)."/"."template/sellad/listad.htm");
	require(dirname(__FILE__)."/"."foot.php");
}

//添加广告
elseif($job=="addplace"&&$Apower[sellad])
{
	require(dirname(__FILE__)."/"."head.php");
	require(dirname(__FILE__)."/"."template/sellad/menu.htm");
	require(dirname(__FILE__)."/"."template/sellad/addplace.htm");
	require(dirname(__FILE__)."/"."foot.php");
}

//修改广告
elseif($job=="editadplace"&&$Apower[sellad])
{
	$rsdb=$db->get_one("SELECT * FROM `{$pre}sellad` WHERE id='$id'");
	$isclose[intval($rsdb[isclose])]=" checked ";
	require(dirname(__FILE__)."/"."head.php");
	require(dirname(__FILE__)."/"."template/sellad/menu.htm");
	require(dirname(__FILE__)."/"."template/sellad/addplace.htm");
	require(dirname(__FILE__)."/"."foot.php");
}

//处理修改广告
elseif($action=="editadplace"&&$Apower[sellad])
{
	if($postdb[day]<1){
		showmsg("有效投放天数不能小于1天");
	}
	if($postdb[price]<1){
		showmsg("最低起价不能小于1");
	}
	$db->query("UPDATE `{$pre}sellad` SET name='$postdb[name]',price='$postdb[price]',day='$postdb[day]',isclose='$isclose',adnum='$postdb[adnum]',wordnum='$postdb[wordnum]',list='$postdb[list]',demourl='$postdb[demourl]' WHERE id='$id' ");
	
	jump("修改成功","index.php?lfj=sellad&job=listad",1);
}

//处理添加广告
elseif($action=="addplace"&&$Apower[sellad])
{

	if(!$IS_BIZPhp168){
		@extract($db->get_one("SELECT COUNT(*) AS NUM FROM {$pre}sellad"));
		if($NUM>19){
			showerr("免费版最多只能创建20个");
		}
	}

	if($postdb[day]<1){
		showmsg("有效投放天数不能小于1天");
	}
	if($postdb[price]<1){
		showmsg("最低起价不能小于1");
	}
	$db->query("INSERT INTO `{$pre}sellad` (`name` , `price` , `day`, `adnum`, `wordnum`, `demourl`) VALUES ('$postdb[name]','$postdb[price]','$postdb[day]','$postdb[adnum]','$postdb[wordnum]','$postdb[demourl]')");	
				
	jump("添加成功","?lfj=$lfj&job=listad",1);
}

//删除广告
elseif($action=='deleteadplace'&&$Apower[sellad])
{
	$db->query("DELETE FROM `{$pre}sellad` WHERE id='$id'");
	$db->query("DELETE FROM `{$pre}sellad_user` WHERE id='$id'");
	jump("删除成功","$FROMURL",1);
}

elseif($job=="listuser"&&$Apower[sellad_listuser])
{
	if($page<1){
		$page=1;
	}
	$rows=20;
	$min=($page-1)*$rows;
	if($id){
		$SQL=" WHERE A.id='$id' ";
	}
	$showpage=getpage("`{$pre}sellad_user` A","$SQL","?job=$job",$rows);
	$query = $db->query("SELECT A.*,B.* FROM `{$pre}sellad_user` A LEFT JOIN `{$pre}sellad` B ON A.id=B.id $SQL ORDER BY A.endtime DESC LIMIT $min,$rows");
	while($rs = $db->fetch_array($query)){
		$rs[begintime]=date("Y-m-d H:i",$rs[begintime]);
		$rs[endtime]=date("Y-m-d H:i",$rs[endtime]);
		$listdb[]=$rs;
	}
	require("head.php");
	require("template/sellad/listuser.htm");
	require("foot.php");
}

elseif($action=="deleteusr"&&$Apower[sellad_listuser])
{
	$db->query("DELETE FROM `{$pre}sellad_user` WHERE ad_id='$ad_id'");
	jump("删除成功","$FROMURL",1);
}

?>