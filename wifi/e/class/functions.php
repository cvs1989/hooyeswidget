<?php
$do_ckhloginip=0;	//后台是否验证登录IP,0为不验证,1为验证

//-------------- 公共区 ----------------------

//返回后台风格
function EcmsReturnAdminStyle(){
	global $public_r;
	$adminstyle=(int)getcvar('loginadminstyleid',1);
	if(!strstr($public_r['adminstyle'],','.$adminstyle.','))
	{
		$adminstyle=$public_r['defadminstyle']?$public_r['defadminstyle']:1;
	}
	return $adminstyle;
}

//返回后台管理信息栏目导航字符串
function AdminReturnClassLink($classid){
	global $class_r,$editor;
	if($editor==1)
	{
		$addurl='../';
	}
	if(empty($class_r[$classid][featherclass]))
	{
		$class_r[$classid][featherclass]="|";
	}
	$r=explode("|",$class_r[$classid][featherclass].$classid."|");
	$string="<a href=\"".$addurl."ListAllInfo.php?tbname=".$class_r[$classid][tbname]."\">管理信息</a>";
	$count=count($r)-1;
	for($i=1;$i<$count;$i++)
	{
		$curl=$class_r[$r[$i]][islast]?"ListNews.php?classid=".$r[$i]:"ListAllInfo.php?tbname=".$class_r[$r[$i]][tbname]."&classid=".$r[$i];
		$string.="&nbsp;>&nbsp;<a href=\"".$addurl."$curl\">".$class_r[$r[$i]][classname]."</a>";
    }
	return $string;
}

//加验证代码
function AddCheckViewCode(){
	$code="if(!defined('InEmpireCMS'))
{
	exit();
}";
	return $code;
}

//加模板验证代码
function AddCheckViewTempCode(){
	$code="<?php
if(!defined('InEmpireCMS'))
{
	exit();
}
?>";
	return $code;
}

//后台分页
function page2($num,$line,$page_line,$start,$page,$search){
	global $fun_r;
	if($num<=$line)
	{
		return '<span class="epages"><a title="'.$fun_r['admintrecord'].'">&nbsp;<b>'.$num.'</b> </a>&nbsp;&nbsp;</span>';
	}
	$url=$_SERVER['PHP_SELF'].'?page';
	$snum=2;//最小页数
	$totalpage=ceil($num/$line);//取得总页数
	$firststr='<a title="'.$fun_r['admintrecord'].'">&nbsp;<b>'.$num.'</b> </a>&nbsp;&nbsp;';
	//上一页
	if($page<>0)
	{
		$toppage='<a href="'.$url.'=0'.$search.'">'.$fun_r['adminstartpage'].'</a>&nbsp;';
		$pagepr=$page-1;
		$prepage='<a href="'.$url.'='.$pagepr.$search.'">'.$fun_r['adminpripage'].'</a>';
	}
	//下一页
	if($page!=$totalpage-1)
	{
		$pagenex=$page+1;
		$nextpage='&nbsp;<a href="'.$url.'='.$pagenex.$search.'">'.$fun_r['adminnextpage'].'</a>';
		$lastpage='&nbsp;<a href="'.$url.'='.($totalpage-1).$search.'">'.$fun_r['adminlastpage'].'</a>';
	}
	$starti=$page-$snum<0?0:$page-$snum;
	$no=0;
	for($i=$starti;$i<$totalpage&&$no<$page_line;$i++)
	{
		$no++;
		if($page==$i)
		{
			$is_1="<b>";
			$is_2="</b>";
		}
		else
		{
			$is_1='<a href="'.$url.'='.$i.$search.'">';
			$is_2="</a>";
		}
		$pagenum=$i+1;
		$returnstr.="&nbsp;".$is_1.$pagenum.$is_2;
	}
	$returnstr=$firststr.$toppage.$prepage.$returnstr.$nextpage.$lastpage;
	return '<span class="epages">'.$returnstr.'</span>';
}

//后台分页
function postpage($num,$line,$page_line,$start,$page,$form){
	global $fun_r;
	if($num<=$line)
	{
		return '';
	}
	$snum=2;//最小页数
	$totalpage=ceil($num/$line);//取得总页数
	$firststr='<a title="'.$fun_r['admintrecord'].'">&nbsp;<b>'.$num.'</b> </a>&nbsp;&nbsp;';
	//上一页
	if($page<>0)
	{
		$toppage='<a href="#ecms" onclick="javascript:GotoPostPage(0,0);">'.$fun_r['adminstartpage'].'</a>&nbsp;';
		$pagepr=$page-1;
		$prepage='<a href="#ecms" onclick="javascript:GotoPostPage('.$pagepr.',0);">'.$fun_r['adminpripage'].'</a>';
	}
	//下一页
	if($page!=$totalpage-1)
	{
		$pagenex=$page+1;
		$nextpage='&nbsp;<a href="#ecms" onclick="javascript:GotoPostPage('.$pagenex.',0);">'.$fun_r['adminnextpage'].'</a>';
		$lastpage='&nbsp;<a href="#ecms" onclick="javascript:GotoPostPage('.($totalpage-1).',0);">'.$fun_r['adminlastpage'].'</a>';
	}
	$starti=$page-$snum<0?0:$page-$snum;
	$no=0;
	for($i=$starti;$i<$totalpage&&$no<$page_line;$i++)
	{
		$no++;
		if($page==$i)
		{
			$is_1="<b>";
			$is_2="</b>";
		}
		else
		{
			$is_1='<a href="#ecms" onclick="javascript:GotoPostPage('.$i.',0);">';
			$is_2="</a>";
		}
		$pagenum=$i+1;
		$returnstr.="&nbsp;".$is_1.$pagenum.$is_2;
	}
	$returnstr=$firststr.$toppage.$prepage.$returnstr.$nextpage.$lastpage;
	$returnstr.="<script>
	function GotoPostPage(page,start){
		".$form.".page.value=page;
		".$form.".start.value=start;
		".$form.".submit();
	}
	</script>";
	return $returnstr;
}

//取得模型表名
function GetModTable($mid){
	global $empire,$dbtbpre;
	$r=$empire->fetch1("select tid,tbname from {$dbtbpre}enewsmod where mid='$mid'");
	return $r;
}

//建立专题目录
function CreateZtPath($ztpath){
	$createpath=ECMS_PATH.$ztpath;
    $mk=DoMkdir($createpath);
}

//建立栏目目录
function CreateClassPath($classpath){
	$createpath=ECMS_PATH.$classpath;
	$mk=DoMkdir($createpath);
	$createfilepath=ECMS_PATH.'d/file/'.$classpath;//建立附件目录
    $mk1=DoMkdir($createfilepath);
}

//删除栏目缓存文件
function DelListEnews(){
	$file=ECMS_PATH."e/data/fc/ListEnews.php";
	DelFiletext($file);
	$file1=ECMS_PATH."e/data/fc/ListClass0.php";
	DelFiletext($file1);
	$file2=ECMS_PATH."e/data/fc/ListClass1.php";
	DelFiletext($file2);
}

//替换php代码
function RepPhpAspJspcode($string){
	global $public_r;
	if(!$public_r[candocode]){
		//$string=str_replace("<?xml","[!--ecms.xml--]",$string);
		$string=str_replace("<?","&lt;?",$string);
		$string=str_replace("<%","&lt;%",$string);
		//$string=str_replace("[!--ecms.xml--]","<?xml",$string);
	}
	return $string;
}

//替换php代码
function RepPhpAspJspcodeText($string){
	//$string=str_replace("<?xml","[!--ecms.xml--]",$string);
	$string=str_replace("<?","&lt;?",$string);
	$string=str_replace("<%","&lt;%",$string);
	//$string=str_replace("[!--ecms.xml--]","<?xml",$string);
	$string=str_replace("<!--code.start-->","&lt;!--code.start--&gt;",$string);
	$string=str_replace("<!--code.end-->","&lt;!--code.end--&gt;",$string);
	return $string;
}

//替换文件前缀
function RepFilenameQz($qz){
	$qz=str_replace("/","",$qz);
	$qz=str_replace("\\","",$qz);
	$qz=str_replace("#","",$qz);
	$qz=str_replace("&","",$qz);
	$qz=str_replace(":","",$qz);
	$qz=str_replace(";","",$qz);
	$qz=str_replace("<","",$qz);
	$qz=str_replace(">","",$qz);
	$qz=str_replace("?","",$qz);
	$qz=str_replace("*","",$qz);
	$qz=str_replace("%","",$qz);
	$qz=str_replace("|","",$qz);
	$qz=str_replace("\"","",$qz);
	$qz=str_replace("'","",$qz);
	return $qz;
}

//替换目录值
function RepPathStr($path){
	$path=str_replace("\\","",$path);
	$path=str_replace("/","",$path);
	return $path;
}

//返回替换字符
function ReturnCheckDoRep(){
	global $empire,$dbtbpre;
	//信息来源
	$befrom=$empire->gettotal("select count(*) as total from {$dbtbpre}enewsbefrom");
	//作者
	$writer=$empire->gettotal("select count(*) as total from {$dbtbpre}enewswriter");
	//替换字符
	$words=$empire->gettotal("select count(*) as total from {$dbtbpre}enewswords");
	//内容关键字
	$key=$empire->gettotal("select count(*) as total from {$dbtbpre}enewskey");
	$str=",$befrom,$writer,$words,$key,";
	return $str;
}

//返回替换验证
function ReturnCheckDoRepStr(){
	global $public_r;
	return explode(',',$public_r[checkdorepstr]);
}

//取得栏目目录名称
function GetPathname($classname){
	$c=explode("/",$classname);
	$count=count($c)-1;
	$cr[0]=$c[$count];//栏目目录名
	$len=strlen($cr[0]);
	//上级栏目目录名
	$cr[1]=substr($classname,0,strlen($classname)-$len);
	return $cr;
}

//更新缓存
function ChangeEnewsData($userid,$username){
	//操作权限
	CheckLevel($userid,$username,$classid,"changedata");
	//更新参数设置
	GetConfig(1);
	//更新类别
	GetClass();
	//更新会员组
	GetMemberLevel();
	//更新全站搜索数据表
	GetSearchAllTb();
	//操作日志
	insert_dolog("");
	printerror("ChangeDataSuccess","history.go(-1)");
}

//返回文件名
function ReturnPathFile($filename){
	$fr=explode("/",$filename);
	$count=count($fr)-1;
	return $fr[$count];
}

//返回栏目链接(无缓存)
function sys_ReturnBqClassUrl($r){
	global $public_r;
	//外部栏目
	if($r[wburl])
	{
		$classurl=$r[wburl];
	}
	//动态列表
	elseif($r['listdt'])
	{
		$classurl=$public_r['newsurl']."e/action/ListInfo/?classid=$r[classid]";
	}
	elseif($r['classurl'])
	{
		$classurl=$r['classurl'];
	}
	else
	{
		$classurl=$public_r['newsurl'].$r['classpath']."/";
	}
	return $classurl;
}

//返回专题链接(无缓存)
function sys_ReturnBqZtUrl($r){
	global $public_r;
	if($r['zturl'])
	{
		$zturl=$r['zturl'];
    }
	else
	{
		$zturl=$public_r['newsurl'].$r['ztpath']."/";
    }
	return $zturl;
}

//组合两数组
function TogTwoArray($r,$ra){
	$returnr=array_merge($r,$ra);
	return $returnr;
}

//下载
function DownLoadFile($file,$filepath,$ecms=0){
	if(empty($file))
	{
		printerror("FileNotExist","history.go(-1)");
    }
	if(!file_exists($filepath))
	{
		printerror("FileNotExist","");
	}
	$filesize=@filesize($filepath);
	//下载
	Header("Content-type: application/octet-stream");
	Header("Accept-Ranges: bytes");
	Header("Accept-Length: ".$filesize);
	Header("Content-Disposition: attachment; filename=".$file);
	echo ReadFiletext($filepath);
	if($ecms==1)
	{
		DelFiletext($filepath);
	}
}

//取得缓存文件内容
function GetFcfiletext($file){
	$str1="document.write(\"";
	$str2="\");";
	$text=ReadFiletext($file);
	$text=stripSlashes(str_replace($str2,"",str_replace($str1,"",$text)));
	return $text;
}

//验证模板组是否存在
function CheckTempGroup($gid){
	global $empire,$dbtbpre;
	if(empty($gid))
	{
		$gid=GetDoTempGid();
	}
	$r=$empire->fetch1("select gid,gname from {$dbtbpre}enewstempgroup where gid='$gid'");
	if(empty($r['gid']))
	{
		printerror("ErrorUrl","");
	}
	return $r['gname'];
}

//附加隐藏表单项
function ReturnFormHidden($vname,$value){
	$value=htmlspecialchars(ClearAddsData($value));
	return "<input type=hidden name=\"".$vname."\" value=\"".$value."\">";
}

//-------------- 信息处理区 ----------------------

//替换关键字
function ReplaceKey($newstext){
	global $empire,$dbtbpre,$public_r;
	if(empty($newstext))
	{return $newstext;}
	$sql=$empire->query("select keyname,keyurl from {$dbtbpre}enewskey");
	while($r=$empire->fetch($sql))
	{
		$newstext=empty($public_r[repkeynum])?str_replace($r[keyname],'<a href="'.$r[keyurl].'" target="_blank" class="infotextkey">'.$r[keyname].'</a>',$newstext):preg_replace('/'.$r[keyname].'/','<a href="'.$r[keyurl].'" target="_blank" class="infotextkey">'.$r[keyname].'</a>',$newstext,$public_r[repkeynum]);
	}
	return $newstext;
}

//替换禁用字符
function ReplaceWord($newstext){
	global $empire,$dbtbpre;
	if(empty($newstext))
	{return $newstext;}
	$sql=$empire->query("select newword,oldword from {$dbtbpre}enewswords");
	while($r=$empire->fetch($sql))
	{
		$newstext=str_replace($r[oldword],$r[newword],$newstext);
	}
	return $newstext;
}

//重命名列表文件
function RenameListfile($classid,$lencord,$num,$type,$newtype,$classpath){
	$page=ceil($num/$lencord);
	for($j=1;$j<=$page;$j++)
	{
		if($j==1)
		{
			$listfile=ECMS_PATH.$classpath."/index";
		}
		else
		{
			$listfile=ECMS_PATH.$classpath."/index_".$j;
		}
		@rename($listfile.$type,$listfile.$newtype);
	}
}

//组合标题属性
function TitleFont($titlefont,$titlecolor=''){
	$add=$titlecolor.',';
	if($titlecolor=='no')
	{
		$add='';
	}
	if($titlefont[b])//粗体
	{$add.='b|';}
	if($titlefont[i])//斜体
	{$add.='i|';}
	if($titlefont[s])//删除线
	{$add.='s|';}
	if($add==',')
	{
		$add='';
	}
	return $add;
}

//组合专题ID
function ZtId($ztid){
	for($i=0;$i<count($ztid);$i++)
	{
		$myztid.=intval($ztid[$i]).'|';
	}
	if($myztid)
	{
		$myztid='|'.$myztid;
	}
	return $myztid;
}

//返回命名方式
function ReturnInfoFilename($classid,$id,$filenameqz){
	global $class_r;
	if($class_r[$classid][filename]==1)	//time命名
	{
		$filename=$class_r[$classid][filename_qz].time().$id;
	}
	elseif($class_r[$classid][filename]==2)	//md5命名
	{
		$filename=$class_r[$classid][filename_qz].md5(uniqid(microtime()));
	}
	elseif($class_r[$classid][filename]==3)	//目录
	{
		$filename=$class_r[$classid][filename_qz].$id.'/index';
	}
	else	//id
	{
		$filename=$class_r[$classid][filename_qz].$id;
	}
	$filename=$filenameqz.$filename;
	return $filename;
}

//更新相应的附件
function UpdateTheFile($id,$checkpass){
	global $empire,$dbtbpre;
	if(empty($id)||empty($checkpass))
	{
		return "";
    }
	$id=(int)$id;
	$checkpass=(int)$checkpass;
	$sql=$empire->query("update {$dbtbpre}enewsfile set id=$id,cjid=0 where cjid=$checkpass");
}

//修改时更新附件
function UpdateTheFileEdit($classid,$id){
	global $empire,$dbtbpre;
	$sql=$empire->query("update {$dbtbpre}enewsfile set cjid=0 where id='$id' and classid='$classid'");
}

//取第几张图片
function GetFpicToTpic($classid,$id,$num=1,$getfirsttitlespic=0,$swidth=0,$sheight=0){
	global $empire,$dbtbpre,$public_r,$class_r;
	$num=$num-1;
	$picr=$empire->fetch1("select fileid,filename,path,id,classid,no,fpath from {$dbtbpre}enewsfile where id=$id and classid=$classid and type=1 order by fileid limit $num,1");
	$firsttitlepic="";
	if($picr['fileid'])
	{
		$rpath=$picr['path']?$picr['path'].'/':$picr['path'];
		$fspath=ReturnFileSavePath($picr[classid],$picr[fpath]);
		if($getfirsttitlespic==1&&$swidth&&$sheight)//缩略图
		{
			$path="../../".$fspath['filepath'].$rpath;
			$yname=$path.$picr[filename];
			$filetype=GetFiletype($picr[filename]);
			$insertfile=substr($picr[filename],0,strlen($picr[filename])-strlen($filetype));
			$name=$path."small".$insertfile;
			$sfiler=GetMySmallImg($classid,$picr[no],$insertfile,$picr[path],$yname,$swidth,$sheight,$name,$add['filepass'],$add['filepass'],$userid,$username);
			$firsttitlepic=$fspath['fileurl'].$rpath."small".$insertfile.$sfiler['filetype'];
		}
		else
		{
			$firsttitlepic=$fspath['fileurl'].$rpath.$picr[filename];
		}
	}
	return $firsttitlepic;
}

//返回相关链接信息ID
function GetKeyid($keyboard,$classid,$id,$link_num){
	global $empire,$public_r,$class_r,$fun_r,$dbtbpre;
	if($keyboard)
	{
		if(empty($link_num))
		{
			$link_num=10;
		}
		$keyboard=RepDyh($keyboard);
		$r=explode(",",$keyboard);
		for($i=0;$i<count($r);$i++)
		{
			if($i==0)
			{
				$or="";
			}
			else
			{
				$or=" or ";
			}
			$repadd.=$or."[!--f--!]"." like '%".$r[$i]."%'";
	    }
		//搜索范围
		if($public_r['newslink']==1)
		{
			$add='('.str_replace('[!--f--!]','keyboard',$repadd).')';
		}
		elseif($public_r['newslink']==2)
		{
			$add='('.str_replace('[!--f--!]','keyboard',$repadd).' or '.str_replace('[!--f--!]','title',$repadd).')';
		}
		else
		{
			$add='('.str_replace('[!--f--!]','title',$repadd).')';
		}
		//模型
		if(!empty($class_r[$classid][modid]))
		{
			$mr=$empire->fetch1("select sonclass from {$dbtbpre}enewsmod where mid='".$class_r[$classid][modid]."'");
			$where=" and (".ReturnClass($mr[sonclass]).")";
		}
		//ID声名
		$keyid="";
		$first=0;
		$key_sql=$empire->query("select id from {$dbtbpre}ecms_".$class_r[$classid][tbname]." where ".$add.$where." and id<>$id and checked=1 order by newstime desc limit $link_num");
		while($link_r=$empire->fetch($key_sql))
		{
			if(empty($first))
			{
				$dh="";
				$first=1;
			}
			else
			{
				$dh=",";
			}
			$keyid.=$dh.$link_r[id];
		}
	}
	else
	{
		$keyid="";
	}
	return $keyid;
}

//删除信息附件
function DelNewsTheFile($id,$classid,$delpl=0){
	global $empire,$dbtbpre;
	if(empty($id))
	{
		return "";
	}
	$i=0;
	$sql=$empire->query("select classid,filename,path,fpath from {$dbtbpre}enewsfile where id='$id' and classid='$classid'");
	while($r=$empire->fetch($sql))
	{
		$i=1;
		DoDelFile($r);
    }
	if($i)
	{
		$empire->query("delete from {$dbtbpre}enewsfile where id='$id' and classid='$classid'");
	}
	//删除评论
	if($delpl==0)
	{
		$empire->query("delete from {$dbtbpre}enewspl where id='$id' and classid='$classid'");
		$tbr=$empire->fetch1("select pldatatbs from {$dbtbpre}enewspublic limit 1");
		if($tbr['pldatatbs'])
		{
			$dtbr=explode(',',$tbr['pldatatbs']);
			$count=count($dtbr)-1;
			for($i=1;$i<$count;$i++)
			{
				$empire->query("delete from {$dbtbpre}enewspl_data_".$dtbr[$i]." where id='$id' and classid='$classid'");
			}
		}
	}
}

//删除信息文件
function DelNewsFile($filename,$newspath,$classid,$newstext,$groupid=0){
	global $class_r,$addgethtmlpath;
	//文件类型
	if($groupid)
	{
		$filetype=".php";
	}
	else
	{
		$filetype=$class_r[$classid][filetype];
	}
	//是否有日期目录
	if(empty($newspath))
	{
		$mynewspath="";
    }
	else
	{
		$mynewspath=$newspath."/";
    }
	$iclasspath=ReturnSaveInfoPath($classid,$id);
	$r=explode("[!--empirenews.page--]",$newstext);
	for($i=1;$i<=count($r);$i++)
	{
		if(strstr($filename,'/'))
		{
			DelPath(ECMS_PATH.$iclasspath.$mynewspath.ReturnInfoSPath($filename));
		}
		else
		{
			if($i==1)
			{
				$file=ECMS_PATH.$iclasspath.$mynewspath.$filename.$filetype;
			}
			else
			{
				$file=ECMS_PATH.$iclasspath.$mynewspath.$filename."_".$i.$filetype;
			}
			DelFiletext($file);
		}
	}
}

//替换图片标签
function RepImg($text,$copyflash){
	global $saveurlimgclearurl;
	$exp1="[--copyimg--]";
	$exp2="[/--copyimg--]";
	//去掉图片链接
	if($saveurlimgclearurl==1)
	{
		$zz2="/\<(a|A) (.*?)(href|Href)=('|\"|\\\\\"|)(.+?)><(img|IMG) (.*?)(src|SRC)=('|\"|\\\\\"|)(.+?)(.jpg|.JPG|.gif|.GIF|.png|.PNG|.bmp|.BMP|.jpeg|.JPEG)(.*?)><\/(a|A)>/is";
		$text=preg_replace($zz2,"<\\6 \\7\\8=\\9\\10\\11\\12>",$text);
	}
	$zz1="/\<(img|IMG) (.*?)(src|SRC)=('|\"|\\\\\"|)(.+?)(.jpg|.JPG|.gif|.GIF|.png|.PNG|.bmp|.BMP|.jpeg|.JPEG)(.*?)>/is";
	$text=preg_replace($zz1,"<\\1 \\2\\3=\\4".$exp1."\\5\\6".$exp2."\\7>",$text);
	return $text;
}

//替换flash标签
function RepFlash($text,$copyflash){
	$exp1="[--copyimg--]";
	$exp2="[/--copyimg--]";
	//去掉flash多余链接
	$zz2="/\<(embed|EMBED) (.*?)(src|SRC)=('|\"|\\\\\"|)(.+?)(.swf|.SWF)(.*?)>(.*?)<\/(embed|EMBED)>/is";
	$text=preg_replace($zz2,"",$text);
	$zz3="/\<(param|PARAM) (name|NAME)=\"(Src|src|SRC)\" (.*?)(value|VALUE)=('|\"|\\\\\"|)(.+?)(.swf|.SWF)(.*?)>/is";
	$text=preg_replace($zz3,"",$text);

	$zz1="/\<(param|PARAM) (.*?)(name|NAME)=\"(movie|MOVIE)\" (.*?)(value|VALUE)=('|\"|\\\\\"|)(.+?)(.swf|.SWF)(.*?)>/is";
	$text=preg_replace($zz1,"<\\1 \\2\\3=\"\\4\" \\5\\6=\\7".$exp1."\\8\\9".$exp2."\\10>",$text);
	return $text;
}

