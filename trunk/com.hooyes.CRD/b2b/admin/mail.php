<?php
!function_exists('html') && exit('ERR');



if($job=="send"&&$Apower[mail_send])
{
	$group_send=group_box("Group",'');
	require(dirname(__FILE__)."/"."head.php");
	require(dirname(__FILE__)."/"."template/mail/menu.htm");
	require(dirname(__FILE__)."/"."template/mail/send.htm");
	require(dirname(__FILE__)."/"."foot.php");
}
elseif($job=="set"&&$Apower[mail_send])
{
	$MailType[$webdb[MailType]]=' checked ';
	require(dirname(__FILE__)."/"."head.php");
	require(dirname(__FILE__)."/"."template/mail/menu.htm");
	require(dirname(__FILE__)."/"."template/mail/set.htm");
	require(dirname(__FILE__)."/"."foot.php");
}
elseif($job=="test"&&$Apower[mail_send])
{
	require(dirname(__FILE__)."/"."head.php");
	require(dirname(__FILE__)."/"."template/mail/menu.htm");
	require(dirname(__FILE__)."/"."template/mail/test.htm");
	require(dirname(__FILE__)."/"."foot.php");
}
elseif($action=="set"&&$Apower[mail_send])
{
	write_config_cache($webdbs);
	jump("设置成功",$FROMURL);
}
elseif($action=="send"&&$Apower[mail_send])
{

	if(!$IS_BIZPhp168){
		showerr("免费版无此功能");
	}
	if($webdb[MailType]=='smtp')
	{
		if(!$webdb[MailServer]||!$webdb[MailPort]||!$webdb[MailId]||!$webdb[MailPw])
		{
			showmsg("请先设置邮件服务器");
		}
		require_once(PHP168_PATH."inc/class.mail.php");
		$smtp = new smtp($webdb[MailServer],$webdb[MailPort],true,$webdb[MailId],$webdb[MailPw]);
		$smtp->debug = false;
	}
	if($page<1)
	{
		$page=1;
		if(!$Group){
			showmsg("你必须选择一个用户组");
		}
		$Group=implode(",",$Group);
		if($Num<1){
			$Num=1;
		}
		if(!$Title){
			showmsg("标题不能为空");
		}
		if(!$Content){
			showmsg("内容不能为空");
		}
		$show="<?php
\$Group='$Group';
\$Num='$Num';
\$Title='$Title';
\$Content='$Content';
		";
		write_file(PHP168_PATH."cache/mail_cache.php",$show);
	}
	else
	{
		include_once(PHP168_PATH."cache/mail_cache.php");
	}
	$Title=addslashes($Title);
	$Content=addslashes($Content);

	$rows=$Num;
	$min=($page-1)*$rows;
	$query = $db->query("SELECT email FROM {$pre}memberdata WHERE groupid IN ($Group) LIMIT $min,$rows");
	while($rs = $db->fetch_array($query)){
		if($webdb[MailType]=='smtp')
		{
			if($smtp->sendmail($rs[email],$webdb[MailId], $Title, $Content, "HTML"))
			{
				$succeeNUM++;
			}
			else
			{
				$failNUM++;
			}
		}
		else
		{
			if(mail($rs[email], $Title, $Content))
			{
				$succeeNUM++;
			}
			else
			{
				$failNUM++;
			}
		}
		$ck++;
	}
	$page++;
	
	if($ck++)
	{
		echo "<META HTTP-EQUIV=REFRESH CONTENT='0;URL=?lfj=$lfj&action=$action&page=$page&succeeNUM=$succeeNUM&failNUM=$failNUM'>";
		exit;
	}
	else
	{
		unlink(PHP168_PATH."cache/mail_cache.php");
		$succeeNUM=intval($succeeNUM);
		$failNUM=intval($failNUM);
		jump("邮件发送完毕,发送成功的邮件有 <font color=red>{$succeeNUM}</font> 封,发送失败的邮件有 <font color=red>{$failNUM}</font> 封","index.php?lfj=mail&job=send",30);	
	}
}
elseif($action=="test"&&$Apower[mail_send])
{
	if($webdb[MailType]=='smtp')
	{
		if(!$webdb[MailServer]||!$webdb[MailPort]||!$webdb[MailId]||!$webdb[MailPw])
		{
			showmsg("请先设置邮件服务器");
		}
		require_once(PHP168_PATH."inc/class.mail.php");
		$smtp = new smtp($webdb[MailServer],$webdb[MailPort],true,$webdb[MailId],$webdb[MailPw]);
		$smtp->debug = false;
	}
	if($page<1)
	{
		$page=1;
		if(!$Emaildb){
			showmsg("邮箱帐号不能为空");
		}
		$Group=implode(",",$Group);
		if($Num<1){
			$Num=1;
		}
		if(!$Title){
			showmsg("标题不能为空");
		}
		if(!$Content){
			showmsg("内容不能为空");
		}
		$show="<?php
\$Num='$Num';
\$Emaildb='$Emaildb';
\$Title='$Title';
\$Content='$Content';
		";
		write_file(PHP168_PATH."cache/mail_cache.php",$show);
	}
	else
	{
		include_once(PHP168_PATH."cache/mail_cache.php");
	}
	$Title=addslashes($Title);
	$Content=addslashes($Content);
	$rows=$Num;
	$min=($page-1)*$rows;
	$detail=explode("\r\n",$Emaildb);
	for($i=$min;$i<($min+$rows);$i++)
	{
		if(!$rs[email]=$detail[$i]){
			continue;
		}
		if($webdb[MailType]=='smtp')
		{
			if($smtp->sendmail($rs[email],$webdb[MailId], $Title, $Content, "HTML"))
			{
				$succeeNUM++;
			}
			else
			{
				$failNUM++;
			}
		}
		else
		{
			if(mail($rs[email], $Title, $Content))
			{
				$succeeNUM++;
			}
			else
			{
				$failNUM++;
			}
		}
		$ck++;
	}
	$page++;
	
	if($ck++)
	{
		echo "<META HTTP-EQUIV=REFRESH CONTENT='0;URL=?lfj=$lfj&action=$action&page=$page&succeeNUM=$succeeNUM&failNUM=$failNUM'>";
		exit;
	}
	else
	{
		unlink(PHP168_PATH."cache/mail_cache.php");
		$succeeNUM=intval($succeeNUM);
		$failNUM=intval($failNUM);
		jump("邮件发送完毕,发送成功的邮件有 <font color=red>{$succeeNUM}</font> 封,发送失败的邮件有 <font color=red>{$failNUM}</font> 封","index.php?lfj=mail&job=$action",30);	
	}
}
?>