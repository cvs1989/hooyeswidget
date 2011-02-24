<?php
!function_exists('html') && exit('ERR');
if($job=='list'&&$Apower[hack_list])
{
	$rsdb=$db->get_one("SELECT * FROM {$pre}hack WHERE keywords='$hack' ");
	@extract(unserialize($rsdb[config]));
	$guestcode=stripslashes($guestcode);
	$membercode=stripslashes($membercode);
	if($webdb[passport_type])
	{
		$systemType='php168';
	}
	if($webdb[passport_type]&&$webdb[passport_TogetherLogin]==2&&$systemType=='php168')
	{
		$systemType='';
	}
	$systemTypeDB[$systemType]=' checked ';
	require("head.php");
	require("template/hack/login/list.htm");
	require("foot.php");
}
elseif($job=='show'&&$Apower[hack_list])
{
	require("head.php");
	require("template/hack/login/getcode.htm");
	require("foot.php");
}
?>