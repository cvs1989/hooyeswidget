<?php
require("global.php");

$fid = intval($fid);
$page=intval($page);
if($page<1){$page=1;}

//缓存参数
$params = array(
	'fid' => $fid,
	'listarea' => $listarea,
	'ordertype' => $ordertype,
	'renzheng' => $renzheng,
	'agent' => $agent,
	'vip' => $vip,
	'page' => $page
);

//缓存
if($jobs != 'show')
	cache_page(PHP_SELF.combine_params($params));

require(Mpath.'inc/categories.php');
@include(Mpath."php168/guide_fid.php");


$SQL = " A.yz = 1 ";
$has_sub = false;
if($fid)
{
	$bcategory->cache_read();
	if(isset($bcategory->categories[$fid]['categories'])){
		$fid_path = $fid .','. implode(',', $bcategory->get_children_ids($fid));
		$has_sub = true;
	}else{
		$fid_path = $fid;
	}
	//$fid_path=getFidAll($fid);
	//$fidDB=$db->get_one("SELECT A.* FROM {$_pre}sort A WHERE A.fid='$fid'");
	$fidDB = $bcategory->get_one($fid);
	
	/***
	*获取栏目与模块的配置文件
	**/
	if($fidDB[jumpurl])
	{
		header("location:$fidDB[jumpurl]");
		exit;
	}
}

/**
*导航
**/
//$guidefid=GuideFid(getFidAll($fid),"clist.php?");
$topMenu['company']=' ck';

$parents = $bcategory->get_parents($fid);
$guidefid = '';

foreach($parents as $v) $guidefid .= ' &gt; <a href="clist.php?fid='. $v['fid'] .'">'. $v['name'] .'</a>';
$guidefid .= ' &gt; <a href="clist.php?fid='. $fid .'">'. $bcategory->categories[$fid]['name'] .'</a>';


$sortArray = isset($bcategory->categories[$fid]['categories']) ? $bcategory->categories[$fid]['categories'] : $bcategory->categories[$bcategory->categories[$fid]['fup']]['categories'];

/**
*专用地区
**/
if($listarea){
	$listarea = intval($listarea);
	$SQL .= " AND A.city_id = $listarea ";
	$force_index = " FORCE INDEX (city_id) ";
	$listarea_fup=$city_DB[fup][$listarea];
	$city_options=select_where('city',"'listarea'   style='width:100px;'",$listarea,$listarea_fup);
}
$area_choose=select_where('province',"'listarea_fup'  onchange='showcity(this)' style='width:100px;'",$listarea_fup,0);
$area_choose=$area_choose."<span id='city_span'>$city_options</span>";


$keyword_code=urlencode($keyword);

//SEO
$titleDB[title]			= filtrate(strip_tags("$fidDB[name] - $webdb[Info_webname]"));
$titleDB[keywords]		= filtrate(strip_tags("$fidDB[metakeywords]  $webdb[Info_metakeywords]"));
$titleDB[description]	= get_word(strip_tags($fidDB[descrip]),200).filtrate(strip_tags("$fidDB[metadescription] $webdb[Info_metadescription]"));

//栏目风格
$fidDB[style] && $STYLE=$fidDB[style];


/**
*模板
**/
$FidTpl=unserialize($fidDB[template]);
$head_tpl=$FidTpl['head'];
$foot_tpl=$FidTpl['foot'];



$rows=$fidDB[maxperpage]>0?$fidDB[maxperpage]:15;


//缓冲缓存
$page=intval($page);
if($page<1){$page=1;}


$mylistorder=$mylistorder?$mylistorder:1;	

$leng = 76;	

/**
*为获取标签参数
**/
$chdb[main_tpl]=getTpl("clist");
/**
*标签
**/

$ch_pagetype = 127;									
$ch_module = $webdb[module_id]?$webdb[module_id]:99;//系统特定ID参数,每个系统不能雷同
$ch = 0;											//不属于任何专题
require(PHP168_PATH."inc/label_module.php");

$fendb=unserialize($fidDB[config2]);




