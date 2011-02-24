<?php
require("global.php");
require_once(PHP168_PATH."inc/function.ad.php");

if(!$lfjid){
	showerr("你还没登录");
}

$linkdb=array(
			"广告位列表"=>"?job=list",
			"我购买的广告"=>"?job=mylist"
			);

$array_adtype=array(
					"word"=>"文字广告",
					"pic"=>"图片广告",
					"swf"=>"FLASH广告",
					"code"=>"代码广告",
					"duilian"=>"对联广告"
					);

if($job=='list')
{
	$query = $db->query("SELECT * FROM {$pre}ad WHERE ifsale=1 ORDER BY list DESC");
	while($rs = $db->fetch_array($query))
	{
		if($rs[autoyz]){
			$rs[_ifyz]='不必审核';
		}else{
			$rs[_ifyz]='需要审核';
		}

		if($_r=$db->get_one("SELECT * FROM {$pre}ad_user WHERE u_endtime>'$timestamp' AND id='$rs[id]'"))
		{
			$_r[u_endtime]=date("Y-m-d H:i",$_r[u_endtime]);
			$rs[state]="{$_r[u_endtime]}过期";
			$rs[alert]="alert('直到{$_r[u_endtime]}以后才可购买');return false;";
			$rs[color]="#ccc;";
		}
		elseif($_r=$db->get_one("SELECT * FROM {$pre}ad_user WHERE u_yz=0 AND id='$rs[id]'"))
		{
			$_r[u_endtime]=date("Y-m-d H:i",$_r[u_endtime]);
			$rs[state]="已有人购买,等待审核当中";
			$rs[alert]="alert('已有人购买,你现在不能购买');return false;";
			$rs[color]="#ccc;";
		}
		else
		{
			$rs[state]="现在可购买";
			$rs[color]="red;";
		}
		$listdb[]=$rs;
	}
	$lfjdb[money]=intval(get_money($lfjdb[uid]));
	require(dirname(__FILE__)."/"."head.php");
	require(dirname(__FILE__)."/"."template/buyad/list.htm");
	require(dirname(__FILE__)."/"."foot.php");
}

elseif($job=='buy')
{
	$_r=$db->get_one("SELECT * FROM {$pre}ad_user WHERE u_endtime>'$timestamp' AND id='$id'");
	
	if($_r)
	{
		$_r[u_endtime]=date("Y-m-d H:i",$_r[u_endtime]);
		showerr("直到{$_r[u_endtime]}才可购买");
	}

	$rsdb=$db->get_one("SELECT * FROM {$pre}ad WHERE id='$id'");
	if(!$rsdb){
		showerr("广告位不存在!");
	}
	if($rsdb[autoyz]){
		$rsdb[_ifyz]='不需管理员审核,直接显示';
	}else{
		$rsdb[_ifyz]='不能立即显示,需要等待管理员审核';
	}
	require(dirname(__FILE__)."/"."head.php");
	require(dirname(__FILE__)."/"."template/buyad/buy.htm");
	require(dirname(__FILE__)."/"."foot.php");
}

