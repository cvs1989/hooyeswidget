<?php
!function_exists('html') && exit('ERR');
if(!$webdb[propagandize_close]&&$db->get_one("SELECT * FROM {$pre}memberdata WHERE uid='$uid'")){
	$today=date("d",$timestamp);
	if($today<=$webdb[propagandize_LogDay]){
		$SQL=" WHERE day<'367' AND day>'$today' ";
	}else{
		$_d=intval($today-$webdb[propagandize_LogDay]);
		$SQL=" WHERE day<'$_d' ";
	}
	$db->query("DELETE FROM {$pre}propagandize $SQL");
	$ip=sprintf("%u",ip2long($onlineip));
	if(!eregi("^$webdb[www_url]",$FROMURL)&&$uid!=$lfjuid&&!$db->get_one("SELECT * FROM {$pre}propagandize WHERE uid='$uid' AND day='$today' AND ip='$ip'")){
		$fromurl=filtrate($FROMURL);
		$db->query("INSERT INTO `{$pre}propagandize` ( `uid` , `ip` , `day` , `posttime` , `fromurl` ) VALUES ( '$uid', '$ip', '$today', '$timestamp', '$fromurl')");
		add_user($uid,$webdb[propagandize_Money],'宣传推广奖分');
	}
}
header("location:$webdb[propagandize_jumpto]");
?>