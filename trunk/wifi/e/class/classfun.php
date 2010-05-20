<?php
//*********************** 专题 *********************

//处理专题提交变量
function DoPostZtVar($add){
	if(empty($add[zttype])){
		$add[zttype]=".html";
	}
	if(empty($add[ztnum])){
		$add[ztnum]=25;
	}
	$add[zcid]=(int)$add['zcid'];
	$add[jstempid]=(int)$add['jstempid'];
	$add[ztname]=addslashes(htmlspecialchars($add[ztname]));
	$add[intro]=addslashes(RepPhpAspJspcode($add[intro]));
	$add[ztpagekey]=addslashes(RepPhpAspJspcode($add[ztpagekey]));
	$add[ztnum]=(int)$add[ztnum];
	$add[listtempid]=(int)$add[listtempid];
	$add[newline]=(int)$add[newline];
	$add[hotline]=(int)$add[hotline];
	$add[goodline]=(int)$add[goodline];
	$add[classid]=(int)$add[classid];
	$add[hotplline]=(int)$add[hotplline];
	$add[firstline]=(int)$add[firstline];
	$add[islist]=(int)$add[islist];
	$add[maxnum]=(int)$add[maxnum];
	$add[showzt]=(int)$add[showzt];
	$add[classtempid]=(int)$add[classtempid];
	$add['myorder']=(int)$add['myorder'];
	$add[nrejs]=(int)$add[nrejs];
	//目录
	$add[ztpath]=$add['pripath'].$add['ztpath'];
	return $add;
}

//增加专题
function AddZt($add,$userid,$username){
	global $empire,$class_r,$dbtbpre;
	$add[ztpath]=trim($add[ztpath]);
	if(!$add[ztname]||!$add[listtempid]||!$add[ztpath]){
		printerror("EmptyZt","");
	}
	CheckLevel($userid,$username,$classid,"zt");
	$add=DoPostZtVar($add);
	$createpath='../../'.$add[ztpath];
	//检测目录是否存在
	if(file_exists($createpath)){
		printerror("ReZtpath","");
	}
	CreateZtPath($add[ztpath]);//建立专题目录
	//取得表名
	$tabler=GetModTable(GetListtempMid($add[listtempid]));
	$tabler[tid]=(int)$tabler[tid];
	$sql=$empire->query("insert into {$dbtbpre}enewszt(ztname,ztnum,listtempid,onclick,ztpath,zttype,newline,zturl,hotline,goodline,classid,hotplline,firstline,islist,maxnum,tid,tbname,reorderf,reorder,intro,ztimg,zcid,jstempid,showzt,ztpagekey,classtempid,myorder,nrejs) values('$add[ztname]',$add[ztnum],$add[listtempid],0,'$add[ztpath]','$add[zttype]',$add[newline],'$add[zturl]',$add[hotline],$add[goodline],$add[classid],$add[hotplline],$add[firstline],$add[islist],$add[maxnum],$tabler[tid],'$tabler[tbname]','$add[reorderf]','$add[reorder]','$add[intro]','$add[ztimg]',$add[zcid],$add[jstempid],$add[showzt],'$add[ztpagekey]','$add[classtempid]',$add[myorder],$add[nrejs]);");
	$ztid=$empire->lastid();
	//生成页面
	if(!$add[islist]){
		NewsBq($ztid,GetClassTemp($add['classtempid']),3,1);
    }
	GetClass();//更新缓存
	if($sql){
		insert_dolog("ztid=".$ztid."<br>ztname=".$add[ztname]);//操作日志
		printerror("AddZtSuccess","AddZt.php?enews=AddZt");
	}
	else{
		printerror("DbError","");
	}
}

//修改专题
function EditZt($add,$userid,$username){
	global $empire,$class_r,$dbtbpre;
	$add[ztid]=(int)$add[ztid];
	$add[ztpath]=trim($add[ztpath]);
	if(!$add[ztname]||!$add[listtempid]||!$add[ztpath]||!$add[ztid]){
		printerror("EmptyZt","");
	}
	CheckLevel($userid,$username,$classid,"zt");
	$add=DoPostZtVar($add);
	//改变目录
	if($add[oldztpath]<>$add[ztpath]){
		$createpath='../../'.$add[ztpath];
		if(file_exists($createpath)){
			printerror("ReZtpath","");
		}
		if($add['oldpripath']==$add['pripath']){
			$new="../../";
			@rename($new.$add[oldztpath],$new.$add[ztpath]);//改变目录名
		}
		else{
			CreateZtPath($add[ztpath]);//建立专题目录
		}
    }
	//取得表名
	$tabler=GetModTable(GetListtempMid($add[listtempid]));
	$tabler[tid]=(int)$tabler[tid];
	$sql=$empire->query("update {$dbtbpre}enewszt set ztname='$add[ztname]',ztnum=$add[ztnum],listtempid=$add[listtempid],ztpath='$add[ztpath]',zttype='$add[zttype]',newline=$add[newline],zturl='$add[zturl]',hotline=$add[hotline],goodline=$add[goodline],classid=$add[classid],hotplline=$add[hotplline],firstline=$add[firstline],islist=$add[islist],maxnum=$add[maxnum],tid=$tabler[tid],tbname='$tabler[tbname]',reorderf='$add[reorderf]',reorder='$add[reorder]',intro='$add[intro]',ztimg='$add[ztimg]',zcid=$add[zcid],jstempid=$add[jstempid],showzt=$add[showzt],ztpagekey='$add[ztpagekey]',classtempid='$add[classtempid]',myorder=$add[myorder],nrejs=$add[nrejs] where ztid='$add[ztid]'");
	GetClass();//更新缓存
	//生成页面
	if(!$add[islist]){
		NewsBq($add[ztid],GetClassTemp($add['classtempid']),3,1);
    }
	if($sql){
		insert_dolog("ztid=".$add[ztid]."<br>ztname=".$add[ztname]);//操作日志
		printerror("EditZtSuccess","ListZt.php");
	}
	else{
		printerror("DbError","");
	}
}

//删除专题
function DelZt($ztid,$userid,$username){
	global $empire,$dbtbpre;
	$ztid=(int)$ztid;
	if(!$ztid){
		printerror("NotDelZtid","");
	}
	CheckLevel($userid,$username,$classid,"zt");
	$r=$empire->fetch1("select * from {$dbtbpre}enewszt where ztid='$ztid'");
	if(empty($r[ztid])){
		printerror("NotDelZtid","history.go(-1)");
	}
	//删除专题
	$sql=$empire->query("delete from {$dbtbpre}enewszt where ztid='$ztid'");
	$delpath="../../".$r[ztpath];
	$del=DelPath($delpath);
	//改变信息专题值
	$nsql=$empire->query("select id,ztid from {$dbtbpre}ecms_".$r[tbname]." where ztid like '%|".$ztid."|%'");
	while($nr=$empire->fetch($nsql))
	{
		$newztid=str_replace("|".$ztid."|","|",$nr[ztid]);
		$usql=$empire->query("update {$dbtbpre}ecms_".$r[tbname]." set ztid='$newztid' where id='$nr[id]'");
    }
	GetClass();//更新缓存
	if($sql){
		insert_dolog("ztid=".$ztid."<br>ztname=".$r[ztname]);//操作日志
		printerror("DelZtSuccess","ListZt.php");
	}
	else{
		printerror("DbError","");
	}
}

