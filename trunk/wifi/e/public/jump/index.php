<?php
require("../../class/connect.php");
$id=(int)$_GET['id'];
$classid=(int)$_GET['classid'];
$url=$_GET['url'];
$url=htmlspecialchars(urldecode($url));
//来源是否正确
if(!$_SERVER['HTTP_REFERER'])
{
	Header("Location:$url");
	exit();
}
if($url&&$id&&$classid)
{
	include("../../class/db_sql.php");
	include("../../data/dbcache/class.php");
	$link=db_connect();
	$empire=new mysqlquery();
	if(empty($class_r[$classid][tbname]))
	{
		exit();
    }
	$sql=$empire->query("update {$dbtbpre}ecms_".$class_r[$classid][tbname]." set onclick=onclick+1 where id='$id'");
	db_close();
	$empire=null;
	Header("Location:$url");
}
?>