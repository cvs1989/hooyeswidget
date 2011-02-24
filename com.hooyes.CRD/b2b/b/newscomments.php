<?php
require_once("global.php");


if($job=='comments'){ //如果是评论
	
	$_web=preg_replace("/http:\/\/([^\/]+)\/(.*)/is","http://\\1",$WEBURL);
	if($webdb[Info_forbidOutPost]&&!ereg("^$_web",$FROMURL))showerr("系统设置不能从外部提交数据");
	if(!$comment_content) showerr("内容不能为空");
	$content=$comment_content;

	if($webdb[forbidComment]){
		$allow=0;
		refreshto("news.php?fid=$fid&id=$id","系统禁止了此项功能");
	}
	/*禁止游客评论*/
	elseif(!$webdb[allowGuestComment]&&!$lfjid)
	{
		$allow=0;
	}
	/*允许所有人评论*/
	else
	{
		$allow=1;
	}
	/*是否允许评论自动通过审核判断处理*/
	/*判断评论是否自动通过验证,allowGuestCommentPass为所有人的评论自动通过验证*/
	if($webdb[allowGuestCommentPass])
	{
		$yz=1;
	}
	/*只有登录用户的评论才能是自动通过验证*/
	elseif($webdb[allowMemberCommentPass]&&$lfjid)
	{
		$yz=1;
	}
	/*都不给自动通过验证*/
	else
	{
		$yz=0;
	}


	$username=filtrate($username);
	$content=filtrate($content);

	$content=str_replace("@@br@@","<br>",$content);

	//过滤不健康的字
	$username=replace_bad_word($username);
	$content=replace_bad_word($content);

	//处理有人恶意用他人帐号做署名的
	if($username)
	{
		$rs=$db->get_one(" SELECT $TB[uid] AS uid FROM $TB[table] WHERE $TB[username]='$username' ");
		if($rs[uid]!=$lfjuid)
		{
			$username="匿名";
		}
	}
	
	$rss=$db->get_one(" SELECT * FROM {$_pre}news WHERE id='$id' ");
	if(!$rss){
		showerr("原数据不存在");
	}
	$fid=$rss[fid];

	$username || $username=$lfjid;
	
	
	if($allow){
		$db->query("INSERT INTO `{$_pre}newscomments` (`cuid`, `type`, `id`, `fid`, `uid`, `username`, `posttime`, `content`, `ip`, `icon`, `yz`) VALUES ('$rss[uid]','0','$id','$fid','$lfjuid','$username','$timestamp','$content','$onlineip','$icon','$yz')");
	
		$db->query(" UPDATE {$_pre}news SET comments=comments+1 WHERE id='$id' ");
	}
	refreshto("news.php?fid=$fid&id=$id","操作成功");
	exit;

}elseif($job=='del'){

	$rs=$db->get_one("SELECT * FROM `{$_pre}newscomments` WHERE cid='$cid'");
	if(!$lfjuid)
	{
		showerr("你还没登录,无权限");
	}
	elseif(!$web_admin&&$rs[uid]!=$lfjuid&&$rs[cuid]!=$lfjuid)
	{
		showerr("你没权限");
	}
	if(!$web_admin&&$rs[uid]!=$lfjuid){
		$lfjdb[money]=get_money($lfjdb[uid]);
		if(abs($webdb[DelOtherCommentMoney])>$lfjdb[money]){
			showerr("你的{$webdb[MoneyName]}不足");
		}
		add_user($lfjdb[uid],-abs($webdb[DelOtherCommentMoney]));
	}
	$db->query(" DELETE FROM `{$_pre}newscomments` WHERE cid='$cid' ");
	$db->query("UPDATE {$_pre}news SET comments=comments-1 WHERE id='$rs[id]' ");
refreshto("news.php?fid=$fid&id=$id","操作成功");
	exit;
}
?>