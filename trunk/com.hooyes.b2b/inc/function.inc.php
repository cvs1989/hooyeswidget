<?php
/**
*读文件函数
**/
function read_file($filename,$method="rb"){
	if($handle=@fopen($filename,$method)){
		@flock($handle,LOCK_SH);
		$filedata=@fread($handle,@filesize($filename));
		@fclose($handle);
	}
	return $filedata;
}

/**
*写文件函数
**/
function write_file($filename,$data,$method="rb+",$iflock=1){
	@touch($filename);
	$handle=@fopen($filename,$method);
	if($iflock){
		@flock($handle,LOCK_EX);
	}
	@fputs($handle,$data);
	if($method=="rb+") @ftruncate($handle,strlen($data));
	@fclose($handle);
	@chmod($filename,0777);	
	if( is_writable($filename) ){
		return 1;
	}else{
		return 0;
	}
}

/**
*图像处理函数
**/
function gdpic($srcFile,$dstFile,$width,$height,$type=''){
	require_once(ROOT_PATH."inc/waterimage.php");
	if(is_array($type)){
		//截取一部分,以满足匹配尺寸
		cutimg($srcFile,$dstFile,$x=$type[x]?$type[x]:0,$y=$type[y]?$type[y]:0,$width,$height,$x2=$type[x2]?$type[x2]:0,$y2=$type[y2]?$type[y2]:0,$scale=$type[s]?$type[s]:100,$fix=$type[fix]?$type[fix]:'');
	}elseif($type==1){
		//成比例的缩放
		ResizeImage($srcFile,$dstFile,$width,$height);
	}else{
		//与尺寸不匹配时.用色彩填充
		gdfillcolor($srcFile,$dstFile,$width,$height);
	}
}

/**
*删除文件,值不为空，则返回不能删除的文件名
**/
function del_file($path){
	if (file_exists($path)){
		if(is_file($path)){
			if(	!@unlink($path)	){
				$show.="$path,";
			}
		} else{
			$handle = opendir($path);
			while (($file = readdir($handle))!='') {
				if (($file!=".") && ($file!="..") && ($file!="")){
					if (is_dir("$path/$file")){
						$show.=del_file("$path/$file");
					} else{
						if( !@unlink("$path/$file") ){
							$show.="$path/$file,";
						}
					}
				}
			}
			closedir($handle);
			if(!@rmdir($path)){
				$show.="$path,";
			}
		}
	}
	return $show;
}

function Tblank($string,$msg="内容不能全为空格"){
	$string=str_replace("&nbsp;","",$string);
	$string=str_replace(" ","",$string);
	$string=str_replace("　","",$string);
	$string=str_replace("\r","",$string);
	$string=str_replace("\n","",$string);
	$string=str_replace("\t","",$string);
	if(!$string){
		showerr($msg);
	}
}

/**
*数据表字段信息处理函数
**/
function table_field($table,$field=''){
	global $db;
	$query=$db->query(" SELECT * FROM $table limit 1");
	$num=mysql_num_fields($query);
	for($i=0;$i<$num;$i++){
		$f_db=mysql_fetch_field($query,$i);
		$showdb[]=$f_db->name;
	}
	if($field){
		if(in_array($field,$showdb) ){
			return 1;
		}else{
			return 0;
		}
	}else{
		return $showdb;
	}
}
/**
*判断数据表是否存在
**/
function is_table($table){
	global $db;
	$query=$db->query("SHOW TABLE STATUS");
	while( $array=$db->fetch_array($query) ){
		if($table==$array[Name]){
			return 1;
		}
	}
}

/**
*上传文件
**/
function upfile($upfile,$array){
	global $db,$lfjuid,$pre,$webdb,$groupdb,$lfjdb,$timestamp;
	$FY=strtolower(strrchr(basename($upfile),"."));if($FY&&$FY!='.tmp'){die("<SCRIPT>alert('上传文件有误');</SCRIPT>");}
	$filename=$array[name];

	$path=makepath(ROOT_PATH.$array[path]);

	if($path=='false')
	{
		showerr("不能创建目录$array[path]，上传失败",1);
	}
	elseif(!is_writable($path))
	{
		showerr("目录不可写".$path,1);
	}

	$size=abs($array[size]);

	$filetype=strtolower(strrchr($filename,"."));

	if(!$upfile)
	{
		showerr("文件不存在，上传失败",1);
	}
	elseif(!$filetype)
	{
		showerr("文件不存在，或文件无后缀名,上传失败",1);
	}
	else
	{
		if($filetype=='.php'||$filetype=='.asp'||$filetype=='.aspx'||$filetype=='.jsp'||$filetype=='.cgi'){
			showerr("系统不允许上传可执行文件,上传失败",1);
		}

		if( $groupdb[upfileType] && !in_array($filetype,explode(" ",$groupdb[upfileType])) )
		{
			showerr("你所上传的文件格式为:$filetype,而你所在用户组仅允许上传的文件格式为:$groupdb[upfileType]",1);
		}
		elseif( !in_array($filetype,explode(" ",$webdb[upfileType])) )
		{
			showerr("你所上传的文件格式为:$filetype,而系统仅允许上传的文件格式为:$webdb[upfileType]",1);
		}

		if( $groupdb[upfileMaxSize] && ($groupdb[upfileMaxSize]*1024)<$size )
		{
			showerr("你所上传的文件大小为:".($size/1024)."K,而你所在用户组仅允许上传的文件大小为:{$groupdb[upfileMaxSize]}K",1);
		}
		if( !$groupdb[upfileMaxSize] && $webdb[upfileMaxSize] && ($webdb[upfileMaxSize]*1024)<$size )
		{
			showerr("你所上传的文件大小为:".($size/1024)."K,而系统仅允许上传的文件大小为:{$webdb[upfileMaxSize]}K",1);
		}
	}
	$oldname=preg_replace("/(.*)\.([^.]*)/is","\\1",$filename);
	//if(eregi("(.jpg|.png|.gif)$",$filetype)){
	//	$tempname="{$lfjuid}_".date("YmdHms_",time()).rands(5).$filetype;
	//}else{
		$tempname="{$lfjuid}_".date("YmdHms_",time()).base64_encode(urlencode($oldname)).$filetype;
		//$tempname=str_replace('+','%2B',$tempname);
	//}
	if(strlen($tempname)>250||strstr($tempname,'+')){
		$tempname="{$lfjuid}_".date("YmdHms_",time()).rands(5).$filetype;
	}
	$newfile="$path/$tempname";

	if(@move_uploaded_file($upfile,$newfile))
	{
		@chmod($newfile, 0777);
		$ck=2;
	}
    if(!$ck)
	{
		if(@copy($upfile,$newfile))
		{
			@chmod($newfile, 0777);
			$ck=2;
		}
	}
	if($ck)
	{	

		if(($array[size]+$lfjdb[usespace])>($webdb[totalSpace]*1048576+$groupdb[totalspace]*1048576+$lfjdb[totalspace])){
			//有的用户组不限制空间大小,$array[updateTable]
			if(!$groupdb[AllowUploadMax]){
				unlink($newfile);
				showerr("你的空间不足,上传失败,你可以联系管理员帮你增大空间!",1);
			}
		}
		$db->query("UPDATE {$pre}memberdata SET usespace=usespace+'$size' WHERE uid='$lfjuid' ");

		//对附件做处理,删除冗余的附件.对附件做个记录
		$url=str_replace("$webdb[updir]/","",$array[path]);
		$db->query("INSERT INTO `{$pre}upfile` ( `uid` , `posttime` , `url` , `filename` , `num`, `if_tmp` ) VALUES ('$lfjuid','$timestamp','$url','tmp-$tempname','1','1')");
		setcookie("IF_upfile",$timestamp);

		return $tempname;
	}
	else
	{
		showerr("请检查空间问题,上传失败",1);
	}
}

/**
*生成目录
**/
function makepath($path){
	//这个\没考虑
	$path=str_replace("\\","/",$path);
	$ROOT_PATH=str_replace("\\","/",ROOT_PATH);
	$detail=explode("/",$path);
	foreach($detail AS $key=>$value){
		if($value==''&&$key!=0){
			//continue;
		}
		$newpath.="$value/";
		if((eregi("^\/",$newpath)||eregi(":",$newpath))&&!strstr($newpath,$ROOT_PATH)){continue;}
		if( !is_dir($newpath) ){
			if(substr($newpath,-1)=='\\'||substr($newpath,-1)=='/')
			{
				$_newpath=substr($newpath,0,-1);
			}
			else
			{
				$_newpath=$newpath;
			}
			if(!is_dir($_newpath)&&!mkdir($_newpath)&&ereg("^\/",ROOT_PATH)){
				return 'false';
			}
			@chmod($newpath,0777);
		}
	}
	return $path;
}

