<?php
require(dirname(__FILE__)."/"."global.php");

if(!$lfjid){
	showerr("你还没登录");
}
$linkdb=array("发布"=>"$webdb[www_url]/do/form.php?mid=$mid");
$mid=intval($mid);
$fidDB = $db->get_one("SELECT * FROM {$pre}form_module WHERE id='$mid'");
if(!$fidDB){
	showerr("MID有误");
}
$array=unserialize($fidDB[config]);


$rows=20;
if($page<1){
	$page=1;
}
$min=($page-1)*$rows;

$SQL=" AND C.uid='$lfjuid' ";
$showpage=getpage("{$pre}form_content C","WHERE C.mid='$mid' $SQL","?mid=$mid",$rows);
$query = $db->query("SELECT C.*,D.* FROM {$pre}form_content C LEFT JOIN {$pre}form_content_$mid D ON C.id=D.id WHERE C.mid='$mid' $SQL ORDER BY C.id DESC LIMIT $min,$rows");
while($rs = $db->fetch_array($query)){
	foreach( $array[listshow_db] AS $key=>$rs2){
		$rs[$key]=SRC_true_value($array[field_db][$key],$rs[$key]);
	}
	$rs[posttime]=date("Y-m-d",$rs[posttime]);
	$listdb[]=$rs;
}
require(dirname(__FILE__)."/"."head.php");
require(PHP168_PATH."php168/form_tpl/list_$mid.htm");
require(dirname(__FILE__)."/"."foot.php");

?>