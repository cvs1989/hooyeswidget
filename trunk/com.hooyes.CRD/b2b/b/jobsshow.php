<?php
require("global.php");
/**/
require(Mpath."php168/all_hrfid.php");
@require_once(Mpath."php168/jobData.php");


if(!$id || !is_numeric($id)) showerr("没有找到你要访问的页");

$jobs_data=$db->get_one("select * from {$_pre}hr_jobs where `jobs_id`='$id'");
if(!$jobs_data[is_check]) showerr("未审核信息，不能查看"); 

//所有换行替换为br
foreach($jobs_data as $key=>$val){
	$jobs_data[$key]=nl2br($jobs_data[$key]);
}

if($jobs_data[rid]){ //如果是登记商家发布的。

	$companyData=$db->get_one("select * from {$_pre}company where rid='{$jobs_data[rid]}'");
	if($jobs_data[companyintro]=='') $jobs_data[companyintro]=$companyData[content];
	$companyData[picurl]=getimgdir($companyData[picurl],3); 
	$jobs_data[companyname]=$jobs_data[companyname]?$jobs_data[companyname]:$companyData[title];
	$companyData[renzheng]=getrenzheng($companyData[renzheng]);
	$companyData[otherjobs]=getzhiweilist("0:{$companyData[uid]}",$hot=0,$rows=10,$leng=30); //本用户其他招聘信息
}

//得到自定义字段
$jobs_show_template=create_jobData_view("jobs",unserialize($jobs_data[other_data]));

//得到类目全名称
$sid_all=explode(",",$jobs_data[sid_all]);
foreach($sid_all as $key){
	if($key){
		$sid_allName[]=$hrFid_db[name][$key];
	}
}

$jobs_data[sid_all]=implode(" &gt; ",$sid_allName);
//地区
$city   =explode(",",$jobs_data[city]);
$jobs_data[city]   = $area_DB[name][$city[0]]." ".$city_DB[name][$city[1]];
//更新点击数
$db->query("update {$_pre}hr_jobs set `hits` =`hits`+1 where `jobs_id`='$id' ");


//SEO
$titleDB[title]			= filtrate(strip_tags("$jobs_data[title] - {$hrFid_db[name][$jobs_data[sid]]} - $webdb[Info_webname]"));
$titleDB[keywords]		= filtrate(strip_tags("$rsdb[keywords] $webdb[Info_metakeywords]"));
$titleDB[description]	= get_word(strip_tags($rsdb[content]),200).filtrate(strip_tags("$fidDB[metadescription] $webdb[Info_metadescription]"));


require(Mpath."inc/head.php");
require(getTpl("jobsshow"));
require(Mpath."inc/foot.php");




?>