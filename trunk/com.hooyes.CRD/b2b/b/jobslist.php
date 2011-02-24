<?php
require("global.php");
/**/
require(Mpath."php168/all_hrfid.php");
@require_once(Mpath."php168/jobData.php");


//if(is_array($hrFid_db[name][$hr_sid]))  $hrFid_db[name][$hr_sid]="";




//搜索栏用
/*$jobs_sort="<select name='sid[]' onchange='choose_jobSort(this.options[this.selectedIndex].value,1)'> <option value='0'>请选择</option>";
foreach($hrFid_db[0] as $key=>$val){
				$jobs_sort.="<option value='$key' >$val</option>";
}
$jobs_sort.="</select>";
$province_fid=select_where("province","'postdb[province_id]'  onChange=\"choose_where(this.options[this.selectedIndex].value,'','')\" ");	*/


//得到列表
	$rows=$webdb[zhaopin_listnum]?$webdb[zhaopin_listnum]:20;
	$page=intval($page);
	if($page<1) $page=1;
	$min=($page-1)*$rows;
	$where=" where is_check=1 ";
	if($hr_sid>0 && is_numeric($hr_sid)){ $where.=" and concat(',',`sid_all`,',') like('%,$hr_sid,%') ";}	
	
	if($search_type=='jobs' || !$search_type){

		if($job_sort) $hr_sid=$job_sort[(count($job_sort)-1)];
		if($hr_sid>0 && is_numeric($hr_sid)){ $where.=" and concat(',',`sid_all`,',') like('%,$hr_sid,%') ";}	
		if($keywords) $where.=" and  `title` like('%$keywords%')";
		if($postdb[province_id]) $city=$postdb[province_id].",".$postdb[city_id];
		if($city) $where.=" and  `city`='$city' ";
		if($province_id) $where.=" and `city` like('$province_id,%') ";
		
		$query=$db->query("select * from {$_pre}hr_jobs $where  order by  best desc,posttime desc limit $min,$rows");
		
		while($rs=$db->fetch_array($query)){
			$rs[posttime]=date('Y-m-d',$rs[posttime]);
			$rs[posttime_full]=date('Y-m-d H:i:s',$rs[posttime]);
			$city=explode(",",$rs[city]);
			$rs[cityname]=$area_DB[name][$city[0]]." ".$city_DB[name][$city[1]];
			$rs[companyname]=$rs[companyname]?get_word($rs[companyname],$leng):"&nbsp;";
			$getzhiweilist[]=$rs;
	
		}
		
		
		$showpage=getpage("{$_pre}hr_jobs",$where,"?hr_sid=$hr_sid&province_id=$province_id&search_type=$search_type&keywords=".urlencode($keywords),$rows);
	
	
	}elseif($search_type=='resume'){
		
		if($job_sort) $hr_sid=$job_sort[(count($job_sort)-1)];
		if($hr_sid>0 && is_numeric($hr_sid)){ $where.=" and concat(',',`sid_all`,',') like('%,$hr_sid,%') ";}	
		if($postdb[province_id]) $city=$postdb[province_id].",".$postdb[city_id];
		if($city) $where.=" and  `city`='$city' ";
		if($province_id) $where.=" and `city` like('$province_id,%') ";

		if($keywords) $where.=" and  `job_name` like('%$keywords%')";
		
		$query=$db->query("select * from {$_pre}hr_resume $where  order by  best desc,posttime desc limit $min,$rows");
		
		while($rs=$db->fetch_array($query)){
			$rs[posttime]=date('Y-m-d',$rs[posttime]);
			$rs[posttime_full]=date('Y-m-d H:i:s',$rs[posttime]);
			$city=explode(",",$rs[city]);
			$rs[cityname]=$area_DB[name][$city[0]]." ".$city_DB[name][$city[1]];
			$getrencailist[]=$rs;
	
		}
		
		
		$showpage=getpage("{$_pre}hr_resume",$where,"?hr_sid=$hr_sid&province_id=$province_id&search_type=$search_type&keywords=".urlencode(keywords),$rows);
		
	
	}
//
if(!$hr_sid){
	$fname=" > 全部分类";
	$list = $hrFid_db[0];
}else{
  $sup=$hr_sid;
  for($i=0;$i<10;$i++){
  	$fname= " > <a href='?hr_sid=$sup'>".$hrFid_db[name][$sup]."</a>".$fname;
	$sup=$hrFid_db[sup][$sup];
	if($sup) continue; 
	else break;
  }
  $list = $hrFid_db['sup'][$hr_sid] ? $hrFid_db[$hrFid_db['sup'][$hr_sid]] : $hrFid_db[$hr_sid];
}
////



//SEO
$titleDB[title]			= filtrate(strip_tags("人才招聘频道 - {$hrFid_db[name][$hr_sid]} - $webdb[Info_webname]"));
$titleDB[keywords]		= filtrate(strip_tags("$fidDB[metakeywords]  $webdb[Info_metakeywords]"));
$titleDB[description]	= get_word(strip_tags($fidDB[descrip]),200).filtrate(strip_tags("$fidDB[metadescription] $webdb[Info_metadescription]"));

require(Mpath."inc/head.php");
require(getTpl("jobslist"));
require(Mpath."inc/foot.php");





?>