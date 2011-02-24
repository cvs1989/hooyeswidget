<?php
!function_exists('html') && exit('ERR');
if($job=="list"&&$Apower[member_list])
{
	if($T=="noyz"){
		$SQL=" WHERE D.yz=0 AND D.uid!=0 ";
	}elseif($T=="yz"){
		$SQL=" WHERE D.yz!=0 AND D.uid!=0 ";
	}elseif($T=="email"){
		$SQL=" WHERE D.email_yz=1 AND D.uid!=0 ";
	}elseif($T=="mob"){
		$SQL=" WHERE D.mob_yz=1 AND D.uid!=0 ";
	}elseif($T=="idcard"){
		$SQL=" WHERE D.idcard_yz=1 AND D.uid!=0 ";
	}elseif($T=="unidcard"){
		$SQL=" WHERE D.idcard_yz=-1 AND D.uid!=0 ";
	}else{
		$SQL=" WHERE 1 ";
	}

	if($groupid){
		$SQL.=" AND D.groupid=$groupid ";
	}
	
	if($keywords&&$type){
		if($type=='username'){
			$SQL.=" AND BINARY M.$TB[username] LIKE '%$keywords%' ";
		}elseif($type=='uid'){
			$SQL.=" AND D.uid='$keywords' ";
		}
	}
	$select_group=select_group("groupid",$groupid,"index.php?lfj=member&job=list&T=$T");

	if(!$page){
		$page=1;
	}
	$rows=20;
	$min=($page-1)*$rows;
	$showpage=getpage("$TB[table] M LEFT JOIN {$pre}memberdata D ON M.$TB[uid]=D.uid","$SQL","index.php?lfj=$lfj&job=$job&type=$type&T=$T&keywords=$keywords&groupid=$groupid",$rows);
	$query=$db->query("SELECT D.*,M.$TB[username] AS username,M.$TB[uid] AS uid FROM $TB[table] M LEFT JOIN {$pre}memberdata D ON M.$TB[uid]=D.uid $SQL ORDER BY M.$TB[uid] DESC LIMIT $min,$rows ");
	while($rs=$db->fetch_array($query)){
		$rs[lastvist]=$rs[lastvist]?date("Y-m-d H:i:s",$rs[lastvist]):'';		
		if(!$rs[groupid]){
			$rs[alert]="alert('此用户的资料,还没有在整站激活,你不能进行任何操作!');return false;";
		}else{
			$rs[alert]="";
		}

		if($rs[yz]){
			$rs[yz]="<A HREF='index.php?lfj=$lfj&action=yz&uid_db[0]=$rs[uid]&T=noyz' style='color:red;' onclick=\"$rs[alert]\" title='已经通过审核,点击即可取消审核'><img src='../member/images/check_yes.gif' border='0'></A>";
		}elseif($rs[groupid]){
			$rs[yz]="<A HREF='index.php?lfj=$lfj&action=yz&uid_db[0]=$rs[uid]&T=yz' style='color:blue;' onclick=\"$rs[alert]\" title='还没有通过审核,点击即可通过审核'><img src='../member/images/check_no.gif' border='0'></A>";
		}else{
			$rs[yz]="未激活";
		}
		
		$listdb[]=$rs;
	}
	require(dirname(__FILE__)."/"."head.php");
	require(dirname(__FILE__)."/"."template/member/menu.htm");
	require(dirname(__FILE__)."/"."template/member/list.htm");
	require(dirname(__FILE__)."/"."foot.php");
}
elseif($job=="addmember"&&$Apower[member_addmember])
{
	$select_group=select_group("postdb[groupid]");
	

	require(dirname(__FILE__)."/"."head.php");
	require(dirname(__FILE__)."/"."template/member/menu.htm");
	require(dirname(__FILE__)."/"."template/member/addmember.htm");
	require(dirname(__FILE__)."/"."foot.php");
}
elseif($action=="addmember"&&$Apower[member_addmember])
{
	if(!$postdb[username]){
		showmsg("用户名不能为空");
	}elseif(strlen($postdb[username])>25){
		showmsg("用户名不能大于25个字符");
	}
	if(!$postdb[passwd]){
		showmsg("密码不能为空");
	}elseif($postdb[passwd]!=$postdb[passwd2]){
		showmsg("两次输入密码不正确");
	}

	if(!$postdb[groupid]){
		showmsg("请选择一个用户组");
	}elseif($postdb[groupid]=='2'){
		showmsg("你不能选择游客组");
	}elseif($postdb[groupid]=='3'&&$userdb[groupid]!=3&&!$founder){
		showmsg("你无权限选择超级管理员用户组,请更换其他的用户组");
	}elseif($postdb[groupid]=='4'&&$userdb[groupid]!=3&&$userdb[groupid]!=4&&!$founder){
		showmsg("你无权限选择此用户组,请更换其他的用户组");
	}

	$rsdb=$db->get_one("SELECT * FROM $TB[table] WHERE $TB[username]='$postdb[username]' ");
	if($rsdb){
		showmsg("$postdb[username],此用户已经存在了,请更换另一个帐号");
	}

	$postdb[passwd]=pwd_md5($postdb[passwd2]=$postdb[passwd]);

	if(eregi("^pwbbs",$webdb[passport_type]))
	{
		$db->query("INSERT INTO {$TB_pre}members (`username` , `password`,email,groupid,memberid,regdate,yz ) VALUES ('$postdb[username]', '$postdb[passwd]','$postdb[email]','-1',8,'$timestamp',1)");

		$rs=$db->get_one(" SELECT $TB[uid] AS uid FROM `$TB[table]` ORDER BY $TB[uid] DESC LIMIT 1 ");

		$db->query("INSERT INTO {$TB_pre}memberinfo (`uid`) VALUES ('$rs[uid]')");
		$db->query("INSERT INTO {$TB_pre}memberdata (`uid`) VALUES ('$rs[uid]')");
	}
	elseif(eregi("^dzbbs",$webdb[passport_type]))
	{
		if( defined("UC_CONNECT") ){
			uc_user_register($postdb[username], $postdb[passwd2], $postdb[email], $questionid = '', $answer = '');
			$rs=$db_uc->get_one("SELECT uid FROM ".UC_DBTABLEPRE."members WHERE username='$postdb[username]'");
			if(!$rs){
				showerr("添加失败,请确认帐号是否符合要求");
			}
			$db->query("INSERT INTO {$TB_pre}members (`uid` ,`username` , `password`,groupid,regdate,email) VALUES ('$rs[uid]','$postdb[username]', '$postdb[passwd]',10,'$timestamp','$postdb[email]')");

			$db->query("INSERT INTO {$TB_pre}memberfields (`uid`) VALUES ('$rs[uid]')");

		}else{
			$db->query("INSERT INTO {$TB_pre}members (`username` , `password`,groupid,regdate,email) VALUES ('$postdb[username]', '$postdb[passwd]',10,'$timestamp','$postdb[email]')");

			$rs=$db->get_one(" SELECT $TB[uid] AS uid FROM `$TB[table]` ORDER BY $TB[uid] DESC LIMIT 1 ");

			$db->query("INSERT INTO {$TB_pre}memberfields (`uid`) VALUES ('$rs[uid]')");
		}
		
	}
	elseif($webdb[passport_type])
	{
		showmsg('请在前台注册新用户');
	}
	else
	{
		$db->query("INSERT INTO `{$pre}members` (`username` , `password` ) VALUES ('$postdb[username]', '$postdb[passwd]')");
		$rs=$db->get_one(" SELECT $TB[uid] AS uid FROM `$TB[table]` ORDER BY $TB[uid] DESC LIMIT 1 ");
	}

	$db->query("INSERT INTO `{$pre}memberdata` (`uid`,`username`, `question`, `groupid`, `groups`, `yz`, `newpm`, `medals`, `money`, `lastvist`, `lastip`, `regdate`, `regip`, `sex`, `bday`, `icon`, `introduce`, `oicq`, `msn`, `homepage`, `email`, `address`, `postalcode`, `mobphone`, `telephone`, `idcard`, `truename`) VALUES ('$rs[uid]','$postdb[username]','$question','$postdb[groupid]','$groups','1','$newpm','$medals','$money','$lastvist','$onlineip','$timestamp','$onlineip','$sex','$bday','$icon','$introduce','$oicq','$msn','$homepage','$postdb[email]','$address','$postalcode','$mobphone','$telephone','$idcard','$truename')");
	jump("创建成功","index.php?lfj=member&job=list",3);
}
elseif($job=="editmember"&&$Apower[member_list])
{
	if( defined("UC_CONNECT") ){
		$rs=$db_uc->get_one("SELECT password,username FROM ".UC_DBTABLEPRE."members WHERE uid='$uid'");
		$_rs=$db->get_one("SELECT D.* FROM {$pre}memberdata D WHERE D.uid='$uid' ");
		if($rs&&$_rs){
			$rsdb=$rs+$_rs;
		}
	}else{
		$rsdb=$db->get_one("SELECT M.$TB[username] AS username,M.$TB[password] AS password,D.* FROM {$pre}memberdata D LEFT JOIN $TB[table] M ON M.$TB[uid]=D.uid WHERE D.uid='$uid' ");
	}
	
	$rsdb[money]=get_money($rsdb[uid]);
	$select_group=select_group("postdb[groupid]",$rsdb[groupid]);
	$select_group2=group_box("postdb[groups]",explode(",",$rsdb[groups]),1);

	$sexdb[intval($rsdb[sex])]=' checked ';

	$yzdb[intval($rsdb[yz])]=' checked ';

	$ConfigDB=unserialize($rsdb[config]);

	$rsdb[totalspace]=floor($rsdb[totalspace]/(1024*1024));

	$ConfigDB[begintime] && $ConfigDB[begintime]=date("Y-m-d H:i:s",$ConfigDB[begintime]);
	$ConfigDB[endtime]   && $ConfigDB[endtime]=date("Y-m-d H:i:s",$ConfigDB[endtime]);

	$email_yz[$rsdb[email_yz]]=' checked ';
	$mob_yz[$rsdb[mob_yz]]=' checked ';
	$idcard_yz[$rsdb[idcard_yz]]=' checked ';

	require(dirname(__FILE__)."/"."head.php");
	require(dirname(__FILE__)."/"."template/member/menu.htm");
	require(dirname(__FILE__)."/"."template/member/editmember.htm");
	require(dirname(__FILE__)."/"."foot.php");
}
elseif($action=="editmember"&&$Apower[member_list])
{
	if($postdb[newpassword]){
		$postdb[password]=pwd_md5($postdb[newpassword]);
	}
	$rsdb=$db->get_one("SELECT * FROM {$pre}memberdata WHERE uid='$uid'");
	if(!$rsdb){
		showmsg("此用户资料不存在,或者帐号还没激活");
	}
	
	if($rsdb[groupid]=='3'&&$userdb[groupid]!=3&&!$founder&&!$ForceEnter){
		showmsg("你无权限修改超级管理员用户组");
	}elseif($rsdb[groupid]=='4'&&$userdb[groupid]!=3&&$userdb[groupid]!=4&&!$founder&&!$ForceEnter){
		showmsg("你无权限修改此用户组");
	}elseif(!$postdb[groupid]){
		showmsg("请选择一个用户组");
	}elseif($postdb[groupid]=='2'){
		showmsg("你不能选择游客组");
	}elseif($postdb[groupid]=='3'&&$userdb[groupid]!=3&&!$founder&&!$ForceEnter){
		showmsg("你无权限选择超级管理员用户组,请更换其他的用户组");
	}elseif($postdb[groupid]=='4'&&$userdb[groupid]!=3&&$userdb[groupid]!=4&&!$founder&&!$ForceEnter){
		showmsg("你无权限选择此用户组,请更换其他的用户组");
	}

	//自定义用户字段
	require_once("../do/regfield.php");
	ck_regpost($postdb);

	$ConfigDB[begintime]&&$ConfigDB[begintime]=preg_replace("/([\d]+)-([\d]+)-([\d]+) ([\d]+):([\d]+):([\d]+)/eis","mk_time('\\4','\\5', '\\6', '\\2', '\\3', '\\1')",$ConfigDB[begintime]);

	$ConfigDB[endtime]&&$ConfigDB[endtime]=preg_replace("/([\d]+)-([\d]+)-([\d]+) ([\d]+):([\d]+):([\d]+)/eis","mk_time('\\4','\\5', '\\6', '\\2', '\\3', '\\1')",$ConfigDB[endtime]);

	$array=unserialize($rsdb[config]);
	foreach( $ConfigDB AS $key=>$value){
		$array[$key]=$value;
	}
	$_config=addslashes(serialize($array));


	$db->query("UPDATE $TB[table] SET $TB[password]='$postdb[password]',$TB[username]='$postdb[username]' WHERE $TB[uid]='$uid'");

	$postdb[totalspace]=$postdb[totalspace]*1024*1024;

	$postdb[groups]=implode(",",$postdb[groups]);
	if($postdb[groups]){
		$postdb[groups]=",$postdb[groups],";
	}
	$db->query("UPDATE {$pre}memberdata SET email='$postdb[email]',groupid='$postdb[groupid]',groups='$postdb[groups]',moneycard='$postdb[moneycard]',totalspace='$postdb[totalspace]',oltime='$postdb[oltime]',sex='$postdb[sex]',icon='$postdb[icon]',introduce='$postdb[introduce]',oicq='$postdb[oicq]',msn='$postdb[msn]',hits='$postdb[hits]',yz='$postdb[yz]',config='$_config',homepage='$postdb[homepage]',email_yz='$email_yz',mob_yz='$mob_yz',idcard_yz='$idcard_yz',address='$postdb[address]',postalcode='$postdb[postalcode]',telephone='$postdb[telephone]',mobphone='$postdb[mobphone]',idcard='$postdb[idcard]',truename='$postdb[truename]' WHERE uid='$uid' ");
	
	$rsdb[money]=get_money($rsdb[uid]);

	add_user( $uid , ($postdb[money]-$rsdb[money]) );

	

	//自定义用户字段
	Reg_memberdata_field($uid,$postdb);

	if($postdb[newpassword]&&defined("UC_CONNECT")){
		uc_user_edit($rsdb[username], $rsdb[password], $postdb[newpassword], $postdb[email], $ignoreoldpw = 1, $questionid = '', $answer = '');
	}

	jump("修改成功","index.php?lfj=member&job=editmember&uid=$uid");
	
}
elseif($action=="delete"&&$Apower[member_list])
{
	$rsdb=$db->get_one("SELECT D.* FROM {$pre}members M LEFT JOIN {$pre}memberdata D ON M.$TB[uid]=D.uid WHERE M.$TB[uid]='$uid' ");
	if($rsdb[groupid]==3&&$userdb[groupid]!=3){
		showmsg("你无权删除超级管理员");
	}
	if($uid==$lfjdb[uid]){
		showmsg("你不能删除自己");
	}
	$db->query("DELETE FROM {$pre}members WHERE $TB[uid]='$uid' ");
	$db->query("DELETE FROM {$pre}memberdata WHERE uid='$uid' ");
	$db->query("DELETE FROM {$pre}memberdata_1 WHERE uid='$uid' ");
	if( $webdb[passport_type] )
	{
		$db->query("DELETE FROM $TB[table] WHERE $TB[uid]='$uid' ");
	}
	if(defined("UC_CONNECT")){
		$db_uc->get_one("DELETE FROM ".UC_DBTABLEPRE."members WHERE uid='$uid'");
		//uc_user_delete($uid);
	}
	del_company($uid);

	jump("删除成功","index.php?lfj=member&job=list");
}
elseif($job=="pwd"&&$Apower[member_list])
{
	require(dirname(__FILE__)."/"."head.php");
	//require("template/member/menu.htm");
	require(dirname(__FILE__)."/"."template/member/pwd.htm");
	require(dirname(__FILE__)."/"."foot.php");
}
elseif($action=="pwd"&&$Apower[member_list])
{
	if($postdb[passwd]!=$postdb[passwd2]){
		showmsg("两次输入密码不一样");
	}
	$PWD=pwd_md5($postdb[passwd]);
	$db->query("UPDATE $TB[table] SET $TB[password]='$PWD' WHERE $TB[uid]='$userdb[uid]' ");
	jump("密码修改成功,请重新登录","$FROMURL");
}
elseif($action=="yz"&&$Apower[member_list])
{
	if($T=='yz'){
		$yz=1;
	}else{
		$yz=0;
	}
	foreach( $uid_db AS $key=>$uid){
		if($yz==0){
			$rs=$db->get_one("SELECT * FROM {$pre}memberdata WHERE uid='$uid'");
			if($rs[groupid]==3||$rs[groupid]==4){
				showmsg("你不可以设置管理员为未审核");
			}
		}
		$db->query("UPDATE {$pre}memberdata SET yz=$yz WHERE uid='$uid' ");
	}
	jump('处理完毕',$FROMURL,0);
}
?>