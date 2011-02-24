<?php
!function_exists('html') && exit('ERR');

!$job && $job='code';
if($action=="set"&&$Apower[code_code])
{
	write_config_cache($webdbs);
	jump('ÉèÖÃ³É¹¦',"$FROMURL",1);
}
elseif(($job=='code' || $job=='info' || $job=='prompt')&&$Apower[code_code]){
	$ifShowPwAd[intval($webdb[ifShowPwAd])]='checked';
	if(!$_POST['step']){
		require(dirname(__FILE__)."/"."head.php");
		require(dirname(__FILE__)."/"."template/code/code.htm");
		require(dirname(__FILE__)."/"."foot.php");
	}else{
		$lgpwd=md5($lgpwd);
		$verify=md5("actionloginlguser{$lguser}lgpwd{$lgpwd}{$_SERVER[HTTP_USER_AGENT]}");
		jump('',"http://union.phpwind.com/index.php?action=login&lguser=$lguser&lgpwd=$lgpwd&verify=$verify",0);
	}
}elseif($job=='key'&&$Apower[code_code]){
	if(!$webdb[siteid]){
		$rt = $db->get_one("SELECT c_value FROM {$pre}config WHERE c_key='siteid'");
		if(!$rt['c_value']){
			$siteid = generatestr(16);
			$db->update("REPLACE INTO {$pre}config(c_key,c_value) VALUES('siteid','$siteid')");

			$siteownerid = generatestr(18);
			$db->update("REPLACE INTO {$pre}config(c_key,c_value) VALUES('siteownerid','$siteownerid')");

			$sitehash = '11'.SitStrCode(md5($siteid.$siteownerid),md5($siteownerid.$siteid));
			$db->update("REPLACE INTO {$pre}config(c_key,c_value) VALUES('sitehash','$sitehash')");

			$webdbs['siteid']		= $siteid;
			$webdbs['siteownerid']	= $siteownerid;
			$webdbs['sitehash']		= $sitehash;
		}else{
			$webdbs['siteid']		= $rt['c_value'];
			$rt = $db->get_one("SELECT c_value FROM {$pre}config WHERE c_key='siteownerid'");
			$webdbs['siteownerid']	= $rt['c_value'];
			$rt = $db->get_one("SELECT c_value FROM {$pre}config WHERE c_key='sitehash'");
			$webdbs['sitehash']		= $rt['c_value'];
		}
		write_config_cache($webdbs);
	}
	require(dirname(__FILE__)."/"."head.php");
	require(dirname(__FILE__)."/"."template/code/code.htm");
	require(dirname(__FILE__)."/"."foot.php");
}

function generatestr($len) {
	mt_srand((double)microtime() * 1000000);
    $keychars = "abcdefghijklmnopqrstuvwxyz0123456789ABCDEFGHIJKLMNOPQRSTUVWYXZ";
	$maxlen = strlen($keychars)-1;
	$str = '';
	for ($i=0;$i<$len;$i++){
		$str .= $keychars[mt_rand(0,$maxlen)];
	}
	return substr(md5($str.time().$_SERVER["HTTP_USER_AGENT"]),0,$len);
}
function SitStrCode($string,$key,$action='ENCODE'){
	$string	= $action == 'ENCODE' ? $string : base64_decode($string);
	$len	= strlen($key);
	$code	= '';
	for($i=0; $i<strlen($string); $i++){
		$k		= $i % $len;
		$code  .= $string[$i] ^ $key[$k];
	}
	$code = $action == 'DECODE' ? $code : str_replace('=','',base64_encode($code));
	return $code;
}
?>