<?php
//require("../b/member/".basename(__FILE__));
if(PHP_VERSION < '4.1.0') {
	$_GET = &$HTTP_GET_VARS;
}
header("location:../b/member/".basename(__FILE__).'?atn='.$_GET[atn].($_GET[id]?"&id=".$_GET[id]:""));
?>