<?php
require(dirname(__FILE__)."/"."global.php");
if(!$lfjid){
	showerr("�㻹û��¼");
}

if($webdb[propagandize_close]){
	showerr("ϵͳû�п��Ŵ˹���");
}

$rows=20;
if(!$page){
	$page=1;
}
$min=($page-1)*$rows;
$showpage=getpage("`{$pre}propagandize`","WHERE uid='$lfjuid'","?");
$query = $db->query("SELECT * FROM `{$pre}propagandize` WHERE uid='$lfjuid' ORDER BY id DESC LIMIT $min,$rows");
while($rs = $db->fetch_array($query)){
	$rs[ip]=long2ip($rs[ip]);
	$rs[ipfrom]=ipfrom($rs[ipfrom]);
	$rs[posttime]=date("Y-m-d H:i",$rs[posttime]);
	$listdb[]=$rs;
}

require(dirname(__FILE__)."/"."head.php");
require(dirname(__FILE__)."/"."template/propagandize.htm");
require(dirname(__FILE__)."/"."foot.php");

?>