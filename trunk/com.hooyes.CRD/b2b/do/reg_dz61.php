<?php

/*
	[Discuz!] (C)2001-2007 Comsenz Inc.
	This is NOT a freeware, use is subject to license terms

	$Id: register.php 13280 2008-04-03 09:52:38Z cnteacher $
*/

//新添加
error_reporting(0);
$_GET['_fromurl'] && $_fromurl=$_GET['_fromurl'];
define("PHP168_PATH",dirname(__FILE__).'/../');
require_once(PHP168_PATH."php168/config.php");
$STYLE=$webdb[style]=$webdb[style]?$webdb[style]:'default';

define('CURSCRIPT', 'register');
define('NOROBOT', TRUE);

//require_once './include/common.inc.php';
require_once PHP168_PATH."$webdb[passport_path]/".'./include/common.inc.php';	//修改过

require_once DISCUZ_ROOT.'./forumdata/cache/cache_profilefields.php';

$discuz_action = 5;

if($discuz_uid) {
	dz_showmessage('login_succeed', $indexname);
} elseif (!$regstatus) {
	dz_showmessage('register_disable');
}

$inviteconfig = array();
$query = $db->query("SELECT * FROM {$tablepre}settings WHERE variable IN ('bbrules', 'bbrulestxt', 'welcomemsg', 'welcomemsgtitle', 'welcomemsgtxt', 'inviteconfig')");
while($setting = $db->fetch_array($query)) {
	$$setting['variable'] = $setting['value'];
}

$invitecode = $regstatus > 1 && $invitecode ? dhtmlspecialchars($invitecode) : '';
if($regstatus > 1) {
	$inviterewardcredit = $inviteaddcredit = $invitedaddcredit = '';
	@extract(unserialize($inviteconfig));
}


$groupinfo = $db->fetch_first("SELECT groupid, allownickname, allowcstatus, allowcusbbcode, allowsigbbcode, allowsigimgcode, maxsigsize FROM {$tablepre}usergroups WHERE ".($regverify ? "groupid='8'" : "creditshigher<=".intval($initcredits)." AND ".intval($initcredits)."<creditslower LIMIT 1"));

$seccodecheck = $seccodestatus & 1;
$secqaacheck = $secqaa['status'][1];

$fromuid = !empty($_DCOOKIE['promotion']) && $creditspolicy['promotion_register'] ? intval($_DCOOKIE['promotion']) : 0;

$action = isset($action) ? $action : '';
$username = isset($username) ? $username : '';

