<?php
!function_exists('html') && exit('ERR');
if(!$lfjuid){
	showerr("请先登录!");
}
if(!$web_admin){
	$rs=$db->get_one("SELECT C.uid FROM `{$pre}vote` V LEFT JOIN `{$pre}vote_config` C ON V.cid=C.cid WHERE V.id='$id'");
	if($rs[uid]!=$lfjuid||!$lfjuid){
		showerr("你没权限!");
	}
}
$db->query("DELETE FROM `{$pre}vote` WHERE id='$id'");	
refreshto($FROMURL,"删除成功",1);
?>