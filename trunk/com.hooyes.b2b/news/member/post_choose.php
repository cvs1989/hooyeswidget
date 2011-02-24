<?php
require_once("global.php");
$listsort=showsorts(0,0);
require(ROOT_PATH."member/head.php");
require(dirname(__FILE__)."/"."template/post_choose.htm");
require(ROOT_PATH."member/foot.php");
function showsorts($fup,$step){
	global $db,$_pre;	
	$step++;
	for($i=0;$i<$step ;$i++ ){
		$icon.="&nbsp;&nbsp;";
	}
	$query = $db->query("SELECT * FROM `{$_pre}sort` WHERE fup='$fup' ORDER BY list");
	while($rs = $db->fetch_array($query)){		
		if($rs['type']==1){
			$show .= "<a href=\"javascript:;\" class='b' onClick=\"noselect()\">".$icon.$rs['name']."</a>\n";
			$show .=showsorts($rs[fid],$step);
		}else{
			$show .= "<a href=\"javascript:;\" class='sort' onClick=\"thisselect(".$rs['fid'].",'".$rs['name']."')\">".$icon.$rs['name']."</a>\n";
		}		
	}	
	return $show;
}
?>