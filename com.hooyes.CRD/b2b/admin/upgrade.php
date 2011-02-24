<?php
!function_exists('html') && exit('ERR');
if($job=="get"&&$Apower[upgrade_ol])
{
	require(dirname(__FILE__)."/"."head.php");
	require(dirname(__FILE__)."/"."template/upgrade/get.htm");
	require(dirname(__FILE__)."/"."foot.php");
}
elseif($action=="get"&&$Apower[upgrade_ol])
{
	$fileurl="http://down2.php168.com/upgrade.zip";
	if($code=file_get_contents($fileurl))
	{
		write_file(PHP168_PATH."cache/upgrade.zip",$code);
	}
	elseif($code=file($fileurl))
	{
		write_file(PHP168_PATH."cache/upgrade.zip",$code);
	}
	elseif(copy($fileurl,PHP168_PATH."cache/upgrade.zip"))
	{
	}
	elseif($code=sockOpenUrl($fileurl))
	{
		write_file(PHP168_PATH."cache/upgrade.zip",$code);
	}

	require_once(PHP168_PATH."inc/class.z.php");
	$z = new Zip;
	makepath(PHP168_PATH."cache/upgrade");
	$z->Extract(PHP168_PATH."cache/upgrade.zip",PHP168_PATH."cache/upgrade");
	unlink(PHP168_PATH."cache/upgrade.zip");
	echo "<META HTTP-EQUIV=REFRESH CONTENT='0;URL=$webdb[www_url]/cache/upgrade/index.php'>";
	exit;
}
?>