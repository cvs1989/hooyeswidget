<?php
require("global.php");
/**
*标签使用
**/
$ch=0;
$chdb[main_tpl]=getTpl("newslist");
$ch_fid	= $ch_pagetype = 0;
$ch_module = 127;//系统特定ID参数,每个系统不能雷同
require(PHP168_PATH."inc/label_module.php");
//SEO
$titleDB[title]			= filtrate(strip_tags("资讯中心 - $webdb[Info_webname]"));
$titleDB[keywords]		= filtrate(strip_tags("$fidDB[metakeywords]  $webdb[Info_metakeywords]"));
$titleDB[description]	= get_word(strip_tags($fidDB[descrip]),200).filtrate(strip_tags("$fidDB[metadescription] $webdb[Info_metadescription]"));

//********************************************************
function get_best_company_fromarea($area,$rows=5,$best=1,$length=32,$order=" order by posttime desc "){
	global $db,$_pre,$area_DB,$city_DB;
	
	$SQL=" where yz=1";
	if($best){
		$SQL.=" and levels='1'";
		$order=" order by renzheng desc";
	}
	if($area){
		$SQL.=" and province_id='$area'";
	}
	$query=$db->query("SELECT rid,title,fname,fid,posttime,picurl,levels,uid,city_id FROM {$_pre}company  $SQL $order  limit 0,$rows");
	while( $rs=$db->fetch_array($query) ){
		
		$rs[title]=get_word($rs[full_title]=$rs[title],$length);
		$rs[posttime]=date("Y-m-d",$rs[full_time]=$rs[posttime]);
		$rs[posttime_short]=date("m/d",$rs[full_time]);
		$rs[fname]=get_word($rs[full_fname]=$rs[fname],8,0);
		$rs[cityname]=$city_DB[name][$rs[city_id]];
		if($rs[picurl]){
			$rs[picurl]=getimgdir($rs[picurl],3);
		}
		$listdb[]=$rs;
	}
	return $listdb;
}

//********************************************************

require(Mpath."inc/head.php");
require(getTpl("newslist"));
require(Mpath."inc/foot.php");

?>