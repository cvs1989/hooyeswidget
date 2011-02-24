<?php
require(dirname(__FILE__)."/"."global.php");


if($job=='zh'){

	$rows=20;
	$page=intval($page);
	if($page<1) $page=1;
	$min=($page-1)*$rows;
	$where=" WHERE uid='$lfjuid'";
	
	if($keyword) $where.=" and A.title like('%$keyword%') ";
	$tab || $tab=0;
	$yz=intval($tab)-1;
	if($yz>-1){
		$where.=" and A.yz=$yz";
	}
	$query=$db->query("select * from {$_pre}zh_content A $where order by A.posttime desc limit $min,$rows");
	
	while($rs=$db->fetch_array($query)){
		$rs[posttime] =date("Y-m-d",$rs[posttime]);
		$rs[starttime]=date("Y-m-d",$rs[starttime]);
		$rs[endtime]  =date("Y-m-d",$rs[endtime]);
		if($rs[yz_time]) $rs[yz_time]  ="(".date("Y-m-d",$rs[yz_time]).")";
		else  $rs[yz_time]="";
		$rs[title]    =get_word($rs[title_full]=$rs[title],60);
		$rs[title]    =$rs[color]?"<font color='$rs[color]'>$rs[title]</font>":$rs[title];
		$rs[title]    =$rs[levels]||$rs[levels_pic]?"<b>$rs[title]</b>":$rs[title];
		
		$rs[showroom_name]    =get_word($rs[showroom_name_full]=$rs[showroom_name],50);
		$rs[content]  =get_word($rs[content],200);
		if($rs[picurl]) 	$rs[picurl]   =$webdb[www_url]."/".$webdb[updir]."/zh/".$rs[picurl];
		$rs[area]     =$area_DB[name][$rs[province_id]]." ".$city_DB[name][$rs[city_id]];
		
		$rs[yz]       =!$rs[yz]?"<font color=red>未审核</font>":"已审核";
		
		$listdb[]=$rs;
	}
	$cksel[$tab]=' ck';
	$keyword2=urlencode($keyword);
	$showpage=getpage("{$_pre}zh_content A",$where,"?job=zh&keyword=$keyword2&tab=$tab",$rows);

}elseif($job=='zhdel'){
	if(!$zh_id)showerr("操作项目不明确");
	
	$rsdb=$db->get_one("select * from `{$_pre}zh_content` where zh_id='$zh_id'");
	
	if($rsdb[picurl]){
		@unlink(PHP168_PATH.$webdb[updir]."/zh/".$rsdb[picurl]);
	
	}		
	
	$db->query("delete from `{$_pre}zh_content` where zh_id='$zh_id'");
	$db->query("delete from `{$_pre}zh_content_1` where zh_id='$zh_id' ");
	refreshto("$FROMURL","删除成功",1);
	
}elseif($job=='zlg'){

	
	if(!$area_DB) @require_once(dirname(__FILE__)."/../php168/all_area.php");
	if(!$city_DB) @require_once(dirname(__FILE__)."/../php168/all_city.php");
	
	$page=intval($page);
	if($page<1) $page=1;
	$rows=20;
	$min=($page-1)*$rows;
	$where=" WHERE uid='$lfjuid'";
	
	if($keyword) $where.=" and A.title like('%$keyword%') ";
	
	$tab || $tab=0;
	$yz=intval($tab)-1;
	if($yz>-1){
		$where.=" and A.yz=$yz";
	}

	$query=$db->query("select * from {$_pre}zh_showroom A $where order by levels desc,posttime desc limit $min,$rows");
	while($rs=$db->fetch_array($query)){
		
		$rs[posttime] =date("Y-m-d",$rs[posttime]);
		$rs[yz_time] =date("Y-m-d H:i:s",$rs[yz_time]);
		
			
		$rs[title]    =$rs[levels]||$rs[levels_pic]?"<b>$rs[title]</b>":$rs[title];
		
     	$rs[yz]       =!$rs[yz]?"<font color=red>未审核</font>":"已审核";
		
		
		$rs[area]     =$area_DB[name][$rs[province_id]]." ".$city_DB[name][$rs[city_id]];
		
	
		$listdb[]=$rs;
	}
	
	$cksel[$tab]=' ck';
	$keyword2=urlencode($keyword);
	$showpage=getpage("{$_pre}zh_showroom A",$where,"?job=$job&keyword=$keyword2&tab=$tab",$rows);

}elseif($job=='zlgdel'){
	if(!$sr_id)showerr("操作项目不明确");
	
	$rsdb=$db->get_one("select * from `{$_pre}zh_showroom` where sr_id='$sr_id'");

	if($rsdb[picurl]){
		@unlink(PHP168_PATH.$webdb[updir]."/zh/".$rsdb[picurl]);
	
	}		
	
	$db->query("delete from `{$_pre}zh_showroom` where sr_id='$sr_id'");
	refreshto("$FROMURL","删除成功",1);
}

require(dirname(__FILE__)."/"."head.php");
require(dirname(__FILE__)."/"."template/zh.htm");
require(dirname(__FILE__)."/"."foot.php");


?>