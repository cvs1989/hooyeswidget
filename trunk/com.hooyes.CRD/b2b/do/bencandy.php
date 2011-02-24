<?php
require_once(dirname(__FILE__)."/"."global.php");
!$aid && $aid = intval($id);
$id = $aid;
$page<1 && $page=1;

if(!$id&&!$aid&&$webdb[NewsMakeHtml]==2){
	//伪静态处理
	Explain_HtmlUrl();
	!$aid && $aid = intval($id);
}

//$Cache_FileName=PHP168_PATH."cache/bencandy_cache/".floor($id/3000)."/{$id}_{$page}.php";
if(!$jobs&&$webdb[bencandy_cache_time]&&(time()-filemtime($Cache_FileName))<($webdb[bencandy_cache_time]*60)){
	echo read_file($Cache_FileName);
	exit;
}

@include(PHP168_PATH."php168/guide_fid.php");		//栏目配置文件




/**
*获取文章
**/
$min=intval($page)-1;
$erp=$Fid_db[iftable][$fid]?$Fid_db[iftable][$fid]:'';
$rsdb=$db->get_one("SELECT R.*,A.* FROM {$pre}article$erp A LEFT JOIN {$pre}reply$erp R ON A.aid=R.aid WHERE A.aid=$aid ORDER BY R.topic DESC,R.orderid ASC LIMIT $min,1");

if(!$rsdb){
	showerr("数据不存在!");
}elseif($fid!=$rsdb[fid]){
	showerr("FID有误");
}

/**
*栏目配置文件
**/
$fidDB=$db->get_one("SELECT S.*,M.alias AS M_alias,M.keywords AS M_keyword,M.config AS M_config FROM {$pre}sort S LEFT JOIN {$pre}article_module M ON S.fmid=M.id WHERE S.fid='$fid'");
$fidDB[M_alias] || $fidDB[M_alias]='文章';
$fidDB[M_config]=unserialize($fidDB[M_config]);
$fidDB[config]=unserialize($fidDB[config]);
$FidTpl=unserialize($fidDB[template]);

$fupId=intval($fidDB[type]?$fid:$fidDB[fup]);

//禁止访问动态页
if($webdb[ForbidShowPhpPage]&&!$NeedCheck&&!$jobs){
	if($webdb[NewsMakeHtml]==2&&ereg("=[0-9]+$",$WEBURL)){		//伪静态
		eval("\$url=\"$webdb[bencandy_filename2]\";");
		header("location:$webdb[www_url]/$url");
		exit;
	}elseif($webdb[NewsMakeHtml]==1){							//真静态
		$detail=get_html_url();
		if(is_file(PHP168_PATH.$detail[_showurl])){
			header("location:$detail[showurl]");
			exit;
		}
	}
}

/**
*文章检查
**/
check_article($rsdb);

//统计点击次数
$db->query("UPDATE {$pre}article$erp SET hits=hits+1,lastview='$timestamp' WHERE aid='$aid'");

//SEO
$titleDB[title]		= filtrate(strip_tags("$rsdb[title] - $fidDB[name] - $webdb[webname]"));
$titleDB[keywords]	= filtrate($rsdb[keywords]);
$rsdb[description] || $rsdb[description]=get_word(preg_replace("/(<([^<]+)>|	|&nbsp;|\n)/is","",$rsdb[content]),250);
$titleDB[description] = filtrate($rsdb[description]);



//文章风格
$STYLE = $rsdb[style] ? $rsdb[style] : ($fidDB[style] ? $fidDB[style] : $STYLE);

//相关栏目名称模板
if(is_file(html("$webdb[SideSortStyle]"))){
	$sortnameTPL=html("$webdb[SideSortStyle]");
}else{
	$sortnameTPL=html("side_sort/0");
}

/**
*模板选择
**/
//类似大旗那样,框架网页模板
if($rsdb[iframeurl])
{
	$head_tpl="template/default/none.htm";
	$main_tpl="template/default/none.htm";
	$foot_tpl="template/default/iframe.htm";
}
else
{
	$showTpl=unserialize($rsdb[template]);
	$head_tpl=$showTpl[head]?$showTpl[head]:$FidTpl['head'];
	$main_tpl=$showTpl[bencandy]?$showTpl[bencandy]:$FidTpl['bencandy'];
	$foot_tpl=$showTpl[foot]?$showTpl[foot]:$FidTpl['foot'];
}

