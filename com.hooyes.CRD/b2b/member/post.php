<?php
require(dirname(__FILE__)."/"."global.php");


if($ctype){ //��CTYPE��˵����������Ϣ
	header("location:$webdb[www_url]/b/member/post.php?ctype=$ctype&fid=$fid&action=$action&id=$id");
}


require_once(PHP168_PATH."inc/artic_function.php");
require(PHP168_PATH."inc/class.inc.php");
$Guidedb=new Guide_DB;

unset($Article_Module);

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

//���û�ѡ����Ŀ
if((!$fid&&!$only)||$jobs=="choose")
{
	$sortdb=array();
	if( count($Fid_db[name])>100||$fid ){
		$rows=50;
		$page<1 && $page=1;
		$min=($page-1)*$rows;
		$showpage=getpage("{$pre}sort","WHERE fup='$fid'","?lfj=$lfj&job=$job&jobs=$jobs&only=$only&mid=$mid&fid=$fid",$rows);
		$query = $db->query("SELECT * FROM {$pre}sort WHERE fup='$fid' ORDER BY list DESC,fid ASC LIMIT $min,$rows");
		while($rs = $db->fetch_array($query)){
			$rs[post]=$rs[NUM]=$rs[do_art]='';
			$detail_admin=@explode(",",$rs[admin]);
			$detail_allowpost=@explode(",",$rs[allowpost]);
			if(!$rs[type]&&( $web_admin||($lfjid&&@in_array($lfjid,$detail_admin))||@in_array($groupdb['gid'],$detail_allowpost) ))
			{	
				$erp=$Fid_db[iftable][$rs[fid]];
				$_rs=$db->get_one("SELECT COUNT(*) AS NUM FROM {$pre}article$erp WHERE fid='$rs[fid]' AND uid='$lfjuid'");
				if($_rs[NUM]&&$lfjid){
					$rs[NUM]="( <b>{$_rs[NUM]}</b> )";
					$rs[do_art]="<A HREF='myarticle.php?job=myarticle&fid=$rs[fid]' class='manage_article'>����</A>";
				}
				$rs[post]="<A HREF='?job=postnew&fid=$rs[fid]' class='post_article'>����</A>";
				$allowpost++;
			}
			$sortdb[]=$rs;
		}
		if($fid){
			$show_guide="<A HREF='?lfj=$lfj&jobs=$jobs&job=$job&only=$only&mid=$mid'>���ض���Ŀ¼</A> ".list_sort_guide($fid);
		}
	}else{		
		list_post_allsort();
		if(!$allowpost){
			showerr("�������û�����Ȩ��������",1);
		}
	}
	$MSG="��ѡ��һ����ĿͶ��";
	require(dirname(__FILE__)."/"."head.php");
	require(dirname(__FILE__)."/"."template/post_set.htm");
	require(dirname(__FILE__)."/"."foot.php");
	exit;
}
if($fid||$step){
	$fidDB=$db->get_one("SELECT * FROM {$pre}sort WHERE fid='$fid'");
	!$fidDB && showerr("��Ŀ����");
}

$job=='postnew' && !$mid && $mid=$fidDB[fmid];

if($lfjid&&@in_array($lfjid,explode(',',$fidDB[admin])))
{
	$web_admin=1;
}
if($fidDB&&!$web_admin&&!in_array($groupdb[gid],explode(',',$fidDB[allowpost])))
{
	showerr("�������û�����Ȩ�ڱ���Ŀ��{$fidDB[name]}�����κβ���");
}

if(!$lfjid&&$job!='postnew')
{
	showerr("�ο���Ȩ����");
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
	refreshto("myarticle.php?job=myarticle&mid=$mid&only=$only","<CENTER>[<A HREF='?job=postnew&fid=$fid&mid=$mid&only=$only'>����������</A>] [<A HREF='?job=post_more&aid=$aid&mid=$mid&only=$only'>����������</A>] [<A HREF='myarticle.php?job=myarticle&fid=$fid&mid=$mid&only=$only'>���������б�</A>] [<A HREF='{$htmlurldb[showurl]}' target=_blank>�鿴����</A>] [<A HREF='?job=manage&aid=$aid&mid=$mid&only=$only'>�޸�����</A>]</CENTER>",60);
}
elseif($job=='manage')
{
	if(!$atc_power)showerr("��ûȨ��");
	if($rsdb[pages]<2){
		header("location:post.php?job=edit&aid=$aid&mid=$mid&only=$only");exit;
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
		require(dirname(__FILE__)."/"."template/post_set.htm");
		require(dirname(__FILE__)."/"."foot.php");
		exit;
	}
}
elseif($action=="delelte")
{
	if(!$atc_power)showerr("��ûȨ��");
	//ɾ�����µĺ���
	delete_article($rsdb[aid],$rsdb[rid]);
	refreshto("myarticle.php?job=myarticle&only=$only&mid=$mid","ɾ���ɹ�",1);
}

