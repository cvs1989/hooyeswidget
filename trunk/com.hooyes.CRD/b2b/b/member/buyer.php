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
		$query=$db->query("select * from `{$_pre}mysort` where uid='$lfjuid' and ctype=1 order by listorder desc");
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
	$where=" where A.uid='$lfjuid'";
	
	if($yz) $where.="and  A.yz='".($yz-1)."'";
	$yz_sel[$yz]=" selected";
	
	if($keyword) $where.=" and B.title like('%".trim($keyword)."%')";
	if($ms_id) $where.=" and A.ms_id='$ms_id'";	
	$query=$db->query("select A.*,B.renzheng,B.title,B.picurl from {$_pre}vendor A left join {$_pre}company B on B.rid=A.owner_rid  $where   limit $min,$rows");
	
	while($rs=$db->fetch_array($query)){
		$rs[posttime]=date("Y-m-d",$rs[posttime]);
		$rs[yz_job]=!$rs[yz]?1:0;
		$rs[yz]=!$rs[yz]?"<font color=red>�ȴ�ȷ��</font>":"<font color=blue>�Ѿ�ȷ��</font>";
		$rs[mysort]=$rs[ms_id]?$mysort[$rs[ms_id]]:"δ����";
		$rs[renzheng]=getrenzheng($rs[renzheng]);
		$rs[pm_username]=urlencode($rs[username]);
		$rs[picurl]=getimgdir($rs[picurl],3);
		$listdb[]=$rs;
	}	
	
	$showpage=getpage("{$_pre}vendor A left join {$_pre}company B on B.rid=A.owner_rid",$where,"?&yz=$yz&ms_id=$ms_id&keyword=".urlencode($keyword),$rows);
	
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
	$db->query("update `{$_pre}vendor`  set yz='$yz'  where vid ='$vid' limit 1");
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
		if(!$content  || strlen($content)>1000 ){
			showerr("���ݲ���Ϊ��,�Ҳ��ܳ���500����");
		}
		$query=$db->query("select owner_uid,uid,username from {$_pre}vendor  where uid='$lfjuid' and yz=1  ");
	
		while($rs=$db->fetch_array($query)){
			$array[touid]=$rs[owner_uid];
			$array[fromuid]=$rs[uid];
			$array[fromer]=$rs[username];
			$array[title]=filtrate($title);
			$array[content]=filtrate($content);
			pm_msgbox($array);
		}	
		refreshto("?","�������");
	}

}



require(dirname(__FILE__)."/"."head.php");
require(dirname(__FILE__)."/"."template/buyer.htm");
require(dirname(__FILE__)."/"."foot.php");
?>