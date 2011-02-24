<?php
!function_exists('html') && exit('ERR');
if($step==2)
{
	if(!ereg("^[-a-zA-Z0-9_\.]+\@([0-9A-Za-z][0-9A-Za-z-]+\.)+[A-Za-z]{2,5}$",$email)) {
		showerr("邮箱不符合规则"); 
	}elseif(!$content){
		showerr("内容不能为空"); 
	}elseif(strlen($content)>3000){
		showerr("内容不能大于3000个字符"); 
	}
	$Title="来自“{$webdb[webname]}”的邮件,你朋友“{$myname}”给你推荐了一篇精彩文章!!";
	
	$rs[email]=$email;
	$Content=filtrate($content);
	$Content=str_replace("\n","<br>",$Content);

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
		refreshto("./","恭喜你，邮件成功发出!",5);
	}
	else
	{
		showerr("邮件发送失败，可能你邮箱地址有误,或者是服务器发送邮件功能有问题！！");
	}
}

$erp=get_id_table($id);
$rsdb=$db->get_one("SELECT * FROM {$pre}article$erp WHERE aid='$id'");
if(!$id){
	showerr("数据不存在");
}
require(PHP168_PATH."inc/head.php");
require(html("recommend"));
require(PHP168_PATH."inc/foot.php");
?>