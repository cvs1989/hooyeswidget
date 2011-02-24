<?php
require_once("global.php");
$linkdb=array("供求评论"=>"?");
//$linkdb=array("供求评论"=>"?","资讯评论"=>"?tb=news");
	if($tb=='news'){
	 $table=" `{$_pre}newscomments`";$tbname="资讯评论";
	
	 }else{
	  $table="`{$_pre}comments`";
	    $tbname="供求信息评论";
	 }
	
if($action=='del'){
	$db->query("delete from  $table  where cid='$cid' ");
	refreshto("$FROMURL","操作成功",1);
	
}elseif($action=='yz'){
	$rt=$db->get_one("select * from $table where cid='$cid'");
	$db->query("update $table set yz='".($rt[yz]?0:1)."' where cid='$cid' ");
	refreshto("$FROMURL","操作成功",1);
}
if(!$job){

	$rows=20;
	$page<1 && $page=1;
	$min=($page-1)*$rows;
	$where=" where 1";
	if($yz!='') $where.=" and `yz`='$yz'";
	//if($keyword) $where.=" and `title` content('%$keyword%') ";
	$query = $db->query("SELECT *  from  {$table} $where order by posttime desc,id desc LIMIT $min,$rows");
	
	while($rs = $db->fetch_array($query)){
		$rs[posttime]=date("m/d H:i",$rs[posttime]);
		$rs[yz]=$rs[yz]?"已审核":"<font color=red>未审核</font>";
		$rs[content]=get_word($rs[content],100);
		if($tb=='news') $rs[url]="../news.php?id=$rs[id]&fid=$rs[fid]";
		else $rs[url]="../bencandy.php?id=$rs[id]&fid=$rs[fid]";
		$listdb[]=$rs;
	}
	$showpage=getpage($table,$where,"?tb=$tb&yz=$yz",$rows);

}elseif($job=="view"){

	$rs=$db->get_one("select * from $table where cid='$cid'");
	$rs[posttime]=date("m/d H:i",$rs[posttime]);
	$rs[yz]=$rs[yz]?"已审核":"<font color=red>未审核</font>";
	if($tb=='news') $rs[url]="../news.php?id=$rs[id]&fid=$rs[fid]";
	else $rs[url]="../bencandy.php?id=$rs[id]&fid=$rs[fid]";
		
}

	require("head.php");
	require("template/comments/list.htm");
	require("foot.php");

?>