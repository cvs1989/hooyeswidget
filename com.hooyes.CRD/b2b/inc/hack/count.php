<?php
if(!$fid)
{
	echo "alert('FID不存在!!')";exit;
}

if($fromurl&&$webdb[ForbidCountDomain]){
	$detail=explode("\r\n",$webdb[ForbidCountDomain]);
	foreach( $detail AS $key=>$value){
		if(!$value){
			continue;
		}
		if(strstr($fromurl,$value)){
			$fromurl='';
		}
	}
}
if($fromurl&&strstr($fromurl,$webdb[www_url])){
	$fromurl='';
}
$stat_client=get_cookie("stat_client");
$detail=explode("\t",$stat_client);
$SID=$detail[0];
$time_day=date("j",$timestamp);
$time_year=date("Y",$timestamp);
$time_month=date("n",$timestamp);
$time_week=date("W",$timestamp);
$time_hour=date("G",$timestamp);
$fromurl_md5=md5("$onlineip$fromurl");

/**
*不同一天或更换IP或不同来源地址时,需要重新统计用户在线详情
**/

if($detail[1]!=$time_day||$detail[2]!=$fromurl_md5){
	$hits=1;
	$windows_type=osinfo();
	$ie_type=browseinfo();

	if($lfjid){
		$db->query("DELETE FROM `{$pre}count_user` WHERE `username`='$lfjid'");
	}elseif($SID){
		$db->query("DELETE FROM `{$pre}count_user` WHERE `id`='$SID'");
	}else{
		$db->query("DELETE FROM `{$pre}count_user` WHERE `ip`='$onlineip'");
	}
	
	$db->query("INSERT INTO `{$pre}count_user` (`fid`, `time_day`, `username`, `lasttime`, `ip`, `ip_address`, `fromurl`, `weburl`, `windows_type`, `ie_type`, `windows_lang`, `screen_size`, `hits`) VALUES ('$fid','$time_day','$lfjid','$timestamp','$onlineip','$ip_address','$fromurl','$nowurl','$windows_type','$ie_type','$windows_lang','$screen_size','$hits')");
	$SID=$db->insert_id();
	
	set_cookie("stat_client","$SID\t$time_day\t$fromurl_md5",3600*24);

	$webdb[MaxOnlineUser] || $webdb[MaxOnlineUser]=1000;
	$rs=$db->get_one("SELECT COUNT(id) AS NUM FROM `{$pre}count_user`");
	if($rs[NUM]>$webdb[MaxOnlineUser]){
		$num=$rs[NUM]-$webdb[MaxOnlineUser];
		$db->query("DELETE FROM `{$pre}count_user` ORDER BY id ASC LIMIT $num");
	}
}
else
{
	$db->query("UPDATE `{$pre}count_user` SET `username`='$lfjid',`lasturl`='$nowurl',`lasttime`='$timestamp',hits=hits+1 WHERE id='$SID'");
}


/**
*统计当天的UV与IP数,IP比较准确,UV的话,如果用户清空COOKIE的话,就会统计失败
**/
$SQL='';
if(get_cookie("stat_client_uv")!=$time_day)
{
	set_cookie("stat_client_uv","$time_day",3600*24);
	$SQL=",uv=uv+1";
	$rs=$db->get_one("SELECT COUNT(id) AS NUM FROM `{$pre}count_user` WHERE time_day='$time_day' AND ip='$onlineip'");
	if(!$rs||$rs[NUM]==1){
		$SQL .=",ip=ip+1";
	}
}

include(PHP168_PATH."cache/hack/count.php");

if($Day!=$time_day)
{
	$pv=$uv=$ip=1;
	$windows_type=$ie_type=$windows_lang=$screen_size=$from_domain='';
	$db->query("INSERT INTO `{$pre}count_stat` (`fid`, `year`, `month`, `week`, `day`, `hour`, `pv`, `uv`, `ip`, `windows_type`, `ie_type`, `windows_lang`, `screen_size`, `from_domain`) VALUES ('$fid','$time_year','$time_month','$time_week','$time_day','','$pv','$uv','$ip','$windows_type','$ie_type','$windows_lang','$screen_size','$from_domain')");
	$Id=$db->insert_id();
	write_file(PHP168_PATH."cache/hack/count.php","<?php\r\n\$Day='$time_day';\r\n\$Id='$Id';");
	if($webdb[KeepTodayCount])
	{
		$db->query("DELETE FROM `{$pre}count_user` WHERE time_day!='$time_day' ");
	}
	if(!is_writable(PHP168_PATH."cache/hack/count.php"))
	{
		echo "alert('cache/hack/count.php文件不可写');";
		exit;
	}
}
else
{
	$db->query("UPDATE `{$pre}count_stat` SET `pv`=`pv`+1$SQL WHERE id='$Id'");
}

?>