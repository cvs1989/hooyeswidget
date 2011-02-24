<?php
require(dirname(__FILE__)."/"."global.php");


if($ctype){ //有CTYPE，说明是商务信息
	header("location:$webdb[www_url]/b/member/post.php?ctype=$ctype&fid=$fid&action=$action&id=$id");
}


require_once(PHP168_PATH."inc/artic_function.php");
require(PHP168_PATH."inc/class.inc.php");
$Guidedb=new Guide_DB;

unset($Article_Module);

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

//让用户选择栏目
if((!$fid&&!$only)||$jobs=="choose")
{
	$sortdb=array();
	if( count($Fid_db[name])>100||$fid ){
		$rows=50;
		$page<1 && $page=1;
		$min=($page-1)*$rows;
		$showpage=getpage("{$pre}sort","WHERE fup='$fid'","?lfj=$lfj&job=$job&jobs=$jobs&only=$only&mid=$mid&fid=$fid",$rows);
		$query = $db->query("SELECT * FROM {$pre}sort WHERE fup='$fid' ORDER BY list DESC,fid ASC LIMIT $min,$rows");
		while($rs = $db->fetch_array($query)){
			$rs[post]=$rs[NUM]=$rs[do_art]='';
			$detail_admin=@explode(",",$rs[admin]);
			$detail_allowpost=@explode(",",$rs[allowpost]);
			if(!$rs[type]&&( $web_admin||($lfjid&&@in_array($lfjid,$detail_admin))||@in_array($groupdb['gid'],$detail_allowpost) ))
			{	
				$erp=$Fid_db[iftable][$rs[fid]];
				$_rs=$db->get_one("SELECT COUNT(*) AS NUM FROM {$pre}article$erp WHERE fid='$rs[fid]' AND uid='$lfjuid'");
				if($_rs[NUM]&&$lfjid){
					$rs[NUM]="( <b>{$_rs[NUM]}</b> )";
					$rs[do_art]="<A HREF='myarticle.php?job=myarticle&fid=$rs[fid]' class='manage_article'>管理</A>";
				}
				$rs[post]="<A HREF='?job=postnew&fid=$rs[fid]' class='post_article'>发表</A>";
				$allowpost++;
			}
			$sortdb[]=$rs;
		}
		if($fid){
			$show_guide="<A HREF='?lfj=$lfj&jobs=$jobs&job=$job&only=$only&mid=$mid'>返回顶级目录</A> ".list_sort_guide($fid);
		}
	}else{		
		list_post_allsort();
		if(!$allowpost){
			showerr("你所在用户组无权发表文章",1);
		}
	}
	$MSG="请选择一个栏目投稿";
	require(dirname(__FILE__)."/"."head.php");
	require(dirname(__FILE__)."/"."template/post_set.htm");
	require(dirname(__FILE__)."/"."foot.php");
	exit;
}
if($fid||$step){
	$fidDB=$db->get_one("SELECT * FROM {$pre}sort WHERE fid='$fid'");
	!$fidDB && showerr("栏目有误");
}

$job=='postnew' && !$mid && $mid=$fidDB[fmid];

if($lfjid&&@in_array($lfjid,explode(',',$fidDB[admin])))
{
	$web_admin=1;
}
if($fidDB&&!$web_admin&&!in_array($groupdb[gid],explode(',',$fidDB[allowpost])))
{
	showerr("你所在用户组无权在本栏目“{$fidDB[name]}”有任何操作");
}

if(!$lfjid&&$job!='postnew')
{
	showerr("游客无权操作");
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
	refreshto("myarticle.php?job=myarticle&mid=$mid&only=$only","<CENTER>[<A HREF='?job=postnew&fid=$fid&mid=$mid&only=$only'>发表新主题</A>] [<A HREF='?job=post_more&aid=$aid&mid=$mid&only=$only'>续发本主题</A>] [<A HREF='myarticle.php?job=myarticle&fid=$fid&mid=$mid&only=$only'>返回文章列表</A>] [<A HREF='{$htmlurldb[showurl]}' target=_blank>查看文章</A>] [<A HREF='?job=manage&aid=$aid&mid=$mid&only=$only'>修改文章</A>]</CENTER>",60);
}
elseif($job=='manage')
{
	if(!$atc_power)showerr("你没权限");
	if($rsdb[pages]<2){
		header("location:post.php?job=edit&aid=$aid&mid=$mid&only=$only");exit;
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
		$MSG="修改内容";
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
		require(dirname(__FILE__)."/"."template/post_set.htm");
		require(dirname(__FILE__)."/"."foot.php");
		exit;
	}
}
elseif($action=="delelte")
{
	if(!$atc_power)showerr("你没权限");
	//删除文章的函数
	delete_article($rsdb[aid],$rsdb[rid]);
	refreshto("myarticle.php?job=myarticle&only=$only&mid=$mid","删除成功",1);
}

