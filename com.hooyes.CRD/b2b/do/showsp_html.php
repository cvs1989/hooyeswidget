<?php
require(dirname(__FILE__)."/"."global.php");
@include(PHP168_PATH."php168/guideSP_fid.php");		//ר����Ŀ�����ļ�

if(!is_writable(PHP168_PATH."cache/makeShow1.php")){
	showerr("/cache/makeShow1.php�ļ�������,���ļ�����д");
}


//������
$GuideFid[$fid]=str_replace("list.php?fid=","listsp.php?fid=",$GuideFid[$fid]);
if(!$GuideFid[$fid]){
	$GuideFid[$fid]="<A HREF='$webdb[www_url]'>&gt;&gt;������ҳ</A>";
}


$rsdb=$db->get_one("SELECT * FROM {$pre}special WHERE id='$id'");
$fid=$rsdb[fid];
if(!$rsdb){
	showerr("���ݲ�����!");
}


//��ǩ
$FidTpl=unserialize($rsdb[template]);

$chdb[main_tpl]=html("showsp",$FidTpl[bencandy]);			//��ȡ��ǩ����

$ch_fid	= intval($id);								//ÿ��ר��ı�ǩ��һ��
$ch_pagetype = 11;									//2,Ϊlistҳ,3,Ϊbencandyҳ
$ch_module = 0;										//����ģ��,Ĭ��Ϊ0
$ch = 0;											//�������κ�ר��

require(PHP168_PATH."inc/label_module.php");

unset($_listdb,$_picdb,$listdb,$picdb);
//ר���������
if($rsdb[aids])
{
	$query = $db->query("SELECT A.*,D.aid FROM {$pre}article_db D LEFT JOIN {$pre}article A ON D.aid=A.aid WHERE D.aid IN ($rsdb[aids])");
	while($rs = $db->fetch_array($query)){
		if(!$rs[title]&&$_rs=get_one_article($rs[aid])){
			$rs=$_rs+$rs;
		}
		$rs[url]="bencandy.php?fid=$rs[fid]&id=$rs[aid]";
		$rs[subject]="<a href='$rs[url]' target=_blank>$rs[title]</a>";
		$_listdb[$rs[aid]]=$rs;
	}
	//ͼƬ����
	$query = $db->query("SELECT * FROM {$pre}article WHERE aid IN ($rsdb[aids]) AND ispic=1");
	while($rs = $db->fetch_array($query)){
		$rs[url]="bencandy.php?fid=$rs[fid]&id=$rs[aid]";
		$rs[subject]="<a href='$rs[url]' target=_blank>$rs[title]</a>";
		$rs[picurl]=tempdir($rs[picurl]);
		$_picdb[$rs[aid]]=$rs;
	}
	$aidsdb=explode(",",$rsdb[aids]);
	foreach($aidsdb AS $key=>$value){
		if($_listdb[$value]){
			$listdb[]=$_listdb[$value];
		}
		if($_picdb[$value]){
			$picdb[]=$_picdb[$value];
		}
	}
}

if($rsdb[tids])
{
	unset($_listdb,$_picdb);
	if(ereg("^pwbbs",$webdb[passport_type]))
	{
		$query = $db->query("SELECT * FROM {$TB_pre}threads WHERE tid IN ($rsdb[tids])");
		while($rs = $db->fetch_array($query)){
			$rs[url]="$webdb[passport_url]/read.php?tid=$rs[tid]";
			$rs[title]=$rs[subject];
			$rs[subject]="<a href='$rs[url]' target=_blank>$rs[subject]</a>";
			$_listdb[$rs[tid]]=$rs;
		}
		//ͼƬ����
		$query = $db->query("SELECT A.*,Att.attachurl FROM {$TB_pre}attachs Att LEFT JOIN {$TB_pre}threads A ON Att.tid=A.tid WHERE A.tid IN ($rsdb[tids]) AND Att.type='img' GROUP BY tid");
		while($rs = $db->fetch_array($query)){
			$rs[url]="$webdb[passport_url]/read.php?tid=$rs[tid]";
			$rs[title]=$rs[subject];
			$rs[picurl]=tempdir($rs[attachurl],'pwbbs');
			$rs[subject]="<a href='$rs[url]' target=_blank>$rs[subject]</a>";
			$_picdb[$rs[tid]]=$rs;
		}
	}
	elseif(ereg("^dzbbs",$webdb[passport_type]))
	{
		$query = $db->query("SELECT * FROM {$TB_pre}threads WHERE tid IN ($rsdb[tids])");
		while($rs = $db->fetch_array($query)){
			$rs[url]="$webdb[passport_url]/viewthread.php?tid=$rs[tid]";
			$rs[title]=$rs[subject];
			$rs[subject]="<a href='$rs[url]' target='_blank'>$rs[subject]</a>";
			$_listdb[$rs[tid]]=$rs;
		}
		//ͼƬ����
		$query = $db->query("SELECT A.*,Att.attachment FROM {$TB_pre}attachments Att LEFT JOIN {$TB_pre}threads A ON Att.tid=A.tid WHERE A.tid IN ($rsdb[tids]) AND Att.isimage='1' GROUP BY Att.tid");
		while($rs = $db->fetch_array($query)){
			$rs[url]="$webdb[passport_url]/viewthread.php?tid=$rs[tid]";
			$rs[title]=$rs[subject];
			$rs[picurl]=tempdir($rs[attachment],'pwbbs');
			$rs[subject]="<a href='$rs[url]' target='_blank'>$rs[subject]</a>";
			$_picdb[$rs[tid]]=$rs;
		}
	}
	$tidsdb=explode(",",$rsdb[tids]);
	foreach($tidsdb AS $key=>$value){
		if($_listdb[$value]){
			$listdb[]=$_listdb[$value];
		}
		if($_picdb[$value]){
			$picdb[]=$_picdb[$value];
		}
	}
}

