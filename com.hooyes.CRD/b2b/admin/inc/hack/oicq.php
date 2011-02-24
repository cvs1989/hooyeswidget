<?php
!function_exists('html') && exit('ERR');
if($job=="edit"&&$Apower[hack_list]){
	$rs=$db->get_one("SELECT * FROM {$pre}hack WHERE keywords='$hack'");
	@extract(unserialize($rs[config]));
	$qq_styledb[$qq_style]=" checked ";
	require("head.php");
	require("template/hack/oicq/oicq.htm");
	require("foot.php");
}
elseif($action=="edit"&&$Apower[hack_list]){
	$detail=explode("\r\n",$qq_num);
	$show='';
	foreach( $detail AS $key=>$value){
		if(ereg("^([0-9]{5,11})$",$value)){
			$show.="<a target=blank href='tencent://message/?uin=$value&Site=$web_name&Menu=yes'><img border='0' SRC='http://wpa.qq.com/pa?p=1:$value:$qq_style' alt='$pic_alt'></a><br><br>";
		}
	}
	unlink(PHP168_PATH."cache/hack/oicq.php");
	$array=array("qq_style"=>"$qq_style","pic_alt"=>"$pic_alt","qq_num"=>"$qq_num","web_name"=>"$web_name");
	$db->query("UPDATE {$pre}hack SET htmlcode='".AddSlashes($show)."',config='".AddSlashes(serialize($array))."' WHERE keywords='$hack'");
	require("head.php");
	require("template/hack/oicq/oicq_code.htm");
	require("foot.php");	
}
elseif($job=='getcode'&&$Apower[hack_list])
{
	require("head.php");
	require("template/hack/oicq/oicq_code.htm");
	require("foot.php");
}