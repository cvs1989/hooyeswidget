<?php
require_once("global.php");

header('Content-Type: text/html; charset=gb2312');

/**
*�����û��ύ������
**/
if($action=="post")
{
	if($ctype == 1){
		$tbl = 'content_sell';
		
	}else if($ctype == 2){
		$tbl = 'content_buy';
		
	}else{
		die("������Ĳ���");
	}

	$_web=preg_replace("/http:\/\/([^\/]+)\/(.*)/is","http://\\1",$WEBURL);
	if($webdb[Info_forbidOutPost]&&!ereg("^$_web",$FROMURL))
	{
		showerr("ϵͳ���ò��ܴ��ⲿ�ύ����");
	}
	
	/*��֤�봦��*/
	if($webdb[Info_GroupCommentYzImg]&&in_array($groupdb['gid'],explode(",",$webdb[Info_GroupCommentYzImg])))
	{
		if(!yzimg($yzimg))
		{
			die("��֤�벻����,����ʧ��");
		}
		else
		{
			//setcookie("yzImgNum","0",$timestamp+3600,"/");
		}
	}

	if(!$content)
	{
		die("���ݲ���Ϊ��");
	}


	/*�Ƿ����������жϴ���*/
	/*��ֹ�����˽�������*/
	if($webdb[forbidComment])
	{
		$allow=0;
	}
	/*��ֹ�ο�����*/
	elseif(!$webdb[allowGuestComment]&&!$lfjid)
	{
		$allow=0;
	}
	/*��������������*/
	else
	{
		$allow=1;
	}
	
	

	/*�Ƿ����������Զ�ͨ������жϴ���*/
	/*�ж������Ƿ��Զ�ͨ����֤,allowGuestCommentPassΪ�����˵������Զ�ͨ����֤*/
	if($webdb[allowGuestCommentPass])
	{
		$yz=1;
	}
	/*ֻ�е�¼�û������۲������Զ�ͨ����֤*/
	elseif($webdb[allowMemberCommentPass]&&$lfjid)
	{
		$yz=1;
	}
	/*�������Զ�ͨ����֤*/
	else
	{
		$yz=0;
	}


	$username=filtrate($username);
	$content=filtrate($content);

	$content=str_replace("@@br@@","<br>",$content);

	//���˲���������
	$username=replace_bad_word($username);
	$content=replace_bad_word($content);

	//�������˶����������ʺ���������
	if($username)
	{
		$rs=$db->get_one(" SELECT $TB[uid] AS uid FROM $TB[table] WHERE $TB[username]='$username' ");
		if($rs[uid]!=$lfjuid)
		{
			$username="����";
		}
	}
	
	$rss=$db->get_one(" SELECT * FROM {$_pre}$tbl WHERE id='$id' ");
	if(!$rss){
		die("ԭ���ݲ�����");
	}
	$fid=$rss[fid];

	$username || $username=$lfjid;


	/*���ϵͳ��������,��ô�е����۽������ύ�ɹ�,��û����ʾ����ʧ��*/
	if($allow)
	{
		
		$db->query("INSERT INTO `{$_pre}comments` (`cuid`, `type`, `id`, `fid`, `uid`, `username`, `posttime`, `content`, `ip`, `icon`, `yz`) VALUES ('$rss[uid]','0','$id','$fid','$lfjuid','$username','$timestamp','$content','$onlineip','$icon','$yz')");

		$db->query(" UPDATE {$_pre}$tbl SET comments=comments+1,`replytime`='$timestamp' WHERE id='$id' ");
	}
}

/*ɾ������*/
elseif($action=="del")
{
	if($ctype == 1){
		$tbl = 'content_sell';
		
	}else if($ctype == 2){
		$tbl = 'content_buy';
		
	}else{
		die("������Ĳ���");
	}
	
	$rs=$db->get_one("SELECT * FROM `{$_pre}comments` WHERE cid='$cid'");
	if(!$lfjuid)
	{
		die("�㻹û��¼,��Ȩ��");
	}
	elseif(!$web_admin&&$rs[uid]!=$lfjuid&&$rs[cuid]!=$lfjuid)
	{
		die("��ûȨ��");
	}
	if(!$web_admin&&$rs[uid]!=$lfjuid){
		$lfjdb[money]=get_money($lfjdb[uid]);
		if(abs($webdb[DelOtherCommentMoney])>$lfjdb[money]){
			die("���{$webdb[MoneyName]}����");
		}
		add_user($lfjdb[uid],-abs($webdb[DelOtherCommentMoney]));
	}
	$db->query(" DELETE FROM `{$_pre}comments` WHERE cid='$cid' ");
	$db->query("UPDATE {$_pre}$tbl SET comments=comments-1 WHERE id='$rs[id]' ");
}
/*�ʻ���������*/
elseif($action=="flowers"||$action=="egg")
{
	if($_COOKIE["{$action}_$cid"]){
		echo "�벻Ҫ�ظ�����!!<hr>";
	}else{
		set_cookie("{$action}_$cid",1,3600);
		$db->query("UPDATE `{$_pre}comments` SET `$action`=`$action`+1 WHERE cid='$cid'");
	}
}
/**
*�Ƿ�ֻ��ʾͨ����֤������,������ȫ����ʾ
**/
if(!$webdb[showNoPassComment])
{
	$SQL=" AND yz=1 ";
}
else
{
	$SQL="";
}

/**
*ÿҳ��ʾ��������
**/
$rows=$webdb[showCommentRows]?$webdb[showCommentRows]:8;

if($page<1)
{
	$page=1;
}
$min=($page-1)*$rows;


//$rsdb=$db->get_one("SELECT M.* FROM {$_pre}sort S LEFT JOIN {$_pre}module M ON S.mid=M.id WHERE S.fid='$fid'");
$rsdb=$db->get_one("SELECT S.* FROM {$_pre}sort S  WHERE S.fid='$fid'");
/*���������ٶ�Ҳֻ������ʾ1000����*/
$leng=1000;

$query=$db->query("SELECT * FROM `{$_pre}comments` WHERE id=$id $SQL ORDER BY cid DESC LIMIT $min,$rows");
while( $rs=$db->fetch_array($query) )
{
	if(!$rs[username])
	{
		$detail=explode(".",$rs[ip]);
		$rs[username]="$detail[0].$detail[1].$detail[2].*";
	}

	$rs[posttime]=date("Y-m-d H:i:s",$rs[posttime]);

	$rs[full_content]=$rs[content];

	$rs[content]=get_word($rs[content],$leng);

	if($rs[type]){
		$rs[content]="<img style='margin-top:3px;' src=$webdb[www_url]/images/default/good_ico.gif> ".$rs[content];
	}

	$rs[content]=str_replace("\n","<br>",$rs[content]);

	$listdb[]=$rs;
}

/**
*���۷ֲ�����
**/
$showpage=getpage("`{$_pre}comments`"," where id='$id' $SQL","?fid=$fid&id=$id",$rows);
$showpage=preg_replace("/\?fid=([\d]+)&id=([\d]+)&page=([\d]+)/is","javascript:getcomment('comment_ajax.php?fid=\\1&id=\\2&page=\\3')",$showpage);


require_once(getTpl('comment_ajax'));

?>