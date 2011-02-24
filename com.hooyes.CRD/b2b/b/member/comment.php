<?php
require(dirname(__FILE__)."/"."global.php");
if($job=='list'){
	if(!$page){
		$page=1;
	}
	$rows=20;
	$min=($page-1)*$rows;
	$query = $db->query("SELECT * FROM {$_pre}comments WHERE cuid='$lfjuid' ORDER BY cid DESC LIMIT $min,$rows");
	while($rs = $db->fetch_array($query)){
		$rs[title]=get_word($rs[content],40);
		if(!$rs[username]){
			$detail=explode(".",$rs[ip]);
			$rs[username]="$detail[0].$detail[1].$detail[2].*";
		}
		$rs[posttime]=date("Y-m-d H:i",$rs[posttime]);
		$listdb[]=$rs;
	}
	$showpage=getpage("{$_pre}comments","WHERE cuid='$lfjuid'","?job=$job",$rows);
	require(dirname(__FILE__)."/"."head.php");
	require(dirname(__FILE__)."/"."template/comment/list.htm");
	require(dirname(__FILE__)."/"."foot.php");
}
elseif($job=='mylist'){
	if(!$page){
		$page=1;
	}
	$rows=20;
	$min=($page-1)*$rows;
	$query = $db->query("SELECT * FROM {$_pre}comments WHERE uid='$lfjuid' ORDER BY cid DESC LIMIT $min,$rows");
	while($rs = $db->fetch_array($query)){
		$rs[title]=get_word($rs[content],40);
		if(!$rs[username]){
			$detail=explode(".",$rs[ip]);
			$rs[username]="$detail[0].$detail[1].$detail[2].*";
		}
		$rs[posttime]=date("Y-m-d H:i",$rs[posttime]);
		$listdb[]=$rs;
	}
	$showpage=getpage("{$_pre}comments","WHERE uid='$lfjuid'","?job=$job",$rows);
	require(dirname(__FILE__)."/"."head.php");
	require(dirname(__FILE__)."/"."template/comment/mylist.htm");
	require(dirname(__FILE__)."/"."foot.php");
}
elseif($action=="del")
{
	if(!$ciddb){
		showerr("请选择一个");
	}
	foreach( $ciddb AS $key=>$value){
		$db->query("DELETE FROM {$_pre}comments WHERE cid='$value' AND (cuid='$lfjuid' OR uid='$lfjuid')");
	}
	refreshto("$FROMURL","删除成功",1);
}
?>