/**
*取得真实目录
**/
function tempdir($file,$type=''){
	global $webdb;
	if($type=='pwbbs'){
		global $db_attachname;
		if(is_file(ROOT_PATH."$webdb[passport_path]/$db_attachname/thumb/$file")){
			$file="$webdb[passport_url]/$db_attachname/thumb/$file";
		}else{
			$file="$webdb[passport_url]/$db_attachname/$file";
		}
		return $file;
	}elseif($type=='dzbbs'){
		global $_DCACHE;
		$file="$webdb[passport_url]/{$_DCACHE[settings][attachurl]}/$file";
		return $file;
	}elseif( ereg("://",$file)||ereg("^/./",$file) ){
		return $file;
	}elseif($webdb[mirror]&&!file_exists(ROOT_PATH."$webdb[updir]/$file")){	//FTP镜像点
		return $webdb[mirror]."/".$file;
	}else{
		return $webdb[www_url]."/".$webdb[updir]."/".$file;
	}
}

/**
*截取字符
**/
function get_word($content,$length,$more=1) {
	if(WEB_LANG=='utf-8'){
		$content = get_utf8_word($content, $length,$more);
		return $content;
	}

	if(WEB_LANG=='big5'){
		$more=1;	//不这样的话.截取字符容易使用页面乱码
	}

	if(!$more){
		$length=$length+2;
	}
	if($length>10){
		$length=$length-2;
	}
	if($length && strlen($content)>$length){
		$num=0;
		for($i=0;$i<$length-1;$i++) {
			if(ord($content[$i])>127){
				$num++;
			}
		}
		$num%2==1 ? $content=substr($content,0,$length-2):$content=substr($content,0,$length-1);
		$more && $content.='..';
	}
	return $content;
}

/**
*UTF8截取字符
**/
function get_utf8_word($string, $length = 80,$more=1 , $etc = '..')
{
	$strcut = '';
	$strLength = 0;
	$width  = 0;
	if(strlen($string) > $length) {
		//将$length换算成实际UTF8格式编码下字符串的长度
		for($i = 0; $i < $length; $i++) {
			if ( $strLength >= strlen($string) ){
				break;
			}
			if ( $width>=$length){
				break;
			}
			//当检测到一个中文字符时
			if( ord($string[$strLength]) > 127 ){
				$strLength += 3;
				$width     += 2;              //大概按一个汉字宽度相当于两个英文字符的宽度
			}else{
				$strLength += 1;
				$width     += 1;
			}
		}
		return substr($string, 0, $strLength).$etc;
	} else {
		return $string;
	}
}


/**
*过滤安全字符
**/
function filtrate($msg){
	//$msg = str_replace('&','&amp;',$msg);
	//$msg = str_replace(' ','&nbsp;',$msg);
	$msg = str_replace('"','&quot;',$msg);
	$msg = str_replace("'",'&#39;',$msg);
	$msg = str_replace("<","&lt;",$msg);
	$msg = str_replace(">","&gt;",$msg);
	$msg = str_replace("\t","   &nbsp;  &nbsp;",$msg);
	//$msg = str_replace("\r","",$msg);
	$msg = str_replace("   "," &nbsp; ",$msg);
	return $msg;
}

/*过滤不健康的字*/
function replace_bad_word($str){
	global $Limitword;
	@include_once(ROOT_PATH."data/limitword.php");
	foreach( $Limitword AS $old=>$new){
		strlen($old)>2 && $str=str_replace($old,trim($new),$str);
	}
	return $str;
}


/**
*取固定图片大小
**/
function pic_size($pic,$w,$h,$url){
	global $updir,$webdb,$N_path;
	$rand=rands(5);
	$show="<script>
			function resizeimage_$rand(obj) {
				var imageObject;
				var MaxW = $w;
				var MaxH = $h;
				imageObject = obj;
				var oldImage = new Image();
				oldImage.src = imageObject.src;
				var dW = oldImage.width;
				originalw=dW;
				var dH = oldImage.height;
				originalh=dH;
				if (dW>MaxW || dH>MaxH) {
					a = dW/MaxW;
					b = dH/MaxH;
					if (b>a) {
						a = b;
					}
					dW = dW/a;
					dH = dH/a;
				}
				if (dW>0 && dH>0) {
					imageObject.width = dW;
					imageObject.height = dH;
				}
			}
			</script>";
	return "$show<a href='$url' target='_blank'><img onload='resizeimage_$rand(this)' src='$pic' border=0 width='$w' height='$h'></a>";
}

/**
*模板相关函数
**/
function html($html,$tpl=''){
	global $STYLE;
	if($tpl&&strstr($tpl,ROOT_PATH)&&file_exists($tpl))
	{
		return $tpl;
	}
	elseif($tpl&&file_exists(ROOT_PATH.$tpl))
	{
		return ROOT_PATH.$tpl;
	}
	elseif(file_exists(ROOT_PATH."template/".$STYLE."/".$html.".htm"))
	{
		return ROOT_PATH."template/".$STYLE."/".$html.".htm";
	}
	elseif(file_exists(ROOT_PATH."template/default/".$html.".htm"))
	{
		return ROOT_PATH."template/default/".$html.".htm";
	}
}

/**
*分页
**/
function getpage($table,$choose,$url,$rows=20,$total=''){
	global $page,$db;
	if(!$page){
		$page=1;
	}
	//当存在$total的时候.就不用再读数据库
	if(!$total && $table){
		$query=$db->get_one("SELECT COUNT(*) AS num  FROM $table $choose");
		$total=$query['num'];
	}
	$totalpage=@ceil($total/$rows);
	$nextpage=$page+1;
	$uppage=$page-1;
	if($nextpage>$totalpage){
		$nextpage=$totalpage;
	}
	if($uppage<1){
		$uppage=1;
	}
	$s=$page-3;
	if($s<1){
		$s=1;
	}
	$b=$s;
	for($ii=0;$ii<6;$ii++){
		$b++;
	}
	if($b>$totalpage){
		$b=$totalpage;
	}
	for($j=$s;$j<=$b;$j++){
		if($j==$page){
			$show.=" <a href='#'><font color=red>$j</font></a>";
		}else{
			$show.=" <a href=\"$url&page=$j\" title=\"第{$j}页\">$j</a>";
		}
	}
	$showpage="<a href=\"$url&page=1\" title=\"首页\">首页</A> <a href=\"$url&page=$uppage\" title=\"上一页\">上一页</A>  {$show}  <a href=\"$url&page=$nextpage\" title=\"下一页\">下一页</A> <a href=\"$url&page=$totalpage\" title=\"尾页\">尾页</A> <a href='#'><font color=red>$page</font>/$totalpage/$total</a>";
    if($totalpage>1){
		return $showpage;
	}
}

/**
*页面跳转函数
**/
function refreshto($url,$msg,$time=1){
	global $webdb;
	if($time==0){
		header("location:$url");
	}else{
		require(ROOT_PATH."template/default/refreshto.htm");
		$content=ob_get_contents();
		ob_end_clean();
		ob_start();
		if($webdb[www_url]=='/.'){
			$content=str_replace('/./','/',$content);
		}
		echo $content;
	}
	exit;
}


/**
*警告页面函数
**/
function showerr($showerrMsg,$type=''){
	require_once(ROOT_PATH."data/level.php");
	if($type==1){
		$showerrMsg=str_replace("'","\'",$showerrMsg);
		echo "<SCRIPT LANGUAGE=\"JavaScript\">
		<!--
		alert('$showerrMsg');
		if(document.referrer==''&&window.self==window.top){
			window.self.close();
		}else{
			history.back(-1);
		}		
		//-->
		</SCRIPT>";
	}else{
		extract($GLOBALS);
		require(ROOT_PATH."template/default/showerr.htm");
		$content=ob_get_contents();
		ob_end_clean();
		ob_start();
		if($webdb[www_url]=='/.'){
			$content=str_replace('/./','/',$content);
		}
		echo $content;
	}
	exit;
}

 
/**
*取得随机字符
**/
function rands($length,$strtolower=1) {
	$hash = '';
	$chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz';
	$max = strlen($chars) - 1;
	mt_srand((double)microtime() * 1000000);
	for($i = 0; $i < $length; $i++) {
		$hash .= $chars[mt_rand(0, $max)];
	}
	if($strtolower==1){
		$hash=strtolower($hash);
	}
	return $hash;
}

