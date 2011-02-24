<?php
if(!function_exists('html')){
	die('F');
}

list($position_x,$position_y)=explode(',',$position);
$title = filtrate($title);
$position_x = filtrate($position_x);
$position_y = filtrate($position_y);

if(WEB_LANG=='gb2312'){
	header('Content-Type: text/html; charset=utf-8');
	require_once(ROOT_PATH."inc/class.chinese.php");
	$cnvert = new Chinese("GB2312","UTF8",$title,ROOT_PATH."./inc/gbkcode/");
	$title = $cnvert->ConvertIT();
}

if(!$webdb[gg_map_api]){
	die('<a href="http://code.google.com/intl/zh-CN/apis/maps/signup.html" target="_blank">请先申请一个谷歌地图API密钥</a>');
}


$title || $title='目标地';

print<<<EOT
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"> 
<html xmlns="http://www.w3.org/1999/xhtml"> 
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8"/>
<title>Google 地图</title>
<script src="http://ditu.google.cn/maps?file=api&amp;v=2&amp;key=$webdb[gg_map_api]&hl=zh-CN" type="text/javascript"></script>
<style type="text/css">
html,body{height:100%; margin:0px;} 
#GG_map{width:100%; height:100%;} 
body{ font-size:13px; line-height:22px;}
</style>
</head>
<body topmargin="0" leftmargin="0">
 
<div id="GG_map"></div>
 
<script type="text/javascript">
var weburl='$webdb[www_url]';
</script>
<script type="text/javascript" src="$webdb[www_url]/images/default/googlemap.js"></script>

<script type="text/javascript">
GoogleMap.mapMsg =[[$position_x,$position_y], ["$title","",""]];
GoogleMap.showLocation(GoogleMap.mapMsg[0]);
</script>
</body>
</html>



EOT;
?>