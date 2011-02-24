<?php
!function_exists('html') && exit('ERR');
if($job=="list"&&$Apower[guestbook_list]){
	$rows=20;
	$page<1&&$page=1;
	$min=($page-1)*$rows;
	$showpage=getpage("`{$pre}guestbook`","","index.php?lfj=$lfj&job=$job",$rows);
	$query = $db->query("SELECT * FROM `{$pre}guestbook` ORDER BY id DESC LIMIT $min,$rows");
	while($rs = $db->fetch_array($query)){
		$rs[content]=get_word($rs[content],50);
		$rs[ismember]=$rs[uid]?"会员":"游客";
		$rs[ifyz]=$rs[yz]?"<A HREF='index.php?lfj=$lfj&job=check&Yz=0&id=$rs[id]' style='color:blue;'><img alt='已通过审核,点击取消审核' src='../member/images/check_yes.gif'></A>":"<A HREF='index.php?lfj=$lfj&job=check&Yz=1&id=$rs[id]' style='color:red;'><img alt='还没通过审核,点击通过审核' src='../member/images/check_no.gif'></A>";
		$listdb[]=$rs;
	}
	require(dirname(__FILE__)."/"."template/head.htm");
	require(dirname(__FILE__)."/"."template/guestbook/menu.htm");
	require(dirname(__FILE__)."/"."template/guestbook/list.htm");
	require(dirname(__FILE__)."/"."template/foot.htm");
}elseif($action=="delete"&&$Apower[guestbook_list]){
	if(!$listdb&&$id){
		$db->query("DELETE FROM `{$pre}guestbook` WHERE id='$id'");
		jump("删除成功","$FROMURL",0);
	}
	foreach( $listdb AS $key=>$value){
		$db->query("DELETE FROM `{$pre}guestbook` WHERE id='$value'");
	}
	jump("删除成功","$FROMURL");
}
elseif($job=="check"&&$Apower[guestbook_list]){
	$db->query("UPDATE `{$pre}guestbook` SET yz='$Yz' WHERE id='$id'");
	jump("操作成功","$FROMURL",0);
}
elseif($job=="show"&&$Apower[guestbook_list])
{
	$rsdb=$db->get_one("SELECT * FROM `{$pre}guestbook` WHERE id='$id' ");
	$rsdb[content]=str_replace("\r\n","<br>",$rsdb[content]);
	echo "内容:$rsdb[content]";exit;
}
elseif($job=="edit"&&$Apower[guestbook_list])
{
	$rsdb=$db->get_one("SELECT * FROM `{$pre}guestbook` WHERE id='$id' ");
	require(dirname(__FILE__)."/"."head.php");
	require(dirname(__FILE__)."/"."template/guestbook/menu.htm");
	require(dirname(__FILE__)."/"."template/guestbook/edit.htm");
	require(dirname(__FILE__)."/"."foot.php");
}
elseif($action=="edit"&&$Apower[guestbook_list])
{
	$db->query("UPDATE `{$pre}guestbook` SET content='$content',username='$username' WHERE id='$id'");
	jump("修改成功","$FROMURL",1);
}
elseif($job=="reply"&&$Apower[guestbook_list])
{
	$rsdb=$db->get_one("SELECT reply FROM `{$pre}guestbook` WHERE id='$id' ");
	$replydb=unserialize($rsdb[reply]);
	$replydb[username] || $replydb[username]=$userdb[username];
	require(dirname(__FILE__)."/"."head.php");
	require(dirname(__FILE__)."/"."template/guestbook/menu.htm");
	require(dirname(__FILE__)."/"."template/guestbook/reply.htm");
	require(dirname(__FILE__)."/"."foot.php");
}
elseif($action=="reply"&&$Apower[guestbook_list])
{
	$postdb[posttime]=$timestamp;
	$postdb[uid]=$userdb[uid];
	$content=addslashes( serialize($postdb) );
	$db->query("UPDATE `{$pre}guestbook` SET reply='$content' WHERE id='$id'");
	jump("修改成功","$FROMURL",1);
}
elseif($job=="config"&&$Apower[guestbook_list])
{
	$yzImgGuestBook[$webdb[yzImgGuestBook]]=" checked ";
	$webdb[ifOpenGuestBook]==='0' || $webdb[ifOpenGuestBook]=1;
	$ifOpenGuestBook[$webdb[ifOpenGuestBook]]=" checked ";
	$webdb[viewNoPassGuestBook]==='0' || $webdb[viewNoPassGuestBook]=1;
	$viewNoPassGuestBook[$webdb[viewNoPassGuestBook]]=" checked ";
	$webdb[groupPassPassGuestBook]="$webdb[groupPassPassGuestBook],3";
	$groupPassPassGuestBook=group_box("webdbs[groupPassPassGuestBook]",explode(",",$webdb[groupPassPassGuestBook]));
	require(dirname(__FILE__)."/"."head.php");
	require(dirname(__FILE__)."/"."template/guestbook/menu.htm");
	require(dirname(__FILE__)."/"."template/guestbook/config.htm");
	require(dirname(__FILE__)."/"."foot.php");
}
elseif($action=="config"&&$Apower[guestbook_list])
{
	write_config_cache($webdbs);
	jump("修改成功",$FROMURL);
}
?>