/**
*简体中文转UTF8编码
**/
function gbk2utf8($text) {
	$fp = fopen(ROOT_PATH."inc/gbkcode/gbk2utf8.table","r");
	while(! feof($fp)) {
		list($gb,$utf8) = fgetcsv($fp,10);
		$charset[$gb] = $utf8;
	}
	fclose($fp);		//以上读取对照表到数组备用wl__hd_sg2_02.gif

	//提取文本中的成分，汉字为一个元素，连续的非汉字为一个元素
	preg_match_all("/(?:[\x80-\xff].)|[\x01-\x7f]+/",$text,$tmp);
	$tmp = $tmp[0];
	//分离出汉字
	$ar = array_intersect($tmp, array_keys($charset));
	//替换汉字编码
	foreach($ar as $k=>$v)
    $tmp[$k] = $charset[$v];
	//返回换码后的串
	return join('',$tmp);
}


/**
*加密与解密函数
**/
function mymd5($string,$action="EN",$rand=''){ //字符串加密和解密 
	global $webdb;
    $secret_string = $webdb[mymd5].$rand.'5*j,.^&;?.%#@!'; //绝密字符串,可以任意设定 
	if(!is_string($string)){
		$string=strval($string);
	}
    if($string==="") return ""; 
    if($action=="EN") $md5code=substr(md5($string),8,10); 
    else{ 
        $md5code=substr($string,-10); 
        $string=substr($string,0,strlen($string)-10); 
    }
    //$key = md5($md5code.$_SERVER["HTTP_USER_AGENT"].$secret_string);
	$key = md5($md5code.$secret_string); 
    $string = ($action=="EN"?$string:base64_decode($string)); 
    $len = strlen($key); 
    $code = "";
    for($i=0; $i<strlen($string); $i++){ 
        $k = $i%$len; 
        $code .= $string[$i]^$key[$k]; 
    }
    $code = ($action == "DE" ? (substr(md5($code),8,10)==$md5code?$code:NULL) : base64_encode($code)."$md5code");
    return $code; 
}

function pwd_md5($code){
	return md5($code);
}


function set_cookie($name,$value,$cktime=0){
	global $webdb,$timestamp;
	if($cktime!=0){
		$cktime=$timestamp+$cktime;
	}
	if($value==''){
		$cktime=$timestamp-31536000;
	}
	$S = $_SERVER['SERVER_PORT'] == '443' ? 1:0;
	if($webdb[cookiePath]){
		$path=$webdb[cookiePath];
	}else{
		$path="/";
	}
	$domain=$webdb[cookieDomain];
	setCookie("$webdb[cookiePre]$name",$value,$cktime,$path,$domain,$S);
}

function get_cookie($name){
	global $webdb;
    return $_COOKIE["$webdb[cookiePre]$name"];
}

function add_user($uid,$money,$about=''){
	global $db,$pre,$timestamp,$webdb,$pre;
	$money = intval($money);
	if(!$money||!$uid){
		return ;
	}
	//$db->query(" UPDATE {$pre}memberdata SET money=money+'$webdb[postArticleMoney]' WHERE uid='$uid' ");
	plus_money($uid,$money,$moneytype='');

	$about = addslashes($about);
	$webdb[moneylog_num] = 100;		//只保存每个用户的最近100条记录
	@extract($db->get_one("SELECT COUNT(*) AS NUM FROM `{$pre}moneylog` WHERE uid='$uid'"));
	if($NUM>$webdb[moneylog_num]){
		$num=$NUM-$webdb[moneylog_num]+1;
		$db->query("DELETE FROM `{$pre}moneylog` WHERE uid='$uid' ORDER BY id ASC LIMIT $num");
	}
	//添加积分变化日志
	$db->query("INSERT INTO `{$pre}moneylog` ( `uid` , `money` , `about` , `posttime` ) VALUES ('$uid', '$money', '$about', '$timestamp')");
}

//增减用户积分
function plus_money($uid,$money,$moneytype=''){
	global $db,$pre,$_pre,$webdb,$TB_pre,$lfjdb;

	if($moneytype=='')
	{
		$moneytype='money';
	}

	if( $webdb[UseMoneyType]=='bbs' )
	{
		if( eregi("^pwbbs",$webdb[passport_type]) )
		{
			$db->query("UPDATE {$TB_pre}memberdata SET $moneytype=$moneytype+'$money' WHERE uid='$uid'");
		}
		elseif( eregi("^dzbbs",$webdb[passport_type]) )
		{
			$db->query("UPDATE {$TB_pre}members SET extcredits2=extcredits2+'$money' WHERE uid='$uid'");
		}
	}
	else
	{
		$db->query("UPDATE {$pre}memberdata SET money=money+'$money' WHERE uid='$uid'");
	}
}

//sock方式打开远程文件
function sockOpenUrl($url,$method='GET',$postValue=''){
	$method = strtoupper($method);
	if(!$url){
		return '';
	}elseif(!ereg("://",$url)){
		$url="http://$url";
	}
	$urldb=parse_url($url);
	$port=$urldb[port]?$urldb[port]:80;
	$host=$urldb[host];
	$query='?'.$urldb[query];
	$path=$urldb[path]?$urldb[path]:'/';
	$method=$method=='GET'?"GET":'POST';

	$fp = fsockopen($host, 80, $errno, $errstr, 30);
	if(!$fp)
	{
		echo "$errstr ($errno)<br />\n";
	}
	else
	{
		$out = "$method $path$query HTTP/1.1\r\n";
		$out .= "Host: $host\r\n";
		$out .= "Cookie: c=1;c2=2\r\n";
		$out .= "Referer: $url\r\n";
		$out .= "Accept: */*\r\n";
		$out .= "Connection: Close\r\n";
		if ( $method == "POST" ) {
			$out .= "Content-Type: application/x-www-form-urlencoded\r\n";
			$length = strlen($postValue);
			$out .= "Content-Length: $length\r\n";
			$out .= "\r\n";
			$out .= $postValue;
		}else{
			$out .= "\r\n";
		}
		fwrite($fp, $out);
		while (!feof($fp)) {
			$file.= fgets($fp, 256);
		}
		fclose($fp);
		if(!$file){
			return '';
		}
		$ck=0;
		$string='';
		$detail=explode("\r\n",$file);
		foreach( $detail AS $key=>$value){
			if($value==''){
				$ck++;
				if($ck==1){
					continue;
				}
			}
			if($ck){
				$stringdb[]=$value;
			}
		}
		$string=implode("\r\n",$stringdb);
		//$string=preg_replace("/([\d]+)(.*)0/is","\\2",$string);
		return $string;
	}
}


/*统计附件*/
function get_content_attachment($str){
	global $webdb;
	$rule=str_replace( array(".","/") , array("\.","\/") , $webdb[www_url] );
	preg_match_all("/$rule\/([a-z_\.0-9A-Z]+)\/([a-z_\.\/0-9A-Z=]+)/is",$str,$array);
	$filedb=$array[2];
	if($webdb[mirror]){
		$rule=str_replace( array(".","/") , array("\.","\/") , $webdb[mirror] );
		preg_match_all("/$rule\/([a-z_\.\/0-9A-Z=]+)/is",$str,$array2);
		if( is_array($filedb) ){
			$filedb+=$array2[1];
		}else{
			$filedb=$array2[1];
		}
	}
	return $filedb;
}

/*删除附件*/
function delete_attachment($uid,$str){
	global $webdb,$db,$pre;
	if(!$str||!$uid){
		return ;
	}
	//真实地址还原
	$str=En_TruePath($str,0);

	$filedb=get_content_attachment($str);
	foreach( $filedb AS $key=>$value){
		$name=basename($value);
		$detail=explode("_",$name);
		//获取文件的UID与用户的UID一样时.才删除.不要乱删除
		
		if($detail[0]&&$detail[0]==$uid){
			$turepath=ROOT_PATH.$webdb[updir]."/".$value;
			
			if($rs=$db->get_one("SELECT * FROM {$pre}upfile WHERE filename='$name'")){
				if($rs[num]>1){
					$db->query("UPDATE `{$pre}upfile` SET `num`=`num`-1 WHERE filename='$name'");
					continue;
				}
				$db->query("DELETE FROM `{$pre}upfile` WHERE filename='$name'");
			}
			$size=@filesize($turepath);
			$size && @unlink($turepath);
			//删除FTP上的资源
			if(!$size&&$webdb[ArticleDownloadUseFtp]){
				$value && $size=ftp_delfile($value);
			}
			$db->query(" UPDATE {$pre}memberdata SET usespace=usespace-'$size' WHERE uid='$uid' ");
		}
	}
}

