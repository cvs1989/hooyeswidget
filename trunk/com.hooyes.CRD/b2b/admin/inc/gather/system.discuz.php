<?php

if($rsdb[type]=='iframe'){//�����滢
	$content="<A HREF='$curl' target='_blank'>$curl</A>";
}
$content=mysql_escape_string($content);
$title=mysql_escape_string($title);

$title=@preg_replace('/<([^>]*)>/is',"",$title);	//��HTML������˵�

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
				gdpic(PHP168_PATH."$webdb[updir]/$fileurl",$Newpicpath,200,150,$webdb[autoCutSmallPic]?array('fix'=>1):'');
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

//������ͬʱ.����һ��BUG
$title2=get_word($title,20);
if($morepage&&$rs=$db->get_one("SELECT tid FROM {$TB_pre}threads WHERE subject='$title' ORDER BY tid DESC LIMIT 1"))
{
	@extract($db->get_one(" SELECT $TB[uid] AS uid FROM $TB[table] WHERE $TB[username]='$username' "));
	$tid=$rs[tid];
	$db->query(" UPDATE {$TB_pre}threads SET replies=replies+1,views=views+2,lastpost='$timestamp',lastposter='$username' WHERE tid='$rs[tid]' ");

	$db->query(" UPDATE {$TB_pre}forums SET posts=posts+1,lastpost='$uid\t$title2\t$timestamp\t$username' WHERE fid='$fid' ");

	$db->query("INSERT INTO {$TB_pre}posts (fid,tid,first,useip,message,author,authorid,subject,dateline,htmlon) VALUES ('$fid','$tid',0,'$onlineip','$content','$username','$uid','$title','$timestamp',1)");
}
elseif(!$ForbidAdd)
{
	@extract($db->get_one(" SELECT $TB[uid] AS uid FROM $TB[table] WHERE $TB[username]='$username' "));
	$copyfrom="�ɼ�����";
	$yz=1;
	$db->query("INSERT INTO {$TB_pre}threads (fid,author,authorid,subject,dateline,views,lastpost,lastposter) 
	VALUES ('$fid','$username','$uid','$title','$timestamp','2','$timestamp','$username')
	");

	@extract($db->get_one("SELECT tid FROM {$TB_pre}threads ORDER BY tid DESC LIMIT 1"));

	$db->query("INSERT INTO {$TB_pre}posts (fid,tid,first,useip,message,author,authorid,subject,dateline,htmlon) VALUES ('$fid','$tid',1,'$onlineip','$content','$username','$uid','$title','$timestamp',1)");

	$db->query(" UPDATE {$TB_pre}forums SET threads=threads+1,posts=posts+1,lastpost='$uid\t$title2\t$timestamp\t$username' WHERE fid='$fid' ");
}

?>