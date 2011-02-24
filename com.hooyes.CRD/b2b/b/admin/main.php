<?php
require_once("global.php");


require("datainfo.php");



$systemMsg=systemMsg();

	




//require("head.php");
require("template/main.htm");
//require("foot.php");



/**
*服务器信息
**/
function systemMsg(){
	global $db,$siteurl,$onlineip,$SCRIPT_FILENAME,$WEBURL;
	
	if(mysql_get_server_info()<'4.1'){
		$rs[mysqlVersion]=mysql_get_server_info()."(低版本);";
	}else{
		$rs[mysqlVersion]=mysql_get_server_info()."(高版本);";
	}

	isset($_COOKIE) ? $rs[ifcookie]="SUCCESS" : $rs[ifcookie]="FAIL";
	$rs[sysversion]=PHP_VERSION;	//PHP版本
	$rs[max_upload]= ini_get('upload_max_filesize') ? ini_get('upload_max_filesize') : 'Disabled';	//最大上传限制
	$rs[max_ex_time]=ini_get('max_execution_time').' 秒';	//最大执行时间
	$rs[sys_mail]= ini_get('sendmail_path') ? 'Unix Sendmail ( Path: '.ini_get('sendmail_path').')' :( ini_get('SMTP') ? 'SMTP ( Server: '.ini_get('SMTP').')': 'Disabled' );	//邮件支持模式
	$rs[systemtime]=date("Y-m-j g:i A");	//服务器所在时间
	$rs[onlineip]=$onlineip;				//当前IP
	if( function_exists("imagealphablending") && function_exists("imagecreatefromjpeg") && function_exists("ImageJpeg") ){
		$rs[gdpic]="支持";
	}else{
		$rs[gdpic]="不支持";
	}
	$rs[allow_url_fopen]=ini_get('allow_url_fopen')?"On 支持采集数据":"OFF 不支持采集数据";
	$rs[safe_mode]=ini_get('safe_mode')?"打开":"关闭";
	$rs[DOCUMENT_ROOT]=$_SERVER["DOCUMENT_ROOT"];	//程序所在磁盘物理位置
	$rs[SERVER_ADDR]=$_SERVER["SERVER_ADDR"]?$_SERVER["SERVER_ADDR"]:$_SERVER["LOCAL_ADDR"];		//服务器IP
	$rs[SERVER_PORT]=$_SERVER["SERVER_PORT"];		//服务器端口
	$rs[SERVER_SOFTWARE]=$_SERVER["SERVER_SOFTWARE"];	//服务器软件
	$rs[SCRIPT_FILENAME]=$_SERVER["SCRIPT_FILENAME"]?$_SERVER["SCRIPT_FILENAME"]:$_SERVER["PATH_TRANSLATED"];//当前文件路径
	$rs[SERVER_NAME]=$_SERVER["SERVER_NAME"];	//域名

	//获取ZEND的版本
	ob_end_clean();
	ob_start();
	phpinfo();
	$phpinfo=ob_get_contents();
	ob_end_clean();
	ob_start();
	preg_match("/with(&nbsp;| )Zend(&nbsp;| )Optimizer(&nbsp;| )([^,]+),/is",$phpinfo,$zenddb);
	$rs[zendVersion]=$zenddb[4]?$zenddb[4]:"未知/可能没安装";
	
	return $rs;
}
?>