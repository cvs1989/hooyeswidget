<?php
require_once(dirname(__FILE__)."/"."global.php");
if(!$lfjid){
	showerr("ฤใปนรปตวยผ");
}


require(dirname(__FILE__)."/"."head.php");
require(get_member_tpl('map'));
require(dirname(__FILE__)."/"."foot.php");

?>