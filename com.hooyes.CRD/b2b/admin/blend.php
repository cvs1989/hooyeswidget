<?php
!function_exists('html') && exit('ERR');

if($action=="set"&&$Apower[blend_set])
{
	if($webdbs[passport_type]){
		if(strstr($webdbs[passport_pre],'.')&&eregi("^[0-9]+",$webdbs[passport_pre])){
			$d=explode(".",$webdbs[passport_pre]);
			$webdbs[passport_pre]="`{$d[0]}`.$d[1]";
		}
		if(eregi("^pwbbs",$webdbs[passport_type])||eregi("^dzbbs",$webdbs[passport_type])){
			if(!$db->get_one("SELECT * FROM $webdbs[passport_pre]members LIMIT 1")){
				showmsg("���ݱ�ǰ׺����,����֮");
			}
		}
		if(eregi("^dzbbs",$webdbs[passport_type])){
			if(!is_file(PHP168_PATH."$webdbs[passport_path]/config.inc.php")){
				showmsg("�ⲿϵͳ���������վ����Ŀ¼λ�ò���,����֮");
			}
		}elseif(eregi("^pwbbs",$webdbs[passport_type])){
			if(!is_file(PHP168_PATH."$webdbs[passport_path]/data/bbscache/config.php")){
				showmsg("�ⲿϵͳ���������վ����Ŀ¼λ�ò���,����֮");
			}
		}
	}
	write_config_cache($webdbs);
	jump("�޸ĳɹ�",$FROMURL);
}
elseif($job=="set"&&$Apower[blend_set])
{
	$webdb[passport_type] || $webdb[passport_type]=0;
	$passport_type='';
	$passport_type["$webdb[passport_type]"]=' checked ';
	require(dirname(__FILE__)."/"."head.php");
	require(dirname(__FILE__)."/"."template/blend/menu.htm");
	require(dirname(__FILE__)."/"."template/blend/set.htm");
	require(dirname(__FILE__)."/"."foot.php");
}

elseif($action=="other2this"&&$Apower[blend_set])
{
	$rows=100;
	if(!$page){
		$page=1;
		$db->query("TRUNCATE TABLE `{$pre}members`");
	}
	$min=($page-1)*$rows;
	
	$query = $db->query("SELECT *,$TB[uid] AS uid,$TB[username] AS username,$TB[password] AS password FROM $TB[table] ORDER BY $TB[uid] ASC LIMIT $min,$rows");
	while($rs = $db->fetch_array($query)){
		
		$db->query("INSERT INTO `{$pre}members` ( `uid` , `username` , `password` ) VALUES ('$rs[uid]', '$rs[username]', '$rs[password]')");
		
		$rss=$db->get_one("SELECT * FROM {$pre}memberdata WHERE uid='$rs[uid]'");
		if(!$rss){
			Add_memberdata($rs[username],0);
		}
		$ckk++;
	}
	$page++;
	if($ckk){
		echo "���ڴ�����...<META HTTP-EQUIV=REFRESH CONTENT='0;URL=index.php?lfj=$lfj&action=$action&page=$page'>";
		exit;
	}else{
		jump("�������","index.php?lfj=blend&job=set",50);
	}
}
elseif($job=="other2this"&&$Apower[blend_set])
{
	if(!$webdb[passport_type]){
		showmsg("�㲢û����������ϵͳ,���Բ��ܵ�������");
	}
	require(dirname(__FILE__)."/"."head.php");
	require(dirname(__FILE__)."/"."template/blend/menu.htm");
	require(dirname(__FILE__)."/"."template/blend/other2this.htm");
	require(dirname(__FILE__)."/"."foot.php");
}
?>