<?php
require("global.php");

if($paymoeny){
	$atc_moeny=base64_decode(strrev($paymoeny));
}
if(!$vo_id) showerr("���Դ���ȱ�ٶ���ƾ֤");

if(in_array($banktype,array('alipay','tenpay','99pay','yeepay'))){
	include(PHP168_PATH."inc/olpay/{$banktype}.php");
}elseif($banktype){
	showerr("֧����������!");	
}



require(Mpath."inc/head.php");
require(getTpl("buymoneycard"));
require(Mpath."inc/foot.php");


function olpay_send(){
	global $db,$pre,$webdb,$banktype,$atc_moeny,$timestamp,$lfjuid,$lfjid,$vo_id,$Mdomain;
	
	if($atc_moeny<1){
		showerr("֧�����������1Ԫ");
	}

	$array[money]=$atc_moeny;
	$array[return_url]="$Mdomain/member/vip.php?action=onlinepay_ok&vo_id=$vo_id&";
	$array[title]="VIP�̼ҷ���֧��";
	$array[content]="Ϊ�ʺ�:$lfjid,֧��VIP�̼ҷ������ѷ���";
	$array[numcode]=strtolower(rands(10));

	//$db->query("INSERT INTO {$pre}olpay (`numcode` , `money` , `posttime` , `uid` , `username`, `banktype`, `paytype` ) VALUES ('$array[numcode]','$array[money]','$timestamp','$lfjuid','$lfjid','$banktype','1')");

	//����
	

	return $array;
}

function olpay_end($numcode){
	global $db,$pre,$webdb,$banktype,$vo_id,$Mdomain;

	$rt = $db->get_one("SELECT * FROM {$pre}olpay WHERE numcode='$numcode' AND `paytype`=1");
	if(!$rt){
		showerr('ϵͳ��û�����ĳ�ֵ�������޷����֧�� ��');
	}
	if($rt['ifpay'] == 1){
		showerr('�ö����Ѿ�֧���ɹ���');
	}
	$db->query("UPDATE {$pre}olpay SET ifpay='1' WHERE id='$rt[id]'");

	$num=$rt[money]*$webdb[alipay_scale];
	$db->query("UPDATE {$pre}memberdata SET moneycard=moneycard+'$num' WHERE uid='$rt[uid]'");
	
	//�����Լ���
	
	refreshto("$Mdomain/member/?main=vip.php","��ϲ��֧���ɹ�",10);
}

?>