//组合专题
function TogZt($add,$userid,$username){
	global $empire,$class_r,$dbtbpre;
	$ztid=(int)$add['ztid'];
	if(empty($ztid))
	{
		printerror("ErrorUrl","history.go(-1)");
    }
	$r=$empire->fetch1("select ztid,ztname,tbname from {$dbtbpre}enewszt where ztid=$ztid");
	if(empty($r['ztid'])||empty($r['tbname']))
	{
		printerror("ErrorUrl","history.go(-1)");
	}
	$wheresql="";
	$formvar="";
	//关键字
	$keyboard=RepPostVar2($add['keyboard']);
	if($keyboard)
	{
		$formvar.=ReturnFormHidden('keyboard',$add['keyboard']);
		$searchfsql='';
		if($add['stitle'])//标题
		{
			$searchfsql.="title like '%$keyboard%'";
			$formvar.=ReturnFormHidden('stitle',$add['stitle']);
		}
		if($add['susername'])//增加者
		{
			if($searchfsql)
			{
				$or=" or ";
			}
			$searchfsql.=$or."username like '%$keyboard%'";
			$formvar.=ReturnFormHidden('susername',$add['susername']);
		}
		if($add['snewstext'])//内容
		{
			$or="";
			if($searchfsql)
			{
				$or=" or ";
			}
			$searchfsql.=$or."newstext like '%$keyboard%'";
			$formvar.=ReturnFormHidden('snewstext',$add['snewstext']);
		}
		if($searchfsql)
		{
			$wheresql=" and (".$searchfsql.")";
		}
	}
	//是否推荐
	if($add['isgood'])
	{
		$wheresql.=" and isgood=1";
		$formvar.=ReturnFormHidden('isgood',$add['isgood']);
	}
	//头条
	if($add['firsttitle'])
	{
		$wheresql.=" and firsttitle=1";
		$formvar.=ReturnFormHidden('firsttitle',$add['firsttitle']);
	}
	//有标题图片
	if($add['titlepic'])
	{
		$wheresql.=" and titlepic<>''";
		$formvar.=ReturnFormHidden('titlepic',$add['titlepic']);
	}
	//审核
	if($add['checked'])
	{
		$wheresql.=" and checked=1";
		$formvar.=ReturnFormHidden('checked',$add['checked']);
	}
	//按栏目刷新
	$classid=(int)$add['classid'];
    if($classid)
	{
		$formvar.=ReturnFormHidden('classid',$add['classid']);
		//大栏目
		if(empty($class_r[$classid][islast]))
		{
			$where=ReturnClass($class_r[$classid][sonclass]);
		}
		//终极栏目
		else
		{
			$where="classid='$classid'";
		}
		$wheresql.=" and (".$where.")";
    }
	$startid=(int)$add[startid];
	$endid=(int)$add[endid];
	$startday=RepPostVar($add[startday]);
	$endday=RepPostVar($add[endday]);
	$formvar.=ReturnFormHidden('retype',$add['retype']);
	//按ID
    if($add['retype'])
	{
		if($endid)
		{
			$wheresql.=" and id>=$startid and id<=$endid";
			$formvar.=ReturnFormHidden('startid',$add[startid]).ReturnFormHidden('endid',$add[endid]);
	    }
    }
    else
	{
		if($startday&&$endday)
		{
			$wheresql.=" and truetime>=".to_time($startday." 00:00:00")." and truetime<=".to_time($endday." 23:59:59");
			$formvar.=ReturnFormHidden('startday',$add[startday]).ReturnFormHidden('endday',$add[endday]);
	    }
    }
	//附件sql条件
	$query=$add['query'];
	if($query)
	{
		$query=ClearAddsData($query);//去除adds
		$wheresql.=" and (".$query.")";
		$formvar.=ReturnFormHidden('query',$add['query']);
	}
	$wheresql=" where ztid not like '%|".$ztid."|%'".$wheresql;
	$owheresql=$wheresql." and ztid=''";
	if($add['doecmszt'])
	{
		if($add['inid'])
		{
			$add['inid']=RepPostVar($add['inid']);
			$wheresql.=" and id not in (".$add['inid'].")";
			$owheresql.=" and id not in (".$add['inid'].")";
		}
		$repztid="|".$ztid."|";
		$conztid=$ztid."|";
		//将空格转换成|
		$usql=$empire->query("update {$dbtbpre}ecms_".$r['tbname']." set ztid='|'".$owheresql);
		//组成新专题
		$sql=$empire->query("update {$dbtbpre}ecms_".$r['tbname']." set ztid=CONCAT(REPLACE(ztid,'".$repztid."','|'),'".$conztid."')".$wheresql);
		if($usql&&$sql)
		{
			//操作日志
	        insert_dolog("ztid=$ztid&ztname=$r[ztname]");
			printerror("TogZtSuccess","TogZt.php?ztid=$ztid");
		}
		else
		{
			printerror("DbError","history.go(-1)");
		}
	}
	$re[0]=$wheresql;
	$re[1]=$formvar.ReturnFormHidden('ztid',$ztid).ReturnFormHidden('pline',$add[pline]).ReturnFormHidden('doecmszt',$add[doecmszt]).ReturnFormHidden('enews',$add[enews]).ReturnFormHidden('inid',$add[inid]);
	$re[2]=$r['tbname'];
	$re[3]=$r['ztname'];
	return $re;
}

//保存专题信息
function SaveTogZtInfo($add,$userid,$username){
	global $empire,$dbtbpre;
	if(!trim($add[togztname]))
	{
		printerror('EmptySaveTogZtname','history.go(-1)');
	}
	$add['doecmszt']=(int)$add['doecmszt'];
	$add[classid]=(int)$add[classid];
	//搜索字段
	$searchf=',';
	if($add[stitle]==1)
	{
		$searchf.='stitle,';
	}
	if($add[susername]==1)
	{
		$searchf.='susername,';
	}
	if($add[snewstext]==1)
	{
		$searchf.='snewstext,';
	}
	//特殊字段
	$specialsearch=',';
	if($add[isgood])
	{
		$specialsearch.='isgood,';
	}
	if($add[firsttitle])
	{
		$specialsearch.='firsttitle,';
	}
	if($add[titlepic])
	{
		$specialsearch.='titlepic,';
	}
	if($add[checked])
	{
		$specialsearch.='checked,';
	}
	$add['retype']=(int)$add['retype'];
	$add['startid']=(int)$add['startid'];
	$add['endid']=(int)$add['endid'];
	$add['pline']=(int)$add['pline'];
	$r=$empire->fetch1("select togid from {$dbtbpre}enewstogzts where togztname='$add[togztname]'");
	if($r[togid])
	{
		$sql=$empire->query("update {$dbtbpre}enewstogzts set keyboard='".addslashes($add[keyboard])."',searchf='$searchf',query='".addslashes($add[query])."',specialsearch='$specialsearch',classid=$add[classid],retype=$add[retype],startday='".addslashes($add[startday])."',endday='".addslashes($add[endday])."',startid=$add[startid],endid=$add[endid],pline=$add[pline],doecmszt=$add[doecmszt] where togid='$r[togid]'");
		$togid=$r[togid];
	}
	else
	{
		$sql=$empire->query("insert into {$dbtbpre}enewstogzts(keyboard,searchf,query,specialsearch,classid,retype,startday,endday,startid,endid,pline,doecmszt,togztname) values('".addslashes($add[keyboard])."','$searchf','".addslashes($add[query])."','$specialsearch',$add[classid],$add[retype],'".addslashes($add[startday])."','".addslashes($add[endday])."',$add[startid],$add[endid],$add[pline],$add[doecmszt],'".addslashes($add[togztname])."');");
		$togid=$empire->lastid();
	}
	if($sql)
	{
		insert_dolog("togid=$togid&togztname=$add[togztname]");//操作日志
		printerror("SaveTogZtInfoSuccess","TogZt.php?ztid=$add[ztid]&togid=$togid");
	}
	else
	{
		printerror("DbError","history.go(-1)");
	}
}

//删除保存专题信息
function DelTogZtInfo($add,$userid,$username){
	global $empire,$dbtbpre;
	$togid=intval($add[togid]);
	if(!$togid)
	{
		printerror('EmptyDelTogztid','history.go(-1)');
	}
	$r=$empire->fetch1("select togid,togztname from {$dbtbpre}enewstogzts where togid='$togid'");
	if(!$r[togid])
	{
		printerror('EmptyDelTogztid','history.go(-1)');
	}
	$sql=$empire->query("delete from {$dbtbpre}enewstogzts where togid='$togid'");
	if($sql)
	{
		insert_dolog("togid=$togid&togztname=$r[togztname]");//操作日志
		printerror('DelTogZtInfoSuccess',$_SERVER['HTTP_REFERER']);
	}
	else
	{
		printerror("DbError","history.go(-1)");
	}
}


//************************************ 栏目 ************************************

//组合不生成的栏目信息
function TogNotReClass($changecache=0){
	global $empire,$dbtbpre;
	$sql=$empire->query("select classid,nreclass,nreinfo,nrejs,nottobq from {$dbtbpre}enewsclass where nreclass=1 or nreinfo=1 or nrejs=1 or nottobq=1");
	$nreclass=',';
	$nreinfo=',';
	$nrejs=',';
	$nottobq=',';
	while($r=$empire->fetch($sql))
	{
		if($r['nreclass']==1)
		{
			$nreclass.=$r['classid'].',';
		}
		if($r['nreinfo']==1)
		{
			$nreinfo.=$r['classid'].',';
		}
		if($r['nrejs']==1)
		{
			$nrejs.=$r['classid'].',';
		}
		if($r['nottobq']==1)
		{
			$nottobq.=$r['classid'].',';
		}
	}
	$empire->query("update {$dbtbpre}enewspublic set nreclass='$nreclass',nreinfo='$nreinfo',nrejs='$nrejs',nottobq='$nottobq' limit 1");
	if($changecache==1)
	{
		GetConfig();
	}
}

//返回投稿权限
function DoPostClassQAddGroupid($groupid){
	$count=count($groupid);
	if(!$count)
	{
		return '';
	}
	$qg=',';
	for($i=0;$i<$count;$i++)
	{
		$qg.=$groupid[$i].',';
	}
	return $qg;
}

