<?php
require("global.php");
require_once("bd_pics.php");
/**/




$zh_id=intval($zh_id);
if(!$zh_id)showerr("未找到您要访问的页");
//得到展会信息
$rsdb=$db->get_one("select * from {$_pre}zh_content where zh_id='$zh_id'");
if(!$rsdb[zh_id])showerr("您访问的内容可能已经删除.");

if(!$rsdb[yz]){
	if($lfjuid!=$rsdb[uid] && !$web_admin) showerr("您访问的内容还在审核中；");
}

if($rsdb[picurl]) 	$rsdb[picurl]   =$webdb[www_url]."/".$webdb[updir]."/zh/".$rsdb[picurl];

$rsdb[starttime]=date("Y-m-d",$rsdb[starttime]);
$rsdb[endtime]=date("Y-m-d",$rsdb[endtime]);
$rsdb[posttime]=date("Y-m-d H:i",$rsdb[posttime]);
$rsdb[yz_time]=date("Y-m-d H:i",$rsdb[yz_time]);
foreach($rsdb as $key=>$val){
	$rsdb[$key]=nl2br($val);
}

//看看是否有商家页面
if($rsdb[rid]){
	$userdb=$db->get_one("select * from {$_pre}company where rid='$rsdb[rid]'");
	//renzheng 
	$userdb[company_name]="<a href='$Mdomain/homepage.php?uid=$userdb[uid]' target='_blank'><strong>$userdb[title]</strong></a>";
	if($userdb[renzheng]){
	$userdb[company_name].="<br>".getrenzheng($userdb[renzheng]);
	}
}
//的大自定义项目

$query=$db->query("select * from {$_pre}zh_content_1 where zh_id='$zh_id' order by `ind` asc");
while($rs=$db->fetch_array($query)){
	$diy_data[$rs[ind]][title]=$rs[title];
	$diy_data[$rs[ind]][content]=$rs[content];
}
//得到展览馆

$showroomdata=$db->get_one("select * from {$_pre}zh_showroom where sr_id='$rsdb[showroom]' limit 1 ");
if($showroomdata[picurl]) 	$showroomdata[picurl]   =$webdb[www_url]."/".$webdb[updir]."/zh/".$showroomdata[picurl];
$showroomdata[area]     =$area_DB[name][$showroomdata[province_id]]." ".$city_DB[name][$showroomdata[city_id]];
//SEO
$zhDB[title]="展会频道";
$titleDB[title]			= filtrate(strip_tags("$zhDB[title]  {$Fid_db[name][$rsdb[sid]]} $rsdb[title] - $webdb[Info_webname]"));
$titleDB[keywords]		= filtrate(strip_tags("$fidDB[metakeywords]  $webdb[Info_metakeywords]"));
$titleDB[description]	= get_word(strip_tags($fidDB[descrip]),200).filtrate(strip_tags("$fidDB[metadescription] $webdb[Info_metadescription]"));
$db->query("update `{$_pre}zh_content` set `hits`=`hits`+1  where zh_id='$zh_id'");



//得到绑定的图片
$show_bd_pics=show_bd_pics("{$_pre}zh_content","  where zh_id='$zh_id'");

require(Mpath."inc/head.php");
require(getTpl("zhshow"));
require(Mpath."inc/foot.php");


?>