<?php
!function_exists('html') && exit('ERR');
if($job=='list'&&$Apower[js_list]){
	$rows=20;
	if(!$page){
		$page=1;
	}
	$min=($page-1)*$rows;
	$showpage=getpage("{$pre}label","WHERE ch=0 AND module=0 AND pagetype=0 ","index.php?lfj=$lfj&job=$job","$rows");
	$query = $db->query("SELECT * FROM {$pre}label WHERE ch=0 AND module=0 AND pagetype=0 ORDER BY lid DESC LIMIT $min,$rows");
	while($rs = $db->fetch_array($query)){
		$listdb[]=$rs;
	}
	require(dirname(__FILE__)."/"."head.php");
	require(dirname(__FILE__)."/"."template/js/menu.htm");
	require(dirname(__FILE__)."/"."template/js/list.htm");
	require(dirname(__FILE__)."/"."foot.php");
}
elseif($job=="edit"&&$Apower[js_list])
{
	$rsdb=$db->get_one("SELECT * FROM {$pre}label WHERE lid='$lid'");
	require(dirname(__FILE__)."/"."head.php");
	require(dirname(__FILE__)."/"."template/js/menu.htm");
	require(dirname(__FILE__)."/"."template/js/edit.htm");
	require(dirname(__FILE__)."/"."foot.php");
}
elseif($action=="edit"&&$Apower[js_list])
{
	$db->query("UPDATE {$pre}label SET name='$name' WHERE lid='$lid' ");
	jump("�޸ĳɹ�",$FROMURL,1);
}
elseif($job=="show"&&$Apower[js_list])
{
	if(!$id){//����´�����JS
		@extract($db->get_one("SELECT lid AS id FROM {$pre}label WHERE ch=0 ORDER BY lid DESC LIMIT 1"));
	}
	require(dirname(__FILE__)."/"."head.php");
	require(dirname(__FILE__)."/"."template/js/menu.htm");
	require(dirname(__FILE__)."/"."template/js/show.htm");
	require(dirname(__FILE__)."/"."foot.php");
}
elseif($action=="delete"&&$Apower[js_list])
{
	$db->query("DELETE FROM {$pre}label WHERE lid='$id' ");
	jump("ɾ���ɹ�",$FROMURL,1);
}