<?php
require("../../../class/connect.php");
require("../../../class/q_functions.php");
require("../../../class/db_sql.php");
require("../../../class/user.php");
$link=db_connect();
$empire=new mysqlquery();
$editor=2;
$ecmsreurl=1;
$user=islogin();
$a="";
$cid=(int)$_GET['cid'];
if($cid)
{
	$a=" and cid=$cid";
}
$query="select fname from {$dbtbpre}enewshy where userid='$user[userid]'".$a." order by fid";
$sql=$empire->query($query);
while($r=$empire->fetch($sql))
{
	$hyselect.="<option value='".$r['fname']."'>".$r['fname']."</option>";
}
//分类
$select=ReturnFavaClass($user[userid],$cid,1);
$fm=$_GET['fm'];
$f=$_GET['f'];
$addvar="fm=".$fm."&f=".$f;
//导入模板
require(ECMS_PATH.'e/template/member/ChangeFriend.php');
db_close();
$empire=null;
?>