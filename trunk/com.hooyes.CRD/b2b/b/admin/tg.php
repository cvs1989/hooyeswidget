<?php
require_once("global.php");

//�����ж�

$linkdb=array("��Ӧ�ƹ�"=>"tg.php?ctype=1","���ƹ�"=>"tg.php?ctype=2");

$ctype = isset($ctype) ? intval($ctype) : 1;
$ctype = min(2, $ctype);

$tg_table = $ctype == 1 ? '_sell' : '_buy';

if(!$action){

	$rows=20;
	$page=intval($page);
	if($page<1) $page=1;
	$min=($page-1)*$rows;
	$where=" WHERE  1 ";

	if($keyword){ 
		$where.=" and A.tg_title like('%$keyword%') ";
	}
	$query=$db->query("select A.*,B.title as company_title,B.username from {$_pre}tg$tg_table A inner join {$_pre}company B on B.uid=A.tg_uid  $where order by A.posttime  desc limit $min,$rows");
	
	while($rs=$db->fetch_array($query)){
	
		if($rs[yz]){
			if(   ( $rs[tg_posttime]+$rs[tg_howlong]*60*60 ) > $timestamp){
				$rs[status]="�ƹ���";
				$rs[yz]    =$rs[yz]?"����":"<font color=red>δ��</font>";
			}else{
				$rs[status]="�ѽ���";
				$rs[yz]    ="";
			}
		}else{
			$rs[status]="&nbsp;";
			$rs[yz]    =$rs[yz]?"��ʾ":"<font color=red>δ��</font>";
		}
		
		$rs[posttime]=$rs[posttime]?date("Y-m-d H:i:s",$rs[posttime]):"";

		$rs[tg_posttime]=$rs[tg_posttime]?date("Y-m-d H:i:s",$rs[tg_posttime]):"&nbsp;";
		
		$listdb[]=$rs;
	}
	
	$showpage=getpage("{$_pre}tg$tg_table A left join {$_pre}company B on B.uid=A.tg_uid",$where,"?keyword=".urlencode($keyword)."&stype=$stype",$rows);

	

}elseif($action=='del'){
	
	if(!$tg_myids && $tg_myid){
		$tg_myids[]=$tg_myid;
	}
	foreach($tg_myids as $tg_myid){
		$db->query("delete from  {$_pre}tg$tg_table where tg_myid='$tg_myid'  ");
	}
	refreshto("?","�����ɹ�");
}elseif($action=='yz'){

	if(!$tg_myids && $tg_myid){
		$tg_myids[]=$tg_myid;
	}
	foreach($tg_myids as $tg_myid){
		$db->query("update  {$_pre}tg$tg_table set yz=1,tg_posttime='$timestamp' where  tg_myid='$tg_myid' ");
	
	}
	refreshto("?","�����ɹ�");
}elseif($action=='unyz'){

	if(!$tg_myids && $tg_myid){
		$tg_myids[]=$tg_myid;
	}
	foreach($tg_myids as $tg_myid){
		$db->query("update  {$_pre}tg$tg_table  set yz=0,tg_posttime='' where  tg_myid='$tg_myid' ");
	
	}
	refreshto("?","�����ɹ�");	
}elseif($action=='betch_over'){
	$db->query("delete from  {$_pre}tg$tg_table where (`tg_posttime`+(`tg_howlong`*60*60)) < $timestamp and  yz=1 ");
	refreshto("?","�����ɹ�");
}

//******************************************���
require("head.php");
require("template/tg/list.htm");
require("foot.php");
	

?>