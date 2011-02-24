<?php
require(dirname(__FILE__)."/"."global.php");
@include(PHP168_PATH."php168/guideSP_fid.php");		//专题栏目配置文件

if(!$fid&&$webdb[NewsMakeHtml]==2){
	//伪静态处理
	Explain_HtmlUrl();
}

$GuideFid[$fid]=str_replace("list.php?fid=","listsp.php?fid=",$GuideFid[$fid]);

//栏目配置文件
$fidDB=$db->get_one("SELECT * FROM {$pre}spsort WHERE fid='$fid'");
if(!$fidDB){
	showerr("栏目有误");
}
$fidDB[config]=unserialize($fidDB[config]);

//强制跳转到静态页
if($webdb[ForbidShowPhpPage]&&!$NeedCheck){
	$detail=get_SPhtml_url($fidDB);
	if(is_file(PHP168_PATH.$detail[listurl])){
		header("location:$detail[listurl]");
		exit;
	}
}

//SEO
$titleDB[title]			= filtrate("$fidDB[name] - $webdb[webname]");
$titleDB[keywords]		= filtrate("$fidDB[metakeywords]  $webdb[metakeywords]");
$titleDB[description]	= filtrate("$fidDB[descrip]");

//以栏目风格为标准
$fidDB[style] && $STYLE=$fidDB[style];

/*模板*/
$FidTpl=unserialize($fidDB[template]);
$head_tpl=$FidTpl['head'];
$foot_tpl=$FidTpl['foot'];


//显示子分类
$listdb_moresort=ListMoreSp();

//列表页多少篇专题
$rows=15;	

$listdb=ListThisSp($rows,$leng=50);		//本栏目专题列表
$showpage=getpage("{$pre}special","WHERE fid=$fid AND yz=1","listsp.php?fid=$fid",$rows);	//专题列表分页

require(PHP168_PATH."inc/head.php");
require(html("listsp",$FidTpl['list']));
require(PHP168_PATH."inc/foot.php");

//伪静态处理
if($webdb[NewsMakeHtml]==2)
{
	$content=ob_get_contents();
	ob_end_clean();
	ob_start();
	$content=fake_html($content);
	echo "$content";
}

/**
*子栏目
**/
function ListMoreSp(){
	global $db,$pre,$fid,$webdb,$fidDB;
	$order='list';
	$order && $_order=" ORDER BY $order DESC ";
	$rows=4;
	$leng=30;
	$query=$db->query("SELECT * FROM {$pre}spsort WHERE fup=$fid ORDER BY list DESC");
	while($rs=$db->fetch_array($query)){
		$SQL="WHERE fid=$rs[fid] $_order LIMIT $rows";
		$which='*';
		$rs[article]=list_special($SQL,$which,$leng);
		//如果本栏目不能获取到专题,将获取其所有子栏目的专题
		if(!$rs[article])
		{
			$array_fid=Get_SonFid("{$pre}spsort",$rs[fid]);
			if($array_fid)
			{
				$SQL="WHERE fid IN (".implode(',',$array_fid).") $_order LIMIT $rows";
				$rs[article]=list_special($SQL,$which,$leng);
			}
		}
		$rs[logo] && $rs[logo]=tempdir($rs[logo]);
		$listdb[]=$rs;
	}
	return $listdb;
}



function ListThisSp($rows,$leng=50){
	global $page,$fid,$fidDB,$webdb;
	if($page<1){
		$page=1;
	}
	$min=($page-1)*$rows;
	$DESC='DESC';
	$ORDER='list';
	$SQL="WHERE fid=$fid AND yz=1 ORDER BY $ORDER $DESC LIMIT $min,$rows";
	$which='*';
	$listdb=list_special($SQL,$which,$leng);
	return $listdb;
}
?>