<?php
if(!defined('InEmpireCMS'))
{
        exit();
}
require_once(ECMS_PATH."e/class/userfun.php");

//列表模板分页函数
function sys_ShowListPage($num,$pagenum,$dolink,$dotype,$page,$lencord,$ok,$search=""){
	global $fun_r;
	//首页
	if($pagenum<>1)
	{
		$pagetop="<a href='".$dolink."index".$dotype."'>".$fun_r['startpage']."</a>&nbsp;&nbsp;";
	}
	else
	{
		$pagetop=$fun_r['startpage']."&nbsp;&nbsp;";
	}
	//上一页
	if($pagenum<>1)
	{
		$pagepr=$pagenum-1;
		if($pagepr==1)
		{
			$prido="index".$dotype;
		}
		else
		{
			$prido="index_".$pagepr.$dotype;
		}
		$pagepri="<a href='".$dolink.$prido."'>".$fun_r['pripage']."</a>&nbsp;&nbsp;";
	}
	else
	{
		$pagepri=$fun_r['pripage']."&nbsp;&nbsp;";
	}
	//下一页
	if($pagenum<>$page)
	{
		$pagenex=$pagenum+1;
		$pagenext="<a href='".$dolink."index_".$pagenex.$dotype."'>".$fun_r['nextpage']."</a>&nbsp;&nbsp;";
	}
	else
	{
		$pagenext=$fun_r['nextpage']."&nbsp;&nbsp;";
	}
	//尾页
	if($pagenum==$page)
	{
		$pageeof=$fun_r['lastpage'];
	}
	else
	{
		$pageeof="<a href='".$dolink."index_".$page.$dotype."'>".$fun_r['lastpage']."</a>";
	}
	$options="";
	//取得下拉页码
	if(empty($search))
	{
		for($go=1;$go<=$page;$go++)
		{
			if($go==1)
			{$file="index".$dotype;}
			else
			{$file="index_".$go.$dotype;}
			if($ok==$go)
			{$select=" selected";}
			else
			{$select="";}
			$myoptions.="<option value='".$dolink.$file."'>".$fun_r['gotos'].$go.$fun_r['gotol']."</option>";
			$options.="<option value='".$dolink.$file."'".$select.">".$fun_r['gotos'].$go.$fun_r['gotol']."</option>";
		}
	}
	else
	{
		$myoptions=$search;
		$options=str_replace("value='".$dolink."index_".$ok.$dotype."'>","value='".$dolink."index_".$ok.$dotype."' selected>",$search);
	}
	$options="<select name=select onchange=\"self.location.href=this.options[this.selectedIndex].value\">".$options."</select>";
	//分页
	$pagelink=$pagetop.$pagepri.$pagenext.$pageeof;
	//替换模板变量
	$pager['showpage']=ReturnListpageStr($pagenum,$page,$lencord,$num,$pagelink,$options);
	$pager['option']=$myoptions;
	return $pager;
}

//列表模板之列表式分页
function sys_ShowListMorePage($num,$page,$dolink,$type,$totalpage,$line,$ok,$search=""){
	global $fun_r,$public_r;
	if($num<=$line)
	{
		$pager['showpage']='';
		return $pager;
	}
	$page_line=$public_r['listpagelistnum'];
	$snum=2;
	//$totalpage=ceil($num/$line);//取得总页数
	$firststr='<a title="Total record">&nbsp;<b>'.$num.'</b> </a>&nbsp;&nbsp;';
	//上一页
	if($page<>1)
	{
		$toppage='<a href="'.$dolink.'index'.$type.'">'.$fun_r['startpage'].'</a>&nbsp;';
		$pagepr=$page-1;
		if($pagepr==1)
		{
			$prido="index".$type;
		}
		else
		{
			$prido="index_".$pagepr.$type;
		}
		$prepage='<a href="'.$dolink.$prido.'">'.$fun_r['pripage'].'</a>';
	}
	//下一页
	if($page!=$totalpage)
	{
		$pagenex=$page+1;
		$nextpage='&nbsp;<a href="'.$dolink.'index_'.$pagenex.$type.'">'.$fun_r['nextpage'].'</a>';
		$lastpage='&nbsp;<a href="'.$dolink.'index_'.$totalpage.$type.'">'.$fun_r['lastpage'].'</a>';
	}
	$starti=$page-$snum<1?1:$page-$snum;
	$no=0;
	for($i=$starti;$i<=$totalpage&&$no<$page_line;$i++)
	{
		$no++;
		if($page==$i)
		{
			$is_1="<b>";
			$is_2="</b>";
		}
		elseif($i==1)
		{
			$is_1='<a href="'.$dolink.'index'.$type.'">';
			$is_2="</a>";
		}
		else
		{
			$is_1='<a href="'.$dolink.'index_'.$i.$type.'">';
			$is_2="</a>";
		}
		$returnstr.='&nbsp;'.$is_1.$i.$is_2;
	}
	$returnstr=$firststr.$toppage.$prepage.$returnstr.$nextpage.$lastpage;
	$pager['showpage']=$returnstr;
	return $pager;
}

//返回内容分页
function sys_ShowTextPage($totalpage,$page,$dolink,$add,$type,$search=""){
	global $fun_r,$public_r;
	if($totalpage==1)
	{
		return '';
	}
	$page_line=$public_r['textpagelistnum'];
	$snum=2;
	//$totalpage=ceil($num/$line);//取得总页数
	$firststr='<a title="Page">&nbsp;<b>'.$page.'</b>/<b>'.$totalpage.'</b> </a>&nbsp;&nbsp;';
	//上一页
	if($page<>1)
	{
		$toppage='<a href="'.$dolink.$add[filename].$type.'">'.$fun_r['startpage'].'</a>&nbsp;';
		$pagepr=$page-1;
		if($pagepr==1)
		{
			$prido=$add[filename].$type;
		}
		else
		{
			$prido=$add[filename].'_'.$pagepr.$type;
		}
		$prepage='<a href="'.$dolink.$prido.'">'.$fun_r['pripage'].'</a>';
	}
	//下一页
	if($page!=$totalpage)
	{
		$pagenex=$page+1;
		$nextpage='&nbsp;<a href="'.$dolink.$add[filename].'_'.$pagenex.$type.'">'.$fun_r['nextpage'].'</a>';
		$lastpage='&nbsp;<a href="'.$dolink.$add[filename].'_'.$totalpage.$type.'">'.$fun_r['lastpage'].'</a>';
	}
	$starti=$page-$snum<1?1:$page-$snum;
	$no=0;
	for($i=$starti;$i<=$totalpage&&$no<$page_line;$i++)
	{
		$no++;
		if($page==$i)
		{
			$is_1="<b>";
			$is_2="</b>";
		}
		elseif($i==1)
		{
			$is_1='<a href="'.$dolink.$add[filename].$type.'">';
			$is_2="</a>";
		}
		else
		{
			$is_1='<a href="'.$dolink.$add[filename].'_'.$i.$type.'">';
			$is_2="</a>";
		}
		$returnstr.='&nbsp;'.$is_1.$i.$is_2;
	}
	$returnstr=$firststr.$toppage.$prepage.$returnstr.$nextpage.$lastpage;
	return $returnstr;
}

//返回下拉式内容分页导航
function sys_ShowTextPageSelect($thispagenum,$dolink,$add,$filetype,$n_r){
	if($thispagenum==1)
	{
		return '';
	}
	$titleselect='';
	for($j=1;$j<=$thispagenum;$j++)
	{
	    if($j==1)
		{
			$title=$add[title];
			$plink=$add[filename].$filetype;
		}
		else
		{
			$k=$j-1;
			$ti_r=explode('[/!--empirenews.page--]',$n_r[$k]);
		    if(count($ti_r)>=2&&$ti_r[0])
			{
				$title=$ti_r[0];
			}
		    else
			{
				$title=$add[title].'('.$j.')';
			}
			$plink=$add[filename].'_'.$j.$filetype;
		}
		$titleselect.='<option value="'.$dolink.$plink.'?'.$j.'">'.$title.'</option>';
	}
	$titleselect='<select name="titleselect" onchange="self.location.href=this.options[this.selectedIndex].value">'.$titleselect.'</select>';
	return $titleselect;
}

