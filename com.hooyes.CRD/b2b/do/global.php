<?php
require(dirname(__FILE__)."/"."../inc/common.inc.php");	//核心文件
require(PHP168_PATH."inc/artic_function.php");			//包含一些调取文件内容的函数

@include_once(PHP168_PATH."php168/ad_cache.php");		//广告变量缓存文件
@include_once(PHP168_PATH."php168/label_hf.php");		//标签头部与底部变量缓存文件
@include_once(PHP168_PATH."php168/all_fid.php");		//全部栏目配置文件

if(!$webdb[web_open])
{
	$webdb[close_why] = str_replace("\n","<br>",$webdb[close_why]);
	showerr("网站暂时关闭:$webdb[close_why]");
}

/**
*允许哪些IP访问
**/
$IS_BIZ && Limt_IP('AllowVisitIp');


$ch=intval($ch);
$fid=intval($fid);
$aid=intval($aid);
$id=intval($id);
$page=intval($page);
unset($listdb,$rs);

//加载JS时的提示语,你可以换成图片,'号要加\
$Load_Msg="<img alt=\"内容加载中,请稍候...\" src=\"$webdb[www_url]/images/default/ico_loading3.gif\">";

/**
*本栏目文章列表功能函数
**/
function ListThisSort($rows,$leng=50){
	global $page,$fid,$fidDB,$webdb,$pre,$Fid_db;
	if($page<1){
		$page=1;
	}
	$min=($page-1)*$rows;
	if($fidDB[listorder]==1){
		$DESC='DESC';
		$ORDER='A.posttime';
	}elseif($fidDB[listorder]==2){
		$DESC='ASC';
		$ORDER='A.posttime';
	}elseif($fidDB[listorder]==3){
		$DESC='DESC';
		$ORDER='A.hits';
	}elseif($fidDB[listorder]==4){
		$DESC='ASC';
		$ORDER='A.hits';
	}elseif($fidDB[listorder]==5){
		$DESC='DESC';
		$ORDER='A.lastview';
	}elseif($fidDB[listorder]==7){
		$DESC='DESC';
		$ORDER='A.digg_num';
	}elseif($fidDB[listorder]==8){
		$DESC='DESC';
		$ORDER='A.digg_time';
	}elseif($fidDB[listorder]==6){
		$DESC='DESC';
		$ORDER='rand()';
	}else{
		$DESC='DESC';
		$ORDER='A.list';
	}
	if(!$webdb[viewNoPassArticle]){
		$SQL_yz=' AND A.yz=1 ';
	}
	if($fid){
		$_fid_sql=" AND A.fid=$fid ";
	}else{
		$_fid_sql=" AND 1 ";
	}
	$erp=$Fid_db[iftable][$fid]?$Fid_db[iftable][$fid]:"";

	$SQL="A LEFT JOIN {$pre}reply$erp R ON A.aid=R.aid WHERE R.topic=1 $_fid_sql $SQL_yz ORDER BY $ORDER $DESC LIMIT $min,$rows";
	$which='A.*,R.content';
	$listdb=list_article($SQL,$which,$leng,$erp);
	return $listdb;
}

/**
*备用,供最新最热调用
**/
function Get_article($rows=10,$leng=30,$order='list'){
	global $fid,$webdb;
	if(!$webdb[viewNoPassArticle]){
		$SQL_yz=' AND yz=1 ';
	}
	if($fid){
		$SQL="WHERE fid=$fid $SQL_yz ORDER BY $order DESC LIMIT $rows";
	}else{
		$SQL="WHERE 1 $SQL_yz ORDER BY $order DESC LIMIT $rows";
	}
	
	$which='*';
	require_once(PHP168_PATH."inc/artic_function.php");
	$listdb=list_article($SQL,$which,$leng);
	return $listdb;
}

/**
*本栏目图片列表
**/
function ListPic($rows,$leng,$page=1,$order='list'){
	global $fid,$webdb;
	if($page<1){
		$page=1;
	}
	$min=($page-1)*$rows;
	if(!$webdb[viewNoPassArticle]){
		$SQL_yz=' AND yz=1 ';
	}
	$SQL="WHERE fid=$fid AND ispic=1 $SQL_yz ORDER BY $order DESC LIMIT $min,$rows";
	$which='*';
	$listdb=list_article($SQL,$which,$leng);
	return $listdb;
}

