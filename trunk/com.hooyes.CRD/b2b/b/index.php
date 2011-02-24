<?php
if(is_file('install.php')){
	header("location:install.php");exit;
}

require("global.php");

//域名判断
if(!preg_match("/[\d\.]{7,15}/",$HTTP_HOST)){
	$t=explode(".",$HTTP_HOST);
	$host=$t[0];
}
if($host){
	$limitmain=explode(",",$webdb[vipselfdomaincannot]);
	if(!in_array($host,$limitmain)){
		if(!preg_match("/^[a-z\d]{2,12}$/",$host))  showerr("抱歉，访问地址不符合规定");
		$rt=$db->get_one("select uid from  {$_pre}company where host='$host'");
		if($rt[uid]){
			$url="homepage.php?uid=".$rt[uid];
			header("location:".$url);
			echo "<META HTTP-EQUIV=REFRESH CONTENT='0;URL=$url'>";
			exit;
		}
	}
	
}
if($jobs != 'show')
	cache_page(PHP_SELF);

//SEO
$titleDB[title]		= $webdb[Info_webname];
$titleDB[keywords]	= $webdb[Info_metakeywords];
require(PHP168_PATH."/php168/friendlink.php");

//print_r($label);//exit;

/**
*推荐的栏目在首页显示
**/
/*

$InfoNum=get_infonum();*/

$listdb_moresort=array();
foreach($Fid_db[best] as $key=>$val){
	$info=Get_Info('hot',$rows=3,$leng=46,$fid=$key);
	$infopic=Get_Info('pic',$rows=3,$leng=30,$fid=$key);
	if($val>0) $listdb_moresort[]=array("fid"=>$key,"name"=>$Fid_db[name][$key],'info'=>$info,'infopic'=>$infopic);
}


/**
*标签使用
**/
$ch=0;
$ch_fid	= $ch_pagetype = 0;
$ch_module = $webdb[module_id]?$webdb[module_id]:127;//系统特定ID参数,每个系统不能雷同

require(PHP168_PATH."inc/label_module.php");
require(Mpath."inc/categories.php");
$bcategory->cache_read();
$bcategory->unsets();


/*if($city_DB[template][$city_id]){
	$tpl_db=unserialize($city_DB[template][$city_id]);
	$head_tpl=$tpl_db[head];
	$foot_tpl=$tpl_db[foot];
	$index_tpl=$tpl_db[index];
}

$query = $db->query("SELECT * FROM {$_pre}keyword ORDER BY num DESC LIMIT 50");
while($rs = $db->fetch_array($query)){
	$keywordDB[]=$rs[word];
}*/

/**
*专用地区
**/
$city_options=select_where('city',"'listarea'   style='width:100px;'",0,0);
$area_choose=select_where('province',"'listarea_fup'  onchange='showcity(this)' style='width:100px;'",$listarea_fup,0);
$area_choose=$area_choose."<span id='city_span'>$city_options</span>";



//专属首页超大广告
$display=$jobs=='show'?"block":"none";
$js_show=$jobs=='show'?0:1;



require(Mpath."inc/head.php");
require(getTpl("index"));//
require(Mpath."inc/foot.php");

if($jobs != 'show')
	cache_page_save();

unset($content);
//伪静态处理
if($webdb[Info_NewsMakeHtml]==2)
{
	$content=ob_get_contents();
	ob_end_clean();
	ob_start();			//此句话为生成静态调用
	$content=fake_html($content);
	echo "$content";
}

if($webdb[Info_MakeIndexHtmlTime]>0||$MakeIndex)
{
	if($MakeIndex||(time()-@filemtime("index.htm")-$webdb[Info_MakeIndexHtmlTime]*60)>0)
	{
		if(!$content)
		{
			$content=ob_get_contents();
		}
		ob_end_clean();
		$content=str_replace("</body>","<div style='display:none'><iframe src=job.php?job=makeindex></iframe></div></body>",$content);
		write_file(PHP168_PATH."/index.htm",$content);
		echo "<META HTTP-EQUIV=REFRESH CONTENT='0;URL=$webdb[www_url]/index.htm'>";
		exit;
	}
}

?>