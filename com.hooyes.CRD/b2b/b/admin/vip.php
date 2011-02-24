<?php
require_once("global.php");
@include_once(Adminpath."../php168/companyData.php");
$linkdb=array("在线支付"=>"?pay_type=online","线下支付"=>"?pay_type=offline");

if(!$action){
	$rows=10;
	$page=intval($page);
	if(!$page)$page=1;
	$min=($page-1)*$rows;
	
	$where=" where 1";
	if($keyword)$where.=" and companyName like('%$keyword%')";
	$showpage=getpage("{$_pre}viphis",$where,"?pay_type=$pay_type&keyword=".urlencode($keyword),$rows);
	

	$query=$db->query("select * from {$_pre}viphis $where order by posttime desc limit $min,$rows");
	while($rs=$db->fetch_array($query)){
		
		
		$rs[all_how_long]=$rs[how_long]*$webdb[vip_min_long];
		$rs[pay_status]=$rs[is_pay]?"已付款":"未支付";
		$rs[vip_status]=$rs[start_time]?"服务中":"未开通";
		if($timestamp > $rs[end_time] && $rs[start_time] && $rs[end_time] ){
			$rs[vip_status]="服务完毕";
		}

		
		//时间
		$rs[posttime]=date('Y-m-d H:i:s',$rs[posttime]);
		$rs[pay_time]=$rs[pay_time]?date("Y-m-d H:i:s",$rs[pay_time]):"";
		$rs[start_time]=$rs[start_time]?date("Y-m-d H:i:s",$rs[start_time]):"";
		$rs[end_time]=$rs[end_time]?date("Y-m-d H:i:s",$rs[end_time]):"";
		//支付操作
		if($rs[pay_type]=='online'){
			$rs[pay_type]="在线支付";
		}elseif($rs[pay_type]=='offline'){
			$rs[pay_type]="<font color='blue'>线下支付</font>";
			$rs[pay_act] =!$rs[is_pay]?"<font color=green>等待确认</font>":"";			
			$rs[enter_pay]=!$rs[is_pay]?"<font color=green>点击收款</font>":"";	
		}


		$listdb[]=$rs;	
	}

	require("head.php");
	require("template/vip/list.htm");
	require("foot.php");

}elseif($action=='offline_pay'){
	
	$rsdb=$db->get_one("select * from {$_pre}viphis where vo_id='$vo_id'");	
	if(!$rsdb) showerr("没有找到您要操作的项目");
	if($rsdb[end_time])showerr("此订单的VIP服务已经在服务中");

	//操作订单
	$open=$db->get_one("select * from `{$_pre}viphis` where uid=$rsdb[uid] and is_pay=1 and end_time>0 order by end_time desc limit 0,1");
	$start_time=$open[end_time]?($open[end_time]+1):$timestamp;
	$end_time=$start_time + $rsdb[all_time]*30*24*60*60;

	$db->query("update `{$_pre}viphis` set
		is_pay=1,
		pay_time='$timestamp',
		start_time='$start_time',
		end_time='$end_time'
		where vo_id='$vo_id'");

	//更新,其实可以省略;为了统一，再调用下吧
	updateCompanyVipIco($rsdb);
	
	//短信
	$array[touid]=$rsdb[uid];
	$array[fromuid]=0;
	$array[fromer]='系统消息';
	$array[title]='通知，您的VIP商家服务续费订单操作通知';
	$array[content]="{$rsdb[username]}您好!<br>您在".date('Y-m-d H:i:s',$open[posttime])."提交的VIP商家服务续费订单已经确认支付,订单完成；如果有需要，您可再次提交。 ";
	if(function_exists('pm_msgbox')){
		pm_msgbox($array);
	}


	refreshto($FROMURL,"操作成功",1);

}elseif($action=='del'){

	$rsdb=$db->get_one("select * from {$_pre}viphis where vo_id='$vo_id'");	
	if(!$rsdb) showerr("没有找到您要操作的项目");
	
	//执行
    $db->query("delete from {$_pre}viphis where vo_id='$vo_id' limit 1");
	
	//更新
	updateCompanyVipIco($rsdb);
	//短信通知
	$array[touid]=$rsdb[uid];
	$array[fromuid]=0;
	$array[fromer]='系统消息';
	$array[title]='通知，您的VIP商家服务续费订单操作通知';
	$array[content]="{$rsdb[username]}您好!<br>您在".date('Y-m-d H:i:s',$rs[posttime])."提交的VIP商家服务续费订单已经撤销；如果有需要，您可再次提交。 ";
	if(function_exists('pm_msgbox')){
		pm_msgbox($array);
	}
	//回去
	refreshto($FROMURL,"操作成功",1);
}
function updateCompanyVipIco($rsdb){ //$rsdb参数，当前代理记录 viphis表获得
	global $db,$_pre;
	$vip=$db->get_one("select * from `{$_pre}viphis` where uid=$rsdb[uid] and is_pay=1 and end_time>0 order by end_time desc limit 0,1");
	$end_time=$vip[end_time];
	$db->query("update `{$_pre}company` set is_vip='$end_time', host = '' where uid='$rsdb[uid]' limit 1 ");
}
?>