//截取图片
function CopyImg($text,$copyimg,$copyflash,$classid,$qz,$username,$theid,$cjid,$mark){
	global $empire,$public_r,$cjnewsurl,$navtheid,$dbtbpre;
	if(empty($text))
	{return "";}
	if($copyimg)
	{
		$text=RepImg($text,$copyflash);
	}
	if($copyflash)
	{$text=RepFlash($text,$copyflash);}
	$exp1="[--copyimg--]";
	$exp2="[/--copyimg--]";
	$r=explode($exp1,$text);
	for($i=1;$i<count($r);$i++)
	{
		$r1=explode($exp2,$r[$i]);
		if(strstr($r1[0],"http://")||strstr($r1[0],"https://"))
	    {
			$dourl=$r1[0];
		}
		else
	    {
			//是否是本地址
			if(!strstr($r1[0],"/")&&$cjnewsurl)
			{
				$fileqz_r=GetPageurlQz($cjnewsurl);
				$fileqz=$fileqz_r['selfqz'];
				$dourl=$fileqz.$r1[0];
			}
			else
			{
				$dourl=$qz.$r1[0];
			}
		}
		$return_r=DoTranUrl($dourl,$classid);
		$text=str_replace($exp1.$r1[0].$exp2,$return_r[url],$text);
		if($return_r[tran])
	    {
			//记录数据库
			$filetime=date("Y-m-d H:i:s");
			//变量处理
			$return_r[filesize]=(int)$return_r[filesize];
			$classid=(int)$classid;
			$return_r[type]=(int)$return_r[type];
			$theid=(int)$theid;
			$cjid=(int)$cjid;
			$sql=$empire->query("insert into {$dbtbpre}enewsfile(filename,filesize,adduser,path,filetime,classid,no,type,id,cjid,onclick,fpath) values('$return_r[filename]',$return_r[filesize],'$username','$return_r[filepath]','$filetime',$classid,'[URL]".$return_r[filename]."',$return_r[type],$theid,$cjid,0,'$public_r[fpath]');");
			//加水
			if($mark&&$return_r[type]==1)
			{
				GetMyMarkImg($return_r['yname']);
			}
        }
	}
	return $text;
}

//生成缩略图
function GetMySmallImg($classid,$no,$insertfile,$filepath,$yname,$maxwidth,$maxheight,$name,$id,$cjid,$userid,$username){
	global $empire,$dbtbpre,$public_r;
	if(empty($yname))
	{
		return "";
    }
	$no="[s]".$no;
	$filer=ResizeImage($yname,$name,$maxwidth,$maxheight,$public_r['spickill']);
	if($filer['file'])
	{
		$insertfile="small".$insertfile.$filer['filetype'];
		$filesize=@filesize($filer['file']);
		//写入数据库
		$filetime=date("Y-m-d H:i:s");
		//变量处理
		$filesize=(int)$filesize;
		$classid=(int)$classid;
		$id=(int)$id;
		$cjid=(int)$cjid;
		$sql=$empire->query("insert into {$dbtbpre}enewsfile(filename,filesize,adduser,path,filetime,classid,no,type,id,cjid,onclick,fpath) values('$insertfile',$filesize,'$username','$filepath','$filetime',$classid,'$no',1,$id,$cjid,0,'$public_r[fpath]');");
    }
	return $filer;
}

//图片加水印
function GetMyMarkImg($groundImage){
	global $public_r;
	if(empty($groundImage))
	{
		return "";
    }
	imageWaterMark($groundImage,$public_r['markpos'],$public_r['markimg'],$public_r['marktext'],$public_r['markfontsize'],$public_r['markfontcolor'],$public_r['markfont'],$public_r['markpct'],$public_r['jpgquality']);
}

//投票组合
function ReturnVote($votename,$votenum,$delvid,$vid,$enews=0){
	global $empire,$dbtbpre;
	$f_exp="::::::";
	$r_exp="\r\n";
	$returnstr="";
	//增加投票
	if(empty($enews))
	{
		for($i=0;$i<count($votename);$i++)
		{
			//替换非法字符
			$name=str_replace($f_exp,"",$votename[$i]);
			$name=str_replace($r_exp,"",$votename[$i]);
			$num=str_replace($f_exp,"",$votenum[$i]);
			$num=str_replace($r_exp,"",$votenum[$i]);
			if($name)
			{
				if(empty($num))
				{$num=0;}
				$returnstr.=$name.$f_exp.$num.$r_exp;
			}
		}
	}
	//修改投票
	else
	{
		for($i=0;$i<count($votename);$i++)
		{
			//删除下载地址
			$del=0;
			for($j=0;$j<count($delvid);$j++)
			{
				if($delvid[$j]==$vid[$i])
				{$del=1;}
			}
			if($del)
			{continue;}
			//替换非法字符
			$name=str_replace($f_exp,"",$votename[$i]);
			$name=str_replace($r_exp,"",$votename[$i]);
			$num=str_replace($f_exp,"",$votenum[$i]);
			$num=str_replace($r_exp,"",$votenum[$i]);
			if($name)
			{
				if(empty($num))
				{$num=0;}
				$returnstr.=$name.$f_exp.$num.$r_exp;
			}
		}
	}
	/*
	if(empty($returnstr))
	{printerror("EmptyVotenum","history.go(-1)");}
	*/
	//去掉最后的字符
	$returnstr=substr($returnstr,0,strlen($returnstr)-2);
	return $returnstr;
}

//显示无限级栏目[增加栏目时]
function ShowClass_AddClass($adminclass,$obclassid,$bclassid,$exp,$modid,$enews=0,$addminfocid=''){
	global $empire,$dbtbpre;
	if(empty($bclassid))
	{
		$bclassid=0;
		$exp="|-";
		if($enews==2)
		{
			$modr=$empire->fetch1("select sonclass from {$dbtbpre}enewsmod where mid='$modid'");
			$addminfocid=$modr['sonclass'];
		}
    }
	else
	{$exp="&nbsp;&nbsp;".$exp;}
	$sql=$empire->query("select classid,classname,bclassid,islast,openadd,modid,sonclass from {$dbtbpre}enewsclass where bclassid='$bclassid' and wburl='' order by myorder,classid");
	$returnstr="";
	while($r=$empire->fetch($sql))
	{
		//投稿显示
		if($enews==2)
		{
			if($r[openadd])
			{
				continue;
			}
			if(CheckHaveInClassid($r,$addminfocid)==0)
			{
				continue;
			}
		}
		if($r[islast])
		{
			if(empty($enews)||$enews==2||$enews==3||$enews==4)
			{
				$color=" style='background:#99C4E3'";
			}
			//隐藏不能投稿的栏目
			if($enews==2)
			{
				if($modid)
				{
					if($r[modid]<>$modid)
				    {continue;}
				}
			}
			//模型
			if($enews==4)
			{
				if($r[modid]<>$modid)
				{continue;}
			}
		}
		else
		{$color="";}
		if($r[classid]==$obclassid)
		{$select=" selected";}
		else
		{$select="";}
		//-----------增加用户时
		if($enews==3)
		{
			$c=explode("|".$r[classid]."|",$adminclass);
			if(count($c)>1)
			{$select=" selected";}
			else
			{$select="";}
	    }
		$returnstr.="<option value=".$r[classid].$select.$color.">".$exp.$r[classname]."</option>";
		$returnstr.=ShowClass_AddClass($adminclass,$obclassid,$r[classid],$exp,$modid,$enews,$addminfocid);
	}
	return $returnstr;
}

//设置伸缩
function SetDisplayClass($open){
	$time=time()+365*24*3600;
	$set=esetcookie("displayclass",$open,$time,1);
	echo"<script>self.location.href='ListClass.php';</script>";
	exit();
}

//删除目录函数
function DelPath($DelPath){
	if($DelPath=="../../"||$DelPath=="../../d/file/")
	{return "";}
	$wm_chief=new del_path();
	$wm_chief_ok=$wm_chief->wm_chief_delpath($DelPath);
	return $wm_chief_ok;
}

//复制目录
function CopyPath($oldpath,$newpath){
	$wm_chief=new copy_path();
    $wm_chief_ok=$wm_chief->wm_chief_copypath($oldpath,$newpath);
	return $wm_chief_ok;
}

//移动目录
function MovePath($oldpath,$newpath){
	//复制
	CopyPath($oldpath,$newpath);
	//删除
	DelPath($oldpath);
}

//替换字符
function RepInfoZZ($text,$exp,$enews=0){
	$text=str_replace("*","(.*?)",$text);
	$text=str_replace("[!--".$exp."--]","(.*?)",$text);
	//$text=str_replace("\\","\\\\",$text);
	//$text=str_replace("^","\^",$text);
	//$text=str_replace("\"","\"",$text);
	$text=str_replace("/","\/",$text);
	$text="/".$text."/is";
	return $text;
}

//取得地址前缀
function GetPageurlQz($self){
	$sr=explode("/",$self);
	$count=count($sr)-1;
	$sfile=$sr[$count];
	$r[selfqz]=substr($self,0,strlen($self)-strlen($sfile));
	//取得域名
	$sr1=explode("http://",$self);
	$sr2=explode("/",$sr1[1]);
	$r[domain]="http://".$sr2[0];
	return $r;
}

//去掉单引号
function RepDyh($text){
	//$text=str_replace("\'","\\\'",stripSlashes($text));
	$text=addslashes(stripSlashes($text));
	return $text;
}

//补零
function AddNumZero($no,$endno){
	$len=strlen($endno);
	$forlen=$len-strlen($no);
	for($i=1;$i<=$forlen;$i++)
	{
		$no="0".$no;
	}
	return $no;
}

//自动分页
function AutoDoPage($mybody,$spsize){
  $sptag="[!--empirenews.page--]";
  if(strlen($mybody)<$spsize) return $mybody;
  $bds = explode('<',$mybody);
  $npageBody = "";
  $istable = 0;
  $mybody = "";
  foreach($bds as $i=>$k)
  {
  	 if($i==0){ $npageBody .= $bds[$i]; continue;}
  	 $bds[$i] = "<".$bds[$i];
  	 if(strlen($bds[$i])>6){
  		  $tname = substr($bds[$i],1,5);
  		  if(strtolower($tname)=='table') $istable++;
  		  else if(strtolower($tname)=='/tabl') $istable--;
  		  if($istable>0){ $npageBody .= $bds[$i]; continue; }
  		  else $npageBody .= $bds[$i];
  	 }else{
  		  $npageBody .= $bds[$i];
  	 }
  	 if(strlen($npageBody)>$spsize){
  		  $mybody .= $npageBody.$sptag;
  		  $npageBody = "";
     }
  }
  if($npageBody!="") $mybody .= $npageBody;
  return $mybody;
}



//-------------- 模板区 ----------------------

//取得模型ID
function GetListtempMid($tempid){
	global $empire;
	$r=$empire->fetch1("select modid from ".GetTemptb("enewslisttemp")." where tempid='$tempid'");
	return $r[modid];
}

//替换模板JS地址
function RepTemplateJsUrl($temp,$classid,$enews=0){
	global $public_r,$class_r,$class_zr;
	$allpath='[!--news.url--]d/js/js/';
	$temp=str_replace("[!--hotnews--]","<script src='".$allpath."hotnews.js'></script>",$temp);
	$temp=str_replace("[!--newnews--]","<script src='".$allpath."newnews.js'></script>",$temp);
	$temp=str_replace("[!--goodnews--]","<script src='".$allpath."goodnews.js'></script>",$temp);
	$temp=str_replace("[!--hotplnews--]","<script src='".$allpath."hotplnews.js'></script>",$temp);
	$temp=str_replace("[!--firstnews--]","<script src='".$allpath."firstnews.js'></script>",$temp);
	if(!empty($classid))
	{
		$path=$enews==1?'[!--news.url--]d/js/class/zt[!--self.classid--]_':'[!--news.url--]d/js/class/class[!--self.classid--]_';
		$temp=str_replace("[!--self.hotnews--]","<script src='".$path."hotnews.js'></script>",$temp);
		$temp=str_replace("[!--self.newnews--]","<script src='".$path."newnews.js'></script>",$temp);
		$temp=str_replace("[!--self.goodnews--]","<script src='".$path."goodnews.js'></script>",$temp);
		$temp=str_replace("[!--self.hotplnews--]","<script src='".$path."hotplnews.js'></script>",$temp);
		$temp=str_replace("[!--self.firstnews--]","<script src='".$path."firstnews.js'></script>",$temp);
	}
	return $temp;
}




//-------------- 生成区 ----------------------

//取得列表模板
function GetListTemp($tempid){
	global $empire;
	$r=$empire->fetch1("select temptext,subnews,listvar,rownum,showdate,modid,subtitle,docode from ".GetTemptb("enewslisttemp")." where tempid='$tempid'");
	$r[temptext]=InfoNewsBq('list'.$tempid,$r[temptext]);
	return $r;
}

//取得封面模板
function GetClassTemp($tempid){
	global $empire;
	$r=$empire->fetch1("select temptext from ".GetTemptb("enewsclasstemp")." where tempid='$tempid'");
	return $r['temptext'];
}

//取得栏目页面内容
function GetClassText($classid){
	global $empire,$dbtbpre;
	$r=$empire->fetch1("select classtext from {$dbtbpre}enewsclassadd where classid='$classid'");
	return $r['classtext'];
}

//取得首页模板
function GetIndextemp(){
	global $empire;
	$r=$empire->fetch1("select indextemp from ".GetTemptb("enewspubtemp")." limit 1");
	return $r['indextemp'];
}

//取得内容模板
function GetNewsTemp($newstempid){
	global $empire,$public_r;
	$r=$empire->fetch1("select temptext,showdate from ".GetTemptb("enewsnewstemp")." where tempid='$newstempid'");
	$r[temptext]=InfoNewsBq('news'.$newstempid,$r[temptext]);
	if($public_r[opennotcj])//启用反采集
	{
		$r[temptext]=ReturnNotcj($r[temptext]);
    }
	return $r;
}

//取得js模板
function GetTheJstemp($tempid){
	global $empire;
	$r=$empire->fetch1("select temptext,showdate,modid,subnews,subtitle from ".GetTemptb("enewsjstemp")." where tempid='$tempid'");
	return $r;
}

//替换全局模板变量
function ReplaceTempvar($temp){
	global $empire;
	if(empty($temp))
	{return $temp;}
	$sql=$empire->query("select myvar,varvalue from ".GetTemptb("enewstempvar")." where isclose=0 order by myorder desc,varid");
	while($r=$empire->fetch($sql))
	{
		$temp=str_replace('[!--temp.'.$r[myvar].'--]',$r[varvalue],$temp);;
    }
	return $temp;
}

//栏目页替换公共标记
function Class_ReplaceSvars($temp,$url,$classid,$title,$key,$des,$classimg,$add,$enews=0){
	global $public_r,$class_r,$class_zr;
	$temp=str_replace('[!--class.menu--]',$public_r['classnavs'],$temp);//栏目导航
	$temp=str_replace('[!--pagetitle--]',$title,$temp);
	$temp=str_replace('[!--pagekey--]',$key,$temp);
	$temp=str_replace('[!--pagedes--]',$des,$temp);
	$temp=str_replace('[!--class.intro--]',$des,$temp);
	$temp=str_replace('[!--class.keywords--]',$key,$temp);
	$temp=str_replace('[!--class.classimg--]',$classimg,$temp);
	$temp=str_replace('[!--self.classid--]',$classid,$temp);
	if($enews==0)//栏目
	{
		$temp=str_replace('[!--class.name--]',$class_r[$classid]['classname'],$temp);
		$bclassid=$class_r[$classid]['bclassid'];
		$temp=str_replace('[!--bclass.id--]',$bclassid,$temp);
		$temp=str_replace('[!--bclass.name--]',$class_r[$bclassid]['classname'],$temp);
		$path=$public_r['newsurl'].'d/js/class/class'.$classid.'_';
	}
	else//专题
	{
		$temp=str_replace('[!--class.name--]',$class_zr[$classid]['ztname'],$temp);
		$path=$public_r['newsurl'].'d/js/class/zt'.$classid.'_';
	}
	$allpath=$public_r[newsurl].'d/js/js/';
	//热门文章
	$temp=str_replace("[!--hotnews--]","<script src='".$allpath."hotnews.js'></script>",$temp);
	$temp=str_replace("[!--self.hotnews--]","<script src='".$path."hotnews.js'></script>",$temp);
	//点击排行
	$temp=str_replace("[!--newnews--]","<script src='".$allpath."newnews.js'></script>",$temp);
	$temp=str_replace("[!--self.newnews--]","<script src='".$path."newnews.js'></script>",$temp);
	//推荐
	$temp=str_replace("[!--goodnews--]","<script src='".$allpath."goodnews.js'></script>",$temp);
	$temp=str_replace("[!--self.goodnews--]","<script src='".$path."goodnews.js'></script>",$temp);
	//评论排行
	$temp=str_replace("[!--hotplnews--]","<script src='".$allpath."hotplnews.js'></script>",$temp);
	$temp=str_replace("[!--self.hotplnews--]","<script src='".$path."hotplnews.js'></script>",$temp);
	//头条排行
	$temp=str_replace("[!--firstnews--]","<script src='".$allpath."firstnews.js'></script>",$temp);
	$temp=str_replace("[!--self.firstnews--]","<script src='".$path."firstnews.js'></script>",$temp);
	$temp=str_replace('[!--news.url--]',$public_r['newsurl'],$temp);
	return $temp;
}

//内容页替换公共标记
function Info_ReplaceSvars($temp,$url,$classid,$title,$key,$des){
	global $public_r,$class_r;
	$temp=str_replace('[!--class.menu--]',$public_r['classnavs'],$temp);//栏目导航
	$temp=str_replace('[!--newsnav--]',$url,$temp);//位置导航
	$temp=str_replace('[!--pagetitle--]',$title,$temp);
	$temp=str_replace('[!--pagekey--]',$key,$temp);
	$temp=str_replace('[!--pagedes--]',$des,$temp);
	$temp=str_replace('[!--self.classid--]',$classid,$temp);
	$bclassid=$class_r[$classid]['bclassid'];
	$temp=str_replace('[!--bclass.id--]',$bclassid,$temp);
	$temp=str_replace('[!--bclass.name--]',$class_r[$bclassid]['classname'],$temp);
	$temp=str_replace('[!--news.url--]',$public_r['newsurl'],$temp);
	return $temp;
}

//替换搜索模板文件
function ReplaceStemp($temptext,$class,$url,$classid,$title,$key,$des,$repvar=1){
	global $public_r;
	if($repvar==1)//全局模板变量
	{
		$temptext=ReplaceTempvar($temptext);
	}
	$temptext=str_replace('[!--class.menu--]',$public_r['classnavs'],$temptext);//栏目导航
	$temptext=str_replace("[!--class--]",$class,$temptext);
	$temptext=str_replace('[!--pagetitle--]',$title,$temptext);
	$temptext=str_replace('[!--pagekey--]',$key,$temptext);
	$temptext=str_replace('[!--pagedes--]',$des,$temptext);
	$temptext=str_replace('[!--self.classid--]',$classid,$temptext);
	//热门文章
	$temptext=str_replace("[!--hotnews--]","<script src='".$public_r[newsurl]."d/js/js/hotnews.js'></script>",$temptext);
	//点击排行
	$temptext=str_replace("[!--newnews--]","<script src='".$public_r[newsurl]."d/js/js/newnews.js'></script>",$temptext);
	//推荐
	$temptext=str_replace("[!--goodnews--]","<script src='".$public_r[newsurl]."d/js/js/goodnews.js'></script>",$temptext);
	//评论排行
	$temptext=str_replace("[!--hotplnews--]","<script src='".$public_r[newsurl]."d/js/js/hotplnews.js'></script>",$temptext);
	//导航条
	$temptext=str_replace("[!--url--]",$url,$temptext);
	$temptext=str_replace('[!--newsnav--]',$url,$temptext);//位置导航
	$temptext=str_replace("[!--news.url--]",$public_r[newsurl],$temptext);
	$temptext=str_replace("[!--newsurl--]",$public_r[newsurl],$temptext);
	return $temptext;
}

