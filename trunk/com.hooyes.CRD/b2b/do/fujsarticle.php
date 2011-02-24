<?php
error_reporting(0);extract($_GET);
require_once(dirname(__FILE__)."/../php168/config.php");
if(!eregi("^(hot|com|new|lastview|like|pic)$",$type)){
	die("类型有误");
}
$FileName=dirname(__FILE__)."/../cache/fujsarticle_cache/";
if($type=='like'){
	$FileName.=floor($id/3000)."/";
}else{
	unset($id);
}

$FileName.="{$type}_{$fid}_{$id}.php";
//默认缓存3分钟.
if(!$webdb["cache_time_$type"]){
	$webdb["cache_time_$type"]=3;
}
if( (time()-filemtime($FileName))<($webdb["cache_time_$type"]*60) ){
	@include($FileName);
	$show=str_replace(array("\n","\r","'"),array("","","\'"),stripslashes($show));
	if($iframeID){	//框架方式不会拖慢主页面打开速度,推荐
		//处理跨域问题
		if($webdb[cookieDomain]){
			echo "<SCRIPT LANGUAGE=\"JavaScript\">document.domain = \"$webdb[cookieDomain]\";</SCRIPT>";
		}
		echo "<SCRIPT LANGUAGE=\"JavaScript\">
		parent.document.getElementById('$iframeID').innerHTML='$show';
		</SCRIPT>";
	}else{			//JS式会拖慢主页面打开速度,不推荐
		echo "document.write('$show');";
	}
	exit;
}

require_once(dirname(__FILE__)."/global.php");

//默认缓存3分钟.
if(!$webdb["cache_time_$type"]){
	$webdb["cache_time_$type"]=3;
}

$rows>0 || $rows=7;
$leng>0 || $leng=60;

unset($SQL,$show);

//热门文章,推荐文章,最新文章
if($type=='hot'||$type=='com'||$type=='new'||$type=='lastview'||$type=='like'||$type=='pic')
{
	$erp=$Fid_db[iftable][$fid];
	if($fid)
	{
		$f_id=get_fid($fid);
		$SQL=" (".implode("OR",$f_id).") ";
	}
	else
	{
		$SQL=" 1 ";
	}

	if($type=='com')
	{
		$SQL.=" AND A.levels=1 ";
		$ORDER=' A.list ';
	}
	elseif($type=='pic')
	{
		$SQL.=" AND A.ispic=1 ";
		$ORDER=' A.list ';
	}
	elseif($type=='hot')
	{
		$ORDER=' A.hits ';
	}
	elseif($type=='new')
	{
		$ORDER=' A.list ';
	}
	elseif($type=='lastview')
	{
		$ORDER=' A.lastview ';
	}
	elseif($type=='like')
	{
		
		$SQL.=" AND A.aid!='$id' ";

		if(!$keyword)
		{
			$erp=get_id_table($id);
			extract($db->get_one("SELECT keywords AS keyword FROM {$pre}article$erp WHERE aid='$id'"));
		}

		if($keyword){
			$detail=explode(" ",$keyword);
			unset($detail2,$ids);
			foreach( $detail AS $key=>$value){
				$value && $detail2[]=" B.keywords='$value' ";
			}
			$str=implode(" OR ",$detail2);
			if($str){
				unset($ids);
				$query = $db->query("SELECT A.aid FROM {$pre}keywordid A LEFT JOIN {$pre}keyword B ON A.id=B.id WHERE $str");
				while($rs = $db->fetch_array($query)){
					$ids[]=$rs[aid];
				}
				if($ids){
					$SQL.=" AND A.aid IN (".implode(",",$ids).") ";
				}else{
					$SQL.=" AND 0 ";
				}				
			}
		}else{
			$SQL.=" AND 0 ";
		}
		
		$ORDER=' A.list ';
	}

	if(!$webdb[viewNoPassArticle]){
		$SQL.=' AND A.yz=1 ';
	}
	
	$SQL="A LEFT JOIN {$pre}fu_article FA ON A.aid=FA.aid WHERE $SQL GROUP BY A.aid ORDER BY $ORDER DESC LIMIT $rows";
	$which='A.*';
	$listdb='';
	$array=list_article($SQL,$which,$leng,$erp);
	foreach($array AS $key=>$r){
		$listdb[$r[aid]]=$r;
	}
	
	if(is_file(PHP168_PATH."template/default/$webdb[SideTitleStyle].htm")){
		$tplcode=read_file(PHP168_PATH."template/default/$webdb[SideTitleStyle].htm");
	}else{
		$tplcode=read_file(PHP168_PATH."template/default/side_tpl/0.htm");
	}
	$tplcode=addslashes($tplcode);
	foreach($listdb AS $key=>$rs)
	{
		//$target=$rs[target]?'_blank':'_self';
		$target='_blank';
		if($type=='pic'){
			$show.="<div class='p' style='float:left;width:130px;padding-left:5px;padding-top:5px;'>  <a href='bencandy.php?fid=$rs[fid]&id=$rs[aid]' style='display:block;width:120px;height:90px;border:1px solid #ccc;' target='$target'><img style='border:2px solid #fff;' width='120' height='90' src='$rs[picurl]' border='0'></a>  <A HREF='$webdb[www_url]/bencandy.php?fid=$rs[fid]&id=$rs[aid]' title='$rs[full_title]' target='$target'>$rs[title]</A>  </div>";
		}else{
			eval("\$show.=\"$tplcode\";");
		}		
	}
	if(!$show){
		$show="暂无...";
	}
}
$show=stripslashes($show);
//真静态
if($webdb[NewsMakeHtml]==1||$gethtmlurl){

	$show=make_html($show,$pagetype='N');

//伪静态
}elseif($webdb[NewsMakeHtml]==2){

	$show=fake_html($show);
}

$show=str_replace(array("\n","\r","'"),array("","","\'"),$show);

if(!is_dir(dirname($FileName))){
	makepath(dirname($FileName));
}
if( (time()-filemtime($FileName))>($webdb["cache_time_$type"]*60) ){
	write_file($FileName,"<?php \r\n\$show=stripslashes('".addslashes($show)."'); ?>");
}

if($iframeID){	//框架方式不会拖慢主页面打开速度,推荐
	//处理跨域问题
	if($webdb[cookieDomain]){
		echo "<SCRIPT LANGUAGE=\"JavaScript\">document.domain = \"$webdb[cookieDomain]\";</SCRIPT>";
	}
	echo "<SCRIPT LANGUAGE=\"JavaScript\">
	parent.document.getElementById('$iframeID').innerHTML='$show';
	</SCRIPT>";
}else{			//JS式会拖慢主页面打开速度,不推荐
	echo "document.write('$show');";
}
exit;

function get_fid($fid){
	global $db,$pre;
	$fid=intval($fid);
	$F[]=" FA.fid=$fid ";
	$query = $db->query("SELECT fid FROM {$pre}fu_sort WHERE fup='$fid'");
	while($rs = $db->fetch_array($query)){
		$F[]=" FA.fid=$rs[fid] ";
	}
	return $F;
}

?>