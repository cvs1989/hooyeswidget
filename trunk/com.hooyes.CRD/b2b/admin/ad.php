<?php
!function_exists('html') && exit('ERR');

require_once(PHP168_PATH."inc/function.ad.php");

$array_adtype=array(
					"word"=>"文字广告",
					"pic"=>"图片广告",
					"swf"=>"FLASH广告",
					"code"=>"代码广告",
					"duilian"=>"对联广告"
					);

//列出所有
if($job=="listad"&&$Apower[ad_listAdPlace]){
	$query = $db->query("SELECT AD.* FROM {$pre}ad AD ORDER BY AD.id DESC");
	while($rs = $db->fetch_array($query)){
		$rs[begintime]=$rs[begintime]?date("Y-m-d H:i:s",$rs[begintime]):'';
		$rs[endtime]=$rs[endtime]?date("Y-m-d H:i:s",$rs[endtime]):'';
		if($rs[ifsale]){
			$rs[_ifsale]='<font color="red">允许购买</font>';
		}else{
			$rs[_ifsale]='禁止购买';
		}
		$listdb[]=$rs;
	}
	require(dirname(__FILE__)."/"."head.php");
	require(dirname(__FILE__)."/"."template/ad/menu.htm");
	require(dirname(__FILE__)."/"."template/ad/listad.htm");
	require(dirname(__FILE__)."/"."foot.php");
}

//添加广告
elseif($job=="addplace"&&$Apower[ad_listAdPlace])
{
	$rsdb[type]='word';
	$rsdb[keywords]="AD_".rand(1,9999);
	$_pictarget[blank]=$_wordtarget[blank]=" checked ";
	$rsdb[ifsale]=1;
	$ifsale[$rsdb[ifsale]]=' checked ';
	$autoyz[1]=' checked ';
	require(dirname(__FILE__)."/"."head.php");
	require(dirname(__FILE__)."/"."template/ad/menu.htm");
	require(dirname(__FILE__)."/"."template/ad/addplace.htm");
	require(dirname(__FILE__)."/"."foot.php");
}

//修改广告
elseif($job=="editadplace"&&$Apower[ad_listAdPlace])
{
	$rsdb=$db->get_one("SELECT * FROM `{$pre}ad` WHERE id='$id'");
	@extract(unserialize($rsdb[adcode]));
	$code=stripslashes($code);
	$rsdb[isclose]=intval($rsdb[isclose]);
	$isclose[$rsdb[isclose]]=" checked ";
	$pictarget||$pictarget='blank';
	$wordtarget||$wordtarget='blank';
	$_pictarget[$pictarget]=" checked ";
	$_wordtarget[$wordtarget]=" checked ";
	$ifsale[$rsdb[ifsale]]=' checked ';
	$autoyz[$rsdb[autoyz]]=' checked ';

	$rsdb[begintime]&&$rsdb[begintime]=date("Y-m-d H:i:s",$rsdb[begintime]);
	$rsdb[endtime]&&$rsdb[endtime]=date("Y-m-d H:i:s",$rsdb[endtime]);

	$code=str_replace("'","&#39;",$code);

	require(dirname(__FILE__)."/"."head.php");
	require(dirname(__FILE__)."/"."template/ad/menu.htm");
	require(dirname(__FILE__)."/"."template/ad/addplace.htm");
	require(dirname(__FILE__)."/"."foot.php");
}

//处理修改广告
elseif($action=="editadplace"&&$Apower[ad_listAdPlace])
{
	if($postdb[type]=='word'){
		$cdb[linkurl]=$wordlinkurl;
		$cdb[wordtarget]=$wordtarget;
	}elseif($postdb[type]=='pic'){
		$cdb[width]=$picwidth;
		$cdb[height]=$picheight;
		$cdb[pictarget]=$pictarget;
	}elseif($postdb[type]=='swf'){
		$cdb[width]=$swfwidth;
		$cdb[height]=$swfheight;
	}elseif($postdb[type]=='duilian'){
		$cdb[l_src]=$l_src;
		$cdb[l_link]=$l_link;
		$cdb[l_width]=$l_width;
		$cdb[l_height]=$l_height;
		$cdb[r_src]=$r_src;
		$cdb[r_link]=$r_link;
		$cdb[r_width]=$r_width;
		$cdb[r_height]=$r_height;
	}
	$cdb[code]=stripslashes($cdb[code]);
	$postdb[adcode]=addslashes(serialize($cdb));

	if($ifsale)
	{
		$begintime=$endtime=0;
	}

	$begintime&&$begintime=preg_replace("/([\d]+)-([\d]+)-([\d]+) ([\d]+):([\d]+):([\d]+)/eis","mk_time('\\4','\\5', '\\6', '\\2', '\\3', '\\1')",$begintime);
	$endtime&&$endtime=preg_replace("/([\d]+)-([\d]+)-([\d]+) ([\d]+):([\d]+):([\d]+)/eis","mk_time('\\4','\\5', '\\6', '\\2', '\\3', '\\1')",$endtime);

	$db->query("UPDATE `{$pre}ad` SET name='$postdb[name]',demourl='$postdb[demourl]',keywords='$postdb[keywords]',adcode='$postdb[adcode]',type='$postdb[type]',isclose='$isclose',ifsale='$ifsale',moneycard='$moneycard',autoyz='$autoyz',begintime='$begintime',endtime='$endtime' WHERE id='$id' ");
	make_ad_cache();
	jump("修改成功","$FROMURL",1);
}

