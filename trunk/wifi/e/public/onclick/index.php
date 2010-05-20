<?php
require("../../class/connect.php");
require("../../class/db_sql.php");
$link=db_connect();
$empire=new mysqlquery();
//------------------------------点击
function onclick($id,$bclassid,$classid,$ztid,$enews){
	global $empire,$dbtbpre;
	if($enews=="donews")//内容点击
	{
		$r=$empire->fetch1("select tbname from {$dbtbpre}enewsclass where classid='$classid'");
		if(empty($r[tbname]))
		{
			return '';
		}
		$query="update {$dbtbpre}ecms_".$r[tbname]." set onclick=onclick+1 where id='$id'";
	}
	elseif($enews=="doclass")//栏目点击
	{
		$query="update {$dbtbpre}enewsclass set onclick=onclick+1 where classid='$classid'";
	}
	elseif($enews=="dozt")//专题点击
	{
		$query="update {$dbtbpre}enewszt set onclick=onclick+1 where ztid='$ztid'";
	}
	else
	{return "";}
	$empire->query($query);
}
$id=(int)$_GET['id'];
$bclassid=(int)$_GET['bclassid'];
$classid=(int)$_GET['classid'];
$ztid=(int)$_GET['ztid'];
$enews=$_GET['enews'];
onclick($id,$bclassid,$classid,$ztid,$enews);
db_close();
$empire=null;
?>
