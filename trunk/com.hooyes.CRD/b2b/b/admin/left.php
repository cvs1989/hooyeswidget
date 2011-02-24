<?php
require_once("global.php");

if($lfjdb[groupid]==5){
	$power=2;
}elseif($web_admin){
	$power=3;
}else{
	$power=1;
}
require_once("menu.php");
require_once("template/left.htm");

?>