//处理栏目提交变量
function DoPostClassVar($add){
	if(empty($add[classtype])){
		$add[classtype]=".html";
	}
	$add[classname]=addslashes(htmlspecialchars($add[classname]));
	$add[intro]=addslashes(RepPhpAspJspcode($add[intro]));
	$add[classpagekey]=addslashes(RepPhpAspJspcode($add[classpagekey]));
	//过滤字符
	$add[listorderf]=RepPostVar($add[listorderf]);
	$add[listorder]=RepPostVar($add[listorder]);
	$add[reorderf]=RepPostVar($add[reorderf]);
	$add[reorder]=RepPostVar($add[reorder]);
	//处理变量
	$add[jstempid]=(int)$add['jstempid'];
	$add[bclassid]=(int)$add[bclassid];
	$add[link_num]=(int)$add[link_num];
	if(empty($add[link_num])){
		$add[link_num]=10;
	}
	$add[newstempid]=(int)$add[newstempid];
	$add[islast]=(int)$add[islast];
	$add[filename]=(int)$add[filename];
	$add[openpl]=(int)$add[openpl];
	$add[openadd]=(int)$add[openadd];
	$add[newline]=(int)$add[newline];
	$add[hotline]=(int)$add[hotline];
	$add[goodline]=(int)$add[goodline];
	$add[groupid]=(int)$add[groupid];
	$add[hotplline]=(int)$add[hotplline];
	$add[modid]=(int)$add[modid];
	$add[checked]=(int)$add[checked];
	$add[docheckuser]=(int)$add[docheckuser];
	$add[firstline]=(int)$add[firstline];
	$add[islist]=(int)$add[islist];
	$add[searchtempid]=(int)$add[searchtempid];
	$add[checkpl]=(int)$add[checkpl];
	$add[down_num]=(int)$add[down_num];
	if(empty($add[down_num])){
		$add[down_num]=1;
	}
	$add[online_num]=(int)$add[online_num];
	if(empty($add[online_num])){
		$add[online_num]=1;
	}
	$add[addinfofen]=(int)$add[addinfofen];
	$add[listdt]=(int)$add[listdt];
	$add[showdt]=(int)$add[showdt];
	$add[maxnum]=(int)$add[maxnum];
	$add[showclass]=(int)$add[showclass];
	$add[checkqadd]=(int)$add[checkqadd];
	$add[qaddlist]=(int)$add[qaddlist];
	$add[qaddgroupid]=DoPostClassQAddGroupid($add[qaddgroupidck]);
	$add[qaddshowkey]=(int)$add[qaddshowkey];
	$add[adminqinfo]=(int)$add[adminqinfo];
	$add[doctime]=(int)$add[doctime];
	$add[nreclass]=(int)$add[nreclass];
	$add[nreinfo]=(int)$add[nreinfo];
	$add[nrejs]=(int)$add[nrejs];
	$add[nottobq]=(int)$add[nottobq];
	$add[lencord]=(int)$add[lencord];
	$add[listtempid]=(int)$add[listtempid];
	$add[dtlisttempid]=(int)$add[dtlisttempid];
	$add[classtempid]=(int)$add[classtempid];
	if(empty($add[bname])){
		$add[bname]=$add[classname];
	}
	$add[myorder]=(int)$add[myorder];
	if($add[infopath]==0)
	{
		$add[ipath]='';
	}
	$add[addreinfo]=(int)$add[addreinfo];
	$add[haddlist]=(int)$add[haddlist];
	$add[sametitle]=(int)$add[sametitle];
	$add[definfovoteid]=(int)$add[definfovoteid];
	$add[qeditchecked]=(int)$add[qeditchecked];
	$add[wapstyleid]=(int)$add[wapstyleid];
	$add[repreinfo]=(int)$add[repreinfo];
	$add[pltempid]=(int)$add[pltempid];
	$add[cgroupid]=(int)$add[cgroupid];
	$add[classtext]=RepPhpAspJspcode($add[classtext]);
	return $add;
}

//增加外部栏目
function AddWbClass($add,$userid,$username){
	global $empire,$dbtbpre;
	//操作权限
	CheckLevel($userid,$username,$classid,"class");
	$add=DoPostClassVar($add);
	if(!$add[classname]||!$add[wburl])
	{
		printerror("EmptyWbClass","");
	}
	$add[islast]=0;
	//取得表名
	$tabler=GetModTable($add[modid]);
	$tabler[tid]=(int)$tabler[tid];
	if(empty($add[bclassid]))//主栏目
	{
		$sonclass="";
		$featherclass="";
	}
	else//中级栏目
	{
		//取得上一级父栏目
		$r=$empire->fetch1("select featherclass,islast,wburl from {$dbtbpre}enewsclass where classid='$add[bclassid]'");
		if($r[islast])//是否终极栏目
		{
			printerror("BclassNotLast","");
		}
		if($r[wburl])
		{
			printerror("BclassNotWb","");
		}
		if(empty($r[featherclass]))
		{
			$r[featherclass]="|";
		}
		$featherclass=$r[featherclass].$add[bclassid]."|";
		$sonclass="";
	}
	$sql=$empire->query("insert into {$dbtbpre}enewsclass(bclassid,classname,is_zt,sonclass,lencord,link_num,newstempid,onclick,listtempid,featherclass,islast,classpath,classtype,newspath,filename,filetype,openpl,openadd,newline,hotline,goodline,classurl,groupid,myorder,filename_qz,hotplline,modid,checked,docheckuser,checkuser,firstline,bname,islist,searchtempid,tid,tbname,maxnum,checkpl,down_num,online_num,listorderf,listorder,reorderf,reorder,intro,classimg,jstempid,addinfofen,listdt,showclass,showdt,checkqadd,qaddlist,qaddgroupid,qaddshowkey,adminqinfo,doctime,classpagekey,dtlisttempid,classtempid,nreclass,nreinfo,nrejs,nottobq,ipath,addreinfo,haddlist,sametitle,definfovoteid,wburl,qeditchecked,wapstyleid,repreinfo,pltempid,cgroupid) values($add[bclassid],'$add[classname]',0,'$sonclass',$add[lencord],$add[link_num],$add[newstempid],0,$add[listtempid],'$featherclass',$add[islast],'$classpath','$add[classtype]','$add[newspath]',$add[filename],'$add[filetype]',$add[openpl],$add[openadd],$add[newline],$add[hotline],$add[goodline],'$add[classurl]',$add[groupid],$add[myorder],'$add[filename_qz]',$add[hotplline],$add[modid],$add[checked],$add[docheckuser],'$add[checkuser]',$add[firstline],'$add[bname]',$add[islist],$add[searchtempid],$tabler[tid],'$tabler[tbname]',$add[maxnum],$add[checkpl],$add[down_num],$add[online_num],'$add[listorderf]','$add[listorder]','$add[reorderf]','$add[reorder]','$add[intro]','$add[classimg]',$add[jstempid],$add[addinfofen],$add[listdt],$add[showclass],$add[showdt],$add[checkqadd],$add[qaddlist],'$add[qaddgroupid]',$add[qaddshowkey],$add[adminqinfo],$add[doctime],'$add[classpagekey]','$add[dtlisttempid]','$add[classtempid]',$add[nreclass],$add[nreinfo],$add[nrejs],$add[nottobq],'$add[ipath]',$add[addreinfo],$add[haddlist],$add[sametitle],$add[definfovoteid],'$add[wburl]',$add[qeditchecked],$add[wapstyleid],'$add[repreinfo]','$add[pltempid]','$add[cgroupid]');");
	$lastid=$empire->lastid();
	//副表
	$empire->query("replace into {$dbtbpre}enewsclassadd(classid,classtext) values('$lastid','".addslashes($add[classtext])."');");
	GetClass();
	DelListEnews();//删除缓存文件
	if($sql)
	{
		insert_dolog("classid=".$lastid."<br>classname=".$add[classname]);//操作日志
		printerror("AddClassSuccess","AddClass.php?enews=AddClass&from=$add[from]");
	}
	else
	{
		printerror("DbError","");
	}
}

