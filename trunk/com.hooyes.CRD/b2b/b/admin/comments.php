<?php
require_once("global.php");
$linkdb=array("��������"=>"?");
//$linkdb=array("��������"=>"?","��Ѷ����"=>"?tb=news");
	if($tb=='news'){
	 $table=" `{$_pre}newscomments`";$tbname="��Ѷ����";
	
	 }else{
	  $table="`{$_pre}comments`";
	    $tbname="������Ϣ����";
	 }
	
if($action=='del'){
	$db->query("delete from  $table  where cid='$cid' ");
	refreshto("$FROMURL","�����ɹ�",1);
	
}elseif($action=='yz'){
	$rt=$db->get_one("select * from $table where cid='$cid'");
	$db->query("update $table set yz='".($rt[yz]?0:1)."' where cid='$cid' ");
	refreshto("$FROMURL","�����ɹ�",1);
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
		$rs[yz]=$rs[yz]?"�����":"<font color=red>δ���</font>";
		$rs[content]=get_word($rs[content],100);
		if($tb=='news') $rs[url]="../news.php?id=$rs[id]&fid=$rs[fid]";
		else $rs[url]="../bencandy.php?id=$rs[id]&fid=$rs[fid]";
		$listdb[]=$rs;
	}
	$showpage=getpage($table,$where,"?tb=$tb&yz=$yz",$rows);

}elseif($job=="view"){

	$rs=$db->get_one("select * from $table where cid='$cid'");
	$rs[posttime]=date("m/d H:i",$rs[posttime]);
	$rs[yz]=$rs[yz]?"�����":"<font color=red>δ���</font>";
	if($tb=='news') $rs[url]="../news.php?id=$rs[id]&fid=$rs[fid]";
	else $rs[url]="../bencandy.php?id=$rs[id]&fid=$rs[fid]";
		
}

	require("head.php");
	require("template/comments/list.htm");
	require("foot.php");

?>