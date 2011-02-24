<?php
require_once("global.php");

if(!$lfjuid){
	showerr('你还没有登录!!');
}

$fidDB=$db->get_one("SELECT A.* FROM {$_pre}sort A WHERE A.fid='$fid'");

if(!$fidDB){
	showerr("FID有误!");
}
$_erp=$Fid_db[tableid][$fid];

$infodb=$db->get_one("SELECT B.*,A.*,D.email FROM `{$_pre}content$_erp` A LEFT JOIN `{$_pre}content_$fidDB[mid]` B ON A.id=B.id LEFT JOIN `{$pre}memberdata` D ON A.uid=D.uid WHERE A.id='$cid'");


if(!$infodb){
	showerr("内容不存在");
}elseif($infodb[fid]!=$fid){
	showerr("FID有误!!!");
}elseif(!$web_admin&&$lfjuid==$infodb[uid]){
	showerr('你不能自己给自己的产品报价');
}



$mid=2;

/**
*模块参数配置文件
**/
$field_db = $module_DB[$mid][field];


/**处理提交的新发表内容**/
if($action=="postnew")
{
	if(!$web_admin){
		if($groupdb[post_baojiadian_num]<1){
			showerr('你所在用户组不允许发报价单,请升级用户组吧');
		}
		$time=$timestamp-24*3600;
		$_rs=$db->get_one("SELECT COUNT(*) AS NUM FROM `{$_pre}join` WHERE uid='$lfjuid' AND posttime>$time");
		if($_rs[NUM]>$groupdb[post_baojiadian_num]){
			showerr('你所在用户组每天发布的报价单不能超过{$groupdb[post_baojiadian_num]}条,请升级用户组吧');
		}
	}

	if(!check_imgnum($yzimg)){
		showerr("验证码不符合");
	}

	//自定义字段的合法检查与数据处理
	$Module_db->checkpost($field_db,$postdb,'');


	/*往主信息表插入内容*/
	$db->query("INSERT INTO `{$_pre}join` ( `mid` , `cid` , `cuid` , `fid` ,  `posttime` ,  `uid` , `username` , `yz` , `ip` ) 
	VALUES (
	'$mid','$cid','$infodb[uid]', '$fid','$timestamp','$lfjdb[uid]','$lfjdb[username]','0','$onlineip')");

	$id = $db->insert_id();

	unset($sqldb);
	$sqldb[]="id='$id'";
	$sqldb[]="fid='$fid'";
	$sqldb[]="uid='$lfjuid'";

	
	/*检查判断辅信息表要插入哪些字段的内容*/
	foreach( $field_db AS $key=>$value){
		isset($postdb[$key]) && $sqldb[]="`{$key}`='{$postdb[$key]}'";
	}

	$sql=implode(",",$sqldb);

	$db->query("INSERT INTO `{$_pre}content_$mid` SET $sql");


	if($webdb[order_send_mail]){
		send_mail($infodb[email],"有客户向你报价了","请尽快查看<A HREF='$Murl/member/joinshow.php?id=$id' target='_blank'>$Murl/member/joinshow.php?id=$id</A>",0);
	}
	if($webdb[order_send_msg]){
		send_msg($infodb[uid],"有客户向你报价了","请尽快查看<A HREF='$Murl/member/joinshow.php?id=$id' target='_blank'>$Murl/member/joinshow.php?id=$id</A>");
	}

	refreshto("bencandy.php?fid=$fid&id=$cid","报价单已经发出,请等待回应!");
	
}

/*删除内容,直接删除,不保留*/
elseif($action=="del")
{
	del_order($id);
	refreshto("bencandy.php?fid=$fid&id=$cid","删除成功");
}

/*编辑内容*/
elseif($job=="edit")
{
	$rsdb=$db->get_one("SELECT A.*,B.* FROM `{$_pre}join` A LEFT JOIN `{$_pre}content_$mid` B ON A.id=B.id WHERE A.id='$id'");

	if($rsdb[uid]!=$lfjuid&&!$web_admin)
	{
		showerr("你无权修改");
	}

	$hownum=$rsdb[shopnum];

	/*表单默认变量作处理*/
	$Module_db->formGetVale($field_db,$rsdb);

	$atc="edit";	

	require(ROOT_PATH."inc/head.php");
	require(getTpl("post_$mid",$FidTpl['post']));
	require(ROOT_PATH."inc/foot.php");
}

/*处理提交的内容做修改*/
elseif($action=="edit")
{
	if(!check_imgnum($yzimg)){
		showerr("验证码不符合");
	}

	$rsdb=$db->get_one("SELECT A.*,B.* FROM `{$_pre}join` A LEFT JOIN `{$_pre}content_$mid` B ON A.id=B.id WHERE A.id='$id' LIMIT 1");

	if($rsdb[uid]!=$lfjuid&&!$web_admin)
	{
		showerr("你无权修改");
	}

	//自定义字段的合法检查与数据处理
	$Module_db->checkpost($field_db,$postdb,$rsdb);


	/*更新主信息表内容*/
	//$db->query("UPDATE `{$_pre}join` SET title='$postdb[title]' WHERE id='$id'");


	/*检查判断辅信息表要插入哪些字段的内容*/
	unset($sqldb);
	foreach( $field_db AS $key=>$value){
		$sqldb[]="`$key`='{$postdb[$key]}'";
	}	
	$sql=implode(",",$sqldb);

	/*更新辅信息表*/
	$db->query("UPDATE `{$_pre}content_$mid` SET $sql WHERE id='$id'");
	
	refreshto("bencandy.php?fid=$fid&id=$cid","修改成功");
}
else
{
	/*模块设置时,有些字段有默认值*/
	foreach( $field_db AS $key=>$rs){	
		if($rs[form_value]){		
			$rsdb[$key]=$rs[form_value];
		}
	}

	/*表单默认变量作处理*/
	$Module_db->formGetVale($field_db,$rsdb);

	$atc="postnew";
	
	$rsdb[ask_title] = "我对您发布的“{$infodb[title]}”很感兴趣";
	$rsdb[hope_price] = $infodb[price];
	
	require(ROOT_PATH."inc/head.php");
	require(getTpl("post_$mid"));
	require(ROOT_PATH."inc/foot.php");
}

?>