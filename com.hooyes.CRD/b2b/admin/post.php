<?php
!function_exists('html') && exit('ERR');
require_once(PHP168_PATH."inc/artic_function.php");
$Guidedb->only=$only;
$Guidedb->mid=$mid;
$Guidedb->ifpost=1;
$Guidedb->forbidpost=1;

if(!$aid&&!$rid){
	$aid=$id;
}
if($rid)
{
	if(!$aid){
		showerr("aid������!");
	}
	$erp=get_id_table($aid);
	//�޸�������޸Ķ�ҳ����
	$rsdb=$db->get_one("SELECT R.*,A.* FROM {$pre}article$erp A LEFT JOIN {$pre}reply$erp R ON A.aid=R.aid WHERE R.rid='$rid'");
	$aid=$rsdb[aid];
	$fid=$rsdb[fid];
	$mid=$rsdb[mid];
}
elseif($aid)
{
	$erp=get_id_table($aid);
	//ֻ�����޸�����/��������
	$rsdb=$db->get_one("SELECT R.*,A.* FROM {$pre}article$erp A LEFT JOIN {$pre}reply$erp R ON A.aid=R.aid WHERE A.aid='$aid' ORDER BY R.rid ASC LIMIT 1");
	isset($fid) || $fid=$rsdb[fid];
	$mid=$rsdb[mid];
}

if($only&&$mid===''){
	$listdb[]=array('id'=>0,'name'=>'����ģ��');
	$query = $db->query("SELECT * FROM {$pre}article_module ORDER BY list DESC");
	while($rs = $db->fetch_array($query)){
		$listdb[]=$rs;
	}	
	foreach( $listdb AS $key=>$rs){
		$erp=$rs[iftable]?$rs[iftable]:"";
		@extract($db->get_one("SELECT COUNT(*) AS NUM FROM {$pre}article$erp WHERE mid='$rs[id]'"));
		$rs[NUM]=intval($NUM);
		$listdb[$key]=$rs;
	}
	require(dirname(__FILE__)."/"."head.php");
	require(dirname(__FILE__)."/"."template/post/choose_sort.htm");
	require(dirname(__FILE__)."/"."foot.php");
	exit;
}
//���û�ѡ����Ŀ
if(!$fid&&!$only)
{
	$sortdb=array();
	list_post_allsort();
	$MSG="��������,��ѡ��һ����Ŀ";
	require(dirname(__FILE__)."/"."head.php");
	require(dirname(__FILE__)."/"."template/post/post_set.htm");
	require(dirname(__FILE__)."/"."foot.php");
	exit;
}

if($fid||$step){
	$fidDB=$db->get_one("SELECT * FROM {$pre}sort WHERE fid='$fid'");
	!$fidDB && showerr("��Ŀ����");
}
$job=='postnew' && !$mid && $mid=$fidDB[fmid];

if(in_array($lfjid,explode(',',$fidDB[admin]))||$userdb[groupid]==3||$userdb[groupid]==4)
{
	$web_admin=1;
}
if($fidDB&&!$web_admin&&!in_array($groupdb[gid],explode(',',$fidDB[allowpost])))
{
	showmsg("�������û�����Ȩ�ڱ���Ŀ��{$fidDB[name]}�����κβ���");
}

if(!$lfjid&&$job!='postnew')
{
	showmsg("�ο���Ȩ����");
}

$atc_power=0;
if($lfjid)
{
	if($web_admin||$lfjuid==$rsdb[uid]){
		$atc_power=1;
	}
}
$uid=isset($rsdb[uid])?$rsdb[uid]:$lfjuid;

