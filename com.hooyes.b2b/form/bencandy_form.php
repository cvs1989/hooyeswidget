<?php
require_once("global.php");

$fidDB=$db->get_one("SELECT * FROM {$_pre}module WHERE id='$mid'");

if(!$fidDB){
	showerr("MID有误");
}

$m_config=unserialize($fidDB[config]);

$rsdb=$db->get_one("SELECT A.*,B.* FROM `{$_pre}content` A LEFT JOIN `{$_pre}content_$fidDB[id]` B ON A.id=B.id WHERE A.id='$id'");

if($rsdb[mid]!=$mid){
	showerr("MID不一致!");
}
if(!$rsdb){
	showerr("内容不存在");
}

if($fidDB[allowview]&&!$web_admin){
	$arr=explode(",",$fidDB[allowview]);
	if(!in_array($groupdb['gid'],$arr)){
		if(!$lfjuid){
			showerr("请先登录");
		}elseif($lfjuid!=$rsdb[uid]){
			showerr("你没权限查看!");
		}		
	}
}

$rsdb[posttime]=date("Y-m-d H:i:s",$rsdb[posttime]);
require_once(ROOT_PATH."inc/encode.php");

foreach( $m_config[field_db] AS $key=>$rs )
{
	if($key=='content'){
		continue;
	}
	if($rs[form_type]=='textarea')
	{
		$rsdb[$key]=format_text($rsdb[$key]);
	}
	elseif($rs[form_type]=='ieedit')
	{
		$rsdb[$key]=En_TruePath($rsdb[$key],0);
	}
	elseif($rs[form_type]=='upfile')
	{
		$rsdb[$key]=tempdir($rsdb[$key]);
	}
	elseif($rs[form_type]=='upmorefile')
	{
		$detail=explode("\n",$rsdb[$key]);
		unset($rsdb[$key]);
		foreach( $detail AS $_key=>$value){
			list($_url,$_name)=explode("@@@",$value);
			$_rsdb[$key][name][]=$_name=$_name?$_name:"DownLoad$_key";
			$_rsdb[$key][url][]=$_url=tempdir($_url);
			$rsdb[$key][show][]="<A HREF='$_url' target=_blank>$_name</A>";
		}
		$rsdb[$key]=implode("<br>",$rsdb[$key][show]);
	}
	elseif($rs[form_type]=='radio'||$rs[form_type]=='select'||$rs[form_type]=='checkbox')
	{
		$rsdb[$key]=SRC_true_value($rs,$rsdb[$key]);
	}
}

//管理员回复
$replydb=$db->get_one("SELECT * FROM `{$pre}form_reply` WHERE id='$id'");
$replydb && $replydb[posttime]=date("Y-m-d H:i:s",$replydb[posttime]);

$db->query("UPDATE `{$_pre}content` SET hits=hits+1 WHERE id='$id'");

require(ROOT_PATH."inc/head.php");
require("data/form_tpl/bencandy_$mid.htm");
require(ROOT_PATH."inc/foot.php");
?>