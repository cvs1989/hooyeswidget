<?php
!function_exists('html') && exit('ERR');

//�г����й��
if($job=="listad"&&$Apower[sellad]){
	$query = $db->query("SELECT * FROM `{$pre}sellad` ORDER BY list DESC");
	while($rs = $db->fetch_array($query)){
		$_s1=$db->get_one("SELECT COUNT(*) AS Num FROM `{$pre}sellad_user` WHERE id='$rs[id]'");
		$rs[AllAdNum]=$_s1[Num];
		$_s2=$db->get_one("SELECT COUNT(*) AS Num FROM `{$pre}sellad_user` WHERE id='$rs[id]' AND endtime>$timestamp");
		$rs[AdNum]=$_s2[Num];
		$rs[isclose]=$rs[isclose]?'�ر�':'����';
		$listdb[]=$rs;
	}
	require(dirname(__FILE__)."/"."head.php");
	require(dirname(__FILE__)."/"."template/sellad/menu.htm");
	require(dirname(__FILE__)."/"."template/sellad/listad.htm");
	require(dirname(__FILE__)."/"."foot.php");
}

//��ӹ��
elseif($job=="addplace"&&$Apower[sellad])
{
	require(dirname(__FILE__)."/"."head.php");
	require(dirname(__FILE__)."/"."template/sellad/menu.htm");
	require(dirname(__FILE__)."/"."template/sellad/addplace.htm");
	require(dirname(__FILE__)."/"."foot.php");
}

//�޸Ĺ��
elseif($job=="editadplace"&&$Apower[sellad])
{
	$rsdb=$db->get_one("SELECT * FROM `{$pre}sellad` WHERE id='$id'");
	$isclose[intval($rsdb[isclose])]=" checked ";
	require(dirname(__FILE__)."/"."head.php");
	require(dirname(__FILE__)."/"."template/sellad/menu.htm");
	require(dirname(__FILE__)."/"."template/sellad/addplace.htm");
	require(dirname(__FILE__)."/"."foot.php");
}

//�����޸Ĺ��
elseif($action=="editadplace"&&$Apower[sellad])
{
	if($postdb[day]<1){
		showmsg("��ЧͶ����������С��1��");
	}
	if($postdb[price]<1){
		showmsg("�����۲���С��1");
	}
	$db->query("UPDATE `{$pre}sellad` SET name='$postdb[name]',price='$postdb[price]',day='$postdb[day]',isclose='$isclose',adnum='$postdb[adnum]',wordnum='$postdb[wordnum]',list='$postdb[list]',demourl='$postdb[demourl]' WHERE id='$id' ");
	
	jump("�޸ĳɹ�","index.php?lfj=sellad&job=listad",1);
}

//������ӹ��
elseif($action=="addplace"&&$Apower[sellad])
{

	if(!$IS_BIZPhp168){
		@extract($db->get_one("SELECT COUNT(*) AS NUM FROM {$pre}sellad"));
		if($NUM>19){
			showerr("��Ѱ����ֻ�ܴ���20��");
		}
	}

	if($postdb[day]<1){
		showmsg("��ЧͶ����������С��1��");
	}
	if($postdb[price]<1){
		showmsg("�����۲���С��1");
	}
	$db->query("INSERT INTO `{$pre}sellad` (`name` , `price` , `day`, `adnum`, `wordnum`, `demourl`) VALUES ('$postdb[name]','$postdb[price]','$postdb[day]','$postdb[adnum]','$postdb[wordnum]','$postdb[demourl]')");	
				
	jump("��ӳɹ�","?lfj=$lfj&job=listad",1);
}

//ɾ�����
elseif($action=='deleteadplace'&&$Apower[sellad])
{
	$db->query("DELETE FROM `{$pre}sellad` WHERE id='$id'");
	$db->query("DELETE FROM `{$pre}sellad_user` WHERE id='$id'");
	jump("ɾ���ɹ�","$FROMURL",1);
}

elseif($job=="listuser"&&$Apower[sellad_listuser])
{
	if($page<1){
		$page=1;
	}
	$rows=20;
	$min=($page-1)*$rows;
	if($id){
		$SQL=" WHERE A.id='$id' ";
	}
	$showpage=getpage("`{$pre}sellad_user` A","$SQL","?job=$job",$rows);
	$query = $db->query("SELECT A.*,B.* FROM `{$pre}sellad_user` A LEFT JOIN `{$pre}sellad` B ON A.id=B.id $SQL ORDER BY A.endtime DESC LIMIT $min,$rows");
	while($rs = $db->fetch_array($query)){
		$rs[begintime]=date("Y-m-d H:i",$rs[begintime]);
		$rs[endtime]=date("Y-m-d H:i",$rs[endtime]);
		$listdb[]=$rs;
	}
	require("head.php");
	require("template/sellad/listuser.htm");
	require("foot.php");
}

elseif($action=="deleteusr"&&$Apower[sellad_listuser])
{
	$db->query("DELETE FROM `{$pre}sellad_user` WHERE ad_id='$ad_id'");
	jump("ɾ���ɹ�","$FROMURL",1);
}

?>