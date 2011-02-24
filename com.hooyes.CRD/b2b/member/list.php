<?php
require(dirname(__FILE__)."/"."global.php");
require_once(PHP168_PATH."inc/artic_function.php");

if(!$lfjuid){
	showerr("请先登录");
}

/*************************
*处理提交后的操作
*************************/
if($step==2){
	if(!$aidDB){
		showerr("请至少选择一篇文章");
	}elseif(!$Type){
		showerr("请选择操作目标,是删除还是审核等...");
	}	
	if($Type=='yz'){
		if($T_yz<1){
			$Type=='unyz';
		}
	}elseif($Type=='leavels'){
		if($levels<1){
			$Type='uncom';
		}else{
			$levels=1;
			$Type='com';
		}
	}
	if($Type=='delete'){
		make_more_article_html("$FROMURL","del_0",$aidDB);
	}
	foreach( $aidDB AS $key=>$value){
		do_work($value,$Type,1);
		
	}
	if($Type=='delete'){
		make_more_article_html("$FROMURL","del_1",$aidDB);
	}else{
		make_more_article_html("$FROMURL",$Type,$aidDB);
	}	
	refreshto("$FROMURL","操作成功",1);
}


$linkdb=array(
			"全部文章"=>"?",
			"已审核的文章"=>"?Type=yz&fid=$fid",
			"未审核的文章"=>"?Type=unyz&fid=$fid",
			"回收站"=>"?Type=rubbish&fid=$fid",
			"精华文章"=>"?Type=levels&fid=$fid",
			"有缩略图的文章"=>"?Type=pic&fid=$fid"
			);

$fid=intval($fid);
unset($fiddb);

//超级管理员
if($web_admin)
{
	require(PHP168_PATH."inc/class.inc.php");
	$Guidedb=new Guide_DB;
	$sort_fid=$Guidedb->Select("{$pre}sort","fid",$fid);
	if($fid){
		$_SQL=" WHERE fid=$fid ";
	}else{
		$_SQL=" WHERE 1 ";
	}
}
//版主权限检查
else
{
	$sort_fid="<select name='fid'><option value=''>所有栏目</option>";
	$query = $db->query("SELECT * FROM {$pre}sort WHERE admin!=''");
	while($rs = $db->fetch_array($query)){
		$detail=explode(",",$rs[admin]);
		if(in_array($lfjid,$detail)){
			$fiddb[]=$rs[fid];
			if($fid==$rs[fid]){
				$_selected=" selected ";
			}else{
				$_selected="";
			}
			$sort_fid.="<option value='$rs[fid]' $_selected>$rs[name]</option>";
		}
	}
	$sort_fid.="</select>";
	if(!$fiddb){
		showerr("你无权管理");
	}

	if($fid&&in_array($fid,$fiddb)){
		$_SQL=" WHERE fid=$fid ";
	}else{
		$fids=implode(",",$fiddb);
		$_SQL="WHERE fid IN ($fids)";
	}
}

if($page<1){
	$page=1;
}
$rows=20;
$min=($page-1)*$rows;

if($Type=="yz"){
	$_SQL.=" AND yz=1 ";
}elseif($Type=="unyz"){
	$_SQL.=" AND yz=0 ";
}elseif($Type=="rubbish"){
	$_SQL.=" AND yz=2 ";
}elseif($Type=="levels"){
	$_SQL.=" AND levels=1 ";
}elseif($Type=="pic"){
	$_SQL.=" AND ispic=1 ";
}

$SQL="$_SQL ORDER BY list DESC LIMIT $min,$rows";
$which='*';
$listdb=list_article($SQL,$which,40);

$showpage=getpage("{$pre}article","$_SQL","?fid=$fid",$rows);

foreach( $listdb AS $key=>$rs){
	if($rs[yz]==2){
		$rs[state]="<A style='color:red;' onclick=\"return confirm('你确认要从回收站取回它吗?')\" href='?Type=return&aidDB[]=$rs[aid]&step=2'>回收站</A>";
	}elseif($rs[yz]==1){
		$rs[state]="<A style='color:;' onclick=\"return confirm('你确认要取消验证吗?')\" href='?Type=unyz&aidDB[]=$rs[aid]&step=2'>已审</a>";
	}elseif(!$rs[yz]){
		$rs[state]="<A style='color:blue;' href='?Type=yz&aidDB[]=$rs[aid]&step=2'>待审</A>";
	}
	if($rs[levels]){
		$rs[levels]="<A style='color:red;' onclick=\"return confirm('你确认要取消推荐吗?')\" href='?Type=uncom&aidDB[]=$rs[aid]&step=2'>已推荐</A>";
	}else{
		$rs[levels]="<A style='color:blue;' href='?Type=com&aidDB[]=$rs[aid]&step=2'>未推荐</a>";
	}
	$listdb[$key]=$rs;
}

$choose_fid=str_replace("<select name='fid'","<select onchange=\"window.location=('?fid='+this.options[this.selectedIndex].value+'')\"",$sort_fid);

require(dirname(__FILE__)."/"."head.php");
require(dirname(__FILE__)."/"."template/list.htm");
require(dirname(__FILE__)."/"."foot.php");
 
?>