<?php
require("global.php");


/* $fid = intval($fid);
$page = intval($page);
$page = max(1, $page);
$page = min($page, 500); */

$fid = intval($fid);
$page=intval($page);
if($page<1){$page=1;}
$bid= isset($bid) ? intval($bid) : 0;
$price1= isset($price1) ? intval($price1) : 0;
$price2= isset($price2) ? intval($price2) : 0;

//缓存参数
$params = array(
	'fid' => $fid,
	'bid' => $bid,
	'listarea' => $listarea,
	'ordertype' => $ordertype,
	'mylistorder' => $mylistorder,
	'renzheng' => $renzheng,
	'agent' => $agent,
	'vip' => $vip,
	'page' => $page
);

//缓存
if($jobs != 'show')
	cache_page(PHP_SELF.combine_params($params));

@include(Mpath."php168/guide_fid.php");
require(Mpath.'inc/categories.php');

if(!$fid&&$webdb[Info_NewsMakeHtml]==2)
{
	//伪静态处理
	//Explain_HtmlUrl();
}

$bcategory->cache_read();

$has_sub = false;
if(!$fid)
{
	//showerr("FID不存在");
}else{
	//add by yao
	
	if($bcategory->categories[$fid]['categories']){
		$fid_path = $fid .','. implode(',', $bcategory->get_children_ids($fid));
		$has_sub = true;
	}else{
		$fid_path = $fid;
	}
	//add by yao
	//$fid_path=getFidAll($fid);
}

/***
*获取栏目与模块的配置文件
**/
$fidDB=$bcategory->get_one($fid);

/**
*导航
**/
//$guidefid=GuideFid(getFidAll($fid),"list.php?ctype=".$ctype);
$topMenu[$ctype]=' ck';
$parents = $bcategory->get_parents($fid);
$guidefid = '';

foreach($parents as $v) $guidefid .= ' &gt; <a href="buy_list.php?fid='. $v['fid'] .'">'. $v['name'] .'</a>';
$guidefid .= ' &gt; <a href="buy_list.php?fid='. $fid .'">'. $bcategory->categories[$fid]['name'] .'</a>';


$sortArray = isset($bcategory->categories[$fid]['categories']) ? $bcategory->categories[$fid]['categories'] : $bcategory->categories[$bcategory->categories[$fid]['fup']]['categories'];

/**
*跳转到外部地址
**/
if($fidDB[jumpurl])
{
	header("location:$fidDB[jumpurl]");
	exit;
}

