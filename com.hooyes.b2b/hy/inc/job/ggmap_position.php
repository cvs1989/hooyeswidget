<?php
if(!function_exists('html')){
	die('F');
}

list($position_x,$position_y)=explode(',',$position);

$position_x = filtrate($position_x);
$position_y = filtrate($position_y);

$position_x >0 || $position_x=0;
$position_y >0 || $position_y=0;

$cityname=str_replace("市","",$city_DB[name][$cityid]);

if(WEB_LANG=='gb2312'){
	header('Content-Type: text/html; charset=utf-8');
	require_once(ROOT_PATH."inc/class.chinese.php");
	$cnvert = new Chinese("GB2312","UTF8",$cityname,ROOT_PATH."./inc/gbkcode/");
	$cityname = $cnvert->ConvertIT();
}

if(!$webdb[gg_map_api]){
	die('<a href="http://code.google.com/intl/zh-CN/apis/maps/signup.html" target="_blank">请先申请一个谷歌地图API密钥</a>');
}
//require_once(ROOT_PATH."inc/pinyin.php");
//$cityname=change2pinyin($cityname,0);

$cityname || $cityname='广州市';	//默认为广州

print<<<EOT

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml">
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8"/>
<title>Google 地图</title>
<script src="http://ditu.google.cn/maps?file=api&amp;v=2&amp;key=$webdb[gg_map_api]&hl=zh-CN" type="text/javascript"></script>
 
<style type="text/css">
body{ font-size:13px; line-height:22px;}
#container{position:relative;z-index:1;margin:10px auto;width:960px;overflow:hidden;clear:both;text-align:center;}
</style>
</head>
<body topmargin="0" leftmargin="0">
<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
  <tr>
    <td align="center"><div id="cusMarkTip">请点击下方的图标开始在地图上标注位置</div></td>
  </tr>
  <tr>
    <td align="center"><img src="$webdb[www_url]/images/default/ggmap_add_position.jpg" onclick="GoogleMap.customMarkPoint();" style="cursor:pointer" ></td>
  </tr>
  <tr>
    <td><div id="GG_map" style="width:100%;height:600px;"></div></td>
  </tr>
</table>

<script type="text/javascript">
var weburl='$webdb[www_url]';
</script>
<script type="text/javascript" src="$webdb[www_url]/images/default/googlemap.js"></script>
<script>
	var mapcity="$cityname";
	var chraddress="";
	if(chraddress==""){
		chraddress=mapcity;
	}
	else{
		if(chraddress.indexOf(mapcity)==-1){
			chraddress =mapcity+chraddress;
		}
		else{
			if(chraddress.indexOf(mapcity.replace("市",""))==-1){
				chraddress =mapcity+chraddress;
			}
		}
	}

	var latitude=$position_x;
	var longitude=$position_y;

	if(latitude > 0 && longitude > 0)
    {
        GoogleMap.mapMsg =[[latitude,longitude], [chraddress]]; 	   
    }
    else
    {
        GoogleMap.mapMsg =[chraddress, [chraddress]];
    }

    GoogleMap.showLocation(GoogleMap.mapMsg[0]);
</script>
</body>
</html>




EOT;
?>