<?php
require("global.php");
/**/
require(Mpath.'inc/categories.php');
/**
*��ǩʹ��
**/
$ch=0;
$chdb[main_tpl]=getTpl("zh");
$ch_fid	= $ch_pagetype = 0;
$ch_module = 127;//ϵͳ�ض�ID����,ÿ��ϵͳ������ͬ
require(PHP168_PATH."inc/label_module.php");

$zhDB[title]="չ��Ƶ��";

//SEO
$titleDB[title]			= filtrate(strip_tags("$zhDB[title]  - $webdb[Info_webname]"));
$titleDB[keywords]		= filtrate(strip_tags("$fidDB[metakeywords]  $webdb[Info_metakeywords]"));
$titleDB[description]	= get_word(strip_tags($fidDB[descrip]),200).filtrate(strip_tags("$fidDB[metadescription] $webdb[Info_metadescription]"));


//��������
$province_fid=select_where("province","'postdb[province_id]'  onChange=\"choose_where(this.options[this.selectedIndex].value,'','')\" ");

////


/*
*�õ��Ƽ���չ����
*/

$query=$db->query("select * from {$_pre}zh_showroom where yz=1 order by levels desc,posttime desc limit 0,10");
while($rs=$db->fetch_array($query)){
	if($rs[picurl]) 	$rs[picurl]   =$webdb[www_url]."/".$webdb[updir]."/zh/".$rs[picurl];
	$rs[posttime] =date("Y-m-d",$rs[posttime]);
	$rs[area]     =$area_DB[name][$rs[province_id]]." ".$city_DB[name][$rs[city_id]];
	$showrommTopdb[]=$rs;
}
$bcategory->cache_read();
$bcategory->unsets();

require(Mpath."inc/head.php");
require(getTpl("zh"));
require(Mpath."inc/foot.php");



?>