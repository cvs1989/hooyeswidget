<?php
define('Mpath',dirname(__FILE__).'/');
define('Mdirname' , preg_replace("/(.*)\/([^\/]+)/is","\\2",str_replace("\\","/",dirname(__FILE__))) );

require_once(Mpath."../inc/common.inc.php");

require_once(Mpath."php168/config.php");			//ϵͳȫ�ֱ���
//@require_once(Mpath."php168/all_fid.php");			//������Ŀ������
@include_once(Mpath."php168/all_brand.php");
/*����*/
@include_once(Mpath."php168/all_helpfid.php");


//����ҳ��
@include_once(Mpath."php168/diypage.php");
@include_once(PHP168_PATH."php168/label_hf.php");		//��ǩͷ����ײ����������ļ�
/**����ϵͳ������ŵ�ַ**/
$Imgdirname="business";

/***�˲���Ƹ�������Ŀ¼ *****/
$Imgdirname_resume=$Imgdirname."/resume";

/***�̼�banner��ŵ�ַ,���ɿ�***/
//$company_banner_dir="";

/***�̼�logo��ŵ�ַ,���ɿ�***/
//$company_logo_dir=""; 

/***�����Ŀ¼ *****/
$user_picdir=$webdb[updir]."/business/userpic/";

$Murl=$webdb[www_url].'/'.Mdirname;//��ģ��ķ��ʵ�ַ

$homepage=$Mdomain."/homepage";

$Mdomain=$ModuleDB[$webdb[module_pre]][domain]?$ModuleDB[$webdb[module_pre]][domain]:$Murl;


$Mdomain=!$webdb[business_install_sys]?$webdb[www_url]:$Mdomain;


require_once(Mpath."php168/all_area.php");
require_once(Mpath."php168/all_city.php");


//����Ҫ����$Mdomain����֮��
$_pre=$pre."business_";

/**
*ϵͳĬ�Ϸ��
**/
$STYLE=$webdb[business_style]?$webdb[business_style]:"default";

/**
*ǰ̨�Ƿ񿪷�
**/
 
if(!$webdb[Info_webOpen])
{
	$webdb[Info_closeWhy]=str_replace("\r\n","<br>",$webdb[Info_closeWhy]);
	//showerr("��վ��ʱ�ر�:$webdb[Info_closeWhy]");
}




unset($foot_tpl,$head_tpl,$index_tpl,$list_tpl,$bencandy_tpl);


 //����̼���Ϣ
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



$action_name[1]="��Ӧ";
$action_name[2]="��";
$action_name[3]="�̼�";






//���볣��ȫ�ֺ����⣬������ڣ��������������
require("comm.php");

//�ж϶���������Ȼ��λ
$webdb[vipselfdomaincannot]=$webdb[vipselfdomaincannot]?$webdb[vipselfdomaincannot]:"www,business";
$webdomain=$HTTP_HOST;

?>