//兼容V6前的版本
if(!$rsdb[ishtml])
{
	//附件真实地址还原
	$rsdb[content] = En_TruePath($rsdb[content],0);
	//UBB处理
	require_once(PHP168_PATH."inc/encode.php");
	$rsdb[content] = format_text($rsdb[content]);
}
else
{
	//附件真实地址还原
	$rsdb[content] = En_TruePath($rsdb[content],0,1);

	require_once(PHP168_PATH."inc/encode.php");
	//文件下载
	//<div><a style="COLOR: red" href="http://1.com/upload_files/other/1_20070729020722_YmI=.rar" target=_blank p8name="p8download">点击下载</a></div>
	$rsdb[content]=preg_replace("/<IMG src=\"([^\"]+)\" border=0><A href=\"([^\"]+)\" target=_blank>([^<>]+)<\/A>/eis","encode_fileurl('\\1','\\2','\\3')",$rsdb[content]);
	$rsdb[content]=preg_replace("/<([^<>]+)href=\"([^\"]+)\"([^<>]+)p8name=\"p8download\"([^<>]*)>([^<>]+)<\/A>/eis","encode_fileurl('','\\2','\\5')",$rsdb[content]);
}

$rsdb[content]=show_keyword($rsdb[content]);	//突出显示关键字

$IS_BIZ && AvoidGather();	//防采集处理

$rsdb[posttime] = date("Y-m-d H:i:s",$rsdb[posttime]);

if($rsdb[copyfromurl]&&!strstr($rsdb[copyfromurl],"http://")){
	$rsdb[copyfromurl]="http://$rsdb[copyfromurl]";
}

//文章分布
$showpage = getpage("","","bencandy.php?fid=$fid&aid=$aid",1,$rsdb[pages]);

/**
*上一篇与下一篇,比较影响速度
**/
$nextdb=$db->get_one("SELECT title,aid,fid FROM {$pre}article$erp WHERE aid<'$id' AND fid='$fid' ORDER BY aid DESC LIMIT 1");
$nextdb[subject]=get_word($nextdb[title],34);
$backdb=$db->get_one("SELECT title,aid,fid FROM {$pre}article$erp WHERE aid>'$id' AND fid='$fid' ORDER BY aid ASC LIMIT 1");
$backdb[subject]=get_word($backdb[title],34);


/**
*为获取标签参数
**/
$chdb[main_tpl]=html("bencandy",$main_tpl);

/**
*标签
**/
$ch_fid	= intval($fidDB[config][label_bencandy]);	//是否定义了栏目专用标签
$ch_pagetype = 3;									//2,为list页,3,为bencandy页
$ch_module = 0;										//文章模块,默认为0
$ch = 0;											//不属于任何专题
require(PHP168_PATH."inc/label_module.php");

//文章自定义模型$fidDB[config]
if($rsdb[mid]){
	if($rsdb[mid]!=$fidDB[fmid]){
		@extract($db->get_one("SELECT config AS m_config FROM {$pre}article_module WHERE id='$rsdb[mid]'"));
		$M_config=unserialize($m_config);
	}else{
		$M_config=$fidDB[M_config];
	}
	
	$_rsdb=$db->get_one("SELECT * FROM `{$pre}article_content_$rsdb[mid]` WHERE aid='$id' AND rid='$rsdb[rid]'");
	if($_rsdb){
		$rsdb=$rsdb+$_rsdb;
		show_module_content($M_config);
	}
}

$rsdb[picurl]=tempdir($rsdb[picurl]);

$webdb[AutoTitleNum] && $rsdb[pages]>1 && $rsdb[title]=Set_Title_PageNum($rsdb[title],$page);

if($rsdb[keywords]){
	unset($array);
	$detail=explode(" ",$rsdb[keywords]);
	foreach( $detail AS $key=>$value){
		$_value=urlencode($value);
		$array[]="<A HREF='$webdb[www_url]/do/search.php?type=keyword&keyword=$_value' target=_blank>$value</A>";
	}
	$rsdb[keywords]=implode(" ",$array);
}

//过滤不良词语
$rsdb[content]=replace_bad_word($rsdb[content]);
$rsdb[title]=replace_bad_word($rsdb[title]);
$rsdb[subhead]=replace_bad_word($rsdb[subhead]);

