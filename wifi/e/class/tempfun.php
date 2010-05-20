<?php
//修改留言板模板
function EditGbooktemp($temptext,$userid,$username){
	global $empire,$public_r,$dbtbpre;
	if(!$temptext)
	{printerror("EmptyTemptext","history.go(-1)");}
	//验证权限
	CheckLevel($userid,$username,$classid,"template");
	$temptext=RepPhpAspJspcode($temptext);
	$gid=(int)$_POST['gid'];
	$sql=$empire->query("update ".GetDoTemptb("enewspubtemp",$gid)." set gbooktemp='".addslashes($temptext)."' limit 1");
	if($gid==$public_r['deftempid']||(!$public_r['deftempid']&&($gid==1||$gid==0)))
	{
		ReGbooktemp();
	}
	if($sql)
	{
		//操作日志
		insert_dolog("gid=$gid");
		printerror("EditGbooktempSuccess","template/EditPublicTemp.php?tname=gbooktemp&gid=$gid#gbooktemp");
	}
	else
	{printerror("DbError","history.go(-1)");}
}

//修改控制面板模板
function EditCptemp($temptext,$userid,$username){
	global $empire,$public_r,$dbtbpre;
	if(!$temptext)
	{printerror("EmptyTemptext","history.go(-1)");}
	//验证权限
	CheckLevel($userid,$username,$classid,"template");
	$temptext=RepPhpAspJspcode($temptext);
	$gid=(int)$_POST['gid'];
	$sql=$empire->query("update ".GetDoTemptb("enewspubtemp",$gid)." set cptemp='".addslashes($temptext)."' limit 1");
	if($gid==$public_r['deftempid']||(!$public_r['deftempid']&&($gid==1||$gid==0)))
	{
		ReCptemp();
	}
	if($sql)
	{
		//操作日志
		insert_dolog("gid=$gid");
		printerror("EditCptempSuccess","template/EditPublicTemp.php?tname=cptemp&gid=$gid#cptemp");
	}
	else
	{printerror("DbError","history.go(-1)");}
}

//修改登陆状态模板
function EditLoginIframe($temptext,$userid,$username){
	global $empire,$public_r,$dbtbpre;
	if(!$temptext)
	{printerror("EmptyTemptext","history.go(-1)");}
	//验证权限
	CheckLevel($userid,$username,$classid,"template");
	$temptext=RepPhpAspJspcode($temptext);
	$gid=(int)$_POST['gid'];
	$sql=$empire->query("update ".GetDoTemptb("enewspubtemp",$gid)." set loginiframe='".addslashes($temptext)."' limit 1");
	if($gid==$public_r['deftempid']||(!$public_r['deftempid']&&($gid==1||$gid==0)))
	{
		ReLoginIframe();
	}
	if($sql)
	{
		//操作日志
		insert_dolog("gid=$gid");
		printerror("EditLoginIframeSuccess","template/EditPublicTemp.php?tname=loginiframe&gid=$gid#loginiframe");
	}
	else
	{printerror("DbError","history.go(-1)");}
}

//修改JS调用登陆状态模板
function EditLoginJstemp($temptext,$userid,$username){
	global $empire,$public_r,$dbtbpre;
	if(!$temptext)
	{printerror("EmptyTemptext","history.go(-1)");}
	//验证权限
	CheckLevel($userid,$username,$classid,"template");
	$temptext=RepPhpAspJspcode($temptext);
	$gid=(int)$_POST['gid'];
	$sql=$empire->query("update ".GetDoTemptb("enewspubtemp",$gid)." set loginjstemp='".addslashes($temptext)."' limit 1");
	if($gid==$public_r['deftempid']||(!$public_r['deftempid']&&($gid==1||$gid==0)))
	{
		ReLoginIframe();
	}
	if($sql)
	{
		//操作日志
		insert_dolog("gid=$gid");
		printerror("EditLoginJstempSuccess","template/EditPublicTemp.php?tname=loginjstemp&gid=$gid#loginjstemp");
	}
	else
	{printerror("DbError","history.go(-1)");}
}

//修改全站搜索模板
function EditSchallTemp($temptext,$sub,$formatdate,$userid,$username){
	global $empire,$public_r,$dbtbpre;
	if(!$temptext)
	{printerror("EmptyTemptext","history.go(-1)");}
	//验证权限
	CheckLevel($userid,$username,$classid,"template");
	$temptext=RepPhpAspJspcode($temptext);
	$gid=(int)$_POST['gid'];
	$sub=(int)$sub;
	$sql=$empire->query("update ".GetDoTemptb("enewspubtemp",$gid)." set schalltemp='".addslashes($temptext)."',schallsubnum='$sub',schalldate='".addslashes($formatdate)."' limit 1");
	if($gid==$public_r['deftempid']||(!$public_r['deftempid']&&($gid==1||$gid==0)))
	{
		ReSchAlltemp();
	}
	if($sql)
	{
		//操作日志
		insert_dolog("gid=$gid");
		printerror("EditSchallTempSuccess","template/EditPublicTemp.php?tname=schalltemp&gid=$gid#schalltemp");
	}
	else
	{printerror("DbError","history.go(-1)");}
}

//增加标签
function AddBq($add,$bqsay,$userid,$username){
	global $empire,$dbtbpre;
	if(!$add[bqname]||!$add[funname]||!$add[bq])
	{printerror("EmptyBqname","history.go(-1)");}
	//验证权限
	CheckLevel($userid,$username,$classid,"bq");
	//标签重复
	$num=$empire->gettotal("select count(*) as total from {$dbtbpre}enewsbq where bq='$add[bq]' limit 1");
	if($num)
	{printerror("ReBq","history.go(-1)");}
	//函数是否存在
	if(!function_exists($add[funname]))
	{
		printerror("NotFun","history.go(-1)");
    }
	$classid=(int)$add['classid'];
	$add[isclose]=(int)$add[isclose];
	$myorder=(int)$add[myorder];
	$bqsay=RepPhpAspJspcodeText($bqsay);
	$sql=$empire->query("insert into {$dbtbpre}enewsbq(bqname,bqsay,funname,bq,issys,bqgs,isclose,classid,myorder) values('".$add[bqname]."','".addslashes($bqsay)."','$add[funname]','$add[bq]',0,'".addslashes($add[bqgs])."',$add[isclose],$classid,'$myorder');");
	$bqid=$empire->lastid();
	if($sql)
	{
		//操作日志
		insert_dolog("bqid=".$bqid."<br>bqname=".$add[bqname]);
		printerror("AddBqSuccess","template/AddBq.php?enews=AddBq");
	}
	else
	{printerror("DbError","history.go(-1)");}
}

