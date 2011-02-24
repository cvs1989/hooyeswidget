<?php
!function_exists('html') && exit('ERR');

if($job=="list"&&$Apower[copyfrom_list])
{
	if(!$page){
		$page=1;
	}
	$rows=50;
	$min=($page-1)*$rows;
	$showpage=getpage("{$pre}copyfrom","","?lfj=$lfj&job=$job",$rows);
	$query = $db->query("SELECT * FROM {$pre}copyfrom ORDER BY list DESC LIMIT $min,$rows");
	while($rs = $db->fetch_array($query)){
		$listdb[]=$rs;
	}
	require(dirname(__FILE__)."/"."head.php");
	require(dirname(__FILE__)."/"."template/copyfrom/menu.htm");
	require(dirname(__FILE__)."/"."template/copyfrom/list.htm");
	require(dirname(__FILE__)."/"."foot.php");
}
elseif($action=="del"&&$Apower[copyfrom_list])
{
	foreach( $iddb AS $key=>$value){
		$db->query("DELETE FROM {$pre}copyfrom WHERE id='$value'");
	}
	jump("删除成功",$FROMURL,0);
}
elseif($job=="add"&&$Apower[copyfrom_list])
{
	$rsdb['list']=0;
	require(dirname(__FILE__)."/"."head.php");
	require(dirname(__FILE__)."/"."template/copyfrom/menu.htm");
	require(dirname(__FILE__)."/"."template/copyfrom/edit.htm");
	require(dirname(__FILE__)."/"."foot.php");
}
elseif($action=="add"&&$Apower[copyfrom_list])
{
	$db->query("INSERT INTO `{$pre}copyfrom` (`name` , `list` ) VALUES ( '$keywords', '$list')");
	jump("添加成功","index.php?lfj=$lfj&job=list",1);
}
elseif($job=="edit"&&$Apower[copyfrom_list])
{
	$rsdb=$db->get_one("SELECT * FROM {$pre}copyfrom WHERE id='$id'");
	require(dirname(__FILE__)."/"."head.php");
	require(dirname(__FILE__)."/"."template/copyfrom/menu.htm");
	require(dirname(__FILE__)."/"."template/copyfrom/edit.htm");
	require(dirname(__FILE__)."/"."foot.php");
}
elseif($action=="edit"&&$Apower[copyfrom_list])
{
	$db->query("UPDATE `{$pre}copyfrom` SET `name`='$keywords',`list`='$list' WHERE id='$id'");
	jump("修改成功","$FROMURL",1);
}

?>