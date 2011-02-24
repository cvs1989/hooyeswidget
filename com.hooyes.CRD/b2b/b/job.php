<?php
require("global.php");

/**
*首页生成静态
**/
if($job=="makeindex")
{
	if($webdb[Info_MakeIndexHtmlTime]>0)
	{
		$time=$webdb[Info_MakeIndexHtmlTime]*60;
		if((time()-@filemtime("index.htm"))>$time)
		{
			echo "<div style='display:none'><iframe src=index.php?MakeIndex=1></iframe></div>";
		}
	}
}

/*用户评论字段的功能*/
elseif($action=='pingfen')
{
	/*针对每条信息30分钟才允许评分一次*/
	$time=$timestamp+30*60;

	$pingfenID="pingfenID_$id";
	if($_COOKIE[$pingfenID])
	{
		showerr("半小时内,不能重复操作!!!");
	}
	setcookie($pingfenID,"1",$time,"/");

	$_web=preg_replace("/http:\/\/([^\/]+)\/(.*)/is","http://\\1",$WEBURL);
	if($webdb[Info_forbidOutPost]&&!ereg("^$_web",$FROMURL))
	{
		showerr("系统设置不能从外部提交数据");
	}

	$rsdb=$db->get_one("SELECT M.config AS m_config,C.mid FROM {$_pre}content C INNER JOIN {$_pre}module M ON C.mid=M.id WHERE C.id='$id' ");

	if(!$rsdb[mid])
	{
		showerr("此ID有问题");
	}
	$m_config=unserialize($rsdb[m_config]);
	$array=$m_config[field_db];
	
	foreach( $postdb AS $key=>$value)
	{
		if($array[$key][form_type]=='pingfen')
		{
			$db->query("UPDATE {$_pre}content_{$rsdb[mid]} SET `$key`=`$key`+'$value' WHERE id='$id' ");
		}
	}
	header("location:$FROMURL");
	exit;
}
elseif($job=="report")
{
	if(!$lfjuid){
		showerr("游客不能举报,请先登录");
	}
	if($step==2)
	{
		if($ctype == 1){
			$tbl = 'content_sell';
		}else if($ctype == 2){
			$tbl = 'content_buy';
		}else{
			showerr("不允许的操作");
		}
		$rs=$db->get_one("SELECT * FROM {$_pre}report WHERE uid='$lfjuid' ORDER BY rid DESC LIMIT 1");
		if( ($timestamp-$rs[posttime])<60 ){
			showerr("1秒内,请不要重复举报");
		}
		$rs=$db->get_one("SELECT title FROM {$_pre}$tbl WHERE id='$id'");
		$content=filtrate($content);
		$db->query("INSERT INTO `{$_pre}report` (`id`, `uid`, `username`, ctype, title, `posttime`, `onlineip`, `type`,`content`) VALUES ('$id','$lfjuid','$lfjid', $ctype, '$rs[title]', '$timestamp','$onlineip','$type','$content')");
		
		refreshto($FROMURL,"举报成功",1);
	}
	require(Mpath."php168/report.php");
	@include(Mpath."php168/guide_fid.php");
	
	$typedb[1]=' checked ';

	require(Mpath."inc/head.php");
	require(getTpl("report"));
	require(Mpath."inc/foot.php");
}
elseif($job=="getshop")
{
	if(!$lfjuid){
		showerr("你还没有登录,请先登录");
	}
	$rs=$db->get_one("SELECT * FROM {$_pre}getshop WHERE uid='$lfjuid' AND id='$id'");
	if( ($timestamp-$rs[posttime])<60 ){
		showerr("请不要重复认领");
	}
	$rs=$db->get_one("SELECT * FROM {$_pre}content WHERE uid='$lfjuid' AND id='$id'");
	if( ($timestamp-$rs[posttime])<60 ){
		showerr("已经是你的店铺了,你不需要认领");
	}
	if($step==2)
	{
		$rs=$db->get_one("SELECT * FROM {$_pre}getshop WHERE uid='$lfjuid' ORDER BY rid DESC LIMIT 1");
		if( ($timestamp-$rs[posttime])<60 ){
			showerr("1秒内,请不要认领");
		}
		$content=filtrate($content);
		$telephone=filtrate($telephone);
		$linkman=filtrate($linkman);
		$db->query("INSERT INTO `{$_pre}getshop` (`id`, `fid`, `uid`, `username`, `posttime`, `onlineip`, `content`, `linkman`, `telephone`) VALUES ('$id','$fid','$lfjuid','$lfjid','$timestamp','$onlineip','$content','$linkman','$telephone')");
		refreshto("bencandy.php?fid=$fid&id=$id","你的认领资料,已提交成功,我们会尽快处理.",5);
	}
	@include(Mpath."php168/guide_fid.php");
	
	require(Mpath."inc/head.php");
	require(getTpl("getshop"));
	require(Mpath."inc/foot.php");
}
//收藏
elseif($job=='collect')
{
	if(!$lfjid){
		showerr("请先登录");
	}elseif(!$id){
		showerr("ID不存在");
	}
	if($db->get_one("SELECT * FROM `{$_pre}collection` WHERE `id`='$id' AND uid='$lfjuid' and ctype=$ctype")){
		showerr("请不要重复收藏本条信息",1); 
	}
	if(!$web_admin){
		if($webdb[Info_CollectArticleNum]<1){
			$webdb[Info_CollectArticleNum]=50;
		}
		$rs=$db->get_one("SELECT COUNT(*) AS NUM FROM `{$_pre}collection` WHERE uid='$lfjuid'");
		if($rs[NUM]>=$webdb[Info_CollectArticleNum]){
			showerr("你最多只能收藏{$webdb[Info_CollectArticleNum]}条信息",1);
		}
	}
	
	$db->query("INSERT INTO `{$_pre}collection` (  `id` , `uid` , `posttime`,`ctype`) VALUES ('$id','$lfjuid','$timestamp','$ctype')");

	refreshto("$Mdomain/b/member/?main=collection.php","收藏成功!",1);
}
elseif($job=="getjob_sort")
{
	if($sid){
		$step=!$step?1:$step;
		$step++;
		@include(Mpath."php168/all_hrfid.php");
		
		if(!is_array($hrFid_db[$sid]) || count($hrFid_db[$sid])<1){
			echo "<SCRIPT LANGUAGE=\"JavaScript\">
		<!--
		
		parent.document.getElementById(\"show_jobs_sort_$step\").innerHTML='$show';
		//-->
		</SCRIPT>";exit;
		}
		$show="<select name='job_sort[{$step}]' onchange='choose_jobSort(this.options[this.selectedIndex].value,$step)'> ";
		foreach($hrFid_db[$sid] as $key=>$val){
					$show.="<option value='$key'>$val</option>";
		}
		$show.="</select>";
	
		$show=str_replace("\r","",$show);
		$show=str_replace("\n","",$show);
		$show=str_replace("'","\'",$show);
		$show.="<span id=show_jobs_sort_".($step+1)."></span>";
		echo "<SCRIPT LANGUAGE=\"JavaScript\">
		<!--
		
		parent.document.getElementById(\"show_jobs_sort_$step\").innerHTML='$show';
		//-->
		</SCRIPT>";
	}else{
		echo "<SCRIPT LANGUAGE=\"JavaScript\">
		<!--
		parent.document.getElementById(\"show_jobs_sort_1\").innerHTML='';
		//-->
		</SCRIPT>";
	}
}
elseif($job=="getcity")
{
	if($fup){
		$show=select_where("city","'postdb[city_id]'",$fid,$fup);
		$show=str_replace("\r","",$show);
		$show=str_replace("\n","",$show);
		$show=str_replace("'","\'",$show);
		echo "<SCRIPT LANGUAGE=\"JavaScript\">
		<!--
		parent.document.getElementById(\"{$typeid}showcity\").innerHTML='$show';
		//-->
		</SCRIPT>";
	}else{
		echo "<SCRIPT LANGUAGE=\"JavaScript\">
		<!--
		parent.document.getElementById(\"{$typeid}showcity\").innerHTML='';
		//-->
		</SCRIPT>";
	}
}
elseif($job=="getcity2"){
	
	if($fup){
		$show=select_where("city","'$name' style='width:100px'",$ck,$fup);
		$show=str_replace("\r","",$show);
		$show=str_replace("\n","",$show);
		$show=str_replace("'","\'",$show);
		echo "<SCRIPT LANGUAGE=\"JavaScript\">
		<!--
		parent.document.getElementById(\"$showspan\").innerHTML='$show';
		//-->
		</SCRIPT>";
	}else{
		echo "<SCRIPT LANGUAGE=\"JavaScript\">
		<!--
		parent.document.getElementById(\"$showspan\").innerHTML='';
		//-->
		</SCRIPT>";
	}
}
elseif($job=='update'){
	if(!$lfjuid){
		showerr('请先登录');
	}
	$rs=$db->get_one("SELECT * FROM {$_pre}content WHERE id='$id'");
	if($rs[uid]!=$lfjuid){
		showerr('你无权限');
	}
	if($timestamp-$rs[posttime]<3600){
		showerr('距离上次更新时间1小时后,才可以进行刷新!');
	}
	if($rs['list']>$timestamp){
		$list=$rs['list'];
	}else{
		$list=$timestamp;
	}
	$db->query("UPDATE {$_pre}content SET list='$list',posttime='$timestamp' WHERE id='$id'");
	refreshto("$FROMURL","刷新成功",1);
}
elseif($job=="gettg")
{
	
	
		echo '<meta http-equiv="Content-Type" content="text/html; charset=gb2312">';
		echo "暂时没有";
	
}
?>