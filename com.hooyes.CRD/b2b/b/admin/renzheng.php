<?php
require_once("global.php");
$linkdb=array("ȫ����֤��Ϣ"=>"?showall=1","���ύ��֤����"=>"?showall=0");
$level=array(1=>"��ͨ��֤",2=>"�߼���֤(���Ƽ�)",3=>"ʵ����֤(���Ƽ�)");
if(!$jobs){

	$rows=20;
	$page<1 && $page=1;
	$min=($page-1)*$rows;
	$where=" where 1";
	if(!$showall) $where.=" and yz=0";
	if($keyword) $where.=" and company_name like('%$keyword%') ";
	$showpage=getpage("`{$_pre}renzheng`",$where,"?showall=$showall&keyword=".urlencode($keyword),$rows);
	$query = $db->query("SELECT *  from  `{$_pre}renzheng` $where order by post_time desc LIMIT $min,$rows");
	
	while($rs = $db->fetch_array($query)){
		$rs[post_time]=date("m-d H:i",$rs[post_time]);
		$rs[yz_time]=$rs[yz]?date("m-d H:i",$rs[yz_time]):"-";
		$rs[level]=$level[$rs[level]];
		$rs[yz]=$rs[yz]?"�Ѵ���":"δ����";
		$listdb[]=$rs;
	}
	require("head.php");
	require("template/renzheng/list.htm");
	require("foot.php");
	
}elseif($jobs=="view"){
	
	if(!$viewuid) showerr("��Ч����");
	$renzhengDB[1][yz_]='<br>δ�ύ';
	$renzhengDB[2][yz_]='<br>δ�ύ';
	$renzhengDB[3][yz_]='<br>δ�ύ';
	$query=$db->query("select * from `{$_pre}renzheng` where uid='$viewuid' order by `level` asc");
	while($rs=$db->fetch_array($query)){
		if($rs[yz]){
			$rs[yz_]="<strong><font color=blue>��ͨ��</font></strong> <br>(".date("Y-m-d,H:i:s",$rs[yz_time]).")";
		}else{
			$rs[yz_]="<font color='red'>���ύ,δ���</font><br>(".date("Y-m-d,H:i:s",$rs[post_time]).")";
		}
		$rs[content]=unserialize($rs[content]);
		$rs[files]=unserialize($rs[files]);
		foreach($rs[files] as $key=>$file){
			if($file) $rs[files][$key]="<a href='".$webdb[www_url]."/".$file."' target='_blank'>����鿴...</a>";
			else $rs[files][$key]="";
		}
		
		$renzhengDB[$rs[level]]=$rs;
	}
	require("head.php");
	require("template/renzheng/view.htm");
	require("foot.php");
	
}elseif($jobs=="del"){

	if(!$id) showerr("��Ч����");
	$rz=$db->get_one("select * from `{$_pre}renzheng` where id='$id'");
	
	$rzshang=$db->get_one("select * from `{$_pre}renzheng` where uid='$rz[uid]' and level='".(intval($rz[level])+1)."';");
	if($rzshang) showerr("����֤��Ϣ���û���������˸��߼������֤������֤ɾ��ʧ��");
	else{
		$db->query("update `{$_pre}company` set `renzheng`=`renzheng`-1  where uid='$rz[uid]'");
		///ɾ���ļ���
		$files=unserialize($rz[files]);
		foreach($files as $file){
			if(file_exists(PHP168_PATH."/".$file)) @unlink(PHP168_PATH."/".$file);
		}
		$db->query("delete from  `{$_pre}renzheng`  where id='$id'");
	}
	$array[touid]=$rz[uid];
	$array[fromuid]=0;
	$array[fromer]='ϵͳ��Ϣ';
	$array[title]='������֤�������㣬����Ա�Ѿ��ܾ�����֤������ɾ��';
	$array[content]="{$rz[username]}����!<br>���ύ��$rz[company_name] {$level[$rz[level]]}����֤��������,����Ա�Ѿ��ܾ�����֤������ɾ��.����Ҫ�ٴ����룬�������Լ������ݣ��ٴ��ύ��лл! ";
	if(function_exists('pm_msgbox')){
	pm_msgbox($array);
	}
	refreshto("$FROMURL","ɾ���ɹ�",1);
	
	
}elseif($jobs=="ok"){
	
	if(!$id) showerr("��Ч����");
	$rz=$db->get_one("select * from `{$_pre}renzheng` where id='$id'");
	if($rz[yz]) showerr("����֤�����Ѿ�ȷ�Ϲ��ˣ������ظ�ȷ��");
	$db->query("update `{$_pre}renzheng` set `yz`=1,`yz_time`='".time()."' where id='$id'");
	$db->query("update `{$_pre}company` set `renzheng`='$rz[level]' where uid='$rz[uid]'");
	$array[touid]=$rz[uid];
	$array[fromuid]=0;
	$array[fromer]='ϵͳ��Ϣ';
	$array[title]='��ϲ����������֤�����Ѿ�ͨ��';
	$array[content]="{$rz[username]}����!<br>���ύ��$rz[company_name] {$level[$rz[level]]}�Ѿ�ͨ����ˣ���л���Ĳ��롣 ";
	if(function_exists('pm_msgbox')){
	pm_msgbox($array);
	}
	refreshto("$FROMURL","�����ɹ�",1);
}

?>