<?php

define('Mdirname', preg_replace("/(.*)\/([^\/]+)\/([^\/]+)/is","\\2",str_replace("\\","/",dirname(__FILE__))) );

define('Memberpath',dirname(__FILE__).'/');

require(Memberpath."../../inc/common.inc.php");
require(PHP168_PATH."php168/level.php");
require(Memberpath."../php168/config.php");
require(Memberpath."../php168/all_fid.php");

@include_once(PHP168_PATH."php168/module.php");

//$_pre="{$pre}{$webdb[module_pre]}";					//数据表前缀
$_pre=$pre."business_";

$Murl=$webdb[www_url].'/'.Mdirname;//本模块的访问地址
$homepage=$Mdomain."/homepage";                        //此处必须和前台global.php文件中设置一样的
$Imgdirname="business";                             //此处必须和前台global.php文件中设置一样的

$Mdomain=$ModuleDB[$webdb[module_pre]][domain]?$ModuleDB[$webdb[module_pre]][domain]:$Murl;
$Mdomain=!$webdb[business_install_sys]?$webdb[www_url]:$Mdomain;

if(!$webdb[web_open])
{
	$webdb[close_why] = str_replace("\n","<br>",$webdb[close_why]);
	showerr_member("网站暂时关闭:$webdb[close_why]");
}

if(!$lfjid || !$lfjuid){
	showerr_member("你还没登录或者登陆失效，请再次登陆。");
}

$member_style=$webdb[sys_member_style]?$webdb[sys_member_style]:($webdb[member_style]?$webdb[member_style]:"images2");



/**
*主要提供给城市,区域,地段的选择使用
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
	return "<select id='$table' name=$name><option value=''>请选择</option>$show</select>";
}

/**
得到认证标识
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
		return "未认证";
	}
}

/*
*得到图片目录
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