<?php
require_once("global.php");

//�����ж�

$linkdb=array("ȫ�������ϵ"=>"?","��Ѱ��Ӧ��"=>"?action=want");



if(!$action){

	$rows=20;
	$page=intval($page);
	if($page<1) $page=1;
	$min=($page-1)*$rows;
	$where=" WHERE  B.rid <> C.rid";

	if($keyword){ 
		if(!$stype){
			$where.=" and (B.title like('%$keyword%')   or C.title like('%$keyword%') )";
		}else{
			$where.=" and ".$stype."  like('%$keyword%') ";
		}
		$stype_sel2['$stype']=" selected";
	}

	$query=$db->query("select A.*,B.title as owner_title,C.title from {$_pre}vendor A left join {$_pre}company B on B.rid=A.owner_rid right join {$_pre}company C on C.rid=A.rid $where order by A.posttime  desc limit $min,$rows");
	
	while($rs=$db->fetch_array($query)){
		$rs[posttime]=date("Y-m-d",$rs[posttime]);
		$rs[yz]=$rs[yz]?"����":"������";
		$listdb[]=$rs;
	}
	
	$showpage=getpage("{$_pre}vendor A left join {$_pre}company B on B.rid=A.owner_rid right join {$_pre}company C on C.rid=A.rid",$where,"?keyword=".urlencode($keyword)."&stype=$stype",$rows);

	

}elseif($action=='del'){

	$rsdb=$db->get_one("select A.*,B.title as owner_title,C.title from {$_pre}vendor A left join {$_pre}company B on B.rid=A.owner_rid right join {$_pre}company C on C.rid=A.rid   WHERE A.vid='$vid' and  B.rid <> C.rid");
	
	$array[touid]=$rsdb[uid];
	$array2[touid]=$rsdb[owner_uid];
	$array2[fromuid]=$array[fromuid]=0;
	$array2[fromer]=$array[fromer]='ϵͳ��Ϣ';
	$array2[title]=$array[title]='�����ϵ���֪ͨ';
	$array[content]="{$rsdb[owner_username]}����!<br>����$rsdb[title] �����ϵ�Ѿ�������Ա���,����в�������ϵ! ";
	$array2[content]="{$rsdb[username]}����!<br>����$rsdb[owner_title] �����ϵ�Ѿ�������Ա���,����в�������ϵ! ";

	if(function_exists('pm_msgbox')){
	pm_msgbox($array);
	pm_msgbox($array2);
	}

	$db->query("delete from  {$_pre}vendor where vid='$vid' ");

	refreshto("?","�����ɹ�");

}elseif($action=='want'){ //��Ѱ��Ӧ��

	$rows=20;
	$page=intval($page);
	if($page<1) $page=1;
	$min=($page-1)*$rows;

	$query=$db->query("select * from {$_pre}vendor_want where 1 order by posttime desc limit $min,$rows");
	$showpage=getpage("{$_pre}vendor_want"," where 1 ","?action=$action",$rows);
	while($rs=$db->fetch_array($query)){
		$rs[tiaojian].=$rs[w_renzheng]?"��֤�û� ":"";
		$rs[tiaojian].=$rs[w_agent]?"������ ":"";
		$rs[tiaojian].=$rs[w_vip]?"VIP�̼� ":"";

		$rs[starttime]=date("Y-m-d H:i:s",$rs[starttime]);
		$rs[endtime]=date("Y-m-d H:i:s",$rs[endtime]);

		$rs[is_show]=$rs[is_show]?"����":"����";

		$rs[is_levels]=$rs[is_levels]?"<font color=red>�Ƽ���</font>":"δ�Ƽ�";

		$listdb[]=$rs;
	}

}elseif($action=='close_want'){
	
	if(!$wv_id) showerr("�Ƿ�����");
	$db->query("update {$_pre}vendor_want set is_show=0 where wv_id='$wv_id';");
	refreshto("?action=want","�����ɹ�");
}elseif($action=='open_want'){
	
	if(!$wv_id) showerr("�Ƿ�����");
	$db->query("update {$_pre}vendor_want set is_show=1 where wv_id='$wv_id';");
	refreshto("?action=want","�����ɹ�");

}elseif($action=='del_want'){
	
	if(!$wv_id) showerr("�Ƿ�����");
	$db->query("delete from  {$_pre}vendor_want  where wv_id='$wv_id';");
	refreshto("?action=want","�����ɹ�");

}elseif($action=='levels_want'){
	
	if(!$wv_id) showerr("�Ƿ�����");
	$rsdb=$db->get_one("select * from {$_pre}vendor_want where  wv_id='$wv_id'");
	$is_levels=$rsdb[is_levels]?0:1;
	$db->query("update {$_pre}vendor_want set is_levels=$is_levels where wv_id='$wv_id';");
	refreshto("?action=want","�����ɹ�");
}

//******************************************���
require("head.php");
require("template/vendor/list.htm");
require("foot.php");
	

?>