if(!submitcheck('regsubmit', 0, $seccodecheck, $secqaacheck)) {

	$referer = isset($referer) ? dhtmlspecialchars($referer) : dreferer();

	if($bbrules && !submitcheck('rulesubmit')) {

		$bbrulestxt = nl2br("\n$bbrulestxt\n\n");
		if($action == 'activation') {
			$auth = dhtmlspecialchars($auth);
		}

	} else {

		if($action == 'activation') {
			$auth = explode("\t", authcode($auth, 'DECODE'));
			if(FORMHASH != $auth[1]) {
				dz_showmessage('register_activation_invalid', 'logging.php?action=login');
			}
			$username = $auth[0];
			$activationauth = authcode("$auth[0]\t".FORMHASH, 'ENCODE');
		}

		$accessexp = '/('.str_replace("\r\n", '|', preg_quote($accessemail, '/')).')$/i';
		$censorexp = '/('.str_replace("\r\n", '|', preg_quote($censoremail, '/')).')$/i';
		$accessemail = str_replace("\r\n", '/', $accessemail);
		$censoremail = str_replace("\r\n", '/', $censoremail);
		$advdisplay = $advcheck = '';
		if($regadvance || $action == 'activation' && $regstatus == 1 && empty($_DCACHE['fields_required'])) {
			$advcheck = 'checked="checked"';
		} else {
			$advdisplay = 'none';
		}
		$fromuser = !empty($fromuser) ? dhtmlspecialchars($fromuser) : '';

		$styleselect = $dayselect = '';
		$query = $db->query("SELECT styleid, name FROM {$tablepre}styles WHERE available='1'");
		while($styleinfo = $db->fetch_array($query)) {
			$styleselect .= '<option value="'.$styleinfo['styleid'].'">'.$styleinfo['name'].'</option>'."\n";
		}

		if($fromuid) {
			$query = $db->query("SELECT username FROM {$tablepre}members WHERE uid='$fromuid'");
			if($db->num_rows($query)) {
				$fromuser = dhtmlspecialchars($db->result($query, 0));
			} else {
				dsetcookie('promotion', '');
			}
		}

		for($num = 1; $num <= 31; $num++) {
			$dayselect .= '<option value="'.$num.'">'.$num.'</option>';
		}

		$dateformatlist = array();
		if(!empty($userdateformat) && ($count = count($userdateformat))) {
			for($num =1; $num <= $count; $num ++) {
				$dateformatlist[$num] = str_replace(array('n', 'j', 'y', 'Y'), array('mm', 'dd', 'yy', 'yyyy'), $userdateformat[$num-1]);
			}
		}

	}

	if($seccodecheck) {
		$seccode = random(6, 1);
	}
	if($secqaa['status'][1]) {
		$seccode = random(1, 1) * 1000000 + substr($seccode, -6);
	}

	$username = dhtmlspecialchars($username);

	$FORMHASH = FORMHASH;
	ob_start();
	require(html("head",$head_tpl));
	$content=ob_get_contents();
	$content=str_replace("hack=login&job=js","",$content);
	ob_end_clean();
	ob_start();
	echo $content;
	require_once(html('reg_dz'));
	require(html("foot",$foot_tpl));

} else {

	require_once DISCUZ_ROOT.'./uc_client/client.php';
	require_once DISCUZ_ROOT.'./include/discuzcode.func.php';

	//P8自定义字段
	require_once(PHP168_PATH."php168/mysql_config.php");
	require_once(PHP168_PATH."php168/config.php");
	require_once("regfield.php");
	ck_regpost($postdb);

	$activation = array();
	if(isset($activationauth)) {
		$activationauth = explode("\t", authcode($activationauth, 'DECODE'));
		if($activationauth[1] == FORMHASH && !($activation = daddslashes(uc_get_user($activationauth[0])))) {
			dz_showmessage('register_activation_invalid', 'logging.php?action=login');
		}
	}

	if(!$activation) {
		$username = addslashes(trim(stripslashes($username)));
		if(uc_get_user($username) && !$db->result_first("SELECT uid FROM {$tablepre}members WHERE username='$username'")) {
			dz_showmessage('register_activation_message', 'logging.php?action=login');
		}

		if($password != $password2) {
			dz_showmessage('profile_passwd_notmatch');
		}

		if(!$password || $password != addslashes($password)) {
			dz_showmessage('profile_passwd_illegal');
		}

		$email = trim($email);

	}

	$alipay = trim($alipay);

	$guestexp = '\xA1\xA1|\xAC\xA3|^Guest|^\xD3\xCE\xBF\xCD|\xB9\x43\xAB\xC8';

	$censorexp = '/^('.str_replace(array('\\*', "\r\n", ' '), array('.*', '|', ''), preg_quote(($censoruser = trim($censoruser)), '/')).')$/i';

	if($censoruser && (@preg_match($censorexp, $nickname) || @preg_match($censorexp, $cstatus))) {
		dz_showmessage('profile_nickname_cstatus_illegal');
	}

	if($alipay && !isemail($alipay)) {
		dz_showmessage('profile_alipay_illegal');
	}

	if($msn && !isemail($msn)) {
		dz_showmessage('profile_alipay_msn');
	}

	$fieldadd1 = $fieldadd2 = '';
	foreach(array_merge($_DCACHE['fields_required'], $_DCACHE['fields_optional']) as $field) {
		$field_key = 'field_'.$field['fieldid'];
		$field_val = ${'field_'.$field['fieldid'].'new'};
		if($field['required'] && trim($field_val) == '') {
			dz_showmessage('profile_required_info_invalid');
		} elseif($field['selective'] && $field_val != '' && !isset($field['choices'][$field_val])) {
			dz_showmessage('undefined_action', NULL, 'HALTED');
		} else {
			$fieldadd1 .= ", $field_key";
			$fieldadd2 .= ', \''.dhtmlspecialchars($field_val).'\'';
		}
	}

	if($regverify == 2 && !trim($regmessage)) {
		dz_showmessage('profile_required_info_invalid');
	}

	if($groupinfo['maxsigsize']) {
		if(strlen($signature) > $groupinfo['maxsigsize']) {
			$maxsigsize = $groupinfo['maxsigsize'];
			dz_showmessage('profile_sig_toolong');
		}
	} else {
		$signature = '';
	}

	if($ipregctrl) {
		foreach(explode("\n", $ipregctrl) as $ctrlip) {
			if(preg_match("/^(".preg_quote(($ctrlip = trim($ctrlip)), '/').")/", $onlineip)) {
				$ctrlip = $ctrlip.'%';
				$regctrl = 72;
				break;
			} else {
				$ctrlip = $onlineip;
			}
		}
	} else {
		$ctrlip = $onlineip;
	}

	if($regstatus > 1) {
		if($regstatus == 2 && !$invitecode) {
			dz_showmessage('register_invite_notfound');
		} elseif($invitecode) {
			$groupinfo['groupid'] = $invitegroupid ? intval($invitegroupid) : $groupinfo['groupid'];
			$invite = $db->fetch_first("SELECT uid, invitecode, inviteip, expiration FROM {$tablepre}invites WHERE invitecode='$invitecode' AND status IN ('1', '3')");
			if(!$invite) {
				dz_showmessage('register_invite_error');
			} else {
				if($invite['inviteip'] == $onlineip) {
					dz_showmessage('register_invite_iperror');
				} elseif($invite['expiration'] < $timestamp) {
					dz_showmessage('register_invite_expiration');
				}
			}
		}
	}

	if($regctrl) {
		$query = $db->query("SELECT ip FROM {$tablepre}regips WHERE ip LIKE '$ctrlip' AND count='-1' AND dateline>$timestamp-'$regctrl'*3600 LIMIT 1");
		if($db->num_rows($query)) {
			dz_showmessage('register_ctrl', NULL, 'HALTED');
		}
	}

	if($regfloodctrl) {
		if($regattempts = $db->result_first("SELECT count FROM {$tablepre}regips WHERE ip='$onlineip' AND count>'0' AND dateline>'$timestamp'-86400")) {
			if($regattempts >= $regfloodctrl) {
				dz_showmessage('register_flood_ctrl', NULL, 'HALTED');
			} else {
				$db->query("UPDATE {$tablepre}regips SET count=count+1 WHERE ip='$onlineip' AND count>'0'");
			}
		} else {
			$db->query("INSERT INTO {$tablepre}regips (ip, count, dateline)
				VALUES ('$onlineip', '1', '$timestamp')");
		}
	}

	$secques = quescrypt($questionid, $answer);

	if(!$activation) {
		$uid = uc_user_register($username, $password, $email);
		if($uid <= 0) {
			if($uid == -1) {
				dz_showmessage('profile_username_illegal');
			} elseif($uid == -2) {
				dz_showmessage('profile_username_protect');
			} elseif($uid == -3) {
				dz_showmessage('profile_username_duplicate');
			} elseif($uid == -4) {
				dz_showmessage('profile_email_illegal');
			} elseif($uid == -5) {
				dz_showmessage('profile_email_domain_illegal');
			} elseif($uid == -6) {
				dz_showmessage('profile_email_duplicate');
			} else {
				dz_showmessage('undefined_action', NULL, 'HALTED');
			}
		}
	} else {
		list($uid, $username, $email) = $activation;
	}

	$tppnew = in_array($tppnew, array(10, 20, 30)) ? $tppnew : 0;
	$pppnew = in_array($pppnew, array(5, 10, 15)) ? $pppnew : 0;

	$dateformatnew = ($dateformatnew = intval($dateformatnew)) && !empty($userdateformat[$dateformatnew -1]) ? $dateformatnew : 0;

	$icq = preg_match("/^([0-9]+)$/", $icq) && strlen($icq) >= 5 && strlen($icq) <= 12 ? $icq : '';
	$qq = preg_match("/^([0-9]+)$/", $qq) && strlen($qq) >= 5 && strlen($qq) <= 12 ? $qq : '';
	$bday = datecheck($bday) ? $bday : '0000-00-00';

	//$avatar = dhtmlspecialchars($avatar);

	$yahoo = dhtmlspecialchars($yahoo);
	$taobao = dhtmlspecialchars($taobao);
	$email = dhtmlspecialchars($email);
	$msn = dhtmlspecialchars($msn);
	$alipay = dhtmlspecialchars($alipay);
	$bday = dhtmlspecialchars($bday);

	$signature = censor($signature);
	$sigstatus = $signature ? 1 : 0;
	$sightml = addslashes(discuzcode(stripslashes($signature), 1, 0, 0, 0, ($groupinfo['allowsigbbcode'] ? ($groupinfo['allowcusbbcode'] ? 2 : 1) : 0), $groupinfo['allowsigimgcode'], 0));

	$bio = censor(dhtmlspecialchars($bio));
	$site = dhtmlspecialchars(trim(preg_match("/^https?:\/\/.+/i", $site) ? $site : ($site ? 'http://'.$site : '')));

	$locationnew = cutstr(censor(dhtmlspecialchars($locationnew)), 30);
	$nickname = $groupinfo['allownickname'] ? cutstr(censor(dhtmlspecialchars($nickname)), 30) : '';
	$cstatus = $groupinfo['allowcstatus'] ? cutstr(censor(dhtmlspecialchars($cstatus)), 30) : '';

	$invisiblenew = $invisiblenew && $groupinfo['allowinvisible'] ? 1 : 0;

	$idstring = random(6);
	$authstr = $regverify == 1 ? "$timestamp\t2\t$idstring" : '';
	$password = md5(random(10));

	$db->query("INSERT INTO {$tablepre}members (uid, username, password, secques, gender, adminid, groupid, regip, regdate, lastvisit, lastactivity, posts, credits, extcredits1, extcredits2, extcredits3, extcredits4, extcredits5, extcredits6, extcredits7, extcredits8, email, bday, sigstatus, tpp, ppp, styleid, dateformat, timeformat, pmsound, showemail, newsletter, invisible, timeoffset)
		VALUES ('$uid', '$username', '$password', '$secques', '$gendernew', '0', '$groupinfo[groupid]', '$onlineip', '$timestamp', '$timestamp', '$timestamp', '0', $initcredits, '$email', '$bday', '$sigstatus', '$tppnew', '$pppnew', '$styleidnew', '$dateformatnew', '$timeformatnew', '$pmsoundnew', '$showemailnew', '$newsletter', '$invisiblenew', '$timeoffsetnew')");

	$db->query("INSERT INTO {$tablepre}memberfields (uid, nickname, site, icq, qq, yahoo, msn, taobao, alipay, location, bio, sightml, customstatus, authstr $fieldadd1)
		VALUES ('$uid', '$nickname', '$site', '$icq', '$qq', '$yahoo', '$msn', '$taobao', '$alipay', '$locationnew', '$bio', '$sightml', '$cstatus', '$authstr' $fieldadd2)");

	if($regctrl || $regfloodctrl) {
		$db->query("DELETE FROM {$tablepre}regips WHERE dateline<='$timestamp'-".($regctrl > 72 ? $regctrl : 72)."*3600", 'UNBUFFERED');
		if($regctrl) {
			$db->query("INSERT INTO {$tablepre}regips (ip, count, dateline)
				VALUES ('$onlineip', '-1', '$timestamp')");
		}
	}

	if($regverify == 2) {
		$db->query("REPLACE INTO {$tablepre}validating (uid, submitdate, moddate, admin, submittimes, status, message, remark)
			VALUES ('$uid', '$timestamp', '0', '', '1', '0', '$regmessage', '')");
	}

	if($invitecode && $regstatus > 1) {
		$db->query("UPDATE {$tablepre}invites SET reguid='$uid', regdateline='$timestamp', status='2' WHERE invitecode='$invitecode'");
		if($inviteaddbuddy) {
			include_once DISCUZ_ROOT.'./uc_client/client.php';
			uc_friend_add($invite['uid'], $uid, '');
		}

		if($inviterewardcredit) {
			if($inviteaddcredit) {
				$db->query("UPDATE {$tablepre}members SET extcredits$inviterewardcredit=extcredits$inviterewardcredit+'$inviteaddcredit' WHERE uid='$uid'");
			}
			if($invitedaddcredit) {
				$db->query("UPDATE {$tablepre}members SET extcredits$inviterewardcredit=extcredits$inviterewardcredit+'$invitedaddcredit' WHERE uid='$invite[uid]'");
			}
		}
	}

	$discuz_uid = $uid;
	$discuz_user = $username;
	$discuz_userss = stripslashes($discuz_user);
	$discuz_pw = $password;
	$discuz_secques = $secques;
	$groupid = $groupinfo['groupid'];
	$styleid = $styleid ? $styleid : $_DCACHE['settings']['styleid'];

	if($welcomemsg && !empty($welcomemsgtxt)) {
		$welcomtitle = !empty($welcomemsgtitle) ? $welcomemsgtitle : "Welcome to $bbname!";
		$welcomtitle = addslashes(replacesitevar($welcomtitle));
		$welcomemsgtxt = addslashes(replacesitevar($welcomemsgtxt));
		if($welcomemsg == 1) {
			sendpm($uid, $welcomtitle, $welcomemsgtxt, 0);
		} elseif($welcomemsg == 2) {
			sendmail("$username <$email>", $welcomtitle, $welcomemsgtxt);
		}
	}

	if($fromuid) {
		updatecredits($fromuid, $creditspolicy['promotion_register']);
		dsetcookie('promotion', '');
	}

	require_once DISCUZ_ROOT.'./include/cache.func.php';
	$_DCACHE['settings']['totalmembers']++;
	$_DCACHE['settings']['lastmember'] = $discuz_userss;
	updatesettings();

	dsetcookie('loginuser', '', -86400 * 365);
	dsetcookie('activationauth', '', -86400 * 365);

	switch($regverify) {
		case 1:
			sendmail("$username <$email>", 'email_verify_subject', 'email_verify_message');
			dz_showmessage('profile_email_verify');
			break;
		case 2:
			dz_showmessage('register_manual_verify', 'memcp.php');
			break;
		default:
			if($_DCACHE['settings']['frameon'] && $_DCOOKIE['frameon'] == 'yes') {
				$extrahead .= '<script>if(top != self) {parent.leftmenu.location.reload();}</script>';
			}

			dsetcookie("auth",authcode("$password\t$secques\t$uid", 'ENCODE'));
			dsetcookie("sid","");

			//注册整站
			$rs=array(
				"uid"=>$uid,
				"username"=>$username,
				"sex"=>$gendernew,
				"bday"=>$bday,
				"icon"=>'',
				"introduce"=>$regintroduce
			);
			Reg_memberdata($rs);
			Reg_memberdata_field($winduid,$postdb);

			dz_showmessage('恭喜你,注册成功.请登录', "../");
			break;
	}

}

function replacesitevar($string, $replaces = array()) {
	global $sitename, $bbname, $timestamp, $timeoffset, $adminemail, $adminemail, $discuz_user;
	$sitevars = array(
		'{sitename}' => $sitename,
		'{bbname}' => $bbname,
		'{time}' => gmdate('Y-n-j H:i', $timestamp + $timeoffset * 3600),
		'{adminemail}' => $adminemail,
		'{username}' => $discuz_user,
		'{myname}' => $discuz_user
	);
	$replaces = array_merge($sitevars, $replaces);
	return str_replace(array_keys($replaces), array_values($replaces), $string);
}



/**
*P8模板相关函数
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

function dz_showmessage($msg,$url='',$exta=''){
	$msgdb[login_succeed]='已经登录';
	$msgdb[register_disable]='禁止注册';
	$msgdb[profile_username_tooshort]='用户名太短';
	$msgdb[profile_username_toolong]='用户名太长';
	$msgdb[profile_passwd_notmatch]='密码不相同';
	$msgdb[profile_username_illegal]='用户名有不合法字符';
	$msgdb[profile_nickname_cstatus_illegal]='昵称有不合法字符';
	$msgdb[profile_passwd_illegal]='密码有误';
	$msgdb[profile_email_illegal]='邮箱有误';
	$msgdb[profile_alipay_illegal]='支付宝帐号有误';
	$msgdb[profile_passwd_illegal]='密码有误';
	$msgdb[profile_alipay_msn]='MSN有误';
	$msgdb[profile_required_info_invalid]='信息有误';
	$msgdb[undefined_action]='表单有误';
	$msgdb[profile_sig_toolong]='签名太长';
	$msgdb[register_invite_notfound]='资料不存在';
	$msgdb[register_invite_error]='信息有误,不存在';
	$msgdb[register_invite_iperror]='IP受限';
	$msgdb[register_invite_expiration]='有效时间不对';
	$msgdb[register_ctrl]='有误';
	$msgdb[profile_username_duplicate]='用户名雷同';
	$msgdb[profile_email_duplicate]='邮箱雷同';
	$msgdb[register_flood_ctrl]='有误,';
	$msgdb[profile_avatar_invalid]='有误,';
	$msgdb[profile_email_verify]='邮件已发出';
	$msgdb[register_manual_verify]=' 已发出';
	$msgdb[register_succeed]=' 注册成功';
	if($msgdb[$msg]){
		$msg=$msgdb[$msg];
	}
	if($url==''){
		showerr($msg);
	}else{
		//refreshto($url,$msg,1);
		echo "$msg<META HTTP-EQUIV=REFRESH CONTENT='1;URL=$url'>";
		exit;
	}
}


/**
*警告页面函数
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

?>