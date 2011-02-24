<?php
require("b/global.php");
//域名判断

if(!preg_match("/[\d\.]{7,15}/",$HTTP_HOST)){
	$t=explode(".",$HTTP_HOST);
	$host=$t[0];
}
if($host){
	$limitmain=explode(",",$webdb[vipselfdomaincannot]);
	if(!in_array($host,$limitmain)){
		if(!preg_match("/^[a-z\d]{2,12}$/",$host))  echo "alert('抱歉，访问地址不符合规定');history.go(-1);";
		$rt=$db->get_one("select uid from  {$_pre}company where host='$host'");
		if($rt[uid]){
			$url="homepage.php?uid=".$rt[uid];
			echo "window.location.href = '$url';";
			exit;
		}
	}
}