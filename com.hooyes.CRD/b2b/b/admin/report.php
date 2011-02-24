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
			$rs[iftrue]="<font color=red>��ʵ</font>";
		}elseif($rs[iftrue]==2){
			$rs[iftrue]="<font color=blue>����ʵ</font>";
		}else{
			$rs[iftrue]="��ȷ��";
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
		showerr("��ѡ��һ��");
	}
	refreshto("$FROMURL","ɾ���ɹ�",1);
	
}
elseif($jobs=="istrue"||$jobs=="isfalse")
{
	if(!$listdb){
		showerr("��ѡ��һ��");
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
	refreshto("$FROMURL","�����ɹ�",1);
}

?>