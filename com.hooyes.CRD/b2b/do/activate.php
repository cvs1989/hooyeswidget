<?php
require_once("global.php");

if($action=='send')
{
	$rs=$db->get_one("SELECT M.*,D.* FROM $TB[table] M LEFT JOIN {$pre}memberdata D ON M.$TB[uid]=D.uid WHERE M.$TB[username]='$atc_username'");
	if(!$rs){
		showerr("�ʺŲ�����");
	}elseif($rs[yz]){
		showerr("��ǰ�ʺ��Ѿ�������,�㲻���ظ�����!");
	}elseif(!$atc_email){
		showerr("����������!");
	}
	if (!ereg("^[-a-zA-Z0-9_\.]+\@([0-9A-Za-z][0-9A-Za-z-]+\.)+[A-Za-z]{2,5}$",$atc_email)) {
		showerr("���䲻���Ϲ���"); 
	}
	if(!$webdb[mymd5])
	{
		$webdb[mymd5]=rands(10);
		$db->query("REPLACE INTO {$pre}config (`c_key`,`c_value`) VALUES ('mymd5','$webdb[mymd5]')");
		write_file(PHP168_PATH."php168/config.php","\$webdb['mymd5']='$webdb[mymd5]';",'a');
	}
	$md5_id=mymd5("{$rs[$TB[username]]}\t{$rs[$TB[password]]}");
	$Title="���ԡ�{$webdb[webname]}�����ʼ�,�뼤���ʺ�!!";
	$Content="���ڡ�{$webdb[webname]}�����ʺ��ǡ�{$rs[$TB[username]]}����û����,������������ַ,��������ʺš�<br><br><A HREF='$webdb[www_url]/do/activate.php?job=activate&md5_id=$md5_id' target='_blank'>$webdb[www_url]/do/activate.php?job=activate&md5_id=$md5_id</A>";

	if($webdb[MailType]=='smtp')
	{
		if(!$webdb[MailServer]||!$webdb[MailPort]||!$webdb[MailId]||!$webdb[MailPw])
		{
			showmsg("�����Ա�������ʼ�������");
		}
		require_once(PHP168_PATH."inc/class.mail.php");
		$smtp = new smtp($webdb[MailServer],$webdb[MailPort],true,$webdb[MailId],$webdb[MailPw]);
		$smtp->debug = false;

		if($smtp->sendmail($atc_email,$webdb[MailId], $Title, $Content, "HTML"))
		{
			$succeeNUM++;
		}
	}
	else
	{
		if(mail($atc_email, $Title, $Content))
		{
			$succeeNUM++;
		}
	}
	if($succeeNUM)
	{
		refreshto("./","ϵͳ�Ѿ��ɹ������ʼ����������:��{$atc_email}������ע�����!",5);
	}
	else
	{
		showerr("�ʼ�����ʧ�ܣ����������������,�����Ƿ����������ʼ����������⣡��");
	}
}
elseif($job=='activate')
{
	list($username,$password)=explode("\t",mymd5($md5_id,'DE'));
	if($rs=$db->get_one("SELECT M.*,D.* FROM $TB[table] M LEFT JOIN {$pre}memberdata D ON M.$TB[uid]=D.uid WHERE M.$TB[username]='$username' AND M.$TB[password]='$password'"))
	{
		$db->query("UPDATE {$pre}memberdata SET `yz`='1' WHERE uid='$rs[uid]'");
		refreshto("login.php","��ϲ�㣬����ʺš�{$username}������ɹ�����������¼�������Ա���еĹ���!",10);
	}
	else
	{
		showerr("�ʺż���ʧ��!");
	}
}

if($username){
	extract( $db->get_one("SELECT D.email FROM $TB[table] M LEFT JOIN {$pre}memberdata D ON M.$TB[uid]=D.uid WHERE M.$TB[username]='$username'") );
}
require(PHP168_PATH."inc/head.php");
require(html("activate"));
require(PHP168_PATH."inc/foot.php");
?>