elseif($action=="buy")
{
	if($atc_day<1)
	{
		showerr("购买的广告不能小于一天");
	}
	$_r=$db->get_one("SELECT * FROM {$pre}ad_user WHERE u_endtime>'$timestamp' AND id='$id'");
	if($_r)
	{
		$_r[u_endtime]=date("Y-m-d H:i",$_r[u_endtime]);
		showerr("直到{$_r[u_endtime]}才可购买");
	}
	
	$rsdb=$db->get_one("SELECT * FROM {$pre}ad WHERE id='$id'");

	$totalmoneycard=$u_moneycard=$rsdb[moneycard]*$atc_day;
	/*
	$lfjdb[moneycard]=intval($lfjdb[moneycard]);
	if($totalmoneycard>$lfjdb[moneycard])
	{
		showerr("你的金币不足$totalmoneycard,你仅有金币:$lfjdb[moneycard]");
	}
	*/
	$lfjdb[money]=intval(get_money($lfjdb[uid]));
	if($totalmoneycard>$lfjdb[money])
	{
		showerr("你的积分不足$totalmoneycard,你仅有积分:$lfjdb[money]");
	}
	$cdb=unserialize($rsdb[adcode]);
	if($rsdb[type]=='pic'){
		//自动截图,把图片截小
		$imgdb=getimagesize(PHP168_PATH."$webdb[updir]/$atc_img");
		if( $imgdb[0]>$cdb[width] || $imgdb[1]>$cdb[height] ){
			gdpic(PHP168_PATH."$webdb[updir]/$atc_img",PHP168_PATH."$webdb[updir]/$atc_img",$cdb[width],$cdb[height],array('fix'=>1));
		}
	}
	
	if($rsdb[type]=='word'){
		$cdb[word]=filtrate($atc_word);
		$cdb[linkurl]=filtrate($atc_url);
	}elseif($rsdb[type]=='pic'){
		$cdb[picurl]=filtrate($atc_img);
		$cdb[linkurl]=filtrate($atc_url);
	}elseif($rsdb[type]=='swf'){
		$cdb[flashurl]=filtrate($atc_url);
	}elseif($rsdb[type]=='duilian'){
		$cdb[l_src]=filtrate($l_src);
		$cdb[l_link]=filtrate($l_link);
		$cdb[r_src]=filtrate($r_src);
		$cdb[r_link]=filtrate($r_link);
	}
	$cdb[code]=stripslashes($atc_code);
	$u_code=addslashes(serialize($cdb));

	$u_yz=$rsdb[autoyz];
	if($u_yz)
	{
		$u_begintime=$timestamp-1;
		$u_endtime=$u_begintime+$atc_day*24*3600;
		add_user( $lfjuid,-intval($totalmoneycard) );	//扣除积分
		//$db->query("UPDATE {$pre}memberdata SET moneycard=moneycard-'$totalmoneycard' WHERE uid='$lfjuid'");
	}
	else
	{
		$u_begintime=$u_endtime=0;
	}
	$u_hits=0;
	$db->query("INSERT INTO `{$pre}ad_user` ( `id` , `u_uid` , `u_username` , `u_day` , `u_begintime` , `u_endtime` , `u_hits` , `u_yz` , `u_code` , `u_money` , `u_moneycard` , `u_posttime` ) VALUES ('$id','$lfjuid','$lfjid','$atc_day','$u_begintime','$u_endtime','$u_hits','$u_yz','$u_code','$u_money','$u_moneycard','$timestamp')");
	make_ad_cache();
	refreshto("?job=list","购买成功,你共支付了{$u_moneycard}积分","3");
}

elseif($job=='mylist')
{
	$query = $db->query("SELECT A.*,B.* FROM {$pre}ad_user B LEFT JOIN {$pre}ad A ON A.id=B.id WHERE B.u_uid='$lfjuid' ORDER BY B.u_id DESC");
	while($rs = $db->fetch_array($query))
	{
		if($rs[u_yz]&&($rs[u_endtime]-$timestamp)<24*3600)
		{
			$rs[alert]="alert('过期或一天内将要过期的广告不能再修改');return false;";
			$rs[color]="#ccc;";
		}
		else
		{
			$rs[alert]="";
			$rs[color]="red;";
		}

		if($rs[u_yz]){
			$rs[_ifyz]='已审核';
		}else{
			$rs[_ifyz]='<font color=blue>未审核</font>';
		}
		if($rs[u_begintime])
		{
			$rs[u_begintime]=date("Y-m-d H:i",$rs[u_begintime]);
		}
		else
		{
			$rs[u_begintime]='';
		}
		if($rs[u_endtime])
		{
			$rs[u_endtime]=date("Y-m-d H:i",$rs[u_endtime]);
		}
		else
		{
			$rs[u_endtime]='';
		}
		$listdb[]=$rs;
	}
	require(dirname(__FILE__)."/"."head.php");
	require(dirname(__FILE__)."/"."template/buyad/mylist.htm");
	require(dirname(__FILE__)."/"."foot.php");
}
elseif($action=="del")
{
	$db->query("DELETE FROM {$pre}ad_user WHERE u_id='$u_id' AND u_uid='$lfjuid'");
	make_ad_cache();
	refreshto("?job=mylist","删除成功","3");
}
elseif($job=='edit')
{
	$rsdb=$db->get_one("SELECT A.*,B.* FROM {$pre}ad_user B LEFT JOIN {$pre}ad A ON A.id=B.id WHERE B.u_id='$u_id'");
	@extract(unserialize($rsdb[u_code]));
	if($rsdb[autoyz]){
		$rsdb[_ifyz]='自动通过审核';
	}else{
		$rsdb[_ifyz]='手工审核';
	}
	$id=$rsdb[id];
	require(dirname(__FILE__)."/"."head.php");
	require(dirname(__FILE__)."/"."template/buyad/buy.htm");
	require(dirname(__FILE__)."/"."foot.php");
}

