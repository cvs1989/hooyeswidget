<?php
!function_exists('html') && exit('ERR');
header('Content-Type: text/html; charset=gb2312');
if($type=='name'){
	if($name==''){
		die("�������ʺ�,����Ϊ��");
	}
	if (strlen($name)>30 || strlen($name)<3){
		die("�ʺŲ���С��3���ַ������30���ַ�");
	}
	$S_key=array('|',' ','��',"'",'"','/','*',',','~',';','<','>','$',"\\","\r","\t","\n","`","!","?","%","^");
	foreach($S_key as $value){
		if (strpos($name,$value)!==false){ 
			die("�û����а����н�ֹ�ķ��š�{$value}��"); 
		}
	}
	if($db->get_one("SELECT * FROM $TB[table] WHERE `$TB[username]`='$name'")){
		die("<font color='blue'>$name</font>,�Ѿ���ע����");
	}
	die("<font color=red>��ϲ��,���ʺſ���ʹ��!</font>");
}elseif($type=='email'){
	if($name==''){
		die("����������,����Ϊ��");
	}
	if (!ereg("^[-a-zA-Z0-9_\.]+\@([0-9A-Za-z][0-9A-Za-z-]+\.)+[A-Za-z]{2,5}$",$name)) {
		die("���䲻���Ϲ���"); 
	}
	die("<font color=red>��ȷ</font>");
}elseif($type=='pwd'){
	if($name==''){
		die("����������,����Ϊ��");
	}
	if (strlen($name)>30 || strlen($name)<6){
		die("���벻��С��6���ַ������30���ַ�");
	}
	$S_key=array('|',' ','��',"'",'"','/','*',',','~',';','<','>','$',"\\","\r","\t","\n","`","!","?","%","^");
	foreach($S_key as $value){
		if (strpos($name,$value)!==false){ 
			die("�����а����н�ֹ�ķ��š�{$value}��"); 
		}
	}
	die("<font color=red>��ȷ</font>");
}
?>