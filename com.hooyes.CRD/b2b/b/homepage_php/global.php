<?php

//���ֶ���
if($action=="msg_post"){ //����
	//������ʱ���ڲ����ظ�����
	$Omsg=$db->get_one("select max(posttime) as posttime  from {$_pre}homepage_guestbook` where cuid='$uid' and uid='$lfjuid' ");
	//echo "select max(posttime)  from {$_pre}homepage_guestbook` where cuid='$uid' and uid='$lfjuid' ";
	//print_r($Omsg);
	//echo "<hr>".time();
	//echo "<hr>".(intval($Omsg[posttime]) + 6);
	
	if($Omsg[posttime]){
		if( intval($Omsg[posttime]) + 60 > time() ){
			showerr("1�����ڲ����ٴ�����");
		}
	}
	//
	if(!$content){
		showerr("���ݲ���Ϊ��");
	}
	if(strlen($content)>1000){
		showerr("���ݲ��ܳ���500����");
	}
	$content=filtrate($content);
	$yz=1;
	$db->query("INSERT INTO `{$_pre}homepage_guestbook` (`cuid`,  `uid` , `username` , `ip` , `content` , `yz` , `posttime` , `list` ) 
	VALUES (
	'$uid','$lfjuid','$lfjid','$onlineip','$content','$yz','".time()."','".time()."')
	");
	refreshto("?m=msg&uid=$uid&page=$page","лл�������",1);

}elseif($action=="msg_delete") //ɾ������
{
	if($web_admin){
		$db->query("DELETE FROM `{$_pre}homepage_guestbook` WHERE id='$id'");
	}else{
		$db->query("DELETE FROM `{$_pre}homepage_guestbook` WHERE id='$id' AND (uid='$lfjuid' OR cuid='$lfjuid' )");
	}
	refreshto("?m=msg&uid=$uid&page=$page","ɾ���ɹ�",0);


/*
* �����Ϊĳ�ҵ�λ�Ĺ�Ӧ��!
* �����ǣ�ĳ�ҵ�λ��.uid,username
* �����������½
* ��Ӻ���ת
*/
}elseif($action=='add_vendor'){
	$owner_uid=intval($owner_uid);
	if(!$owner_uid || !$owner_username || !$owner_rid){
		showerr("����ʧ�ܣ����Ժ����ԣ�");exit;
	}
	if($owner_uid==$uid){
		showerr("����ʧ�ܣ��������Լ����룡");exit;
	}
	//����Ƿ��Ѿ��ǹ�Ӧ����
	$yj=$db->get_one("select count(*) as num from `{$_pre}vendor` where uid='$lfjuid' and owner_uid='$owner_uid'");
	if($yj[num]>0) showerr("���Ѿ����������Ϊ�Է��Ĺ�Ӧ����");
	//����Ƿ��Լ�����˺��̼�
	$rsdb=$db->get_one("select * from {$_pre}company where uid='$lfjuid' limit 1");
	if(!$rsdb[rid]) showerr("����û�еǼ��̼ң����<a href='post.php?ctype=3' style='color:red'>���Ǽ��̼ҡ�</a>");
	if(!$rsdb[yz]) showerr("���Ǽǵ��̼���Ϣ���ڲ����ã���������˽׶Σ����Ժ�����");
	if($webdb[vendorRenzheng]){
		if(!$rsdb[renzheng]) showerr("���Ǽǵ��̼���Ϣ��û���ṩ��֤��Ϣ������������Ϊ���˵Ĺ�Ӧ��,�����ڻ�Ա�����ṩ��֤����.");
	}
	//ִ��
	
	$db->query("INSERT INTO `{$_pre}vendor` ( `vid` , `owner_uid` , `owner_username`,`owner_rid` , `ms_id` , `uid` , `username` , `rid` , `remarks` , `posttime` , `yz` , `yztime` , `levels` ) 
VALUES ('', '$owner_uid', '$owner_username','$owner_rid', '', '$lfjuid', '$lfjid', '$rsdb[rid]', '$remarks', '".$timestamp."', '0', '0', '0');");
	
	$array[touid]=$owner_uid;
	$array[fromuid]=0;
	$array[fromer]='ϵͳ��Ϣ';
	$array[title]="��Ӧ������֪ͨ($rsdb[title])";
	$array[content]="{$owner_username}����!<br><br>�û�{$lfjid}($rsdb[title]) �Ѿ���������Ӧ���ʸ����룬<a href=$Mdomain/homepage.php?uid=$lfjuid target=_blank>��˲鿴�Է���ҳ</a>��";
	if(function_exists('pm_msgbox')){
		pm_msgbox($array);
	}
	refreshto("?uid=$owner_uid&m=showmsg&t=regvendor","����ɹ����ȴ���Ӧ",0);	
	exit;

}elseif($action=='add_vendor2'){

	$gy_uid=intval($gy_uid);
	if(!$gy_uid || !$gy_username || !$gy_rid){
		showerr("����ʧ�ܣ����Ժ����ԣ�");exit;
	}
	if($gy_uid==$uid){
		showerr("����ʧ�ܣ��������Լ����룡");exit;
	}
	//����Ƿ��Ѿ��ǲɹ���
	$yj=$db->get_one("select count(*) as num from `{$_pre}vendor` where uid='$gy_uid' and owner_uid='$lfjuid'");
	if($yj[num]>0) showerr("���Ѿ����������Ϊ�Է��Ĳɹ�����");
	
	//����Ƿ��Լ�����˺��̼�
	$rsdb=$db->get_one("select * from {$_pre}company where uid='$lfjuid' limit 1");
	if(!$rsdb[rid]) showerr("����û�еǼ��̼ң����<a href='post.php?ctype=3' style='color:red'>���Ǽ��̼ҡ�</a>");
	if(!$rsdb[yz]) showerr("���Ǽǵ��̼���Ϣ���ڲ����ã���������˽׶Σ����Ժ�����");
	if($webdb[vendorRenzheng]){
		if(!$rsdb[renzheng]) showerr("���Ǽǵ��̼���Ϣ��û���ṩ��֤��Ϣ������������Ϊ���˵Ĺ�Ӧ��,�����ڻ�Ա�����ṩ��֤����.");
	}

	//ִ��
	
	$db->query("INSERT INTO `{$_pre}vendor` ( `vid` , `owner_uid` , `owner_username`,`owner_rid` , `ms_id` , `uid` , `username` , `rid` , `remarks` , `posttime` , `yz` , `yztime` , `levels` ) 
VALUES ('', '$lfjuid', '$lfjid','$rsdb[rid]', '', '$gy_uid', '$gy_username', '$gy_rid', '$remarks', '".$timestamp."', '1', '$timestamp', '0');");
	
	$array[touid]=$gy_uid;
	$array[fromuid]=0;
	$array[fromer]='ϵͳ��Ϣ';
	$array[title]="�ɹ�������֪ͨ($rsdb[title])";
	$array[content]="{$gy_username}����!<br><br>�û�{$lfjid}($rsdb[title]) �Ѿ���������ɹ����ʸ����룬<a href=$Mdomain/homepage.php?uid=$lfjuid target=_blank>��˲鿴�Է���ҳ</a>��";
	if(function_exists('pm_msgbox')){
		pm_msgbox($array);
	}
	refreshto("?uid=$gy_uid&m=showmsg&t=regvendor2","����ɹ����ȴ���Ӧ",0);	
	exit;

}


/////////////////////////
//��ʼ������
$tpl_left=array(
'base'=>"�̼ҵ���",
'sort'=>"��Ϣ����",
'tongji'=>"ͳ����Ϣ",
'news'=>"���Ŷ�̬",
'friendlink'=>"��������"
);

$tpl_right=array(
'info'=>"�̼Ҽ��",
'selllist'=>"��Ӧ��Ϣ",
'buylist'=>"����Ϣ",
'zh'=>"չ����Ϣ",
'hr'=>"�˲���Ƹ",
'msg'=>"�� �� ��",
'visitor'=>"�ÿ��㼣"
);
/*****��ҳɸѡ����****/
//����,����ʱ�滻_Ϊ�ո�
$myorderby['A.posttime desc']="���·�����ǰ";
$myorderby['A.posttime asc']="��󷢲���ǰ";
$myorderby['B.my_price desc']="�۸����ǰ";
$myorderby['B.my_price asc']="�۸����ǰ";

/***�����Ŀ¼ *****/
$tpl_dir=Mpath."/images/homepage_style/";



$webdb[homepage_banner_size]=$webdb[homepage_banner_size]?$webdb[homepage_banner_size]:80;
$webdb[homepage_ico_size]=$webdb[homepage_ico_size]?$webdb[homepage_ico_size]:50;
$webdb[friendlinkmax]=$webdb[friendlinkmax]?$webdb[friendlinkmax]:20;
//�ĵ��������
if(is_dir($tpl_dir))
{

	if ($handle = opendir($tpl_dir)) {
		while (false !== ($file = readdir($handle))) {
			if ($file != "." && $file != "..") {
				if(is_dir($tpl_dir.$file)){
					if(file_exists($tpl_dir.$file."/style.php")){
						@require($tpl_dir.$file."/style.php");
					}
				}
			}
		}
	}
	closedir($handle);	

}else{

	die("��վ������������ϵ����Ա!");
}


//�����̼�
function caretehomepage($rsdb){
	global $db,$webdb,$_pre,$tpl_left,$tpl_right,$ctrl,$atn,$timestamp;
	
	foreach($tpl_left as $key=>$val){
		$index_left[]=$key;
	}
	$index_left=implode(",",$index_left);
	
	foreach($tpl_right as $key=>$val){
		if(in_array($key,array('info','selllist','buylist'))){  //������Щģ����Գ�ʼ��
			$index_right[]=$key;
		}
	}
	$index_right=implode(",",$index_right);
	
	$listnum=array(
	'selllist'=>10,'buylist'=>10,'guestbook'=>4,'visitor'=>10,'newslist'=>10,'hr'=>10,'zh'=>10,'friendlink'=>10,
	'Mselllist'=>10,'Mbuylist'=>10,'Mguestbook'=>10,'Mvisitor'=>40,'Mnewslist'=>10,'Mhr'=>20,'Mzh'=>20);
	$listnum=serialize($listnum);

	$db->query("INSERT INTO `{$_pre}homepage` ( `hid` , `rid` , `uid` , `username` , `style` , `index_left` , `index_right` ,`listnum`,`banner`, `bodytpl`,`renzheng_show`,`friendlink` , `visitor` ) 
VALUES (
'', '$rsdb[rid]', '$rsdb[uid]', '$rsdb[username]', 'default', '$index_left', '$index_right','$listnum','','left','0', '', '');
");
	

	//��ʼ��ͼ��
	$db->query("INSERT INTO `{$_pre}homepage_picsort` ( `psid` , `psup` , `name` , `remarks` , `uid` , `username` , `rid` , `level` , `posttime` , `orderlist` ) VALUES 
	('', '0', '��Ʒͼ��', '��¼��Ʒ�෽��ͼƬ����', '$rsdb[uid]', '$rsdb[username]', '{$rsdb[rid]}', '0', '$timestamp', '2'),
	('', '0', '����˵��', '����֤�飬��֤�飬Ӫҵִ��', '$rsdb[uid]', '$rsdb[username]', '{$rsdb[rid]}', '0', '$timestamp', '1');   
	");
	
	//��ת
	$url="?uid=$rsdb[uid]";
	

	echo "�̼�ҳ�漤��ɹ�....<META HTTP-EQUIV=REFRESH CONTENT='0;URL=$url'>";
	exit;
}




//��Ŀ���б�
function choose_sort($fid,$class,$ck=0,$ctype)
{
	global $db,$_pre;
	for($i=0;$i<$class;$i++){
		$icon.="&nbsp;&nbsp;&nbsp;&nbsp;";
	}
	$class++;          //AND type=1
	$query = $db->query("SELECT * FROM {$_pre}sort WHERE fup='$fid'   ORDER BY list DESC LIMIT 500");
	while($rs = $db->fetch_array($query)){
		$ckk=$ck==$rs[fid]?' selected ':'';
		$fup_select.="<option value='$rs[fid]' $ckk >$icon|-$rs[name]</option>";
		$fup_select.=choose_sort($rs[fid],$class,$ck,$ctype);
	}
	return $fup_select;
}

?>