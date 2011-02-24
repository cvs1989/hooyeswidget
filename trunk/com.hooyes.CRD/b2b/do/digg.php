<?php
require_once(dirname(__FILE__)."/"."global.php");


//列表页多少篇文章
$rows=20;	

$fidDB[listorder]=8;
$listdb=ListThisSort($rows,50);		//本栏目文章列表
$showpage=getpage("{$pre}article","WHERE yz=1","?",$rows);	//文章列表分页

require(PHP168_PATH."inc/head.php");
require(html("digg"));
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

?>