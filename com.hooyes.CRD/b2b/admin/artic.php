<?php
!function_exists('html') && exit('ERR');
require_once(PHP168_PATH."inc/artic_function.php");


$Guidedb->only=$only;
$Guidedb->mid=$mid;

//我的文章
if($job=='myarticle'&&$Apower[artic_myarticle])
{
	if($do=='del'){
		$erp=get_id_table($id);
		$rs=$db->get_one("SELECT * FROM {$pre}article$erp WHERE aid='$id' AND uid='$lfjuid' ");
		if(!$rs){
			showerr("文章不存在");
		}
		delete_article($id,$rid);
		//静态页处理
		make_article_html("$FROMURL",'del',$rs);
		refreshto("$FROMURL","删除成功",1);
	}

	if($only&&$mid===''){
		$listdb[]=array('id'=>0,'name'=>'文章模型');
		$query = $db->query("SELECT * FROM {$pre}article_module ORDER BY list DESC");
		while($rs = $db->fetch_array($query)){
			$listdb[]=$rs;
		}
		foreach( $listdb AS $key=>$rs){
			$erp=$article_moduleDB[$rs[id]][iftable]?$article_moduleDB[$rs[id]][iftable]:'';
			@extract($db->get_one("SELECT COUNT(*) AS NUM FROM {$pre}article$erp WHERE mid='$rs[id]' AND uid='$userdb[uid]'"));
			$rs[NUM]=intval($NUM);
			$listdb[$key]=$rs;
		}
		require(dirname(__FILE__)."/"."head.php");
		require(dirname(__FILE__)."/"."template/artic/choose_sort.htm");
		require(dirname(__FILE__)."/"."foot.php");
		exit;
	}

	if($page<1){
		$page=1;
	}
	$rows=50;
	$min=($page-1)*$rows;
	
	$_sql="";
	if($fid>0){
		$_sql=" AND fid='$fid' ";
	}
	if($only){
		$_sql.=" AND mid='$mid' ";
	}
	
	$SQL="WHERE uid=$lfjuid AND yz!=2 $_sql ORDER BY aid DESC LIMIT $min,$rows";
	$which='*';
	$showpage=getpage("{$pre}article","WHERE uid=$lfjuid AND yz!=2 $_sql","?lfj=$lfj&job=$job&fid=$fid&only=$only&mid=$mid",$rows);
	$listdb=list_article($SQL,$which,36);
	$listdb || $listdb=array();
	foreach( $listdb AS $key=>$rs){
		if($rs[pages]<1){
			$rs[pages]=1;
			$erp=get_id_table($rs[aid]);
			$db->query("UPDATE {$pre}article$erp SET pages=1 WHERE aid='$rs[aid]'");
		}
		if($rs[yz]==2){
			$rs[state]="作废";
		}elseif($rs[yz]==1){
			$rs[state]="<A style='color:red;'>已审</A>";
		}elseif(!$rs[yz]){
			$rs[state]="<A style='color:blue;'>待审</A>";
		}
		if($rs[levels]){
			$rs[levels]="<A style='color:red;'>已推荐</A>";
		}else{
			$rs[levels]="未推荐";
		}
		$listdb[$key]=$rs;
	}
	require(dirname(__FILE__)."/"."head.php");
	require(dirname(__FILE__)."/"."template/artic/myarticle.htm");
	require(dirname(__FILE__)."/"."foot.php");
}