//增加栏目
function AddClass($add,$userid,$username){
	global $empire,$dbtbpre;
	//增加外部栏目
	if($add[ecmsclasstype])
	{
		AddWbClass($add,$userid,$username);
	}
	$add[classpath]=trim($add[classpath]);
	if(!$add[classname]||!$add[classpath]||!$add[modid])
	{
		printerror("EmptyClass","");
	}
	if($add[islast]&&(!$add[newstempid]||!$add[listtempid]))
	{
		printerror("LastMustChange","");
	}
	//操作权限
	CheckLevel($userid,$username,$classid,"class");
	$add=DoPostClassVar($add);
	//目录已存在
	if(strchr($add[classpath],".")||strchr($add[classpath],"/")||strchr($add[classpath],"\\"))
	{
		printerror("badpath","");
	}
	$classpath=$add[pripath].$add[classpath];
	if(file_exists("../../".$classpath))
	{
		printerror("ReClasspath","");
	}
	//取得表名
	$tabler=GetModTable($add[modid]);
	$tabler[tid]=(int)$tabler[tid];
	//增加大栏目
	if(!$add[islast])
	{
		if(empty($add[bclassid]))//主栏目
		{
			$sonclass="";
			$featherclass="";
	    }
		else//中级栏目
		{
			//取得上一级父栏目
			$r=$empire->fetch1("select featherclass,islast,wburl from {$dbtbpre}enewsclass where classid='$add[bclassid]'");
			if($r[islast])//是否终极栏目
			{
				printerror("BclassNotLast","");
			}
			if($r[wburl])
			{
				printerror("BclassNotWb","");
			}
			if(empty($r[featherclass]))
			{
				$r[featherclass]="|";
			}
			$featherclass=$r[featherclass].$add[bclassid]."|";
			$sonclass="";
	    }
		//建立目录
		CreateClassPath($classpath);
		$sql=$empire->query("insert into {$dbtbpre}enewsclass(bclassid,classname,is_zt,sonclass,lencord,link_num,newstempid,onclick,listtempid,featherclass,islast,classpath,classtype,newspath,filename,filetype,openpl,openadd,newline,hotline,goodline,classurl,groupid,myorder,filename_qz,hotplline,modid,checked,docheckuser,checkuser,firstline,bname,islist,searchtempid,tid,tbname,maxnum,checkpl,down_num,online_num,listorderf,listorder,reorderf,reorder,intro,classimg,jstempid,addinfofen,listdt,showclass,showdt,checkqadd,qaddlist,qaddgroupid,qaddshowkey,adminqinfo,doctime,classpagekey,dtlisttempid,classtempid,nreclass,nreinfo,nrejs,nottobq,ipath,addreinfo,haddlist,sametitle,definfovoteid,wburl,qeditchecked,wapstyleid,repreinfo,pltempid,cgroupid) values($add[bclassid],'$add[classname]',0,'$sonclass',$add[lencord],$add[link_num],$add[newstempid],0,$add[listtempid],'$featherclass',$add[islast],'$classpath','$add[classtype]','$add[newspath]',$add[filename],'$add[filetype]',$add[openpl],$add[openadd],$add[newline],$add[hotline],$add[goodline],'$add[classurl]',$add[groupid],$add[myorder],'$add[filename_qz]',$add[hotplline],$add[modid],$add[checked],$add[docheckuser],'$add[checkuser]',$add[firstline],'$add[bname]',$add[islist],$add[searchtempid],$tabler[tid],'$tabler[tbname]',$add[maxnum],$add[checkpl],$add[down_num],$add[online_num],'$add[listorderf]','$add[listorder]','$add[reorderf]','$add[reorder]','$add[intro]','$add[classimg]',$add[jstempid],$add[addinfofen],$add[listdt],$add[showclass],$add[showdt],$add[checkqadd],$add[qaddlist],'$add[qaddgroupid]',$add[qaddshowkey],$add[adminqinfo],$add[doctime],'$add[classpagekey]','$add[dtlisttempid]','$add[classtempid]',$add[nreclass],$add[nreinfo],$add[nrejs],$add[nottobq],'$add[ipath]',$add[addreinfo],$add[haddlist],$add[sametitle],$add[definfovoteid],'',$add[qeditchecked],$add[wapstyleid],'$add[repreinfo]','$add[pltempid]','$add[cgroupid]');");
		$lastid=$empire->lastid();
		//副表
		$empire->query("replace into {$dbtbpre}enewsclassadd(classid,classtext) values('$lastid','".addslashes($add[classtext])."');");
		TogNotReClass(1);
		GetClass();
		if($add[islist]==0||$add[islist]==2)
		{
			$classtemp=$add[islist]==2?GetClassText($lastid):GetClassTemp($add['classtempid']);
			NewsBq($lastid,$classtemp,0,1);
		}
		DelListEnews();//删除缓存文件
		GetSearch($add[modid]);//更新缓存
		if($sql){
			insert_dolog("classid=".$lastid."<br>classname=".$add[classname]);//操作日志
			printerror("AddClassSuccess","AddClass.php?enews=AddClass&from=$add[from]");
		}
		else{
			printerror("DbError","");
		}
    }
	//增加终级栏目
	else
	{
		//文件前缀
		$add[filename_qz]=RepFilenameQz($add[filename_qz]);
		if(empty($add[bclassid]))//主类别为终级栏目时
		{
			$sonclass="";
			$featherclass="";
	    }
		else//子栏目
		{
			//取得上一级父栏目
			$r=$empire->fetch1("select featherclass,islast,wburl from {$dbtbpre}enewsclass where classid='$add[bclassid]'");
			//是否终极类别
			if($r[islast])
			{
				printerror("BclassNotLast","");
			}
			if($r[wburl])
			{
				printerror("BclassNotWb","");
			}
			if(empty($r[featherclass])){
				$r[featherclass]="|";
			}
			$featherclass=$r[featherclass].$add[bclassid]."|";
			$sonclass="";
		}
		//建立栏目目录
		CreateClassPath($classpath);
		$sql=$empire->query("insert into {$dbtbpre}enewsclass(bclassid,classname,sonclass,is_zt,lencord,link_num,newstempid,onclick,listtempid,featherclass,islast,classpath,classtype,newspath,filename,filetype,openpl,openadd,newline,hotline,goodline,classurl,groupid,myorder,filename_qz,hotplline,modid,checked,docheckuser,checkuser,firstline,bname,islist,searchtempid,tid,tbname,maxnum,checkpl,down_num,online_num,listorderf,listorder,reorderf,reorder,intro,classimg,jstempid,addinfofen,listdt,showclass,showdt,checkqadd,qaddlist,qaddgroupid,qaddshowkey,adminqinfo,doctime,classpagekey,dtlisttempid,classtempid,nreclass,nreinfo,nrejs,nottobq,ipath,addreinfo,haddlist,sametitle,definfovoteid,wburl,qeditchecked,wapstyleid,repreinfo,pltempid,cgroupid) values($add[bclassid],'$add[classname]','$sonclass',0,$add[lencord],$add[link_num],$add[newstempid],0,$add[listtempid],'$featherclass',$add[islast],'$classpath','$add[classtype]','$add[newspath]',$add[filename],'$add[filetype]',$add[openpl],$add[openadd],$add[newline],$add[hotline],$add[goodline],'$add[classurl]',$add[groupid],$add[myorder],'$add[filename_qz]',$add[hotplline],$add[modid],$add[checked],$add[docheckuser],'$add[checkuser]',$add[firstline],'$add[bname]',$add[islist],$add[searchtempid],$tabler[tid],'$tabler[tbname]',$add[maxnum],$add[checkpl],$add[down_num],$add[online_num],'$add[listorderf]','$add[listorder]','$add[reorderf]','$add[reorder]','$add[intro]','$add[classimg]',$add[jstempid],$add[addinfofen],$add[listdt],$add[showclass],$add[showdt],$add[checkqadd],$add[qaddlist],'$add[qaddgroupid]',$add[qaddshowkey],$add[adminqinfo],$add[doctime],'$add[classpagekey]','$add[dtlisttempid]','$add[classtempid]',$add[nreclass],$add[nreinfo],$add[nrejs],$add[nottobq],'$add[ipath]',$add[addreinfo],$add[haddlist],$add[sametitle],$add[definfovoteid],'',$add[qeditchecked],$add[wapstyleid],'$add[repreinfo]','$add[pltempid]','$add[cgroupid]');");
		$lastid=$empire->lastid();
		//副表
		$empire->query("replace into {$dbtbpre}enewsclassadd(classid,classtext) values('$lastid','".addslashes($add[classtext])."');");
		//修改父栏目的子栏目
		if($add[bclassid])
		{
			$b_r=$empire->fetch1("select sonclass,featherclass from {$dbtbpre}enewsclass where classid='$add[bclassid]'");
			if(empty($b_r[sonclass]))
			{
				$b_r[sonclass]="|";
			}
			$new_sonclass=$b_r[sonclass].$lastid."|";
			$update=$empire->query("update {$dbtbpre}enewsclass set sonclass='$new_sonclass' where classid='$add[bclassid]'");
			//更改父类别的父栏目的子栏目
			$where=ReturnClass($b_r[featherclass]);
			if(empty($where)){
				$where="classid=0";
			}
			$bsql=$empire->query("select sonclass,classid from {$dbtbpre}enewsclass where ".$where);
			while($br=$empire->fetch($bsql))
			{
				if(empty($br[sonclass]))
				{
					$br[sonclass]="|";
				}
				$new_sonclass=$br[sonclass].$lastid."|";
				$update=$empire->query("update {$dbtbpre}enewsclass set sonclass='$new_sonclass' where classid='$br[classid]'");
            }
	    }
		DelListEnews();//删除缓存文件
		TogNotReClass(1);
		GetClass();
		GetSearch($add[modid]);//更新缓存
		if($sql){
			insert_dolog("classid=".$lastid."<br>classname=".$add[classname]);//操作日志
			printerror("AddLastClassSuccess","AddClass.php?enews=AddClass&from=$add[from]");
		}
		else{
			printerror("DbError","history.go(-1)");
		}
    }
}

//绑定域名应用于子栏目
function UpdateSmallClassDomain($classid,$classurl,$classpath){
	global $empire,$dbtbpre;
	if(empty($classurl)){
		$query="update {$dbtbpre}enewsclass set classurl='' where featherclass like '%|".$classid."|%'";
    }
	else{
		$query="update {$dbtbpre}enewsclass set classurl=CONCAT('".$classurl."',SUBSTRING(classpath,LENGTH('".$classpath."')+1)) where featherclass like '%|".$classid."|%'";
    }
	$sql=$empire->query($query);
}

//栏目目录修改
function AlterClassPath($classid,$islast,$oldclasspath,$classpath){
	global $empire,$dbtbpre;
	//更新目录名
	if($oldclassname!=$classpath)
	{
		@rename("../../".$oldclasspath,"../../".$classpath);
		@rename("../../d/file/".$oldclasspath,"../../d/file/".$classpath);
		if(empty($islast))
		{
			$sql=$empire->query("update {$dbtbpre}enewsclass set classpath=REPLACE(classpath,'".$oldclasspath."/','".$classpath."/') where featherclass like '%|".$classid."|%'");
		}
		DelListEnews();
	}
}

