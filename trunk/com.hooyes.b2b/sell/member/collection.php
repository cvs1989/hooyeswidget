<?php
require("global.php");


if($do=='del'){
	$db->query("DELETE FROM {$_pre}collection WHERE cid='$cid' AND uid='$lfjuid'");
}
if($page<1){
	$page=1;
}
$rows=20;
$min=($page-1)*$rows;

$showpage=getpage(" {$_pre}collection B ","WHERE B.uid=$lfjuid","?job=$job",$rows);
$listdb=array();
$query = $db->query("SELECT A.*,B.id,B.cid,B.posttime AS ctime FROM {$_pre}content A LEFT JOIN {$_pre}collection B ON A.id=B.id WHERE B.uid=$lfjuid ORDER BY B.cid DESC LIMIT $min,$rows");
while($rs = $db->fetch_array($query)){

	if($rs[yz]==2){
		$rs[state]="<A style='color:red;'>����</A>";
	}elseif($rs[yz]==1){
		$rs[state]="����";
	}elseif(!$rs[yz]){
		$rs[state]="<A style='color:blue;'>����</A>";
	}
	if($rs[levels]){
		$rs[levels]="<A style='color:red;'>���Ƽ�</A>";
	}else{
		$rs[levels]="δ�Ƽ�";
	}
	$rs[ctime]=date("Y-m-d",$rs[ctime]);
	$rs[title]=get_word($rs[full_title]=$rs[title],54);
	$listdb[]=$rs;
}

require(ROOT_PATH."member/head.php");
require(dirname(__FILE__)."/"."template/collection.htm");
require(ROOT_PATH."member/foot.php");
?>