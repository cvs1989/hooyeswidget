<?php
$TB_pre=$webdb[passport_pre]?$webdb[passport_pre]:"cdb_";
$TB_path=$webdb[passport_path];
$TB_url=$webdb[passport_url];
$TB_register="register.php";
$TB_login="logging.php?action=login";
$TB_quit="logging.php?action=logout&formhash=";


$TB[table]="{$TB_pre}members";
$TB[uid]="uid";
$TB[username]="username";
$TB[password]="password";

@include(PHP168_PATH."$TB_path/forumdata/cache/cache_settings.php");
@include(PHP168_PATH."$TB_path/config.inc.php");
include( PHP168_PATH."php168/mysql_config.php");

if(defined("UC_CONNECT")){
	if(UC_DBHOST!=$dbhost||UC_DBUSER!=$dbuser||UC_DBPW!=$dbpw){
		$db_uc=new MYSQL_DB;
		$db_uc->connect(UC_DBHOST, UC_DBUSER, UC_DBPW, UC_DBNAME, $pconnect = '');
	}else{
		$db_uc=$db;
	}
}

/**
*取得用户数据
**/
function PassportUserdb(){
	global $db,$timestamp,$webdb,$onlineip,$TB,$pre,$_DCACHE,$tablepre,$cookiepre;
	//5.0使用$tablepre,5.5使用$cookiepre
	$discuz_auth_key = md5($_DCACHE['settings']['authkey'].$_SERVER['HTTP_USER_AGENT']);
	list($lfjpwd, $discuz_secques, $lfjuid)=explode("\t", authcode($_COOKIE["{$cookiepre}auth"]?$_COOKIE["{$cookiepre}auth"]:$_COOKIE["{$tablepre}auth"], 'DECODE',$discuz_auth_key));
	if(!$lfjpwd||!$lfjuid)
	{
		$sid=$_COOKIE["{$cookiepre}sid"]?$_COOKIE["{$cookiepre}sid"]:$_COOKIE["{$tablepre}sid"];
		if($sid&&$rs=$db->get_one("SELECT * FROM {$webdb[passport_pre]}sessions WHERE sid='$sid' AND CONCAT_WS('.',ip1,ip2,ip3,ip4)='$onlineip'")){
			$lfjuid=$rs[uid];
			$detail=$db->get_one("SELECT D.*,M.$TB[username] AS username,M.$TB[password] AS password FROM $TB[table] M LEFT JOIN {$pre}memberdata D ON M.uid=D.uid WHERE M.$TB[uid]='$lfjuid' ");
			if($detail&&!$detail[uid]){
				Add_memberdata($rs[username]);
			}
			return $detail;
		}else{
			return '';
		}
	}
	$detail=$db->get_one("SELECT M.$TB[username] AS username,M.$TB[password] AS password,D.* FROM $TB[table] M LEFT JOIN {$pre}memberdata D ON M.uid=D.uid WHERE M.$TB[uid]='$lfjuid' ");
	if( $detail[password]!=$lfjpwd ){
		//setcookie("{$tablepre}auth",'',0,'/');
		return '';
	}
	if($detail&&!$detail[uid]){
		Add_memberdata($detail[username]);
	}
	return $detail;
}

