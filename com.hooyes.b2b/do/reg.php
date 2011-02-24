<?php
require(dirname(__FILE__)."/"."global.php");
require(ROOT_PATH."data/level.php");

$_GET['_fromurl'] && $_fromurl=$_GET['_fromurl'];
if($lfjid){
	showerr("你已经注册了,请不要重复注册,要注册,请先退出");
}
if($webdb[forbidReg]){
	showerr("很抱歉,网站关闭了注册");
}


if($step==2){

	//用户自定义字段
	require_once(ROOT_PATH."/do/regfield.php");
	ck_regpost($postdb);

	if($webdb[forbidRegIp]){
		$detail=explode("\r\n",$webdb[forbidRegIp]);
		foreach( $detail AS $key=>$value){
			//if(strstr($onlineip,$value)&&ereg("^$value",$onlineip)){
			if(strstr($onlineip,$value)){
				showerr("你所在IP禁止注册");
			}
		}
	}
	if($webdb[limitRegTime]&&get_cookie("limitRegTime")){
		showerr("{$webdb[limitRegTime]} 分钟内,请不要重复注册");
	}
	if( $webdb[yzImgReg] ){
		if(!check_imgnum($yzimg)){
			showerr("验证码不符合");
		}
	}
	if($password!=$password2){
		showerr("两次输入密码不一样");
	}elseif ($msn&&!ereg("^[-a-zA-Z0-9_\.]+\@([0-9A-Za-z][0-9A-Za-z-]+\.)+[A-Za-z]{2,5}$",$msn)) {
		showerr("MSN不符合规则"); 
	}
	if($webdb[forbidRegName]){
		$detail=explode("\r\n",$webdb[forbidRegName]);
		if(in_array($username,$detail)){
			showerr("受保护的帐号,不允许使用,请更换一个吧");
		}
	}
	$msn = filtrate($msn);
	$homepage = filtrate($homepage);
	
	
	$gtype=0;
	//需要用户填写资料后,才能成为企业用户.如不填写资料也能成为企业用户的话,请把下面的//线取消即可
	//$gtype=$grouptype==1?1:0;
	
	if($groupid==3||$groupid==4||$memberlevel[$groupid]||!in_array($groupid,explode(",",$webdb[reg_group]))){
		$groupid=8;
	}

	$groupid || $groupid=8;
	

	$array=array(
		'username'=>$username,
		'password'=>$password,
		'groupid'=>intval($groupid),
		'grouptype'=>$gtype,
		'yz'=>$webdb[RegYz],
		'lastvist'=>$timestamp,
		'lastip'=>$onlineip,
		'regdate'=>$timestamp,
		'regip'=>$onlineip,
		'sex'=>$sex,
		'bday'=>"$bday_y-$bday_m-$bday_d",
		'oicq'=>$oicq,
		'msn'=>$msn,
		'homepage'=>$homepage,
		'email'=>$email,
	);

	//用户注册
	$uid = $userDB->register_user($array);
	if(!is_numeric($uid)){
		showerr($uid);
	}

	if($webdb[RegCompany] && $gtype==1){
		//注册企业用户
		//$db->query("INSERT INTO `{$pre}memberdata_1` ( `uid`) VALUES ('$uid')");
	}
	
	//用户登录
	$cookietime = 3600;
	$userDB->login($username,$password,$cookietime);

	//注册时间间隔处理
	if($webdb[limitRegTime]){
		set_cookie("limitRegTime",1,$webdb[limitRegTime]*60);
	}
	
	//注册用户自定义字段
	Reg_memberdata_field($uid,$postdb);

	//通行证处理
	if($_COOKIE[passport_url]||$_POST[passport_url]){
		$passport_url=urldecode($_COOKIE[passport_url]?$_COOKIE[passport_url]:$_POST[passport_url]);
		setcookie('passport_url','');
		$userDB->passport_server($username,$passport_url);
	}

	$jumpto&&$jumpto=urldecode($jumpto);

	add_user($uid,$webdb[regmoney],'注册得分');

	if(strstr($jumpto,$webdb[www_url])){
		refreshto("$jumpto","恭喜你，注册成功",1);
	}else{
		refreshto("$webdb[www_url]","恭喜你，注册成功",1);
	}
}else{

	//通行证处理
	if($_GET[passport_url]){
		setcookie('passport_url',$_GET[passport_url]);
	}

	$_fromurl || $_fromurl=$FROMURL;
	require(ROOT_PATH."inc/head.php");
	require(html("reg"));
	require(ROOT_PATH."inc/foot.php");
}
?>