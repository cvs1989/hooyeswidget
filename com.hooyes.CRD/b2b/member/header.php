<?php
require("global.php");

$show='';
$query = $db->query("SELECT * FROM {$pre}module WHERE unite_member=1 ORDER BY list DESC");
while($rs = $db->fetch_array($query))
{
	$show.=",'{$rs[name]}|userinfo.php?uid=|left.php?Smenu=$rs[pre]'";
}

require(dirname(__FILE__)."/"."template/header.htm");
?>