<?php
require_once("global.php");
@include_once(Adminpath."../php168/companyData.php");
$linkdb=array("全部"=>"?showall=0","新申请"=>"?showall=1","提出撤销"=>"?cancel=1");

if(!$action){
	$rows=10;
	$page=intval($page);
	if(!$page)$page=1;
	$min=($page-1)*$rows;
	
	
	$where=" where 1";
	if($showall)$where.=" and yz=0 ";
	if($cancel) $where.=" and is_cancel=1";
	if($keyword)$where.=" and (companyName like('%$keyword%') or ag_name like('%$keyword%'))";
	$showpage=getpage("`{$_pre}agents`",$where,"?showall=$showall&cancel=$cancel&keyword=".urlencode($keyword),$rows);
	
	$query=$db->query("select * from {$_pre}agents $where order by posttime desc limit $min,$rows");
	while($rs=$db->fetch_array($query)){
		
		$rs[posttime]=date("Y-m-d H:i",$rs[posttime]);
		
		$rs[ag_cert]=$webdb[www_url]."/".$webdb[updir]."/{$Imgdirname}/ag_cert/$rs[uid]/".$rs[ag_cert];
		
		
		$rs[status]=$rs[yz]?"<font title='点击取消审核'>已审核</font><br>".date("Y-m-d H:i",$rs[yz_time]):"<font title='点击审核'>未审核</font>";
		if($rs[is_cancel]) $rs[cancel]="申请撤销中";
		
		
		
		$rs[contact_info]=unserialize($rs[contact_info]);
		$rs[ag_level]=$ag_level_array[$rs[ag_level]];
		$listdb[]=$rs;	
	}

	require("head.php");
	require("template/agents/list.htm");
	require("foot.php");
	
}elseif($action=='yz'){

	if(!$ag_id) showerr("请不要进行非法操作");
	$rsdb=$db->get_one("select * from {$_pre}agents where ag_id='$ag_id'");	
	if(!$rsdb) showerr("没有找到您要操作的项目");
	
	$yz=$rsdb[yz]?0:1;
	$yz_time=$yz?$timestamp:0;
	
	
	$db->query("update {$_pre}agents set yz='$yz',yz_time='$yz_time' where ag_id='$ag_id' limit 1");
	
	updateCompanyAgentIco($rsdb);
	
	//短信通知
	$array[touid]=$rsdb[uid];
	$array[fromuid]=0;
	$array[fromer]='系统消息';
	$array[title]='通知，您的代理商资格申请已经做处理';
	if($yz){
		$array[content]="{$rsdb[username]}您好,恭喜您!<br>您提交的$rsdb[ag_name] ({$ag_level_array[$rsdb[ag_level]]}) 已经通过审核；感谢您的参与。 ";
	}else{
		$array[content]="{$rsdb[username]}您好,抱歉!<br>您提交的$rsdb[ag_name] ({$ag_level_array[$rsdb[ag_level]]}) 已经取消审核资格；如果有需要，您可再次提交申请。 ";
	}
	if(function_exists('pm_msgbox')){
		pm_msgbox($array);
	}
	//回去
	refreshto($FROMURL,"操作成功",1);

}elseif($action=='del'){

	$rsdb=$db->get_one("select * from {$_pre}agents where ag_id='$ag_id'");	
	if(!$rsdb) showerr("没有找到您要操作的项目");
	
	//删除附件
	@unlink(PHP168_PATH.$webdb[updir]."/{$Imgdirname}/ag_cert/$rsdb[uid]/$rsdb[ag_cert]");

	//执行
    $db->query("delete from {$_pre}agents where ag_id='$ag_id' limit 1");
	//更新商家信息是否代理图标
	updateCompanyAgentIco($rsdb);
	//短信通知
	$array[touid]=$rsdb[uid];
	$array[fromuid]=0;
	$array[fromer]='系统消息';
	$array[title]='通知，您的代理商资格申请已经做处理';
	$array[content]="{$rsdb[username]}您好!<br>您提交的$rsdb[ag_name] ({$ag_level_array[$rsdb[ag_level]]}) 已经撤销；如果有需要，您可再次提交申请。 ";
	if(function_exists('pm_msgbox')){
		pm_msgbox($array);
	}
	//回去
	refreshto($FROMURL,"操作成功",1);
}
function updateCompanyAgentIco($rsdb){ //$rsdb参数，当前代理记录 agents表获得
	global $db,$_pre;
	$agents=$db->get_one("select count(*) as num from {$_pre}agents where uid='$rsdb[uid]' and yz=1");
	if($agents[num]<1){
		$is_agent=0;  //已经取消了代理图标
	}else{
		$is_agent=1;  //获得代理图标
	}
	$db->query("update `{$_pre}company` set is_agent='$is_agent' where uid='$rsdb[uid]' limit 1 ");
}
?>