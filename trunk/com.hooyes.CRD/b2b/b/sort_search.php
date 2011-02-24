<?php
require("global.php");
$sort_key=trim($sort_key);
if($sort_key){
	$_sort_key=explode(" ",$sort_key);
}else{
	echo " <SCRIPT LANGUAGE=\"JavaScript\">
		<!--
		parent.document.getElementById('sort_search_rt_show').innerHTML=\"<center><font color='red'>请输入关键字</font></center>\";
		//-->
		</SCRIPT>";exit;
}

foreach($_sort_key as $key){
	$where_or[]=" name like('%$key%')";
    $sort_key_2[]="<font color=red>".$key."</font>";
}
$where=" where ".implode(' or ',$where_or);
$query=$db->query("select name,fid from {$_pre}sort $where  order by `best` desc limit 0,50;");
//echo "select name,fid from {$_pre}sort $where  order by `best` desc;";
while($rs=$db->fetch_array($query)){
	$rs[name]=str_replace($_sort_key,$sort_key_2,$rs[name]);
	$show.="<a href='$Mdomain/list.php?fid=$rs[fid]' style='width:100px;float:left'>$rs[name]</a>&nbsp;&nbsp;";
}

echo " <SCRIPT LANGUAGE=\"JavaScript\">
		<!--
		parent.document.getElementById('sort_search_rt_show').innerHTML=\"<strong>搜索结果：<font color='red'>$sort_key</font></strong>(多个关键字以空格隔开)$show\";
		//-->
		</SCRIPT>";
?>