<?php
require(dirname(__FILE__)."/"."global.php");



if(!$job){
	//����ƹ�
	$where=" WHERE tg_uid='$lfjuid' AND tg_ctype=1  AND (`tg_posttime`+(`tg_howlong`*60*60)) > $timestamp";
			$query=$db->query("SELECT * FROM {$_pre}tg_sell  $where ORDER BY tg_myid DESC ");
			while($rs=$db->fetch_array($query)){
				$overtime=intval( $rs[tg_posttime] + ($rs[tg_howlong]*60*60)    );
				if($overtime > $timestamp) $mytg_ids[]=$rs[tg_id]; 

			}
	//�õ����Լ��ķ���
	$query=$db->query("SELECT * FROM `{$_pre}mysort` WHERE uid='$lfjuid' and ctype=1 ORDER BY listorder DESC");
		while($rs=$db->fetch_array($query)){
			 $ck=$ms_id==$rs[ms_id]?" selected":"";
			 $ms_id_options.="<option value='$rs[ms_id]' $ck>$rs[sortname]</option>";
			 $mysort[$rs[ms_id]]=$rs[sortname];
	}
	//�õ���Ӧ�б�
	$rows=20;
	if($page<1){
		$page=1;
	}
	$min=($page-1)*$rows;
	$where=" WHERE A.uid='$lfjuid' ";
	if($keyword) $where.=" AND A.title LIKE('%$keyword%')";
	if($ms_id) $where.=" AND A.ms_id='$ms_id'";	

	$tab || $tab=0;
	$yz=intval($tab)-1;
	if($yz>-1){
		$where.=" AND A.yz=$yz";
	}
	$query=$db->query("SELECT A.* ,B.* FROM {$_pre}content_sell A INNER JOIN {$_pre}content_1 B ON B.id=A.id $where ORDER BY A.posttime DESC LIMIT $min,$rows");
	
	while($rs=$db->fetch_array($query)){
		$rs[posttime]=date("Y-m-d",$rs[posttime]);
		$rs[yz]=!$rs[yz]?"<font color=red>�����</font>":"��ͨ��";
		$rs[mysort]=$rs[ms_id]?$mysort[$rs[ms_id]]:"δ����";
		$rs[picurl_min]=getimgdir($rs[picurl],1).".gif";
		$rs[my_price]=$rs[my_price]>0?"<strong><font color=red>$rs[my_price]</font></strong>Ԫ/$rs[quantity_type]":"<font color='#898989'>�۸�����</font>";

		if(in_array($rs[id],$mytg_ids)) $rs[tg_status]="<font color=red>�ƹ���</font>";
		else $rs[tg_status]="<a href='../tg_sell.php?action=posttgnew&fid=$rs[fid]&id=$rs[id]' target='_blank'>�ƹ�</a>";

		$listdb[]=$rs;
	}

	
	$cksel[$tab]='ck';
	$keyword2=urlencode($keyword);
	$showpage=getpage("{$_pre}content_sell A INNER JOIN {$_pre}content_1 B ON B.id=A.id",$where,"?ms_id=$ms_id&keyword=$keyword2&tab=$tab",$rows);

	
	
}elseif($job=='betch_move'){

	if(!is_array($id_db) || count($id_db)<1){
		showerr("����ѡ������һ����Ŀ");
	}
	if(!$to_ms_id) {
		showerr("����ѡ��Ҫ�Ƶ��ķ���");
	}
	$id_db=implode(",",$id_db);
	
	if($id_db){
		$db->query("UPDATE `{$_pre}content_sell` SET  ms_id='$to_ms_id' WHERE id IN($id_db)");
	}
	refreshto("?","�����ɹ�");
	
}elseif($job=='update_posttime'){

	if(!is_array($id_db) || count($id_db)<1){
		showerr("����ѡ������һ����Ŀ");
	}
	$id_db=implode(",",$id_db);
	
	if($id_db){
		$db->query("UPDATE `{$_pre}content_sell` SET  posttime='$timestamp' WHERE id IN($id_db)");
	}
	refreshto("?","�����ɹ�");



}
require(dirname(__FILE__)."/"."head.php");
require(dirname(__FILE__)."/"."template/selllist.htm");
require(dirname(__FILE__)."/"."foot.php");
?>