<?php
require_once(dirname(__FILE__)."/"."global.php");
$page<1 && $page=1;

if(!$fid&&$webdb[NewsMakeHtml]==2){
	//α��̬����
	//Explain_HtmlUrl();
}

$Cache_FileName=PHP168_PATH."cache/fulist_cache/{$fid}_{$page}.php";
if(!$jobs&&$webdb[fulist_cache_time]&&(time()-filemtime($Cache_FileName))<($webdb[fulist_cache_time]*60)){
	echo read_file($Cache_FileName);
	exit;
}
@include_once(PHP168_PATH."php168/fu_all_fid.php");
@include(PHP168_PATH."php168/fu_guide_fid.php");	//��Ŀ����

$GuideFid[$fid]=str_replace("list.php?","fulist.php?",$GuideFid[$fid]);

if(!$fid){
	showerr("��ĿFID������");
}

//��Ŀ�����ļ�
$fidDB=$db->get_one("SELECT S.*,M.alias AS M_alias,M.config AS M_config,M.iftable FROM {$pre}fu_sort S LEFT JOIN {$pre}article_module M ON S.fmid=M.id WHERE S.fid='$fid'");
if(!$fidDB){
	showerr("��ĿID����");
}
$fidDB[M_alias]='����';
$fidDB[M_config]=unserialize($fidDB[M_config]);
$fidDB[config]=unserialize($fidDB[config]);
$fidDB[descrip]=En_TruePath($fidDB[descrip],0);
if($fidDB[type]==2){
	$rsdb[content]=$fidDB[descrip];
}

$fupId=intval($fidDB[type]?$fid:$fidDB[fup]);

/*
//��ֹ���ʶ�̬ҳ
if($webdb[ForbidShowPhpPage]&&!$NeedCheck&&!$jobs){
	if($webdb[NewsMakeHtml]==2&&ereg("=[0-9]+$",$WEBURL)){		//α��̬
		eval("\$url=\"$webdb[list_filename2]\";");
		header("location:$webdb[www_url]/$url");
		exit;
	}elseif($webdb[NewsMakeHtml]==1){							//�澲̬
		$detail=get_html_url();
		if(is_file(PHP168_PATH.$detail[_listurl])){
			header("location:$detail[listurl]");
			exit;
		}
	}
}
*/

/**
*��Ŀ�����ļ����
**/
check_fid($fidDB);

//SEO
$titleDB[title]			= filtrate("$fidDB[name] - $webdb[webname]");
$titleDB[keywords]		= filtrate("$fidDB[metakeywords]  $webdb[metakeywords]");
$titleDB[description]	= filtrate("$fidDB[descrip]");

//����Ŀ���Ϊ��׼
$fidDB[style] && $STYLE=$fidDB[style];

/*ģ��*/
$FidTpl=unserialize($fidDB[template]);
$head_tpl=$FidTpl['head'];
$foot_tpl=$FidTpl['foot'];

/**
*Ϊ��ȡ��ǩ����
**/
$chdb[main_tpl]=html("fulist",$FidTpl['list']);

/**
*��ǩ
**/
$ch_fid	= intval($fidDB[config][label_list]);		//�Ƿ�������Ŀר�ñ�ǩ
$ch_pagetype = 12;									//2,Ϊlistҳ,3,Ϊbencandyҳ
$ch_module = 0;										//����ģ��,Ĭ��Ϊ0
$ch = 0;											//�������κ�ר��
require(PHP168_PATH."inc/label_module.php");

//��ʾ�ӷ���
$listdb_moresort=fuListMoreSort();

//�б�ҳ����ƪ����,��Ŀ���õĻ�.����ĿΪ��׼,������ϵͳΪ��׼,ϵͳ�����ھ�Ĭ��20
$rows=$fidDB[maxperpage]?$fidDB[maxperpage]:($webdb[list_row]?$webdb[list_row]:20);	

$listdb=fuListThisSort($rows,$webdb[ListLeng]?$webdb[ListLeng]:50);		//����Ŀ�����б�
$page_sql=$webdb[viewNoPassArticle]?'':' AND yz=1 ';

$erp='';
$showpage=getpage("{$pre}article$erp A LEFT JOIN {$pre}fu_article F ON A.aid=F.aid","WHERE F.fid=$fid $page_sql","fulist.php?fid=$fid",$rows);	//�����б��ҳ

