<?php
!function_exists('html') && exit('ERR');

if($job=='listjf')
{
	$SQL='';
	if($fid){
		$SQL=" AND B.fid='$fid' ";
	}

	$rows=50;
	if($page<1){
		$page=1;
	}
	$min=($page-1)*$rows;

	$showpage=getpage("{$pre}jfabout B LEFT JOIN `{$pre}jfsort` S ON B.fid=S.fid","WHERE 1 $SQL","index.php?lfj=jfadmin&job=listjf&","$rows");

	$query = $db->query("SELECT B.*,S.name AS fname FROM {$pre}jfabout B LEFT JOIN `{$pre}jfsort` S ON B.fid=S.fid WHERE 1 $SQL ORDER BY list DESC LIMIT $min,$rows");
	while($rs = $db->fetch_array($query)){
		$listdb[]=$rs;
	}

	require(dirname(__FILE__)."/"."template/head.htm");
	require(dirname(__FILE__)."/"."template/jfadmin/menu.htm");
	require(dirname(__FILE__)."/"."template/jfadmin/listjf.htm");
	require(dirname(__FILE__)."/"."template/foot.htm");
}
elseif($action=="deljf")
{
	foreach( $idb AS $key=>$value){
		$db->query("DELETE FROM `{$pre}jfabout` WHERE id='$value'");
	}
	jump("删除成功","index.php?lfj=jfadmin&job=listjf",1);
}
elseif($action=="addjf")
{
	$db->query("INSERT INTO `{$pre}jfabout` ( `fid` , `title` , `content`, `list` ) VALUES ( '$fid', '$title', '$content', '$list' )");
	jump("添加成功","index.php?lfj=jfadmin&job=listjf&fid=$fid",1);
}
elseif($job=="addjf")
{
	$selectfid="<select name='fid'>";
	$query = $db->query("SELECT * FROM `{$pre}jfsort` ORDER BY list DESC");
	while($rs = $db->fetch_array($query)){
		$selectfid.="<option value='$rs[fid]'>$rs[name]</option>";
	}
	$selectfid.="</select>";
	require(dirname(__FILE__)."/"."template/head.htm");
	require(dirname(__FILE__)."/"."template/jfadmin/menu.htm");
	require(dirname(__FILE__)."/"."template/jfadmin/addjf.htm");
	require(dirname(__FILE__)."/"."template/foot.htm");
}
elseif($job=="editjf")
{
	$rsdb=$db->get_one("SELECT * FROM `{$pre}jfabout` WHERE id='$id'");
	$selectfid="<select name='fid'>";
	$query = $db->query("SELECT * FROM `{$pre}jfsort` ORDER BY list DESC");
	while($rs = $db->fetch_array($query)){
		$ck=$rs[fid]==$rsdb[fid]?' selected ':'';
		$selectfid.="<option value='$rs[fid]' $ck>$rs[name]</option>";
	}
	$selectfid.="</select>";
	require(dirname(__FILE__)."/"."template/head.htm");
	require(dirname(__FILE__)."/"."template/jfadmin/menu.htm");
	require(dirname(__FILE__)."/"."template/jfadmin/addjf.htm");
	require(dirname(__FILE__)."/"."template/foot.htm");
}
elseif($action=="editjf")
{
	$db->query("UPDATE `{$pre}jfabout` SET `fid`='$fid',`title`='$title',`content`='$content' WHERE id='$id'");
	jump("添加成功","index.php?lfj=jfadmin&job=listjf&fid=$fid",1);
}
elseif($job=='listsort')
{
	$query = $db->query("SELECT * FROM `{$pre}jfsort` ORDER BY list DESC");
	while($rs = $db->fetch_array($query)){
		$listdb[]=$rs;
	}
	require(dirname(__FILE__)."/"."template/head.htm");
	require(dirname(__FILE__)."/"."template/jfadmin/menu.htm");
	require(dirname(__FILE__)."/"."template/jfadmin/listsort.htm");
	require(dirname(__FILE__)."/"."template/foot.htm");
}
elseif($action=="addsort")
{
	$name=filtrate($name);
	$db->query("INSERT INTO {$pre}jfsort (name) VALUES ('$name') ");
	jump("创建成功","$FROMURL");
}
elseif($action=="delsort")
{
	$db->query("DELETE FROM {$pre}jfsort WHERE fid='$fid'");
	jump("删除成功","$FROMURL");
}
elseif($job=="editsort")
{
	$rsdb=$db->get_one("SELECT * FROM {$pre}jfsort WHERE fid='$fid'");
	require(dirname(__FILE__)."/"."template/head.htm");
	require(dirname(__FILE__)."/"."template/jfadmin/menu.htm");
	require(dirname(__FILE__)."/"."template/jfadmin/editsort.htm");
	require(dirname(__FILE__)."/"."template/foot.htm");
}
elseif($action=='editsort')
{
	$db->query("UPDATE {$pre}jfsort SET name='$postdb[name]' WHERE fid='$fid'");
	jump("修改成功","index.php?lfj=jfadmin&job=listsort",1);
}
elseif($action=='sortorder')
{
	foreach( $order AS $key=>$value){
		$db->query("UPDATE {$pre}jfsort SET list='$value' WHERE fid='$key'");
	}
	jump("修改成功","index.php?lfj=jfadmin&job=listsort",1);
}