//返回sql语句
function sys_ReturnBqQuery($classid,$line,$enews=0,$do=0){
	global $empire,$public_r,$class_r,$class_zr,$navclassid,$do_openbqquery,$dbtbpre,$fun_r,$class_tr,$emod_r;
	if($do_openbqquery==1&&$enews==24)//按sql查询
	{
		$query_first=substr($classid,0,7);
		if(!($query_first=='select '||$query_first=='SELECT '))
		{
			return "";
		}
		$classid=RepSqlTbpre($classid);
		$sql=$empire->query1($classid);
		if(!$sql)
		{
			echo"SQL Error: ".$classid;
		}
		return $sql;
	}
	if($enews==0||$enews==1||$enews==2||$enews==9||$enews==12||$enews==15)//栏目
	{
		if(strstr($classid,','))//多栏目
		{
			$son_r=sys_ReturnMoreClass($classid,1);
			$classid=$son_r[0];
			$where=$son_r[1];
		}
		else
		{
			if($classid=='selfinfo')//显示当前栏目信息
			{
				$classid=$navclassid;
			}
			if($class_r[$classid][islast])
			{
				$where="classid='$classid'";
			}
			else
			{
				$where=ReturnClass($class_r[$classid][sonclass]);
			}
		}
    }
	elseif($enews==6||$enews==7||$enews==8||$enews==11||$enews==14||$enews==17)//专题
	{
		if(strstr($classid,','))//多专题
		{
			$son_r=sys_ReturnMoreZt($classid);
			$classid=$son_r[0];
			$where=$son_r[1];
		}
		else
		{
			if($classid=='selfinfo')//显示当前专题信息
			{
				$classid=$navclassid;
			}
			$where="ztid like '%|".$classid."|%'";
		}
	}
	elseif($enews==25||$enews==26||$enews==27||$enews==28||$enews==29||$enews==30)//标题分类
	{
		if(strstr($classid,','))//多标题分类
		{
			$son_r=sys_ReturnMoreTT($classid);
			$classid=$son_r[0];
			$where=$son_r[1];
		}
		else
		{
			$where="ttid='$classid'";
		}
		$ttmid=$class_tr[$classid][mid];
		$tbname=$emod_r[$ttmid][tbname];
	}
	if($enews==0)//栏目最新
	{
		$query='('.$where.') and checked=1';
		$order='newstime';
		$tbname=$class_r[$classid][tbname];
    }
	elseif($enews==1)//栏目热门
	{
		$query='('.$where.') and checked=1';
		$order='onclick';
		$tbname=$class_r[$classid][tbname];
    }
	elseif($enews==2)//栏目推荐
	{
		$query='('.$where.') and isgood=1 and checked=1';
		$order='newstime';
		$tbname=$class_r[$classid][tbname];
    }
	elseif($enews==9)//栏目评论排行
	{
		$query='('.$where.') and checked=1';
		$order='plnum';
		$tbname=$class_r[$classid][tbname];
    }
	elseif($enews==12)//栏目头条
	{
		$query='('.$where.') and firsttitle=1 and checked=1';
		$order='newstime';
		$tbname=$class_r[$classid][tbname];
    }
	elseif($enews==15)//栏目下载排行
	{
		$query='('.$where.') and checked=1';
		$order='totaldown';
		$tbname=$class_r[$classid][tbname];
    }
	elseif($enews==3)//所有最新
	{
		$query='checked=1';
		$order='newstime';
		$tbname=$public_r[tbname];
    }
	elseif($enews==4)//所有点击排行
	{
		$query='checked=1';
		$order='onclick';
		$tbname=$public_r[tbname];
    }
	elseif($enews==5)//所有推荐
	{
		$query='isgood=1 and checked=1';
		$order='newstime';
		$tbname=$public_r[tbname];
    }
	elseif($enews==10)//所有评论排行
	{
		$query='checked=1';
		$order='plnum';
		$tbname=$public_r[tbname];
    }
	elseif($enews==13)//所有头条
	{
		$query='firsttitle=1 and checked=1';
		$order='newstime';
		$tbname=$public_r[tbname];
    }
	elseif($enews==16)//所有下载排行
	{
		$query='checked=1';
		$order='totaldown';
		$tbname=$public_r[tbname];
    }
	elseif($enews==6)//专题最新
	{
		$query='('.$where.') and checked=1';
		$order='newstime';
		$tbname=$class_zr[$classid][tbname];
    }
	elseif($enews==7)//专题点击排行
	{
		$query='('.$where.') and checked=1';
		$order='onclick';
		$tbname=$class_zr[$classid][tbname];
    }
	elseif($enews==8)//专题推荐
	{
		$query='('.$where.') and isgood=1 and checked=1';
		$order='newstime';
		$tbname=$class_zr[$classid][tbname];
    }
	elseif($enews==11)//专题评论排行
	{
		$query='('.$where.') and checked=1';
		$order='plnum';
		$tbname=$class_zr[$classid][tbname];
    }
	elseif($enews==14)//专题头条
	{
		$query='('.$where.') and firsttitle=1 and checked=1';
		$order='newstime';
		$tbname=$class_zr[$classid][tbname];
    }
	elseif($enews==17)//专题下载排行
	{
		$query='('.$where.') and checked=1';
		$order='totaldown';
		$tbname=$class_zr[$classid][tbname];
    }
	elseif($enews==18)//各表最新
	{
		$query='checked=1';
		$order='newstime';
		$tbname=$classid;
	}
	elseif($enews==19)//各表热门
	{
		$query='checked=1';
		$order='onclick';
		$tbname=$classid;
	}
	elseif($enews==20)//各表推荐
	{
		$query='isgood=1 and checked=1';
		$order='newstime';
		$tbname=$classid;
	}
	elseif($enews==21)//各表评论排行
	{
		$query='checked=1';
		$order='plnum';
		$tbname=$classid;
	}
	elseif($enews==22)//各表头条信息
	{
		$query='firsttitle=1 and checked=1';
		$order="newstime";
		$tbname=$classid;
	}
	elseif($enews==23)//各表下载排行
	{
		$query='checked=1';
		$order='totaldown';
		$tbname=$classid;
	}
	elseif($enews==25)//标题分类最新
	{
		$query='('.$where.') and checked=1';
		$order='newstime';
    }
	elseif($enews==26)//标题分类点击排行
	{
		$query='('.$where.') and checked=1';
		$order='onclick';
    }
	elseif($enews==27)//标题分类推荐
	{
		$query='('.$where.') and isgood=1 and checked=1';
		$order='newstime';
    }
	elseif($enews==28)//标题分类评论排行
	{
		$query='('.$where.') and checked=1';
		$order='plnum';
    }
	elseif($enews==29)//标题分类头条
	{
		$query='('.$where.') and firsttitle=1 and checked=1';
		$order='newstime';
    }
	elseif($enews==30)//标题分类下载排行
	{
		$query='('.$where.') and checked=1';
		$order='totaldown';
    }
	//不调用
	if(!strstr($public_r['nottobq'],','.$classid.','))
	{
		$query.=ReturnNottoBqWhere();
	}
	//图片信息
	if($do)
	{
		$query.=" and titlepic<>''";
    }
	//中止
	if(empty($tbname))
	{
		echo $fun_r['BqErrorCid']."=<b>".$classid."</b>".$fun_r['BqErrorNtb']."(".$fun_r['BqErrorDo']."=".$enews.")";
		return false;
	}
	//当前时间
	//$todaytime=date("Y-m-d H:i:s");
	$query="select * from {$dbtbpre}ecms_".$tbname." where ".$query." order by istop desc,".$order." desc,id desc limit $line";
	$sql=$empire->query1($query);
	if(!$sql)
	{
		echo"SQL Error: ".$query;
	}
	return $sql;
}

//返回标签模板
function sys_ReturnBqTemp($tempid){
	global $empire,$dbtbpre,$fun_r;
	$r=$empire->fetch1("select tempid,modid,temptext,showdate,listvar,subnews,rownum,docode from ".GetTemptb("enewsbqtemp")." where tempid='$tempid'");
	if(empty($r[tempid]))
	{
		echo $fun_r['BqErrorNbqtemp']."(ID=".$tempid.")";
	}
	return $r;
}

//替换栏目名
function ReplaceEcmsinfoClassname($temp,$enews,$classid){
	global $class_r,$class_zr;
	if(strstr($classid,","))
	{
		return $temp;
    }
	$thecdo=",0,1,2,9,12,15,25,26,";
	$thezdo=",6,7,8,11,14,17,29,30,";
	//栏目
	if(strstr($thecdo,",".$enews.","))
	{
		$classname=$class_r[$classid][classname];
		$r[classid]=$classid;
		$classurl=sys_ReturnBqClassname($r,9);
    }
	//专题
	elseif(strstr($thezdo,",".$enews.","))
	{
		$r[ztid]=$classid;
		$classname=$class_zr[$classid][ztname];
		$classurl=sys_ReturnBqZtname($r);
    }
	else
	{}
	if($classname)
	{
		$temp=str_replace("[!--the.classname--]",$classname,$temp);
		$temp=str_replace("[!--the.classurl--]",$classurl,$temp);
		$temp=str_replace("[!--the.classid--]",$classid,$temp);
	}
	return $temp;
}

//带模板的标签
function sys_GetEcmsInfo($classid,$line,$strlen,$have_class=0,$enews=0,$tempid,$doing=0){
	global $empire,$public_r;
	$sql=sys_ReturnBqQuery($classid,$line,$enews,$doing);
	if(!$sql)
	{return "";}
	//取得模板
	$tr=sys_ReturnBqTemp($tempid);
	if(empty($tr['tempid']))
	{return "";}
	$listtemp=str_replace('[!--news.url--]',$public_r[newsurl],$tr[temptext]);
	$subnews=$tr[subnews];
	$listvar=str_replace('[!--news.url--]',$public_r[newsurl],$tr[listvar]);
	$rownum=$tr[rownum];
	$formatdate=$tr[showdate];
	$docode=$tr[docode];
	//替换变量
	$listtemp=ReplaceEcmsinfoClassname($listtemp,$enews,$classid);
	if(empty($rownum))
	{$rownum=1;}
	//字段
	$ret_r=ReturnReplaceListF($tr[modid]);
	//列表
	$list_exp="[!--empirenews.listtemp--]";
	$list_r=explode($list_exp,$listtemp);
	$listtext=$list_r[1];
	$no=1;
	$changerow=1;
	while($r=$empire->fetch($sql))
	{
		$r[oldtitle]=$r[title];
		//替换列表变量
		$repvar=ReplaceListVars($no,$listvar,$subnews,$strlen,$formatdate,$url,$have_class,$r,$ret_r,$docode);
		$listtext=str_replace("<!--list.var".$changerow."-->",$repvar,$listtext);
		$changerow+=1;
		//超过行数
		if($changerow>$rownum)
		{
			$changerow=1;
			$string.=$listtext;
			$listtext=$list_r[1];
		}
		$no++;
    }
	//多余数据
    if($changerow<=$rownum&&$listtext<>$list_r[1])
	{
		$string.=$listtext;
    }
    $string=$list_r[0].$string.$list_r[2];
	echo $string;
}

//灵动标签：返回SQL内容函数
function sys_ReturnEcmsLoopBq($classid=0,$line=10,$enews=3,$doing=0){
	return sys_ReturnBqQuery($classid,$line,$enews,$doing);
}

//灵动标签：返回特殊内容函数
function sys_ReturnEcmsLoopStext($r){
	global $class_r;
	$sr['titleurl']=sys_ReturnBqTitleLink($r);
	$sr['classname']=$class_r[$r[classid]][bname]?$class_r[$r[classid]][bname]:$class_r[$r[classid]][classname];
	$sr['classurl']=sys_ReturnBqClassname($r,9);
	return $sr;
}

//返回相关链接操作类型
function sys_OtherLinkQuery($classid,$line,$enews,$doing){
	global $empire,$class_r,$class_zr,$navinfor,$dbtbpre;
	if($enews==1)//按表
	{
		$tbname=$classid;
	}
	elseif($enews==2)//按栏目
	{
		$tbname=$class_r[$classid]['tbname'];
		if($classid=='selfinfo')//当前栏目
		{
			$classid=$navinfor['classid'];
		}
		if($class_r[$classid][islast])
		{
			$and="classid='$classid'";
		}
		else
		{
			$and=ReturnClass($class_r[$classid][sonclass]);
		}
	}
	elseif($enews==3)//按专题
	{
		$tbname=$class_zr[$classid]['tbname'];
		$and="ztid like '%|".$classid."|%'";
	}
	else//默认
	{
		$tbname=$class_r[$navinfor[classid]]['tbname'];
	}
	//关键字
	$keys='';
	if(!empty($enews))
	{
		$keyr=explode(',',$navinfor['keyboard']);
		$count=count($keyr);
		for($i=0;$i<$count;$i++)
		{
			if($i==0)
			{
				$or='';
			}
			else
			{
				$or=' or ';
			}
			$keys.=$or."keyboard like '%".$keyr[$i]."%'";
		}
		$keys='('.$keys.')';
	}
	else
	{
		$keys='id in ('.$navinfor['keyid'].')';
	}
	//当前信息
	if($tbname==$class_r[$navinfor[classid]][tbname])
	{
		$and.=empty($and)?"id<>'$navinfor[id]'":" and id<>'$navinfor[id]'";
	}
	//图片信息
	if($doing)
	{
		$and.=empty($and)?"titlepic<>''":" and titlepic<>''";
    }
	if($and)
	{
		$and.=' and ';
	}
	if(empty($line))
	{
		$line=$class_r[$navinfor[classid]]['link_num'];
	}
	$query="select * from {$dbtbpre}ecms_".$tbname." where ".$and.$keys." order by newstime desc limit $line";
	$sql=$empire->query1($query);
	if(!$sql)
	{
		echo"SQL Error: ".$query;
	}
	return $sql;
}