//修改外部栏目
function EditWbClass($add,$userid,$username){
	global $empire,$class_r,$dbtbpre;
	//操作权限
	CheckLevel($userid,$username,$classid,"class");
	$add=DoPostClassVar($add);
	$add[classid]=(int)$add[classid];
	if(!$add[classname]||!$add[classid]||!$add[wburl])
	{
		printerror("EmptyWbClass","");
	}
	$add[islast]=0;
	//取得表名
	$tabler=GetModTable($add[modid]);
	$tabler[tid]=(int)$tabler[tid];
	//改变大栏目
	if($add[bclassid]<>$add[oldbclassid])
	{
		//转到主栏目
		if(empty($add[bclassid]))
		{
			$sonclass="";
			$featherclass="";
		}
		//转到中级栏目
		else
		{
			//大栏目跟原栏目相同
			if($add[classid]==$add[bclassid])
			{
				printerror("BclassIsself","");
			}
			//取得现在大栏目的值
	 		$b=$empire->fetch1("select featherclass,sonclass,islast,wburl from {$dbtbpre}enewsclass where classid='$add[bclassid]'");
			//检测大栏目是否为终级栏目
			if($b[islast])
			{
				printerror("BclassNotLast","");
			}
			if($b[wburl])
			{
				printerror("BclassNotWb","");
			}
			//是否非法父栏目
			if($b[featherclass])
			{
				$c_nb_r=explode("|".$add[classid]."|",$b[featherclass]);
				if(count($c_nb_r)<>1)
				{
					printerror("BclassIssmall","");
				}
			}
			if(empty($b[featherclass]))
			{
				$b[featherclass]="|";
			}
			$featherclass=$b[featherclass].$add[bclassid]."|";
		}
		$change=",bclassid=$add[bclassid],featherclass='$featherclass'";
	}
	//修改数据库资料
	$sql=$empire->query("update {$dbtbpre}enewsclass set classname='$add[classname]',classpath='$classpath',classtype='$add[classtype]',newline=$add[newline],hotline=$add[hotline],goodline=$add[goodline],classurl='$add[classurl]',groupid=$add[groupid],myorder=$add[myorder],filename_qz='$add[filename_qz]',hotplline=$add[hotplline],modid=$add[modid],checked=$add[checked],docheckuser=$add[docheckuser],checkuser='$add[checkuser]',firstline=$add[firstline],bname='$add[bname]',islist=$add[islist],listtempid=$add[listtempid],lencord=$add[lencord],searchtempid=$add[searchtempid],tid=$tabler[tid],tbname='$tabler[tbname]',maxnum=$add[maxnum],checkpl=$add[checkpl],down_num=$add[down_num],online_num=$add[online_num],listorderf='$add[listorderf]',listorder='$add[listorder]',reorderf='$add[reorderf]',reorder='$add[reorder]',intro='$add[intro]',classimg='$add[classimg]',jstempid=$add[jstempid],listdt=$add[listdt],showclass=$add[showclass],showdt=$add[showdt],qaddgroupid='$add[qaddgroupid]',qaddshowkey=$add[qaddshowkey],adminqinfo=$add[adminqinfo],doctime=$add[doctime],classpagekey='$add[classpagekey]',dtlisttempid='$add[dtlisttempid]',classtempid='$add[classtempid]',nreclass=$add[nreclass],nreinfo=$add[nreinfo],nrejs=$add[nrejs],nottobq=$add[nottobq],ipath='$add[ipath]',addreinfo=$add[addreinfo],haddlist=$add[haddlist],sametitle=$add[sametitle],definfovoteid=$add[definfovoteid],wburl='$add[wburl]',qeditchecked=$add[qeditchecked],openadd=$add[openadd],wapstyleid='$add[wapstyleid]',repreinfo='$add[repreinfo]',pltempid='$add[pltempid]',cgroupid='$add[cgroupid]'".$change." where classid='$add[classid]'");
	//副表
	$empire->query("update {$dbtbpre}enewsclassadd set classtext='".addslashes($add[classtext])."' where classid='$add[classid]'");
	GetClass();
	//删除缓存文件
	if($add[oldclassname]<>$add[classname]||$add[bclassid]<>$add[oldbclassid])
	{
		DelListEnews();
    }
	//来源
	if($add['from'])
	{
		$returnurl="ListPageClass.php";
	}
	else
	{
		$returnurl="ListClass.php";
	}
	if($sql)
	{
		insert_dolog("classid=".$add[classid]."<br>classname=".$add[classname]);//操作日志
		printerror("EditClassSuccess",$returnurl);
	}
	else
	{
		printerror("DbError","history.go(-1)");
	}
}

