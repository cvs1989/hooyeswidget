<?php
if(!defined('empirecms'))
{
	exit();
}
//扣点
require_once($check_path."e/class/connect.php");
if(!defined('InEmpireCMS'))
{
	exit();
}
require_once(ECMS_PATH."e/class/db_sql.php");
require_once(ECMS_PATH."e/class/user.php");
require_once(ECMS_PATH.'e/data/dbcache/MemberLevel.php');
$check_classid=(int)$check_classid;
$check_groupid=(int)$check_groupid;
$toreturnurl=$_SERVER['PHP_SELF'];	//返回页面地址
$gotourl=$eloginurl?$eloginurl:$public_r['newsurl']."e/member/login/";	//登陆地址
$loginuserid=(int)getcvar('mluserid');
$logingroupid=(int)getcvar('mlgroupid');
if(!$loginuserid)
{
	printerror2("本栏目需要 ".$level_r[$check_groupid][groupname]." 会员级别以上才能查看","");
}
if($level_r[$logingroupid][level]<$level_r[$check_groupid][level])
{
	printerror2("本栏目需要 ".$level_r[$check_groupid][groupname]." 会员级别以上才能查看","");
}
?>