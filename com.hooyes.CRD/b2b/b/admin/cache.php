<?php
require_once("global.php");


if($job=="list"){
	require("head.php");
	require("template/cache/list.htm");
	require("foot.php");
}
elseif($act=="updatesortnum")
{
	set_time_limit(300);
	$db->query("UPDATE {$_pre}sort SET contents=0");
	$query = $db->query("SELECT * FROM {$_pre}sort WHERE type=0 LIMIT 500");
	while($rs = $db->fetch_array($query))
	{
		@extract($db->get_one("SELECT COUNT(id) AS NUM FROM {$_pre}content WHERE fid=$rs[fid]"));
		$db->query("UPDATE {$_pre}sort SET contents='$NUM' WHERE fid=$rs[fid]");
		$db->query("UPDATE {$_pre}sort SET contents=contents+'$NUM' WHERE fid=$rs[fup]");
	}
	refreshto("$FROMURL","更新完毕","1");
}
elseif($act=="updatesort")
{
	set_time_limit(300);
	fid_cache();
	cache_spsort();
	refreshto("$FROMURL","更新完毕","1");
}

function cache_spsort(){

}