<?php	
!function_exists('html') && exit('ERR');	

$ForbidDo[100]=array("photourl");
$ForbidDo[101]=array("softurl");
$ForbidDo[102]=array("mvurl");
$ForbidDo[103]=array("shop_id","shoptype","shopmoney","martprice","nowprice","shopnum");
$ForbidDo[104]=array("flashurl");
$ForbidDo[105]=array();
$ForbidDo[0]=array();

if($job=="list"&&$Apower[article_module])	
{	
	$query = $db->query("SELECT * FROM {$pre}article_module ORDER BY list DESC");	
	while($rs = $db->fetch_array($query)){
		$erp=$rs[iftable]?$rs[iftable]:'';
		$rss=$db->get_one("SELECT count(*) AS NUM FROM {$pre}article$erp WHERE mid='$rs[id]' ");	
		$rs[NUM]=$rss[NUM];	
		$listdb[]=$rs;	
	}	
		
	require("head.php");
	require("template/article_module/list.htm");
	require("foot.php");
}	
elseif($action=="editlist"&&$Apower[article_module])
{	
	foreach( $order AS $key=>$value){	
		$db->query("UPDATE {$pre}article_module SET list='$value' WHERE id='$key' ");	
	}	

	jump("�޸ĳɹ�","$FROMURL",1);	
}	
elseif($action=="addmodule"&&$Apower[article_module])
{
	if($iftable){
		$R=$db->get_one("SELECT * FROM {$pre}article_module ORDER BY iftable DESC LIMIT 1");
		if($R[iftable]>99){
			$tableid=$R[iftable]+1; 
			if( strlen(intval($tableid))!=3 ){
				$tableid=100;
			}
		}else{
			$tableid=100;
		}
	}
	if($tableid==100){
		set_time_limit(0);
		$db->query("ALTER TABLE `{$pre}collection` CHANGE `id` `id` INT( 10 ) NOT NULL AUTO_INCREMENT");
		$db->query("ALTER TABLE `{$pre}comment` CHANGE `aid` `aid` INT( 10 ) UNSIGNED DEFAULT '0' NOT NULL");
		$db->query("ALTER TABLE `{$pre}keywordid` CHANGE `aid` `aid` INT( 10 ) DEFAULT '0' NOT NULL");
		$db->query("ALTER TABLE `{$pre}reply` CHANGE `aid` `aid` INT( 10 ) DEFAULT '0' NOT NULL");
		$db->query("ALTER TABLE `{$pre}shoporderproduct` CHANGE `shopid` `shopid` INT( 10 ) DEFAULT '0' NOT NULL");
		$db->query("ALTER TABLE `{$pre}article` CHANGE `aid` `aid` INT( 10 ) UNSIGNED NOT NULL AUTO_INCREMENT");
		$db->query("ALTER TABLE `{$pre}report` CHANGE `aid` `aid` INT( 10 ) DEFAULT '0' NOT NULL");
	}
	if(!$name){
		showmsg("���Ʋ���Ϊ��");
	}
	$rs=$db->get_one("SELECT * FROM `{$pre}article_module` WHERE `name`='$name'");
	if($rs){
		showmsg("��ǰģ�������Ѿ�������,�����һ��");
	}
	if($fid){
		$type=0;
	}else{
		$type=1;
	}
	$array[field_db][my_content]=array(
		"title"=>"��ע",
		"field_name"=>"my_content",
		"field_type"=>"mediumtext",
		"form_type"=>"textarea",
		"search"=>"0"
		);
	$array[is_html][my_content]="��ע";	
	$config=serialize($array);
	
	$db->query("INSERT INTO {$pre}article_module (name,alias,config,iftable) VALUES ('$name','$name','$config','$tableid') ");
	@extract($db->get_one("SELECT id FROM {$pre}article_module ORDER BY id DESC LIMIT 0,1"));
	unset($SQL);	
	if($dbcharset && mysql_get_server_info() > '4.1' ){	
		$SQL=" DEFAULT CHARSET=$dbcharset ";	
	}
	if( $iftable && !is_table("{$pre}article{$tableid}") ){
		$rs=$db->get_one("SHOW CREATE TABLE {$pre}article ");
		$sql=str_replace(array("{$pre}article",";"),array("{$pre}article{$tableid}",""),$rs['Create Table']);
		if(mysql_get_server_info() > '4.1'){
			if(!strstr($sql,'DEFAULT CHARSET')){
				$sql.=$SQL;
			}			
		}
		if(eregi("AUTO_INCREMENT=",$sql)){
			$sql=preg_replace("/AUTO_INCREMENT=([0-9]+)/is","AUTO_INCREMENT=".$tableid."000001 ;",$sql);
		}else{
			$sql.=" AUTO_INCREMENT=".$tableid."000001 ; ";
		}
		$db->query($sql);	
		$sql="ALTER TABLE `{$pre}article{$tableid}` CHANGE `aid` `aid` INT( 10 ) UNSIGNED NOT NULL AUTO_INCREMENT ";
		$db->query($sql);
		$sql="ALTER TABLE `{$pre}article{$tableid}` CHANGE `mid` `mid` MEDIUMINT( 5 ) DEFAULT '{$tableid}' NOT NULL;";
		$db->query($sql);

		$rs=$db->get_one("SHOW CREATE TABLE {$pre}reply ");
		$sql=str_replace(array("{$pre}reply",";"),array("{$pre}reply{$tableid}",""),$rs['Create Table']);
		if(mysql_get_server_info() > '4.1'){
			if(!strstr($sql,'DEFAULT CHARSET')){
				$sql.="$SQL";
			}
		}
		$db->query($sql);
		$sql="ALTER TABLE `{$pre}reply{$tableid}` CHANGE `aid` `aid` INT( 10 ) DEFAULT '0' NOT NULL";
		$db->query($sql);
	}
	$SQL="CREATE TABLE `{$pre}article_content_{$id}` (
  `id` mediumint(7) NOT NULL auto_increment,
  `aid` int(10) NOT NULL default '0',
  `rid` mediumint(7) NOT NULL default '0',
  `fid` mediumint(7) NOT NULL default '0',
  `uid` mediumint(7) NOT NULL default '0',
  `my_content` mediumtext NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `fid` (`fid`),
  KEY `uid` (`uid`),
  KEY `aid` (`aid`)
	) TYPE=MyISAM {$SQL} AUTO_INCREMENT=1 ;";	
	$db->query($SQL);	
	
	//���ɻ���
	article_module_cache();

	jump("�����ɹ�<br><a href='?lfj=$lfj&job=tpl&id=$id' style='color:red;font-size:25px'>���ע�⣡�������������ģ��,ģ�������Ч</a> ","index.php?lfj=article_module&job=editmodule&id=$id",10);	
}	
	
