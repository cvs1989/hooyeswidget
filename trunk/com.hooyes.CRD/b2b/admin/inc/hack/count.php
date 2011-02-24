<?php
!function_exists('html') && exit('ERR');
if($job=='list'&&$Apower[hack_list])
{
	$time_day=date("j",$timestamp);
	$time_year=date("Y",$timestamp);
	$time_month=date("n",$timestamp);
	$time_week=date("W",$timestamp);
	$time_hour=date("G",$timestamp);
	$todydb=$db->get_one("SELECT * FROM {$pre}count_stat WHERE year='$time_year' AND month='$time_month' AND week='$time_week' AND day='$time_day'");
	
	$timestamp2=$timestamp-3600*24;
	$time_day=date("j",$timestamp2);
	$time_year=date("Y",$timestamp2);
	$time_month=date("n",$timestamp2);
	$time_week=date("W",$timestamp2);
	$time_hour=date("G",$timestamp2);
	$yesterdaydb=$db->get_one("SELECT * FROM {$pre}count_stat WHERE year='$time_year' AND month='$time_month' AND week='$time_week' AND day='$time_day'");
	$yesterdaydb[pv]=intval($yesterdaydb[pv]);
	$yesterdaydb[uv]=intval($yesterdaydb[uv]);
	$yesterdaydb[ip]=intval($yesterdaydb[ip]);

	$page>1 || $page=1;
	$rows=50;
	$min=($page-1)*$rows;
	$i=-1;
	$query = $db->query("SELECT * FROM {$pre}count_user ORDER BY id DESC LIMIT $min,$rows ");
	while($rs = $db->fetch_array($query)){
		if($rs[fromurl]&&strstr($rs[fromurl],'baidu.com')){
			preg_match("/wd=([^&]+)/is",$rs[fromurl],$array);
			$rs[keyword]=$array[1];
		}elseif($rs[fromurl]&&(strstr($rs[fromurl],'.google.')||strstr($rs[fromurl],'so.163.com'))){
			preg_match("/q=([^&]+)/is",$rs[fromurl],$array);
			$rs[keyword]=$array[1];
		}elseif($rs[fromurl]&&strstr($rs[fromurl],'yahoo.com')){
			preg_match("/p=([^&]+)/is",$rs[fromurl],$array);
			$rs[keyword]=$array[1];
		}elseif($rs[fromurl]&&strstr($rs[fromurl],'soso.com')){
			preg_match("/w=([^&]+)/is",$rs[fromurl],$array);
			$rs[keyword]=$array[1];
		}elseif($rs[fromurl]&&strstr($rs[fromurl],'sogou.com')){
			preg_match("/query=([^&]+)/is",$rs[fromurl],$array);
			$rs[keyword]=$array[1];
		}else{
			$rs[keyword]='';
		}
		if(!$rs[fromurl]){
			$rs[fromurl]='浏览器直接输入';
		}
		$rs[ip_address]=ipfrom($rs[ip]);
		$rs[keyword]=urldecode($rs[keyword]);
		if(strstr($rs[fromurl],'.google.')||strstr($rs[fromurl],'yahoo.com')){
			require_once(PHP168_PATH."inc/class.chinese.php");
			$cnvert = new Chinese("UTF8","GB2312",$rs[keyword],PHP168_PATH."./inc/gbkcode/");
			$rs[keyword] = $cnvert->ConvertIT();
		}
		$rs[lasttime]=date("Y-m-d H:i:s",$rs[lasttime]);
		$listdb[]=$rs;
	}

	$showpage=getpage("{$pre}count_user","","index.php?lfj=hack&hack=count&job=list","$rows",$NUM);
	arsort($hitsDB);
	arsort($screen_sizeDB);
	arsort($ie_typeDB);
	arsort($windows_typeDB);
	arsort($_furlDB);
	//echo count($hitsDB);exit;

	require("head.php");
	require("template/hack/count/list.htm");
	require("foot.php");
}
elseif($job=='listmore'&&$Apower[hack_list])
{
	require_once(PHP168_PATH."inc/class.chinese.php");
	$query = $db->query("SELECT * FROM {$pre}count_user ORDER BY id DESC ");
	while($rs = $db->fetch_array($query)){
		$hitsDB["$rs[ip]"]=$rs[hits];
		$screen_sizeDB["$rs[screen_size]"]++;
		$ie_typeDB["$rs[ie_type]"]++;
		$windows_typeDB["$rs[windows_type]"]++;
		if($rs[fromurl]){
			$_furl=preg_replace("/http:\/\/([^\/]+)\/(.*)/is","\\1",$rs[fromurl]);
			$_furlDB["$_furl"]++;
		}
 
		if($rs[fromurl]&&strstr($rs[fromurl],'baidu.com')){
			preg_match("/wd=([^&]+)/is",$rs[fromurl],$array);
			$rs[keyword]=$array[1];
		}elseif($rs[fromurl]&&(strstr($rs[fromurl],'.google.')||strstr($rs[fromurl],'so.163.com'))){
			preg_match("/q=([^&]+)/is",$rs[fromurl],$array);
			$rs[keyword]=$array[1];
		}elseif($rs[fromurl]&&strstr($rs[fromurl],'yahoo.com')){
			preg_match("/p=([^&]+)/is",$rs[fromurl],$array);
			$rs[keyword]=$array[1];
		}elseif($rs[fromurl]&&strstr($rs[fromurl],'soso.com')){
			preg_match("/w=([^&]+)/is",$rs[fromurl],$array);
			$rs[keyword]=$array[1];
		}elseif($rs[fromurl]&&strstr($rs[fromurl],'sogou.com')){
			preg_match("/query=([^&]+)/is",$rs[fromurl],$array);
			$rs[keyword]=$array[1];
		}else{
			$rs[keyword]='';
		}
		$rs[keyword]=urldecode($rs[keyword]);
		if(strstr($rs[fromurl],'.google.')||strstr($rs[fromurl],'yahoo.com')){
			$cnvert = new Chinese("UTF8","GB2312",$rs[keyword],PHP168_PATH."./inc/gbkcode/");
			$rs[keyword] = $cnvert->ConvertIT();
		}

		$rs[keyword] && $listdb[]=$rs[keyword];
	}
	$keyword=implode("\r\n",$listdb);
	arsort($hitsDB);
	arsort($screen_sizeDB);
	arsort($ie_typeDB);
	arsort($windows_typeDB);
	arsort($_furlDB);

	require("head.php");
	require("template/hack/count/listmore.htm");
	require("foot.php");
}
elseif($action=='list'&&$Apower[hack_list]){
	if(!$iddb){
		showmsg("请选择一个");
	}
	$s=implode(",",$iddb);
	$db->query("DELETE FROM {$pre}count_user WHERE id IN ($s)");
	jump("删除成功",$FROMURL);
}
elseif($job=='listall'&&$Apower[hack_list])
{
	$query = $db->query("SELECT * FROM {$pre}count_stat ORDER BY id");
	while($rs = $db->fetch_array($query)){
		$listdb[]=$rs;
	}
	require("head.php");
	require("template/hack/count/listall.htm");
	require("foot.php");
}
elseif($job=='set'&&$Apower[hack_list])
{
	$close_count[intval($webdb[close_count])]=' checked ';
	$KeepTodayCount[intval($webdb[KeepTodayCount])]=' checked ';
	require("head.php");
	require("template/hack/count/set.htm");
	require("foot.php");
}
elseif($action=='set'&&$Apower[hack_list])
{
	if($webdbs[KeepTodayCount])
	{
		$time_day=date("j",$timestamp);
		$db->query("DELETE FROM `{$pre}count_user` WHERE time_day!='$time_day' ");
	}
	write_config_cache($webdbs);
	jump("修改成功",$FROMURL);
}


function s_ip($db,$s,$ip,$l_d=''){
	global $ipa0;
	if(!$l_d){
		fseek($db,$s+1,SEEK_SET);
		$l_d=fgets($db,100);
	}
	$ip_a=explode("\t",$l_d);
	$ip_a[0]=implode('.',d_ip(explode('.',$ip_a[0])));
	$ip_a[1]=implode('.',d_ip(explode('.',$ip_a[1])));
	if($ip<$ip_a[0]) $ipa0=1;
	if ($ip>=$ip_a[0] && $ip<=$ip_a[1]) return $ip_a[2].$ip_a[3];
}
function nset($db){
	$l_d=fgets($db,100);
	$ip_a=explode("\t",$l_d);
	return array($l_d,$ip_a[2].$ip_a[3]);
}
function d_ip($d_ip){
	for($i=0; $i<=3; $i++){
		$d_ip[$i]     = sprintf("%03d", $d_ip[$i]);
	}
	return $d_ip;
}

?>