if($job=='edit'||$job=='post_more'||$job=='edit_more'){
	if(!$atc_power)showerr("��ûȨ��");
}

//�Է���ǰ�뷢��ǰ������
require_once(PHP168_PATH."inc/check.postarticle.php");

if($job=='postnew')
{

	if($step=='post')
	{
		post_new();

		//���ɾ�̬
		make_article_html("$webdb[www_url]/member/post.php?job=endHTML&aid=$aid&mid=$mid&only=$only");

		$mid && $mid<106 && $none='none';

		refreshto("?job=postnew&fid=$fid&mid=$mid&only=$only","<CENTER>[<A HREF='?job=postnew&fid=$fid&mid=$mid&only=$only'>��������������</A>] <span style='display:$none;'>[<A HREF='?job=post_more&fid=$fid&mid=$mid&aid=$aid&only=$only'>����������</A>]</span> [<A HREF='myarticle.php?job=myarticle&fid=$fid&mid=$mid&only=$only'>���������б�</A>] [<A HREF='../bencandy.php?fid=$fid&aid=$aid' target=_blank>�鿴����</A>] [<A HREF='?job=edit&aid=$aid&mid=$mid&only=$only'>����޸�</A>]</CENTER>",60);
	}
	$MSG='�·�������';
	//$select_fid=list_post_selectsort(0,$fid,$mid,$only);
	$select_fid=$Guidedb->SelectIn("{$pre}sort",0,$fid);

	require(dirname(__FILE__)."/"."head.php");
	if($mid&&file_exists(PHP168_PATH."php168/member_tpl/post_$mid.htm")){
		require(PHP168_PATH."php168/member_tpl/post_$mid.htm");
	}else{
		require(dirname(__FILE__)."/"."template/post.htm");
	}
	require(dirname(__FILE__)."/"."foot.php");
}
elseif($job=='edit')
{
	if($rsdb[yz]&&!$web_admin&&$groupdb[EditPassPower]==2){
		showerr("����˵�����,�㲻�����޸�");
	}
	if($step=='post')
	{
		post_edit();

		//���ɾ�̬
		make_article_html("$webdb[www_url]/member/post.php?job=endHTML&aid=$aid&mid=$mid&only=$only");

		$mid && $mid<106 && $none='none';

		refreshto("$FROMURL","<CENTER>[<A HREF='?job=postnew&fid=$fid&mid=$mid&only=$only'>����������</A>] <span style='display:$none;'>[<A HREF='?job=post_more&aid=$aid&mid=$mid&only=$only'>����������</A>]</span> [<A HREF='myarticle.php?job=myarticle&fid=$fid&mid=$mid&only=$only'>���������б�</A>] [<A HREF='../bencandy.php?fid=$fid&aid=$aid' target=_blank>�鿴����</A>] [<A HREF='?job=edit&aid=$aid&mid=$mid&only=$only'>�����޸�</A>]</CENTER>",60);
	}
	$MSG='�޸�����';
	//$select_fid=list_post_selectsort(0,$fid,$mid,$only);
	$select_fid=$Guidedb->SelectIn("{$pre}sort",0,$fid);
	require(dirname(__FILE__)."/"."head.php");
	if($mid&&file_exists(PHP168_PATH."php168/member_tpl/post_$mid.htm")){
		require(PHP168_PATH."php168/member_tpl/post_$mid.htm");
	}else{
		require(dirname(__FILE__)."/"."template/post.htm");
	}
	require(dirname(__FILE__)."/"."foot.php");
}
elseif($job=='post_more')
{
	if($step=='post')
	{
		//�������
		query_reply($aid,'','');

		//���ɾ�̬
		make_article_html("$webdb[www_url]/member/post.php?job=endHTML&aid=$aid&mid=$mid&only=$only");

		refreshto("$FROMURL","<CENTER>[<A HREF='?job=postnew&fid=$fid&mid=$mid&only=$only'>����������</A>] [<A HREF='?job=post_more&aid=$aid&mid=$mid&only=$only'>����������</A>] [<A HREF='myarticle.php?job=myarticle&fid=$fid&mid=$mid&only=$only'>���������б�</A>] [<A HREF='../bencandy.php?fid=$fid&aid=$aid' target=_blank>�鿴����</A>] [<A HREF='?job=manage&aid=$aid&mid=$mid&only=$only'>�޸�����</A>]</CENTER>",60);
	}
	$MSG='��������';
	unset($rsdb[content],$rsdb[subhead]);
	require(dirname(__FILE__)."/"."head.php");
	if($mid&&file_exists(PHP168_PATH."php168/member_tpl/post_$mid.htm")){
		require(PHP168_PATH."php168/member_tpl/post_$mid.htm");
	}else{
		require(dirname(__FILE__)."/"."template/post.htm");
	}
	require(dirname(__FILE__)."/"."foot.php");
}
elseif($job=='edit_more')
{
	if($step=='post')
	{
		//�޸�����
		query_reply($aid,$rid,'edit');

		//���ɾ�̬
		make_article_html("$webdb[www_url]/member/post.php?job=endHTML&aid=$aid&mid=$mid&only=$only");

		refreshto("$FROMURL","<CENTER>[<A HREF='?job=postnew&fid=$fid&mid=$mid&only=$only'>����������</A>] [<A HREF='?job=post_more&aid=$aid&mid=$mid&only=$only'>����������</A>] [<A HREF='myarticle.php?job=myarticle&fid=$fid&mid=$mid&only=$only'>���������б�</A>] [<A HREF='../bencandy.php?fid=$fid&aid=$aid' target=_blank>�鿴����</A>] [<A HREF='?job=edit_more&aid=$aid&rid=$rid&mid=$mid&only=$only'>�޸�����</A>]</CENTER>",60);
	}
	$MSG='�޸�����';
	require(dirname(__FILE__)."/"."head.php");
	if($mid&&file_exists(PHP168_PATH."php168/member_tpl/post_$mid.htm")){
		require(PHP168_PATH."php168/member_tpl/post_$mid.htm");
	}else{
		require(dirname(__FILE__)."/"."template/post.htm");
	}
	require(dirname(__FILE__)."/"."foot.php");
}