//修改栏目
function EditClass($add,$userid,$username){
	global $empire,$class_r,$dbtbpre;
	//修改外部栏目
	if($add[ecmsclasstype])
	{
		EditWbClass($add,$userid,$username);
	}
	$add[classid]=(int)$add[classid];
	$add[classpath]=trim($add[classpath]);
	$checkclasspath=$add['classpath'];
	if($add['oldclasspath']<>$add['pripath'].$add['oldcpath'])//更换父栏目
	{
		$add[classpath]=$add['oldcpath'];
	}
	if(!$add[classname]||!$add[classpath]||!$add[modid]||!$add[classid]){
		printerror("EmptyClass","");
	}
	if($add[islast]&&(!$add[newstempid]||!$add[listtempid])){
		printerror("LastMustChange","");
	}
	//操作权限
	CheckLevel($userid,$username,$classid,"class");
	$add=DoPostClassVar($add);
	//改变目录
	$classpath=$add[pripath].$add[classpath];
	if($add[oldclasspath]<>$classpath&&$checkclasspath==$add['oldcpath']){
		if(file_exists("../../".$classpath)){//检测目录是否存在
			printerror("ReClasspath","");
		}
    }
	//取得表名
	$tabler=GetModTable($add[modid]);
	$tabler[tid]=(int)$tabler[tid];
	//修改大栏目
	if(!$add[islast]){
		//改变大栏目
		if($add[bclassid]<>$add[oldbclassid]){
			//转到主栏目
			if(empty($add[bclassid])){
				$sonclass="";
				$featherclass="";
				//取得本栏目的子栏目
				$r=$empire->fetch1("select sonclass,featherclass,classpath from {$dbtbpre}enewsclass where classid='$add[classid]'");
				//改变父栏目的子栏目
				$where=ReturnClass($r[featherclass]);
				if(empty($where)){
					$where="classid=0";
				}
				$osql=$empire->query("select sonclass,classid from {$dbtbpre}enewsclass where ".$where);
				while($o=$empire->fetch($osql)){
					$newsonclass=str_replace($r[sonclass],"|",$o[sonclass]);
					$uosql=$empire->query("update {$dbtbpre}enewsclass set sonclass='$newsonclass' where classid='$o[classid]'");
				}
				//修改子栏目的父栏目
				$osql=$empire->query("select featherclass,classid,classpath from {$dbtbpre}enewsclass where featherclass like '%|".$add[classid]."%|'");
				while($o=$empire->fetch($osql)){
					$newclasspath=str_replace($r[classpath]."/",$classpath."/",$o[classpath]);
					$newfeatherclass=str_replace($r[featherclass],"|",$o[featherclass]);
					$uosql=$empire->query("update {$dbtbpre}enewsclass set featherclass='$newfeatherclass',classpath='$newclasspath' where classid='$o[classid]'");
				}
			}
			//转到中级栏目
			else
			{
				//大栏目跟原栏目相同
				if($add[classid]==$add[bclassid]){
				  printerror("BclassIsself","");
				}
				//取得现在大栏目的值
	 			$b=$empire->fetch1("select featherclass,sonclass,islast,wburl from {$dbtbpre}enewsclass where classid='$add[bclassid]'");
				//检测大栏目是否为终级栏目
				if($b[islast])
				{
					printerror("BclassNotLast","");
				}
				if($b[wburl])
				{
					printerror("BclassNotWb","");
				}
				//是否非法父栏目
				if($b[featherclass]){
					$c_nb_r=explode("|".$add[classid]."|",$b[featherclass]);
					if(count($c_nb_r)<>1){
						printerror("BclassIssmall","");
					}
				}
				if(empty($b[featherclass])){
					$b[featherclass]="|";
				}
				$featherclass=$b[featherclass].$add[bclassid]."|";
				//取得现在栏目本身的值
				$o=$empire->fetch1("select featherclass,sonclass,classpath from {$dbtbpre}enewsclass where classid='$add[classid]'");
				//修改子栏目的父栏目
				$osql=$empire->query("select featherclass,classid,classpath from {$dbtbpre}enewsclass where featherclass like '%|".$add[classid]."|%'");
				while($or=$empire->fetch($osql)){
					$newclasspath=str_replace($o[classpath]."/",$classpath."/",$or[classpath]);
					if(empty($o[featherclass])){
						$newfeatherclass=$b[featherclass].$add[bclassid].$or[featherclass];
					}
					else{
						$newfeatherclass=str_replace($o[featherclass],$featherclass,$or[featherclass]);
					}
					$uosql=$empire->query("update {$dbtbpre}enewsclass set featherclass='$newfeatherclass',classpath='$newclasspath' where classid='$or[classid]'");
				}
				//改变旧大栏目的所有子栏目
				$owhere=ReturnClass($o[featherclass]);
				if(empty($owhere)){
					$owhere="classid=0";
				}
				$oosql=$empire->query("select sonclass,classid from {$dbtbpre}enewsclass where ".$owhere);
				while($oo=$empire->fetch($oosql)){
					$newsonclass=str_replace($o[sonclass],"|",$oo[sonclass]);
					$usql=$empire->query("update {$dbtbpre}enewsclass set sonclass='$newsonclass' where classid='$oo[classid]'");
				}
				//改变新大栏目的子栏目
				$where=ReturnClass($featherclass);
				if(empty($where)){
					$where="classid=0";
				}
				$nbsql=$empire->query("select sonclass,classid from {$dbtbpre}enewsclass where ".$where);
				while($nb=$empire->fetch($nbsql)){
					if(empty($nb[sonclass]))
					{$nb[sonclass]="|";}
					$newsonclass=$nb[sonclass].substr($o[sonclass],1);
					$usql=$empire->query("update {$dbtbpre}enewsclass set sonclass='$newsonclass' where classid='$nb[classid]'");
				}
			}
			$change=",bclassid=$add[bclassid],featherclass='$featherclass'";
		}
		//绑定域名应用于子栏目
		if($add['UrlToSmall']){
			UpdateSmallClassDomain($add['classid'],$add['classurl'],$classpath);
		}
		//wap模板应用于子栏目
		if($add['wapstylesclass'])
		{
			$empire->query("update {$dbtbpre}enewsclass set wapstyleid='$add[wapstyleid]' where featherclass like '%|".$add[classid]."|%'");
		}
		//修改数据库资料
		$sql=$empire->query("update {$dbtbpre}enewsclass set classname='$add[classname]',classpath='$classpath',classtype='$add[classtype]',newline=$add[newline],hotline=$add[hotline],goodline=$add[goodline],classurl='$add[classurl]',groupid=$add[groupid],myorder=$add[myorder],filename_qz='$add[filename_qz]',hotplline=$add[hotplline],modid=$add[modid],checked=$add[checked],docheckuser=$add[docheckuser],checkuser='$add[checkuser]',firstline=$add[firstline],bname='$add[bname]',islist=$add[islist],listtempid=$add[listtempid],lencord=$add[lencord],searchtempid=$add[searchtempid],tid=$tabler[tid],tbname='$tabler[tbname]',maxnum=$add[maxnum],checkpl=$add[checkpl],down_num=$add[down_num],online_num=$add[online_num],listorderf='$add[listorderf]',listorder='$add[listorder]',reorderf='$add[reorderf]',reorder='$add[reorder]',intro='$add[intro]',classimg='$add[classimg]',jstempid=$add[jstempid],listdt=$add[listdt],showclass=$add[showclass],showdt=$add[showdt],qaddgroupid='$add[qaddgroupid]',qaddshowkey=$add[qaddshowkey],adminqinfo=$add[adminqinfo],doctime=$add[doctime],classpagekey='$add[classpagekey]',dtlisttempid='$add[dtlisttempid]',classtempid='$add[classtempid]',nreclass=$add[nreclass],nreinfo=$add[nreinfo],nrejs=$add[nrejs],nottobq=$add[nottobq],ipath='$add[ipath]',addreinfo=$add[addreinfo],haddlist=$add[haddlist],sametitle=$add[sametitle],definfovoteid=$add[definfovoteid],wburl='',qeditchecked=$add[qeditchecked],openadd=$add[openadd],wapstyleid='$add[wapstyleid]',repreinfo='$add[repreinfo]',pltempid='$add[pltempid]',cgroupid='$add[cgroupid]'".$change." where classid='$add[classid]'");
		//副表
		$empire->query("update {$dbtbpre}enewsclassadd set classtext='".addslashes($add[classtext])."' where classid='$add[classid]'");
		GetClass();
		//生成栏目文件
		if($add[islist]==0||$add[islist]==2)
		{
			$classtemp=$add[islist]==2?GetClassText($add[classid]):GetClassTemp($add['classtempid']);
			NewsBq($add[classid],$classtemp,0,1);
		}
	}
	//终级栏目
	else
	{
		//改变大栏目
		if($add[bclassid]<>$add[oldbclassid]){
			//转到主栏目
			if(empty($add[bclassid])){
				$sonclass="";
				$featherclass="";
				//取得栏目原本的大栏目
				$r=$empire->fetch1("select featherclass,classpath from {$dbtbpre}enewsclass where classid='$add[classid]'");
				//改变原本大栏目的子栏目
				$where=ReturnClass($r[featherclass]);
				if(empty($where)){
					$where="classid=0";
				}
				$bsql=$empire->query("select classid,sonclass from {$dbtbpre}enewsclass where ".$where);
				while($br=$empire->fetch($bsql)){
					$newsonclass=str_replace("|".$add[classid]."|","|",$br[sonclass]);
					$usql=$empire->query("update {$dbtbpre}enewsclass set sonclass='$newsonclass' where classid='$br[classid]'");
				}
			}
			//转到中级栏目
			else
			{
				//取得现在大栏目的值
				$b=$empire->fetch1("select featherclass,islast,wburl from {$dbtbpre}enewsclass where classid='$add[bclassid]'");
				//检测大栏目是否为终级栏目
				if($b[islast])
				{
					printerror("BclassNotLast","");
				}
				if($b[wburl])
				{
					printerror("BclassNotWb","");
				}
				if(empty($b[featherclass])){
					$b[featherclass]="|";
				}
				$featherclass=$b[featherclass].$add[bclassid]."|";
				//改变新大栏目的子栏目
				$where=ReturnClass($featherclass);
				if(empty($where)){
					$where="classid=0";
				}
				$bsql=$empire->query("select sonclass,classid from {$dbtbpre}enewsclass where ".$where);
				while($nb=$empire->fetch($bsql))
				{
					if(empty($nb[sonclass]))
					{$nb[sonclass]="|";}
					$newsonclass=$nb[sonclass].$add[classid]."|";
					$usql=$empire->query("update {$dbtbpre}enewsclass set sonclass='$newsonclass' where classid='$nb[classid]'");
				}
				//改变旧大栏目的子栏目
				$o=$empire->fetch1("select sonclass,featherclass from {$dbtbpre}enewsclass where classid='$add[classid]'");
				$where=ReturnClass($o[featherclass]);
				if(empty($where)){
					$where="classid=0";
				}
				$osql=$empire->query("select sonclass,classid from {$dbtbpre}enewsclass where ".$where);
				while($ob=$empire->fetch($osql)){
				   $newsonclass=str_replace("|".$add[classid]."|","|",$ob[sonclass]);
				   $usql=$empire->query("update {$dbtbpre}enewsclass set sonclass='$newsonclass' where classid='$ob[classid]'");
			   }
			}
			$change=",bclassid=$add[bclassid],featherclass='$featherclass'";
		}
		//应用于已生成的信息
		if($add['tobetempinfo']){
			$upsql=$empire->query("update {$dbtbpre}ecms_".$tabler[tbname]." set newstempid=$add[newstempid] where classid='$add[classid]'");
		}
		//文件前缀
	    $add[filename_qz]=RepFilenameQz($add[filename_qz]);
		$sql=$empire->query("update {$dbtbpre}enewsclass set classname='$add[classname]',classpath='$classpath',classtype='$add[classtype]',link_num=$add[link_num],lencord=$add[lencord],newstempid=$add[newstempid],listtempid=$add[listtempid],newspath='$add[newspath]',filename=$add[filename],filetype='$add[filetype]',openpl=$add[openpl],openadd=$add[openadd],newline=$add[newline],hotline=$add[hotline],goodline=$add[goodline],classurl='$add[classurl]',groupid=$add[groupid],myorder=$add[myorder],filename_qz='$add[filename_qz]',hotplline=$add[hotplline],modid=$add[modid],checked=$add[checked],docheckuser=$add[docheckuser],checkuser='$add[checkuser]',firstline=$add[firstline],bname='$add[bname]',searchtempid=$add[searchtempid],tid=$tabler[tid],tbname='$tabler[tbname]',maxnum=$add[maxnum],checkpl=$add[checkpl],down_num=$add[down_num],online_num=$add[online_num],listorderf='$add[listorderf]',listorder='$add[listorder]',reorderf='$add[reorderf]',reorder='$add[reorder]',intro='$add[intro]',classimg='$add[classimg]',jstempid=$add[jstempid],addinfofen=$add[addinfofen],listdt=$add[listdt],showclass=$add[showclass],showdt=$add[showdt],checkqadd=$add[checkqadd],qaddlist=$add[qaddlist],qaddgroupid='$add[qaddgroupid]',qaddshowkey=$add[qaddshowkey],adminqinfo=$add[adminqinfo],doctime=$add[doctime],classpagekey='$add[classpagekey]',dtlisttempid='$add[dtlisttempid]',classtempid='$add[classtempid]',nreclass=$add[nreclass],nreinfo=$add[nreinfo],nrejs=$add[nrejs],nottobq=$add[nottobq],ipath='$add[ipath]',addreinfo=$add[addreinfo],haddlist=$add[haddlist],sametitle=$add[sametitle],definfovoteid=$add[definfovoteid],wburl='',qeditchecked=$add[qeditchecked],wapstyleid='$add[wapstyleid]',repreinfo='$add[repreinfo]',pltempid='$add[pltempid]',cgroupid='$add[cgroupid]'".$change." where classid='$add[classid]'");
		//副表
		$empire->query("update {$dbtbpre}enewsclassadd set classtext='".addslashes($add[classtext])."' where classid='$add[classid]'");
		GetClass();
	}
	//移动目录
	if($add[bclassid]<>$add[oldbclassid]){
		$opath="../../".$add[oldclasspath];
		$newpath="../../".$classpath;
		MovePath($opath,$newpath);
		$opath="../../d/file/".$add[oldclasspath];
		$npath="../../d/file/".$classpath;
		CopyPath($opath,$npath);
    }
	else{
		if($add['oldcpath']<>$add['classpath'])//更换栏目目录
		{
			AlterClassPath($add['classid'],$add['islast'],$add['oldclasspath'],$classpath);
			GetClass();
		}
	}
	//删除缓存文件
	if($add[oldclassname]<>$add[classname]||$add[bclassid]<>$add[oldbclassid]){
		DelListEnews();
		GetSearch($add[modid]);
    }
	else{
		if($add[openadd]<>$add[oldopenadd]||$add[modid]<>$add[oldmodid]){
			GetSearch($add[modid]);
			if($add[modid]<>$add[oldmodid]){
				GetSearch($add[oldmodid]);
			}
		}
	}
	//修改栏目扩展名
	if($add[oldclasstype]<>$add[classtype]){
		$todaytime=date("Y-m-d H:i:s");
		if($add[islast]){
			$query="select count(*) as total from {$dbtbpre}ecms_".$class_r[$add[classid]][tbname]." where checked=1 and classid='$add[classid]'";
			$lencord=$add[oldlencord];
			$num=$empire->gettotal($query);
		}
		else{
			$lencord=$add[oldlencord];
			if($add[oldislist]==1){
				$where=ReturnClass($class_r[$add[classid]][sonclass]);
				$query="select count(*) as total from {$dbtbpre}ecms_".$class_r[$add[classid]][tbname]." where checked=1 and (".$where.")";
				$num=$empire->gettotal($query);
			}
			else
			{
				$num=1;
			}
		}
		RenameListfile($add[classid],$lencord,$num,$add[oldclasstype],$add[classtype],$classpath);
	}
	//来源
	if($add['from']){
		$returnurl="ListPageClass.php";
	}
	else{
		$returnurl="ListClass.php";
	}
	TogNotReClass(1);
	if($sql){
		insert_dolog("classid=".$add[classid]."<br>classname=".$add[classname]);//操作日志
		printerror("EditClassSuccess",$returnurl);
	}
	else{
		printerror("DbError","history.go(-1)");
	}
}

