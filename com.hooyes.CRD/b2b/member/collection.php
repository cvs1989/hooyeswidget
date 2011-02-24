<?php
require(dirname(__FILE__)."/"."global.php");
require(PHP168_PATH."inc/artic_function.php");

if(!$lfjid){
	showerr("你还没登录");
}
if($do=='del'){
	$db->query("DELETE FROM {$pre}collection WHERE id='$id' AND uid='$lfjuid'");
}
if($page<1){
	$page=1;
}
$rows=20;
$min=($page-1)*$rows;


$showpage=getpage("{$pre}collection B LEFT JOIN {$pre}article A ON A.aid=B.aid","WHERE B.uid=$lfjuid","?job=$job",$rows);

$query = $db->query("SELECT A.*,B.id,B.aid,B.posttime AS ctime FROM {$pre}collection B LEFT JOIN {$pre}article A ON A.aid=B.aid WHERE B.uid=$lfjuid ORDER BY B.id DESC LIMIT $min,$rows");
while($rs = $db->fetch_array($query)){
	if(!$rs[title]&&$_rs=get_one_article($rs[aid])){
		$rs=$_rs+$rs;
	}
	if($rs[yz]==2){
		$rs[state]="<A style='color:red;'>作废</A>";
	}elseif($rs[yz]==1){
		$rs[state]="已审";
	}elseif(!$rs[yz]){
		$rs[state]="<A style='color:blue;'>待审</A>";
	}
	if($rs[levels]){
		$rs[levels]="<A style='color:red;'>已推荐</A>";
	}else{
		$rs[levels]="未推荐";
	}
	$rs[ctime]=date("Y-m-d",$rs[ctime]);
	$rs[title]=get_word($rs[full_title]=$rs[title],54);
	$listdb[]=$rs;
}

require(dirname(__FILE__)."/"."head.php");
require(dirname(__FILE__)."/"."template/collection.htm");
require(dirname(__FILE__)."/"."foot.php");
?>