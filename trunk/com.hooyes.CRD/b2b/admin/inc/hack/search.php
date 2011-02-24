<?php
!function_exists('html') && exit('ERR');
if($job=='list'&&$Apower[hack_list])
{
	$rsdb=$db->get_one("SELECT * FROM {$pre}hack WHERE keywords='$hack' ");
	@extract(unserialize($rsdb[config]));
	$searchcode=stripslashes($searchcode);
	$systemTypeDB[$systemType]=' checked ';
	require("head.php");
	require("template/hack/search/list.htm");
	require("foot.php");
}
elseif($action=='list'&&$Apower[hack_list])
{
	$db->query("UPDATE {$pre}hack SET config='".AddSlashes(serialize($postdb))."' WHERE keywords='$hack'");
	$show="<?php
			\$searchcode=\"$postdb[searchcode]\";";
	write_file(PHP168_PATH."cache/hack/search.php",$show);
	jump("ÉèÖÃ³É¹¦","index.php?lfj=hack&hack=$hack&job=getcode",0);
}
elseif($job=='getcode'&&$Apower[hack_list])
{
	require("head.php");
	require("template/hack/search/getcode.htm");
	require("foot.php");
}
elseif($job=="choose"&&$Apower[hack_list])
{
	$msg=read_file("template/hack/search/$type.htm");
	$msg=AddSlashes($msg);
	$msg=str_replace("\r\n",'\r\n',$msg);

	echo "<SCRIPT LANGUAGE=\"JavaScript\">
	<!--
	window.parent.showcode('$msg');
	//-->
	</SCRIPT>";
}
?>