<?php
require_once("global.php");
@include_once(Adminpath."../php168/companyData.php");
$linkdb=array("ȫ��"=>"?showall=0","������"=>"?showall=1","�������"=>"?cancel=1");

if(!$action){
	$rows=10;
	$page=intval($page);
	if(!$page)$page=1;
	$min=($page-1)*$rows;
	
	
	$where=" where 1";
	if($showall)$where.=" and yz=0 ";
	if($cancel) $where.=" and is_cancel=1";
	if($keyword)$where.=" and (companyName like('%$keyword%') or ag_name like('%$keyword%'))";
	$showpage=getpage("`{$_pre}agents`",$where,"?showall=$showall&cancel=$cancel&keyword=".urlencode($keyword),$rows);
	
	$query=$db->query("select * from {$_pre}agents $where order by posttime desc limit $min,$rows");
	while($rs=$db->fetch_array($query)){
		
		$rs[posttime]=date("Y-m-d H:i",$rs[posttime]);
		
		$rs[ag_cert]=$webdb[www_url]."/".$webdb[updir]."/{$Imgdirname}/ag_cert/$rs[uid]/".$rs[ag_cert];
		
		
		$rs[status]=$rs[yz]?"<font title='���ȡ�����'>�����</font><br>".date("Y-m-d H:i",$rs[yz_time]):"<font title='������'>δ���</font>";
		if($rs[is_cancel]) $rs[cancel]="���볷����";
		
		
		
		$rs[contact_info]=unserialize($rs[contact_info]);
		$rs[ag_level]=$ag_level_array[$rs[ag_level]];
		$listdb[]=$rs;	
	}

	require("head.php");
	require("template/agents/list.htm");
	require("foot.php");
	
}elseif($action=='yz'){

	if(!$ag_id) showerr("�벻Ҫ���зǷ�����");
	$rsdb=$db->get_one("select * from {$_pre}agents where ag_id='$ag_id'");	
	if(!$rsdb) showerr("û���ҵ���Ҫ��������Ŀ");
	
	$yz=$rsdb[yz]?0:1;
	$yz_time=$yz?$timestamp:0;
	
	
	$db->query("update {$_pre}agents set yz='$yz',yz_time='$yz_time' where ag_id='$ag_id' limit 1");
	
	updateCompanyAgentIco($rsdb);
	
	//����֪ͨ
	$array[touid]=$rsdb[uid];
	$array[fromuid]=0;
	$array[fromer]='ϵͳ��Ϣ';
	$array[title]='֪ͨ�����Ĵ������ʸ������Ѿ�������';
	if($yz){
		$array[content]="{$rsdb[username]}����,��ϲ��!<br>���ύ��$rsdb[ag_name] ({$ag_level_array[$rsdb[ag_level]]}) �Ѿ�ͨ����ˣ���л���Ĳ��롣 ";
	}else{
		$array[content]="{$rsdb[username]}����,��Ǹ!<br>���ύ��$rsdb[ag_name] ({$ag_level_array[$rsdb[ag_level]]}) �Ѿ�ȡ������ʸ��������Ҫ�������ٴ��ύ���롣 ";
	}
	if(function_exists('pm_msgbox')){
		pm_msgbox($array);
	}
	//��ȥ
	refreshto($FROMURL,"�����ɹ�",1);

}elseif($action=='del'){

	$rsdb=$db->get_one("select * from {$_pre}agents where ag_id='$ag_id'");	
	if(!$rsdb) showerr("û���ҵ���Ҫ��������Ŀ");
	
	//ɾ������
	@unlink(PHP168_PATH.$webdb[updir]."/{$Imgdirname}/ag_cert/$rsdb[uid]/$rsdb[ag_cert]");

	//ִ��
    $db->query("delete from {$_pre}agents where ag_id='$ag_id' limit 1");
	//�����̼���Ϣ�Ƿ����ͼ��
	updateCompanyAgentIco($rsdb);
	//����֪ͨ
	$array[touid]=$rsdb[uid];
	$array[fromuid]=0;
	$array[fromer]='ϵͳ��Ϣ';
	$array[title]='֪ͨ�����Ĵ������ʸ������Ѿ�������';
	$array[content]="{$rsdb[username]}����!<br>���ύ��$rsdb[ag_name] ({$ag_level_array[$rsdb[ag_level]]}) �Ѿ��������������Ҫ�������ٴ��ύ���롣 ";
	if(function_exists('pm_msgbox')){
		pm_msgbox($array);
	}
	//��ȥ
	refreshto($FROMURL,"�����ɹ�",1);
}
function updateCompanyAgentIco($rsdb){ //$rsdb��������ǰ�����¼ agents����
	global $db,$_pre;
	$agents=$db->get_one("select count(*) as num from {$_pre}agents where uid='$rsdb[uid]' and yz=1");
	if($agents[num]<1){
		$is_agent=0;  //�Ѿ�ȡ���˴���ͼ��
	}else{
		$is_agent=1;  //��ô���ͼ��
	}
	$db->query("update `{$_pre}company` set is_agent='$is_agent' where uid='$rsdb[uid]' limit 1 ");
}
?>