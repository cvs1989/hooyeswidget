<?php
!function_exists('html') && exit('ERR');
require_once(PHP168_PATH."inc/artic_function.php");
$Guidedb->only=$only;
$Guidedb->mid=$mid;
$Guidedb->ifpost=1;
$Guidedb->forbidpost=1;

if(!$aid&&!$rid){
	$aid=$id;
}
if($rid)
{
	if(!$aid){
		showerr("aid不存在!");
	}
	$erp=get_id_table($aid);
	//修改主题或修改多页都可
	$rsdb=$db->get_one("SELECT R.*,A.* FROM {$pre}article$erp A LEFT JOIN {$pre}reply$erp R ON A.aid=R.aid WHERE R.rid='$rid'");
	$aid=$rsdb[aid];
	$fid=$rsdb[fid];
	$mid=$rsdb[mid];
}
elseif($aid)
{
	$erp=get_id_table($aid);
	//只能是修改主题/续发文章
	$rsdb=$db->get_one("SELECT R.*,A.* FROM {$pre}article$erp A LEFT JOIN {$pre}reply$erp R ON A.aid=R.aid WHERE A.aid='$aid' ORDER BY R.rid ASC LIMIT 1");
	isset($fid) || $fid=$rsdb[fid];
	$mid=$rsdb[mid];
}

if($only&&$mid===''){
	$listdb[]=array('id'=>0,'name'=>'文章模型');
	$query = $db->query("SELECT * FROM {$pre}article_module ORDER BY list DESC");
	while($rs = $db->fetch_array($query)){
		$listdb[]=$rs;
	}	
	foreach( $listdb AS $key=>$rs){
		$erp=$rs[iftable]?$rs[iftable]:"";
		@extract($db->get_one("SELECT COUNT(*) AS NUM FROM {$pre}article$erp WHERE mid='$rs[id]'"));
		$rs[NUM]=intval($NUM);
		$listdb[$key]=$rs;
	}
	require(dirname(__FILE__)."/"."head.php");
	require(dirname(__FILE__)."/"."template/post/choose_sort.htm");
	require(dirname(__FILE__)."/"."foot.php");
	exit;
}
//让用户选择栏目
if(!$fid&&!$only)
{
	$sortdb=array();
	list_post_allsort();
	$MSG="发表文章,请选择一个栏目";
	require(dirname(__FILE__)."/"."head.php");
	require(dirname(__FILE__)."/"."template/post/post_set.htm");
	require(dirname(__FILE__)."/"."foot.php");
	exit;
}

if($fid||$step){
	$fidDB=$db->get_one("SELECT * FROM {$pre}sort WHERE fid='$fid'");
	!$fidDB && showerr("栏目有误");
}
$job=='postnew' && !$mid && $mid=$fidDB[fmid];

if(in_array($lfjid,explode(',',$fidDB[admin]))||$userdb[groupid]==3||$userdb[groupid]==4)
{
	$web_admin=1;
}
if($fidDB&&!$web_admin&&!in_array($groupdb[gid],explode(',',$fidDB[allowpost])))
{
	showmsg("你所在用户组无权在本栏目“{$fidDB[name]}”有任何操作");
}

if(!$lfjid&&$job!='postnew')
{
	showmsg("游客无权操作");
}

$atc_power=0;
if($lfjid)
{
	if($web_admin||$lfjuid==$rsdb[uid]){
		$atc_power=1;
	}
}
$uid=isset($rsdb[uid])?$rsdb[uid]:$lfjuid;

