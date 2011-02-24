<?php
require(dirname(__FILE__)."/"."global.php");

if(!$lfjid){
	showerr("你还没登录");
}
$ctype=$ctype?$ctype:1;
if($do=='del'){
	$db->query("DELETE FROM {$_pre}collection WHERE cid='$cid' AND uid='$lfjuid'");
}
if($page<1){
	$page=1;
}
$rows=20;
$min=($page-1)*$rows;

if($ctype==1){
	$showpage=getpage("{$_pre}collection A left join `{$_pre}content_sell` B on B.id=A.id","WHERE A.uid=$lfjuid  and A.`ctype`='$ctype'","?job=$job&ctype=$ctype",$rows);
	$query = $db->query("SELECT A.*,B.title,B.id,B.fid from {$_pre}collection A left join `{$_pre}content_sell` B on B.id=A.id WHERE A.uid='$lfjuid'  and A.`ctype`='$ctype' ORDER BY A.cid DESC LIMIT $min,$rows");
}else if($ctype==2){
	$showpage=getpage("{$_pre}collection A left join `{$_pre}content_buy` B on B.id=A.id","WHERE A.uid=$lfjuid  and A.`ctype`='$ctype'","?job=$job&ctype=$ctype",$rows);
	$query = $db->query("SELECT A.*,B.title,B.id,B.fid from {$_pre}collection A left join `{$_pre}content_buy` B on B.id=A.id WHERE A.uid='$lfjuid'  and A.`ctype`='$ctype' ORDER BY A.cid DESC LIMIT $min,$rows");
}else if($ctype==3){
	$showpage=getpage("{$_pre}collection A left join `{$_pre}company` C on C.rid=A.id","WHERE A.uid=$lfjuid and A.`ctype`='$ctype'","?job=$job&ctype=$ctype",$rows);
	$query = $db->query("SELECT A.*,C.title,C.uid from {$_pre}collection A left join `{$_pre}company` C on C.rid=A.id WHERE A.uid='$lfjuid'  and A.`ctype`='$ctype' ORDER BY A.cid DESC LIMIT $min,$rows");
}

while($rs = $db->fetch_array($query)){
	$listdb[]=$rs;
}
$listdb || $listdb=array();
foreach( $listdb AS $key=>$rs){
	
	$rs[posttime]=date("Y-m-d H:i",$rs[posttime]);
	$rs[title]=get_word($rs[full_title]=$rs[title],54);
	
	if($ctype == 1){
		$rs[ctype]="供应信息";
		$rs[url]="$Mdomain/sell_bencandy.php?fid=$rs[fid]&id=$rs[id]";
	}else if($ctype == 2){
		$rs[ctype]="求购信息";
		$rs[url]="$Mdomain/buy_bencandy.php?fid=$rs[fid]&id=$rs[id]";
	}else if($ctype==3){
		$rs[ctype]="商家";
		$rs[url]="$Mdomain/homepage.php?uid=$rs[uid]";
	}

	$listdb[$key]=$rs;
}

require(dirname(__FILE__)."/"."head.php");
require(dirname(__FILE__)."/"."template/collection.htm");
require(dirname(__FILE__)."/"."foot.php");
?>