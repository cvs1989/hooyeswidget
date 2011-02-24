<?php
require(dirname(__FILE__)."/"."global.php");

isset($webdb[ShopEmsSend]) || $webdb[ShopEmsSend]=25;
isset($webdb[ShopOtherSend]) || $webdb[ShopOtherSend]=18;
isset($webdb[ShopNormalSend]) || $webdb[ShopNormalSend]=8;

$buyid=$_COOKIE['buyid'];

//选中产品
if($job=='buy')
{
	if( !strstr($buyid,",$id,") ){
		setcookie("buyid","$buyid,$id,");
	}
	echo "<META HTTP-EQUIV=REFRESH CONTENT='0;URL=?job=list'>";
	exit;
}

//提交
elseif($step=='updateNum')
{
	//商品数量调整
	$buyid='';
	foreach( $product AS $key=>$value){
		if($value>500){
			showerr('单个商品,不能大于500个');
		}
		for($i=0;$i<$value;$i++){
			$buyid.="$key,";
		}
	}
	setcookie("buyid",",$buyid");
	echo "<META HTTP-EQUIV=REFRESH CONTENT='0;URL=?job=list'>";
	exit;
}

//清除某个商品
elseif($job=="del")
{
	$detail=explode(",",$buyid);
	foreach( $detail AS $key=>$value){
		if($value==$did){
			unset($detail[$key]);
		}
	}
	$buyid=implode(",",$detail);
	setcookie("buyid",",$buyid,");
	echo "<META HTTP-EQUIV=REFRESH CONTENT='0;URL=?job=list'>";
	exit;
}


//结帐
elseif($action=='order')
{	
	if(!$buyid)
	{
		showerr("请不要重复提交");
	}
	if(!ereg("[0-9]+",$buyid)){
		showerr("你并没有购买任何一件商品");
	}
	if(!$buyer)
	{
		showerr("顾客姓名不能为空");
	}
	elseif(!$mobphone)
	{
		showerr("联系手机号码不能为空");
	}
	if(!ereg("^1[0-9]{10}$",$mobphone)){
		showerr("手机号码有误");
	}
	$buyer=filtrate($buyer);
	$sex=filtrate($sex);
	$telphone=filtrate($telphone);
	$mobphone=filtrate($mobphone);
	$email=filtrate($email);
	$oicq=filtrate($oicq);
	$postalcode=filtrate($postalcode);
	$sendType=filtrate($sendType);
	$payType=filtrate($payType);
	$address=filtrate($address);
	$otherSay=filtrate($otherSay);
	$array=explode(",",$buyid);
	unset($orderid);
	$totalmoney=0;
	$shopmoney=0;
	foreach( $array AS $key=>$value){
		if(!is_numeric($value)){
			continue;
		}
		if(!$orderid){
			$db->query("INSERT INTO `{$pre}shoporderuser` (`uid` , `username` , `truename` , `sex` , `telphone` , `mobphone` , `email` , `oicq` , `postalcode` , `sendtype` , `paytype` ,  `olpaytype` , `address` , `othersay` , `posttime` ) 
				VALUES 
			('$lfjuid','$lfjid','$buyer','$sex','$telphone','$mobphone','$email','$oicq','$postalcode','$sendType','$payType','$olpaytype','$address','$otherSay','$timestamp')");
			@extract($db->get_one("SELECT id AS orderid FROM `{$pre}shoporderuser` ORDER BY id DESC LIMIT 1"));
		}
		
		$erp=get_id_table($value);
		$rs=$db->get_one("SELECT * FROM {$pre}article$erp WHERE aid=$value ");
		if($rs[mid]){
			$rss=$db->get_one("SELECT * FROM {$pre}article_content_{$rs[mid]} WHERE aid='$value' ");
			if($rss){
				$rs+=$rss;
				if($rss[shopnum]>0){
					$db->query("UPDATE `{$pre}article_content_{$rs[mid]}` SET `shopnum`=`shopnum`-1 WHERE aid='$value'");
				}
				//奖励购买者积分
				$shopmoney+=$rss[shopmoney];
			}
		}
		
		if($buydb[$value])
		{
			$_rs=$db->get_one("SELECT pid FROM {$pre}shoporderproduct WHERE `shopid`=$value ORDER BY pid DESC LIMIT 1");
			$db->query("UPDATE `{$pre}shoporderproduct` SET `amount`=`amount`+1 WHERE pid='$_rs[pid]'");
		}
		else
		{
			$db->query("INSERT INTO `{$pre}shoporderproduct` (`title`, `orderid`, `shopid`, `shopuid`, `ifsend`, `amount`) VALUES ('$rs[title]','$orderid','$value','$rs[uid]','0','1')");
		}
		
		$rs[nowprice]=str_replace(",","",$rs[nowprice]);
		$totalmoney+=$rs[nowprice];
		$buydb[$value]=1;
	}
	if($sendType=='EMS快递'){
		$totalmoney+=$webdb[ShopEmsSend];
	}elseif($sendType=='其他快递'){
		$totalmoney+=$webdb[ShopOtherSend];
	}elseif($sendType=='平邮'){
		$totalmoney+=$webdb[ShopNormalSend];
	}
	$db->query("UPDATE `{$pre}shoporderuser` SET `totalmoney`='$totalmoney' WHERE id='$orderid'");
	setcookie("buyid",'');

	if($payType=='在线支付'&&$totalmoney>0)
	{
		$pay_code=mymd5("module\t$totalmoney\t$orderid\t$rs[mid]\t$shopmoney");
		echo "<META HTTP-EQUIV=REFRESH CONTENT='0;URL=$webdb[www_url]/do/olpay.php?pay_code=$pay_code'>";
		exit;
	}
	echo '<meta http-equiv="Content-Type" content="text/html; charset=gb2312">';
	die("<CENTER>恭喜你,订单提交成功,请劳记你的订单号,方便查询,你的订单号是:<font color=red>$orderid</font><br><br><A HREF='$webdb[www_url]/'>点击返回首页</A></CENTER>");
}


$detail=explode(",",$buyid);
foreach( $detail AS $key=>$value){
	if(!is_numeric($value)){
		unset($detail[$key]);
	}
	$numdb[$value]++;
}
$fids=implode(",",$detail);
$totalmoney=0;
if($fids){
	$query = $db->query("SELECT A.*,D.aid FROM {$pre}article_db D LEFT JOIN {$pre}article A ON D.aid=A.aid WHERE D.aid IN ($fids)");
	while($rs = $db->fetch_array($query)){
		if(!$rs[title]&&$_rs=get_one_article($rs[aid])){
			$rs=$_rs+$rs;
		}
		if($rs[mid]){
			$rss=$db->get_one("SELECT * FROM {$pre}article_content_{$rs[mid]} WHERE aid=$rs[aid] ");
			if($rss){
				$rs+=$rss;
			}
		}
		$rs[num]=$numdb[$rs[aid]];
		$rs[nowprice]=str_replace(",","",$rs[nowprice]);
		$rs[totalprice]=$numdb[$rs[aid]]*$rs[nowprice];
		$totalmoney+=$rs[totalprice];
		$listdb[$rs[aid]]=$rs;
	}
}

require(PHP168_PATH."inc/head.php");
require(html("buy"));
require(PHP168_PATH."inc/foot.php");

?>