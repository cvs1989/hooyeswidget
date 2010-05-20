<?php
require("../class/connect.php");
require("../class/db_sql.php");
require("../class/functions.php");
require LoadLang("pub/fun.php");
require("../class/t_functions.php");
require("../data/dbcache/class.php");
require("../data/dbcache/MemberLevel.php");
$link=db_connect();
$empire=new mysqlquery();
//验证用户
$lur=is_login();
$logininid=$lur['userid'];
$loginin=$lur['username'];
$loginrnd=$lur['rnd'];
$loginlevel=$lur['groupid'];
$loginadminstyleid=$lur['adminstyleid'];

//定时刷新任务
function DoTimeRepage($time){
	global $empire,$dbtbpre;
	if(empty($time))
	{$time=120;}
	echo"<meta http-equiv=\"refresh\" content=\"".$time.";url=DoTimeRepage.php\">";
	$todaytime=time();
	$b=0;
	$sql=$empire->query("select doing,classid,doid from {$dbtbpre}enewsdo where isopen=1 and lasttime+dotime*60<$todaytime");
	while($r=$empire->fetch($sql))
	{
		$b=1;
		if($r[doing]==1)//生成栏目
		{
			$cr=explode(',',$r[classid]);
			$count=count($cr)-1;
			for($i=1;$i<$count;$i++)
			{
				if(empty($cr[$i]))
				{
					continue;
				}
				ReListHtml($cr[$i],1);
			}
	    }
		elseif($r[doing]==2)//生成专题
		{
			$cr=explode(',',$r[classid]);
			$count=count($cr)-1;
			for($i=1;$i<$count;$i++)
			{
				if(empty($cr[$i]))
				{
					continue;
				}
				ListHtml($cr[$i],$ret_r[0],1);
			}
	    }
		elseif($r[doing]==3)//生成自定义列表
		{
			$cr=explode(',',$r[classid]);
			$count=count($cr)-1;
			for($i=1;$i<$count;$i++)
			{
				if(empty($cr[$i]))
				{
					continue;
				}
				$ur=$empire->fetch1("select listid,pagetitle,filepath,filetype,totalsql,listsql,maxnum,lencord,listtempid from {$dbtbpre}enewsuserlist where listid='".$cr[$i]."'");
				ReUserlist($ur,"");
			}
	    }
		elseif($r[doing]==4)//生成自定义页面
		{
			$cr=explode(',',$r[classid]);
			$count=count($cr)-1;
			for($i=1;$i<$count;$i++)
			{
				if(empty($cr[$i]))
				{
					continue;
				}
				$ur=$empire->fetch1("select id,path,pagetext,title,pagetitle,pagekeywords,pagedescription from {$dbtbpre}enewspage where id='".$cr[$i]."'");
				ReUserpage($ur[id],$ur[pagetext],$ur[path],$ur[title],$ur[pagetitle],$ur[pagekeywords],$ur[pagedescription]);
			}
	    }
		elseif($r[doing]==5)//生成自定义JS
		{
			$cr=explode(',',$r[classid]);
			$count=count($cr)-1;
			for($i=1;$i<$count;$i++)
			{
				if(empty($cr[$i]))
				{
					continue;
				}
				$ur=$empire->fetch1("select jsid,jsname,jssql,jstempid,jsfilename,substr from {$dbtbpre}enewsuserjs where jsid='".$cr[$i]."'");
				ReUserjs($ur,'');
			}
	    }
		else//生成首页
		{
			$indextemp=GetIndextemp();
			NewsBq($classid,$indextemp,1,0);
	    }
		$empire->query("update {$dbtbpre}enewsdo set lasttime=$todaytime where doid='$r[doid]'");
    }
	if($b)
	{
		echo "最后执行时间：".date("Y-m-d H:i:s",$todaytime)."<br><br>";
	}
}

DoTimeRepage(120);
db_close();
$empire=null;
?>
<b>说明：本页面为定时刷新任务执行窗口.</b>