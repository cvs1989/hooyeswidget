<?php
require("global.php");
/**/
require(Mpath.'inc/categories.php');

//SEO
$zhDB[title]="展会频道";
$titleDB[title]			= filtrate(strip_tags("$zhDB[title]  {$Fid_db[name][$sid]}- $webdb[Info_webname]"));
$titleDB[keywords]		= filtrate(strip_tags("$fidDB[metakeywords]  $webdb[Info_metakeywords]"));
$titleDB[description]	= get_word(strip_tags($fidDB[descrip]),200).filtrate(strip_tags("$fidDB[metadescription] $webdb[Info_metadescription]"));


//搜索栏用
$province_fid=select_where("province","'postdb[province_id]'  onChange=\"choose_where(this.options[this.selectedIndex].value,'','')\" ");

////列表
	if(!$sid && !$province_id && !$opentime && !$keyword) showerr("请先选择搜索条件");
	$rows=$webdb[zhListNum]?$webdb[zhListNum]:20;
	$page=intval($page);
	if($page<1) $page=1;
	$min=($page-1)*$rows;
	$where=" WHERE A.yz=1";
	if($sid) $where .=" and A.sid='$sid' ";
	if($province_id) $where.=" and A.province_id='$province_id'";
	if($city_id) $where.=" and A.city_id='$city_id'";
	if($opentime){
	$starttime = explode("-",$opentime);
	$yue_chu=mktime(0,0,0,$starttime[1],1,$starttime[0]);
	$yue_di=mktime(0,0,0,$starttime[1],31,$starttime[0]);
	 $where.=" and (A.starttime > '$yue_chu' and A.starttime < '$yue_di')";
	}
	if($keyword) $where.=" and (A.title like('%$keyword%')    or   A.showroom_name like('%$keyword%'))";
	if($ispic) $where.=" and A.picurl <> '' ";
	
	if($levels){ 
		if($levels=='pic') $where.=" A.levels_pic=1";
		else $where.=" A.levels=1";
	}
	
	
	$query=$db->query("select * from {$_pre}zh_content A $where order by A.levels desc,posttime desc limit $min,$rows");
	
	while($rs=$db->fetch_array($query)){
		$rs[posttime] =date("Y-m-d",$rs[posttime]);
		$rs[starttime]=date("Y-m-d",$rs[starttime]);
		$rs[endtime]  =date("Y-m-d",$rs[endtime]);
		$rs[title]    =get_word($rs[title_full]=$rs[title],60);

		$rs[title]    =$rs[color]?"<font color='$rs[color]'>$rs[title]</font>":$rs[title];
		$rs[title]    =$rs[levels]||$rs[levels_pic]?"<b>$rs[title]</b>":$rs[title];

		$rs[showroom_name]    =get_word($rs[showroom_name_full]=$rs[showroom_name],50);

		$rs[content]  =get_word($rs[content],200);
		if($rs[picurl]) 	$rs[picurl]   =$webdb[www_url]."/".$webdb[updir]."/zh/".$rs[picurl];
		$rs[area]     =$area_DB[name][$rs[province_id]]." ".$city_DB[name][$rs[city_id]];
		$listdb[]=$rs;
	}
	
$showpage=getpage("{$_pre}zh_content A",$where,"?sid=$sid&keyword=".urlencode($keyword),$rows);




/**
*标签使用
**/
$ch=0;
$ch_fid	= $ch_pagetype = 0;
$ch_module = $webdb[module_id]?$webdb[module_id]:127;//系统特定ID参数,每个系统不能雷同

require(PHP168_PATH."inc/label_module.php");
$bcategory->cache_read();
$bcategory->unsets();


require(Mpath."inc/head.php");
require(getTpl("zh_list"));
require(Mpath."inc/foot.php");


?>