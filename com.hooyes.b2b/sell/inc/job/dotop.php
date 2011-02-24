<?php
if(!function_exists('html')){
die('F');
}
if(!$lfjuid){
	showerr('请先登录');
}



$_erp=$Fid_db[tableid][$fid];
$rs=$db->get_one("SELECT * FROM {$_pre}content$_erp WHERE id='$id'");
if($rs[uid]!=$lfjuid&&!$web_admin){
	showerr('你没权限');
}
$list=$timestamp+3600*24*$webdb[Info_TopDay];
if(!$web_admin){
	$lfjdb[money]=intval(get_money($lfjuid));
	if($lfjdb[money]<$webdb[Info_TopMoney]){
		showerr("你的积分不足:$webdb[Info_TopMoney],不能选择置顶");
	}
	add_user($lfjuid,-intval($webdb[Info_TopMoney]),'置顶扣分');
}
$db->query("UPDATE {$_pre}content$_erp SET list='$list' WHERE id='$id'");
refreshto("$FROMURL","置顶成功",1);
?>