//修改标签
function EditBq($add,$bqsay,$userid,$username){
	global $empire,$dbtbpre;
	$add[bqid]=(int)$add[bqid];
	if(!$add[bqname]||!$add[funname]||!$add[bq]||!$add[bqid])
	{printerror("EmptyBqname","history.go(-1)");}
	//验证权限
	CheckLevel($userid,$username,$classid,"bq");
	//标签重复
	$num=$empire->gettotal("select count(*) as total from {$dbtbpre}enewsbq where bq='$add[bq]' and bqid<>'$add[bqid]' limit 1");
	if($num)
	{printerror("ReBq","history.go(-1)");}
	//函数是否存在
	if(!function_exists($add[funname]))
	{
		printerror("NotFun","history.go(-1)");
    }
	$bqsay=RepPhpAspJspcodeText($bqsay);
	$classid=(int)$add['classid'];
	$add[isclose]=(int)$add[isclose];
	$myorder=(int)$add[myorder];
	$sql=$empire->query("update {$dbtbpre}enewsbq set bqname='$add[bqname]',bqsay='".addslashes($bqsay)."',funname='$add[funname]',bq='$add[bq]',bqgs='".addslashes($add[bqgs])."',isclose=$add[isclose],classid=$classid,myorder='$myorder' where bqid='$add[bqid]'");
	if($sql)
	{
		//操作日志
	    insert_dolog("bqid=".$add[bqid]."<br>bqname=".$add[bqname]);
		printerror("EditBqSuccess","template/ListBq.php?classid=$add[cid]");
	}
	else
	{printerror("DbError","history.go(-1)");}
}

//删除标签
function DelBq($bqid,$cid,$userid,$username){
	global $empire,$dbtbpre;
	$bqid=(int)$bqid;
	if(empty($bqid))
	{printerror("NotDelBqid","history.go(-1)");}
	//验证权限
	CheckLevel($userid,$username,$classid,"bq");
	$num=$empire->gettotal("select count(*) as total from {$dbtbpre}enewsbq where bqid='$bqid' and issys=0");
	if(empty($num))
	{printerror("NotDelSysBq","history.go(-1)");}
	$r=$empire->fetch1("select bqname from {$dbtbpre}enewsbq where bqid='$bqid'");
	$sql=$empire->query("delete from {$dbtbpre}enewsbq where bqid='$bqid'");
	if($sql)
	{
		//操作日志
	    insert_dolog("bqid=".$bqid."<br>bqname=".$r[bqname]);
		printerror("DelBqSuccess","template/ListBq.php?classid=$cid");
	}
	else
	{printerror("DbError","history.go(-1)");}
}

//修改搜索页面
function EditSearchTemp($tempname,$temptext,$userid,$username){
	global $empire,$dbtbpre,$public_r;
	if(empty($temptext)||empty($tempname))
	{printerror("EmptySearchTemp","history.go(-1)");}
	//操作权限
	CheckLevel($userid,$username,$classid,"template");
	$temptext=RepPhpAspJspcode($temptext);
	$tempname=RepPostVar($tempname);
	if($tempname=="searchtemp")//搜索表单模板
	{
		$f="searchtemp";
		$tname="searchformtemp";
	}
	elseif($tempname=="searchjstemp")//搜索JS模板（横向)
	{
		$temptext=str_replace("\r\n","",$temptext);
		$f="searchjstemp";
		$tname="searchformjs";
	}
	else//搜索JS模板（纵向)
	{
		$temptext=str_replace("\r\n","",$temptext);
		$f="searchjstemp1";
		$tname="searchformjs1";
    }
	$gid=(int)$_POST['gid'];
	$sql=$empire->query("update ".GetDoTemptb("enewspubtemp",$gid)." set ".$f."='".addslashes($temptext)."'");
	if($gid==$public_r['deftempid']||(!$public_r['deftempid']&&($gid==1||$gid==0)))
	{
		GetSearch();
	}
	if($sql)
	{
		//操作日志
		insert_dolog("temp=$f&gid=$gid");
		printerror("EditSearchTempSuccess","template/EditPublicTemp.php?tname=$tname&gid=$gid#$tempname");
	}
	else
	{printerror("DbError","history.go(-1)");}
}

//修改相关链接模板
function EditOtherLinkTemp($tempname,$temptext,$userid,$username){
	global $empire,$dbtbpre;
	if(empty($temptext)||empty($tempname))
	{printerror("EmptyOtherLinkTemp","history.go(-1)");}
	//操作权限
	CheckLevel($userid,$username,$classid,"template");
	$tempname=RepPostVar($tempname);
	$temptext=RepPhpAspJspcode($temptext);
	$f="otherlinktemp";
	$tname="otherlinktemp";
	$otherlinktempsub=(int)$_POST['otherlinktempsub'];
	$otherlinktempdate=$_POST['otherlinktempdate'];
	$gid=(int)$_POST['gid'];
	$sql=$empire->query("update ".GetDoTemptb("enewspubtemp",$gid)." set ".$f."='".addslashes($temptext)."',otherlinktempsub=$otherlinktempsub,otherlinktempdate='".addslashes($otherlinktempdate)."'");
	if($sql)
	{
		//操作日志
		insert_dolog("temp=$f&gid=$gid");
		printerror("EditOtherLinkTempSuccess","template/EditPublicTemp.php?tname=$tname&gid=$gid#$tempname");
	}
	else
	{printerror("DbError","history.go(-1)");}
}

//修改打印模板
function EditPrintTemp($tempname,$temptext,$userid,$username){
	global $empire,$dbtbpre,$public_r;
	if(empty($temptext)||empty($tempname))
	{printerror("EmptyPrintTemp","history.go(-1)");}
	//操作权限
	CheckLevel($userid,$username,$classid,"template");
	$temptext=RepPhpAspJspcode($temptext);
	$tempname=RepPostVar($tempname);
	$gid=(int)$_POST['gid'];
	$sql=$empire->query("update ".GetDoTemptb("enewspubtemp",$gid)." set ".$tempname."='".addslashes($temptext)."'");
	if($gid==$public_r['deftempid']||(!$public_r['deftempid']&&($gid==1||$gid==0)))
	{
		if($tempname=="downsofttemp"||$tempname=="onlinemovietemp"||$tempname=="listpagetemp")
		{
			GetConfig();
		}
		elseif($tempname=="downpagetemp")
		{
			GetDownloadPage();
		}
		elseif($tempname=="pljstemp")
		{
			GetPlJsPage();
		}
		else
		{
			GetPrintPage();
		}
	}
	if($sql)
	{
		//操作日志
		insert_dolog("temp=$tempname&gid=$gid");
		printerror("EditDownTempSuccess","template/EditPublicTemp.php?tname=$tempname&gid=$gid#$tempname");
	}
	else
	{printerror("DbError","history.go(-1)");}
}

