<?php
require("global.php");

//�Ƹ�ͨ
if($attach=='my_magic_string'){
	$text = "cmdno=$cmdno&pay_result=$pay_result&date=$date&transaction_id=$transaction_id&sp_billno=$sp_billno&total_fee=$total_fee&fee_type=$fee_type&attach=$attach&key=$webdb[tenpay_key]";
	$mac = strtoupper(md5($text));

	if($mac != $sign){
		showerr( "��֤MD5ǩ��ʧ��"); 
	}

	if( $webdb[tenpay_id] != $bargainor_id ){
		showerr( "������̻���"); 
	}

	if($pay_result != "0" ){
		showerr( "֧��ʧ��"); 
	}

	$rt = $db->get_one("SELECT * FROM {$pre}olpay WHERE numcode='$sp_billno'");
	if(!$rt){
		showerr('ϵͳ��û�����ĳ�ֵ����������ʧ�ܣ�');
	}
	if($rt['ifpay'] == 1){
		showerr('�ö����Ѿ�֧���ɹ���');
	}
	$db->query("UPDATE {$pre}olpay SET ifpay='1' WHERE id='$rt[id]'");
	$db->query("UPDATE {$pre}shoporderuser SET ifpay='1' WHERE id='$rt[id]'");

	refreshto("../","��ϲ��֧���ɹ�",5);
}

//֧����
if($trade_status=='TRADE_FINISHED'){
	$veryfy_result = file_get_contents("http://notify.alipay.com/trade/notify_query.do?notify_id=$notify_id&partner=2088001505801569");
	if(!eregi("true$",$veryfy_result)){
		showerr('��ȫ��֤����У��ʧ�ܣ��޷���ɳ�ֵ��<hr>'.$veryfy_result);
	}
	$rt = $db->get_one("SELECT * FROM {$pre}olpay WHERE numcode='$out_trade_no'");
	if(!$rt){
		showerr('ϵͳ��û������֧���������޷���ɳ�ֵ��');
	}
	if($rt['ifpay'] == 1){
		showerr('�ö����Ѿ�֧���ɹ���');
	}
	$db->query("UPDATE {$pre}olpay SET ifpay='1' WHERE id='$rt[id]'");
	$db->query("UPDATE {$pre}shoporderuser SET ifpay='1' WHERE id='$rt[id]'");

	refreshto("../","��ϲ��֧���ɹ�",5);
}

//��Ǯ
if($signMsg){
	$webdb[pay99_id] && $webdb[pay99_id]="{$webdb[pay99_id]}01";
	$key=$webdb[pay99_key];
	//���ɼ��ܴ������뱣������˳��
	$merchantSignMsgVal=appendParam($merchantSignMsgVal,"merchantAcctId",$merchantAcctId);
	$merchantSignMsgVal=appendParam($merchantSignMsgVal,"version",$version);
	$merchantSignMsgVal=appendParam($merchantSignMsgVal,"language",$language);
	$merchantSignMsgVal=appendParam($merchantSignMsgVal,"signType",$signType);
	$merchantSignMsgVal=appendParam($merchantSignMsgVal,"payType",$payType);
	$merchantSignMsgVal=appendParam($merchantSignMsgVal,"bankId",$bankId);
	$merchantSignMsgVal=appendParam($merchantSignMsgVal,"orderId",$orderId);
	$merchantSignMsgVal=appendParam($merchantSignMsgVal,"orderTime",$orderTime);
	$merchantSignMsgVal=appendParam($merchantSignMsgVal,"orderAmount",$orderAmount);
	$merchantSignMsgVal=appendParam($merchantSignMsgVal,"dealId",$dealId);
	$merchantSignMsgVal=appendParam($merchantSignMsgVal,"bankDealId",$bankDealId);
	$merchantSignMsgVal=appendParam($merchantSignMsgVal,"dealTime",$dealTime);
	$merchantSignMsgVal=appendParam($merchantSignMsgVal,"payAmount",$payAmount);
	$merchantSignMsgVal=appendParam($merchantSignMsgVal,"fee",$fee);
	$merchantSignMsgVal=appendParam($merchantSignMsgVal,"ext1",$ext1);
	$merchantSignMsgVal=appendParam($merchantSignMsgVal,"ext2",$ext2);
	$merchantSignMsgVal=appendParam($merchantSignMsgVal,"payResult",$payResult);
	$merchantSignMsgVal=appendParam($merchantSignMsgVal,"errCode",$errCode);
	$merchantSignMsgVal=appendParam($merchantSignMsgVal,"key",$key);
	$merchantSignMsg= md5($merchantSignMsgVal);

	if( strtoupper($signMsg)!=strtoupper($merchantSignMsg) ){
		showerr( "��֤MD5ǩ��ʧ��"); 
	}

	if( $webdb[pay99_id] != $merchantAcctId ){
		showerr( "������̻����"); 
	}

	if($payResult != "10" ){
		showerr( "֧��ʧ��"); 
	}

	$rt = $db->get_one("SELECT * FROM {$pre}olpay WHERE numcode='$orderId'");
	if(!$rt){
		showerr('ϵͳ��û������֧���������޷���ɳ�ֵ��');
	}
	if($rt['ifpay'] == 1){
		showerr('�ö����Ѿ���ֵ�ɹ���');
	}
	$db->query("UPDATE {$pre}olpay SET ifpay='1' WHERE id='$rt[id]'");
	$db->query("UPDATE {$pre}shoporderuser SET ifpay='1' WHERE id='$rt[id]'");

	refreshto("../","��ϲ��֧���ɹ�",5);
}
?>