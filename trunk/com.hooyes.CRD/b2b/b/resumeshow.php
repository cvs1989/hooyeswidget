<?php
require("global.php");
/**/

//require(Mpath."php168/all_hrfid.php");
@require_once(Mpath."php168/jobData.php");
require(Mpath.'inc/hr_categories.php');

if(!$id || !is_numeric($id)) showerr("û���ҵ���Ҫ���ʵ�ҳ");

$resume_data=$db->get_one("select * from {$_pre}hr_resume where `re_id`='$id'");
if(!$resume_data) showerr("û���ҵ���Ҫ�鿴����Ϣ"); 
if(!$resume_data[is_check]) showerr("δ�����Ϣ�����ܲ鿴"); 

$resume_data[posttime]=date("Y-m-d H:i:s",$resume_data[posttime]);
//���л����滻Ϊbr
foreach($resume_data as $key=>$val){
	$resume_data[$key]=nl2br($resume_data[$key]);
}

//Ӣ������
$resume_data[eng_name]=$resume_data[eng_name]?"(Ӣ����:$resume_data[eng_name])":"";

//�õ��Զ����ֶ�
$resume_show_template=create_jobData_view("resume",unserialize($resume_data[other_data]));

//�õ���Ŀȫ����
$parents = $hrcategory->get_parents($resume_data['sid']);

//$sid_all=explode(",",$jobs_data[sid_all]);
$sid_allName = array();

foreach($parents as $v){
	$sid_allName[] = $v['sname'];
}
$sid_allName[] = $hrcategory->categories[$resume_data['sid']]['sname'];
//����
$city   =explode(",",$resume_data[city]);
$resume_data[city]   = $area_DB[name][$city[0]]." ".$city_DB[name][$city[1]];
//���µ����
$db->query("update {$_pre}hr_resume set `hits` =`hits`+1 where `re_id`='$id' ");

//��������
$resume_data[coll_company_to_this]=$resume_data[coll_company_to_this]?$resume_data[coll_company_to_this]:"0";

//$resume_data[coll_company_to_this]  �������˲ſ�
$coll_company_to_this=$db->get_one("select count(*) as num from {$_pre}hr_td  where td_type='add' and re_id='$resume_data[re_id]' ");

$resume_data[coll_company_to_this]=$coll_company_to_this[num];
$hrFid_db=$hrcategory->get_one($resume_data['sid']);

//SEO
$titleDB[title]			= filtrate(strip_tags("$resume_data[truename]�ļ��� - $hrFid_db[sname] - $webdb[Info_webname]"));
$titleDB[keywords]		= filtrate(strip_tags("$rsdb[keywords] $webdb[Info_metakeywords]"));
$titleDB[description]	= get_word(strip_tags($rsdb[content]),200).filtrate(strip_tags("$fidDB[metadescription] $webdb[Info_metadescription]"));


if($print){
	require(getTpl("resumeshow_print"));
}else{
	require(Mpath."inc/head.php");
	require(getTpl("resumeshow"));
	require(Mpath."inc/foot.php");
}


?>