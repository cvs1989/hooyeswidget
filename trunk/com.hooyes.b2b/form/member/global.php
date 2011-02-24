<?php
define('Memberpath',dirname(__FILE__).'/');
require(Memberpath."../global.php");

/**
*前台是否开放
**/
if($webdb[module_close])
{
	$webdb[Info_closeWhy]=str_replace("\r\n","<br>",$webdb[Info_closeWhy]);
	showerr("本系统暂时关闭:$webdb[Info_closeWhy]");
}

if(!$lfjid){
	showerr("你还没登录");
}

?>