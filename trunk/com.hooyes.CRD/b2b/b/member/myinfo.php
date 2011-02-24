<?php
require(dirname(__FILE__)."/"."global.php");

$rt=$db->get_one("select rid,uid,title from `{$_pre}company` where uid='$lfjuid'");
if($rt[title]){
	extract($db->get_one("select count(*) as gongyinnum from `{$_pre}content_sell` where uid='$lfjuid'"));
	extract($db->get_one("select count(*) as qiugounum from `{$_pre}content_buy` where uid='$lfjuid'"));
}


require(dirname(__FILE__)."/"."head.php");
require(dirname(__FILE__)."/"."template/myinfo.htm");
require(dirname(__FILE__)."/"."foot.php");
?>