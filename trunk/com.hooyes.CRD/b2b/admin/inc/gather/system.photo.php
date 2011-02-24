<?php
if($Filedb)
{
	@extract($db->get_one(" SELECT name AS fname FROM {$pre}photo_sort WHERE fid='$fid' "));
	@extract($db->get_one(" SELECT $TB[uid] AS uid FROM $TB[table] WHERE $TB[username]='$username' "));
	$copyfrom="采集所得";
	$yz=1;
	//
	foreach($Filedb AS $key=>$photo){
		if(!$photo){
			continue;
		}
		list($title,$pic_url)=explode("@@",$title);
		if($pic_url&&$GetFile){
			$dir_id=$file_dir?$file_dir:$fid;
			$picurl="$dir_id/".rands(6).basename($pic_url);

			if( $getfilecontent=sockOpenUrl($pic_url) ){
				write_file(PHP168_PATH."$webdb[updir]/$picurl",$getfilecontent);
			}else{
				copy($pic_url,PHP168_PATH."$webdb[updir]/$picurl");
			}
			
		}elseif($pic_url){
			$picurl=$pic_url;
		}
		if(!$pic_url){
			if($GetFile){
				$dir_id=$file_dir?$file_dir:$fid;
				$picurl="$dir_id/".rands(6).basename($photo);
				copy($photo,PHP168_PATH."$webdb[updir]/$picurl");
			}else{
				$picurl=$photo;
			}
		}
		echo "$title--$picurl--$photo<hr>";//die();break;
		unset($content);
		$title=@preg_replace('/<([^<]*)>/is',"",$title);	//把HTML代码过滤掉
		$title=filtrate($title);
		$photo=filtrate($photo);
		$author=filtrate($author);
		$about=filtrate($about);
		/*判断是否有重复*/
		$rs=$db->get_one("SELECT id FROM {$pre}photo_pic WHERE photo='$photo'");
		if($GetFile&&$rs){
			continue;
		}
		
		$db->query("
		INSERT INTO `{$pre}photo_pic` (`title`, `albumid` , `fid` , `fname` , `hits` ,   `posttime` , `list` , `uid` , `username` ,  `picurl` , `yz` , `keywords` , `jumpurl` , `iframeurl` ,  `head_tpl` , `main_tpl` , `foot_tpl` , `target` , `ishtml` , `ip` ,  `content` , `photo` ) 
		VALUES (
		'$title','$albumid','$fid','$fname', '$hits', '$timestamp','$timestamp','$uid','$username','$picurl','$yz','$keywords','$jumpurl','$iframeurl','$head_tpl','$main_tpl','$foot_tpl','$target','1','$onlineip','$about','$photo')
		");
	}
}
?>