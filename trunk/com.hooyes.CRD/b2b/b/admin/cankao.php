<?php
require_once("global.php");
@include_once(Adminpath."../php168/companyData.php");
$linkdb=array("δ��˵�"=>"?unyz=1","ȫ���ο�"=>"?");

if(!$action){
	$rows=10;
	$page=intval($page);
	if(!$page)$page=1;
	$min=($page-1)*$rows;
	
	$where=" where 1";
	if($keyword)$where.=" and companyName like('%$keyword%')";
	if($unyz) $where.=" and yz=0 ";
	$showpage=getpage("{$_pre}cankao",$where,"?unyz=$unyz&keyword=".urlencode($keyword),$rows);
	

	$query=$db->query("select * from {$_pre}cankao $where  limit $min,$rows");
	while($rs=$db->fetch_array($query)){
		
		$rs[yz]=!$rs[yz]?"δ���":"<font color=red>�����</font>";
		
		$rs[description]=get_word($rs[description],200);

		$listdb[]=$rs;	
	}

	require("head.php");
	require("template/cankao/list.htm");
	require("foot.php");

}elseif($action=='yz'){
	
	$rsdb=$db->get_one("select * from {$_pre}cankao where ck_id='$ck_id'");	
	if(!$rsdb) showerr("û���ҵ���Ҫ��������Ŀ");
	
	$yz=$rsdb[yz]?0:1;
	
	$db->query("update {$_pre}cankao set yz=$yz where ck_id='$ck_id' ");

	refreshto($FROMURL,"�����ɹ�",1);

}elseif($action=='del'){

	$rsdb=$db->get_one("select * from {$_pre}cankao where ck_id='$ck_id'");	
	if(!$rsdb) showerr("û���ҵ���Ҫ��������Ŀ");
	

	
	//����֪ͨ
	$array[touid]=$rsdb[uid];
	$array[fromuid]=0;
	$array[fromer]='ϵͳ��Ϣ';
	$array[title]='֪ͨ������վ��ο����ϲ���֪ͨ';
	$array[content]="{$rsdb[username]}����!<br>�����ύ��\"$rsdb[title]\"�ο�����δ��ͨ����ˣ��Ѿ���ɾ�����������Ҫ�������ٴ��ύ�� ";
	if(function_exists('pm_msgbox')){
		pm_msgbox($array);
	}
	//ִ��
    $db->query("delete from {$_pre}cankao where ck_id='$ck_id' limit 1");
	//��ȥ
	refreshto($FROMURL,"�����ɹ�",1);
}

?>