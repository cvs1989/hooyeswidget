<?php
$wind_in='rg';
require_once(dirname(__FILE__)."/".'global_pw.php');

include_once(D_P."data/bbscache/dbreg.php");
include_once(D_P.'data/bbscache/customfield.php');
@include_once(D_P.'data/bbscache/inv_config.php');
list($rg_regminname,$rg_regmaxname) = explode("\t",$rg_namelen);
list($rg_regminpwd,$rg_regmaxpwd) = explode("\t",$rg_pwdlen);


if(GetGP('vip')=='activating'){
	InitGP(array('r_uid','pwd'),'G');
	$r_uid=(int)$r_uid;
	$u_db=$db->get_one("SELECT yz FROM pw_members WHERE uid='$r_uid'");
	if($u_db){
		if($pwd==$u_db['yz']){//����ʱ�����֤
			$db->update("UPDATE pw_members SET yz=1 WHERE uid='$r_uid'");
			Showmsg('reg_jihuo_success');
		} else{
			Showmsg('reg_jihuo_fail');
		}
	} else{
		Showmsg('reg_jihuo_fail');
	}
}
if($db_pptifopen && $db_ppttype=='client'){
	Showmsg('passport_register');
}
list($reggd) = explode("\t",$db_gdcheck);
list($regq) = explode("\t",$db_qcheck);

if(GetGP('action','P') == 'regnameck'){
	InitGP('username','P');
	if(strlen($username) > $rg_regmaxname || strlen($username) < $rg_regminname){
		echo"<script language=\"JavaScript1.2\">parent.retmsg('0');</script>";
		exit;
	}
	$S_key = array("\\",'&',' ',"'",'"','/','*',',','<','>',"\r","\t","\n",'#','%');
	foreach($S_key as $value){
		if(strpos($username,$value) !== false){
			echo"<script language=\"JavaScript1.2\">parent.retmsg('1');</script>";
			exit;
		}
	}
	if(!$rg_rglower){
		for($asc=65;$asc<=90;$asc++){
			if(strpos($username,chr($asc)) !== false){
				echo"<script language=\"JavaScript1.2\">parent.retmsg('2');</script>";
				exit;
			}
		}
	}
	$rg_banname=explode(',',$rg_banname);
	foreach($rg_banname as $value){
		if(strpos($username,$value)!==false){
			echo"<script language=\"JavaScript1.2\">parent.retmsg('1');</script>";
			exit;
		}
	}
	$rt = $db->get_one("SELECT uid FROM pw_members WHERE username='$username'");
	if($rt){
		echo"<script language=\"JavaScript1.2\">parent.retmsg('3');</script>";
		exit;
	} else {
		echo"<script language=\"JavaScript1.2\">parent.retmsg('4');</script>";
		exit;
	}
}
if($rg_allowregister==0){
	Showmsg($rg_whyregclose);
}
if($rg_allowsameip && file_exists(D_P.'data/bbscache/ip_cache.php')){
	$ipdata=readover(D_P.'data/bbscache/ip_cache.php');
	$pretime=(int) substr($ipdata,13,10);
	if($timestamp-$pretime>$rg_allowsameip*3600){
		P_unlink(D_P.'data/bbscache/ip_cache.php');
	} elseif(strpos($ipdata,"<$onlineip>")!==false){
		Showmsg('reg_limit');
	}
}
$forward = $db_pptifopen ? GetGP('forward') : '';
$groupid!='guest' && Showmsg('reg_repeat');

InitGP(array('invcode'));