//相关链接标签
function sys_GetOtherLinkInfo($tempid,$classid='',$line=0,$strlen=60,$have_class=0,$enews=0,$doing=0){
	global $empire,$navinfor,$public_r;
	if(empty($navinfor['keyboard'])||(empty($enews)&&!$navinfor['keyid']))
	{
		return '';
	}
	$sql=sys_OtherLinkQuery($classid,$line,$enews,$doing);
	if(!$sql)
	{return "";}
	//取得模板
	$tr=sys_ReturnBqTemp($tempid);
	if(empty($tr['tempid']))
	{return "";}
	$listtemp=str_replace('[!--news.url--]',$public_r[newsurl],$tr[temptext]);
	$subnews=$tr[subnews];
	$listvar=str_replace('[!--news.url--]',$public_r[newsurl],$tr[listvar]);
	$rownum=$tr[rownum];
	$formatdate=$tr[showdate];
	$docode=$tr[docode];
	//替换变量
	$listtemp=ReplaceEcmsinfoClassname($listtemp,$enews,$classid);
	if(empty($rownum))
	{$rownum=1;}
	//字段
	$ret_r=ReturnReplaceListF($tr[modid]);
	//列表
	$list_exp="[!--empirenews.listtemp--]";
	$list_r=explode($list_exp,$listtemp);
	$listtext=$list_r[1];
	$no=1;
	$changerow=1;
	while($r=$empire->fetch($sql))
	{
		$r[oldtitle]=$r[title];
		//替换列表变量
		$repvar=ReplaceListVars($no,$listvar,$subnews,$strlen,$formatdate,$url,$have_class,$r,$ret_r,$docode);
		$listtext=str_replace("<!--list.var".$changerow."-->",$repvar,$listtext);
		$changerow+=1;
		//超过行数
		if($changerow>$rownum)
		{
			$changerow=1;
			$string.=$listtext;
			$listtext=$list_r[1];
		}
		$no++;
    }
	//多余数据
    if($changerow<=$rownum&&$listtext<>$list_r[1])
	{
		$string.=$listtext;
    }
    $string=$list_r[0].$string.$list_r[2];
	echo $string;
}

//文字标签函数
function sys_GetClassNews($classid,$line,$strlen,$showdate=true,$enews=0,$have_class=0,$formatdate='(m-d)'){
	global $empire;
	$sql=sys_ReturnBqQuery($classid,$line,$enews,0);
	if(!$sql)
	{return "";}
	$record=0;
	while($r=$empire->fetch($sql))
	{
		$record=1;
		$oldtitle=$r[title];
		$title=sub($r[title],0,$strlen,false);
		//标题属性
		$title=DoTitleFont($r[titlefont],$title);
		//显示栏目
		$myadd=sys_ReturnBqClassname($r,$have_class);
		//显示时间
        if($showdate)
		{
			$newstime=date($formatdate,$r[newstime]);
            $newstime="&nbsp;".$newstime;
        }
		//标题链接
		$titleurl=sys_ReturnBqTitleLink($r);
        $title="·".$myadd."<a href='".$titleurl."' target=_blank title='".$oldtitle."'>".$title."</a>".$newstime;
        $allnews.="<tr><td height=20>".$title."</td></tr>";
    }
	if($record)
	{
		echo"<table border=0 cellpadding=0 cellspacing=0>$allnews</table>";
	}
}

//图文信息调用
function sys_GetClassNewsPic($classid,$line,$num,$width,$height,$showtitle=true,$strlen,$enews=0){
	global $empire;
	$sql=sys_ReturnBqQuery($classid,$num,$enews,1);
	if(!$sql)
	{return "";}
	//输出
	$i=0;
	while($r=$empire->fetch($sql))
	{
		$i++;
		if(($i-1)%$line==0||$i==1)
		{$class_text.="<tr>";}
		//标题链接
		$titleurl=sys_ReturnBqTitleLink($r);
		//------是否显示标题
		if($showtitle)
		{
			$oldtitle=$r[title];
			$title=sub($r[title],0,$strlen,false);
			//标题属性
			$title=DoTitleFont($r[titlefont],$title);
			$title="<br><span style='line-height:15pt'>".$title."</span>";
		}
        $class_text.="<td align=center><a href='".$titleurl."' target=_blank><img src='".$r[titlepic]."' width='".$width."' height='".$height."' border=0 alt='".$oldtitle."'>".$title."</a></td>";
        //分割
        if($i%$line==0)
		{$class_text.="</tr>";}
    }
    if($i<>0)
	{
		$table="<table width=100% border=0 cellpadding=3 cellspacing=0>";$table1="</table>";
        $ys=$line-$i%$line;
		$p=0;
        for($j=0;$j<$ys&&$ys!=$line;$j++)
		{
			$p=1;
			$class_text.="<td></td>";
        }
		if($p==1)
		{
			$class_text.="</tr>";
		}
	}
    $text=$table.$class_text.$table1;
    echo"$text";
}

//简介型调用
function sys_GetClassNewsText($classid,$line,$tablecolor,$enews=0,$have_class=0){
	global $empire;
	$sql=sys_ReturnBqQuery($classid,$line,$enews,0);
	if(!$sql)
	{return "";}
	while($r=$empire->fetch($sql))
	{
		//标题属性
		$title=DoTitleFont($r[titlefont],$r[title]);
		//标题链接
		$titleurl=sys_ReturnBqTitleLink($r);
		//显示类别
		$myadd=sys_ReturnBqClassname($r,$have_class);
		$smalltext=nl2br($r[smalltext]);
		$allnews.="<table width=99% border=0 align=center cellpadding=3 cellspacing=0>
  <tr bgcolor=".$tablecolor."><td width=62% height=25><strong>.</strong>&nbsp;".$myadd."<a href='".$titleurl."' target=_blank>".$title."</a></td><td width=38%>发布时间：".date("Y-m-d H:i:s",$r[newstime])."</td></tr><tr valign=top><td height=25 colspan=2>".$smalltext."</td></tr></table>";
	}
	echo"$allnews";
}

//滚动图片信息
function sys_GetAutoPic($classid,$line,$width,$height,$showtitle=true,$strlen,$speed=5000,$enews=0){
	global $empire;
	$sql=sys_ReturnBqQuery($classid,$line,$enews,1);
	if(!$sql)
	{return "";}
	$jsarray="";
	$i=0;
	while($r=$empire->fetch($sql))
	{
		$i++;
		//标题链接
		$titleurl=sys_ReturnBqTitleLink($r);
		//------是否显示标题
		if($showtitle)
		{
			$title=sub($r[title],0,$strlen,false);
			//标题属性
			$title=addslashes(DoTitleFont($r[titlefont],htmlspecialchars($title)));
		}
		$jsarray.="imgUrl[".$i."]=\"".$r[titlepic]."\";
		imgLink[".$i."]=\"".$titleurl."\";
		imgTz[".$i."]=\"<a href='".$titleurl."' target=_blank>".$title."</a>\";";
    }
	$pic="<a onclick=\"javascript:goUrl();\" style=\"CURSOR: hand\"><img style=\"FILTER: revealTrans(duration=2,transition=20);border-color:black;color:#000000\" src=\"javascript:\" width='".$width."' height='".$height."' border=1 name=imgInit id=imgInit></a>";
?>
<script language=JavaScript>
var imgUrl=new Array();
var imgLink=new Array();
var imgTz=new Array();
var adNum=0;
<?=$jsarray?>
var imgPre=new Array();
var j=0;
for (i=1;i<=<?=$line?>;i++) {
	if( (imgUrl[i]!="") && (imgLink[i]!="") ) {
		j++;
	} else {
		break;
	}
}
function playTran(){
	if (document.all)
		imgInit.filters.revealTrans.play();
}
var key=0;
function nextAd(){
	if(adNum<j)adNum++ ;
	else adNum=1;
	
	if( key==0 ){
		key=1;
	} else if (document.all){
		imgInit.filters.revealTrans.Transition=6;
		imgInit.filters.revealTrans.apply();
                   playTran();
	}
	document.images.imgInit.src=imgUrl[adNum];
	<?
	if($showtitle)
	{
	?>
document.getElementById('jdtz').innerHTML=imgTz[adNum];
	<?
	}	
	?>
	theTimer=setTimeout("nextAd()", <?=$speed?>);
}
function goUrl(){
	jumpUrl=imgLink[adNum];
	jumpTarget='_blank';
	if (jumpUrl != ''){
		if (jumpTarget != '') 
			window.open(jumpUrl,jumpTarget);
		else
			location.href=jumpUrl;
	}
}
</script>
<?
	//显示标题
	if($showtitle)
	{
	?>
    <table border=0 cellpadding=0 cellspacing=0>
    <tr><td align=center><?=$pic?></td></tr>
	<tr><td height=1></td></tr>
	<tr><td align=center style="background:#B1B1B1 right no-repeat" height=23 id=jdtz></td></tr>
	</table>
	<?
	}
    else
	{
		echo $pic;
	}
	echo"<script>nextAd();</script>";
}

//图片信息调用
function sys_GetPicNews($picid,$showtitle=false,$showtext=false){
	global $empire,$dbtbpre;
	$r=$empire->fetch1("select picid,title,pic_url,url,pic_width,pic_height,open_pic,border,pictext from {$dbtbpre}enewspic where picid='$picid' limit 1");
	$pic="";
	if($r[pic_width])
	{$pic.=" width='".$r[pic_width]."'";}
	if($r[pic_height])
	{$pic.=" height='".$r[pic_height]."'";}
	$pic="<a href='".$r[url]."' title='".$r[title]."' target='".$r[open_pic]."'><img src='".$r[pic_url]."'".$pic." border=".$r[border]."></a>";
	//显示标题
	if($showtitle)
	{$pic.="<br><span style='line-height:15pt'><a href='".$r[url]."' target='".$r[open_pic]."'>".$r[title]."</a></span>";}
	//显示简介
	if($showtext)
	{$pic="<table width=100% border=0 align=center cellpadding=3 cellspacing=1><tr><td width=32%><div align=center>".$pic."</div></td><td width=68%><div><a href='".$r[url]."' title='".$r[title]."' target='".$r[open_pic]."'>".nl2br($r[pictext])."</a></div></td></tr></table>";}
	echo"$pic";
}

//多图片信息
function sys_GetMorePicNews($classid,$line,$num,$width,$height,$showtitle=true,$strlen,$enews=0){
	global $empire,$public_r,$dbtbpre;
	$sql=$empire->query("select * from {$dbtbpre}enewspic where classid='$classid' order by picid desc limit $num");
	//输出
	$i=0;
	while($r=$empire->fetch($sql))
	{
		$i++;
		if(($i-1)%$line==0||$i==1)
		{$class_text.="<tr>";}
		//是否显示原链接
	    if($enews==0)
		{$titleurl=$r[url];}
		else
		{$titleurl=$public_r[newsurl]."e/NewsSys/ShowImg?picid=".$r[picid];}
		//------是否显示标题
		if($showtitle)
		{
			$title=sub($r[title],0,$strlen,false);
			$title="<br><span style='line-height:15pt'>".$title."</span>";
		}
        $class_text.="<td align=center><a href='".$titleurl."' target=_blank><img src='".$r[pic_url]."' width='".$width."' height='".$height."' border=0>".$title."</a></td>";
        //分割
        if($i%$line==0)
		{$class_text.="</tr>";}
    }
    if($i<>0)
	{
		$table="<table width=100% border=0 cellpadding=3 cellspacing=0>";$table1="</table>";
        $ys=$line-$i%$line;
		$p=0;
        for($j=0;$j<$ys&&$ys!=$line;$j++)
		{
			$p=1;
			$class_text.="<td></td>";
        }
		if($p==1)
		{
			$class_text.="</tr>";
		}
	}
    $text=$table.$class_text.$table1;
    echo"$text";
}

