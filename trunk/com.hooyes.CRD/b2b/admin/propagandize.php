<?php
!function_exists('html') && exit('ERR');
if($action=="del"&&$Apower[propagandize_list])
{
	foreach( $idDB AS $key=>$value){
		$db->query("DELETE FROM `{$pre}propagandize` WHERE id='$value'");
	}
	jump("删除成功","$FROMURL",1);
}
elseif($job=="list"&&$Apower[propagandize_list])
{
	$rows=20;
	if(!$page){
		$page=1;
	}
	$min=($page-1)*$rows;
	$showpage=getpage("`{$pre}propagandize`"," ","?lfj=$lfj&job=$job");
	$query = $db->query("SELECT P.*,M.$TB[username] FROM `{$pre}propagandize` P LEFT JOIN {$TB[table]} M ON P.uid=M.$TB[uid] ORDER BY P.id DESC LIMIT $min,$rows");
	while($rs = $db->fetch_array($query)){
		$rs[ip]=long2ip($rs[ip]);
		$rs[ipfrom]=ipfrom($rs[ipfrom]);
		$rs[posttime]=date("Y-m-d H:i",$rs[posttime]);
		$listdb[]=$rs;
	}
	require(dirname(__FILE__)."/"."template/head.htm");
	require(dirname(__FILE__)."/"."template/propagandize/menu.htm");
	require(dirname(__FILE__)."/"."template/propagandize/list.htm");
	require(dirname(__FILE__)."/"."template/foot.htm");
}
elseif($action=="set"&&$Apower[propagandize_list])
{
	write_config_cache($webdbs);
	jump("修改成功",$FROMURL);
}
elseif($job=="set"&&$Apower[propagandize_list])
{
	$propagandize_close[intval($webdb[propagandize_close])]=' checked ';
	require(dirname(__FILE__)."/"."template/head.htm");
	require(dirname(__FILE__)."/"."template/propagandize/menu.htm");
	require(dirname(__FILE__)."/"."template/propagandize/set.htm");
	require(dirname(__FILE__)."/"."template/foot.htm");
}
?>