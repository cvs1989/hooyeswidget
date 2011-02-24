<?php

define('Mdirname', preg_replace("/(.*)\/([^\/]+)\/([^\/]+)/is","\\2",str_replace("\\","/",dirname(__FILE__))) );

define('Memberpath',dirname(__FILE__).'/');

require(Memberpath."../../inc/common.inc.php");
require(PHP168_PATH."php168/level.php");
require(Memberpath."../php168/config.php");
require(Memberpath."../php168/all_fid.php");

@include_once(PHP168_PATH."php168/module.php");

//$_pre="{$pre}{$webdb[module_pre]}";					//���ݱ�ǰ׺
$_pre=$pre."business_";

$Murl=$webdb[www_url].'/'.Mdirname;//��ģ��ķ��ʵ�ַ
$homepage=$Mdomain."/homepage";                        //�˴������ǰ̨global.php�ļ�������һ����
$Imgdirname="business";                             //�˴������ǰ̨global.php�ļ�������һ����

$Mdomain=$ModuleDB[$webdb[module_pre]][domain]?$ModuleDB[$webdb[module_pre]][domain]:$Murl;
$Mdomain=!$webdb[business_install_sys]?$webdb[www_url]:$Mdomain;

if(!$webdb[web_open])
{
	$webdb[close_why] = str_replace("\n","<br>",$webdb[close_why]);
	showerr_member("��վ��ʱ�ر�:$webdb[close_why]");
}

if(!$lfjid || !$lfjuid){
	showerr_member("�㻹û��¼���ߵ�½ʧЧ�����ٴε�½��");
}

$member_style=$webdb[sys_member_style]?$webdb[sys_member_style]:($webdb[member_style]?$webdb[member_style]:"images2");



/**
*��Ҫ�ṩ������,����,�ضε�ѡ��ʹ��
**/
function select_where($table,$name='fup',$ck='',$fup=''){
	global $db;
	if($fup){
		$SQL=" WHERE fup='$fup' ";
	}
	$query = $db->query("SELECT * FROM $table $SQL ORDER BY list DESC");
	while($rs = $db->fetch_array($query)){
		$ckk=$ck==$rs[fid]?" selected ":" ";
		$show.="<option value='$rs[fid]' $ckk>$rs[name]</option>";
	}
	return "<select id='$table' name=$name><option value=''>��ѡ��</option>$show</select>";
}

/**
�õ���֤��ʶ
**/
function getrenzheng($re)
{
	global $Murl,$STYLE;
	if($re==1){
		return "<img src='{$Murl}/images/{$STYLE}/jibenrenzheng.gif'  border='0'/>";
	}elseif($re==2){
		return "<img src='{$Murl}/images/{$STYLE}/yinpairenzheng.gif'  border='0'/>";
	}elseif($re==3){
		return "<img src='{$Murl}/images/{$STYLE}/jinpairenzheng.gif'  border='0'/>";
	}else{
		return "δ��֤";
	}
}

/*
*�õ�ͼƬĿ¼
*/
function getimgdir($img,$ctype=1){
global $webdb,$Imgdirname;
	if($ctype==1){
		return $webdb[www_url].'/'.$webdb[updir].'/'.$Imgdirname.'/'.$img;
	}elseif($ctype==2){
		return $webdb[www_url].'/'.$webdb[updir].'/'.$Imgdirname.'/'.$img;
	}elseif($ctype==3){
		return $webdb[www_url].'/'.$webdb[updir].'/'.$Imgdirname.'/ico/'.$img;
	}
	return "";
}


function showerr_member($msg){
	global $Mdomain;
	echo '<meta http-equiv="Content-Type" content="text/html; charset=gb2312">
	';
	echo  "<script>
	alert('{$msg}');
	parent.location='{$Mdomain}';
	</script>";

	exit;
}


?>