<?php
if($rsdb[type]=='iframe'){//�����滢
	$content="<A HREF='$curl' target='_blank'>$curl</A>";
}

//���ƴ���
if($iframeurl){
	$content="<A HREF='$iframeurl' target='_blank'>$iframeurl</A>";
}

$content=mysql_escape_string($content);
if( eregi("@@",$title) )
{
	list($title,$picurl)=explode("@@",$title);
}
$title=@preg_replace('/<([^>]*)>/is',"",$title);	//��HTML������˵�
$title=mysql_escape_string($title);

//����Ҫ�ɼ�������ͬ������
$ForbidAdd='';
if($rsdb[gatherthesame]&&!$morepage){
	$ForbidAdd=$db->get_one("SELECT aid FROM {$pre}article$erp WHERE title='$title' ORDER BY aid DESC LIMIT 1");
}


if(!$picurl&&is_array($Filedb)){
	foreach( $Filedb AS $key=>$value){
		if(eregi("(\.png|\.jpg|\.gif)$",$value)){
			$picurl=$value;
			break;
		}
	}
}

//����ɼ��ر��غ�.��Ҫ��Դ��ַ������
if($Filedb&&$GetFile&&!$ForbidAdd){
	foreach( $Filedb AS $key=>$fileurl){
		$content=str_replace($oldFileDB[$key],tempdir("$fileurl"),$content);
		if( eregi("(jpg|gif|png)$",$fileurl) && ($webdb[if_gdimg]) ){
			//��������ͼ
			if( !$havemakesmallpic ){
				$Newpicpath=PHP168_PATH."$webdb[updir]/$fileurl.gif";
				gdpic(PHP168_PATH."$webdb[updir]/$fileurl",$Newpicpath,300,225,$webdb[autoCutSmallPic]?array('fix'=>1):'');
				if( filesize($Newpicpath)>1024*3 ){
					$picurl="$fileurl.gif";
					$havemakesmallpic++;
				}else{
					unlink($Newpicpath);
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

//������ַ������
$content=En_TruePath($content);

//���ϵͳû�����Զ���������ͼ.��ȡ��
//$webdb[autoGetSmallPic] || $picurl='';


if($picurl){
	$ispic=1;
}else{
	$ispic=0;
}
//������ͬʱ.����һ��BUG
if($morepage&&$rs=$db->get_one("SELECT aid FROM {$pre}article$erp WHERE title='$title' ORDER BY aid DESC LIMIT 1"))
{
	$aid=$rs[aid];
	$db->query(" UPDATE {$pre}article$erp SET pages=pages+1 WHERE aid='$rs[aid]' ");
	$db->query("INSERT INTO `{$pre}reply$erp` (  `aid` , `fid` ,uid,  `content`, ishtml) VALUES ( '$rs[aid]', '$fid','$userdb[uid]', '$content',1)");

	@extract($db->get_one("SELECT rid FROM {$pre}reply$erp WHERE aid='$rs[aid]' ORDER BY rid DESC LIMIT 1 "));
}
elseif(!$ForbidAdd)
{
	$fidDB=$db->get_one(" SELECT A.name,B.config,A.fmid FROM {$pre}sort A LEFT JOIN {$pre}article_module B ON A.fmid=B.id WHERE A.fid='$fid' ");
	$fname=$fidDB[name];

	@extract($db->get_one(" SELECT $TB[uid] AS uid FROM $TB[table] WHERE $TB[username]='$username' "));
	//$copyfrom="�ɼ�����";
	$author=filtrate($author);
	$copyfrom=filtrate($copyfrom);
	$yz=1;

	//��ȡʱ��
	$posttime=get_time($posttime);
	$hits=intval($hits);

	$db->query("
	INSERT INTO `{$pre}article$erp` 
	( `title`, `mid`,  `fid`, `fname`, `pages`, `posttime`, `list`, `uid`, `username`,`copyfrom`, `copyfromurl`,  `picurl`,`ispic`, `yz`, `keywords`, `jumpurl`, `iframeurl`, `ip`,`author`,`hits`) 
	VALUES
	('$title','$fidDB[fmid]','$fid','$fname','1','$posttime','$posttime','$uid','$username','$copyfrom','$curl','$picurl','$ispic','$yz','$keywords','$jumpurl','$iframeurl','$onlineip','$author','$hits')
	");
	
	@extract($db->get_one("SELECT aid FROM {$pre}article$erp ORDER BY aid DESC LIMIT 1 "));

	$db->query("INSERT INTO `{$pre}article_db` (`aid`) VALUES ('$aid')");

	$db->query("INSERT INTO `{$pre}reply$erp` (  `aid` , `fid` ,`uid` ,  `content` ,`ishtml`,topic) VALUES ( '$aid', '$fid','$userdb[uid]', '$content','1',1)");
	
	//������ģ���Զ����ֶεĲɼ�.����������
	if($fidDB[config]){
		$SQL='';
		$M_config=unserialize($fidDB[config]);
		foreach( $M_config[field_db] AS $key=>$value){
			if($gather_module_valeDB[$key]){
				$v=addslashes($gather_module_valeDB[$key]);
				$SQL.=",`$key`='$v'";
			}
		}
		if($SQL){
			@extract($db->get_one("SELECT rid FROM {$pre}reply$erp ORDER BY rid DESC LIMIT 1 "));
			$db->query("INSERT INTO `{$pre}article_content_$fidDB[fmid]` SET aid='$aid',rid='$rid',fid='$fid',uid='$userdb[uid]'$SQL ");
		}
	}
}
?>