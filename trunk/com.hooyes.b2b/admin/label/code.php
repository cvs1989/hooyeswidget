<?php
!function_exists('html') && exit('ERR');
if($action=='mod'){

	if(ereg("\\\$AD_label",$code)){
		showmsg("这里不能放广告代码,要放广告代码,只能手工修改模板放进去!");
	}
	//$code=preg_replace("/http:\/\/([\d\w\/_\.]*)\/ie_edit\//is","",$code);
	//对地址做处理
	$code=En_TruePath($code);

	$div_db[html_edit]=$html_edit;
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

//if($html_edit==1||$htmledit=='yes'){
$rsdb[code] = editor_replace($rsdb[code]);
//}

//强制更换$html_edit
//if($htmledit=="no"){
//	$html_edit=0;
//}elseif($htmledit=="yes"){
//	$html_edit=1;
//}


//真实地址还原
$rsdb[code]=En_TruePath($rsdb[code],0);

require("head.php");
require("template/label/code.htm");
require("foot.php");
?>