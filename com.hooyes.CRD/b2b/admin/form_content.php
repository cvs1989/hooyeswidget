<?php
!function_exists('html') && exit('ERR');


if($job=="list"&&$Apower[form_content])
{

	if(!$mid){
		$query = $db->query("SELECT * FROM {$pre}form_module ORDER BY list DESC,id ASC");
		while($rs = $db->fetch_array($query)){
			@extract($db->get_one("SELECT COUNT(*) AS NUM FROM {$pre}form_content WHERE mid='$rs[id]'"));
			$rs[NUM]=$NUM;
			$Mdb[$rs[id]]=$rs;
		}
		require("head.php");
		require("template/form_content/list.htm");
		require("foot.php");
		exit;
	}

	$mid=intval($mid);
	$fidDB = $db->get_one("SELECT * FROM {$pre}form_module WHERE id='$mid'");

	$array=unserialize($fidDB[config]);
  
	$rows=20;
	if($page<1){
		$page=1;
	}
	$min=($page-1)*$rows;

	$showpage=getpage("{$pre}form_content","WHERE  mid='$mid'","index.php?lfj=form_content&job=list&mid=$mid",$rows);
	$query = $db->query("SELECT C.*,D.* FROM {$pre}form_content C LEFT JOIN {$pre}form_content_$mid D ON C.id=D.id WHERE C.mid='$mid' ORDER BY C.id DESC LIMIT $min,$rows");
	while($rs = $db->fetch_array($query)){

		foreach( $array[listshow_db] AS $key=>$rs2){
			$rs[$key]=SRC_true_value($array[field_db][$key],$rs[$key]);
		}

		$rs[posttime]=date("Y-m-d",$rs[posttime]);
		$listdb[]=$rs;
	}
	require("head.php");
	require(PHP168_PATH."php168/form_tpl/admin_list_$mid.htm");
	require("foot.php");
}
elseif($action=="delete"&&$Apower[form_content])
{
	if($id){
		$rs = $db->get_one("SELECT * FROM {$pre}form_content WHERE id='$id'");

		$db->query("DELETE FROM {$pre}form_content WHERE id='$id'");
		$db->query("DELETE FROM {$pre}form_content_$rs[mid] WHERE id='$id'");
		$db->query("DELETE FROM `{$pre}form_reply` WHERE id='$id'");
	}else{
		foreach( $iddb AS $key=>$value){
			$rs = $db->get_one("SELECT * FROM {$pre}form_content WHERE id='$value'");
			$db->query("DELETE FROM {$pre}form_content WHERE id='$value'");
			$db->query("DELETE FROM {$pre}form_content_$rs[mid] WHERE id='$value'");
			$db->query("DELETE FROM `{$pre}form_reply` WHERE id='$value'");
		}
	}
	jump("删除成功","$FROMURL",1);
}
elseif($job=="view"&&$Apower[form_content])
{
	$query = $db->query("SELECT * FROM {$pre}form_module ORDER BY list DESC");
	while($rs = $db->fetch_array($query)){
		$Mdb[$rs[id]]=$rs[name];
	}
	$mid=intval($mid);
	$colordb[$mid]='red;';

	$fidDB=$db->get_one("SELECT * FROM {$pre}form_module WHERE id='$mid'");

	$m_config=unserialize($fidDB[config]);

	$rsdb=$db->get_one("SELECT A.*,B.* FROM `{$pre}form_content` A LEFT JOIN `{$pre}form_content_$fidDB[id]` B ON A.id=B.id WHERE A.id='$id'");

	require_once(PHP168_PATH."inc/encode.php");

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

	$rsdb[posttime]=date("Y-m-d H:i:s",$rsdb[posttime]);
	require("head.php");
	require(PHP168_PATH."php168/form_tpl/admin_bencandy_$mid.htm");
	require("foot.php");
}
elseif($job=="yz"&&$Apower[form_content])
{
	$db->query("UPDATE `{$pre}form_content` SET yz='$yz' WHERE id='$id'");
	jump("修改成功","$FROMURL",'0');
}
elseif($job=="reply"&&$Apower[form_content])
{
	$rsdb=$db->get_one("SELECT * FROM `{$pre}form_reply` WHERE id='$id'");
	$rsdb[content]=En_TruePath($rsdb[content],0);
	$rsdb[content]=str_replace("'","&#39;",$rsdb[content]);
	require("head.php");
	require("template/form_content/reply.htm");
	require("foot.php");
}
elseif($action=="reply"&&$Apower[form_content])
{
	$rsdb=$db->get_one("SELECT A.*,U.mobphone FROM `{$pre}form_content` A LEFT JOIN `{$pre}memberdata` U ON A.uid=U.uid WHERE A.id='$id'");
	$db->query("DELETE FROM `{$pre}form_reply` WHERE id='$id'");

	$postdb[content]=En_TruePath($postdb[content]);

	$db->query("UPDATE `{$pre}form_content` SET yz=1 WHERE id='$id'");

	$db->query("INSERT INTO `{$pre}form_reply` ( `id` , `mid` , `posttime` , `uid` , `username` , `content` , `ip` ) VALUES ('$id', '$mid', '$timestamp', '$userdb[uid]', '$userdb[username]', '$postdb[content]', '$onlineip')");

	//手机短信通知客户
	if($send_sms){
		if(!$rsdb[mobphone]){
			$MSG='客户没有设置手机号码,短信发送失败.';
		}else{
			$mdb=$db->get_one("SELECT * FROM {$pre}form_module WHERE id='$mid' ");

			$Title="你好,你在<$webdb[webname]-$mdb[name]>提的问题,管理员已作解答,请尽快上网查阅!";
			if( sms_send($rsdb[mobphone], $Title )===1 ){
				$MSG='短信发送成功';
			}else{
				$MSG='短信发送失败,请检查短信接口,是否帐号有误,或者是余额不足!';
			}
		}
	}else{
		$MSG='回复成功';
	}

	jump($MSG,"index.php?lfj=form_content&job=view&mid=$mid&id=$id",'3');
}
?>