//�����Ŀ����ģ��
if(is_file(html("fu_$webdb[SideSortStyle]"))){
	$sortnameTPL=html("fu_$webdb[SideSortStyle]");
}else{
	$sortnameTPL=html("fu_side_sort/0");
}

//��Ŀ����ģ��
$aboutsortTPL=html("aboutsort_tpl/0");

//�������ʾ��ʽ
$fidDB[config][ListShowBigType] || $fidDB[config][ListShowBigType]=0;
$bigsortTPL=html("fu_bigsort_tpl/0",PHP168_PATH."template/default/{$fidDB[config][ListShowBigType]}.htm");



//�����б���ʾ��ʽ.
$fidDB[config][ListShowType] || $fidDB[config][ListShowType]=0;
unset($listTPL);
if($fidDB[fmid]&&!$fidDB[config][ListShowType]){
	$listTPL=html("list_tpl/mod_$fidDB[fmid]");
}

if(!$listTPL){
	$listTPL=html("list_tpl/0",PHP168_PATH."template/default/{$fidDB[config][ListShowType]}.htm");
}

//��ģ����չ�ӿ�
@include(PHP168_PATH."inc/list_{$fidDB[fmid]}.php");

require(PHP168_PATH."inc/head.php");
require(html("fulist",$FidTpl['list']));
require(PHP168_PATH."inc/foot.php");

//α��̬����
if($webdb[NewsMakeHtml]==2)
{
	$content=ob_get_contents();
	ob_end_clean();
	ob_start();
	$content=fake_html($content);
	echo "$content";
}
else
{
	$content=ob_get_contents();
	ob_end_clean();
	ob_start();
	$content=preg_replace("/ href=('|\"|)bencandy\.php\?fid=([\d]+)&(id|aid)=([\d]+)/is"," target=_blank  href=\\1$webdb[www_url]/bencandy.php?fid=\\2&aid=\\4",$content);
	echo "$content";
}


if(!$jobs&&$webdb[list_cache_time]&&(time()-filemtime($Cache_FileName))>($webdb[list_cache_time]*60)){
	
	if(!is_dir(dirname($Cache_FileName))){
		makepath(dirname($Cache_FileName));
	}
	write_file($Cache_FileName,$content);
}elseif($jobs=='show'){
	@unlink($Cache_FileName);
}


/**
*��Ŀ�����ļ����
**/
function check_fid($fidDB){
	global $web_admin,$groupdb,$fid;
	if(!$fidDB)
	{
		showerr("��Ŀ������");
	}

	//��ת���ⲿ��ַ
	if( $fidDB[jumpurl] )
	{
		header("location:$fidDB[jumpurl]");
		exit;
	}

	//��Ŀ����
	if( $fidDB[passwd] )
	{
		if( $_POST[password] )
		{
			if( $_POST[password] != $fidDB[passwd] )
			{
				echo "<A HREF=\"?fid=$fid\">���벻��ȷ,�������</A>";
				exit;
			}
			else
			{
				setcookie("sort_passwd_$fid",$fidDB[passwd]);
				$_COOKIE["sort_passwd_$fid"]=$fidDB[passwd];
			}
		}
		if( $_COOKIE["sort_passwd_$fidDB[fid]"] != $fidDB[passwd] )
		{
			echo "<CENTER><form name=\"form1\" method=\"post\" action=\"\">��������Ŀ����:<input type=\"password\" 	name=\"password\"><input type=\"submit\" name=\"Submit\" value=\"�ύ\"></form></CENTER>";
			exit;
		}
	}

	if( $fidDB[allowviewtitle] || $fidDB[allowviewcontent] )
	{
		if(!$web_admin&&!in_array($groupdb[gid],explode(",","$fidDB[allowviewtitle],$fidDB[allowviewcontent]")))
		{
			showerr("�������û��鲻�����������");
		}
	}
}