//广告标签
function sys_GetAd($adid){
	global $empire,$public_r,$dbtbpre;
	$r=$empire->fetch1("select * from {$dbtbpre}enewsad where adid='$adid'");
	//到期
	if(time()>to_time($r['endtime']))
	{
		echo addslashes($r[reptext]);
		return '';
	}
	if($r['ylink'])
	{
		$ad_url=$r['url'];
	}
	else
	{
		$ad_url=$public_r[newsurl]."e/public/ClickAd?adid=".$adid;//广告链接
	}
	//----------------------文字广告
	if($r[t]==1)
	{
		$r[titlefont]=$r[titlecolor].','.$r[titlefont];
		$picurl=DoTitleFont($r[titlefont],$r[picurl]);//文字属性
		$h="<a href='".$ad_url."' target=".$r[target]." title='".$r[alt]."'>".addslashes($picurl)."</a>";
		//普通显示
		if($r[adtype]==1)
		{
			$html=$h;
	    }
		//可移动透明对话框
		else
		{
			$html="<script language=javascript src=".$public_r[newsurl]."d/js/acmsd/ecms_dialog.js></script> 
<div style='position:absolute;left:300px;top:150px;width:".$r[pic_width]."; height:".$r[pic_height].";z-index:1;solid;filter:alpha(opacity=90)' id=DGbanner5 onmousedown='down1(this)' onmousemove='move()' onmouseup='down=false'><table cellpadding=0 border=0 cellspacing=1 width=".$r[pic_width]." height=".$r[pic_height]." bgcolor=#000000><tr><td height=18 bgcolor=#5A8ACE align=right style='cursor:move;'><a href=# style='font-size: 9pt; color: #eeeeee; text-decoration: none' onClick=clase('DGbanner5') >关闭>>><img border='0' src='".$public_r[newsurl]."d/js/acmsd/close_o.gif'></a>&nbsp;</td></tr><tr><td bgcolor=f4f4f4 >&nbsp;".$h."</td></tr></table></div>";
	    }
    }
	//------------------html广告
	elseif($r[t]==2)
	{
		$h=addslashes($r[htmlcode]);
		//普通显示
		if($r[adtype]==1)
		{
			$html=$h;
		}
		//可移动透明对话框
		else
		{
			$html="<script language=javascript src=".$public_r[newsurl]."d/js/acmsd/ecms_dialog.js></script>
<div style='position:absolute;left:300px;top:150px;width:".$r[pic_width]."; height:".$r[pic_height].";z-index:1;solid;filter:alpha(opacity=90)' id=DGbanner5 onmousedown='down1(this)' onmousemove='move()' onmouseup='down=false'><table cellpadding=0 border=0 cellspacing=1 width=".$r[pic_width]." height=".$r[pic_height]." bgcolor=#000000><tr><td height=18 bgcolor=#5A8ACE align=right style='cursor:move;'><a href=# style='font-size: 9pt; color: #eeeeee; text-decoration: none' onClick=clase('DGbanner5') >关闭>>><img border='0' src='".$public_r[newsurl]."d/js/acmsd/close_o.gif'></a>&nbsp;</td></tr><tr><td bgcolor=f4f4f4 >&nbsp;".$h."</td></tr></table></div>";
		}
    }
	//------------------弹出广告
	elseif($r[t]==3)
	{
		//打开新窗口
		if($r[adtype]==8)
		{
			$html="<script>window.open('".$r[url]."');</script>";
		}
		//弹出窗口
	    elseif($r[adtype]==9)
		{
			$html="<script>window.open('".$r[url]."','','width=".$r[pic_width].",height=".$r[pic_height].",scrollbars=yes');</script>";
		}
		//普能网页窗口
		else
		{
			$html="<script>window.showModalDialog('".$r[url]."','','dialogWidth:".$r[pic_width]."px;dialogHeight:".$r[pic_height]."px;scroll:no;status:no;help:no');</script>";
		}
    }
	//---------------------图片与flash广告
	else
	{
		$filetype=GetFiletype($r[picurl]);
		//flash
		if($filetype==".swf")
		{
			$h="<object classid='clsid:D27CDB6E-AE6D-11cf-96B8-444553540000' codebase='http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,29,0' name='movie' width='".$r[pic_width]."' height='".$r[pic_height]."' id='movie'><param name='movie' value='".$r[picurl]."'><param name='quality' value='high'><param name='menu' value='false'><embed src='".$r[picurl]."' width='".$r[pic_width]."' height='".$r[pic_height]."' quality='high' pluginspage='http://www.macromedia.com/go/getflashplayer' type='application/x-shockwave-flash' id='movie' name='movie' menu='false'></embed><PARAM NAME='wmode' VALUE='Opaque'></object>";
		}
		else
		{
			$h="<a href='".$ad_url."' target=".$r[target]."><img src='".$r[picurl]."' border=0 width='".$r[pic_width]."' height='".$r[pic_height]."' alt='".$r[alt]."'></a>";
		}
		//普通显示
		if($r[adtype]==1)
		{
			$html=$h;
		}
		//满屏浮动显示
		elseif($r[adtype]==4)
		{
			$html="<script>ns4=(document.layers)?true:false;
ie4=(document.all)?true:false;
if(ns4){document.write(\"<layer id=DGbanner2 width=".$r[pic_width]." height=".$r[pic_height]." onmouseover=stopme('DGbanner2') onmouseout=movechip('DGbanner2')>".$h."</layer>\");}
else{document.write(\"<div id=DGbanner2 style='position:absolute; width:".$r[pic_width]."px; height:".$r[pic_height]."px; z-index:9; filter: Alpha(Opacity=90)' onmouseover=stopme('DGbanner2') onmouseout=movechip('DGbanner2')>".$h."</div>\");}</script>
<script language=javascript src=".$public_r[newsurl]."d/js/acmsd/ecms_float_fullscreen.js></script>";
		}
		//上下浮动显示 - 右
		elseif($r[adtype]==5)
		{
			$html="<script>if (navigator.appName == 'Netscape')
{document.write(\"<layer id=DGbanner3 top=150 width=".$r[pic_width]." height=".$r[pic_height].">".$h."</layer>\");}
else{document.write(\"<div id=DGbanner3 style='position: absolute;width:".$r[pic_height].";top:150;visibility: visible;z-index: 1'>".$h."</div>\");}</script>
<script language=javascript src=".$public_r[newsurl]."d/js/acmsd/ecms_float_upanddown.js></script>";
		}
		//上下浮动显示 - 左
		elseif($r[adtype]==6)
		{
			$html="<script>if(navigator.appName == 'Netscape')
{document.write(\"<layer id=DGbanner10 top=150 width=".$r[pic_width]." height=".$r[pic_height].">".$h."</layer>\");}
else{document.write(\"<div id=DGbanner10 style='position: absolute;width:".$r[pic_width].";top:150;visibility: visible;z-index: 1'>".$h."</div>\");}</script>
<script language=javascript src=".$public_r[newsurl]."d/js/acmsd/ecms_float_upanddown_L.js></script>";
		}
		//全屏幕渐隐消失
		elseif($r[adtype]==7)
		{
			$html="<script>ns4=(document.layers)?true:false;
if(ns4){document.write(\"<layer id=DGbanner4Cont onLoad='moveToAbsolute(layer1.pageX-160,layer1.pageY);clip.height=".$r[pic_height].";clip.width=".$r[pic_width]."; visibility=show;'><layer id=DGbanner4News position:absolute; top:0; left:0>".$h."</layer></layer>\");}
else{document.write(\"<div id=DGbanner4 style='position:absolute;top:0; left:0;'><div id=DGbanner4Cont style='position:absolute;width:".$r[pic_width].";height:".$r[pic_height].";clip:rect(0,".$r[pic_width].",".$r[pic_height].",0)'><div id=DGbanner4News style='position:absolute;top:0;left:0;right:820'>".$h."</div></div></div>\");}</script> 
<script language=javascript src=".$public_r[newsurl]."d/js/acmsd/ecms_fullscreen.js></script>";
		}
		//可移动透明对话框
		elseif($r[adtype]==3)
		{
			$html="<script language=javascript src=".$public_r[newsurl]."d/js/acmsd/ecms_dialog.js></script> 
<div style='position:absolute;left:300px;top:150px;width:".$r[pic_width]."; height:".$r[pic_height].";z-index:1;solid;filter:alpha(opacity=90)' id=DGbanner5 onmousedown='down1(this)' onmousemove='move()' onmouseup='down=false'><table cellpadding=0 border=0 cellspacing=1 width=".$r[pic_width]." height=".$r[pic_height]." bgcolor=#000000><tr><td height=18 bgcolor=#5A8ACE align=right style='cursor:move;'><a href=# style='font-size: 9pt; color: #eeeeee; text-decoration: none' onClick=clase('DGbanner5') >关闭>>><img border='0' src='".$public_r[newsurl]."d/js/acmsd/close_o.gif'></a>&nbsp;</td></tr><tr><td bgcolor=f4f4f4 >&nbsp;".$h."</td></tr></table></div>";
		}
		else
		{
			$html="<script>function closeAd(){huashuolayer2.style.visibility='hidden';huashuolayer3.style.visibility='hidden';}function winload(){huashuolayer2.style.top=109;huashuolayer2.style.left=5;huashuolayer3.style.top=109;huashuolayer3.style.right=5;}//if(document.body.offsetWidth>800){
				{document.write(\"<div id=huashuolayer2 style='position: absolute;visibility:visible;z-index:1'><table width=0  border=0 cellspacing=0 cellpadding=0><tr><td height=10 align=right bgcolor=666666><a href=javascript:closeAd()><img src=".$public_r[newsurl]."d/js/acmsd/close.gif width=12 height=10 border=0></a></td></tr><tr><td>".$h."</td></tr></table></div>\"+\"<div id=huashuolayer3 style='position: absolute;visibility:visible;z-index:1'><table width=0  border=0 cellspacing=0 cellpadding=0><tr><td height=10 align=right bgcolor=666666><a href=javascript:closeAd()><img src=".$public_r[newsurl]."d/js/acmsd/close.gif width=12 height=10 border=0></a></td></tr><tr><td>".$h."</td></tr></table></div>\");}winload()//}</script>";
		}
	}
	echo $html;
}

//投票标签
function sys_GetVote($voteid){
	global $empire,$public_r,$dbtbpre;
	$r=$empire->fetch1("select * from {$dbtbpre}enewsvote where voteid='$voteid'");
	if(empty($r[votetext]))
	{
		return '';
	}
	//模板
	$votetemp=ReturnVoteTemp($r[tempid],0);
	$votetemp=RepVoteTempAllvar($votetemp,$r);
	$listexp="[!--empirenews.listtemp--]";
	$listtemp_r=explode($listexp,$votetemp);
	$r_exp="\r\n";
	$f_exp="::::::";
	//项目数
	$r_r=explode($r_exp,$r[votetext]);
	$checked=0;
	for($i=0;$i<count($r_r);$i++)
	{
		$checked++;
		$f_r=explode($f_exp,$r_r[$i]);
		//投票类型
		if($r[voteclass])
		{$vote="<input type=checkbox name=vote[] value=".$checked.">";}
		else
		{$vote="<input type=radio name=vote value=".$checked.">";}
		$votetext.=RepVoteTempListvar($listtemp_r[1],$vote,$f_r[0]);
    }
	$votetext=$listtemp_r[0].$votetext.$listtemp_r[2];
	echo"$votetext";
}

//信息投票标签
function sys_GetInfoVote($classid,$id){
	global $empire,$public_r,$dbtbpre;
	$r=$empire->fetch1("select * from {$dbtbpre}enewsinfovote where id='$id' and classid='$classid' limit 1");
	if(empty($r[votetext]))
	{
		return '';
	}
	//模板
	$votetemp=ReturnVoteTemp($r[tempid],0);
	$votetemp=RepVoteTempAllvar($votetemp,$r);
	$listexp="[!--empirenews.listtemp--]";
	$listtemp_r=explode($listexp,$votetemp);
	$r_exp="\r\n";
	$f_exp="::::::";
	//项目数
	$r_r=explode($r_exp,$r[votetext]);
	$checked=0;
	for($i=0;$i<count($r_r);$i++)
	{
		$checked++;
		$f_r=explode($f_exp,$r_r[$i]);
		//投票类型
		if($r[voteclass])
		{$vote="<input type=checkbox name=vote[] value=".$checked.">";}
		else
		{$vote="<input type=radio name=vote value=".$checked.">";}
		$votetext.=RepVoteTempListvar($listtemp_r[1],$vote,$f_r[0]);
    }
	$votetext=$listtemp_r[0].$votetext.$listtemp_r[2];
	return $votetext;
}

//友情链接
function sys_GetSitelink($line,$num,$enews=0,$classid=0,$stats=0){
	global $empire,$public_r,$dbtbpre;
	//图片
	if($enews==1)
	{$a=" and lpic<>''";}
	//文字
	elseif($enews==2)
	{$a=" and lpic=''";}
	else
	{$a="";}
	//调用相应的栏目分类
	if(!empty($classid))
	{
		$whereclass=" and classid='$classid'";
	}
	$sql=$empire->query("select * from {$dbtbpre}enewslink where checked=1".$a.$whereclass." order by myorder,lid limit ".$num);
	//输出
	$i=0;
	while($r=$empire->fetch($sql))
	{
		//链接
		if(empty($stats))
		{
			$linkurl=$public_r[newsurl]."e/public/GotoSite?lid=".$r[lid]."&url=".urlencode($r[lurl]);
		}
		else
		{
			$linkurl=$r[lurl];
		}
		$i++;
		if(($i-1)%$line==0||$i==1)
		{$class_text.="<tr>";}
		//文字
		if(empty($r[lpic]))
		{
			$logo="<a href='".$linkurl."' title='".$r[lname]."' target=".$r[target].">".$r[lname]."</a>";
		}
		//图片
		else
		{
			$logo="<a href='".$linkurl."' target=".$r[target]."><img src='".$r[lpic]."' alt='".$r[lname]."' border=0 width='".$r[width]."' height='".$r[height]."'></a>";
		}
		$class_text.="<td align=center>".$logo."</td>";
		//分割
		if($i%$line==0)
		{$class_text.="</tr>";}
	}
	if($i<>0)
	{
		$table="<table width=100% border=0 cellpadding=3 cellspacing=0>";$table1="</table>";
        $ys=$line-$i%$line;
		$p=0;
        for($j=0;$j<$ys&&$ys!=$line;$j++)
		{
			$p=1;
			$class_text.="<td></td>";
        }
		if($p==1)
		{
			$class_text.="</tr>";
		}
	}
	$text=$table.$class_text.$table1;
    echo"$text";
}

//显示栏目导航
function sys_ShowClass($show=0){
	global $navclassid,$empire,$class_r,$public_r,$dbtbpre;
	if(empty($navclassid))
	{$classid=0;}
	else
	{
		$classid=$navclassid;
		//终极栏目则显示同级栏目
		if($class_r[$classid][islast]&&$class_r[$classid][bclassid])
	    {
			$classid=$class_r[$classid][bclassid];
		}
		if($class_r[$classid][islast]&&empty($class_r[$classid][bclassid]))
		{$classid=0;}
	}
	$sql=$empire->query("select classid,classname,islast,sonclass,tbname from {$dbtbpre}enewsclass where bclassid='$classid' and showclass=0 order by myorder,classid");
	$s="";
	while($r=$empire->fetch($sql))
	{
		//栏目链接
		$classurl=sys_ReturnBqClassname($r,9);
		//显示类别数据数
		if($show)
		{
			//终极栏目
	        if($r[islast])
		    {
				$where="classid='$r[classid]'";
	        }
	        else
		    {
				$where=ReturnClass($r[sonclass]);
	        }
			$cr=$empire->fetch1("select count(*) as total from {$dbtbpre}ecms_".$r[tbname]." where ".$where." and checked=1");
			$num=$cr[total];
			$newsdata="&nbsp;(<font color=red>".$num."</font>)";
		}
		$s.="<tr><td>&nbsp;<img src='".$public_r[newsurl]."e/data/images/class.gif' border=0>&nbsp;<a href=".$classurl.">".$r[classname]."</a>".$newsdata."</td></tr>";
	}
	$s="<table>".$s."</table>";
	echo $s;
}

//显示网站地图
function sys_ShowMap($classid,$line,$bcolor,$color,$shownum=0){
	global $empire,$class_r,$dbtbpre;
	$sql=$empire->query("select classid,classname,sonclass,islast,tbname from {$dbtbpre}enewsclass where bclassid='$classid' and showclass=0 order by myorder,classid");
	while($r=$empire->fetch($sql))
	{
		//显示栏目数据数
		if($shownum)
		{
			//终极栏目
	        if($r[islast])
		    {
				$where="classid='$r[classid]'";
	        }
	        else
		    {
				$where=ReturnClass($r[sonclass]);
	        }
			$cr=$empire->fetch1("select count(*) as total from {$dbtbpre}ecms_".$r[tbname]." where ".$where." and checked=1 limit 1");
			$num=$cr[total];
			$newsdata="&nbsp;<font color=cccccc>(".$num.")</font>";
		}
		//栏目链接
		$classurl=sys_ReturnBqClassname($r,9);
		$text.="<table width=100% border=0 cellpadding=5 cellspacing=1 bgcolor=".$bcolor."><tr><td>&nbsp;<a href=\"".$classurl."\" target=\"_blank\">".$r[classname]."</a>".$newsdata."</td></tr><tr><td bgcolor=".$color.">";
		$sql1=$empire->query("select classname,classid,islast,sonclass,tbname from {$dbtbpre}enewsclass where bclassid='$r[classid]' and showclass=0 order by myorder,classid");
		$i=0;
		$class_text="";
		while($r1=$empire->fetch($sql1))
		{
			//显示类别数据数
			if($shownum)
			{
				//终极类别
				if($r1[islast])
				{
					$where="classid='$r1[classid]'";
				}
				else
				{
					$where=ReturnClass($r1[sonclass]);
				}
				$cr=$empire->fetch1("select count(*) as total from {$dbtbpre}ecms_".$r1[tbname]." where ".$where." and checked=1 limit 1");
				$num=$cr[total];
				$newsdata="&nbsp;<font color=cccccc>(".$num.")</font>";
			}
			//栏目链接
			$classurl=sys_ReturnBqClassname($r1,9);
			$i++;
			if(($i-1)%$line==0||$i==1)
			{$class_text.="<tr>";}
			$class_text.="<td align=center><a href=\"".$classurl."\" target=\"_blank\">".$r1[classname]."</a>".$newsdata."</td>";
			//分割
			if($i%$line==0)
			{$class_text.="</tr>";}
		}
		if($i<>0)
		{
			$table="<table width=100% border=0 cellpadding=5 cellspacing=1>";
			$table1="</table>";
			$ys=$line-$i%$line;
			$p=0;
			for($j=0;$j<$ys&&$ys!=$line;$j++)
			{
				$p=1;
				$class_text.="<td></td>";
			}
			if($p==1)
			{
				$class_text.="</tr>";
			}
		}
		$text.=$table.$class_text.$table1."</td></tr></table>";
	}
	echo $text;
}

//引用文件
function sys_IncludeFile($file){
	@include($file);
}

//读取远程文件
function sys_ReadFile($http){
	global $do_openreadfile;
	if($do_openreadfile==0&&!strstr($http,"://"))
	{
		return "";
	}
	echo ReadFiletext($http);
}

//信息统计
function sys_TotalData($classid,$enews=0,$day=0){
	global $empire,$class_r,$class_zr,$dbtbpre,$fun_r;
	if(empty($classid))
	{
		return "";
    }
	if($day)
	{
		if($day==1)//今日信息
		{
			$date=date("Y-m-d");
			$starttime=$date." 00:00:01";
			$endtime=$date." 23:59:59";
		}
		elseif($day==2)//本月信息
		{
			$date=date("Y-m");
			$starttime=$date."-01 00:00:01";
			$endtime=$date."-".date("t")." 23:59:59";
		}
		elseif($day==3)//本年信息
		{
			$date=date("Y");
			$starttime=$date."-01-01 00:00:01";
			$endtime=($date+1)."-01-01 00:00:01";
		}
		$and=" and newstime>=".to_time($starttime)." and newstime<=".to_time($endtime);
	}
	//统计专题
	if($enews==1)
	{
		if(empty($class_zr[$classid][tbname]))
		{
			echo $fun_r['BqErrorZid']."=<b>".$classid."</b>".$fun_r['BqErrorNtb'];
			return "";
		}
		$query="select count(*) as total from {$dbtbpre}ecms_".$class_zr[$classid][tbname]." where ztid like '%|".$classid."|%' and checked=1".$and;
    }
	//统计数据表
	elseif($enews==2)
	{
		$query="select count(*) as total from {$dbtbpre}ecms_".$classid." where checked=1".$and;
    }
	//统计栏目数据
	else
	{
		if(empty($class_r[$classid][tbname]))
		{
			echo $fun_r['BqErrorCid']."=<b>".$classid."</b>".$fun_r['BqErrorNtb'];
			return "";
		}
		if($class_r[$classid][islast])//终极栏目
		{
			$where="classid='$classid'";
		}
		else//大栏目
		{
			$where=ReturnClass($class_r[$classid][sonclass]);
		}
		$query="select count(*) as total from {$dbtbpre}ecms_".$class_r[$classid][tbname]." where ".$where." and checked=1".$and;
    }
	$num=$empire->gettotal($query);
	echo $num;
}

//flash幻灯图片信息调用
function sys_FlashPixpic($classid,$line,$width,$height,$showtitle=true,$strlen,$enews=0,$sec=5){
	global $empire,$public_r,$class_r,$class_zr;
	$sql=sys_ReturnBqQuery($classid,$line,$enews,1);
	if(!$sql)
	{return "";}
	$i=0;
	while($r=$empire->fetch($sql))
	{
		//标题链接
		$titleurl=sys_ReturnBqTitleLink($r);
		//------是否显示标题
		if($showtitle)
		{
			$title=sub($r[title],0,$strlen,false);
			//标题属性
			$title=addslashes(DoTitleFont($r[titlefont],htmlspecialchars($title)));
		}
		$fh="|";
		if($i==0)
		{
			$fh="";
		}
		$url.=$fh.$titleurl;
		$pic.=$fh.$r[titlepic];
		$subject.=$fh.$title;
		$i=1;
	}
	//显示标题
	if($showtitle)
	{
		$text_height=22;
	}
	else
	{
		$text_height=0;
	}
?>
<script type="text/javascript">
<!--
 var interval_time=<?=$sec?>;
 var focus_width=<?=$width?>;
 var focus_height=<?=$height?>;
 var text_height=<?=$text_height?>;
 var text_align="center";
 var swf_height = focus_height+text_height;
 var swfpath="<?=$public_r[newsurl]?>e/data/images/pixviewer.swf";
 var swfpatha="<?=$public_r[newsurl]?>e/data/images/pixviewer.swf";
 
 var pics="<?=urlencode($pic)?>";
 var links="<?=urlencode($url)?>";
 var texts="<?=htmlspecialchars($subject)?>";
 
 document.write('<object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" codebase="http://fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=8,0,0,0" width="'+ focus_width +'" height="'+ swf_height +'">');
 document.write('<param name="movie" value="'+swfpath+'"><param name="quality" value="high"><param name="bgcolor" value="#ffffff">');
 document.write('<param name="menu" value="false"><param name=wmode value="opaque">');
 document.write('<param name="FlashVars" value="pics='+pics+'&links='+links+'&texts='+texts+'&borderwidth='+focus_width+'&borderheight='+focus_height+'&textheight='+text_height+'&text_align='+text_align+'&interval_time='+interval_time+'">');
 document.write('<embed src="'+swfpath+'" wmode="opaque" FlashVars="pics='+pics+'&links='+links+'&texts='+texts+'&borderwidth='+focus_width+'&borderheight='+focus_height+'&textheight='+text_height+'&text_align='+text_align+'&interval_time='+interval_time+'" menu="false" bgcolor="#ffffff" quality="high" width="'+ focus_width +'" height="'+ swf_height +'" allowScriptAccess="sameDomain" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer" />');
 document.write('</object>');
//-->
</script>
<?
}

//搜索关键字
function sys_ShowSearchKey($line,$num,$classid=0,$enews=0){
	global $empire,$public_r,$dbtbpre;
	if($enews)
	{
		$order="searchid";
	}
	else
	{
		$order="onclick";
	}
	if($classid)
	{
		$add=" and classid='$classid'";
	}
	$sql=$empire->query("select searchid,keyboard from {$dbtbpre}enewssearch where iskey=0".$add." order by ".$order." desc limit ".$num);
	$i=0;
	$returnkey="";
	while($r=$empire->fetch($sql))
	{
		$i++;
		$keyurl=$public_r[newsurl]."e/search/result?searchid=$r[searchid]";
		$br="";
		if($i%$line==0)
		{
			$br="<br>";
		}
		$jg="&nbsp;";
		if($br)
		{
			$jg="";
		}
		$returnkey.="<a href='".$keyurl."' target=_blank>".$r[keyboard]."</a>".$jg.$br;
	}
	echo $returnkey;
}

//带模板的标签显示-循环
function sys_GetEcmsInfoMore($classid,$line,$strlen,$have_class=0,$ecms=0,$tr,$doing=0,$field,$cr,$dofirstinfo=0,$fsubtitle=0,$fsubnews=0,$fdoing=0){
	global $empire,$public_r;
	//操作类型
	if($ecms==0)//栏目最新
	{
		$enews=0;
	}
	elseif($ecms==1)//栏目热门
	{
		$enews=1;
	}
	elseif($ecms==2)//栏目推荐
	{
		$enews=2;
	}
	elseif($ecms==3)//栏目评论排行
	{
		$enews=9;
	}
	elseif($ecms==4)//栏目头条
	{
		$enews=12;
	}
	elseif($ecms==5)//栏目下载排行
	{
		$enews=15;
	}
	elseif($ecms==6)//栏目评分
	{
		$enews=25;
	}
	elseif($ecms==7)//栏目投票
	{
		$enews=26;
	}
	else
	{
		$enews=0;
	}
	$sql=sys_ReturnBqQuery($classid,$line,$enews,$doing);
	if(!$sql)
	{return "";}
	//取得模板
	$listtemp=$tr[temptext];
	$subnews=$tr[subnews];
	$listvar=$tr[listvar];
	$rownum=$tr[rownum];
	$formatdate=$tr[showdate];
	$docode=$tr[docode];
	//替换变量
	$listtemp=ReplaceEcmsinfoClassname($listtemp,$enews,$classid);
	$listtemp=sys_ForSonclassDataFirstInfo($listtemp,$cr,$dofirstinfo,$fsubtitle,$fsubnews,$fdoing);
	if(empty($rownum))
	{$rownum=1;}
	//列表
	$list_exp="[!--empirenews.listtemp--]";
	$list_r=explode($list_exp,$listtemp);
	$listtext=$list_r[1];
	$no=1;
	$changerow=1;
	while($r=$empire->fetch($sql))
	{
		$r[oldtitle]=$r[title];
		//替换列表变量
		$repvar=ReplaceListVars($no,$listvar,$subnews,$strlen,$formatdate,$url,$have_class,$r,$field,$docode);
		$listtext=str_replace("<!--list.var".$changerow."-->",$repvar,$listtext);
		$changerow+=1;
		//超过行数
		if($changerow>$rownum)
		{
			$changerow=1;
			$string.=$listtext;
			$listtext=$list_r[1];
		}
		$no++;
    }
	//多余数据
    if($changerow<=$rownum&&$listtext<>$list_r[1])
	{
		$string.=$listtext;
    }
    $string=$list_r[0].$string.$list_r[2];
	echo $string;
}

//循环子栏目显示头条信息
function sys_ForSonclassDataFirstInfo($temptext,$cr,$ecms=0,$subtitle=0,$subnews=0,$fdoing=0){
	global $empire,$class_r,$public_r,$dbtbpre;
	if($ecms==2||$ecms==3||$ecms==4)
	{
		$where=$class_r[$cr[classid]][islast]?"classid='$cr[classid]'":ReturnClass($class_r[$cr[classid]][sonclass]);
	}
	if($fdoing)
	{
		$add=" and titlepic<>''";
	}
	if($ecms==1)//栏目缩图
	{
		$id=$cr['classid'];
		$title=$cr['classname'];
		$titleurl=sys_ReturnBqClassname($cr,9);
		$titlepic=$cr['classimg'];
		$smalltext=$cr['intro'];
	}
	elseif($ecms==2)//推荐信息
	{
		$r=$empire->fetch1("select * from {$dbtbpre}ecms_".$class_r[$cr[classid]][tbname]." where checked=1 and isgood=1 and (".$where.")".$add." order by newstime desc limit 1");
	}
	elseif($ecms==3)//头条信息
	{
		$r=$empire->fetch1("select * from {$dbtbpre}ecms_".$class_r[$cr[classid]][tbname]." where checked=1 and firsttitle=1 and (".$where.")".$add." order by newstime desc limit 1");
	}
	elseif($ecms==4)//最新信息
	{
		$r=$empire->fetch1("select * from {$dbtbpre}ecms_".$class_r[$cr[classid]][tbname]." where checked=1 and (".$where.")".$add." order by newstime desc limit 1");
	}
	else
	{
		return $temptext;
	}
	if($ecms!=1)
	{
		$id=$r['id'];
		$title=$r['title'];
		$titleurl=sys_ReturnBqTitleLink($r);
		$titlepic=$r['titlepic'];
		//简介
		if($r['smalltext'])
		{$smalltext=$r['smalltext'];}
		elseif($r['flashsay'])
		{$smalltext=$r['flashsay'];}
		elseif($r['softsay'])
		{$smalltext=$r['softsay'];}
		elseif($r['moviesay'])
		{$smalltext=$r['moviesay'];}
		elseif($r['picsay'])
		{$smalltext=$r['picsay'];}
	}
	$oldtitle=$title;
	if($subtitle)
	{$title=sub($title,0,$subtitle,false);}
	if(empty($titlepic))
	{$titlepic=$public_r[newsurl]."e/data/images/notimg.gif";}
	if(!empty($subnews))
	{$smalltext=sub($smalltext,0,$subnews,false);}
	$temptext=str_replace('[!--sonclass.id--]',$id,$temptext);
	$temptext=str_replace('[!--sonclass.title--]',$title,$temptext);
	$temptext=str_replace('[!--sonclass.oldtitle--]',$oldtitle,$temptext);
	$temptext=str_replace('[!--sonclass.titlepic--]',$titlepic,$temptext);
	$temptext=str_replace('[!--sonclass.titleurl--]',$titleurl,$temptext);
	$temptext=str_replace('[!--sonclass.text--]',$smalltext,$temptext);
	return $temptext;
}

//循环子栏目数据
function sys_ForSonclassData($classid,$line,$strlen,$have_class=0,$enews=0,$tempid,$doing=0,$cline=0,$dofirstinfo=0,$fsubtitle=0,$fsubnews=0,$fdoing=0){
	global $empire,$public_r,$class_r,$class_zr,$navclassid,$dbtbpre;
	//多栏目
	if(strstr($classid,","))
	{
		$son_r=sys_ReturnMoreClass($classid);
		$classid=$son_r[0];
		$where=$son_r[1];
	}
	else
	{
		//当前栏目
		if($classid=="selfinfo")
		{
			$classid=$navclassid;
		}
		$where="bclassid='$classid'";
	}
	//取得模板
	$tr=sys_ReturnBqTemp($tempid);
	if(empty($tr['tempid']))
	{return "";}
	$tr[temptext]=str_replace('[!--news.url--]',$public_r[newsurl],$tr[temptext]);
	$tr[listvar]=str_replace('[!--news.url--]',$public_r[newsurl],$tr[listvar]);
	//限制条数
	if($cline)
	{
		$limit=" limit ".$cline;
	}
	//字段
	$ret_r=ReturnReplaceListF($tr[modid]);
	//栏目字段
	if($dofirstinfo==1)
	{
		$addclassfield=',classname,classimg,intro';
	}
	$csql=$empire->query("select classid".$addclassfield." from {$dbtbpre}enewsclass where ".$where." and wburl='' order by myorder,classid".$limit);
	while($cr=$empire->fetch($csql))
	{
		sys_GetEcmsInfoMore($cr[classid],$line,$strlen,$have_class,$enews,$tr,$doing,$ret_r,$cr,$dofirstinfo,$fsubtitle,$fsubnews,$fdoing);
	}
}

//带模板的栏目导航标签
function sys_ShowClassByTemp($classid,$tempid,$show=0,$cline=0){
	global $navclassid,$empire,$class_r,$public_r,$dbtbpre;
	//当前栏目
	if($classid=="selfinfo")
	{
		if(empty($navclassid))
		{$classid=0;}
		else
		{
			$classid=$navclassid;
			//终极类别则显示同级类别
			if($class_r[$classid][islast]&&$class_r[$classid][bclassid])
			{
				$classid=$class_r[$classid][bclassid];
			}
			if($class_r[$classid][islast]&&empty($class_r[$classid][bclassid]))
			{$classid=0;}
		}
	}
	//取得模板
	$tr=sys_ReturnBqTemp($tempid);
	if(empty($tr['tempid']))
	{return "";}
	$listtemp=str_replace('[!--news.url--]',$public_r[newsurl],$tr[temptext]);
	$subnews=$tr[subnews];
	$listvar=str_replace('[!--news.url--]',$public_r[newsurl],$tr[listvar]);
	$rownum=$tr[rownum];
	$formatdate=$tr[showdate];
	if(empty($rownum))
	{$rownum=1;}
	//限制条数
	if($cline)
	{
		$limit=" limit ".$cline;
	}
	//替换变量
	$bclassname=$class_r[$classid][classname];
	$br[classid]=$classid;
	$bclassurl=sys_ReturnBqClassname($br,9);
	$listtemp=str_replace("[!--bclassname--]",$bclassname,$listtemp);
	$listtemp=str_replace("[!--bclassurl--]",$bclassurl,$listtemp);
	$listtemp=str_replace("[!--bclassid--]",$classid,$listtemp);
	//列表
	$list_exp="[!--empirenews.listtemp--]";
	$list_r=explode($list_exp,$listtemp);
	$listtext=$list_r[1];
	$no=1;
	$changerow=1;
	$sql=$empire->query("select classid,classname,islast,sonclass,tbname,intro,classimg from {$dbtbpre}enewsclass where bclassid='$classid' and showclass=0 order by myorder,classid".$limit);
	while($r=$empire->fetch($sql))
	{
		//显示类别数据数
		if($show)
		{
			//终极类别
	        if($r[islast])
		    {
				$where="classid='$r[classid]'";
	        }
	        else
		    {
				$where=ReturnClass($r[sonclass]);
	        }
			$cr=$empire->fetch1("select count(*) as total from {$dbtbpre}ecms_".$r[tbname]." where ".$where." and checked=1");
			$num=$cr[total];
		}
		//替换列表变量
		$repvar=ReplaceShowClassVars($no,$listvar,$r,$num,0,$subnews);
		$listtext=str_replace("<!--list.var".$changerow."-->",$repvar,$listtext);
		$changerow+=1;
		//超过行数
		if($changerow>$rownum)
		{
			$changerow=1;
			$string.=$listtext;
			$listtext=$list_r[1];
		}
		$no++;
	}
	//多余数据
    if($changerow<=$rownum&&$listtext<>$list_r[1])
	{
		$string.=$listtext;
    }
    $string=$list_r[0].$string.$list_r[2];
	echo $string;
}

//循环子栏目导航标签
function sys_ForShowSonClass($classid,$tempid,$show=0,$cline=0){
	global $navclassid,$empire,$class_r,$public_r,$dbtbpre;
	//多栏目
	if(strstr($classid,","))
	{
		$where='classid in ('.$classid.')';
	}
	else
	{
		if($classid=="selfinfo")//当前栏目
		{
			$classid=intval($navclassid);
		}
		$where="bclassid='$classid'";
	}
	//取得模板
	$tr=sys_ReturnBqTemp($tempid);
	if(empty($tr['tempid']))
	{return "";}
	$tr[temptext]=str_replace('[!--news.url--]',$public_r[newsurl],$tr[temptext]);
	$tr[listvar]=str_replace('[!--news.url--]',$public_r[newsurl],$tr[listvar]);
	//限制条数
	if($cline)
	{
		$limit=" limit ".$cline;
	}
	$no=1;
	$sql=$empire->query("select classid,classname,islast,sonclass,tbname,intro,classimg from {$dbtbpre}enewsclass where ".$where." and showclass=0 order by myorder,classid".$limit);
	while($r=$empire->fetch($sql))
	{
		//显示栏目数据数
		if($show)
		{
	        if($r[islast])//终极栏目
		    {
				$swhere="classid='$r[classid]'";
	        }
	        else
		    {
				$swhere=ReturnClass($r[sonclass]);
	        }
			$num=$empire->gettotal("select count(*) as total from {$dbtbpre}ecms_".$r[tbname]." where ".$swhere." and checked=1");
		}
		sys_GetShowClassMore($r[classid],$r,$tr,$no,$num,$show);
		$no++;
	}
}

//栏目导航标签－循环
function sys_GetShowClassMore($bclassid,$bcr,$tr,$bno,$bnum,$show=0){
	global $empire,$class_r,$public_r,$dbtbpre;
	//取得模板
	$listtemp=$tr[temptext];
	$subnews=$tr[subnews];
	$listvar=$tr[listvar];
	$rownum=$tr[rownum];
	$formatdate=$tr[showdate];
	if(empty($rownum))
	{$rownum=1;}
	//替换变量
	$listtemp=str_replace("[!--bclassname--]",$bcr[classname],$listtemp);
	$bclassurl=sys_ReturnBqClassname($bcr,9);//栏目链接
	$listtemp=str_replace("[!--bclassurl--]",$bclassurl,$listtemp);
	$listtemp=str_replace("[!--bclassid--]",$bclassid,$listtemp);
	$bclassimg=$bcr[classimg]?$bcr[classimg]:$public_r[newsurl]."e/data/images/notimg.gif";//栏目图片
	$listtemp=str_replace("[!--bclassimg--]",$bclassimg,$listtemp);
	$listtemp=str_replace("[!--bintro--]",nl2br($bcr[intro]),$listtemp);//栏目简介
	$listtemp=str_replace("[!--bno--]",$bno,$listtemp);
	$listtemp=str_replace("[!--bnum--]",$bnum,$listtemp);
	//列表
	$list_exp="[!--empirenews.listtemp--]";
	$list_r=explode($list_exp,$listtemp);
	$listtext=$list_r[1];
	$no=1;
	$changerow=1;
	$sql=$empire->query("select classid,classname,islast,sonclass,tbname,intro,classimg from {$dbtbpre}enewsclass where bclassid='$bclassid' and showclass=0 order by myorder,classid");
	while($r=$empire->fetch($sql))
	{
		//显示栏目数据数
		if($show)
		{
	        if($r[islast])//终极栏目
		    {
				$where="classid='$r[classid]'";
	        }
	        else
		    {
				$where=ReturnClass($r[sonclass]);
	        }
			$num=$empire->gettotal("select count(*) as total from {$dbtbpre}ecms_".$r[tbname]." where ".$where." and checked=1");
		}
		//替换列表变量
		$repvar=ReplaceShowClassVars($no,$listvar,$r,$num,0,$subnews);
		$listtext=str_replace("<!--list.var".$changerow."-->",$repvar,$listtext);
		$changerow+=1;
		//超过行数
		if($changerow>$rownum)
		{
			$changerow=1;
			$string.=$listtext;
			$listtext=$list_r[1];
		}
		$no++;
	}
	//多余数据
    if($changerow<=$rownum&&$listtext<>$list_r[1])
	{
		$string.=$listtext;
    }
    $string=$list_r[0].$string.$list_r[2];
	echo $string;
}

//替换栏目导航标签
function ReplaceShowClassVars($no,$listtemp,$r,$num,$ecms=0,$subnews=0){
	global $public_r,$class_r;
	//栏目链接
	if($ecms==1)
	{
		$classurl=sys_ReturnBqZtname($r);
		$r['classname']=$r['ztname'];
		$r['classid']=$r['ztid'];
		$r['classimg']=$r['ztimg'];
	}
	else
	{
		$classurl=sys_ReturnBqClassname($r,9);
	}
	if($subnews)
	{
		$r[intro]=sub($r[intro],0,$subnews,false);
	}
	$listtemp=str_replace("[!--classurl--]",$classurl,$listtemp);
	//栏目名称
	$listtemp=str_replace("[!--classname--]",$r[classname],$listtemp);
	//栏目id
	$listtemp=str_replace("[!--classid--]",$r[classid],$listtemp);
	//栏目图片
	if(empty($r[classimg]))
	{
		$r[classimg]=$public_r[newsurl]."e/data/images/notimg.gif";
	}
	$listtemp=str_replace("[!--classimg--]",$r[classimg],$listtemp);
	//栏目简介
	$listtemp=str_replace("[!--intro--]",nl2br($r[intro]),$listtemp);
	//记录数
	$listtemp=str_replace("[!--num--]",$num,$listtemp);
	//序号
	$listtemp=str_replace("[!--no--]",$no,$listtemp);
	return $listtemp;
}

//留言调用
function sys_ShowLyInfo($line,$tempid,$bid=0){
	global $empire,$dbtbpre,$public_r;
	$a="";
	if($bid)
	{
		$a=" and bid='$bid'";
	}
	//取得模板
	$tr=sys_ReturnBqTemp($tempid);
	if(empty($tr['tempid']))
	{return "";}
	$listtemp=str_replace('[!--news.url--]',$public_r[newsurl],$tr[temptext]);
	$subnews=$tr[subnews];
	$listvar=str_replace('[!--news.url--]',$public_r[newsurl],$tr[listvar]);
	$rownum=$tr[rownum];
	$formatdate=$tr[showdate];
	if(empty($rownum))
	{$rownum=1;}
	//列表
	$list_exp="[!--empirenews.listtemp--]";
	$list_r=explode($list_exp,$listtemp);
	$listtext=$list_r[1];
	$no=1;
	$changerow=1;
	$sql=$empire->query("select lyid,name,email,lytime,lytext,retext from {$dbtbpre}enewsgbook where checked=0".$a." order by lyid desc limit ".$line);
	while($r=$empire->fetch($sql))
	{
		//替换列表变量
		$repvar=ReplaceShowLyVars($no,$listvar,$r,$formatdate,$subnews);
		$listtext=str_replace("<!--list.var".$changerow."-->",$repvar,$listtext);
		$changerow+=1;
		//超过行数
		if($changerow>$rownum)
		{
			$changerow=1;
			$string.=$listtext;
			$listtext=$list_r[1];
		}
		$no++;
	}
	//多余数据
    if($changerow<=$rownum&&$listtext<>$list_r[1])
	{
		$string.=$listtext;
    }
    $string=$list_r[0].$string.$list_r[2];
	echo $string;
}

//替换留言标签
function ReplaceShowLyVars($no,$listtemp,$r,$formatdate,$subnews=0){
	global $public_r;
	if($subnews)
	{
		$r['lytext']=sub($r['lytext'],0,$subnews,false);
	}
	$listtemp=str_replace("[!--lyid--]",$r['lyid'],$listtemp);//id
	$listtemp=str_replace("[!--lytext--]",nl2br($r['lytext']),$listtemp);//留言内容
	$listtemp=str_replace("[!--retext--]",nl2br($r['retext']),$listtemp);//回复
	$listtemp=str_replace("[!--lytime--]",format_datetime($r['lytime'],$formatdate),$listtemp);
	$listtemp=str_replace("[!--name--]",$r['name'],$listtemp);
	$listtemp=str_replace("[!--email--]",$r['email'],$listtemp);
	//序号
	$listtemp=str_replace("[!--no--]",$no,$listtemp);
	return $listtemp;
}

//专题调用
function sys_ShowZtData($tempid,$zcid=0,$cline=0){
	global $empire,$dbtbpre,$public_r;
	$a="";
	if($zcid)
	{
		$a=" and zcid='$zcid'";
	}
	//取得模板
	$tr=sys_ReturnBqTemp($tempid);
	if(empty($tr['tempid']))
	{return "";}
	$listtemp=str_replace('[!--news.url--]',$public_r[newsurl],$tr[temptext]);
	$subnews=$tr[subnews];
	$listvar=str_replace('[!--news.url--]',$public_r[newsurl],$tr[listvar]);
	$rownum=$tr[rownum];
	$formatdate=$tr[showdate];
	if(empty($rownum))
	{$rownum=1;}
	//限制条数
	if($cline)
	{
		$limit=" limit ".$cline;
	}
	//列表
	$list_exp="[!--empirenews.listtemp--]";
	$list_r=explode($list_exp,$listtemp);
	$listtext=$list_r[1];
	$no=1;
	$changerow=1;
	$sql=$empire->query("select ztid,ztname,intro,ztimg from {$dbtbpre}enewszt where showzt=0".$a." order by myorder,ztid desc".$limit);
	while($r=$empire->fetch($sql))
	{
		//替换列表变量
		$repvar=ReplaceShowClassVars($no,$listvar,$r,$num,1,$subnews);
		$listtext=str_replace("<!--list.var".$changerow."-->",$repvar,$listtext);
		$changerow+=1;
		//超过行数
		if($changerow>$rownum)
		{
			$changerow=1;
			$string.=$listtext;
			$listtext=$list_r[1];
		}
		$no++;
	}
	//多余数据
    if($changerow<=$rownum&&$listtext<>$list_r[1])
	{
		$string.=$listtext;
    }
    $string=$list_r[0].$string.$list_r[2];
	echo $string;
}

//图库模型分页标签
function sys_PhotoMorepage($tempid,$spicwidth=0,$spicheight=0){
	global $navinfor;
	$morepic=$navinfor['morepic'];
	if(empty($morepic))
	{
		return "";
	}
	//取得标签
	$tempr=sys_ReturnBqTemp($tempid);
	if(empty($tempr['tempid']))
	{return "";}
	$rexp="\r\n";
	$fexp="::::::";
	$gs="";
	if($spicwidth)
	{$gs=" width='".$spicwidth."'";}
	if($spicheight)
	{$gs.=" height='".$spicheight."'";}
	$rstr="";
	$sdh="";
	$firstpic="";
	$optionstr="";
	$titleoption="";
	$listpage="";
	$nbsp="";
	$rr=explode($rexp,$morepic);
	$count=count($rr);
	for($i=0;$i<$count;$i++)
	{
		$j=$i+1;
		$fr=explode($fexp,$rr[$i]);
		$smallpic=$fr[0];	//小图
		$bigpic=$fr[1];	//大图
		if(empty($bigpic))
		{
			$bigpic=$smallpic;
		}
		$picname=htmlspecialchars($fr[2]);	//名称
		$showpic=ReplaceMorePagelistvar($tempr['listvar'],$picname,$bigpic);
		$sdh.=$nbsp."<a href='#ecms' onclick='GotoPhPage(".$j.");' title='".$picname."'><img src='".$smallpic."' alt='".$picname."' border=0".$gs."></a>";
		if($i==0)
		{
			$firstpic=$showpic;
		}
		$rstr.="photosr[".$j."]=\"".addslashes($showpic)."\";
		";
		$optionstr.="<option value=".$j.">第 ".$j." 页</option>";
		$titleoption.="<option value=".$j.">".$j."、".$picname."</option>";
		$listpage.=$nbsp."<a href='#ecms' onclick='GotoPhPage(".$j.");' title='".$picname."'>".$j."</a>";
		$nbsp="&nbsp;";
	}
	echo ReplaceMorePagetemp($tempr['temptext'],$rstr,$sdh,$optionstr,$titleoption,$firstpic,$listpage);
}

//替换图片集分页模板
function ReplaceMorePagetemp($temp,$rstr,$sdh,$select,$titleselect,$showpic,$listpage){
	$temp=str_replace("[!--photor--]",$rstr,$temp);
	$temp=str_replace("[!--smalldh--]",$sdh,$temp);
	$temp=str_replace("[!--select--]",$select,$temp);
	$temp=str_replace("[!--titleselect--]",$titleselect,$temp);
	$temp=str_replace("[!--listpage--]",$listpage,$temp);
	$temp=str_replace("<!--list.var1-->",$showpic,$temp);
	return $temp;
}

//替换图片集listvar模板
function ReplaceMorePagelistvar($temp,$picname,$picurl){
	$temp=str_replace("[!--picname--]",$picname,$temp);
	$temp=str_replace("[!--picurl--]",$picurl,$temp);
	return $temp;
}

//输出复选框字段内容
function sys_EchoCheckboxFValue($f,$exp='<br>'){
	global $navinfor;
	$r=explode('|',$navinfor[$f]);
	$count=count($r);
	for($i=1;$i<$count-1;$i++)
	{
		if($i==1)
		{
			$str.=$r[$i];
		}
		else
		{
			$str.=$exp.$r[$i];
		}
	}
	echo $str;
}

//评论调用
function sys_ShowPlInfo($line,$tempid,$classid=0,$id=0,$isgood=0,$enews=0){
	global $empire,$dbtbpre,$class_r,$public_r;
	$a="";
	if($isgood)
	{
		$a.=" and isgood='$isgood'";
	}
	if($classid)
	{
		if($class_r[$classid][islast])
		{
			$where="classid='$classid'";
		}
		else
		{
			$where=ReturnClass($class_r[$classid][sonclass]);
		}
		$a.=" and ".$where;
	}
	if($id)
	{
		$a.=" and id='$id'";
	}
	//排序
	if($enews==1)//支持
	{
		$order='zcnum desc,plid desc';
	}
	elseif($enews==2)//反对
	{
		$order='fdnum desc,plid desc';
	}
	else//发布时间
	{
		$order='plid desc';
	}
	//取得模板
	$tr=sys_ReturnBqTemp($tempid);
	if(empty($tr['tempid']))
	{return "";}
	$listtemp=str_replace('[!--news.url--]',$public_r[newsurl],$tr[temptext]);
	$subnews=$tr[subnews];
	$listvar=str_replace('[!--news.url--]',$public_r[newsurl],$tr[listvar]);
	$rownum=$tr[rownum];
	$formatdate=$tr[showdate];
	if(empty($rownum))
	{$rownum=1;}
	//列表
	$list_exp="[!--empirenews.listtemp--]";
	$list_r=explode($list_exp,$listtemp);
	$listtext=$list_r[1];
	$no=1;
	$changerow=1;
	$sql=$empire->query("select plid,userid,username,saytime,id,classid,zcnum,fdnum,stb from {$dbtbpre}enewspl where checked=0".$a." order by ".$order." limit ".$line);
	while($r=$empire->fetch($sql))
	{
		$fr=$empire->fetch1("select saytext from {$dbtbpre}enewspl_data_".$r['stb']." where plid='$r[plid]'");
		$r[saytext]=$fr[saytext];
		//替换列表变量
		$repvar=ReplaceShowPlVars($no,$listvar,$r,$formatdate,$subnews);
		$listtext=str_replace("<!--list.var".$changerow."-->",$repvar,$listtext);
		$changerow+=1;
		//超过行数
		if($changerow>$rownum)
		{
			$changerow=1;
			$string.=$listtext;
			$listtext=$list_r[1];
		}
		$no++;
	}
	//多余数据
    if($changerow<=$rownum&&$listtext<>$list_r[1])
	{
		$string.=$listtext;
    }
    $string=$list_r[0].$string.$list_r[2];
	echo $string;
}

//替换评论标签
function ReplaceShowPlVars($no,$listtemp,$r,$formatdate,$subnews=0){
	global $public_r,$empire,$dbtbpre,$class_r;
	//标题
	$infor=$empire->fetch1("select titleurl,groupid,classid,newspath,filename,id,title from {$dbtbpre}ecms_".$class_r[$r[classid]][tbname]." where id='$r[id]' limit 1");
	$r['saytext']=stripSlashes($r['saytext']);
	if($subnews)
	{
		$r['saytext']=sub($r['saytext'],0,$subnews,false);
	}
	if($r['userid'])
	{
		$r['username']="<a href='".$public_r[newsurl]."e/space/?userid=$r[userid]' target='_blank'>$r[username]</a>";
	}
	if(empty($r['username']))
	{
		$r['username']='匿名';
	}
	$titleurl=sys_ReturnBqTitleLink($infor);
	$listtemp=str_replace("[!--titleurl--]",$titleurl,$listtemp);
	$listtemp=str_replace("[!--title--]",$infor['title'],$listtemp);
	$listtemp=str_replace("[!--plid--]",$r['plid'],$listtemp);
	$listtemp=str_replace("[!--pltext--]",RepPltextFace($r['saytext']),$listtemp);
	$listtemp=str_replace("[!--id--]",$r['id'],$listtemp);
	$listtemp=str_replace("[!--classid--]",$r['classid'],$listtemp);
	$listtemp=str_replace("[!--pltime--]",format_datetime($r['saytime'],$formatdate),$listtemp);
	$listtemp=str_replace("[!--username--]",$r['username'],$listtemp);
	$listtemp=str_replace("[!--zcnum--]",$r['zcnum'],$listtemp);
	$listtemp=str_replace("[!--fdnum--]",$r['fdnum'],$listtemp);
	//序号
	$listtemp=str_replace("[!--no--]",$no,$listtemp);
	return $listtemp;
}

//显示单个会员信息
function sys_ShowMemberInfo($userid=0,$fields=''){
	global $empire,$dbtbpre,$public_r,$navinfor,$level_r,$user_tablename,$user_userid,$user_group;
	if(empty($userid)&&$navinfor[ismember]==0)
	{
		return '';
	}
	if(!defined('InEmpireCMSUser'))
	{
		include_once ECMS_PATH.'e/class/user.php';
	}
	$uid=$userid?$userid:$navinfor[userid];
	$uid=(int)$uid;
	if(empty($fields))
	{
		$fields='u.*,ui.*';
	}
	$r=$empire->fetch1("select ".$fields." from {$user_tablename} u LEFT JOIN {$dbtbpre}enewsmemberadd ui ON u.".$user_userid."=ui.userid where u.".$user_userid."='$uid' limit 1");
	$r['groupname']=$level_r[$r[$user_group]][groupname];//会员组
	return $r;
}

//调用会员列表
function sys_ListMemberInfo($line=10,$ecms=0,$groupid=0,$userids=0,$fields=''){
	global $empire,$dbtbpre,$public_r,$navinfor,$level_r,$user_tablename,$user_userid,$user_group,$user_userfen,$user_money,$user_checked;
	if(!defined('InEmpireCMSUser'))
	{
		include_once ECMS_PATH.'e/class/user.php';
	}
	//操作类型
	if($ecms==1)//积分排行
	{
		$order='u.'.$user_userfen.' desc';
	}
	elseif($ecms==2)//资金排行
	{
		$order='u.'.$user_money.' desc';
	}
	elseif($ecms==3)//空间人气排行
	{
		$order='ui.viewstats desc';
	}
	else//用户ID排行
	{
		$order='u.'.$user_userid.' desc';
	}
	$where='';
	if($groupid)
	{
		$where.=' and u.'.$user_group.' in ('.$groupid.')';
	}
	if($userids)
	{
		$where.=' and u.'.$user_userid.' in ('.$userids.')';
	}
	if(empty($fields))
	{
		$fields='u.*,ui.*';
	}
	$sql=$empire->query("select ".$fields." from {$user_tablename} u LEFT JOIN {$dbtbpre}enewsmemberadd ui ON u.".$user_userid."=ui.userid where u.".$user_checked."=1".$where." order by ".$order." limit ".$line);
	return $sql;
}
?>