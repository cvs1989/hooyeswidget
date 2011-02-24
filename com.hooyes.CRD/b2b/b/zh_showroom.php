<?php
require("global.php");
require_once("bd_pics.php");
/**/




$sr_id=intval($sr_id);
if(!$sr_id)showerr("未找到您要访问的页");
//得到展会信息
$rsdb=$db->get_one("select * from {$_pre}zh_showroom where sr_id='$sr_id'");
if(!$rsdb[sr_id])showerr("您访问的内容可能已经删除.");
if(!$rsdb[yz] && $lfjuid!=$rsdb[uid] && !$web_admin) showerr("您访问的内容还在审核中；");
if($rsdb[picurl]) 	$rsdb[picurl]   =$webdb[www_url]."/".$webdb[updir]."/zh/".$rsdb[picurl];
$rsdb[area]     =$area_DB[name][$rsdb[province_id]]." ".$city_DB[name][$rsdb[city_id]];

$rsdb[posttime]=date("Y-m-d H:i",$rsdb[posttime]);
$rsdb[yz_time]=date("Y-m-d H:i",$rsdb[yz_time]);
foreach($rsdb as $key=>$val){
	$rsdb[$key]=nl2br($val);
}


if($morezh){
	//$jblistdb
	////列表
	
	$rows=$webdb[zhListNum]?$webdb[zhListNum]:20;
	$page=intval($page);
	if($page<1) $page=1;
	$min=($page-1)*$rows;
	$where=" WHERE yz=1 and showroom='$sr_id'";
	
	$query=$db->query("select * from {$_pre}zh_content  $where order by levels desc,posttime desc limit $min,$rows");
	
	while($rs=$db->fetch_array($query)){
		$rs[posttime] =date("Y-m-d",$rs[posttime]);
		$rs[starttime]=date("Y-m-d",$rs[starttime]);
		$rs[endtime]  =date("Y-m-d",$rs[endtime]);
		$rs[title_yuan]= $rs[title];
		
    	if($rs[picurl]) 	$rs[picurl]   =$webdb[www_url]."/".$webdb[updir]."/zh/".$rs[picurl];
		$rs[area]     =$area_DB[name][$rs[province_id]]." ".$city_DB[name][$rs[city_id]];
		$jblistdb[]=$rs;
	}
	
$showpage=getpage("{$_pre}zh_content",$where,"?sr_id=$sr_id&morezh=1",$rows);



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
//SEO
$zhDB[title]="展会频道 - 展览馆";
$titleDB[title]			= filtrate(strip_tags("$zhDB[title]  {$Fid_db[name][$rsdb[sid]]}-$rsdb[title] - $webdb[Info_webname]"));
$titleDB[keywords]		= filtrate(strip_tags("$fidDB[metakeywords]  $webdb[Info_metakeywords]"));
$titleDB[description]	= get_word(strip_tags($fidDB[descrip]),200).filtrate(strip_tags("$fidDB[metadescription] $webdb[Info_metadescription]"));

//更新点击次数
if(!$morezh){
	$db->query("update `{$_pre}zh_showroom` set `hits`=`hits`+1  where sr_id='$sr_id'");
}


//得到绑定的图片
$show_bd_pics=show_bd_pics("{$_pre}zh_showroom","  where sr_id='$sr_id'");

require(Mpath."inc/head.php");
require(getTpl("zh_showroom"));
require(Mpath."inc/foot.php");


?>