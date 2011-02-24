<?php

if($Filedb)
{
	@extract($db->get_one(" SELECT name AS fname FROM {$pre}music_sort WHERE fid='$fid' "));
	@extract($db->get_one(" SELECT $TB[uid] AS uid FROM $TB[table] WHERE $TB[username]='$username' "));
	$copyfrom="采集所得";
	$yz=1;
	
	foreach($Filedb AS $key=>$songurl){
		if(!$songurl){
			continue;
		}

		//list($title,$picurl2)=explode("@@",$title);
		//$picurl2 && $picurl=$picurl2;
		echo "$picurl=$songurl";
		unset($content);
		$title=@preg_replace('/<([^>]*)>/is',"",$title);	//把HTML代码过滤掉
		$title=filtrate($title);
		$songurl=filtrate($songurl);
		$about=filtrate($about);
		$singer=filtrate($singer);
		$songurl=addslashes($songurl);
		/*判断是否有重复*/
		$rs=$db->get_one("SELECT * FROM {$pre}music_song WHERE songurl='$songurl'");
		if($rs){
			continue;
		}
		
		$db->query("INSERT INTO `{$pre}music_song` (`title`, `fid`, `fname`, `albumid`, `albumname`,`posttime`, `list`, `uid`, `username`, `titlecolor`, `fonttype`, `picurl`, `yz`, `yzer`, `yztime`, `levels`, `levelstime`, `keywords`, `jumpurl`, `iframeurl`, `style`, `head_tpl`, `main_tpl`, `foot_tpl`, `target`, `ishtml`, `ip`, `lastfid`, `money`, `passwd`, `editer`, `edittime`, `begintime`, `endtime`, `songurl`, `content`, `singer`) VALUES ('$title','$fid','$fname','$albumid','$albumname','$timestamp','$timestamp','$uid','$username','$titlecolor','$fonttype','$picurl','$yz','$yzer','$yztime','$levels','$levelstime','$keywords','$jumpurl','$iframeurl','$style','$head_tpl','$main_tpl','$foot_tpl','$target','$ishtml','$onlineip','$lastfid','$money','$passwd','$editer','$edittime','$begintime','$endtime','$songurl','$about','$singer')");
	}
}
?>