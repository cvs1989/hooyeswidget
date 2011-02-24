<?php
!function_exists('html') && exit('ERR');

if($job=="list"&&$Apower[shoporder])
{
	$SQL=" 1 ";
	if($type=="id"){
		$SQL.=" AND id='".intval($keyword)."' ";
	}
	elseif($type=="buyer"){
		$SQL.=" AND binary truename LIKE '%$keyword%' ";
	}
	$rows=20;
	if($page<1){
		$page=1;
	}
	$min=($page-1)*$rows;

	$showpage=getpage("{$pre}shoporderuser"," WHERE $SQL","?job=$job","");

	$query=$db->query("SELECT * FROM {$pre}shoporderuser WHERE $SQL ORDER BY id DESC LIMIT $min,$rows");
	while($rs=$db->fetch_array($query))
	{
		$rs[ifsend]=$rs[ifsend]?"<A HREF='?lfj=$lfj&action=send&pid=$rs[id]&jobs=0' style='color:red;'>已发货</A>":"<A HREF='?lfj=$lfj&action=send&pid=$rs[id]&jobs=1' style='color:blue;'>未发货</A>";
		$_rs=$db->get_one("SELECT * FROM {$pre}olpay WHERE orderid='$rs[id]'");
		
		if($rs[ifpay]){
			$rs[ifpay]="<A HREF='?lfj=$lfj&action=setpay&v=0&id=$rs[id]'><font color=red>已支付</font></A>";
		}elseif($_rs){
			$rs[ifpay]="<A HREF='?lfj=$lfj&action=setpay&v=1&id=$rs[id]'><font color=blue>支付失败</font></A>";
		}else{
			$rs[ifpay]="<A HREF='?lfj=$lfj&action=setpay&v=1&id=$rs[id]'>等待确认</A>";
		}
		$rs[posttime]=date("Y-m-d H:i:s",$rs[posttime]);
		$rs[Id]=$rs[id];
		while( strlen($rs[Id])<10 ){
			$rs[Id]="0$rs[Id]";
		}
		$listdb[$rs[id]]=$rs;
	}
	require(dirname(__FILE__)."/"."template/head.htm");
	require(dirname(__FILE__)."/"."template/shoporder/menu.htm");
	require(dirname(__FILE__)."/"."template/shoporder/list.htm");
	require(dirname(__FILE__)."/"."template/foot.htm");
}
elseif($action=="send"&&$Apower[shoporder])
{
	$db->query("UPDATE {$pre}shoporderuser SET ifsend='$jobs' WHERE id='$pid' ");
	header("location:$FROMURL");
	exit;
}
elseif($action=="del"&&$Apower[shoporder])
{
	$db->query("DELETE FROM `{$pre}shoporderuser` WHERE id='$id'");
	$db->query("DELETE FROM `{$pre}shoporderproduct` WHERE orderid='$id'");	
	$db->query("DELETE FROM `{$pre}olpay` WHERE orderid='$id'");
	header("location:$FROMURL");
	exit;
}
elseif($job=="view"&&$Apower[shoporder])
{
	$rsdb=$db->get_one("SELECT * FROM {$pre}shoporderuser WHERE id='$orderid'");
	if(!$rsdb){
		header("location:?lfj=$lfj&job=list");
	}
	$totalmoney=0;
	$query = $db->query("SELECT A.*,B.* FROM {$pre}shoporderproduct A LEFT JOIN {$pre}article B ON A.shopid=B.aid WHERE A.orderid='$orderid' ");
	while($rs = $db->fetch_array($query)){
		if(!$rs[title]&&$_rs=get_one_article($rs[shopid])){
			$rs=$_rs+$rs;
		}
		if($rs[mid]){
			$rss=$db->get_one("SELECT * FROM {$pre}article_content_{$rs[mid]} WHERE aid=$rs[aid] ");
			if($rss){
				$rs+=$rss;
			}
		}
		$listdb[]=$rs;
		$totalmoney+=$rs[nowprice];
	}
	if($rsdb[sendtype]=='EMS快递'){
		$totalmoney+=$webdb[Shop_emsSend];
	}elseif($rsdb[sendtype]=='其他快递'){
		$totalmoney+=$webdb[Shop_otherSend];
	}elseif($rsdb[sendtype]=='平邮'){
		$totalmoney+=$webdb[Shop_normalSend];
	}
	$_rs=$db->get_one("SELECT * FROM {$pre}olpay WHERE orderid='$orderid'");
	if($_rs[ifpay]){
		$totalmoney=$_rs[money];
	}
	if($_rs[ifpay]||$rsdb[ifpay]){
		$rsdb[ifpay]='(<font color=red>已支付</font>)';
	}elseif($_rs){
		$rsdb[ifpay]='(<font color=blue>支付失败</font>)';
	}else{
		$rsdb[ifpay]='(<font color=blue>等待确认</font>)';
	}
	require(dirname(__FILE__)."/"."template/head.htm");
	require(dirname(__FILE__)."/"."template/shoporder/menu.htm");
	require(dirname(__FILE__)."/"."template/shoporder/show.htm");
	require(dirname(__FILE__)."/"."template/foot.htm");
}
elseif($action=="setpay"&&$Apower[shoporder])
{
	$db->query("UPDATE `{$pre}shoporderuser` SET `ifpay`='$v' WHERE id='$id'");
	$db->query("UPDATE `{$pre}olpay` SET `ifpay`='$v' WHERE orderid='$id'");
	refreshto("$FROMURL","修改成功",0);
}
elseif($job=='set'&&$Apower[shoporder])
{
	require(dirname(__FILE__)."/"."template/head.htm");
	require(dirname(__FILE__)."/"."template/shoporder/menu.htm");
	require(dirname(__FILE__)."/"."template/shoporder/set.htm");
	require(dirname(__FILE__)."/"."template/foot.htm");
}
elseif($action=="set"&&$Apower[shoporder])
{
	write_config_cache($webdbs);
	jump("修改成功",$FROMURL);
}

?>