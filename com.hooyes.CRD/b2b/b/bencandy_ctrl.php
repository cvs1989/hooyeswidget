<?php
require("global.php");


$rsdb=$db->get_one("SELECT A.id,A.uid,A.posttime,A.fid,A.title,A.picurl,B.my_price FROM `{$_pre}content` A LEFT JOIN `{$_pre}content_".$ctype."` B ON A.id=B.id WHERE A.id='$id'");
$rsdb[posttime]=$rsdb[posttime]?date("Y-m-d H:i:s",$rsdb[posttime]):"&nbsp;";
if($rsdb){

	$rsdb[picurl]=$rsdb[picurl]?$webdb[www_url].'/'.$webdb[updir].'/'.$Imgdirname.'/'.$rsdb[picurl]:"";
	//设置浏览记录//array_unshift
	$webdb[viewHistoryNums]=$webdb[viewHistoryNums]?$webdb[viewHistoryNums]:10;
	$myhistorylist=get_cookie("user_historylist"); 
	if(strpos($myhistorylist,"$rsdb[id]|||$rsdb[fid]|||$rsdb[title]|||$rsdb[picurl]|||$rsdb[my_price]")===false){
		$myhistorylist="$rsdb[id]|||$rsdb[fid]|||$rsdb[title]|||$rsdb[picurl]|||$rsdb[my_price]@@@".$myhistorylist;
	}
	$myhistorylist=explode("@@@",$myhistorylist);
	$myhistorylist=count($myhistorylist)>$webdb[viewHistoryNums]?array_slice($myhistorylist,10):$myhistorylist;
	$myhistorylist=implode("@@@",$myhistorylist);
	set_cookie('user_historylist',$myhistorylist);

}

if(($lfjuid&&$lfjuid==$rsdb[uid])){
	$show.="&nbsp;<a href=$Mdomain/member/?main=post.php?action=edit&id=$id&ctype=$ctype >修改</a>";
	$show.="&nbsp;<a href=$Mdomain/tg.php?action=posttgnew&fid=$fid&id=$id&ctype=$ctype>推广</a>&nbsp;";
}



echo "document.getElementById('bencandy_ctrl').innerHTML=\"".$show."\";";

?>