<?php
!function_exists('html') && exit('ERR');
if($action=='mod'){

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

$rs=$db->get_one("SELECT * FROM {$pre}hack WHERE keywords='webmsg'");
@extract(unserialize($rs[config]));
$rsdb[code]="<SCRIPT src='http://www_php168_com/do/hack.php?hack=webmsg&job=js&cktime=$cktime'></SCRIPT>";


require("head.php");
require("template/label/hack_code.htm");
require("foot.php");
?>