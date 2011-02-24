<?php
require("global.php");

if($paymoeny){
	$atc_moeny=base64_decode(strrev($paymoeny));
}
if(!$vo_id) showerr("调试错误，缺少订单凭证");

if(in_array($banktype,array('alipay','tenpay','99pay','yeepay'))){
	include(PHP168_PATH."inc/olpay/{$banktype}.php");
}elseif($banktype){
	showerr("支付类型有误!");	
}



require(Mpath."inc/head.php");
require(getTpl("buymoneycard"));
require(Mpath."inc/foot.php");


function olpay_send(){
	global $db,$pre,$webdb,$banktype,$atc_moeny,$timestamp,$lfjuid,$lfjid,$vo_id,$Mdomain;
	
	if($atc_moeny<1){
		showerr("支付金额必须大于1元");
	}

	$array[money]=$atc_moeny;
	$array[return_url]="$Mdomain/member/vip.php?action=onlinepay_ok&vo_id=$vo_id&";
	$array[title]="VIP商家服务支付";
	$array[content]="为帐号:$lfjid,支付VIP商家服务续费费用";
	$array[numcode]=strtolower(rands(10));

	//$db->query("INSERT INTO {$pre}olpay (`numcode` , `money` , `posttime` , `uid` , `username`, `banktype`, `paytype` ) VALUES ('$array[numcode]','$array[money]','$timestamp','$lfjuid','$lfjid','$banktype','1')");

	//处理
	

	return $array;
}

function olpay_end($numcode){
	global $db,$pre,$webdb,$banktype,$vo_id,$Mdomain;

	$rt = $db->get_one("SELECT * FROM {$pre}olpay WHERE numcode='$numcode' AND `paytype`=1");
	if(!$rt){
		showerr('系统中没有您的充值订单，无法完成支付 ！');
	}
	if($rt['ifpay'] == 1){
		showerr('该订单已经支付成功！');
	}
	$db->query("UPDATE {$pre}olpay SET ifpay='1' WHERE id='$rt[id]'");

	$num=$rt[money]*$webdb[alipay_scale];
	$db->query("UPDATE {$pre}memberdata SET moneycard=moneycard+'$num' WHERE uid='$rt[uid]'");
	
	//处理自己的
	
	refreshto("$Mdomain/member/?main=vip.php","恭喜你支付成功",10);
}

?>