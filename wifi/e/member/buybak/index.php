<?php
require("../../class/connect.php");
include("../../class/db_sql.php");
include("../../class/q_functions.php");
include("../../class/user.php");
include "../".LoadLang("pub/fun.php");
$link=db_connect();
$empire=new mysqlquery();
$editor=1;
//是否登陆
$user=islogin();
$page=(int)$_GET['page'];
$start=0;
$line=25;//每页显示条数
$page_line=12;//每页显示链接数
$offset=$page*$line;//总偏移量
$totalquery="select count(*) as total from {$dbtbpre}enewsbuybak where userid='$user[userid]'";
$num=$empire->gettotal($totalquery);//取得总条数
$query="select * from {$dbtbpre}enewsbuybak where userid='$user[userid]'";
$query=$query." order by buytime desc limit $offset,$line";
$sql=$empire->query($query);
$returnpage=page1($num,$line,$page_line,$start,$page,$search);
//导入模板
require(ECMS_PATH.'e/template/member/buybak.php');
db_close();
$empire=null;
?>