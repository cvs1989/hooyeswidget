<?php
!function_exists('html') && exit('ERR');
if($step==2)
{
	if(!ereg("^[-a-zA-Z0-9_\.]+\@([0-9A-Za-z][0-9A-Za-z-]+\.)+[A-Za-z]{2,5}$",$email)) {
		showerr("���䲻���Ϲ���"); 
	}elseif(!$content){
		showerr("���ݲ���Ϊ��"); 
	}elseif(strlen($content)>3000){
		showerr("���ݲ��ܴ���3000���ַ�"); 
	}
	$Title="���ԡ�{$webdb[webname]}�����ʼ�,�����ѡ�{$myname}�������Ƽ���һƪ��������!!";
	
	$rs[email]=$email;
	$Content=filtrate($content);
	$Content=str_replace("\n","<br>",$Content);

	if($webdb[MailType]=='smtp')
	{
		if(!$webdb[MailServer]||!$webdb[MailPort]||!$webdb[MailId]||!$webdb[MailPw])
		{
			showmsg("�����Ա�������ʼ�������");
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
		refreshto("./","��ϲ�㣬�ʼ��ɹ�����!",5);
	}
	else
	{
		showerr("�ʼ�����ʧ�ܣ������������ַ����,�����Ƿ����������ʼ����������⣡��");
	}
}

$erp=get_id_table($id);
$rsdb=$db->get_one("SELECT * FROM {$pre}article$erp WHERE aid='$id'");
if(!$id){
	showerr("���ݲ�����");
}
require(PHP168_PATH."inc/head.php");
require(html("recommend"));
require(PHP168_PATH."inc/foot.php");
?>