//修改首页模板
function EditIndextemp($temptext,$userid,$username){
	global $empire,$dbtbpre,$public_r;
	if(!$temptext)
	{
		printerror("EmptyIndexTemp","history.go(-1)");
	}
	CheckLevel($userid,$username,$classid,"template");//操作权限
	$temptext=RepPhpAspJspcode($temptext);
	$gid=(int)$_POST['gid'];
	$sql=$empire->query("update ".GetDoTemptb("enewspubtemp",$gid)." set indextemp='".addslashes($temptext)."'");
	//刷新首页
	if($gid==$public_r['deftempid']||(!$public_r['deftempid']&&($gid==1||$gid==0)))
	{
		NewsBq($classid,$temptext,1,0);
	}
	if($sql)
	{
	    insert_dolog("gid=$gid");//操作日志
		printerror("EditPublicTempSuccess","template/EditPublicTemp.php?tname=indextemp&gid=$gid");
	}
	else
	{
		printerror("DbError","history.go(-1)");
	}
}

//批量导入栏目模板
function LoadTempInClass($path,$start,$userid,$username){
	global $empire,$public_r,$dbtbpre;
	//验证权限
	CheckLevel($userid,$username,$classid,"template");
	$start=(int)$start;
	if(empty($public_r[loadtempnum]))
	{$public_r[loadtempnum]=50;}
	$b=0;
	$sql=$empire->query("select classid,classtempid from {$dbtbpre}enewsclass where islast=0 and islist<>1 and classid>$start order by classid limit ".$public_r[loadtempnum]);
	while($r=$empire->fetch($sql))
	{
		$b=1;
		$newstart=$r[classid];
		$file="../data/LoadTemp/".$r[classid].".htm";
		if(file_exists($file))
		{
			$data=addslashes(ReadFiletext($file));
			$data=RepPhpAspJspcode($data);
			if($r[islist]==2)
			{
				$usql=$empire->query("update {$dbtbpre}enewsclassadd set classtext='".$data."' where classid='$r[classid]'");
			}
			else
			{
				$usql=$empire->query("update {$dbtbpre}enewsclasstemp set temptext='".$data."' where tempid='$r[classtempid]'");
			}
			NewsBq($r[classid],$data,0,0);
	    }
    }
	if(empty($b))
	{
		//操作日志
	    insert_dolog("");
		printerror("LoadClassTempSuccess","template/LoadTemp.php");
	}
	echo $fun_r['LoadOneTempSuccess']."(ID:<font color=red><b>".$newstart."</b></font>)<script>self.location.href='ecmstemp.php?enews=LoadTempInClass&start=$newstart';</script>";
	exit();
}

//批量更换栏目列表模板
function ChangeClassListtemp($classid,$listtempid,$userid,$username){
	global $empire,$class_r,$dbtbpre;
	if(empty($listtempid))
	{printerror("EmptChangeListtempid","history.go(-1)");}
	//验证权限
	CheckLevel($userid,$username,$classid,"template");
	$listtempid=(int)$listtempid;
	if(empty($classid))
	{$where="classid<>0";}
	else
	{
		//中级栏目
		if(empty($class_r[$classid][islast]))
		{
			$where=ReturnClass($class_r[$classid][sonclass]);
		}
		//终极栏目
		else
		{
			$where="classid='$classid'";
		}
	}
	$sql=$empire->query("update {$dbtbpre}enewsclass set listtempid=$listtempid where ".$where);
	GetClass();
	if($sql)
	{
		//操作日志
		insert_dolog("classid=$classid&listtempid=$listtempid");
		printerror("ChangeClassListtempSuccess","history.go(-1)");
	}
	else
	{printerror("DbError","history.go(-1)");}
}

//导出标签
function LoadOutBq($add,$userid,$username){
	global $empire,$dbtbpre;
	$bqid=(int)$add['bqid'];
	if(!$bqid||!$add['funvalue'])
	{
		printerror("EmptyLoadBqid","history.go(-1)");
	}
	//验证权限
    CheckLevel($userid,$username,$classid,"bq");
	$r=$empire->fetch1("select bqid,bqname,bqsay,funname,bq,bqgs from {$dbtbpre}enewsbq where bqid=$bqid");
	if(!$r[bqid])
	{
		printerror("NotThisBqid","history.go(-1)");
	}
	$add['funvalue']=ClearAddsData($add['funvalue']);
	$field="<!--#empirecms.bq-phome.net#--!>";
	$str=$r['bqname'].$field.stripSlashes($r['bqsay']).$field.$r['funname'].$field.$r['bq'].$field.stripSlashes($r['bqgs']).$field.$add['funvalue'];
	$filename=$r['bq'].time().".bq";
	$filepath=ECMS_PATH.'e/data/tmp/temp/'.$filename;
	WriteFiletext_n($filepath,$str);
	DownLoadFile($filename,$filepath,1);
	//操作日志
	insert_dolog("bqid=".$bqid."<br>bqname=".$r[bqname]);
	exit();
}

//导入标签
function LoadInBq($add,$file,$file_name,$file_type,$file_size,$userid,$username){
	global $empire,$dbtbpre;
	//验证权限
    CheckLevel($userid,$username,$classid,"bq");
	$classid=(int)$add['classid'];
	if(!$file_name||!$file_size)
	{
		printerror("EmptyLoadInBqFile","history.go(-1)");
	}
	//扩展名
	$filetype=GetFiletype($file_name);
	if($filetype!=".bq")
	{
		printerror("LoadInBqMustBq","history.go(-1)");
	}
	$field="<!--#empirecms.bq-phome.net#--!>";
	$path=ECMS_PATH.'e/data/tmp/temp/uploadbq'.time().'.bq';
	//上传文件
	$cp=@move_uploaded_file($file,$path);
	DoChmodFile($path);
	$data=ReadFiletext($path);
	DelFiletext($path);
	$r=explode($field,$data);
	$num=$empire->gettotal("select count(*) as total from {$dbtbpre}enewsbq where bq='$r[3]' or funname='$r[2]' limit 1");
	if($num)
	{
		printerror("ReLoadInBq","history.go(-1)");
	}
	$sql=$empire->query("insert into {$dbtbpre}enewsbq(bqname,bqsay,funname,bq,issys,bqgs,isclose,classid,myorder) values('".addslashes($r[0])."','".addslashes($r[1])."','".addslashes($r[2])."','".addslashes($r[3])."',0,'".addslashes($r[4])."',0,$classid,0);");
	$bqid=$empire->lastid();
	//操作日志
	insert_dolog("bqid=".$bqid."<br>bqname=".$r[0]);
	return $r;
}