//�޸���Ŀ��Ϣ	
elseif($job=="editsort"&&$Apower[article_module])	
{	
	$rsdb=$db->get_one("SELECT * FROM {$pre}article_module WHERE id='$id'");	
	
	$select_style=select_style('Info_style',$rsdb[style]);	
	
	$array=unserialize($rsdb[config]);	
	
	$listdb=$array[field_db];	
	
	require("head.php");	
	require("template/article_module/editsort.htm");	
	require("foot.php");	
}	
elseif($job=="editmodule"&&$Apower[article_module])	
{	
	$rsdb=$db->get_one("SELECT * FROM {$pre}article_module WHERE id='$id'");
	$array=unserialize($rsdb[config]);
	@extract($array[moduleSet]);
	$etypeDB[intval($etype)]=' checked ';
	$morepageDB[intval($morepage)]=' checked ';
	$no_authorDB[intval($no_author)]=' checked ';
	$no_fromDB[intval($no_from)]=' checked ';
	$no_fromurlDB[intval($no_fromurl)]=' checked ';
	$descriptionDB[intval($description)]=' checked ';

	$allowpost=group_box("postdb[allowpost]",explode(",",$rsdb[allowpost]));

	require("head.php");	
	require("template/article_module/editmodule.htm");	
	require("foot.php");	
}	
elseif($action=="editsort"&&$Apower[article_module])	
{
	$rsdb=$db->get_one("SELECT * FROM {$pre}article_module WHERE id='$id' ");	
	$array=unserialize($rsdb[config]);
	$array[moduleSet]=$postdb;
	$config=addslashes(serialize($array));
	$postdb[allowpost]=@implode(",",$postdb[allowpost]);
	$db->query(" UPDATE {$pre}article_module SET name='$name',alias='$postdb[alias]',config='$config',allowpost='$postdb[allowpost]' WHERE id='$id' ");	
	
	//���ɻ���
	article_module_cache();

	jump("�޸ĳɹ�","$FROMURL");	
}	
elseif($action=="editorder"&&$Apower[article_module])	
{	
	$rsdb=$db->get_one("SELECT * FROM {$pre}article_module WHERE id='$id' ");	
	$array=unserialize($rsdb[config]);	
	$field_db=$array[field_db];	
	
	foreach( $field_db AS $key=>$value){	
		$postdb[$key]=intval($postdb[$key]);	
		$field_db[$key][orderlist]=$postdb[$key];	
		$_listdb[$postdb[$key]]=$field_db[$key];	
	}	
	krsort($_listdb);	
	foreach( $_listdb AS $key=>$rs){	
		$listdb[$rs[field_name]]=$rs;	
	}	
	if(is_array($listdb)){	
		$field_db=$listdb+$field_db;	
	}	
	$array[field_db]=$field_db;	
	
	
	$config=addslashes(serialize($array));	
	$db->query("UPDATE {$pre}article_module SET config='$config' WHERE id='$id' ");	

	//���ɻ���
	article_module_cache();

	jump("�޸ĳɹ�<br><a href='?lfj=$lfj&job=tpl&id=$id' style='color:red;font-size:25px'>���ע�⣡�������������ģ��,ģ�������Ч</a> ","?lfj=$lfj&job=editsort&id=$id",10);	
}	
elseif($job=="editfield"&&$Apower[article_module])	
{	
	$rsdb=$db->get_one("SELECT * FROM {$pre}article_module WHERE id='$id' ");	
	$array=unserialize($rsdb[config]);	
	$_rs=$array[field_db][$field_name];	
	if($_rs[field_name]=='content'){	
		$readonly=" readony ";	
	}	
	$_rs[field_leng]<1 && $_rs[field_leng]='';	
	$search[$_rs[search]]=" checked ";	
	$mustfill[$_rs[mustfill]]=" checked ";	
	$form_type[$_rs[form_type]]=" selected ";	
	$field_type[$_rs[field_type]]=" selected ";	
	$group_view=group_box("postdb[allowview]",explode(",",$_rs[allowview]));	
	
	$_rs[form_title]=StripSlashes($_rs[form_title]);

	require("head.php");	
	require("template/article_module/editfield.htm");	
	require("foot.php");	
}	
elseif($action=="editfield"&&$Apower[article_module])	
{	
	$postdb[allowview]=implode(",",$postdb[allowview]);	
	
	$rsdb=$db->get_one("SELECT * FROM {$pre}article_module WHERE id='$id' ");	
	
	$array=unserialize($rsdb[config]);	
	
	$field_array=$array[field_db][$field_name];	
	
	if(!ereg("^([a-z])([a-z0-9_]{2,})$",$postdb[field_name])){	
		showmsg("�ֶ�ID�����Ϲ���");	
	}

	
	if($postdb[field_name]!=$field_name){
		if( table_field("{$pre}article_content_$id",$postdb[field_name]) ){
			showmsg("���ֶ�ID�Ѵ���,�����һ��");
		}
	}
	
	if(table_field("{$pre}article",$postdb[field_name])||table_field("{$pre}reply",$postdb[field_name])){
		showmsg("���ֶ�ID�ܱ���,�����һ��");
	}	
	
	$postdb[field_leng]=intval($postdb[field_leng]);	
	
	if($postdb[field_type]=='int')	
	{	
		if( $postdb[field_leng]>10 || $postdb[field_leng]<1 ){	
			$postdb[field_leng]=10;	
		}	
		$db->query("ALTER TABLE `{$pre}article_content_$id` CHANGE `{$field_array[field_name]}` `{$postdb[field_name]}` INT( $postdb[field_leng] ) NOT NULL");	
	}	
	elseif($postdb[field_type]=='varchar')	
	{	
		if( $postdb[field_leng]>255 || $postdb[field_leng]<1 ){	
			$postdb[field_leng]=255;	
		}	
		$db->query("ALTER TABLE `{$pre}article_content_$id` CHANGE `{$field_array[field_name]}` `{$postdb[field_name]}` VARCHAR ( $postdb[field_leng] ) NOT NULL");	
	}	
	elseif($postdb[field_type]=='mediumtext')	
	{	
		$db->query("ALTER TABLE `{$pre}article_content_$id` CHANGE `{$field_array[field_name]}` `{$postdb[field_name]}` MEDIUMTEXT NOT NULL");	
	}	
	unset($array[field_db][$field_name]);	
	$array[field_db]["{$postdb[field_name]}"]=$postdb;	
	if($postdb[search]){	
		$array[search_db][$field_name]=$postdb[title];	
	}else{	
		unset($array[search_db][$field_name]);	
	}	
	if($postdb[form_type]=='ieedit'){	
		$array[is_html][$field_name]=$postdb[title];	
	}else{	
		unset($array[is_html][$field_name]);	
	}	
	if($postdb[form_type]=='upfile'){	
		$array[is_upfile][$field_name]=$postdb[title];	
	}else{	
		unset($array[is_upfile][$field_name]);	
	}

	//����
	foreach( $array[field_db] AS $key=>$value ){
		$_listdb[intval($value[orderlist])]=$value;
	}
	krsort($_listdb);
	unset($listdb);
	foreach( $_listdb AS $key=>$rs){
		$listdb[$rs[field_name]]=$rs;
	}
	$array[field_db]=$listdb+$array[field_db];

	$config=addslashes(serialize($array));	
	$db->query("UPDATE {$pre}article_module SET config='$config' WHERE id='$id' ");

	//���ɻ���
	article_module_cache();

	jump("�޸ĳɹ�<br><a href='?lfj=$lfj&job=tpl&id=$id' style='color:red;font-size:25px'>���ע�⣡�������������ģ��,ģ�������Ч</a> ","?lfj=$lfj&job=editfield&id=$id&field_name=$postdb[field_name]",10);	
}	
elseif($job=="addfield"&&$Apower[article_module])	
{	
	$rsdb=$db->get_one("SELECT * FROM {$pre}article_module WHERE id='$id' ");	
	//$group_view=group_box("postdb[allowview]",explode(",",$rsdb[allowview]));	
	$_rs[field_type]='mediumtext';	
	$field_type[$_rs[field_type]]=" selected ";	
	$_rs[field_name]="my_".rand(1,999);	
	$_rs[title]="�ҵ��ֶ�$_rs[field_name]";	
	$mustfill[0]=$search[0]=' checked ';	
	$_rs[form_type]='text';
	require("head.php");	
	require("template/article_module/editfield.htm");	
	require("foot.php");	
}	
elseif($action=="addfield"&&$Apower[article_module])	
{	
	$postdb[allowview]=implode(",",$postdb[allowview]);	
	if(!ereg("^([a-z])([a-z0-9_]{2,})$",$postdb[field_name])){	
		showmsg("�ֶ�ID�����Ϲ���");	
	}	
	if(table_field("{$pre}article",$postdb[field_name])||table_field("{$pre}reply",$postdb[field_name])||table_field("{$pre}article_content_$id",$postdb[field_name])){	
		showmsg("���ֶ�ID���ܱ������Ѵ���,�����һ��");	
	}	
	$postdb[field_leng]=intval($postdb[field_leng]);	
	
	if($postdb[field_type]=='int')	
	{	
		if( $postdb[field_leng]>10 || $postdb[field_leng]<1 ){	
			$postdb[field_leng]=10;	
		}	
		$db->query("ALTER TABLE `{$pre}article_content_$id` ADD `{$postdb[field_name]}` INT( $postdb[field_leng] ) NOT NULL");	
	}	
	elseif($postdb[field_type]=='varchar')	
	{	
		if( $postdb[field_leng]>255 || $postdb[field_leng]<1 ){	
			$postdb[field_leng]=255;	
		}	
		$db->query("ALTER TABLE `{$pre}article_content_$id` ADD `{$postdb[field_name]}` VARCHAR( $postdb[field_leng] ) NOT NULL");	
	}	
	elseif($postdb[field_type]=='mediumtext')	
	{	
		$db->query("ALTER TABLE `{$pre}article_content_$id` ADD `{$postdb[field_name]}` MEDIUMTEXT NOT NULL");	
	}	
	
	$rsdb=$db->get_one("SELECT * FROM {$pre}article_module WHERE id='$id' ");	
	$field_name=$postdb[field_name];	
	$array=unserialize($rsdb[config]);	
	$array[field_db][$field_name]=$postdb;	
	if($postdb[search]){
		$array[search_db][$field_name]=$postdb[title];	
	}else{	
		unset($array[search_db][$field_name]);	
	}
	if($postdb[form_type]=='ieedit'){	
		$array[is_html][$field_name]=$postdb[title];	
	}else{	
		unset($array[is_html][$field_name]);	
	}
	if($postdb[form_type]=='upfile'){	
		$array[is_upfile][$field_name]=$postdb[title];	
	}else{	
		unset($array[is_upfile][$field_name]);	
	}

	if($postdb[field_type]!='mediumtext'&&$postdb[field_type]!='text'){
		if($postdb[search]){
			$db->query("ALTER TABLE `{$pre}article_content_$id` ADD INDEX ( `{$field_name}` );");
		}
	}

	$config=addslashes(serialize($array));	
	$db->query("UPDATE {$pre}article_module SET config='$config' WHERE id='$id' ");
	//���ɻ���
	article_module_cache();

	jump("��ӳɹ�<br><a href='?lfj=$lfj&job=tpl&id=$id' style='color:red;font-size:25px'>���ע�⣡�������������ģ��,ģ�������Ч</a>","index.php?lfj=$lfj&job=editsort&id=$id",10);	
}	
elseif($action=="delfield"&&$Apower[article_module])	
{	
	if($field_name=="content"){	
		//showmsg("�ܱ����ֶ�,�㲻��ɾ��");	
	}
	$rsdb=$db->get_one("SELECT * FROM {$pre}article_module WHERE id='$id' ");	
	$array=unserialize($rsdb[config]);	
	unset($array[field_db][$field_name]);	
	$config=addslashes(serialize($array));	
	$db->query("UPDATE {$pre}article_module SET config='$config' WHERE id='$id' ");	
	$db->query("ALTER TABLE `{$pre}article_content_$id` DROP `$field_name`");
	//���ɻ���
	article_module_cache();

	jump("ɾ���ɹ�<br><a href='?lfj=$lfj&job=tpl&id=$id' style='color:red;font-size:25px'>���ע�⣡�������������ģ��,ģ�������Ч</a> ",$FROMURL);	
}	
elseif($job=='tpl'&&$Apower[article_module])	
{	
	if($automaketpl){	//��������ģ��
		$autojump="autopost();";
		$page=intval($page);
		$rsdb=$db->get_one("SELECT * FROM {$pre}article_module LIMIT $page,1 ");
		$id=$rsdb[id];
		if(!$id){
			jump("ģ���������","index.php?lfj=$lfj&job=list",3);
		}
		$page++;
	}else{
		$rsdb=$db->get_one("SELECT * FROM {$pre}article_module WHERE id='$id' ");
	}
	

	//��̨����ҳ
	if(is_file("template/post/tpl/post_$id.htm")){
		$post_tpl_file="template/post/tpl/post_$id.htm";
	}else{
		$post_tpl_file="template/post/post.htm";
	}
	$post_tpl=read_file($post_tpl_file);

	//ǰ̨��Ա����ҳ
	if(is_file(PHP168_PATH."member/template/tpl/post_$id.htm")){
		$member_post_tpl_file="member/template/tpl/post_$id.htm";
	}else{
		$member_post_tpl_file="member/template/post.htm";
	}
	$member_post_tpl=read_file(PHP168_PATH.$member_post_tpl_file);

	//����ҳ
	if(is_file(PHP168_PATH."template/default/tpl/bencandy_$id.htm")){
		$show_tpl_file="template/default/tpl/bencandy_$id.htm";
	}else{
		$show_tpl_file="template/default/bencandy.htm";
	}
	$show_tpl=read_file(PHP168_PATH.$show_tpl_file);

	//����ҳ
	if(is_file(PHP168_PATH."template/default/tpl/search_$id.htm")){
		$search_tpl_file="template/default/tpl/search_$id.htm";
	}else{
		$search_tpl_file="template/default/search.htm";
	}
	$search_tpl=read_file(PHP168_PATH.$search_tpl_file);

		
	$array=unserialize($rsdb[config]);	
	
	$i=0;
	foreach( $array[field_db] AS $key=>$rs){
		$i++;
		$styleclass=($i%2==0)?' b2':' b1';
		$tpl_p.=make_post_table($rs,$member_post_tpl);
		$admin_post_tpl.=make_post_table($rs,$post_tpl);
		$tpl_s.=make_show_table($rs,$styleclass,$show_tpl);
		if($array[search_db][$key]){	
			if($rs[form_type]=="select"||$rs[form_type]=="radio"||$rs[form_type]=="checkbox"){	
				$show=make_search_table($rs);	
				$tpl_sarch2.="<tr><td align='left'>{$rs[title]}:</td><td align='left'>$show</td></tr>";	
			}else{	
				$tpl_sarch1.=make_search_table($rs);	
			}
		}
	}
	/*
	$admin_post_tpl="<table width='100%' cellspacing='1' cellpadding='3' class='module_table'>
  <tr class='module_tr'> 
    <td colspan='2'>���������¡�{$rsdb[name]}������</td>
  </tr>
  <tr> 
    <td width='17%'></td>
    <td width='83%'></td>
  </tr>$admin_post_tpl
</table>";*/

  $_tpl_s="<table width='99%' cellspacing='1' cellpadding='3' class='module_table'>
  <tr class='module_tr'> 
    <td colspan='2'>�����ǡ�{$rsdb[name]}����ϸ����</td>
  </tr>
  <tr> 
    <td width='17%'></td>
    <td width='83%'></td>
  </tr>$tpl_s
</table>";
	
	//��̨����ҳ
	$post_tpl=str_replace('$Article_Module',$admin_post_tpl,$post_tpl);	
	$post_tpl=str_replace("<","&lt;",$post_tpl);	
	$post_tpl=str_replace(">","&gt;",$post_tpl);
	
	//ǰ̨����ҳ
	$tpl_p=str_replace("upfile.php","../do/upfile.php",$tpl_p);
	$tpl_p=str_replace("ewebeditor/ewebeditor.php","../ewebeditor/ewebeditor.php",$tpl_p);
	$member_post_tpl=str_replace('$Article_Module',$tpl_p,$member_post_tpl);
	$member_post_tpl=str_replace("<","&lt;",$member_post_tpl);	
	$member_post_tpl=str_replace(">","&gt;",$member_post_tpl);

	//����ҳ
	if( strstr($show_tpl,'$bencandytpl') ){
		$show_tpl=str_replace('$bencandytpl',$tpl_s,$show_tpl);
	}else{
		$show_tpl=str_replace('$rsdb[content]','$rsdb[content]'.$_tpl_s,$show_tpl);
	}	
	$show_tpl=str_replace("<","&lt;",$show_tpl);
	$show_tpl=str_replace(">","&gt;",$show_tpl);

	//����ҳ
	$search_tpl=str_replace('$TempLate1',$tpl_sarch1,$search_tpl);	
	$search_tpl=str_replace('$TempLate2',$tpl_sarch2,$search_tpl);	
	$search_tpl=str_replace("<","&lt;",$search_tpl);	
	$search_tpl=str_replace(">","&gt;",$search_tpl);	
	
	require("head.php");
	require("template/article_module/tpl.htm");
	require("foot.php");
}	
elseif($action=='tpl'&&$Apower[article_module])	
{	
	$tpl_post=stripslashes($tpl_post);
	$member_tpl_post=stripslashes($member_tpl_post);
	$tpl_bigsort=stripslashes($tpl_bigsort);
	$tpl_sort=stripslashes($tpl_sort);
	$tpl_show=stripslashes($tpl_show);
	$tpl_search=stripslashes($tpl_search);
	if(!is_dir(PHP168_PATH."php168/article_tpl")){
		makepath(PHP168_PATH."php168/article_tpl");
	}
	if(!is_dir(PHP168_PATH."php168/member_tpl")){
		makepath(PHP168_PATH."php168/member_tpl");
	}
	write_file(PHP168_PATH."php168/admin_tpl/post_$id.htm",$tpl_post);
	write_file(PHP168_PATH."php168/member_tpl/post_$id.htm",$member_tpl_post);
	write_file(PHP168_PATH."template/default/bencandy_$id.htm",$tpl_show);	
	write_file(PHP168_PATH."template/default/search_$id.htm",$tpl_search);	
	if(!is_writable(PHP168_PATH."php168/admin_tpl/post_$id.htm")){	
		showmsg("php168/admin_tpl/post_$id.htmģ������ʧ��,�п�����Ŀ¼Ȩ�޲���д,���ֹ�����һ��,���ƴ����ȥ");	
	}
	if(!is_writable(PHP168_PATH."php168/member_tpl/post_$id.htm")){	
		showmsg("php168/member_tpl/post_$id.htmģ������ʧ��,�п�����Ŀ¼Ȩ�޲���д,���ֹ�����һ��,���ƴ����ȥ");	
	}
	if(!is_writable(PHP168_PATH."template/default/bencandy_$id.htm")){	
		showmsg("template/default/bencandy_$id.htmģ������ʧ��,�п�����Ŀ¼Ȩ�޲���д,���ֹ�����һ��,���ƴ����ȥ");	
	}
	if(!is_writable(PHP168_PATH."template/default/search_$id.htm")){	
		showmsg("template/default/search_$id.htmģ������ʧ��,�п�����Ŀ¼Ȩ�޲���д,���ֹ�����һ��,���ƴ����ȥ");	
	}
	if($automaketpl){
		jump("���Ժ�,����������һ��ģ��","index.php?lfj=$lfj&job=$action&page=$page&automaketpl=$automaketpl",1);
	}else{
		jump("ģ���������","index.php?lfj=article_module&job=editsort&id=$id");
	}
}	
elseif($action=="delete"&&$Apower[article_module])	
{	
	$erp=$article_moduleDB[$id][iftable]?$article_moduleDB[$id][iftable]:"";
	$rs=$db->get_one("SELECT count(*) AS num FROM {$pre}article$erp WHERE mid='$id' ");	
	if($rs[num]){	
		showmsg("��ģ��Ƶ������������,����ɾ������");	
	}
	$rs=$db->get_one("SELECT count(*) AS num FROM {$pre}sort WHERE fmid='$id' ");	
	if($rs[num]){	
		showmsg("��ģ��Ƶ��������Ŀ��,����ɾ����Ŀ");	
	}
	$db->query(" DELETE FROM `{$pre}article_module` WHERE id='$id' ");	
	$db->query(" DROP TABLE IF EXISTS `{$pre}article_content_{$id}`");
	if( !$db->get_one("SELECT * FROM `{$pre}article_module` WHERE iftable='{$article_moduleDB[$id][iftable]}'") ){
		$db->query(" DROP TABLE IF EXISTS `{$pre}article$erp`");
		$db->query(" DROP TABLE IF EXISTS `{$pre}reply$erp`");
	}

	unlink(PHP168_PATH."php168/admin_tpl/post_$id.htm");
	unlink(PHP168_PATH."php168/member_tpl/post_$id.htm");
	unlink(PHP168_PATH."template/default/bencandy_$id.htm");
	unlink(PHP168_PATH."template/default/search_$id.htm");

	//���ɻ���
	article_module_cache();
	
	jump("ɾ���ɹ�","index.php?lfj=article_module&job=list");	
}
elseif($job=="use"&&$Apower[article_module]){
	$db->query("UPDATE {$pre}article_module SET ifclose='$va' WHERE id='$id' ");
	jump("","$FROMURL",0);
}
	
	
function make_post_table($rs,$tplcode=''){
	$rs[form_title]=StripSlashes($rs[form_title]);

	//�����д��ڱ�����$unsetdb[]=$rsdb[photourl];�Ļ�,�Ͳ�Ҫ�ظ�����ʾ��
	if($tplcode&&strstr($tplcode,"\$unsetdb[]=\$rsdb[{$rs[field_name]}]")){
		return ;
	}
	if($rs[mustfill]=='2'||$rs[form_type]=='pingfen'){	
		return ;	
	}elseif($rs[mustfill]=='1'){	
		$mustfill='<font color=red>(����)</font>';	
	}	
	if($rs[form_type]=='text')	
	{
		$rs[field_inputleng]>0 || $rs[field_inputleng]=10;
		$show="<tr> <td >{$rs[title]}:$mustfill</td> <td > <input type='text' name='post_db[{$rs[field_name]}]' id='atc_{$rs[field_name]}' size='{$rs[field_inputleng]}' value='\$rsdb[{$rs[field_name]}]'> $rs[form_units] {$rs[form_title]}</td></tr>";	
	}
	elseif($rs[form_type]=='time')	
	{	
		$show="<tr> <td >{$rs[title]}:$mustfill </td> <td > <input  onclick=\"setday(this,1)\" type='text' name='post_db[{$rs[field_name]}]' id='atc_{$rs[field_name]}' size='20' value='\$rsdb[{$rs[field_name]}]'> $rs[form_units] {$rs[form_title]}</td></tr>";	
	}
	elseif($rs[form_type]=='upfile')	
	{	
		$show="<tr> <td >{$rs[title]}:$mustfill<br>{$rs[form_title]}</td> <td > <input type='text' name='post_db[{$rs[field_name]}]' id='atc_{$rs[field_name]}' size='50' value='\$rsdb[{$rs[field_name]}]'> $rs[form_units]<br><iframe frameborder=0 height=23 scrolling=no src='upfile.php?fn=upfile&dir=\$_pre\$fid&label=atc_{$rs[field_name]}&ISone=1' width=310></iframe> </td></tr>";	
	}
	elseif($rs[form_type]=='upplay')	
	{	
		$show="<tr> <td >{$rs[title]}:$mustfill<br>{$rs[form_title]}</td> <td >
 ����������: <input style=\"display:none;\" type=\"text\" name=\"post_db[{$rs[field_name]}][type][]\" id=\"atc_{$rs[field_name]}_type0\" size=\"3\" value=\"{\$rsdb[{$rs[field_name]}][type][0]}\"><select id=\"obj_Select_0\" onChange=\"document.getElementById('atc_{$rs[field_name]}_type0').value=this.options[this.selectedIndex].value\"><option value=\"\">��ѡ��</option><option value=\"avi\">MediaPlayer</option><option value=\"rm\">RealPlayer</option><option value=\"swf\">FLASH</option><option value=\"flv\">FLV������</option><option value=\"mp3\">MP3������</option></select>
 ��ַ: 	<input type=\"text\" name=\"post_db[{$rs[field_name]}][url][]\" id=\"atc_{$rs[field_name]}_url0\" size=\"40\" value=\"{\$rsdb[{$rs[field_name]}][url][0]}\">	
                    [<a href='javascript:' onClick='window.open(\"upfile.php?fn=upfile_{$rs[field_name]}&dir=\$_pre\$fid&label=0\",\"\",\"width=350,height=50,top=200,left=400\")'><font color=\"#FF0000\">����ϴ��ļ�</font></a>] <SCRIPT LANGUAGE=\"JavaScript\">
function obj_Select_{$rs[field_name]}(){
	objSelect=document.getElementById('obj_Select_0');
	for(var i=0;i<objSelect.options.length;i++)
	{
		if(document.getElementById('atc_{$rs[field_name]}_type0').value==objSelect.options[i].value){
			objSelect.options[i].selected=true;
		}
	}
}
obj_Select_{$rs[field_name]}();
function upfile_{$rs[field_name]}(url,name,size,label){	
	document.getElementById(\"atc_{$rs[field_name]}_url\"+label).value=url;	
}	
</SCRIPT></td></tr>";	
	}
	elseif($rs[form_type]=='upmoremv')	
	{	
		$show="<tr> <td >{$rs[title]}:$mustfill<br>{$rs[form_title]}<br>���� <input type='text' size='3' name='nums_{$rs[field_name]}' value='2'> �� <input type='button' name='Submit2' value='����' onClick='showinput_{$rs[field_name]}()'><br><br>��ʾ:Ҫɾ��ĳһ��,ֻ�����һ���������ռ���.</td> <td ><!--	
EOT;
\$num=count(\$rsdb[{$rs[field_name]}][url]);	
\$num||\$num=1;	
for( \$i=0; \$i<\$num ;\$i++ ){	
print <<<EOT
--> ����: <input type=\"text\" name=\"post_db[{$rs[field_name]}][name][]\" id=\"atc_{$rs[field_name]}_name\$i\" size=\"8\" value=\"{\$rsdb[{$rs[field_name]}][name][\$i]}\">	
 ����{\$webdb[MoneyName]}: <input type=\"text\" name=\"post_db[{$rs[field_name]}][fen][]\" id=\"atc_{$rs[field_name]}_fen\$i\" size=\"3\" value=\"{\$rsdb[{$rs[field_name]}][fen][\$i]}\">
 ����������: <input style=\"display:none;\" type=\"text\" name=\"post_db[{$rs[field_name]}][type][]\" id=\"atc_{$rs[field_name]}_type\$i\" size=\"3\" value=\"{\$rsdb[{$rs[field_name]}][type][\$i]}\"><select id=\"obj_Select_\$i\" onChange=\"document.getElementById('atc_{$rs[field_name]}_type\$i').value=this.options[this.selectedIndex].value\"><option value=\"\">ϵͳʶ��</option><option value=\"avi\">MediaPlayer</option><option value=\"rm\">RealPlayer</option><option value=\"swf\">FLASH</option><option value=\"flv\">FLV������</option><option value=\"mp3\">MP3������</option></select>
 ��ַ: 	
                    <input type=\"text\" name=\"post_db[{$rs[field_name]}][url][]\" id=\"atc_{$rs[field_name]}_url\$i\" size=\"20\" value=\"{\$rsdb[{$rs[field_name]}][url][\$i]}\">	
                    [<a href='javascript:' onClick='window.open(\"upfile.php?fn=upfile_{$rs[field_name]}&dir=\$_pre\$fid&label=\$i\",\"\",\"width=350,height=50,top=200,left=400\")'><font color=\"#FF0000\">����ϴ��ļ�</font></a>] <SCRIPT LANGUAGE=\"JavaScript\">
function obj_Select_{$rs[field_name]}_\$i(){
	objSelect=document.getElementById('obj_Select_\$i');
	for(var i=0;i<objSelect.options.length;i++)
	{
		if(document.getElementById('atc_{$rs[field_name]}_type\$i').value==objSelect.options[i].value){
			objSelect.options[i].selected=true;
		}
	}
}
obj_Select_{$rs[field_name]}_\$i();
</SCRIPT>	
                    <br><!--	
EOT;
}
print <<<EOT
--><div id=\"input_{$rs[field_name]}\"></div>	
<script LANGUAGE=\"JavaScript\">	
totalnum_{$rs[field_name]}=0;	
function showinput_{$rs[field_name]}(){	
	var str=document.getElementById(\"input_{$rs[field_name]}\").innerHTML;	
	var num=2;	
	num=document.FORM.nums_{$rs[field_name]}.value;	
	for(var i=1;i<=num;i++){
		if(parent.document.getElementById('member_mainiframe')!=null){
	parent.document.getElementById('member_mainiframe').height=parseInt(parent.document.getElementById('member_mainiframe').height)+18;
	}
		totalnum_{$rs[field_name]}=totalnum_{$rs[field_name]}+i+\$num-1;	
	    str+='����: &nbsp;<input type=\"text\" name=\"post_db[{$rs[field_name]}][name][]\" id=\"atc_{$rs[field_name]}_name'+totalnum_{$rs[field_name]}+'\" size=\"8\"> ����{\$webdb[MoneyName]}: &nbsp;<input type=\"text\" name=\"post_db[{$rs[field_name]}][fen][]\" id=\"atc_{$rs[field_name]}_fen'+totalnum_{$rs[field_name]}+'\" size=\"3\"> ����������: &nbsp;<input  style=\"display:none;\" type=\"text\" name=\"post_db[{$rs[field_name]}][type][]\" id=\"atc_{$rs[field_name]}_type'+totalnum_{$rs[field_name]}+'\" size=\"3\"><select onChange=\"document.getElementById(\'atc_{$rs[field_name]}_type'+totalnum_{$rs[field_name]}+'\').value=this.options[this.selectedIndex].value\"><option value=\"\">ϵͳʶ��</option><option value=\"avi\">MediaPlayer</option><option value=\"rm\">RealPlayer</option><option value=\"swf\">FLASH</option><option value=\"flv\">FLV������</option><option value=\"mp3\">MP3������</option></select> ��ַ: &nbsp;<input type=\"text\" name=\"post_db[{$rs[field_name]}][url][]\" id=\"atc_{$rs[field_name]}_url'+totalnum_{$rs[field_name]}+'\" size=\"20\" > [<a href=\'javascript:\' onClick=\'window.open(\"upfile.php?fn=upfile_{$rs[field_name]}&dir=\$_pre\$fid&label='+totalnum_{$rs[field_name]}+'\",\"\",\"width=350,height=50,top=200,left=400\")\'><font color=\"#FF0000\">����ϴ��ļ�</font></a>]<br>';	
	}	
	document.getElementById(\"input_{$rs[field_name]}\").innerHTML=str;	
}	
	
function upfile_{$rs[field_name]}(url,name,size,label){	
	document.getElementById(\"atc_{$rs[field_name]}_url\"+label).value=url;	
	arr=name.split('.');	
	document.getElementById(\"atc_{$rs[field_name]}_name\"+label).value=arr[0];	
}	
</SCRIPT></td></tr>";	
	}
	elseif($rs[form_type]=='upmorefile')	
	{	
		$show="<tr> <td >{$rs[title]}:$mustfill<br>{$rs[form_title]}<br>���� <input type='text' size='3' name='nums_{$rs[field_name]}' value='2'> �� <input type='button' name='Submit2' value='����' onClick='showinput_{$rs[field_name]}()'><br><br>��ʾ:Ҫɾ��ĳһ��,ֻ�����һ���������ռ���.</td> <td ><!--	
EOT;
\$num=count(\$rsdb[{$rs[field_name]}][url]);	
\$num||\$num=1;	
for( \$i=0; \$i<\$num ;\$i++ ){	
print <<<EOT
--> ����: <input type=\"text\" name=\"post_db[{$rs[field_name]}][name][]\" id=\"atc_{$rs[field_name]}_name\$i\" size=\"15\" value=\"{\$rsdb[{$rs[field_name]}][name][\$i]}\">	
 ����{\$webdb[MoneyName]}: <input type=\"text\" name=\"post_db[{$rs[field_name]}][fen][]\" id=\"atc_{$rs[field_name]}_fen\$i\" size=\"3\" value=\"{\$rsdb[{$rs[field_name]}][fen][\$i]}\">	
 ��ַ: 	
                    <input type=\"text\" name=\"post_db[{$rs[field_name]}][url][]\" id=\"atc_{$rs[field_name]}_url\$i\" size=\"30\" value=\"{\$rsdb[{$rs[field_name]}][url][\$i]}\">	
                    [<a href='javascript:' onClick='window.open(\"upfile.php?fn=upfile_{$rs[field_name]}&dir=\$_pre\$fid&label=\$i\",\"\",\"width=350,height=50,top=200,left=400\")'><font color=\"#FF0000\">����ϴ��ļ�</font></a>] 	
                    <br><!--	
EOT;
}
print <<<EOT
--><div id=\"input_{$rs[field_name]}\"></div>	
<script LANGUAGE=\"JavaScript\">	
totalnum_{$rs[field_name]}=0;	
function showinput_{$rs[field_name]}(){	
	var str=document.getElementById(\"input_{$rs[field_name]}\").innerHTML;	
	var num=2;	
	num=document.FORM.nums_{$rs[field_name]}.value;	
	for(var i=1;i<=num;i++){
		if(parent.document.getElementById('member_mainiframe')!=null){
	parent.document.getElementById('member_mainiframe').height=parseInt(parent.document.getElementById('member_mainiframe').height)+18;
	}
		totalnum_{$rs[field_name]}=totalnum_{$rs[field_name]}+i+\$num-1;	
	    str+='����: &nbsp;<input type=\"text\" name=\"post_db[{$rs[field_name]}][name][]\" id=\"atc_{$rs[field_name]}_name'+totalnum_{$rs[field_name]}+'\" size=\"15\"> ����{\$webdb[MoneyName]}: &nbsp;<input type=\"text\" name=\"post_db[{$rs[field_name]}][fen][]\" id=\"atc_{$rs[field_name]}_fen'+totalnum_{$rs[field_name]}+'\" size=\"3\"> ��ַ: &nbsp;<input type=\"text\" name=\"post_db[{$rs[field_name]}][url][]\" id=\"atc_{$rs[field_name]}_url'+totalnum_{$rs[field_name]}+'\" size=\"30\" > [<a href=\'javascript:\' onClick=\'window.open(\"upfile.php?fn=upfile_{$rs[field_name]}&dir=\$_pre\$fid&label='+totalnum_{$rs[field_name]}+'\",\"\",\"width=350,height=50,top=200,left=400\")\'><font color=\"#FF0000\">����ϴ��ļ�</font></a>]<br>';	
	}	
	document.getElementById(\"input_{$rs[field_name]}\").innerHTML=str;	
}	
	
function upfile_{$rs[field_name]}(url,name,size,label){	
	document.getElementById(\"atc_{$rs[field_name]}_url\"+label).value=url;	
	arr=name.split('.');	
	document.getElementById(\"atc_{$rs[field_name]}_name\"+label).value=arr[0];	
}	
</SCRIPT></td></tr>";	
	}
	elseif($rs[form_type]=='upmorepic')	
	{	
		$show="<tr> <td >{$rs[title]}:$mustfill<br>{$rs[form_title]}<br>���� <input type='text' size='3' name='nums_{$rs[field_name]}' value='2'> �� <input type='button' name='Submit2' value='����' onClick='showinput_{$rs[field_name]}()'><br><br>��ʾ:Ҫɾ��ĳһ��,ֻ�����һ���������ռ���.</td> <td ><!--	
EOT;
\$num=count(\$rsdb[{$rs[field_name]}][url]);	
\$num||\$num=1;	
for( \$i=0; \$i<\$num ;\$i++ ){	
print <<<EOT
--> ����: <input type=\"text\" name=\"post_db[{$rs[field_name]}][name][]\" id=\"atc_{$rs[field_name]}_name\$i\" size=\"15\" value=\"{\$rsdb[{$rs[field_name]}][name][\$i]}\"> 	
 ��ַ: 	
                    <input type=\"text\" name=\"post_db[{$rs[field_name]}][url][]\" id=\"atc_{$rs[field_name]}_url\$i\" size=\"30\" value=\"{\$rsdb[{$rs[field_name]}][url][\$i]}\">	
                    [<a href='javascript:' onClick='window.open(\"upfile.php?fn=upfile_{$rs[field_name]}&dir=\$_pre\$fid&label=\$i\",\"\",\"width=350,height=50,top=200,left=400\")'><font color=\"#FF0000\">����ϴ��ļ�</font></a>] 	
                    <br><!--	
EOT;
}
print <<<EOT
--><div id=\"input_{$rs[field_name]}\"></div>	
<script LANGUAGE=\"JavaScript\">	
totalnum_{$rs[field_name]}=0;	
function showinput_{$rs[field_name]}(){	
	var str=document.getElementById(\"input_{$rs[field_name]}\").innerHTML;	
	var num=2;	
	num=document.FORM.nums_{$rs[field_name]}.value;	
	for(var i=1;i<=num;i++){
		if(parent.document.getElementById('member_mainiframe')!=null){
	parent.document.getElementById('member_mainiframe').height=parseInt(parent.document.getElementById('member_mainiframe').height)+18;
	}
		totalnum_{$rs[field_name]}=totalnum_{$rs[field_name]}+i+\$num-1;	
	    str+='����: &nbsp;<input type=\"text\" name=\"post_db[{$rs[field_name]}][name][]\" id=\"atc_{$rs[field_name]}_name'+totalnum_{$rs[field_name]}+'\" size=\"15\">  ��ַ: &nbsp;<input type=\"text\" name=\"post_db[{$rs[field_name]}][url][]\" id=\"atc_{$rs[field_name]}_url'+totalnum_{$rs[field_name]}+'\" size=\"30\" > [<a href=\'javascript:\' onClick=\'window.open(\"upfile.php?fn=upfile_{$rs[field_name]}&dir=\$_pre\$fid&label='+totalnum_{$rs[field_name]}+'\",\"\",\"width=350,height=50,top=200,left=400\")\'><font color=\"#FF0000\">����ϴ��ļ�</font></a>]<br>';	
	}	
	document.getElementById(\"input_{$rs[field_name]}\").innerHTML=str;	
}	
	
function upfile_{$rs[field_name]}(url,name,size,label){	
	document.getElementById(\"atc_{$rs[field_name]}_url\"+label).value=url;	
	arr=name.split('.');	
	document.getElementById(\"atc_{$rs[field_name]}_name\"+label).value=arr[0];	
}	
</SCRIPT></td></tr>";	
	}
	elseif($rs[form_type]=='textarea')	
	{	
		$show="<tr><td >{$rs[title]}:$mustfill </td><td ><textarea name='post_db[{$rs[field_name]}]' id='atc_{$rs[field_name]}' cols='70' rows='8'>\$rsdb[{$rs[field_name]}]</textarea>$rs[form_units] {$rs[form_title]}</td></tr>";	
	}	
	elseif($rs[form_type]=='ieedit')	
	{	
		$show="<tr><td >{$rs[title]}:$mustfill<br>{$rs[form_title]}</td><td ><iframe id='eWebEditor1' src='ewebeditor/ewebeditor.php?id=atc_{$rs[field_name]}&style=standard&etype=1' frameborder='0' scrolling='no' width='630' height='200'></iframe>$rs[form_units]<input name='post_db[{$rs[field_name]}]' id='atc_{$rs[field_name]}' type='hidden' value='\$rsdb[{$rs[field_name]}]'></td></tr>";	
	}	
	elseif($rs[form_type]=='select')	
	{	
		$detail=explode("\r\n",$rs[form_set]);	
		foreach( $detail AS $key=>$value){	
			if($value===''){	
				continue;	
			}	
			list($v1,$v2)=explode("|",$value);	
			$v2 || $v2=$v1;	
			$_show.="<option value='$v1' {\$rsdb[{$rs[field_name]}]['{$v1}']}>$v2</option>";	
		}	
		$show="<tr> <td >{$rs[title]}:$mustfill </td><td > <select name='post_db[{$rs[field_name]}]' id='atc_{$rs[field_name]}'>$_show</select>$rs[form_units] {$rs[form_title]}</td> </tr>";	
	}	
	elseif($rs[form_type]=='radio')	
	{	
		$detail=explode("\r\n",$rs[form_set]);	
		foreach( $detail AS $key=>$value){	
			if($value===''){	
				continue;	
			}	
			list($v1,$v2)=explode("|",$value);	
			$v2 || $v2=$v1;	
			$_show.="<input type='radio' name='post_db[{$rs[field_name]}]' value='$v1' {\$rsdb[{$rs[field_name]}]['{$v1}']}>$v2";	
		}	
		$show="<tr> <td >{$rs[title]}:$mustfill </td> <td >$_show$rs[form_units] {$rs[form_title]}</td></tr>";	
	}	
	elseif($rs[form_type]=='checkbox')	
	{	
		$detail=explode("\r\n",$rs[form_set]);
		foreach( $detail AS $key=>$value){
			if($value===''){
				continue;
			}
			list($v1,$v2)=explode("|",$value);
			$v2 || $v2=$v1;
			$_show.="<input type='checkbox' name='post_db[{$rs[field_name]}][]' value='$v1' {\$rsdb[{$rs[field_name]}]['{$v1}']}>$v2";
		}
		$show="<tr> <td >{$rs[title]}:$mustfill<br>{$rs[form_title]}</td> <td >$_show$rs[form_units] {$rs[form_title]}</td></tr>";
	}
	return $show;
}

function make_show_table($rs,$styleclass='',$tplcode=''){
	//�����д��ڱ�����$unsetdb[]=$rsdb[photourl];�Ļ�,�Ͳ�Ҫ�ظ�����ʾ��
	if($tplcode&&strstr($tplcode,"\$unsetdb[]=\$rsdb[{$rs[field_name]}]")){
		return ;
	}
	if($rs[mustfill]=='2'){	
		return ;	
	}	
	if($rs[form_type]=='pingfen'){	
		$detail=explode("\r\n",$rs[form_set]);	
		foreach( $detail AS $key=>$value){	
			if($value===''){	
				continue;	
			}	
			list($v1,$v2)=explode("|",$value);	
			$v2 || $v2=$v1;	
			$selected=$v1==$rs[form_value]?' selected ':'';	
			$_show.="<option value='$v1' {\$rsdb[{$rs[field_name]}]['{$v1}']} $selected>$v2</option>";	
		}	
		$show="<select name='postdb[{$rs[field_name]}]' id='atc_{$rs[field_name]}'>$_show</select>&nbsp;<input type='submit' value='�ύ'><input type='hidden' name='id' value='\$id'><input type='hidden' name='fid' value='\$fid'><input type='hidden' name='mid' value='\$rsdb[mid]'><input type='hidden' name='rid' value='\$rsdb[rid]'><input type='hidden' name='i_id' value='\$rsdb[id]'>";	
	}	
	$show="<tr id='tr_{$rs[field_name]}'> <td class='a1$styleclass'>{$rs[title]}:</td> <td class='a2$styleclass'>{\$rsdb[{$rs[field_name]}]}&nbsp;{$rs[form_units]}</td></tr>";	
	if($rs[form_type]=='pingfen'){	
		$show="<form method='post' action='\$webdb[www_url]/do/job.php?job=pingfen'>$show</form>";	
	}	
	return $show;	
}	
	
function make_search_table($rs)	
{	
	if($rs[form_type]=="select"||$rs[form_type]=="radio"||$rs[form_type]=="checkbox")	
	{	
		$detail=explode("\r\n",$rs[form_set]);	
		foreach( $detail AS $key=>$value){	
			if(!$value){	
				continue;	
			}	
			list($v1,$v2)=explode("|",$value);	
			$v2 || $v2=$v1;	
			$_show.="<option value='$v1' {\$rsdb[{$rs[field_name]}]['{$v1}']}>$v2</option>";	
		}	
		$show="<select name='postdb[{$rs[field_name]}]' id='atc_{$rs[field_name]}'><option value=''>����</option>$_show</select>";			
	}	
	else	
	{	
		$show="&nbsp;<input type='radio' name='type' style='border:0px;' value='{$rs[field_name]}' \$typedb[{$rs[field_name]}]>{$rs[title]} ";	
	}	
	return $show;	
}	
?>