<?php
require(dirname(__FILE__)."/../global.php");
require_once(dirname(__FILE__)."/../bd_pics.php");
require(Mpath.'inc/categories.php');

if(!$Admin){
		if(!$lfjuid || !$lfjid){ showerr("�㻹û�е�¼,���ȵ�½");}
}

$ctype = 2;

$rt=$db->get_one("SELECT uid,rid,title FROM `{$_pre}company` c INNER JOIN `{$_pre}company_fid` cf ON c.rid = cf.cid WHERE uid='$lfjuid'");
if(!$rt[rid]){
	if($lfjuid)	showerr("��Ǹ��������Ʒ֮ǰ�����������̼�����,<a href='$Murl/member/homepage_ctrl.php?atn=info'> <strong>���</strong> </a>����");
	else  showerr("�㻹û�е�¼,���ȵ�½");
}

//�̼������ļ�

$homepage=$db->get_one("SELECT * FROM {$_pre}homepage WHERE rid='$rt[rid]' LIMIT 1");
if(!$homepage[hid]) { //�����̼���Ϣ
	showerr("�������̻�û�м��������� [ <a href='$Mdomain/myhomepage.php' target='_blank'>����</a> ]");
}

//TEMP 
$webdb[upfileMaxSize]=$webdb[postfileMaxSize]?$webdb[postfileMaxSize]:500;

