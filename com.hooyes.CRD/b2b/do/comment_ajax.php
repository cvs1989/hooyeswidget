<?php
require_once(dirname(__FILE__)."/"."global.php");
header('Content-Type: text/html; charset=gb2312');
/**
*��������
**/
if($action=="post"){

	//��֤�봦��
	if(!$web_admin&&$groupdb[CommentArticleYzImg])
	{
		if(!yzimg($yzimg))
		{
			if($iframeID){
				die('<SCRIPT LANGUAGE="JavaScript">alert("��֤�벻��!!");</SCRIPT>');
			}
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
	

	//ͬ���û���¼
	if(!$lfjid&&$username&&$password){
		$uid=user_login($username,$password,$cookietime);
		if($uid>0){
			$lfjuid=$uid;
			$lfjid=$username;
			$lfjdb=array('uid'=>$uid,'username'=>$username);
		}else{
			die('<SCRIPT LANGUAGE="JavaScript">alert("�ʺ���������,����ʧ��!!");</SCRIPT>');
		}
	}	

	//Ȩ���ж��Ƿ�����������
	//��ֹȫ��������
	if($webdb[forbidComment])
	{
		$allow=0;
	}
	//��Ա��������,���οͲ�������
	elseif(!$webdb[allowGuestComment]&&!$lfjid)
	{
		$allow=0;
	}
	//ȫ���˿�������
	else
	{
		$allow=1;
	}


	//�����Զ�ͨ����˵��ж�
	//ȫ���˵������Զ�ͨ�����
	if($webdb[allowGuestCommentPass])
	{
		$yz=1;
	}
	//ֻ�л�Ա�Ĳ��Զ�ͨ�����
	elseif($webdb[allowMemberCommentPass]&&$lfjid)
	{
		$yz=1;
	}
	//�������Զ�ͨ�����
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

	if(!$lfjdb){
		$username="�ο�";
	}else{
		$username=$lfjid;
	}
	$erp=get_id_table($aid);
	$rss=$db->get_one(" SELECT A.*,B.allowcomment AS Fallowcomment FROM {$pre}article$erp A LEFT JOIN {$pre}sort B ON A.fid=B.fid WHERE A.aid='$aid' ");
	if(!$rss){
		die("<SCRIPT LANGUAGE=\"JavaScript\">alert('ԭ���ݲ�����')</SCRIPT>");
	}
	if(!$webdb[showComment]){
		die("<SCRIPT LANGUAGE=\"JavaScript\">alert('ϵͳ�ر������۹���')</SCRIPT>");
	}elseif(!$rss[Fallowcomment]){
		die("<SCRIPT LANGUAGE=\"JavaScript\">alert('����Ŀ�ر������۹���')</SCRIPT>");
	}elseif($rss[forbidcomment]){
		die("<SCRIPT LANGUAGE=\"JavaScript\">alert('���Ĺر������۹���')</SCRIPT>");
	}

	$username || $username=$lfjid;

	/*���ϵͳ��������,��ô�е����۽������ύ�ɹ�,��û����ʾ����ʧ��*/
	if($allow)
	{
		$db->query("INSERT INTO `{$pre}comment` (`aid` , `fid` , `uid` , `username` , `posttime` , `content` , `ip` , `icon` , `yz` ,`authorid`) VALUES ('$aid', '$fid', '$lfjuid', '$username', '$timestamp', '$content', '$onlineip', '$commentface', '$yz','$rss[uid]')");
		$db->query("UPDATE {$pre}article$erp SET comments=comments+1 WHERE aid='$aid' ");
	}
}

/**
*ɾ������
**/
elseif($action=="del")
{
	$rs=$db->get_one("SELECT * FROM `{$pre}comment` WHERE cid='$cid'");
	if($web_admin||($lfjuid&&$lfjuid==$rs[uid]) )
	{
		$db->query("DELETE FROM `{$pre}comment` WHERE cid='$cid'");

		if($rs[aid])
		{
			$erp=get_id_table($rs[aid]);
			$db->query("UPDATE {$pre}article$erp SET comments=comments-1 WHERE aid='$rs[aid]' ");
		}
	}
}

/**
*ɾ������
**/
elseif($web_admin&&($action=="uncom"||$action=="com"))
{
	if($action=="uncom"){
		$db->query("UPDATE {$pre}comment SET ifcom=0 WHERE cid='$cid' ");
	}else{
		$db->query("UPDATE {$pre}comment SET ifcom=1 WHERE cid='$cid' ");
	}
}

/**
*ͶƱ
***/
elseif($action=='vote')
{
	$rs=$db->get_one("SELECT * FROM `{$pre}comment` WHERE cid='$cid'");
	if($job=='agree')
	{
		if($_COOKIE["agree_$cid"])
		{
			//���ݾɰ�
			if($posttype=='ajax'){
				echo "�벻Ҫ�ظ�ͶƱ!!<br><br>";
			}else{
				die('<SCRIPT LANGUAGE="JavaScript">alert("�벻Ҫ�ظ�ͶƱ!!");</SCRIPT>');
			}
		}
		else
		{
			set_cookie("agree_$cid",1,3600);
			$db->query("UPDATE {$pre}comment SET agree=agree+1 WHERE cid='$rs[cid]' ");
		}
	}
	elseif($job=='disagree')
	{
		if($_COOKIE["agree_$cid"])
		{
			//���ݾɰ�
			if($posttype=='ajax'){
				echo "�벻Ҫ�ظ�ͶƱ!!<br><br>";
			}else{
				die('<SCRIPT LANGUAGE="JavaScript">alert("�벻Ҫ�ظ�ͶƱ!!");</SCRIPT>');
			}
		}
		else
		{
			set_cookie("agree_$cid",1,3600);
			$db->query("UPDATE {$pre}comment SET disagree=disagree+1 WHERE cid='$rs[cid]' ");
		}
	}
	if($posttype!='ajax')
	{
		//refreshto("лл���ͶƱ!!",$FROMURL);
	}
}


//�ж��Ƿ���ʾȫ������
if(!$webdb[showNoPassComment])
{
	$SQL=" AND A.yz=1 ";
}
else
{
	$SQL="";
}

$rows=$webdb[showCommentRows]?$webdb[showCommentRows]:8;

if($page<1)
{
	$page=1;
}
$min=($page-1)*$rows;

/*���������ٶ�Ҳֻ������ʾ1000����*/
$leng=10000;

if($webdb[CommentOrderType]==1){
	$orderSQL=" A.ifcom DESC, ";
}elseif($webdb[CommentOrderType]==2){
	$orderSQL=" A.agree DESC, ";
}else{
	$orderSQL="";
}

$query=$db->query("SELECT A.*,B.icon,A.icon AS img FROM `{$pre}comment` A LEFT JOIN {$pre}memberdata B ON A.uid=B.uid WHERE A.aid=$aid $SQL ORDER BY $orderSQL A.cid DESC LIMIT $min,$rows");
while( $rs=$db->fetch_array($query) )
{
	if(!$rs[username]){
		$detail=explode(".",$rs[ip]);
		$rs[username]="$detail[0].$detail[1].$detail[2].*";
	}
	if($rs[icon]){
		$rs[icon]=tempdir($rs[icon]);
	}
	$rs[posttime]=date("Y-m-d H:i:s",$rs[posttime]);
	$rs[content]=get_word($rs[full_content]=$rs[content],$leng);

	$rs[title]=preg_replace("/\[quote\](.*)\[\/quote\]/","",$rs[content]);
	$rs[title]=get_word($rs[title],50);
	$rs[content]=get_word($rs[content],$leng);
	$rs[content]=preg_replace("/\[quote\](.*)\[\/quote\]/","<div class='quotecomment_div'>\\1</div>",$rs[content]);

	$rs[content]=str_replace("\n","<br>",$rs[content]);
	$rs[content]=replace_bad_word($rs[content]);
	$rs[username]=replace_bad_word($rs[username]);
	$listdb[]=$rs;
}

$showpage=getpage("`{$pre}comment` A"," where A.aid='$aid' $SQL","?fid=$fid&aid=$aid",$rows);
if($iframeID){
	$showpage=str_replace("href=\"?","target=\"$iframeID\" href=\"$webdb[www_url]/do/comment_ajax.php?iframeID=$iframeID&",$showpage);
}else{
	$showpage=preg_replace("/\?fid=([\d]+)&aid=([\d]+)&page=([\d]+)/is","javascript:getcomment('$webdb[www_url]/do/comment_ajax.php?fid=\\1&aid=\\2&page=\\3')",$showpage);
}


require_once(html('comment_ajax'));

if($iframeID){
	$content=str_replace(array("\n","\r","'","<!---->"),array("","","\'",""),ob_get_contents());
	ob_end_clean();
	//�����������
	if($webdb[cookieDomain]){
		echo "<SCRIPT LANGUAGE=\"JavaScript\">document.domain = \"$webdb[cookieDomain]\";</SCRIPT>";
	}
	echo "<SCRIPT LANGUAGE=\"JavaScript\">
		parent.document.getElementById('$iframeID').innerHTML='$content';
		</SCRIPT>";
}
?>