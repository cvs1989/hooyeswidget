<?php
require_once(dirname(__FILE__)."/global.php");
@include(Mpath."data/guide_fid.php");

if(!$lfjid){
	showerr('���ȵ�¼!');
}

if(!$web_admin&&$webdb[Info_HYpost]&&!$db->get_one("SELECT * FROM {$pre}hy_home WHERE uid='$lfjuid'")){
	showerr('�ܱ�Ǹ,��Ҫ����������,�ſ��Է�����Ϣ!');
}

/**
*��ȡ��Ŀ�����ļ�
**/
$fidDB=$db->get_one("SELECT * FROM {$_pre}sort WHERE fid='$fid'");

if(!$fidDB){
	showerr('��Ŀ����!');
}

//SEO
$titleDB[title]		= "$fidDB[name] - $webdb[Info_webname]";

/**
*ģ�Ͳ��������ļ�
**/
$field_db = $module_DB[$fidDB[mid]][field];
$ifdp = $module_DB[$fidDB[mid]][ifdp];
$m_config[moduleSet][useMap] = $module_DB[$fidDB[mid]][config][moduleSet][useMap];

if($fidDB[type]){
	showerr("�����,������������");
}elseif( $fidDB[allowpost] && $action!="del" && in_array($groupdb[gid],explode(",",$fidDB[allowpost])) ){
	showerr("�������û���,��Ȩ�ڱ���Ŀ����");
}


/*ģ��*/
$FidTpl=unserialize($fidDB[template]);

$lfjdb[money]=$lfjdb[_money]=intval(get_money($lfjuid));

if($action=="postnew"||$action=="edit"){

	$postdb['title']=filtrate($postdb['title']);
	$postdb['price']=filtrate($postdb['price']);
	$postdb['keywords']=filtrate($postdb['keywords']);
	if(!$postdb[title]){
		showerr("���ⲻ��Ϊ��");
	}elseif(strlen($postdb[title])>80){
		showerr("���ⲻ�ܴ���40������.");
	}
}

$_erp=$Fid_db[tableid][$fid];