//多模型扩展接口
@include(PHP168_PATH."inc/bencandy_{$rsdb[mid]}.php");

/* $Murl = $webdb['www_url'].'/b';
$head_tpl = PHP168_PATH.'b/template/'. $STYLE .'/head.htm'; */
require(PHP168_PATH."inc/head.php");
if($rsdb[mid]&&file_exists(html("bencandy_$rsdb[mid]",$main_tpl))){
	require(html("bencandy_$rsdb[mid]",$main_tpl));
}else{
	require(html("bencandy",$main_tpl));
}
require(PHP168_PATH."inc/foot.php");

/*处理伪静态*/
if($webdb[NewsMakeHtml]==2)
{
	$content=ob_get_contents();
	ob_end_clean();
	ob_start();
	$content=fake_html($content);
	echo "$content";
}

if(!$jobs&&$webdb[bencandy_cache_time]&&(time()-filemtime($Cache_FileName))>($webdb[bencandy_cache_time]*60)){
	
	if(!is_dir(dirname($Cache_FileName))){
		makepath(dirname($Cache_FileName));
	}
	$content.="<SCRIPT LANGUAGE='JavaScript' src='$webdb[www_url]/do/job.php?job=updatehits&aid=$id'></SCRIPT>";
	write_file($Cache_FileName,$content);
}elseif($jobs=='show'){
	@unlink($Cache_FileName);
}

