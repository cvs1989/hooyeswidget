<?php
!function_exists('html') && exit('ERR');
if($action=='mod'){

	unset($SQL);
	//$postdb[js]=comimg();
	$postdb[rolltype]=$rolltype;
	$postdb[width]=$width;
	$postdb[height]=$height;
	$postdb[picurl]=$picurl;
	$postdb[piclink]=$piclink;
	$postdb[picalt]=$picalt;
	foreach($postdb[picurl] AS $key=>$value){
		$postdb[picurl][$key]=En_TruePath($value);
	}
	foreach($postdb[piclink] AS $key=>$value){
		$postdb[piclink][$key]=En_TruePath($value);
	}
	$code=addslashes(serialize($postdb));
	$div_db[div_w]=$div_w;
	$div_db[div_h]=$div_h;
	$div_db[div_bgcolor]=$div_bgcolor;
	$div=addslashes(serialize($div_db));
	$typesystem=0;

	//插入或更新标签库
	do_post();

}else{

	$rsdb=get_label();
	$div=unserialize($rsdb[divcode]);
	@extract($div);
	$code=unserialize($rsdb[code]);
	@extract($code);
	if(!is_array($picurl)){
		$picurl=array(1=>"",2=>"");
	}
	$div_width && $div_w=$div_width;
	$div_height && $div_h=$div_height;

	if($rsdb[js_time]){
		$js_time='checked';
	}
	$hide=(int)$rsdb[hide];
	$hidedb["$hide"]="checked";

	foreach($picurl AS $key=>$value){
		$picurl[$key]=En_TruePath($value,0);
	}
	foreach($piclink AS $key=>$value){
		$piclink[$key]=En_TruePath($value,0);
	}

	$_rolltype[intval($rolltype)]=' checked ';
	
	
 	require("head.php");
	require("template/label/rollpic.htm");
	require("foot.php");

}
?>