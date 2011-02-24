<?php
!function_exists('html') && exit('ERR');

$erp=get_id_table($aid);
$rs=$db->get_one("SELECT aid,fid FROM {$pre}article$erp WHERE fid=$fid AND aid>$aid ORDER BY aid ASC LIMIT 1");
if($webdb[NewsMakeHtml]){
	$id=$rs[aid];
	$rs[fid] && $fid=$rs[fid];
	$page=1;
	$dirid=floor($aid/1000);
	
	if($webdb[NewsMakeHtml]==2){
		eval("\$list_filename=\"$webdb[list_filename2]\";");
		eval("\$bencandy_filename=\"$webdb[bencandy_filename2]\";");
		if($rs[aid]){
			header("location:$webdb[www_url]/$bencandy_filename");exit;
		}else{
			header("location:$webdb[www_url]/$list_filename");exit;
		}
	}
	eval("\$list_filename=\"$webdb[list_filename]\";");
	eval("\$bencandy_filename=\"$webdb[bencandy_filename]\";");
	if(file_exists(PHP168_PATH."$bencandy_filename")){
		header("location:$webdb[www_url]/$bencandy_filename");exit;
	}elseif(file_exists(PHP168_PATH."$list_filename")){
		header("location:$webdb[www_url]/$list_filename");exit;
	}
}
if($rs[aid]){
	header("location:$webdb[www_url]/bencandy.php?fid=$fid&aid=$rs[aid]");exit;
}else{
	header("location:$webdb[www_url]/list.php?fid=$fid");exit;
	//showerr("已到尽头了");
}
?>