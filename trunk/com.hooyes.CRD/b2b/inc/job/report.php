<?php
!function_exists('html') && exit('ERR');
$rsdb=$db->get_one("SELECT * FROM {$pre}article WHERE aid='$id'");
if(!$id){
	showerr("���ݲ�����");
}
if($step==2)
{
	$rs=$db->get_one("SELECT * FROM `{$pre}report` WHERE `ip`='$onlineip'");
	if(!$web_admin&&($timestamp-$rs[posttime]<30)){
		showerr("��30����پٱ���Ϣ"); 
	}

	if(!$Type){
		showerr("��ѡ��һ������"); 
	}elseif(strlen($myname)>30){
		showerr("��ĳƺ����ܴ���30���ַ�"); 
	}
	$Title="���ԡ�{$webdb[webname]}�����ʼ�,�����ѡ�{$myname}�������Ƽ���һƪ��������!!";
	
	$rs[email]=$email;
	$content=filtrate($content);
	$Type=filtrate($Type);
	$Content=str_replace("\n","<br>",$Content);

	$db->query("INSERT INTO `{$pre}report` (  `aid` , `type` , `uid` , `name` , `content` , `posttime` , `ip` ) VALUES ('$id','$Type','$lfjuid','$myname','$content','$timestamp','$onlineip')");

	refreshto("$webdb[www_url]/","лл�㣬�ٱ�������Ϣ!",5);
}

require(PHP168_PATH."inc/head.php");
require(html("report"));
require(PHP168_PATH."inc/foot.php");
?>