//栏目页验证
function AddCheckClassLevel($classid,$groupid,$classpath){
	$classpath=ReturnSaveClassPath($classid);
	$pr=explode('/',$classpath);
	$pcount=count($pr);
	for($i=0;$i<$pcount;$i++)
	{
		$include.='../';
	}
	$include1=$include;
	$include.='e/class/CheckClassLevel.php';
	$addlevel="<?php
	define('empirecms','wm_chief');
	\$check_groupid=".$groupid.";
	\$check_classid=".$classid.";
	\$check_path=\"".$include1."\";
	require(\"".$include."\");
	?>";
	return $addlevel;
}

//标签替换
function NewsBq($classid,$indextext,$enews=0,$doing=0){
	global $empire,$dbtbpre,$public_r,$emod_r,$class_r,$class_zr,$fun_r,$navclassid,$navinfor,$class_tr,$level_r;
	$indextext=stripSlashes($indextext);
	$indextext=ReplaceTempvar($indextext);//替换全局模板变量
	$classlevel='';
	if($enews==0)//生成大栏目
	{
		if(strstr($public_r['nreclass'],','.$classid.',')||$class_r[$classid][wburl])//不生成栏目
		{
			return '';
		}
		$GLOBALS['navclassid']=$classid;
		$url=ReturnClassLink($classid);//导航
		$cf=$doing==1?',classpath,classtype,classname':'';
		$cr=$empire->fetch1("select classpagekey,intro,classimg,cgroupid".$cf." from {$dbtbpre}enewsclass where classid='$classid'");
		if(!empty($cf))
		{
			$class_r[$classid][classpath]=$cr[classpath];
			$class_r[$classid][classtype]=$cr[classtype];
			$class_r[$classid][classname]=$cr[classname];
		}
		//权限
		if($cr['cgroupid'])
		{
			$classlevel=AddCheckClassLevel($classid,$cr['cgroupid'],'');
		}
		//页面
		$pagetitle=htmlspecialchars($class_r[$classid][classname]);
		$pagekey=htmlspecialchars($cr['classpagekey']);
		$pagedes=htmlspecialchars($cr['intro']);
		$classimg=$cr['classimg'];
		$onclick="<script src=".$public_r[newsurl]."e/public/onclick?enews=doclass&classid=$classid></script>";
		$truefile=ECMS_PATH.ReturnSaveClassPath($classid,1);
		$file=ECMS_PATH.'e/data/tmp/class'.$classid.'.php';
		$indextext=str_replace("[!--newsnav--]",$url,$indextext);//位置导航
		$indextext=Class_ReplaceSvars($indextext,$url,$classid,$pagetitle,$pagekey,$pagedes,$classimg,$add,0);
	}
	elseif($enews==3)//专题
	{
		$GLOBALS['navclassid']=$classid;
		$url=ReturnZtLink($classid);//导航
		$cf=$doing==1?',ztpath,zttype,ztname':'';
		$cr=$empire->fetch1("select ztpagekey,intro,ztimg".$cf." from {$dbtbpre}enewszt where ztid='$classid'");
		if(!empty($cf))
		{
			$class_zr[$classid][ztpath]=$cr[ztpath];
			$class_zr[$classid][zttype]=$cr[zttype];
			$class_zr[$classid][ztname]=$cr[ztname];
	    }
		$pagetitle=htmlspecialchars($class_zr[$classid][ztname]);
		$pagekey=htmlspecialchars($cr['ztpagekey']);
		$pagedes=htmlspecialchars($cr['intro']);
		$classimg=$cr['ztimg'];
		$onclick="<script src=".$public_r[newsurl]."e/public/onclick?enews=dozt&ztid=$ztid></script>";
		$truefile=ECMS_PATH.ReturnSaveZtPath($classid,1);
		$file=ECMS_PATH.'e/data/tmp/zt'.$classid.'.php';
		$indextext=str_replace("[!--newsnav--]",$url,$indextext);//位置导航
		$indextext=Class_ReplaceSvars($indextext,$url,$classid,$pagetitle,$pagekey,$pagedes,$classimg,$add,1);
	}
	elseif($enews==1)//生成首页文件
	{
		$pr=$empire->fetch1("select sitekey,siteintro from {$dbtbpre}enewspublic limit 1");
		//页面
		$pagetitle=htmlspecialchars($public_r['sitename']);
		$pagekey=htmlspecialchars($pr['sitekey']);
		$pagedes=htmlspecialchars($pr['siteintro']);
		$url="<a href=\"".$public_r[newsurl]."\">".$fun_r['index']."</a>";//栏目导航
		$onclick='';
		$truefile=ECMS_PATH.ReturnSaveIndexFile();
		$file=ECMS_PATH.'e/data/tmp/index.php';
		$indextext=ReplaceSvars($indextext,$url,0,$pagetitle,$pagekey,$pagedes,$add,0);
	}
	$indextext=str_replace("[!--page.stats--]",$onclick,$indextext);
	//替换标签
	$indextext=DoRepEcmsLoopBq($indextext);
	$indextext=RepBq($indextext);
	//写文件
	WriteFiletext($file,AddCheckViewTempCode().$indextext);
	//读取文件内容
    ob_start();
	include($file);
	$string=ob_get_contents();
	ob_end_clean();
	$string=RepExeCode($string);//解析代码
	WriteFiletext($truefile,$classlevel.$string);
	return $string;
}

//标签替换2
function InfoNewsBq($classid,$indextext){
	global $empire,$dbtbpre,$public_r,$emod_r,$class_r,$class_zr,$fun_r,$navclassid,$navinfor,$class_tr,$level_r;
	if($_GET['reallinfotime'])
	{
		$classid.='_all';
	}
	$file=ECMS_PATH.'e/data/tmp/temp'.$classid.'.php';
	if($_GET['reallinfotime']&&file_exists($file))
	{
		$filetime=filemtime($file);
		if($_GET['reallinfotime']<=$filetime)
		{
			ob_start();
			include($file);
			$string=ob_get_contents();
			ob_end_clean();
			$string=RepExeCode($string);//解析代码
			return $string;
		}
	}
	$indextext=stripSlashes($indextext);
	$indextext=ReplaceTempvar($indextext);//替换全局模板变量
	//替换标签
	$indextext=DoRepEcmsLoopBq($indextext);
	$indextext=RepBq($indextext);
	//写文件
	WriteFiletext($file,AddCheckViewTempCode().$indextext);
	//读取文件内容
    ob_start();
	include($file);
	$string=ob_get_contents();
	ob_end_clean();
	$string=RepExeCode($string);//解析代码
	return $string;
}

//标签替换3
function DtNewsBq($classid,$indextext,$ecms=0){
	global $empire,$dbtbpre,$public_r,$emod_r,$class_r,$class_zr,$fun_r,$navclassid,$navinfor,$class_tr,$level_r;
	$cachetime=$ecms==1?$public_r['dtncachetime']:$public_r['dtcachetime'];
	$file=ECMS_PATH.'e/data/tmp/dt_temp'.$classid.'.php';
	if($cachetime&&file_exists($file))
	{
		$filetime=filemtime($file);
		if(time()-$cachetime*60<=$filetime)
		{
			ob_start();
			include($file);
			$string=ob_get_contents();
			ob_end_clean();
			$string=RepExeCode($string);//解析代码
			return $string;
		}
	}
	$indextext=stripSlashes($indextext);
	$indextext=ReplaceTempvar($indextext);//替换全局模板变量
	//替换标签
	$indextext=DoRepEcmsLoopBq($indextext);
	$indextext=RepBq($indextext);
	//写文件
	WriteFiletext($file,AddCheckViewTempCode().$indextext);
	//读取文件内容
    ob_start();
	include($file);
	$string=ob_get_contents();
	ob_end_clean();
	$string=RepExeCode($string);//解析代码
	return $string;
}

//解析代码
function RepExeCode($string){
	global $public_r;
	if($public_r[candocode])
	{
		$string=str_replace('<!--code.start-->','<',$string);
		$string=str_replace('<!--code.end-->','>',$string);
    }
	return $string;
}

function ClearRepDoECode($string){
	$string=str_replace('<!--code.start-->','&lt;!--code.start--&gt;',$string);
	$string=str_replace('<!--code.end-->','&lt;!--code.end--&gt;',$string);
	return $string;
}

//替换标签
function RepBq($indextext){
	global $empire,$dbtbpre;
	$sql=$empire->query("select bq,funname from {$dbtbpre}enewsbq where isclose=0 order by bqid");
	while($r=$empire->fetch($sql))
	{
        $preg_str="/\[".$r[bq]."\](.+?)\[\/".$r[bq]."\]/is";
		$indextext=preg_replace($preg_str,"<? @".$r[funname]."(\\1);?>",$indextext);
	}
	return $indextext;
}

//替换灵动标签
function DoRepEcmsLoopBq($temp){
	$yzz="/\[e:loop={(.+?)}\](.+?)\[\/e:loop\]/is";
	$xzz="<?php
\$bqno=0;
\$ecms_bq_sql=sys_ReturnEcmsLoopBq(\\1);
while(\$bqr=\$empire->fetch(\$ecms_bq_sql)){
\$bqsr=sys_ReturnEcmsLoopStext(\$bqr);
\$bqno++;
?>\\2<?php
}
?>";
	return preg_replace($yzz,$xzz,$temp);
}

//无信息的信息列表
function NotinfoListHtml($path,$list_r,$classlevel){
	global $fun_r;
	$word=$fun_r['HaveNotListInfo'];
	$pagetext=$list_r[0].$word.$list_r[2];
	$pagetext=str_replace('[!--show.page--]','',$pagetext);
	$pagetext=str_replace('[!--show.listpage--]','',$pagetext);
	$pagetext=str_replace('[!--list.pageno--]','',$pagetext);
	WriteFiletext($path,$classlevel.$pagetext);
}

//生成信息列表
function ListHtml($classid,$fields,$enews=0,$userlistr=""){
	global $empire,$dbtbpre,$emod_r,$public_r,$class_r,$class_zr,$fun_r,$class_tr,$level_r;
	//不生成栏目
	if(($enews==0||$enews==3)&&(strstr($public_r['nreclass'],','.$classid.',')||$class_r[$classid][wburl]))
	{
		return '';
	}
	$GLOBALS['navclassid']=$classid;
	$doclass="index";
	$classlevel='';
	if($enews==0)//子栏目列表
	{
		$selfclassid=$classid;
		$doenews=0;
		$cr=$empire->fetch1("select classpagekey,intro,classimg,cgroupid from {$dbtbpre}enewsclass where classid='$classid'");
		$mid=$class_r[$classid][modid];
		//权限
		if($cr['cgroupid'])
		{
			$classlevel=AddCheckClassLevel($classid,$cr['cgroupid'],'');
		}
		//页面
		$pagetitle=htmlspecialchars($class_r[$classid][classname]);
		$pagekey=htmlspecialchars($cr['classpagekey']);
		$pagedes=htmlspecialchars($cr['intro']);
		$classimg=$cr['classimg'];
		$url=ReturnClassLink($classid);
		$haveclass=0;
		//排序
		if(empty($class_r[$classid][reorderf]))
		{
			$addorder=",newstime desc";
	    }
		else
		{
			$addorder=",".$class_r[$classid][reorderf]." ".$class_r[$classid][reorder];
	    }
		if($class_r[$classid][maxnum])//总记录数
		{
			$limit=" limit ".$class_r[$classid][maxnum];
			$limitnum=$class_r[$classid][maxnum];
		}
		$query="select * from {$dbtbpre}ecms_".$class_r[$classid][tbname]." where classid='$classid' and checked=1 order by istop desc".$addorder.",id desc".$limit;
		$totalquery="select count(*) as total from {$dbtbpre}ecms_".$class_r[$classid][tbname]." where classid='$classid' and checked=1";//统计
		$doclasspath=ReturnSaveClassPath($classid,0);
		$dopath=ECMS_PATH.$doclasspath."/";
		if(empty($class_r[$classid][classurl]))
		{
			$dolink=$public_r[newsurl].$doclasspath."/";
		}
		else
		{
			$dolink=$class_r[$classid][classurl]."/";
		}
		$dotype=$class_r[$classid][classtype];
		$classname=$class_r[$classid][classname];
		$lencord=$class_r[$classid][lencord];//记录数
		$onclick="<script src='".$public_r[newsurl]."e/public/onclick?enews=doclass&classid=$classid'></script>";
		//模板
		$listtempid=$class_r[$classid][listtempid];
	}
	elseif($enews==1)//专题列表
	{
		$selfclassid=$classid;
		$doenews=1;
		$cr=$empire->fetch1("select ztpagekey,intro,ztimg,classtempid from {$dbtbpre}enewszt where ztid='$classid'");
		//页面
		$pagetitle=htmlspecialchars($class_zr[$classid][ztname]);
		$pagekey=htmlspecialchars($cr['ztpagekey']);
		$pagedes=htmlspecialchars($cr['intro']);
		$classimg=$cr['ztimg'];
		$url=ReturnZtLink($classid);
		$haveclass=1;
		if(!$class_zr[$classid][islist])//非列表式
		{
			NewsBq($classid,GetClassTemp($cr['classtempid']),3,0);
			return "";
		}
		//排序
		if(empty($class_zr[$classid][reorderf]))
		{
			$addorder=",newstime desc";
	    }
		else
		{
			$addorder=",".$class_zr[$classid][reorderf]." ".$class_zr[$classid][reorder];
	    }
		if($class_zr[$classid][maxnum])
		{
			$limit=" limit ".$class_zr[$classid][maxnum];
			$limitnum=$class_zr[$classid][maxnum];
		}
		$query="select * from {$dbtbpre}ecms_".$class_zr[$classid][tbname]." where ztid like '%|".$classid."|%' and checked=1 order by istop desc".$addorder.",id desc".$limit;
		$totalquery="select count(*) as total from {$dbtbpre}ecms_".$class_zr[$classid][tbname]." where ztid like '%|".$classid."|%' and checked=1";//统计
		$doclasspath=ReturnSaveZtPath($classid,0);
		$dopath=ECMS_PATH.$doclasspath."/";
		if(empty($class_zr[$classid][zturl]))
		{
			$dolink=$public_r[newsurl].$doclasspath."/";
		}
		else
		{
			$dolink=$class_zr[$classid][zturl]."/";
		}
		$dotype=$class_zr[$classid][zttype];
		$classname=$class_zr[$classid][ztname];
		$lencord=$class_zr[$classid][ztnum];//记录数
		$onclick="<script src='".$public_r[newsurl]."e/public/onclick?enews=dozt&ztid=$classid'></script>";
		//模板
		$listtempid=$class_zr[$classid][listtempid];
	}
	elseif($enews==3)//大栏目列表
	{
		$selfclassid=$classid;
		$doenews=0;
		$cr=$empire->fetch1("select classpagekey,intro,classimg,cgroupid from {$dbtbpre}enewsclass where classid='$classid'");
		$mid=$class_r[$classid][modid];
		//权限
		if($cr['cgroupid'])
		{
			$classlevel=AddCheckClassLevel($classid,$cr['cgroupid'],'');
		}
		//页面
		$pagetitle=htmlspecialchars($class_r[$classid][classname]);
		$pagekey=htmlspecialchars($cr['classpagekey']);
		$pagedes=htmlspecialchars($cr['intro']);
		$classimg=$cr['classimg'];
		$url=ReturnClassLink($classid);
		$haveclass=1;
		//排序
		if(empty($class_r[$classid][reorderf]))
		{
			$addorder=",newstime desc";
	    }
		else
		{
			$addorder=",".$class_r[$classid][reorderf]." ".$class_r[$classid][reorder];
	    }
		if($class_r[$classid][maxnum])
		{
			$limit=" limit ".$class_r[$classid][maxnum];
			$limitnum=$class_r[$classid][maxnum];
		}
		$whereclass=ReturnClass($class_r[$classid][sonclass]);
		$query="select * from {$dbtbpre}ecms_".$class_r[$classid][tbname]." where (".$whereclass.") and checked=1 order by istop desc".$addorder.",id desc".$limit;
		$totalquery="select count(*) as total from {$dbtbpre}ecms_".$class_r[$classid][tbname]." where (".$whereclass.") and checked=1";//统计
		$doclasspath=ReturnSaveClassPath($classid,0);
		$dopath=ECMS_PATH.$doclasspath."/";
		if(empty($class_r[$classid][classurl]))
		{
			$dolink=$public_r[newsurl].$doclasspath."/";
		}
		else
		{
			$dolink=$class_r[$classid][classurl]."/";
		}
		$dotype=$class_r[$classid][classtype];
		$classname=$class_r[$classid][classname];
		$lencord=$class_r[$classid][lencord];//记录数
		$onclick="<script src='".$public_r[newsurl]."e/public/onclick?enews=doclass&classid=$classid'></script>";
		//模板
		$listtempid=$class_r[$classid][listtempid];
	}
	elseif($enews==4)//按sql语句生成列表
	{
		$selfclassid=0;
		$doenews=1;
		$userlistr['listsql']=RepSqlTbpre($userlistr['listsql']);
		$userlistr['totalsql']=RepSqlTbpre($userlistr['totalsql']);
		//页面
		$pagetitle=htmlspecialchars($userlistr['pagetitle']);
		$pagekey=$pagetitle;
		$pagedes=$pagetitle;
		$haveclass=1;
		if($userlistr['maxnum'])//最大查询数
		{
			$limit=" limit ".$userlistr['maxnum'];
			$limitnum=$userlistr['maxnum'];
		}
		$query=stripSlashes($userlistr['listsql']).$limit;
		//统计
		$totalquery=stripSlashes($userlistr['totalsql']);
		$dopath=$userlistr['addpath'].$userlistr['filepath'];
		$dolink=$public_r[newsurl].str_replace($userlistr['addpath'].'../../','',$dopath);
		$dotype=$userlistr['filetype'];
		$classname=$userlistr['pagetitle'];
		$lencord=$userlistr['lencord'];//记录数
		$onclick='';
		$url=ReturnUserPLink($pagetitle,$dolink);
		//模板
		$listtempid=$userlistr['listtempid'];
	}
	if(empty($lencord))
	{
		$lencord=25;
	}
	//列表模板
	$listtemp_r=GetListTemp($listtempid);
    $listtemp=$listtemp_r[temptext];
	$subnews=$listtemp_r[subnews];
	$subtitle=$listtemp_r[subtitle];
	$docode=$listtemp_r[docode];
	$listvar=str_replace('[!--news.url--]',$public_r[newsurl],$listtemp_r[listvar]);
	$rownum=$listtemp_r[rownum];
	$formatdate=$listtemp_r[showdate];
	if(empty($rownum))
	{
		$rownum=1;
	}
	if(empty($mid))
	{
		$mid=$listtemp_r[modid];
	}
	$field=ReturnReplaceListF($mid);
	//分页列表函数
	if(!empty($public_r['listpagefun'])||!empty($public_r['listpagelistfun']))
	{
		if(strstr($listtemp,'[!--show.page--]'))//下拉式
		{
			$thefun=$public_r['listpagefun'];
			$bereplistpage='[!--show.page--]';
		}
		else//列表式
		{
			$thefun=$public_r['listpagelistfun'];
			$bereplistpage='[!--show.listpage--]';
		}
	}
	else
	{
		$thefun='sys_ShowListPage';
		$bereplistpage='[!--show.page--]';
	}
	//替换模板变量
	$listtemp=str_replace('[!--newsnav--]',$url,$listtemp);//位置导航
	$listtemp=Class_ReplaceSvars($listtemp,$url,$selfclassid,$pagetitle,$pagekey,$pagedes,$classimg,$add,$doenews);
	$listtemp=str_replace('[!--page.stats--]',$onclick,$listtemp);
	$no=1;
	$ok=0;
	$changerow=1;
	$num=$empire->gettotal($totalquery);
	//最大数
	if($limitnum&&$limitnum<$num)
	{
		$num=$limitnum;
	}
	$page=ceil($num/$lencord);
	//取得列表模板
	$list_exp="[!--empirenews.listtemp--]";
	$list_r=explode($list_exp,$listtemp);
	//无信息
	if(empty($num))
	{
		$noinfopath=$dopath."index".$dotype;
		NotinfoListHtml($noinfopath,$list_r,$classlevel);
		return "";
	}
	$sql=$empire->query($query);
	$listtext=$list_r[1];
	while($k=$empire->fetch($sql))
	{
		//替换列表变量
		$repvar=ReplaceListVars($no,$listvar,$subnews,$subtitle,$formatdate,$url,$haveclass,$k,$field,$docode);
		$listtext=str_replace("<!--list.var".$changerow."-->",$repvar,$listtext);
		$changerow+=1;
		//超过行数
		if($changerow>$rownum)
		{
			$changerow=1;
			$string.=$listtext;
			$listtext=$list_r[1];
		}
		if($no%$lencord==0||($num%$lencord<>0&&$num==$no))
		{
			$ok+=1;
			$pagenum=ceil($no/$lencord);
			//首页
			if($pagenum==1)
			{
				$path=$dopath."index".$dotype;
			}
			else
			{
				$path=$dopath."index_".$ok.$dotype;
			}
			//取得分页参数
			$returnpager=$thefun($num,$pagenum,$dolink,$dotype,$page,$lencord,$ok,$myoptions);
			$showpage=$returnpager['showpage'];
			$myoptions=$returnpager['option'];
			$list1=str_replace($bereplistpage,$showpage,$list_r[0]);
			$list2=str_replace($bereplistpage,$showpage,$list_r[2]);
			//多余数据
			if($changerow<=$rownum&&$listtext<>$list_r[1])
			{
				$string.=$listtext;
			}
			$listtext=$list_r[1];
			$changerow=1;
			$string=$list1.$string.$list2;
			//替换分页数
			$string=str_replace('[!--list.pageno--]',$pagenum,$string);
			WriteFiletext($path,$classlevel.$string);
			$string='';
		}
		$no++;
	}
	$empire->free($sql);
}

//返回分页
function ReturnListpageStr($pagenum,$page,$lencord,$num,$pagelink,$options){
	global $public_r;
	$temp=$public_r['listpagetemp'];
	$temp=str_replace('[!--thispage--]',$pagenum,$temp);//页次
	$temp=str_replace('[!--pagenum--]',$page,$temp);//总页数
	$temp=str_replace('[!--lencord--]',$lencord,$temp);//每页显示条数
	$temp=str_replace('[!--num--]',$num,$temp);//总条数
	$temp=str_replace('[!--pagelink--]',$pagelink,$temp);//页面链接
	$temp=str_replace('[!--options--]',$options,$temp);//下拉分页
	return $temp;
}

//投稿生成html
function DoGetHtml($classid,$id){
	global $empire,$class_r,$public_r,$dbtbpre;
	$classid=intval($classid);
	$id=intval($id);
	$tbname=$class_r[$classid][tbname];
	//不存在
	if(!$id||!$classid||!$tbname)
	{
		echo"<script>self.location.href='".$public_r['newsurl']."';</script>";
		exit();
	}
	$r=$empire->fetch1("select * from {$dbtbpre}ecms_".$tbname." where id='$id'");
	if(!$r[id]||!$r['checked'])
	{
		echo"<script>self.location.href='".$public_r['newsurl']."';</script>";
		exit();
	}
	$titleurl=sys_ReturnBqAutoTitleLink($r);
	//已生成
	if(!empty($r[havehtml]))
	{
		return $titleurl;
	}
	//生成html
	GetHtml($r,$ret_r);
	return $titleurl;
}

//生成内容文件
function GetHtml($add,$fields,$doall=0){
	global $public_r,$class_r,$class_zr,$fun_r,$empire,$dbtbpre,$emod_r,$class_tr,$level_r;
	if(empty($doall))
	{
		if($add['titleurl']||$add['checked']==0||$class_r[$add[classid]][showdt]==2||strstr($public_r['nreinfo'],','.$add['classid'].','))//不生成
		{
			return '';
		}
	}
	$mid=$class_r[$add[classid]]['modid'];
	$tbname=$class_r[$add[classid]][tbname];
	//副表
	if($emod_r[$mid]['tbdataf']&&$emod_r[$mid]['tbdataf']<>',')
	{
		$selectdataf=substr($emod_r[$mid]['tbdataf'],1,strlen($emod_r[$mid]['tbdataf'])-2);
		$addr=$empire->fetch1("select ".$selectdataf." from {$dbtbpre}ecms_".$tbname."_data_".$add[stb]." where id='$add[id]'");
		$add=array_merge($add,$addr);
	}
	$iclasspath=ReturnSaveInfoPath($add[classid],$add[id]);
	$doclasspath=ECMS_PATH.$iclasspath;
	$createinfopath=$doclasspath;
	//建立日期目录
	$newspath='';
	if($add[newspath])
	{
		$createpath=$doclasspath.$add[newspath];
		if(!file_exists($createpath))
		{
			$r[newspath]=FormatPath($add[classid],$add[newspath],1);
		}
		$createinfopath.=$add[newspath].'/';
		$newspath=$add[newspath].'/';
	}
	//新建存放目录
	if($class_r[$add[classid]][filename]==3)
	{
		$createinfopath.=ReturnInfoSPath($add['filename']);
		DoMkdir($createinfopath);
		$fn3=1;
	}
	//存文本
	if($emod_r[$mid]['savetxtf'])
	{
		$stf=$emod_r[$mid]['savetxtf'];
		if($add[$stf])
		{
			$add[$stf]=GetTxtFieldText($add[$stf]);
		}
	}
	$GLOBALS['navclassid']=$add[classid];
	$GLOBALS['navinfor']=$add;
	//取得内容模板
	$add[newstempid]=$add[newstempid]?$add[newstempid]:$class_r[$add[classid]][newstempid];
	$newstemp_r=GetNewsTemp($add[newstempid]);
	$newstemptext=$newstemp_r[temptext];
	$formatdate=$newstemp_r[showdate];
	//页面
	$pagetitle=htmlspecialchars($add[title]);
	$url=ReturnClassLink($add[classid]);//导航
	$newstemptext=Info_ReplaceSvars($newstemptext,$url,$add[classid],$pagetitle,$add[keyboard],$pagetitle);
	//文件类型/权限
	if($add[groupid])
	{
		if(empty($add[newspath]))
		{
			$include='';
	    }
		else
		{
			$pr=explode('/',$add[newspath]);
			for($i=0;$i<count($pr);$i++)
			{
				$include.='../';
			}
		}
		if($fn3==1)
		{
			$include.='../';
		}
		$pr=explode('/',$iclasspath);
		$pcount=count($pr);
		for($i=0;$i<$pcount-1;$i++)
		{
			$include.='../';
		}
		$include1=$include;
		$include.='e/class/CheckLevel.php';
		$filetype='.php';
		$addlevel="<?php
		define('empirecms','wm_chief');
		\$check_tbname='".$class_r[$add[classid]][tbname]."';
		\$check_infoid=".$add[id].";
		\$check_classid=".$add[classid].";
		\$check_path=\"".$include1."\";
		require(\"".$include."\");
		?>";
    }
	else
	{
		$filetype=$class_r[$add[classid]][filetype];
		$addlevel='';
	}
	//取得本目录链接
	if($class_r[$add[classid]][classurl])//域名
	{
		$dolink=$class_r[$add[classid]][classurl].'/'.$newspath;
	}
	else
	{
		$dolink=$public_r[newsurl].$iclasspath.$newspath;
	}
	//相关信息
	if(strstr($newstemptext,'[!--other.link--]'))
	{
    	$keyboardtext=GetKeyboard($add[keyboard],$add[keyid],$add[classid],$add[id],$class_r[$add[classid]][link_num]);
	}
	$onclick="<script src='".$public_r[newsurl]."e/public/onclick?enews=donews&classid=$add[classid]&id=".$add[id]."'></script>";
	//返回替换验证字符
	$docheckrep=ReturnCheckDoRepStr();
	if($add[newstext])
	{
		if($docheckrep[3])
		{
			$add[newstext]=ReplaceWord($add[newstext]);//过滤字符
		}
		if($docheckrep[4]&&!empty($add[dokey]))//替换关键字
		{
			$add[newstext]=ReplaceKey($add[newstext]);
		}
		if($public_r['opencopytext'])
		{
			$add[newstext]=AddNotCopyRndStr($add[newstext]);//随机复制字符
		}
	}
	//分页字段
	$expage='[!--empirenews.page--]';//分页符
	$pf=$emod_r[$mid]['pagef'];
	//变量
	$tempf=$emod_r[$mid]['tempf'];
	if($pf&&strstr($add[$pf],$expage))
	{
		$tempf=str_replace(','.$pf.',',',',$tempf);
	}
	$fr=explode(',',$tempf);
	$fcount=count($fr)-1;
	//变量替换
	$newstempstr=$newstemptext;//模板
	for($i=1;$i<$fcount;$i++)
	{
		$f=$fr[$i];
		$value=$add[$f];
		if($f=='downpath')//下载地址
		{
			if(strstr($newstemptext,'[!--downpath--]'))
			{
				$value=ReturnDownSoftHtml($add);
			}
		}
		elseif($f=='onlinepath')//观看地址
		{
			if(strstr($newstemptext,'[!--onlinepath--]'))
			{
				$value=ReturnOnlinepathHtml($add);
			}
		}
		elseif($f=='morepic')//图片集
		{
			if(strstr($newstemptext,'[!--morepic--]'))
			{
				$value=ReturnMorepicpathHtml($add);
			}
		}
		elseif($f=='newstime')//时间
		{
			if(strstr($newstemptext,'[!--newstime--]'))
			{
				$value=date($formatdate,$value);
			}
		}
		elseif($f=='befrom')//信息来源
		{
			if($docheckrep[1]&&strstr($newstemptext,'[!--befrom--]'))
			{
				$value=ReplaceBefrom($value);
			}
		}
		elseif($f=='writer')//作者
		{
			if($docheckrep[2]&&strstr($newstemptext,'[!--writer--]'))
			{
				$value=ReplaceWriter($value);
			}
		}
		elseif($f=='titlepic')//标题图片
		{
			if(empty($value))
			{$value=$public_r[newsurl].'e/data/images/notimg.gif';}
		}
		elseif($f=='title')//标题
		{
		}
		else//正常字段
		{
			if(!strstr($emod_r[$mid]['editorf'],','.$f.','))
			{
				if(strstr($emod_r[$mid]['tobrf'],','.$f.','))//加br
				{
					$value=nl2br($value);
				}
				if(!strstr($emod_r[$mid]['dohtmlf'],','.$f.','))//去除html
				{
					$value=RepFieldtextNbsp(htmlspecialchars($value));
				}
			}
		}
		$newstempstr=str_replace('[!--'.$f.'--]',$value,$newstempstr);
	}
	//固定变量
	$newstempstr=str_replace('[!--id--]',$add[id],$newstempstr);
	$newstempstr=str_replace('[!--classid--]',$add[classid],$newstempstr);
	$newstempstr=str_replace('[!--class.name--]',$class_r[$add[classid]][classname],$newstempstr);
	$newstempstr=str_replace('[!--ttid--]',$add[ttid],$newstempstr);
	$newstempstr=str_replace('[!--tt.name--]',$class_tr[$add[ttid]][tname],$newstempstr);
	$newstempstr=str_replace('[!--onclick--]',$add[onclick],$newstempstr);
	$newstempstr=str_replace('[!--userfen--]',$add[userfen],$newstempstr);
	$newstempstr=str_replace('[!--username--]',$add[username],$newstempstr);
	//带链接的用户名
	if($add[ismember]==1&&$add[userid])
	{
		$newstempstr=str_replace('[!--linkusername--]',"<a href='".$public_r[newsurl]."e/space/?userid=".$add[userid]."' target=_blank>".$add[username]."</a>",$newstempstr);
	}
	else
	{
		$newstempstr=str_replace('[!--linkusername--]',$add[username],$newstempstr);
	}
	$newstempstr=str_replace('[!--userid--]',$add[userid],$newstempstr);
	$newstempstr=str_replace('[!--other.link--]',$keyboardtext,$newstempstr);
	$newstempstr=str_replace('[!--news.url--]',$public_r[newsurl],$newstempstr);
	$newstempstr=str_replace('[!--plnum--]',$add[plnum],$newstempstr);
	$newstempstr=str_replace('[!--totaldown--]',$add[totaldown],$newstempstr);
	$newstempstr=str_replace('[!--keyboard--]',$add[keyboard],$newstempstr);
	//链接
	$titleurl=sys_ReturnBqTitleLink($add);
	$newstempstr=str_replace('[!--titleurl--]',$titleurl,$newstempstr);
	$newstempstr=str_replace('[!--page.stats--]',$onclick,$newstempstr);
	//下一篇
	if(strstr($newstemptext,'[!--info.next--]'))
	{
		$next_r=$empire->fetch1("select titleurl,groupid,classid,newspath,filename,id,title from {$dbtbpre}ecms_".$class_r[$add[classid]][tbname]." where id>$add[id] and classid='$add[classid]' and checked=1 order by id limit 1");
		if(empty($next_r[id]))
		{
			$infonext=$fun_r['HaveNoNextLink'];
		}
		else
		{
			//链接
			$nexttitleurl=sys_ReturnBqTitleLink($next_r);
			$infonext="<a href='".$nexttitleurl."'>".$next_r[title]."</a>";
		}
		$newstempstr=str_replace('[!--info.next--]',$infonext,$newstempstr);
	}
	//上一篇
	if(strstr($newstemptext,'[!--info.pre--]'))
	{
		$next_r=$empire->fetch1("select titleurl,groupid,classid,newspath,filename,id,title from {$dbtbpre}ecms_".$class_r[$add[classid]][tbname]." where id<$add[id] and classid='$add[classid]' and checked=1 order by id desc limit 1");
		if(empty($next_r[id]))
		{
			$infonext=$fun_r['HaveNoNextLink'];
		}
		else
		{
			//链接
			$nexttitleurl=sys_ReturnBqTitleLink($next_r);
			$infonext="<a href='".$nexttitleurl."'>".$next_r[title]."</a>";
		}
		$newstempstr=str_replace('[!--info.pre--]',$infonext,$newstempstr);
	}
	//投票
	if(strstr($newstemptext,'[!--info.vote--]'))
	{
		$myvotetext=sys_GetInfoVote($add[classid],$add[id]);
		$newstempstr=str_replace('[!--info.vote--]',$myvotetext,$newstempstr);
	}
	//评分
	if(strstr($newstemptext,'[!--pinfopfen--]'))
	{
		$pinfopfen=$add[infopfennum]?round($add[infopfen]/$add[infopfennum]):0;
		$newstempstr=str_replace('[!--pinfopfen--]',$pinfopfen,$newstempstr);
		$newstempstr=str_replace('[!--infopfennum--]',$add[infopfennum],$newstempstr);
	}
	if($pf&&strstr($add[$pf],$expage))//有分页
	{
		$n_r=explode($expage,$add[$pf]);
		$thispagenum=count($n_r);
		//取得分页
		$thefun=$public_r['textpagefun']?$public_r['textpagefun']:'sys_ShowTextPage';
		//下拉式分页
		if(strstr($newstemptext,'[!--title.select--]'))
		{
			$dotitleselect=sys_ShowTextPageSelect($thispagenum,$dolink,$add,$filetype,$n_r);
		}
		for($j=1;$j<=$thispagenum;$j++)
		{
			$string=$newstempstr;//模板
			$truepage='';
			$titleselect='';
			//下一页链接
			if($thispagenum==$j)
			{
				$thisnextlink=$dolink.$add[filename].$filetype;
			}
			else
			{
				$thisj=$j+1;
				$thisnextlink=$dolink.$add[filename].'_'.$thisj.$filetype;
			}
			$k=$j-1;
			if($j==1)
			{
				$file=$doclasspath.$newspath.$add[filename].$filetype;
				$ptitle=$add[title];
			}
			else
			{
				$file=$doclasspath.$newspath.$add[filename].'_'.$j.$filetype;
				$ti_r=explode('[/!--empirenews.page--]',$n_r[$k]);
				if(count($ti_r)>=2)
				{
					$ptitle=$ti_r[0];
					$n_r[$k]=$ti_r[1];
				}
				else
				{
					$ptitle=$add[title].'('.$j.')';
				}
			}
			//取得当前页
			if($thispagenum!=1)
			{
				$truepage=$thefun($thispagenum,$j,$dolink,$add,$filetype,'');
				$titleselect=str_replace("?".$j."\">","?".$j."\" selected>",$dotitleselect);
			}
			//替换变量
			$newstext=$n_r[$k];
			if(!strstr($emod_r[$mid]['editorf'],','.$pf.','))
			{
				if(strstr($emod_r[$mid]['tobrf'],','.$pf.','))//加br
				{
					$newstext=nl2br($newstext);
				}
				if(!strstr($emod_r[$mid]['dohtmlf'],','.$pf.','))//去除html
				{
					$newstext=htmlspecialchars($newstext);
					$newstext=RepFieldtextNbsp($newstext);
				}
			}
			$string=str_replace('[!--'.$pf.'--]',$newstext,$string);
			$string=str_replace('[!--p.title--]',$ptitle,$string);
			$string=str_replace('[!--next.page--]',$thisnextlink,$string);
			$string=str_replace('[!--page.url--]',$truepage,$string);
			$string=str_replace('[!--title.select--]',$titleselect,$string);
			//写文件
			WriteFiletext($file,$addlevel.$string);
		}
	}
	else
	{
		$file=$doclasspath.$newspath.$add[filename].$filetype;
		$string=$newstempstr;//模板
		//替换变量
		$string=str_replace('[!--p.title--]',$add[title],$string);
		$string=str_replace('[!--next.page--]','',$string);
		$string=str_replace('[!--page.url--]','',$string);
		$string=str_replace('[!--title.select--]','',$string);
		//写文件
		WriteFiletext($file,$addlevel.$string);
	}
	//设为已生成
	if(empty($add['havehtml']))
	{
		$empire->query("update {$dbtbpre}ecms_".$class_r[$add[classid]][tbname]." set havehtml=1 where id='$add[id]' limit 1");
	}
}

//返回随机字符
function ReturnNotcj($string){
	global $notcj_r,$notcjnum;
	if(empty($notcjnum))
	{
		$rep="";
    }
	else
	{
		$i=rand(1,$notcjnum);
		$rep=$notcj_r[$i];
    }
	$cjword="<!--ecms.*-->";
	$string=str_replace($cjword,$rep,$string);
	return $string;
}

//取得相关链接
function GetKeyboard($keyboard,$keyid,$classid,$id,$link_num){
	global $empire,$public_r,$class_r,$fun_r,$dbtbpre;
	if($keyid)
	{
		if(empty($link_num))
		{
			$link_num=10;
		}
		$add="id in (".$keyid.")";
		$tr=$empire->fetch1("select otherlinktemp,otherlinktempsub,otherlinktempdate from ".GetTemptb("enewspubtemp")." limit 1");//取得相关链接模板
		$temp_r=explode("[!--empirenews.listtemp--]",$tr[otherlinktemp]);
		$key_sql=$empire->query("select id,newspath,newstime,title,filename,titleurl,groupid,classid,titlepic from {$dbtbpre}ecms_".$class_r[$classid][tbname]." where ".$add." order by newstime desc limit $link_num");
		while($link_r=$empire->fetch($key_sql))
		{
			$keyboardtext.=RepOtherTemp($temp_r[1],$link_r,$tr);
		}
		$keyboardtext=$temp_r[0].$keyboardtext.$temp_r[2];
	}
	else
	{
		$keyboardtext=$fun_r['NotLinkNews'];
	}
	return $keyboardtext;
}

//替换相关链接模板
function RepOtherTemp($temptext,$r,$tr){
	global $public_r,$class_r;
	$title=sub($r[title],0,$tr['otherlinktempsub'],false);
	$r['newstime']=date($tr['otherlinktempdate'],$r['newstime']);
	$titlelink=sys_ReturnBqTitleLink($r);//标题链接
	$temptext=str_replace("[!--title--]",$title,$temptext);
	$temptext=str_replace("[!--oldtitle--]",$r[title],$temptext);
	$temptext=str_replace("[!--titleurl--]",$titlelink,$temptext);
	$temptext=str_replace("[!--newstime--]",$r[newstime],$temptext);
	if(empty($r[titlepic]))
	{
		$titlepic=$public_r[newsurl]."e/data/images/notimg.gif";
	}
	else
	{
		$titlepic=$r[titlepic];
	}
	$temptext=str_replace("[!--titlepic--]",$titlepic,$temptext);
	return $temptext;
}

//返回下载地址html代码
function ReturnDownSoftHtml($add){
	global $class_r,$public_r,$fun_r,$level_r;
	if(empty($add[downpath]))
	{
		return '';
	}
	//每行显示条数
	$down_num=$class_r[$add[classid]][down_num]?$class_r[$add[classid]][down_num]:1;
	//替换模板
	$ydownsofttemp=$public_r[downsofttemp];
	$ydownsofttemp=str_replace('[!--classid--]',$add[classid],$ydownsofttemp);
	$ydownsofttemp=str_replace('[!--id--]',$add[id],$ydownsofttemp);
	$ydownsofttemp=str_replace('[!--title--]',$add[title],$ydownsofttemp);
	$ydownsofttemp=str_replace('[!--news.url--]',$public_r[newsurl],$ydownsofttemp);
	//组合地址
	$all_downpath='';
	$path_r=explode("\r\n",$add[downpath]);
	$count=count($path_r);
    for($pj=0;$pj<$count;$pj++)
    {
		$p=$pj+1;
        if($p%$down_num==0)
        {
			$ok='<br>';
		}
        else
        {
			$ok='';
		}
		//相同
		if($count==$p)
		{
			$ok='';
		}
		if($pj%$down_num==0||$pj==0)
        {
			$nbsp='';
		}
        else
        {
			$nbsp='&nbsp;&nbsp;';
		}
	    $showdown_r=explode('::::::',$path_r[$pj]);
	    if(count($showdown_r)<2)
		{
			$showdown_r[0]=$fun_r['DownPath'].$p;
		}
		//模板
		$downsofttemp=RepDownOnlinePathTemp($add,$ydownsofttemp,$pj,$showdown_r,0);
        $all_downpath.=$nbsp.stripSlashes($downsofttemp).$ok;
    }
	$value=$all_downpath;
	return $value;
}

//替换下载在线地址模板
function RepDownOnlinePathTemp($add,$downsofttemp,$pj,$showdown_r,$ecms){
	global $public_r,$level_r,$fun_r;
	if($ecms==0)//下载
	{
		$downurl=$public_r[newsurl]."e/DownSys/DownSoft/?classid=$add[classid]&id=$add[id]&pathid=$pj";
	}
	else//在线
	{
		$downurl=$public_r[newsurl]."e/DownSys/play/?classid=$add[classid]&id=$add[id]&pathid=$pj";
	}
	$downsofttemp=str_replace('[!--down.url--]',$downurl,$downsofttemp);
	$downsofttemp=str_replace('[!--down.name--]',$showdown_r[0],$downsofttemp);
	$downsofttemp=str_replace('[!--pathid--]',$pj,$downsofttemp);
	$downsofttemp=str_replace('[!--fen--]',$showdown_r[3],$downsofttemp);
	$group=$showdown_r[2]?$level_r[$showdown_r[2]][groupname]:$fun_r['hguest'];
	$downsofttemp=str_replace('[!--group--]',$group,$downsofttemp);
	if(strstr($downsofttemp,'[!--true.down.url--]'))
	{
		$durl=stripSlashes($showdown_r[1]);
		$durlr=ReturnDownQzPath($durl,$showdown_r[4]);
		$durl=$durlr['repath'];
		$downsofttemp=str_replace('[!--true.down.url--]',$durl,$downsofttemp);
	}
	return $downsofttemp;
}

//返回在线地址html代码
function ReturnOnlinepathHtml($add){
	global $class_r,$public_r,$fun_r,$level_r;
	if(empty($add[onlinepath]))
	{
		return '';
	}
	//每行显示条数
	$down_num=$class_r[$add[classid]][online_num]?$class_r[$add[classid]][online_num]:1;
	//替换模板
	$yonlinemovietemp=$public_r[onlinemovietemp];
	$yonlinemovietemp=str_replace('[!--classid--]',$add[classid],$yonlinemovietemp);
	$yonlinemovietemp=str_replace('[!--id--]',$add[id],$yonlinemovietemp);
	$yonlinemovietemp=str_replace('[!--title--]',$add[title],$yonlinemovietemp);
	$yonlinemovietemp=str_replace('[!--news.url--]',$public_r[newsurl],$yonlinemovietemp);
	//地址
	$all_downpath='';
	$path_r=explode("\r\n",$add[onlinepath]);
	$count=count($path_r);
    for($pj=0;$pj<$count;$pj++)
    {
		$p=$pj+1;
        if($p%$down_num==0)
        {
			$ok='<br>';
		}
        else
        {
			$ok='';
		}
		//相同
		if($count==$p)
		{
			$ok='';
		}
		if($pj%$down_num==0||$pj==0)
        {
			$nbsp='';
		}
        else
        {
			$nbsp='&nbsp;&nbsp;';
		}
	    $showdown_r=explode('::::::',$path_r[$pj]);
	    if(count($showdown_r)<2)
		{
			$showdown_r[0]=$p;
		}
		//模板
		$downsofttemp=RepDownOnlinePathTemp($add,$yonlinemovietemp,$pj,$showdown_r,1);
        $all_downpath.=$nbsp.stripSlashes($downsofttemp).$ok;
	}
	$value=$all_downpath;
	return $value;
}

//返回图片集html代码
function ReturnMorepicpathHtml($add){
	global $public_r,$fun_r;
	if(empty($add[morepic]))
	{
		return '';
	}
	$line=$add[num]?$add[num]:1;//每行显示
	$picpath='';
	$path_r=explode("\r\n",$add[morepic]);
	for($pj=0;$pj<count($path_r);$pj++)
    {
		$p=$pj+1;
		if(($p-1)%$line==0||$p==1)
		{
			$picpath.='<tr>';
		}
	    $showdown_r=explode('::::::',$path_r[$pj]);
		//显示图片名称
		$name='';
		if(!empty($showdown_r[2]))
		{
			$name="<br><span style='line-height=18pt'>".$showdown_r[2]."</span>";
		}
		$width=$add[width]?" width='".$add[width]."'":'';//宽度
		$height=$add[height]?" height='".$add[height]."'":'';//高度
		$picpath.="<td align=center><a href='".$public_r[newsurl]."e/ViewImg/index.html?url=".$showdown_r[1]."' target=_blank><img src='".$showdown_r[0]."'".$width.$height." border=0>".$name."</a></td>";
		//分割
        if($p%$line==0)
		{
			$picpath.='</tr>';
		}
	}
	if($p<>0)
	{
		$table="<table width='100%' border=0 cellpadding=4 cellspacing=4>";
		$table1="</table>";
        $ys=$line-$p%$line;
		$dotr=0;
        for($j=0;$j<$ys&&$ys!=$line;$j++)
		{
			$dotr=1;
            $picpath.='<td></td>';
        }
		if($dotr==1)
		{
			$picpath.='</tr>';
		}
	}
	$value=$table.$picpath.$table1;
	return $value;
}

//生成js
function GetNewsJs($classid,$line,$sub,$showdate,$enews=0,$tempr){
	global $empire,$public_r,$class_r,$class_zr,$dbtbpre;
	if(empty($line))
	{
		$line=10;
	}
	if(empty($sub))
	{
		$sub=26;
	}
	//栏目
	if($enews==0||$enews==1||$enews==2||$enews==9||$enews==12||$enews==15)
	{
		$where=$class_r[$classid][islast]?"classid='$classid'":ReturnClass($class_r[$classid][sonclass]);
    }
	$allpath=ECMS_PATH.'d/js/js/';
	$ztpath=ECMS_PATH.'d/js/class/zt'.$classid.'_';
	$classpath=ECMS_PATH.'d/js/class/class'.$classid.'_';
	if($enews==0)//栏目最新
	{
	   $tbname=$class_r[$classid][tbname];
	   $query=$where.' and checked=1';
	   $order='newstime';
	   $newsjs=$classpath.'newnews.js';
    }
	elseif($enews==1)//栏目热门
	{
	   $tbname=$class_r[$classid][tbname];
	   $query=$where.' and checked=1';
	   $order="onclick";
	   $newsjs=$classpath.'hotnews.js';
    }
	elseif($enews==2)//栏目推荐
	{
	   $tbname=$class_r[$classid][tbname];
	   $query=$where.' and isgood=1 and checked=1';
	   $order='newstime';
	   $newsjs=$classpath.'goodnews.js';
    }
	elseif($enews==9)//各栏目评论排行
	{
	   $tbname=$class_r[$classid][tbname];
	   $query=$where.' and checked=1';
	   $order='plnum';
	   $newsjs=$classpath.'hotplnews.js';
    }
	elseif($enews==12)//各栏目头条
	{
		$tbname=$class_r[$classid][tbname];
		$query=$where.' and firsttitle=1 and checked=1';
		$order='newstime';
		$newsjs=$classpath.'firstnews.js';
    }
	elseif($enews==3)//所有最新
	{
	   $tbname=$public_r['tbname'];
	   $query='checked=1';
	   $order='newstime';
	   $newsjs=$allpath.'newnews.js';
    }
	elseif($enews==4)//所有点击排行
	{
	   $tbname=$public_r['tbname'];
	   $query='checked=1';
	   $order='onclick';
	   $newsjs=$allpath.'hotnews.js';
    }
	elseif($enews==5)//所有推荐
	{
	   $tbname=$public_r['tbname'];
	   $query='isgood=1 and checked=1';
	   $order='newstime';
	   $newsjs=$allpath.'goodnews.js';
    }
	elseif($enews==10)//所有评论排行
	{
	   $tbname=$public_r['tbname'];
	   $query='checked=1';
	   $order='plnum';
	   $newsjs=$allpath.'hotplnews.js';
    }
	elseif($enews==13)//所有头条
	{
	   $tbname=$public_r['tbname'];
	   $query='firsttitle=1 and checked=1';
	   $order='newstime';
	   $newsjs=$allpath.'firstnews.js';
    }
	elseif($enews==6)//专题最新
	{
	   $tbname=$class_zr[$classid][tbname];
	   $query="ztid like '%|".$classid."|%' and checked=1";
	   $order='newstime';
	   $newsjs=$ztpath.'newnews.js';
    }
	elseif($enews==7)//专题点击排行
	{
	   $tbname=$class_zr[$classid][tbname];
	   $query="ztid like '%|".$classid."|%' and checked=1";
	   $order='onclick';
	   $newsjs=$ztpath.'hotnews.js';
    }
	elseif($enews==8)//专题推荐
	{
	   $tbname=$class_zr[$classid][tbname];
	   $query="ztid like '%|".$classid."|%' and isgood=1 and checked=1";
	   $order='newstime';
	   $newsjs=$ztpath.'goodnews.js';
    }
	elseif($enews==11)//专题评论排行
	{
	   $tbname=$class_zr[$classid][tbname];
	   $query="ztid like '%|".$classid."|%' and checked=1";
	   $order='plnum';
	   $newsjs=$ztpath.'hotplnews.js';
    }
	elseif($enews==14)//专题头条
	{
	   $tbname=$class_zr[$classid][tbname];
	   $query="ztid like '%|".$classid."|%' and firsttitle=1 and checked=1";
	   $order='newstime';
	   $newsjs=$ztpath.'firstnews.js';
    }
	$ret_r=ReturnReplaceListF($tempr[modid]);//字段
	$query="select * from {$dbtbpre}ecms_".$tbname." where ".$query." order by istop desc,".$order." desc,id desc limit $line";
	$sql=$empire->query($query);
	//取得js模板
	$tempr[temptext]=str_replace('[!--news.url--]',$public_r[newsurl],$tempr[temptext]);
	$temp_r=explode("[!--empirenews.listtemp--]",$tempr[temptext]);
	$no=1;
	while($r=$empire->fetch($sql))
	{
		$r[oldtitle]=$r[title];
		//替换列表变量
		$repvar=ReplaceListVars($no,$temp_r[1],$tempr[subnews],$tempr[subtitle],$tempr[showdate],$url,0,$r,$ret_r);
		$allnew.=$repvar;
		$no++;
	}
	$allnew="document.write(\"".addslashes(stripSlashes(str_replace("\r\n","",$temp_r[0].$allnew.$temp_r[2])))."\");";
	WriteFiletext_n($newsjs,$allnew);
}

//生成自定义js
function ReUserjs($jsr,$addpath){
	global $empire,$public_r;
	DoFileMkDir($addpath.$jsr['jsfilename']);//建目录
	//取得js模板
	$jstemptext=GetTheJstemp($jsr[jstempid]);
	$ret_r=ReturnReplaceListF($jstemptext[modid]);//字段
	$jstemptext[temptext]=str_replace('[!--news.url--]',$public_r[newsurl],$jstemptext[temptext]);
	$temp_r=explode("[!--empirenews.listtemp--]",$jstemptext[temptext]);
	$query=stripSlashes($jsr[jssql]);
	$query=RepSqlTbpre($query);
	$sql=$empire->query($query);
	$no=1;
	while($r=$empire->fetch($sql))
	{
		$r[oldtitle]=$r[title];
		//替换列表变量
		$repvar=ReplaceListVars($no,$temp_r[1],$jstemptext[subnews],$jstemptext[subtitle],$jstemptext[showdate],$url,0,$r,$ret_r);
		$allnew.=$repvar;
		$no++;
	}
	$allnew="document.write(\"".addslashes(stripSlashes(str_replace("\r\n","",$temp_r[0].$allnew.$temp_r[2])))."\");";
	WriteFiletext_n($addpath.$jsr['jsfilename'],$allnew);
}

//刷新信息列表
function ReListHtml($classid,$enews=0){
	global $empire,$class_r,$dbtbpre;
	$classid=(int)$classid;
	if(!$classid)
	{
		printerror("NotChangeReClassid","history.go(-1)");
	}
	$r=$empire->fetch1("select classtempid,islist from {$dbtbpre}enewsclass where classid='$classid'");
	if($class_r[$classid][islast])//终极栏目
	{
		ListHtml($classid,$ret_r,0);
	}
	else
	{
		if($r[islist]==1)
		{
			ListHtml($classid,$ret_r,3);
		}
		else
		{
			$classtemp=$r[islist]==2?GetClassText($classid):GetClassTemp($r['classtempid']);
			NewsBq($classid,$classtemp,0,0);
		}
	}
	if($enews==1)//内部刷新
	{return "";}
	insert_dolog("");//操作日志
	printerror("ReClassidSuccess","history.go(-1)");
}

//替换自定义页面标签
function RepUserpageVar($pagetext,$title,$pagetitle,$pagekeywords,$pagedescription){
	$pagetext=str_replace("[!--pagetitle--]",$pagetitle,$pagetext);
	$pagetext=str_replace("[!--pagekeywords--]",$pagekeywords,$pagetext);
	$pagetext=str_replace("[!--pagedescription--]",$pagedescription,$pagetext);
	return $pagetext;
}

//生成自定义页面
function ReUserpage($id,$pagetext,$path,$title="",$pagetitle,$pagekeywords,$pagedescription){
	global $public_r;
	if(empty($path))
	{
		return "";
	}
	DoFileMkDir($path);//建目录
	if(empty($pagetitle))
	{
		$pagetitle=$title;
	}
	$pagetext=InfoNewsBq("page".$id,$pagetext);
	$pagetext=RepUserpageVar($pagetext,$title,$pagetitle,$pagekeywords,$pagedescription);
	$pagetext=str_replace("[!--news.url--]",$public_r['newsurl'],$pagetext);
	WriteFiletext($path,$pagetext);
}

//生成自定义信息列表
function ReUserlist($listr,$addpath){
	$listr['addpath']=$addpath;
	DoFileMkDir($listr['addpath'].$listr['filepath']);//建目录
	ListHtml($classid,$field,4,$listr);
}

//生成搜索文件
function GetSearch($mid=0){
	global $empire,$public_r,$fun_r,$dbtbpre;
	//取得模板
	$tr=$empire->fetch1("select searchtemp,searchjstemp,searchjstemp1 from ".GetTemptb("enewspubtemp")." limit 1");
	//返回栏目搜索
	$fcfile="../data/fc/ListEnews.php";
	$fcjsfile="../data/fc/cmsclass.js";
	if(file_exists($fcjsfile)&&file_exists($fcfile))
	{
		$options=GetFcfiletext($fcjsfile);
	}
	else
	{
		$options=ShowClass_AddClass("","n",0,"|-",0,1);
	}
	//$options="<script src=".$public_r[newsurl]."e/data/fc/searchclass.js></script>";
	$functions="function search_check(obj){if(obj.keyboard.value.length==0){alert('".$fun_r['EmptyKey']."');return false;}return true;}";
	//横向搜索
	$searchjstemp=ReplaceStemp($tr[searchjstemp],$options,$url,0,'','','');
	$text2=$functions."document.write(\"".$searchjstemp."\");";
	//纵向搜索
	$searchjstemp1=ReplaceStemp($tr[searchjstemp1],$options,$url,0,'','','');
	$text3.=$functions."document.write(\"".$searchjstemp1."\");";
	//高级搜索
	$url="<a href='".$public_r[newsurl]."'>".$fun_r['index']."</a>&nbsp;>&nbsp;<a href='../search/'>".$fun_r['adsearch']."</a>&nbsp;>";//导航栏
	//搜索模板替换
	$dbsearchtemp=ReplaceStemp($tr[searchtemp],$options,$url,0,$fun_r['adsearch'],$fun_r['adsearch'],$fun_r['adsearch'],1);
	$text4=$dbsearchtemp;
	//增加信息栏目
	if($mid)
	{
		$options1=ShowClass_AddClass("","n",0,"|-",$mid,2);
		$addnews_class="document.write(\"".addslashes($options1)."\");";
		$filename3="../../d/js/js/addinfo".$mid.".js";
		WriteFiletext_n($filename3,$addnews_class);
    }
	$filename="../../d/js/js/search_news1.js";
	WriteFiletext_n($filename,$text2);
	$filename1="../../d/js/js/search_news2.js";
	WriteFiletext_n($filename1,$text3);
	$filename2="../../search/index".$public_r[searchtype];
	WriteFiletext($filename2,$text4);
}

//替换搜索结果变量
function RepSearchRtemp($temptext,$url){
	global $public_r;
	//热门文章
	$temptext=str_replace("[!--hotnews--]","<script src=".$public_r[newsurl]."d/js/js/hotnews.js></script>",$temptext);
	//点击排行
	$temptext=str_replace("[!--newnews--]","<script src=".$public_r[newsurl]."d/js/js/newnews.js></script>",$temptext);
	//推荐
	$temptext=str_replace("[!--goodnews--]","<script src=".$public_r[newsurl]."d/js/js/goodnews.js></script>",$temptext);
	//评论排行
	$temptext=str_replace("[!--hotplnews--]","<script src=".$public_r[newsurl]."d/js/js/hotplnews.js></script>",$temptext);
	//分页
	$temptext=str_replace("[!--listpage--]","<?=\$listpage?>",$temptext);
	//关键字
	$temptext=str_replace("[!--keyboard--]","<?=\$keyboard?>",$temptext);
	//总记录数
	$temptext=str_replace("[!--num--]","<?=\$num?>",$temptext);
	//导行条
	$temptext=str_replace("[!--url--]",$url,$temptext);
	$temptext=str_replace("[!--newsurl--]",$public_r[newsurl],$temptext);
	return $temptext;
}

//生成评论文件
function GetPlTempPage($pltempid=0){
	global $empire,$public_r,$fun_r,$dbtbpre;
	$pl_t_filename=ECMS_PATH.'e/data/template/pltemp.txt';
	$yplfiletemp=ReadFiletext($pl_t_filename);
	$yplfiletemp=str_replace("\\","\\\\",$yplfiletemp);
	//导航栏
	$url="<a href=".$public_r[newsurl].">".$fun_r['index']."</a>&nbsp;>&nbsp;[!--title--]&nbsp;>&nbsp;".$fun_r['newspl']."&nbsp;>";
	$pagetitle="<?=\$title?> ".$fun_r['newspl'];
	$pagekey=$pagetitle;
	$pagedes=$pagetitle;
	$pr=$empire->fetch1("select plf from {$dbtbpre}enewspublic limit 1");
	//回车字段
	$tobrf=',';
	$plfsql=$empire->query("select f from {$dbtbpre}enewsplf where ftype='VARCHAR' or ftype='TEXT' or ftype='MEDIUMTEXT' or ftype='LONGTEXT'");
	while($plfr=$empire->fetch($plfsql))
	{
		$tobrf.=$plfr[f].',';
	}
	$pr['pltobrf']=$tobrf;
	//取得评论页面模板
	$where=$pltempid?" where tempid='$pltempid'":'';
	$ptsql=$empire->query("select tempid,temptext from ".GetTemptb("enewspltemp").$where);
	while($ptr=$empire->fetch($ptsql))
	{
		$plfiletemp=$yplfiletemp;
		$pl_filename=ECMS_PATH.'e/data/filecache/template/pl'.$ptr[tempid].'.php';
		$pltemp=$ptr['temptext'];
		//头部变量
		$pltemp=ReplaceSvars($pltemp,$url,0,$pagetitle,$pagekey,$pagedes,$add,1);
		$pltemp=RepSearchRtemp($pltemp,$url);
		//变量
		$pltemp=str_replace("[!--title--]","<?=\$title?>",$pltemp);
		$pltemp=str_replace("[!--titleurl--]","<?=\$titleurl?>",$pltemp);
		$pltemp=str_replace("[!--id--]","<?=\$id?>",$pltemp);
		$pltemp=str_replace("[!--classid--]","<?=\$classid?>",$pltemp);
		$pltemp=str_replace("[!--plnum--]","<?=\$num?>",$pltemp);
		//评分
		$pltemp=str_replace("[!--pinfopfen--]","<?=\$pinfopfen?>",$pltemp);
		$pltemp=str_replace("[!--infopfennum--]","<?=\$infopfennum?>",$pltemp);
		//登录
		$pltemp=str_replace("[!--key.url--]",$public_r[newsurl]."e/ShowKey/?v=pl",$pltemp);
		$pltemp=str_replace("[!--lusername--]","<?=\$lusername?>",$pltemp);
		$pltemp=str_replace("[!--lpassword--]","<?=\$lpassword?>",$pltemp);
		//列表变量
		$listtemp_r=explode("[!--empirenews.listtemp--]",$pltemp);
		$plfiletemp=str_replace("<!--empire.listtemp.top-->",$listtemp_r[0],$plfiletemp);
		$plfiletemp=str_replace("<!--empire.listtemp.footer-->",$listtemp_r[2],$plfiletemp);
		//列表中间
		$listtemp_center=str_replace("[!--plid--]","<?=\$r[plid]?>",$listtemp_r[1]);
		$listtemp_center=str_replace("[!--pltext--]","<?=\$saytext?>",$listtemp_center);
		$listtemp_center=str_replace("[!--pltime--]","<?=\$r[saytime]?>",$listtemp_center);
		$listtemp_center=str_replace("[!--plip--]","<?=\$sayip?>",$listtemp_center);
		$listtemp_center=str_replace("[!--username--]","<?=\$plusername?>",$listtemp_center);
		$listtemp_center=str_replace("[!--userid--]","<?=\$r[userid]?>",$listtemp_center);
		$listtemp_center=str_replace("[!--includelink--]","<?=\$includelink?>",$listtemp_center);
		$listtemp_center=str_replace("[!--zcnum--]","<?=\$r[zcnum]?>",$listtemp_center);
		$listtemp_center=str_replace("[!--fdnum--]","<?=\$r[fdnum]?>",$listtemp_center);
		$listtemp_center=ReplacePlListVars($listtemp_center,$r,$pr,0);
		$plfiletemp=str_replace("<!--empire.listtemp.center-->",$listtemp_center,$plfiletemp);
		WriteFiletext($pl_filename,$plfiletemp);
	}
}

//替换评论字段
function ReplacePlListVars($temp,$r,$pr,$ecms=0){
	$fr=explode(',',$pr['plf']);
	$count=count($fr)-1;
	for($i=1;$i<$count;$i++)
	{
		$f=$fr[$i];
		if($ecms==1)
		{
			if(strstr($pr['pltobrf'],','.$f.','))
			{
				$temp=str_replace('[!--'.$f.'--]',"<?=addslashes(stripSlashes(str_replace(\"\\r\\n\",\"\",\$fr[".$f."])))?>",$temp);
			}
			else
			{
				$temp=str_replace('[!--'.$f.'--]',"<?=\$fr[".$f."]?>",$temp);
			}
		}
		else
		{
			if(strstr($pr['pltobrf'],','.$f.','))
			{
				$temp=str_replace('[!--'.$f.'--]',"<?=stripSlashes(\$fr[".$f."])?>",$temp);
			}
			else
			{
				$temp=str_replace('[!--'.$f.'--]',"<?=\$fr[".$f."]?>",$temp);
			}
		}
	}
	return $temp;
}

//生成评论JS文件
function GetPlJsPage(){
	global $empire,$public_r,$fun_r,$dbtbpre;
	$pl_t_filename="../data/template/pljstemp.txt";
	$pl_filename="../pl/more/index.php";
	$pltemp=ReadFiletext($pl_t_filename);
	$pr=$empire->fetch1("select plf from {$dbtbpre}enewspublic limit 1");
	//回车字段
	$tobrf=',';
	$plfsql=$empire->query("select f from {$dbtbpre}enewsplf where ftype='VARCHAR' or ftype='TEXT' or ftype='MEDIUMTEXT' or ftype='LONGTEXT'");
	while($plfr=$empire->fetch($plfsql))
	{
		$tobrf.=$plfr[f].',';
	}
	$pr['pltobrf']=$tobrf;
	//取得评论JS模板
	$pl_r=$empire->fetch1("select pljstemp from ".GetTemptb("enewspubtemp")." limit 1");
	$pljstemp=str_replace("\r\n","",$pl_r['pljstemp']);
	$pljstemp=addslashes(stripSlashes($pljstemp));
	$pljstemp=str_replace("[!--id--]","<?=\$id?>",$pljstemp);
	$pljstemp=str_replace("[!--classid--]","<?=\$classid?>",$pljstemp);
	$pljstemp=str_replace("[!--news.url--]",$public_r[newsurl],$pljstemp);
	$listtemp_r=explode("[!--empirenews.listtemp--]",$pljstemp);
	$pltemp=str_replace("<!--empire.listtemp.top-->",$listtemp_r[0],$pltemp);
	$pltemp=str_replace("<!--empire.listtemp.footer-->",$listtemp_r[2],$pltemp);
	//列表中间
	$listtemp_center=str_replace("[!--plid--]","<?=\$r[plid]?>",$listtemp_r[1]);
	$listtemp_center=str_replace("[!--pltext--]","<?=\$saytext?>",$listtemp_center);
	$listtemp_center=str_replace("[!--pltime--]","<?=\$r[saytime]?>",$listtemp_center);
	$listtemp_center=str_replace("[!--plip--]","<?=\$sayip?>",$listtemp_center);
	$listtemp_center=str_replace("[!--username--]","<?=\$plusername?>",$listtemp_center);
	$listtemp_center=str_replace("[!--userid--]","<?=\$r[userid]?>",$listtemp_center);
	$listtemp_center=str_replace("[!--zcnum--]","<?=\$r[zcnum]?>",$listtemp_center);
	$listtemp_center=str_replace("[!--fdnum--]","<?=\$r[fdnum]?>",$listtemp_center);
	$listtemp_center=ReplacePlListVars($listtemp_center,$r,$pr,1);
	$pltemp=str_replace("<!--empire.listtemp.center-->",$listtemp_center,$pltemp);
    WriteFiletext_n($pl_filename,$pltemp);
}

//生成留言板文件
function ReGbooktemp(){
	global $empire,$public_r,$fun_r,$dbtbpre;
	$tfile="../data/template/gbooktemp.txt";
	$file="../tool/gbook/index.php";
	$gbtemp=ReadFiletext($tfile);
	//取得留言页面模板
	$pr=$empire->fetch1("select gbooktemp from ".GetTemptb("enewspubtemp")." limit 1");
	$url="<?=\$url?>";
	$pagetitle="<?=\$bname?>";
	$pr['gbooktemp']=ReplaceSvars($pr['gbooktemp'],$url,0,$pagetitle,$pagetitle,$pagetitle,$add,1);
	$pr['gbooktemp']=RepSearchRtemp($pr['gbooktemp'],$url);
	$pr['gbooktemp']=str_replace("[!--bname--]","<?=\$bname?>",$pr['gbooktemp']);
	$pr['gbooktemp']=str_replace("[!--bid--]","<?=\$bid?>",$pr['gbooktemp']);

	$listtemp_r=explode("[!--empirenews.listtemp--]",$pr['gbooktemp']);
	$gbtemp=str_replace("<!--empire.listtemp.top-->",$listtemp_r[0],$gbtemp);
	$gbtemp=str_replace("<!--empire.listtemp.footer-->",$listtemp_r[2],$gbtemp);
	//---列表中间
	//处理回复
	$restart="
<?
if(\$r[retext])
{
?>
";
	$endstart="
<?
}
?>";
	$listtemp_center=str_replace("[!--start.regbook--]",$restart,$listtemp_r[1]);
	$listtemp_center=str_replace("[!--end.regbook--]",$endstart,$listtemp_center);

	$listtemp_center=str_replace("[!--lyid--]","<?=\$r[lyid]?>",$listtemp_center);
	$listtemp_center=str_replace("[!--name--]","<?=\$r[name]?>",$listtemp_center);
	$listtemp_center=str_replace("[!--email--]","<?=\$r[email]?>",$listtemp_center);
	$listtemp_center=str_replace("[!--call--]","<?=\$r[call]?>",$listtemp_center);
	$listtemp_center=str_replace("[!--lytime--]","<?=\$r[lytime]?>",$listtemp_center);
	$listtemp_center=str_replace("[!--lytext--]","<?=\$r[lytext]?>",$listtemp_center);
	$listtemp_center=str_replace("[!--retext--]","<?=\$r[retext]?>",$listtemp_center);

	$gbtemp=str_replace("<!--empire.listtemp.center-->",$listtemp_center,$gbtemp);
	WriteFiletext($file,$gbtemp);
}

//更新控制面板模板
function ReCptemp(){
	global $empire,$public_r,$dbtbpre,$fun_r;
	$pr=$empire->fetch1("select cptemp from ".GetTemptb("enewspubtemp")." limit 1");
	$url="<?=\$url?>";
	$pagetitle="<?=defined('empirecms')?\$r[title]:'".$fun_r['membercp']."'?>";
	$temptext=ReplaceSvars($pr['cptemp'],$url,0,$pagetitle,$pagetitle,$pagetitle,$add,1);
	//生成头尾文件
	$r=explode("[!--empirenews.template--]",$temptext);
	$file1="../data/template/cp_1.php";
	WriteFiletext($file1,AddCheckViewTempCode().$r[0]);
	$file2="../data/template/cp_2.php";
	WriteFiletext($file2,AddCheckViewTempCode().$r[1]);
}

//更新登陆状态模板
function ReLoginIframe(){
	global $empire,$public_r,$dbtbpre;
	$tfile="../data/template/loginiframetemp.txt";
	$loginiframetemp=ReadFiletext($tfile);
	$pr=$empire->fetch1("select loginiframe,loginjstemp from ".GetTemptb("enewspubtemp")." limit 1");
	//框架登陆状态调用
	$temptext=str_replace("[!--news.url--]",$public_r['newsurl'],$pr['loginiframe']);
	$temptext=str_replace("[!--userid--]","<?=\$myuserid?>",$temptext);
	$temptext=str_replace("[!--username--]","<?=\$myusername?>",$temptext);
	$temptext=str_replace("[!--groupname--]","<?=\$groupname?>",$temptext);
	$temptext=str_replace("[!--money--]","<?=\$money?>",$temptext);
	$temptext=str_replace("[!--userdate--]","<?=\$userdate?>",$temptext);
	$temptext=str_replace("[!--havemsg--]","<?=\$havemsg?>",$temptext);
	$temptext=str_replace("[!--userfen--]","<?=\$userfen?>",$temptext);
	$r=explode("[!--empirenews.template--]",$temptext);
	$text=str_replace("<!--login-->",$r[0],$loginiframetemp);
	$text=str_replace("<!--loginin-->",$r[1],$text);
	$file="../member/iframe/index.php";
	WriteFiletext($file,$text);
	//JS登陆状态调用
	$temptext=str_replace("[!--news.url--]",$public_r['newsurl'],$pr['loginjstemp']);
	$temptext=str_replace("[!--userid--]","<?=\$myuserid?>",$temptext);
	$temptext=str_replace("[!--username--]","<?=\$myusername?>",$temptext);
	$temptext=str_replace("[!--groupname--]","<?=\$groupname?>",$temptext);
	$temptext=str_replace("[!--money--]","<?=\$money?>",$temptext);
	$temptext=str_replace("[!--userdate--]","<?=\$userdate?>",$temptext);
	$temptext=str_replace("[!--havemsg--]","<?=\$havemsg?>",$temptext);
	$temptext=str_replace("[!--userfen--]","<?=\$userfen?>",$temptext);
	$r=explode("[!--empirenews.template--]",$temptext);
	$login="document.write(\"".addslashes(stripSlashes(str_replace("\r\n","",$r[0])))."\");";
	$loginin="document.write(\"".addslashes(stripSlashes(str_replace("\r\n","",$r[1])))."\");";
	$text=str_replace("<!--login-->",$login,$loginiframetemp);
	$text=str_replace("<!--loginin-->",$loginin,$text);
	$file="../member/login/loginjs.php";
	WriteFiletext_n($file,$text);
}

//返回投票模板
function ReturnVoteTemp($tempid,$enews=0){
	global $empire;
	$r=$empire->fetch1("select temptext from ".GetTemptb("enewsvotetemp")." where tempid='$tempid'");
	if($enews)
	{
		$r[temptext]=str_replace("\r\n","",$r[temptext]);
	}
	return $r[temptext];
}

//替换投票模板总体变量
function RepVoteTempAllvar($temptext,$r){
	global $public_r;
	$action=$public_r['newsurl']."e/enews/index.php";
	$temptext=str_replace("[!--vote.action--]",$action,$temptext);
	$temptext=str_replace("[!--title--]",$r[title],$temptext);
	$viewurl=$public_r[newsurl]."e/tool/vote/?voteid=".$r[voteid];
	$temptext=str_replace("[!--vote.view--]",$viewurl,$temptext);
	$temptext=str_replace("[!--width--]",$r[width],$temptext);
	$temptext=str_replace("[!--height--]",$r[height],$temptext);
	$temptext=str_replace("[!--voteid--]",$r[voteid],$temptext);
	$temptext=str_replace("[!--id--]",$r[id],$temptext);
	$temptext=str_replace("[!--classid--]",$r[classid],$temptext);
	$temptext=str_replace("[!--news.url--]",$public_r[newsurl],$temptext);
	return $temptext;
}

//替换投票模板列表
function RepVoteTempListvar($temptext,$votebox,$votename){
	$temptext=str_replace("[!--vote.box--]",$votebox,$temptext);
	$temptext=str_replace("[!--vote.name--]",$votename,$temptext);
	return $temptext;
}

//生成打印页面
function GetPrintPage(){
	global $empire,$dbtbpre,$fun_r;
	$file="../data/template/printtemp.txt";
	$string=ReadFiletext($file);
	$r=$empire->fetch1("select printtemp from ".GetTemptb("enewspubtemp")." limit 1");
	$url="<?=\$url?>";
	$pagetitle="<?=\$r[title]?> ".$fun_r['PrintPage'];
	$r[printtemp]=ReplaceSvars($r[printtemp],$url,0,$pagetitle,$pagetitle,$pagetitle,$add,1);
	$printtemp=RepPrintTempV($r[printtemp]);
	$string=str_replace("<!--empire.print-->",$printtemp,$string);
	$truefile="../DoPrint/index.php";
	WriteFiletext($truefile,$string);
}

//替换打印模板变量
function RepPrintTempV($temptext){
	$temptext=str_replace("[!--id--]","<?=\$r[id]?>",$temptext);
	$temptext=str_replace("[!--classid--]","<?=\$r[classid]?>",$temptext);
	$temptext=str_replace("[!--title--]","<?=\$r[title]?>",$temptext);
	$temptext=str_replace("[!--writer--]","<?=\$r[writer]?>",$temptext);
	$temptext=str_replace("[!--newstime--]","<?=\$r[newstime]?>",$temptext);
	$temptext=str_replace("[!--befrom--]","<?=\$r[befrom]?>",$temptext);
	$temptext=str_replace("[!--newstext--]","<?=\$r[newstext]?>",$temptext);
	$temptext=str_replace("[!--smalltext--]","<?=\$r[smalltext]?>",$temptext);
	$temptext=str_replace("[!--username--]","<?=\$r[username]?>",$temptext);
	$temptext=str_replace("[!--titlelink--]","<?=\$titlelink?>",$temptext);
	$temptext=str_replace("[!--url--]","<?=\$url?>",$temptext);
	return $temptext;
}

//更新下载页面模板
function GetDownloadPage(){
	global $empire,$public_r,$dbtbpre,$fun_r;
	$pr=$empire->fetch1("select downpagetemp from ".GetTemptb("enewspubtemp")." limit 1");
	$temptext=$pr['downpagetemp'];
	$url="<a href='".$public_r[newsurl]."'>".$fun_r['index']."</a>&nbsp;>&nbsp;<a href='<?=\$titleurl?>'><?=\$r[title]?></a>&nbsp;>&nbsp;<?=\$thisdownname?>";
	$pagetitle="<?=htmlspecialchars(\$r[title])?> - <?=htmlspecialchars(\$thisdownname)?>";
	$temptext=ReplaceSvars($temptext,$url,"<?=\$r[classid]?>",$pagetitle,$pagetitle,$pagetitle,$add,1);
	//分类
	$temptext=str_replace("[!--classid--]","<?=\$r[classid]?>",$temptext);
	$temptext=str_replace("[!--class.name--]","<?=\$classname?>",$temptext);
	$temptext=str_replace("[!--bclass.id--]","<?=\$bclassid?>",$temptext);
	$temptext=str_replace("[!--bclass.name--]","<?=\$bclassname?>",$temptext);
	//下载地址
	$temptext=str_replace("[!--down.url--]","<?=\$url?>",$temptext);
	$temptext=str_replace("[!--true.down.url--]","<?=\$trueurl?>",$temptext);
	$temptext=str_replace("[!--down.name--]","<?=\$thisdownname?>",$temptext);
	//下载权限
	$temptext=str_replace("[!--fen--]","<?=\$fen?>",$temptext);
	$temptext=str_replace("[!--group--]","<?=\$downuser?>",$temptext);
	//信息
	$temptext=str_replace("[!--id--]","<?=\$r[id]?>",$temptext);
	$temptext=str_replace("[!--titleurl--]","<?=\$titleurl?>",$temptext);
	$temptext=str_replace("[!--title--]","<?=\$r[title]?>",$temptext);
	$temptext=str_replace("[!--newstime--]","<?=\$r[newstime]?>",$temptext);
	$temptext=str_replace("[!--titlepic--]","<?=\$titlepic?>",$temptext);
	$temptext=str_replace("[!--keyboard--]","<?=\$r[keyboard]?>",$temptext);
	$temptext=str_replace("[!--userid--]","<?=\$r[userid]?>",$temptext);
	$temptext=str_replace("[!--username--]","<?=\$r[username]?>",$temptext);
	$temptext=str_replace("[!--pathid--]","<?=\$pathid?>",$temptext);
	$temptext=str_replace("[!--totaldown--]","<?=\$r[totaldown]?>",$temptext);
	$temptext=str_replace("[!--onclick--]","<?=\$r[onclick]?>",$temptext);
	$file="../data/template/downpagetemp.php";
	WriteFiletext($file,AddCheckViewTempCode().$temptext);
}

//生成全站搜索文件
function ReSchAlltemp(){
	global $empire,$public_r,$fun_r,$dbtbpre;
	$tfile="../data/template/schalltemp.txt";
	$file="../sch/index.php";
	$temp=ReadFiletext($tfile);
	//取得页面模板
	$pr=$empire->fetch1("select schalltemp,schallsubnum,schalldate from ".GetTemptb("enewspubtemp")." limit 1");
	$url="<?=\$url?>";
	$pagetitle=$fun_r['SearchAllNav'];
	$pr['schalltemp']=ReplaceSvars($pr['schalltemp'],$url,0,$pagetitle,$pagetitle,$pagetitle,$add,1);
	$temp=str_replace("<!--empire.listtemp.subnum-->",$pr['schallsubnum'],$temp);
	$temp=str_replace("<!--empire.listtemp.formatdate-->",$pr['schalldate'],$temp);

	$pr['schalltemp']=str_replace("[!--keyboard--]","<?=\$keyboard?>",$pr['schalltemp']);
	$pr['schalltemp']=str_replace("[!--num--]","<?=\$num?>",$pr['schalltemp']);
	$pr['schalltemp']=str_replace("[!--listpage--]","<?=\$listpage?>",$pr['schalltemp']);

	$listtemp_r=explode("[!--empirenews.listtemp--]",$pr['schalltemp']);
	$temp=str_replace("<!--empire.listtemp.top-->",$listtemp_r[0],$temp);
	$temp=str_replace("<!--empire.listtemp.footer-->",$listtemp_r[2],$temp);
	//---列表中间
	$listtemp_center=str_replace("[!--no.num--]","<?=\$no?>",$listtemp_r[1]);
	$listtemp_center=str_replace("[!--titleurl--]","<?=\$titleurl?>",$listtemp_center);
	$listtemp_center=str_replace("[!--id--]","<?=\$r[id]?>",$listtemp_center);
	$listtemp_center=str_replace("[!--classid--]","<?=\$r[classid]?>",$listtemp_center);
	$listtemp_center=str_replace("[!--titlepic--]","<?=\$titlepic?>",$listtemp_center);
	$listtemp_center=str_replace("[!--newstime--]","<?=\$newstime?>",$listtemp_center);
	$listtemp_center=str_replace("[!--title--]","<?=\$title?>",$listtemp_center);
	$listtemp_center=str_replace("[!--smalltext--]","<?=\$smalltext?>",$listtemp_center);

	$temp=str_replace("<!--empire.listtemp.center-->",$listtemp_center,$temp);
	WriteFiletext($file,$temp);
}


//-------------- 用户区 ----------------------

//返回操作权限
function ReturnLeftLevel($groupid){
	global $empire,$dbtbpre;
	if(empty($groupid))
	{return "";}
	$groupid=(int)$groupid;
	$r=$empire->fetch1("select * from {$dbtbpre}enewsgroup where groupid='$groupid'");
	return $r;
}

//返回操作权限
function CheckLevel($userid,$username,$classid,$enews){
	global $empire,$dbtbpre;
	$userid=(int)$userid;
	$r=$empire->fetch1("select groupid,adminclass from {$dbtbpre}enewsuser where userid='$userid' limit 1");
	//操作信息
	if($enews=="news")
	{
		//操作所有栏目权限
		$gr=$empire->fetch1("select doall,doselfinfo,doaddinfo,doeditinfo,dodelinfo from {$dbtbpre}enewsgroup where groupid='$r[groupid]'");
		if(empty($gr[doall]))
		{
			$e_r=explode("|".$classid."|",$r[adminclass]);
			if(count($e_r)!=2)
			{printerror("NotNewsLevel","history.go(-1)");}
		}
		return $gr;
    }
	else
	{
		//用户组
		$gr=$empire->fetch1("select * from {$dbtbpre}enewsgroup where groupid='$r[groupid]'");
		$enews="do".$enews;
		if(empty($gr[$enews]))
		{
			printerror("NotLevel","history.go(-1)");
	    }
    }
}

//是否登陆
function is_login($uid=0,$uname='',$urnd=''){
	global $empire,$public_r,$dbtbpre;
	eCheckAccessIp(1);//禁止IP
	$userid=$uid?$uid:getcvar('loginuserid',1);
	$username=$uname?$uname:getcvar('loginusername',1);
	$rnd=$urnd?$urnd:getcvar('loginrnd',1);
	$userid=(int)$userid;
	$username=RepPostVar($username);
	$rnd=RepPostVar($rnd);
	if(!$userid||!$username||!$rnd)
	{
		printerror("NotLogin","index.php");
	}
	$groupid=(int)getcvar('loginlevel',1);
	$adminstyle=(int)getcvar('loginadminstyleid',1);
	if(!strstr($public_r['adminstyle'],','.$adminstyle.','))
	{
		$adminstyle=$public_r['defadminstyle']?$public_r['defadminstyle']:1;
	}
	//COOKIE验证
	if(getcvar('loginusername',1))
	{
		$cdbdata=getcvar('ecmsdodbdata',1)?1:0;
		DoChECookieRnd($userid,$username,$rnd,$cdbdata,$groupid,$adminstyle);
	}
	//db
	$adminr=$empire->fetch1("select userid,groupid from {$dbtbpre}enewsuser where userid='$userid' and username='".$username."' and rnd='".$rnd."' and checked=0 limit 1");
	if(!$adminr['userid'])
	{
		printerror("SingleUser","index.php");
	}
	//登陆超时
	$logintime=getcvar('logintime',1);
	if($logintime)
	{
		if(time()-$logintime>$public_r['exittime']*60)
		{
			printerror("LoginTime","index.php");
	    }
		esetcookie("logintime",time(),0,1);
	}
	if(getcvar('eloginlic',1)<>"empirecmslic")
	{
		printerror("NotLogin","index.php");
	}
	$ur[userid]=$userid;
	$ur[username]=$username;
	$ur[rnd]=$rnd;
	$ur[groupid]=$adminr[groupid];
	$ur[adminstyleid]=(int)$adminstyle;
	return $ur;
}

function is_login_ebak($userid,$username,$rnd){
	global $empire,$public_r;
	eCheckAccessIp(1);//禁止IP
	$userid=(int)$userid;
	$username=RepPostVar($username);
	$dodbdata=getcvar('ecmsdodbdata',1);
	if(!$userid||!$username)
	{
		printerror("NotLogin","index.php");
	}
	if($dodbdata!="empirecms")
	{
		printerror("NotLogin","index.php");
	}
	$rnd=RepPostVar($rnd);
	//COOKIE验证
	$cdbdata=$dodbdata?1:0;
	$groupid=(int)getcvar('loginlevel',1);
	$adminstyle=(int)getcvar('loginadminstyleid',1);
	DoChECookieRnd($userid,$username,$rnd,$cdbdata,$groupid,$adminstyle);
	//超时
	$logintime=getcvar('logintime',1);
	if($logintime)
	{
		if(time()-$logintime>$public_r['exittime']*60)
		{
			printerror("LoginTime","index.php");
	    }
		esetcookie("logintime",time(),0,1);
	}
	$ur[userid]=$userid;
	$ur[username]=$username;
	$ur[rnd]=$rnd;
	$ur[groupid]=$groupid;
	$ur[adminstyleid]=$adminstyle;
	return $ur;
}

//COOKIE加密
function DoECookieRnd($userid,$username,$rnd,$dbdata,$groupid,$adminstyle){
	global $do_ecookiernd,$do_ckhloginip;
	$ip=$do_ckhloginip==0?'127.0.0.1':egetip();
	$ecmsckpass=md5(md5($rnd.$do_ecookiernd).'-'.$ip.'-'.$userid.'-'.$username.'-'.$dbdata.$rnd.$groupid.'-'.$adminstyle);
	esetcookie("loginecmsckpass",$ecmsckpass,0,1);
}

function DoChECookieRnd($userid,$username,$rnd,$dbdata,$groupid,$adminstyle){
	global $do_ecookiernd,$do_ckhloginip;
	$ip=$do_ckhloginip==0?'127.0.0.1':egetip();
	$ecmsckpass=md5(md5($rnd.$do_ecookiernd).'-'.$ip.'-'.$userid.'-'.$username.'-'.$dbdata.$rnd.$groupid.'-'.$adminstyle);
	if($ecmsckpass<>getcvar('loginecmsckpass',1))
	{
		printerror("NotLogin","index.php");
	}
}

//写入操作日志
function insert_dolog($doing){
	global $empire,$enews,$phome,$logininid,$loginin,$do_thedolog,$dbtbpre;
	if($do_thedolog)
	{
		return "";
	}
	if(empty($doing))
	{$doing="---";}
	$doing=addslashes(stripSlashes($doing));
	//ip
	$logip=egetip();
	$logtime=date("Y-m-d H:i:s");
	if(empty($enews))
	{$enews=$phome;}
	$enews=RepPostVar($enews);
	$sql=$empire->query("insert into {$dbtbpre}enewsdolog(username,logip,logtime,enews,doing) values('$loginin','$logip','$logtime','$enews','$doing');");
}


//-------------- 远程发布区 ----------------------

//返回FTP目录或文件绝对地址
function FtpRTruePath($ftppath,$path){
	$truepath=$ftppath.'/'.$path;
	return $truepath;
}

//目录转向
function FtpChPath($e,$r){
	$path=$r[ftppath].'/e/ftp';
	$e->fChdir($path);
	return '';
}

//上传ftp目录
function FtpTranPath($ftpid,$ldir,$hdir){
	$r=ReturnFtpInfo($ftpid);
	$e=new EmpireCMSFTP();
	$e->fconnect($r[ftphost],$r[ftpport],$r[ftpusername],$r[ftppassword],$r[ftppath],$r[ftpssl],$r[ftppasv],$r[ftpmode],$r[ftpouttime]);
	FtpChPath($e,$r);
    //上传目录
    $e->ftp_copy($ldir,$hdir);
    $e->fExit();
}

//删除ftp目录
function FtpDelPath($ftpid,$dir){
	$r=ReturnFtpInfo($ftpid);
	$e=new EmpireCMSFTP();
	$e->fconnect($r[ftphost],$r[ftpport],$r[ftpusername],$r[ftppassword],$r[ftppath],$r[ftpssl],$r[ftppasv],$r[ftpmode],$r[ftpouttime]);
	FtpChPath($e,$r);
    //删除目录
    $e->ftp_rmAll($dir);
    $e->fExit();
}

//删除ftp文件
function FtpDelFile($ftpid,$fr){
	$r=ReturnFtpInfo($ftpid);
    $e=new EmpireCMSFTP();
	$e->fconnect($r[ftphost],$r[ftpport],$r[ftpusername],$r[ftppassword],$r[ftppath],$r[ftpssl],$r[ftppasv],$r[ftpmode],$r[ftpouttime]);
	FtpChPath($e,$r);
    //删除文件
	$e->fMoreDelFile($fr);
    $e->fExit();
}

//上传文件
function FtpTranFile($ftpid,$fr,$fr1){
	$r=ReturnFtpInfo($ftpid);
    $e=new EmpireCMSFTP();
	$e->fconnect($r[ftphost],$r[ftpport],$r[ftpusername],$r[ftppassword],$r[ftppath],$r[ftpssl],$r[ftppasv],$r[ftpmode],$r[ftpouttime]);
	FtpChPath($e,$r);
    //上传文件
	$e->fMoreTranFile($fr1,$fr);
    $e->fExit();
}

//建立ftp目录
function FtpMkdir($ftpid,$pr,$mod){
	$r=ReturnFtpInfo($ftpid);
	$e=new EmpireCMSFTP();
	$e->fconnect($r[ftphost],$r[ftpport],$r[ftpusername],$r[ftppassword],$r[ftppath],$r[ftpssl],$r[ftppasv],$r[ftpmode],$r[ftpouttime]);
	FtpChPath($e,$r);
	for($i=0;$i<count($pr);$i++)
	{
		if(stristr($pr[$i],ECMS_PATH))
		{
			$pr[$i]=FtpRTruePath($r[ftppath],str_replace(ECMS_PATH,'',$pr[$i]));
		}
		if(!$e->fChdir($pr[$i]))
		{
			$e->fMkdir($pr[$i]);
			if($mod)
			{
				$e->fChmoddir($mod,$pr[$i]);
			}
		}
	}
    $e->fExit();
}

//返回ftp信息
function ReturnFtpInfo($ftpid){
	global $empire,$dbtbpre;
	$r=$empire->fetch1("select * from {$dbtbpre}enewspublic limit 1");
	return $r;
}

//初使化发布任务
function AddPostUrlData($postdata,$userid,$username){
	global $empire,$fun_r,$dbtbpre;
	$count=count($postdata);
	if(empty($count))
	{printerror("NotPostData","history.go(-1)");}
	//操作权限
	CheckLevel($userid,$username,$classid,"postdata");
	$e="!!!";
	$rnd=md5(uniqid(microtime()));
	for($i=0;$i<$count;$i++)
	{
		$r=explode($e,$postdata[$i]);
		$r[1]=(int)$r[1];
		$sql=$empire->query("insert into {$dbtbpre}enewspostdata(rnd,postdata,ispath) values('$rnd','$r[0]',$r[1]);");
    }
	$line=(int)$_POST['line'];
	if($line==0)
	{
		$line=10;
	}
	echo $fun_r[AddPostDataSuccess]."<script>self.location.href='enews.php?enews=PostUrlData&start=0&line=$line&rnd=$rnd';</script>";
	exit();
}

//远程发布
function PostUrlData($start,$rnd,$userid,$username){
	global $empire,$fun_r,$dbtbpre,$incftp;
	if(empty($rnd))
	{printerror("FailCX","history.go(-1)");}
	//链接FTP
	if(empty($incftp))
	{
		@include(ECMS_PATH."e/class/ftp.php");
	}
	$pr=ReturnFtpInfo($ftpid);
	$e=new EmpireCMSFTP();
	$e->fconnect($pr[ftphost],$pr[ftpport],$pr[ftpusername],$pr[ftppassword],$pr[ftppath],$pr[ftpssl],$pr[ftppasv],$pr[ftpmode],$pr[ftpouttime]);
	FtpChPath($e,$pr);

	$line=(int)$_GET['line'];//每10个为一组
	$start=(int)$start;
	$b=0;
	$sql=$empire->query("select postid,postdata,ispath from {$dbtbpre}enewspostdata where rnd='$rnd' and postid>$start order by postid limit ".$line);
	while($r=$empire->fetch($sql))
	{
		$b=1;
		$newstart=$r[postid];
		//文件
		if($r[ispath])
		{
			$fr=explode(",",$r[postdata]);
			for($i=0;$i<count($fr);$i++)
			{
				$e->fTranFile(FtpRTruePath($pr[ftppath],$fr[$i]),ECMS_PATH.$fr[$i]);
			}
		}
		//目录
		else
		{
			$e->ftp_copy(ECMS_PATH.$r[postdata],FtpRTruePath($pr[ftppath],$r[postdata]));
		}
	}
	$e->fExit();
	if(empty($b))
	{
		$sql=$empire->query("delete from {$dbtbpre}enewspostdata where rnd='$rnd'");
		//操作日志
		insert_dolog("");
		printerror("PostDataSuccess","PostUrlData.php");
	}
	echo $fun_r[OnePostDataSuccess]."<script>self.location.href='enews.php?enews=PostUrlData&start=$newstart&line=$line&rnd=$rnd';</script>";
	exit();
}


//-------------- 模型区 ----------------------

//复制表
function CopyEcmsTb($otb,$tb){
	global $empire;
	$usql=$empire->query("SET SQL_QUOTE_SHOW_CREATE=1;");//设置引号
	$r=$empire->fetch1("SHOW CREATE TABLE `$otb`;");//数据表结构
	$create=str_replace("\"","\\\"",$r[1]);
	$create=str_replace($otb,$tb,$create);
	$empire->query($create);
}

//建立数据表
function SetCreateTable($sql,$dbcharset) {
	global $phome_use_dbver;
	$type=strtoupper(preg_replace("/^\s*CREATE TABLE\s+.+\s+\(.+?\).*(ENGINE|TYPE)\s*=\s*([a-z]+?).*$/isU", "\\2", $sql));
	$type = in_array($type, array('MYISAM', 'HEAP')) ? $type : 'MYISAM';
	return preg_replace("/^\s*(CREATE TABLE\s+.+\s+\(.+?\)).*$/isU", "\\1", $sql).
		($phome_use_dbver>='4.1'&&$dbcharset ? " ENGINE=$type DEFAULT CHARSET=$dbcharset" : " TYPE=$type");
}

//组合存文本
function TogSaveTxtF($ecms=0){
	global $empire,$dbtbpre;
	$savesql=$empire->query("select f,tbname from {$dbtbpre}enewsf where savetxt=1");
	$savef=',';
	while($saver=$empire->fetch($savesql))
	{
		$savef.=$saver[tbname].'.'.$saver[f].',';
	}
	$empire->query("update {$dbtbpre}enewspublic set savetxtf='$savef' limit 1");
	if($ecms==0)
	{
		GetConfig();
	}
}

//返回附件字段
function ReturnMFileF($enter,$tbname,$tid,$fform="file"){
	global $empire;
	$record="<!--record-->";
	$field="<!--field--->";
	if($tid)
	{
		$a=" and tid='$tid'";
	}
	$f=",";
	$sql=$empire->query("select f from ".$tbname." where fform='$fform'".$a);
	while($r=$empire->fetch($sql))
	{
		if(strstr($enter,$field.$r[f].$record))
		{
			$f.=$r[f].",";
		}
	}
	return $f;
}

//执行字段函数
function DoFFun($mid,$f,$value,$isadd=1,$isq=0){
	global $empire,$dbtbpre,$emod_r;
	if($isq==1)//前台
	{
		$dofun=$isadd==1?$emod_r[$mid]['qadddofunf']:$emod_r[$mid]['qeditdofunf'];
	}
	else//后台
	{
		$dofun=$isadd==1?$emod_r[$mid]['adddofunf']:$emod_r[$mid]['editdofunf'];
	}
	if(!strstr($dofun,'||'.$f.'!#!'))
	{
		return $value;
	}
	$dfr=explode('||'.$f.'!#!',$dofun);
	$dfr1=explode('||',$dfr[1]);
	$r=explode('##',$dfr1[0]);
	if($r[0])
	{
		$fun=$r[0];
		$value=$fun($mid,$f,$isadd,$isq,$value,$r[1]);
	}
	return $value;
}

//取得字段名
function ChGetFname($mid,$f){
	global $empire,$dbtbpre,$emod_r;
	$r=$empire->fetch1("select fname from {$dbtbpre}enewsf where f='$f' and tid='".$emod_r[$mid]['tid']."' limit 1");
	return $r[fname]?$r[fname]:$f;
}

//验证必填项
function ChMustAddF($mid,$f,$value){
	global $empire,$dbtbpre,$emod_r;
	if(strstr($emod_r[$mid]['mustqenterf'],','.$f.','))
	{
		if(!trim($value))
		{
			$GLOBALS['msgmustf']=ChGetFname($mid,$f);
			printerror("EmptyMustF","history.go(-1)");
		}
	}
}

//验证唯一项
function ChIsOnlyAddF($mid,$id,$f,$value,$isq=0){
	global $empire,$dbtbpre,$emod_r;
	if(strstr($emod_r[$mid]['onlyf'],','.$f.','))
	{
		if($id)
		{
			$and=" and id<>$id";
		}
		$value=RepPostStr($value);
		$num=$empire->gettotal("select count(*) as total from {$dbtbpre}ecms_".$emod_r[$mid]['tbname']." where ".$f."='".addslashes($value)."'".$and." limit 1");
		if($num)
		{
			$GLOBALS['msgisonlyf']=ChGetFname($mid,$f);
			if($isq==1)
			{
				printerror("ReIsOnlyF","history.go(-1)",1);
			}
			else
			{
				printerror("ReIsOnlyF","history.go(-1)");
			}
		}
	}
}

//数据同步
function SameDataAddF($id,$classid,$mid,$f,$value){
	global $empire,$public_r,$dbtbpre,$emod_r,$emod_pubr;
	if(strstr($emod_pubr['linkfields'],','.$emod_r[$mid]['tbname'].'.'.$f.'|'))
	{
		$value=addslashes($value);
		$r=$empire->fetch1("select ".$f." from {$dbtbpre}ecms_".$emod_r[$mid]['tbname']." where id='$id' limit 1");
		if($r[$f]<>$value)
		{
			$tbr=ReturnSameDataTb($emod_r[$mid]['tbname'],$f);
			$ltbname=$tbr[0];
			$lf=$tbr[1];
			if($ltbname&&$lf)
			{
				$empire->query("update {$dbtbpre}ecms_".$ltbname." set ".$lf."='$value' where ".$lf."='$r[$f]'");
			}
		}
	}
}

//返回数据同步表与字段名
function ReturnSameDataTb($tbname,$f){
	global $public_r,$emod_pubr;
	$expr=explode(','.$tbname.'.'.$f.'|',$emod_pubr['linkfields']);
	$expr1=explode('|',$expr[0]);
	$count=count($expr1)-1;
	$tbr=explode('.',$expr1[$count]);
	return $tbr;
}

//自定义字段返回模板字段处理
function doReturnAddTempf($temp){
	$record="<!--record-->";
	$field="<!--field--->";
	$r=explode($record,$temp);
	$count=count($r);
	$str=',';
	for($i=0;$i<$count-1;$i++)
	{
		$r1=explode($field,$r[$i]);
		$str.=$r1[1].",";
	}
	if($str==',,')
	{
		$str=',';
	}
	return $str;
}

//组合复选框数据
function ReturnCheckboxAddF($r,$mid,$f){
	global $public_r,$emod_r;
	$val=$r;
	if(is_array($r)&&strstr($emod_r[$mid]['checkboxf'],','.$f.','))
	{
		$val='';
		$count=count($r);
		for($i=0;$i<$count;$i++)
		{
			$val.=$r[$i].'|';
		}
		if($val)
		{
			$val='|'.$val;
		}
	}
	return $val;
}

//返回自定义字段
function ReturnAddF($add,$modid,$userid,$username,$do=0,$rdata=0,$ch=0){
	global $empire,$public_r,$dbtbpre,$emod_r;
	if($do==0||$do==1)
	{
		//导入gd处理文件
		if($add['mark']||$add['getfirsttitlespic']||$add['mcreatespic'])
		{
			include_once(ECMS_PATH.'e/class/gd.php');
		}
	}
	$ret_r['tb']=$emod_r[$modid]['deftb'];
	$r=explode(',',$emod_r[$modid][enter]);
	$count=count($r)-1;
	if(empty($do))//增加
	{
		//数据库操作
		for($i=1;$i<$count;$i++)
		{
			$f=$r[$i];
			if($f=='special.field'||!strstr($emod_r[$modid]['canaddf'],','.$f.','))
			{
				continue;
			}
			$add[$f]=ReturnCheckboxAddF($add[$f],$modid,$f);//复选框
			$value=RepPhpAspJspcodeText($add[$f]);
			if($f=='newstime')//时间
			{
				$value=empty($value)?time():to_time($value);
			}
			elseif($f=="morepic")//图片集
			{
				$value=ReturnMorepicpath($add['msmallpic'],$add['mbigpic'],$add['mpicname'],$add['mdelpicid'],$add['mpicid'],$add,$add['mpicurl_qz'],0);
			}
			elseif($f=="downpath")//下载地址
			{
				$value=ReturnDownpath($add['downname'],$add['downpath'],$add['delpathid'],$add['pathid'],$add['downuser'],$add['fen'],$add['thedownqz'],$add,$add['foruser'],$add['downurl_qz'],0);
			}
			elseif($f=="onlinepath")//在线地址
			{
				$value=ReturnDownpath($add['odownname'],$add['odownpath'],$add['odelpathid'],$add['opathid'],$add['odownuser'],$add['ofen'],$add['othedownqz'],$add,$add['oforuser'],$add['onlineurl_qz'],0);
			}
			elseif($f=="smalltext")//简介
			{
				if(!trim($value))
				{
					$value=SubSmalltextVal($add[newstext],$public_r[smalltextlen]);//截取新闻内容
				}
			}
			elseif($f=='infoip')//ip
			{
				$value=egetip();
			}
			elseif($f=='infozm')//字母
			{
				$value=$value?$value:GetInfoZm($add[title]);
			}
			//处理函数
			$value=DoFFun($modid,$f,$value,1,0);
			//检测必填字段
			if($ch==1&&empty($add['titleurl']))
			{
				ChMustAddF($modid,$f,$value);
				ChIsOnlyAddF($modid,0,$f,$value,0);//唯一值
			}
			//编辑器
			if($f=="newstext")
			{
				//远程保存
				$value=addslashes(CopyImg(stripSlashes($value),$add[copyimg],$add[copyflash],$add[classid],$add[qz_url],$username,$add['id'],$add['filepass'],$add['mark']));
				//自动分页
				if($add[autopage]&&!strstr($value,"[!--empirenews.page--]"))
				{
					if(empty($add[autosize]))
					{$add[autosize]=5000;}
					$value=AutoDoPage($value,$add[autosize]);
				}
			}
			//存文本
			if($emod_r[$modid]['savetxtf']&&$f==$emod_r[$modid]['savetxtf'])
			{
				//建立目录
				$thetxtfile=GetFileMd5();
				$truevalue=MkDirTxtFile(date("Y/md"),$thetxtfile);
				//写放文件
				EditTxtFieldText($truevalue,$value);
				$value=$truevalue;
			}
			if(strstr($emod_r[$modid]['tbdataf'],','.$f.','))//副表
			{
				$ret_r['datafields'].=",".$f;
				$ret_r['datavalues'].=",'".addslashes($value)."'";
			}
			else//主表
			{
				$ret_r['fields'].=",".$f;
				$ret_r['values'].=",'".addslashes($value)."'";
			}
		}
	}
	elseif($do==1)//修改
	{
		//数据库操作
		for($i=1;$i<$count;$i++)
		{
			$f=$r[$i];
			if($f=="special.field"||!strstr($emod_r[$modid]['caneditf'],','.$f.','))
			{
				continue;
			}
			$add[$f]=ReturnCheckboxAddF($add[$f],$modid,$f);//复选框
			$value=RepPhpAspJspcodeText($add[$f]);
			if($f=='newstime')//时间
			{
				$value=empty($value)?time():to_time($value);
			}
			elseif($f=="morepic")//图片集
			{
				$value=ReturnMorepicpath($add['msmallpic'],$add['mbigpic'],$add['mpicname'],$add['mdelpicid'],$add['mpicid'],$add,$add['mpicurl_qz'],1);
			}
			elseif($f=="downpath")//下载地址
			{
				$value=ReturnDownpath($add['downname'],$add['downpath'],$add['delpathid'],$add['pathid'],$add['downuser'],$add['fen'],$add['thedownqz'],$add,$add['foruser'],$add['downurl_qz'],1);
			}
			elseif($f=="onlinepath")//在线地址
			{
				$value=ReturnDownpath($add['odownname'],$add['odownpath'],$add['odelpathid'],$add['opathid'],$add['odownuser'],$add['ofen'],$add['othedownqz'],$add,$add['oforuser'],$add['onlineurl_qz'],1);
			}
			elseif($f=="smalltext")//简介
			{
				if(!trim($value))
				{
					$value=SubSmalltextVal($add[newstext],$public_r[smalltextlen]);//截取新闻内容
				}
			}
			elseif($f=='infozm')//字母
			{
				$value=$value?$value:GetInfoZm($add[title]);
			}
			//处理函数
			$value=DoFFun($modid,$f,$value,0,0);
			//检测必填字段
			if($ch==1&&empty($add['titleurl']))
			{
				ChMustAddF($modid,$f,$value);
				ChIsOnlyAddF($modid,$add[id],$f,$value,0);//唯一值
			}
			//数据同步
			SameDataAddF($add[id],$add[classid],$modid,$f,$value);
			//内容
			if($f=="newstext")
			{
				//远程保存
				$value=addslashes(CopyImg(stripSlashes($value),$add[copyimg],$add[copyflash],$add[classid],$add[qz_url],$username,$add['id'],$add['filepass'],$add['mark']));
				//自动分页
				if($add[autopage]&&!strstr($value,"[!--empirenews.page--]"))
				{
					if(empty($add[autosize]))
					{$add[autosize]=5000;}
					$value=AutoDoPage($value,$add[autosize]);
				}
			}
			//存文本
			if($emod_r[$modid]['savetxtf']&&$f==$emod_r[$modid]['savetxtf'])
			{
				//建立目录
				$newstexttxt_r=explode("/",$add[newstext_url]);
				$thetxtfile=$newstexttxt_r[2];
				$truevalue=MkDirTxtFile($newstexttxt_r[0]."/".$newstexttxt_r[1],$thetxtfile);
				//写放文件
				EditTxtFieldText($truevalue,$value);
				$value=$truevalue;
			}
			if(strstr($emod_r[$modid]['tbdataf'],','.$f.','))//副表
			{
				$ret_r['datafields'].=",".$f;
				$ret_r['datavalues'].=",".$f."='".addslashes($value)."'";
			}
			else//主表
			{
				$ret_r['fields'].=",".$f;
				$ret_r['values'].=",".$f."='".addslashes($value)."'";
			}
		}
	}
	elseif($do==8)//同步修改
	{
		//数据库操作
		for($i=1;$i<$count;$i++)
		{
			$f=$r[$i];
			if($f=='special.field')
			{
				continue;
			}
			$value=$add[$f];
			//存文本
			if($emod_r[$modid]['savetxtf']&&$f==$emod_r[$modid]['savetxtf'])
			{
				//建立目录
				$newstexttxt_r=explode("/",$add[newstext_url]);
				$thetxtfile=$newstexttxt_r[2];
				$truevalue=MkDirTxtFile($newstexttxt_r[0]."/".$newstexttxt_r[1],$thetxtfile);
				//写放文件
				EditTxtFieldText($truevalue,$value);
				$value=$truevalue;
			}
			if(strstr($emod_r[$modid]['tbdataf'],','.$f.','))//副表
			{
				$ret_r['datafields'].=",".$f;
				$ret_r['datavalues'].=",".$f."='".StripAddsData($value)."'";
			}
			else//主表
			{
				$ret_r['fields'].=",".$f;
				$ret_r['values'].=",".$f."='".StripAddsData($value)."'";
			}
		}
	}
	elseif($do==9)//复制
	{
		//数据库操作
		for($i=1;$i<$count;$i++)
		{
			$f=$r[$i];
			if($f=='special.field')
			{
				continue;
			}
			$value=$add[$f];
			//存文本
			if($emod_r[$modid]['savetxtf']&&$f==$emod_r[$modid]['savetxtf'])
			{
				//建立目录
				$thetxtfile=GetFileMd5();
				$truevalue=MkDirTxtFile(date("Y/md"),$thetxtfile);
				//写放文件
				EditTxtFieldText($truevalue,$value);
				$value=$truevalue;
			}
			if(strstr($emod_r[$modid]['tbdataf'],','.$f.','))//副表
			{
				$ret_r['datafields'].=",".$f;
				$ret_r['datavalues'].=",'".StripAddsData($value)."'";
			}
			else//主表
			{
				$ret_r['fields'].=",".$f;
				$ret_r['values'].=",'".StripAddsData($value)."'";
			}
		}
	}
	elseif($do==10)//归档
	{
		//数据库操作
		for($i=1;$i<$count;$i++)
		{
			$f=$r[$i];
			if($f=='special.field')
			{
				continue;
			}
			$value=$add[$f];
			if(strstr($emod_r[$modid]['tbdataf'],','.$f.','))//副表
			{
				$ret_r['datafields'].=",".$f;
				$ret_r['datavalues'].=",'".StripAddsData($value)."'";
			}
			else//主表
			{
				$ret_r['fields'].=",".$f;
				$ret_r['values'].=",'".StripAddsData($value)."'";
			}
		}
	}
	return $ret_r;
}

//返回采集字段
function ReturnAddCj($add,$cj,$do=0){
	global $empire;
	$record="<!--record-->";
	$field="<!--field--->";
	$record_r=explode($record,$cj);
	for($i=0;$i<count($record_r)-1;$i++)
	{
		$field_r=explode($field,$record_r[$i]);
		//增加
		if(empty($do))
		{
			$f1="zz_".$field_r[1];
			$f2="z_".$field_r[1];
			$f3="qz_".$field_r[1];
			$f4="save_".$field_r[1];
			$ret_r[0].=",".$f1.",".$f2.",".$f3.",".$f4;
			$ret_r[1].=",'".addslashes($add[$f1])."','".addslashes($add[$f2])."','".addslashes($add[$f3])."','".$add[$f4]."'";
	    }
		//修改
		else
		{
			$f1="zz_".$field_r[1];
			$f2="z_".$field_r[1];
			$f3="qz_".$field_r[1];
			$f4="save_".$field_r[1];
			$ret_r[0].=",".$f1."='".addslashes($add[$f1])."',".$f2."='".addslashes($add[$f2])."',".$f3."='".addslashes($add[$f3])."',".$f4."='".$add[$f4]."'";
	    }
    }
	return $ret_r;
}

//图片集上传图片
function SaveMorepicFile($varname,$msavepic,$i,$picurl,$picname,$classid,$id,$add){
	global $public_r,$empire,$loginin,$dbtbpre,$tranpicturetype;
	if($varname=="mbigpfile")
	{
		$addname="[b]";
	}
	$type=1;
	$r[url]=$picurl;
	//上传
	if($_FILES[$varname]['name'][$i])
	{
		//取得文件类型
		$filetype=GetFiletype($_FILES[$varname]['name'][$i]);
		//允许上传类型
		if(CheckSaveTranFiletype($filetype))
		{
			return $r;
		}
		if(!strstr($public_r['filetype'],"|".$filetype."|"))
		{
			return $r;
		}
		//图片文件
		if(!strstr($tranpicturetype,','.$filetype.','))
		{
			return $r;
		}
		//文件大小
		if($_FILES[$varname]['size'][$i]>$public_r['filesize']*1024)
		{
			return $r;
		}
		//上传
		$r=DoTranFile($_FILES[$varname]['tmp_name'][$i],$_FILES[$varname]['name'][$i],$_FILES[$varname]['type'][$i],$_FILES[$varname]['size'][$i],$classid);
		//------------------------写入数据库
		$r[filesize]=(int)$r[filesize];
		$classid=(int)$classid;
		$filetime=date("Y-m-d H:i:s");
		if(empty($picname))
		{
			$picname=$r[filename];
		}
		else
		{
			$picname=$addname.$picname;
		}
		$picname=RepPostStr($picname);
		$id=(int)$id;
		$cjid=0;
		if(!$id)
		{
			$cjid=(int)$add['filepass'];
		}
		$sql=$empire->query("insert into {$dbtbpre}enewsfile(filename,filesize,adduser,path,filetime,classid,no,type,onclick,id,cjid,fpath) values('$r[filename]',$r[filesize],'$loginin','$r[filepath]','$filetime',$classid,'$picname',$type,0,$id,$cjid,'$public_r[fpath]');");
		return $r;
	}
	//远程保存
	else
	{
		if(empty($msavepic))
		{
			return $r;
		}
		if(empty($picurl))
		{
			return $r;
		}
		//----------------取得文件类型
		$filetype=GetFiletype($picurl);
		//允许上传类型
		if(CheckSaveTranFiletype($filetype))
		{
			return $r;
		}
		if(!strstr($public_r['filetype'],"|".$filetype."|"))
		{
			return $r;
		}
		//图片文件
		if(!strstr($tranpicturetype,','.$filetype.','))
		{
			return $r;
		}
		//保存
		$r=DoTranUrl($picurl,$classid);
		if($r['tran'])
		{
			//记录数据库
			$filetime=date("Y-m-d H:i:s");
			//变量处理
			$r[filesize]=(int)$r[filesize];
			$classid=(int)$classid;
			$r[type]=(int)$r[type];
			if(empty($picname))
			{
				$picname=$r[filename];
			}
			else
			{
				$picname=$addname.$picname;
			}
			$picname=RepPostStr($picname);
			$id=(int)$id;
			$cjid=0;
			if(!$id)
			{
				$cjid=(int)$add['filepass'];
			}
			$sql=$empire->query("insert into {$dbtbpre}enewsfile(filename,filesize,adduser,path,filetime,classid,no,type,id,cjid,onclick,fpath) values('$r[filename]',$r[filesize],'$loginin','$r[filepath]','$filetime',$classid,'$picname',$type,$id,$cjid,0,'$public_r[fpath]');");
			return $r;
		}
		return $r;
	}
}

//入库时远程保存
function LoadInSaveMorepicFile($morepic,$msavepic,$classid,$id,$add){
	if(empty($morepic)||!$msavepic)
	{
		return $morepic;
	}
	$f_exp="::::::";
	$r_exp="\r\n";
	$returnstr="";
	$r=explode($r_exp,$morepic);
	$countr=count($r);
	for($i=0;$i<$countr;$i++)
	{
		$r1=explode($f_exp,$r[$i]);
		//小图
		$smpr=SaveMorepicFile("msmallpfile",$msavepic,0,$r1[0],$r1[2],$classid,$id,$add);
		$spic=$smpr[url];
		//大图
		if($r1[0]!=$r1[1])
		{
			$bmpr=SaveMorepicFile("mbigpfile",$msavepic,0,$r1[1],$r1[2],$classid,$id,$add);
			$bpic=$bmpr[url];
		}
		else
		{
			$bpic=$spic;
		}
		if($spic)
		{
			$returnstr.=$spic.$f_exp.$bpic.$f_exp.$r1[2].$r_exp;
		}
	}
	//去掉最后的字符
	$returnstr=substr($returnstr,0,strlen($returnstr)-2);
	return $returnstr;
}

//---------图片地址组合
function ReturnMorepicpath($smallpic,$bigpic,$picname,$delpicid,$picid,$add,$downurl,$down=0){
	global $loginin,$logininid;
	$f_exp="::::::";
	$r_exp="\r\n";
	$returnstr="";
    $downurl=str_replace($f_exp,"",$downurl);
	$downurl=str_replace($r_exp,"",$downurl);
	//增加信息
	if(empty($down))
	{
		for($i=0;$i<count($smallpic);$i++)
		{
			$name=str_replace($f_exp,"",$picname[$i]);
			$name=str_replace($r_exp,"",$name);
			//替换非法字符
			$spic=str_replace($f_exp,"",$smallpic[$i]);
			$spic=str_replace($r_exp,"",$spic);
			$spic=$spic?$downurl.$spic:'';
			//保存图片
			$smpr=SaveMorepicFile("msmallpfile",$add[msavepic],$i,$spic,$name,$add[classid],$add[id],$add);
			$spic=$smpr[url];

			//如没有大图的话跟缩略图一样
			if(empty($bigpic[$i])&&!$_FILES['mbigpfile']['name'][$i])
			{
				$bpic=$spic;
			}
			else
			{
				$bpic=str_replace($f_exp,"",$bigpic[$i]);
				$bpic=str_replace($r_exp,"",$bpic);
				$bpic=$bpic?$downurl.$bpic:'';
				//保存图片
				$bmpr=SaveMorepicFile("mbigpfile",$add[msavepic],$i,$bpic,$name,$add[classid],$add[id],$add);
				$bpic=$bmpr[url];
				//生成缩图
				if(empty($spic)&&$bpic&&$bmpr[tran]&&$add[mcreatespic])
				{
					$picno='[b]'.($name?$name:$bmpr[filename]);
					$sfiler=GetMySmallImg($add['classid'],$picno,$bmpr[insertfile],$bmpr[filepath],$bmpr[yname],$add[mcreatespicwidth],$add[mcreatespicheight],$bmpr[name],$add['filepass'],$add['filepass'],$logininid,$loginin);
					$spic=str_replace("/".$bmpr[filename],"/small".$bmpr[insertfile].$sfiler['filetype'],$bmpr[url]);
				}
			}
			if(empty($spic))
			{
				$spic=$bpic;
			}
			if($spic)
			{$returnstr.=$spic.$f_exp.$bpic.$f_exp.$name.$r_exp;}
		}
	}
	//修改信息
	else
	{
		for($i=0;$i<count($smallpic);$i++)
		{
			//删除地址
			$del=0;
			for($j=0;$j<count($delpicid);$j++)
			{
				if($delpicid[$j]==$picid[$i])
				{$del=1;}
			}
			if($del)
			{continue;}
			$name=str_replace($f_exp,"",$picname[$i]);
			$name=str_replace($r_exp,"",$name);
			//替换非法字符
			$spic=str_replace($f_exp,"",$smallpic[$i]);
			$spic=str_replace($r_exp,"",$spic);
			$spic=$spic?$downurl.$spic:'';
			//保存图片
			$smpr=SaveMorepicFile("msmallpfile",$add[msavepic],$i,$spic,$name,$add[classid],$add[id],$add);
			$spic=$smpr[url];

			//如没有大图的话跟缩略图一样
			if(empty($bigpic[$i])&&!$_FILES['mbigpfile']['name'][$i])
			{
				$bpic=$spic;
			}
			else
			{
				$bpic=str_replace($f_exp,"",$bigpic[$i]);
				$bpic=str_replace($r_exp,"",$bpic);
				$bpic=$bpic?$downurl.$bpic:'';
				//保存图片
				$bmpr=SaveMorepicFile("mbigpfile",$add[msavepic],$i,$bpic,$name,$add[classid],$add[id],$add);
				$bpic=$bmpr[url];
				//生成缩图
				if(empty($spic)&&$bpic&&$bmpr[tran]&&$add[mcreatespic])
				{
					$picno='[b]'.($name?$name:$bmpr[filename]);
					$sfiler=GetMySmallImg($add['classid'],$picno,$bmpr[insertfile],$bmpr[filepath],$bmpr[yname],$add[mcreatespicwidth],$add[mcreatespicheight],$bmpr[name],$add['filepass'],$add['filepass'],$logininid,$loginin);
					$spic=str_replace("/".$bmpr[filename],"/small".$bmpr[insertfile].$sfiler['filetype'],$bmpr[url]);
				}
			}
			if(empty($spic))
			{
				$spic=$bpic;
			}
			if($spic)
			{$returnstr.=$spic.$f_exp.$bpic.$f_exp.$name.$r_exp;}
		}
	}
	//去掉最后的字符
	$returnstr=substr($returnstr,0,strlen($returnstr)-2);
	return $returnstr;
}

//---------下载地址组合
function ReturnDownpath($downname,$downpath,$delpathid,$pathid,$downuser,$fen,$thedownqz,$add,$foruser,$downurl,$down=0){
	$f_exp="::::::";
	$r_exp="\r\n";
	$returnstr="";
    $downurl=str_replace($f_exp,"",$downurl);
	$downurl=str_replace($r_exp,"",$downurl);
	//增加软件
	if(empty($down))
	{
		for($i=0;$i<count($downname);$i++)
		{
			//替换非法字符
			$name=str_replace($f_exp,"",$downname[$i]);
			$name=str_replace($r_exp,"",$downname[$i]);
			$path=str_replace($f_exp,"",$downpath[$i]);
			$path=str_replace($r_exp,"",$downpath[$i]);
			//批量更换权限
			if($add[doforuser])
			{
				if(empty($foruser))
				{
					$foruser=0;
			    }
				$fuser=$foruser;
		    }
			else
			{
				if(empty($downuser[$i]))
				{
					$fuser=0;
			    }
				else
				{
					$fuser=$downuser[$i];
				}
		    }
			//批量更新点数
			if($add[dodownfen])
			{
				if(empty($add[downfen]))
				{
					$add[downfen]=0;
				}
				$ffen=$add[downfen];
			}
			else
			{
				if(empty($fen[$i]))
				{
					$ffen=0;
				}
				else
				{
					$ffen=$fen[$i];
				}
			}
			$downqz=$thedownqz[$i];
			if($path&&$name)
			{$returnstr.=$name.$f_exp.$downurl.$path.$f_exp.$fuser.$f_exp.$ffen.$f_exp.$downqz.$r_exp;}
		}
	}
	//修改软件
	else
	{
		for($i=0;$i<count($downname);$i++)
		{
			//删除下载地址
			$del=0;
			for($j=0;$j<count($delpathid);$j++)
			{
				if($delpathid[$j]==$pathid[$i])
				{$del=1;}
			}
			if($del)
			{continue;}
			//替换非法字符
			$name=str_replace($f_exp,"",$downname[$i]);
			$name=str_replace($r_exp,"",$downname[$i]);
			$path=str_replace($f_exp,"",$downpath[$i]);
			$path=str_replace($r_exp,"",$downpath[$i]);
			//批量更换权限
			if($add[doforuser])
			{
				if(empty($foruser))
				{
					$foruser=0;
			    }
				$fuser=$foruser;
		    }
			else
			{
				if(empty($downuser[$i]))
				{
					$fuser=0;
			    }
				else
				{
					$fuser=$downuser[$i];
				}
		    }
			//批量更新点数
			if($add[dodownfen])
			{
				if(empty($add[downfen]))
				{
					$add[downfen]=0;
				}
				$ffen=$add[downfen];
			}
			else
			{
				if(empty($fen[$i]))
				{
					$ffen=0;
				}
				else
				{
					$ffen=$fen[$i];
				}
			}
			$downqz=$thedownqz[$i];
			if($path&&$name)
			{$returnstr.=$name.$f_exp.$downurl.$path.$f_exp.$fuser.$f_exp.$ffen.$f_exp.$downqz.$r_exp;}
		}
	}
	//去掉最后的字符
	$returnstr=substr($returnstr,0,strlen($returnstr)-2);
	return $returnstr;
}

//-------------- 缓存区 ----------------------

//一级栏目导航
function GetClassNavCache($line,$navfh){
	global $empire,$dbtbpre,$public_r;
	$limit='';
	if($line)
	{
		$limit=" limit ".$line;
	}
	$navs='';
	$fh='';
	$sql=$empire->query("select classid,classname,wburl,listdt,classurl,classpath from {$dbtbpre}enewsclass where bclassid=0 and showclass=0 order by myorder,classid".$limit);
	while($r=$empire->fetch($sql))
	{
		$classurl=sys_ReturnBqClassUrl($r);
		if($navs)
		{
			$fh=$navfh;
		}
		$navs.=$fh."<a href=\"".$classurl."\">".$r[classname]."</a>";
	}
	return $navs;
}

//生成配置文件
function GetConfig($domod=0){
	$filename=ECMS_PATH."e/class/config.php";
	$exp='//-------EmpireCMS.Public.Cache-------';
	$text=ReadFiletext($filename);
	$r=explode($exp,$text);
	if($r[0]=='')
	{
		return false;
	}
	$r[1]=GetPubCache();
	if($domod==1)
	{
		$r[2]=GetModCache();
	}
	$setting=$r[0].$exp.$r[1].$exp.$r[2].$exp.$r[3];
	WriteFiletext_n($filename,$setting);
}

//更新公共缓存
function GetPubCache(){
	global $empire,$dbtbpre;
	$r=$empire->fetch1("select * from {$dbtbpre}enewspublic limit 1");
	$tr=$empire->fetch1("select downsofttemp,onlinemovietemp,listpagetemp from ".GetTemptb("enewspubtemp")." limit 1");
	$GLOBALS['public_r']['newsurl']=$r['newsurl'];
	$classnavs=GetClassNavCache($r[classnavline],$r[classnavfh]);
	$checkdorepstr=ReturnCheckDoRep();
	$setting.="

//------------e_public
\$public_r=array('sitename'=>'".addslashes($r[sitename])."',
'newsurl'=>'".addslashes($r[newsurl])."',
'filetype'=>'".addslashes($r[filetype])."',
'filesize'=>".$r[filesize].",
'relistnum'=>".$r[relistnum].",
'renewsnum'=>".$r[renewsnum].",
'min_keyboard'=>".$r[min_keyboard].",
'max_keyboard'=>".$r[max_keyboard].",
'search_num'=>".$r[search_num].",
'search_pagenum'=>".$r[search_pagenum].",
'newslink'=>".$r[newslink].",
'checked'=>".$r[checked].",
'pltime'=>".$r[pltime].",
'searchtime'=>".$r[searchtime].",
'loginnum'=>".$r[loginnum].",
'logintime'=>".$r[logintime].",
'addnews_ok'=>".$r[addnews_ok].",
'register_ok'=>".$r[register_ok].",
'indextype'=>'".addslashes($r[indextype])."',
'goodlencord'=>".$r[goodlencord].",
'goodtype'=>'".addslashes($r[goodtype])."',
'searchtype'=>'".addslashes($r[searchtype])."',
'exittime'=>".$r[exittime].",
'smalltextlen'=>".$r[smalltextlen].",
'defaultgroupid'=>".$r[defaultgroupid].",
'fileurl'=>'".addslashes($r[fileurl])."',
'install'=>".$r[install].",
'phpmode'=>".$r[phpmode].",
'plsize'=>".$r[plsize].",
'plincludesize'=>".$r[plincludesize].",
'dorepnum'=>".$r[dorepnum].",
'loadtempnum'=>".$r[loadtempnum].",
'bakdbpath'=>'".addslashes($r[bakdbpath])."',
'bakdbzip'=>'".addslashes($r[bakdbzip])."',
'downpass'=>'".addslashes($r[downpass])."',
'filechmod'=>".$r[filechmod].",
'loginkey_ok'=>".$r[loginkey_ok].",
'tbname'=>'".addslashes($r[tbname])."',
'limittype'=>".$r[limittype].",
'plkey_ok'=>".$r[plkey_ok].",
'redodown'=>".$r[redodown].",
'downsofttemp'=>'".addslashes(stripSlashes($tr[downsofttemp]))."',
'onlinemovietemp'=>'".addslashes(stripSlashes($tr[onlinemovietemp]))."',
'havefp'=>".$r[havefp].",
'fpnum'=>".$r[fpnum].",
'lctime'=>".$r[lctime].",
'candocode'=>".$r[candocode].",
'opennotcj'=>".$r[opennotcj].",
'listpagetemp'=>'".addslashes(stripSlashes($tr[listpagetemp]))."',
'reuserpagenum'=>".$r[reuserpagenum].",
'revotejsnum'=>".$r[revotejsnum].",
'readjsnum'=>".$r[readjsnum].",
'qaddtran'=>".$r[qaddtran].",
'qaddtransize'=>".$r[qaddtransize].",
'ebakthisdb'=>".$r[ebakthisdb].",
'delnewsnum'=>".$r[delnewsnum].",
'markpos'=>".$r[markpos].",
'markimg'=>'".addslashes($r[markimg])."',
'marktext'=>'".addslashes($r[marktext])."',
'markfontsize'=>'".addslashes($r[markfontsize])."',
'markfontcolor'=>'".addslashes($r[markfontcolor])."',
'markfont'=>'".addslashes($r[markfont])."',
'adminloginkey'=>".$r[adminloginkey].",
'php_outtime'=>".$r[php_outtime].",
'listpagefun'=>'".addslashes($r[listpagefun])."',
'textpagefun'=>'".addslashes($r[textpagefun])."',
'adfile'=>'".addslashes($r[adfile])."',
'notsaveurl'=>'".addslashes($r[notsaveurl])."',
'rssnum'=>".$r[rssnum].",
'rsssub'=>".$r[rsssub].",
'savetxtf'=>'".addslashes($r[savetxtf])."',
'dorepdlevelnum'=>".$r[dorepdlevelnum].",
'listpagelistfun'=>'".addslashes($r[listpagelistfun])."',
'listpagelistnum'=>".$r[listpagelistnum].",
'infolinknum'=>".$r[infolinknum].",
'searchgroupid'=>".$r[searchgroupid].",
'opencopytext'=>".$r[opencopytext].",
'reuserjsnum'=>".$r[reuserjsnum].",
'reuserlistnum'=>".$r[reuserlistnum].",
'opentitleurl'=>".$r[opentitleurl].",
'searchtempvar'=>".$r[searchtempvar].",
'showinfolevel'=>".$r[showinfolevel].",
'navfh'=>'".addslashes($r[navfh])."',
'spicwidth'=>".$r[spicwidth].",
'spicheight'=>".$r[spicheight].",
'spickill'=>".$r[spickill].",
'jpgquality'=>".$r[jpgquality].",
'markpct'=>".$r[markpct].",
'redoview'=>".$r[redoview].",
'reggetfen'=>".$r[reggetfen].",
'regbooktime'=>".$r[regbooktime].",
'revotetime'=>".$r[revotetime].",
'fpath'=>".$r[fpath].",
'filepath'=>'".addslashes($r[filepath])."',
'nreclass'=>'".addslashes($r[nreclass])."',
'nreinfo'=>'".addslashes($r[nreinfo])."',
'nrejs'=>'".addslashes($r[nrejs])."',
'nottobq'=>'".addslashes($r[nottobq])."',
'defspacestyleid'=>".$r[defspacestyleid].",
'canposturl'=>'".addslashes($r[canposturl])."',
'openspace'=>".$r[openspace].",
'defadminstyle'=>".$r[defadminstyle].",
'realltime'=>".$r[realltime].",
'closeip'=>'".addslashes($r[closeip])."',
'openip'=>'".addslashes($r[openip])."',
'hopenip'=>'".addslashes($r[hopenip])."',
'plface'=>'".addslashes($r[plface])."',
'plfacenum'=>".$r[plfacenum].",
'textpagelistnum'=>".$r[textpagelistnum].",
'memberlistlevel'=>".$r[memberlistlevel].",
'ebakcanlistdb'=>".$r[ebakcanlistdb].",
'keytog'=>".$r[keytog].",
'keytime'=>".$r[keytime].",
'keyrnd'=>'".addslashes($r[keyrnd])."',
'checkdorepstr'=>'".addslashes($checkdorepstr)."',
'regkey_ok'=>".$r[regkey_ok].",
'opengetdown'=>".$r[opengetdown].",
'gbkey_ok'=>".$r[gbkey_ok].",
'fbkey_ok'=>".$r[fbkey_ok].",
'newaddinfotime'=>".$r[newaddinfotime].",
'classnavs'=>'".addslashes($classnavs)."',
'plgroupid'=>".$r[plgroupid].",
'adminstyle'=>'".addslashes($r[adminstyle])."',
'docnewsnum'=>".$r[docnewsnum].",
'openschall'=>".$r[openschall].",
'schallfield'=>".$r[schallfield].",
'schallminlen'=>".$r[schallminlen].",
'schallmaxlen'=>".$r[schallmaxlen].",
'schallnum'=>".$r[schallnum].",
'schallpagenum'=>".$r[schallpagenum].",
'dtcanbq'=>".$r[dtcanbq].",
'dtcachetime'=>".$r[dtcachetime].",
'defpltempid'=>".$r[defpltempid].",
'buycarnum'=>".$r[buycarnum].",
'shopddgroupid'=>".$r[shopddgroupid].",
'repkeynum'=>".$r[repkeynum].",
'regacttype'=>".$r[regacttype].",
'opengetpass'=>".$r[opengetpass].",
'hlistinfonum'=>".$r[hlistinfonum].",
'qlistinfonum'=>".$r[qlistinfonum].",
'dtncanbq'=>".$r[dtncanbq].",
'dtncachetime'=>".$r[dtncachetime].",
'readdinfotime'=>".$r[readdinfotime].",
'qeditinfotime'=>".$r[qeditinfotime].",
'deftempid'=>".$r[deftempid].");
//------------e_public

";
	return $setting;
}

//更新模型缓存
function GetModCache(){
	global $empire,$dbtbpre;
	$alllinkfields='|';//关联同步
	$modsql=$empire->query("select * from {$dbtbpre}enewsmod");
	while($mr=$empire->fetch($modsql))
	{
		$listtempf=doReturnAddTempf($mr['listtempvar']);//列表模板
		$texttempf=doReturnAddTempf($mr['tempvar']);//内容模板
		$enter=doReturnAddTempf($mr['enter']);//录入项
		$qenter=doReturnAddTempf($mr['qenter']);//投稿项
		$cj=doReturnAddTempf($mr['cj']);//采集项
		//表字段
		$dataf=',';//副表字段
		$tobrf=',';//回车字段
		$dohtmlf=',';//html字段
		$savetxtf='';//存文本字段
		$pagef='';//分页字段
		$smalltextf=',';//简介字段
		$checkboxf=',';//复选框字段
		$filef=',';//附件字段
		$imgf=',';//图片字段
		$flashf=',';//FLASH字段
		$onlyf=',';//唯一字段
		$linkfields='|';//关联同步
		$editorf=',';//编辑器字段
		$ubbeditorf=',';//UBB编辑器字段
		$adddofunf='||';//增加处理函数
		$editdofunf='||';//修改处理函数
		$qadddofunf='||';//投稿增加处理函数
		$qeditdofunf='||';//投稿修改处理函数
		$fsql=$empire->query("select * from {$dbtbpre}enewsf where tid='$mr[tid]'");
		while($fr=$empire->fetch($fsql))
		{
			if($fr['tbdataf'])
			{
				$dataf.=$fr['f'].',';
			}
			if($fr['tobr'])
			{
				$tobrf.=$fr['f'].',';
			}
			if($fr['dohtml'])
			{
				$dohtmlf.=$fr['f'].',';
			}
			if($fr['savetxt'])
			{
				$savetxtf=$fr['f'];
			}
			if($fr['ispage'])
			{
				$pagef=$fr['f'];
			}
			if($fr['issmalltext'])
			{
				$smalltextf.=$fr['f'].',';
			}
			if($fr['fform']=='checkbox')
			{
				$checkboxf.=$fr['f'].',';
			}
			if($fr['fform']=='file')
			{
				$filef.=$fr['f'].',';
			}
			if($fr['fform']=='img')
			{
				$imgf.=$fr['f'].',';
			}
			if($fr['fform']=='flash')
			{
				$flashf.=$fr['f'].',';
			}
			if($fr['isonly'])
			{
				$onlyf.=$fr['f'].',';
			}
			if(($fr['fform']=='linkfield'||$fr['fform']=='linkfieldselect')&&$fr['samedata']&&$fr['linkfieldval'])
			{
				$linkfields.=$fr[f].','.$fr[linkfieldtb].'.'.$fr[linkfieldval].'|';
				$alllinkfields.=$fr[tbname].'.'.$fr[f].','.$fr[linkfieldtb].'.'.$fr[linkfieldval].'|';
			}
			if($fr['fform']=='editor')
			{
				$editorf.=$fr['f'].',';
			}
			if($fr['fform']=='ubbeditor')
			{
				$ubbeditorf.=$fr['f'].',';
			}
			if($fr['adddofun'])
			{
				$adddofunf.=$fr[f].'!#!'.$fr[adddofun].'||';
			}
			if($fr['editdofun'])
			{
				$editdofunf.=$fr[f].'!#!'.$fr[editdofun].'||';
			}
			if($fr['qadddofun'])
			{
				$qadddofunf.=$fr[f].'!#!'.$fr[qadddofun].'||';
			}
			if($fr['qeditdofun'])
			{
				$qeditdofunf.=$fr[f].'!#!'.$fr[qeditdofun].'||';
			}
		}
		//表数据
		$tr=$empire->fetch1("select * from {$dbtbpre}enewstable where tid='$mr[tid]'");
		//字符
		$mods.="\$emod_r[".$mr[mid]."]=Array('mid'=>".$mr[mid].",
'mname'=>'".addslashes($mr[mname])."',
'qmname'=>'".addslashes($mr[qmname])."',
'defaulttb'=>".$tr[isdefault].",
'datatbs'=>'".addslashes($tr[datatbs])."',
'deftb'=>'".addslashes($tr[deftb])."',
'enter'=>'".addslashes($enter)."',
'qenter'=>'".addslashes($qenter)."',
'listtempf'=>'".addslashes($listtempf)."',
'tempf'=>'".addslashes($texttempf)."',
'mustqenterf'=>'".addslashes($mr[mustqenterf])."',
'listandf'=>'".addslashes($mr[listandf])."',
'setandf'=>".$mr[setandf].",
'searchvar'=>'".addslashes($mr[searchvar])."',
'cj'=>'".addslashes($cj)."',
'canaddf'=>'".addslashes($mr[canaddf])."',
'caneditf'=>'".addslashes($mr[caneditf])."',
'tbdataf'=>'".addslashes($dataf)."',
'tobrf'=>'".addslashes($tobrf)."',
'dohtmlf'=>'".addslashes($dohtmlf)."',
'checkboxf'=>'".addslashes($checkboxf)."',
'savetxtf'=>'".addslashes($savetxtf)."',
'editorf'=>'".addslashes($editorf)."',
'ubbeditorf'=>'".addslashes($ubbeditorf)."',
'pagef'=>'".addslashes($pagef)."',
'smalltextf'=>'".addslashes($smalltextf)."',
'filef'=>'".addslashes($filef)."',
'imgf'=>'".addslashes($imgf)."',
'flashf'=>'".addslashes($flashf)."',
'linkfields'=>'".addslashes($linkfields)."',
'onlyf'=>'".addslashes($onlyf)."',
'adddofunf'=>'".addslashes($adddofunf)."',
'editdofunf'=>'".addslashes($editdofunf)."',
'qadddofunf'=>'".addslashes($qadddofunf)."',
'qeditdofunf'=>'".addslashes($qeditdofunf)."',
'definfovoteid'=>".$mr[definfovoteid].",
'orderf'=>'".addslashes($mr[orderf])."',
'sonclass'=>'".addslashes($mr[sonclass])."',
'tid'=>".$mr[tid].",
'tbname'=>'".addslashes($mr[tbname])."');
";
	}
	$mods="

\$emod_pubr=Array('linkfields'=>'".addslashes($alllinkfields)."');

\$emod_r=array();
".$mods."

";
	return $mods;
}

//会员组缓存
function GetMemberLevel(){
	global $empire,$dbtbpre;
	$file=ECMS_PATH."e/data/dbcache/MemberLevel.php";
	$sql=$empire->query("select * from {$dbtbpre}enewsmembergroup order by groupid");
	while($r=$empire->fetch($sql))
	{
		$levels.="\$level_r[".$r[groupid]."]=Array('groupid'=>".$r[groupid].",
'groupname'=>'".addslashes($r[groupname])."',
'level'=>".$r[level].",
'checked'=>".$r[checked].",
'favanum'=>".$r[favanum].",
'daydown'=>".$r[daydown].",
'msglen'=>".$r[msglen].",
'regchecked'=>".$r[regchecked].",
'spacestyleid'=>".$r[spacestyleid].",
'dayaddinfo'=>".$r[dayaddinfo].",
'infochecked'=>".$r[infochecked].",
'msgnum'=>".$r[msgnum].");
";
	}
	$levels="<?php
//level
\$level_r=array();
".$levels."
//level
?>";
	WriteFiletext_n($file,$levels);
}

