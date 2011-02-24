<?php
if($rsdb[type]=='iframe'){//类似奇虎
	$content="<A HREF='$curl' target='_blank'>$curl</A>";
}

//类似大旗
if($iframeurl){
	$content="<A HREF='$iframeurl' target='_blank'>$iframeurl</A>";
}

$content=mysql_escape_string($content);
if( eregi("@@",$title) )
{
	list($title,$picurl)=explode("@@",$title);
}
$title=@preg_replace('/<([^>]*)>/is',"",$title);	//把HTML代码过滤掉
$title=mysql_escape_string($title);

//处理不要采集标题相同的文章
$ForbidAdd='';
if($rsdb[gatherthesame]&&!$morepage){
	$ForbidAdd=$db->get_one("SELECT aid FROM {$pre}article$erp WHERE title='$title' ORDER BY aid DESC LIMIT 1");
}


if(!$picurl&&is_array($Filedb)){
	foreach( $Filedb AS $key=>$value){
		if(eregi("(\.png|\.jpg|\.gif)$",$value)){
			$picurl=$value;
			break;
		}
	}
}

//如果采集回本地后.需要对源地址做处理
if($Filedb&&$GetFile&&!$ForbidAdd){
	foreach( $Filedb AS $key=>$fileurl){
		$content=str_replace($oldFileDB[$key],tempdir("$fileurl"),$content);
		if( eregi("(jpg|gif|png)$",$fileurl) && ($webdb[if_gdimg]) ){
			//生成缩略图
			if( !$havemakesmallpic ){
				$Newpicpath=PHP168_PATH."$webdb[updir]/$fileurl.gif";
				gdpic(PHP168_PATH."$webdb[updir]/$fileurl",$Newpicpath,300,225,$webdb[autoCutSmallPic]?array('fix'=>1):'');
				if( filesize($Newpicpath)>1024*3 ){
					$picurl="$fileurl.gif";
					$havemakesmallpic++;
				}else{
					unlink($Newpicpath);
				}
			}
			//图片打水印
			if($webdb[is_waterimg]){
				include_once(PHP168_PATH."inc/waterimage.php");
				imageWaterMark(PHP168_PATH."$webdb[updir]/$fileurl",$webdb[waterpos],PHP168_PATH.$webdb[waterimg]);
			}
		}
	}
}elseif($Filedb){
	foreach( $Filedb AS $key=>$fileurl){
		$content=str_replace($oldFileDB[$key],"$fileurl",$content);
	}
}

//附件地址做处理
$content=En_TruePath($content);

//如果系统没设置自动生成缩略图.将取消
//$webdb[autoGetSmallPic] || $picurl='';


if($picurl){
	$ispic=1;
}else{
	$ispic=0;
}
//标题雷同时.存在一点BUG
if($morepage&&$rs=$db->get_one("SELECT aid FROM {$pre}article$erp WHERE title='$title' ORDER BY aid DESC LIMIT 1"))
{
	$aid=$rs[aid];
	$db->query(" UPDATE {$pre}article$erp SET pages=pages+1 WHERE aid='$rs[aid]' ");
	$db->query("INSERT INTO `{$pre}reply$erp` (  `aid` , `fid` ,uid,  `content`, ishtml) VALUES ( '$rs[aid]', '$fid','$userdb[uid]', '$content',1)");

	@extract($db->get_one("SELECT rid FROM {$pre}reply$erp WHERE aid='$rs[aid]' ORDER BY rid DESC LIMIT 1 "));
}
elseif(!$ForbidAdd)
{
	$fidDB=$db->get_one(" SELECT A.name,B.config,A.fmid FROM {$pre}sort A LEFT JOIN {$pre}article_module B ON A.fmid=B.id WHERE A.fid='$fid' ");
	$fname=$fidDB[name];

	@extract($db->get_one(" SELECT $TB[uid] AS uid FROM $TB[table] WHERE $TB[username]='$username' "));
	//$copyfrom="采集所得";
	$author=filtrate($author);
	$copyfrom=filtrate($copyfrom);
	$yz=1;

	//获取时间
	$posttime=get_time($posttime);
	$hits=intval($hits);

	$db->query("
	INSERT INTO `{$pre}article$erp` 
	( `title`, `mid`,  `fid`, `fname`, `pages`, `posttime`, `list`, `uid`, `username`,`copyfrom`, `copyfromurl`,  `picurl`,`ispic`, `yz`, `keywords`, `jumpurl`, `iframeurl`, `ip`,`author`,`hits`) 
	VALUES
	('$title','$fidDB[fmid]','$fid','$fname','1','$posttime','$posttime','$uid','$username','$copyfrom','$curl','$picurl','$ispic','$yz','$keywords','$jumpurl','$iframeurl','$onlineip','$author','$hits')
	");
	
	@extract($db->get_one("SELECT aid FROM {$pre}article$erp ORDER BY aid DESC LIMIT 1 "));

	$db->query("INSERT INTO `{$pre}article_db` (`aid`) VALUES ('$aid')");

	$db->query("INSERT INTO `{$pre}reply$erp` (  `aid` , `fid` ,`uid` ,  `content` ,`ishtml`,topic) VALUES ( '$aid', '$fid','$userdb[uid]', '$content','1',1)");
	
	//对其它模型自定义字段的采集.添加数据入库
	if($fidDB[config]){
		$SQL='';
		$M_config=unserialize($fidDB[config]);
		foreach( $M_config[field_db] AS $key=>$value){
			if($gather_module_valeDB[$key]){
				$v=addslashes($gather_module_valeDB[$key]);
				$SQL.=",`$key`='$v'";
			}
		}
		if($SQL){
			@extract($db->get_one("SELECT rid FROM {$pre}reply$erp ORDER BY rid DESC LIMIT 1 "));
			$db->query("INSERT INTO `{$pre}article_content_$fidDB[fmid]` SET aid='$aid',rid='$rid',fid='$fid',uid='$userdb[uid]'$SQL ");
		}
	}
}
?>