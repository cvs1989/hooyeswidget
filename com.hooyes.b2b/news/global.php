<?php
define('Mpath',dirname(__FILE__).'/');
define( 'Mdirname' , preg_replace("/(.*)\/([^\/]+)/is","\\2",str_replace("\\","/",dirname(__FILE__))) );

require_once(Mpath."../inc/common.inc.php");
require_once(Mpath."data/config.php");			//本系统全局变量值
require_once(Mpath."data/all_fid.php");			//部分栏目的名称变量值
@include_once(ROOT_PATH."data/ad_cache.php");	//全站广告变量缓存文件
require_once(ROOT_PATH."data/label_hf.php");	//标签的头与底的变量值
@include_once(ROOT_PATH."data/module.php");		//模块系统的参数变量值

$_pre="{$pre}{$webdb[module_pre]}";					//数据表前缀
$Murl=$webdb[www_url].'/'.Mdirname;					//本模块的访问地址
$Mdomain=$ModuleDB[$webdb[module_pre]][domain]?$ModuleDB[$webdb[module_pre]][domain]:$Murl;

//@include_once(Mpath."biz/function.php");

/**
*系统默认风格
**/
//$STYLE=$webdb[Info_style]?$webdb[Info_style]:"default";

/**
*前台是否开放本系统
**/
if($webdb[module_close])
{
	$webdb[Info_closeWhy]=str_replace("\r\n","<br>",$webdb[Info_closeWhy]);
	showerr("当前系统暂时关闭:$webdb[Info_closeWhy]");
}


$fid=intval($fid);
$id=intval($id);
$page=intval($page);
$rows=intval($rows);
$leng=intval($leng);




/**
*获取模板的函数
**/
function getTpl($html,$tplpath=''){
	global $STYLE;
	if($tplpath&&file_exists($tplpath)){
		return $tplpath;
	}elseif($tplpath&&file_exists(Mpath.$tplpath)){
		return Mpath.$tplpath;
	}elseif(file_exists(Mpath."template/$STYLE/$html.htm")){
		return Mpath."template/$STYLE/$html.htm";
	}else{
		return Mpath."template/default/$html.htm";
	}
}

/**
*获取信息内容
**/
function list_content($SQL,$leng=40){
	global $db,$_pre,$webdb;
	$query=$db->query("SELECT A.* FROM {$_pre}content A $SQL");
	while( $rs=$db->fetch_array($query) ){
		//把辅信息表的内容也同时取出来,方便给模板调用
		$rs[mid] && $rss=$db->get_one("SELECT * FROM {$_pre}content_$rs[mid] WHERE id=$rs[id] LIMIT 1");
		is_array($rss) && $rs=$rs+$rss;

		if($webdb[Info_KeepDataTxt])
		{
			$_rid=$rs[rid];
			$dirid=floor($_rid/1000);
			$rs[content]=read_file(Mpath."data/data/1_$dirid/$_rid.php");
			$rs[content]=substr($rs[content], 15);
		}

		$rs[content]=@preg_replace('/<([^>]*)>/is',"",$rs[content]);	//把HTML代码过滤掉
		$rs[content]=get_word($rs[full_content]=$rs[content],100);
		$rs[title]=get_word($rs[full_title]=$rs[title],$leng);
		if($rs[titlecolor]||$rs[fonttype]){
			$titlecolor=$rs[titlecolor]?"color:$rs[titlecolor];":'';
			$font_weight=$rs[fonttype]==1?'font-weight:bold;':'';
			$rs[title]="<font style='$titlecolor$font_weight'>$rs[title]</font>";
		}
		$rs[posttime]=date("Y-m-d",$rs[full_time]=$rs[posttime]);
		if($rs[picurl]){
			$rs[picurl]=tempdir($rs[picurl]);
		}
		$listdb[]=$rs;
	}
	return $listdb;
}


/**
*获取子栏目
**/
function Get_Fid($fid,$rows=100){
	global $db,$_pre;
	$fid=intval($fid);
	$query = $db->query("SELECT * FROM {$_pre}sort WHERE fup=$fid ORDER BY list DESC LIMIT $rows");
	while($rs = $db->fetch_array($query)){
		$F[$rs[fid]]=$rs;
	}
	return $F;
}