/**
*�û���ѡ��
**/
function group_box($name="postdb[group]",$ckdb=array()){
	global $db,$pre;
	$query=$db->query("SELECT * FROM {$pre}group ORDER BY gid ASC");
	while($rs=$db->fetch_array($query))
	{
		$checked=in_array($rs[gid],$ckdb)?"checked":"";
		$show.="<input type='checkbox' name='{$name}[]' value='{$rs[gid]}' $checked>&nbsp;{$rs[grouptitle]}&nbsp;&nbsp;";
	}
	return $show;
}

/**
*ģ��ѡ��
**/
function select_template($cname,$type=1,$ck=''){
	global $db,$pre;
	$show="<select name='$cname' $reto><option value='' style='color:red;'>��ѡ��ģ��</option>";
	if($type==7||$type==8){
		//$show.="<option value='template/default/none.htm'>��Ҫ����</option>";
	}
	$query=$db->query("SELECT * FROM {$pre}template WHERE type='$type'");
	while($rs=$db->fetch_array($query))
	{
		if(!$rs[name]){
			$rs[name]="ģ��$rs[id]";
		}
		$rs[filepath]==$ck?$ckk='selected':$ckk='';
		$show.="  <option value='$rs[filepath]' $ckk>$rs[name]</option>";
	}
	 return $show." </select>";
}

/**
*���ѡ��
**/
function select_style($name='stylekey',$ck='',$url='',$select=''){
	if($url) 
	$reto=" onchange=\"window.location=('{$url}&{$name}='+this.options[this.selectedIndex].value+'')\"";
	$show="<select name='$name' $reto><option value=''>ѡ����</option>";
	$filedir=opendir(PHP168_PATH."php168/style/");
	while($file=readdir($filedir)){
		if(ereg("\.php$",$file)){
			include PHP168_PATH."php168/style/$file";
			$ck==$styledb[keywords]?$ckk='selected':$ckk='';	//ָ����ĳ��
			/*ֻѡ��һ��
			if($select){
				if($style_web!=$select){
					continue;
				}
			}
			*/
			$show.="<option value='$styledb[keywords]' $ckk style='color=blue'>$styledb[name]</option>";
		}
	}
	return $show." </select>";   
}

function list_sort_guide($fup){
	global $db,$pre,$mid,$only,$lfj,$job,$jobs;
	$rs=$db->get_one("SELECT fup,name FROM {$pre}sort WHERE fid='$fup'");
	if($rs){
		$show=" -> <A HREF='?lfj=$lfj&job=$job&jobs=$jobs&only=$only&mid=$mid&fid=$fup'>$rs[name]</A> ";
		$show=list_sort_guide($rs[fup]).$show;
	}
	return $show;
}
?>