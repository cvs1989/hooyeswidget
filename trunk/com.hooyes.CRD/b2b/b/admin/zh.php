<?php
require_once("global.php");

//功能判断

$linkdb=array(
"展会列表"=>"?",
"未审核展会"=>"zh.php?type=0",
"已审核展会"=>"zh.php?type=1",
"推荐展会"=>"zh.php?levels=1",

"展览馆管理"=>"zh.php?action=zlglist",
"未审核展览馆"=>"zh.php?type=0&action=zlglist",
"已审核展览馆"=>"zh.php?type=1&action=zlglist",
"推荐展览馆"=>"zh.php?levels=1&action=zlglist",
);

if(!$action || $action=='zhlist'){
		
	$rows=20;
	$page=intval($page);
	if($page<1) $page=1;
	$min=($page-1)*$rows;
	$where=" WHERE 1";
	if($sid) $where .=" and A.sid='$sid' ";

	if($type!='') {
		$where.=" and A.yz=$type";
		$typeurl="&type=$type";
	}
	if($levels!='') $where.=" and A.levels=1";
	
	if($keyword) $where.=" and A.title like('%$keyword%') ";
		
	$query=$db->query("select * from {$_pre}zh_content A $where order by A.posttime desc limit $min,$rows");
	
	while($rs=$db->fetch_array($query)){
		$rs[posttime] =date("Y-m-d",$rs[posttime]);
		$rs[starttime]=date("Y-m-d",$rs[starttime]);
		$rs[endtime]  =date("Y-m-d",$rs[endtime]);
		$rs[title]    =get_word($rs[title_full]=$rs[title],60);
		$rs[title]    =$rs[color]?"<font color='$rs[color]'>$rs[title]</font>":$rs[title];
		$rs[title]    =$rs[levels]||$rs[levels_pic]?"<b>$rs[title]</b>":$rs[title];
		
		$rs[levels_a] =$rs[levels]?"zhunlevels":"zhlevels";
		$rs[levels]   =$rs[levels]?"<font color=red>已推荐</font>":"未推荐";
		$rs[yz_a]     =$rs[yz]?"zhunyz":"zhyz";
		$rs[yz]       =!$rs[yz]?"<font color=red>未审核</font>":"已审核";
		
		$rs[showroom_name]    =get_word($rs[showroom_name_full]=$rs[showroom_name],50);
		$rs[content]  =get_word($rs[content],200);
		if($rs[picurl]) 	$rs[picurl]   =$webdb[www_url]."/".$webdb[updir]."/zh/".$rs[picurl];
		$rs[area]     =$area_DB[name][$rs[province_id]]." ".$city_DB[name][$rs[city_id]];
		
		if($rs[rid]) $rs[home]=$Mdomain."/".$homepage."/?uid=".$rs[uid];
		else $rs[home]="#";
		
		$listdb[]=$rs;
	}

	
	$showpage=getpage("{$_pre}zh_content A",$where,"?sid=$sid&province_id=$province_id&city_id=$city_id&opentime=$opentime&ispic=$ispic&levels=$levels&keyword=".urlencode($keyword).$typeurl,$rows);

	require("head.php");
	require("template/zh/zhlist.htm");
	require("foot.php");
	
}elseif($action=='zhyz'){
	
	if($zh_id)  $listdb['only']=$zh_id;
	if($listdb)	$zh_ids=implode(',',$listdb);
	if($zh_ids){
		
		$db->query("update `{$_pre}zh_content` set yz=1 ,yz_time='".time()."' where zh_id in($zh_ids) ");
		refreshto("?action=zhlist","操作成功");
	}else{
		showerr("操作项目不明确");
	}	
	
	
}elseif($action=='zhunyz'){
	
	if($zh_id)  $listdb['only']=$zh_id;
	if($listdb)	$zh_ids=implode(',',$listdb);
	if($zh_ids){
		
		$db->query("update `{$_pre}zh_content` set yz=0 ,yz_time='' where zh_id in($zh_ids) ");
		refreshto("?action=zhlist","操作成功");
	}else{
		showerr("操作项目不明确");
	}	


}elseif($action=='zhlevels'){

	if($zh_id)  $listdb['only']=$zh_id;
	if($listdb)	$zh_ids=implode(',',$listdb);
	if($zh_ids){
		
		$db->query("update `{$_pre}zh_content` set levels=1  where zh_id in($zh_ids) ");
		refreshto("?action=zhlist","操作成功");
	}else{
		showerr("操作项目不明确");
	}	

}elseif($action=='zhunlevels'){

	if($zh_id)  $listdb['only']=$zh_id;
	if($listdb)	$zh_ids=implode(',',$listdb);
	if($zh_ids){
		
		$db->query("update `{$_pre}zh_content` set levels=0  where zh_id in($zh_ids) ");
		refreshto("?action=zhlist","操作成功");
	}else{
		showerr("操作项目不明确");
	}	

}elseif($action=='zhcolor'){
	
	
	if($zh_id)  $listdb['only']=$zh_id;
	if($listdb)	$zh_ids=implode(',',$listdb);
	if($zh_ids){
		
		$db->query("update `{$_pre}zh_content` set color='$color'  where zh_id in($zh_ids) ");
		refreshto("?action=zhlist","操作成功");
	}else{
		showerr("操作项目不明确");
	}	

}elseif($action=='zhdel'){


	if(!$zh_id)showerr("操作项目不明确");
	
	$rsdb=$db->get_one("select * from `{$_pre}zh_content` where zh_id='$zh_id'");
	if(!$rsdb)showerr("操作项目无法查找");
	if($rsdb[picurl]){
		@unlink($webdb[updir]."/zh/".$rsdb[picurl]);
	
	}
		
	$array[touid]=$rsdb[uid];
	$array[fromuid]=0;
	$array[fromer]='系统消息';
	$array[title]='您的一条展会信息已经被管理员删除';
	$array[content]="{$rsdb[username]}您好!<br>您在".date("Y-m-d H:i",$rsdb[posttime])."提交的$rsdb[title] 已经被管理员删除。详情请联系管理员 ";
	if(function_exists('pm_msgbox')){
		pm_msgbox($array);
	}
	$db->query("delete from `{$_pre}zh_content` where zh_id='$zh_id'");
	$db->query("delete from `{$_pre}zh_content_1` where zh_id='$zh_id' ");
	refreshto("$FROMURL","删除成功",1);
	

}elseif($action=='zlglist'){

	if(!$area_DB) @require_once("../php168/all_area.php");
	if(!$city_DB) @require_once("../php168/all_city.php");
	
	$page=intval($page);
	if($page<1) $page=1;
	$rows=20;
	$min=($page-1)*$rows;
	$where=" WHERE 1";
	
	if($type!='') {
		$where.=" and A.yz=$type";
		$typeurl="&type=$type";
	}
	if($levels!='') $where.=" and A.levels=1";

	if($province_id) $where.=" and A.province_id='$province_id'";
	if($city_id) $where.=" and A.city_id='$city_id'";
	if($keyword) $where.=" and A.title like('%$keyword%') ";
	
	$query=$db->query("select * from {$_pre}zh_showroom A $where order by levels desc,posttime desc limit $min,$rows");
	while($rs=$db->fetch_array($query)){
		
		$rs[posttime] =date("Y-m-d",$rs[posttime]);
		
		
		$rs[title]    =$rs[levels]||$rs[levels_pic]?"<b>$rs[title]</b>":$rs[title];
		
		$rs[levels_a] =$rs[levels]?"zlgunlevels":"zlglevels";
		$rs[levels]   =$rs[levels]?"<font color=red>已推荐</font>":"未推荐";
		$rs[yz_a]     =$rs[yz]?"zlgunyz":"zlgyz";
		$rs[yz]       =!$rs[yz]?"<font color=red>未审核</font>":"已审核";
		
		
		$rs[area]     =$area_DB[name][$rs[province_id]]." ".$city_DB[name][$rs[city_id]];
		
		if($rs[rid]) $rs[home]=$Mdomain."/".$homepage."/?uid=".$rs[uid];
		else $rs[home]="#";
		$listdb[]=$rs;
	}
	
	$showpage=getpage("{$_pre}zh_showroom A",$where,"?action=$action&province_id=$province_id&city_id=$city_id&keyword=".urlencode($keyword).$typeurl,$rows);
	
	require("head.php");
	require("template/zh/zlglist.htm");
	require("foot.php");
	
}elseif($action=='zlgyz'){
	
	if($sr_id)  $listdb['only']=$sr_id;
	if($listdb)	$sr_ids=implode(',',$listdb);
	if($sr_ids){
		
		$db->query("update `{$_pre}zh_showroom` set yz=1 ,yz_time='".time()."' where sr_id in($sr_ids) ");
		refreshto("?action=zlglist","操作成功");
	}else{
		showerr("操作项目不明确");
	}	
	
	
}elseif($action=='zlgunyz'){
	
	
	if($sr_id)  $listdb['only']=$sr_id;
	if($listdb)	$sr_ids=implode(',',$listdb);
	if($sr_ids){
		
		$db->query("update `{$_pre}zh_showroom` set yz=0 ,yz_time='' where sr_id in($sr_ids) ");
		refreshto("?action=zlglist","操作成功");
	}else{
		showerr("操作项目不明确");
	}	


}elseif($action=='zlglevels'){

	
	if($sr_id)  $listdb['only']=$sr_id;
	if($listdb)	$sr_ids=implode(',',$listdb);
	if($sr_ids){
		
		$db->query("update `{$_pre}zh_showroom` set levels=1  where sr_id in($sr_ids) ");
		refreshto("?action=zlglist","操作成功");
	}else{
		showerr("操作项目不明确");
	}	

}elseif($action=='zlgunlevels'){

	
	if($sr_id)  $listdb['only']=$sr_id;
	if($listdb)	$sr_ids=implode(',',$listdb);
	if($sr_ids){
		
		$db->query("update `{$_pre}zh_showroom` set levels=0  where sr_id in($sr_ids) ");
		refreshto("?action=zlglist","操作成功");
	}else{
		showerr("操作项目不明确");
	}	
}elseif($action=='zlgdel'){


	if(!$sr_id)showerr("操作项目不明确");
	
	$rsdb=$db->get_one("select * from `{$_pre}zh_showroom` where sr_id='$sr_id'");
	if(!$rsdb)showerr("操作项目无法查找");
	if($rsdb[picurl]){
		@unlink($webdb[updir]."/zh/".$rsdb[picurl]);
	
	}
	
	
	$array[touid]=$rsdb[uid];
	$array[fromuid]=0;
	$array[fromer]='系统消息';
	$array[title]='您的一条展览馆信息已经被管理员删除';
	$array[content]="{$rsdb[username]}您好!<br>您在".date("Y-m-d H:i",$rsdb[posttime])."提交的展览馆:$rsdb[title] 已经被管理员删除。详情请联系管理员 ";
	if(function_exists('pm_msgbox')){
		pm_msgbox($array);
	}
	$db->query("delete from `{$_pre}zh_showroom` where sr_id='$sr_id'");
	refreshto("$FROMURL","删除成功",1);
	

}



//******************************************输出

?>