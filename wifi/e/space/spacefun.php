<?php
//返回sql语句
function espace_ReturnBqQuery($classid,$line,$enews=0,$do=0){
	global $empire,$dbtbpre,$public_r,$class_r,$class_zr,$do_openbqquery,$fun_r,$class_tr,$emod_r,$userid;
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
	$query.=" and userid='$userid' and ismember=1";
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
	$query="select * from {$dbtbpre}ecms_".$tbname." where ".$query." order by istop desc,".$order." desc,id desc limit $line";
	$sql=$empire->query1($query);
	if(!$sql)
	{
		echo"SQL Error: ".$query;
	}
	return $sql;
}

//灵动标签：返回SQL内容函数
function espace_eloop($classid=0,$line=10,$enews=3,$doing=0){
	return espace_ReturnBqQuery($classid,$line,$enews,$doing);
}

//灵动标签：返回特殊内容函数
function espace_eloop_sp($r){
	global $class_r;
	$sr['titleurl']=sys_ReturnBqTitleLink($r);
	$sr['classname']=$class_r[$r[classid]][bname]?$class_r[$r[classid]][bname]:$class_r[$r[classid]][classname];
	$sr['classurl']=sys_ReturnBqClassname($r,9);
	return $sr;
}
?>