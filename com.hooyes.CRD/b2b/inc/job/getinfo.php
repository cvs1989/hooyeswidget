<?php
!function_exists('html') && exit('ERR');
@extract($db->get_one("SELECT COUNT(*) AS articleNUM FROM {$pre}article"));
@extract($db->get_one("SELECT COUNT(*) AS commentNUM FROM {$pre}comment"));
@extract($db->get_one("SELECT COUNT(*) AS guestbookNUM FROM {$pre}guestbook"));
@extract($db->get_one("SELECT COUNT(*) AS memberdataNUM FROM {$pre}memberdata"));
$show="<div>��վ����: <font color=red>{$articleNUM}</font> ƪ</div>
	<div>��������: <font color=red>{$commentNUM}</font> ��</div>
	<div>�ÿ�����: <font color=red>{$guestbookNUM}</font> ��</div>
	<div>ע���Ա: <font color=red>{$memberdataNUM}</font> ��</div>";
$show=str_replace(array("\n","\r","'"),array("","","\'"),$show);
if($webdb[cookieDomain]){
	echo "<SCRIPT LANGUAGE=\"JavaScript\">document.domain = \"$webdb[cookieDomain]\";</SCRIPT>";
}
echo "<SCRIPT LANGUAGE=\"JavaScript\">
parent.document.getElementById('$iframeID').innerHTML='$show';
</SCRIPT>";
?>