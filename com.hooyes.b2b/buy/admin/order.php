<?php
!function_exists('html') && exit('ERR');
ck_power('order');
if($job=='pay'){
	$db->query("UPDATE {$_pre}join SET ifpay='$ifpay' WHERE id='$id'");
}elseif($job=='send'){
	$db->query("UPDATE {$_pre}join SET ifsend='$ifsend' WHERE id='$id'");
}elseif($act=='del'){
	foreach($iddb AS $key=>$value){
		$db->query("DELETE FROM {$_pre}join WHERE id='$value'");
		$db->query("DELETE FROM {$_pre}content_2 WHERE id='$value'");
	}
}

$rows=15;

if(!$page)
{
	$page=1;
}
$min=($page-1)*$rows;

unset($listdb,$i);



$query = $db->query("SELECT SQL_CALC_FOUND_ROWS B.*,A.* FROM {$_pre}join A LEFT JOIN {$_pre}content_2 B ON A.id=B.id  ORDER BY A.id DESC LIMIT $min,$rows");

$RS=$db->get_one("SELECT FOUND_ROWS()");
$totalNum=$RS['FOUND_ROWS()'];
$showpage=getpage("","","$admin_path&job=$job",$rows,$totalNum);

while($rs = $db->fetch_array($query))
{
	$_erp=$Fid_db[tableid][$rs[fid]];
	$rs[shop]=$db->get_one("SELECT * FROM {$_pre}content$_erp WHERE id='$rs[cid]'");
	$rs[posttime]=date("m-d H:i",$rs[posttime]);
	if($job=='buyer'){
		$rs[pay]=$rs[ifpay]?"<A style='color:red;'>已付款</A>":"<A style='color:blue;'>未付款</A>";
		$rs[send]=$rs[ifsend]?"<A style='color:red;'>已发货</A>":"<A style='color:blue;'>未发货</A>";
	}else{
		$rs[pay]=$rs[ifpay]?"<A HREF='$admin_path&job=pay&id=$rs[id]&ifpay=0' style='color:red;'>已付款</A>":"<A HREF='$admin_path&job=pay&id=$rs[id]&ifpay=1' style='color:blue;'>未付款</A>";
		$rs[send]=$rs[ifsend]?"<A HREF='$admin_path&job=send&id=$rs[id]&ifsend=0' style='color:red;'>已发货</A>":"<A HREF='$admin_path&job=send&id=$rs[id]&ifsend=1' style='color:blue;'>未发货</A>";
	}

	$listdb[]=$rs;
}

get_admin_html('list');
?>