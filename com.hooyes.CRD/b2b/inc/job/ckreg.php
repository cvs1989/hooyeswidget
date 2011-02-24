<?php
!function_exists('html') && exit('ERR');
header('Content-Type: text/html; charset=gb2312');
if($type=='name'){
	if($name==''){
		die("请输入帐号,不能为空");
	}
	if (strlen($name)>30 || strlen($name)<3){
		die("帐号不能小于3个字符或大于30个字符");
	}
	$S_key=array('|',' ','',"'",'"','/','*',',','~',';','<','>','$',"\\","\r","\t","\n","`","!","?","%","^");
	foreach($S_key as $value){
		if (strpos($name,$value)!==false){ 
			die("用户名中包含有禁止的符号“{$value}”"); 
		}
	}
	if($db->get_one("SELECT * FROM $TB[table] WHERE `$TB[username]`='$name'")){
		die("<font color='blue'>$name</font>,已经被注册了");
	}
	die("<font color=red>恭喜你,此帐号可以使用!</font>");
}elseif($type=='email'){
	if($name==''){
		die("请输入邮箱,不能为空");
	}
	if (!ereg("^[-a-zA-Z0-9_\.]+\@([0-9A-Za-z][0-9A-Za-z-]+\.)+[A-Za-z]{2,5}$",$name)) {
		die("邮箱不符合规则"); 
	}
	die("<font color=red>正确</font>");
}elseif($type=='pwd'){
	if($name==''){
		die("请输入密码,不能为空");
	}
	if (strlen($name)>30 || strlen($name)<6){
		die("密码不能小于6个字符或大于30个字符");
	}
	$S_key=array('|',' ','',"'",'"','/','*',',','~',';','<','>','$',"\\","\r","\t","\n","`","!","?","%","^");
	foreach($S_key as $value){
		if (strpos($name,$value)!==false){ 
			die("密码中包含有禁止的符号“{$value}”"); 
		}
	}
	die("<font color=red>正确</font>");
}
?>