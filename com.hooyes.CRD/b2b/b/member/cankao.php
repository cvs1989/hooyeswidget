<?php
require(dirname(__FILE__)."/"."global.php");
$rt=$db->get_one("select renzheng,title,uid,rid from `{$_pre}company` where uid='$lfjuid'");
if(!$rt){	//无商家信息
	showerr("抱歉，您还没有登记商家信息。<br>点击这里【<a href='$Murl/post_company.php?' target=_blank>登记商家</a>】");
}
	$webdb[vip_par_payfor]=$webdb[vip_par_payfor]?$webdb[vip_par_payfor]:50;
	$webdb[vip_min_long]=$webdb[vip_min_long]?$webdb[vip_min_long]:1;


if(!$action){
	$page=abs(intval($page));
	$page=$page?$page:1;
	$rows=10;
	$min=($page-1)*$rows;
	$where=" where uid='$lfjuid' ";
	$query=$db->query("select * from {$_pre}cankao $where  limit $min,$rows");
	$showpage=getpage("{$_pre}cankao",$where,"?",$rows);
	while($rs=$db->fetch_array($query)){
		
		$rs[yz]=!$rs[yz]?"未审核":"<font color=red>已审核</font>";

		$listdb[]=$rs;
	}

}elseif($action=='add'){
	if($ck_id){
		$rsdb=$db->get_one("select * from {$_pre}cankao where ck_id='$ck_id' limit 1");
	}else{
	
	}
		
}elseif($action=='save_add'){	

	if(!$title || strlen($title)>40) showerr("标题不能为空，且只能是40个字符");
	if(!$url || strtolower(substr($url,0,4)!='http')) showerr("参考地址不能为空，且必须以http开头");
	if(strlen($description)>400)showerr("描述最多400个字符");
	if($ck_id){
		$db->query("update `{$_pre}cankao` set
		title='$title',
		url='$url',
		description='$description',
		yz=0
		where ck_id='$ck_id';");
	}else{
		$db->query("INSERT INTO `{$_pre}cankao` ( `ck_id` , `uid` , `username` , `rid` , `companyName` , `title` , `url` , `description` , `yz` ) 
VALUES ('', '$lfjuid', '$lfjid', '$rt[rid]', '$rt[title]', '$title', '$url', '$description', '0');");
	}
	//跳转
	refreshto("?","保存成功",1);
}elseif($action=='del'){	
	
	if($ck_id){
		$rsdb=$db->get_one("delete from {$_pre}cankao where ck_id='$ck_id' limit 1");
	}
	refreshto("?","操作成功",1);
}

require(dirname(__FILE__)."/"."head.php");
require(dirname(__FILE__)."/"."template/cankao.htm");
require(dirname(__FILE__)."/"."foot.php");

?>