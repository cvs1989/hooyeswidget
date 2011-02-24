<?php
!function_exists('html') && exit('ERR');
$rsdb=$db->get_one("SELECT * FROM {$pre}article WHERE aid='$id'");
if(!$id){
	showerr("数据不存在");
}
if($step==2)
{
	$rs=$db->get_one("SELECT * FROM `{$pre}report` WHERE `ip`='$onlineip'");
	if(!$web_admin&&($timestamp-$rs[posttime]<30)){
		showerr("请30秒后再举报信息"); 
	}

	if(!$Type){
		showerr("请选择一个类型"); 
	}elseif(strlen($myname)>30){
		showerr("你的称呼不能大于30个字符"); 
	}
	$Title="来自“{$webdb[webname]}”的邮件,你朋友“{$myname}”给你推荐了一篇精彩文章!!";
	
	$rs[email]=$email;
	$content=filtrate($content);
	$Type=filtrate($Type);
	$Content=str_replace("\n","<br>",$Content);

	$db->query("INSERT INTO `{$pre}report` (  `aid` , `type` , `uid` , `name` , `content` , `posttime` , `ip` ) VALUES ('$id','$Type','$lfjuid','$myname','$content','$timestamp','$onlineip')");

	refreshto("$webdb[www_url]/","谢谢你，举报本条信息!",5);
}

require(PHP168_PATH."inc/head.php");
require(html("report"));
require(PHP168_PATH."inc/foot.php");
?>