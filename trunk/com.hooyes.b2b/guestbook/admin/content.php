<?php
!function_exists('html') && exit('ERR');

ck_power('content');


if($job=="list"){
	$rows=20;
	$page<1&&$page=1;
	$min=($page-1)*$rows;
	
	$SQL=" WHERE 1 ";
	if($fid){
		$SQL.=" AND fid=$fid ";
	}

	require_once(Adminpath."../data/all_fid.php");

	$query = $db->query("SELECT SQL_CALC_FOUND_ROWS * FROM `{$_pre}content` $SQL ORDER BY id DESC LIMIT $min,$rows");

	$RS=$db->get_one("SELECT FOUND_ROWS()");
	$totalNum=$RS['FOUND_ROWS()'];

	$showpage=getpage('','',"$admin_path&job=$job&fid=$fid",$rows,$totalNum);

	while($rs = $db->fetch_array($query)){
		$rs[content]=get_word($rs[content],50);
		$rs[ismember]=$rs[uid]?"会员":"游客";
		$rs[ifyz]=$rs[yz]?"<A HREF='$admin_path&job=check&Yz=0&id=$rs[id]' style='color:blue;'><img alt='已通过审核,点击取消审核' src='../member/images/check_yes.gif'></A>":"<A HREF='$admin_path&job=check&Yz=1&id=$rs[id]' style='color:red;'><img alt='还没通过审核,点击通过审核' src='../member/images/check_no.gif'></A>";
		$listdb[]=$rs;
	}
	get_admin_html('list');
}elseif($action=="delete"){
	if(!$listdb&&$id){
		$db->query("DELETE FROM `{$_pre}content` WHERE id='$id'");
		jump("删除成功","$FROMURL",0);
	}
	foreach( $listdb AS $key=>$value){
		$db->query("DELETE FROM `{$_pre}content` WHERE id='$value'");
	}
	jump("删除成功","$FROMURL");
}
elseif($job=="check"){
	$db->query("UPDATE `{$_pre}content` SET yz='$Yz' WHERE id='$id'");
	jump("操作成功","$FROMURL",0);
}
elseif($job=="show")
{
	$rsdb=$db->get_one("SELECT * FROM `{$_pre}content` WHERE id='$id' ");
	$rsdb[content]=str_replace("\r\n","<br>",$rsdb[content]);
	echo "内容:$rsdb[content]";exit;
}
elseif($job=="edit")
{
	$rsdb=$db->get_one("SELECT * FROM `{$_pre}content` WHERE id='$id' ");
	get_admin_html('edit');
}
elseif($action=="edit")
{
	$db->query("UPDATE `{$_pre}content` SET content='$content',username='$username' WHERE id='$id'");
	jump("修改成功","$FROMURL",1);
}
elseif($job=="reply")
{
	$rsdb=$db->get_one("SELECT reply FROM `{$_pre}content` WHERE id='$id' ");
	$replydb=unserialize($rsdb[reply]);
	$replydb[username] || $replydb[username]=$userdb[username];
	get_admin_html('reply');
}
elseif($action=="reply")
{
	$postdb[posttime]=$timestamp;
	$postdb[uid]=$userdb[uid];
	$content=addslashes( serialize($postdb) );
	$db->query("UPDATE `{$_pre}content` SET reply='$content' WHERE id='$id'");
	jump("修改成功","$FROMURL",1);
}
?>