/*移动附件*/
function move_attachment($uid,$str,$newdir,$type=''){
	global $webdb,$db,$pre,$id,$aid,$fid,$timestamp,$webdb,$Fid_db;
	if(!$str||!$uid||!$newdir){
		return $str;
	}
	$_id=$id?$id:$aid;
	//目前仅对文章作处理,新发文章时,设法获取ID
	if(!$webdb[module_id]&&!$_id){
		$erp=$Fid_db[iftable][$fid];
		$rs=$db->get_one("SHOW TABLE STATUS LIKE '{$pre}article$erp'");
		$_id=$rs[Auto_increment];
	}
	$filedb=get_content_attachment($str);
	foreach( $filedb AS $key=>$value){
		$name=basename($value);
		if($rs=$db->get_one("SELECT * FROM {$pre}upfile WHERE filename='$name'")){
			if($_id&&!in_array($_id,explode(",",$rs[ids]))){
				$db->query("UPDATE `{$pre}upfile` SET `num`=`num`+1,ids='$rs[ids],$_id' WHERE filename='$name'");
			}			
			continue;
		}
		$detail=explode("_",$name);
		//获取文件的UID与用户的UID一样时.才删除.不要乱删除
		if($detail[0]&&$detail[0]==$uid){
			$turepath=ROOT_PATH.$webdb[updir]."/".$value;
			if(!is_dir(ROOT_PATH.$webdb[updir]."/".$newdir))
			{
				makepath(ROOT_PATH.$webdb[updir]."/".$newdir);
			}
			//自动缩小太大张的图片
			if($webdb[ArticlePicWidth]&&$webdb[ArticlePicHeight]&&(eregi(".gif$",$turepath)||eregi(".jpg$",$turepath))){
				$img_array=getimagesize($turepath);
				if($img_array[0]>$webdb[ArticlePicWidth]||$img_array[1]>$webdb[ArticlePicHeight]){
					gdpic($turepath,$turepath,$webdb[ArticlePicWidth],$webdb[ArticlePicHeight],1);
				}
			}
			//图片加水印
			if($type!='small'&&$webdb[is_waterimg]&&(eregi(".gif$",$turepath)||eregi(".jpg$",$turepath)))
			{
				include_once(ROOT_PATH."inc/waterimage.php");
				imageWaterMark($turepath,$webdb[waterpos],ROOT_PATH.$webdb[waterimg]);
			}
			if( @rename($turepath,ROOT_PATH.$webdb[updir]."/$newdir/$name") )
			{
				$str=str_replace("$value","$newdir/$name",$str);
				$db->query("INSERT INTO `{$pre}upfile` ( `module_id` , `ids` , `fid` , `uid` , `posttime` , `url` , `filename` , `num` ) VALUES ('$webdb[module_id]','$_id','$fid','$uid','$timestamp','$newdir/$name','$name','1')");
			}
		}
	}
	return $str;
}

//对真实地址做处理
function En_TruePath($content,$type=1,$ifgetplayer=0){
	global $webdb;
	if($type==1)
	{
		//使用了远程附件镜像,要做特别处理,不局限于使用FTP
		if($webdb[mirror]){
			$content=str_replace("$webdb[mirror]","http://www_qibosoft_com/Tmp_updir",$content);
		}
		$content=str_replace("$webdb[www_url]/$webdb[updir]","http://www_qibosoft_com/Tmp_updir",$content);		
		$content=str_replace("$webdb[www_url]","http://www_qibosoft_com",$content);
	}
	else
	{
		//使用了远程附件镜像,要做特别处理,不局限于使用FTP
		if($webdb[mirror]){
			$content=preg_replace("/http:\/\/www_php168_com\/Tmp_updir\/([-_=\/\.A-Za-z0-9]+)/eis","tempdir('\\1')",$content);
			$content=preg_replace("/http:\/\/www_qibosoft_com\/Tmp_updir\/([-_=\/\.A-Za-z0-9]+)/eis","tempdir('\\1')",$content);
		}else{
			$content=str_replace("http://www_php168_com/Tmp_updir","$webdb[www_url]/$webdb[updir]",$content);
			$content=str_replace("http://www_qibosoft_com/Tmp_updir","$webdb[www_url]/$webdb[updir]",$content);
		}		
		$content=str_replace("http://www_php168_com","$webdb[www_url]",$content);
		$content=str_replace("http://www_qibosoft_com","$webdb[www_url]",$content);
		if($ifgetplayer){
			$content=preg_replace("/\[(mp3|flv|wmv|flash|rmvb),([\d]+),([\d]+)\]([^\[]+)\[\/(mp3|flv|wmv|flash|rmvb)\]/eis","player('\\4','\\2','\\3','true','\\1')",$content);
		}
		//自动补全一些不对称的TABLE,TD,DIV标签
		//$content=check_html_format($content);
	}
	return $content;
}

//获取所有子栏目
function Get_SonFid($table,$fid=0){
	global $db;
	$query = $db->query("SELECT fid,sons FROM $table WHERE fup=$fid");
	while($rs = $db->fetch_array($query)){
		if($rs[sons]){
			$array2=Get_SonFid($table,$rs[fid]);
			if($array2){
				foreach( $array2 AS $key=>$value){
					$array[]=$value;
				}
			}
		}
		$array[]=$rs[fid];
	}
	return $array;
}

//静态网页处理
function Explain_HtmlUrl(){
	global $fid,$aid,$id,$page,$WEBURL;
	$detail=explode("fid-",$WEBURL);
	$detail2=explode(".",$detail[1]);
	$rs=explode("-",$detail2[0]);
	$fid=$rs[0];					//LIST页的fid,bencandy页的fid
	$rs[1] && $$rs[1]=$rs[2];		//可能是LIST页的PAGE,也可能是bencandy页的id
	$rs[3] && $$rs[3]=$rs[4];		//bencandy页的page
}


//获取用户积分
function get_money($uid,$moneytype=''){
	global $db,$pre,$_pre,$webdb,$TB_pre,$lfjdb;
	
	if($moneytype=='')
	{
		$moneytype='money';
	}

	if( $webdb[UseMoneyType]=='bbs'&&$webdb[passport_type] )
	{
		if( eregi("^pwbbs",$webdb[passport_type]) )
		{
			$rs=$db->get_one("SELECT * FROM {$TB_pre}memberdata WHERE uid='$uid'");
			return $rs[$moneytype];
		}
		elseif( eregi("^dzbbs",$webdb[passport_type]) )
		{
			$rs=$db->get_one("SELECT * FROM {$TB_pre}members WHERE uid='$uid'");
			return $rs[extcredits2];
		}
	}
	else
	{
		if($lfjdb[uid]==$uid)
		{
			return $lfjdb[money];
		}
		else
		{
			$rs=$db->get_one("SELECT * FROM {$pre}memberdata WHERE uid='$uid'");
			return $rs[money];
		}
	}
}



/*页面显示,强制过滤关键字*/
function kill_badword($content){
	global $webdb,$Limitword;
	if($webdb[kill_badword])
	{
		if(!$content)
		{
			$content=@ob_get_contents();
			$ck++;
		}
		
		@include_once(ROOT_PATH."data/limitword.php");

		foreach( $Limitword AS $key=>$value){
			$content=str_replace($key,$value,$content);
		}
		if($ck)
		{
			ob_end_clean();
			ob_start();
			echo $content;
		}
		else
		{
			return $content;
		}
	}
	else
	{
		return $content;
	}
}

function send_msg($uid,$title,$content,$fromuid=0){
	global $lfjid;
	$fromer = $fromuid?$lfjid:'SYSTEM';
	$array = array(
		'touid' => $uid,
		'fromuid' => $fromuid,
		'title' => $title,
		'content' => $content,
		'fromer' => $fromer,
		);
	pm_msgbox($array);
}