if(!$_POST['step'] && !$rg_reg){
	require_once(R_P.'require/header.php');
	require_once(PrintEot('register'));footer();
} elseif($_POST['step']==1 || $rg_reg=='1' && $_POST['step']!=2){
	!$rg_timestart && $rg_timestart=1960;
	!$rg_timeend && $rg_timeend=2000;
	$img=@opendir("$imgdir/face");
	while($imagearray=@readdir($img)){
		if($imagearray!="." && $imagearray!=".." && $imagearray!="" && $imagearray!="none.gif"){
			$imgselect.="<option value='$imagearray'>$imagearray</option>";
		}
	}
	@closedir($img);
	 
	$custominfo = unserialize($db_union[7]);

	require(html("head",$head_tpl));
	require_once(html('reg_pw'));
	require(html("foot",$foot_tpl));

} elseif($_POST['step']==2){

	//P8�Զ����ֶ�
	require_once(PHP168_PATH."php168/mysql_config.php");
	require_once(PHP168_PATH."php168/config.php");
	require_once("regfield.php");
	ck_regpost($postdb);
	
	$reggd && GdConfirm($_POST['gdcode']);
	//$regq && Qcheck($_POST['qanswer'],$_POST['qkey']);
	InitGP(array('regreason','regname','regpwd','regicon','regreason','reghomepage','regfrom','regintroduce','regsign','regemail','regsex','regbirthyear','regbirthmonth','regbirthday','regoicq','customdata','regifemail'),'P',1);
	InitGP(array('question','customquest','answer'),'P');
	$regsex        = (int)$regsex;
	$regifemail    = (int)$regifemail;
	$regemailtoall = (int)$regemailtoall;

	if($inv_open=='1'){
		if(empty($invcode)){
			Showmsg('invcode_empty');
		} else{
			$inv_days*=86400;
			$inv=$db->get_one("SELECT id FROM pw_invitecode WHERE invcode='$invcode' AND ifused<'2' AND $timestamp-createtime<'$inv_days'");
			!$inv && Showmsg('illegal_invcode');
		}
	}

	if($rg_ifcheck && !$regreason){
		Showmsg('reg_reason');
	}
	if(strlen($regname)>$rg_regmaxname || strlen($regname)<$rg_regminname){
		Showmsg('reg_username_limit');
	}
	if(strlen($regpwd)<$rg_regminpwd) {
		Showmsg('reg_password_minlimit');
	} elseif($rg_regmaxpwd && strlen($regpwd)>$rg_regmaxpwd){
		Showmsg('reg_password_maxlimit');
	} elseif ($rg_npdifferf && $regpwd==$regname) {
		Showmsg('reg_nameuptopwd');
	}
	$S_key=array("\\",'&',' ',"'",'"','/','*',',','<','>',"\r","\t","\n",'#','%');
	foreach($S_key as $value){
		if(strpos($regname,$value)!==false){
			Showmsg('illegal_username');
		}
		if($regpwd!=$regname && strpos($regpwd,$value)!==false){
			Showmsg('illegal_password');
		}
	}
	$safecv='';
	if($db_ifsafecv){
		require_once(R_P.'require/checkpass.php');
		$safecv=questcode($question,$customquest,$answer);
	}
	if(!$rg_rglower){
		for($asc=65;$asc<=90;$asc++){
			if (strpos($regname,chr($asc))!==false){
				Showmsg('username_limit');
			}
		}
	}
	if(strpos($regicon,'..')!==false){
		Showmsg('undefined_action');
	}
	$regicon .= '|1';
	$regpwd = md5($regpwd);
	if(strlen($regintroduce)>100) Showmsg('introduce_limit');

	require_once(D_P.'data/bbscache/level.php');
	@asort($lneed);
	$rg_memberid = key($lneed);
	if($regsign != ""){
		if (file_exists(D_P."data/groupdb/group_$rg_memberid.php")) {
			require_once Pcv(D_P."data/groupdb/group_$rg_memberid.php");
		}else{
			$gp_signnum = 50;
		}
		if(strlen($regsign)>$gp_signnum){
			Showmsg('sign_limit');
		}
		require_once(R_P.'require/bbscode.php');
		$lxsign=convert($regsign,$db_windpic,2);
		if($lxsign==$regsign){
			$rg_ifconvert=1;
		} else{
			$rg_ifconvert=2;
		}
	} else{
		$rg_ifconvert=1;
	}
	if(@include_once(D_P."data/bbscache/wordsfb.php")){
		$wordsfb = $wordsfb + $replace;
		foreach($wordsfb as $key => $value){
			$banword = (string) stripslashes($key);
			if(strpos($regsign,$banword)!==false || strpos($regintroduce,$banword)!==false){
				Showmsg('sign_wordsfb');
			}
		}
	}
	if(strpos($regpwd,"\r")!==false || strpos($regpwd,"\t")!==false || strpos($regpwd,"|")!==false || strpos($regpwd,"<")!==false || strpos($regpwd,">")!==false){
		Showmsg('illegal_password');
	}
	if(empty($regemail) || !ereg("^[-a-zA-Z0-9_\.]+\@([0-9A-Za-z][0-9A-Za-z-]+\.)+[A-Za-z]{2,5}$",$regemail)){
		Showmsg('illegal_email');
	} else{
		if($rg_email){
			$e_check=0;
			$e_limit=explode(',',$rg_email);
			foreach($e_limit as $key=>$val){
				if(strpos($regemail,"@".$val)!==false){
					$e_check=1;
					break;
				}
			}
			$e_check==0 && Showmsg('email_check');
		}
	}
	$rs = $db->get_one("SELECT COUNT(*) AS count FROM pw_members WHERE username='$regname'");
	if($rs['count']>0){
		Showmsg('username_same');
	}

	$regname=='guest' && Showmsg('illegal_username');
	$rg_banname=explode(',',$rg_banname);
	foreach($rg_banname as $value){
		if(strpos($regname,$value)!==false){
			Showmsg('illegal_username');
		}
	}
	$regsex=$regsex ? $regsex : "0";
	$rg_birth = (!$regbirthyear || !$regbirthmonth || !$regbirthday) ? '0000-00-00' : $regbirthyear."-".$regbirthmonth."-".$regbirthday;
	if($regoicq && !ereg("^[0-9]{5,}$",$regoicq)){
		Showmsg('illegal_OICQ');
	}
	if($rg_ifcheck=='1'){
		$rg_groupid='7';//��̨�����Ƿ���Ҫ��֤
	} else{
		$rg_groupid='-1';
	}

	$rg_yz=$rg_emailcheck==1 ? $timestamp : 1;
	$fieldadd='';
	if($customfield){
		foreach($customfield as $key=>$val){
			$field="field_".(int)$val['id'];
			$$field=GetGP($field,'P');
			if($val['required'] && !$$field){
				Showmsg('field_empty');
			}
			if($val['maxlen'] && strlen($$field) > $val['maxlen']){
				Showmsg('field_lenlimit');
			}
			$$field = Char_cv($$field);
			$fieldadd .= $fieldadd ? ",$field='{$$field}'" : "$field='{$$field}'";
		}
	}
	$db->update("INSERT INTO pw_members (username,password,safecv,email,publicmail,groupid,memberid,icon,gender,regdate,signature,introduce,oicq,icq,site,location,bday,receivemail,yz,signchange) VALUES ('$regname','$regpwd','$safecv','$regemail','$regemailtoall','$rg_groupid','$rg_memberid','$regicon','$regsex','$timestamp','$regsign','$regintroduce','$regoicq','','$reghomepage','$regfrom','$rg_birth','$regifemail','$rg_yz','$rg_ifconvert')");
	$winduid=$db->insert_id();
	$db->update("INSERT INTO pw_memberdata (uid,postnum,rvrc,money,lastvisit,thisvisit,onlineip) VALUES ('$winduid','0','$rg_regrvrc','$rg_regmoney','$timestamp','$timestamp','$onlineip')");
	if($rg_ifcheck){
		$db->update("INSERT INTO pw_memberinfo(uid,regreason) VALUES ('$winduid','$regreason')");
	}
	$db_union=explode("\t",stripslashes($db_union));
	$custominfo=unserialize($db_union[7]);
	if($custominfo && $customdata){
		foreach($customdata as $key=>$val){
			$key=Char_cv($key);
			$val=Char_cv($val);
			$customdata[stripslashes($key)]=stripslashes($val);
		}
		$customdata=addslashes(serialize($customdata));
		$db->pw_update(
			"SELECT uid FROM pw_memberinfo WHERE uid='$winduid'",
			"UPDATE pw_memberinfo SET customdata='$customdata' WHERE uid='$winduid'",
			"INSERT INTO pw_memberinfo SET uid='$winduid',customdata='$customdata'"
		);
	}
	$db->update("UPDATE pw_bbsinfo SET newmember='$regname',totalmember=totalmember+1 WHERE id='1'");
	if($fieldadd){
		$db->pw_update(
			"SELECT uid FROM pw_memberinfo WHERE uid='$winduid'",
			"UPDATE pw_memberinfo SET $fieldadd WHERE uid='$winduid'",
			"INSERT INTO pw_memberinfo SET uid='$winduid',$fieldadd"
		);
	}
	if($inv_open=='1'){
		$db->update("UPDATE pw_invitecode SET receiver='$regname',usetime='$timestamp',ifused='2' WHERE id='$inv[id]'");
	}
	$windid=$regname;
	$windpwd=$regpwd;
	//$iptime=$timestamp+86400;
	//Cookie("ifregip",$onlineip,$iptime);
	if($rg_allowsameip){
		if(file_exists(D_P.'data/bbscache/ip_cache.php')){
			writeover(D_P.'data/bbscache/ip_cache.php',"<$onlineip>","ab");
		}else{
			writeover(D_P.'data/bbscache/ip_cache.php',"<?php die;?><$timestamp>\n<$onlineip>");
		}
	}
	//addonlinefile();
	if(GetCookie('userads') && $db_ads=='2'){
		list($u,$a)=explode("\t",GetCookie('userads'));
		if(is_numeric($u) || ($a && strlen($a)<16)){
			require_once(R_P.'require/userads.php');
		}
	}
	if($rg_yz == 1){
		Cookie("winduser",StrCode($winduid."\t".PwdCode($windpwd)."\t".$safecv));
		Cookie("ck_info",$db_ckpath."\t".$db_ckdomain);
		Cookie('lastvisit','',0);//��$lastvist����Խ���ע��Ļ�Ա������յ��û�Ա��
	}
	//���Ͷ���Ϣ
	if($rg_regsendmsg){
		//require_once(R_P.'require/msg.php');
		//$rg_welcomemsg = str_replace('$rg_name',$regname,$rg_welcomemsg);
		//$messageinfo   = array($windid,'0',"Welcome To[{$db_bbsname}]!",$timestamp,$rg_welcomemsg,'N');
		//writenewmsg($messageinfo,1);
	}

	//�����ʼ�

	if($rg_emailcheck){
		//require_once(R_P.'require/sendemail.php');
		//$sendinfo = sendemail($regemail,'email_check_subject','email_check_content','email_additional');
		//if ($sendinfo===true) {
		//	Showmsg('reg_email_success');
		//} else{
		//	Showmsg(is_string($sendinfo) ? $sendinfo : 'reg_email_fail');
		//}
	} elseif($rg_regsendemail){
		//require_once(R_P.'require/sendemail.php');
		//sendemail($regemail,'email_welcome_subject','email_welcome_content','email_additional');
	}
	//���ͽ���

	//passport
	if($db_pptifopen && $db_ppttype == 'server' && ($db_ppturls || $forward)){
		$action='login';
		$jumpurl=$forward ? $forward : $db_ppturls;
		require_once(R_P.'require/passport_server.php');
	}
	//passport


	//ע����վ
	$rs=array(
		"uid"=>$winduid,
		"username"=>$regname,
		"sex"=>$regsex,
		"email"=>$regemail,
		"bday"=>$rg_birth,
		"icon"=>'',
		"introduce"=>$regintroduce
	);
	Reg_memberdata($rs);
	Reg_memberdata_field($winduid,$postdb);

	refreshto("../$db_bfn",'reg_success');
}


