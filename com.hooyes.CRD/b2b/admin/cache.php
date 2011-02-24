<?php
!function_exists('html') && exit('ERR');

require_once(PHP168_PATH."inc/function.ad.php");

if($job=="cache"&&$Apower[cache_cache])
{
	require(dirname(__FILE__)."/"."head.php");
	require(dirname(__FILE__)."/"."template/cache/list.htm");
	require(dirname(__FILE__)."/"."foot.php");
}
elseif($action=="cache"&&$Apower[cache_cache])
{
	set_time_limit(0);

	$Guidedb->FidSonCache("{$pre}sort");
	$Guidedb->GuideFidCache("{$pre}sort");
	

	write_config_cache();

	write_friendlink();

	write_hackmenu_cache();

	write_group_cache();

	make_module_cache();

	get_htmltype();

	write_limitword_cache();

	write_keyword_cache();

	article_module_cache();
	
	//���¹��
	make_ad_cache();
	
	//�Զ�������ҳ�ļ���
	get_showhtmltype();

	del_file(PHP168_PATH."cache/jsarticle_cache");
	del_file(PHP168_PATH."cache/label_cache");
	del_file(PHP168_PATH."cache/list_cache");
	del_file(PHP168_PATH."cache/bencandy_cache");
	del_file(PHP168_PATH."cache/showsp_cache");

	jump("�������","$FROMURL");
}
elseif($action=="repair")
{
	set_time_limit(0);
	if(!$page){
		$page=1;
		$query = $db->query("SELECT * FROM {$pre}sort");
		while($rs = $db->fetch_array($query)){
			$erp=$Fid_db[iftable][$rs[fid]];
			$db->query("UPDATE {$pre}article$erp SET fname='$rs[name]' WHERE fid='$rs[fid]' ");
		}
		$db->query("UPDATE {$pre}article SET fname='' WHERE fid='0' ");
		$db->query("UPDATE {$pre}article SET ispic=1 WHERE picurl!='' ");
		$db->query("UPDATE {$pre}article SET ispic=0 WHERE picurl='' ");
		foreach($Fid_db[iftable] AS $key=>$erp){
			$db->query("UPDATE {$pre}article$erp SET fname='' WHERE fid='0' ");
			$db->query("UPDATE {$pre}article$erp SET ispic=1 WHERE picurl!='' ");
			$db->query("UPDATE {$pre}article$erp SET ispic=0 WHERE picurl='' ");
		}
	}
	$rows=100;
	$min=($page-1)*$rows;
	$query = $db->query("SELECT * FROM {$pre}article_db LIMIT $min,$rows");
	while($rs = $db->fetch_array($query)){
		$erp=get_id_table($rs[aid]);
		$s=$db->get_one("SELECT COUNT(*) AS NUM FROM {$pre}reply$erp WHERE aid='$rs[aid]' ");
		$db->query("UPDATE {$pre}article$erp SET pages='$s[NUM]' WHERE aid='$rs[aid]' ");

		$s=$db->get_one("SELECT COUNT(*) AS NUM FROM {$pre}comment WHERE aid='$rs[aid]' ");
		$db->query("UPDATE {$pre}article$erp SET comments='$s[NUM]' WHERE aid='$rs[aid]' ");
		$ckk++;
	}
	if($ckk){
		echo '<meta http-equiv="Content-Type" content="text/html; charset=gb2312">';
		$page++;
		echo "���Ժ�$page<META HTTP-EQUIV=REFRESH CONTENT='0;URL=?action=$action&lfj=$lfj&page=$page'>";
		exit;
	}

	jump("�޸����","index.php?lfj=cache&job=cache");
}
elseif($action=="repair_table")
{
	set_time_limit(0);
	if(!$page){
		$page=1;
		$i=0;
	}
	$query=$db->query("SHOW TABLE STATUS");
	while( $array=$db->fetch_array($query) ){
		if(!ereg("^($pre)",$array[Name])){
			continue;
		}
		$i++;
		if($i<$page){
			continue;
		}elseif($i>$page){
			break;
		}
		$db->query("REPAIR TABLE `$array[Name]` ");
		$OPTIMIZE && $db->query("OPTIMIZE TABLE `$array[Name]` ");
		$ck++;
	}
	if($ck){
		echo '<meta http-equiv="Content-Type" content="text/html; charset=gb2312">';
		$page++;
		echo "���Ժ�$page,���ڴ������ݱ�-$array[Name]<META HTTP-EQUIV=REFRESH CONTENT='0;URL=?action=$action&lfj=$lfj&page=$page&OPTIMIZE=$OPTIMIZE'>";
		exit;
	}
	jump("�޸����","index.php?lfj=cache&job=cache");
}

function write_hackmenu_cache(){
	global $db,$pre;
	$show="<?php\r\n";
	$query = $db->query("SELECT * FROM {$pre}hack");
	while($rs = $db->fetch_array($query)){
		$show.="\r\n\$menudb['�����������']['$rs[name]']['link']='$rs[adminurl]';";
		$show.="\r\n\$menudb['�����������']['$rs[name]']['power']='hack_$rs[keywords]';\r\n";
	}
	//write_file(PHP168_PATH."php168/hack.php",$show);
}
?>