<?php
require(dirname(__FILE__)."/"."global.php");
$rt=$db->get_one("select renzheng,title,uid,rid from `{$_pre}company` where uid='$lfjuid'");
if(!$rt){	//���̼���Ϣ
	showerr("��Ǹ������û�еǼ��̼���Ϣ��<br>������<a href='$Murl/member/post_company.php?'  >�Ǽ��̼�</a>��");
}
	$webdb[vip_par_payfor]=$webdb[vip_par_payfor]?$webdb[vip_par_payfor]:50;
	$webdb[vip_min_long]=$webdb[vip_min_long]?$webdb[vip_min_long]:1;


if(!$action){
	$page=abs(intval($page));
	$page=$page?$page:1;
	$rows=10;
	$min=($page-1)*$rows;
	$where=" where uid='$lfjuid' ";
	$query=$db->query("select * from {$_pre}viphis $where order by posttime desc   limit $min,$rows");
	$showpage=getpage("{$_pre}viphis",$where,"?",$rows);
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
			$rs[pay_act] =!$rs[is_pay]?"<a href='$webdb[www_url]/do/olpay.php?vo_id=$rs[vo_id]&paymoeny=".strrev(base64_encode($rs[all_pay]))."' target=_blank><font color=blue>����֧��</font></a>":"";

		}elseif($rs[pay_type]=='offline'){
			$rs[pay_type]="����֧��";
			$rs[pay_act] =!$rs[is_pay]?"<font color=green>�ȴ�ȷ��</font>":"";			

		}


		$listdb[]=$rs;
	}

}elseif($action=='payfor'){
	
	
	
}elseif($action=='save_payfor'){	
	
	//��������
	$per_payfor=$webdb[vip_par_payfor];
	$all_pay   =$per_payfor * $how_long;
	$all_time  =$webdb[vip_min_long] * $how_long;
	if($all_pay<1) showerr("��С֧�����Ϊ1Ԫ");
	if($all_time<1) showerr("��С����ʱ��Ϊ1����");
	//ִ��
	
	$db->query("INSERT INTO `{$_pre}viphis` ( `vo_id` , `uid` , `username` , `companyName` , `rid` ,`posttime`,  `pay_type` , `is_pay` , `pay_time` , `how_long` , `per_payfor` ,`all_pay`, `all_time`,`start_time` , `end_time` , `remarks` , `contact_info` )VALUES ('', '$lfjuid', '$lfjid', '$rt[title]', '$rt[rid]','$timestamp',  '$pay_type', '0', '0', '$how_long', '$per_payfor','$all_pay','$all_time', '0', '0', '$remarks', '$contact_info');");
	
	//��ת
	refreshto("?","�ύ�ɹ�",1);

}elseif($action=="onlinepay_ok"){
	
	if($vo_id){
		$rsdb=$db->get_one("select * from `{$_pre}viphis` where vo_id='$vo_id'");
		if($rsdb[uid]!=$lfjuid) showerr("���Դ���,����������������!");
		
		if($rsdb[end_time] || $rs[is_pay]) showerr("��VIP�̼ҷ������Ѷ����Ѿ�֧���ɹ�");

		//�ҵ��ϴγɹ�����ʱ��
		$open=$db->get_one("select * from `{$_pre}viphis` where uid=$rsdb[uid] and is_pay=1 and end_time>0 order by end_time desc limit 0,1");
		$start_time=$open[end_time]?($open[end_time]+1):$timestamp;
		$end_time=$start_time + $rsdb[all_time]*30*24*60*60;

		$db->query("update `{$_pre}viphis` set
		is_pay=1,
		pay_time='$timestamp',
		start_time='$start_time',
		end_time='$end_time'
		where vo_id='$vo_id'");

		$end_time=$open[end_time];
		$db->query("update `{$_pre}company` set is_vip='$end_time' where uid='$open[uid]' limit 1 ");

	}
	//refreshto("?","֧�����",1);
	echo "<script>
	alert('֧���ɹ���ҳ�潫�رգ�');
	window.close();
	</script>";
}

require(dirname(__FILE__)."/"."head.php");
require(dirname(__FILE__)."/"."template/vip.htm");
require(dirname(__FILE__)."/"."foot.php");

?>