//发站内消息
function pm_msgbox($array){
	global $db,$pre,$timestamp,$webdb,$TB_pre,$TB,$userDB,$db_modes;
	$array[content] = addslashes($array[content]);
	$array[title] = addslashes($array[title]);
	if( ereg("^pwbbs",$webdb[passport_type]) &&!is_array($db_modes) )
	{
		if(strlen($array[title])>130){
			showerr("标题不能大于65个汉字");
		}
		if(is_table("{$TB_pre}msgc")){
			$db->query("INSERT INTO {$TB_pre}msg (`touid`,`fromuid`, `username`, `type`, `ifnew`, `mdate`) VALUES ('$array[touid]','$array[fromuid]', '$array[fromer]', 'rebox', '1', '$timestamp')");
			$mid=$db->insert_id();
			$db->query("INSERT INTO {$TB_pre}msgc (`mid`, `title`, `content`) VALUES ('$mid','$array[title]','$array[content]')");
		}else{
			$db->query("INSERT INTO {$TB_pre}msg (`touid`,`fromuid`, `username`, `type`, `ifnew`, `title`, `mdate`, `content`) VALUES ('$array[touid]','$array[fromuid]', '$array[fromer]', 'rebox', '1', '$array[title]', '$timestamp', '$array[content]')");
		}
		$array=array(
				'uid'=>$array[touid],
				'newpm'=>1
			);
		$userDB->edit_pw_member($array);
	}
	elseif(defined("UC_CONNECT"))
	{
		if(strlen($array[title])>75){
			showerr("标题不能大于32个汉字");
		}
		uc_pm_send('$array[fromuid]','$array[touid]','$array[title]','$array[content]',1,0,1);
	}
	else
	{
		if(strlen($array[title])>130){
			showerr("标题不能大于65个汉字");
		}
		$db->query("INSERT INTO `{$pre}pm` (`touid`,`fromuid`, `username`, `type`, `ifnew`, `title`, `mdate`, `content`) VALUES ('$array[touid]','$array[fromuid]', '$array[fromer]', 'rebox', '1', '$array[title]', '$timestamp', '$array[content]')");
	}
}

//删除文章的函数
function delete_article($aid,$rid,$forcedel=0){
	global $db,$pre,$webdb;
	if(!$aid){
		showerr("id不存在");
	}
	$erp=get_id_table($aid);
	if($rid){
		$rsdb=$db->get_one("SELECT R.*,A.* FROM {$pre}article$erp A LEFT JOIN {$pre}reply$erp R ON A.aid=R.aid WHERE R.rid='$rid'");
	}elseif($aid){
		$rsdb=$db->get_one("SELECT R.*,A.* FROM {$pre}article$erp A LEFT JOIN {$pre}reply$erp R ON A.aid=R.aid WHERE A.aid='$aid' ORDER BY R.rid ASC LIMIT 1");
		if(!$rsdb[rid]){
			$db->query("DELETE FROM {$pre}article$erp WHERE aid='$aid'");
			$db->query("DELETE FROM {$pre}article_db WHERE aid='$aid'");
			$db->query("DELETE FROM {$pre}fu_article WHERE aid='$aid'");
		}
	}
	if(!$rsdb){
		return ;
	}
	if($rsdb[topic]){
		if($forcedel||$webdb[ForceDel]){
			if($rsdb[picurl]){
				delete_attachment( $rsdb[uid],tempdir($rsdb[picurl]) );
				delete_attachment( $rsdb[uid],tempdir("$rsdb[picurl].jpg") );
				delete_attachment( $rsdb[uid],tempdir("$rsdb[picurl].jpg.jpg") );
			}
			$query = $db->query("SELECT * FROM {$pre}reply$erp WHERE aid='$rsdb[aid]'");
			while($rs = $db->fetch_array($query)){
				delete_attachment($rs[uid],$rs[content]);
			}
			if($rsdb[mid]){
				$r2=$db->get_one("SELECT * FROM {$pre}article_content_$rsdb[mid] WHERE aid='$rsdb[aid]'");
				//删除附件
				if($rsdb[mid]==100||$rsdb[mid]==101||$rsdb[mid]==102){	//删除图片,软件,视频
					if($rsdb[mid]==100){
						$string=$r2[photourl];
					}elseif($rsdb[mid]==101){
						$string=$r2[softurl];
					}elseif($rsdb[mid]==102){
						$string=$r2[mvurl];
					}
					$string=str_replace("\r","",$string);
					$detail=explode("\n",$string);
					foreach($detail AS $value){
						$d=explode("@@@",$value);
						delete_attachment($rsdb[uid],tempdir($d[0]));
					}
				}elseif($rsdb[mid]==104){	//删除FLASH
					$d=explode("@@@",$r2[flashurl]);
					delete_attachment($rsdb[uid],tempdir($d[0]));
				}
				$db->query("DELETE FROM {$pre}article_content_$rsdb[mid] WHERE aid='$rsdb[aid]'");
			}
			$db->query("DELETE FROM `{$pre}collection` WHERE aid='$rsdb[aid]' ");
			$db->query("DELETE FROM `{$pre}article$erp` WHERE aid='$rsdb[aid]' ");
			$db->query("DELETE FROM `{$pre}article_db` WHERE aid='$rsdb[aid]' ");
			$db->query("DELETE FROM `{$pre}reply$erp` WHERE aid='$rsdb[aid]' ");
			$db->query("DELETE FROM `{$pre}comment` WHERE aid='$rsdb[aid]' ");
			$db->query("DELETE FROM `{$pre}report` WHERE aid='$rsdb[aid]' ");
			$db->query("DELETE FROM `{$pre}fu_article` WHERE aid='$rsdb[aid]'");
			//财富处理
			Give_article_money($rsdb[uid],'del');
			if($rsdb[levels]){
				Give_article_money($rsdb[uid],'uncom');
			}
			//删除关键字
			keyword_del($rsdb[aid],$rsdb[keywords]);
		}else{
			$db->query("UPDATE {$pre}article$erp SET yz=2 WHERE aid='$rsdb[aid]'");
		}
	}else{
		$db->query("DELETE FROM {$pre}reply$erp WHERE rid='$rsdb[rid]'");
		delete_attachment($rsdb[uid],$rsdb[content]);
		if($rsdb[mid]){
			$db->query("DELETE FROM {$pre}article_content_$rsdb[mid] WHERE rid='$rsdb[rid]'");
		}
		$db->query("UPDATE {$pre}article$erp SET pages=pages-1 WHERE aid='$rsdb[aid]'");
	}
	//删除缓存文件
	delete_cache_file($rsdb[fid],$rsdb[aid]);
}

//主要是给发表文章或修改文章时调用
function get_html_url(){
	global $rsdb,$aid,$fidDB,$webdb,$fid,$page,$showHtml_Type,$Html_Type;
	$id=$aid;
	if($page<1){
		$page=1;
	}
	$postdb[posttime]=$rsdb[posttime];

	if($showHtml_Type[bencandy][$id]){
		$filename_b=$showHtml_Type[bencandy][$id];
	}elseif($fidDB[bencandy_html]){
		$filename_b=$fidDB[bencandy_html];
	}else{
		$filename_b=$webdb[bencandy_filename];
	}
	//对于内容页的首页把$page去除
	if($page==1){
		$filename_b=preg_replace("/(.*)(-{\\\$page}|_{\\\$page})(.*)/is","\\1\\3",$filename_b);
	}
	$dirid=floor($aid/1000);
	//对于内容页的栏目小于1000篇文章时,把DIR分目录去除
	if($dirid==0){
		$filename_b=preg_replace("/(.*)(-{\\\$dirid}|_{\\\$dirid})(.*)/is","\\1\\3",$filename_b);
	}
	if(strstr($filename_b,'$time_')){
		$time_Y=date("Y",$postdb[posttime]);
		$time_y=date("y",$postdb[posttime]);
		$time_m=date("m",$postdb[posttime]);
		$time_d=date("d",$postdb[posttime]);
		$time_W=date("W",$postdb[posttime]);
		$time_H=date("H",$postdb[posttime]);
		$time_i=date("i",$postdb[posttime]);
		$time_s=date("s",$postdb[posttime]);
	}
	if($fidDB[list_html]){
		$filename_l=$fidDB[list_html];
	}else{
		$filename_l=$webdb[list_filename];
	}	
	if($page==1){
		if($webdb[DefaultIndexHtml]==1){
			$filename_l=preg_replace("/(.*)\/([^\/]+)/is","\\1/index.html",$filename_l);
		}else{
			$filename_l=preg_replace("/(.*)\/([^\/]+)/is","\\1/index.htm",$filename_l);
		}
	}
	eval("\$array[_showurl]=\"$filename_b\";");
	eval("\$array[_listurl]=\"$filename_l\";");
	//自定义了栏目域名
	if($Html_Type[domain][$fid]&&$Html_Type[domain_dir][$fid]){
		$rule=str_replace("/","\/",$Html_Type[domain_dir][$fid]);
		$filename_b=preg_replace("/^$rule/is","{$Html_Type[domain][$fid]}/",$filename_b);
		$filename_l=preg_replace("/^$rule/is","{$Html_Type[domain][$fid]}/",$filename_l);
		//特别处理一下些自定义内容页文件名的情况.
		if(!eregi("^http:\/\/",$filename_b)){
			$filename_b="$webdb[www_url]/$filename_b";
		}
	}else{
		$filename_b="$webdb[www_url]/$filename_b";
		$filename_l="$webdb[www_url]/$filename_l";
	}

	eval("\$array[showurl]=\"$filename_b\";");
	eval("\$array[listurl]=\"$filename_l\";");
	return $array;
}