//终极栏目与非终极栏目之间的转换
function ChangeClassIslast($reclassid,$userid,$username){
	global $empire,$dbtbpre;
	//操作权限
	CheckLevel($userid,$username,$classid,"class");
	$count=count($reclassid);
	$classid=(int)$reclassid[0];
	if($count==0||!$classid)
	{
		printerror("NotChangeIslastClassid","");
	}
	//取得本栏目信息
	$r=$empire->fetch1("select classid,sonclass,featherclass,islist,islast,classname,modid,tbname,wburl from {$dbtbpre}enewsclass where classid=$classid");
	if(empty($r[classid]))
	{
		printerror("NotChangeIslastClassid","");
	}
	if($r[wburl])
	{
		printerror("NotChangeWbClassid","");
	}
	//非终极栏目
	if(!$r[islast])
	{
		$num=$empire->gettotal("select count(*) as total from {$dbtbpre}enewsclass where bclassid=$classid");
		if($num)
		{
			printerror("LastTheClassHaveSonclass","history.go(-1)");
		}
		//修改父栏目的子栏目
		$where=ReturnClass($r[featherclass]);
		if(empty($where))
		{
			$where="classid=0";
		}
		$sql=$empire->query("select classid,sonclass from {$dbtbpre}enewsclass where ".$where);
		while($br=$empire->fetch($sql))
		{
			if(empty($br[sonclass]))
			{
				$br[sonclass]="|";
			}
			$newsonclass=$br[sonclass].$classid."|";
			$usql=$empire->query("update {$dbtbpre}enewsclass set sonclass='$newsonclass' where classid=$br[classid]");
		}
		$dosql=$empire->query("update {$dbtbpre}enewsclass set islast=1 where classid=$classid");
		$mess="ChangeClassToLastSuccess";
	}
	//终极栏目
	else
	{
		$num=$empire->gettotal("select count(*) as total from {$dbtbpre}ecms_".$r[tbname]." where classid=$classid");
		if($num)
		{
			printerror("LastTheClassHaveInfo","history.go(-1)");
		}
		//修改父栏目的子栏目
		$where=ReturnClass($r[featherclass]);
		if(empty($where))
		{
			$where="classid=0";
		}
		$sql=$empire->query("select classid,sonclass from {$dbtbpre}enewsclass where ".$where);
		while($br=$empire->fetch($sql))
		{
			if(empty($br[sonclass]))
			{
				$br[sonclass]="|";
			}
			$newsonclass=str_replace("|".$classid."|","|",$br[sonclass]);
			$usql=$empire->query("update {$dbtbpre}enewsclass set sonclass='$newsonclass' where classid=$br[classid]");
		}
		$dosql=$empire->query("update {$dbtbpre}enewsclass set islast=0 where classid=$classid");
		$mess="ChangeClassToNolastSuccess";
	}
	//删除缓存文件
	DelListEnews();
	//更新缓存
	GetClass();
	GetSearch($r[modid]);
	if($dosql)
	{
		//操作日志
		insert_dolog("classid=".$classid."<br>classname=".$r[classname]);
		printerror($mess,$_SERVER['HTTP_REFERER']);
	}
	else
	{
		printerror("DbError","history.go(-1)");
	}
}

//删除栏目
function DelClass($classid,$userid,$username){
	global $empire,$dbtbpre;
	$classid=(int)$classid;
	if(!$classid)
	{
		printerror("NotDelClassid","");
	}
	//操作权限
	CheckLevel($userid,$username,$classid,"class");
	$r=$empire->fetch1("select * from {$dbtbpre}enewsclass where classid='$classid'");
	if(empty($r[classid]))
	{
		printerror("NotClassid","history.go(-1)");
	}
    DelClass1($classid);
    GetClass();
	GetSearch($r[modid]);
	//返回地址
	if($_GET['from'])
	{$returnurl="ListPageClass.php";}
	else
	{$returnurl="ListClass.php";}
	TogNotReClass(1);
	insert_dolog("classid=".$classid."<br>classname=".$r[classname]);//操作日志
	printerror("DelClassSuccess",$returnurl);
}

//删除栏目,不返回值
function DelClass1($classid){
	global $empire,$class_r,$dbtbpre;
	$r=$empire->fetch1("select * from {$dbtbpre}enewsclass where classid='$classid'");
	//外部栏目
	if($r[wburl])
	{
		$sql=$empire->query("delete from {$dbtbpre}enewsclass where classid='$classid'");
		$empire->query("delete from {$dbtbpre}enewsclassadd where classid='$classid'");
		//删除缓存
		DelListEnews();
		return "";
	}
	//删除终极栏目
	if($r[islast])
	{
		//删除附件
		DelClassTranFile($classid);
		$filepath="../../d/file/".$r[classpath];
		$delf=DelPath($filepath);
		$usql=$empire->query("delete from {$dbtbpre}enewsfile where classid='$classid'");
		//删除信息
		$sql=$empire->query("delete from {$dbtbpre}ecms_".$r[tbname]." where classid='$classid'");
		DelClassTbDataInfo($classid);
		//删除信息附加表
		$delsql=$empire->query("delete from {$dbtbpre}enewsqf where classid='$classid'");
		$delsql=$empire->query("delete from {$dbtbpre}enewsinfovote where classid='$classid'");
		$delsql=$empire->query("delete from {$dbtbpre}enewsdiggips where classid='$classid'");
		//删除栏目本身
	    $sql1=$empire->query("delete from {$dbtbpre}enewsclass where classid='$classid'");
		$empire->query("delete from {$dbtbpre}enewsclassadd where classid='$classid'");
		$delpath="../../".$r[classpath];
		$del=DelPath($delpath);
		//更新大栏目的子栏目
		$where=ReturnClass($r[featherclass]);
	    if(empty($where))
		{$where="classid=0";}
	    $bsql=$empire->query("select sonclass,classid from {$dbtbpre}enewsclass where ".$where);
		while($br=$empire->fetch($bsql))
		{
			$newsonclass=str_replace("|".$classid."|","|",$br[sonclass]);
			$usql=$empire->query("update {$dbtbpre}enewsclass set sonclass='$newsonclass' where classid='$br[classid]'");
		}
	}
	//删除大栏目
	else
	{
	    //删除栏目
		$where=ReturnClass($r[sonclass]);
		if(empty($where))
		{$where="classid=0";}
		$delcr=explode("|",$r[sonclass]);
		$count=count($delcr);
		for($i=1;$i<$count-1;$i++)
		{
			$delcid=$delcr[$i];
			//删除附件
			DelClassTranFile($delcid);
			$sql=$empire->query("delete from {$dbtbpre}ecms_".$class_r[$delcid][tbname]." where classid='$delcid'");
			DelClassTbDataInfo($delcid);
			//删除信息附加表
			$delsql=$empire->query("delete from {$dbtbpre}enewsqf where classid='$delcid'");
			$delsql=$empire->query("delete from {$dbtbpre}enewsinfovote where classid='$delcid'");
			$delsql=$empire->query("delete from {$dbtbpre}enewsdiggips where classid='$delcid'");
		}
		//删除附件
		$filepath="../../d/file/".$r[classpath];
	    $delf=DelPath($filepath);
		if($where<>'classid=0')
		{
			$usql=$empire->query("delete from {$dbtbpre}enewsfile where ".$where);
		}
		//删除子栏目副表
		$fcsql=$empire->query("select classid from {$dbtbpre}enewsclass where featherclass like '%|".$classid."|%'");
		while($fcr=$empire->fetch($fcsql))
		{
			$empire->query("delete from {$dbtbpre}enewsclassadd where classid='$fcr[classid]'");
		}
		//删除子栏目
		$sql1=$empire->query("delete from {$dbtbpre}enewsclass where featherclass like '%|".$classid."|%'");
		//改变父栏目的子类
		$where=ReturnClass($r[featherclass]);
		if(empty($where))
		{$where="classid=0";}
		$bbsql=$empire->query("select classid,sonclass from {$dbtbpre}enewsclass where ".$where);
		while($bbr=$empire->fetch($bbsql))
		{
			$newsonclass=str_replace($r[sonclass],"|",$bbr[sonclass]);
			$usql=$empire->query("update {$dbtbpre}enewsclass set sonclass='$newsonclass' where classid='$bbr[classid]'");
		}
		//删除栏目本身
		$sql2=$empire->query("delete from {$dbtbpre}enewsclass where classid='$classid'");
		$empire->query("delete from {$dbtbpre}enewsclassadd where classid='$classid'");
		$delpath="../../".$r[classpath];
		$del=DelPath($delpath);
	}
	//删除缓存
	DelListEnews();
}

//删除栏目附件
function DelClassTranFile($classid){
	global $empire,$class_r,$dbtbpre,$emod_r;
	//删除存文本
	$mid=$class_r[$classid][modid];
	$savetxtf=$emod_r[$mid]['savetxtf'];
	if($savetxtf)
	{
		$txtsql=$empire->query("select ".$savetxtf." from {$dbtbpre}ecms_".$class_r[$classid][tbname]." where classid='$classid'");
		while($txtr=$empire->fetch($txtsql))
		{
			$newstextfile=$txtr[$savetxtf];
			$txtr[$savetxtf]=GetTxtFieldText($txtr[$savetxtf]);
			DelTxtFieldText($newstextfile);//删除文件
		}
	}
	//删除附件
	$filesql=$empire->query("select id from {$dbtbpre}ecms_".$class_r[$classid][tbname]." where classid='$classid'");
	while($filer=$empire->fetch($filesql))
	{
		DelNewsTheFile($filer[id],$classid);//删除附件
	}
}

