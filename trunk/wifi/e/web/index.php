<?php
require('../class/connect.php');
require('../class/q_functions.php');
require('../class/db_sql.php');
require('../data/dbcache/class.php');
$link=db_connect();
$empire=new mysqlquery();
//参数设置
$pagecode=$phome_ecms_charver?$phome_ecms_charver:'gb2312';

//返回简介字段名
function ReturnTheIntroField($r){
	global $public_r,$class_r,$emod_r,$tbname;
	$mid=$class_r[$r[classid]]['modid'];
	$smalltextf=$emod_r[$mid]['smalltextf'];
	$stf=$emod_r[$mid]['savetxtf'];
	$field='';
	if($smalltextf&&$smalltextf<>',')
	{
		$smr=explode(',',$smalltextf);
		$smcount=count($smr)-1;
		for($i=1;$i<$smcount;$i++)
		{
			$smf=$smr[$i];
			if($r[$smf])
			{
				$field=$smf;
				break;
			}
		}
	}
	if(empty($field))
	{
		$field='newstext';
	}
	//存文本
	if($stf==$field)
	{
		$field='';
	}
	return $field;
}
//替换
function RepSpeRssStr($str){
	$str=stripSlashes($str);
	$str=htmlspecialchars($str,ENT_QUOTES);
	$str=str_replace(array('[!--empirenews.page--]','[/!--empirenews.page--]','[',']'),array('','','',''),$str);
	return $str;
}

//地址验证
if(!stristr($public_r['newsurl'],'://'))
{
	$public_r['newsurl']=eReturnDomain().$public_r['newsurl'];
}

$pagetitle=$public_r['sitename'];
$pageurl=$public_r['newsurl'];
$pageecms=1;
$pageclassid=0;
$tbname='';
$add='';
//模型ID
$mid=(int)$_GET['mid'];
if($mid)
{
	$tbname=$emod_r[$mid]['tbname'];
	if(empty($tbname))
	{
		exit();
	}
}
//栏目
$trueclassid=0;
$classid=$_GET['classid'];
if($classid)
{
	$classid=RepPostVar($classid);
	if(strstr($classid,','))//多栏目
	{
		$son_r=sys_ReturnMoreClass($classid,1);
		$trueclassid=$son_r[0];
		$add.=' and ('.$son_r[1].')';
	}
	else
	{
		$trueclassid=intval($classid);
		if($class_r[$trueclassid][islast])//终极栏目
		{
			$add.=" and classid='$trueclassid'";
		}
		else
		{
			$add.=' and '.ReturnClass($class_r[$trueclassid][sonclass]);
		}
		//页面标题
		$pagetitle=$class_r[$trueclassid]['classname'];
		$this_r['classid']=$trueclassid;
		$pageurl=sys_ReturnBqClassname($this_r,9);
		$pageecms=0;
		$pageclassid=$trueclassid;
	}
	if(empty($class_r[$trueclassid]['tbname']))
	{
		exit();
	}
	if(empty($tbname))
	{
		$tbname=$class_r[$trueclassid][tbname];
		$mid=$class_r[$trueclassid][modid];
	}
}
//专题
$trueztid=0;
$ztid=$_GET['ztid'];
if($ztid)
{
	$ztid=RepPostVar($ztid);
	if(strstr($ztid,','))//多专题
	{
		$son_r=sys_ReturnMoreZt($ztid);
		$trueztid=$son_r[0];
		$add.=' and ('.$son_r[1].')';
	}
	else
	{
		$trueztid=intval($ztid);
		$add.=" and ztid like '%|".$trueztid."|%'";
		if($pageecms==1)
		{
			$pagetitle=$class_zr[$trueztid]['ztname'];
			$this_r['ztid']=$trueztid;
			$pageurl=sys_ReturnBqZtname($this_r);
			$pageclassid=$trueztid;
		}
	}
	if(empty($class_zr[$trueztid][tbname]))
	{
		printerror('ErrorUrl','',1);
	}
	if(empty($tbname))
	{
		$tbname=$class_zr[$trueztid][tbname];
	}
}
//默认表
if(empty($tbname))
{
	$tbname=$public_r['tbname'];
}
if(empty($tbname))
{
	printerror('ErrorUrl','',1);
}
//排序
$order=(int)$_GET['order'];
if($order==1)//按ID
{
	$myorder="id";
}
elseif($order==2)//评论数
{
	$myorder="plnum";
}
elseif($order==3)//点击数
{
	$myorder="onclick";
}
elseif($order==4)//下载数
{
	$myorder="totaldown";
}
else//发布时间
{
	$myorder="newstime";
}
//显示顺序
$orderby=(int)$_GET['orderby'];
if($orderby)
{
	$myorderby="ASC";
}
else
{
	$myorderby="DESC";
}
$query="select * from {$dbtbpre}ecms_".$tbname." where checked=1".$add." order by ".$myorder." ".$myorderby." limit ".$public_r['rssnum'];
$sublen=$public_r['rsssub'];
$sql=$empire->query($query);
//显示文件
$type=$_GET['type'];
if($type=="xml")//xml
{
	$webfilename="xml.php";
}
elseif($type=="atom")//atom
{
	$webfilename="atom.php";
}
elseif($type=="rss1")//rss1
{
	$webfilename="rss10.php";
}
else//rss2
{
	$webfilename="rss20.php";
}
require($webfilename);
db_close();
$empire=null;
?>