if($job=='endHTML')
{
	$htmlurldb=get_html_url();
	//首页生成静态
	@unlink(PHP168_PATH."index.htm.bak");
	rename(PHP168_PATH."index.htm",PHP168_PATH."index.htm.bak");
	refreshto("index.php?lfj=artic&job=listartic&mid=$mid&only=$only","<CENTER>[<A HREF='?lfj=$lfj&job=postnew&fid=$fid&mid=$mid&only=$only'>发表新主题</A>] [<A HREF='?lfj=$lfj&job=post_more&aid=$aid&mid=$mid&only=$only'>续发本主题</A>] [<A HREF='index.php?lfj=artic&job=listartic&fid=$fid&mid=$mid&only=$only'>返回文章列表</A>] [<A HREF='{$htmlurldb[showurl]}' target=_blank>查看文章</A>] [<A HREF='?lfj=$lfj&job=manage&aid=$aid&mid=$mid&only=$only'>修改文章</A>]</CENTER>",60);
}
elseif($job=='manage')
{
	if(!$atc_power)showmsg("你没权限");
	if($rsdb[pages]<2){
		header("location:?lfj=$lfj&job=edit&aid=$aid&mid=$mid&only=$only");exit;
	}
	$erp=get_id_table($aid);
	if($step==2){
		asort($orderDB);
		$i=0;
		foreach( $orderDB AS $key=>$value){
			$i++;
			$db->query("UPDATE {$pre}reply$erp SET orderid=$i WHERE aid='$aid' AND rid='$key'");
		}
		refreshto("$FROMURL","排序成功",1);
	}
	if($rsdb[pages]>1){
		$MSG="修改文章";
		$i=0;
		$query = $db->query("SELECT * FROM {$pre}reply$erp WHERE aid='$aid' ORDER BY topic DESC,orderid ASC");
		while($rs = $db->fetch_array($query)){
			if(!$rs[subhead]){
				$rs[subhead]=$rsdb[title];
			}
			$rs[postdate]='';
			if($rs[postdate]){
				$rs[postdate]=date("Y-m-d H:i:s",$rs[postdate]);
			}
			$rs[i]=++$i;
			$listdb[]=$rs;
		}
		require(dirname(__FILE__)."/"."head.php");
		require(dirname(__FILE__)."/"."template/post/post_set.htm");
		require(dirname(__FILE__)."/"."foot.php");
		exit;
	}
}
elseif($action=="delelte")
{
	if(!$atc_power)showmsg("你没权限");
	//删除文章的函数
	delete_article($rsdb[aid],$rsdb[rid]);
	refreshto("$FROMURL","删除成功",1);
}

if($job=='edit'||$job=='post_more'||$job=='edit_more'){
	if(!$atc_power)showmsg("你没权限");
}

//对发表前与发表前做处理
require_once(PHP168_PATH."inc/check.postarticle.php");