//处理添加广告
elseif($action=="addplace"&&$Apower[ad_listAdPlace])
{
	if($postdb[type]=='word'){
		$cdb[linkurl]=$wordlinkurl;
		$cdb[wordtarget]=$wordtarget;
	}elseif($postdb[type]=='pic'){
		$cdb[width]=$picwidth;
		$cdb[height]=$picheight;
		$cdb[pictarget]=$pictarget;
	}elseif($postdb[type]=='swf'){
		$cdb[width]=$swfwidth;
		$cdb[height]=$swfheight;
	}elseif($postdb[type]=='duilian'){
		$cdb[l_src]=$l_src;
		$cdb[l_link]=$l_link;
		$cdb[l_width]=$l_width;
		$cdb[l_height]=$l_height;
		$cdb[r_src]=$r_src;
		$cdb[r_link]=$r_link;
		$cdb[r_width]=$r_width;
		$cdb[r_height]=$r_height;
	}
	$cdb[code]=stripslashes($cdb[code]);
	$postdb[adcode]=addslashes(serialize($cdb));
	
	if($ifsale)
	{
		$begintime=$endtime=0;
	}

	$begintime&&$begintime=preg_replace("/([\d]+)-([\d]+)-([\d]+) ([\d]+):([\d]+):([\d]+)/eis","mk_time('\\4','\\5', '\\6', '\\2', '\\3', '\\1')",$begintime);
	$endtime&&$endtime=preg_replace("/([\d]+)-([\d]+)-([\d]+) ([\d]+):([\d]+):([\d]+)/eis","mk_time('\\4','\\5', '\\6', '\\2', '\\3', '\\1')",$endtime);

	$db->query("INSERT INTO `{$pre}ad` (`name` ,`demourl` , `keywords` , `adcode` , `type`, `ifsale`, `moneycard`, `autoyz`, `begintime`, `endtime` ) 
				VALUES (
		'$postdb[name]','$postdb[demourl]','$postdb[keywords]','$postdb[adcode]','$postdb[type]','$ifsale','$moneycard','$autoyz','$begintime','$endtime'
		)");
	make_ad_cache();
	jump("添加成功","index.php?lfj=$lfj&job=listad",1);
}

//删除广告
elseif($action=='deleteadplace'&&$Apower[ad_listAdPlace])
{
	$db->query("DELETE FROM `{$pre}ad` WHERE id='$id'");
	$db->query("DELETE FROM `{$pre}ad_user` WHERE id='$id'");
	make_ad_cache();
	jump("删除成功","$FROMURL",1);
}
elseif($job=="listuserad"&&$Apower[ad_listuserad])
{
	if($page<1){
		$page=1;
	}
	$rows=30;
	$min=($page-1)*$rows;
	$query = $db->query("SELECT A.*,B.* FROM `{$pre}ad_user` A LEFT JOIN `{$pre}ad` B ON A.id=B.id ORDER BY A.id DESC LIMIT $min,$rows");
	while($rs = $db->fetch_array($query)){
		if($rs[u_begintime]){
			$rs[u_begintime]=date("Y-m-d H:i",$rs[u_begintime]);
		}else{
			$rs[u_begintime]='';
		}
		if($rs[u_endtime]){
			$rs[u_endtime]=date("Y-m-d H:i",$rs[u_endtime]);
		}else{
			$rs[u_endtime]='';
		}
		if($rs[u_yz]){
			$rs[u_yz]="<A HREF='#' style='color:red;'>已审</A>";
		}else{
			$rs[u_yz]="<A HREF='?lfj=$lfj&action=yz&yz=1&u_id=$rs[u_id]' style='color:blue;'>未审</A>";
		}
		$rs[u_posttime]=date("Y-m-d H:i",$rs[u_posttime]);
		$listdb[]=$rs;
	}
	require(dirname(__FILE__)."/"."head.php");
	//require(dirname(__FILE__)."/"."template/ad/menu.htm");
	require(dirname(__FILE__)."/"."template/ad/listuserad.htm");
	require(dirname(__FILE__)."/"."foot.php");
}
elseif($action=='yz'&&$Apower[ad_listuserad])
{
	$rsdb=$db->get_one("SELECT * FROM `{$pre}ad_user` WHERE u_id='$u_id'");

	$SQL='';
	if($yz&&!$rsdb[u_begintime])
	{
		/*
		$_rs=$db->get_one("SELECT moneycard FROM `{$pre}memberdata` WHERE uid='$rsdb[u_uid]'");
		if($_rs[moneycard]<$rsdb[u_moneycard]){
			showerr("当前用户的金币数不足:{$rsdb[u_moneycard]},他仅有金币数:$_rs[moneycard]");
		}
		*/
		$_rs[money]=intval(get_money($rsdb[u_uid]));
		if($_rs[money]<$rsdb[u_moneycard]){
			showerr("当前用户的积分不足:{$rsdb[u_moneycard]},他仅有积分:$_rs[money]");
		}
		$rsdb[u_endtime]=$timestamp+3600*24*$rsdb[u_day];
		$SQL=",u_begintime='$timestamp',u_endtime='$rsdb[u_endtime]'";
		add_user($rsdb[u_uid],-$rsdb[u_moneycard]);	//扣除积分
		//$db->query("UPDATE `{$pre}memberdata` SET moneycard=moneycard-'$rsdb[u_moneycard]' WHERE uid='$rsdb[u_uid]'");
	}
	$db->query("UPDATE `{$pre}ad_user` SET u_yz='$yz'$SQL WHERE u_id='$u_id'");
	make_ad_cache();
	jump("操作成功",$FROMURL,0);
}
elseif($action=='delete_u_ad'&&$Apower[ad_listuserad])
{
	$db->query("DELETE FROM {$pre}ad_user WHERE u_id='$u_id'");
	make_ad_cache();
	jump("操作成功",$FROMURL,2);
}
elseif($job=="edituserad"&&$Apower[ad_listuserad])
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
	//require(dirname(__FILE__)."/"."template/ad/menu.htm");
	require(dirname(__FILE__)."/"."template/ad/edituserad.htm");
	require(dirname(__FILE__)."/"."foot.php");
}
elseif($action=="edituserad"&&$Apower[ad_listuserad])
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
	$lfjdb[moneycard]=intval($lfjdb[moneycard]);
	
	$cdb=unserialize($rsdb[adcode]);
	
	if($rsdb[type]=='word'){
		$cdb[word]=$atc_word;
		$cdb[linkurl]=$atc_url;
	}elseif($rsdb[type]=='pic'){
		$cdb[picurl]=$atc_img;
		$cdb[linkurl]=$atc_url;
	}elseif($rsdb[type]=='swf'){
		$cdb[flashurl]=$atc_url;
	}elseif($rsdb[type]=='duilian'){
		$cdb[l_src]=$l_src;
		$cdb[l_link]=$l_link;
		$cdb[r_src]=$r_src;
		$cdb[r_link]=$r_link;
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
			if($totalmoneycard>$lfjdb[moneycard])
			{
				//showerr("你的金币不足$totalmoneycard,你仅有金币:$lfjdb[moneycard]");
			}
			
			//$db->query("UPDATE {$pre}memberdata SET moneycard=moneycard-'$totalmoneycard' WHERE uid='$lfjuid'");
		}
		else
		{
			if( $totalmoneycard>($lfjdb[moneycard]+$rsdb[u_money]) )
			{
				//showerr("你的金币不足,你仅有金币:$lfjdb[moneycard]");
			}
			//$db->query("UPDATE {$pre}memberdata SET moneycard=moneycard-'$totalmoneycard'+'$rsdb[u_money]' WHERE uid='$lfjuid'");
		}			
	}
	else
	{
		if($totalmoneycard>$lfjdb[moneycard])
		{
			//showerr("你的金币不足$totalmoneycard,你仅有金币:$lfjdb[moneycard]");
		}
		$u_begintime=$u_endtime=0;
	}

	$u_hits=0;
	$db->query("UPDATE `{$pre}ad_user` SET `u_day`='$atc_day',`u_begintime`='$u_begintime',`u_endtime`='$u_endtime',`u_code`='$u_code',`u_money`='$u_money',`u_moneycard`='$u_moneycard' WHERE u_id='$u_id'");
	
	make_ad_cache();
	jump("修改成功",$FROMURL,"3");
}
?>