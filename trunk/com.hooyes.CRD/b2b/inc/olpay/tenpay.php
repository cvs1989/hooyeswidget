<?php
!function_exists('html') && exit('ERR');

if(!$webdb[tenpay_id]){
	showerr('系统没有设置财付通收款帐号,所以不能使用财付通在线支付');
}elseif(!$webdb[tenpay_key]){
	showerr('系统没有设置财付通密钥,所以不能使用财付通在线支付');
}

if($attach=='my_magic_string'){
	$text = "cmdno=$cmdno&pay_result=$pay_result&date=$date&transaction_id=$transaction_id&sp_billno=$sp_billno&total_fee=$total_fee&fee_type=$fee_type&attach=$attach&key=$webdb[tenpay_key]";
	$mac = strtoupper(md5($text));

	if($mac != $sign){
		showerr( "验证MD5签名失败"); 
	}

	if( $webdb[tenpay_id] != $bargainor_id ){
		showerr( "错误的商户号"); 
	}

	if($pay_result != "0" ){
		showerr( "支付失败"); 
	}
	
	olpay_end($sp_billno);
}
else
{
	$array=olpay_send();
  
	/*这里替换为您的实际商户号*/
	$strSpid    = $webdb[tenpay_id]; 
	/*strSpkey是32位商户密钥, 请替换为您的实际密钥*/
	$strSpkey   = $webdb[tenpay_key]; 
	/*财付通支付为"1" (当前只支持 cmdno=1)*/
	$strCmdNo   = "1";
	/*交易日期 (yyyymmdd)*/
	$strBillDate= date('Ymd');  
	/*银行类型:	
      0		  财付通
  		1001	招商银行   
  		1002	中国工商银行  
  		1003	中国建设银行  
  		1004	上海浦东发展银行   
  		1005	中国农业银行  
  		1006	中国民生银行  
  		1008	深圳发展银行   
  		1009	兴业银行   */
	$strBankType= "0";  
	/*商品名称*/
	$strDesc    = $array[title]; 		
	/*用户QQ号码, 现在置为空串*/
	$strBuyerId = "";
	/*商户号*/	
	$strSaler   = $strSpid;				
	/*商户生成的订单号(最多10位)*/	
	$strSpBillNo = $array[numcode];
	/*重要: 交易单号
	  交易单号(28位): 商户号(10位) + 日期(8位) + 流水号(10位), 必须按此格式生成, 且不能重复
	  如果sp_billno超过10位, 则截取其中的流水号部分加到transaction_id后部(不足10位左补0)
	  如果sp_billno不足10位, 则左补0, 加到transaction_id后部*/
	$strTransactionId = $strSpid . $strBillDate . $strSpBillNo;
	/*总金额, 分为单位*/
	$strTotalFee = $array[money]*100;
	/*货币类型: 1 – RMB(人民币) 2 - USD(美元) 3 - HKD(港币)*/
	$strFeeType  = "1";
	/*财付通回调页面地址, 推荐使用ip地址的方式(最长255个字符)*/
	$strRetUrl  = $array[return_url];
	$strRetUrl  = urlencode($strRetUrl);
	/*商户私有数据, 请求回调页面时原样返回*/
	$strAttach  = "my_magic_string";
	/*生成MD5签名*/
	$strSignText = "cmdno=" . $strCmdNo . "&date=" . $strBillDate . "&bargainor_id=" . $strSaler .
	      "&transaction_id=" . $strTransactionId . "&sp_billno=" . $strSpBillNo .        
	      "&total_fee=" . $strTotalFee . "&fee_type=" . $strFeeType . "&return_url=" . $strRetUrl .
	      "&attach=" . $strAttach . "&key=" . $strSpkey;
	$strSign = strtoupper(md5($strSignText));
  
	/*请求支付串*/
	$strRequest = "cmdno=" . $strCmdNo . "&date=" . $strBillDate . "&bargainor_id=" . $strSaler .        
  "&transaction_id=" . $strTransactionId . "&sp_billno=" . $strSpBillNo .        
  "&total_fee=" . $strTotalFee . "&fee_type=" . $strFeeType . "&return_url=" . $strRetUrl .        
  "&attach=" . $strAttach . "&bank_type=" . $strBankType . "&desc=" . $strDesc .        
  "&purchaser_id=" . $strBuyerId .        
  "&sign=" . $strSign ; 
	
	header("location:https://www.tenpay.com/cgi-bin/v1.0/pay_gate.cgi?$strRequest");
	exit;
}

?>