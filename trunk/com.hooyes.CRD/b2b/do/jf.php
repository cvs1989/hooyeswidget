<?php
require(dirname(__FILE__)."/"."global.php");

if($action=='money2card')
{
	if(!$lfjuid){
		showerr("请先登录");
	}
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


$lfjdb && $lfjdb[money]=get_money($lfjdb[uid]);

$query = $db->query("SELECT * FROM {$pre}jfsort ORDER BY list");
while($rs = $db->fetch_array($query)){
	$fnameDB[$rs[fid]]=$rs[name];
	$query2 = $db->query("SELECT * FROM {$pre}jfabout WHERE fid='$rs[fid]' ORDER BY list");
	while($rs2 = $db->fetch_array($query2)){
		eval("\$rs2[title]=\"$rs2[title]\";");
		eval("\$rs2[content]=\"$rs2[content]\";");
		$jfDB[$rs[fid]][]=$rs2;
	}
}

require(PHP168_PATH."inc/head.php");
require(html("jf"));
require(PHP168_PATH."inc/foot.php");

?>