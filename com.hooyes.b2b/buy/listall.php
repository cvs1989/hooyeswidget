<?php
require(dirname(__FILE__)."/global.php");

if($page<1){
	$page=1;
}
$rows=10;
$min=($page-1)*$rows;
if(!in_array($ordertype,array('hits','id','levelstime'))){
	$ordertype='id';
}
if($ordertype=='levelstime'){
	$SQL=" AND A.levels=1 ";
}else{
	$SQL=" ";
}


$query = $db->query("SELECT SQL_CALC_FOUND_ROWS A.*,C.renzheng,C.title AS companyname FROM {$_pre}content A LEFT JOIN {$pre}hy_company C ON A.uid=C.uid WHERE A.city_id='$city_id' $SQL ORDER BY A.$ordertype DESC LIMIT $min,$rows");
$RS=$db->get_one("SELECT FOUND_ROWS()");
$totalNum=$RS['FOUND_ROWS()'];
while($rs = $db->fetch_array($query)){
	$rs[picurl]=tempdir($rs[picurl]);
	$rs[url]=get_info_url($rs[id],$rs[fid],$rs[city_id]);
	$rs[posttime]=date("Y-m-d H:i",$rs[posttime]);
	$listdb[]=$rs;
}


$showpage=getpage("","","?ordertype=$ordertype",$rows,$totalNum);
/**
*为获取标签参数
**/
$chdb[main_tpl]=getTpl("listall");

/**
*标签
**/
$ch_fid	= 0;										//是否定义了栏目专用标签
$ch_pagetype = 4;									//2,为list页,3,为bencandy页
$ch_module = $webdb[module_id]?$webdb[module_id]:99;//系统特定ID参数,每个系统不能雷同
$ch = 0;											//不属于任何专题
require(ROOT_PATH."inc/label_module.php");

require(ROOT_PATH."inc/head.php");
require(getTpl("listall"));
require(ROOT_PATH."inc/foot.php");

?>