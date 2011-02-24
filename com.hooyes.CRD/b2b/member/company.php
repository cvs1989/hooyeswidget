<?php
require(dirname(__FILE__)."/"."global.php");
@include(dirname(__FILE__)."/../"."php168/all_area.php");
if($job=='edit'){
	if(!$lfjid){
		showerr("你还没登录");
	}
	$cpDB=$db->get_one("SELECT * FROM `{$pre}memberdata_1` WHERE uid='$lfjuid'");
	if($step==2){
		foreach( $_POST AS $key=>$value){
			$_POST[$key]=filtrate($value);
		}
		@extract($_POST);
		if(!$cpname){showerr("企业名称不能为空");}
		if(!$cptype){showerr("请选择企业性质");}
		if(!$cptrade){showerr("请选择企业所属行业");}
		if(!$cpfounder){showerr("企业法人不能为空");}
		if(!$cptelephone){showerr("公司电话不能为空");}
		if(!$cpaddress){showerr("公司地址不能为空");}
		if(!$cpcity){showerr("请选择企业所在城市");}
		if(!$cpcode){showerr("组织机构代码不能为空");}
		if(!ereg("^[0-9]{8}",$cpcode)){
			showerr("请认真填写组织机构代码");	//如果不想严格控制机构码,请把这一行删除
		}
		if(!$cpDB){
			$db->query("INSERT INTO `{$pre}memberdata_1` ( `uid` , `cpname` , `cplogo` , `cptype` , `cptrade` , `cpproduct` , `cpcity` , `cpfoundtime` , `cpfounder` , `cpmannum` , `cpmoney` , `cpcode` , `cppermit` , `cpweb` , `cppostcode` , `cptelephone` , `cpfax` , `cpaddress` ,`cplinkman`,`cpmobphone`,`cpqq`,`cpmsn`) VALUES ( '$lfjuid','$cpname','$cplogo','$cptype','$cptrade','$cpproduct','$cpcity','$cpfoundtime','$cpfounder','$cpmannum','$cpmoney','$cpcode','$cppermit','$cpweb','$cppostcode','$cptelephone','$cpfax','$cpaddress','$cplinkman','$cpmobphone','$cpqq','$cpmsn')");
			$grouptype=$webdb[AutoPassCompany]?'1':'-1';
			$db->query("UPDATE {$pre}memberdata SET grouptype='$grouptype' WHERE uid='$lfjuid'");
			refreshto("company.php?job=edit","你的资料已经提交",1);	
		}else{
			$db->query("UPDATE {$pre}memberdata_1 SET cpname='$cpname',cplogo='$cplogo',cptype='$cptype',cptrade='$cptrade',cpproduct='$cpproduct',cpcity='$cpcity',cpfoundtime='$cpfoundtime',cpfounder='$cpfounder',cpmannum='$cpmannum',cpmoney='$cpmoney',cpcode='$cpcode',cppermit='$cppermit',cpweb='$cpweb',cppostcode='$cppostcode',cptelephone='$cptelephone',cpfax='$cpfax',cpaddress='$cpaddress',cplinkman='$cplinkman',cpmobphone='$cpmobphone',cpqq='$cpqq',cpmsn='$cpmsn' WHERE uid='$lfjuid'");
			refreshto("company.php?job=edit","修改成功",1);
		}			
	}
	$cptype[$cpDB[cptype]]=' selected ';	
}elseif($job=='view'){
	$cpDB=$db->get_one("SELECT * FROM `{$pre}memberdata_1` WHERE uid='$uid'");
	if(!$cpDB){
		showerr("资料不存在!!");
	}
}

require(dirname(__FILE__)."/"."head.php");
require(dirname(__FILE__)."/"."template/company.htm");
require(dirname(__FILE__)."/"."foot.php");

?>