//-----------------------批量替换模板字符
function DoRepTemp($add,$userid,$username){
	global $empire,$dbtbpre;
	//验证权限
	CheckLevel($userid,$username,$classid,"template");
	$oldword=RepPhpAspJspcode(addslashes($add['oldword']));
	$newword=RepPhpAspJspcode(addslashes($add['newword']));
	if(!$oldword)
	{
		printerror("EmptyRepTemp","history.go(-1)");
    }
	$gid=(int)$add['gid'];
	//公共表
	if($add['indextemp']||$add['cptemp']||$add['sformtemp']||$add['printtemp']||$add['gbooktemp']||$add['loginiframe']||$add['pljstemp']||$add['schalltemp']||$add['loginjstemp']||$add['downpagetemp'])
	{
		$set='';
		//首页模板
		if($add['indextemp'])
		{
			
			$set.=",indextemp=REPLACE(indextemp,'".$oldword."','".$newword."')";
		}
		//控制面板模板
		if($add['cptemp'])
		{
			$set.=",cptemp=REPLACE(cptemp,'".$oldword."','".$newword."')";
		}
		//搜索表单模板
		if($add['sformtemp'])
		{
			$set.=",searchtemp=REPLACE(searchtemp,'".$oldword."','".$newword."')";
		}
		//打印模板
		if($add['printtemp'])
		{
			$set.=",printtemp=REPLACE(printtemp,'".$oldword."','".$newword."')";
		}
		//留言板模板
		if($add['gbooktemp'])
		{
			$set.=",gbooktemp=REPLACE(gbooktemp,'".$oldword."','".$newword."')";
		}
		//登陆状态模板
		if($add['loginiframe'])
		{
			$set.=",loginiframe=REPLACE(loginiframe,'".$oldword."','".$newword."')";
		}
		//评论JS模板
		if($add['pljstemp'])
		{
			$set.=",pljstemp=REPLACE(pljstemp,'".$oldword."','".$newword."')";
		}
		//全站搜索模板
		if($add['schalltemp'])
		{
			$set.=",schalltemp=REPLACE(schalltemp,'".$oldword."','".$newword."')";
		}
		//JS调用登陆状态模板
		if($add['loginjstemp'])
		{
			$set.=",loginjstemp=REPLACE(loginjstemp,'".$oldword."','".$newword."')";
		}
		//最终下载页模板
		if($add['downpagetemp'])
		{
			$set.=",downpagetemp=REPLACE(downpagetemp,'".$oldword."','".$newword."')";
		}
		$empire->query("update ".GetDoTemptb("enewspubtemp",$gid)." set id=1".$set." limit 1");
	}
	//修改栏目封面模板
	if($add['classtemp'])
	{
		$empire->query("update ".GetDoTemptb("enewsclasstemp",$gid)." set temptext=REPLACE(temptext,'".$oldword."','".$newword."')");
    }
	//修改标签模板
	if($add['bqtemp'])
	{
		$empire->query("update ".GetDoTemptb("enewsbqtemp",$gid)." set temptext=REPLACE(temptext,'".$oldword."','".$newword."'),listvar=REPLACE(listvar,'".$oldword."','".$newword."')");
    }
	//修改列表模板
	if($add['listtemp'])
	{
		$empire->query("update ".GetDoTemptb("enewslisttemp",$gid)." set temptext=REPLACE(temptext,'".$oldword."','".$newword."'),listvar=REPLACE(listvar,'".$oldword."','".$newword."')");
    }
	//修改内容模板
	if($add['newstemp'])
	{
		$empire->query("update ".GetDoTemptb("enewsnewstemp",$gid)." set temptext=REPLACE(temptext,'".$oldword."','".$newword."')");
	}
	//修改搜索模板
	if($add['searchtemp'])
	{
		$empire->query("update ".GetDoTemptb("enewssearchtemp",$gid)." set temptext=REPLACE(temptext,'".$oldword."','".$newword."'),listvar=REPLACE(listvar,'".$oldword."','".$newword."')");
	}
	//修改自定义页面
	if($add['userpage'])
	{
		$empire->query("update {$dbtbpre}enewspage set pagetext=REPLACE(pagetext,'".$oldword."','".$newword."')");
	}
	//评论列表模板
	if($add['pltemp'])
	{
		$empire->query("update ".GetDoTemptb("enewspltemp",$gid)." set temptext=REPLACE(temptext,'".$oldword."','".$newword."')");
    }
	//模板变量
	if($add['tempvar'])
	{
		$empire->query("update ".GetDoTemptb("enewstempvar",$gid)." set varvalue=REPLACE(varvalue,'".$oldword."','".$newword."')");
	}
	//修改投票模板
	if($add['votetemp'])
	{
		$empire->query("update ".GetDoTemptb("enewsvotetemp",$gid)." set temptext=REPLACE(temptext,'".$oldword."','".$newword."')");
    }
	//反馈表单模板
	if($add['feedbackbtemp'])
	{
		$empire->query("update {$dbtbpre}enewsfeedbackclass set btemp=REPLACE(btemp,'".$oldword."','".$newword."')");
	}
	//操作日志
	insert_dolog("gid=$gid");
	printerror("RepTempSuccess","history.go(-1)");
}

//修改模板组
function EditTempGroup($add,$userid,$username){
	global $empire,$dbtbpre;
	//验证权限
	CheckLevel($userid,$username,$classid,"tempgroup");
	$gid=$add['gid'];
	$gname=$add['gname'];
	$count=count($gid);
	for($i=0;$i<$count;$i++)
	{
		$usql=$empire->query("update {$dbtbpre}enewstempgroup set gname='".$gname[$i]."' where gid='".$gid[$i]."'");
	}
	//操作日志
	insert_dolog("");
	printerror("EditTempGroupSuccess","TempGroup.php");
}

//默认模板组
function DefTtempGroup($add,$userid,$username){
	global $empire,$dbtbpre;
	//验证权限
	CheckLevel($userid,$username,$classid,"tempgroup");
	$gid=(int)$add['changegid'];
	if(!$gid)
	{
		printerror("EmptyTempGroup","");
	}
	$r=$empire->fetch1("select gid,gname from {$dbtbpre}enewstempgroup where gid=$gid");
	if(!$r['gid'])
	{
		printerror("EmptyTempGroup","");
	}
	$usql=$empire->query("update {$dbtbpre}enewstempgroup set isdefault=0");
	$sql=$empire->query("update {$dbtbpre}enewstempgroup set isdefault=1 where gid=$gid");
	$upsql=$empire->query("update {$dbtbpre}enewspublic set deftempid=$gid limit 1");
	if($usql&&$sql&&$upsql)
	{
		GetConfig();
		//操作日志
		insert_dolog("gid=$gid&gname=$r[gname]");
		printerror("DefTempGroupSuccess","TempGroup.php");
	}
	else
	{
		printerror("DbError","");
	}
}

