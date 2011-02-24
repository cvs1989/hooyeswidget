<?php
require(dirname(__FILE__)."/"."global.php");

isset($webdb[ShopEmsSend]) || $webdb[ShopEmsSend]=25;
isset($webdb[ShopOtherSend]) || $webdb[ShopOtherSend]=18;
isset($webdb[ShopNormalSend]) || $webdb[ShopNormalSend]=8;

$buyid=$_COOKIE['buyid'];

//ѡ�в�Ʒ
if($job=='buy')
{
	if( !strstr($buyid,",$id,") ){
		setcookie("buyid","$buyid,$id,");
	}
	echo "<META HTTP-EQUIV=REFRESH CONTENT='0;URL=?job=list'>";
	exit;
}

//�ύ
elseif($step=='updateNum')
{
	//��Ʒ��������
	$buyid='';
	foreach( $product AS $key=>$value){
		if($value>500){
			showerr('������Ʒ,���ܴ���500��');
		}
		for($i=0;$i<$value;$i++){
			$buyid.="$key,";
		}
	}
	setcookie("buyid",",$buyid");
	echo "<META HTTP-EQUIV=REFRESH CONTENT='0;URL=?job=list'>";
	exit;
}

//���ĳ����Ʒ
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


//����
elseif($action=='order')
{	
	if(!$buyid)
	{
		showerr("�벻Ҫ�ظ��ύ");
	}
	if(!ereg("[0-9]+",$buyid)){
		showerr("�㲢û�й����κ�һ����Ʒ");
	}
	if(!$buyer)
	{
		showerr("�˿���������Ϊ��");
	}
	elseif(!$mobphone)
	{
		showerr("��ϵ�ֻ����벻��Ϊ��");
	}
	if(!ereg("^1[0-9]{10}$",$mobphone)){
		showerr("�ֻ���������");
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
				//���������߻���
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
	if($sendType=='EMS���'){
		$totalmoney+=$webdb[ShopEmsSend];
	}elseif($sendType=='�������'){
		$totalmoney+=$webdb[ShopOtherSend];
	}elseif($sendType=='ƽ��'){
		$totalmoney+=$webdb[ShopNormalSend];
	}
	$db->query("UPDATE `{$pre}shoporderuser` SET `totalmoney`='$totalmoney' WHERE id='$orderid'");
	setcookie("buyid",'');

	if($payType=='����֧��'&&$totalmoney>0)
	{
		$pay_code=mymd5("module\t$totalmoney\t$orderid\t$rs[mid]\t$shopmoney");
		echo "<META HTTP-EQUIV=REFRESH CONTENT='0;URL=$webdb[www_url]/do/olpay.php?pay_code=$pay_code'>";
		exit;
	}
	echo '<meta http-equiv="Content-Type" content="text/html; charset=gb2312">';
	die("<CENTER>��ϲ��,�����ύ�ɹ�,���ͼ���Ķ�����,�����ѯ,��Ķ�������:<font color=red>$orderid</font><br><br><A HREF='$webdb[www_url]/'>���������ҳ</A></CENTER>");
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