//栏目缓存
function GetClass(){
	global $empire,$dbtbpre;
	$fileqz=ECMS_PATH.'e/data/dbcache/';
	$filename=$fileqz.'class.php';
	$line=250;//每个文件存放栏目数
	$num=$empire->gettotal("select count(*) as total from {$dbtbpre}enewsclass");
	$sql=$empire->query("select * from {$dbtbpre}enewsclass order by classid desc");
	$no=0;
	$p=0;
	$l="";
	$mod="";
	$modstr=",";
	while($r=$empire->fetch($sql))
	{
		$no++;
		$l="";
		if($r[wburl])//外部栏目
		{
			$l=",
'wburl'=>'".addslashes($r[wburl])."'";
		}
		elseif($r[islast])//终极栏目
		{
			//组合模型
			if(empty($mod[$r[modid]]))
			{
				$mod[$r[modid]]="|";
		    }
			$mod[$r[modid]].=$r[classid]."|";
			if(!strstr($modstr,",".$r[modid].","))
			{
				$modstr.=$r[modid].",";
			}
			$l=",
'lencord'=>".$r[lencord].",
'link_num'=>".$r[link_num].",
'newstempid'=>".$r[newstempid].",
'listtempid'=>".$r[listtempid].",
'pltempid'=>".$r[pltempid].",
'newspath'=>'".addslashes($r[newspath])."',
'filename'=>".$r[filename].",
'ipath'=>'".addslashes($r[ipath])."',
'filetype'=>'".addslashes($r[filetype])."',
'openpl'=>".$r[openpl].",
'openadd'=>".$r[openadd].",
'groupid'=>'".addslashes($r[groupid])."',
'filename_qz'=>'".addslashes($r[filename_qz])."',
'checked'=>".$r[checked].",
'docheckuser'=>".$r[docheckuser].",
'checkuser'=>'".addslashes($r[checkuser])."',
'bname'=>'".addslashes($r[bname])."',
'reorderf'=>'".addslashes($r[reorderf])."',
'reorder'=>'".addslashes($r[reorder])."',
'checkpl'=>".$r[checkpl].",
'listdt'=>".$r[listdt].",
'showdt'=>".$r[showdt];
	    }
		else
		{
			//列表式
			if($r[islist]==1&&empty($r[islast]))
			{
				$l=",
'lencord'=>".$r[lencord].",
'reorderf'=>'".addslashes($r[reorderf])."',
'reorder'=>'".addslashes($r[reorder])."',
'listtempid'=>".$r[listtempid].",
'listdt'=>".$r[listdt];
			}
			elseif($r[listtempid])
			{
				$l=",
'lencord'=>".$r[lencord].",
'reorderf'=>'".addslashes($r[reorderf])."',
'reorder'=>'".addslashes($r[reorder])."',
'listtempid'=>".$r[listtempid];
			}
		}
		if($r[dtlisttempid])
		{
			$l.=",
'dtlisttempid'=>".$r[dtlisttempid];
		}
		$classes.="\$class_r[".$r[classid]."]=Array('classid'=>".$r[classid].",
'bclassid'=>".$r[bclassid].",
'classname'=>'".addslashes($r[classname])."',
'sonclass'=>'".addslashes($r[sonclass])."',
'featherclass'=>'".addslashes($r[featherclass])."',
'islast'=>".$r[islast].",
'classpath'=>'".addslashes($r[classpath])."',
'classtype'=>'".addslashes($r[classtype])."',
'classurl'=>'".addslashes($r[classurl])."',
'searchtempid'=>".$r[searchtempid].",
'maxnum'=>".$r[maxnum].",
'down_num'=>".$r[down_num].",
'online_num'=>".$r[online_num].",
'islist'=>".$r[islist].",
'tbname'=>'".addslashes($r[tbname])."',
'modid'=>".$r[modid].$l.");
";
		if($no%$line==0||($num%$line<>0&&$num==$no))
		{
			$p++;
			$file="class".$p.".php";
			$include.="require(ECMS_PATH.'e/data/dbcache/".$file."');\r\n";
			$classes="<?php
".$classes."?>";
			WriteFiletext_n($fileqz.$file,$classes);
			$classes="";
        }
	}
	//-----专题缓存
	$zsql=$empire->query("select * from {$dbtbpre}enewszt order by ztid desc");
	$zt="";
	$zfile=$fileqz."ztclass.php";
	while($zr=$empire->fetch($zsql))
	{
		$zt.="\$class_zr[".$zr[ztid]."]=Array('ztid'=>".$zr[ztid].",
'ztname'=>'".addslashes($zr[ztname])."',
'ztnum'=>".$zr[ztnum].",
'listtempid'=>".$zr[listtempid].",
'ztpath'=>'".addslashes($zr[ztpath])."',
'zttype'=>'".addslashes($zr[zttype])."',
'zturl'=>'".addslashes($zr[zturl])."',
'islist'=>".$zr[islist].",
'maxnum'=>".$zr[maxnum].",
'reorderf'=>'".addslashes($zr[reorderf])."',
'reorder'=>'".addslashes($zr[reorder])."',
'tbname'=>'".addslashes($zr[tbname])."');
";
	}
	$zt="<?php
