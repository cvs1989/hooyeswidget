<?php
/**
*
*  Copyright (c) 2003-06  PHPWind.net. All rights reserved.
*  Support : http://www.phpwind.net
*  This software is the proprietary information of PHPWind.com.
*
*/


$wind_in='rg';
require_once(dirname(__FILE__)."/".'global_pw.php');

include_once(D_P."data/bbscache/dbreg.php");
include_once(D_P.'data/bbscache/customfield.php');
@include_once(D_P.'data/bbscache/inv_config.php');

if(defined("P_W")){
	$_fromurl=urlencode($_fromurl);
	echo "<META HTTP-EQUIV=REFRESH CONTENT='0;URL=reg_pw7.php?_fromurl=$_fromurl'>";
	exit;
}
elseif($rg_namelen){
	$_fromurl=urlencode($_fromurl);
	echo "<META HTTP-EQUIV=REFRESH CONTENT='0;URL=reg_pw63.php?_fromurl=$_fromurl'>";
	exit;
}

if($vip=='activating'){
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
if($passport_ifopen && $passport_type=='client'){
	Showmsg('passport_register');
}
list($reggd) = explode("\t",$db_gdcheck);

if($action == 'regnameck'){
	include_once(D_P."data/bbscache/dbreg.php");
	if(strlen($username) > $rg_regmaxname || strlen($username) < $rg_regminname){
		echo"<script language=\"JavaScript1.2\">parent.retmsg('0');</script>";
		exit;
	}
	$S_key = array("\\",'&',' ',"'",'"','/','*',',','<','>',"\r","\t","\n",'#');
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
if($rg_allowsameip){
	if(file_exists(D_P.'data/bbscache/ip_cache.php')){
		$ipdata=readover(D_P.'data/bbscache/ip_cache.php');
		$pretime=(int) substr($ipdata,9,10);
		if($timestamp-$pretime>$rg_allowsameip*3600){
			P_unlink(D_P.'data/bbscache/ip_cache.php');
		}elseif(strpos($ipdata,"<$onlineip>")!==false){
			Showmsg('reg_limit');
		}
	}
}
!$passport_ifopen && $groupid!='guest' && Showmsg('reg_repeat');
list($rg_question,$rg_answer,$rg_variable) = explode("\t",$rg_unreg);

if($_POST['step']!=2){
	$imgpatherror=0;
	!$rg_timestart && $rg_timestart=1960;
	!$rg_timeend && $rg_timeend=2000;
	if(ereg("^http",$picpath)){
		$picpath=basename($picpath);//�������ͼƬ·������Ϊ�����������ϵ�ͼƬ,����ر���ͼƬĿ¼ͬ��,��������ڳ���bug ֮��
		if(!file_exists($picpath)){
			$imgpatherror=1;
		}
	}
	$img=@opendir("$imgdir/face");
	while ($imagearray=@readdir($img)){
		if ($imagearray!="." && $imagearray!=".." && $imagearray!="" && $imagearray!="none.gif"){
			$imgselect.="<option value='$imagearray'>$imagearray</option>";
		}
	}
	@closedir($img);

	$imgpath="$webdb[passport_url]/$imgpath";

	require(html("head",$head_tpl));
	require_once(html('reg_pw'));
	require(html("foot",$foot_tpl));
	
} elseif($_POST['step']==2){
	
	//P8�Զ����ֶ�
	require_once(PHP168_PATH."php168/mysql_config.php");
	require_once(PHP168_PATH."php168/config.php");
	require_once("regfield.php");
	ck_regpost($postdb);

	$reggd && GdConfirm($gdcode);

	if($inv_open=='1'){
		if(empty($invcode)){
			Showmsg('invcode_empty');
		}else{
			$inv_days*=86400;
			$inv=$db->get_one("SELECT id FROM pw_invitecode WHERE invcode='$invcode' AND ifused<'2' AND $timestamp-createtime<'$inv_days'");
			!$inv && Showmsg('illegal_invcode');
		}
	}
	if($rg_question && $rg_variable && $$rg_variable != $rg_answer){
		Showmsg('reg_refuse');
	}
	if($rg_ifcheck && !$regreason){
		//Showmsg('reg_reason');
	}
	if(strlen($regname)>$rg_regmaxname || strlen($regname)<$rg_regminname){
		Showmsg('reg_username_limit');
	}
	$S_key=array("\\",'&',' ',"'",'"','/','*',',','<','>',"\r","\t","\n",'#');
	foreach($S_key as $value){
		if(strpos($regname,$value)!==false){ 
			Showmsg('illegal_username'); 
		}
		if(strpos($regpwd,$value)!==false){ 
			Showmsg('illegal_password'); 
		}
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
	$regicon=Char_cv($regicon);

	$rg_name      = Char_cv($regname);
	$regpwd       = Char_cv($regpwd);
	$rg_pwd       = md5($regpwd);
	$regreason    = Char_cv($regreason);
	$rg_homepage  = Char_cv($reghomepage);
	$rg_from	  = Char_cv($regfrom);
	$rg_introduce = Char_cv($regintroduce);
	$rg_sign	  =	Char_cv($regsign);
	if(strlen($rg_introduce)>200) Showmsg('introduce_limit');
	if($rg_sign != ""){
		if(strlen($rg_sign)>50){
			$gp_signnum=50;
			Showmsg('sign_limit');
		}
		require_once(R_P.'require/bbscode.php');
		$lxsign=convert($rg_sign,$db_windpic,2);
		if($lxsign==$rg_sign){
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
			if(strpos($rg_sign,$banword)!==false || strpos($rg_introduce,$banword)!==false){
				Showmsg('post_wordsfb');
			}
		}
	}
	if(strpos($regpwd,"\r")!==false || strpos($regpwd,"\t")!==false || strpos($regpwd,"|")!==false || strpos($regpwd,"<")!==false || strpos($regpwd,">")!==false) {
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
		$rg_email=$regemail;
	}
	$rs = $db->get_one("SELECT COUNT(*) AS count FROM pw_members WHERE username='$rg_name'");
	if($rs['count']>0){
		Showmsg('username_same');
	}

	$rg_name=='guest' && Showmsg('illegal_username');
	$rg_banname=explode(',',$rg_banname);
	foreach($rg_banname as $value){
		if(strpos($rg_name,$value)!==false){
			Showmsg('illegal_username');
		}
	}
	$rg_sex=$regsex ? $regsex : "0";
	$rg_birth = (!$regbirthyear || !$regbirthmonth || !$regbirthday) ? '0000-00-00' : $regbirthyear."-".$regbirthmonth."-".$regbirthday;
	$rg_oicq = $regoicq ? $regoicq : '';

	if($regoicq && !ereg("^[0-9]{5,}$",$regoicq)){
		Showmsg('illegal_OICQ');
	}
	if($regkf && !ereg("^[0-9a-zA-Z\-]{5,22}$",$regkf)){
		Showmsg('illegal_53kf');
	}
	if($rg_ifcheck=='1'){
		$rg_groupid='7';//��̨�����Ƿ���Ҫ��֤
	} else{
		$rg_groupid='-1';
	}
	require_once(D_P.'data/bbscache/level.php');
	@asort($lneed);
	$rg_memberid=key($lneed);

	$rg_yz=$rg_emailcheck==1 ? $timestamp : 1;
	$rg_ifemail    = (int)$regifemail;
	$rg_emailtoall = (int)$regemailtoall;

	$fieldadd='';
	if($customfield){
		foreach($customfield as $key=>$val){
			$field="field_".(int)$val['id'];
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
	if( !table_field("pw_members",'kf') )
	{
		$db->query("ALTER TABLE `pw_members` ADD `kf` VARCHAR( 22 ) NOT NULL");
	}
	$db->update("INSERT INTO pw_members (username, password, email,publicmail,groupid,memberid,icon,gender,regdate,signature,introduce,oicq,icq,kf,site,location,bday,receivemail,yz,signchange) VALUES ('$rg_name','$rg_pwd','$rg_email','$rg_emailtoall','$rg_groupid','$rg_memberid','$regicon','$rg_sex','$timestamp','$rg_sign','$rg_introduce','$rg_oicq','','$regkf','$rg_homepage','$rg_from','$rg_birth','$rg_ifemail','$rg_yz','$rg_ifconvert')");
	$winduid=$db->insert_id();
	$db->update("INSERT INTO pw_memberdata (uid,postnum,rvrc,money,lastvisit,thisvisit,onlineip) VALUES ('$winduid','0','$rg_regrvrc','$rg_regmoney','$timestamp','$timestamp','$onlineip')");
	if($rg_ifcheck){
		$db->update("INSERT INTO pw_memberinfo(uid,regreason) VALUES ('$winduid','$regreason')");
	}
	$db->update("UPDATE pw_bbsinfo SET newmember='$rg_name',totalmember=totalmember+1 WHERE id='1'");
	if($fieldadd){
		$db->pw_update(
			"SELECT uid FROM pw_memberinfo WHERE uid='$winduid'",
			"UPDATE pw_memberinfo SET $fieldadd WHERE uid='$winduid'",
			"INSERT INTO pw_memberinfo SET uid='$winduid',$fieldadd"
		);
	}
	if($inv_open=='1'){
		$db->update("UPDATE pw_invitecode SET receiver='$rg_name',usetime='$timestamp',ifused='2' WHERE id='$inv[id]'");
	}
	$windid=$rg_name;
	$windpwd=$rg_pwd;
	//$iptime=$timestamp+86400;
	//Cookie("ifregip",$onlineip,$iptime);
	if($rg_allowsameip){
		if(file_exists(D_P.'data/bbscache/ip_cache.php')){
			writeover(D_P.'data/bbscache/ip_cache.php',"<$onlineip>","ab");
		}else{
			writeover(D_P.'data/bbscache/ip_cache.php',"<?die;?><$timestamp><$onlineip>");
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
		Cookie("winduser",StrCode($winduid."\t".PwdCode($windpwd)));
		Cookie("ck_info",$db_ckpath."\t".$db_ckdomain);
		Cookie('lastvisit','',0);//��$lastvist����Խ���ע��Ļ�Ա������յ��û�Ա��
	}
	//���Ͷ���Ϣ
	if($rg_regsendmsg){
		//require_once(R_P.'require/msg.php');
		//$rg_welcomemsg = str_replace('$rg_name',$rg_name,$rg_welcomemsg);
		//$messageinfo   = array($windid,'0',"Welcome To[{$db_bbsname}]!",$timestamp,$rg_welcomemsg,0);
		//writenewmsg($messageinfo,1);
	}

	//�����ʼ�

	if($rg_emailcheck){
		//require_once(R_P.'require/sendemail.php');
		//if(sendemail($rg_email,'email_check_subject','email_check_content','email_additional')){
		//	Showmsg('reg_email_success');
		//} else{
		//	Showmsg('reg_email_fail');
		//}
	} elseif($rg_regsendemail){
		//require_once(R_P.'require/sendemail.php');
		//sendemail($rg_email,'email_welcome_subject','email_welcome_content','email_additional');
	}
	//���ͽ���

	//passport
	if($passport_ifopen && $passport_type == 'server'){
		$action='login';
		$cktime='F';
		require_once(R_P.'require/passport_server.php');
	}
	//passport
	
	//echo $_fromurl;exit;

	//ע����վ
	$rs=array(
		"uid"=>$winduid,
		"username"=>$rg_name,
		"sex"=>$rg_sex,
		"email"=>$rg_email,
		"bday"=>$rg_birth,
		"icon"=>'',
		"introduce"=>$rg_introduce
	);
	Reg_memberdata($rs);
	Reg_memberdata_field($winduid,$postdb);


	if($forward){
		refreshto($forward,'reg_success');exit;
	}
	refreshto("../",'reg_success');
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
?>