/**
*大分类的子栏目
**/
function ListMoreSort(){
	global $db,$pre,$fid,$webdb,$fidDB,$Fid_db;
	//排序
	if($fidDB[config][sonListorder]==1){
		$order='A.list';
	}elseif($fidDB[config][sonListorder]==2){
		$order='A.hits';
	}elseif($fidDB[config][sonListorder]==3){
		$order='A.lastview';
	}elseif($fidDB[config][sonListorder]==4){
		$order='rand()';
	}else{
		$order='A.list';
	}
	$_order=" ORDER BY $order DESC ";

	//显示几行
	if($fidDB[config][sonTitleRow]>0){
		$rows=$fidDB[config][sonTitleRow];
	}elseif($webdb[ListSonRows]>0){
		$rows=$webdb[ListSonRows];
	}else{
		$rows=10;
	}

	//每个标题显示几个字
	if($fidDB[config][sonTitleLeng]>0){
		$leng=$fidDB[config][sonTitleLeng];
	}elseif($webdb[ListSonLeng]>0){
		$leng=$webdb[ListSonLeng];
	}else{
		$leng=30;
	}

	if(!$webdb[viewNoPassArticle]){
		$SQL_yz=' AND A.yz=1 ';
	}
	$query=$db->query("SELECT * FROM {$pre}sort WHERE fup=$fid AND forbidshow!=1 ORDER BY list DESC");
	while($rs=$db->fetch_array($query)){
		$erp=$Fid_db[iftable][$rs[fid]]?$Fid_db[iftable][$rs[fid]]:'';
		$SQL="A LEFT JOIN {$pre}reply$erp R ON A.aid=R.aid WHERE R.topic=1 AND A.fid=$rs[fid] $SQL_yz $_order LIMIT $rows";
		$which='A.*,R.content';
		$rs[article]=list_article($SQL,$which,$leng,$erp);

		//如果本栏目不能获取到文章,将获取其所有子栏目的文章
		if(!$rs[article])
		{
			$array_fid=Get_SonFid("{$pre}sort",$rs[fid]);
			if($array_fid)
			{
				//分表后,如果子栏目不是同一模型的话.将不能获取资料
				$SQL="A LEFT JOIN {$pre}reply$erp R ON A.aid=R.aid WHERE R.topic=1 AND A.fid IN (".implode(',',$array_fid).") $SQL_yz $_order LIMIT $rows";
				$rs[article]=list_article($SQL,$which,$leng,$erp);
			}
		}
		$rs[logo] && $rs[logo]=tempdir($rs[logo]);
		$listdb[]=$rs;
	}
	return $listdb;
}



/**
*伪静态功能函数
**/
function fake_html_Function($filename,$fid,$id,$page=1,$P='',$linkcode){
	$linkcode=stripslashes($linkcode);
	eval("\$filename=\"$filename\";");
	return "$linkcode$P$filename";
}

/**
*伪静态功能函数
**/
function fake_html($content){
	global $webdb;
	$listpath=$webdb[list_filename2];
	$bencandypath=$webdb[bencandy_filename2];
	$content=preg_replace("/ href=('|\"|)([-_a-z0-9\.:\/]{0,}\/|)bencandy\.php\?fid=([\d]+)&(aid|id)=([\d]+)&page=([\d]+)/eis","fake_html_Function('$bencandypath','\\3','\\5','\\6','\\2',' href=\\1')",$content);	//有分页的最终页
	$content=preg_replace("/ href=('|\"|)([-_a-z0-9\.:\/]{0,}\/|)bencandy\.php\?fid=([\d]+)&(id|aid)=([\d]+)/eis","fake_html_Function('$bencandypath','\\3','\\5','1','\\2',' href=\\1')",$content);	//无分页的最终页
	$content=preg_replace("/ href=('|\"|)([-_a-z0-9\.:\/]{0,}\/|)list\.php\?fid=([\d]+)&page=([\d]+)/eis","fake_html_Function('$listpath','\\3','','\\4','\\2',' href=\\1')",$content);	//有分页的列表页
	$content=preg_replace("/ href=('|\"|)([-_a-z0-9\.:\/]{0,}\/|)list\.php\?fid=([\d]+)/eis","fake_html_Function('$listpath','\\3','','1','\\2',' href=\\1')",$content);	//无分页的列表页
	
	//专题
	$listpath=$webdb[SPlist_filename2];
	$bencandypath=$webdb[SPbencandy_filename2];
	$content=preg_replace("/ href=('|\"|)([-_a-z0-9\.:\/]{0,}\/|)showsp\.php\?fid=([\d]+)&id=([\d]+)/eis","fake_html_Function('$bencandypath','\\3','\\4','1','\\2',' href=\\1')",$content);	//最终页
	$content=preg_replace("/ href=('|\"|)([-_a-z0-9\.:\/]{0,}\/|)listsp\.php\?fid=([\d]+)&page=([\d]+)/eis","fake_html_Function('$listpath','\\3','','\\4','\\2',' href=\\1')",$content);	//有分页的列表页
	$content=preg_replace("/ href=('|\"|)([-_a-z0-9\.:\/]{0,}\/|)listsp\.php\?fid=([\d]+)/eis","fake_html_Function('$listpath','\\3','','1','\\2',' href=\\1')",$content);	//无分页的列表页

	return $content;
}

