<?php
require(dirname(__FILE__)."/"."global.php");

$rt=$db->get_one("select renzheng,title,uid,rid from `{$_pre}company` where uid='$lfjuid'");
if(!$rt){	//���̼���Ϣ
	showerr("��Ǹ������û�еǼ��̼���Ϣ��<br>������<a href='$Murl/post_company.php?' target=_blank>�Ǽ��̼�</a>��");
}
$homepage=$db->get_one("select * from {$_pre}homepage where rid='$rt[rid]' limit 1");
if(!$homepage[hid]) { //�����̼���Ϣ
	showerr("�������̻�û�м��������� [ <a href='$Mdomain/myhomepage.php' target='_blank'>����</a> ]");
}
if(!$job){
	//�õ����Լ��ķ���
		$query=$db->query("select * from `{$_pre}mysort` where uid='$lfjuid' and ctype=2 order by listorder desc");
		while($rs=$db->fetch_array($query)){
			 $ck=$ms_id==$rs[ms_id]?" selected":"";
			 $ms_id_options.="<option value='$rs[ms_id]' $ck>$rs[sortname]</option>";
			 $mysort[$rs[ms_id]]=$rs[sortname];
		}
	//�õ��ҵĹ�Ӧ�б�
	$rows=20;
	if($page<1){
		$page=1;
	}
	$min=($page-1)*$rows;
	$where=" where A.owner_uid='$lfjuid'";
	
	if($yz) $where.="and  A.yz='".($yz-1)."'";
	$yz_sel[$yz]=" selected";
	
	if($keyword) $where.=" and B.title like('%".trim($keyword)."%')";
	if($ms_id) $where.=" and A.ms_id='$ms_id'";	
	$query=$db->query("select A.*,B.renzheng,B.title,B.picurl from {$_pre}vendor A left join {$_pre}company B on B.rid=A.rid  $where   limit $min,$rows");
	
	while($rs=$db->fetch_array($query)){
		$rs[posttime]=date("Y-m-d",$rs[posttime]);
		$rs[yztime]=$rs[yztime]?date("Y-m-d",$rs[yztime]):"&nbsp;";
		$rs[yz_job]=!$rs[yz]?1:0;
		$rs[thisaction]=!$rs[yz]?"ȷ����Ӧ��ϵ":"��ͣ��Ӧ��ϵ";
		$rs[yz]=!$rs[yz]?"<font color=red>δȷ��</font>":"<font color=blue>�Ѿ�ȷ��</font>";
		$rs[mysort]=$rs[ms_id]?$mysort[$rs[ms_id]]:"δ����";
		$rs[renzheng]=getrenzheng($rs[renzheng]);
		$rs[pm_username]=urlencode($rs[username]);
		$rs[picurl]=getimgdir($rs[picurl],3);
		$listdb[]=$rs;
	}	
	
	$showpage=getpage("{$_pre}vendor A left join {$_pre}company B on B.rid=A.rid",$where,"?&yz=$yz&ms_id=$ms_id&keyword=".urlencode($keyword),$rows);
	
	//���� ��Ѱ����״̬��ʾ
	$vendor_open="";
	$rsdb=$db->get_one("select * from {$_pre}vendor_want where uid='$lfjuid' limit 1");
	if($rsdb[is_show] && $rsdb[endtime]>$timestamp) $vendor_open='<img src="images/want_vendor_open.gif"  border="0"/>';
	
}elseif($job=='betch_move'){

	if(!is_array($id_db) || count($id_db)<1){
		showerr("����ѡ������һ����Ŀ");
	}
	if(!$to_ms_id) {
		showerr("����ѡ��Ҫ�Ƶ��ķ���");
	}
	$id_db=implode(",",$id_db);
	
	if($id_db){
		$db->query("update `{$_pre}vendor` set  ms_id='$to_ms_id' where vid in($id_db)");
	}
	refreshto("?","�����ɹ�");
}elseif($job=='yz'){
	
	if(!$vid)showerr("����ѡ������һ����Ŀ");
	$yz=intval($yz);
	$timestamp=$yz?$timestamp:"0";
	$db->query("update `{$_pre}vendor`  set yz='$yz',yztime='$timestamp'  where vid ='$vid' limit 1");
	refreshto("?","�����ɹ�");
	
}elseif($job=='del'){
	if(!$vid)showerr("����ѡ������һ����Ŀ");
	$db->query("delete  from `{$_pre}vendor`   where vid ='$vid' limit 1");
	refreshto("?","�����ɹ�");

}elseif($job=='betch_pm'){
	if($step){
		if(!$title){
			showerr("���ⲻ��Ϊ��");
		}
		if(!$content || strlen($content)>1000 ){
			showerr("���ݲ���Ϊ��,�Ҳ��ܳ���500����");
		}
		$query=$db->query("select uid,owner_uid,owner_username from {$_pre}vendor  where owner_uid='$lfjuid' and yz=1  ");
	
		while($rs=$db->fetch_array($query)){
			$array[touid]=$rs[uid];
			$array[fromuid]=$rs[owner_uid];
			$array[fromer]=$rs[owner_username];
			$array[title]=filtrate($title);
			$array[content]=filtrate($content);
			pm_msgbox($array);
		}	
		refreshto("?","�������");
	}

}elseif($job=='want_vendor'){

		
	if(!$step){
		
		$rsdb=$db->get_one("select * from {$_pre}vendor_want where uid='$lfjuid' limit 1");
		$w_renzheng[$rsdb[w_renzheng]]=" checked ";
		$w_agent[$rsdb[w_agent]]      =" checked ";
		$w_vip[$rsdb[w_vip]]          =" checked ";
		$is_show[$rsdb[is_show]]      =" checked ";
		$starttime=$rsdb[starttime]?date("Y-m-d H:i:s",$rsdb[starttime]):date("Y-m-d H:i:s");
		$howlong=$rsdb[endtime]?intval(($rsdb[endtime]-$rsdb[starttime])/(60*60*24)):"1";
		$w_title=$rsdb[w_title]?$rsdb[w_title]:$rt[title]."��Ѱ��Ӧ��";

	}else{
		
		if(!$w_title ||  strlen($w_title)>40) showerr("����ֻ����С��40���ַ�֮�ڣ��Ҳ���Ϊ��");
		if(!preg_match("/[0-9]{4}-[0-9]{1,2}-[0-9]{1,2} [0-9]{1,2}:[0-9]{1,2}:[0-9]{1,2}/",$starttime))showerr("ʱ���ʽ����ȷ���밴����ȷ��ʽ��д");
		$howlong=intval($howlong);
		if($howlong<1){	showerr("������1����߸���֮��ֹͣչʾ");}
		
		$starttime_1=explode(" ",$starttime);
		$starttime_2=explode("-",$starttime_1[0]);
		$starttime_3=explode(":",$starttime_1[1]);
		$starttime=mktime(intval($starttime_3[0]),intval($starttime_3[1]),intval($starttime_3[2]),$starttime_2[1],$starttime_2[2],$starttime_2[0]);
		$endtime=$starttime+($howlong*24*60*60);

		if(!$wv_id){
			//���
			$yz=isset($webdb[vendor_want_yz])?$webdb[vendor_want_yz]:1;
			$db->query("INSERT INTO `{$_pre}vendor_want` ( `wv_id` , `uid` , `username` , `rid` , `w_title` , `w_renzheng` , `w_agent` , `w_vip` , `posttime` , `starttime` , `endtime` , `yz` , `is_show` ,`is_levels`) VALUES ('', '$lfjuid', '$lfjid', '$rt[rid]', '$w_title', '$w_renzheng', '$w_agent', '$w_vip', '$timestamp', '$starttime', '$endtime', '$yz', '$is_show',0);");	

		}else{
			//�޸�
			$db->query("update `{$_pre}vendor_want` set 
			`w_title`='$w_title',
			`w_renzheng`='$w_renzheng',
			`w_agent`='$w_agent',
			`w_vip`='$w_vip',
			`starttime`='$starttime',
			`endtime`='$endtime',
			`is_show`='$is_show'
			where `wv_id`='$wv_id';");

			
		}
	
		refreshto("?","�����ɹ�");
	
	}

}



require(dirname(__FILE__)."/"."head.php");
require(dirname(__FILE__)."/"."template/vendor.htm");
require(dirname(__FILE__)."/"."foot.php");
?>