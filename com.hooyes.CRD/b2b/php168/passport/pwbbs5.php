<?php
$TB_pre=$webdb[passport_pre]?$webdb[passport_pre]:"pw_";
$TB_path=$webdb[passport_path];
$TB_url=$webdb[passport_url];
$TB_register="register.php";
$TB_login="login.php";
$TB_quit="login.php?action=quit";

$TB[table]="{$TB_pre}members";
$TB[uid]="uid";
$TB[username]="username";
$TB[password]="password";

@include(PHP168_PATH."$TB_path/data/bbscache/config.php");


/**
*取得用户数据
**/
function PassportUserdb(){
	global $db,$timestamp,$webdb,$onlineip,$TB,$pre,$db_ifsafecv;
	list($lfjuid,$lfjpwd,$safecv)=explode("\t",StrCode(GetCookie('winduser'),'DECODE'));
	if( !$lfjuid || !$lfjpwd )
	{
		return '';
	}
	if($db_ifsafecv)
	{
		$SQL=",M.safecv";
	}
	$detail=$db->get_one("SELECT D.*,M.$TB[username] AS username,M.$TB[password] AS password$SQL FROM $TB[table] M LEFT JOIN {$pre}memberdata D ON M.uid=D.uid WHERE M.$TB[uid]='$lfjuid' ");
	if( PwdCode($detail[password])!=$lfjpwd || ( $db_ifsafecv && $safecv!=$detail['safecv'] ) ){
		//setcookie('passport','',0,'/');
		return '';
	}
	if($detail&&!$detail[uid]){
		Add_memberdata($detail[username]);
	}
	return $detail;
}

function GetCookie($Var){
    return $_COOKIE[CookiePre().'_'.$Var];
}
function CookiePre(){
	return substr(md5(isset($GLOBALS['db_ifsafecv'])?$GLOBALS['db_sitehash']:$GLOBALS['db_hash']),0,5);
}

function PwdCode($pwd){
	return md5($_SERVER["HTTP_USER_AGENT"].$pwd.$GLOBALS['db_hash']);
}

function StrCode($string,$action='ENCODE'){
	$key	= substr(md5($_SERVER["HTTP_USER_AGENT"].$GLOBALS['db_hash']),8,18);
	$string	= $action == 'ENCODE' ? $string : base64_decode($string);
	$len	= strlen($key);
	$code	= '';
	for($i=0; $i<strlen($string); $i++){
		$k		= $i % $len;
		$code  .= $string[$i] ^ $key[$k];
	}
	$code = $action == 'DECODE' ? $code : base64_encode($code);
	return $code;
}

function Add_memberdata($username,$jump=1){
	global $db,$TB,$pre,$timestamp,$onlineip,$TB_path,$WEBURL,$webdb;
	$rs=$db->get_one("SELECT *,$TB[uid] AS uid FROM $TB[table] WHERE $TB[username]='$username'");
	if( $rs[groupid]==7 || !$rs[yz] ){
		global $action;
		if($action!='quit'){
			echo "<META HTTP-EQUIV=REFRESH CONTENT='1;URL=$webdb[www_url]/do/login.php?action=quit'>";
			die("未验证会员,不能激活帐号");
		}
		return ;
	}
	$rs[uid]=$rs[uid];
	$rs[groupid]=8;
	$rs[groups]='';
	$rs[yz]=($webdb[BbsUserAutoPass]!=='0')?1:0;
	$rs[newpm]=0;
	$rs[medals]='';
	$rs[money]='';
	$rs[totalspace]='';
	$rs[usespace]='';
	$rs[lastvist]=$timestamp;
	$rs[lastip]=$rs[regip]=$onlineip;
	$rs[regdate]=$rs[regdate];
	$rs[icon]='';
	$rs[sex]=$rs[gender];
	$rs[bday]=$rs[bday];
	$rs[email]=$rs[email];
	$rs[introduce]=addslashes($rs[introduce]);
	$rs[icon]=ereg("^http",$rs[icon])?$rs[icon]:($rs[icon]?"$TB_path/$rs[icon]":'');
	$db->query("INSERT INTO `{$pre}memberdata` ( `uid` ,`username` , `question` , `groupid` , `groups` , `yz` , `newpm` , `medals` , `money` , `totalspace` , `usespace` , `lastvist` , `lastip` , `regdate` , `regip` , `sex` , `bday` , `icon` , `introduce` , `oicq` , `msn` , `homepage` , `email` , `address` , `postalcode` , `mobphone` , `telephone` , `idcard` , `truename` )
	VALUES (
	'$rs[uid]','$username','$rs[question]','$rs[groupid]','$rs[groups]','$rs[yz]','$rs[newpm]','$rs[medals]','$rs[money]','$rs[totalspace]','$rs[usespace]','$rs[lastvist]','$rs[lastip]','$rs[regdate]','$rs[regip]','$rs[sex]','$rs[bday]','$rs[icon]','$rs[introduce]','$rs[oicq]','$rs[msn]','$rs[homepage]','$rs[email]','$rs[address]','$rs[postalcode]','$rs[mobphone]','$rs[telephone]','$rs[idcard]','$rs[truename]')");
	if($jump){
		echo "帐号激活成功<META HTTP-EQUIV=REFRESH CONTENT='0;URL=$WEBURL'>";
		exit;
	}
		
}