/**
*真静态专题页功能函数
**/
function make_sphtml_Function($fid,$id,$page=1,$P='',$linkcode=''){
	global $webdb,$Html_Type,$showHtml_Type,$WEBURL,$db,$pre;
	$P=preg_replace("/(.*)(do\/)$/is","\\1",$P);
	$linkcode=stripslashes($linkcode);
	if($id){
		if($showHtml_Type[SPbencandy][$id]){
			$filename=$showHtml_Type[SPbencandy][$id];
		}elseif($Html_Type['SPbencandy'][$fid]){
			$filename=$Html_Type['SPbencandy'][$fid];
		}else{
			$filename=$webdb[SPbencandy_filename];
		}
		$dirid=floor($id/1000);
		if(strstr($filename,'$time_')){
			$rs=$db->get_one("SELECT posttime FROM {$pre}special WHERE id='$id'");
			$time_Y=date("Y",$rs[posttime]);
			$time_y=date("y",$rs[posttime]);
			$time_m=date("m",$rs[posttime]);
			$time_d=date("d",$rs[posttime]);
			$time_W=date("W",$rs[posttime]);
			$time_H=date("H",$rs[posttime]);
			$time_i=date("i",$rs[posttime]);
			$time_s=date("s",$rs[posttime]);
		}
	}else{
		if($Html_Type['SPlist'][$fid]){
			$filename=$Html_Type['SPlist'][$fid];
		}else{
			$filename=$webdb[SPlist_filename];
		}
		//if($page==1){
		//	$filename=preg_replace("/(.*)\/([^\/]+)/is","\\1/",$filename);
		//}
	}

	$dirid=floor($id/1000);
	eval("\$filename=\"$filename\";");

	//使用绝对地址,避免在二级目录以下URL出错
	if(!$P||$P=='./'){
		$P="$webdb[www_url]/";
	}
	return "$linkcode$P$filename";
}

/**
*真静态功能函数
**/
function make_html_Function($fid,$id,$page=1,$P='',$linkcode=''){
	global $webdb,$Html_Type,$showHtml_Type,$WEBURL,$db,$pre;
	$linkcode=stripslashes($linkcode);
	if($id){
		if($showHtml_Type[bencandy][$id]){
			$filename=$showHtml_Type[bencandy][$id];
		}elseif($Html_Type['bencandy'][$fid]){
			$filename=$Html_Type['bencandy'][$fid];
		}else{
			$filename=$webdb[bencandy_filename];
		}
		//对于内容页的首页把$page去除
		if($page==1){
			$filename=preg_replace("/(.*)(-{\\\$page}|_{\\\$page})(.*)/is","\\1\\3",$filename);
		}
		$dirid=floor($id/1000);
		//对于内容页的栏目小于1000篇文章时,把DIR分目录去除
		if($dirid==0){
			$filename=preg_replace("/(.*)(-{\\\$dirid}|_{\\\$dirid})(.*)/is","\\1\\3",$filename);
		}
		if(strstr($filename,'$time_')){
			$erp=get_id_table($id);
			$rs=$db->get_one("SELECT posttime FROM {$pre}article$erp WHERE aid='$id'");
			$time_Y=date("Y",$rs[posttime]);
			$time_y=date("y",$rs[posttime]);
			$time_m=date("m",$rs[posttime]);
			$time_d=date("d",$rs[posttime]);
			$time_W=date("W",$rs[posttime]);
			$time_H=date("H",$rs[posttime]);
			$time_i=date("i",$rs[posttime]);
			$time_s=date("s",$rs[posttime]);
		}
	}else{
		if($Html_Type['list'][$fid]){
			$filename=$Html_Type['list'][$fid];
		}else{
			$filename=$webdb[list_filename];
		}
		if($page==1)
		{
			$filename=preg_replace("/(.*)\/([^\/]+)/is","\\1/",$filename);
		}
	}
	
	/*
	if($P&&$P!='/'&&$P!="$webdb[www_url]/"){
		if($id){
			return "{$P}bencandy.php?fid=$fid&id=$id";
		}else{
			return "{$P}list.php?fid=$fid";
		}
	}
	*/
	//$dirid=floor($id/1000);
	eval("\$filename=\"$filename\";");
	
	//自定义了栏目域名
	if($Html_Type[domain][$fid]&&$Html_Type[domain_dir][$fid]){
		$rule=str_replace("/","\/",$Html_Type[domain_dir][$fid]);
		$filename=preg_replace("/^$rule/is","{$Html_Type[domain][$fid]}/",$filename);
		//特别处理一下些自定义内容页文件名的情况.
		if(!eregi("^http:\/\/",$filename)){
			$filename="$webdb[www_url]/$filename";
		}
		return "$linkcode$filename";
	}else{
		//使用绝对地址,避免在二级目录以下URL出错
		if(!$P||$P=='./'){
			$P="$webdb[www_url]/";
		}
		return "$linkcode$P$filename";		
	}
}