/**
*P8ģ����غ���
**/
function html($html,$tpl=''){
	global $STYLE;
	if($tpl&&strstr($tpl,PHP168_PATH)&&file_exists($tpl))
	{
		return $tpl;
	}
	elseif($tpl&&file_exists(PHP168_PATH.$tpl))
	{
		return PHP168_PATH.$tpl;
	}
	elseif(file_exists(PHP168_PATH."template/".$STYLE."/".$html.".htm"))
	{
		return PHP168_PATH."template/".$STYLE."/".$html.".htm";
	}
	else
	{
		return PHP168_PATH."template/default/".$html.".htm";
	}
}

function Showmsg($msg_info,$dejump=0){

	require_once GetLang('msg');
	$lang[$msg_info] && $msg_info=$lang[$msg_info];
	showerr($msg_info);
	exit;

}


/**
*����ҳ�溯��
**/
function showerr($msg,$type=''){
	global $webdb,$showerrMsg;
	$showerrMsg=$msg;
	if($type==1){
		$msg=str_replace("'","\'",$msg);
		echo "<SCRIPT LANGUAGE=\"JavaScript\">
		<!--
		alert('$msg');
		history.back(-1);
		//-->
		</SCRIPT>";
	}else{
		require(PHP168_PATH."template/default/showerr.htm");
	}
	exit;
}