function GetSonFid($fid,$rows=100){
	global $db,$_pre;
	$fid=intval($fid);
	$query = $db->query("SELECT * FROM {$_pre}sort WHERE fup=$fid ORDER BY list DESC LIMIT $rows");
	while($rs = $db->fetch_array($query)){
		$F[$rs[fid]]=$rs[fid];
	}
	return $F;
}

function GetAllSonFid($fid,$rows=100){
	global $db,$_pre;
	$fid=intval($fid);
	$query = $db->query("SELECT fid,fup FROM {$_pre}sort WHERE fup=$fid ORDER BY list DESC LIMIT $rows");
	while($rs = $db->fetch_array($query)){
		$show.=",$rs[fid]";
		$show.=GetAllSonFid($rs[fid],$rows);
	}
	return $show;
}

function GetAll_SPSonFid($fid,$rows=100){
	global $db,$_pre;
	$fid=intval($fid);
	$query = $db->query("SELECT fid,fup FROM {$_pre}spsort WHERE fup=$fid ORDER BY list DESC LIMIT $rows");
	while($rs = $db->fetch_array($query)){
		$show.=",$rs[fid]";
		$show.=GetAll_SPSonFid($rs[fid],$rows);
	}
	return $show;
}


/**
*获取信息内容
**/
function Get_Info($type,$rows=5,$leng=20,$fid=0,$mid=0,$getall=0,$cityid=0,$provinceid=0){
	if($mid){
		$SQL=" AND A.mid='$mid' ";
	}
	if($cityid){
		$SQL=" AND A.cityid='$cityid' ";
	}elseif($provinceid){
		$SQL=" AND A.provinceid='$provinceid' ";
	}
	if($fid){
		if($getall){
			$SQL.=" AND A.fid IN ($fid".GetAllSonFid($fid).") ";
		}else{
			$SQL.=" AND A.fid='$fid' ";
		}
	}
	if($type=='hot'){
		$SQL=" USE INDEX (hits) WHERE A.yz=1 $SQL ORDER BY A.hits DESC LIMIT $rows";
	}elseif($type=='lastview'){
		$SQL=" USE INDEX (lastview) WHERE A.yz=1 $SQL ORDER BY A.lastview DESC LIMIT $rows";
	}elseif($type=='comment'){
		$SQL=" WHERE A.yz=1 $SQL ORDER BY A.comments DESC LIMIT $rows";
	}elseif($type=='new'){
		$SQL=" USE INDEX (list) WHERE A.yz=1 $SQL ORDER BY A.list DESC LIMIT $rows";
	}elseif($type=='level'){
		$SQL="WHERE A.yz=1 AND A.levels=1 $SQL ORDER BY A.list DESC LIMIT $rows";
	}elseif($type=='pic'){
		$SQL="WHERE A.yz=1 AND A.ispic=1 $SQL ORDER BY A.list DESC LIMIT $rows";
	}else{
		return ;
	}
	$listdb=list_content($SQL,$leng);
	return $listdb;
}

/**
*获取副栏目信息内容
**/
function Get_spInfo($type,$rows=5,$leng=20,$fid=0,$mid=0,$getall=0,$cityid=0,$provinceid=0){
	global $_pre;
	if($mid){
		$SQL=" AND A.mid='$mid' ";
	}
	if($cityid){
		$SQL=" AND A.cityid='$cityid' ";
	}elseif($provinceid){
		$SQL=" AND A.provinceid='$provinceid' ";
	}
	if($fid){
		if($getall){
			$SQL.=" AND SP.fid IN ($fid".GetAll_SPSonFid($fid).") ";
		}else{
			$SQL.=" AND SP.fid='$fid' ";
		}
	}else{
		return ;
	}
	if($type=='hot'){
		$SQL="WHERE A.yz=1 $SQL ORDER BY A.hits DESC LIMIT $rows";
	}elseif($type=='lastview'){
		$SQL="WHERE A.yz=1 $SQL ORDER BY A.lastview DESC LIMIT $rows";
	}elseif($type=='new'){
		$SQL="WHERE A.yz=1 $SQL ORDER BY A.list DESC LIMIT $rows";
	}elseif($type=='level'){
		$SQL="WHERE A.yz=1 AND A.levels=1 $SQL ORDER BY A.list DESC LIMIT $rows";
	}elseif($type=='pic'){
		$SQL="WHERE A.yz=1 AND A.ispic=1 $SQL ORDER BY A.list DESC LIMIT $rows";
	}else{
		return ;
	}
	$listdb=list_content(" LEFT JOIN {$_pre}special SP ON SP.id=A.id $SQL",$leng);
	return $listdb;
}

