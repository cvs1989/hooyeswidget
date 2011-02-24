<?php
require("global.php");

if(!$lfjuid){
	showerr("请先在前台登录");
}

if($action=='get')
{
	if(!$atc_passwd){
		showerr("请输入充值卡密码");
	}
	$rsdb=$db->get_one("SELECT * FROM {$pre}moneycard WHERE passwd='$atc_passwd'");
	if(!$rsdb){
		showerr("充值卡密码不对");
	}elseif($rsdb[ifsell]){
		showerr("本充值卡已使用过,请不要重复充值");
	}
	$db->query("UPDATE {$pre}moneycard SET ifsell='1',uid='$lfjuid',username='$lfjid',posttime='$timestamp' WHERE id='$rsdb[id]'");
	$db->query("UPDATE {$pre}memberdata SET moneycard=moneycard+'$rsdb[moneycard]' WHERE uid='$lfjuid'");
	refreshto("?","恭喜你,充值成功",2);
}

require(PHP168_PATH."inc/head.php");
require(html("moneycard"));
require(PHP168_PATH."inc/foot.php");

?>