if(!$listdb){
	$listdb[]=array("subject"=>"��ר����������,�����Ա�ں�̨�������");
}

//ͳ�Ƶ������
$db->query("UPDATE {$pre}special SET hits=hits+1,lastview='$timestamp' WHERE id='$id'");

/**
*��Ŀ�����ļ�
**/
$fidDB=$db->get_one("SELECT * FROM {$pre}spsort WHERE fid='$fid'");
$fidDB[config]=unserialize($fidDB[config]);
$FidTpl=unserialize($fidDB[template]);

//SEO
$titleDB[title]			= filtrate(strip_tags("$rsdb[title] - $fidDB[name] - $webdb[webname]"));
$titleDB[keywords]		= filtrate("$rsdb[keywords] $webdb[metakeywords]");
$rsdb[description]		= get_word(preg_replace("/(<([^<]+)>|	|&nbsp;|\n)/is","",$rsdb[content]),250);
$titleDB[description]	= filtrate($rsdb[description]);

//���
$STYLE = $rsdb[style] ? $rsdb[style] : ($fidDB[style] ? $fidDB[style] : $STYLE);

/**
*ģ��ѡ��
**/
$showTpl=unserialize($rsdb[template]);
$head_tpl=$showTpl[head]?$showTpl[head]:$FidTpl['head'];
$main_tpl=$showTpl[bencandy]?$showTpl[bencandy]:$FidTpl['bencandy'];
$foot_tpl=$showTpl[foot]?$showTpl[foot]:$FidTpl['foot'];


//������ʵ��ַ��ԭ
$rsdb[content] = En_TruePath($rsdb[content],0);


$rsdb[posttime] = date("Y-m-d H:i:s",$rsdb[posttime]);
$rsdb[picurl] && $rsdb[picurl] = tempdir($rsdb[picurl]);


if(!$rsdb[yz]){
	$showsp="<META HTTP-EQUIV=REFRESH CONTENT='0;URL=$webdb[www_url]/do/showsp.php?fid=$fid&id=$id'>";
}else{
	$showsp="";
}

//require(PHP168_PATH."inc/head.php");
require(html("showsp",$main_tpl));
//require(PHP168_PATH."inc/foot.php");

$content=ob_get_contents();ob_end_clean();
$content=preg_replace("/<!--php168(.*?)php168-->/is","\\1",$content);
make_html($showsp?$showsp:$content,'showsp');


unset($iddb,$fiddb);
require_once(PHP168_PATH."cache/makeShow1.php");


if($string=$iddb[++$II]){
	$ar=explode("-",$string);
	write_file(PHP168_PATH."cache/makeShow_record.php","?fid=$ar[0]&id=$ar[1]&II=$II");
	echo '<meta http-equiv="Content-Type" content="text/html; charset=gb2312" />';
	echo "���Ժ�,��������ר������ҳ��̬...<META HTTP-EQUIV=REFRESH CONTENT='0;URL=?fid=$ar[0]&id=$ar[1]&II=$II'>";
	exit;
}else{
	unlink(PHP168_PATH."cache/makeShow1.php");
	unlink(PHP168_PATH."cache/makeShow_record.php");
	if(count($iddb)==1){
		$detail=get_SPhtml_url($fidDB,$id,$rsdb[posttime]);
		header("location:$detail[showurl]");exit;
	}
	echo '<meta http-equiv="Content-Type" content="text/html; charset=gb2312" />';
	echo "<A HREF='$weburl'>ר�⾲̬ҳ�������,��������</A>";
	exit;
}
?>