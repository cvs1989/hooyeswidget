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
		$rs[type]=$rs[type]?'自由系统':'固定系统';
		$rs[admindir]=$rs[admindir]?$rs[admindir]:'admin';
		$listdb[]=$rs;
	}
	require(dirname(__FILE__)."/"."head.php");
	require(dirname(__FILE__)."/"."template/module/menu.htm");
	require(dirname(__FILE__)."/"."template/module/list.htm");
	require(dirname(__FILE__)."/"."foot.php");
}
elseif($job=='make')
{
	require(dirname(__FILE__)."/"."head.php");
	require(dirname(__FILE__)."/"."template/module/menu.htm");
	require(dirname(__FILE__)."/"."template/module/make.htm");
	require(dirname(__FILE__)."/"."foot.php");
}
elseif($action=='make')
{
	if($db->get_one("SELECT * FROM {$pre}module WHERE pre='$postdb[pre]'")){
		showmsg("此系统已存在了,请不要重复创建");
	}
	if(!$postdb[pre]){
		showmsg("关键字/数据表前缀不能为空");
	}
	if(!$postdb['dirname']){
		showmsg("系统存放目录不能为空");
	}
	if(!is_dir(PHP168_PATH.$postdb['dirname'])){
		showmsg("目录不存在");
	}
	if($postdb[admindir]&&!is_dir(PHP168_PATH.$postdb['dirname']."/$postdb[admindir]")){
		showmsg("后台目录不存在");
	}
	if(ereg("^(photo|down|shop|flash|blog|mv|music)$",$postdb[pre]))
	{
		if( !is_table("{$pre}{$postdb[pre]}_config") ){
			showmsg("请先安装好系统,再填写此表单");
		}
		$postdb[type]=0;
	}
	else
	{
		if( !is_table("{$pre}{$postdb[pre]}config") ){
			showmsg("请先安装好系统,再填写此表单");
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
	jump("创建成功","index.php?lfj=module&job=list",1);
}
elseif($job=='mod')
{
	$rsdb=$db->get_one("SELECT * FROM {$pre}module WHERE id='$id'");
	$unite_member[$rsdb[unite_member]]=' checked ';
	require(dirname(__FILE__)."/"."head.php");
	require(dirname(__FILE__)."/"."template/module/mod.htm");
	require(dirname(__FILE__)."/"."foot.php");
}
elseif($action=='mod')
{
	if(!$admindir=$postdb[admindir]){
		$admindir="admin";
	}
	if(!is_dir(PHP168_PATH."$postdb[dirname]/$postdb[admindir]")){
		showerr("后台目录不存在".PHP168_PATH."$postdb[dirname]/$postdb[admindir]");
	}
	if(!is_writable(PHP168_PATH."$postdb[dirname]/php168")){
		showerr(PHP168_PATH."$postdb[dirname]/php168"."目录不可写");
	}
	if(!is_writable(PHP168_PATH."$postdb[dirname]/php168/config.php")){
		showerr(PHP168_PATH."$postdb[dirname]/php168/config.php"."文件不可写");
	}
	$db->query("UPDATE {$pre}module SET name='$postdb[name]',dirname='$postdb[dirname]',admindir='$postdb[admindir]',domain='$postdb[domain]',list='$postdb[list]',adminmember='$postdb[adminmember]',unite_member='$postdb[unite_member]' WHERE id='$id'");
	make_module_cache();


	@extract($db->get_one("SELECT pre AS Mpre,id AS Mid,type AS Type FROM `{$pre}module` WHERE id='$id' "));

	if($Type){
		$table="{$pre}{$Mpre}config";
	}elseif($Mpre=='blog'){
		$table="{$pre}blog_setting";
	}else{
		$table="{$pre}{$Mpre}_config";
	}

	$db->query("DELETE FROM `$table` WHERE c_key='module_id'");
	$db->query("DELETE FROM `$table` WHERE c_key='module_pre'");

	$db->query("INSERT INTO `$table` ( `c_key` , `c_value` , `c_descrip` ) VALUES ('module_id', '$Mid', '')");

	$db->query("INSERT INTO `$table` ( `c_key` , `c_value` , `c_descrip` ) VALUES ('module_pre', '$Mpre', '')");

	$writefile="<?php\r\n";
	$query = $db->query("SELECT * FROM `$table`");
	while($rs = $db->fetch_array($query)){
		$rs[c_value]=addslashes($rs[c_value]);
		$writefile.="\$webdb['$rs[c_key]']='$rs[c_value]';\r\n";
	}
	write_file(PHP168_PATH."$postdb[dirname]/php168/config.php",$writefile);


	jump("修改成功","index.php?lfj=module&job=list",1);
}
elseif($action=="del")
{
	$db->query("DELETE FROM {$pre}module WHERE id='$id'");
	make_module_cache();
	jump("删除成功","index.php?lfj=module&job=list",1);
}



?>