<?php
require("global.php");

if(in_array($banktype,array('alipay','tenpay','99pay','yeepay'))){
	include(PHP168_PATH."inc/olpay/{$banktype}.php");
}elseif($banktype){
	showerr("֧����������!");	
}

require(PHP168_PATH."inc/head.php");
require(html("buymoneycard"));
require(PHP168_PATH."inc/foot.php");


function olpay_send(){
	global $db,$pre,$webdb,$banktype,$atc_moeny,$timestamp,$lfjuid,$lfjid;
	
	if($atc_moeny<1){
		showerr("������ĳ�ֵ��������1Ԫ");
	}

	$array[money]=$atc_moeny;
	$array[return_url]="$webdb[www_url]/do/buymoneycard.php?banktype=$banktype&";
	$array[title]="������,������ֵ";
	$array[content]="Ϊ�ʺ�:$lfjid,���߸������";
	$array[numcode]=strtolower(rands(10));

	$db->query("INSERT INTO {$pre}olpay (`numcode` , `money` , `posttime` , `uid` , `username`, `banktype`, `paytype` ) VALUES ('$array[numcode]','$array[money]','$timestamp','$lfjuid','$lfjid','$banktype','1')");

	return $array;
}

function olpay_end($numcode){
	global $db,$pre,$webdb,$banktype;

	$rt = $db->get_one("SELECT * FROM {$pre}olpay WHERE numcode='$numcode' AND `paytype`=1");
	if(!$rt){
		showerr('ϵͳ��û�����ĳ�ֵ�������޷���ɳ�ֵ��');
	}
	if($rt['ifpay'] == 1){
		showerr('�ö����Ѿ���ֵ�ɹ���');
	}
	$db->query("UPDATE {$pre}olpay SET ifpay='1' WHERE id='$rt[id]'");

	$num=$rt[money]*$webdb[alipay_scale];
	$db->query("UPDATE {$pre}memberdata SET moneycard=moneycard+'$num' WHERE uid='$rt[uid]'");

	refreshto("$webdb[www_url]/","��ϲ���ֵ�ɹ�",10);
}

?>