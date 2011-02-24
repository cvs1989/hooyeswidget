<?php
require(dirname(__FILE__)."/"."global.php");

if(!$lfjid){
	showerr("你还没登录");
}
unset($fiddb);
if(!$web_admin){
	$query = $db->query("SELECT * FROM {$pre}sort WHERE admin!=''");
	while($rs = $db->fetch_array($query)){
		$detail=explode(",",$rs[admin]);
		if(in_array($lfjid,$detail)){
			$fiddb[]=$rs[fid];
		}
	}
}

/*************
*删除评论
**************/
if($job=='del'){
	foreach( $cidDB AS $key=>$value){
		$rs=$db->get_one("SELECT aid FROM {$pre}comment WHERE cid='$value'");
		$erp=get_id_table($rs[aid]);
		$rsdb=$db->get_one("SELECT C.cid,C.uid AS commentuid,C.aid,A.uid,A.fid FROM {$pre}comment C LEFT JOIN {$pre}article$erp A ON C.aid=A.aid WHERE C.cid='$value'");
		if($rsdb[uid]==$lfjuid||$rsdb[commentuid]==$lfjuid||$web_admin||in_array($rsdb[fid],$fiddb)){
			$db->query("DELETE FROM {$pre}comment WHERE cid='$rsdb[cid]'");
		}
		$db->query("UPDATE {$pre}article$erp SET comments=comments-1 WHERE aid='$rsdb[aid]'");
	}
	refreshto("$FROMURL","删除成功",0);
}

/*************
*评论加精华
**************/
if($job=='ifcom'){
	foreach( $cidDB AS $key=>$value){
		$rs=$db->get_one("SELECT aid FROM {$pre}comment WHERE cid='$value'");
		$erp=get_id_table($rs[aid]);
		$rsdb=$db->get_one("SELECT C.cid,C.aid,A.uid,A.fid FROM {$pre}comment C LEFT JOIN {$pre}article$erp A ON C.aid=A.aid WHERE C.cid='$value'");
		if($web_admin||in_array($rsdb[fid],$fiddb)){
			$db->query("UPDATE {$pre}comment SET ifcom='$ifcom' WHERE cid='$rsdb[cid]'");
		}		
	}
	refreshto("$FROMURL","设置成功",0);
}

/*************
*审核评论
**************/
if($job=='yz'){
	foreach( $cidDB AS $key=>$value){
		$rs=$db->get_one("SELECT aid FROM {$pre}comment WHERE cid='$value'");
		$erp=get_id_table($rs[aid]);
		$rsdb=$db->get_one("SELECT C.cid,C.aid,A.uid,A.fid FROM {$pre}comment C LEFT JOIN {$pre}article$erp A ON C.aid=A.aid WHERE C.cid='$value'");
		if($web_admin||in_array($rsdb[fid],$fiddb)){
			$db->query("UPDATE {$pre}comment SET yz='$yz' WHERE cid='$rsdb[cid]'");
		}		
	}
	refreshto("$FROMURL","设置成功",0);
}


if($job=='work'){
	if($web_admin){
		$SQL=" WHERE 1 ";
	}else{
		$fids=implode(",",$fiddb);
		if($fids){
			$SQL=" WHERE C.fid IN ($fids) ";
		}else{
			$SQL=" WHERE 0 ";
		}		
	}
}else{
	$SQL=" WHERE C.authorid='$lfjuid' ";
}

$rows=20;
if(!$page){
	$page=1;
}
$min=($page-1)*$rows;


$showpage=getpage("{$pre}comment C LEFT JOIN {$pre}article A ON C.aid=A.aid"," $SQL ","?job=$job",$rows);
$query = $db->query("SELECT C.*,A.title FROM {$pre}comment C LEFT JOIN {$pre}article A ON C.aid=A.aid $SQL ORDER BY C.cid DESC LIMIT $min,$rows");
while($rs = $db->fetch_array($query)){
	if(!$rs[title]){
		$erp=get_id_table($rs[aid]);
		$erp && $_rs=$db->get_one("SELECT title FROM {$pre}article$erp WHERE aid='$rs[aid]'");
		$rs[title]=$_rs[title];
	}
	if($job=='work'){
		if($rs[yz]){
			$rs[state]="&nbsp;&nbsp;<A style='color:red;' href='?job=yz&yz=0&cidDB[]=$rs[cid]' title='已审核,点击取消审核'><img src='images/check_yes.gif' border=0></A>&nbsp;&nbsp;";
		}else{
			$rs[state]="&nbsp;&nbsp;<A style='color:blue;' href='?job=yz&yz=1&cidDB[]=$rs[cid]' title='还没审核,点击通过审核'><img src='images/check_no.gif' border=0></A>&nbsp;&nbsp;";
		}
		if($rs[ifcom]){
			$rs[state].=" <A style='color:red;' href='?job=ifcom&ifcom=0&cidDB[]=$rs[cid]' title='已推荐为精华,点击取消精华'><img src='../images/default/good_ico.gif' border=0></A>";
		}else{
			$rs[state].=" <A style='color:blue;' href='?job=ifcom&ifcom=1&cidDB[]=$rs[cid]' title='非精华,点击设置为精华'><img src='images/nogood_ico.gif' border=0></A>";
		}
	}else{
		if($rs[yz]){
			$rs[state]="<A style='color:red;'>已审核</A>";
		}else{
			$rs[state]="<A style='color:blue;'>未审核</A>";
		}
	}
	$rs[posttime]=date("Y-m-d H:i",$rs[posttime]);
	$rs[ctitle]=get_word($rs[content],110);
	$listdb[]=$rs;
}

require(dirname(__FILE__)."/"."head.php");
require(dirname(__FILE__)."/"."template/comment.htm");
require(dirname(__FILE__)."/"."foot.php");

?>