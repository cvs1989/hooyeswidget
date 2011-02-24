<?php
require_once("global.php");
require(Adminpath."../php168/report.php");

if($job=='list')
{
	$rows=20;
	$page<1 && $page=1;
	$min=($page-1)*$rows;
	
	$showpage=getpage("{$_pre}report","","?job=$job",$rows);
	$query = $db->query("SELECT B.id, B.ctype, B.title, B.rid,B.iftrue,B.username AS Rname,B.posttime AS Rtime,B.type AS Rtype FROM {$_pre}report B ORDER BY B.rid DESC LIMIT $min,$rows");
	while($rs = $db->fetch_array($query)){
		if($rs[iftrue]==1){
			$rs[iftrue]="<font color=red>属实</font>";
		}elseif($rs[iftrue]==2){
			$rs[iftrue]="<font color=blue>不属实</font>";
		}else{
			$rs[iftrue]="待确认";
		}
		$rs[posttime]=date("m-d H:i",$rs[posttime]);
		$rs[Rtime]=date("m-d H:i",$rs[Rtime]);
		$listdb[]=$rs;
	}
	require("head.php");
	require("template/report/list.htm");
	require("foot.php");
}
elseif($jobs=="del"||$action=="del")
{
	if($rid){
		$db->query("DELETE FROM {$_pre}report WHERE rid='$rid'");
	}elseif($listdb){
		$s=implode(",",$listdb);
		$db->query("DELETE FROM {$_pre}report WHERE rid IN ($s)");
	}else{
		showerr("请选择一个");
	}
	refreshto("$FROMURL","删除成功",1);
	
}
elseif($jobs=="istrue"||$jobs=="isfalse")
{
	if(!$listdb){
		showerr("请选择一个");
	}
	$s=implode(",",$listdb);
	
	$query = $db->query("SELECT B.rid,B.iftrue FROM {$_pre}report B WHERE B.rid IN ($s)");
	while($rs = $db->fetch_array($query)){
		if($jobs=="istrue"&&$rs[iftrue]!=1){
			$db->query("UPDATE {$_pre}report SET iftrue=1 WHERE rid IN ($rs[rid])");
			add_user($rs[uid],abs($webdb[ReportMoney]));
			//add_user($rs[authoid],-abs($webdb[ReportMoney]));
		}elseif($jobs=="isfalse"&&$rs[iftrue]!=2){
			$db->query("UPDATE {$_pre}report SET iftrue=2 WHERE rid IN ($rs[rid])");
			add_user($rs[authoid],-abs($webdb[DelReportMoney]));
		}
	}
	refreshto("$FROMURL","操作成功",1);
}

?>