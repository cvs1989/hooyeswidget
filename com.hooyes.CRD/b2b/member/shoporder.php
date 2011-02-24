<?php
require("global.php");

if(!$lfjid){
	showerr("你还没登录");
}


if($action=="del_record"){
	$rsdb=$db->get_one("SELECT * FROM {$pre}shoporderuser WHERE uid='$lfjuid' AND id='$id'");
	if(!$rsdb){
		showerr("你无权限!");
	}
	$db->query("DELETE FROM {$pre}shoporderuser WHERE uid='$lfjuid' AND id='$id'");
	$db->query("DELETE FROM {$pre}shoporderproduct WHERE orderid='$id'");
}
if($job=='view'){
	$rsdb=$db->get_one("SELECT * FROM {$pre}shoporderuser WHERE uid='$lfjuid' AND id='$id'");
	if(!$rsdb){
		showerr("你无权限!");
	}
	$rsdb[posttime]=date("Y-m-d H:i:s",$rsdb[posttime]);
	$rsdb[ifpay]=$rsdb[ifpay]?"<font color=red>已支付</font>":"未支付";
	$rsdb[ifsend]=$rsdb[ifsend]?"<font color=red>已发货</font>":"未发货";
	$rsdb[Id]=$rsdb[id];
	while( strlen($rsdb[Id])<10 ){
		$rsdb[Id]="0$rsdb[Id]";
	}
	$query = $db->query("SELECT A.*,B.amount FROM {$pre}shoporderproduct B LEFT JOIN {$pre}article A ON B.shopid=A.aid WHERE B.orderid='$id'");
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
	}
}else{
	if($page<1){
		$page=1;
	}
	$rows=20;
	$min=($page-1)*$rows;
	unset($listdb);
	$showpage=getpage("{$pre}shoporderuser","WHERE uid='$lfjuid'","?job=$job",$rows);
	$query = $db->query("SELECT * FROM {$pre}shoporderuser WHERE uid='$lfjuid' ORDER BY id DESC LIMIT $min,$rows");
	while($rs = $db->fetch_array($query)){
		$rs[ifpay]=$rs[ifpay]?"<font color=red>已支付</font>":"未支付";
		$rs[ifsend]=$rs[ifsend]?"<font color=red>已发货</font>":"未发货";
		$rs[posttime]=date("Y-m-d H:i:s",$rs[posttime]);
		$rs[Id]=$rs[id];
		while( strlen($rs[Id])<10 ){
			$rs[Id]="0$rs[Id]";
		}
		$listdb[]=$rs;
	}	
}

require(dirname(__FILE__)."/"."head.php");
require(dirname(__FILE__)."/"."template/shoporder.htm");
require(dirname(__FILE__)."/"."foot.php");
?>