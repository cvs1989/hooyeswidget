<?php
!function_exists('html') && exit('ERR');
if($job=="make"&&$Apower[makehtml_make])
{
	$select_news=$Guidedb->Select("{$pre}sort",'fiddb[]',$fiddb,'','0','',1,'20');
	require(dirname(__FILE__)."/"."head.php");
	require(dirname(__FILE__)."/"."template/makehtml/menu.htm");
	require(dirname(__FILE__)."/"."template/makehtml/make.htm");
	require(dirname(__FILE__)."/"."foot.php");
}
elseif($action=="make"&&$Apower[makehtml_make])
{
	if(!$fiddb[0]){
		showmsg("请选择一个栏目");
	}
	foreach($fiddb AS $key=>$fid){
		if(!$fid){
			unset($fiddb[$key]);
			continue;
		}
		$showdb[]="\$ListFid[]=$fid;";
	}
	$beginTime=preg_replace("/([\d]+)-([\d]+)-([\d]+) ([\d]+):([\d]+):([\d]+)/eis","mk_time('\\4','\\5', '\\6', '\\2', '\\3', '\\1')",$beginTime);
	$showdb[]="\$beginTime='$beginTime';";
	$endTime=preg_replace("/([\d]+)-([\d]+)-([\d]+) ([\d]+):([\d]+):([\d]+)/eis","mk_time('\\4','\\5', '\\6', '\\2', '\\3', '\\1')",$endTime);
	$showdb[]="\$endTime='$endTime';";

	$showdb[]="\$Mtype='$Mtype';";

	$showdb[]="\$beginId='$beginId';";
	$showdb[]="\$endId='$endId';";

	$showdb[]="\$list_filename='$list_filename';";
	$showdb[]="\$bencandy_filename='$bencandy_filename';";

	$showdb[]="\$www_url='$www_url';";
	
	$showdb[]="\$showNum='$showNum';";

	$write_file="<?php \r\n".implode("\n",$showdb)."\r\n?>";

	write_file(PHP168_PATH."cache/MakeHtml.php",$write_file);
	unset($urldb);
	jump("正在开始生成静态","$webdb[www_url]/do/list_html.php",0);
}
elseif($job=="end_make"&&$Apower[makehtml_make])
{
	@include_once(PHP168_PATH."cache/MakeHtml.php");
	$fids=implode(",",$ListFid);
	$query = $db->query("SELECT * FROM {$pre}sort WHERE fid IN ($fids)");
	while($rs = $db->fetch_array($query)){
		$HtmlName=$webdb[list_filename];
		$page=1;
		$fid=$rs[fid];
		eval("\$rs[htmlLink]=\"$webdb[www_url]/$HtmlName\";");
		$listdb[]=$rs;
	}
	
	require(dirname(__FILE__)."/"."head.php");
	require(dirname(__FILE__)."/"."template/makehtml/menu.htm");
	require(dirname(__FILE__)."/"."template/makehtml/end.htm");
	require(dirname(__FILE__)."/"."foot.php");
	showmsg("生成完毕");
}
elseif($job=="delhtml2"&&$Apower[makehtml_make])
{
	$detail=explode("/",$webdb[list_filename]);
	$path=PHP168_PATH."$detail[0]";
	$totalsize=getfilesize($path);
	$totalsize=number_format($totalsize/(1024*1024),3);
	
	getfile($path);//$htmldb
	if(!$page){
		$page=1;
	}
	$rows=20;
	$num=count($htmldb);
	$showpage=getpage("","","index.php?lfj=$lfj&job=$job",$rows,$num);
	$min=($page-1)*$rows;
	for($i=$min;$i<($min+$rows);$i++){
		if(!$htmldb[$i])break;
		$rs[size]=number_format(filesize($htmldb[$i])/1024,3);
		$rs[html]=str_replace(PHP168_PATH,"$webdb[www_url]/",$htmldb[$i]);
		$listdb[$i]=$rs;
	}
	require(dirname(__FILE__)."/"."head.php");
	require(dirname(__FILE__)."/"."template/makehtml/menu.htm");
	require(dirname(__FILE__)."/"."template/makehtml/listhtml.htm");
	require(dirname(__FILE__)."/"."foot.php");
	echo "<br><br><A HREF='index.php?lfj=sort&action=delhtml'>清除所有静态文件</A>";
}
elseif($action=="deletehtml"&&$Apower[makehtml_make])
{
	$detail=explode("/",$webdb[list_filename]);
	if(!$detail[0]){
		showmsg("目录不存在");
	}
	$path=PHP168_PATH."$detail[0]";
	getfile($path);
	if($step=='all')
	{	$path=PHP168_PATH."$detail[0]";
		if($Force==1){
			del_file($path);
			makepath($path);
			jump("删除成功","index.php?lfj=makehtml&job=delhtml2",1);
		}
		echo '<meta http-equiv="Content-Type" content="text/html; charset=gb2312">';
		echo "<CENTER>你确实要清空删除此目录({$path})吗?<br>[<A HREF='index.php?lfj=makehtml&action=deletehtml&step=all&Force=1'>是</A>] [<A HREF='$FROMURL'>否</A>]</CENTER>";
		exit;
	}
	elseif(is_array($iddb))
	{
		foreach( $iddb AS $key=>$value){
			unlink($htmldb[$key]);
			$j++;
		}
		jump("删除成功{$j}个","$FROMURL",1);
	}
	else
	{
		unlink($htmldb[$id]);
		jump("删除成功","$FROMURL",1);
	}
}

function getfilesize($dir) 
{//
	if(is_dir($dir))
	{
		$dh=opendir($dir);
		while(($file=readdir($dh))!='')
		{
			if($file!='.'&&$file!='..'&&is_dir($dir."/".$file))
			{
				$size+=getfilesize($dir."/".$file);
			}
			elseif(is_file($dir."/".$file))
			{
				$size+=filesize($dir."/".$file);
			}
		}
		closedir($dh);
	}
	elseif(is_file($dir))
	{	
		$size=filesize($dir);
	}
	return $size; 
}


function getfile($path){
	global $htmldb;
	if (file_exists($path)){
		if(is_file($path)){
			 $htmldb[]=$path;
		} else{
			$handle = opendir($path);
			while (($file = readdir($handle))!='') {
				if (($file!=".") && ($file!="..") && ($file!="")){
					if (is_dir("$path/$file")){
						getfile("$path/$file");
					}else{
						$htmldb[]="$path/$file";
					}
				}
			}
			closedir($handle);
		}
	}
	return $show;
}