/**
*真静态功能函数
**/
function make_html($content,$pagetype='N'){
	
	$content=preg_replace("/ href=('|\"|)([-_a-z0-9\.:\/]{0,}\/|)bencandy\.php\?fid=([\d]+)&(aid|id)=([\d]+)&page=([\d]+)/eis","make_html_Function('\\3','\\5','\\6','\\2',' href=\\1')",$content);	//有分页的最终页
	$content=preg_replace("/ href=('|\"|)([-_a-z0-9\.:\/]{0,}\/|)bencandy\.php\?fid=([\d]+)&(id|aid)=([\d]+)/eis","make_html_Function('\\3','\\5','1','\\2',' href=\\1')",$content);	//无分页的最终页
	$content=preg_replace("/ href=('|\"|)([-_a-z0-9\.:\/]{0,}\/|)list\.php\?fid=([\d]+)&page=([\d]+)/eis","make_html_Function('\\3','','\\4','\\2',' href=\\1')",$content);	//有分页的列表页
	$content=preg_replace("/ href=('|\"|)([-_a-z0-9\.:\/]{0,}\/|)list\.php\?fid=([\d]+)/eis","make_html_Function('\\3','','1','\\2',' href=\\1')",$content);	//无分页的列表页
	
	//专题静态
	$content=preg_replace("/ href=('|\"|)([-_a-z0-9\.:\/]{0,}\/|)showsp\.php\?fid=([\d]+)&(id|aid)=([\d]+)/eis","make_sphtml_Function('\\3','\\5','1','\\2',' href=\\1')",$content);	//无分页的最终页
	$content=preg_replace("/ href=('|\"|)([-_a-z0-9\.:\/]{0,}\/|)listsp\.php\?fid=([\d]+)&page=([\d]+)/eis","make_sphtml_Function('\\3','','\\5','',' href=\\1')",$content);	//有分页的列表页
	$content=preg_replace("/ href=('|\"|)([-_a-z0-9\.:\/]{0,}\/|)listsp\.php\?fid=([\d]+)/eis","make_sphtml_Function('\\3','','1','\\2',' href=\\1')",$content);	//无分页的列表页

	if($pagetype=='N'){
		return $content;
	}
	
	global $fid,$id,$fidDB,$webdb,$page,$rsdb,$Html_Type,$showHtml_Type;
	
	$content=str_replace("jsspecial.php?","jsspecial.php?gethtmlurl=1&",$content);
	$content=str_replace("jsarticle.php?","jsarticle.php?gethtmlurl=1&",$content);

	if($pagetype=='bencandy')
	{
		if($showHtml_Type[bencandy][$id]){
			$filename=$showHtml_Type[bencandy][$id];
		}elseif($fidDB[bencandy_html]){
			$filename=$fidDB[bencandy_html];
		}else{
			$filename=$webdb[bencandy_filename];
		}
		//对于内容页的首页把$page去除
		if($page==1){
			$filename=preg_replace("/(.*)(-{\\\$page}|_{\\\$page})(.*)/is","\\1\\3",$filename);
		}
		$dirid=floor($id/1000);
		//对于内容页的栏目小于1000篇文章时,把DIR分目录去除
		if($dirid==0){
			$filename=preg_replace("/(.*)(-{\\\$dirid}|_{\\\$dirid})(.*)/is","\\1\\3",$filename);
		}
		if(strstr($filename,'$time_')){
			$time_Y=date("Y",$rsdb[full_posttime]);
			$time_y=date("y",$rsdb[full_posttime]);
			$time_m=date("m",$rsdb[full_posttime]);
			$time_d=date("d",$rsdb[full_posttime]);
			$time_W=date("W",$rsdb[full_posttime]);
			$time_H=date("H",$rsdb[full_posttime]);
			$time_i=date("i",$rsdb[full_posttime]);
			$time_s=date("s",$rsdb[full_posttime]);
		}
		$content.="<div style='display:none;'><iframe width=0 height=0 src='$webdb[www_url]/do/job.php?job=updatehits&aid=$id'></iframe></div>";
	}
	elseif($pagetype=='list')
	{
		if($fidDB[list_html]){
			$filename=$fidDB[list_html];
		}else{
			$filename=$webdb[list_filename];
		}
		if($page==1){
			if($webdb[DefaultIndexHtml]==2){
				$filename=preg_replace("/(.*)\/([^\/]+)/is","\\1/index.shtml",$filename);
			}elseif($webdb[DefaultIndexHtml]==1){
				$filename=preg_replace("/(.*)\/([^\/]+)/is","\\1/index.html",$filename);
			}else{
				$filename=preg_replace("/(.*)\/([^\/]+)/is","\\1/index.htm",$filename);
			}
		}
	}
	elseif($pagetype=='listsp')
	{
		if($fidDB[list_html]){
			$filename=$fidDB[list_html];
		}else{
			$filename=$webdb[SPlist_filename];
		}
	}
	elseif($pagetype=='showsp')
	{
		if($showHtml_Type[SPbencandy][$id]){
			$filename=$showHtml_Type[SPbencandy][$id];
		}elseif($fidDB[SPbencandy_html]){
			$filename=$fidDB[SPbencandy_html];
		}else{
			$filename=$webdb[SPbencandy_filename];
		}
		if(strstr($filename,'$time_')){
			$time_Y=date("Y",$rsdb[full_posttime]);
			$time_y=date("y",$rsdb[full_posttime]);
			$time_m=date("m",$rsdb[full_posttime]);
			$time_d=date("d",$rsdb[full_posttime]);
			$time_W=date("W",$rsdb[full_posttime]);
			$time_H=date("H",$rsdb[full_posttime]);
			$time_i=date("i",$rsdb[full_posttime]);
			$time_s=date("s",$rsdb[full_posttime]);
		}		
		$content.="<SCRIPT LANGUAGE='JavaScript' src='$webdb[www_url]/do/job.php?job=update_special_hits&id=$id'></SCRIPT>";
	}
	eval("\$filename=\"$filename\";");
	$HtmlDir=dirname($filename);
	if(!is_dir(PHP168_PATH.$HtmlDir)){
		makepath(PHP168_PATH.$HtmlDir);
	}
	write_file(PHP168_PATH.$filename,$content);
	return $content;
}

