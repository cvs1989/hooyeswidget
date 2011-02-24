<?php
!function_exists('html') && exit('ERR');

if($job=="list"&&$Apower[report_list])
{
	$rows=50;
	if($page<1){
		$page=1;
	}
	$min=($page-1)*$rows;
	$SQL="B.id>0";
	$showpage=getpage("{$pre}report B LEFT JOIN {$pre}article A ON A.aid=B.aid","WHERE $SQL","index.php?lfj=$lfj&job=$job",$rows);

 	$query=$db->query("SELECT A.*,B.aid,B.uid AS r_id,B.name,B.content,B.posttime,B.ip,B.yz AS r_yz,B.id,B.type FROM {$pre}report B LEFT JOIN {$pre}article A ON A.aid=B.aid WHERE $SQL ORDER BY B.id DESC LIMIT $min,$rows");
	while($rs=$db->fetch_array($query))
	{
		if(!$rs[title]&&$_rs=get_one_article($rs[aid])){
			$rs=$_rs+$rs;
		}		
		$rs[ischeck]=$rs[yz]?"<A HREF='?lfj=$lfj&action=work&jobs=unyz&aid=$rs[aid]'>已审核</A>":"<A HREF='?lfj=$lfj&action=work&jobs=yz&aid=$rs[aid]' style='color:blue;'>未审核</A>";
		$rs[iscom]=$rs[levels]?"<A HREF='?lfj=$lfj&action=work&jobs=com&aid=$rs[aid]&levels=0' style='color:red;'>已推荐</A>":"<A HREF='?lfj=$lfj&action=work&jobs=com&aid=$rs[aid]&levels=1'>未推荐</A>";
		$rs[title2]=urlencode($rs[title]);
		$rs[posttime]=date("Y-m-d",$rs[posttime]);
		$rs[pages]<1 && $rs[pages]=1;
		$rs[yz]==2 && $rs[fname]="<A HREF='?lfj=$lfj&action=work&jobs=getRubbish&aid=$rs[aid]' style='color:blue;'>回收站</A>";
		if($rs[r_yz]==1){
			$rs[r_yz]="<A HREF='?lfj=$lfj&action=yz&yz=0&id=$rs[id]' style='color:red;'>已阅</A>";
		}else{
			$rs[r_yz]="<A HREF='?lfj=$lfj&action=yz&yz=1&id=$rs[id]' style='color:;'>未阅</A>";
		}
		$listdb[$rs[aid]]=$rs;
	}
	require(dirname(__FILE__)."/"."head.php");
	require(dirname(__FILE__)."/"."template/report/list.htm");
	require(dirname(__FILE__)."/"."foot.php");
}
elseif($action=='yz'&&$Apower[report_list])
{
	$db->query("UPDATE {$pre}report SET yz='$yz' WHERE id='$id'");
	jump("操作成功","$FROMURL",0);
}
elseif($action=='delete')
{
	foreach( $listdb AS $key=>$id){
		$db->query("DELETE FROM {$pre}report WHERE id='$id'");
	}
	jump("操作成功","$FROMURL",0);
}
elseif($job=='view')
{
	$db->query("UPDATE {$pre}report SET yz='1' WHERE id='$id'");
	$rsdb=$db->get_one("SELECT A.*,B.uid AS r_id,B.aid,B.name,B.content,B.posttime AS Rtime,B.ip,B.yz AS r_yz,B.id,B.type FROM {$pre}report B LEFT JOIN {$pre}article A ON A.aid=B.aid WHERE B.id='$id'");
	if(!$rsdb[title]&&$_rs=get_one_article($rsdb[aid])){
		$rsdb=$_rs+$rsdb;
	}
	$rsdb[Rtime]=date("Y-m-d H:i",$rsdb[Rtime]);
	$rsdb[posttime]=date("Y-m-d H:i",$rsdb[posttime]);
	require(dirname(__FILE__)."/"."head.php");
	require(dirname(__FILE__)."/"."template/report/view.htm");
	require(dirname(__FILE__)."/"."foot.php");
}