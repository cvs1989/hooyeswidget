<?php
if($rsdb[type]=='iframe'){//�����滢
	$content="<A HREF='$curl' target='_blank'>$curl</A>";
}
$content=mysql_escape_string($content);
$title=mysql_escape_string($title);

$title=@preg_replace('/<([^<]*)>/is',"",$title);	//��HTML������˵�

//����Ҫ�ɼ�������ͬ������
$ForbidAdd='';
if($rsdb[gatherthesame]&&!$morepage){
	$ForbidAdd=$db->get_one("SELECT tid FROM {$TB_pre}threads WHERE subject='$title' ORDER BY tid DESC LIMIT 1");
}

//����ɼ��ر��غ�.��Ҫ��Դ��ַ������
if($Filedb&&$GetFile&&!$ForbidAdd){
	foreach( $Filedb AS $key=>$fileurl){
		$content=str_replace($oldFileDB[$key],tempdir($fileurl),$content);
		if( (eregi("jpg$",$fileurl)||eregi("gif$",$fileurl)) && ($webdb[if_gdimg]) ){
			//��������ͼ
			if( !$havemakesmallpic ){
				$Newpicpath=PHP168_PATH."$webdb[updir]/$fileurl.gif";
				gdpic(PHP168_PATH."$webdb[updir]/$fileurl",$Newpicpath,200,150);
				if( file_exists($Newpicpath) ){
					$picurl="$fileurl.gif";
					$havemakesmallpic++;
				}
			}
			//ͼƬ��ˮӡ
			if($webdb[is_waterimg]){
				include_once(PHP168_PATH."inc/waterimage.php");
				imageWaterMark(PHP168_PATH."$webdb[updir]/$fileurl",$webdb[waterpos],PHP168_PATH.$webdb[waterimg]);
			}
		}
	}
}elseif($Filedb){
	foreach( $Filedb AS $key=>$fileurl){
		$content=str_replace($oldFileDB[$key],"$fileurl",$content);
	}
}

//��ȡʱ��
$posttime=get_time($posttime);

//������ͬʱ.����һ��BUG
$title2=get_word($title,20);
if($morepage&&$rs=$db->get_one("SELECT tid FROM {$TB_pre}threads WHERE subject='$title' ORDER BY tid DESC LIMIT 1"))
{
	@extract($db->get_one(" SELECT $TB[uid] AS uid FROM $TB[table] WHERE $TB[username]='$username' "));
	$tid=$rs[tid];
	$db->query(" UPDATE {$TB_pre}threads SET replies=replies+1,hits=hits+2,lastpost='$posttime',lastposter='$username' WHERE tid='$rs[tid]' ");
	$db->query(" UPDATE {$TB_pre}forumdata SET article=article+1,lastpost='Re:$title2\t$username\t$posttime\tread.php?tid=$tid&page=e#a' WHERE fid='$fid' ");
	$db->query("INSERT INTO {$TB_pre}posts (fid,tid,author,authorid,postdate,subject,userip,ifsign,ifconvert,ifcheck,content) VALUES ('$fid','$tid','$username','$uid','$posttime','$title','$onlineip','1','1','1','$content')");
}
elseif(!$ForbidAdd)
{
	@extract($db->get_one(" SELECT $TB[uid] AS uid FROM $TB[table] WHERE $TB[username]='$username' "));
	$copyfrom="�ɼ�����";
	$yz=1;
	$db->query("INSERT INTO {$TB_pre}threads (fid,author,authorid,subject,ifcheck,postdate,hits,lastpost,lastposter) 
	VALUES ('$fid','$username','$uid','$title','1','$posttime','2','$posttime','$username')
	");
	@extract($db->get_one("SELECT tid FROM {$TB_pre}threads ORDER BY tid DESC LIMIT 1"));
	$db->query("INSERT INTO {$TB_pre}tmsgs (tid,userip,ifsign,ifconvert,content) VALUES ('$tid','$onlineip','1','1','$content')");
	$db->query(" UPDATE {$TB_pre}forumdata SET topic=topic+1,article=article+1,lastpost='Re:$title2\t$username\t$posttime\tread.php?tid=$tid&page=e#a' WHERE fid='$fid' ");
}

?>