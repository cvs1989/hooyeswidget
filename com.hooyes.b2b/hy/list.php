<?php

require(dirname(__FILE__)."/"."global.php");

//������
@include(Mpath."data/guide_fid.php");

$fidDB=$db->get_one("SELECT A.* FROM {$_pre}sort A WHERE A.fid='$fid'");
if(!$fidDB){
	showerr("��Ŀ������");
}


//SEO
$titleDB[title]	= $fidDB[name];


$rows=5;
if($page<1){
	$page=1;
}
$min=($page-1)*$rows;

if($Fid_db[0][$fid]){
	$SQL="SELECT SQL_CALC_FOUND_ROWS DISTINCT A.* FROM {$_pre}company A LEFT JOIN {$_pre}company_fid B ON A.uid=B.uid LEFT JOIN {$_pre}sort S ON B.fid=S.fid WHERE S.fup='$fid' ORDER BY A.rid DESC LIMIT $min,$rows";
}else{
	$SQL="SELECT SQL_CALC_FOUND_ROWS DISTINCT A.* FROM {$_pre}company A LEFT JOIN {$_pre}company_fid B ON A.uid=B.uid WHERE B.fid='$fid' ORDER BY A.rid DESC LIMIT $min,$rows";
}

$query = $db->query($SQL);

$RS=$db->get_one("SELECT FOUND_ROWS()");
$totalNum=$RS['FOUND_ROWS()'];
$showpage=getpage("","","list.php?fid=$fid",$rows,$totalNum);

while($rs = $db->fetch_array($query)){
	$rs[posttime]=date('Y-m-d',$rs[posttime]);
	$rs[picurl] && $rs[picurl]=tempdir($rs[picurl]);
	$listdb[]=$rs;
}
/**
*��ǩ
**/
$chdb[main_tpl]=getTpl('list');
$ch_fid	= intval($fidDB[config][label_list]);		//�Ƿ�������Ŀר�ñ�ǩ
$ch_pagetype = 2;									//2,Ϊlistҳ,3,Ϊbencandyҳ
$ch_module = $webdb[module_id]?$webdb[module_id]:99;//ϵͳ�ض�ID����,ÿ��ϵͳ������ͬ
$ch = 0;											//�������κ�ר��
require(ROOT_PATH."inc/label_module.php");


require(ROOT_PATH."inc/head.php");
require(getTpl('list'));
require(ROOT_PATH."inc/foot.php");

?>