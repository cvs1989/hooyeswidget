<?php
!function_exists('html') && exit('ERR');
if(!$lfjuid){
	showerr("���ȵ�¼!");
}
if(!$web_admin){
	$rs=$db->get_one("SELECT C.uid FROM `{$pre}vote` V LEFT JOIN `{$pre}vote_config` C ON V.cid=C.cid WHERE V.id='$id'");
	if($rs[uid]!=$lfjuid||!$lfjuid){
		showerr("��ûȨ��!");
	}
}
$db->query("DELETE FROM `{$pre}vote` WHERE id='$id'");	
refreshto($FROMURL,"ɾ���ɹ�",1);
?>