//返回模板表
function ReturnTemptbList(){
	$templist="enewsbqtemp,enewsjstemp,enewslisttemp,enewsnewstemp,enewspubtemp,enewssearchtemp,enewstempvar,enewsvotetemp,enewsclasstemp,enewspltemp";
	return $templist;
}

//删除模板数据表
function DelTempTb($gid){
	global $empire,$dbtbpre;
	if($gid==1)
	{
		return "";
	}
	$templist=ReturnTemptbList();
	$r=explode(",",$templist);
	$count=count($r);
	$droptb="";
	for($i=0;$i<$count;$i++)
	{
		$dh=",";
		if($i==0)
		{
			$dh="";
		}
		$droptb.=$dh.$dbtbpre.$r[$i]."_".$gid;
	}
	$sql=$empire->query("DROP TABLE IF EXISTS ".$droptb.";");
	return $sql;
}

//清空模板数据表
function ClearTempTb($gid,$en){
	global $empire,$dbtbpre;
	$templist=ReturnTemptbList();
	$r=explode(",",$templist);
	$count=count($r);
	for($i=0;$i<$count;$i++)
	{
		$tb=$dbtbpre.$r[$i].$en;
		$empire->query("TRUNCATE `".$tb."`;");
	}
}

//新建模板数据表
function CreateTempTb($gid,$en){
	global $empire,$dbtbpre;
	if($gid==1)
	{
		return "";
	}
	$templist=ReturnTemptbList();
	$r=explode(",",$templist);
	$count=count($r);
	for($i=0;$i<$count;$i++)
	{
		$otb=$dbtbpre.$r[$i];
		$tb=$dbtbpre.$r[$i].$en;
		CopyEcmsTb($otb,$tb);
	}
}

//删除模板组
function DelTempGroup($add,$userid,$username){
	global $empire,$dbtbpre;
	//验证权限
	CheckLevel($userid,$username,$classid,"tempgroup");
	$gid=(int)$add['changegid'];
	if(!$gid)
	{
		printerror("EmptyDelTempGroup","");
	}
	if($gid==1)
	{
		printerror("NotDelDefTempGroup","");
	}
	$r=$empire->fetch1("select gid,gname,isdefault from {$dbtbpre}enewstempgroup where gid=$gid");
	if(!$r['gid'])
	{
		printerror("EmptyDelTempGroup","");
	}
	$sql=$empire->query("delete from {$dbtbpre}enewstempgroup where gid=$gid");
	if($r['isdefault'])
	{
		$upsql=$empire->query("update {$dbtbpre}enewspublic set deftempid=0 limit 1");
		GetConfig();
	}
	DelTempTb($gid);
	if($sql)
	{
		//操作日志
		insert_dolog("gid=$gid&gname=$r[gname]");
		printerror("DelTempGroupSuccess","TempGroup.php");
	}
	else
	{
		printerror("DbError","");
	}
}

//导出模板组
function LoadTempGroup($add,$userid,$username){
	global $empire,$dbtbpre;
	//验证权限
	CheckLevel($userid,$username,$classid,"tempgroup");
	$gid=(int)$add['changegid'];
	if(!$gid)
	{
		printerror("EmptyLoadTempGroup","");
	}
	$r=$empire->fetch1("select gid,gname from {$dbtbpre}enewstempgroup where gid=$gid");
	if(!$r['gid'])
	{
		printerror("EmptyLoadTempGroup","");
	}
	$pageexp="<!---ecms.temp--->";
	$record="<!---ecms.record--->";
	$field="<!---ecms.field--->";
	if($gid==1)
	{
		$en="";
	}
	else
	{
		$en="_".$gid;
	}
	$bqtemp=LoadTGBqtemp($gid,$en,$pageexp,$record,$field);//标签模板
	$jstemp=LoadTGJstemp($gid,$en,$pageexp,$record,$field);//JS模板
	$listtemp=LoadTGListtemp($gid,$en,$pageexp,$record,$field);//列表模板
	$newstemp=LoadTGNewstemp($gid,$en,$pageexp,$record,$field);//内容模板
	$pubtemp=LoadTGPubtemp($gid,$en,$pageexp,$record,$field);//公共模板
	$searchtemp=LoadTGSearchtemp($gid,$en,$pageexp,$record,$field);//搜索模板
	$tempvar=LoadTGTempvar($gid,$en,$pageexp,$record,$field);//模板变量
	$votetemp=LoadTGVotetemp($gid,$en,$pageexp,$record,$field);//投票模板
	$classtemp=LoadTGClasstemp($gid,$en,$pageexp,$record,$field);//栏目模板
	$pltemp=LoadTGPltemp($gid,$en,$pageexp,$record,$field);//评论模板
	$loadtemptext=$r['gname'].$pageexp.$bqtemp.$pageexp.$jstemp.$pageexp.$listtemp.$pageexp.$newstemp.$pageexp.$pubtemp.$pageexp.$searchtemp.$pageexp.$tempvar.$pageexp.$votetemp.$pageexp.$classtemp.$pageexp.$pltemp;
	$loadtemptext=stripSlashes($loadtemptext);
	$file="e".time().".temp";
	$filepath=ECMS_PATH.'e/data/tmp/temp/'.$file;
	WriteFiletext_n($filepath,$loadtemptext);
	DownLoadFile($file,$filepath,1);
	//操作日志
	insert_dolog("gid=$gid&gname=$r[gname]");
	exit();
}