if($job=='edit'||$job=='post_more'||$job=='edit_more'){
	if(!$atc_power)showerr("你没权限");
}

//对发表前与发表前做处理
require_once(PHP168_PATH."inc/check.postarticle.php");

if($job=='postnew')
{

	if($step=='post')
	{
		post_new();

		//生成静态
		make_article_html("$webdb[www_url]/member/post.php?job=endHTML&aid=$aid&mid=$mid&only=$only");

		$mid && $mid<106 && $none='none';

		refreshto("?job=postnew&fid=$fid&mid=$mid&only=$only","<CENTER>[<A HREF='?job=postnew&fid=$fid&mid=$mid&only=$only'>继续发表新主题</A>] <span style='display:$none;'>[<A HREF='?job=post_more&fid=$fid&mid=$mid&aid=$aid&only=$only'>续发本主题</A>]</span> [<A HREF='myarticle.php?job=myarticle&fid=$fid&mid=$mid&only=$only'>返回主题列表</A>] [<A HREF='../bencandy.php?fid=$fid&aid=$aid' target=_blank>查看主题</A>] [<A HREF='?job=edit&aid=$aid&mid=$mid&only=$only'>点击修改</A>]</CENTER>",60);
	}
	$MSG='新发表内容';
	//$select_fid=list_post_selectsort(0,$fid,$mid,$only);
	$select_fid=$Guidedb->SelectIn("{$pre}sort",0,$fid);

	require(dirname(__FILE__)."/"."head.php");
	if($mid&&file_exists(PHP168_PATH."php168/member_tpl/post_$mid.htm")){
		require(PHP168_PATH."php168/member_tpl/post_$mid.htm");
	}else{
		require(dirname(__FILE__)."/"."template/post.htm");
	}
	require(dirname(__FILE__)."/"."foot.php");
}
elseif($job=='edit')
{
	if($rsdb[yz]&&!$web_admin&&$groupdb[EditPassPower]==2){
		showerr("已审核的文章,你不能再修改");
	}
	if($step=='post')
	{
		post_edit();

		//生成静态
		make_article_html("$webdb[www_url]/member/post.php?job=endHTML&aid=$aid&mid=$mid&only=$only");

		$mid && $mid<106 && $none='none';

		refreshto("$FROMURL","<CENTER>[<A HREF='?job=postnew&fid=$fid&mid=$mid&only=$only'>发表新主题</A>] <span style='display:$none;'>[<A HREF='?job=post_more&aid=$aid&mid=$mid&only=$only'>续发本主题</A>]</span> [<A HREF='myarticle.php?job=myarticle&fid=$fid&mid=$mid&only=$only'>返回主题列表</A>] [<A HREF='../bencandy.php?fid=$fid&aid=$aid' target=_blank>查看主题</A>] [<A HREF='?job=edit&aid=$aid&mid=$mid&only=$only'>继续修改</A>]</CENTER>",60);
	}
	$MSG='修改内容';
	//$select_fid=list_post_selectsort(0,$fid,$mid,$only);
	$select_fid=$Guidedb->SelectIn("{$pre}sort",0,$fid);
	require(dirname(__FILE__)."/"."head.php");
	if($mid&&file_exists(PHP168_PATH."php168/member_tpl/post_$mid.htm")){
		require(PHP168_PATH."php168/member_tpl/post_$mid.htm");
	}else{
		require(dirname(__FILE__)."/"."template/post.htm");
	}
	require(dirname(__FILE__)."/"."foot.php");
}
elseif($job=='post_more')
{
	if($step=='post')
	{
		//添加内容
		query_reply($aid,'','');

		//生成静态
		make_article_html("$webdb[www_url]/member/post.php?job=endHTML&aid=$aid&mid=$mid&only=$only");

		refreshto("$FROMURL","<CENTER>[<A HREF='?job=postnew&fid=$fid&mid=$mid&only=$only'>发表新主题</A>] [<A HREF='?job=post_more&aid=$aid&mid=$mid&only=$only'>续发本主题</A>] [<A HREF='myarticle.php?job=myarticle&fid=$fid&mid=$mid&only=$only'>返回文章列表</A>] [<A HREF='../bencandy.php?fid=$fid&aid=$aid' target=_blank>查看文章</A>] [<A HREF='?job=manage&aid=$aid&mid=$mid&only=$only'>修改文章</A>]</CENTER>",60);
	}
	$MSG='续发文章';
	unset($rsdb[content],$rsdb[subhead]);
	require(dirname(__FILE__)."/"."head.php");
	if($mid&&file_exists(PHP168_PATH."php168/member_tpl/post_$mid.htm")){
		require(PHP168_PATH."php168/member_tpl/post_$mid.htm");
	}else{
		require(dirname(__FILE__)."/"."template/post.htm");
	}
	require(dirname(__FILE__)."/"."foot.php");
}
elseif($job=='edit_more')
{
	if($step=='post')
	{
		//修改内容
		query_reply($aid,$rid,'edit');

		//生成静态
		make_article_html("$webdb[www_url]/member/post.php?job=endHTML&aid=$aid&mid=$mid&only=$only");

		refreshto("$FROMURL","<CENTER>[<A HREF='?job=postnew&fid=$fid&mid=$mid&only=$only'>发表新主题</A>] [<A HREF='?job=post_more&aid=$aid&mid=$mid&only=$only'>续发本主题</A>] [<A HREF='myarticle.php?job=myarticle&fid=$fid&mid=$mid&only=$only'>返回文章列表</A>] [<A HREF='../bencandy.php?fid=$fid&aid=$aid' target=_blank>查看文章</A>] [<A HREF='?job=edit_more&aid=$aid&rid=$rid&mid=$mid&only=$only'>修改文章</A>]</CENTER>",60);
	}
	$MSG='修改文章';
	require(dirname(__FILE__)."/"."head.php");
	if($mid&&file_exists(PHP168_PATH."php168/member_tpl/post_$mid.htm")){
		require(PHP168_PATH."php168/member_tpl/post_$mid.htm");
	}else{
		require(dirname(__FILE__)."/"."template/post.htm");
	}
	require(dirname(__FILE__)."/"."foot.php");
}



