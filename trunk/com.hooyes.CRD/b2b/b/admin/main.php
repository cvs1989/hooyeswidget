<?php
require_once("global.php");


require("datainfo.php");



$systemMsg=systemMsg();

	




//require("head.php");
require("template/main.htm");
//require("foot.php");



/**
*��������Ϣ
**/
function systemMsg(){
	global $db,$siteurl,$onlineip,$SCRIPT_FILENAME,$WEBURL;
	
	if(mysql_get_server_info()<'4.1'){
		$rs[mysqlVersion]=mysql_get_server_info()."(�Ͱ汾);";
	}else{
		$rs[mysqlVersion]=mysql_get_server_info()."(�߰汾);";
	}

	isset($_COOKIE) ? $rs[ifcookie]="SUCCESS" : $rs[ifcookie]="FAIL";
	$rs[sysversion]=PHP_VERSION;	//PHP�汾
	$rs[max_upload]= ini_get('upload_max_filesize') ? ini_get('upload_max_filesize') : 'Disabled';	//����ϴ�����
	$rs[max_ex_time]=ini_get('max_execution_time').' ��';	//���ִ��ʱ��
	$rs[sys_mail]= ini_get('sendmail_path') ? 'Unix Sendmail ( Path: '.ini_get('sendmail_path').')' :( ini_get('SMTP') ? 'SMTP ( Server: '.ini_get('SMTP').')': 'Disabled' );	//�ʼ�֧��ģʽ
	$rs[systemtime]=date("Y-m-j g:i A");	//����������ʱ��
	$rs[onlineip]=$onlineip;				//��ǰIP
	if( function_exists("imagealphablending") && function_exists("imagecreatefromjpeg") && function_exists("ImageJpeg") ){
		$rs[gdpic]="֧��";
	}else{
		$rs[gdpic]="��֧��";
	}
	$rs[allow_url_fopen]=ini_get('allow_url_fopen')?"On ֧�ֲɼ�����":"OFF ��֧�ֲɼ�����";
	$rs[safe_mode]=ini_get('safe_mode')?"��":"�ر�";
	$rs[DOCUMENT_ROOT]=$_SERVER["DOCUMENT_ROOT"];	//�������ڴ�������λ��
	$rs[SERVER_ADDR]=$_SERVER["SERVER_ADDR"]?$_SERVER["SERVER_ADDR"]:$_SERVER["LOCAL_ADDR"];		//������IP
	$rs[SERVER_PORT]=$_SERVER["SERVER_PORT"];		//�������˿�
	$rs[SERVER_SOFTWARE]=$_SERVER["SERVER_SOFTWARE"];	//���������
	$rs[SCRIPT_FILENAME]=$_SERVER["SCRIPT_FILENAME"]?$_SERVER["SCRIPT_FILENAME"]:$_SERVER["PATH_TRANSLATED"];//��ǰ�ļ�·��
	$rs[SERVER_NAME]=$_SERVER["SERVER_NAME"];	//����

	//��ȡZEND�İ汾
	ob_end_clean();
	ob_start();
	phpinfo();
	$phpinfo=ob_get_contents();
	ob_end_clean();
	ob_start();
	preg_match("/with(&nbsp;| )Zend(&nbsp;| )Optimizer(&nbsp;| )([^,]+),/is",$phpinfo,$zenddb);
	$rs[zendVersion]=$zenddb[4]?$zenddb[4]:"δ֪/����û��װ";
	
	return $rs;
}
?>