//获取专题页的静态URL地址
function get_SPhtml_url($fidDB,$id='',$posttime=''){
	global $webdb,$showHtml_Type,$Html_Type;
	$page=1;
	$fid=$fidDB[fid];
	$postdb[posttime]=$posttime;
	
	if($showHtml_Type[SPbencandy][$id]){
		$filename_b=$showHtml_Type[SPbencandy][$id];
	}elseif($fidDB[bencandy_html]){
		$filename_b=$fidDB[bencandy_html];
	}else{
		$filename_b=$webdb[SPbencandy_filename];
	}
	if(strstr($filename_b,'$time_')){
		$time_Y=date("Y",$postdb[posttime]);
		$time_y=date("y",$postdb[posttime]);
		$time_m=date("m",$postdb[posttime]);
		$time_d=date("d",$postdb[posttime]);
		$time_W=date("W",$postdb[posttime]);
		$time_H=date("H",$postdb[posttime]);
		$time_i=date("i",$postdb[posttime]);
		$time_s=date("s",$postdb[posttime]);
	}
	if($fidDB[list_html]){
		$filename_l=$fidDB[list_html];
	}else{
		$filename_l=$webdb[SPlist_filename];
	}
	$filename_b="$webdb[www_url]/$filename_b";
	$filename_l="$webdb[www_url]/$filename_l";
	eval("\$array[showurl]=\"$filename_b\";");
	eval("\$array[listurl]=\"$filename_l\";");
	return $array;
}

//一般用在内容页的提示!
function Remind_msg($MSG){
	global $rsdb;
	$rsdb[content].= "<SCRIPT LANGUAGE='JavaScript'>
	<!--
	alert('$MSG');
	//-->
	</SCRIPT>";
}


//生成所安装的模块与系统的缓存
function make_module_cache(){
	global $db,$pre;
	$show="<?php\r\nunset(\$ModuleDB);\r\n";
	$query = $db->query("SELECT * FROM {$pre}module ORDER BY list DESC");
	while($rs = $db->fetch_array($query))
	{
		$rs[name]=addslashes($rs[name]);
		
		$rs[config]=str_replace("'","\'",$rs[config]);
		$rs[name]=str_replace("'","\'",$rs[name]);

		$show.="
			\$ModuleDB['{$rs[pre]}']=array('name'=>'$rs[name]',
				'dirname'=>'$rs[dirname]',
				'domain'=>'$rs[domain]',
				'admindir'=>'$rs[admindir]',
				'type'=>'$rs[type]',
				'config'=>'$rs[config]',
				'adminmember'=>'$rs[adminmember]',
				'id'=>'$rs[id]',
				'ifclose'=>'$rs[ifclose]'
			);
			";
	}
	write_file(ROOT_PATH."data/module.php",$show.'?>');
}

//根据IP获取来源地
function ipfrom($ip) {
	if(!preg_match("/^\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}$/", $ip)) {
		return '';
	}
	if( !is_file(ROOT_PATH.'inc/ip.dat') ){
		return '<a title><A HREF="http://down.qibosoft.com/ip.rar" title="点击下载后,解压放到整站/inc/目录即可">IP库不存在,请点击下载一个!</A></a>';
	}
	if($fd = @fopen(ROOT_PATH.'inc/ip.dat', 'rb')) {

		$ip = explode('.', $ip);
		$ipNum = $ip[0] * 16777216 + $ip[1] * 65536 + $ip[2] * 256 + $ip[3];

		$DataBegin = fread($fd, 4);
		$DataEnd = fread($fd, 4);
		$ipbegin = implode('', unpack('L', $DataBegin));
		if($ipbegin < 0) $ipbegin += pow(2, 32);
		$ipend = implode('', unpack('L', $DataEnd));
		if($ipend < 0) $ipend += pow(2, 32);
		$ipAllNum = ($ipend - $ipbegin) / 7 + 1;

		$BeginNum = 0;
		$EndNum = $ipAllNum;

		while($ip1num > $ipNum || $ip2num < $ipNum) {
			$Middle= intval(($EndNum + $BeginNum) / 2);

			fseek($fd, $ipbegin + 7 * $Middle);
			$ipData1 = fread($fd, 4);
			if(strlen($ipData1) < 4) {
				fclose($fd);
				return '- System Error';
			}
			$ip1num = implode('', unpack('L', $ipData1));
			if($ip1num < 0) $ip1num += pow(2, 32);

			if($ip1num > $ipNum) {
				$EndNum = $Middle;
				continue;
			}

			$DataSeek = fread($fd, 3);
			if(strlen($DataSeek) < 3) {
				fclose($fd);
				return '- System Error';
			}
			$DataSeek = implode('', unpack('L', $DataSeek.chr(0)));
			fseek($fd, $DataSeek);
			$ipData2 = fread($fd, 4);
			if(strlen($ipData2) < 4) {
				fclose($fd);
				return '- System Error';
			}
			$ip2num = implode('', unpack('L', $ipData2));
			if($ip2num < 0) $ip2num += pow(2, 32);

			if($ip2num < $ipNum) {
				if($Middle == $BeginNum) {
					fclose($fd);
					return '- Unknown';
				}
				$BeginNum = $Middle;
			}
		}

		$ipFlag = fread($fd, 1);
		if($ipFlag == chr(1)) {
			$ipSeek = fread($fd, 3);
			if(strlen($ipSeek) < 3) {
				fclose($fd);
				return '- System Error';
			}
			$ipSeek = implode('', unpack('L', $ipSeek.chr(0)));
			fseek($fd, $ipSeek);
			$ipFlag = fread($fd, 1);
		}

		if($ipFlag == chr(2)) {
			$AddrSeek = fread($fd, 3);
			if(strlen($AddrSeek) < 3) {
				fclose($fd);
				return '- System Error';
			}
			$ipFlag = fread($fd, 1);
			if($ipFlag == chr(2)) {
				$AddrSeek2 = fread($fd, 3);
				if(strlen($AddrSeek2) < 3) {
					fclose($fd);
					return '- System Error';
				}
				$AddrSeek2 = implode('', unpack('L', $AddrSeek2.chr(0)));
				fseek($fd, $AddrSeek2);
			} else {
				fseek($fd, -1, SEEK_CUR);
			}

			while(($char = fread($fd, 1)) != chr(0))
				$ipAddr2 .= $char;

			$AddrSeek = implode('', unpack('L', $AddrSeek.chr(0)));
			fseek($fd, $AddrSeek);

			while(($char = fread($fd, 1)) != chr(0))
				$ipAddr1 .= $char;
		} else {
			fseek($fd, -1, SEEK_CUR);
			while(($char = fread($fd, 1)) != chr(0))
				$ipAddr1 .= $char;

			$ipFlag = fread($fd, 1);
			if($ipFlag == chr(2)) {
				$AddrSeek2 = fread($fd, 3);
				if(strlen($AddrSeek2) < 3) {
					fclose($fd);
					return '- System Error';
				}
				$AddrSeek2 = implode('', unpack('L', $AddrSeek2.chr(0)));
				fseek($fd, $AddrSeek2);
			} else {
				fseek($fd, -1, SEEK_CUR);
			}
			while(($char = fread($fd, 1)) != chr(0))
				$ipAddr2 .= $char;
		}
		fclose($fd);

		if(preg_match('/http/i', $ipAddr2)) {
			$ipAddr2 = '';
		}
		$ipaddr = "$ipAddr1 $ipAddr2";
		$ipaddr = preg_replace('/CZ88\.NET/is', '', $ipaddr);
		$ipaddr = preg_replace('/^\s*/is', '', $ipaddr);
		$ipaddr = preg_replace('/\s*$/is', '', $ipaddr);
		if(preg_match('/http/i', $ipaddr) || $ipaddr == '') {
			$ipaddr = '- Unknown';
		}

		if(WEB_LANG=='big5'){
			require_once(ROOT_PATH."inc/class.chinese.php");
			$cnvert = new Chinese("GB2312","BIG5",$ipaddr,ROOT_PATH."./inc/gbkcode/");
			$ipaddr = $cnvert->ConvertIT();
		}elseif(WEB_LANG=='utf-8'){
			require_once(ROOT_PATH."inc/class.chinese.php");
			$cnvert = new Chinese("GB2312","UTF8",$ipaddr,ROOT_PATH."./inc/gbkcode/");
			$ipaddr = $cnvert->ConvertIT();
		}

		return $ipaddr;
	}
}

