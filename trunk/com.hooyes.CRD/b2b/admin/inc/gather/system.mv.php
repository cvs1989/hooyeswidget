<?php
if($Filedb)
{
	@extract($db->get_one(" SELECT name AS fname FROM {$pre}mv_sort WHERE fid='$fid' "));
	@extract($db->get_one(" SELECT $TB[uid] AS uid FROM $TB[table] WHERE $TB[username]='$username' "));
	$copyfrom="采集所得";
	$yz=1;
	
	foreach($Filedb AS $key=>$videourl){
		if(!$videourl){
			continue;
		}
		list($title,$pic_url)=explode("@@",$title);
		$pic_url && $picurl=$pic_url;
		if($pic_url){
			$dir_id=$file_dir?$file_dir:$fid;
			$picurl="$dir_id/".rands(6).basename($pic_url);
			copy($pic_url,PHP168_PATH."$webdb[updir]/$picurl");
			if(!is_file(PHP168_PATH."$webdb[updir]/$picurl")){
				$picurl=$pic_url;
			}
		}
		echo "$picurl=$videourl";
		unset($content);
		$title=@preg_replace('/<([^>]*)>/is',"",$title);	//把HTML代码过滤掉
		$title=filtrate($title);
		$videourl=filtrate($videourl);
		$about=filtrate($about);
		/*判断是否有重复*/
		$rs=$db->get_one("SELECT id FROM {$pre}mv_video WHERE videourl='$videourl'");
		if($rs){
			continue;
		}

		$db->query("INSERT INTO `{$pre}mv_video` (`title` , `albumid` , `albumname` , `fid` , `fname` , `posttime` , `list` , `uid` , `username` , `titlecolor` , `fonttype` , `picurl` , `yz` , `keywords`, `ishtml` , `ip` , `content` , `videourl` ) VALUES ('$title','$albumid','$albumname','$fid','$fname','$timestamp','$timestamp','$uid','$username','$titlecolor','$fonttype','$picurl','$yz','$keywords','1','$onlineip','$about','$videourl')");
	}
}
?>