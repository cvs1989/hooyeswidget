<?php
require("global.php");
@require_once(Mpath."php168/form_data.php");

if(!$action) showerr("δ֪����");

if($action=='form1'){/////////////////////////////////////////////ѯ��
	/*�ж�ID*/
	if(!$ids) showerr("δָ��������");
	if(is_array($ids)){
		$ids_str=implode(",",$ids);
		$action_name="����ѯ��";
	}else{
	    $ids_str=$ids;
		if(!is_numeric($ids_str)) showerr("����������");
		$action_name="����ѯ��";
	}
	$ids_str=str_replace(array(",,",",,,",),array(",",","),$ids_str);
	$ids_str=str_replace(array(",,",",,,",),array(",",","),$ids_str); //��ֹ
	
	/*������Ӧ����*/
	if($ids_str){
	$query=$db->query("SELECT A.title,A.id,A.fid,A.uid,A.username ,B.title AS owner_name FROM {$_pre}content_sell A LEFT JOIN {$_pre}company B ON B.uid=A.uid WHERE A.id in($ids_str)");
	}	
	while($rs=$db->fetch_array($query)){
		$rs[title_short]=get_word($rs[title],70);
		$listdb[]=$rs;
	}
	$listnum=count($listdb);
	$wantinfo_htm=create_wantinfo($wantinfo);
	$addinfo_htm=create_addinfo($addinfo);
	
	if($lfjuid){
		$contact_info=$db->get_one("SELECT * FROM `{$_pre}company` WHERE uid='$lfjuid'");
		$contact_info[qq]=explode(",",$contact_info[qq]);
		$contact_info[qq]=$contact_info[qq][0];
	}
	require(Mpath."inc/head.php");
	require(getTpl("form1"));
	require(Mpath."inc/foot.php");

}elseif($action=='subform1'){ ////////////////////////////////////����ѯ��

	if(!$ids_str) showerr("��ָ��ѯ�۽��ܷ�.");
	if(!$contact_info[truename]) showerr("��������ϵ������");
	if(!$contact_info[company_name]) showerr("�����빫˾����");
	if(!$contact_info[qy_contact_tel]) showerr("������̶��绰");
	if(!$contact_info[qy_contact_email]) showerr("�����������ַ");
	
	if($quantity){
		if(!is_numeric($quantity))showerr("������������������");
	}
	$quantity    =abs(intval($quantity));
	$hope_price  =floatval($hope_price);
	$add_content =htmlspecialchars($add_content);
	if($hopereply_time){if(!preg_match("/[0-9]{4}-[0-9]{1,2}-[0-9]{1,2}/",$hopereply_time)) showerr("ϣ���ظ������ڲ����ϸ�ʽ,��ʽΪ:".date("Y-m-d"));}
	$get_info    =implode(',',$get_info);
	foreach($contact_info as $key=>$val){$contact_info[$key]=htmlspecialchars($val);}
	$contact_info=serialize($contact_info);

	//���ÿ���û���෢��
	
	$rt=$db->get_one("SELECT COUNT(*) AS num FROM `{$_pre}form1` WHERE ".($lfjuid?"`owner_uid`='$lfjuid'":"`from_ip`='$onlineip'")." AND `posttime` > ".(24*60*60).";");
	if($rt[num]>=$webdb[freeSentform1]) showerr("24Сʱ����ֻ�ܷ���{$webdb[freeSentform1]}��ѯ�۵�");


	foreach($titles as $key=>$title){
		$title=htmlspecialchars($title);
		$title=get_word($title,120);
		$db->query("INSERT INTO `{$_pre}form1` ( `id` , `info_id` , `owner_uid` , `owner_username` , `from_uid` , `from_username` ,`from_ip`, `title` , `quantity` , `hope_price` , `get_info` , `add_content` , `hopereply_time` , `posttime` , `is_reply` , `reply_content` , `reply_time` , `contact_info` ) VALUES ('', '$key', '$owner_uid[$key]', '$owner_username[$key]', '$lfjuid', '$lfjid','$onlineip', '$title', '$quantity', '$hope_price', '$get_info', '$add_content', '$hopereply_time', '".time()."', '0', '', '0', '$contact_info');");
		$db->query("UPDATE `{$_pre}content_1` SET `xunjia_num`=`xunjia_num`+1 WHERE id='$key'");//���±�������
	}

	echo '<meta http-equiv="Content-Type" content="text/html; charset=gb2312">';
	echo "<script>alert('�ɹ�������".count($titles)."ѯ�۵�');
	if(!window.close()){window.location='./';}
	</script>";

}elseif($action=='form2'){ ///////////////////////////////////////����
	
	/*�ж�ID*/
	if(!$ids) showerr("δָ��������");
	if(is_array($ids)){
		$ids_str=implode(",",$ids);
		$action_name="��������";
	}else{
	    $ids_str=$ids;
		$action_name="��������";
	}
	/*������Ӧ����*/
	if($ids_str){
	$query=$db->query("select A.title,A.id,A.fid,A.uid,A.username ,B.title as owner_name from {$_pre}content_buy A left join {$_pre}company B on B.uid=A.uid where A.id in($ids_str)");
	}	
	while($rs=$db->fetch_array($query)){
		$rs[title_short]=get_word($rs[title],70);
		$listdb[]=$rs;
	}
	$listnum=count($listdb);
	
	if($lfjuid){
		$contact_info=$db->get_one("select * from `{$_pre}company` where uid='$lfjuid'");
		$contact_info[qq]=explode(",",$contact_info[qq]);
		$contact_info[qq]=$contact_info[qq][0];
	}
	require(Mpath."inc/head.php");
	require(getTpl("form2"));
	require(Mpath."inc/foot.php");

}elseif($action=='subform2'){ ////////////////////////////////////���汨�۵�
	
	if(!$ids_str) showerr("��ָ��ѯ�۽��ܷ�.");
	if(!$put_price) showerr("�����۸������д.");
	if(!$contact_info[truename]) showerr("��������ϵ������");
	if(!$contact_info[company_name]) showerr("�����빫˾����");
	if(!$contact_info[qy_contact_tel]) showerr("������̶��绰");
	if(!$contact_info[qy_contact_email]) showerr("�����������ַ");
	
	if($quantity){
		if(!is_numeric($quantity))showerr("����������������������");
	}
	$quantity    =abs(intval($quantity));
	$put_price  =floatval($put_price);
	$add_content =htmlspecialchars($add_content);
	if($hopereply_time){if(!preg_match("/[0-9]{4}-[0-9]{1,2}-[0-9]{1,2}/",$hopereply_time)) showerr("ϣ���ظ������ڲ����ϸ�ʽ,��ʽΪ:".date("Y-m-d"));}
	$get_info    =implode(',',$get_info);
	foreach($contact_info as $key=>$val){$contact_info[$key]=htmlspecialchars($val);}
	$contact_info=serialize($contact_info);
	//���ÿ���û���෢��
	$rt=$db->get_one("select count(*) as num from `{$_pre}form2` where ".($lfjuid?"`owner_uid`='$lfjuid'":"`from_ip`='$onlineip'")." and `posttime` > ".(24*60*60).";");
	if($rt[num]>=$webdb[freeSentform2]) showerr("24Сʱ����ֻ�ܷ���{$webdb[freeSentform1]}�����۵�");
	foreach($titles as $key=>$title){
		$title=htmlspecialchars($title);
		$title=get_word($title,120);
		$db->query("INSERT INTO `{$_pre}form2` ( `id` , `info_id` , `owner_uid` , `owner_username` , `from_uid` , `from_username`,`from_ip` , `title` , `quantity` , `put_price` ,  `add_content` ,`cankao`, `hopereply_time` , `posttime` , `is_reply` , `reply_content` , `reply_time` , `contact_info` ) VALUES ('', '$key', '$owner_uid[$key]', '$owner_username[$key]', '$lfjuid', '$lfjid','$onlineip', '$title', '$quantity', '$put_price',  '$add_content','$cankao', '$hopereply_time', '".time()."', '0', '', '0', '$contact_info');");
		$db->query("update `{$_pre}content_2` set `baojia_num`=`baojia_num`+1 where id='$key'");//���±�������
		
	}
	echo '<meta http-equiv="Content-Type" content="text/html; charset=gb2312">';
	echo "<script>alert('�ɹ�������".count($titles)."���۵�');
	if(!window.close()){window.location='./';}
	</script>";

}elseif($action=='form3'){ ///////////////////////////////////////�̼ҶԱ�
	
	/*�ж�ID*/
	if(!$ids_str){
	if(!$ids) showerr("δָ��������");
		$ids_str=implode(",",$ids);
		$action_name="�Ա��̼�";
	}else{
		$ids_str=str_replace($removeid,'0',$ids_str);
	}
	/*������Ӧ����*/
	if($ids_str){
	$query=$db->query("select A.* from {$_pre}company A  where A.rid in($ids_str)");
	}	
	while($rs=$db->fetch_array($query)){
	
		$rs[picurl]=getimgdir($rs[picurl],3);
		$rs[picurl]="<img src='".$rs[picurl]."' border=0 width='120' onerror=\"this.style.display='none';\">";
		$rs[title]="<a href='$Mdomain/homepage.php?uid=$rs[uid]' target='_blank'><b>".get_word($rs[title],70)."</b></a>";
	
		$rs[content]=@preg_replace('/<([^>]*)>/is',"",$rs[content]);	//��HTML������˵�
		$rs[content]=get_word($rs[content],200);
		$rs[fid_all]=GuideFid($rs[fid_all]);
		$rs[fid_all]=@preg_replace('/<([^>]*)>/is',"",$rs[fid_all]);
		$rs[renzheng]=getrenzheng($rs[renzheng]);
		$rs[posttime]="�Ǽ�ʱ��:".date("Y-m-d",$rs[posttime]);
		$rs[city_id]="���ڵ�����{$area_DB[name][$city_DB[fup][$rs[city_id]]]}/{$city_DB[name][$rs[city_id]]}";
		$rs[qy_cate]="�̼����ͣ�".$rs[qy_cate];
		$rs[qy_saletype]="�������ͣ�".$rs[qy_saletype];
		$rs[qy_regmoney]="ע���ʱ���".$rs[qy_regmoney]."��Ԫ";
		$rs[qy_createtime]="������˾��".$rs[qy_createtime];
		$rs[qy_regplace]="ע�������".$rs[qy_regplace];

		$rs[username]="ϵͳ�ʺţ�".$rs[username];
		
		$listdb[]=$rs;
		$showlist=array("title","content","fid_all","username","renzheng","posttime","picurl","city_id","qy_cate","qy_saletype","qy_regmoney","qy_createtime","qy_regplace");
		foreach($rs as $key=>$val){
			if(in_array($key,$showlist)){
			
			$data[$key][]=$val;
			}
		}
		
	}
	$listnum=count($listdb);
	
	
	require(Mpath."inc/head.php");
	require(getTpl("form3"));
	require(Mpath."inc/foot.php");
}elseif($action=='subform3'){ ////////////////////////////////////�����ղ�

	if(!$lfjid){
		showerr("���ȵ�¼");
	}elseif(!$ids_str){
		showerr("ID������");
	}
	$ids=explode(",",$ids_str);
	$ctype=3;//ǿ����3
	if(is_array($ids)){
		
		foreach($ids as $id){
			if($db->get_one("SELECT * FROM `{$_pre}collection` WHERE `id`='$id' AND uid='$lfjuid'")){
				showerr("�벻Ҫ�ظ��ղر�����Ϣ",1); 
			}
			if(!$web_admin){
				if($webdb[Info_CollectArticleNum]<1){
					$webdb[Info_CollectArticleNum]=50;
				}
				$rs=$db->get_one("SELECT COUNT(*) AS NUM FROM `{$_pre}collection` WHERE uid='$lfjuid'");
				if($rs[NUM]>=$webdb[Info_CollectArticleNum]){
					showerr("�����ֻ���ղ�{$webdb[Info_CollectArticleNum]}����Ϣ",1);
				}
			}	
			$db->query("INSERT INTO `{$_pre}collection` (  `id` , `uid` , `posttime`,`ctype`) VALUES ('$id','$lfjuid','$timestamp','$ctype')");
		}
	}

	refreshto("$Mdomain/member/?main=collection.php","�ղسɹ�!",1);
}else{////////////////////////////////////////////////////////////ERROR

	showerr("�Ƿ�����");
}


function create_wantinfo($wantinfo){
	if(is_array($wantinfo)){
		foreach($wantinfo as $val){
			$htm.="<label><input type='checkbox' name='get_info[]' value='$val'>$val</label> \r\n ";
		}	
	}	
	return $htm;
}
function create_addinfo($addinfo){
	if(is_array($addinfo)){
		$htm.="<select name='autoSelect' id='autoSelect' onchange=\"changeaddContent(this);\">\r\n<option value=''>(���ô��֣���������д������æ��) </option>\r\n";
		foreach($addinfo as $val){
			$htm.="<option value='$val'>$val</option> \r\n";
		}	
		$htm.="<select>\r\n";
	}	
	return $htm;
}
?>