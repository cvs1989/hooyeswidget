<?php
require_once("global.php");
@include_once(Adminpath."../php168/companyData.php");
$linkdb=array("未审核的"=>"?unyz=1","全部参考"=>"?");

if(!$action){
	$rows=10;
	$page=intval($page);
	if(!$page)$page=1;
	$min=($page-1)*$rows;
	
	$where=" where 1";
	if($keyword)$where.=" and companyName like('%$keyword%')";
	if($unyz) $where.=" and yz=0 ";
	$showpage=getpage("{$_pre}cankao",$where,"?unyz=$unyz&keyword=".urlencode($keyword),$rows);
	

	$query=$db->query("select * from {$_pre}cankao $where  limit $min,$rows");
	while($rs=$db->fetch_array($query)){
		
		$rs[yz]=!$rs[yz]?"未审核":"<font color=red>已审核</font>";
		
		$rs[description]=get_word($rs[description],200);

		$listdb[]=$rs;	
	}

	require("head.php");
	require("template/cankao/list.htm");
	require("foot.php");

}elseif($action=='yz'){
	
	$rsdb=$db->get_one("select * from {$_pre}cankao where ck_id='$ck_id'");	
	if(!$rsdb) showerr("没有找到您要操作的项目");
	
	$yz=$rsdb[yz]?0:1;
	
	$db->query("update {$_pre}cankao set yz=$yz where ck_id='$ck_id' ");

	refreshto($FROMURL,"操作成功",1);

}elseif($action=='del'){

	$rsdb=$db->get_one("select * from {$_pre}cankao where ck_id='$ck_id'");	
	if(!$rsdb) showerr("没有找到您要操作的项目");
	

	
	//短信通知
	$array[touid]=$rsdb[uid];
	$array[fromuid]=0;
	$array[fromer]='系统消息';
	$array[title]='通知，您的站外参考资料操作通知';
	$array[content]="{$rsdb[username]}您好!<br>您在提交的\"$rsdb[title]\"参考资料未能通过审核，已经被删除；如果有需要，您可再次提交。 ";
	if(function_exists('pm_msgbox')){
		pm_msgbox($array);
	}
	//执行
    $db->query("delete from {$_pre}cankao where ck_id='$ck_id' limit 1");
	//回去
	refreshto($FROMURL,"操作成功",1);
}

?>