<?php
require(dirname(__FILE__)."/"."global.php");
@include(PHP168_PATH."php168/guideSP_fid.php");		//ר����Ŀ�����ļ�

if(!is_writable(PHP168_PATH."cache/makelist.php"))
{
	showerr("cache/makelist.php�ļ�������,���ļ�����д");
}

$GuideFid[$fid]=str_replace("list.php?fid=","listsp.php?fid=",$GuideFid[$fid]);

//��Ŀ�����ļ�
$fidDB=$db->get_one("SELECT * FROM {$pre}spsort WHERE fid='$fid'");
if(!$fidDB){
	showerr("��Ŀ����");
}
$fidDB[config]=unserialize($fidDB[config]);

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
$showpage=getpage("{$pre}special","WHERE fid=$fid","listsp.php?fid=$fid",$rows);	//ר���б��ҳ

require(PHP168_PATH."inc/head.php");
require(html("listsp",$FidTpl['list']));
require(PHP168_PATH."inc/foot.php");

$content=ob_get_contents();ob_end_clean();
$content=preg_replace("/<!--php168(.*?)php168-->/is","\\1",$content);
make_html($content,'listsp');


require_once(PHP168_PATH."cache/makelist.php");
$page++;
$min=($page-1)*$rows;
if($db->get_one("SELECT * FROM {$pre}special WHERE fid='$fid' LIMIT $min,1")){
	write_file(PHP168_PATH."cache/makelist_record.php","listsp_html.php?fid=$fid&page=$page&III=$III");
	echo "���Ժ�,��������ר���б�ҳ��̬...$Ppage<META HTTP-EQUIV=REFRESH CONTENT='0;URL=?fid=$fid&page=$page&III=$III'>";
	exit;
}else{
	$III++;
	$page=1;
	$fiddb=explode(",", $allfid);
	if($fid=$fiddb[$III]){
		write_file(PHP168_PATH."cache/makelist_record.php","?fid=$fid&page=$page&III=$III");
		echo '<meta http-equiv="Content-Type" content="text/html; charset=gb2312" />';
		echo "���Ժ�,��������ר���б�ҳ��̬...$fid<META HTTP-EQUIV=REFRESH CONTENT='0;URL=?fid=$fid&page=$page&III=$III'>";
		exit;
	}else{
		unlink(PHP168_PATH."cache/makelist_record.php");
		unlink(PHP168_PATH."cache/makelist.php");
		echo '<meta http-equiv="Content-Type" content="text/html; charset=gb2312" />';
		echo "ר���б�̬ҳ�������,������������ҳ<META HTTP-EQUIV=REFRESH CONTENT='0;URL=$weburl'>";
		exit;
	}
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
	$SQL="WHERE fid=$fid ORDER BY $ORDER $DESC LIMIT $min,$rows";
	$which='*';
	$listdb=list_special($SQL,$which,$leng);
	return $listdb;
}
?>