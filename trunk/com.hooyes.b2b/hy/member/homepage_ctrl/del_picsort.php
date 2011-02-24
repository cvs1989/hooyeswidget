<?php
//删除分类

if(!$psid) showerr("操作失败");
	
$mypics=$db->get_one("SELECT count(*) AS num FROM {$_pre}pic WHERE psid='$psid'");
if($mypics[num]>0) showerr("非空图集，不能删除");
	
$db->query("DELETE FROM {$_pre}picsort WHERE psid='$psid' AND uid='$uid' LIMIT 1");
refreshto("?uid=$uid&atn=pic","删除成功");

?>