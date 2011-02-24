<?php
require("global.php");

//栏目配置文件
$fidDB=$db->get_one("SELECT * FROM {$pre}exam_sort WHERE fid='$fid'");
if(!$fidDB){
	showerr("栏目有误");
}

//SEO
$titleDB[title]		= "$fidDB[name] - $titleDB[title]";


$rows=30;
$page<1 && $page=1;
$min=($page-1)*$rows;

$showpage=getpage("`{$pre}exam_form`","WHERE fid='$fid'","?fid=$fid","$rows");

$query = $db->query("SELECT F.*,S.name AS fname FROM {$pre}exam_form F LEFT JOIN {$pre}exam_sort S ON F.fid=S.fid WHERE F.fid='$fid' ORDER BY F.id DESC LIMIT $min,$rows");
while($rs = $db->fetch_array($query)){
	if($rs[type]==1){
		$rs[type]="试卷";
	}elseif($rs[type]==2){
		$rs[type]="调查表";
	}
	$listdb[]=$rs;
}


require(PHP168_PATH."inc/head.php");
require(html("exam_list"));
require(PHP168_PATH."inc/foot.php");
 
?>