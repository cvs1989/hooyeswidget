<?php
require_once("global.php");
if(!$uid){
	$uid=$lfjuid;
}
header("location:../job.php?job=userinfo&uid=$uid");
?>