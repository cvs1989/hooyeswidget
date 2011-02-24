<?php
require_once("global.php");

if($action=='send')
{
	$rs=$db->get_one("SELECT M.*,D.* FROM $TB[table] M LEFT JOIN {$pre}memberdata D ON M.$TB[uid]=D.uid WHERE M.$TB[username]='$atc_username'");
	if(!$rs){
		showerr("帐号有误,不存在");
	}elseif(!$rs[email]){
		showerr("当前帐号没有设置邮箱,请联系统管理员帮你修改密码!");
	}
	if(!$webdb[mymd5])
	{
		$webdb[mymd5]=rands(10);
		$db->query("REPLACE INTO {$pre}config (`c_key`,`c_value`) VALUES ('mymd5','$webdb[mymd5]')");
		write_file(PHP168_PATH."php168/config.php","\$webdb['mymd5']='$webdb[mymd5]';",'a');
	}
	$newpwd=strtolower(rands(8));
	$md5_id=mymd5("{$rs[$TB[username]]}\t{$rs[$TB[password]]}\t$newpwd");
	$Title="来自“{$webdb[webname]}”的邮件,取回密码!!";
	$Content="你在“{$webdb[webname]}”的帐号是“{$rs[$TB[username]]}”,你的新密码是：“{$newpwd}”,请点击此以下网址,激活新密码,点击激活后,才可以生效。<br><br><A HREF='$webdb[www_url]/do/sendpwd.php?job=getpwd&md5_id=$md5_id' target='_blank'>$webdb[www_url]/do/sendpwd.php?job=getpwd&md5_id=$md5_id</A>";

	if($webdb[MailType]=='smtp')
	{
		if(!$webdb[MailServer]||!$webdb[MailPort]||!$webdb[MailId]||!$webdb[MailPw])
		{
			showmsg("请管理员先设置邮件服务器");
		}
		require_once(PHP168_PATH."inc/class.mail.php");
		$smtp = new smtp($webdb[MailServer],$webdb[MailPort],true,$webdb[MailId],$webdb[MailPw]);
		$smtp->debug = false;

		if($smtp->sendmail($rs[email],$webdb[MailId], $Title, $Content, "HTML"))
		{
			$succeeNUM++;
		}
	}
	else
	{
		if(mail($rs[email], $Title, $Content))
		{
			$succeeNUM++;
		}
	}
	if($succeeNUM)
	{
		refreshto("./","新密码已经成功发送到你的邮箱:“{$rs[email]}”，请注意查收!",5);
	}
	else
	{
		showerr("邮件发送失败，可能你的邮箱有误,或者是服务器发送邮件功能有问题！！");
	}
}
elseif($job=='getpwd')
{
	list($username,$password,$newpassword)=explode("\t",mymd5($md5_id,'DE'));
	if($db->get_one("SELECT M.*,D.* FROM $TB[table] M LEFT JOIN {$pre}memberdata D ON M.$TB[uid]=D.uid WHERE M.$TB[username]='$username' AND M.$TB[password]='$password'"))
	{
		$newpassword=pwd_md5($newpassword);
		$db->query("UPDATE $TB[table] SET $TB[password]='$newpassword' WHERE $TB[username]='$username'");
		refreshto("login.php","恭喜你，新密码激活成功，请尽快登录修改密码!",10);
	}
	else
	{
		showerr("新密码激活失败!");
	}
}

require(PHP168_PATH."inc/head.php");
require(html("sendpwd"));
require(PHP168_PATH."inc/foot.php");
?>