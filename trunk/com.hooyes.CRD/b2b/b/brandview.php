<?php
require("global.php");

if($jobs=='show'){
	
	foreach($Brand_db[0] as $key=>$val){
		$bid=$key;
		break;
	}
}
if(intval($bid)<1) showerr("非法访问");

$rsdb=$db->get_one("select * from `{$_pre}brand` where bid='$bid'");
if(!$rsdb[yz]){
	if($lfjuid!=$rsdb[uid]){
		showerr("未验证的信息，不能查看");
	}
}
if($Brand_db[fbid][$bid]){
	$fname=$Brand_db[name][$Brand_db[fbid][$bid]]."旗下品牌";
}

//类目
$rsdb[vs_fid]=explode(",",$rsdb[vs_fid]);



$rsdb[description]=nl2br($rsdb[description]);


//视频
$rsdb[video]=unserialize($rsdb[video]);
if($rsdb[video][url]){
	$rsdb[video_show]=get_video($rsdb[video]);
}else{
	$rsdb[video_show]="<br><center><img src='$Murl/images/default/novideo.jpg' border=0></center>";
}


//----代理商$agents_company

$query=$db->query("select  A.uid,A.ag_name,B.title as companyName from {$_pre}agents A left join {$_pre}company B on B.rid=A.rid where A.brand_id='$bid' and A.yz=1 order by B.is_vip desc");
while($rs=$db->fetch_array($query)){
	$agents_company[]=$rs;
}



/**
*标签使用
**/
$ch=0;
$chdb[main_tpl]=getTpl("brandview");
$ch_fid	= $ch_pagetype = 0;
$ch_module = 127;//系统特定ID参数,每个系统不能雷同
require(PHP168_PATH."inc/label_module.php");


//seo
$titleDB[title]			= filtrate(strip_tags("{$Brand_db[name][$bid]} 品牌 - $webdb[Info_webname]"));
$titleDB[keywords]		= filtrate(strip_tags("$rsdb[metakeywords]  $webdb[Info_metakeywords]"));
$titleDB[description]	= get_word(strip_tags($rsdb[metadescription]),200).filtrate(strip_tags("$webdb[Info_metadescription]"));

$db->query("update `{$_pre}brand`  set hits=hits+1 where bid='$bid'");

//输出
require(Mpath."inc/head.php");

if($rsdb[template] && file_exists(PHP168_PATH.$rsdb[template])){
	require(PHP168_PATH.$rsdb[template]);
}else{
	require(getTpl("brandview"));
}
require(Mpath."inc/foot.php");


//静态
if($rsdb[is_html] && !$jobs)
{
	$content=ob_get_contents();
	ob_end_clean();
	ob_start();
	if(!trim($rsdb[html_name])){
		$rsdb[html_name]=$tt="/brand/".$timestamp.".htm";
		$db->query("update {$_pre}brand  set html_name='$tt' where bid='$bid'");
	}
	makepath(dirname(PHP168_PATH.$rsdb[html_name]));
	write_file(PHP168_PATH.$rsdb[html_name],$content);
	echo "$content";
}


function brand_fid_list($bid,$fid,$rows=8,$ctype=1){
	global $db,$webdb,$_pre,$Mdomain,$Fid_db,$area_DB,$city_DB;
	
	$rows=$rows<5?5:$rows;
	$where=" where A.yz=1 and C.yz=1";
	if($bid){
		$where.=" and A.bid='$bid' ";
	}
	if($fid){
		$where.=" and A.fid='$fid' ";
	}
	
	$query=$db->query("SELECT 
	A.title,A.posttime,A.id,A.fid,A.uid,
	C.title as owner_name,C.is_agent,C.is_vip,C.city_id,C.province_id,C.renzheng
	FROM {$_pre}content_sell A 
	left join {$_pre}company C on C.uid=A.uid
	$where  ORDER BY C.is_vip desc,C.is_agent desc,A.posttime desc 
	limit 0,$rows "); 
	
	//left join {$_pre}content_$ctype B on B.id=A.id 
	//B.my_price,B.quantity_type,

	while($rs=$db->fetch_array($query)){
		$rs[title]=get_word($rs[full_title]=$rs[title],$leng);
		$rs[posttime]=date("Y-m-d",$rs[full_time]=$rs[posttime]);
		

		$rs[owner_name_short]=get_word($rs[owner_name],30);
		$rs[services]=get_services($rs);
		$rs[services]=$rs[services]?$rs[services]:"&nbsp;普通商家";
		$rs[quantity_type]=$rs[quantity_type]?$rs[quantity_type]:"件";
		
		if($rs[my_price]){
				$rs[my_price]=formartprice($rs[my_price]);
				$rs[my_price]="<strong><font color=#FF3300>$rs[my_price]</font></strong>元/$rs[quantity_type]";
		}else{
				$rs[my_price]='价格面议';
		}

		$rs[area]="{$area_DB[name][$city_DB[fup][$rs[city_id]]]}/{$city_DB[name][$rs[city_id]]}";

		if($webdb[bencandyIsHtml] && $rs[htmlname]){
			$rs[url]=$webdb[www_url]."/".$rs[htmlname];
		}else{
			$rs[url]=$Mdomain."/bencandy.php?fid=$rs[fid]&id=$rs[id]";
		}
		
		$listdb[]=$rs;
	}

	return $listdb;

}



function get_video($video){

	if($video[type]=='media'){
		
		$str='<embed src="'.$video[url].'" width="250" height="190" style="margin-top:5px;"></embed>';
	
	}elseif($video[type]=='flash'){
		
		$str='<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=7,0,19,0" width="250" height="190" style="margin-top:5px;">
  <param name="movie" value="'.$video[url].'" />
  <param name="quality" value="high" />
  <embed src="remarks" quality="high" pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash" width="260" height="195"></embed>
</object>';
	
	}

	return $str."<div>".get_word(htmlspecialchars($video[remarks]),150,1)."</div>";


}
?>