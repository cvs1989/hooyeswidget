<?php
require("global.php");

$_GET['_fromurl'] && $_fromurl=$_GET['_fromurl'];


if($webdb[forbidReg]){
	showerr("�ܱ�Ǹ,��վ�ر���ע��");
}

/*
if($webdb[passport_type])
{
	if(!$_fromurl)
	{
		$_fromurl=$FROMURL;
	}
	$_fromurl=urlencode($_fromurl);
	if(eregi("^pwbbs",$webdb[passport_type]))
	{
		header("location:reg_pw.php?_fromurl=$_fromurl");
		exit;
	}
	if(eregi("dzbbs5",$webdb[passport_type]))
	{
		header("location:reg_dz.php?_fromurl=$_fromurl");
		exit;
	}
	if( $webdb[passport_type]!='dzbbs7')
	{
		header("location:$TB_url/$TB_register");
		exit;
	}
}
*/

if($step==2){

	//�û��Զ����ֶ�
	require_once(PHP168_PATH."/do/regfield.php");
	ck_regpost($postdb);

	if($webdb[forbidRegIp]){
		$detail=explode("\r\n",$webdb[forbidRegIp]);
		foreach( $detail AS $key=>$value){
			//if(strstr($onlineip,$value)&&ereg("^$value",$onlineip)){
			if(strstr($onlineip,$value)){
				showerr("������IP��ֹע��");
			}
		}
	}
	if($webdb[limitRegTime]&&$_COOKIE[limitRegTime]){
		showerr("{$webdb[limitRegTime]} ������,�벻Ҫ�ظ�ע��");
	}
	if( $webdb[yzImgReg] ){
		if(!yzimg($yzimg)){
			showerr("��֤�벻����");
		}
	}
	if(!$username){
		showerr("�ʺŲ���Ϊ��");
	}elseif(!$password){
		showerr("���벻��Ϊ��");
	}elseif($password!=$password2){
		showerr("�����������벻һ��");
	}elseif(!$email){
		showerr("���䲻��Ϊ��");
	}
	if (!ereg("^[-a-zA-Z0-9_\.]+\@([0-9A-Za-z][0-9A-Za-z-]+\.)+[A-Za-z]{2,5}$",$email)) {
		showerr("���䲻���Ϲ���"); 
	}
	if (strlen($username)>30 || strlen($username)<3){
		showerr("�ʺŲ���С��3���ַ������30���ַ�");
	}
	if (strlen($password)>30 || strlen($password)<6){
		showerr("���벻��С��6���ַ������30���ַ�");
	}
	$S_key=array('|',' ','��',"'",'"','/','*',',','~',';','<','>','$',"\\","\r","\t","\n","`","!","?","%","^");
	foreach($S_key as $value){
		if (strpos($username,$value)!==false){ 
			showerr("�û����а����н�ֹ�ķ��š�{$value}��"); 
		}
		if (strpos($password,$value)!==false){ 
			showerr("�����а����н�ֹ�ķ��š�{$value}��"); 
		}
	}
	if($webdb[forbidRegName]){
		$detail=explode("\r\n",$webdb[forbidRegName]);
		if(in_array($username,$detail)){
			showerr("�ܱ������ʺ�,������ʹ��,�����һ����");
		}
	}
/*
for($i=0;$i<100000;$i++){
	$username = 'user_'.$i;
	$timestamp = time();
*/
	
	$msn=filtrate($msn);
	$homepage=filtrate($homepage);

	if(eregi("^pwbbs",$webdb[passport_type]))
	{
		if( $db->get_one("SELECT * FROM $TB[table] WHERE username='$username'") ){
			showerr("���û����Ѿ�������,������ע��һ��");
		}
		$rg_yz=$webdb[RegYz]?1:$timestamp;
		$password=pwd_md5($password);
		$db->update("INSERT INTO {$TB_pre}members (username,password,email,groupid,memberid,gender,regdate,introduce,oicq,icq,bday,yz) VALUES ('$username','$password','$email','-1',8,'$sex','$timestamp','','$oicq','$icq','$bday_y-$bday_m-$bday_d','$rg_yz')");		
		@extract($db->get_one("SELECT uid FROM $TB[table] ORDER BY uid DESC LIMIT 1"));
		$db->update("INSERT INTO {$TB_pre}memberdata (uid,postnum,rvrc,money,lastvisit,thisvisit,onlineip) VALUES ('$uid','0','0','0','$timestamp','$timestamp','$onlineip')");
		$db->update("UPDATE {$TB_pre}bbsinfo SET newmember='$username',totalmember=totalmember+1 WHERE id='1'");
	}
	elseif(eregi("dzbbs5",$webdb[passport_type]))
	{
		if( $db->get_one("SELECT * FROM $TB[table] WHERE username='$username'") ){
			showerr("���û����Ѿ�������,������ע��һ��");
		}
		$password=pwd_md5($password);
		$db->query("INSERT INTO {$TB_pre}members (username, password,  gender, adminid, groupid, regip, regdate, lastvisit, email)
		VALUES ( '$username', '$password', '$sex', '0', '10', '$onlineip', '$timestamp', '$timestamp','$email')");
		@extract($db->get_one("SELECT uid FROM $TB[table] ORDER BY uid DESC LIMIT 1"));
		$db->query("INSERT INTO {$TB_pre}memberfields (uid) VALUES ('$uid')");
	}
	elseif($webdb[passport_type]=='dzbbs7')
	{
		$uid = uc_user_register($_POST['username'], $_POST['password'], $_POST['email']);
		if($uid <= 0) {
			if($uid == -1) {
				showerr('�û������Ϸ�');
			} elseif($uid == -2) {
				showerr('����Ҫ����ע��Ĵ���');
			} elseif($uid == -3) {
				showerr('�û����Ѿ�����');
			} elseif($uid == -4) {
				showerr('Email ��ʽ����');
			} elseif($uid == -5) {
				showerr('Email ������ע��');
			} elseif($uid == -6) {
				showerr('�� Email �Ѿ���ע��');
			} else {
				showerr('δ����');
			}
			exit;
		}
	}
	else
	{
		if( $db->get_one("SELECT * FROM `{$pre}members` WHERE username='$username'") ){
			showerr("���û����Ѿ�������,������ע��һ��");
		}
		$password=pwd_md5($password);
		$db->query("INSERT INTO `{$pre}members` (`username` , `password` ) VALUES ('$username', '$password')");
		@extract($db->get_one("SELECT uid FROM `{$pre}members` ORDER BY uid DESC LIMIT 1"));
	}	
	//$yz=1;
	
	$gtype=0;
	//��Ҫ�û���д���Ϻ�,���ܳ�Ϊ��ҵ�û�.�粻��д����Ҳ�ܳ�Ϊ��ҵ�û��Ļ�,��������//��ȡ������
	//$gtype=$grouptype==1?1:0;

	if($webdb[RegCompany] && $gtype==1){
		//ע����ҵ�û�
		$db->query("INSERT INTO `{$pre}memberdata_1` ( `uid`) VALUES ('$uid')");
	}

	$db->query("INSERT INTO `{$pre}memberdata` ( `uid` , `username` , `question` , `groupid` , `grouptype` , `yz` , `newpm` , `medals` , `money` , `lastvist` , `lastip` , `regdate` , `regip` , `sex` , `bday` , `icon` , `introduce` , `oicq` , `msn` , `homepage` , `email` , `address` , `postalcode` , `mobphone` , `telephone` , `idcard` , `truename` ) VALUES ('$uid' , '$username', '', '8', '$gtype', '$webdb[RegYz]', '0', '', '$webdb[regmoney]', '$timestamp', '$onlineip', '$timestamp', '$onlineip', '$sex', '$bday_y-$bday_m-$bday_d', '', '', '$oicq', '$msn', '$homepage', '$email', '', '0', '', '', '', '')");

	if(eregi("dzbbs7",$webdb[passport_type])){
		$db->query("INSERT INTO $TB[table] (uid,username,password,groupid,regip,regdate,lastip,lastvisit,lastactivity,email,pmsound,newsletter,timeoffset,editormode) VALUES ('$uid','$username','$password','10','$onlineip','$timestamp','$onlineip','$timestamp','$timestamp','$email',1,1,9999,2) ");
		$rs=$db_uc->get_one("SELECT * FROM ".UC_DBTABLEPRE."members WHERE username='$username'");
		@extract($rs=$db->get_one("SELECT *,secques AS discuz_secques FROM {$TB_pre}members WHERE  username='$username'"));
		$discuz_auth_key = md5($_DCACHE['settings']['authkey'].$_SERVER['HTTP_USER_AGENT']);
		set_cookie("{$cookiepre}auth",authcode("$rs[password]\t$discuz_secques\t$rs[uid]", 'ENCODE'),$cookietime);
		set_cookie("{$cookiepre}sid","");
	}
	elseif( eregi("^pwbbs",$webdb[passport_type]) ){
		set_cookie(CookiePre().'_winduser',StrCode($uid."\t".PwdCode($password)."\t$safecv"),3600);
		set_cookie('lastvisit','',0);
	}
	else{
		set_cookie("passport","$uid\t$username\t".mymd5("$password"),31536000);
	}

	//ע��ʱ��������
	if($webdb[limitRegTime]){
		set_cookie("limitRegTime",1,$webdb[limitRegTime]*60);
	}
	
	//ע���û��Զ����ֶ�
	Reg_memberdata_field($uid,$postdb);

	$jumpto&&$jumpto=urldecode($jumpto);
	
	//ע�ṫ˾����

	$jastgoctrl=Reg_company_info($company_db); //�����Ƿ�浵 1Ϊ�浵 0û��

/*}*/
	
	//��ת����ҳ����ҳ��
	refreshto("?step=3&jastgoctrl=$jastgoctrl","��ϲ�㣬ע��ɹ�",0);

	/*
	if(strstr($jumpto,$webdb[www_url])){
		refreshto("$jumpto","��ϲ�㣬ע��ɹ�",0);
	}else{
		refreshto("$webdb[www_url]","��ϲ�㣬ע��ɹ�",0);
	}
	*/
}elseif($step==3){
	
	
	$jastgourl=$jastgoctrl?$Mdomain."/member/index.php?main=homepage_ctrl.php?atn=info":$Mdomain."/member/index.php?main=post_company.php";
	//$jastgourl=$Mdomain."/member/index.php?main=post_company.php";
	

}else{
	if($lfjid){
		showerr("���Ѿ�ע����,�벻Ҫ�ظ�ע��,Ҫע��,�����˳�");
	}
	$_fromurl || $_fromurl=$FROMURL;
	

}

//require(Mpath."inc/head.php");
require(getTpl("head2"));
require(getTpl("reg"));
require(Mpath."inc/foot.php");

function Reg_company_info($company_db){
	global $db,$webdb,$username,$uid,$timestamp,$_pre;
	
	if(!$company_db['title'] || !$company_db['qy_contact']){
		return 0;
	}else{
		$yz=$webdb[postcompanyauto_yz]?$webdb[postcompanyauto_yz]:0;
		$db->query("INSERT INTO `{$_pre}company` ( `rid` , `title` ,  `fname` , `uid` , `username` , `posttime` , `listorder` , `picurl` , `yz` , `yzer` , `yztime` , `content` , `province_id` , `city_id` , `qy_cate` , `qy_saletype` , `qy_regmoney` , `qy_createtime` , `qy_regplace` , `qy_address` , `qy_postnum` , `qy_pro_ser` , `my_buy` , `my_trade` , `qy_contact`,`qy_contact_zhiwei` , `qy_contact_sex` , `qy_contact_tel` , `qy_contact_mobile` , `qy_contact_fax` , `qy_contact_email` , `qy_website` , `qq` , `msn` , `skype` ) 
VALUES (
'', '$company_db[title]', '', '$uid', '$username', '".$timestamp."', '0', '', '$yz', '', '".$timestamp."', '', '', '', '', '', '', '', '', '', '', '', '', '', '$company_db[qy_contact]', '$company_db[qy_contact_zhiwei]', '$company_db[qy_contact_sex]', '$company_db[qy_contact_tel]', '$company_db[qy_contact_mobile]', '$company_db[qy_contact_fax]', '$company_db[qy_contact_email]', '$company_db[qy_website]', '$company_db[qq]', '$company_db[msn]', '$company_db[skype]');");
		
	//��������
		if($webdb[company_add_money]) plus_money($lfjuid,$webdb[company_add_money]);
		

	//��������
		$title='��ӭע��'.$webdb[webname]."��Ա!";
		$reg_sent_html=get_reg_sent_html($username,$uid);

	//����
		$array[touid]=$uid;
		$array[fromuid]=0;
		$array[fromer]='ϵͳ��Ϣ';
		$array[title]=$title;
		$array[content]=$reg_sent_html;
		if(function_exists('pm_msgbox')){
			//pm_msgbox($array);
		}
	//�ʼ�
		if(function_exists('easy_sent_email')){
			//easy_sent_email($uid,$title,$reg_sent_html);
		}

		return 1;
	}

}
?>