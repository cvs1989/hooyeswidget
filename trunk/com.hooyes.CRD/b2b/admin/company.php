<?php
!function_exists('html') && exit('ERR');
if($job=="list"&&$Apower[company_list])
{
	if($T=="noyz"){
		$SQL=" WHERE D.yz=0 AND D.uid!=0 ";
	}elseif($T=="yz"){
		$SQL=" WHERE D.yz!=0 AND D.uid!=0 ";
	}else{
		$SQL=" WHERE 1 ";
	}

	if($groupid){
		$SQL.=" AND D.groupid='$groupid' ";
	}
	
	if($keywords&&$type){
		if($type=='username'){
			$SQL.=" AND BINARY D.username LIKE '%$keywords%' ";
		}elseif($type=='uid'){
			$SQL.=" AND D.uid='$keywords' ";
		}elseif($type=='cpname'){
			$SQL.=" AND BINARY M.cpname='%$keywords%' ";
		}
	}
	$select_group=select_group("groupid",$groupid,"index.php?lfj=member&job=list&T=$T");

	if(!$page){
		$page=1;
	}
	$rows=20;
	$min=($page-1)*$rows;
	$showpage=getpage("{$pre}memberdata_1 M LEFT JOIN {$pre}memberdata D ON M.uid=D.uid","$SQL","index.php?lfj=$lfj&job=$job&type=$type&T=$T&keywords=$keywords&groupid=$groupid",$rows);
	$query=$db->query("SELECT D.*,M.* FROM {$pre}memberdata_1 M LEFT JOIN {$pre}memberdata D ON M.uid=D.uid $SQL ORDER BY M.uid DESC LIMIT $min,$rows ");
	while($rs=$db->fetch_array($query)){
		$rs[lastvist]=$rs[lastvist]?date("Y-m-d H:i:s",$rs[lastvist]):'';

		if($rs[grouptype]==1){
			$rs[yz]="<A HREF='index.php?lfj=$lfj&action=yz&uid_db[0]=$rs[uid]&T=noyz' style='color:red;' onclick=\"$rs[alert]\"><img alt='已通过审核,点击取消审核' src='../member/images/check_yes.gif' border=0></A>";
		}else{
			$rs[yz]="<A HREF='index.php?lfj=$lfj&action=yz&uid_db[0]=$rs[uid]&T=yz' style='color:blue;' onclick=\"$rs[alert]\"><img alt='还没通过审核,点击通过审核' src='../member/images/check_no.gif' border=0></A>";
		}
		$listdb[]=$rs;
	}
	require(dirname(__FILE__)."/"."head.php");
	require(dirname(__FILE__)."/"."template/company/menu.htm");
	require(dirname(__FILE__)."/"."template/company/list.htm");
	require(dirname(__FILE__)."/"."foot.php");
}
elseif($job=="editmember"&&$Apower[company_list])
{
	$cpDB=$db->get_one("SELECT D.*,C.* FROM {$pre}memberdata_1 C LEFT JOIN {$pre}memberdata D ON C.uid=D.uid WHERE C.uid='$uid'");

	$cptype[$cpDB[cptype]]=' selected ';

	require(dirname(__FILE__)."/"."head.php");
	require(dirname(__FILE__)."/"."template/company/editmember.htm");
	require(dirname(__FILE__)."/"."foot.php");
}
elseif($action=="editmember"&&$Apower[company_list])
{
	$rsdb=$db->get_one("SELECT D.*,C.* FROM {$pre}memberdata_1 C LEFT JOIN {$pre}memberdata D ON C.uid=D.uid WHERE C.uid='$uid'");
	if(!$rsdb){
		showmsg("此企业资料不存在 ");
	}
	$db->query("UPDATE {$pre}memberdata_1 SET cpname='$cpname',cplogo='$cplogo',cptype='$cptype',cptrade='$cptrade',cpproduct='$cpproduct',cpcity='$cpcity',cpfoundtime='$cpfoundtime',cpfounder='$cpfounder',cpmannum='$cpmannum',cpmoney='$cpmoney',cpcode='$cpcode',cppermit='$cppermit',cpweb='$cpweb',cppostcode='$cppostcode',cptelephone='$cptelephone',cpfax='$cpfax',cpaddress='$cpaddress',cplinkman='$cplinkman',cpmobphone='$cpmobphone',cpqq='$cpqq',cpmsn='$cpmsn' WHERE uid='$uid'");

	jump("修改成功","index.php?lfj=$lfj&job=editmember&uid=$uid");
	
}
elseif($action=="delete"&&$Apower[company_list])
{
	$db->query("UPDATE {$pre}memberdata SET grouptype='0' WHERE uid='$uid' ");
	$db->query("DELETE FROM {$pre}memberdata_1 WHERE uid='$uid' ");
	jump("删除成功","index.php?lfj=$lfj&job=list");
}
elseif($action=="yz"&&$Apower[company_list])
{
	if($T=='yz'){
		$yz=1;
	}else{
		$yz=-1;
	}
	foreach( $uid_db AS $key=>$uid){
		$db->query("UPDATE {$pre}memberdata SET grouptype='$yz' WHERE uid='$uid' ");
	}
	jump('处理完毕',$FROMURL,0);
}
?>