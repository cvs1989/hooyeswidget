<?php
require_once("global.php");

//�����ж�

$linkdb=array(
"����ģ�͹���"=>"?",
);

$cols_type=array(
'text'=>"�����ı���[��]",
'langtext'=>"�����ı���[��]",
'textarea'=>"�����ı���",
'radio'=>"��ѡ��",
'checkbox'=>"��ѡ��",
'select'=>"�����б�"
);


if(!$job && !$action){

	$query = $db->query("SELECT * FROM {$_pre}parameters_module ORDER BY listorder DESC");
	while($rs = $db->fetch_array($query)){
		$listdb[]=$rs;
	}


	require("head.php");
	require("template/parameters/list.htm");
	require("foot.php");

}elseif($action=='orderlist'){

	if($listorder){
		foreach($listorder as $mid=>$val){
			$db->query("update {$_pre}parameters_module  set listorder='$val' where mid='$mid'");
		}		
	}
	refreshto("?","���³ɹ�");

}elseif($action=='add'){
	
	if(!$name) showerr("��������ģ�͵�����");
	$db->query("INSERT INTO `{$_pre}parameters_module` ( `mid` , `name` , `alias` , `listorder` , `config` )VALUES ('', '$name', '$name', '0', '');");
	$mid=$db->insert_id();
	refreshto("?job=edit&mid=$mid","�����ɹ�",0);


}elseif($job=="edit"){

	if(!$mid) showerr("��������");
	$rsdb=$db->get_one("select * from `{$_pre}parameters_module` where mid='$mid' ");

	require("head.php");
	require("template/parameters/edit.htm");
	require("foot.php");


}elseif($action=='edit'){

	if(!$mid) showerr("��������");
	$db->query("update {$_pre}parameters_module set
	name='$name',
	alias='$alias',
	listorder='$val'
	where mid='$mid'");
	refreshto("?","���³ɹ�");

}elseif($job=="del"){

	if(!$mid) showerr("��������");
	$db->query("delete from {$_pre}parameters_module where mid='$mid'");

	//�Ƿ�ͬʱɾ������
	if($deldata){
		$db->query("delete from {$_pre}parameters where mid='$mid'");
	}
	refreshto("?","ɾ���ɹ�");

}elseif($job=='colslist'){

	if(!$mid) showerr("��������");
	$middb=$db->get_one("select * from `{$_pre}parameters_module` where mid='$mid' ");
	//�õ����Լ���
	
	$cols=unserialize($middb[config]);

	require("head.php");
	require("template/parameters/colslist.htm");
	require("foot.php");


}elseif($action=='addcolslist'){

	
	if(!$mid) showerr("��������");


	if($cols){

		foreach($cols as $key=>$rs){
			if($rs[name] && $rs[type]){
				$yes_cols[]=$rs;
			}
		}
		$db->query("update {$_pre}parameters_module  set config='".serialize($yes_cols)."' where mid='$mid'");
	}
	refreshto("?job=colslist&mid=$mid","���óɹ�");

}



?>