function ftp_upfile($source,$file,$ifdel=1){
	global $webdb;
	if(!$webdb[FtpHost]||!$webdb[FtpName]||!$webdb[FtpPwd]||!$webdb[FtpPort]||!$webdb[FtpDir]){
		return ;
	}
	require_once(ROOT_PATH."inc/ftp.php");
	$ftp = new FTP($webdb[FtpHost],$webdb[FtpPort],$webdb[FtpName],$webdb[FtpPwd],$webdb[FtpDir]);
	$path=dirname($file);
	$detail=explode("/",$path);
	//$pathname=$webdb[FtpDir];
	foreach( $detail AS $key=>$value){
		$pathname.="$value/";
		if(!$ftp->dir_exists($pathname)){
			$ftp->mkd($pathname);
		}
	}
	$ifput=$ftp->upload($source,$file);
	$ftp->close();
	if($ifput){
		$ifdel && unlink($source);
		return "$webdb[mirror]/$file";
	}else{
		return "$webdb[www_url]/$webdb[updir]/$file";
	}
}

function ftp_delfile($file){
	global $webdb;
	if(!$webdb[FtpHost]||!$webdb[FtpName]||!$webdb[FtpPwd]||!$webdb[FtpPort]||!$webdb[FtpDir]){
		return ;
	}
	require_once(ROOT_PATH."inc/ftp.php");
	$ftp = new FTP($webdb[FtpHost],$webdb[FtpPort],$webdb[FtpName],$webdb[FtpPwd],$webdb[FtpDir]);
	$size = $ftp->size($file,0);
	$ftp->delete($file);
	$ftp->close();
	return $size;
}


//发送手机短信
function sms_send($mob,$content){
	global $webdb;
	if($webdb[fetion_id]&&$webdb[fetion_pwd]&&in_array($mob,explode("\r\n",$webdb[fetion_friend]))){
		$url="http://sms.api.bz/fetion.php?username=$webdb[fetion_id]&password=$webdb[fetion_pwd]&sendto=$mob&message=$content";
		$msg=sockOpenUrl($url);die("$msg-$url");
		return 1;
	}elseif($webdb[sms_type]=='eshang8'){
		$url="http://http.chinasms.com.cn/tx/?uid=$webdb[sms_es_name]&key=".strtolower(md5($webdb[sms_es_key]))."&msg=$content&phone=$mob&smskind=1";
		if( !$msg=sockOpenUrl($url) ){
			//$msg=file_get_contents($url);
		}
		if($msg===''){
			return 0;
		}elseif($msg==='100'){
			return 1;			//发送成功
		}else{
			return $msg;
		}
	}elseif($webdb[sms_type]=='winic'){
		$url="http://service.winic.org/sys_port/gateway/?id=$webdb[sms_wi_id]&pwd=$webdb[sms_wi_pwd]&to=$mob&content=$content&time=";
		if( !$msg=sockOpenUrl($url) ){
			//$msg=file_get_contents($url);
		}
		if($msg===''){
			return 0;
		}
		$detail=explode("/",$msg);
		if($detail[0]==='000'){
			return 1;			//发送成功
		}else{
			return $detail[0];
		}
	}else{
		showerr("系统没有选择短信接口平台!");
	}
}


/**
自定义模型当中,获取这三个select,radio,checkbox表单中类似“
1|中国
2|美国
”真实值
**/
function SRC_true_value($rs,$rsdb_v){
	if($rs[form_type]=='radio'||$rs[form_type]=='select'){
		$detail=explode("\r\n",$rs[form_set]);
		foreach( $detail AS $_key=>$value){
			list($v1,$v2)=explode("|",$value);
			if($v1==$rsdb_v&&$v2){
				$rsdb_v=$v2;
				break;
			}
		}
	}elseif($rs[form_type]=='checkbox'){
		$detail2=explode("/",$rsdb_v);
		$detail=explode("\r\n",$rs[form_set]);
		foreach( $detail AS $_key=>$value){
			list($v1,$v2)=explode("|",$value);
			if(in_array($v1,$detail2)&&$v2){
				foreach( $detail2 AS $key2=>$value2){
					if($value2==$v1){
						$detail2[$key2]=$v2;
						break;
					}
				}
			}
		}
		$rsdb_v=implode(" , ",$detail2);
	}
	return $rsdb_v;
}

//自定义内容页文件名缓存生成
function get_showhtmltype(){
	global $db,$pre,$Fid_db;
	$query = $db->query("SELECT aid,htmlname FROM {$pre}article WHERE htmlname!=''");
	while($rs = $db->fetch_array($query)){
		$show.="\$showHtml_Type[bencandy][{$rs[aid]}]='$rs[htmlname]';\r\n";
	}
	foreach( $Fid_db[iftable] AS $key=>$erp){
		$query = $db->query("SELECT aid,htmlname FROM {$pre}article$erp WHERE htmlname!=''");
		while($rs = $db->fetch_array($query)){
			$show.="\$showHtml_Type[bencandy][{$rs[aid]}]='$rs[htmlname]';\r\n";
		}
	}
	write_file(ROOT_PATH."data/showhtmltype.php","<?php\r\n".$show.'?>');
}

//用户登录
function user_login($username,$password,$cookietime){
	global $userDB;
	$rs = $userDB->login($username,$password,$cookietime);
	return $rs;
}

//获取UNIX时间,主要是特别处理变成整数.不加引号08与8会不一样的结果,加引号是正常的
function mk_time($h,$i, $s, $m, $d, $y){
	$time=@mktime(intval($h),intval($i),intval($s),intval($m),intval($d),intval($y));
	return $time;
}


//检测某个关键字是否包含在数组里边
function ifin_array($array,$filename,$ISin=''){
	foreach($array as $key=>$value){
		if(!is_array($value)){
			if(strstr($value,$filename)){
				$ISin=1;
				break;
			}
		}elseif(!$ISin){
			$ISin=ifin_array($array[$key],$filename,$ISin);
		}
	}
	return $ISin;
}


/*讯雷联盟*/
function Thunder_Encode($url) 
{
	$thunderPrefix="AA";
	$thunderPosix="ZZ";
	$thunderTitle="thunder://";
	$thunderUrl=$thunderTitle.base64_encode($thunderPrefix.$url.$thunderPosix);
	return $thunderUrl;
}


/*快车联盟*/
function Flashget_Encode($t_url,$uid) 
{
	$prefix= "Flashget://";
	$FlashgetURL=$prefix.base64_encode("[FLASHGET]".$t_url."[FLASHGET]")."&".$uid;
	return $FlashgetURL;
}

//播放器
function player($url,$width=400,$height=300,$autostart='false',$force=''){
	global $webdb;
	//$urlstring=mymd5($url);
	$urlstring=urlencode($url);
	$string="
<SCRIPT LANGUAGE='JavaScript' src='$webdb[www_url]/do/job.php?job=playurl&urlstring=$urlstring'></SCRIPT>
<SCRIPT LANGUAGE=\"JavaScript\">
qibo_player(playurl,'$width','$height','$force','$autostart');
</SCRIPT>
";
	return $string;
}


//自动补全一些不对称的TABLE,TD,DIV标签
function check_html_format($string){
	preg_match_all("/<div([^>]*)>/",$string,$array0);
	preg_match_all("/<\/div>/",$string,$array1);
	$num0=count($array0[0]);
	$num1=count($array1[0]);
	$divNUM=abs($num0-$num1);
	for($i=0;$i<$divNUM;$i++){
		if($num0>$num1){
			$string.="</div>";
		}else{
			$string="<div>$string";
		}
		break;
	}
	preg_match_all("/<td([^>]*)>/",$string,$array0);
	preg_match_all("/<\/td>/",$string,$array1);
	$num0=count($array0[0]);
	$num1=count($array1[0]);
	$tdNUM=abs($num0-$num1);
	for($i=0;$i<$tdNUM;$i++){
		if($num0>$num1){
			$string.="</td>";
		}else{
			$string="<td>$string";
		}
		break;
	}
	preg_match_all("/<table([^>]*)>/",$string,$array0);
	preg_match_all("/<\/table>/",$string,$array1);
	$num0=count($array0[0]);
	$num1=count($array1[0]);
	$tableNUM=abs($num0-$num1);
	for($i=0;$i<$tableNUM;$i++){
		if($num0>$num1){
			$string.="</table>";
		}else{
			$string="<table>$string";
		}
		break;
	}
	if($tdNUM>1||$tdNUM>1||$tableNUM>1){
		$string=check_html_format($string);
	}
	return $string;
}