function fuListMoreSort(){
	global $db,$pre,$fid,$webdb,$fidDB,$Fid_db;
	//����
	if($fidDB[config][sonListorder]==1){
		$order='A.list';
	}elseif($fidDB[config][sonListorder]==2){
		$order='A.hits';
	}elseif($fidDB[config][sonListorder]==3){
		$order='A.lastview';
	}elseif($fidDB[config][sonListorder]==4){
		$order='rand()';
	}else{
		$order='A.list';
	}
	$_order=" ORDER BY $order DESC ";

	//��ʾ����
	if($fidDB[config][sonTitleRow]>0){
		$rows=$fidDB[config][sonTitleRow];
	}elseif($webdb[ListSonRows]>0){
		$rows=$webdb[ListSonRows];
	}else{
		$rows=10;
	}

	//ÿ��������ʾ������
	if($fidDB[config][sonTitleLeng]>0){
		$leng=$fidDB[config][sonTitleLeng];
	}elseif($webdb[ListSonLeng]>0){
		$leng=$webdb[ListSonLeng];
	}else{
		$leng=30;
	}

	if(!$webdb[viewNoPassArticle]){
		$SQL_yz=' AND A.yz=1 ';
	}
	$query=$db->query("SELECT * FROM {$pre}fu_sort WHERE fup=$fid AND forbidshow!=1 ORDER BY list DESC");
	while($rs=$db->fetch_array($query)){
		$erp='';
		$SQL="A LEFT JOIN {$pre}fu_article F ON A.aid=F.aid LEFT JOIN {$pre}reply$erp R ON F.aid=R.aid WHERE F.fid=$rs[fid] AND R.topic=1 $SQL_yz $_order LIMIT $rows";
		$which='A.*,R.content';
		$rs[article]=list_article($SQL,$which,$leng,$erp);

		//�������Ŀ���ܻ�ȡ������,����ȡ����������Ŀ������
		if(!$rs[article])
		{
			$array_fid=Get_SonFid("{$pre}sort",$rs[fid]);
			if($array_fid)
			{
				//�ֱ��,�������Ŀ����ͬһģ�͵Ļ�.�����ܻ�ȡ����
				$SQL="A LEFT JOIN {$pre}fu_article F ON A.aid=F.aid LEFT JOIN {$pre}reply$erp R ON F.aid=R.aid WHERE R.topic=1 AND F.fid IN (".implode(',',$array_fid).") $SQL_yz $_order LIMIT $rows";
				$rs[article]=list_article($SQL,$which,$leng,$erp);
			}
		}
		$rs[logo] && $rs[logo]=tempdir($rs[logo]);
		$listdb[]=$rs;
	}
	return $listdb;
}


function fuListThisSort($rows,$leng=50){
	global $page,$fid,$fidDB,$webdb,$pre,$Fid_db;
	if($page<1){
		$page=1;
	}
	$min=($page-1)*$rows;
	if($fidDB[listorder]==1){
		$DESC='DESC';
		$ORDER='A.posttime';
	}elseif($fidDB[listorder]==2){
		$DESC='ASC';
		$ORDER='A.posttime';
	}elseif($fidDB[listorder]==3){
		$DESC='DESC';
		$ORDER='A.hits';
	}elseif($fidDB[listorder]==4){
		$DESC='ASC';
		$ORDER='A.hits';
	}elseif($fidDB[listorder]==5){
		$DESC='DESC';
		$ORDER='A.lastview';
	}elseif($fidDB[listorder]==7){
		$DESC='DESC';
		$ORDER='A.digg_num';
	}elseif($fidDB[listorder]==8){
		$DESC='DESC';
		$ORDER='A.digg_time';
	}elseif($fidDB[listorder]==6){
		$DESC='DESC';
		$ORDER='rand()';
	}else{
		$DESC='DESC';
		$ORDER='A.list';
	}
	if(!$webdb[viewNoPassArticle]){
		$SQL_yz=' AND A.yz=1 ';
	}
	if($fid){
		$_fid_sql=" AND F.fid=$fid ";
	}else{
		$_fid_sql=" AND 1 ";
	}
	$erp="";

	$SQL="A LEFT JOIN {$pre}fu_article F ON A.aid=F.aid LEFT JOIN {$pre}reply$erp R ON F.aid=R.aid WHERE R.topic=1 $_fid_sql $SQL_yz ORDER BY $ORDER $DESC LIMIT $min,$rows";
	$which='A.*,R.content';
	$listdb=list_article($SQL,$which,$leng,$erp);
	return $listdb;
}

?>