/**
*真静态功能函数
**/
function make_html_Function($fid,$id,$page=1,$P=''){
	global $webdb,$HtmlType,$WEBURL,$db,$_pre,$Mdomain,$Murl;
	if($id){
		if($HtmlType['bencandy'][$fid]){
			$filename=$HtmlType['bencandy'][$fid];
		}else{
			$filename=$webdb[Info_bencandy_filename];
		}
		$dirid=floor($id/1000);
		if(strstr($filename,'$time_')){
			$rs=$db->get_one("SELECT posttime FROM {$_pre}content WHERE id='$id'");
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
		if($HtmlType['list'][$fid]){
			$filename=$HtmlType['list'][$fid];
		}else{
			$filename=$webdb[Info_list_filename];
		}
		if($page==1)
		{
			$filename=preg_replace("/(.*)\/([^\/]+)/is","\\1/",$filename);
		}
	}

	if($P&&$P!='/'){
		$weburl=preg_replace("/(.*)\/([^\/]+)/is","\\1/",$WEBURL);
		if(!ereg($P,$weburl)&&$P!="$Murl/"&&$P!="$Mdomain/"){
			if($id){
				return "{$P}bencandy.php?fid=$fid&id=$id";
			}else{
				return "{$P}list.php?fid=$fid";
			}
		}
	}

	$dirid=floor($id/1000);
	eval("\$filename=\"$filename\";");

	if(!$P){
		$P="$Mdomain/";
	}
	return "$P$filename";
}

/**
*真静态功能函数
**/
function make_html($content,$pagetype=''){
	global $fid,$id,$fidDB,$webdb,$page,$rsdb;
	$content=preg_replace("/bencandy\.php\?fid=([\d]+)&(aid|id)=([\d]+)&page=([\d]+)/eis","make_html_Function('\\1','\\3','\\4','')",$content);	//有分页的最终页
	$content=preg_replace("/([a-z0-9-\.:\/]{0,})bencandy\.php\?fid=([\d]+)&(id|aid)=([\d]+)/eis","make_html_Function('\\2','\\4','1','\\1')",$content);	//无分页的最终页
	$content=preg_replace("/list\.php\?fid=([\d]+)&page=([\d]+)/eis","make_html_Function('\\1','','\\2','')",$content);	//有分页的列表页
	$content=preg_replace("/([a-z0-9-\.:\/]{0,})list\.php\?fid=([\d]+)/eis","make_html_Function('\\2','','1','\\1')",$content);	//无分页的列表页

	if($pagetype=='index')
	{
		$filename='index.htm';
	}
	elseif($id)
	{
		if($fidDB[bencandy_html]){
			$filename=$fidDB[bencandy_html];
		}else{
			$filename=$webdb[Info_bencandy_filename];
		}
		$dirid=floor($id/1000);
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
	}
	else
	{
		if($fidDB[list_html]){
			$filename=$fidDB[list_html];
		}else{
			$filename=$webdb[Info_list_filename];
		}
		if($page==1)
		{
			$filename=preg_replace("/(.*)\/([^\/]+)/is","\\1/index.htm",$filename);
		}
	}
	eval("\$filename=\"$filename\";");
	$HtmlDir=dirname($filename);
	if(!is_dir(ROOT_PATH.Mdirname."/$HtmlDir")){
		makepath(ROOT_PATH.Mdirname."/$HtmlDir");
	}
	if($pagetype!='index')
	{
		write_file($filename,$content);
	}
	return $content;
}



/**
*针对栏目获取内容信息列表
**/
function ListThisSort($rows,$leng){
	global $page,$fid,$fidDB,$SQL;
	if($page<1){
		$page=1;
	}
	if($fidDB[listorder]==1){
		$sql_list="A.posttime";
		$sql_order="DESC";
	}elseif($fidDB[listorder]==2){
		$sql_list="A.posttime";
		$sql_order="ASC";
	}elseif($fidDB[listorder]==3){
		$sql_list="A.hits";
		$sql_order="DESC";
	}elseif($fidDB[listorder]==4){
		$sql_list="A.hits";
		$sql_order="ASC";
	}elseif($fidDB[listorder]==5){
		$sql_list="A.lastview";
		$sql_order="DESC";
	}else{
		$sql_list="A.list";
		$sql_order="DESC";
	}
	$min=($page-1)*$rows;
	$_SQL="WHERE A.fid=$fid AND A.yz=1 $SQL ORDER BY $sql_list $sql_order LIMIT $min,$rows";
	$listdb=list_content($_SQL,$leng);
	return $listdb;
}


/**
*针对分类获取子栏目
**/
function ListOnlySort($rows){
	global $db,$_pre,$fid,$page,$Fid_db,$fidDB,$webdb;

	$_SonOrder='id';

	//排序
	if($fidDB[config][sonListorder]==1){
		$_SonOrder='id';		//理应是list
	}elseif($fidDB[config][sonListorder]==2){
		$_SonOrder='hits';
	}elseif($fidDB[config][sonListorder]==3){
		$_SonOrder='lastview';
	}elseif($fidDB[config][sonListorder]==4){
		$_SonOrder='rand()';
	}else{
		$_SonOrder='id';
	}

	//显示几行
	if($fidDB[config][sonTitleRow]>0){
		$_SonRow=$fidDB[config][sonTitleRow];
	}elseif($webdb[InfoListSonRows]>0){
		$_SonRow=$webdb[InfoListSonRows];
	}else{
		$_SonRow=10;
	}

	//每个标题显示几个字
	if($fidDB[config][sonTitleLeng]>0){
		$_SonLeng=$fidDB[config][sonTitleLeng];
	}elseif($webdb[InfoListSonLeng]>0){
		$_SonLeng=$webdb[InfoListSonLeng];
	}else{
		$_SonLeng=30;
	}

	if(!$page){
		$page=1;
	}
	$min=($page-1)*$rows;
	$query=$db->query("SELECT * FROM {$_pre}sort WHERE fup=$fid AND forbidshow=0 ORDER BY list DESC LIMIT $min,$rows");
	while($rs=$db->fetch_array($query))
	{
		$rs[article]=$_SQL=$fiddb='';
		if($rs[type]){
			foreach( $Fid_db[$rs[fid]] AS $key=>$value){
				$fiddb[]=$key;
				foreach( $Fid_db[$key] AS $key2=>$value2){
					$fiddb[]=$key2;
				}
			}
			if($fiddb){
				$fids=implode(",",$fiddb);
				$_SQL="WHERE A.fid IN ($fids) AND A.yz=1 ORDER BY $_SonOrder DESC LIMIT $_SonRow";
			}
		}else{
			$_SQL="WHERE A.fid=$rs[fid] AND A.yz=1 ORDER BY $_SonOrder DESC LIMIT $_SonRow";
		}
		if($_SQL){
			$rs[article]=list_content($_SQL,$_SonLeng);
		}
		$rs[logo] && $rs[logo]=tempdir($rs[logo]);
		$listdb[]=$rs;
	}
	return $listdb;
}


/**
*伪静态功能函数
**/
function fake_html_Function($filename,$fid,$id,$page=1,$P=''){
	$dirid=floor($id/1000);
	eval("\$filename=\"$filename\";");
	return "$P$filename";
}

/**
*伪静态功能函数
**/
function fake_html($content){
	global $webdb;
	$listpath=$webdb[Info_list_filename2];
	$bencandypath=$webdb[Info_bencandy_filename2];

	$content=preg_replace("/bencandy\.php\?fid=([\d]+)&(aid|id)=([\d]+)&page=([\d]+)/eis","fake_html_Function('$bencandypath','\\1','\\3','\\4','')",$content);	//有分页的最终页
	$content=preg_replace("/([a-z0-9-\.:\/]{0,})bencandy\.php\?fid=([\d]+)&(id|aid)=([\d]+)/eis","fake_html_Function('$bencandypath','\\2','\\4','1','\\1')",$content);	//无分页的最终页
	$content=preg_replace("/list\.php\?fid=([\d]+)&page=([\d]+)/eis","fake_html_Function('$listpath','\\1','','\\2','')",$content);	//有分页的列表页
	$content=preg_replace("/([a-z0-9-\.:\/]{0,})list\.php\?fid=([\d]+)/eis","fake_html_Function('$listpath','\\2','','1','\\1')",$content);	//无分页的列表页

	return $content;
}

?>