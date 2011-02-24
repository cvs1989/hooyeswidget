<?php
require(dirname(__FILE__)."/"."global.php");
$rt=$db->get_one("select renzheng,title,uid,rid from `{$_pre}company` where uid='$lfjuid'");
if(!$rt){	//无商家信息
	showerr("抱歉，您还没有登记商家信息。<br>点击这里【<a href='$Murl/member/post_company.php?'  >登记商家</a>】");
}
	$webdb[vip_par_payfor]=$webdb[vip_par_payfor]?$webdb[vip_par_payfor]:50;
	$webdb[vip_min_long]=$webdb[vip_min_long]?$webdb[vip_min_long]:1;


if(!$action){
	$page=abs(intval($page));
	$page=$page?$page:1;
	$rows=10;
	$min=($page-1)*$rows;
	$where=" where uid='$lfjuid' ";
	$query=$db->query("select * from {$_pre}viphis $where order by posttime desc   limit $min,$rows");
	$showpage=getpage("{$_pre}viphis",$where,"?",$rows);
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
			$rs[pay_act] =!$rs[is_pay]?"<a href='$webdb[www_url]/do/olpay.php?vo_id=$rs[vo_id]&paymoeny=".strrev(base64_encode($rs[all_pay]))."' target=_blank><font color=blue>现在支付</font></a>":"";

		}elseif($rs[pay_type]=='offline'){
			$rs[pay_type]="线下支付";
			$rs[pay_act] =!$rs[is_pay]?"<font color=green>等待确认</font>":"";			

		}


		$listdb[]=$rs;
	}

}elseif($action=='payfor'){
	
	
	
}elseif($action=='save_payfor'){	
	
	//处理数据
	$per_payfor=$webdb[vip_par_payfor];
	$all_pay   =$per_payfor * $how_long;
	$all_time  =$webdb[vip_min_long] * $how_long;
	if($all_pay<1) showerr("最小支付金额为1元");
	if($all_time<1) showerr("最小续费时间为1个月");
	//执行
	
	$db->query("INSERT INTO `{$_pre}viphis` ( `vo_id` , `uid` , `username` , `companyName` , `rid` ,`posttime`,  `pay_type` , `is_pay` , `pay_time` , `how_long` , `per_payfor` ,`all_pay`, `all_time`,`start_time` , `end_time` , `remarks` , `contact_info` )VALUES ('', '$lfjuid', '$lfjid', '$rt[title]', '$rt[rid]','$timestamp',  '$pay_type', '0', '0', '$how_long', '$per_payfor','$all_pay','$all_time', '0', '0', '$remarks', '$contact_info');");
	
	//跳转
	refreshto("?","提交成功",1);

}elseif($action=="onlinepay_ok"){
	
	if($vo_id){
		$rsdb=$db->get_one("select * from `{$_pre}viphis` where vo_id='$vo_id'");
		if($rsdb[uid]!=$lfjuid) showerr("调试错误,订单隶属者有异议!");
		
		if($rsdb[end_time] || $rs[is_pay]) showerr("此VIP商家服务续费订单已经支付成功");

		//找到上次成功结束时间
		$open=$db->get_one("select * from `{$_pre}viphis` where uid=$rsdb[uid] and is_pay=1 and end_time>0 order by end_time desc limit 0,1");
		$start_time=$open[end_time]?($open[end_time]+1):$timestamp;
		$end_time=$start_time + $rsdb[all_time]*30*24*60*60;

		$db->query("update `{$_pre}viphis` set
		is_pay=1,
		pay_time='$timestamp',
		start_time='$start_time',
		end_time='$end_time'
		where vo_id='$vo_id'");

		$end_time=$open[end_time];
		$db->query("update `{$_pre}company` set is_vip='$end_time' where uid='$open[uid]' limit 1 ");

	}
	//refreshto("?","支付完毕",1);
	echo "<script>
	alert('支付成功，页面将关闭！');
	window.close();
	</script>";
}

require(dirname(__FILE__)."/"."head.php");
require(dirname(__FILE__)."/"."template/vip.htm");
require(dirname(__FILE__)."/"."foot.php");

?>