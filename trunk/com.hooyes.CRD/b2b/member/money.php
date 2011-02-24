<?php
require("global.php");

if(!$lfjid){
	showerr("你还没登录");
}

$lfjdb[money]=get_money($lfjuid);
$webdb[MoneyRatio]=intval($webdb[MoneyRatio]);



if($action=='money2card')
{
	if(!is_numeric($atc_moneycard))
	{
		showerr("请输入一个正整数");
	}

	$atc_moneycard=intval($atc_moneycard);

	if($atc_moneycard<1)
	{
		showerr("你输入的数字必须大于0");
	}
	elseif($atc_moneycard>$lfjdb[moneycard])
	{
		showerr("你输入的数值不能大于你本身的金币数");
	}
	$db->query("UPDATE {$pre}memberdata SET moneycard=moneycard-'$atc_moneycard' WHERE uid='$lfjuid'");
	add_user($lfjuid,$atc_moneycard*$webdb[MoneyRatio]);
	refreshto("$FROMURL","恭喜你,兑换成功",1);
}
elseif($action=='card2money')
{
	if(!$webdb[Money2card])
	{
		showerr("管理员关闭了{$webdb[MoneyName]}兑换金币功能");
	}
	$lfjdb[money]=get_money($lfjuid);
	if(!is_numeric($atc_money))
	{
		showerr("请输入一个正整数");
	}

	$atc_money=intval($atc_money);

	if($atc_money<1)
	{
		showerr("你输入的数字必须大于0");
	}
	elseif($atc_money>$lfjdb[money])
	{
		showerr("你输入的数值不能大于你本身的{$webdb[MoneyName]}");
	}
	$moneycard=floor($atc_money/$webdb[MoneyRatio]);
	if($moneycard<1){
		showerr("你必须输入兑换一个金币以上的{$webdb[MoneyName]}");
	}
	$db->query("UPDATE {$pre}memberdata SET moneycard=moneycard+'$moneycard' WHERE uid='$lfjuid'");
	add_user($lfjuid,-$atc_money);
	refreshto("$FROMURL","恭喜你,兑换成功",1);
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
		$rs[ifpay]=$rs[ifpay]?"<font color=red>支付成功</font>":"支付失败";
		$rs[posttime]=date("Y-m-d H:i:s",$rs[posttime]);
		if($rs[banktype]=='tenpay'){
			$rs[banktype]="财付通";
		}elseif($rs[banktype]=='alipay'){
			$rs[banktype]="支付宝";
		}elseif($rs[banktype]=='99pay'){
			$rs[banktype]="快钱";
		}elseif($rs[banktype]=='yeepay'){
			$rs[banktype]="易宝支付";
		}else{
			$rs[banktype]="其它方式";
		}
		$listdb[]=$rs;
	}
}

require(dirname(__FILE__)."/"."head.php");
require(dirname(__FILE__)."/"."template/money.htm");
require(dirname(__FILE__)."/"."foot.php");

?>