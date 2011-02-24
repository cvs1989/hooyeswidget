<?php
require("global.php");
header("Content-type: application/xml");
if(!$fid)
{
	$time=date('r',$timestamp);

echo '<?xml version="1.0" encoding="gbk"?>';
print <<<EOT

<rss version="2.0">
<channel>
 <title><![CDATA[{$webdb[webname]}]]></title>
 <link><![CDATA[{$webdb[www_url]}]]></link>
 <description><![CDATA[{$webdb[description]}]]></description>
 <copyright><![CDATA[Copyright(C) {$webdb[webname]}]]></copyright>
 <generator><![CDATA[powered by www.php168.com]]></generator>
 <lastBuildDate><![CDATA[{$time}]]></lastBuildDate>
  <image>
 <url><![CDATA[{$webdb[www_url]}/images/default/rss.gif]]></url>
 <title><![CDATA[PHP168CMS]]></title>
 <link><![CDATA[{$webdb[www_url]}]]></link>
 <description><![CDATA[{$webdb[webname]}]]></description>
</image>
EOT;

	$query = $db->query("SELECT * FROM {$pre}sort WHERE fup=0");
	while($rs = $db->fetch_array($query)){

print<<<EOT
<item>
 <title><![CDATA[{$rs[name]}]]></title>
 <description><![CDATA[]]></description>
 <link><![CDATA[{$webdb[www_url]}/list.php?fid={$rs[fid]}]]></link>
 <author><![CDATA[{$rs[admin]}]]></author>
 <category><![CDATA[{$rs[descrip]}]]></category>
 <pubdate><![CDATA[{$time}]]></pubdate>
</item>
EOT;
	}

}
elseif($fid&&!$id){
	$rsdb=$db->get_one("SELECT * FROM {$pre}sort WHERE fid='$fid'");
	$time=date('r',$timestamp);
	echo '<?xml version="1.0" encoding="gbk"?>';

print <<<EOT

<rss version="2.0">
<channel>
 <title><![CDATA[{$rsdb[name]}]]></title>
 <link><![CDATA[{$webdb[www_url]}/list.php?fid=$fid]]></link>
 <description><![CDATA[{$rsdb[descrip]}]]></description>
 <copyright><![CDATA[Copyright(C) {$webdb[webname]}]]></copyright>
 <generator><![CDATA[powered by www.php168.com]]></generator>
 <lastBuildDate><![CDATA[{$time}]]></lastBuildDate>
  <image>
 <url><![CDATA[{$webdb[www_url]}/images/default/rss.gif]]></url>
 <title><![CDATA[PHP168CMS]]></title>
 <link><![CDATA[{$webdb[www_url]}]]></link>
 <description><![CDATA[{$webdb[webname]}]]></description>
</image>
EOT;
	
	$erp=$Fid_db[iftable][$fid];
	$query = $db->query("SELECT * FROM {$pre}article$erp WHERE fid='$fid' AND yz=1 ORDER BY aid DESC LIMIT 30");
	while($rs = $db->fetch_array($query)){
		$rs[posttime]=date("r",$rs[posttime]);
print<<<EOT
<item>
 <title><![CDATA[{$rs[title]}]]></title>
 <description><![CDATA[{$rs[description]}]]></description>
 <link><![CDATA[{$webdb[www_url]}/bencandy.php?fid={$rs[fid]}&id={$rs[aid]}]]></link>
 <author><![CDATA[{$rs[username]}]]></author>
 <category><![CDATA[{$rs[keywords]}]]></category>
 <pubdate><![CDATA[{$rs[posttime]}]]></pubdate>
</item>
EOT;
	}
}

elseif($fid&&$id){
	$fidDB=$db->get_one("SELECT * FROM {$pre}sort WHERE fid='$fid'");
	$erp=$Fid_db[iftable][$fid];
	$rsdb=$db->get_one("SELECT A.*,R.content FROM {$pre}article$erp A LEFT JOIN {$pre}reply R ON A.aid=R.aid WHERE A.aid='$id' AND A.yz=1 LIMIT 1");
	//check_article($rsdb);
	$rsdb[content]=preg_replace("/(<([^<]+)>|	|&nbsp;|\n)/is","",$rsdb[content]);
	$rsdb[content]=str_replace(array(">","<"),array("",""),$rsdb[content]);
	$time=date('r',$rsdb[posttime]);
	echo '<?xml version="1.0" encoding="gbk"?>';

print <<<EOT

<rss version="2.0">
<channel>
 <title><![CDATA[{$rsdb[title]}]]></title>
 <link><![CDATA[{$webdb[www_url]}/bencandy.php?fid=$fid&id=$id]]></link>
 <description><![CDATA[{$rsdb[content]}]]></description>
 <copyright><![CDATA[Copyright(C) {$webdb[webname]}]]></copyright>
 <generator><![CDATA[powered by www.php168.com]]></generator>
 <lastBuildDate><![CDATA[{$time}]]></lastBuildDate>
  <image>
 <url><![CDATA[{$webdb[www_url]}/images/default/rss.gif]]></url>
 <title><![CDATA[PHP168CMS]]></title>
 <link><![CDATA[{$webdb[www_url]}]]></link>
 <description><![CDATA[{$fidDB[name]}]]></description>
</image>
EOT;
	 
	$query = $db->query("SELECT * FROM {$pre}reply WHERE aid='$id' AND topic=0 ORDER BY rid DESC LIMIT 30");
	while($rs = $db->fetch_array($query)){
		$rs[content]=preg_replace("/(<([^<]+)>|	|&nbsp;|\n)/is","",$rs[content]);
		$rs[content]=str_replace(array(">","<"),array("",""),$rs[content]);
		$rs[postdate]=date("r",$rs[postdate]);
print<<<EOT
<item>
 <title><![CDATA[{$rs[subhead]}]]></title>
 <description><![CDATA[{$rs[content]}]]></description>
 <link><![CDATA[{$webdb[www_url]}/bencandy.php?fid={$rs[fid]}&id={$rs[aid]}]]></link>
 <author><![CDATA[{$rsdb[username]}]]></author>
 <category><![CDATA[{$rsdb[keywords]}]]></category>
 <pubdate><![CDATA[{$rs[postdate]}]]></pubdate>
</item>
EOT;
	}
}
echo "
</channel></rss>";
?>