<?php
require_once("global.php");

if($action=='send')
{
	$rs=$db->get_one("SELECT M.*,D.* FROM $TB[table] M LEFT JOIN {$pre}memberdata D ON M.$TB[uid]=D.uid WHERE M.$TB[username]='$atc_username'");
	if(!$rs){
		showerr("�ʺ�����,������");
	}elseif(!$rs[email]){
		showerr("��ǰ�ʺ�û����������,����ϵͳ����Ա�����޸�����!");
	}
	if(!$webdb[mymd5])
	{
		$webdb[mymd5]=rands(10);
		$db->query("REPLACE INTO {$pre}config (`c_key`,`c_value`) VALUES ('mymd5','$webdb[mymd5]')");
		write_file(PHP168_PATH."php168/config.php","\$webdb['mymd5']='$webdb[mymd5]';",'a');
	}
	$newpwd=strtolower(rands(8));
	$md5_id=mymd5("{$rs[$TB[username]]}\t{$rs[$TB[password]]}\t$newpwd");
	$Title="���ԡ�{$webdb[webname]}�����ʼ�,ȡ������!!";
	$Content="���ڡ�{$webdb[webname]}�����ʺ��ǡ�{$rs[$TB[username]]}��,����������ǣ���{$newpwd}��,������������ַ,����������,��������,�ſ�����Ч��<br><br><A HREF='$webdb[www_url]/do/sendpwd.php?job=getpwd&md5_id=$md5_id' target='_blank'>$webdb[www_url]/do/sendpwd.php?job=getpwd&md5_id=$md5_id</A>";

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
		refreshto("./","�������Ѿ��ɹ����͵��������:��{$rs[email]}������ע�����!",5);
	}
	else
	{
		showerr("�ʼ�����ʧ�ܣ����������������,�����Ƿ����������ʼ����������⣡��");
	}
}
elseif($job=='getpwd')
{
	list($username,$password,$newpassword)=explode("\t",mymd5($md5_id,'DE'));
	if($db->get_one("SELECT M.*,D.* FROM $TB[table] M LEFT JOIN {$pre}memberdata D ON M.$TB[uid]=D.uid WHERE M.$TB[username]='$username' AND M.$TB[password]='$password'"))
	{
		$newpassword=pwd_md5($newpassword);
		$db->query("UPDATE $TB[table] SET $TB[password]='$newpassword' WHERE $TB[username]='$username'");
		refreshto("login.php","��ϲ�㣬�����뼤��ɹ����뾡���¼�޸�����!",10);
	}
	else
	{
		showerr("�����뼤��ʧ��!");
	}
}

require(PHP168_PATH."inc/head.php");
require(html("sendpwd"));
require(PHP168_PATH."inc/foot.php");
?>