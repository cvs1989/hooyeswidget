<?php
!function_exists('html') && exit('ERR');

if($job=="show"&&$Apower[article_more_show])
{
	$select_news=$Guidedb->Checkbox("{$pre}sort",'hideFid[]',explode(",",$webdb[hideFid]));
	$showsortlogo[intval($webdb[showsortlogo])]=" checked ";
	
	require(dirname(__FILE__)."/"."head.php");
	//require(dirname(__FILE__)."/"."template/article_more/menu.htm");
	require(dirname(__FILE__)."/"."template/article_more/show.htm");
	require(dirname(__FILE__)."/"."foot.php");
}
elseif($action=="show"&&$Apower[article_more_show])
{
	write_config_cache($webdbs);
	jump("修改成功",$FROMURL);
}

elseif($job=="avoidgather"&&$Apower[article_more_avoidgather])
{
	$AvoidGather[intval($webdb[AvoidGather])]=" checked ";
	$AvoidCopy[intval($webdb[AvoidCopy])]=" checked ";
	$AvoidSave[intval($webdb[AvoidSave])]=" checked ";
	require(dirname(__FILE__)."/"."head.php");
	//require(dirname(__FILE__)."/"."template/article_more/menu.htm");
	require(dirname(__FILE__)."/"."template/article_more/avoidgather.htm");
	require(dirname(__FILE__)."/"."foot.php");
}
elseif($action=="avoidgather"&&$Apower[article_more_avoidgather])
{
	write_config_cache($webdbs);
	jump("修改成功",$FROMURL);
}
elseif($job=="config"&&$Apower[article_more_config])
{
	$webdb[viewNoPassArticle]==='0' || $webdb[viewNoPassArticle]=1;
	$viewNoPassArticle[$webdb[viewNoPassArticle]]=" checked ";

	$webdb[ifContribute]==='0' || $webdb[ifContribute]=1;
	$ifContribute[$webdb[ifContribute]]=" checked ";

	$webdb[autoGetSmallPic]=(int)$webdb[autoGetSmallPic];
	$autoGetSmallPic[$webdb[autoGetSmallPic]]=" checked ";

	$autoGetKeyword[intval($webdb[autoGetKeyword])]=" checked ";

	$SortUseOtherModule[intval($webdb[SortUseOtherModule])]=" checked ";

	$allowGuestSearch[$webdb[allowGuestSearch]]=" checked ";

	$adminPostEditType[$webdb[adminPostEditType]]=" checked ";
	$ListShowIcon[intval($webdb[ListShowIcon])]=" checked ";
	$webdb[newArticleTime] || $webdb[newArticleTime]=24;
	$webdb[hotArticleNum] || $webdb[hotArticleNum]=100;
	$yzImgComment[$webdb[yzImgComment]]=" checked ";
	$yzImgContribute[$webdb[yzImgContribute]]=" checked ";
	$ForceDel[intval($webdb[ForceDel])]=" checked ";

	$HideNopowerPost[intval($webdb[HideNopowerPost])]=" checked ";

	$UseArticleHeart[intval($webdb[UseArticleHeart])]=" checked ";

	$UseArticleDigg[intval($webdb[UseArticleDigg])]=" checked ";

	$ForbidRepeatTitle[intval($webdb[ForbidRepeatTitle])]=" checked ";
	$AutoTitleNum[intval($webdb[AutoTitleNum])]=" checked ";

	$autoCutSmallPic[intval($webdb[autoCutSmallPic])]=" checked ";
	$ArticleDownloadUseFtp[intval($webdb[ArticleDownloadUseFtp])]=" checked ";

	$webdb[PostNotice]=str_replace("'","&#39;",$webdb[PostNotice]);

	if($webdb[ArticleDownloadDirTime]){
		$ArticleDownloadDirTime[$webdb[ArticleDownloadDirTime]]=' checked ';
	}else{
		$ArticleDownloadDirTime[0]=' checked ';
	}
	
	$showsortlogo[intval($webdb[showsortlogo])]=" checked ";

	$EditSystem[intval($webdb[EditSystem])]=" checked ";

	$allowDownMv[intval($webdb[allowDownMv])]=" checked ";
	$autoPlayFirstMv[intval($webdb[autoPlayFirstMv])]=" checked ";
	$heart_noRecord[intval($webdb[heart_noRecord])]=" checked ";
	$getLabelTpl=getLabelTpl();
	$get_S_LabelTpl=get_S_LabelTpl();

	require(dirname(__FILE__)."/"."head.php");
	//require(dirname(__FILE__)."/"."template/article_more/menu.htm");
	require(dirname(__FILE__)."/"."template/article_more/config.htm");
	require(dirname(__FILE__)."/"."foot.php");
}
elseif($action=="config"&&$Apower[article_more_config])
{
	if($webdb[ArticleDownloadUseFtp]&&(!$webdb[FtpHost]||!$webdb[FtpName]||!$webdb[FtpPwd]||!$webdb[FtpPort]||!$webdb[FtpDir])){
		showmsg('请先配置好FTP,再选择附件存放到远程服务器!');
	}
	write_config_cache($webdbs);
	jump("修改成功",$FROMURL);
}


