<?php
define('Mpath',dirname(__FILE__).'/');
define('Mdirname' , preg_replace("/(.*)\/([^\/]+)/is","\\2",str_replace("\\","/",dirname(__FILE__))) );

require_once(Mpath."../inc/common.inc.php");

require_once(Mpath."php168/config.php");			//系统全局变量
//@require_once(Mpath."php168/all_fid.php");			//部分栏目的名称
@include_once(Mpath."php168/all_brand.php");
/*帮助*/
@include_once(Mpath."php168/all_helpfid.php");


//独立页面
@include_once(Mpath."php168/diypage.php");
@include_once(PHP168_PATH."php168/label_hf.php");		//标签头部与底部变量缓存文件
/**商务系统附件存放地址**/
$Imgdirname="business";

/***人才招聘简历存放目录 *****/
$Imgdirname_resume=$Imgdirname."/resume";

/***商家banner存放地址,不可控***/
//$company_banner_dir="";

/***商家logo存放地址,不可控***/
//$company_logo_dir=""; 

/***相册存放目录 *****/
$user_picdir=$webdb[updir]."/business/userpic/";

$Murl=$webdb[www_url].'/'.Mdirname;//本模块的访问地址

$homepage=$Mdomain."/homepage";

$Mdomain=$ModuleDB[$webdb[module_pre]][domain]?$ModuleDB[$webdb[module_pre]][domain]:$Murl;


$Mdomain=!$webdb[business_install_sys]?$webdb[www_url]:$Mdomain;


require_once(Mpath."php168/all_area.php");
require_once(Mpath."php168/all_city.php");


//必须要放在$Mdomain变量之后
$_pre=$pre."business_";

/**
*系统默认风格
**/
$STYLE=$webdb[business_style]?$webdb[business_style]:"default";

/**
*前台是否开放
**/
 
if(!$webdb[Info_webOpen])
{
	$webdb[Info_closeWhy]=str_replace("\r\n","<br>",$webdb[Info_closeWhy]);
	//showerr("网站暂时关闭:$webdb[Info_closeWhy]");
}




unset($foot_tpl,$head_tpl,$index_tpl,$list_tpl,$bencandy_tpl);


 //如果商家信息
if(substr($id,0,3)=='cp-'){
	$newurl="$Mdomain/homepage.php?uid=".intval(str_replace("cp-","",$id));
	@header("location:$newurl");
	echo "<META HTTP-EQUIV=REFRESH CONTENT='0;URL=$newurl'>";
	exit;
}

$ch=intval($ch);
$fid=intval($fid);
$id=intval($id);
$page=intval($page);



$action_name[1]="供应";
$action_name[2]="求购";
$action_name[3]="商家";






//导入常用全局函数库，必须存在，否则程序不能运行
require("comm.php");

//判断二级域名，然后定位
$webdb[vipselfdomaincannot]=$webdb[vipselfdomaincannot]?$webdb[vipselfdomaincannot]:"www,business";
$webdomain=$HTTP_HOST;

?>