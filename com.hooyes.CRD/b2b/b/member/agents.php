<?php
require(dirname(__FILE__)."/"."global.php");
$rt=$db->get_one("select * from `{$_pre}company` where uid='$lfjuid'");
if(!$rt){	//���̼���Ϣ
	showerr("��Ǹ������û�еǼ��̼���Ϣ��<br>������<a href='$Murl/post_company.php?' target=_blank>�Ǽ��̼�</a>��");
}

@include_once(Memberpath."../php168/companyData.php");
@include_once(Memberpath."../php168/all_brand.php");
if(!$action){
	
	$query=$db->query("select * from {$_pre}agents where uid='$lfjuid' order by posttime desc ");
	while($rs=$db->fetch_array($query)){
		$rs[posttime]=date("Y-m-d H:i",$rs[posttime]);
		$rs[ag_cert]=$webdb[www_url]."/".$webdb[updir]."/{$Imgdirname}/ag_cert/$rs[uid]/".$rs[ag_cert];
		
		$rs[status]=$rs[yz]?"ͨ�����<br>".date("Y-m-d H:i",$rs[yz_time]):"�����...";
		if($rs[is_cancel]) $rs[status]="������...";
		else $rs[cancel]="���볷��";
		
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


	//�õ�Ʒ��
	
	$brand_options=get_brand_options(0);

}elseif($action=='cancel'){

	if(!$ag_id) showerr("�벻Ҫ���зǷ�����");
	$rsdb=$db->get_one("select * from {$_pre}agents where ag_id='$ag_id'");	
	if(!$rsdb) showerr("û���ҵ���Ҫ��������Ŀ");
	if($rsdb[uid]!=$lfjuid) showerr("����Ȩ����");
	
	$db->query("update {$_pre}agents set is_cancel=1 where ag_id='$ag_id' limit 1");
	refreshto("?","�ύ�ɹ�����ȴ�����",1);
	
}elseif($action=='save_add'){	

	$ag_name=htmlspecialchars($ag_name);
	if(!$ag_name || strlen($ag_name)>40 || strlen($ag_name)<10)  showerr("�������Ʊ�����д(5-20������)");
	
	foreach($contact_info as $key=>$val){$contact_info[$key]=htmlspecialchars($val);}
	if(!$contact_info[name]) showerr("��ϵ�˲���Ϊ��");
	if(!$contact_info[tel]) showerr("��ϵ�绰����Ϊ��");
	if(!$contact_info[email] || strpos($contact_info[email],'@')===false) showerr("�����ַ����Ϊ��");
	if(!$contact_info[address]) showerr("��ϸ��ַ����Ϊ��");
	

	/* if(!$brand_name && !$brand_id){
		showerr("Ʒ�Ʊ���ѡ�������д");
	}
	if($brand_id){
		$brand_name=$Brand_db[name][$brand_id];
	} */
	//�ϴ�ͼƬ
	if(is_uploaded_file($_FILES[ag_cert][tmp_name])){
			
			$array[name]=is_array($ag_cert)?$_FILES[ag_cert][name]:$ag_cert_name;
			$picpre=explode(".",$array[name]);
			if(!in_array(strtolower($picpre[count($picpre)-1]),array('jpg','gif'))){ showerr("ͼƬ��ʽֻ����jpg,gif");}
			
			$array[path]=$webdb[updir]."/{$Imgdirname}/ag_cert/$lfjuid/";
			
			$array[size]=is_array($ag_cert)?$_FILES[ag_cert][size]:$ag_cert_size;
			
			if($array[size]>500*1024) { showerr("ͼƬ�ļ���С�� 500 kb ����");}
			
			$picurl=upfile(is_array($ag_cert)?$_FILES[ag_cert][tmp_name]:$ag_cert,$array);
			
			if(!$picurl){
				showerr("ͼƬ�ϴ�ʧ��,���Ժ�����!");
			}
	}else{
			showerr("���ϴ��������ʸ�֤��,��ʽΪjpg,gif");
	}
	$contact_info=serialize($contact_info);
	$db->query("INSERT INTO `{$_pre}agents` ( `ag_id` , `uid` , `username` , `companyName` , `rid` , `yz` , `yz_time` , `posttime` ,`brand_name`,`brand_id`, `is_cancel` , `ag_cert` , `ag_name` , `ag_level` , `contact_info` ) VALUES ('', '$lfjuid', '$lfjid', '$rt[title]', '$rt[rid]', '0', '0', '$timestamp','$brand_name','$brand_id', '0', '$picurl', '$ag_name', '$ag_level', '$contact_info');");
	refreshto("?","�ύ�ɹ�����ȴ����",1);
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