function get_id_table($id){
	global $Fid_db;
	if(strlen($id)<9){
		return ;
	}
	if(!$Fid_db){
		@include(ROOT_PATH."data/all_fid.php");
	}	
	$tableid=preg_replace("/([0-9]{3})([0-9]{6})/is","\\1",$id);
	$tableid=intval($tableid);
	if(in_array($tableid,$Fid_db[iftable])){
		return $tableid;
	}	
}

function get_one_article($id){
	global $db,$pre;
	$erp=get_id_table($id);
	$rs=$db->get_one("SELECT * FROM {$pre}article$erp WHERE aid='$id'");
	return $rs;
}

function delete_cache_file($fid,$id){
	del_file(ROOT_PATH."cache/jsarticle_cache");
	del_file(ROOT_PATH."cache/label_cache");
	del_file(ROOT_PATH."cache/list_cache");
	del_file(ROOT_PATH."cache/bencandy_cache");
	del_file(ROOT_PATH."cache/showsp_cache");
}

//核对验证码
function check_imgnum($yzimg){
	global $db,$pre,$timestamp,$webdb;
	$time=$timestamp-1800;	//半小时前的验证码失效.

	if($webdb[YzImg_letter_differ]){	//区别字母大小写
		$SQL=" BINARY ";
	}
	if($db->get_one("SELECT * FROM {$pre}yzimg WHERE $SQL imgnum='$yzimg'")){
		$db->query("DELETE FROM {$pre}yzimg WHERE imgnum='$yzimg' OR posttime<$time");
		return true;
	}else{
		return false;
	}
}


//各模块,更新核心设置缓存
function module_write_config_cache($webdbs)
{
	global $db,$_pre,$atc_webdbs;
	
	//checkbox要特别处理
	foreach($atc_webdbs AS $key=>$value){
		if(!$webdbs[$key]){
			$webdbs[$key]='';
		}
	}

	if( is_array($webdbs) )
	{
		foreach($webdbs AS $key=>$value)
		{
			if(is_array($value))
			{
				$webdbs[$key]=$value=implode(",",$value);
			}
			$SQL2.="'$key',";
			$SQL.="('$key', '$value', ''),";
		}
		$SQL=$SQL.";";
		$SQL=str_Replace("'),;","')",$SQL);
		$db->query(" DELETE FROM {$_pre}config WHERE c_key IN ($SQL2'') ");
		$db->query(" INSERT INTO `{$_pre}config` VALUES  $SQL ");	
	}
	$writefile="<?php\r\n";
	$query = $db->query("SELECT * FROM {$_pre}config");
	while($rs = $db->fetch_array($query)){
		$rs[c_value]=addslashes($rs[c_value]);
		$writefile.="\$webdb['$rs[c_key]']='$rs[c_value]';\r\n";
	}
	write_file(Mpath."data/config.php",$writefile);
}

//发送邮件
function send_mail($email,$title,$content,$ifcheck=1){
	global $webdb;
	if($webdb[MailType]=='smtp'){	
		if(!$webdb[MailServer]||!$webdb[MailPort]||!$webdb[MailId]||!$webdb[MailPw]){
			if($ifcheck){
				showerr("请先设置邮件服务器");
			}else{
				return false;
			}			
		}
		require_once(ROOT_PATH."inc/class.mail.php");
		$smtp = new smtp($webdb[MailServer],$webdb[MailPort],true,$webdb[MailId],$webdb[MailPw]);
		$smtp->debug = false;
		if($smtp->sendmail($email,$webdb[MailId], $title, $content, "HTML")){
			$succeeNUM++;
		}else{
			$failNUM++;
		}
	}else{
		if(mail($email, $title, $content)){
			$succeeNUM++;
		}else{
			$failNUM++;
		}
	}	
	if($succeeNUM){
		return true;
	}else{
		if($ifcheck){
			showerr('邮件发送失败,请管理员检查服务器配置');
		}else{
			return false;
		}
	}
}


//插件菜单写缓存
function write_hackmenu_cache(){
	global $db,$pre;
	$show="<?php\r\n";
	$query = $db->query("SELECT * FROM {$pre}hack ORDER BY list DESC");
	while($rs = $db->fetch_array($query)){
		if(!$rs[class2]||!$rs[class1]){
			$rs[class1]='other';
			$rs[class2]='插件大全';
		}
		$rs[adminurl]=addslashes($rs[adminurl]);
		$rs[linkname] && $rs[name]=$rs[linkname];
		$rs[name]=addslashes($rs[name]);
		$s="\r\n\$menu_partDB['{$rs[class1]}'][]='{$rs[class2]}';\r\n\$menudb['{$rs[class2]}']['{$rs[name]}']=array('power'=>'{$rs[keywords]}','link'=>'{$rs[adminurl]}');\r\n";
		if($rs[isbiz]){
			$show.="\r\nif(\$IS_BIZPhp168||\$GLOBALS[IS_BIZPhp168]){".$s."}\r\n";
		}else{
			$show.=$s;
		}
	}
	write_file(ROOT_PATH."data/hack.php",$show.'?>');
}


//会员中心模板
function get_member_tpl($type){
	global $webdb;	
	if(!$webdb[style_member]||!is_file($file=ROOT_PATH."member/template/$webdb[style_member]/$type.htm")){
		$file = ROOT_PATH."member/template/default/$type.htm";
	}
	return $file;
}



//全站伪静态
function rewrite_url(&$content){
	$content=preg_replace("/<a([^>]+)href=([\'\"]?)([^\'\"> ]+)([\'\"]?)/eis","rewrite_replace_url('\\3','\\1','\\2','\\4')",$content);
}
function rewrite_replace_url($code3,$code1,$code2,$code4){
	$code3=preg_replace("/(.*)(list|bencandy|listsp|showsp|listall|listhomepage|joinshow)\.php\?(.*)/eis","rewrite_replace_parameter('\\1','\\2','\\3')",$code3);
	return stripslashes("<a{$code1}href={$code2}{$code3}{$code4}");
}
function rewrite_replace_parameter($path,$filename,$parameter){
	if($path&&substr($path,-1)!='/'){
		return "{$path}{$filename}.php?{$parameter}";	//对于这种就不能处理的XXlist.php
	}
	$re='-htm-';
	$filetype='.html';
	$parameter=preg_replace("/^([&]+)(.*)/is","\\2",$parameter);
	$parameter=str_replace(array('&&','&','='),array('&','-','-'),$parameter);
	return "$path$filename$re$parameter$filetype";
}

//删除商铺,没删除会员
function delete_home($uid){
	global $db,$pre;
	$db->query("DELETE FROM {$pre}hy_company WHERE uid='$uid'");
	$db->query("DELETE FROM {$pre}hy_company_fid WHERE uid='$uid'");
	$db->query("DELETE FROM {$pre}hy_friendlink WHERE uid='$uid'");
	$db->query("DELETE FROM {$pre}hy_guestbook WHERE uid='$uid'");
	$db->query("DELETE FROM {$pre}hy_home WHERE uid='$uid'");
	//$db->query("DELETE FROM {$pre}hy_mysort WHERE uid='$uid'");
	$db->query("DELETE FROM {$pre}hy_news WHERE uid='$uid'");
	$db->query("DELETE FROM {$pre}hy_pic WHERE uid='$uid'");
	$db->query("DELETE FROM {$pre}hy_picsort WHERE uid='$uid'");
	$db->query("DELETE FROM {$pre}hy_picsort WHERE uid='$uid'");
	$db->query("UPDATE {$pre}memberdata SET grouptype=0 WHERE uid='$uid'");
}

//给在线编辑器做特殊字符替换使用的
function editor_replace($content){
	$content = str_replace(
		array('"',"'",'&lt;','&gt;','<','>','&nbsp;'),
		array("&quot;",'&#39;','&amp;lt;','&amp;gt;','&lt;','&gt;',"&amp;nbsp;"),
		$content);
	return $content;
}

?>