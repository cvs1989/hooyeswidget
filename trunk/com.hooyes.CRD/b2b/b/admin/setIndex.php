<?php
require_once("global.php");

$linkdb=array("首页设置"=>"?");

if(!$action){
	require("head.php");
	require("template/setIndex/set.htm");
	require("foot.php");

}elseif($action=='setbusinesshome'){
	$content = <<<EOT
<?php
require(dirname(__FILE__)."/b/".basename(__FILE__));
?>
EOT;
	write_file(PHP168_PATH."index.php", $content);

	refreshto($FROMURL,"操作成功",1);

}elseif($action=='setv6sinesshome'){

	$content = <<<EOT
<?php
require(dirname(__FILE__)."/do/".basename(__FILE__));
?>
EOT;
	write_file(PHP168_PATH."index.php", $content);
	refreshto($FROMURL,"操作成功",1);
}

?>