if($job=='postnew'&&$Apower[artic_postnew])
{

	if($step=='post')
	{
		post_new();

		//生成静态
		make_article_html("$webdb[admin_url]/index.php?lfj=$lfj&job=endHTML&aid=$aid&mid=$mid&only=$only");

		$mid && $mid<106 && $none='none';

		refreshto("?lfj=$lfj&job=postnew&fid=$fid&mid=$mid&only=$only","<CENTER>[<A HREF='?lfj=$lfj&job=postnew&fid=$fid&mid=$mid&only=$only'>继续发表新主题</A>] <span style='display:$none;'>[<A HREF='?lfj=$lfj&job=post_more&fid=$fid&mid=$mid&aid=$aid&only=$only'>续发本主题</A>]</span> [<A HREF='index.php?lfj=artic&job=listartic&fid=$fid&mid=$mid&only=$only'>返回主题列表</A>] [<A HREF='../bencandy.php?fid=$fid&aid=$aid' target=_blank>查看主题</A>] [<A HREF='?lfj=$lfj&job=edit&aid=$aid&mid=$mid&only=$only'>点击修改</A>]</CENTER>",60);
	}
	$MSG='新发表内容';
	//$select_fid=list_post_selectsort(0,$fid,$mid,$only);
	$select_fid=$Guidedb->SelectIn("{$pre}sort",0,$fid);
	require(dirname(__FILE__)."/"."head.php");
	if($mid&&file_exists(PHP168_PATH."php168/admin_tpl/post_$mid.htm")){
		require(PHP168_PATH."php168/admin_tpl/post_$mid.htm");
	}else{
		require(dirname(__FILE__)."/"."template/post/post.htm");
	}
	require(dirname(__FILE__)."/"."foot.php");
}
elseif($job=='edit'&&$Apower[artic_postnew])
{
	if($rsdb[yz]&&!$web_admin&&$groupdb[EditPassPower]==2){
		showerr("已审核的文章,你不能再修改");
	}
	if($step=='post')
	{
		post_edit();

		//生成静态
		make_article_html("$webdb[admin_url]/index.php?lfj=$lfj&job=endHTML&aid=$aid&mid=$mid&only=$only");

		$mid && $mid<106 && $none='none';

		refreshto("$FROMURL","<CENTER>[<A HREF='?lfj=$lfj&job=postnew&fid=$fid&mid=$mid&only=$only'>发表新主题</A>] <span style='display:$none;'>[<A HREF='?lfj=$lfj&job=post_more&aid=$aid&mid=$mid&only=$only'>续发本主题</A>]</span> [<A HREF='index.php?lfj=artic&job=listartic&fid=$fid&mid=$mid&only=$only'>返回主题列表</A>] [<A HREF='../bencandy.php?fid=$fid&aid=$aid' target=_blank>查看主题</A>] [<A HREF='?lfj=$lfj&job=edit&aid=$aid&mid=$mid&only=$only'>继续修改</A>]</CENTER>",60);
	}
	$MSG='修改内容';
	//$select_fid=list_post_selectsort(0,$fid,$mid,$only);
	$select_fid=$Guidedb->SelectIn("{$pre}sort",0,$fid);
	require(dirname(__FILE__)."/"."head.php");
	if($mid&&file_exists(PHP168_PATH."php168/admin_tpl/post_$mid.htm")){
		require(PHP168_PATH."php168/admin_tpl/post_$mid.htm");
	}else{
		require(dirname(__FILE__)."/"."template/post/post.htm");
	}
	require(dirname(__FILE__)."/"."foot.php");
}
elseif($job=='post_more'&&$Apower[artic_postnew])
{
	if($step=='post')
	{
		//添加内容
		query_reply($aid,'','');

		//生成静态
		make_article_html("$webdb[admin_url]/index.php?lfj=$lfj&job=endHTML&aid=$aid&mid=$mid&only=$only");

		refreshto("$FROMURL","<CENTER>[<A HREF='?lfj=$lfj&job=postnew&fid=$fid&mid=$mid&only=$only'>发表新主题</A>] [<A HREF='?lfj=$lfj&job=post_more&aid=$aid&mid=$mid&only=$only'>续发本主题</A>] [<A HREF='index.php?lfj=artic&job=listartic&fid=$fid&mid=$mid&only=$only'>返回文章列表</A>] [<A HREF='../bencandy.php?fid=$fid&aid=$aid' target=_blank>查看文章</A>] [<A HREF='?lfj=$lfj&job=manage&aid=$aid&mid=$mid&only=$only'>修改文章</A>]</CENTER>",60);
	}
	$MSG='续发文章';
	unset($rsdb[content],$rsdb[subhead]);
	require(dirname(__FILE__)."/"."head.php");
	if($mid&&file_exists(PHP168_PATH."php168/admin_tpl/post_$mid.htm")){
		require(PHP168_PATH."php168/admin_tpl/post_$mid.htm");
	}else{
		require(dirname(__FILE__)."/"."template/post/post.htm");
	}
	require(dirname(__FILE__)."/"."foot.php");
}
elseif($job=='edit_more'&&$Apower[artic_postnew])
{
	if($step=='post')
	{
		//修改内容
		query_reply($aid,$rid,'edit');

		//生成静态
		make_article_html("$webdb[admin_url]/index.php?lfj=$lfj&job=endHTML&aid=$aid&mid=$mid&only=$only");

		refreshto("$FROMURL","<CENTER>[<A HREF='?lfj=$lfj&job=postnew&fid=$fid&mid=$mid&only=$only'>发表新主题</A>] [<A HREF='?lfj=$lfj&job=post_more&aid=$aid&mid=$mid&only=$only'>续发本主题</A>] [<A HREF='index.php?lfj=artic&job=listartic&fid=$fid&mid=$mid&only=$only'>返回文章列表</A>] [<A HREF='../bencandy.php?fid=$fid&aid=$aid' target=_blank>查看文章</A>] [<A HREF='?lfj=$lfj&job=edit_more&aid=$aid&rid=$rid&mid=$mid&only=$only'>修改文章</A>]</CENTER>",60);
	}
	$MSG='修改文章';
	require(dirname(__FILE__)."/"."head.php");
	if($mid&&file_exists(PHP168_PATH."php168/admin_tpl/post_$mid.htm")){
		require(PHP168_PATH."php168/admin_tpl/post_$mid.htm");
	}else{
		require(dirname(__FILE__)."/"."template/post/post.htm");
	}
	require(dirname(__FILE__)."/"."foot.php");
}

?>