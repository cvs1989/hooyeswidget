<?php
require_once(dirname(__FILE__)."/"."global.php");

$ifuseMenu=1;	//不想使用后台自定义菜单的话.把1换成0

if(!$lfjid){
	showerr("你还没登录");
}

if($web_admin){
	$power=3;
}elseif( $db->get_one("SELECT fid FROM {$pre}sort WHERE BINARY admin LIKE '%$lfjid%' LIMIT 1") ){
	$power=2;
}else{
	$power=1;
}
unset($menudb,$menu_GpartDB);
$SystemId=intval($SystemId);

if($SystemId){
	require_once(dirname(__FILE__)."/"."menu.php");
	unset($menudb);
	$rs = $db->get_one("SELECT * FROM {$pre}module WHERE id='$SystemId'");
	$rs['dirname'] && @include(PHP168_PATH.$rs['dirname']."/member/menu.php");

	preg_match("/(.*)\/(index\.php|)\?SystemId=([\d]+)&main=([^\/]+)/is",$WEBURL,$UrlArray);
	if($UrlArray[4]){
		$MainUrl="$webdb[www_url]/$rs[dirname]/member/$UrlArray[4]";
	}elseif(is_file(PHP168_PATH.$rs['dirname']."/member/map.php")){
		$MainUrl="$webdb[www_url]/$rs[dirname]/member/map.php";
	}else{
		$MainUrl="map.php?uid=$lfjuid";
	}

	foreach( $menudb AS $key=>$array){
		foreach( $array AS $key2=>$array2){
			!eregi("^http:",$array2['link'])&&$menudb[$key][$key2]['link']="$webdb[www_url]/$rs[dirname]/member/".$array2['link'];
		}
	}
}elseif($ifuseMenu){
	preg_match("/(.*)\/(index\.php|)\?main=([^\/]+)/is",$WEBURL,$UrlArray);
	$MainUrl=$UrlArray[3]?$UrlArray[3]:"map.php?uid=$lfjuid";
	require_once(dirname(__FILE__)."/"."menu.php");
	unset($menudb);
	$query = $db->query("SELECT * FROM {$pre}admin_menu WHERE groupid='-8' AND fid=0 ORDER BY list DESC");
	while($rs = $db->fetch_array($query)){
		$menu_GpartDB[$rs[id]][name]=$rs[name];
		$query2 = $db->query("SELECT * FROM {$pre}admin_menu WHERE fid='$rs[id]' ORDER BY list DESC");
		while($rs2 = $db->fetch_array($query2)){
			$menu_GpartDB[$rs[id]][son][]=$rs2;
		}
	}
}else{
	preg_match("/(.*)\/(index\.php|)\?main=([^\/]+)/is",$WEBURL,$UrlArray);
	$MainUrl=$UrlArray[3]?$UrlArray[3]:"map.php?uid=$lfjuid";

	require_once(dirname(__FILE__)."/"."menu.php");
	$menudb1=$menudb;
	$query = $db->query("SELECT * FROM {$pre}module WHERE pre='blog' ORDER BY list DESC LIMIT 5");
	while($rs = $db->fetch_array($query)){
		unset($menudb);
		$rs['dirname'] && @include(PHP168_PATH.$rs['dirname']."/member/menu.php");
		$menudb2=$menudb;
		foreach( $menudb2 AS $key=>$array){
			foreach( $array AS $key2=>$array2){
				!eregi("^http:",$array2['link'])&&$menudb2[$key][$key2]['link']="$webdb[www_url]/$rs[dirname]/member/".$array2['link'];
			}
		}
		$menudb1=$menudb1+$menudb2;
	}
	$menudb=$menudb1;
}



$topmenu='';
$select_id=0;
$i=0;
$query = $db->query("SELECT * FROM {$pre}module WHERE unite_member=1 ORDER BY list DESC");
while($rs = $db->fetch_array($query))
{
	$i++;
	if($rs[id]==$SystemId){
		$select_id=$i;
	}
	$topmenu.=",'{$rs[name]}|index.php?SystemId=$rs[id]|left.php?Smenu=$rs[pre]'";
}

$start_id=($select_id-4>0)?($select_id-4):0;

if( ereg("^pwbbs",$webdb[passport_type]) ){
	@extract($db->get_one("SELECT COUNT(*) AS pmNUM FROM {$TB_pre}msg WHERE `touid`='$lfjuid' AND type='rebox' AND ifnew=1"));
}elseif( ereg("^dzbbs",$webdb[passport_type]) ){
	if($webdb[passport_type]=='dzbbs7'){
		$pmNUM=uc_pm_checknew($lfjuid);
	}else{
		@extract($db->get_one("SELECT COUNT(*) AS pmNUM FROM {$TB_pre}pms WHERE `msgtoid`='$lfjuid' AND folder='inbox' AND new=1"));
	}			
}else{
	@extract($db->get_one("SELECT COUNT(*) AS pmNUM FROM `{$pre}pm` WHERE `touid`='$lfjuid' AND type='rebox' AND ifnew='1'"));
}



require_once(dirname(__FILE__)."/"."template/index.htm");
?>