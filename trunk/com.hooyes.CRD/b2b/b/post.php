<?php
require_once("global.php");

header('Content-type: text/html; charset=gbk');

if($action=='getfidsonslist'){
	require(Mpath."inc/categories.php");
	
	$bcategory->cache_read();
	
	if(!isset($bcategory->categories[$fup]['categories'])) echo "&nbsp;";
	
	foreach($bcategory->categories[$fup]['categories'] as $v){
		$next='true';
		if(!isset($v['categories']) || $class >= 4) $next = 'false';
		$str.= "<div><a href=\"javascript:;\" onclick=\"changeClassName($class,this,{$v['fid']},$next,$ctype ,\'$Murl\')\">{$v['name']}</a></div>"; 
	}
	
	$str=$str?$str:"&nbsp;";
	echo '<script >parent.inputcontent(\''.$str.'\','.$class.');</script>';
	exit;
//װ�ز�������
}elseif($action=='oladparametersform'){ 
	require(Mpath."inc/categories.php");
	
	$bcategory->cache_read();
	
	$form=parameters_postform($fid,$id);
	if(!$form) $form="�޿���������";

	echo '<div id="mycontent">'.$form.'</div>';
	echo '<script >parent.inputcontent_to(document.getElementById("mycontent").innerHTML,"'.$showid.'");</script>';
	exit;
	
}else{
	
	//��ת����Ա���ķ���ҳ

}
?>