function show_keyword($content){
	global $Key_word,$webdb;
	if(!$webdb[ifShowKeyword]){
		return $content;
	}
	require_once(PHP168_PATH."php168/keyword.php");
	//把图片描述去掉
	$content=preg_replace("/ alt=([^ >]+)/is","",$content);
	foreach( $Key_word AS $key=>$value){
		if(!$value){
			$value="$webdb[www_url]/do/search.php?type=title&keyword=".urlencode($key);
		}
		$content=str_replace($key,"<a href=$value style=text-decoration:underline;font-size:14px;color:{$webdb[ShowKeywordColor]}; target=_blank>$key</a>",$content);
	}
	return $content;
}

//为多页的文章自动添加页码
function Set_Title_PageNum($title,$page){
	$page<1 && $page=1;
	if($page<100){
		if($page==10){
			$page='0';
		}elseif($page>10&&$page%10!=0){
			if($page>20){
				$page=floor($page/10).'0'.($page%10);
			}else{
				$page='0'.($page%10);
			}
			
		}
		$array_ch=array("十","一","二","三","四","五","六","七","八","九");
		$array_ali=array("/0/","/1/","/2/","/3/","/4/","/5/","/6/","/7/","/8/","/9/");
		$page=preg_replace($array_ali,$array_ch,$page);
	}
	return "{$title}({$page})";
}

?>