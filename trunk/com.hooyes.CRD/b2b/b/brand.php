<?php
require("global.php");
require(Mpath."inc/categories.php");

$bcategory->cache_read();

$cat = $bcategory->categories[$fid];

//����ó�Ʒ��
if($fid){
	$fids = $bcategory->get_children_ids($fid);
	$oneFid_db_brand = array();
	
	foreach($Brand_db[ownbyfid] as $bid=>$ownbyfid){
		$ownbyfid=explode(",",$ownbyfid);
		
		if(array_intersect($ownbyfid, $fids))
			$oneFid_db_brand[] = $bid;
	}
}else{
	
	foreach($Brand_db[ownbyfid] as $bid=>$ownbyfid){
		$ownbyfid=explode(",",$ownbyfid);
		
		foreach($ownbyfid as $key){
			if($bcategory->categories[$key]['fup']){
				$data = array_shift($bcategory->get_parents($key));
				
				$key = $data['fid'];
			}
			
			$Fid_db_brand[$key][]=$bid;
			$Fid_db_brand[$key] = array_unique($Fid_db_brand[$key]);
		}
		
	}
}

//�õ��Ƽ�Ʒ��
$j=1;
foreach($Brand_db[0] as $bid=>$val){
	if($j>10) break;
	if($Brand_db[level][$bid]){
		$rs[bid]=$bid;
		$levellistdb[]=$rs;
		$i++;
	}
}

//�õ����ŵ�Ʒ��
$query=$db->query("SELECT bid,hits FROM {$_pre}brand WHERE yz=1 ORDER BY  hits DESC limit 0,10");
while($rs=$db->fetch_array($query)){
		$hotlistdb[]=$rs;
}

//�õ����µ�Ʒ��
$query=$db->query("SELECT bid,hits FROM {$_pre}brand WHERE yz=1 ORDER BY  yz_time DESC limit 0,10");
while($rs=$db->fetch_array($query)){
		$new_brand[]=$rs;
}
/**
*��ǩʹ��
**/
$ch=0;
$chdb[main_tpl]=getTpl("brand");
$ch_fid	= $ch_pagetype = 0;
$ch_module = 127;//ϵͳ�ض�ID����,ÿ��ϵͳ������ͬ
require(PHP168_PATH."inc/label_module.php");

//SEO

if($fid){
	$fid_name=$Fid_db[name][$fid];
}

$titleDB[title]			= filtrate(strip_tags("$fid_name Ʒ�� - $webdb[Info_webname]"));
$titleDB[keywords]		= filtrate(strip_tags("$webdb[Info_metakeywords]"));
$titleDB[description]	= get_word(strip_tags($fidDB[descrip]),200).filtrate(strip_tags("$webdb[Info_metadescription]"));

$bcategory->unsets();
//���
require(Mpath."inc/head.php");
require(getTpl("brand"));
require(Mpath."inc/foot.php");



?>