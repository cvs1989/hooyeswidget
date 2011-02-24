<?php
!function_exists('html') && exit('ERR');

$erp=get_id_table($id);
$rsdb=$db->get_one("SELECT * FROM {$pre}article$erp WHERE aid='$id'");
$fidDB=$db->get_one("SELECT * FROM {$pre}sort WHERE fid='$rsdb[fid]'");

if($fidDB[admin]&&$lfjid){
	$detail=explode(",",$fidDB[admin]);
	if( in_array($lfjid,$detail) ){
		$web_admin=1;
	}
}

if($fidDB[allowdownload]&&!$web_admin&&$lfjuid!==$rsdb[uid]){
	$detail=explode(",",$fidDB[allowdownload]);
	if( !in_array($groupdb['gid'],$detail) ){
		showerr("你所在的用户组在本栏目无权限下载");
	}
}
if($rsdb[allowdown]&&!$web_admin&&$lfjuid!==$rsdb[uid]){
	$detail=explode(",",$rsdb[allowdown]);
	if( !in_array($groupdb['gid'],$detail) ){
		showerr("你所在的用户组本文设置无权限下载");
	}
}
$url=base64_decode($url);
$fileurl=str_replace($webdb[www_url],"",$url);
if( eregi(".php$",$fileurl) && is_file(PHP168_PATH."$fileurl") ){
	die("ERR");
}

if(!$webdb[DownLoad_readfile]){
	$fileurl=strstr($url,"://")?$url:tempdir($fileurl);
	header("location:$fileurl");
	exit;
}

if( is_file(PHP168_PATH."$fileurl") ){
	$filename=basename($fileurl);
	$filetype=substr(strrchr($filename,'.'),1);
	$_filename=preg_replace("/([\d]+)_(200[\d]+)_([^_]+)\.([^\.]+)/is","\\3",$filename);
	
	if(eregi("^([a-z0-9=]+)$",$_filename)&&!eregi("(jpg|gif|png)$",$filename)){
		$filename=urldecode(base64_decode($_filename)).".$filetype";
	}
	ob_end_clean();
	header('Last-Modified: '.gmdate('D, d M Y H:i:s',time()).' GMT');
	header('Pragma: no-cache');
	header('Content-Encoding: none');
	header('Content-Disposition: attachment; filename='.$filename);
	header('Content-type: '.$filetype);
	header('Content-Length: '.filesize(PHP168_PATH."$fileurl"));
	readfile(PHP168_PATH."$fileurl");
}else{
	$filename=basename($fileurl);
	$filetype=substr(strrchr($filename,'.'),1);
	$fileurl=strstr($url,"://")?$url:tempdir($fileurl);
	ob_end_clean();
	header('Last-Modified: '.gmdate('D, d M Y H:i:s',time()).' GMT');
	header('Pragma: no-cache');
	header('Content-Encoding: none');
	header('Content-Disposition: attachment; filename='.$filename);
	header('Content-type: '.$filetype);
	readfile($fileurl);
}
//$fileurl=strstr($url,"://")?$url:tempdir($fileurl);
//header("location:$fileurl");
?>