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

$rsdb[code]="<iframe src='http://weather.265.com/weather.htm' width='168' height='54' frameborder='no' border='0' marginwidth='0' marginheight='0' scrolling='no'></iframe>";

require("head.php");
require("template/label/hack_code.htm");
require("foot.php");
?>