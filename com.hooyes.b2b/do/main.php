<?php
if(file_exists(dirname(__FILE__)."/../".'install.php')){
	header("location:install.php");exit;
}
require(dirname(__FILE__)."/"."global.php");

$Cache_FileName=ROOT_PATH."cache/list_cache/index.php";
if(!$jobs&&!$MakeIndex&&$ch<2&&$webdb[index_cache_time]&&(time()-filemtime($Cache_FileName))<($webdb[index_cache_time]*60)){
	echo read_file($Cache_FileName);
	exit;
}

require(ROOT_PATH."data/friendlink.php");




/**
*fid��ĿFID��Ϊ0,pagetypeҳ�����Ͷ���0(��ʵΪ1��,ʡ�Է���Щ),module����Ϊ0
**/
$chdb[main_tpl] = html('main');
$ch_fid	= $ch_module = 0;
$ch_pagetype = 9;
$ch = 0;
require(ROOT_PATH."inc/label_module.php");



require(ROOT_PATH."inc/head.php");
require(html("main"));
require(ROOT_PATH."inc/foot.php");



//���ɻ���
if(!$jobs&&!$MakeIndex&&$ch<2&&$webdb[index_cache_time]&&(time()-filemtime($Cache_FileName))>($webdb[index_cache_time]*60)){
	
	if(!is_dir(dirname($Cache_FileName))){
		makepath(dirname($Cache_FileName));
	}
	write_file($Cache_FileName,$content);
}elseif($jobs=='show'){
	@unlink($Cache_FileName);
}

/*��ҳ����̬*/
if( ($jobs!='show'&&$webdb[MakeIndexHtmlTime]>0) || $MakeIndex ){
	if( $MakeIndex || (time()-@filemtime('index.htm')-$webdb[MakeIndexHtmlTime]*60)>0 ){	
		write_file(ROOT_PATH.'index.htm',$content);
		if($MakeIndex){		
			ob_end_clean();
			echo "<META HTTP-EQUIV=REFRESH CONTENT='0;URL=$webdb[www_url]/index.htm'>";
			exit;
		}
	}
}

?>