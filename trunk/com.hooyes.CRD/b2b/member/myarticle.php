<?php
require(dirname(__FILE__)."/"."global.php");
require_once(PHP168_PATH."inc/artic_function.php");

if(!$lfjid){
	showerr("你还没登录");
}

//删除文章
if($do=='del'){
	$erp=get_id_table($id);
	$rs=$db->get_one("SELECT * FROM {$pre}article$erp WHERE aid='$id' AND uid='$lfjuid' ");
	if(!$rs){
		showerr("文章不存在");
	}
	delete_article($id,$rid);
	//静态页处理
	make_article_html("$FROMURL",'del',$rs);
	refreshto("$FROMURL","删除成功",1);
}

if($page<1){
	$page=1;
}
$rows=20;
$min=($page-1)*$rows;

$_sql="";
if($fid>0){
	$_sql=" AND fid='$fid' ";
	$erp=$Fid_db[iftable][$fid];
}elseif($mid>0){
	$_sql=" AND mid='$mid' ";
	$erp=$article_moduleDB[$mid][iftable]?$article_moduleDB[$mid][iftable]:'';
}elseif($mid==-1){
	$_sql=" AND mid='0' ";
}
if($only){
	$_sql.=" AND mid='$mid' ";
}



$SQL="WHERE uid=$lfjuid AND yz!=2 $_sql ORDER BY aid DESC LIMIT $min,$rows";
$which='*';
$showpage=getpage("{$pre}article$erp","WHERE uid=$lfjuid AND yz!=2 $_sql","?job=$job&fid=$fid&mid=$mid&only=$only",$rows);
$listdb=list_article($SQL,$which,50,$erp);
$listdb || $listdb=array();
foreach( $listdb AS $key=>$rs){
	if($rs[pages]<1){
		$rs[pages]=1;
		$erp=get_id_table($rs[aid]);
		$db->query("UPDATE {$pre}article$erp SET pages=1 WHERE aid='$rs[aid]'");
	}
	$rs[state]="";
	if($rs[yz]==2){
		$rs[state]="作废";
	}elseif($rs[yz]==1){
		$rs[state]="<A style='color:red;'>已审</A>";
	}elseif(!$rs[yz]){
		$rs[state]="<A style='color:blue;'>待审</A>";
	}
	if($rs[levels]){
		$rs[levels]="<A style='color:red;'>已推荐</A>";
	}else{
		$rs[levels]="未推荐";
	}
	
	$listdb[$key]=$rs;
}
$_MSG='我的稿件';
require(dirname(__FILE__)."/"."head.php");
require(dirname(__FILE__)."/"."template/myarticle.htm");
require(dirname(__FILE__)."/"."foot.php");
?>