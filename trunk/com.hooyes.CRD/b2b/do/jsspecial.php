<?php
require_once(dirname(__FILE__)."/".'global.php');

$fid=intval($fid);
$rows>0 || $rows=7;
$leng>0 || $leng=60;

unset($SQL,$show);

//����ר��,�Ƽ�ר��,����ר��,û��ʹ�û���

if($type=='hot'||$type=='com'||$type=='new'||$type=='lastview')
{
	if($fid)
	{
		$f_id=get_fid($fid);
		$SQL=" yz=1 AND (".implode("OR",$f_id).") ";
	}
	else
	{
		$SQL=" yz=1 ";
	}

	if($type=='com')
	{
		$SQL.=" AND levels=1 ";
		$ORDER=' list ';
	}
	elseif($type=='hot')
	{
		$ORDER=' hits ';
	}
	elseif($type=='new')
	{
		$ORDER=' list ';
	}
	elseif($type=='lastview')
	{
		$ORDER=' lastview ';
	}

	$SQL=" WHERE $SQL ORDER BY $ORDER DESC LIMIT $rows";
	$which='*';
	$listdb=list_special($SQL,$which,$leng);

	foreach($listdb AS $key=>$rs)
	{
		$target=$rs[target]?'_blank':'_self';
		$show.="<div>��<A target='$target' HREF='$webdb[www_url]/do/showsp.php?fid=$rs[fid]&id=$rs[id]' title='$rs[full_title]'>$rs[title]</A></div>";
	}
	if(!$show){
		$show="����...";
	}

	//�澲̬
	if($webdb[NewsMakeHtml]==1||$gethtmlurl)
	{
		$show=make_html($show,$pagetype='N');
	}
	//α��̬
	elseif($webdb[NewsMakeHtml]==2)
	{
		$show=fake_html($show);
	}
	
	$show=str_replace(array("\n","\r","'"),array("","","\'"),$show);

	if($iframeID){	//��ܷ�ʽ����������ҳ����ٶ�,�Ƽ�
		//�����������
		if($webdb[cookieDomain]){
			echo "<SCRIPT LANGUAGE=\"JavaScript\">document.domain = \"$webdb[cookieDomain]\";</SCRIPT>";
		}
		echo "<SCRIPT LANGUAGE=\"JavaScript\">
		parent.document.getElementById('$iframeID').innerHTML='$show';
		</SCRIPT>";
	}else{			//JSʽ��������ҳ����ٶ�,���Ƽ�
		echo "document.write('$show');";
	}
	exit;
}
elseif($type=='sonfid')
{
	$fid && $rs=$db->get_one("SELECT fup FROM {$pre}spsort WHERE fid='$fid'");
	$show=get_fidName($rs[fup],$rows,$class?$class:3);
	if(!$show){
		$show="����...";
	}
	//�澲̬
	if($webdb[NewsMakeHtml]==1||$gethtmlurl)
	{
		$show=make_html($show,$pagetype='N');
	}
	//α��̬
	elseif($webdb[NewsMakeHtml]==2)
	{
		$show=fake_html($show);
	}
	$show="<ul>$show</ul>";
	$show=str_Replace("'",'"',$show);
	$show=str_Replace("\r",'',$show);
	$show=str_Replace("\n",'',$show);
	$show="document.write('$show');";
	echo $show;
}
else
{
	die("document.write('ָ�������Ͳ�����');");
}


function get_fid($fid){
	global $db,$pre;
	$fid=intval($fid);
	$F[]=" fid=$fid ";
	$query = $db->query("SELECT fid FROM {$pre}spsort WHERE fup='$fid'");
	while($rs = $db->fetch_array($query)){
		$F[]=" fid=$rs[fid] ";
	}
	return $F;
}


//$fid,��ȡ��FID������Ŀ,$rows=15,ֻ��ȡ���ٸ�����Ŀ,$class=2,��ʾ���ټ�����Ŀ
function get_fidName($fid,$rows=15,$class=2,$_Class=0){
	global $db,$pre,$webdb;
	if( !$class ){
		return '';
	}
	$_Class++;
	$class--;
	$query = $db->query("SELECT fid,name,fup,sons FROM {$pre}spsort WHERE fup='$fid' ORDER BY list DESC LIMIT $rows");
	while($rs = $db->fetch_array($query))
	{
		$icon='';
		for($i=1;$i<$_Class;$i++){
			$icon.='&nbsp;&nbsp;';
		}
		if($rs[sons])
		{
			if($class==0){
				$icon.="<A>+</A>";
			}else{
				$icon.="<A onclick=showSonName($rs[fid]) style=cursor:hand>+</A>";
			}
		}
		else
		{
			$icon.='<a>&nbsp;</a>';
		}
		$display=$_Class==1?'':'none';

		$show.="<div style=display:$display class=SonName$rs[fup]>{$icon}��<A HREF='$webdb[www_url]/list.php?fid=$rs[fid]'>{$rs[name]}</A>��</div>";
		if($rs[sons])
		{
			$show.=get_fidName($rs[fid],$rows,$class,$_Class);
		}
	}
	return $show;
}
?>