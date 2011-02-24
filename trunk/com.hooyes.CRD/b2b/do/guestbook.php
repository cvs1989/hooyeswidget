<?php
require(dirname(__FILE__)."/"."global.php");
require_once(PHP168_PATH."inc/encode.php");
if($step=="post"){
	if(!$webdb[ifOpenGuestBook]){
		showerr("�ܱ�Ǹ,����Ա�ر������Թ���");
	}
	if( $webdb[yzImgGuestBook]&&!$web_admin ){
		if(!yzimg($yzimg)){
			showerr("��֤�벻����");
		}else{
			set_cookie("yzImgNum","");
		}
	}
	if(!$postdb[content]){
		showerr("���ݲ���Ϊ��");
	}
	if($postdb[oicq]&&!ereg("^[0-9]{5,11}$",$postdb[oicq])){
		showerr("OICQ��ʽ�����Ϲ���");
	}
	if ($postdb[email]&&!ereg("^[-a-zA-Z0-9_\.]+\@([0-9A-Za-z][0-9A-Za-z-]+\.)+[A-Za-z]{2,5}$",$postdb[email])) {
		showerr("���䲻���Ϲ���");
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
		if(in_array($lfjdb[groupid],$webdb[groupPassPassGuestBook])){
			$yz=1;
		}
	}

	//���˲���������
	$postdb[content]=replace_bad_word($postdb[content]);
	$postdb[username]=replace_bad_word($postdb[username]);

	//�������˶����������ʺ���������
	if($postdb[username]){
		$rs=$db->get_one(" SELECT $TB[uid] AS uid FROM $TB[table] WHERE $TB[username]='$postdb[username]' ");
		if($rs[uid]!=$lfjuid){
			showerr("���û���Ϊע���û����ʺ�,�뻻һ��");
		}
	}

	$db->query("INSERT INTO `{$pre}guestbook` ( `ico` , `email` , `oicq` , `weburl` , `blogurl` , `uid` , `username` , `ip` , `content` , `yz` , `posttime` , `list` ) 
	VALUES (
	'$face','$postdb[email]','$postdb[oicq]','$postdb[weburl]','$postdb[blogurl]','$lfjuid','$postdb[username]','$onlineip','$postdb[content]','$yz','$timestamp','$timestamp')
	");
	refreshto("?","лл�������",1);
}elseif($action=="delete"&&$lfjuid){
	if($web_admin){
		$db->query("DELETE FROM `{$pre}guestbook` WHERE id='$id'");
	}else{
		$db->query("DELETE FROM `{$pre}guestbook` WHERE id='$id' AND uid='$lfjuid' ");
	}
	refreshto("?","ɾ���ɹ�",1);
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
$showpage=getpage("`{$pre}guestbook` G LEFT JOIN `{$pre}memberdata` D ON G.uid=D.uid","$SQL","?","$rows");
$query = $db->query("SELECT G.*,D.icon FROM `{$pre}guestbook` G LEFT JOIN `{$pre}memberdata` D ON G.uid=D.uid $SQL ORDER BY G.id DESC LIMIT $min,$rows");
while($rs = $db->fetch_array($query)){
	$rs[content]=format_text($rs[content]);	
	$rs[content]=replace_bad_word($rs[content]);	//���˲���������
	if($rs[reply]){
		$replydb=unserialize($rs[reply]);
		$replydb[content]=str_replace("\r\n","<br>",$replydb[content]);
		$replydb[content]=replace_bad_word($replydb[content]);	//���˲���������
		$replydb[posttime]=date("Y-m-d H:i:s",$replydb[posttime]);
		$rs[content] .= "<FIELDSET><LEGEND>���Իظ�</LEGEND>$replydb[content] (����:$replydb[username]/����:$replydb[posttime])</FIELDSET>";
	}
	$rs[posttime]=date("Y-m-d H:i:s",$rs[posttime]);
	$detail=explode(".",$rs[ip]);
	$rs[ip]="$detail[0].$detail[1].$detail[2].*";
	if($web_admin){
		$rs['delete']="[<A HREF='job.php?job=replyguestbook&id=$rs[id]'>�ظ�</A>] [<A HREF='?action=delete&id=$rs[id]'>ɾ��</A>]";
	}elseif($lfjuid==$rs[uid]){
		$rs['delete']="[<A HREF='?action=delete&id=$rs[id]'>ɾ��</A>]";
	}
	if($rs[weburl]){
		$rs['_weburl']="<A HREF='$rs[weburl]' target='_blank' title='�鿴��ҳ'>".'<img src="images/default/home.gif" width="16" height="16" border="0">'."</A>";
	}
	if($rs[blogurl]){
		$rs['_blogurl']="<A HREF='$rs[blogurl]' target=_blank title='�鿴BLOG'>".'<img src="images/default/song_word.gif" width="16" height="16" border="0">'."</A>";
	}
	$rs[icon]&&$rs[icon]=tempdir($rs[icon]);
	if($rs[oicq]){
		$rs[oicq]="<a target=blank href=tencent://message/?uin=$rs[oicq]&Site=$VlogCfg[webname]&Menu=yes><img border='0' SRC=http://wpa.qq.com/pa?p=1:$rs[oicq]:9 alt='��������'></a>";
	}else{
		$rs[oicq]='';
	}
	$rs[onclick]="";
	if(!$rs[username]){
		$rs[username]='*�����ο�*';
		$rs[onclick]="onclick='return false;'";
	}
	$listdb[]=$rs;
}
require(PHP168_PATH."inc/head.php");
require(html("guestbook"));
require(PHP168_PATH."inc/foot.php");
?>