/**
列表页显示全屏
**/
if($webdb[listwidth]!='full'){
	$listwidth=" width:990px;margin:auto;";
}else{
	$listwidth=" width:99%;margin:auto;";
}

$fid_join = "";
	$force_index = " FORCE INDEX (levels) ";	//用于查询,有子分类情况不要用
	$force_index_ = " FORCE INDEX (levels) ";	//用于计数,有子分类下不可用
	if($fid_path){
		$fid_join = " INNER JOIN `{$_pre}company_fid` cf ON A.rid = cf.cid ";
		$SQL .= " AND cf.fid IN ($fid_path) ";
		if($has_sub){
			$force_index = "";
		}else{
			$force_index_ = "";
		}
		//add by yao
	}
	
	if($listarea){
		$SQL.=" AND A.city_id='$listarea' ";
		$force_index = " FORCE INDEX (city_id) ";
	}
	
	if($renzheng){
		$SQL.=" AND renzheng='$renzheng'";
	}
	
	if($keyword){
		$SQL.=" AND A.title LIKE('%$keyword%')";
	}
	
	
	$min=($page-1)*$rows;
	

		//排序
		if($mylistorder==1){
			$sql_list="A.posttime";
			$sql_order="DESC";
		}elseif($mylistorder==2){
			$sql_list="A.posttime";
			$sql_order="ASC";	
		}elseif($mylistorder==3){
			$sql_list="A.renzheng";
			$sql_order="DESC";	
		}elseif($mylistorder==4){
			$sql_list="A.renzheng";
			$sql_order="ASC";	
		}

		if($agent){ //代理商
			$SQL.=" AND A.is_agent=1";
		}

		if($vip){ //VIP用户
			$SQL.=" AND A.is_vip > $timestamp";
		}
		
		//分页

	$showpage=getpage("{$_pre}company A $force_index_ $fid_join","WHERE $SQL","clist.php?&fid=$fid&listarea=$listarea&ordertype=$ordertype&mylistorder=$mylistorder&renzheng=$renzheng&agent=$agent&vip=$vip&keyword=".urlencode($keyword),$rows);
		
		//$query = $db->query("SELECT * FROM {$_pre}company A WHERE A.yz=1 $SQL ORDER BY A.levels DESC, $sql_list $sql_order LIMIT $min,$rows ");
	$query=$db->query("SELECT DISTINCT A.* FROM {$_pre}company A $force_index $fid_join WHERE $SQL ORDER BY A.levels DESC, $sql_list $sql_order LIMIT $min,$rows ");
	
	
	while( $rs=$db->fetch_array($query) ){
		$rs[content]=@preg_replace('/<([^>]*)>/is',"",$rs[content]);	//把HTML代码过滤掉
		$rs[content]=get_word($rs[full_content]=$rs[content],200);
		$rs[title]=get_word($rs[full_title]=$rs[title],$leng);
		$rs[title]=str_replace($keyword,"<font color=red>$keyword</font>",$rs[title]);

		if($rs['list']>$timestamp){
			$rs[title]="<img src='$webdb[www_url]/images/default/headtoppic.gif' border=0> <font color='$webdb[Info_TopColor]'>$rs[title]</font>";
		}
		$rs[posttime]=date("Y-m-d",$rs[full_time]=$rs[posttime]);
		
		
		
		$rs[renzheng]=getrenzheng($rs[renzheng]);
		$rs[owner_name_short]=get_word($rs[owner_name],30);
		
		$rs[services]=get_services($rs);
		
		$rs[quantity_type]=$rs[quantity_type]?$rs[quantity_type]:"件";
			
				
		$rs[qy_pro_ser]=get_word($rs[qy_pro_ser],50);
		$rs[picurl]=getimgdir($rs[picurl],'company');
		
		$listdb[]=$rs;
	}

require(Mpath."inc/head.php");
require(getTpl("clist"));
require(Mpath."inc/foot.php");

//写缓存
if($jobs != 'show')
	cache_page_save();
?>