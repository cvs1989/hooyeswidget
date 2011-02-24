<?php
!function_exists('html') && exit('ERR');
if($job=="list"){
	!$page&&$page=1;
	$rows=20;
	$min=($page-1)*$rows;
	$showpage=getpage("{$pre}download","","index.php?lfj=$lfj&job=$job",$rows);
	$query=$db->query(" SELECT * FROM {$pre}download ORDER BY did DESC LIMIT $min,$rows ");
	while($rs=$db->fetch_array($query)){
		$erp=get_id_table($rs[aid]);
		$rss=$db->get_one(" SELECT title,fname FROM {$pre}article$erp WHERE aid='$rs[aid]' ");
		$rs[title]=$rss[title];
		$rs[fname]=$rss[fname];
		$rs[fileurl]=tempdir($rs[fileurl]);
		$rs['filesize']=number_format($rs['filesize']/1024,3);
		$listdb[]=$rs;
	}
	require(dirname(__FILE__)."/"."head.php");
	require(dirname(__FILE__)."/"."template/download/list.htm");
	require(dirname(__FILE__)."/"."foot.php");
}
elseif($action=="delete")
{
	foreach($diddb AS $key=>$rs){
		$rs=$db->get_one("SELECT * FROM {$pre}download WHERE did='$key'");
		$db->query("DELETE FROM {$pre}download WHERE did='$key'");
		@unlink(PHP168_PATH."/$webdb[updir]/$rs[fileurl]");
	}
	jump("É¾³ý³É¹¦","$FROMURL",1);
}
?>