".$zt.GetTitleTypeCache()."?>";
	WriteFiletext_n($zfile,$zt);
	$include.="require(ECMS_PATH.'e/data/dbcache/ztclass.php');\r\n";
	$include="<?php
".AddCheckViewCode()."
\$class_r=array();
\$class_zr=array();
\$class_tr=array();
".$include."
?>";
	WriteFiletext_n($filename,$include);
	//组合模型
	$er=explode(",",$modstr);
	for($i=1;$i<count($er)-1;$i++)
	{
		$mid=$er[$i];
		$usql=$empire->query("update {$dbtbpre}enewsmod set sonclass='".$mod[$mid]."' where mid='$mid'");
    }
}

//标题分类缓存
function GetTitleTypeCache(){
	global $empire,$dbtbpre;
	$sql=$empire->query("select typeid,tname,mid from {$dbtbpre}enewsinfotype order by typeid");
	while($r=$empire->fetch($sql))
	{
		$string.="\$class_tr[".$r[typeid]."]=Array('typeid'=>".$r[typeid].",
'tname'=>'".addslashes($r[tname])."',
'mid'=>".$r[mid].");
";
	}
	return $string;
}

//全站搜索数据源缓存
function GetSearchAllTb(){
	global $empire,$dbtbpre;
	$file=ECMS_PATH."e/data/dbcache/SearchAllTb.php";
	$sql=$empire->query("select tbname,titlefield,smalltextfield from {$dbtbpre}enewssearchall_load order by lid");
	while($r=$empire->fetch($sql))
	{
		$tbs.="\$schalltb_r['".$r[tbname]."']=Array('tbname'=>'".addslashes($r[tbname])."',
'titlefield'=>'".addslashes($r[titlefield])."',
'smalltextfield'=>'".addslashes($r[smalltextfield])."');
";
	}
	$tbs="<?php
//tbs
\$schalltb_r=array();
".$tbs."
//tbs
?>";
	WriteFiletext_n($file,$tbs);
}
?>