<?php
require("global.php");
/**/
@require(Mpath."php168/all_hrfid.php");
if(file_exists(Mpath."php168/jobData.php")){
	@require_once(Mpath."php168/jobData.php");
}
/**
*��ǩʹ��
**/
$ch=0;
$chdb[main_tpl]=getTpl("jobs");
$ch_fid	= $ch_pagetype = 0;
$ch_module = 127;//ϵͳ�ض�ID����,ÿ��ϵͳ������ͬ
require(PHP168_PATH."inc/label_module.php");

$hrDB[title]="�˲���ƸƵ��";
//SEO
$titleDB[title]			= filtrate(strip_tags("$hrDB[title]  - $webdb[Info_webname]"));
$titleDB[keywords]		= filtrate(strip_tags("$fidDB[metakeywords]  $webdb[Info_metakeywords]"));
$titleDB[description]	= get_word(strip_tags($fidDB[descrip]),200).filtrate(strip_tags("$fidDB[metadescription] $webdb[Info_metadescription]"));


//��������
$jobs_sort_1="<select name='job_sort[1]' onchange='choose_jobSort(this.options[this.selectedIndex].value,1)'> <option value='0'>��ѡ��</option>";
	foreach($hrFid_db[0] as $key=>$val){
	  $jobs_sort_1.="<option value='$key'>$val</option>";
	}
$jobs_sort_1.="</select><span id='show_jobs_sort_2'></span>";
$province_fid=select_where("province","'postdb[province_id]'  onChange=\"choose_where(this.options[this.selectedIndex].value,'','')\" ");

////

require(Mpath."inc/head.php");
require(getTpl("jobs"));
require(Mpath."inc/foot.php");



?>