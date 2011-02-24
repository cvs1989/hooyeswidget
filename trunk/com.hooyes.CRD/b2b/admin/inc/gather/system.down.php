<?php
if($Filedb)
{
	@extract($db->get_one(" SELECT name AS fname FROM {$pre}down_sort WHERE fid='$fid' "));
	@extract($db->get_one(" SELECT $TB[uid] AS uid FROM $TB[table] WHERE $TB[username]='$username' "));
	$copyfrom="采集所得";
	$yz=1;
	
	foreach($Filedb AS $key=>$downloadurl){
		if(!$downloadurl){
			continue;
		}
		$downloadurl=str_replace("FILE","",$downloadurl);

		unset($content);
		$title=@preg_replace('/<([^>]*)>/is',"",$title);	//把HTML代码过滤掉
		$title=filtrate($title);
		$downloadurl=filtrate($downloadurl);
		$about=mysql_escape_string($about);

		/*判断是否有重复*/
		$rs=$db->get_one("SELECT id FROM {$pre}down_software WHERE downloadurl='$downloadurl'");
		if($rs){
			continue;
		}
		//list($title,$picurl)=explode("@@",$title);
		$db->query("
		INSERT INTO `{$pre}down_software` ( `title` , `fid` , `fname` , `albumid` , `albumname` , `info` , `hits` , `comments` , `posttime` , `list` , `uid` , `username` , `titlecolor` , `fonttype` , `picurl` , `yz` , `yzer` , `yztime` , `levels` , `levelstime` , `keywords` , `jumpurl` , `iframeurl` , `style` , `head_tpl` , `main_tpl` , `foot_tpl` , `target` , `ishtml` , `ip` , `lastfid` , `money` , `passwd` , `editer` , `edittime` , `begintime` , `endtime` , `download` , `description` , `content` , `author` , `downloadurl` , `softsize` , `softversion` , `copyfrom` , `softlanguage` , `copyright` , `operatingsystem` , `demourl` , `loadnum` , `forbidguestdown` ) 
		VALUES (
		'$title','$fid','$fname','$albumid','$albumname','$info','$hits','$comments','$timestamp','$timestamp','$uid','$username','$titlecolor','$fonttype','$picurl','$yz','$yzer','$yztime','$levels','$levelstime','$keywords','$jumpurl','$iframeurl','$style','$head_tpl','$main_tpl','$foot_tpl','$target','1','$onlineip','$lastfid','$money','$passwd','$editer','$edittime','$begintime','$endtime','$download','$description','$about','$author','$downloadurl','$softsize','$softversion','$copyfrom','$softlanguage','$copyright','$operatingsystem','$demourl','$loadnum','$forbidguestdown')

		");
	}
}
?>