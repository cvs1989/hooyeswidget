<?php
require(dirname(__FILE__)."/"."global.php");

if(!$lfjid){
	showerr("ฤใปนรปตวยผ");
}

if($job=="publicize")
{
	require(dirname(__FILE__)."/"."head.php");
	require(dirname(__FILE__)."/"."template/getmoney.htm");
	require(dirname(__FILE__)."/"."foot.php");
}


?>