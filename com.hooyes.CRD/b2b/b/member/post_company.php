<?php
require_once(dirname(__FILE__)."/../global.php");
require_once(dirname(__FILE__)."/../bd_pics.php");

$post_company=1;//ʹ����Ŀѡ��ʱ���Զ�ѡ

if($action=='add'){
	
	if(count($fids)<1)showerr_post("����ѡ��һ������");
	
	//��������
	$ifids = array();
	foreach($fids as $key){
		$key = intval($key);
		if($key) $ifids[] = $key;
	}

	if(!$postdb[province_id]) showerr_post("��ѡ��ʡ"); 
	if(!$postdb[city_id]) showerr_post("��ѡ����"); 		
		
	if(!$postdb[title]) showerr_post("�����빫˾ȫ��");
	if(!$postdb[qy_regmoney]) showerr_post("�����빫˾ע���ʱ�");
	if(!$postdb[content]) showerr_post("��ϸ�̼ҽ��ܲ���Ϊ��");
	$postdb[content]=nl2br($postdb[content]);
	if(!$postdb[qy_contact_tel]) showerr_post("ָ����ϵ�˵绰����Ϊ��");
	if(!$postdb[qy_contact]) showerr_post("ָ����ϵ�˲���Ϊ��");
	if(!$postdb[qy_contact_email]) showerr_post("ָ����ϵ�������ַ����Ϊ��");
	foreach($postdb as $key=>$val){//ȫ�����ݴ���
			$postdb[$key]=ReplaceHtmlAndJs($val);
	}

	//ͼƬ����
	if(is_uploaded_file($_FILES[postfile][tmp_name])){
		$array[name]=is_array($postfile)?$_FILES[postfile][name]:$postfile_name;
		
		$array[path]=$webdb[updir]."/{$Imgdirname}/ico/";//�̼�ͼƬ���
				
		$array[size]=is_array($postfile)?$_FILES[postfile][size]:$postfile_size;
		$picurl=upfile_func2(is_array($postfile)?$_FILES[postfile][tmp_name]:$postfile,$array);
		if(substr($picurl,0,3)=='ERR')	{
			showerr_post(str_replace("ERR-","",$picurl));	
		}
				
		if($picurl){//����ͼƬ
						@unlink(PHP168_PATH.$array[path].$oldfile);
		}else{
						$picurl=$oldfile;
		}
		//�̼�LOGO
		if($picurl){
				$Newpicpath=PHP168_PATH."$array[path]/logo_{$picurl}";
				gdpic(PHP168_PATH."$array[path]/$picurl",$Newpicpath,120,120);
				if(!file_exists($Newpicpath)){
					copy(PHP168_PATH."$array[path]/{$picurl}",$Newpicpath);
				}else{
					@unlink(PHP168_PATH."$array[path]/{$picurl}");
					$picurl="logo_{$picurl}";
				}
		}
		
	}else{
		$picurl="";
	}
	
	//д�����ݿ�
	$postdb[yz]=$webdb[postauto_yz]?$webdb[postauto_yz]:0;
	$yz=$postdb[yz];

	$yz=$webdb[postcompanyauto_yz]?$webdb[postcompanyauto_yz]:0;
		$db->query("INSERT INTO `{$_pre}company` ( `rid` , `title` , `fname` , `uid` , `username` , `posttime` , `listorder` , `picurl` , `yz` , `yzer` , `yztime` , `content` , `province_id` , `city_id` , `qy_cate` , `qy_saletype` , `qy_regmoney` , `qy_createtime` , `qy_regplace` , `qy_address` , `qy_postnum` , `qy_pro_ser` , `my_buy` , `my_trade` , `qy_contact`,`qy_contact_zhiwei` , `qy_contact_sex` , `qy_contact_tel` , `qy_contact_mobile` , `qy_contact_fax` , `qy_contact_email` , `qy_website` , `qq` , `msn` , `skype` ) 
VALUES (
'', '$postdb[title]', '$fname', '$lfjuid', '$lfjid', '".$timestamp."', '0', '$picurl', '$yz', '', '".$timestamp."', '$postdb[content]', '$postdb[province_id]', '$postdb[city_id]', '$postdb[qy_cate]', '$postdb[qy_saletype]', '$postdb[qy_regmoney]', '$postdb[qy_createtime]', '$postdb[qy_regplace]', '$postdb[qy_address]', '$postdb[qy_postnum]', '$postdb[qy_pro_ser]', '$postdb[my_buy]', '$postdb[my_trade]', '$postdb[qy_contact]', '$postdb[qy_contact_zhiwei]', '$postdb[qy_contact_sex]', '$postdb[qy_contact_tel]', '$postdb[qy_contact_mobile]', '$postdb[qy_contact_fax]', '$postdb[qy_contact_email]', '$postdb[qy_website]', '$postdb[qq]', '$postdb[msn]', '$postdb[skype]');");
	
	$id = $db->insert_id();
	
	foreach($ifids as $v){
		$db->query("INSERT INTO {$_pre}company_fid (cid, fid) VALUES($id, $v)");
	}
		
	//��������
	if($webdb[company_add_money]) plus_money($lfjuid,$webdb[company_add_money]);
		
	parent_goto("?action=ok","");//�ɹ�

}elseif($action=='ok'){
		
		$msg="��ϲ�����̼ҵǼǳɹ���!";

		$do[0]['text']="����鿴��������";$do[0]['target']=" target=_blank";
		$do[0]['link']="$Mdomain/homepage.php?uid=$lfjuid";
		

		
		//$do[1]['text']="��˽������̹������";
		//$do[1]['link']="homepage_ctrl.php";
		
		//�Զ������̼�ҳ��

		//@sockOpenUrl('$Mdomain/homepage.php?uid=$lfjuid');
		//file_get_contents('$Mdomain/homepage.php?uid=$lfjuid');

}else{
	
	$rt=$db->get_one("select uid,rid,title from `{$_pre}company` where uid='$lfjuid'");
	if($rt[rid]){
		//showerr("��Ǹ�����Ѿ��Ǽ��̼ҡ�<a href='$Mdomain/homepage.php?uid=$rt[uid]' target='_blank'>$rt[title]</a>��,<br>�����ظ��Ǽǡ�");
		$action='ok';
		$do[0]['text']="���ѵǼǹ��̼���Ϣ��,����鿴��������";$do[0]['target']=" target=_blank";
		$do[0]['link']="$Mdomain/homepage.php?uid=$lfjuid";
		
	}else{
		$area_choose=select_where('province',"'postdb[province_id]'  onchange='showcity(this)' style='width:100px;'",0,0);
		$area_choose=$area_choose."<span id='city_span'></span>";
		$webdb[maxCompanyFidNum]=$webdb[maxCompanyFidNum]?$webdb[maxCompanyFidNum]:10;
	} 
	
		
}

function showerr_post($msg,$html_id=''){
		echo "<SCRIPT LANGUAGE=\"JavaScript\">
			<!--
			alert(\"$msg\");
			parent.document.getElementById('post_showmsg').innerHTML=\"<strong>$msg</strong>\";	
			parent.document.getElementById('postSubmit').disabled=false;	
			//-->
			</SCRIPT>";exit;
}
function parent_goto($url,$msg=''){

	echo "<SCRIPT LANGUAGE=\"JavaScript\">
			<!--
			";
	if($msg!=''){
		echo "alert('$msg');";
	}
	echo    "
			
			parent.location='$url';	
			parent.location.href='$url';	
		
			//-->
			</SCRIPT>";exit;
}

require(Mpath."inc/categories.php");
		
$bcategory->cache_read();
$categories = $bcategory->unsets(true);
unset($bcategory);

$member_style=$webdb[sys_member_style]?$webdb[sys_member_style]:($webdb[member_style]?$webdb[member_style]:"images2");

require(dirname(__FILE__)."/"."head.php");
require(dirname(__FILE__)."/"."template/post_company.htm");
require(dirname(__FILE__)."/"."foot.php");
?>