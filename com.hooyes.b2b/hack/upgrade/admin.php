<?php
!function_exists('html') && exit('ERR');
if($job=="get"&&$Apower[upgrade_ol])
{

	hack_admin_tpl('get');
}
elseif($action=="get"&&$Apower[upgrade_ol])
{
	$fileurl="http://down2.qibosoft.com/upgrade.zip";
	if($code=file_get_contents($fileurl))
	{
		write_file(ROOT_PATH."cache/upgrade.zip",$code);
	}
	elseif($code=file($fileurl))
	{
		write_file(ROOT_PATH."cache/upgrade.zip",$code);
	}
	elseif(copy($fileurl,ROOT_PATH."cache/upgrade.zip"))
	{
	}
	elseif($code=sockOpenUrl($fileurl))
	{
		write_file(ROOT_PATH."cache/upgrade.zip",$code);
	}

	require_once(ROOT_PATH."inc/class.z.php");
	$z = new Zip;
	makepath(ROOT_PATH."cache/upgrade");
	$z->Extract(ROOT_PATH."cache/upgrade.zip",ROOT_PATH."cache/upgrade");
	unlink(ROOT_PATH."cache/upgrade.zip");
	echo "<META HTTP-EQUIV=REFRESH CONTENT='0;URL=$webdb[www_url]/cache/upgrade/index.php'>";
	exit;
}
?>