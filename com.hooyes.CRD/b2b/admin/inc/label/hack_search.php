<?php
!function_exists('html') && exit('ERR');
if($action=='mod'){

	$div_db[div_w]=$div_w;
	$div_db[div_h]=$div_h;
	$div_db[div_bgcolor]=$div_bgcolor;
	$div=addslashes(serialize($div_db));
	$typesystem=0;

	//�������±�ǩ��
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

$rs=$db->get_one("SELECT * FROM {$pre}hack WHERE keywords='search'");
@extract(unserialize($rs[config]));
$rsdb[code]=stripslashes($searchcode);
$rsdb[code]=str_replace('$webdb[www_url]/',"http://www_php168_com/",$rsdb[code]);

require("head.php");
require("template/label/hack_code.htm");
require("foot.php");
?>