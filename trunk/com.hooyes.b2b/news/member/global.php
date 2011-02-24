<?php

define('Memberpath',dirname(__FILE__).'/');

require(Memberpath."../global.php");

require(ROOT_PATH."inc/class.inc.php");

$Guidedb=new Guide_DB;

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


function ckGroupYz($value){
	global $lfjdb;
	if(!$value)
	{
		return 0;
	}
	$detail=explode(",",$value);
	if( in_array($lfjdb[groupid],$detail) )
	{
		return 1;
	}
}


?>