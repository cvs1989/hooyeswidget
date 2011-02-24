<?php
require_once("global.php");
$linkdb=array("核心设置"=>"center.php?job=config");//,"静态目录布署"=>"center.php?job=makehtml","页面显示设置"=>"center.php?job=guide"
if($job)
{
	$query=$db->query(" select * from {$_pre}config ");
	while( $rs=$db->fetch_array($query) ){
		$webdb[$rs[c_key]]=$rs[c_value];
	}


	$allowGuestComment[$webdb[allowGuestComment]]=" checked";
	$forbidComment[$webdb[forbidComment]]=" checked";
	$allowGuestCommentPass[$webdb[allowGuestCommentPass]]=" checked";
	$allowUserPostJob[$webdb[allowUserPostJob]]=" checked";
	$checkUserPostJob[$webdb[checkUserPostJob]]=" checked";
	$checkUserPostResume[$webdb[checkUserPostResume]]=" checked";
	$resumeView_contact[$webdb[resumeView_contact]]=" checked";
	$postzhCheck[$webdb[postzhCheck]]=" checked";
	$vendorRenzheng[$webdb[vendorRenzheng]]=" checked";
	$auto_userpostpic[$webdb[auto_userpostpic]]=" checked";
	$page_head_style[$webdb[page_head_style]]=" checked";
	$autoyz_tg[$webdb[autoyz_tg]]=" checked";
	$company_lxfs[$webdb[company_lxfs]]=" checked";
	$postauto_yz[$webdb[postauto_yz]]=" checked";
	$postcompanyauto_yz[$webdb[postcompanyauto_yz]]=" checked";
	$bencandyIsHtml[$webdb[bencandyIsHtml]]=" checked";
	$vipselfdomain[$webdb[vipselfdomain]]=' checked';
	$listwidth[$webdb[listwidth]]=" checked";
}

if($job=="config")
{
	
	$select_style=select_style('webdbs[business_style]',$webdb[business_style]);

	include(PHP168_PATH."php168/member_style.php");
	
	

	$MaxSize=ini_get('upload_max_filesize');
	
	
	require("head.php");
	
	require("template/center/config.htm");
	require("foot.php");
}
elseif($action=="config")
{
	if(isset($webdbs[w8_MakeIndexHtmlTime])&&!$webdbs[w8_MakeIndexHtmlTime]&&$webdb[w8_MakeIndexHtmlTime]){
		@unlink("../index.htm.bak");
		rename("../index.htm","../index.htm.bak");
	}
	write_config_cache($webdbs);

	refreshto($FROMURL,"修改成功");

}
elseif($job=="gqlist_cache_remove")
{

	$path=Adminpath."../php168/list_cache/";
	del_file_listcache($path);
	refreshto($FROMURL,"清除成功");
}



?>