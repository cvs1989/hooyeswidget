<?php
!function_exists('html') && exit('ERR');
if(!$lfjid){
	showerr("���ȵ�¼");
}elseif(!$id){
	showerr("ID������");
}
if($db->get_one("SELECT * FROM `{$pre}collection` WHERE `aid`='$id' AND uid='$lfjuid'")){
	showerr("�벻Ҫ�ظ��ղر���",1); 
}
if(!$web_admin){
	if($groupdb[CollectArticleNum]<1){
		$groupdb[CollectArticleNum]=50;
	}
	$rs=$db->get_one("SELECT COUNT(*) AS NUM FROM `{$pre}collection` WHERE uid='$lfjuid'");
	if($rs[NUM]>=$groupdb[CollectArticleNum]){
		showerr("�������û������ֻ���ղ�{$groupdb[CollectArticleNum]}ƪ����",1);
	}
}
$db->query("INSERT INTO `{$pre}collection` (  `aid` , `uid` , `posttime`) VALUES ('$id','$lfjuid','$timestamp')");

refreshto($FROMURL,"�ղسɹ�!",1);
?>