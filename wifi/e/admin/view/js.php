<?php
require("../../class/connect.php");
require("../../class/db_sql.php");
require("../../class/functions.php");
require("../../data/dbcache/class.php");
$link=db_connect();
$empire=new mysqlquery();
$editor=1;
//验证用户
$lur=is_login();
$logininid=$lur['userid'];
$loginin=$lur['username'];
$loginrnd=$lur['rnd'];
$loginlevel=$lur['groupid'];
$loginadminstyleid=$lur['adminstyleid'];

$js=$_GET['js'];
$classid=$_GET['classid'];
$ztid=$_GET['ztid'];
$p=$_GET['p'];
if($classid)
{
	$url=$js;
}
else
{
	$url="../../../d/js/".$p."/".$js.".js";
}
?>
<link href="../adminstyle/<?=$loginadminstyleid?>/adminstyle.css" rel="stylesheet" type="text/css">
<script src=<?=$url?>></script>
