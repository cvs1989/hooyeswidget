<?php
require("global.php");

if(!$lfjid){
	showerr("�㻹û��¼");
}

$lfjdb[money]=get_money($lfjuid);
$webdb[MoneyRatio]=intval($webdb[MoneyRatio]);



if($action=='money2card')
{
	if(!is_numeric($atc_moneycard))
	{
		showerr("������һ��������");
	}

	$atc_moneycard=intval($atc_moneycard);

	if($atc_moneycard<1)
	{
		showerr("����������ֱ������0");
	}
	elseif($atc_moneycard>$lfjdb[moneycard])
	{
		showerr("���������ֵ���ܴ����㱾��Ľ����");
	}
	$db->query("UPDATE {$pre}memberdata SET moneycard=moneycard-'$atc_moneycard' WHERE uid='$lfjuid'");
	add_user($lfjuid,$atc_moneycard*$webdb[MoneyRatio]);
	refreshto("$FROMURL","��ϲ��,�һ��ɹ�",1);
}
elseif($action=='card2money')
{
	if(!$webdb[Money2card])
	{
		showerr("����Ա�ر���{$webdb[MoneyName]}�һ���ҹ���");
	}
	$lfjdb[money]=get_money($lfjuid);
	if(!is_numeric($atc_money))
	{
		showerr("������һ��������");
	}

	$atc_money=intval($atc_money);

	if($atc_money<1)
	{
		showerr("����������ֱ������0");
	}
	elseif($atc_money>$lfjdb[money])
	{
		showerr("���������ֵ���ܴ����㱾���{$webdb[MoneyName]}");
	}
	$moneycard=floor($atc_money/$webdb[MoneyRatio]);
	if($moneycard<1){
		showerr("���������һ�һ��������ϵ�{$webdb[MoneyName]}");
	}
	$db->query("UPDATE {$pre}memberdata SET moneycard=moneycard+'$moneycard' WHERE uid='$lfjuid'");
	add_user($lfjuid,-$atc_money);
	refreshto("$FROMURL","��ϲ��,�һ��ɹ�",1);
}

if($action=="del_record"){
	$db->query("DELETE FROM {$pre}olpay WHERE uid='$lfjuid' AND id='$id'");
}
if($job=="record")
{
	if($page<1){
		$page=1;
	}
	$rows=20;
	$min=($page-1)*$rows;
	unset($listdb);
	$showpage=getpage("{$pre}olpay","WHERE uid='$lfjuid'","?job=$job",$rows);
	$query = $db->query("SELECT * FROM {$pre}olpay WHERE uid='$lfjuid' ORDER BY id DESC LIMIT $min,$rows");
	while($rs = $db->fetch_array($query)){
		$rs[ifpay]=$rs[ifpay]?"<font color=red>֧���ɹ�</font>":"֧��ʧ��";
		$rs[posttime]=date("Y-m-d H:i:s",$rs[posttime]);
		if($rs[banktype]=='tenpay'){
			$rs[banktype]="�Ƹ�ͨ";
		}elseif($rs[banktype]=='alipay'){
			$rs[banktype]="֧����";
		}elseif($rs[banktype]=='99pay'){
			$rs[banktype]="��Ǯ";
		}elseif($rs[banktype]=='yeepay'){
			$rs[banktype]="�ױ�֧��";
		}else{
			$rs[banktype]="������ʽ";
		}
		$listdb[]=$rs;
	}
}

require(dirname(__FILE__)."/"."head.php");
require(dirname(__FILE__)."/"."template/money.htm");
require(dirname(__FILE__)."/"."foot.php");

?>