//删除栏目副表信息
function DelClassTbDataInfo($classid){
	global $empire,$class_r,$dbtbpre,$emod_r;
	$mid=$class_r[$classid][modid];
	$tbname=$class_r[$classid][tbname];
	$dtbr=explode(',',$emod_r[$mid][datatbs]);
	$tbcount=count($dtbr);
	for($i=1;$i<$tbcount-1;$i++)
	{
		$empire->query("delete from {$dbtbpre}ecms_".$tbname."_data_".$dtbr[$i]." where classid='$classid'");
	}
}

//修改栏目顺序
function EditClassOrder($classid,$myorder,$userid,$username){
	global $empire,$dbtbpre;
	//验证权限
	CheckLevel($userid,$username,$classid,"class");
	for($i=0;$i<count($classid);$i++)
	{
		$newmyorder=(int)$myorder[$i];
		$sql=$empire->query("update {$dbtbpre}enewsclass set myorder=$newmyorder where classid='$classid[$i]'");
    }
	//删除缓存
	DelListEnews();
	//操作日志
	insert_dolog("");
	printerror("EditClassOrderSuccess",$_SERVER['HTTP_REFERER']);
}

//更新栏目关系
function ChangeSonclass($start,$userid,$username){
	global $empire,$public_r,$fun_r,$dbtbpre;
	//验证权限
	CheckLevel($userid,$username,$classid,"changedata");
	$start=(int)$start;
	$b=0;
	$sql=$empire->query("select classid from {$dbtbpre}enewsclass where islast=0 and classid>".$start." order by classid limit ".$public_r[relistnum]);
	while($r=$empire->fetch($sql))
	{
		$b=1;
		$newstart=$r[classid];
		//子栏目
		$sonclass="|";
		$ssql=$empire->query("select classid from {$dbtbpre}enewsclass where islast=1 and featherclass like '%|".$r[classid]."|%' order by classid");
		while($sr=$empire->fetch($ssql))
		{
			$sonclass.=$sr[classid]."|";
	    }
		$usql=$empire->query("update {$dbtbpre}enewsclass set sonclass='$sonclass' where classid='$r[classid]'");
    }
	//完毕
	if(empty($b))
	{
		GetClass();
		printerror("ChangeSonclassSuccess","ReHtml/ChangeData.php");
	}
	echo $fun_r['OneChangeSonclassSuccess']."(ID:<font color=red><b>".$newstart."</b></font>)<script>self.location.href='ecmsclass.php?enews=ChangeSonclass&start=$newstart';</script>";
	exit();
}

//删除栏目缓存文件
function DelFcListClass(){
	DelListEnews();
	//操作日志
	insert_dolog("");
	printerror("DelListEnewsSuccess","history.go(-1)");
}

//批量设置栏目
function SetMoreClass($add,$userid,$username){
	global $empire,$dbtbpre;
	//验证权限
	CheckLevel($userid,$username,$classid,"setmclass");
	//栏目
	$classid=$add['classid'];
	$count=count($classid);
	if($count==0)
	{
		printerror("NotChangeSetClass","");
	}
	$cids='';
	$dh='';
	for($i=0;$i<$count;$i++)
	{
		$cids.=$dh.intval($classid[$i]);
		$dh=',';
	}
	$whereclass='classid in ('.$cids.')';
	$seting='';
	//基本属性
	if($add['doclasstype'])
	{
		$seting.=",classtype='$add[classtype]'";
	}
	if($add['dolisttempid']&&$add[listtempid])
	{
		$seting.=",listtempid='$add[listtempid]'";
	}
	if($add['dodtlisttempid'])
	{
		$seting.=",dtlisttempid='$add[dtlisttempid]'";
	}
	if($add['domaxnum'])
	{
		$seting.=",maxnum='$add[maxnum]'";
	}
	if($add['dolencord'])
	{
		$seting.=",lencord='$add[lencord]'";
	}
	if($add['dosearchtempid'])
	{
		$seting.=",searchtempid='$add[searchtempid]'";
	}
	if($add['dowapstyleid'])
	{
		$seting.=",wapstyleid='$add[wapstyleid]'";
	}
	if($add['dolistorder'])
	{
		$seting.=",listorderf='$add[listorderf]',listorder='$add[listorder]'";
	}
	if($add['doreorder'])
	{
		$seting.=",reorderf='$add[reorderf]',reorder='$add[reorder]'";
	}
	if($add['dolistdt'])
	{
		$seting.=",listdt='$add[listdt]'";
	}
	if($add['doshowdt'])
	{
		$seting.=",showdt='$add[showdt]'";
	}
	if($add['doshowclass'])
	{
		$seting.=",showclass='$add[showclass]'";
	}
	if($add['doopenadd'])
	{
		$seting.=",openadd='$add[openadd]'";
	}
	//选项设置[大栏目]
	if($add['doclasstempid'])
	{
		$seting.=",classtempid='$add[classtempid]'";
	}
	if($add['doislist'])
	{
		$seting.=",islist='$add[islist]'";
	}
	//选项设置[终极栏目]
	if($add['donewstempid']&&$add[newstempid])
	{
		$seting.=",newstempid='$add[newstempid]'";
		if($add['tobetempinfo'])
		{
			$donewstemp=1;
		}
	}
	if($add['dopltempid'])
	{
		$seting.=",pltempid='$add[pltempid]'";
	}
	if($add['dolink_num'])
	{
		$seting.=",link_num='$add[link_num]'";
	}
	if($add['doinfopath'])
	{
		if($add['infopath']==0)
		{
			$add['ipath']='';
		}
		$seting.=",ipath='$add[ipath]'";
	}
	if($add['donewspath'])
	{
		$seting.=",newspath='$add[newspath]'";
	}
	if($add['dofilename_qz'])
	{
		$seting.=",filename_qz='$add[filename_qz]'";
	}
	if($add['dofilename'])
	{
		$seting.=",filename='$add[filename]'";
	}
	if($add['dofiletype'])
	{
		$seting.=",filetype='$add[filetype]'";
	}
	if($add['doopenpl'])
	{
		$seting.=",openpl='$add[openpl]'";
	}
	if($add['docheckpl'])
	{
		$seting.=",checkpl='$add[checkpl]'";
	}
	if($add['doqaddshowkey'])
	{
		$seting.=",qaddshowkey='$add[qaddshowkey]'";
	}
	if($add['docheckqadd'])
	{
		$seting.=",checkqadd='$add[checkqadd]'";
	}
	if($add['doqaddgroupid'])
	{
		$add[qaddgroupid]=DoPostClassQAddGroupid($add[qaddgroupidck]);
		$seting.=",qaddgroupid='$add[qaddgroupid]'";
	}
	if($add['doqaddlist'])
	{
		$seting.=",qaddlist='$add[qaddlist]'";
	}
	if($add['doaddinfofen'])
	{
		$seting.=",addinfofen='$add[addinfofen]'";
	}
	if($add['doadminqinfo'])
	{
		$seting.=",adminqinfo='$add[adminqinfo]'";
	}
	if($add['doqeditchecked'])
	{
		$seting.=",qeditchecked='$add[qeditchecked]'";
	}
	if($add['doaddreinfo'])
	{
		$seting.=",addreinfo='$add[addreinfo]'";
	}
	if($add['dohaddlist'])
	{
		$seting.=",haddlist='$add[haddlist]'";
	}
	if($add['dosametitle'])
	{
		$seting.=",sametitle='$add[sametitle]'";
	}
	if($add['dochecked'])
	{
		$seting.=",checked='$add[checked]'";
	}
	if($add['dorepreinfo'])
	{
		$seting.=",repreinfo='$add[repreinfo]'";
	}
	if($add['dodefinfovoteid'])
	{
		$seting.=",definfovoteid='$add[definfovoteid]'";
	}
	if($add['dogroupid'])
	{
		$seting.=",groupid='$add[groupid]'";
	}
	if($add['dodoctime'])
	{
		$seting.=",doctime='$add[doctime]'";
	}
	//特殊模型设置
	if($add['dodown_num'])
	{
		$seting.=",down_num='$add[down_num]'";
	}
	if($add['doonline_num'])
	{
		$seting.=",online_num='$add[online_num]'";
	}
	//JS调用设置
	if($add['dojstempid'])
	{
		$seting.=",jstempid='$add[jstempid]'";
	}
	if($add['donewjs'])
	{
		$seting.=",newline='$add[newline]'";
	}
	if($add['dohotjs'])
	{
		$seting.=",hotline='$add[hotline]'";
	}
	if($add['dogoodjs'])
	{
		$seting.=",goodline='$add[goodline]'";
	}
	if($add['dohotpljs'])
	{
		$seting.=",hotplline='$add[hotplline]'";
	}
	if($add['dofirstjs'])
	{
		$seting.=",firstline='$add[firstline]'";
	}
	if(empty($seting))
	{
		printerror("NotChangeSetClassInfo","");
	}
	$seting=substr($seting,1);
	$sql=$empire->query("update {$dbtbpre}enewsclass set ".$seting." where ".$whereclass);
	//内容模板应用于子生成的信息
	if($donewstemp==1)
	{
		$csql=$empire->query("select classid,tbname from {$dbtbpre}enewsclass where (".$whereclass.") and islast=1");
		while($r=$empire->fetch($csql))
		{
			$upsql=$empire->query("update {$dbtbpre}ecms_".$r[tbname]." set newstempid='$add[newstempid]' where classid='$r[classid]'");
		}
	}
	if($sql)
	{
		GetClass();
		//操作日志
		insert_dolog("");
		printerror("SetMoreClassSuccess","SetMoreClass.php");
	}
	else
	{printerror("DbError","");}
}
?>