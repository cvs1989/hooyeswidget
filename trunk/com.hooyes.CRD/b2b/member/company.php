<?php
require(dirname(__FILE__)."/"."global.php");
@include(dirname(__FILE__)."/../"."php168/all_area.php");
if($job=='edit'){
	if(!$lfjid){
		showerr("�㻹û��¼");
	}
	$cpDB=$db->get_one("SELECT * FROM `{$pre}memberdata_1` WHERE uid='$lfjuid'");
	if($step==2){
		foreach( $_POST AS $key=>$value){
			$_POST[$key]=filtrate($value);
		}
		@extract($_POST);
		if(!$cpname){showerr("��ҵ���Ʋ���Ϊ��");}
		if(!$cptype){showerr("��ѡ����ҵ����");}
		if(!$cptrade){showerr("��ѡ����ҵ������ҵ");}
		if(!$cpfounder){showerr("��ҵ���˲���Ϊ��");}
		if(!$cptelephone){showerr("��˾�绰����Ϊ��");}
		if(!$cpaddress){showerr("��˾��ַ����Ϊ��");}
		if(!$cpcity){showerr("��ѡ����ҵ���ڳ���");}
		if(!$cpcode){showerr("��֯�������벻��Ϊ��");}
		if(!ereg("^[0-9]{8}",$cpcode)){
			showerr("��������д��֯��������");	//��������ϸ���ƻ�����,�����һ��ɾ��
		}
		if(!$cpDB){
			$db->query("INSERT INTO `{$pre}memberdata_1` ( `uid` , `cpname` , `cplogo` , `cptype` , `cptrade` , `cpproduct` , `cpcity` , `cpfoundtime` , `cpfounder` , `cpmannum` , `cpmoney` , `cpcode` , `cppermit` , `cpweb` , `cppostcode` , `cptelephone` , `cpfax` , `cpaddress` ,`cplinkman`,`cpmobphone`,`cpqq`,`cpmsn`) VALUES ( '$lfjuid','$cpname','$cplogo','$cptype','$cptrade','$cpproduct','$cpcity','$cpfoundtime','$cpfounder','$cpmannum','$cpmoney','$cpcode','$cppermit','$cpweb','$cppostcode','$cptelephone','$cpfax','$cpaddress','$cplinkman','$cpmobphone','$cpqq','$cpmsn')");
			$grouptype=$webdb[AutoPassCompany]?'1':'-1';
			$db->query("UPDATE {$pre}memberdata SET grouptype='$grouptype' WHERE uid='$lfjuid'");
			refreshto("company.php?job=edit","��������Ѿ��ύ",1);	
		}else{
			$db->query("UPDATE {$pre}memberdata_1 SET cpname='$cpname',cplogo='$cplogo',cptype='$cptype',cptrade='$cptrade',cpproduct='$cpproduct',cpcity='$cpcity',cpfoundtime='$cpfoundtime',cpfounder='$cpfounder',cpmannum='$cpmannum',cpmoney='$cpmoney',cpcode='$cpcode',cppermit='$cppermit',cpweb='$cpweb',cppostcode='$cppostcode',cptelephone='$cptelephone',cpfax='$cpfax',cpaddress='$cpaddress',cplinkman='$cplinkman',cpmobphone='$cpmobphone',cpqq='$cpqq',cpmsn='$cpmsn' WHERE uid='$lfjuid'");
			refreshto("company.php?job=edit","�޸ĳɹ�",1);
		}			
	}
	$cptype[$cpDB[cptype]]=' selected ';	
}elseif($job=='view'){
	$cpDB=$db->get_one("SELECT * FROM `{$pre}memberdata_1` WHERE uid='$uid'");
	if(!$cpDB){
		showerr("���ϲ�����!!");
	}
}

require(dirname(__FILE__)."/"."head.php");
require(dirname(__FILE__)."/"."template/company.htm");
require(dirname(__FILE__)."/"."foot.php");

?>