elseif($action=="edit")
{
	if($atc_day<1)
	{
		showerr("购买的广告不能小于一天");
	}
	
	$rsdb=$db->get_one("SELECT A.*,B.* FROM {$pre}ad_user B LEFT JOIN {$pre}ad A ON A.id=B.id WHERE B.u_id='$u_id'");

	if($rsdb[u_endtime]<$timestamp)
	{
		showerr("过期广告不能再修改");
	}
	elseif((($rsdb[u_endtime]-$timestamp)<24*3600)&&$atc_day<$rsdb[u_day])
	{
		showerr("今天内将要过期的广告不能把日期改小");
	}
	//$rsdb=$db->get_one("SELECT * FROM {$pre}ad WHERE id='$id'");

	$totalmoneycard=$u_moneycard=$rsdb[moneycard]*$atc_day;
	//$lfjdb[moneycard]=intval($lfjdb[moneycard]);
	$lfjdb[money]=intval(get_money($lfjdb[uid]));
	
	$cdb=unserialize($rsdb[adcode]);
	
	if($rsdb[type]=='word'){
		$cdb[word]=filtrate($atc_word);
		$cdb[linkurl]=filtrate($atc_url);
	}elseif($rsdb[type]=='pic'){
		$cdb[picurl]=filtrate($atc_img);
		$cdb[linkurl]=filtrate($atc_url);
	}elseif($rsdb[type]=='swf'){
		$cdb[flashurl]=filtrate($atc_url);
	}elseif($rsdb[type]=='duilian'){
		$cdb[l_src]=filtrate($l_src);
		$cdb[l_link]=filtrate($l_link);
		$cdb[r_src]=filtrate($r_src);
		$cdb[r_link]=filtrate($r_link);
	}
	$cdb[code]=stripslashes($atc_code);
	$u_code=addslashes(serialize($cdb));

	$u_yz=$rsdb[autoyz];
	if($rsdb[autoyz])
	{
		$u_begintime=$rsdb[u_begintime];
		$u_endtime=$rsdb[u_endtime]+($atc_day-$rsdb[u_day])*3600*24;

		if(!$rsdb[u_yz])
		{
			if($totalmoneycard>$lfjdb[money])
			{
				showerr("你的积分不足$totalmoneycard,你仅有积分:$lfjdb[money]");
			}
			
			//$db->query("UPDATE {$pre}memberdata SET moneycard=moneycard-'$totalmoneycard' WHERE uid='$lfjuid'");
			add_user($lfjuid,-intval($totalmoneycard));	//扣除积分
		}
		else
		{
			if( $totalmoneycard>($lfjdb[money]+$rsdb[u_money]) )
			{
				showerr("你的积分不足,你仅有积分:$lfjdb[money]");
			}
			//$db->query("UPDATE {$pre}memberdata SET moneycard=moneycard-'$totalmoneycard'+'$rsdb[u_money]' WHERE uid='$lfjuid'");
			add_user($lfjuid,-intval($rsdb[u_money]-$totalmoneycard));	//扣除积分
		}			
	}
	else
	{
		if($totalmoneycard>$lfjdb[money])
		{
			showerr("你的积分不足$totalmoneycard,你仅有积分:$lfjdb[money]");
		}
		$u_begintime=$u_endtime=0;
	}

	$u_hits=0;
	$db->query("UPDATE `{$pre}ad_user` SET `u_day`='$atc_day',`u_begintime`='$u_begintime',`u_endtime`='$u_endtime',`u_yz`='$u_yz',`u_code`='$u_code',`u_money`='$u_money',`u_moneycard`='$u_moneycard' WHERE u_id='$u_id' AND u_uid='$lfjuid'");
	
	make_ad_cache();
	refreshto("?job=mylist","修改成功","3");
}


?>