<?php
require(dirname(__FILE__)."/"."global.php");
require_once(ROOT_PATH."inc/encode.php");


if($step=="post"){
	if(!$webdb[ifOpenGuestBook]){
		showerr("很抱歉,管理员关闭了留言功能");
	}
	if( $webdb[yzImgGuestBook]&&!$web_admin ){
		if(!check_imgnum($yzimg)){
			showerr("验证码不符合");
		}else{
			set_cookie("yzImgNum","");
		}
	}
	if(!$postdb[content]){
		showerr("内容不能为空");
	}elseif(strlen($postdb[content])>50000){
		showerr("内容不能大于5万字符!");
	}
	if($postdb[oicq]&&!ereg("^[0-9]{5,11}$",$postdb[oicq])){
		showerr("OICQ格式不符合规则");
	}
	if($postdb[mobphone]&&!ereg("^1[0-9]{10}$",$postdb[mobphone])){
		showerr("手机号码不符合规则");
	}
	if ($postdb[email]&&!ereg("^[-a-zA-Z0-9_\.]+\@([0-9A-Za-z][0-9A-Za-z-]+\.)+[A-Za-z]{2,5}$",$postdb[email])) {
		showerr("邮箱不符合规则");
	}
	if($postdb[weburl]&&!eregi(":\/\/",$postdb[weburl])){
		$postdb[weburl]="http://$postdb[weburl]";
	}
	if($postdb[blogurl]&&!eregi(":\/\/",$postdb[blogurl])){
		$postdb[blogurl]="http://$postdb[blogurl]";
	}
	foreach($postdb AS $key=>$value){
		$postdb[$key]=filtrate($postdb[$key]);
	}
	$yz=0;
	if($web_admin){
		$yz=1;
	}elseif($webdb[groupPassPassGuestBook]){
		$webdb[groupPassPassGuestBook]=explode(",",$webdb[groupPassPassGuestBook]);
		if(in_array($groupdb[gid],$webdb[groupPassPassGuestBook])){
			$yz=1;
		}
	}

	//过滤不健康的字
	$postdb[content]=replace_bad_word($postdb[content]);
	$postdb[username]=replace_bad_word($postdb[username]);

	//处理有人恶意用他人帐号做署名的
	if($postdb[username]){
		$rs=$userDB->get_passport($postdb[username],'name');
		if($rs && $rs[uid]!=$lfjuid){
			showerr("此用户名为注册用户的帐号,请换一个");
		}
	}

	$db->query("INSERT INTO `{$_pre}content` ( `ico` , `email` , `oicq` , `weburl` , `blogurl` , `uid` , `username` , `ip` , `content` , `yz` , `posttime` , `list`, `fid`, `mobphone` ) 
	VALUES (
	'$face','$postdb[email]','$postdb[oicq]','$postdb[weburl]','$postdb[blogurl]','$lfjuid','$postdb[username]','$onlineip','$postdb[content]','$yz','$timestamp','$timestamp','$fid','$postdb[mobphone]')
	");
	refreshto("?fid=$fid","谢谢你的留言",1);
}elseif($action=="delete"&&$lfjuid){
	if($web_admin){
		$db->query("DELETE FROM `{$_pre}content` WHERE id='$id'");
	}else{
		$db->query("DELETE FROM `{$_pre}content` WHERE id='$id' AND uid='$lfjuid' ");
	}
	refreshto("?fid=$fid","删除成功",1);
}


$rows=$webdb[GuestBookNum]>0?$webdb[GuestBookNum]:10;
if($page<1){
	$page=1;
}
$min=($page-1)*$rows;

if(!$webdb[viewNoPassGuestBook]){
	$SQL=" WHERE G.yz=1 ";
}else{
	$SQL=" WHERE 1 ";
}
if($fid){
	$SQL .= " AND fid='$fid' ";
}

$query = $db->query("SELECT SQL_CALC_FOUND_ROWS G.*,D.icon FROM `{$_pre}content` G LEFT JOIN `{$pre}memberdata` D ON G.uid=D.uid $SQL ORDER BY G.id DESC LIMIT $min,$rows");
$RS=$db->get_one("SELECT FOUND_ROWS()");
$totalNum=$RS['FOUND_ROWS()'];
$showpage=getpage("","","?fid=$fid",$rows,$totalNum);

while($rs = $db->fetch_array($query)){
	$rs[content]=format_text($rs[content]);	
	$rs[content]=replace_bad_word($rs[content]);	//过滤不健康的字
	if($rs[reply]){
		$replydb=unserialize($rs[reply]);
		$replydb[content]=str_replace("\r\n","<br>",$replydb[content]);
		$replydb[content]=replace_bad_word($replydb[content]);	//过滤不健康的字
		$replydb[posttime]=date("Y-m-d H:i:s",$replydb[posttime]);
		$rs[content] .= "<FIELDSET><LEGEND>留言回复</LEGEND>$replydb[content] (署名:$replydb[username]/日期:$replydb[posttime])</FIELDSET>";
	}
	$rs[posttime]=date("Y-m-d H:i:s",$rs[posttime]);
	$detail=explode(".",$rs[ip]);
	$rs[ip]="$detail[0].$detail[1].$detail[2].*";
	if($web_admin){
		$rs['delete']="[<A HREF='replyguestbook.php?fid=$fid&id=$rs[id]'>回复</A>] [<A HREF='?action=delete&id=$rs[id]'>删除</A>]";
	}elseif($lfjuid==$rs[uid]){
		$rs['delete']="[<A HREF='?action=delete&id=$rs[id]'>删除</A>]";
	}
	if($rs[weburl]){
		$rs['_weburl']="<A HREF='$rs[weburl]' target='_blank' title='查看主页'>".'<img src="'.$webdb[www_url].'/images/default/home.gif" width="16" height="16" border="0">'."</A>";
	}
	if($rs[blogurl]){
		$rs['_blogurl']="<A HREF='$rs[blogurl]' target=_blank title='查看BLOG'>".'<img src="'.$webdb[www_url].'/images/default/song_word.gif" width="16" height="16" border="0">'."</A>";
	}
	$rs[icon]&&$rs[icon]=tempdir($rs[icon]);
	if($rs[oicq]){
		$rs[oicq]="<a target=blank href=tencent://message/?uin=$rs[oicq]&Site=$VlogCfg[webname]&Menu=yes><img border='0' SRC=http://wpa.qq.com/pa?p=1:$rs[oicq]:9 alt='给我留言'></a>";
	}else{
		$rs[oicq]='';
	}
	$rs[onclick]="";
	if(!$rs[username]){
		$rs[username]='*匿名游客*';
		$rs[onclick]="onclick='return false;'";
	}
	if($rs[mobphone] && $web_admin){
		$rs[_mobphone]=" 手机号码:$rs[mobphone] ";
	}
	$listdb[]=$rs;
}

$chdb[main_tpl]=getTpl("index");
$ch_fid	= $ch_pagetype = 0;
$ch_module = $webdb[module_id]?$webdb[module_id]:7;
@include(ROOT_PATH."inc/label_module.php");

require(ROOT_PATH."inc/head.php");
require(getTpl("index"));
require(ROOT_PATH."inc/foot.php");
?>