/**�����ύ���·�������**/
if($action=="postnew")
{
	/*��֤�봦��*/
	if(!$web_admin){
		if(!check_imgnum($yzimg)){		
			showerr("��֤�벻����,����ʧ��");
		}
	}

	if(!$web_admin){
		if($groupdb[post_sell_num]<1){
			showerr('�������û��鲻��������Ʒ��Ϣ,�������û����');
		}
		$time=$timestamp-24*3600;
		$_rs=$db->get_one("SELECT COUNT(*) AS NUM FROM `{$_pre}content$_erp` WHERE uid='$lfjuid' AND posttime>$time");
		if($_rs[NUM]>$groupdb[post_sell_num]){
			showerr("�������û���ÿ�췢���Ĳ�Ʒ��Ϣ���ܳ���{$groupdb[post_sell_num]}��,�������û����");
		}
	}

	$postdb['list']=$timestamp;
	if($iftop){		//�Ƽ��ö�
		@extract($db->get_one("SELECT COUNT(*) AS NUM FROM `{$_pre}content$_erp` WHERE list>'$timestamp' AND fid='$fid' AND city_id='$postdb[city_id]'"));
		if($webdb[Info_TopNum]&&$NUM>=$webdb[Info_TopNum]){
			showerr("��ǰ��Ŀ�ö���Ϣ�Ѵﵽ����!");
		}
		$postdb['list']+=3600*24*$webdb[Info_TopDay];
		if($lfjdb[money]<$webdb[Info_TopMoney]){
			showerr("���{$webdb[MoneyName]}����:$webdb[Info_TopMoney],����ѡ���ö�");
		}
		$lfjdb[money]=$lfjdb[money]-$webdb[Info_TopMoney];	//Ϊ�������жϻ����Ƿ��㹻
	}

	
	if(eregi("[a-z0-9]{15,}",$postdb[title])){
		showerr("������д�ñ���!");
	}
	if(eregi("[a-z0-9]{25,}",$postdb[content])){
		//showerr("��������д����!");
	}
	
	//�Զ����ֶν���У������Ƿ�Ϸ�
	$Module_db->checkpost($field_db,$postdb,'');

	//�ϴ�����ͼƬ
	post_info_photo();

	unset($num,$postdb[picurl]);
	foreach( $photodb AS $key=>$value ){
		if(!$value || !eregi("(gif|jpg|png)$",$value)){
			continue;
		}
		if($titledb[$key]>100){
			showerr("���ⲻ�ܴ���50������");
		}
		$num++;
		if(!$postdb[picurl]){
			if(is_file(ROOT_PATH."$webdb[updir]/$value.gif")){
				$postdb[picurl]="$value.gif";
			}else{
				$postdb[picurl]=$value;
			}
		}
	}

	$postdb[ispic]=$postdb[picurl]?1:0;

	//�Ƿ��Զ�ͨ�����
	$postdb[yz] = ($web_admin||$groupdb[sell_postauto_yz])?1:0;

	if($postdb[yz]==1){
		add_user($lfjdb[uid],$webdb[PostInfoMoney],'������Ϣ����');
	}

	//�ö��۷�
	if($iftop){
		add_user($lfjuid,-intval($webdb[Info_TopMoney]),'�ö��۷�');
	}
	
	//��Ϣ��
	$db->query("INSERT INTO `{$_pre}db` (`id`,`fid`,`city_id`,`uid`) VALUES ('','$fid','$postdb[city_id]','$lfjdb[uid]')");
	$id=$db->insert_id();

	/*��������������*/
	$db->query("INSERT INTO `{$_pre}content$_erp` (`id`, `title` , `mid` ,`fid` , `fname` ,  `posttime` , `list` , `uid` , `username` ,  `picurl` , `ispic` , `yz` , `keywords` , `ip` , `money` , `city_id`,`picnum`,`price`) 
	VALUES (
	'$id','$postdb[title]','$fidDB[mid]','$fid','$fidDB[name]','$timestamp','$postdb[list]','$lfjdb[uid]','$lfjdb[username]','$postdb[picurl]','$postdb[ispic]','$postdb[yz]','$postdb[keywords]','$onlineip','$postdb[money]','$postdb[city_id]','$num','$postdb[price]')");


	//����ͼƬ
	foreach( $photodb AS $key=>$value ){
		if(!$value || !eregi("(gif|jpg|png)$",$value)){
			continue;
		}
		$titledb[$key]=filtrate($titledb[$key]);
		$value=filtrate($value);
		$db->query("INSERT INTO `{$_pre}pic` ( `id` , `fid` , `mid` , `uid` , `type` , `imgurl` , `name` ) VALUES ( '$id', '$fid', '$mid', '$lfjuid', '0', '$value', '{$titledb[$key]}')");
	}

	unset($sqldb);
	$sqldb[]="id='$id'";
	$sqldb[]="fid='$fid'";
	$sqldb[]="uid='$lfjuid'";

	/*����жϸ���Ϣ��Ҫ������Щ�ֶε�����*/
	foreach( $field_db AS $key=>$value){
		isset($postdb[$key]) && $sqldb[]="`{$key}`='{$postdb[$key]}'";
	}

	$sql=implode(",",$sqldb);

	/*������Ϣ���������*/
	$db->query("INSERT INTO `{$_pre}content_$fidDB[mid]` SET $sql");

	refreshto($FROMURL,"<a href='$FROMURL'>��������</a> <a href='../bencandy.php?fid=$fid&id=$id' target='_blank'>�鿴Ч��</a>",600);

}

/*ɾ������,ֱ��ɾ��,������*/
elseif($action=="del")
{
	$_erp=$Fid_db[tableid][$fid];
	$rsdb=$db->get_one("SELECT A.*,B.* FROM `{$_pre}content$_erp` A LEFT JOIN `{$_pre}content_$fidDB[mid]` B ON A.id=B.id WHERE A.id='$id'");

	if($rsdb[fid]!=$fidDB[fid])
	{
		showerr("��Ŀ������");
	}
	
	elseif($rsdb[uid]!=$lfjuid&&!$web_admin&&!in_array($lfjid,explode(",",$fidDB[admin])))
	{
		showerr("��ûȨ��!");
	}

	del_info($id,$_erp,$rsdb);

	if($rsdb[yz]){
		add_user($lfjdb[uid],-$webdb[PostInfoMoney],'ɾ����Ϣ�۷�');
	}

	refreshto("list.php","ɾ���ɹ�");
}

/*�༭����*/
elseif($job=="edit")
{
	$_erp=$Fid_db[tableid][$fid];
	$rsdb=$db->get_one("SELECT B.*,A.* FROM `{$_pre}content$_erp` A LEFT JOIN `{$_pre}content_$fidDB[mid]` B ON A.id=B.id WHERE A.id='$id'");

	if($rsdb[uid]!=$lfjuid&&!$web_admin&&!in_array($lfjid,explode(",",$fidDB[admin]))){	
		showerr('��ûȨ��!');
	}
	
	/*��Ĭ�ϱ���������*/
	$Module_db->formGetVale($field_db,$rsdb);

	$atc="edit";



	$rsdb['list']>$timestamp?($ifTop[1]=' checked '):($ifTop[0]=' checked ');

	$rsdb[price]==0 && $rsdb[price]='';
	
	$listdb = array();
	$query = $db->query("SELECT * FROM {$_pre}pic WHERE id='$id'");
	while($rs = $db->fetch_array($query)){
		$listdb[$rs[pid]]=$rs;
	}


	require(ROOT_PATH."member/head.php");
	require(getTpl("post_$fidDB[mid]",$FidTpl['post']));
	require(ROOT_PATH."member/foot.php");
}

/*�����ύ���������޸�*/
elseif($action=="edit")
{
	$_erp=$Fid_db[tableid][$fid];
	$rsdb=$db->get_one("SELECT A.*,B.* FROM `{$_pre}content$_erp` A LEFT JOIN `{$_pre}content_$fidDB[mid]` B ON A.id=B.id WHERE A.id='$id'");

	if($rsdb[uid]!=$lfjuid&&!$web_admin&&!in_array($lfjid,explode(",",$fidDB[admin]))){
		showerr("����Ȩ�޸�");
	}


	if($iftop&&$rsdb['list']<$timestamp){
		@extract($db->get_one("SELECT COUNT(*) AS NUM FROM `{$_pre}content$_erp` WHERE list>'$timestamp' AND fid='$fid' AND city_id='$postdb[city_id]'"));
		if($webdb[Info_TopNum]&&$NUM>=$webdb[Info_TopNum]){
			showerr("��ǰ��Ŀ�ö���Ϣ�Ѵﵽ����!");
		}
		if($lfjdb[money]<$webdb[Info_TopMoney]){
			showerr("��Ļ��ֲ���:$webdb[Info_TopMoney],����ѡ���ö�");
		}
	}
	
	//�Զ����ֶν���У������Ƿ�Ϸ�
	$Module_db->checkpost($field_db,$postdb,$rsdb);

	//�ϴ�����ͼƬ
	post_info_photo();

	unset($num,$postdb[picurl]);
	foreach( $photodb AS $key=>$value ){

		if(!$value&&$piddb[$key]){
			$db->query("DELETE FROM `{$_pre}pic` WHERE pid='{$piddb[$key]}' AND id='$id'");
		}

		if(!$value || !eregi("(gif|jpg|png)$",$value)){
			continue;
		}
		$titledb[$key]=filtrate($titledb[$key]);
		$value=filtrate($value);
		if($titledb[$key]>100){
			showerr("���ⲻ�ܴ���50������");
		}
		$num++;
		if($piddb[$key]){		
			$db->query("UPDATE `{$_pre}pic` SET name='{$titledb[$key]}',imgurl='$value' WHERE pid='{$piddb[$key]}' AND id='$id'");
		}else{
			$db->query("INSERT INTO `{$_pre}pic` ( `id` , `fid` , `mid` , `uid` , `type` , `imgurl` , `name` ) VALUES ( '$id', '$fid', '$mid', '$lfjuid', '0', '$value', '{$titledb[$key]}')");
		}
		if(!$postdb[picurl]){
			if(is_file(ROOT_PATH."$webdb[updir]/$value.gif")){
				$postdb[picurl]="$value.gif";
			}else{
				$postdb[picurl]=$value;
			}
		}
	}

	/*�ж��Ƿ�ΪͼƬ����*/
	$postdb[ispic]=$postdb[picurl]?1:0;


	if($iftop){
		if($rsdb['list']<$timestamp){
			$list=$timestamp+3600*24*$webdb[Info_TopDay];
			$SQL=",list='$list'";
			add_user($lfjuid,-intval($webdb[Info_TopMoney]),'�ö��۷�');
		}	
	}else{
		if($rsdb['list']>$timestamp){
			$SQL=",list='$rsdb[posttime]'";
		}
	}

	/*��������Ϣ������*/
	$db->query("UPDATE `{$_pre}content$_erp` SET title='$postdb[title]',keywords='$postdb[keywords]',picurl='$postdb[picurl]',ispic='$postdb[ispic]',picnum='$num',price='$postdb[price]' WHERE id='$id'");


	/*����жϸ���Ϣ��Ҫ������Щ�ֶε�����*/
	unset($sqldb);
	foreach( $field_db AS $key=>$value){
		$sqldb[]="`$key`='{$postdb[$key]}'";
	}	
	$sql=implode(",",$sqldb);

	/*���¸���Ϣ��*/
	$db->query("UPDATE `{$_pre}content_$fidDB[mid]` SET $sql WHERE id='$id'");

	refreshto($FROMURL,"<a href='$FROMURL'>�����޸�</a> <a href='../bencandy.php?fid=$fid&id=$id' target='_blank'>�鿴Ч��</a>",600);
}
else
{
	/*ģ������ʱ,��Щ�ֶ���Ĭ��ֵ*/
	foreach( $field_db AS $key=>$rs){	
		if($rs[form_value]){		
			$rsdb[$key]=$rs[form_value];
		}
	}

	/*��Ĭ�ϱ���������*/
	$Module_db->formGetVale($field_db,$rsdb);


	$atc="postnew";

	$ifTop[0]=' checked ';

	$listdb=array('');

	require(ROOT_PATH."member/head.php");
	require(getTpl("post_$fidDB[mid]",$FidTpl['post']));
	require(ROOT_PATH."member/foot.php");
}


?>