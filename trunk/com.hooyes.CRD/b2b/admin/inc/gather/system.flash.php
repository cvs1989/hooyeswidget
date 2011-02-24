<?php
if($Filedb)
{
	@extract($db->get_one(" SELECT name AS fname FROM {$pre}flash_sort WHERE fid='$fid' "));
	@extract($db->get_one(" SELECT $TB[username] AS uid FROM $TB[table] WHERE $TB[username]='$username' "));
	$copyfrom="采集所得";
	$yz=1;
	
	foreach($Filedb AS $key=>$flashurl){
		if(!$flashurl){
			continue;
		}
		$author=filtrate($author);
		$about=filtrate($about);
		$flashurl=filtrate($flashurl);
		$title=@preg_replace('/<([^>]*)>/is',"",$title);	//把HTML代码过滤掉
		$title=filtrate($title);

		/*判断是否有重复*/
		$rs=$db->get_one("SELECT id FROM {$pre}flash_swf WHERE flashurl='$flashurl'");
		if($rs){
			continue;
		}

		list($title,$picurl)=explode("@@",$title);

		$db->query("
		INSERT INTO `{$pre}flash_swf` (`fid` , `title` , `fname` , `albumid` , `albumname` , `info` , `hits` , `comments` , `posttime` , `list` , `uid` , `username` , `titlecolor` , `fonttype` , `picurl` , `yz` , `yzer` , `yztime` , `levels` , `levelstime` , `keywords` , `jumpurl` , `iframeurl` , `style` , `head_tpl` , `main_tpl` , `foot_tpl` , `target` , `ishtml` , `ip` , `lastfid` , `money` , `passwd` , `editer` , `edittime` , `begintime` , `endtime` , `flashsize` , `flashurl` , `content` , `author` ) 
		VALUES (
		'$fid','$title','$fname','$albumid','$albumname','$info','2','$comments','$timestamp','$timestamp','$uid','$username','$titlecolor','$fonttype','$picurl','1','$yzer','$yztime','$levels','$levelstime','$keywords','$jumpurl','$iframeurl','$style','$head_tpl','$main_tpl','$foot_tpl','$target','1','$onlineip','$lastfid','$money','$passwd','$editer','$edittime','$begintime','$endtime','$flashsize','$flashurl','$about','$author')
		");
	}
}
?>