//û�ж���ֻ�Ǿ�Ĭ�Ϸ�������
if(!$action){

		if(!$lfjuid || !$lfjid){ showerr("�㻹û�е�¼,���ȵ�½");}

	
	
		//�õ���ѡ����ķ���
		if(count($userpost_fid_history) < 11 && !isset($userpost_fid_history[$fid]))
		set_cookie('userpost_fid_history['. $fid .']', 1);
	
		$bcategory->cache_read();
		

		
		//$province_fid=select_where("province","'postdb[province_id]'  onChange=\"choose_where(this.options[this.selectedIndex].value,'','')\" ");	
		//$fid_all=getFidAll($fid);
		//$guidefid=GuideFid($fid_all,"list.php?ctype=$ctype");
		
		//�õ��ҵķ���
		$ms_id_num=0;
		$webdb[maxMysort]=$webdb[maxMysort]?$webdb[maxMysort]:10;
		$query=$db->query("select * from {$_pre}mysort where uid='$lfjuid' and ctype='$ctype' order by listorder desc;");
		while($rs=$db->fetch_array($query)){
			 $ms_id_options.="<option value='$rs[ms_id]'>$rs[sortname]</option> \r\n";
			 $ms_id_num++;
		}

		//�õ�������
		$Parameters_postform=parameters_postform($fid);

		
	
		//�õ�Ʒ��
		$select_brand=select_brand('postdb[bid]','postdb[bid]');
	

	
	
//�������
}elseif($action=='add'){

	if(!$lfjuid || !$lfjid){ showerr_post("�㻹û�е�¼,���ȵ�½");}

	
	if(!$fid) showerr_post("��Ŀ����Ϊ�գ�������ѡ����Ŀ");

	if(is_array($Fid_db[$fid])){
		 showerr_post("��ǰ���໹���ӷ��࣬��ѡ������ϸ�ķ���");
	}
	
	
	//��¼ѡ����ķ���
	$userpost_fid_history=get_cookie("userpost_fid_history"); 
	if(strpos($userpost_fid_history,$fid)===false)	$userpost_fid_history=$fid.",".$userpost_fid_history;
	$userpost_fid_history=explode(",",$userpost_fid_history);
	$userpost_fid_history=count($userpost_fid_history)>10?array_slice($userpost_fid_history,10):$userpost_fid_history;
	$userpost_fid_history=implode(",",$userpost_fid_history);
	set_cookie('userpost_fid_history',$userpost_fid_history);
	
	$bcategory->cache_read();

	//��ͼƬ���ݴ���

		//�������
		//print_r($_POST);exit;
		if(!$fid) showerr_post("��ѡ����Ŀ");
		
		//if(!$fid_all)$fid_all=getFidAll($fid);
		
		//if(!$fid_all)showerr_post("�������ڴ���Ŀ�·�������");
		
		if(!$postdb[title]) showerr_post("�������󹺱���");

		if(!$postdb[overtime]) showerr_post("��Ч�ڱ���ѡ��");
		
		foreach($postdb as $key=>$val){//ȫ�����ݴ���
			if($key!='content')	$postdb[$key]=ReplaceHtmlAndJs($val);
		}
	
	
	//}
	
	//ͼƬ����
	if(is_uploaded_file($_FILES[postfile][tmp_name])){
		$array[name]=is_array($postfile)?$_FILES[postfile][name]:$postfile_name;
		
		if($ctype==3)$array[path]=$webdb[updir]."/{$Imgdirname}/ico/";//�̼�ͼƬ���
		else $array[path]=$webdb[updir]."/{$Imgdirname}/";
		
		$array[size]=is_array($postfile)?$_FILES[postfile][size]:$postfile_size;
		
		if(($array[size]/1024) > $webdb[postfileMaxSize]){
			showerr_post("���ϴ�������ͼ�ļ�����{$webdb[postfileMaxSize]}K���ƣ��봦������ϴ���");
		}
		$picurl=upfile_func2(is_array($postfile)?$_FILES[postfile][tmp_name]:$postfile,$array);
		if(substr($picurl,0,3)=='ERR')	{
			showerr_post(str_replace("ERR-","",$picurl));	
		}
				
		if($picurl){//����ͼƬ
						@unlink(PHP168_PATH.$array[path].$oldfile);
		}else{
						$picurl=$oldfile;
		}
		//��Ӧ������������ͼ
		if($picurl){
				$Newpicpath=PHP168_PATH."$array[path]/{$picurl}.gif";
				gdpic(PHP168_PATH."$array[path]/$picurl",$Newpicpath,120,120);
				if(!file_exists($Newpicpath)){
					copy(PHP168_PATH."$array[path]/{$picurl}",$Newpicpath);
				}else{
					$picurl=$picurl;
				}
		}
		
		
	}else{
		$picurl="";
	}
	
	//Ʒ�ƴ���
	if($postdb[bid]){
		$postdb[brandname]="";
	}else{
		if($postdb[brandname]){
			if(strlen($postdb[brandname])>20){
				showerr_post("�����Ʒ�����Ʋ��ܳ���10�����֣�20����ĸ");
			}
		}
	}
	
	//�Լ����ദ��
	if($ms_id){
		//new_sortname
		$ms_id = intval($ms_id);
		if($ms_id=='new'){
			if(!$new_sortname || strlen($new_sortname)>20){
				showerr_post("�ҵķ������Ʋ���Ϊ�գ���С��20���ַ�");
			}
			$db->query("INSERT INTO `{$_pre}mysort` ( `ms_id` , `uid` , `sortname` , `fup` , `listorder` , `ctype` , `hits` , `best` )
	VALUES ('', '$lfjuid', '$new_sortname', '$fup', '$listorder', '$ctype', '0', '0');");
			$ms_id=$db->insert_id();
		}
	}

	//д�����ݿ�
	$postdb[yz]=$webdb[postauto_yz]?$webdb[postauto_yz]:0;
	$yz=$postdb[yz];
	
		$db->query("INSERT INTO `{$_pre}content_buy` SET
			title = '$postdb[title]',
			mid = '$fidDB[mid]',
			province_id = '$postdb[province_id]',
			albumname = '',
			fid = '$fid',
			ms_id = '$ms_id',
			fname = '{$bcategory->categories[$fid]['name']}',
			bid = '$postdb[bid]',
			brandname = '$postdb[brandname]',
			info = '',
			hits = '',
			comments = '',
			posttime = '$timestamp',
			list = '$postdb[list]',
			uid = '$lfjdb[uid]',
			username = '$lfjdb[username]',
			titlecolor = '',
			fonttype = '',
			picurl = '$picurl',
			ispic = '$postdb[ispic]',
			yz = '$postdb[yz]',
			yzer = '',
			yztime = '',
			levels = '',
			levelstime = '',
			keywords = '$postdb[keywords]',
			jumpurl = '$postdb[jumpurl]',
			iframeurl = '$postdb[iframeurl]',
			style = '$postdb[style]',
			head_tpl = '$postdb[head_tpl]',
			main_tpl = '$postdb[main_tpl]',
			foot_tpl = '$postdb[foot_tpl]',
			target = '$postdb[target]',
			ishtml = '$postdb[ishtml]',
			ip = '$onlineip',
			lastfid = '0',
			money = '$postdb[money]',
			passwd = '$postdb[passwd]',
			editer = '',
			edittime = '',
			begintime = '$postdb[begintime]',
			endtime = '$postdb[endtime]',
			config = '',
			lastview = '$postdb[lastview]',
			city_id = '$postdb[city_id]',
			zone_id = '$postdb[zone_id]',
			street_id = '$postdb[street_id]',
			editpwd = '$postdb[editpwd]',
			showday = '$postdb[showday]',
			telephone = '$postdb[telephone]',
			mobphone = '$postdb[mobphone]',
			email = '$postdb[email]',
			oicq = '$postdb[oicq]',
			msn = '$postdb[msn]',
			maps = '$postdb[maps]',
			owner =  '$owner',
			ctype = '$ctype',
			my_price = '$postdb[my_price]'
		");
		$id=$db->insert_id();
		
		if($id){
			$db->query("INSERT INTO `{$_pre}content_2` ( `id` , `fid` , `uid` , `content` , `overtime` , `quantity_num` , `quantity_type` ) VALUES (
 '$id', '$fid', '$lfjuid', '$postdb[content]', '$postdb[overtime]', '$postdb[quantity_num]', '$postdb[quantity_type]');");
			//���°�ͼƬ	
			
			$db->query("INSERT INTO {$_pre}buy_fid VALUES($id, $fid)");
			
			bd_pics("{$_pre}content_buy"," where id='$id' ");
		}
		//����ģ�ʹ���
		parameters_savedata($fid,$id);
		//������Ϣ����Ӧ��
		postmsgtomy('vendor',$id);	
		//��������
		if($webdb[post_add_money]) plus_money($lfjuid,$webdb[post_add_money]);
		//���
		parent_goto("?action=ok2&fid=$fid&id=$id","");//�����ɹ�
		exit;
	
	//}
	
//�����ɹ�
}elseif($action=='ok'){

		
		$msg="��ϲ���������ɹ���!";
		$do[0]['text']="����鿴����";$do[0]['target']=" target=_blank";
		$do[0]['link']="$Mdomain/buy_bencandy.php?fid=$fid&id=$id";

		$do[1]['text']="���������������Ʒ";
		$do[1]['link']="?ctype=$ctype";
		
		$do[2]['text']="��������ҵ���ҳ";$do[2]['target']=" target=_blank";
		$do[2]['link']="$Mdomain/homepage.php?uid=".$lfjuid;
		
}elseif($action=='ok2'){

		$msg="��ϲ���������ɹ���!";
		$do[0]['text']="����鿴����";$do[0]['target']=" target=_blank";
		$do[0]['link']="$Mdomain/buy_bencandy.php?fid=$fid&id=$id";
		
		
		$do[1]['text']="���������������";
		$do[1]['link']="?ctype=$ctype";
		
		$do[2]['text']="��������ҵ���ҳ";$do[2]['target']=" target=_blank";
		$do[2]['link']="$Mdomain/homepage.php?uid=".$lfjuid;


}elseif($action=='del'){
	
	if(!$id || !is_numeric($id)) showerr("���ݳ�����ֹ����");
	$rsdb=$db->get_one("select * from `{$_pre}content_buy` where id='$id';");
	if(!$rsdb) showerr("ID������");
	if(!$fromadmin){
		if($rsdb[uid]!=$lfjuid && !$Admin) showerr("����û��Ȩ���޸�");
	}
	//ɾ����̬
	if(file_exists(PHP168_PATH.$rsdb[htmlname])){
		@unlink(PHP168_PATH.$rsdb[htmlname]);
	}
	//ɾ������
	parameters_deldata($id);
	extract($rsdb);
	if($db->query("DELETE FROM `{$_pre}content_2` where id='$id'")){
		$db->query("DELETE FROM `{$_pre}content_buy` where id='$id'");
		$db->query("DELETE FROM `{$_pre}buy_fid` where id='$id'");
		@unlink(PHP168_PATH.$webdb[updir]."/{$Imgdirname}/".$picurl);
		@unlink(PHP168_PATH.$webdb[updir]."/{$Imgdirname}/".$picurl.".gif");
	}

	//if(!$fromadmin){
	//	refreshto("$Mdomain/list.php?fid=$fid&ctype=$ctype","ɾ���ɹ�");
	//}else{
		refreshto($FROMURL,"ɾ���ɹ� ");
	//}
	exit;	
	
}elseif($action=='edit'){
	
	if(!$id || !is_numeric($id))showerr("���ݳ�����ֹ����");
	$rsdb=$db->get_one("SELECT * FROM `{$_pre}content_buy` WHERE id='$id';");
	if(!$rsdb) showerr("ID������");
	if($rsdb[uid]!=$lfjuid && !$Admin) showerr("����û��Ȩ���޸�");
	if($fid){ 
		//����ѡ��FID��
		$rsdb[fid]=$fid;
	}
	extract($rsdb);
	
	$bcategory->cache_read();
		
	$parents = $bcategory->get_parents($fid);
	$guidefid = '';

	foreach($parents as $v) $guidefid .= ' &gt; '. $v['name'];
	$guidefid .= ' &gt; '. $bcategory->categories[$rsdb['fid']]['name'];
	
	$picurl_show=getimgdir($picurl,$ctype);
	
	//�õ���ϸ����
	$baseinfo=$db->get_one("SELECT * FROM `{$_pre}content_2` WHERE id='$id'");
	$rsdb[sent_limit]['$baseinfo[sent_limit]']=" selected";
	$rsdb=array_merge($rsdb,$baseinfo);

	//�õ��ҵķ���
	$ms_id_num=0;
	$webdb[maxMysort]=$webdb[maxMysort]?$webdb[maxMysort]:10;
	$query=$db->query("SELECT * FROM {$_pre}mysort WHERE uid='$rsdb[uid]' AND ctype='$ctype' ORDER BY listorder DESC");
	while($rs=$db->fetch_array($query)){
			 $sel=$rs[ms_id]==$rsdb[ms_id]?" selected":"";
			 $ms_id_options.="<option value='$rs[ms_id]' $sel>$rs[sortname]</option> \r\n";
			 $ms_id_num++;
	}
	
	//�õ�������
	$Parameters_postform=parameters_postform($fid,$id);
	//�õ�Ʒ��
	$select_brand=select_brand('postdb[bid]','postdb[bid]',$rsdb[bid],0); //$fid  ��ʱ��ʾȫ��Ʒ�ƣ�0�滻Ϊ$fid����

}elseif($action=='edit_save'){
	
	if(!$id || !is_numeric($id))showerr_post("���ݳ�����ֹ����");
	$rsdb=$db->get_one("select * from `{$_pre}content_buy` where id='$id';");
	if(!$rsdb) showerr_post("ID������");
	if($rsdb[uid]!=$lfjuid &&!$Admin) showerr_post("����û��Ȩ���޸�");
	$ctype=$rsdb['ctype'];

	
	if(!$fid) showerr_post("��ѡ����Ŀ");
	
	$bcategory->cache_read();
	
	//if(!$fid_all)$fid_all=getFidAll($fid);
	//if(!$fid_all)showerr_post("�������ڴ���Ŀ�·�������");
	if(!$postdb[title]) showerr_post("��������Ʒȫ��");
	if(strlen($postdb[title])>64) showerr_post("���������");
	if($postdb[my_price]) $postdb[my_price] = formartprice($postdb[my_price]);
	
		
		if(!$postdb[overtime]) showerr_post("��Ч�ڱ���ѡ��");		
		foreach($postdb as $key=>$val){//ȫ�����ݴ���
			if($key!='content')	$postdb[$key]=ReplaceHtmlAndJs($val);
		}
		//ͼƬ����
	if(is_uploaded_file($_FILES[postfile][tmp_name])){
		$array[name]=is_array($postfile)?$_FILES[postfile][name]:$postfile_name;
		$array[path]=$webdb[updir]."/{$Imgdirname}/";
		$array[size]=is_array($postfile)?$_FILES[postfile][size]:$postfile_size;
		$picurl=upfile_func2(is_array($postfile)?$_FILES[postfile][tmp_name]:$postfile,$array);
		if(substr($picurl,0,3)=='ERR')	{
			showerr_post(str_replace("ERR-","",$picurl));	
		}
		if($picurl){//����ͼƬ
						@unlink(PHP168_PATH.$array[path].$oldfile);
						@unlink(PHP168_PATH.$array[path].$oldfile.".gif");
		}else{
						$picurl=$oldfile;
		}
		//��Ӧ������������ͼ
		if($picurl && ($ctype==1 || $ctype==2)){
				$Newpicpath=PHP168_PATH."$array[path]/{$picurl}.gif";
				gdpic(PHP168_PATH."$array[path]/$picurl",$Newpicpath,120,120);
				if(!file_exists($Newpicpath)){
					copy(PHP168_PATH."$array[path]/{$picurl}",$Newpicpath);
				}else{
					$picurl=$picurl;
				}
		}
		//�̼�LOGO
		//�༭���̼�
	}else{
		$picurl=$oldfile;
	}
	
	//Ʒ�ƴ���
	if($postdb[bid]){
		$postdb[brandname]="";
	}else{
		if($postdb[brandname]){
			if(strlen($postdb[brandname])>20){
				showerr_post("�����Ʒ�����Ʋ��ܳ���10�����֣�20����ĸ");
			}
		}
	}

	//�Լ����ദ��
	if($ms_id){
		//new_sortname
		if($ms_id=='new'){
			if(!$new_sortname || strlen($new_sortname)>20){
				showerr_post("�ҵķ������Ʋ���Ϊ�գ���С��20���ַ�");
			}
			$db->query("INSERT INTO `{$_pre}mysort` ( `ms_id` , `uid` , `sortname` , `fup` , `listorder` , `ctype` , `hits` , `best` )
	VALUES ('', '$lfjuid', '$new_sortname', '$fup', '$listorder', '$ctype', '0', '0');");
			$ms_id=$db->insert_id();
		}
	}
	
	//��������
	$db->query("UPDATE `{$_pre}content_buy` SET
	`title`='$postdb[title]',
	`fid`='$fid',
	`fname`='{$bcategory->categories[$fid]['name']}',
	`ms_id`='$ms_id',
	`bid`='$postdb[bid]',
	`brandname`='$postdb[brandname]',
	`picurl`='$picurl',
	`my_price`='$postdb[my_price]',
	`posttime`='".$timestamp."'
	WHERE id='$id'");
	extract($postdb);
	
	//���°�ͼƬ	
	bd_pics("{$_pre}content_buy"," WHERE id='$id' ");
	
		$db->query("UPDATE `{$_pre}content_2` SET 
		`fid`='$fid',
		`content`='$content',
		`overtime`='$overtime',
		`quantity_num`='$quantity_num',
		`quantity_type`='$quantity_type'
		WHERE id='$id'");
		
		$db->query("UPDATE {$_pre}sell_fid SET fid = $fid WHERE id = $id");
		
		$backurl="$Murl/member/buylist.php";
	//}
	
	//����ģ�ʹ���
	parameters_savedata($fid,$id);

	parent_goto($backurl,"�ѳɹ�����");//�༭�ɹ�
	exit;	
	


//���·���ʱ��
}elseif($action=="updateposttime"){
	
	if(!$id && !is_numeric($id)) showerr("���ݳ�����ֹ����");
	$rsdb=$db->get_one("select * from `{$_pre}content_buy` where id='$id';");
	if(!$rsdb) showerr("ID������");
	if($rsdb[uid]!=$lfjuid) showerr("����û��Ȩ�޲���");
	$db->query("update `{$_pre}content_buy` set
	`posttime`='".$timestamp."'
	where id='$id'");
	
	refreshto($FROMURL,"���³ɹ� ");
}



//postmsgtomy����Ϣ
function postmsgtomy($type='buyer',$id){
	global $db,$webdb,$lfjuid,$lfjid,$_pre,$Mdomain;
	
	$rsdb=$db->get_one("select * from {$_pre}content_buy where id='$id'");	
	if($type=='buyer'){
		
		$myinfo=$db->get_one("select * from {$_pre}company where uid='$lfjuid' limit 1");
		
		if(!$myinfo) return false;
		
		$query=$db->query("select * from {$_pre}vendor where uid='$lfjuid' and yz=1");
		while($rs=$db->fetch_array($query)){
			//վ����
			$title="����Ӧ��$rsdb[title]($myinfo[title])";
			$content="����˾���·����˹�Ӧ��Ϣ��$rsdb[title] ��������Ӳ鿴���飻
$Mdomain/buy_bencandy.php?id=$rsdb[id]&fid=$rsdb[fid]
���ѯ��:
$Mdomain/action.php?action=form1&ids=$rsdb[id]
				
--------����Ϣ���ԣ�$myinfo[title] $Mdomain/homage.php?uid=$lfjuid 
($webdb[webname] $webdb[Info_webname] $webdb[www_url]/$Mdomain)";
			
			$array[touid]=$rs[owner_uid];
			$array[fromuid]=$rs[uid];
			$array[fromer]=$rs[username];
			$array[title]=filtrate($title);
			$array[content]=filtrate($content);
			pm_msgbox($array);
			//�ʼ�
			easy_sent_email($rs[owner_uid],$title,$content);
			
			
		}
		
	}elseif($type=='vendor'){
		
		$myinfo=$db->get_one("select * from {$_pre}company where uid='$lfjuid' limit 1");
		
		if(!$myinfo) return false;
		
		$query=$db->query("select * from {$_pre}vendor where owner_uid='$lfjuid' and yz=1");
		while($rs=$db->fetch_array($query)){
			//վ����
			$title="����Ӧ��$rsdb[title]($myinfo[title])";
			$content="����˾���·���������Ϣ��$rsdb[title] ��������Ӳ鿴���飻
$Mdomain/buy_bencandy.php?id=$rsdb[id]&fid=$rsdb[fid]
��˱���:
$Mdomain/action.php?action=form2&ids=$rsdb[id]
				
------����Ϣ���ԣ�$myinfo[title] $Mdomain/homage.php?uid=$lfjuid 
($webdb[webname] $webdb[Info_webname]  $webdb[www_url]/$Mdomain)";
			
			$array[touid]=$rs[uid];
			$array[fromuid]=$rs[owner_uid];
			$array[fromer]=$rs[owner_username];
			$array[title]=filtrate($title);
			$array[content]=filtrate($content);
			pm_msgbox($array);
			//�ʼ�
			easy_sent_email($rs[uid],$array[title],$array[content]);
			
			
			
		}
		
		
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

	//����html
	@file_get_contents($url."&makehtml=back");

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

$member_style=$webdb[sys_member_style]?$webdb[sys_member_style]:($webdb[member_style]?$webdb[member_style]:"images2");

		
$bcategory->cache_read();
$categories = $bcategory->unsets(true);

require(dirname(__FILE__)."/"."head.php");
//require(getTpl("post_".$ctype));
require(dirname(__FILE__)."/"."template/post_2.htm");
require(dirname(__FILE__)."/"."foot.php");

exit;
require(Mpath."inc/head.php");
require(Mpath."inc/foot.php");
?>