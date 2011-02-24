<?php
!function_exists('html') && exit('ERR');
include_once("../php168/showhtmltype.php");
include_once("../php168/htmltype.php");
if($action=='googlemap'&&$Apower[googlemap_makemap])
{
	$systemdb[]='article';
	set_time_limit(0);
	index_map($systemdb);
	foreach( $systemdb AS $key=>$value){
		system_map($value);
	}
	jump("生成完毕",$FROMURL);
}
elseif($action=='baidumap'&&$Apower[googlemap_makemap])
{
	set_time_limit(0);
	baidu_map();
	jump("生成完毕",$FROMURL);
}
elseif($job=='makemap'&&$Apower[googlemap_makemap])
{
	require(dirname(__FILE__)."/"."head.php");
	require(dirname(__FILE__)."/"."template/googlemap/makemap.htm");
	require(dirname(__FILE__)."/"."foot.php");
}

function index_map($array){
	global $webdb;
	foreach( $array AS $key=>$value){
		$showdb[]="<sitemap>\r\n<loc>$webdb[www_url]/GoogleMap_$value.xml</loc>\r\n</sitemap>";
	}
	$show='<?xml version="1.0" encoding="GB2312"?>'."\r\n";
	$show.='<sitemapindex xmlns="http://www.google.com/schemas/sitemap/0.84">'."\r\n";
	$show.=implode("\r\n",$showdb);
	$show.="\r\n".'</sitemapindex>'."\r\n";
	write_file(PHP168_PATH."sitemap.xml",$show);
}

function system_map($type){
	global $pre,$db,$webdb;
	$query = $db->query("SELECT fid,aid FROM {$pre}article WHERE mid=0 AND yz=1 ORDER BY aid DESC LIMIT 1000");
	while($rs = $db->fetch_array($query)){
		$fid = $rs[fid];
		$id = $rs[aid];
		
		if($webdb[NewsMakeHtml]==1){
			global $rsdb,$aid,$fidDB,$fid,$page;
			$rsdb = $rs;
			$aid  = $rs[aid];
			$fid  = $rs[fid];
			$page = 1;
			$fidDB= $db->get_one("SELECT * FROM {$pre}sort WHERE fid='$rs[fid]'");
			$array= get_html_url();
			$showurl  = $array[showurl];
			$listurl  = $array[listurl];
			if(!$_fiddb[$fid]){
				$showdb[]="<url>\r\n<loc>$listurl</loc>\r\n</url>";
			}
			$showdb[]="<url>\r\n<loc>$showurl</loc>\r\n</url>";
		}else{
			if(!$_fiddb[$fid]){
				$showdb[]="<url>\r\n<loc>".replace_url("$webdb[www_url]/list.php?fid=$fid")."</loc>\r\n</url>";
			}
			$showdb[]="<url>\r\n<loc>".replace_url("$webdb[www_url]/bencandy.php?fid=$fid&id=$id")."</loc>\r\n</url>";
		}
		
		$_fiddb[$fid]++;
		
	}
	$show='<?xml version="1.0" encoding="GB2312"?>'."\r\n";
	$show.='<urlset xmlns="http://www.google.com/schemas/sitemap/0.84">'."\r\n";
	$show.=implode("\r\n",$showdb);
	$show.="\r\n".'</urlset>'."\r\n";
	write_file(PHP168_PATH."GoogleMap_article.xml",$show);
}

function replace_url($url){
	$url=str_replace("&","&amp;",$url);
	$url=str_replace("'","&apos;",$url);
	$url=str_replace("\"","&quot;",$url);
	$url=str_replace("\"","&quot;",$url);
	$url=str_replace(">","&gt;",$url);
	$url=str_replace("<","&lt;",$url);
	return $url;
}

function baidu_map(){
	global $pre,$db,$webdb;
	$query = $db->query("SELECT A.*,R.content FROM {$pre}article A LEFT JOIN {$pre}reply R ON A.aid=R.aid WHERE A.mid=0 AND A.yz=1 AND R.topic=1 ORDER BY A.aid DESC LIMIT 100");
	while($rs = $db->fetch_array($query)){
		if($webdb[NewsMakeHtml]==1){
			global $rsdb,$aid,$fidDB,$fid,$page;
			$rsdb = $rs;
			$aid  = $rs[aid];
			$fid  = $rs[fid];
			$page = 1;
			$fidDB= $db->get_one("SELECT * FROM {$pre}sort WHERE fid='$rs[fid]'");
			$array= get_html_url();
			$url  = $array[showurl];
		}else{
			$url = replace_url("$webdb[www_url]/bencandy.php?fid=$rs[fid]&id=$rs[aid]");
		}
		
		$time=date("Y-m-d H:i:s",$rs[posttime]);
		$rs[picurl] && $rs[picurl]=tempdir($rs[picurl]);
		$content=get_word(preg_replace("/(<([^<]+)>|	|&nbsp;|\n)/is","",$rs[content]),500);
		$content=str_replace(array(">","<"),array("",""),$content);
		$showdb[]="<item>\r\n<link>$url</link>\r\n<title>$rs[title]</title>\r\n<text>$content</text>\r\n<image>$rs[picurl]</image>\r\n<category>$rs[fname]</category>\r\n<pubDate>$time</pubDate>\r\n</item>\r\n";
	}
	$show='<?xml version="1.0" encoding="GB2312"?>'."\r\n";
	$show.='<document>'."\r\n";
	$show.="<webSite>$webdb[www_url]/</webSite>"."\r\n";
	$show.="<webMaster>$webdb[webmail]</webMaster>"."\r\n";
	$show.="<updatePeri>1440</updatePeri>"."\r\n";
	$show.=implode("\r\n",$showdb);
	$show.="\r\n".'</document>'."\r\n";
	write_file(PHP168_PATH."baidu_MAP.xml",$show);
}
?>