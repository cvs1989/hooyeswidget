<?php
!function_exists('html') && exit('ERR');

if($job=="list"&&$Apower[gather_list_sort])
{
	$fid=intval($fid);
	
	$sortdb=array();
	$query = $db->query("SELECT * FROM {$pre}gather_sort ORDER BY list DESC");
	while($rs = $db->fetch_array($query)){
		$sortdb[]=$rs;
	}

	require(dirname(__FILE__)."/"."head.php");
	require(dirname(__FILE__)."/"."template/gather_sort/sort.htm");
	require(dirname(__FILE__)."/"."foot.php");
}
elseif($action=="addsort"&&$Apower[gather_list_sort])
{
	if(!$IS_BIZPhp168){
		@extract($db->get_one("SELECT COUNT(*) AS NUM FROM {$pre}gather_sort"));
		if($NUM>9){
			showerr("��Ѱ����ֻ�ܴ���10������");
		}
	}
	$db->query("INSERT INTO {$pre}gather_sort (name,fup,class,type) VALUES ('$name','0','1','1') ");	
	jump("�����ɹ�",$FROMURL);
}
elseif($job=="editsort"&&$Apower[gather_list_sort])
{
	$rsdb=$db->get_one("SELECT * FROM {$pre}gather_sort WHERE fid='$fid'");
	require(dirname(__FILE__)."/"."head.php");
	require(dirname(__FILE__)."/"."template/gather_sort/editsort.htm");
	require(dirname(__FILE__)."/"."foot.php");
}
elseif($action=="editsort"&&$Apower[gather_list_sort])
{
	$db->query("UPDATE {$pre}gather_sort SET name='$postdb[name]' WHERE fid='$fid' ");
	jump("�޸ĳɹ�","index.php?lfj=gather_sort&job=list");
}
elseif($action=="delete"&&$Apower[gather_list_sort])
{
	$db->query(" DELETE FROM `{$pre}gather_sort` WHERE fid='$fid' ");
	jump("ɾ���ɹ�",$FROMURL);
}
elseif($action=="editlist"&&$Apower[gather_list_sort])
{
	foreach( $order AS $key=>$value){
		$db->query("UPDATE {$pre}gather_sort SET list='$value' WHERE fid='$key' ");
	}
	jump("�޸ĳɹ�","$FROMURL",1);
}
?>