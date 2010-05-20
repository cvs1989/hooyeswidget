<?php
error_reporting(E_ALL ^ E_NOTICE);
$picurl=$_GET['picurl'];
$pic_width=$_GET['pic_width'];
$pic_height=$_GET['pic_height'];
$url=$_GET['url'];
?>
<title>广告预览</title>
<a href="<?=$url?>" target=_blank><img src="<?=$picurl?>" border=0 width=<?=$pic_width?> height=<?=$pic_height?>></a>