//导入模板组
function LoadInTempGroup($add,$file,$file_name,$file_type,$file_size,$userid,$username){
	global $empire,$dbtbpre;
	//验证权限
	CheckLevel($userid,$username,$classid,"tempgroup");
	if(!$file_name||!$file_size)
	{
		printerror("EmptyLoadInTempGroup","");
	}
	$gid=(int)$add['gid'];
	//扩展名
	$filetype=GetFiletype($file_name);
	if($filetype!=".temp")
	{
		printerror("LoadInTempGroupMusttemp","");
	}
	//上传文件
	$path=ECMS_PATH.'e/data/tmp/temp/uploadtg'.time().'.temp';
	$cp=@move_uploaded_file($file,$path);
	DoChmodFile($path);
	$data=ReadFiletext($path);
	DelFiletext($path);
	if(empty($data))
	{
		printerror("EmptyLoadInTempGroup","");
	}
	//入库
	$pageexp="<!---ecms.temp--->";
	$record="<!---ecms.record--->";
	$field="<!---ecms.field--->";
	$pr=explode($pageexp,$data);
	if(empty($gid))//新建模板组
	{
		$sql=$empire->query("insert into {$dbtbpre}enewstempgroup(gname,isdefault) values('".addslashes($pr[0])."',0);");
		$gid=$empire->lastid();
		$gname=$pr[0];
		$en="_".$gid;
		CreateTempTb($gid,$en);//复制表
	}
	else//覆盖模板组
	{
		$r=$empire->fetch1("select gid,gname from {$dbtbpre}enewstempgroup where gid=$gid");
		if(!$r['gid'])
		{
			printerror("LoadInTempGroupMusttemp","");
		}
		if($gid==1)
		{
			$en="";
		}
		else
		{
			$en="_".$gid;
		}
		$gname=$r['gname'];
		ClearTempTb($gid,$en);//清空表
	}
	//版本
	$isold=0;
	if(count($pr)<=10)
	{
		$isold=1;
	}
	LoadInTGBqtemp($gid,$en,$record,$field,$pr[1]);//标签模板
	LoadInTGJstemp($gid,$en,$record,$field,$pr[2]);//JS模板
	LoadInTGListtemp($gid,$en,$record,$field,$pr[3]);//列表模板
	LoadInTGNewstemp($gid,$en,$record,$field,$pr[4]);//内容模板
	LoadInTGPubtemp($gid,$en,$record,$field,$pr[5],$isold);//公共模板
	LoadInTGSearchtemp($gid,$en,$record,$field,$pr[6]);//搜索模板
	LoadInTGTempvar($gid,$en,$record,$field,$pr[7]);//模板变量
	LoadInTGVotetemp($gid,$en,$record,$field,$pr[8]);//投票模板
	LoadInTGClasstemp($gid,$en,$record,$field,$pr[9]);//栏目模板
	if($isold==0)
	{
		LoadInTGPltemp($gid,$en,$record,$field,$pr[10]);//评论模板
	}
	//操作日志
	insert_dolog("gid=$gid&gname=$gname");
	printerror("LoadInTempGroupSuccess","TempGroup.php");
}

//替换模板组存放格式
function ReplaceLoadTGTemp($pageexp,$record,$field,$text){
	$text=str_replace($pageexp,"",$text);
	$text=str_replace($record,"",$text);
	$text=str_replace($field,"",$text);
	return $text;
}

//标签模板
function LoadTGBqtemp($gid,$en,$pageexp,$record,$field){
	global $empire,$dbtbpre;
	$tb=$dbtbpre."enewsbqtemp".$en;
	$sql=$empire->query("select * from ".$tb." order by tempid");
	$classid=0;
	while($r=$empire->fetch($sql))
	{
		$r['temptext']=ReplaceLoadTGTemp($pageexp,$record,$field,$r['temptext']);
		$r['listvar']=ReplaceLoadTGTemp($pageexp,$record,$field,$r['listvar']);
		$text.=$r['tempid'].$field.$r['tempname'].$field.$r['modid'].$field.$r['temptext'].$field.$r['showdate'].$field.$r['listvar'].$field.$r['subnews'].$field.$r['rownum'].$field.$classid.$field.$r['docode'].$record;
	}
	return $text;
}

function LoadInTGBqtemp($gid,$en,$record,$field,$text){
	global $empire,$dbtbpre;
	if(empty($text))
	{
		return "";
	}
	$tb=$dbtbpre."enewsbqtemp".$en;
	$rr=explode($record,$text);
	$count=count($rr);
	for($i=0;$i<$count-1;$i++)
	{
		$r=explode($field,$rr[$i]);
		$sql=$empire->query("insert into ".$tb."(tempid,tempname,modid,temptext,showdate,listvar,subnews,rownum,classid,docode) values('$r[0]','".addslashes($r[1])."','$r[2]','".addslashes($r[3])."','".addslashes($r[4])."','".addslashes($r[5])."','$r[6]','$r[7]','$r[8]','$r[9]');");
	}
}

//JS模板
function LoadTGJstemp($gid,$en,$pageexp,$record,$field){
	global $empire,$dbtbpre;
	$tb=$dbtbpre."enewsjstemp".$en;
	$sql=$empire->query("select * from ".$tb." order by tempid");
	$classid=0;
	while($r=$empire->fetch($sql))
	{
		$r['temptext']=ReplaceLoadTGTemp($pageexp,$record,$field,$r['temptext']);
		$text.=$r['tempid'].$field.$r['tempname'].$field.$r['temptext'].$field.$classid.$field.$r['isdefault'].$field.$r['showdate'].$field.$r['modid'].$field.$r['subnews'].$field.$r['subtitle'].$record;
	}
	return $text;
}

function LoadInTGJstemp($gid,$en,$record,$field,$text){
	global $empire,$dbtbpre;
	if(empty($text))
	{
		return "";
	}
	$tb=$dbtbpre."enewsjstemp".$en;
	$rr=explode($record,$text);
	$count=count($rr);
	for($i=0;$i<$count-1;$i++)
	{
		$r=explode($field,$rr[$i]);
		if(empty($r[6]))
		{
			$r[6]=1;
		}
		$sql=$empire->query("insert into ".$tb."(tempid,tempname,temptext,classid,isdefault,showdate,modid,subnews,subtitle) values('$r[0]','".addslashes($r[1])."','".addslashes($r[2])."','$r[3]','$r[4]','".addslashes($r[5])."','$r[6]','$r[7]','$r[8]');");
	}
}

//列表模板
function LoadTGListtemp($gid,$en,$pageexp,$record,$field){
	global $empire,$dbtbpre;
	$tb=$dbtbpre."enewslisttemp".$en;
	$sql=$empire->query("select * from ".$tb." order by tempid");
	$classid=0;
	while($r=$empire->fetch($sql))
	{
		$r['temptext']=ReplaceLoadTGTemp($pageexp,$record,$field,$r['temptext']);
		$r['listvar']=ReplaceLoadTGTemp($pageexp,$record,$field,$r['listvar']);
		$text.=$r['tempid'].$field.$r['tempname'].$field.$r['temptext'].$field.$r['subnews'].$field.$r['isdefault'].$field.$r['listvar'].$field.$r['rownum'].$field.$r['modid'].$field.$r['showdate'].$field.$r['subtitle'].$field.$classid.$field.$r['docode'].$record;
	}
	return $text;
}

