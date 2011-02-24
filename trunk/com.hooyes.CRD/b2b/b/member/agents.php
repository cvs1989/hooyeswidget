<?php
require(dirname(__FILE__)."/"."global.php");
$rt=$db->get_one("select * from `{$_pre}company` where uid='$lfjuid'");
if(!$rt){	//无商家信息
	showerr("抱歉，您还没有登记商家信息。<br>点击这里【<a href='$Murl/post_company.php?' target=_blank>登记商家</a>】");
}

@include_once(Memberpath."../php168/companyData.php");
@include_once(Memberpath."../php168/all_brand.php");
if(!$action){
	
	$query=$db->query("select * from {$_pre}agents where uid='$lfjuid' order by posttime desc ");
	while($rs=$db->fetch_array($query)){
		$rs[posttime]=date("Y-m-d H:i",$rs[posttime]);
		$rs[ag_cert]=$webdb[www_url]."/".$webdb[updir]."/{$Imgdirname}/ag_cert/$rs[uid]/".$rs[ag_cert];
		
		$rs[status]=$rs[yz]?"通过审核<br>".date("Y-m-d H:i",$rs[yz_time]):"审核中...";
		if($rs[is_cancel]) $rs[status]="撤销中...";
		else $rs[cancel]="申请撤销";
		
		$rs[contact_info]=unserialize($rs[contact_info]);
		$rs[ag_level]=$ag_level_array[$rs[ag_level]];
		$listdb[]=$rs;	
	}
}elseif($action=='add'){

	$contact_info[name]=$rt[qy_contact];
	$contact_info[tel]=$rt[qy_contact_tel];
	$contact_info[fax]=$rt[qy_contact_fax];
	$contact_info[email]=$rt[qy_contact_email];
	$contact_info[address]=$rt[qy_address];


	//得到品牌
	
	$brand_options=get_brand_options(0);

}elseif($action=='cancel'){

	if(!$ag_id) showerr("请不要进行非法操作");
	$rsdb=$db->get_one("select * from {$_pre}agents where ag_id='$ag_id'");	
	if(!$rsdb) showerr("没有找到您要操作的项目");
	if($rsdb[uid]!=$lfjuid) showerr("您无权操作");
	
	$db->query("update {$_pre}agents set is_cancel=1 where ag_id='$ag_id' limit 1");
	refreshto("?","提交成功，请等待处理",1);
	
}elseif($action=='save_add'){	

	$ag_name=htmlspecialchars($ag_name);
	if(!$ag_name || strlen($ag_name)>40 || strlen($ag_name)<10)  showerr("代理名称必须填写(5-20个汉字)");
	
	foreach($contact_info as $key=>$val){$contact_info[$key]=htmlspecialchars($val);}
	if(!$contact_info[name]) showerr("联系人不能为空");
	if(!$contact_info[tel]) showerr("联系电话不能为空");
	if(!$contact_info[email] || strpos($contact_info[email],'@')===false) showerr("邮箱地址不能为空");
	if(!$contact_info[address]) showerr("详细地址不能为空");
	

	/* if(!$brand_name && !$brand_id){
		showerr("品牌必须选择或者填写");
	}
	if($brand_id){
		$brand_name=$Brand_db[name][$brand_id];
	} */
	//上传图片
	if(is_uploaded_file($_FILES[ag_cert][tmp_name])){
			
			$array[name]=is_array($ag_cert)?$_FILES[ag_cert][name]:$ag_cert_name;
			$picpre=explode(".",$array[name]);
			if(!in_array(strtolower($picpre[count($picpre)-1]),array('jpg','gif'))){ showerr("图片格式只能是jpg,gif");}
			
			$array[path]=$webdb[updir]."/{$Imgdirname}/ag_cert/$lfjuid/";
			
			$array[size]=is_array($ag_cert)?$_FILES[ag_cert][size]:$ag_cert_size;
			
			if($array[size]>500*1024) { showerr("图片文件大小在 500 kb 以内");}
			
			$picurl=upfile(is_array($ag_cert)?$_FILES[ag_cert][tmp_name]:$ag_cert,$array);
			
			if(!$picurl){
				showerr("图片上传失败,请稍后再试!");
			}
	}else{
			showerr("请上传代理商资格证书,格式为jpg,gif");
	}
	$contact_info=serialize($contact_info);
	$db->query("INSERT INTO `{$_pre}agents` ( `ag_id` , `uid` , `username` , `companyName` , `rid` , `yz` , `yz_time` , `posttime` ,`brand_name`,`brand_id`, `is_cancel` , `ag_cert` , `ag_name` , `ag_level` , `contact_info` ) VALUES ('', '$lfjuid', '$lfjid', '$rt[title]', '$rt[rid]', '0', '0', '$timestamp','$brand_name','$brand_id', '0', '$picurl', '$ag_name', '$ag_level', '$contact_info');");
	refreshto("?","提交成功，请等待审核",1);
}

require(dirname(__FILE__)."/"."head.php");
require(dirname(__FILE__)."/"."template/agents.htm");
require(dirname(__FILE__)."/"."foot.php");

function get_brand_options($ckbid=0){
 global $Brand_db;
	
	foreach($Brand_db[0] as $bid=>$brand){
		$ck=$bid==$ckbid?" selected":"";
		$brand_options.="<option value='$bid' $ck>$brand</option>";
		foreach($Brand_db[$bid] as $bid2=>$brand2){
			$ck2=$bid2==$ckbid?" selected":"";
			$brand_options.="<option value='$bid2' $ck2 style='color:#565656'>$brand2</option>";
		}
	}
	return $brand_options;
}
?>