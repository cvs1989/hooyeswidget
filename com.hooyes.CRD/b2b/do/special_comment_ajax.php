<?php
require_once(dirname(__FILE__)."/"."global.php");
header('Content-Type: text/html; charset=gb2312');

/**
*发表评论
**/
if($action=="post"){

	//验证码处理
	if(!$web_admin&&$groupdb[CommentArticleYzImg])
	{
		if(!yzimg($yzimg))
		{
			die("验证码不符合,评论失败");
		}
		else
		{
			//setcookie("yzImgNum","0",$timestamp+3600,"/");
		}
	}

	if(!$content)
	{
		die("内容不能为空");
	}
	

	//权限判断是否允许发表评论
	//禁止全部人评论
	if($webdb[forbidComment])
	{
		$allow=0;
	}
	//会员可以评论,但游客不能评论
	elseif(!$webdb[allowGuestComment]&&!$lfjid)
	{
		$allow=0;
	}
	//全部人可以评论
	else
	{
		$allow=1;
	}


	//评论自动通过审核的判断
	//全部人的评论自动通过审核
	if($webdb[allowGuestCommentPass])
	{
		$yz=1;
	}
	//只有会员的才自动通过审核
	elseif($webdb[allowMemberCommentPass]&&$lfjid)
	{
		$yz=1;
	}
	//都不能自动通过审核
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
	
	$rss=$db->get_one(" SELECT * FROM {$pre}special WHERE id='$cid' ");
	if(!$rss)
	{
		die("原数据不存在");
	}

	$username || $username=$lfjid;

	/*如果系统做了限制,那么有的评论将不给提交成功,但没做提示评论失败*/
	if($allow)
	{
		$db->query("INSERT INTO `{$pre}special_comment` (`cid` , `uid` , `username` , `posttime` , `content` , `ip` , `icon` , `yz` ) VALUES ('$cid', '$lfjuid', '$username', '$timestamp', '$content', '$onlineip', '$icon', '$yz')");
	}
}

/**
*删除评论
**/
elseif($action=="del")
{
	$rs=$db->get_one("SELECT * FROM `{$pre}special_comment` WHERE id='$id'");
	if($web_admin||$lfjuid==$rs[uid])
	{
		$db->query("DELETE FROM `{$pre}special_comment` WHERE id='$id'");
	}
}

//判断是否显示全部评论
if(!$webdb[showNoPassComment])
{
	$SQL=" AND A.yz=1 ";
}
else
{
	$SQL="";
}

$rows=$webdb[showCommentRows]?$webdb[showCommentRows]:8;

if($page<1)
{
	$page=1;
}
$min=($page-1)*$rows;

/*评论字数再多也只限制显示1000个字*/
$leng=10000;

$query=$db->query("SELECT A.*,B.icon FROM `{$pre}special_comment` A LEFT JOIN {$pre}memberdata B ON A.uid=B.uid WHERE A.cid='$cid' $SQL ORDER BY A.cid DESC LIMIT $min,$rows");
while( $rs=$db->fetch_array($query) )
{
	if(!$rs[username]){
		$detail=explode(".",$rs[ip]);
		$rs[username]="$detail[0].$detail[1].$detail[2].*";
	}
	if($rs[icon]){
		$rs[icon]=tempdir($rs[icon]);
	}
	$rs[posttime]=date("Y-m-d H:i:s",$rs[posttime]);
	$rs[content]=get_word($rs[full_content]=$rs[content],$leng);
	$rs[content]=str_replace("\n","<br>",$rs[content]);
	$rs[content]=kill_badword($rs[content]);
	$rs[username]=kill_badword($rs[username]);
	$listdb[]=$rs;
}

$showpage=getpage("`{$pre}special_comment` A"," WHERE A.cid='$cid' $SQL","?cid=$cid",$rows);
$showpage=preg_replace("/\?cid=([\d]+)&page=([\d]+)/is","javascript:getcomment('$webdb[www_url]/do/special_comment_ajax.php?cid=\\1&page=\\2')",$showpage);

require_once(html('special_comment_ajax'));

?>