<?php
require(dirname(__FILE__)."/"."global.php");
require_once(PHP168_PATH."inc/class.inc.php");
$Guidedb=new Guide_DB;

$keyword=str_replace("%","\%",$keyword);
$keyword=trim($keyword);
$rows=20;


if( ($_GET[type]||$_POST[type]) && !$keyword )
{
	showerr("关键字不能为空");
}


if( $_GET[keyword] || $_POST[keyword] )
{
	if($mid=='-39'){
		//与39健康网的接口
		header("location:http://search.39.net/result.aspx?w=web&words=$keyword");
		exit;
	}
	$search_module=0;
	if(!$type)
	{
		$type='title';
	}
	if(!$web_admin)
	{
		if(!$groupdb[SearchArticleType]){
			showerr("你所在用户组,无权使用搜索!");
		}
		elseif($type=='content'&&$groupdb[SearchArticleType]!=2){
			showerr("你所在用户组,无权搜索文章内容!");
		}
	}

	if($_POST[keyword]&&$_COOKIE[searchTime])
	{
		showerr("3秒钟内,请不要重复提交查询");
	}
	setcookie("searchTime",1,$timestamp+3);

	$SQL=" A.yz=1 ";


	if($type=='keyword')
	{
		unset($array);
		$query = $db->query("SELECT B.aid FROM {$pre}keywordid B LEFT JOIN {$pre}keyword A ON A.id=B.id WHERE A.keywords='$keyword'");
		while($rs = $db->fetch_array($query)){
			$array[]=$rs[aid];
		}
		if($array){
			$SQL.=" AND A.aid IN (".implode(',',$array).") ";
		}else{
			$SQL.=" AND 0 ";
		}
	}
	elseif($type=='title')
	{
		$SQL.=" AND BINARY A.title LIKE '%$keyword%' ";
	}
	elseif($type=='id')
	{
		$SQL.=" AND A.aid = '$keyword' ";
	}
	elseif($type=='username')
	{
		$SQL.=" AND BINARY A.username = '$keyword' ";
	}
	elseif($type=='content')
	{
		$SQL.=" AND BINARY B.content LIKE '%$keyword%' ";
	}
	elseif($type&&$mid&&is_table("{$pre}article_content_$mid")&&table_field("{$pre}article_content_$mid",$type))
	{
		$SQL.=" AND BINARY C.$type LIKE '%$keyword%' ";
		$search_module=1;
	}

	if($mid&&is_table("{$pre}article_content_$mid"))
	{
		$erp=$article_moduleDB[$mid][iftable]?$article_moduleDB[$mid][iftable]:'';
		$search_url='';
		foreach( $postdb AS $key=>$value)
		{
			if( $value && table_field("{$pre}article_content_$mid",$key) )
			{
				$SQL.=" AND C.`$key`='$value' ";
				$rsdb[$key][$value]=" selected ";
				$search_module=1;
			}
			$value=urlencode($value);
			$search_url.="&postdb[{$key}]=$value";
		}
	}

	if($page<1)
	{
		$page=1;
	}
	$min=($page-1)*$rows;

	$_SQL=" AND B.topic=1 ";
	if($fid){
		$_SQL=" AND A.fid='$fid' ";
	}
	
	$_moduleSql=$_moduleSqlSearch="";
	if($search_module==1)
	{
		$_moduleSql=" LEFT JOIN {$pre}article_content_$mid C ON A.aid=C.aid ";
		$_moduleSqlSearch=" C.*, ";
	}
	
	$showpage=getpage("{$pre}article$erp A LEFT JOIN {$pre}reply B ON A.aid=B.aid $_moduleSql","WHERE $SQL $_SQL","search.php?fid=$fid&type=$type&$search_url&keyword=".urlencode($_POST[keyword]).urlencode($_GET[keyword])."",$rows);

	unset($listdb);

	$query = $db->query("SELECT {$_moduleSqlSearch}B.*,A.* FROM {$pre}article$erp A LEFT JOIN {$pre}reply B ON A.aid=B.aid $_moduleSql WHERE $SQL $_SQL ORDER BY A.aid DESC LIMIT $min,$rows");
	while($rs = $db->fetch_array($query))
	{
		$rs[posttime] = date("Y-m-d",$rs[posttime]);
		$rs[url]="$webdb[www_url]/bencandy.php?fid=$rs[fid]&id=$rs[aid]";
		$listdb[] = $rs;
	}
	
	if(!$listdb)
	{
		showerr("很抱歉,没有找到你要查询的内容");
	}

	
	$typedb[$type]=' selected ';
}


$module_select="<select name='mid' onChange=\"window.location.href='?mid='+this.options[this.selectedIndex].value\"><option value='0'  style='color:#aaa;'>所有模型</option>";
$query = $db->query("SELECT * FROM {$pre}article_module WHERE ifclose=0 ORDER BY list DESC");
while($rs = $db->fetch_array($query)){
	$ckk=$mid==$rs[id]?' selected ':' ';
	$module_select.="<option value='$rs[id]' $ckk>$rs[name]</option>";
}
$module_select.="</select>";


$fid_select="<select name='fid' onChange=\"if(this.options[this.selectedIndex].value=='-1'){alert('你不能选择大分类');}\"><option value='0' style='color:#aaa;'>所有栏目</option>";
$fid_select.=list_allsort(0,1,$fid);
$fid_select.="</select>";

$type||$type='title';
$typedb[$type]=" checked ";


require(PHP168_PATH."inc/head.php");
require(html($mid?"search_$mid":"search"));
require(PHP168_PATH."inc/foot.php");

//伪静态
if($webdb[NewsMakeHtml]==2)
{
	$content=ob_get_contents();
	ob_end_clean();
	ob_start();
	$content=fake_html($content);
	echo "$content";
}

//真静态
elseif($webdb[NewsMakeHtml]==1)
{
	$content=ob_get_contents();
	ob_end_clean();
	ob_start();			//备用
	$content=make_html($content,'N');
	echo "$content";
}


/*栏目列表*/
function list_allsort($fid,$Class,$ckfid){
	global $db,$pre,$listdb;
	$Class++;
	$query=$db->query("SELECT * FROM {$pre}sort WHERE fup='$fid' ORDER BY list DESC");
	while( $rs=$db->fetch_array($query) ){
		$icon="";
		for($i=1;$i<$Class;$i++){
			$icon.="&nbsp;|&nbsp;&nbsp;";
		}
 		if($icon){
			$icon=substr($icon,0,-12);
			$icon.="--";
		}
 		if($rs[type]){
			$fid_select.="<option value='-1' style='color:red;'>$icon$rs[name]</option>";
 		}else{
 			$ckk=$ckfid==$rs[fid]?' selected ':' ';
			$fid_select.="<option value='$rs[fid]' $ckk>$icon$rs[name]</option>";
 		}
		$fid_select.=list_allsort($rs[fid],$Class,$ckfid);
	}
	return $fid_select;
}
?>