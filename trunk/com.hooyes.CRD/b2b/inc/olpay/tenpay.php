<?php
!function_exists('html') && exit('ERR');

if(!$webdb[tenpay_id]){
	showerr('ϵͳû�����òƸ�ͨ�տ��ʺ�,���Բ���ʹ�òƸ�ͨ����֧��');
}elseif(!$webdb[tenpay_key]){
	showerr('ϵͳû�����òƸ�ͨ��Կ,���Բ���ʹ�òƸ�ͨ����֧��');
}

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
	
	olpay_end($sp_billno);
}
else
{
	$array=olpay_send();
  
	/*�����滻Ϊ����ʵ���̻���*/
	$strSpid    = $webdb[tenpay_id]; 
	/*strSpkey��32λ�̻���Կ, ���滻Ϊ����ʵ����Կ*/
	$strSpkey   = $webdb[tenpay_key]; 
	/*�Ƹ�֧ͨ��Ϊ"1" (��ǰֻ֧�� cmdno=1)*/
	$strCmdNo   = "1";
	/*�������� (yyyymmdd)*/
	$strBillDate= date('Ymd');  
	/*��������:	
      0		  �Ƹ�ͨ
  		1001	��������   
  		1002	�й���������  
  		1003	�й���������  
  		1004	�Ϻ��ֶ���չ����   
  		1005	�й�ũҵ����  
  		1006	�й���������  
  		1008	���ڷ�չ����   
  		1009	��ҵ����   */
	$strBankType= "0";  
	/*��Ʒ����*/
	$strDesc    = $array[title]; 		
	/*�û�QQ����, ������Ϊ�մ�*/
	$strBuyerId = "";
	/*�̻���*/	
	$strSaler   = $strSpid;				
	/*�̻����ɵĶ�����(���10λ)*/	
	$strSpBillNo = $array[numcode];
	/*��Ҫ: ���׵���
	  ���׵���(28λ): �̻���(10λ) + ����(8λ) + ��ˮ��(10λ), ���밴�˸�ʽ����, �Ҳ����ظ�
	  ���sp_billno����10λ, ���ȡ���е���ˮ�Ų��ּӵ�transaction_id��(����10λ��0)
	  ���sp_billno����10λ, ����0, �ӵ�transaction_id��*/
	$strTransactionId = $strSpid . $strBillDate . $strSpBillNo;
	/*�ܽ��, ��Ϊ��λ*/
	$strTotalFee = $array[money]*100;
	/*��������: 1 �C RMB(�����) 2 - USD(��Ԫ) 3 - HKD(�۱�)*/
	$strFeeType  = "1";
	/*�Ƹ�ͨ�ص�ҳ���ַ, �Ƽ�ʹ��ip��ַ�ķ�ʽ(�255���ַ�)*/
	$strRetUrl  = $array[return_url];
	$strRetUrl  = urlencode($strRetUrl);
	/*�̻�˽������, ����ص�ҳ��ʱԭ������*/
	$strAttach  = "my_magic_string";
	/*����MD5ǩ��*/
	$strSignText = "cmdno=" . $strCmdNo . "&date=" . $strBillDate . "&bargainor_id=" . $strSaler .
	      "&transaction_id=" . $strTransactionId . "&sp_billno=" . $strSpBillNo .        
	      "&total_fee=" . $strTotalFee . "&fee_type=" . $strFeeType . "&return_url=" . $strRetUrl .
	      "&attach=" . $strAttach . "&key=" . $strSpkey;
	$strSign = strtoupper(md5($strSignText));
  
	/*����֧����*/
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