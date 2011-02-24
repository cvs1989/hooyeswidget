<?php
require_once("global.php");

$mid=intval($mid);
$fidDB = $db->get_one("SELECT * FROM {$pre}form_module WHERE id='$mid'");

if(!$fidDB){
	showerr("MIDÓÐÎó");
}

if($fidDB[allowview]&&!$web_admin){
	$arr=explode(",",$fidDB[allowview]);
	if(!in_array($groupdb['gid'],$arr)){
		if($lfjuid){
			$uid=$lfjuid;
		}else{
			showerr("ÇëÏÈµÇÂ¼");
		}		
	}
}

$array=unserialize($fidDB[config]);
  
$rows=20;
if($page<1){
	$page=1;
}
$min=($page-1)*$rows;

$SQL='';
if($uid){
	$SQL=" AND C.uid='$uid' ";
}

$showpage=getpage("{$pre}form_content C","WHERE C.mid='$mid' $SQL","?mid=$mid",$rows);
$query = $db->query("SELECT C.*,D.* FROM {$pre}form_content C LEFT JOIN {$pre}form_content_$mid D ON C.id=D.id WHERE C.mid='$mid' $SQL ORDER BY C.id DESC LIMIT $min,$rows");
while($rs = $db->fetch_array($query)){

	foreach( $array[listshow_db] AS $key=>$rs2){
		$rs[$key]=SRC_true_value($array[field_db][$key],$rs[$key]);
	}

	$rs[posttime]=date("Y-m-d",$rs[posttime]);
	$listdb[]=$rs;
}
require(PHP168_PATH."inc/head.php");
require(PHP168_PATH."php168/form_tpl/list_$mid.htm");
require(PHP168_PATH."inc/foot.php");

?>