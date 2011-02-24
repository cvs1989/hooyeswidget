<?php
!function_exists('html') && exit('ERR');

if(!$webdb[alipay_id]){
	showerr('系统没有设置支付宝收款帐号,所以不能使用支付宝在线支付');
}

if($trade_status=='TRADE_FINISHED'){
	$veryfy_result = file_get_contents("http://notify.alipay.com/trade/notify_query.do?notify_id=$notify_id&partner=2088001505801569");
	if(!eregi("true$",$veryfy_result)){
		showerr('安全验证参数校验失败，无法完成充值！<hr>'.$veryfy_result);
	}

	olpay_end($out_trade_no);
}
else
{
	$array=olpay_send();

	$url  = "http://pay.phpwind.com/pay/create_payurl.php?";

	$para = array(
			'_input_charset' => 'gbk',
			'service' => 'create_direct_pay_by_user',
			'return_url' => $array[return_url],
			'payment_type' => '1',
			'subject' => $array[title],
			'body' => $array[content],
			'out_trade_no' => $array[numcode],
			'total_fee' => $array[money],
			'seller_email' => $webdb[alipay_id],
		);
	foreach($para as $key => $value){
		if($value){
			$url  .= "$key=".urlencode($value)."&";
		}
	}
	
	header("location:$url");
	exit;
}
?>