<?php
require_once("global.php");
$linkdb=array("全部认证信息"=>"?showall=1","新提交认证申请"=>"?showall=0");
$level=array(1=>"普通认证",2=>"高级认证(银牌级)",3=>"实力认证(金牌级)");
if(!$jobs){

	$rows=20;
	$page<1 && $page=1;
	$min=($page-1)*$rows;
	$where=" where 1";
	if(!$showall) $where.=" and yz=0";
	if($keyword) $where.=" and company_name like('%$keyword%') ";
	$showpage=getpage("`{$_pre}renzheng`",$where,"?showall=$showall&keyword=".urlencode($keyword),$rows);
	$query = $db->query("SELECT *  from  `{$_pre}renzheng` $where order by post_time desc LIMIT $min,$rows");
	
	while($rs = $db->fetch_array($query)){
		$rs[post_time]=date("m-d H:i",$rs[post_time]);
		$rs[yz_time]=$rs[yz]?date("m-d H:i",$rs[yz_time]):"-";
		$rs[level]=$level[$rs[level]];
		$rs[yz]=$rs[yz]?"已处理":"未处理";
		$listdb[]=$rs;
	}
	require("head.php");
	require("template/renzheng/list.htm");
	require("foot.php");
	
}elseif($jobs=="view"){
	
	if(!$viewuid) showerr("无效操作");
	$renzhengDB[1][yz_]='<br>未提交';
	$renzhengDB[2][yz_]='<br>未提交';
	$renzhengDB[3][yz_]='<br>未提交';
	$query=$db->query("select * from `{$_pre}renzheng` where uid='$viewuid' order by `level` asc");
	while($rs=$db->fetch_array($query)){
		if($rs[yz]){
			$rs[yz_]="<strong><font color=blue>已通过</font></strong> <br>(".date("Y-m-d,H:i:s",$rs[yz_time]).")";
		}else{
			$rs[yz_]="<font color='red'>新提交,未审核</font><br>(".date("Y-m-d,H:i:s",$rs[post_time]).")";
		}
		$rs[content]=unserialize($rs[content]);
		$rs[files]=unserialize($rs[files]);
		foreach($rs[files] as $key=>$file){
			if($file) $rs[files][$key]="<a href='".$webdb[www_url]."/".$file."' target='_blank'>点击查看...</a>";
			else $rs[files][$key]="";
		}
		
		$renzhengDB[$rs[level]]=$rs;
	}
	require("head.php");
	require("template/renzheng/view.htm");
	require("foot.php");
	
}elseif($jobs=="del"){

	if(!$id) showerr("无效操作");
	$rz=$db->get_one("select * from `{$_pre}renzheng` where id='$id'");
	
	$rzshang=$db->get_one("select * from `{$_pre}renzheng` where uid='$rz[uid]' and level='".(intval($rz[level])+1)."';");
	if($rzshang) showerr("此认证信息的用户意见申请了更高级别的认证，此认证删除失败");
	else{
		$db->query("update `{$_pre}company` set `renzheng`=`renzheng`-1  where uid='$rz[uid]'");
		///删除文件先
		$files=unserialize($rz[files]);
		foreach($files as $file){
			if(file_exists(PHP168_PATH."/".$file)) @unlink(PHP168_PATH."/".$file);
		}
		$db->query("delete from  `{$_pre}renzheng`  where id='$id'");
	}
	$array[touid]=$rz[uid];
	$array[fromuid]=0;
	$array[fromer]='系统消息';
	$array[title]='您的认证条件不足，管理员已经拒绝，认证资料已删除';
	$array[content]="{$rz[username]}您好!<br>您提交的$rz[company_name] {$level[$rz[level]]}的认证条件不足,管理员已经拒绝，认证资料已删除.如需要再次申请，请完善自己的内容，再次提交。谢谢! ";
	if(function_exists('pm_msgbox')){
	pm_msgbox($array);
	}
	refreshto("$FROMURL","删除成功",1);
	
	
}elseif($jobs=="ok"){
	
	if(!$id) showerr("无效操作");
	$rz=$db->get_one("select * from `{$_pre}renzheng` where id='$id'");
	if($rz[yz]) showerr("此认证申请已经确认过了，无须重复确认");
	$db->query("update `{$_pre}renzheng` set `yz`=1,`yz_time`='".time()."' where id='$id'");
	$db->query("update `{$_pre}company` set `renzheng`='$rz[level]' where uid='$rz[uid]'");
	$array[touid]=$rz[uid];
	$array[fromuid]=0;
	$array[fromer]='系统消息';
	$array[title]='恭喜您，您的认证申请已经通过';
	$array[content]="{$rz[username]}您好!<br>您提交的$rz[company_name] {$level[$rz[level]]}已经通过审核；感谢您的参与。 ";
	if(function_exists('pm_msgbox')){
	pm_msgbox($array);
	}
	refreshto("$FROMURL","操作成功",1);
}

?>