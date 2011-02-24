<?php
require(dirname(__FILE__)."/global.php");

//导航条
@include(Mpath."data/guide_fid.php");



$fidDB=$db->get_one("SELECT A.* FROM {$_pre}sort A WHERE A.fid='$fid'");
if(!$fidDB){
	showerr("栏目不存在");
}elseif($fidDB[jumpurl]){
	header("location:$fidDB[jumpurl]");
	exit;
}

/**
*模型配置文件
**/
$field_db = $module_DB[$fidDB[mid]][field];

//字段筛选
unset($TempSearch_2,$TempSearch_array);
foreach($field_db AS $key=>$value){
	if($value[listfilter]){
		$TempSearch_2.="$key=>'{$$key}',";		//分页链接使用
		$TempSearch_array[$key]=$$key;			//其它链接使用
		$search_fieldDB[$key][$$key!=''?$$key:0]=" selected class='ck' style='color:red;'";
	}
}


/**
*栏目配置参数及栏目用户自定义的变量
*对栏目用户自定义的变量附件路径做处理
*以下用的比较少,可以删除忽略
**/
$fidDB[config]=unserialize($fidDB[config]);
$CV=$fidDB[config][field_value];
$_array=array_flip($fidDB[config][is_html]);
foreach( $fidDB[config][field_db] AS $key=>$rs){
	if(in_array($key,$_array)){
		$CV[$key]=En_TruePath($CV[$key],0);
	}elseif($rs[form_type]=='upfile'){
		$CV[$key]=tempdir($CV[$key]);
	}
}


//SEO
$titleDB[title]	= $fidDB[metatitle]?($fidDB[metatitle]):strip_tags("{$city_DB[name][$city_id]} $fidDB[name]");
$titleDB[keywords] = ($fidDB[metakeywords]);
$titleDB[description] = ($fidDB[metadescription]);

//栏目风格
$fidDB[style] && $STYLE=$fidDB[style];


/**
*栏目模板优先于城市模板
**/
if($fidDB[template]){
	$FidTpl=unserialize($fidDB[template]);
	$FidTpl['head'] && $head_tpl=Mpath.$FidTpl['head'];
	$FidTpl['foot'] && $foot_tpl=Mpath.$FidTpl['foot'];
	$FidTpl['list'] && $FidTpl['list']=Mpath.$FidTpl['list'];
}

$rows = $fidDB[maxperpage]>0 ? $fidDB[maxperpage] : 15;

$listdb=array();

if($page<1){
	$page=1;
}

$min=($page-1)*$rows;

//排序方式
switch($fidDB['listorder']){
	case 1:
		$sql_list="A.posttime";
		$sql_order="DESC";
		break;
	case 2:
		$sql_list="A.posttime";
		$sql_order="ASC";
		break;
	case 3:
		$sql_list="A.hits";
		$sql_order="DESC";
		break;
	case 4:
		$sql_list="A.hits";
		$sql_order="ASC";
		break;
	case 5:
		$sql_list="A.lastview";
		$sql_order="DESC";
		break;
	default:
		$sql_list="A.list";
		$sql_order="DESC";
		break;
}

$SQL = '';

if($city_id>0){
	$SQL = " AND A.city_id='$city_id' ";
}

//用户自定义筛选字段,过滤数据
foreach($field_db AS $key=>$value){
	if($value[listfilter]){
		if($_GET[$key]!=''){
			$SQL .= " AND B.`$key`='$_GET[$key]' ";
		}
	}
}

if(!$webdb[Info_ShowNoYz]){
	$SQL .= " AND A.yz='1' ";
}

if($Fid_db[$fid]){
	//同一个大分类下,必须是同一张表,否则数据获取不到
	foreach($Fid_db[$fid] AS $key=>$value){
		$_erp = $Fid_db['tableid'][$key];
		break;
	}
	$SQL=" LEFT JOIN {$_pre}sort S ON A.fid=S.fid WHERE S.fup='$fid' $SQL ";
}else{
	$_erp = $Fid_db['tableid'][$fid];
	$SQL = " WHERE A.fid='$fid' $SQL ";
}


$query=$db->query("SELECT SQL_CALC_FOUND_ROWS B.*,A.*,C.title AS companyname,C.renzheng FROM {$_pre}content$_erp A LEFT JOIN {$_pre}content_{$fidDB[mid]} B ON A.id=B.id LEFT JOIN {$pre}hy_company C ON A.uid=C.uid $SQL ORDER BY $sql_list $sql_order LIMIT $min,$rows");

$RS=$db->get_one("SELECT FOUND_ROWS()");
$totalNum=$RS['FOUND_ROWS()'];
$showpage=getpage("","","list.php?fid=$fid",$rows,$totalNum);

while( $rs=$db->fetch_array($query) ){

	$rs[content]=@preg_replace('/<([^<]*)>/is',"",$rs[content]);	//把HTML代码过滤掉
	$rs[content]=get_word($rs[full_content]=$rs[content],100);
	$rs[title]=get_word($rs[full_title]=$rs[title],50);
	if($rs['list']>$timestamp){
		$rs[title]=" <font color='$webdb[Info_TopColor]'>$rs[title]</font> <img src='$webdb[www_url]/images/default/icotop.gif' border=0>";
	}elseif($rs['list']>$rs[posttime]){
		//置顶过期的信息,需要恢复原来发布日期以方便排序,放在后面
		$db->query("UPDATE {$_pre}content$_erp SET list='$rs[posttime]' WHERE id='$rs[id]'");
	}

	$rs[posttime]=date("Y-m-d",$rs[full_time]=$rs[posttime]);
	if($rs[picurl]){
		$rs[picurl]=tempdir($rs[picurl]);
	}

	$Module_db->showfield($field_db,$rs,'list');

	$rs[url]=get_info_url($rs[id],$rs[fid],$rs[city_id]);

	$listdb[]=$rs;
}

/**
*为获取标签参数
**/
$chdb[main_tpl] = getTpl("list_$fidDB[mid]",$FidTpl['list']);

/**
*标签
**/
$ch_fid	= intval($fidDB[config][label_list]);		//是否定义了栏目专用标签
$ch_pagetype = 2;									//2,为list页,3,为bencandy页
$ch_module = $webdb[module_id]?$webdb[module_id]:99;//系统特定ID参数,每个系统不能雷同
$ch = 0;											//不属于任何专题
require(ROOT_PATH."inc/label_module.php");

require(ROOT_PATH."inc/head.php");
require(getTpl("list_$fidDB[mid]",$FidTpl['list']));
require(ROOT_PATH."inc/foot.php");

?>