<?php
require_once("global.php");

if(!$lfjuid){
	showerr('�㻹û�е�¼!!');
}

$fidDB=$db->get_one("SELECT A.* FROM {$_pre}sort A WHERE A.fid='$fid'");

if(!$fidDB){
	showerr("FID����!");
}
$_erp=$Fid_db[tableid][$fid];

$infodb=$db->get_one("SELECT B.*,A.*,D.email FROM `{$_pre}content$_erp` A LEFT JOIN `{$_pre}content_$fidDB[mid]` B ON A.id=B.id LEFT JOIN `{$pre}memberdata` D ON A.uid=D.uid WHERE A.id='$cid'");


if(!$infodb){
	showerr("���ݲ�����");
}elseif($infodb[fid]!=$fid){
	showerr("FID����!!!");
}elseif(!$web_admin&&$lfjuid==$infodb[uid]){
	showerr('�㲻���Լ����Լ��Ĳ�Ʒ����');
}



$mid=2;

/**
*ģ����������ļ�
**/
$field_db = $module_DB[$mid][field];


/**�����ύ���·�������**/
if($action=="postnew")
{
	if(!$web_admin){
		if($groupdb[post_baojiadian_num]<1){
			showerr('�������û��鲻�������۵�,�������û����');
		}
		$time=$timestamp-24*3600;
		$_rs=$db->get_one("SELECT COUNT(*) AS NUM FROM `{$_pre}join` WHERE uid='$lfjuid' AND posttime>$time");
		if($_rs[NUM]>$groupdb[post_baojiadian_num]){
			showerr('�������û���ÿ�췢���ı��۵����ܳ���{$groupdb[post_baojiadian_num]}��,�������û����');
		}
	}

	if(!check_imgnum($yzimg)){
		showerr("��֤�벻����");
	}

	//�Զ����ֶεĺϷ���������ݴ���
	$Module_db->checkpost($field_db,$postdb,'');


	/*������Ϣ���������*/
	$db->query("INSERT INTO `{$_pre}join` ( `mid` , `cid` , `cuid` , `fid` ,  `posttime` ,  `uid` , `username` , `yz` , `ip` ) 
	VALUES (
	'$mid','$cid','$infodb[uid]', '$fid','$timestamp','$lfjdb[uid]','$lfjdb[username]','0','$onlineip')");

	$id = $db->insert_id();

	unset($sqldb);
	$sqldb[]="id='$id'";
	$sqldb[]="fid='$fid'";
	$sqldb[]="uid='$lfjuid'";

	
	/*����жϸ���Ϣ��Ҫ������Щ�ֶε�����*/
	foreach( $field_db AS $key=>$value){
		isset($postdb[$key]) && $sqldb[]="`{$key}`='{$postdb[$key]}'";
	}

	$sql=implode(",",$sqldb);

	$db->query("INSERT INTO `{$_pre}content_$mid` SET $sql");


	if($webdb[order_send_mail]){
		send_mail($infodb[email],"�пͻ����㱨����","�뾡��鿴<A HREF='$Murl/member/joinshow.php?id=$id' target='_blank'>$Murl/member/joinshow.php?id=$id</A>",0);
	}
	if($webdb[order_send_msg]){
		send_msg($infodb[uid],"�пͻ����㱨����","�뾡��鿴<A HREF='$Murl/member/joinshow.php?id=$id' target='_blank'>$Murl/member/joinshow.php?id=$id</A>");
	}

	refreshto("bencandy.php?fid=$fid&id=$cid","���۵��Ѿ�����,��ȴ���Ӧ!");
	
}

/*ɾ������,ֱ��ɾ��,������*/
elseif($action=="del")
{
	del_order($id);
	refreshto("bencandy.php?fid=$fid&id=$cid","ɾ���ɹ�");
}

/*�༭����*/
elseif($job=="edit")
{
	$rsdb=$db->get_one("SELECT A.*,B.* FROM `{$_pre}join` A LEFT JOIN `{$_pre}content_$mid` B ON A.id=B.id WHERE A.id='$id'");

	if($rsdb[uid]!=$lfjuid&&!$web_admin)
	{
		showerr("����Ȩ�޸�");
	}

	$hownum=$rsdb[shopnum];

	/*��Ĭ�ϱ���������*/
	$Module_db->formGetVale($field_db,$rsdb);

	$atc="edit";	

	require(ROOT_PATH."inc/head.php");
	require(getTpl("post_$mid",$FidTpl['post']));
	require(ROOT_PATH."inc/foot.php");
}

/*�����ύ���������޸�*/
elseif($action=="edit")
{
	if(!check_imgnum($yzimg)){
		showerr("��֤�벻����");
	}

	$rsdb=$db->get_one("SELECT A.*,B.* FROM `{$_pre}join` A LEFT JOIN `{$_pre}content_$mid` B ON A.id=B.id WHERE A.id='$id' LIMIT 1");

	if($rsdb[uid]!=$lfjuid&&!$web_admin)
	{
		showerr("����Ȩ�޸�");
	}

	//�Զ����ֶεĺϷ���������ݴ���
	$Module_db->checkpost($field_db,$postdb,$rsdb);


	/*��������Ϣ������*/
	//$db->query("UPDATE `{$_pre}join` SET title='$postdb[title]' WHERE id='$id'");


	/*����жϸ���Ϣ��Ҫ������Щ�ֶε�����*/
	unset($sqldb);
	foreach( $field_db AS $key=>$value){
		$sqldb[]="`$key`='{$postdb[$key]}'";
	}	
	$sql=implode(",",$sqldb);

	/*���¸���Ϣ��*/
	$db->query("UPDATE `{$_pre}content_$mid` SET $sql WHERE id='$id'");
	
	refreshto("bencandy.php?fid=$fid&id=$cid","�޸ĳɹ�");
}
else
{
	/*ģ������ʱ,��Щ�ֶ���Ĭ��ֵ*/
	foreach( $field_db AS $key=>$rs){	
		if($rs[form_value]){		
			$rsdb[$key]=$rs[form_value];
		}
	}

	/*��Ĭ�ϱ���������*/
	$Module_db->formGetVale($field_db,$rsdb);

	$atc="postnew";
	
	$rsdb[ask_title] = "�Ҷ��������ġ�{$infodb[title]}���ܸ���Ȥ";
	$rsdb[hope_price] = $infodb[price];
	
	require(ROOT_PATH."inc/head.php");
	require(getTpl("post_$mid"));
	require(ROOT_PATH."inc/foot.php");
}

?>