//获取标题标签模板
function getLabelTpl($path='template/default/side_tpl'){
	global $webdb,$rsdb;
	$pictitledb[]=$f1="默认模板";
	$picurldb[]=$f2="$webdb[www_url]/$path/0.jpg";
	
	$select="<option value='$f2'>$f1</option>";
	$dir=opendir(PHP168_PATH.$path);
	while($file=readdir($dir)){
		if(eregi("\.htm$",$file)&&$file!='0.htm'){
			$pictitledb[]=str_replace(".htm","",$file);
			$picurldb[]=$f2="$webdb[www_url]/$path/".str_replace(".htm",".jpg",$file);
			$select.="<option value='$f2'>".str_replace(".htm","",$file)."</option>";
		}
	}

	$picurldb=implode('","',$picurldb);
	$pictitledb=implode('","',$pictitledb);
	$myurl=str_replace(array(".","/"),array("\.","\/"),$webdb[www_url]);
$show=<<<EOT
<table  border="0" cellspacing="0" cellpadding="0">
<tr><td style="padding-left:20px;padding-bottom:10px;"><select id="selectTyls" onChange="selectTpl(this)">
    $select<option value='-2' style='color:red;'>新建一个</option>
  </select> [<a href="#LOOK" onclick="show_MorePic(-1)">上一个</a>] 
      【<span id="upfile_PicNum">1/2</span>】[<a href="#LOOK" onclick="show_MorePic(1)">下一个</a>]  
       


	
</td></tr>
  <tr>
    <td height="30" style="padding-left:20px;"><div id="showpicdiv" class="showpicdiv"  ><A style="border:2px solid #fff;display:block;" HREF="javascript::" id="showPicID" target="_blank"><img border="0" onerror="this.src=replace_img(this.src);" onload="if(this.height>200)this.height='200'" id="upfile_PicUrl"></A></div></td>

    

  </tr>
</table>

	
<SCRIPT LANGUAGE="JavaScript">
var ImgLinks= new Array("$picurldb");
var ImgTitle= new Array("$pictitledb");
function replace_img(url){
	//如果图片不存在,就去官方获取图片,如果还是不存在,就使用默认的无图片.
	reg=/http:\/\/down2\.php168\.com/g
	if(reg.test(url)){
		return "$webdb[www_url]/images/default/nopic.jpg";
	}
	re   = /$myurl/g;
	links = url.replace(re, "http://down2.php168.com");
	return links;
}
</SCRIPT>
EOT;
	return $show;
}

//获取栏目标签模板
function get_S_LabelTpl($path='template/default/side_sort'){
	global $webdb,$rsdb;
	$pictitledb[]=$f1="默认模板";
	$picurldb[]=$f2="$webdb[www_url]/$path/0.jpg";
	
	$select="<option value='$f2'>$f1</option>";
	$dir=opendir(PHP168_PATH.$path);
	while($file=readdir($dir)){
		if(eregi("\.htm$",$file)&&$file!='0.htm'){
			$pictitledb[]=str_replace(".htm","",$file);
			$picurldb[]=$f2="$webdb[www_url]/$path/".str_replace(".htm",".jpg",$file);
			$select.="<option value='$f2'>".str_replace(".htm","",$file)."</option>";
		}
	}

	$picurldb=implode('","',$picurldb);
	$pictitledb=implode('","',$pictitledb);
	$myurl=str_replace(array(".","/"),array("\.","\/"),$webdb[www_url]);
$show=<<<EOT
<table  border="0" cellspacing="0" cellpadding="0">
<tr><td style="padding-left:20px;padding-bottom:10px;"><select id="select_S_Tyls" onChange="select_S_Tpl(this)">
    $select<option value='-2' style='color:red;'>新建一个</option>
  </select> [<a href="#LOOK" onclick="show_S_MorePic(-1)">上一个</a>] 
      【<span id="upfile__S_PicNum">1/2</span>】[<a href="#LOOK" onclick="show_S_MorePic(1)">下一个</a>]  
       


	
</td></tr>
  <tr>
    <td height="30" style="padding-left:20px;"><div id="show_S_picdiv" class="showpicdiv"  ><A style="border:2px solid #fff;display:block;" HREF="javascript::" id="show_S_PicID" target="_blank"><img border="0" onerror="this.src=replace_S_img(this.src);" onload="if(this.height>200)this.height='200'" id="upfile_s_PicUrl"></A></div></td>

    

  </tr>
</table>

	
<SCRIPT LANGUAGE="JavaScript">
var Img_S_Links= new Array("$picurldb");
var Img_S_Title= new Array("$pictitledb");
function replace_S_img(url){
	//如果图片不存在,就去官方获取图片,如果还是不存在,就使用默认的无图片.
	reg=/http:\/\/down2\.php168\.com/g
	if(reg.test(url)){
		return "$webdb[www_url]/images/default/nopic.jpg";
	}
	re   = /$myurl/g;
	links = url.replace(re, "http://down2.php168.com");
	return links;
}
</SCRIPT>
EOT;
	return $show;
}

?>