<?php
require_once("global.php");

header('Content-Type: text/html; charset=gb2312');

/**
*处理用户提交的评论
**/
if($action=="post")
{
	if($ctype == 1){
		$tbl = 'content_sell';
		
	}else if($ctype == 2){
		$tbl = 'content_buy';
		
	}else{
		die("不允许的操作");
	}

	$_web=preg_replace("/http:\/\/([^\/]+)\/(.*)/is","http://\\1",$WEBURL);
	if($webdb[Info_forbidOutPost]&&!ereg("^$_web",$FROMURL))
	{
		showerr("系统设置不能从外部提交数据");
	}
	
	/*验证码处理*/
	if($webdb[Info_GroupCommentYzImg]&&in_array($groupdb['gid'],explode(",",$webdb[Info_GroupCommentYzImg])))
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


	/*是否允许评论判断处理*/
	/*禁止所有人进行评论*/
	if($webdb[forbidComment])
	{
		$allow=0;
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
	
	$rss=$db->get_one(" SELECT * FROM {$_pre}$tbl WHERE id='$id' ");
	if(!$rss){
		die("原数据不存在");
	}
	$fid=$rss[fid];

	$username || $username=$lfjid;


	/*如果系统做了限制,那么有的评论将不给提交成功,但没做提示评论失败*/
	if($allow)
	{
		
		$db->query("INSERT INTO `{$_pre}comments` (`cuid`, `type`, `id`, `fid`, `uid`, `username`, `posttime`, `content`, `ip`, `icon`, `yz`) VALUES ('$rss[uid]','0','$id','$fid','$lfjuid','$username','$timestamp','$content','$onlineip','$icon','$yz')");

		$db->query(" UPDATE {$_pre}$tbl SET comments=comments+1,`replytime`='$timestamp' WHERE id='$id' ");
	}
}

/*删除留言*/
elseif($action=="del")
{
	if($ctype == 1){
		$tbl = 'content_sell';
		
	}else if($ctype == 2){
		$tbl = 'content_buy';
		
	}else{
		die("不允许的操作");
	}
	
	$rs=$db->get_one("SELECT * FROM `{$_pre}comments` WHERE cid='$cid'");
	if(!$lfjuid)
	{
		die("你还没登录,无权限");
	}
	elseif(!$web_admin&&$rs[uid]!=$lfjuid&&$rs[cuid]!=$lfjuid)
	{
		die("你没权限");
	}
	if(!$web_admin&&$rs[uid]!=$lfjuid){
		$lfjdb[money]=get_money($lfjdb[uid]);
		if(abs($webdb[DelOtherCommentMoney])>$lfjdb[money]){
			die("你的{$webdb[MoneyName]}不足");
		}
		add_user($lfjdb[uid],-abs($webdb[DelOtherCommentMoney]));
	}
	$db->query(" DELETE FROM `{$_pre}comments` WHERE cid='$cid' ");
	$db->query("UPDATE {$_pre}$tbl SET comments=comments-1 WHERE id='$rs[id]' ");
}
/*鲜花鸡蛋处理*/
elseif($action=="flowers"||$action=="egg")
{
	if($_COOKIE["{$action}_$cid"]){
		echo "请不要重复操作!!<hr>";
	}else{
		set_cookie("{$action}_$cid",1,3600);
		$db->query("UPDATE `{$_pre}comments` SET `$action`=`$action`+1 WHERE cid='$cid'");
	}
}
/**
*是否只显示通过验证的评论,或者是全部显示
**/
if(!$webdb[showNoPassComment])
{
	$SQL=" AND yz=1 ";
}
else
{
	$SQL="";
}

/**
*每页显示评论条数
**/
$rows=$webdb[showCommentRows]?$webdb[showCommentRows]:8;

if($page<1)
{
	$page=1;
}
$min=($page-1)*$rows;


//$rsdb=$db->get_one("SELECT M.* FROM {$_pre}sort S LEFT JOIN {$_pre}module M ON S.mid=M.id WHERE S.fid='$fid'");
$rsdb=$db->get_one("SELECT S.* FROM {$_pre}sort S  WHERE S.fid='$fid'");
/*评论字数再多也只限制显示1000个字*/
$leng=1000;

$query=$db->query("SELECT * FROM `{$_pre}comments` WHERE id=$id $SQL ORDER BY cid DESC LIMIT $min,$rows");
while( $rs=$db->fetch_array($query) )
{
	if(!$rs[username])
	{
		$detail=explode(".",$rs[ip]);
		$rs[username]="$detail[0].$detail[1].$detail[2].*";
	}

	$rs[posttime]=date("Y-m-d H:i:s",$rs[posttime]);

	$rs[full_content]=$rs[content];

	$rs[content]=get_word($rs[content],$leng);

	if($rs[type]){
		$rs[content]="<img style='margin-top:3px;' src=$webdb[www_url]/images/default/good_ico.gif> ".$rs[content];
	}

	$rs[content]=str_replace("\n","<br>",$rs[content]);

	$listdb[]=$rs;
}

/**
*评论分布功能
**/
$showpage=getpage("`{$_pre}comments`"," where id='$id' $SQL","?fid=$fid&id=$id",$rows);
$showpage=preg_replace("/\?fid=([\d]+)&id=([\d]+)&page=([\d]+)/is","javascript:getcomment('comment_ajax.php?fid=\\1&id=\\2&page=\\3')",$showpage);


require_once(getTpl('comment_ajax'));

?>