function LoadInTGListtemp($gid,$en,$record,$field,$text){
	global $empire,$dbtbpre;
	if(empty($text))
	{
		return "";
	}
	$tb=$dbtbpre."enewslisttemp".$en;
	$rr=explode($record,$text);
	$count=count($rr);
	for($i=0;$i<$count-1;$i++)
	{
		$r=explode($field,$rr[$i]);
		$sql=$empire->query("insert into ".$tb."(tempid,tempname,temptext,subnews,isdefault,listvar,rownum,modid,showdate,subtitle,classid,docode) values('$r[0]','".addslashes($r[1])."','".addslashes($r[2])."','$r[3]','$r[4]','".addslashes($r[5])."','$r[6]','$r[7]','".addslashes($r[8])."','$r[9]','$r[10]','$r[11]');");
	}
}

//内容模板
function LoadTGNewstemp($gid,$en,$pageexp,$record,$field){
	global $empire,$dbtbpre;
	$tb=$dbtbpre."enewsnewstemp".$en;
	$sql=$empire->query("select * from ".$tb." order by tempid");
	$classid=0;
	while($r=$empire->fetch($sql))
	{
		$r['temptext']=ReplaceLoadTGTemp($pageexp,$record,$field,$r['temptext']);
		$text.=$r['tempid'].$field.$r['tempname'].$field.$r['isdefault'].$field.$r['temptext'].$field.$r['showdate'].$field.$r['modid'].$field.$classid.$record;
	}
	return $text;
}

function LoadInTGNewstemp($gid,$en,$record,$field,$text){
	global $empire,$dbtbpre;
	if(empty($text))
	{
		return "";
	}
	$text=RepTemplateJsUrl($text,1,0);//替换JS地址
	$tb=$dbtbpre."enewsnewstemp".$en;
	$rr=explode($record,$text);
	$count=count($rr);
	for($i=0;$i<$count-1;$i++)
	{
		$r=explode($field,$rr[$i]);
		$sql=$empire->query("insert into ".$tb."(tempid,tempname,isdefault,temptext,showdate,modid,classid) values('$r[0]','".addslashes($r[1])."','$r[2]','".addslashes($r[3])."','".addslashes($r[4])."','$r[5]','$r[6]');");
	}
}

//公共模板
function LoadTGPubtemp($gid,$en,$pageexp,$record,$field){
	global $empire,$dbtbpre;
	$tb=$dbtbpre."enewspubtemp".$en;
	$r=$empire->fetch1("select * from ".$tb." limit 1");
	$r['indextemp']=ReplaceLoadTGTemp($pageexp,$record,$field,$r['indextemp']);
	$r['cptemp']=ReplaceLoadTGTemp($pageexp,$record,$field,$r['cptemp']);
	$r['searchtemp']=ReplaceLoadTGTemp($pageexp,$record,$field,$r['searchtemp']);
	$r['searchjstemp']=ReplaceLoadTGTemp($pageexp,$record,$field,$r['searchjstemp']);
	$r['searchjstemp1']=ReplaceLoadTGTemp($pageexp,$record,$field,$r['searchjstemp1']);
	$r['otherlinktemp']=ReplaceLoadTGTemp($pageexp,$record,$field,$r['otherlinktemp']);
	$r['printtemp']=ReplaceLoadTGTemp($pageexp,$record,$field,$r['printtemp']);
	$r['downsofttemp']=ReplaceLoadTGTemp($pageexp,$record,$field,$r['downsofttemp']);
	$r['onlinemovietemp']=ReplaceLoadTGTemp($pageexp,$record,$field,$r['onlinemovietemp']);
	$r['listpagetemp']=ReplaceLoadTGTemp($pageexp,$record,$field,$r['listpagetemp']);
	$r['gbooktemp']=ReplaceLoadTGTemp($pageexp,$record,$field,$r['gbooktemp']);
	$r['loginiframe']=ReplaceLoadTGTemp($pageexp,$record,$field,$r['loginiframe']);
	$r['loginjstemp']=ReplaceLoadTGTemp($pageexp,$record,$field,$r['loginjstemp']);
	$r['downpagetemp']=ReplaceLoadTGTemp($pageexp,$record,$field,$r['downpagetemp']);
	$r['pljstemp']=ReplaceLoadTGTemp($pageexp,$record,$field,$r['pljstemp']);
	$r['schalltemp']=ReplaceLoadTGTemp($pageexp,$record,$field,$r['schalltemp']);
	$text.=$r['id'].$field.$r['indextemp'].$field.''.$field.$r['cptemp'].$field.$r['searchtemp'].$field.$r['searchjstemp'].$field.$r['searchjstemp1'].$field.$r['otherlinktemp'].$field.$r['printtemp'].$field.$r['downsofttemp'].$field.$r['onlinemovietemp'].$field.$r['listpagetemp'].$field.$r['gbooktemp'].$field.$r['loginiframe'].$field.$r['otherlinktempsub'].$field.$r['otherlinktempdate'].$field.$r['loginjstemp'].$field.$r['downpagetemp'].$field.$r['pljstemp'].$field.$r['schalltemp'].$field.$r['schallsubnum'].$field.$r['schalldate'].$record;
	return $text;
}

function LoadInTGPubtemp($gid,$en,$record,$field,$text,$isold=0){
	global $empire,$dbtbpre,$fun_r;
	if(empty($text))
	{
		return "";
	}
	$tb=$dbtbpre."enewspubtemp".$en;
	$rr=explode($record,$text);
	$r=explode($field,$rr[0]);
	//相关链接设置
	if(empty($r[14]))
	{
		$r[14]=30;
	}
	if(empty($r[15]))
	{
		$r[15]='Y-m-d H:i:s';
	}
	if(empty($r[21]))
	{
		$r[21]='Y-m-d H:i:s';
	}
	$sql=$empire->query("insert into ".$tb."(id,indextemp,cptemp,searchtemp,searchjstemp,searchjstemp1,otherlinktemp,printtemp,downsofttemp,onlinemovietemp,listpagetemp,gbooktemp,loginiframe,otherlinktempsub,otherlinktempdate,loginjstemp,downpagetemp,pljstemp,schalltemp,schallsubnum,schalldate) values('$r[0]','".addslashes($r[1])."','".addslashes($r[3])."','".addslashes($r[4])."','".addslashes($r[5])."','".addslashes($r[6])."','".addslashes($r[7])."','".addslashes($r[8])."','".addslashes($r[9])."','".addslashes($r[10])."','".addslashes($r[11])."','".addslashes($r[12])."','".addslashes($r[13])."','$r[14]','$r[15]','".addslashes($r[16])."','".addslashes($r[17])."','".addslashes($r[18])."','".addslashes($r[19])."','$r[20]','$r[21]');");
	//旧版本
	if($isold==1&&$r[1])
	{
		$pltb=$dbtbpre."enewspltemp".$en;
		$pltempname=$fun_r['PlListTempname'];
		$empire->query("insert into ".$pltb."(tempid,tempname,temptext,isdefault) values(NULL,'".addslashes($pltempname)."','".addslashes($r[2])."',1);");
	}
}

