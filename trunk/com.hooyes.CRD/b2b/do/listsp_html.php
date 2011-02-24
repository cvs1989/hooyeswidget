<?php
require(dirname(__FILE__)."/"."global.php");
@include(PHP168_PATH."php168/guideSP_fid.php");		//专题栏目配置文件

if(!is_writable(PHP168_PATH."cache/makelist.php"))
{
	showerr("cache/makelist.php文件不存在,或文件不可写");
}

$GuideFid[$fid]=str_replace("list.php?fid=","listsp.php?fid=",$GuideFid[$fid]);

//栏目配置文件
$fidDB=$db->get_one("SELECT * FROM {$pre}spsort WHERE fid='$fid'");
if(!$fidDB){
	showerr("栏目有误");
}
$fidDB[config]=unserialize($fidDB[config]);

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
$showpage=getpage("{$pre}special","WHERE fid=$fid","listsp.php?fid=$fid",$rows);	//专题列表分页

require(PHP168_PATH."inc/head.php");
require(html("listsp",$FidTpl['list']));
require(PHP168_PATH."inc/foot.php");

$content=ob_get_contents();ob_end_clean();
$content=preg_replace("/<!--php168(.*?)php168-->/is","\\1",$content);
make_html($content,'listsp');


require_once(PHP168_PATH."cache/makelist.php");
$page++;
$min=($page-1)*$rows;
if($db->get_one("SELECT * FROM {$pre}special WHERE fid='$fid' LIMIT $min,1")){
	write_file(PHP168_PATH."cache/makelist_record.php","listsp_html.php?fid=$fid&page=$page&III=$III");
	echo "请稍候,正在生成专题列表页静态...$Ppage<META HTTP-EQUIV=REFRESH CONTENT='0;URL=?fid=$fid&page=$page&III=$III'>";
	exit;
}else{
	$III++;
	$page=1;
	$fiddb=explode(",", $allfid);
	if($fid=$fiddb[$III]){
		write_file(PHP168_PATH."cache/makelist_record.php","?fid=$fid&page=$page&III=$III");
		echo '<meta http-equiv="Content-Type" content="text/html; charset=gb2312" />';
		echo "请稍候,正在生成专题列表页静态...$fid<META HTTP-EQUIV=REFRESH CONTENT='0;URL=?fid=$fid&page=$page&III=$III'>";
		exit;
	}else{
		unlink(PHP168_PATH."cache/makelist_record.php");
		unlink(PHP168_PATH."cache/makelist.php");
		echo '<meta http-equiv="Content-Type" content="text/html; charset=gb2312" />';
		echo "专题列表静态页生成完毕,继续生成内容页<META HTTP-EQUIV=REFRESH CONTENT='0;URL=$weburl'>";
		exit;
	}
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
	$SQL="WHERE fid=$fid ORDER BY $ORDER $DESC LIMIT $min,$rows";
	$which='*';
	$listdb=list_special($SQL,$which,$leng);
	return $listdb;
}
?>