function authcode ($string, $operation, $key = '') {
	if(defined("UC_CONNECT")){
		$ckey_length = 4;
		$key = md5($key ? $key : $GLOBALS['discuz_auth_key']);
		$keya = md5(substr($key, 0, 16));
		$keyb = md5(substr($key, 16, 16));
		$keyc = $ckey_length ? ($operation == 'DECODE' ? substr($string, 0, $ckey_length): substr(md5(microtime()), -$ckey_length)) : '';

		$cryptkey = $keya.md5($keya.$keyc);
		$key_length = strlen($cryptkey);

		$string = $operation == 'DECODE' ? base64_decode(substr($string, $ckey_length)) : sprintf('%010d', $expiry ? $expiry + time() : 0).substr(md5($string.$keyb), 0, 16).$string;
		$string_length = strlen($string);

		$result = '';
		$box = range(0, 255);

		$rndkey = array();
		for($i = 0; $i <= 255; $i++) {
			$rndkey[$i] = ord($cryptkey[$i % $key_length]);
		}

		for($j = $i = 0; $i < 256; $i++) {
			$j = ($j + $box[$i] + $rndkey[$i]) % 256;
			$tmp = $box[$i];
			$box[$i] = $box[$j];
			$box[$j] = $tmp;
		}

		for($a = $j = $i = 0; $i < $string_length; $i++) {
			$a = ($a + 1) % 256;
			$j = ($j + $box[$a]) % 256;
			$tmp = $box[$a];
			$box[$a] = $box[$j];
			$box[$j] = $tmp;
			$result .= chr(ord($string[$i]) ^ ($box[($box[$a] + $box[$j]) % 256]));
		}

		if($operation == 'DECODE') {
			if((substr($result, 0, 10) == 0 || substr($result, 0, 10) - time() > 0) && substr($result, 10, 16) == substr(md5(substr($result, 26).$keyb), 0, 16)) {
				return substr($result, 26);
			} else {
				return '';
			}
		} else {
			return $keyc.str_replace('=', '', base64_encode($result));
		}
	}else{
		$key = md5($key ? $key : $GLOBALS['discuz_auth_key']);
		$key_length = strlen($key);

		$string = $operation == 'DECODE' ? base64_decode($string) : substr(md5($string.$key), 0, 8).$string;
		$string_length = strlen($string);

		$rndkey = $box = array();
		$result = '';

		for($i = 0; $i <= 255; $i++) {
			$rndkey[$i] = ord($key[$i % $key_length]);
			$box[$i] = $i;
		}

		for($j = $i = 0; $i < 256; $i++) {
			$j = ($j + $box[$i] + $rndkey[$i]) % 256;
			$tmp = $box[$i];
			$box[$i] = $box[$j];
			$box[$j] = $tmp;
		}

		for($a = $j = $i = 0; $i < $string_length; $i++) {
			$a = ($a + 1) % 256;
			$j = ($j + $box[$a]) % 256;
			$tmp = $box[$a];
			$box[$a] = $box[$j];
			$box[$j] = $tmp;
			$result .= chr(ord($string[$i]) ^ ($box[($box[$a] + $box[$j]) % 256]));
		}

		if($operation == 'DECODE') {
			if(substr($result, 0, 8) == substr(md5(substr($result, 8).$key), 0, 8)) {
				return substr($result, 8);
			} else {
				return '';
			}
		} else {
			return str_replace('=', '', base64_encode($result));
		}
	}
}

function Add_memberdata($username,$jump=1){
	global $db,$TB,$pre,$timestamp,$onlineip,$TB_path,$WEBURL,$webdb;
	$rs=$db->get_one("SELECT *,$TB[uid] AS uid FROM $TB[table] WHERE $TB[username]='$username'");
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
	$rs[icon]=ereg("^http",$rs[icon])?$rs[icon]:($rs[icon]?"$TB_path/$rs[icon]":'');
	$db->query("INSERT INTO `{$pre}memberdata` ( `uid` , `username` , `question` , `groupid` , `groups` , `yz` , `newpm` , `medals` , `money` , `totalspace` , `usespace` , `lastvist` , `lastip` , `regdate` , `regip` , `sex` , `bday` , `icon` , `introduce` , `oicq` , `msn` , `homepage` , `email` , `address` , `postalcode` , `mobphone` , `telephone` , `idcard` , `truename` )
	VALUES (
	'$rs[uid]','$username','$rs[question]','$rs[groupid]','$rs[groups]','$rs[yz]','$rs[newpm]','$rs[medals]','$rs[money]','$rs[totalspace]','$rs[usespace]','$rs[lastvist]','$rs[lastip]','$rs[regdate]','$rs[regip]','$rs[sex]','$rs[bday]','$rs[icon]','$rs[introduce]','$rs[oicq]','$rs[msn]','$rs[homepage]','$rs[email]','$rs[address]','$rs[postalcode]','$rs[mobphone]','$rs[telephone]','$rs[idcard]','$rs[truename]')");
	if($jump){
		echo "帐号激活成功<META HTTP-EQUIV=REFRESH CONTENT='0;URL=$WEBURL'>";
		exit;
	}
}