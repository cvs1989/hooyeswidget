<?php
require(dirname(__FILE__)."/"."global.php");
@include(PHP168_PATH."php168/guideSP_fid.php");		//ר����Ŀ�����ļ�

if(!$fid&&$webdb[NewsMakeHtml]==2){
	//α��̬����
	Explain_HtmlUrl();
}

$GuideFid[$fid]=str_replace("list.php?fid=","listsp.php?fid=",$GuideFid[$fid]);

//��Ŀ�����ļ�
$fidDB=$db->get_one("SELECT * FROM {$pre}spsort WHERE fid='$fid'");
if(!$fidDB){
	showerr("��Ŀ����");
}
$fidDB[config]=unserialize($fidDB[config]);

//ǿ����ת����̬ҳ
if($webdb[ForbidShowPhpPage]&&!$NeedCheck){
	$detail=get_SPhtml_url($fidDB);
	if(is_file(PHP168_PATH.$detail[listurl])){
		header("location:$detail[listurl]");
		exit;
	}
}

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


//��ʾ�ӷ���
$listdb_moresort=ListMoreSp();

//�б�ҳ����ƪר��
$rows=15;	

$listdb=ListThisSp($rows,$leng=50);		//����Ŀר���б�
$showpage=getpage("{$pre}special","WHERE fid=$fid AND yz=1","listsp.php?fid=$fid",$rows);	//ר���б��ҳ

require(PHP168_PATH."inc/head.php");
require(html("listsp",$FidTpl['list']));
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

/**
*����Ŀ
**/
function ListMoreSp(){
	global $db,$pre,$fid,$webdb,$fidDB;
	$order='list';
	$order && $_order=" ORDER BY $order DESC ";
	$rows=4;
	$leng=30;
	$query=$db->query("SELECT * FROM {$pre}spsort WHERE fup=$fid ORDER BY list DESC");
	while($rs=$db->fetch_array($query)){
		$SQL="WHERE fid=$rs[fid] $_order LIMIT $rows";
		$which='*';
		$rs[article]=list_special($SQL,$which,$leng);
		//�������Ŀ���ܻ�ȡ��ר��,����ȡ����������Ŀ��ר��
		if(!$rs[article])
		{
			$array_fid=Get_SonFid("{$pre}spsort",$rs[fid]);
			if($array_fid)
			{
				$SQL="WHERE fid IN (".implode(',',$array_fid).") $_order LIMIT $rows";
				$rs[article]=list_special($SQL,$which,$leng);
			}
		}
		$rs[logo] && $rs[logo]=tempdir($rs[logo]);
		$listdb[]=$rs;
	}
	return $listdb;
}



function ListThisSp($rows,$leng=50){
	global $page,$fid,$fidDB,$webdb;
	if($page<1){
		$page=1;
	}
	$min=($page-1)*$rows;
	$DESC='DESC';
	$ORDER='list';
	$SQL="WHERE fid=$fid AND yz=1 ORDER BY $ORDER $DESC LIMIT $min,$rows";
	$which='*';
	$listdb=list_special($SQL,$which,$leng);
	return $listdb;
}
?>