<?php
!function_exists('html') && exit('ERR');
unset($name,$uid,$email);
list($name,$uid,$email)=explode("\t",mymd5($eid,'DE') );
if($name&&$uid&&$email){
	
	$rsdb=$db->get_one("SELECT D.* FROM {$pre}memberdata D LEFT JOIN {$TB[table]} M ON D.uid=M.$TB[uid] WHERE M.$TB[uid]='$uid'");
	if($rsdb[email_yz]==1){
		showerr("�벻Ҫ�ظ���֤");
	}elseif($rsdb){
		$db->query("UPDATE {$pre}memberdata SET email_yz='1',email='$email' WHERE uid='$uid'");
		add_user($rsdb[uid],$webdb[YZ_EmailMoney]);
		refreshto("$webdb[www_url]/","��ϲ��!������֤�ɹ�,ͬʱ��Ļ���������{$webdb[YZ_EmailMoney]}��",3);
	}else{
		showerr("������֤ʧ��,�������޸�������,�������ύ��֤!");
	}
}else{
	showerr("��֤ʧ��!");
}
?>