//搜索模板
function LoadTGSearchtemp($gid,$en,$pageexp,$record,$field){
	global $empire,$dbtbpre;
	$tb=$dbtbpre."enewssearchtemp".$en;
	$sql=$empire->query("select * from ".$tb." order by tempid");
	$classid=0;
	while($r=$empire->fetch($sql))
	{
		$r['temptext']=ReplaceLoadTGTemp($pageexp,$record,$field,$r['temptext']);
		$r['listvar']=ReplaceLoadTGTemp($pageexp,$record,$field,$r['listvar']);
		$text.=$r['tempid'].$field.$r['tempname'].$field.$r['temptext'].$field.$r['subnews'].$field.$r['isdefault'].$field.$r['listvar'].$field.$r['rownum'].$field.$r['modid'].$field.$r['showdate'].$field.$r['subtitle'].$field.$classid.$field.$r['docode'].$record;
	}
	return $text;
}

function LoadInTGSearchtemp($gid,$en,$record,$field,$text){
	global $empire,$dbtbpre;
	if(empty($text))
	{
		return "";
	}
	$tb=$dbtbpre."enewssearchtemp".$en;
	$rr=explode($record,$text);
	$count=count($rr);
	for($i=0;$i<$count-1;$i++)
	{
		$r=explode($field,$rr[$i]);
		$sql=$empire->query("insert into ".$tb."(tempid,tempname,temptext,subnews,isdefault,listvar,rownum,modid,showdate,subtitle,classid,docode) values('$r[0]','".addslashes($r[1])."','".addslashes($r[2])."','$r[3]','$r[4]','".addslashes($r[5])."','$r[6]','$r[7]','".addslashes($r[8])."','$r[9]','$r[10]','$r[11]');");
	}
}

//模板变量
function LoadTGTempvar($gid,$en,$pageexp,$record,$field){
	global $empire,$dbtbpre;
	$tb=$dbtbpre."enewstempvar".$en;
	$sql=$empire->query("select * from ".$tb." order by varid");
	$classid=0;
	while($r=$empire->fetch($sql))
	{
		$r['varvalue']=ReplaceLoadTGTemp($pageexp,$record,$field,$r['varvalue']);
		$text.=$r['varid'].$field.$r['myvar'].$field.$r['varname'].$field.$r['varvalue'].$field.$classid.$field.$r['isclose'].$field.$r['myorder'].$record;
	}
	return $text;
}

function LoadInTGTempvar($gid,$en,$record,$field,$text){
	global $empire,$dbtbpre;
	if(empty($text))
	{
		return "";
	}
	$tb=$dbtbpre."enewstempvar".$en;
	$rr=explode($record,$text);
	$count=count($rr);
	for($i=0;$i<$count-1;$i++)
	{
		$r=explode($field,$rr[$i]);
		$sql=$empire->query("insert into ".$tb."(varid,myvar,varname,varvalue,classid,isclose,myorder) values('$r[0]','".addslashes($r[1])."','".addslashes($r[2])."','".addslashes($r[3])."','$r[4]','$r[5]','$r[6]');");
	}
}

//投票模板
function LoadTGVotetemp($gid,$en,$pageexp,$record,$field){
	global $empire,$dbtbpre;
	$tb=$dbtbpre."enewsvotetemp".$en;
	$sql=$empire->query("select * from ".$tb." order by tempid");
	$classid=0;
	while($r=$empire->fetch($sql))
	{
		$r['temptext']=ReplaceLoadTGTemp($pageexp,$record,$field,$r['temptext']);
		$text.=$r['tempid'].$field.$r['tempname'].$field.$r['temptext'].$record;
	}
	return $text;
}

function LoadInTGVotetemp($gid,$en,$record,$field,$text){
	global $empire,$dbtbpre;
	if(empty($text))
	{
		return "";
	}
	$tb=$dbtbpre."enewsvotetemp".$en;
	$rr=explode($record,$text);
	$count=count($rr);
	for($i=0;$i<$count-1;$i++)
	{
		$r=explode($field,$rr[$i]);
		$sql=$empire->query("insert into ".$tb."(tempid,tempname,temptext) values('$r[0]','".addslashes($r[1])."','".addslashes($r[2])."');");
	}
}

//栏目封面模板
function LoadTGClasstemp($gid,$en,$pageexp,$record,$field){
	global $empire,$dbtbpre;
	$tb=$dbtbpre."enewsclasstemp".$en;
	$sql=$empire->query("select * from ".$tb." order by tempid");
	$classid=0;
	while($r=$empire->fetch($sql))
	{
		$r['temptext']=ReplaceLoadTGTemp($pageexp,$record,$field,$r['temptext']);
		$text.=$r['tempid'].$field.$r['tempname'].$field.$r['temptext'].$field.$classid.$record;
	}
	return $text;
}

function LoadInTGClasstemp($gid,$en,$record,$field,$text){
	global $empire,$dbtbpre;
	if(empty($text))
	{
		return "";
	}
	$tb=$dbtbpre."enewsclasstemp".$en;
	$rr=explode($record,$text);
	$count=count($rr);
	for($i=0;$i<$count-1;$i++)
	{
		$r=explode($field,$rr[$i]);
		$sql=$empire->query("insert into ".$tb."(tempid,tempname,temptext,classid) values('$r[0]','".addslashes($r[1])."','".addslashes($r[2])."','$r[3]');");
	}
}

//评论列表模板
function LoadTGPltemp($gid,$en,$pageexp,$record,$field){
	global $empire,$dbtbpre;
	$tb=$dbtbpre."enewspltemp".$en;
	$sql=$empire->query("select * from ".$tb." order by tempid");
	while($r=$empire->fetch($sql))
	{
		$r['temptext']=ReplaceLoadTGTemp($pageexp,$record,$field,$r['temptext']);
		$text.=$r['tempid'].$field.$r['tempname'].$field.$r['temptext'].$field.$r['isdefault'].$record;
	}
	return $text;
}

function LoadInTGPltemp($gid,$en,$record,$field,$text){
	global $empire,$dbtbpre;
	if(empty($text))
	{
		return "";
	}
	$tb=$dbtbpre."enewspltemp".$en;
	$rr=explode($record,$text);
	$count=count($rr);
	for($i=0;$i<$count-1;$i++)
	{
		$r=explode($field,$rr[$i]);
		$sql=$empire->query("insert into ".$tb."(tempid,tempname,temptext,isdefault) values('$r[0]','".addslashes($r[1])."','".addslashes($r[2])."','$r[3]');");
	}
}
?>