<?php
require(dirname(__FILE__)."/"."global.php");

//快捷
$rsdb=$db->get_one("select renzheng,is_vip,host from {$_pre}company where uid='$lfjuid'");

if($rsdb[host]){
	
	$myonly='http://'.$HTTP_HOST."/";
	$re = "/http:\/\/.*?([^\.]+\.(com\.cn|org\.cn|net\.cn|[^\.]+))\//";
	if(preg_match($re, $myonly)){
			preg_match_all($re, $myonly, $res,PREG_PATTERN_ORDER);
			$TOP_DOMAIN = $res[1][0];
	}

	if($TOP_DOMAIN){
		$rsdb[host]="http://".$rsdb[host].".".$TOP_DOMAIN;
	}else{
		$rsdb[host]="";
	}
}
//数据统计

$rt=$db->get_one("SELECT COUNT(*) AS num FROM {$_pre}content_sell WHERE uid='$lfjuid'");
$data[sell_num]=$rt[num];

$rt=$db->get_one("SELECT COUNT(*) AS num FROM {$_pre}content_buy WHERE uid='$lfjuid'");
$data[buy_num]=$rt[num];


$rt=$db->get_one("SELECT COUNT(*) AS num FROM {$_pre}collection WHERE uid='$lfjuid'");
$data[coll_num]=$rt[num];

$rt=$db->get_one("SELECT COUNT(*) AS num FROM {$_pre}comments WHERE cuid='$lfjuid'");
$data[commtome_num]=$rt[num];

$rt=$db->get_one("SELECT COUNT(*) AS num FROM {$_pre}comments WHERE uid='$lfjuid'");
$data[commtoother_num]=$rt[num];

$rt=$db->get_one("SELECT COUNT(*) AS num FROM {$_pre}form1 WHERE owner_uid='$lfjuid'");
$data[form1_num]=$rt[num];

$rt=$db->get_one("SELECT COUNT(*) AS num FROM {$_pre}form1 WHERE from_uid='$lfjuid'");
$data[myform1_num]=$rt[num];

$rt=$db->get_one("SELECT COUNT(*) AS num FROM {$_pre}form2 WHERE owner_uid='$lfjuid'");
$data[form2_num]=$rt[num];

$rt=$db->get_one("SELECT COUNT(*) AS num FROM {$_pre}form2 WHERE from_uid='$lfjuid'");
$data[myform2_num]=$rt[num];


$rt=$db->get_one("SELECT COUNT(*) AS num FROM {$_pre}hr_jobs WHERE uid='$lfjuid'");
$data[jobs_num]=$rt[num];

$rt=$db->get_one("SELECT COUNT(*) AS num FROM {$_pre}hr_td WHERE jobs_uid='$lfjuid'");
$data[resume_num]=$rt[num];

$rt=$db->get_one("SELECT COUNT(*) AS num FROM {$_pre}zh_content WHERE uid='$lfjuid'");
$data[zh_num]=$rt[num];

$rt=$db->get_one("SELECT COUNT(*) AS num FROM {$_pre}zh_showroom WHERE uid='$lfjuid'");
$data[zlg_num]=$rt[num];






require(dirname(__FILE__)."/"."head.php");
require(dirname(__FILE__)."/"."template/main.htm");
require(dirname(__FILE__)."/"."foot.php");

?>