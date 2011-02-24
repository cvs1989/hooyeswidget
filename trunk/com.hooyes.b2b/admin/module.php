<?php
!function_exists('html') && exit('ERR');


if($job=='list')
{
	$query = $db->query("SELECT * FROM {$pre}module ORDER BY list DESC");
	while($rs = $db->fetch_array($query))
	{
		if($rs[domain]){
			$rs[url]=$rs[domain];
		}else{
			$rs[url]="$webdb[www_url]/$rs[dirname]";
		}
		if($rs[type]==2){
			$rs[type]='<font color=blue>ģ��</font>';
		}else{
			$rs[type]='<font color=red>ϵͳ</font>';
		}
		//$rs[type]=$rs[type]?'����ϵͳ':'�̶�ϵͳ';
		$rs[admindir]=$rs[admindir]?$rs[admindir]:'admin';
		$listdb[]=$rs;
	}
	require(dirname(__FILE__)."/"."head.php");
	require(dirname(__FILE__)."/"."template/module/menu.htm");
	require(dirname(__FILE__)."/"."template/module/list.htm");
	require(dirname(__FILE__)."/"."foot.php");
}
elseif($job=='automake')
{
	$dir=opendir(ROOT_PATH);
	while($file=readdir($dir)){
		if($file!='.'&&$file!='..'&&is_file(ROOT_PATH."$file/install/fix.php")){
			$array = include(ROOT_PATH."$file/install/fix.php");
			if(!$db->get_one("SELECT * FROM {$pre}module WHERE pre='$array[pre]'")){
				$listdb[$file]=$array;
			}
		}
	}
	require(dirname(__FILE__)."/"."head.php");
	require(dirname(__FILE__)."/"."template/module/menu.htm");
	require(dirname(__FILE__)."/"."template/module/automake.htm");
	require(dirname(__FILE__)."/"."foot.php");
}
elseif($job=='make')
{
	require(dirname(__FILE__)."/"."head.php");
	require(dirname(__FILE__)."/"."template/module/menu.htm");
	require(dirname(__FILE__)."/"."template/module/make.htm");
	require(dirname(__FILE__)."/"."foot.php");
}
elseif($action=='automake')
{
	$array = @include(ROOT_PATH."$file/install/fix.php");
	if(!$db->get_one("SELECT * FROM {$pre}module WHERE pre='$array[pre]'")){
		$readfiles = read_file(ROOT_PATH."$file/install/data.sql");
		$readfiles = str_replace("qb_","$pre",$readfiles);
		$db->insert_file('',$readfiles);	//�������ݿ�
		
		//������ݱ����ַ�����qb_�Ļ���serialize�ַ��ĳ��Ȼ������仯�����Ҫ����
		if(strpos($readfiles,"{$pre}label")&&strlen($pre)!=3){
			$query=$db->query("SELECT * FROM {$pre}label WHERE typesystem=1 ");
			while($rs=$db->fetch_array($query)){
				$rs[code]=preg_replace("/s:([\d]+):\"([^\"]+)\"/eis","strlen_lable('\\1','\\2')",$rs[code]);
				$rs[code]=addslashes($rs[code]);
				$db->query("UPDATE {$pre}label SET code='$rs[code]' WHERE lid='$rs[lid]' ");
			}
		}
		
		//�е�ģ�����Ҫִ��һЩ����
		@include(ROOT_PATH."$file/install/install.inc.php");
	}
	refreshto("index.php?lfj=module&job=list","��װ�ɹ���<br><a href='index.php?lfj=group&job=admin_gr&gid=3'>��������һ��Ȩ��</a>",60);
}
elseif($action=='make')
{
	if($db->get_one("SELECT * FROM {$pre}module WHERE pre='$postdb[pre]'")){
		showmsg("��ϵͳ�Ѵ�����,�벻Ҫ�ظ�����");
	}
	if(!$postdb[pre]){
		showmsg("�ؼ���/���ݱ�ǰ׺����Ϊ��");
	}
	if(!$postdb['dirname']){
		showmsg("ϵͳ���Ŀ¼����Ϊ��");
	}
	if(!is_dir(ROOT_PATH.$postdb['dirname'])){
		showmsg("Ŀ¼������");
	}
	if($postdb[admindir]&&!is_dir(ROOT_PATH.$postdb['dirname']."/$postdb[admindir]")){
		showmsg("��̨Ŀ¼������");
	}
	if(ereg("^(photo|down|shop|flash|blog|mv|music)$",$postdb[pre]))
	{
		if( !is_table("{$pre}{$postdb[pre]}_config") ){
			showmsg("���Ȱ�װ��ϵͳ,����д�˱�");
		}
		$postdb[type]=0;
	}
	else
	{
		if( !is_table("{$pre}{$postdb[pre]}config") ){
			showmsg("���Ȱ�װ��ϵͳ,����д�˱�");
		}
		$type=1;
	}
	$db->query("INSERT INTO `{$pre}module` ( `type`, `name`, `pre`, `dirname`, `domain`, `admindir`) VALUES ('$postdb[type]', '$postdb[name]', '$postdb[pre]', '$postdb[dirname]', '$postdb[domain]', '$postdb[admindir]')");

	if($type==1)
	{
		$rs=$db->get_one("SELECT * FROM {$pre}module WHERE pre='$postdb[pre]'");
		$db->query("DELETE FROM `{$pre}{$postdb[pre]}config` WHERE c_key='module_id'");
		$db->query("INSERT INTO `{$pre}{$postdb[pre]}config` ( `c_key` , `c_value` , `c_descrip` ) VALUES ('module_id', '$rs[id]', '')");
	}
	make_module_cache();
	jump("�����ɹ�","index.php?lfj=module&job=list",1);
}
elseif($job=='mod')
{
	$rsdb=$db->get_one("SELECT * FROM {$pre}module WHERE id='$id'");
	$ifclose[$rsdb[ifclose]]=' checked ';
	require(dirname(__FILE__)."/"."head.php");
	require(dirname(__FILE__)."/"."template/module/mod.htm");
	require(dirname(__FILE__)."/"."foot.php");
}
elseif($action=='mod')
{
	if(!$admindir=$postdb[admindir]){
		$admindir="admin";
	}
	if(!is_dir(ROOT_PATH."$postdb[dirname]/$postdb[admindir]")){
		showerr("��̨Ŀ¼������".ROOT_PATH."$postdb[dirname]/$postdb[admindir]");
	}
	if(!is_writable(ROOT_PATH."$postdb[dirname]/data/")){
		showerr(ROOT_PATH."$postdb[dirname]/data/"."Ŀ¼����д");
	}




	$db->query("UPDATE {$pre}module SET name='$postdb[name]',dirname='$postdb[dirname]',admindir='$postdb[admindir]',domain='$postdb[domain]',list='$postdb[list]',adminmember='$postdb[adminmember]',ifclose='$postdb[ifclose]' WHERE id='$id'");
	make_module_cache();


	@extract($db->get_one("SELECT pre AS Mpre,id AS Mid,type AS Type FROM `{$pre}module` WHERE id='$id' "));

	if($Type){
		$table="{$pre}{$Mpre}config";
	}else{
		$table="{$pre}{$Mpre}_config";
	}

	if(!is_table($table)){
		jump("�޸ĳɹ�!","index.php?lfj=module&job=list",1);
	}

	if(!is_writable(ROOT_PATH."$postdb[dirname]/data/config.php")){
		showerr(ROOT_PATH."$postdb[dirname]/data/config.php"."�ļ�����д");
	}

	$db->query("DELETE FROM `$table` WHERE c_key='module_id'");
	$db->query("DELETE FROM `$table` WHERE c_key='module_pre'");

	$db->query("DELETE FROM `$table` WHERE c_key='module_close'");

	$db->query("INSERT INTO `$table` ( `c_key` , `c_value` , `c_descrip` ) VALUES ('module_id', '$Mid', '')");

	$db->query("INSERT INTO `$table` ( `c_key` , `c_value` , `c_descrip` ) VALUES ('module_pre', '$Mpre', '')");

	$db->query("INSERT INTO `$table` ( `c_key` , `c_value` , `c_descrip` ) VALUES ('module_close', '$postdb[ifclose]', '')");

	$writefile="<?php\r\n";
	$query = $db->query("SELECT * FROM `$table`");
	while($rs = $db->fetch_array($query)){
		$rs[c_value]=addslashes($rs[c_value]);
		$writefile.="\$webdb['$rs[c_key]']='$rs[c_value]';\r\n";
	}
	write_file(ROOT_PATH."$postdb[dirname]/data/config.php",$writefile);


	jump("�޸ĳɹ�","index.php?lfj=module&job=list",1);
}
elseif($action=="del")
{
	$rsdb=$db->get_one("SELECT * FROM {$pre}module WHERE id='$id'");

	$array = @include(ROOT_PATH."$rsdb[dirname]/install/fix.php");

	if($array[forbid_del]){
		showmsg('��ǰģ�������˽�ֹж�أ�');
	}

	$query=$db->query("SHOW TABLE STATUS");
	while( $rs=$db->fetch_array($query) ){
		if(eregi("^{$pre}{$rsdb[pre]}",$rs[Name])){
			$db->query("DROP TABLE IF EXISTS `$rs[Name]`");	//ɾ����ص����ݱ�����ܹؼ�����������ͬ��ǰ׺
		}
	}
	$db->query("DELETE FROM `{$pre}label` WHERE module='$id'");	//ɾ����ǩ
	$db->query("DELETE FROM {$pre}module WHERE id='$id'");	//ɾ��ģ�������ļ�

	del_file(ROOT_PATH."$rsdb[dirname]/");	//ɾ��Ŀ¼�ļ�

	make_module_cache();

	jump("ж�سɹ�","index.php?lfj=module&job=list",1);
}
elseif($job=='copy')
{
	$rsdb=$db->get_one("SELECT * FROM {$pre}module WHERE id='$id'");

	$array = @include(ROOT_PATH."$rsdb[dirname]/install/fix.php");

	if($array[forbid_copy]){
		showmsg('��ǰģ�������˽�ֹ���ƣ�');
	}

	require(dirname(__FILE__)."/"."head.php");
	require(dirname(__FILE__)."/"."template/module/copy.htm");
	require(dirname(__FILE__)."/"."foot.php");

}
elseif($action=='copy')
{
	$rsdb=$db->get_one("SELECT * FROM {$pre}module WHERE id='$id'");

	$array = @include(ROOT_PATH."$rsdb[dirname]/install/fix.php");

	if($array[forbid_del]){
		showmsg('��ǰģ�������˽�ֹж�أ�');
	}
	
	if(!ereg("^([_a-z0-9]+)$",$postdb[pre])){
		showmsg('���ݱ�ǰ׺ֻ����Ӣ�Ļ�����');
	}
	if(!ereg("(_)$",$postdb[pre])){
		$postdb[pre]="{$postdb[pre]}_";
	}

	if(!ereg("^([_a-z0-9]+)$",$postdb['dir'])){
		showmsg('ֻ����Ӣ�Ļ�����');
	}
	if( !$postdb['name'] ){
		showmsg('ģ�����Ʋ���Ϊ�գ�');
	}

	$db->query("INSERT INTO `{$pre}module` (`type` , `name` , `pre` , `dirname` ) VALUES ('$rsdb[type]', '$postdb[name]', '$postdb[pre]', '$postdb[dir]')");

	$newid = $db->insert_id();

	$db->query("SET SQL_QUOTE_SHOW_CREATE = 1");
	
	$query=$db->query("SHOW TABLE STATUS");
	while( $rs=$db->fetch_array($query) ){
		if(eregi("^{$pre}{$rsdb[pre]}",$rs[Name])){
			$array=$db->get_one("SHOW CREATE TABLE $rs[Name]");
			if(mysql_get_server_info() > '4.1' && $dbcharset){
				$array['Create Table']=preg_replace("/DEFAULT CHARSET=([0-9a-z]+)/is","",$array['Create Table']);
				$array['Create Table'].=" DEFAULT CHARSET=$dbcharset";
			}
			$array['Create Table'] = str_replace("{$pre}$rsdb[pre]","{$pre}$postdb[pre]",$array['Create Table']);
			$db->query($array['Create Table']);
			$newtable=str_replace("{$pre}$rsdb[pre]","{$pre}$postdb[pre]",$rs[Name]);
			$db->query("INSERT INTO `$newtable` SELECT * FROM `$rs[Name]`");
		}
	}
	make_module_cache();

	$query = $db->query("SELECT * FROM {$pre}label WHERE module='$id'");
	while($rs = $db->fetch_array($query)){
		$rs[divcode]=addslashes($rs[divcode]);
		$rs[code]=addslashes($rs[code]);
		$db->query("INSERT INTO `{$pre}label` (  `tag` , `type` , `typesystem` , `code` , `divcode` ,  `uid` , `username` , `posttime` , `pagetype` , `module` , `fid` ,  `style` ) VALUES ( '$rs[tag]', '$rs[type]', '$rs[typesystem]', '$rs[code]', '$rs[divcode]' , '$rs[uid]', '$rs[username]',  '$rs[posttime]', '$rs[pagetype]', '$newid', '$rs[fid]', '$rs[style]' )");
	}

	copy_module_file(ROOT_PATH."$rsdb[dirname]/",ROOT_PATH."$postdb[dir]/");	//���Ƴ���Ŀ¼

	$table="{$pre}{$postdb[pre]}config";
	$db->query("DELETE FROM `$table` WHERE c_key='module_id'");
	$db->query("DELETE FROM `$table` WHERE c_key='module_pre'");
	$db->query("INSERT INTO `$table` ( `c_key` , `c_value` , `c_descrip` ) VALUES ('module_id', '$newid', '')");
	$db->query("INSERT INTO `$table` ( `c_key` , `c_value` , `c_descrip` ) VALUES ('module_pre', '$postdb[pre]', '')");

	$writefile="<?php\r\n";
	$query = $db->query("SELECT * FROM `$table`");
	while($rs = $db->fetch_array($query)){
		$rs[c_value]=addslashes($rs[c_value]);
		$writefile.="\$webdb['$rs[c_key]']='$rs[c_value]';\r\n";
	}
	write_file(ROOT_PATH."$postdb[dir]/data/config.php",$writefile);

	jump("���Ƴɹ�,������һ����ģ��ĺ�̨Ȩ��","index.php?lfj=group&job=admin_gr&gid=3",10);
}
elseif($action=="order")
{
	foreach( $postdb AS $key=>$value){
		$db->query("UPDATE {$pre}module SET list='$value' WHERE id='$key'");
	}
	
	make_module_cache();
	jump("�����ɹ�","index.php?lfj=module&job=list",1);
}


function strlen_lable($num,$sring){
	global $pre;
	if(eregi('SELECT ',$sring)){
		$num=strlen($sring);
	}	
	return "s:$num:\"$sring\"";
}

function copy_module_file($path,$newp){
	if(!is_dir($newp)){
		mkdir($newp);
	}
	if (file_exists($path)){
		if(is_file($path)){
			copy($path,$newp);
		} else{
			$handle = opendir($path);
			while (($file = readdir($handle))!='') {
				if (($file!=".") && ($file!="..") && ($file!="")){
					if (is_dir("$path/$file")){
						copy_module_file("$path/$file","$newp/$file");
					} else{
						copy("$path/$file","$newp/$file");
					}
				}
			}
			closedir($handle);
		}
	}
}

?>