/**
*���ݱ��ֶ���Ϣ������
**/
function table_field($table,$field=''){
	global $db;
	$query=$db->query(" SELECT * FROM $table limit 1");
	$num=mysql_num_fields($query);
	for($i=0;$i<$num;$i++){
		$f_db=mysql_fetch_field($query,$i);
		$showdb[]=$f_db->name;
	}
	if($field){
		if(in_array($field,$showdb) ){
			return 1;
		}else{
			return 0;
		}
	}else{
		return $showdb;
	}
}

function GetGP($key,$method=null){
	//Copyright (c) 2003-06 PHPWind
	if ($method=='G' || $method!='P' && isset($_GET[$key])) {
		return $_GET[$key];
	}
	return $_POST[$key];
}
function InitGP($keys,$method=null,$cv=null){
	//Copyright (c) 2003-06 PHPWind
	!is_array($keys) && $keys = array($keys);
	foreach ($keys as $value) {
		$GLOBALS[$value] = NULL;
		if ($method!='P' && isset($_GET[$value])) {
			$GLOBALS[$value] = $_GET[$value];
		} elseif ($method!='G' && isset($_POST[$value])) {
			$GLOBALS[$value] = $_POST[$value];
		}
		isset($GLOBALS[$value]) && !empty($cv) && $GLOBALS[$value] = value_cv($GLOBALS[$value],$cv);
	}
}

function value_cv($value,$cv=null){
	if (empty($cv)) {
		return $value;
	} elseif ($cv=='int') {
		return (int)$value;
	} elseif ($cv=='array') {
		return is_array($value) ? $value : '';
	}
	return Char_cv($value);
}

function read_file($filename,$method="rb"){
	if($handle=@fopen($filename,$method)){
		@flock($handle,LOCK_SH);
		$filedata=@fread($handle,@filesize($filename));
		@fclose($handle);
	}
	return $filedata;
}

?>