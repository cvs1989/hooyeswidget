<?php
require("global.php");

if(in_array($banktype,array('alipay','tenpay','99pay','yeepay'))){
	include(PHP168_PATH."inc/olpay/{$banktype}.php");
}elseif($banktype){
	showerr("支付类型有误!");	
}

require(PHP168_PATH."inc/head.php");
require(html("buymoneycard"));
require(PHP168_PATH."inc/foot.php");


function olpay_send(){
	global $db,$pre,$webdb,$banktype,$atc_moeny,$timestamp,$lfjuid,$lfjid;
	
	if($atc_moeny<1){
		showerr("你输入的充值额必须大于1元");
	}

	$array[money]=$atc_moeny;
	$array[return_url]="$webdb[www_url]/do/buymoneycard.php?banktype=$banktype&";
	$array[title]="购买金币,在线允值";
	$array[content]="为帐号:$lfjid,在线付款购买金币";
	$array[numcode]=strtolower(rands(10));

	$db->query("INSERT INTO {$pre}olpay (`numcode` , `money` , `posttime` , `uid` , `username`, `banktype`, `paytype` ) VALUES ('$array[numcode]','$array[money]','$timestamp','$lfjuid','$lfjid','$banktype','1')");

	return $array;
}

function olpay_end($numcode){
	global $db,$pre,$webdb,$banktype;

	$rt = $db->get_one("SELECT * FROM {$pre}olpay WHERE numcode='$numcode' AND `paytype`=1");
	if(!$rt){
		showerr('系统中没有您的充值订单，无法完成充值！');
	}
	if($rt['ifpay'] == 1){
		showerr('该订单已经充值成功！');
	}
	$db->query("UPDATE {$pre}olpay SET ifpay='1' WHERE id='$rt[id]'");

	$num=$rt[money]*$webdb[alipay_scale];
	$db->query("UPDATE {$pre}memberdata SET moneycard=moneycard+'$num' WHERE uid='$rt[uid]'");

	refreshto("$webdb[www_url]/","恭喜你充值成功",10);
}

?>