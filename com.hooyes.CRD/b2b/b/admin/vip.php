<?php
require_once("global.php");
@include_once(Adminpath."../php168/companyData.php");
$linkdb=array("����֧��"=>"?pay_type=online","����֧��"=>"?pay_type=offline");

if(!$action){
	$rows=10;
	$page=intval($page);
	if(!$page)$page=1;
	$min=($page-1)*$rows;
	
	$where=" where 1";
	if($keyword)$where.=" and companyName like('%$keyword%')";
	$showpage=getpage("{$_pre}viphis",$where,"?pay_type=$pay_type&keyword=".urlencode($keyword),$rows);
	

	$query=$db->query("select * from {$_pre}viphis $where order by posttime desc limit $min,$rows");
	while($rs=$db->fetch_array($query)){
		
		
		$rs[all_how_long]=$rs[how_long]*$webdb[vip_min_long];
		$rs[pay_status]=$rs[is_pay]?"�Ѹ���":"δ֧��";
		$rs[vip_status]=$rs[start_time]?"������":"δ��ͨ";
		if($timestamp > $rs[end_time] && $rs[start_time] && $rs[end_time] ){
			$rs[vip_status]="�������";
		}

		
		//ʱ��
		$rs[posttime]=date('Y-m-d H:i:s',$rs[posttime]);
		$rs[pay_time]=$rs[pay_time]?date("Y-m-d H:i:s",$rs[pay_time]):"";
		$rs[start_time]=$rs[start_time]?date("Y-m-d H:i:s",$rs[start_time]):"";
		$rs[end_time]=$rs[end_time]?date("Y-m-d H:i:s",$rs[end_time]):"";
		//֧������
		if($rs[pay_type]=='online'){
			$rs[pay_type]="����֧��";
		}elseif($rs[pay_type]=='offline'){
			$rs[pay_type]="<font color='blue'>����֧��</font>";
			$rs[pay_act] =!$rs[is_pay]?"<font color=green>�ȴ�ȷ��</font>":"";			
			$rs[enter_pay]=!$rs[is_pay]?"<font color=green>����տ�</font>":"";	
		}


		$listdb[]=$rs;	
	}

	require("head.php");
	require("template/vip/list.htm");
	require("foot.php");

}elseif($action=='offline_pay'){
	
	$rsdb=$db->get_one("select * from {$_pre}viphis where vo_id='$vo_id'");	
	if(!$rsdb) showerr("û���ҵ���Ҫ��������Ŀ");
	if($rsdb[end_time])showerr("�˶�����VIP�����Ѿ��ڷ�����");

	//��������
	$open=$db->get_one("select * from `{$_pre}viphis` where uid=$rsdb[uid] and is_pay=1 and end_time>0 order by end_time desc limit 0,1");
	$start_time=$open[end_time]?($open[end_time]+1):$timestamp;
	$end_time=$start_time + $rsdb[all_time]*30*24*60*60;

	$db->query("update `{$_pre}viphis` set
		is_pay=1,
		pay_time='$timestamp',
		start_time='$start_time',
		end_time='$end_time'
		where vo_id='$vo_id'");

	//����,��ʵ����ʡ��;Ϊ��ͳһ���ٵ����°�
	updateCompanyVipIco($rsdb);
	
	//����
	$array[touid]=$rsdb[uid];
	$array[fromuid]=0;
	$array[fromer]='ϵͳ��Ϣ';
	$array[title]='֪ͨ������VIP�̼ҷ������Ѷ�������֪ͨ';
	$array[content]="{$rsdb[username]}����!<br>����".date('Y-m-d H:i:s',$open[posttime])."�ύ��VIP�̼ҷ������Ѷ����Ѿ�ȷ��֧��,������ɣ��������Ҫ�������ٴ��ύ�� ";
	if(function_exists('pm_msgbox')){
		pm_msgbox($array);
	}


	refreshto($FROMURL,"�����ɹ�",1);

}elseif($action=='del'){

	$rsdb=$db->get_one("select * from {$_pre}viphis where vo_id='$vo_id'");	
	if(!$rsdb) showerr("û���ҵ���Ҫ��������Ŀ");
	
	//ִ��
    $db->query("delete from {$_pre}viphis where vo_id='$vo_id' limit 1");
	
	//����
	updateCompanyVipIco($rsdb);
	//����֪ͨ
	$array[touid]=$rsdb[uid];
	$array[fromuid]=0;
	$array[fromer]='ϵͳ��Ϣ';
	$array[title]='֪ͨ������VIP�̼ҷ������Ѷ�������֪ͨ';
	$array[content]="{$rsdb[username]}����!<br>����".date('Y-m-d H:i:s',$rs[posttime])."�ύ��VIP�̼ҷ������Ѷ����Ѿ��������������Ҫ�������ٴ��ύ�� ";
	if(function_exists('pm_msgbox')){
		pm_msgbox($array);
	}
	//��ȥ
	refreshto($FROMURL,"�����ɹ�",1);
}
function updateCompanyVipIco($rsdb){ //$rsdb��������ǰ�����¼ viphis����
	global $db,$_pre;
	$vip=$db->get_one("select * from `{$_pre}viphis` where uid=$rsdb[uid] and is_pay=1 and end_time>0 order by end_time desc limit 0,1");
	$end_time=$vip[end_time];
	$db->query("update `{$_pre}company` set is_vip='$end_time', host = '' where uid='$rsdb[uid]' limit 1 ");
}
?>