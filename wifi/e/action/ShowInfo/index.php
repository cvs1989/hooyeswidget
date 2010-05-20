<?php
require('../../class/connect.php');
include('../../class/db_sql.php');
include('../../class/functions.php');
include('../../class/t_functions.php');
include '../'.LoadLang('pub/fun.php');
include('../../data/dbcache/class.php');
include('../../data/dbcache/MemberLevel.php');
$link=db_connect();
$empire=new mysqlquery();
$classid=(int)$_GET['classid'];
$id=(int)$_GET['id'];
$addgethtmlpath='../';
$titleurl=DoGetHtml($classid,$id);
db_close();
$empire=null;
Header("Location:$titleurl");
?>