<?php
require_once("global.php");


if($job=='comments'){ //���������
	
	$_web=preg_replace("/http:\/\/([^\/]+)\/(.*)/is","http://\\1",$WEBURL);
	if($webdb[Info_forbidOutPost]&&!ereg("^$_web",$FROMURL))showerr("ϵͳ���ò��ܴ��ⲿ�ύ����");
	if(!$comment_content) showerr("���ݲ���Ϊ��");
	$content=$comment_content;

	if($webdb[forbidComment]){
		$allow=0;
		refreshto("news.php?fid=$fid&id=$id","ϵͳ��ֹ�˴����");
	}
	/*��ֹ�ο�����*/
	elseif(!$webdb[allowGuestComment]&&!$lfjid)
	{
		$allow=0;
	}
	/*��������������*/
	else
	{
		$allow=1;
	}
	/*�Ƿ����������Զ�ͨ������жϴ���*/
	/*�ж������Ƿ��Զ�ͨ����֤,allowGuestCommentPassΪ�����˵������Զ�ͨ����֤*/
	if($webdb[allowGuestCommentPass])
	{
		$yz=1;
	}
	/*ֻ�е�¼�û������۲������Զ�ͨ����֤*/
	elseif($webdb[allowMemberCommentPass]&&$lfjid)
	{
		$yz=1;
	}
	/*�������Զ�ͨ����֤*/
	else
	{
		$yz=0;
	}


	$username=filtrate($username);
	$content=filtrate($content);

	$content=str_replace("@@br@@","<br>",$content);

	//���˲���������
	$username=replace_bad_word($username);
	$content=replace_bad_word($content);

	//�������˶����������ʺ���������
	if($username)
	{
		$rs=$db->get_one(" SELECT $TB[uid] AS uid FROM $TB[table] WHERE $TB[username]='$username' ");
		if($rs[uid]!=$lfjuid)
		{
			$username="����";
		}
	}
	
	$rss=$db->get_one(" SELECT * FROM {$_pre}news WHERE id='$id' ");
	if(!$rss){
		showerr("ԭ���ݲ�����");
	}
	$fid=$rss[fid];

	$username || $username=$lfjid;
	
	
	if($allow){
		$db->query("INSERT INTO `{$_pre}newscomments` (`cuid`, `type`, `id`, `fid`, `uid`, `username`, `posttime`, `content`, `ip`, `icon`, `yz`) VALUES ('$rss[uid]','0','$id','$fid','$lfjuid','$username','$timestamp','$content','$onlineip','$icon','$yz')");
	
		$db->query(" UPDATE {$_pre}news SET comments=comments+1 WHERE id='$id' ");
	}
	refreshto("news.php?fid=$fid&id=$id","�����ɹ�");
	exit;

}elseif($job=='del'){

	$rs=$db->get_one("SELECT * FROM `{$_pre}newscomments` WHERE cid='$cid'");
	if(!$lfjuid)
	{
		showerr("�㻹û��¼,��Ȩ��");
	}
	elseif(!$web_admin&&$rs[uid]!=$lfjuid&&$rs[cuid]!=$lfjuid)
	{
		showerr("��ûȨ��");
	}
	if(!$web_admin&&$rs[uid]!=$lfjuid){
		$lfjdb[money]=get_money($lfjdb[uid]);
		if(abs($webdb[DelOtherCommentMoney])>$lfjdb[money]){
			showerr("���{$webdb[MoneyName]}����");
		}
		add_user($lfjdb[uid],-abs($webdb[DelOtherCommentMoney]));
	}
	$db->query(" DELETE FROM `{$_pre}newscomments` WHERE cid='$cid' ");
	$db->query("UPDATE {$_pre}news SET comments=comments-1 WHERE id='$rs[id]' ");
refreshto("news.php?fid=$fid&id=$id","�����ɹ�");
	exit;
}
?>