<?php
!function_exists('html') && exit('ERR');
@extract($db->get_one("SELECT COUNT(*) AS articleNUM FROM {$pre}article"));
@extract($db->get_one("SELECT COUNT(*) AS commentNUM FROM {$pre}comment"));
@extract($db->get_one("SELECT COUNT(*) AS guestbookNUM FROM {$pre}guestbook"));
@extract($db->get_one("SELECT COUNT(*) AS memberdataNUM FROM {$pre}memberdata"));
$show="<div>网站内容: <font color=red>{$articleNUM}</font> 篇</div>
	<div>内容评论: <font color=red>{$commentNUM}</font> 条</div>
	<div>访客留言: <font color=red>{$guestbookNUM}</font> 条</div>
	<div>注册会员: <font color=red>{$memberdataNUM}</font> 个</div>";
$show=str_replace(array("\n","\r","'"),array("","","\'"),$show);
if($webdb[cookieDomain]){
	echo "<SCRIPT LANGUAGE=\"JavaScript\">document.domain = \"$webdb[cookieDomain]\";</SCRIPT>";
}
echo "<SCRIPT LANGUAGE=\"JavaScript\">
parent.document.getElementById('$iframeID').innerHTML='$show';
</SCRIPT>";
?>