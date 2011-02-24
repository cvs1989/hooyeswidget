<?php
require_once("global.php");
$linkdb=array("全部商家"=>"?","未认证商家"=>"?level=0","普通认证"=>"?level=1","高级认证(银)"=>"?level=2","实力认证(金)"=>"?level=3");

$levelname=array(1=>"普通认证",2=>"高级认证(银牌级)",3=>"实力认证(金牌级)");


require_once("../php168/all_area.php");
require_once("../php168/all_city.php");
if(!$jobs){

	$rows=20;
	$page<1 && $page=1;
	$min=($page-1)*$rows;
	$where=" where 1";
	if($level!='') $where.=" and `renzheng`='$level'";
	if($keyword) $where.=" and `title` like('%$keyword%') ";
	$showpage=getpage("`{$_pre}company`",$where,"?level=$level&keyword=".urlencode($keyword),$rows);
	$query = $db->query("SELECT *  from  `{$_pre}company` $where order by rid desc LIMIT $min,$rows");
	
	while($rs = $db->fetch_array($query)){
		$rs[posttime]=date("m-d H:i",$rs[posttime]);
		$rs[level]=$levelname[$rs[renzheng]];
		$rs[yz]=$rs[yz]?"已审核":"<font color=red>未审核</font>";
		$rs[city_id]="{$area_DB[name][$city_DB[fup][$rs[city_id]]]}/{$city_DB[name][$rs[city_id]]}";
		$rs[levels]=$rs[levels]?"<font color=red>是</font>":"否";

		$rs[agent]=$rs[is_agent]?"<font color='red'>√</font>":"×";
		$rs[vip]=$rs[is_vip]?"<font color='red'>√</font>":"×";
		$listdb[]=$rs;
	}
	require("head.php");
	require("template/company/list.htm");
	require("foot.php");
	
}elseif($jobs=="view"){
	
	
	
}elseif($jobs=="del"){

	
	del_company($rid);

	refreshto("$FROMURL","删除成功",1);
	
}elseif($jobs=="levels"){	
	
	if(!$rid) showerr("未指定ID");
	$rz=$db->get_one("select * from `{$_pre}company` where rid='$rid'");
	if(!$rz[yz])  showerr("不能推荐没有审核的商家");
	$levels=$rz[levels]?0:1;
	$db->query("update `{$_pre}company` set `levels`='$levels' where rid='$rid'");
	refreshto("$FROMURL","操作成功",1);
	
}elseif($jobs=="yz"){
	
	if(!$rid) showerr("未指定ID");
	$rz=$db->get_one("select * from `{$_pre}company` where rid='$rid'");
	if($rz[yz]) $yz=0;
	else $yz=1;
	
	$db->query("update `{$_pre}company` set `yz`='$yz',`yztime`='".time()."' where rid='$rid'");
	
	$array[touid]=$rz[uid];
	$array[fromuid]=0;
	$array[fromer]='系统消息';
	if($yz){
		$array[title]='恭喜您，您登记的商家已经通过审核！您可以发布您的信息啦';
		$array[content]="{$rz[username]}您好!<br>您等级的商家已经通过审核, 您可以发布您的信息啦!,之前如果有下架的产品都已经上架啦。 ";
		$db->query("update `{$_pre}content_sell` set `yz`=1 where `uid`='$rz[uid]';");
		$db->query("update `{$_pre}content_buy` set `yz`=1 where `uid`='$rz[uid]';");
	}else{
		$array[title]='抱歉，您登记的商家已经被取消审核，现在不能使用！';
		$array[content]="{$rz[username]}您好!<br>您登记商家已经被取消审核, 现在不能使用,您发布的供求信息将全部下架! ";
		//本商家全部商品下架
		$db->query("update `{$_pre}content_sell` set `yz`=0 where `uid`='$rz[uid]';");
		$db->query("update `{$_pre}content_buy` set `yz`=0 where `uid`='$rz[uid]';");
	}
	if(function_exists('pm_msgbox')){
	pm_msgbox($array);
	}
	refreshto("$FROMURL","操作成功",1);
}


function del_company($rid){
	global $db,$webdb,$user_picdir,$_pre,$Imgdirname;
	
	if(!$rid) return false;

	$rz=$db->get_one("select * from `{$_pre}company` where rid='$rid'");
	$array[touid]=$uid=$rz[uid];
	$array[fromuid]=0;
	$array[fromer]='系统消息';
	$array[title]='您的商家页面操作提示';
	$array[content]="{$rz[username]}您好!<br>您的$rz[company_name] 主页内容由于某种原因,管理员已经删除.如有异议请联系管理员。谢谢! ";
	if(function_exists('pm_msgbox')){
		pm_msgbox($array);
	}
	@unlink(PHP168_PATH.$webdb[updir]."/$Imgdirname/ico/".$rz[picurl]);
	$db->query("delete from `{$_pre}company` where rid='$rid'");

	
	//页面配置文件
	$db->query("delete from `{$_pre}homepage` where rid='$rid'");
	$db->query("delete from `{$_pre}homepage_article` where rid='$rid'");
	$db->query("delete from `{$_pre}homepage_guestbook` where cuid='$uid'");
	$db->query("delete from `{$_pre}homepage_pic` where rid='$rid'");
	$db->query("delete from `{$_pre}homepage_picsort` where rid='$rid'");
	
	
	//删除图片
	$dir=$user_picdir."$uid/";
	if(is_dir($dir)){
		if ($handle = opendir($dir)) {
			while (false !== ($file = readdir($handle))) {
				if ($file != "." && $file != "..") {
					@unlink($dir.$file);
				}
			}
		}
		closedir($handle);	
		@rmdir($dir);
	}
	//删除供求
	$Imgdirname=$Imgdirname?$Imgdirname:"business";
	$query=$db->query("select id from `{$_pre}content_sell` where uid='$uid';");
		while($rs=$db->fetch_array($query)){
		$db->query("delete from `{$_pre}content_1` where id='$rs[id]'");
		$db->query("delete from `{$_pre}content_sell` where id='$rs[id]'");
		$db->query("delete from `{$_pre}sell_fid` where id='$rs[id]'");
		@unlink(PHP168_PATH.$webdb[updir]."/{$Imgdirname}/".$rs[picurl]);
		@unlink(PHP168_PATH.$webdb[updir]."/{$Imgdirname}/".$rs[picurl].".gif");
	}
	$query=$db->query("select id from `{$_pre}content_buy` where uid='$uid';");
		while($rs=$db->fetch_array($query)){
		$db->query("delete from `{$_pre}content_2` where id='$rs[id]'");
		$db->query("delete from `{$_pre}content_buy` where id='$rs[id]'");
		$db->query("delete from `{$_pre}buy_fid` where id='$rs[id]'");
		@unlink(PHP168_PATH.$webdb[updir]."/{$Imgdirname}/".$rs[picurl]);
		@unlink(PHP168_PATH.$webdb[updir]."/{$Imgdirname}/".$rs[picurl].".gif");
	}
	$query=$db->query("select rid from `{$_pre}company` where uid='$uid';");
		while($rs=$db->fetch_array($query)){
		$db->query("delete from `{$_pre}company_fid` where cid='$rs[rid]'");
		$db->query("delete from `{$_pre}content` where rid='$rs[rid]'");
	}


	//还有的东西忽略了
}


?>