if($job=='endHTML')
{
	$htmlurldb=get_html_url();
	//��ҳ���ɾ�̬
	@unlink(PHP168_PATH."index.htm.bak");
	rename(PHP168_PATH."index.htm",PHP168_PATH."index.htm.bak");
	refreshto("index.php?lfj=artic&job=listartic&mid=$mid&only=$only","<CENTER>[<A HREF='?lfj=$lfj&job=postnew&fid=$fid&mid=$mid&only=$only'>����������</A>] [<A HREF='?lfj=$lfj&job=post_more&aid=$aid&mid=$mid&only=$only'>����������</A>] [<A HREF='index.php?lfj=artic&job=listartic&fid=$fid&mid=$mid&only=$only'>���������б�</A>] [<A HREF='{$htmlurldb[showurl]}' target=_blank>�鿴����</A>] [<A HREF='?lfj=$lfj&job=manage&aid=$aid&mid=$mid&only=$only'>�޸�����</A>]</CENTER>",60);
}
elseif($job=='manage')
{
	if(!$atc_power)showmsg("��ûȨ��");
	if($rsdb[pages]<2){
		header("location:?lfj=$lfj&job=edit&aid=$aid&mid=$mid&only=$only");exit;
	}
	$erp=get_id_table($aid);
	if($step==2){
		asort($orderDB);
		$i=0;
		foreach( $orderDB AS $key=>$value){
			$i++;
			$db->query("UPDATE {$pre}reply$erp SET orderid=$i WHERE aid='$aid' AND rid='$key'");
		}
		refreshto("$FROMURL","����ɹ�",1);
	}
	if($rsdb[pages]>1){
		$MSG="�޸�����";
		$i=0;
		$query = $db->query("SELECT * FROM {$pre}reply$erp WHERE aid='$aid' ORDER BY topic DESC,orderid ASC");
		while($rs = $db->fetch_array($query)){
			if(!$rs[subhead]){
				$rs[subhead]=$rsdb[title];
			}
			$rs[postdate]='';
			if($rs[postdate]){
				$rs[postdate]=date("Y-m-d H:i:s",$rs[postdate]);
			}
			$rs[i]=++$i;
			$listdb[]=$rs;
		}
		require(dirname(__FILE__)."/"."head.php");
		require(dirname(__FILE__)."/"."template/post/post_set.htm");
		require(dirname(__FILE__)."/"."foot.php");
		exit;
	}
}
elseif($action=="delelte")
{
	if(!$atc_power)showmsg("��ûȨ��");
	//ɾ�����µĺ���
	delete_article($rsdb[aid],$rsdb[rid]);
	refreshto("$FROMURL","ɾ���ɹ�",1);
}

if($job=='edit'||$job=='post_more'||$job=='edit_more'){
	if(!$atc_power)showmsg("��ûȨ��");
}

//�Է���ǰ�뷢��ǰ������
require_once(PHP168_PATH."inc/check.postarticle.php");