/**
*用户组选择
**/
function group_box($name="postdb[group]",$ckdb=array()){
	global $db,$pre;
	$query=$db->query("SELECT * FROM {$pre}group ORDER BY gid ASC");
	while($rs=$db->fetch_array($query))
	{
		$checked=in_array($rs[gid],$ckdb)?"checked":"";
		$show.="<input type='checkbox' name='{$name}[]' value='{$rs[gid]}' $checked>&nbsp;{$rs[grouptitle]}&nbsp;&nbsp;";
	}
	return $show;
}

/**
*模板选择
**/
function select_template($cname,$type=1,$ck=''){
	global $db,$pre;
	$show="<select name='$cname' $reto><option value='' style='color:red;'>请选择模板</option>";
	if($type==7||$type==8){
		//$show.="<option value='template/default/none.htm'>我要屏蔽</option>";
	}
	$query=$db->query("SELECT * FROM {$pre}template WHERE type='$type'");
	while($rs=$db->fetch_array($query))
	{
		if(!$rs[name]){
			$rs[name]="模板$rs[id]";
		}
		$rs[filepath]==$ck?$ckk='selected':$ckk='';
		$show.="  <option value='$rs[filepath]' $ckk>$rs[name]</option>";
	}
	 return $show." </select>";
}

/**
*风格选择
**/
function select_style($name='stylekey',$ck='',$url='',$select=''){
	if($url) 
	$reto=" onchange=\"window.location=('{$url}&{$name}='+this.options[this.selectedIndex].value+'')\"";
	$show="<select name='$name' $reto><option value=''>选择风格</option>";
	$filedir=opendir(PHP168_PATH."php168/style/");
	while($file=readdir($filedir)){
		if(ereg("\.php$",$file)){
			include PHP168_PATH."php168/style/$file";
			$ck==$styledb[keywords]?$ckk='selected':$ckk='';	//指定的某个
			/*只选定一个
			if($select){
				if($style_web!=$select){
					continue;
				}
			}
			*/
			$show.="<option value='$styledb[keywords]' $ckk style='color=blue'>$styledb[name]</option>";
		}
	}
	return $show." </select>";   
}

function list_sort_guide($fup){
	global $db,$pre,$mid,$only,$lfj,$job,$jobs;
	$rs=$db->get_one("SELECT fup,name FROM {$pre}sort WHERE fid='$fup'");
	if($rs){
		$show=" -> <A HREF='?lfj=$lfj&job=$job&jobs=$jobs&only=$only&mid=$mid&fid=$fup'>$rs[name]</A> ";
		$show=list_sort_guide($rs[fup]).$show;
	}
	return $show;
}
?>