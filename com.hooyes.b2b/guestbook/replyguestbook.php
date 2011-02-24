<?php
require(dirname(__FILE__)."/"."global.php");

if(!$web_admin){
	showerr('你无权操作');
}
if($step){
	$array[content]=($content);//$array[content]=filtrate($content);
	$array[uid]=$lfjuid;
	$array[username]=$lfjid;
	$array[posttime]=$timestamp;
	$str=addslashes(serialize($array));
	$db->query("UPDATE {$_pre}content SET reply='$str' WHERE id='$id'");
	refreshto("./?fid=$fid","回复成功",1);
}else{
	$rsdb=$db->get_one("SELECT * FROM {$_pre}content WHERE id='$id'");
	extract(unserialize($rsdb[reply]));
}

require(ROOT_PATH."inc/head.php");
require(getTpl("replyguestbook"));
require(ROOT_PATH."inc/foot.php");
?>