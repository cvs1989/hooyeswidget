<?php
require("global.php");
/**/



$zhDB[title]="展会频道 展览馆";

//SEO
$titleDB[title]			= filtrate(strip_tags("$zhDB[title]  - $webdb[Info_webname]"));
$titleDB[keywords]		= filtrate(strip_tags("$fidDB[metakeywords]  $webdb[Info_metakeywords]"));
$titleDB[description]	= get_word(strip_tags($fidDB[descrip]),200).filtrate(strip_tags("$fidDB[metadescription] $webdb[Info_metadescription]"));


//搜索栏用
$province_fid=select_where("province","'postdb[province_id]'  onChange=\"choose_where(this.options[this.selectedIndex].value,'','')\" ");

////


/*
*得到推荐的展览馆
*/

$query=$db->query("select * from {$_pre}zh_showroom where yz=1 order by levels desc,posttime desc limit 0,10");
while($rs=$db->fetch_array($query)){
	if($rs[picurl]) 	$rs[picurl]   =$webdb[www_url]."/".$webdb[updir]."/zh/".$rs[picurl];
	$rs[posttime] =date("Y-m-d",$rs[posttime]);
	$rs[area]     =$area_DB[name][$rs[province_id]]." ".$city_DB[name][$rs[city_id]];
	$showrommTopdb[]=$rs;
}
//真实列表//////////////////////////////////////////////////////
$page=intval($page);
if($page<1) $page=1;
$rows=$webdb[zhListNum]?$webdb[zhListNum]:20;
$min=($page-1)*$rows;
$where=" WHERE A.yz=1";
if($province_id) $where.=" and A.province_id='$province_id'";
if($city_id) $where.=" and A.city_id='$city_id'";
if($keyword) $where.=" and A.title like('%$keyword%') ";

$query=$db->query("select * from {$_pre}zh_showroom A $where order by levels desc,posttime desc limit $min,$rows");
while($rs=$db->fetch_array($query)){
	if($rs[picurl]) 	$rs[picurl]   =$webdb[www_url]."/".$webdb[updir]."/zh/".$rs[picurl];
	$rs[posttime] =date("Y-m-d",$rs[posttime]);
	$rs[area]     =$area_DB[name][$rs[province_id]]." ".$city_DB[name][$rs[city_id]];
	$listdb[]=$rs;
}

$showpage=getpage("{$_pre}zh_showroom A",$where,"?province_id=$province_id&city_id=$city_id&keyword=".urlencode($keyword),$rows);


require(Mpath."inc/head.php");
require(getTpl("zh_showroomlist"));
require(Mpath."inc/foot.php");



?>