/**
*专用地区
**/
if($listarea){
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

$SQL=" A.yz = 1 ";

//自我排序用
$mylistorder=$mylistorder?$mylistorder:1;	

$leng = 76;
/**
*为获取标签参数
**/
$chdb[main_tpl]=getTpl("list_2");

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

//得到浏览历史
$webdb[viewHistoryNums]=$webdb[viewHistoryNums]?$webdb[viewHistoryNums]:10;
$myhistorylist=get_cookie("user_historylist"); 
$myhistorylist=explode("@@@",$myhistorylist);
foreach($myhistorylist as $rs){
	$rs=explode("|||",$rs);
	if($rs[0] && $rs[1] && $rs[2]  && $rs[4])
	{
		$hislistdb[]=array(
			'id'=>$rs[0],
			'fid'=>$rs[1],
			'title'=>$rs[2],
			'picurl'=>$rs[3].".gif",
			'my_price'=>$rs[4]
		);
	}
}

	$A_list_force_index = "";
	$A_count_force_index = "";
	$sf_list_force_index = "";
	$sf_count_force_index = "";

	if($mylistorder==1){
		$order_by="A.levels DESC, A.posttime DESC";
		$A_list_force_index = " FORCE INDEX(posttime) ";
	}elseif($mylistorder==2){
		$order_by="A.levels DESC, A.posttime ASC";
		$A_list_force_index = " FORCE INDEX(posttime) ";
	}elseif($mylistorder==4){
		$order_by="A.my_price ASC";
		$A_list_force_index = " FORCE INDEX(my_price) ";
	}elseif($mylistorder==3){
		$order_by="A.my_price DESC";
		$A_list_force_index = " FORCE INDEX(my_price) ";
	}


//列表----------------------------------------------------
	
	
	
	$sf_count_fid_join = "";
	
	if($fid_path){		//有选分类
		$SQL.=" AND sf.fid IN ($fid_path) ";
		$sf_list_force_index = " FORCE INDEX (PRIMARY) ";
		
		$sf_count_force_index = " FORCE INDEX (fid) ";
		$A_count_force_index = " FORCE INDEX (PRIMARY) ";
		
		$sf_list_fid_join = " INNER JOIN `{$_pre}buy_fid` sf $sf_list_force_index ON A.id = sf.id ";
		$sf_count_fid_join = " INNER JOIN `{$_pre}buy_fid` sf $sf_count_force_index ON A.id = sf.id ";
		
	}
	//SELECT count(*)   FROM p8_business_content_sell A force index(primary) INNER JOIN p8_business_sell_fid sf  ON sf.id = A.id  WHERE  A.yz = 1  AND sf.fid IN (156) 
	//SELECT *   FROM p8_business_content_sell A force index(levels)  INNER JOIN p8_business_sell_fid sf  ON sf.id = A.id  WHERE  A.yz = 1  AND sf.fid IN (156,137) order by levels desc limit 50
	
	$com_join = " INNER JOIN {$_pre}company C ON A.uid = C.uid ";
	$bid_join = "";
	
	if($keyword){
		$SQL.=" AND A.title LIKE('%$keyword%') ";
		if(!$fid_path){
			$sf_list_fid_join = "";
		}
	}
	
	$webdb[showposttimelast]=intval($webdb[showposttimelast]);
	if($webdb[showposttimelast]){
		$showposttimelast=time()-($webdb[showposttimelast]*24*60*60);
		$timelimit="  AND A.posttime > $showposttimelast ";
	}
	
	$com_join_ = false;
	if($listarea){
		$SQL.=" AND C.city_id='$listarea' ";
		$com_join_ = true;
	}
	if($agent){ //代理商
		$SQL.=" AND C.is_agent=1 ";
		$com_join_ = true;
	}

	if($vip){ //VIP用户
		$SQL.=" AND C.is_vip > $timestamp ";
		$com_join_ = true;
	}
	
	if($renzheng){ //VIP用户
		$SQL.=" AND C.renzheng = 1 ";
		$com_join_ = true;
	}
	
	$com_join_ = $com_join_ ? $com_join : "";
	
	if($bid){//品牌
		$SQL.=" AND A.bid='$bid' ";
	}

	$min=($page-1)*$rows;

	$showpage=getpage("{$_pre}content_buy A $A_count_force_index $sf_count_fid_join $com_join_","WHERE $SQL","buy_list.php?&fid=$fid&bid=$bid&listarea=$listarea&price1=$price1&price2=$price2&ordertype=$ordertype&mylistorder=$mylistorder&renzheng=$renzheng&agent=$agent&vip=$vip&keyword=".urlencode($keyword),$rows);
	
	$query=$db->query("SELECT A.*,B.*,C.title as owner_name,C.is_agent,C.is_vip,C.city_id,C.province_id,C.renzheng FROM {$_pre}content_buy A $A_list_force_index $sf_list_fid_join $com_join INNER JOIN {$_pre}content_2 B ON A.id=B.id WHERE $SQL $timelimit ORDER BY $order_by LIMIT $min,$rows ");


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
		
		if($rs[my_price]){
			$rs[my_price]=formartprice($rs[my_price]);
			$rs[my_price]="<strong><font color=#FF3300>$rs[my_price]</font></strong>元/$rs[quantity_type]";
		}else{
			$rs[my_price]='价格面议';
		}
	
		if($rs[picurl]){
		   $rs[picurl]=getimgdir($rs[picurl],$ctype);
		}
			
		
		

		if($webdb[bencandyIsHtml] && $rs[htmlname]){
			$rs[url]=$webdb[www_url]."/".$rs[htmlname];
		}else{
			$rs[url]=$Mdomain."/buy_bencandy.php?fid=$rs[fid]&id=$rs[id]";
		}

		$listdb[]=$rs;
	}



require(Mpath."inc/head.php");
require(getTpl("list_2"));
require(Mpath."inc/foot.php");

//写缓存
if($jobs != 'show')
	cache_page_save();


?>