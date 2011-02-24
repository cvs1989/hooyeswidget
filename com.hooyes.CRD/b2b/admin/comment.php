<?php
!function_exists('html') && exit('ERR');

if($job=="set"&&$Apower[comment_set])
{
	$showComment[$webdb[showComment]]=' checked ';
	$allowGuestComment[$webdb[allowGuestComment]]=' checked ';
	$allowGuestCommentPass[$webdb[allowGuestCommentPass]]=' checked ';
	$allowMemberCommentPass[$webdb[allowMemberCommentPass]]=' checked ';
	$showNoPassComment[$webdb[showNoPassComment]]=' checked ';
	$forbidComment[$webdb[forbidComment]]=' checked ';


	$CommentOrderType[intval($webdb[CommentOrderType])]=' checked ';

	$logShowComment[$webdb[logShowComment]]=' checked ';
	$downShowComment[$webdb[downShowComment]]=' checked ';
	$photoShowComment[$webdb[photoShowComment]]=' checked ';
	$mvShowComment[$webdb[mvShowComment]]=' checked ';
	$shopShowComment[$webdb[shopShowComment]]=' checked ';
	$musicShowComment[$webdb[musicShowComment]]=' checked ';
	$flashShowComment[$webdb[flashShowComment]]=' checked ';

	require(dirname(__FILE__)."/"."head.php");
	//require(dirname(__FILE__)."/"."template/comment/menu.htm");
	require(dirname(__FILE__)."/"."template/comment/set.htm");
	require(dirname(__FILE__)."/"."foot.php");
}
elseif($action=="set"&&$Apower[comment_set])
{
	write_config_cache($webdbs);
	jump("修改成功",$FROMURL);
}
elseif($job=="list"&&$Apower[comment_list])
{
	!$page&&$page=1;
	$rows=20;
	$min=($page-1)*$rows;
	$SQL=" WHERE 1 ";
	if($aid){
		$SQL.=" AND aid='$aid' ";
	}
	$showpage=getpage("{$pre}comment","$SQL","index.php?lfj=$lfj&job=$job&aid=$aid","$rows");
	$query=$db->query(" SELECT * FROM {$pre}comment $SQL ORDER BY cid DESC LIMIT $min,$rows ");
	while($rs=$db->fetch_array($query)){
		$rs[content]=filtrate(get_word($rs[content],60));
		$rs[posttime]=date("Y-m-d",$rs[posttime]);
		$rs[username]=$rs[username]?$rs[username]:$rs[ip];
		if($rs[yz]==1){
			$rs[yz]="<A HREF='index.php?lfj=comment&action=list&jobs=unyz&ciddb[{$rs[cid]}]=$rs[cid]' style='color:blue;' title='已通过审核,点击取消审核'><img src='../member/images/check_yes.gif'></A>";
		}elseif($rs[yz]==0){
			$rs[yz]="<A HREF='index.php?lfj=comment&action=list&jobs=yz&ciddb[{$rs[cid]}]=$rs[cid]' style='color:red;' title='还没通过审核,点击通过审核'><img src='../member/images/check_no.gif'></A>";
		}
		if($rs[ifcom]==1){
			$rs[com]="<A HREF='index.php?lfj=comment&action=list&jobs=uncom&ciddb[{$rs[cid]}]=$rs[cid]' style='color:red;' title='已推荐为精华,点击可取消精华'><img src='../images/default/good_ico.gif'></A>";
		}elseif($rs[ifcom]==0){
			$rs[com]="<A HREF='index.php?lfj=comment&action=list&jobs=com&ciddb[{$rs[cid]}]=$rs[cid]' title='非精华,点击可推荐为精华'><img src='../member/images/nogood_ico.gif'></A>";
		}
		$listdb[]=$rs;
	}
	require(dirname(__FILE__)."/"."head.php");
	//require(dirname(__FILE__)."/"."template/comment/menu.htm");
	require(dirname(__FILE__)."/"."template/comment/list.htm");
	require(dirname(__FILE__)."/"."foot.php");
}
elseif($action=="list"&&$Apower[comment_list])
{
	if(!$ciddb){
		showmsg("请选择一条评论");
	}
	if($jobs=="delete")
	{
		foreach($ciddb AS $key=>$rs){
			$rs=$db->get_one("SELECT aid FROM {$pre}comment WHERE cid='$key' ");
			$erp=get_id_table($rs[aid]);
			$db->query(" UPDATE {$pre}article$erp SET comments=comments-1 WHERE aid='$rs[aid]' ");
			$db->query("DELETE FROM {$pre}comment WHERE cid='$key' ");
			$ck++;
		}
	}
	elseif($jobs=="yz"||$jobs=="unyz")
	{
		if($jobs=="yz"){
			$yz=1;
		}else{
			$yz=0;
		}
		foreach($ciddb AS $key=>$rs){
			$db->query(" UPDATE {$pre}comment SET yz='$yz' WHERE cid='$key' ");
			$ck++;
		}
	}
	elseif($jobs=="com"||$jobs=="uncom")
	{
		if($jobs=="com"){
			$yz=1;
		}else{
			$yz=0;
		}
		foreach($ciddb AS $key=>$rs){
			$db->query(" UPDATE {$pre}comment SET ifcom='$yz' WHERE cid='$key' ");
			$ck++;
		}
	}
	$retime=$ck==1?0:1;
	jump("操作成功","$FROMURL",$retime);
}
elseif($job=="show"&&$Apower[comment_list])
{
	$rsdb=$db->get_one("SELECT * FROM {$pre}comment WHERE cid='$cid' ");
	$rsdb[content]=str_replace("\r\n","<br>",$rsdb[content]);
	require(dirname(__FILE__)."/"."head.php");
	require(dirname(__FILE__)."/"."template/comment/show.htm");
	require(dirname(__FILE__)."/"."foot.php");
}

?>