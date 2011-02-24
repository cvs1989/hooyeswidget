<?php
require(dirname(__FILE__)."/"."global.php");
$mid=intval($mid);
$keyword=str_replace("%","\%",$keyword);
$keyword=trim($keyword);
$rows=20;


if( ($_GET[type]||$_POST[type]) && !$keyword )
{
	showerr("关键字不能为空");
}


if( $_GET[keyword] || $_POST[keyword] )
{
	$search_module=0;
	if(!$type)
	{
		$type='username';
	}
	if(!$web_admin)
	{
		if(!$groupdb[SearchArticleType]){
			showerr("你所在用户组,无权使用搜索!");
		}
	}

	if($_POST[keyword]&&$_COOKIE[searchTime])
	{
		showerr("3秒钟内,请不要重复提交查询");
	}
	setcookie("searchTime",1,$timestamp+3);

	$SQL=" 1 ";

	if($type=='id')
	{
		$SQL.=" AND A.id = '$keyword' ";
	}
	elseif($type=='username')
	{
		$SQL.=" AND BINARY A.username = '$keyword' ";
	}
	elseif($type&&$mid&&is_table("{$_pre}content_$mid")&&table_field("{$_pre}content_$mid",$type))
	{
		$SQL.=" AND BINARY C.$type LIKE '%$keyword%' ";
		$search_module=1;
	}

	if($mid&&is_table("{$_pre}content_$mid"))
	{
		$search_url='';
		foreach( $postdb AS $key=>$value)
		{
			if( $value!='' && table_field("{$_pre}content_$mid",$key) )
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

	$_moduleSql=$_moduleSqlSearch="";
	if($search_module==1)
	{
		$_moduleSql=" LEFT JOIN {$_pre}content_$mid C ON A.id=C.id ";
		$_moduleSqlSearch=" C.*, ";
	}
	
	$showpage=getpage("{$_pre}content A $_moduleSql","WHERE $SQL","?type=$type&$search_url&keyword=".urlencode($_POST[keyword]).urlencode($_GET[keyword])."",$rows);

	unset($listdb);

	$query = $db->query("SELECT {$_moduleSqlSearch}A.* FROM {$_pre}content A $_moduleSql WHERE $SQL  ORDER BY A.id DESC LIMIT $min,$rows");
	while($rs = $db->fetch_array($query))
	{
		if(!$IS_BIZ)continue;
		$rs[posttime] = date("Y-m-d",$rs[posttime]);
		$listdb[] = $rs;
	}
	
	if(!$listdb)
	{
		showerr("很抱歉,没有找到你要查询的内容");
	}

	
	$typedb[$type]=' selected ';
}


$module_select="<select name='mid' onChange=\"window.location.href='?mid='+this.options[this.selectedIndex].value\">";
$query = $db->query("SELECT * FROM {$_pre}module ORDER BY list DESC");
while($rs = $db->fetch_array($query)){
	$ckk=$mid==$rs[id]?' selected ':' ';
	$module_select.="<option value='$rs[id]' $ckk>$rs[name]</option>";
}
$module_select.="</select>";


$type||$type='username';
$typedb[$type]=" checked ";


require(ROOT_PATH."inc/head.php");
require("data/form_tpl/search_$mid.htm");
require(ROOT_PATH."inc/foot.php");

?>