<?php
!function_exists('html') && exit('ERR');
if($action=='mod'){
	
	$code="<SCRIPT src=\'http://www_qibosoft_com/vote/vote.php?job=js&cid=$voteid\'></SCRIPT>";

	$div_db[voteid]=$voteid;

	$div_db[div_w]=$div_w;
	$div_db[div_h]=$div_h;
	$div_db[div_bgcolor]=$div_bgcolor;
	$div=addslashes(serialize($div_db));
	$typesystem=0;

	//插入或更新标签库
	do_post();


}



$rsdb=get_label();
$rsdb[hide]?$hide_1='checked':$hide_0='checked';
if($rsdb[js_time]){
	$js_time='checked';
}

@extract(unserialize($rsdb[divcode]));
$div_width && $div_w=$div_width;
$div_height && $div_h=$div_height;

$query = $db->query("SELECT * FROM `{$_pre}topic` ORDER BY cid DESC");
while($rs = $db->fetch_array($query)){
	if(!$voteid){
		$voteid=$rs[cid];
	}
	$rs[ckcid]=$voteid==$rs[cid]?" checked ":"";
	$listdb[]=$rs;
}

require("head.php");
require(dirname(__FILE__)."/template/label/hack_vote.htm");
require("foot.php");
?>