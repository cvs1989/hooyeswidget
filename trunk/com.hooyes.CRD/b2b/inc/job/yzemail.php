<?php
!function_exists('html') && exit('ERR');
unset($name,$uid,$email);
list($name,$uid,$email)=explode("\t",mymd5($eid,'DE') );
if($name&&$uid&&$email){
	
	$rsdb=$db->get_one("SELECT D.* FROM {$pre}memberdata D LEFT JOIN {$TB[table]} M ON D.uid=M.$TB[uid] WHERE M.$TB[uid]='$uid'");
	if($rsdb[email_yz]==1){
		showerr("请不要重复验证");
	}elseif($rsdb){
		$db->query("UPDATE {$pre}memberdata SET email_yz='1',email='$email' WHERE uid='$uid'");
		add_user($rsdb[uid],$webdb[YZ_EmailMoney]);
		refreshto("$webdb[www_url]/","恭喜你!邮箱验证成功,同时你的积分增加了{$webdb[YZ_EmailMoney]}点",3);
	}else{
		showerr("邮箱验证失败,可能你修改了密码,请重新提交验证!");
	}
}else{
	showerr("验证失败!");
}
?>