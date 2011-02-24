<?php
require(dirname(__FILE__)."/"."global.php");


if(!$action){
	$page=$page?$page:1;
	$rows=20;
	$min=($page-1)*$rows;
	
	$query=$db->query("SELECT A.*,B.title as owner,B.uid FROM `{$_pre}form1` A  INNER JOIN `{$_pre}company` B on B.uid=A.owner_uid where A.from_uid='$lfjuid' limit $min,$rows");
	while($rs = $db->fetch_array($query)){
		$rs[posttime]=date("Y-m-d H:i",$rs[posttime]);
		$rs[reply]=$rs[is_reply]?"<font color=red>已回复</font>":"未回复";
		
		$listdb[]=$rs;
	}
	$showpage=getpage("`{$_pre}form1` A  INNER JOIN `{$_pre}company` B on B.uid=A.owner_uid","where A.from_uid='$lfjuid'","?",$rows);
}elseif($action=='view'){
	if(!$id) showerr("参数错误");
	$rt=$db->get_one("SELECT A.*,B.title as owner FROM `{$_pre}form1` A  INNER JOIN `{$_pre}company` B on B.uid=A.owner_uid  where id='$id'");
	$rt[posttime]=date("Y-m-d H:i",$rt[posttime]);
	$contact_info=unserialize($rt[contact_info]);
	$rt[reply_time]=date("Y-m-d,H:i:s",$rt[reply_time]);
	//print_r($contact_info);
}

require(dirname(__FILE__)."/"."head.php");
require(dirname(__FILE__)."/"."template/form1.htm");
require(dirname(__FILE__)."/"."foot.php");
?>