/**
*文章检查
**/
function check_article($rsdb){
	global $fidDB,$web_admin,$groupdb,$timestamp,$lfjid,$lfjuid,$fid,$id,$aid,$buy,$lfjdb,$webdb,$pre,$db;
	if(!$rsdb)
	{
		showerr("文章不存在");
	}
	if( $fidDB[allowviewcontent]&&!in_array($fidDB[M_keyword],array('mv','download')) )
	{
		if( !$web_admin&&!in_array($groupdb[gid],explode(",",$fidDB[allowviewcontent])) )
		{
			showerr("你所在用户组不允许浏览文章内容");
		}
	}

	if( $rsdb[allowview]&&!in_array($fidDB[M_keyword],array('mv','download')) )
	{
		if( !$web_admin&&!in_array($groupdb[gid],explode(",",$rsdb[allowview])) )
		{
			showerr("本文,你所在用户组不允许浏览文章内容");
		}
	}

	//设置了开始浏览日期限制
	if($rsdb[begintime]&&$timestamp<$rsdb[begintime])
	{
		$rsdb[begintime]=date("Y-m-d H:i:s",$rsdb[begintime]);
		if($web_admin){
			 Remind_msg("本文只有到了“{$rsdb[begintime]}”那个时间才可以查看,因为你是管理员,所以可以查看,其他人是不能查看的");
		}else{
			showerr("<font color='red' ><u>很抱歉,发布者设置了本文内容只有到了“{$rsdb[begintime]}”那个时间才可以查看</u></font>");
		}
	}

	//设置了失效浏览日期限制
	if($rsdb[endtime]&&$timestamp>$rsdb[endtime])
	{
		$rsdb[endtime]=date("Y-m-d H:i:s",$rsdb[endtime]);
		if($web_admin){
			 Remind_msg("本文内容最后查看期限是“{$rsdb[endtime]}”,因为你是管理员,所以可以查看,其他人是不能查看的");
		}else{
			showerr("<font color='red' ><u>很抱歉,发布者设置了本文内容最后查看期限是“{$rsdb[endtime]}”，现在已超过了这个期限，所以不能查看</u></font>");
		}
	}

	if($rsdb[yz]==2){
		if($web_admin){
			 Remind_msg("回收站的内容不可以查看,因为你是管理员,所以可以查看,其他人是不能查看的");
		}else{
			showerr("回收站的内容你不可以查看");
		}
	}
	//未审核
	if($rsdb[yz]==0&&(!$lfjid||$lfjuid!=$rsdb[uid]))
	{
		if($web_admin){
			 Remind_msg("本文还没通过验证,因为你是管理员,所以可以查看,其他人是不能查看的");
		}else{
			showerr("<font color='red' ><u>很抱歉,本文还没通过验证,你不能查看</u></font>");
		}
	}

	//跳转到外面
	if($rsdb[jumpurl])
	{
		echo "页面正在跳转中，请稍候...<META HTTP-EQUIV=REFRESH CONTENT='0;URL=$rsdb[jumpurl]'>";
		exit;
	}

	//文章密码
	if($rsdb[passwd])
	{
		if($web_admin)
		{
			 Remind_msg("本文设置了密码,因为你是管理员,所以可以查看,其他人是不能查看的");
		}
		else
		{
			if( $_POST[password] && $_POST[TYPE] == 'article'  )
			{
				if( $_POST[password] != $rsdb[passwd] )
				{
					echo "<A HREF=\"?fid=$fid&aid=$aid\">密码不正确,点击返回</A>";
					exit;
				}
				else
				{
					setcookie("article_passwd_$id",$rsdb[passwd]);
					$_COOKIE["article_passwd_$id"]=$rsdb[passwd];
				}
			}
			if( $_COOKIE["article_passwd_$id"] != $rsdb[passwd] )
			{
				echo "<CENTER><form name=\"form1\" method=\"post\" action=\"\">请输入文章密码:<input type=\"password\" name=\"password\"><input type=\"hidden\" name=\"TYPE\" value=\"article\"><input type=\"submit\" name=\"Submit\" value=\"提交\"></form></CENTER>";
				exit;
			}
		}
	}

	//栏目密码
	if( $makehtml!=2 && $fidDB[passwd] )
	{
		if($web_admin)
		{
			 Remind_msg("本栏目设置了密码,因为你是管理员,所以可以查看,其他人是不能查看的");
		}
		else
		{
			if( $_POST[password] && $_POST[TYPE] == 'sort' )
			{
				if( $_POST[password] != $fidDB[passwd] )
				{
					echo "<A HREF=\"?fid=$fid&aid=$aid\">密码不正确,点击返回</A>";
					exit;
				}
				else
				{
					setcookie("sort_passwd_$fid",$fidDB[passwd]);
					$_COOKIE["sort_passwd_$fid"]=$fidDB[passwd];
				}
			}
			if( $_COOKIE["sort_passwd_$fid"] != $fidDB[passwd] )
			{
				echo "<CENTER><form name=\"form1\" method=\"post\" action=\"\">请输入栏目密码:<input type=\"password\" name=\"password\"><input type=\"hidden\" name=\"TYPE\" value=\"sort\"><input type=\"submit\" name=\"Submit\" value=\"提交\"></form></CENTER>";
				exit;
			}
		}
	}

	//积分处理
	if( $rsdb[money]=abs($rsdb[money])&&!in_array($fidDB[M_keyword],array('mv','download')) ){
		if(!$lfjuid)
		{
			showerr("请先登录,需要支付{$rsdb[money]}{$webdb[MoneyName]}才能查看");
		}
		elseif($web_admin)
		{
			 Remind_msg("本文设置了收费,因为你是管理员,所以可以查看,其他人是不能查看的");
		}
		elseif($lfjuid==$rsdb[uid])
		{
			 Remind_msg("本文设置了收费,因为你是发布者,所以可以查看,其他人是不能查看的");
		}
		elseif( !strstr($rsdb[buyuser],",$lfjid,") )
		{
			$lfjdb[money]=get_money($lfjuid);
			if($lfjdb[money]<$rsdb[money])
			{
				showerr("你的{$webdb[MoneyName]}不足$rsdb[money]");
			}
			elseif($buy==1)
			{
				add_user($lfjuid,"-$rsdb[money]");
				add_user($rsdb[uid],"$rsdb[money]");
				$rsdb[buyuser]=$rsdb[buyuser]?",{$lfjid}{$rsdb[buyuser]}":",$lfjid,";
				$erp=get_id_table($id);
				$db->query("UPDATE {$pre}article$erp SET buyuser='$rsdb[buyuser]' WHERE aid=$id");
				refreshto("?fid=$fid&id=$id","购买成功,你刚刚消耗了{$webdb[MoneyName]}{$rsdb[money]}{$webdb[MoneyDW]}",3);
			}
			else
			{
				showerr("你需要消耗{$webdb[MoneyName]}{$rsdb[money]}{$webdb[MoneyDW]}才有权限查看,是否继续<br><br>[<A HREF='?fid=$fid&id=$id&buy=1'>我要继续</A>]");
			}
		}
	}
}
?>