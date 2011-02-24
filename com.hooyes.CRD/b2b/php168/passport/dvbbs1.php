<?php
$TB_pre=$webdb[passport_pre]?$webdb[passport_pre]:"dv_";
$TB_path=$webdb[passport_path];
$TB_url=$webdb[passport_url];
$TB_register="reg.php";
$TB_login="login.php";
$TB_quit="login.php";

$TB[table]="{$TB_pre}user";
$TB[uid]="userid";
$TB[username]="username";
$TB[password]="userpassword";

$magic_quotes_gpc = get_magic_quotes_gpc();
define('ISDVBBS', true);
@include(PHP168_PATH."$TB_path/inc/config.php");


/**
*取得用户数据
**/
function PassportUserdb(){
	global $db,$timestamp,$webdb,$onlineip,$TB,$pre;

	$membername = strip_tags(trim(DVbbs_get_cookie("username")));
	$memberword = trim(DVbbs_get_cookie("password"));

	if( !$membername || !$memberword )
	{
		return '';
	}
	$detail=$db->get_one("SELECT D.*,M.$TB[username] AS username,M.truepassword AS password FROM $TB[table] M LEFT JOIN {$pre}memberdata D ON M.$TB[uid]=D.uid WHERE M.$TB[username]='$membername' ");
	if( $detail[password]!=$memberword ){
		//setcookie('passport','',0,'/');
		return '';
	}
	if($detail&&!$detail[uid]){
		Add_memberdata($detail[username]);
	}
	return $detail;
}

function DVbbs_get_cookie($name){
	global $_COOKIE,$cookieprename,$magic_quotes_gpc;
	if (isset($_COOKIE[$cookieprename.$name])) {
		$value = urldecode($_COOKIE[$cookieprename.$name]);
		if ($magic_quotes_gpc) {
			$value = addslashes(stripslashes($value));
		} else {
			$value = addslashes($value);
		}
		return $value;
	}
	return FALSE;
}

function Add_memberdata($username){
	global $db,$TB,$pre,$timestamp,$onlineip,$TB_path,$WEBURL;
	$rs=$db->get_one("SELECT *,$TB[uid] AS uid FROM $TB[table] WHERE $TB[username]='$username'");
	$rs[uid]=$rs[uid];
	$rs[groupid]=8;
	$rs[groups]='';
	$rs[yz]=1;
	$rs[newpm]=0;
	$rs[medals]='';
	$rs[money]='';
	$rs[totalspace]='';
	$rs[usespace]='';
	$rs[lastvist]=$timestamp;
	$rs[lastip]=$rs[regip]=$rs[userlastip];
	$rs[regdate]=$rs[joindate];
	$rs[icon]='';
	$rs[sex]=$rs[usersex];
	$rs[bday]=$rs[bday];
	$rs[email]=$rs[useremail];
	$rs[icon]=ereg("^http",$rs[userface])?$rs[userface]:($rs[userface]?"$TB_path/$rs[userface]":'');
	$db->query("INSERT INTO `{$pre}memberdata` ( `uid` ,`username` , `question` , `groupid` , `groups` , `yz` , `newpm` , `medals` , `money` , `totalspace` , `usespace` , `lastvist` , `lastip` , `regdate` , `regip` , `sex` , `bday` , `icon` , `introduce` , `oicq` , `msn` , `homepage` , `email` , `address` , `postalcode` , `mobphone` , `telephone` , `idcard` , `truename` )
	VALUES (
	'$rs[uid]','$username','$rs[question]','$rs[groupid]','$rs[groups]','$rs[yz]','$rs[newpm]','$rs[medals]','$rs[money]','$rs[totalspace]','$rs[usespace]','$rs[lastvist]','$rs[lastip]','$rs[regdate]','$rs[regip]','$rs[sex]','$rs[bday]','$rs[icon]','$rs[introduce]','$rs[oicq]','$rs[msn]','$rs[homepage]','$rs[email]','$rs[address]','$rs[postalcode]','$rs[mobphone]','$rs[telephone]','$rs[idcard]','$rs[truename]')");
	if($jump){
		echo "帐号激活成功<META HTTP-EQUIV=REFRESH CONTENT='0;URL=$WEBURL'>";
		exit;
	}
}


?>