if($job=='postnew'&&$Apower[artic_postnew])
{

	if($step=='post')
	{
		post_new();

		//���ɾ�̬
		make_article_html("$webdb[admin_url]/index.php?lfj=$lfj&job=endHTML&aid=$aid&mid=$mid&only=$only");

		$mid && $mid<106 && $none='none';

		refreshto("?lfj=$lfj&job=postnew&fid=$fid&mid=$mid&only=$only","<CENTER>[<A HREF='?lfj=$lfj&job=postnew&fid=$fid&mid=$mid&only=$only'>��������������</A>] <span style='display:$none;'>[<A HREF='?lfj=$lfj&job=post_more&fid=$fid&mid=$mid&aid=$aid&only=$only'>����������</A>]</span> [<A HREF='index.php?lfj=artic&job=listartic&fid=$fid&mid=$mid&only=$only'>���������б�</A>] [<A HREF='../bencandy.php?fid=$fid&aid=$aid' target=_blank>�鿴����</A>] [<A HREF='?lfj=$lfj&job=edit&aid=$aid&mid=$mid&only=$only'>����޸�</A>]</CENTER>",60);
	}
	$MSG='�·�������';
	//$select_fid=list_post_selectsort(0,$fid,$mid,$only);
	$select_fid=$Guidedb->SelectIn("{$pre}sort",0,$fid);
	require(dirname(__FILE__)."/"."head.php");
	if($mid&&file_exists(PHP168_PATH."php168/admin_tpl/post_$mid.htm")){
		require(PHP168_PATH."php168/admin_tpl/post_$mid.htm");
	}else{
		require(dirname(__FILE__)."/"."template/post/post.htm");
	}
	require(dirname(__FILE__)."/"."foot.php");
}
elseif($job=='edit'&&$Apower[artic_postnew])
{
	if($rsdb[yz]&&!$web_admin&&$groupdb[EditPassPower]==2){
		showerr("����˵�����,�㲻�����޸�");
	}
	if($step=='post')
	{
		post_edit();

		//���ɾ�̬
		make_article_html("$webdb[admin_url]/index.php?lfj=$lfj&job=endHTML&aid=$aid&mid=$mid&only=$only");

		$mid && $mid<106 && $none='none';

		refreshto("$FROMURL","<CENTER>[<A HREF='?lfj=$lfj&job=postnew&fid=$fid&mid=$mid&only=$only'>����������</A>] <span style='display:$none;'>[<A HREF='?lfj=$lfj&job=post_more&aid=$aid&mid=$mid&only=$only'>����������</A>]</span> [<A HREF='index.php?lfj=artic&job=listartic&fid=$fid&mid=$mid&only=$only'>���������б�</A>] [<A HREF='../bencandy.php?fid=$fid&aid=$aid' target=_blank>�鿴����</A>] [<A HREF='?lfj=$lfj&job=edit&aid=$aid&mid=$mid&only=$only'>�����޸�</A>]</CENTER>",60);
	}
	$MSG='�޸�����';
	//$select_fid=list_post_selectsort(0,$fid,$mid,$only);
	$select_fid=$Guidedb->SelectIn("{$pre}sort",0,$fid);
	require(dirname(__FILE__)."/"."head.php");
	if($mid&&file_exists(PHP168_PATH."php168/admin_tpl/post_$mid.htm")){
		require(PHP168_PATH."php168/admin_tpl/post_$mid.htm");
	}else{
		require(dirname(__FILE__)."/"."template/post/post.htm");
	}
	require(dirname(__FILE__)."/"."foot.php");
}
elseif($job=='post_more'&&$Apower[artic_postnew])
{
	if($step=='post')
	{
		//�������
		query_reply($aid,'','');

		//���ɾ�̬
		make_article_html("$webdb[admin_url]/index.php?lfj=$lfj&job=endHTML&aid=$aid&mid=$mid&only=$only");

		refreshto("$FROMURL","<CENTER>[<A HREF='?lfj=$lfj&job=postnew&fid=$fid&mid=$mid&only=$only'>����������</A>] [<A HREF='?lfj=$lfj&job=post_more&aid=$aid&mid=$mid&only=$only'>����������</A>] [<A HREF='index.php?lfj=artic&job=listartic&fid=$fid&mid=$mid&only=$only'>���������б�</A>] [<A HREF='../bencandy.php?fid=$fid&aid=$aid' target=_blank>�鿴����</A>] [<A HREF='?lfj=$lfj&job=manage&aid=$aid&mid=$mid&only=$only'>�޸�����</A>]</CENTER>",60);
	}
	$MSG='��������';
	unset($rsdb[content],$rsdb[subhead]);
	require(dirname(__FILE__)."/"."head.php");
	if($mid&&file_exists(PHP168_PATH."php168/admin_tpl/post_$mid.htm")){
		require(PHP168_PATH."php168/admin_tpl/post_$mid.htm");
	}else{
		require(dirname(__FILE__)."/"."template/post/post.htm");
	}
	require(dirname(__FILE__)."/"."foot.php");
}
elseif($job=='edit_more'&&$Apower[artic_postnew])
{
	if($step=='post')
	{
		//�޸�����
		query_reply($aid,$rid,'edit');

		//���ɾ�̬
		make_article_html("$webdb[admin_url]/index.php?lfj=$lfj&job=endHTML&aid=$aid&mid=$mid&only=$only");

		refreshto("$FROMURL","<CENTER>[<A HREF='?lfj=$lfj&job=postnew&fid=$fid&mid=$mid&only=$only'>����������</A>] [<A HREF='?lfj=$lfj&job=post_more&aid=$aid&mid=$mid&only=$only'>����������</A>] [<A HREF='index.php?lfj=artic&job=listartic&fid=$fid&mid=$mid&only=$only'>���������б�</A>] [<A HREF='../bencandy.php?fid=$fid&aid=$aid' target=_blank>�鿴����</A>] [<A HREF='?lfj=$lfj&job=edit_more&aid=$aid&rid=$rid&mid=$mid&only=$only'>�޸�����</A>]</CENTER>",60);
	}
	$MSG='�޸�����';
	require(dirname(__FILE__)."/"."head.php");
	if($mid&&file_exists(PHP168_PATH."php168/admin_tpl/post_$mid.htm")){
		require(PHP168_PATH."php168/admin_tpl/post_$mid.htm");
	}else{
		require(dirname(__FILE__)."/"."template/post/post.htm");
	}
	require(dirname(__FILE__)."/"."foot.php");
}

?>