//批量发图
elseif($job=="addpic"&&$Apower[artic_addpic])
{
	$Guidedb->ifpost=1;
	$Guidedb->forbidpost=1;
	$sort_fid=$Guidedb->Select("{$pre}sort","postdb[fid]",$fid);
	$sort_fid=str_replace("<select name='postdb[fid]'","<select id='fid' name='postdb[fid]'",$sort_fid);
	//非超级管理员,屏蔽一些栏目
	if($userdb['groupid']!=3&&$userdb['groupid']!=4&&!$founder)
	{
		unset($fiddb);
		$query = $db->query("SELECT fid,admin,allowpost FROM {$pre}sort");
		while($rs = $db->fetch_array($query))
		{
			$detail_admin=@explode(",",$rs[admin]);
			$detail_allowpost=@explode(",",$rs[allowpost]);
			if(@in_array($userdb[username],$detail_admin)||@in_array($userdb['groupid'],$detail_allowpost) )
			{
				$fiddb[]=$rs[fid];
			}
		}
		$fiddb || $fiddb=array();
		foreach( $fiddb AS $key=>$value)
		{
			$sort_fid=str_replace("value='$value'","value='@@@$value'",$sort_fid);
		}

		$sort_fid=preg_replace("/<option([^<>@]+)>([^<>]+)<\/option>/is","",$sort_fid);
		$sort_fid=str_replace("@@@","",$sort_fid);
	}
	$yz=" checked ";	
	require(dirname(__FILE__)."/"."head.php");
	require(dirname(__FILE__)."/"."template/artic/addpic.htm");
	require(dirname(__FILE__)."/"."foot.php");
}
/*发表组图*/
elseif($action=="addpic"&&$Apower[artic_addpic])
{
	if(!$postdb[fid]){
		showmsg("请选择一个栏目");
	}
	foreach( $photodb AS $key=>$value){
		if(!$value){
			unset($photodb[$key]);
		}
	}
	if(!$photodb){
		showmsg("请上传一张图片");
	}
	if(!$postdb[fid]){
		showmsg("请选择一个栏目");
	}
	$aidDB='';
	$ck=0;
	unset($aiddb);
	$II=1;
	$fidDB=$db->get_one(" SELECT * FROM {$pre}sort WHERE fid='$postdb[fid]' ");
	$fname=$fidDB[name];
	foreach($photodb AS $key=>$photo){

		//图片目录转移
		move_attachment($userdb[uid],tempdir($photo),"article/$postdb[fid]");
		if(file_exists(PHP168_PATH."$webdb[updir]/article/$postdb[fid]/".basename($photo))){
			$photo="article/$postdb[fid]/".basename($photo);
		}
		
		if($batch==0){
			$postdb[title]=$namedb[$key];
		}else{
			$postdb[title]=$title;
		}

		if( !$postdb[picurl] && $webdb[if_gdimg] )
		{
			$smallpic=str_replace(".","_",$photo).".gif";
			$Newpicpath=PHP168_PATH."$webdb[updir]/$smallpic";
			gdpic(PHP168_PATH."$webdb[updir]/$photo",$Newpicpath,200,150);
			if( file_exists($Newpicpath) )
			{
				$postdb[picurl]="$smallpic";
			}
			else
			{
				$postdb[picurl]="$photo";
			}
		}
		elseif(!$postdb[picurl])
		{
			$postdb[picurl]="$photo";
		}
		$postdb[content]=addslashes(En_TruePath("<CENTER><IMG onclick=window.open(this.src); src='".tempdir($photo)."' onload=makesmallpic(this,500,700); border=0><br><br>{$namedb[$key]}</CENTER>"));
		$postdb[yz]=1;
		$erp=$Fid_db[iftable][$postdb[fid]];
		if($batch==0||$ck==0)
		{
			$timestamp++;
			$db->query("
			INSERT INTO {$pre}article$erp ( `title`, `fid`,`fname`,`pages`, `posttime`, `list`, `uid`, `username`, `author`,`picurl`,`ispic`, `yz`, `keywords`,`style`, `template`, `target`,`ip` ,bak_id) 
			VALUES
			('$postdb[title]','$postdb[fid]','$fname','1','$timestamp','$timestamp','$userdb[uid]','$userdb[username]','$postdb[author]','$postdb[picurl]',1,'$postdb[yz]','$postdb[keywords]','$postdb[style]','$postdb[template]','$postdb[target]','$onlineip' ,'$postdb[bak_id]')
			");
			$rs=$db->get_one("SELECT * FROM {$pre}article$erp ORDER BY aid DESC LIMIT 1");

			$db->query("INSERT INTO {$pre}reply$erp (  `aid` , `fid` ,`uid` ,  `content` ,`ishtml`,`topic`) VALUES ( '$rs[aid]', '$postdb[fid]','$userdb[uid]', '$postdb[content]','1','1')");

			unset($postdb[picurl]);
			$aidDB[]=$rs[aid];
		}
		else
		{
			$db->query(" UPDATE {$pre}article$erp SET pages=pages+1 WHERE aid='$rs[aid]' ");
			$db->query("INSERT INTO {$pre}reply$erp (  `aid` , `fid` ,`uid` ,  `content` ,`ishtml`) VALUES ( '$rs[aid]', '$postdb[fid]','$userdb[uid]', '$postdb[content]','1')");
			$II++;
		}
		$ck++;
	}
	//静态页处理
	make_more_article_html("$webdb[admin_url]/index.php?lfj=artic&job=myarticle&fid=$postdb[fid]",'',$aidDB);
	jump("<CENTER>[<A HREF='index.php?lfj=artic&job=addpic&fid=$postdb[fid]&bak_id=$postdb[bak_id]'>继续发表新组图</A>] [<A HREF='index.php?lfj=artic&job=myarticle&fid=$postdb[fid]'>返回文章列表</A>] [<A HREF='$webdb[www_url]/bencandy.php?fid=$rs[fid]&aid=$rs[aid]' target=_blank>查看文章</A>] [<A HREF='index.php?lfj=post&job=manage&id=$rs[aid]'>点击修改</A>]</CENTER>","index.php?lfj=artic&job=postnew&fid=$postdb[fid]",600);
}

if($action=="delete"&&$Apower[artic_listartic])
{
	if(!$aid&&$id){
		$aid=$id;
	}
	$id=$aid;
	
	$erp=get_id_table($id);
	$rs=$db->get_one("SELECT A.*,B.admin FROM {$pre}article$erp A LEFT JOIN {$pre}sort B ON A.fid=B.fid WHERE A.aid='$id'");
	if($rs[uid]!=$lfjuid&&$lfjdb[groupid]!=3&&$lfjdb[groupid]!=5&&!in_array($lfjid,explode(",",$rs[admin]))){
		showmsg('你没权限');
	}
	delete_article($id,$rid);
	//静态页处理
	make_article_html("$FROMURL",'del',$rs);
	
	jump("删除成功","$FROMURL",1);
}
/**
*列出所有文章
**/
elseif($job=="listartic"&&$Apower[artic_listartic])
{
	if($only&&$mid===''){
		$listdb[]=array('id'=>0,'name'=>'文章模型');
		$query = $db->query("SELECT * FROM {$pre}article_module ORDER BY list DESC");
		while($rs = $db->fetch_array($query)){
			$listdb[]=$rs;
		}
		foreach( $listdb AS $key=>$rs){
			$erp=$rs[iftable]?$rs[iftable]:'';
			@extract($db->get_one("SELECT COUNT(*) AS NUM FROM {$pre}article$erp WHERE mid='$rs[id]'"));
			$rs[NUM]=intval($NUM);
			$listdb[$key]=$rs;
		}
		require(dirname(__FILE__)."/"."head.php");
		require(dirname(__FILE__)."/"."template/artic/choose_sort.htm");
		require(dirname(__FILE__)."/"."foot.php");
		exit;
	}
	$_sql_c="";
	$SQL=" 1 ";
	if(is_numeric($fid)){
		$SQL.=" AND A.fid=$fid ";
	}

	if($only){
		$SQL.=" AND A.mid='$mid' ";
	}
	$erp='';
	if($mid&&$article_moduleDB[$mid][iftable]){
		$erp=$article_moduleDB[$mid][iftable];
	}

	if($type=="yz"){
		$SQL.=" AND A.yz=1 ";
	}
	elseif($type=="unyz"){
		$SQL.=" AND A.yz=0 ";
	}
	elseif($type=="rubbish"){
		$SQL.=" AND A.yz=2 ";
	}
	elseif($type=="levels"){
		$SQL.=" AND A.levels!=0 ";
	}
	elseif($type=="unlevels"){
		$SQL.=" AND A.levels=0 ";
	}
	elseif($type=="my"){
		$SQL.=" AND A.uid='$userdb[uid]' ";
	}
	elseif($type=="picurl"){
		$SQL.=" AND A.ispic=1 ";
	}
	elseif($type=="title"){
		$SQL.=" AND binary A.title LIKE '%$keyword%' ";
	}
	elseif($type=="aid"){
		$SQL.=" AND A.aid='$keyword' ";
	}
	elseif($type=="content"){
		//未完善好
		$SQL.=" AND binary B.content LIKE '%$keyword%' ";
		$_sql_c=" LEFT JOIN {$pre}reply B ON A.aid=B.aid ";
	}
	elseif($type=="username"){
		$SQL.=" AND binary A.username LIKE '%$keyword%' ";
	}


	if($type!="my"&&!$founder&&$userdb[groupid]!=3&&$userdb[groupid]!=4)
	{
		unset($fiddb);
		$query = $db->query("SELECT fid FROM {$pre}sort WHERE binary admin LIKE '%,$userdb[username],%' ");
		while($rs = $db->fetch_array($query)){
			$fiddb[]=$rs[fid];
		}
		if(!$fiddb){
			echo "<SCRIPT LANGUAGE=\"JavaScript\">
			<!--
			alert('你没有可以管理的栏目,因为你不是超级管理员也不是前台管理员,也不是栏目管理员');
			//-->
			</SCRIPT>";
		}
		if($fiddb)
		{
			$_sql=implode(",",$fiddb);
			$SQL.=" AND A.fid IN ($_sql) ";
		}
		else
		{	
			//目的是不给再读取内容
			$SQL.=" AND A.fid='-1' ";
		}
	}

	$rows=50;
	if($page<1){
		$page=1;
	}
	$min=($page-1)*$rows;
	$order="A.aid";
	$desc="DESC";
	$showpage=getpage("{$pre}article$erp A","WHERE $SQL","index.php?lfj=artic&job=listartic&fid=$fid&type=$type&keyword=$keyword&only=$only&mid=$mid",$rows,"");
	$sort_fid=$Guidedb->Select("{$pre}sort","fid",$fid,"index.php?lfj=$lfj&job=listartic");
	$query=$db->query("SELECT * FROM {$pre}article$erp A WHERE $SQL ORDER BY $order $desc LIMIT $min,$rows");
	while($rs=$db->fetch_array($query))
	{
		$rs[ischeck]=$rs[yz]?"<A HREF='?lfj=$lfj&action=work&jobs=unyz&aid=$rs[aid]&only=$only&mid=$mid' title='已经通过审核,点击可取消审核'><img src='../member/images/check_yes.gif' border=0></A>":"<A HREF='?lfj=$lfj&action=work&jobs=yz&aid=$rs[aid]&only=$only&mid=$mid' style='color:blue;' title='还没有通过审核,点击可通过审核'><img src='../member/images/check_no.gif' border=0></A>";
		$rs[iscom]=$rs[levels]?"<A HREF='?lfj=$lfj&action=work&jobs=uncom&aid=$rs[aid]&levels=0&only=$only&mid=$mid' style='color:red;' title='已推荐,点击可取消推荐'><img src='../images/default/good_ico.gif' border=0></A>":"<A HREF='?lfj=$lfj&action=work&jobs=com&aid=$rs[aid]&levels=1&only=$only&mid=$mid' title='未推荐,点击可设置为推荐'><img src='../member/images/nogood_ico.gif' border=0></A>";
		$rs[title2]=urlencode($rs[title]);
		$rs[posttime]=date("Y-m-d",$rs[posttime]);
		$rs[pages]<1 && $rs[pages]=1;
		$rs[yz]==2 && $rs[fname]="<A HREF='?lfj=$lfj&action=work&jobs=return&listdb[]=$rs[aid]&only=$only&mid=$mid' style='color:blue;' onclick=\"return confirm('你确认要从回收站取回它吗?')\">回收站</A>";
		$listdb[$rs[aid]]=$rs;
	}
	require(dirname(__FILE__)."/"."head.php");
	require(dirname(__FILE__)."/"."template/artic/article_menu.htm");
	require(dirname(__FILE__)."/"."template/artic/listartic.htm");
	require(dirname(__FILE__)."/"."foot.php");
}
elseif($job=="work"&&$Apower[artic_listartic])
{
	if(!$listdb){
		showmsg("请选择一篇文章");
	}
	if($jobs=="move"){
		$sort_fid=$Guidedb->Select("{$pre}sort","fid");
	}elseif($jobs=="special"){
		$special_select=special_select($name='spid');
	}
	require(dirname(__FILE__)."/"."head.php");
	require(dirname(__FILE__)."/"."template/artic/article_menu.htm");
	require(dirname(__FILE__)."/"."template/artic/work.htm");
	require(dirname(__FILE__)."/"."foot.php");
}
elseif($action=="work"&&$Apower[artic_listartic])
{
	if(!$listdb&&!$aid){
		showmsg("请选择一篇文章");
	}
	
	$url=$fromurl?$fromurl:$FROMURL;

	if(!is_array($listdb)&&$aid)
	{
		$listdb[$aid]=$aid;
	}
	
	if($jobs=='delete'){
		make_more_article_html("$webdb[admin_url]/index.php?lfj=artic&job=listartic","del_0",$listdb);
	}

	foreach($listdb AS $key=>$value){
		do_work($value,$jobs,1);
	}
	
	if($jobs=='delete'){
		make_more_article_html("$webdb[admin_url]/index.php?lfj=artic&job=listartic","del_1",$listdb);
	}else{
		make_more_article_html("$webdb[admin_url]/index.php?lfj=artic&job=listartic",$jobs,$listdb);
	}
	jump("操作成功",$url,0);
}
?>