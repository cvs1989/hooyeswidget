<?php
require_once("global.php");
$linkdb=array("ȫ���̼�"=>"?","δ��֤�̼�"=>"?level=0","��ͨ��֤"=>"?level=1","�߼���֤(��)"=>"?level=2","ʵ����֤(��)"=>"?level=3");

$levelname=array(1=>"��ͨ��֤",2=>"�߼���֤(���Ƽ�)",3=>"ʵ����֤(���Ƽ�)");


require_once("../php168/all_area.php");
require_once("../php168/all_city.php");
if(!$jobs){

	$rows=20;
	$page<1 && $page=1;
	$min=($page-1)*$rows;
	$where=" where 1";
	if($level!='') $where.=" and `renzheng`='$level'";
	if($keyword) $where.=" and `title` like('%$keyword%') ";
	$showpage=getpage("`{$_pre}company`",$where,"?level=$level&keyword=".urlencode($keyword),$rows);
	$query = $db->query("SELECT *  from  `{$_pre}company` $where order by rid desc LIMIT $min,$rows");
	
	while($rs = $db->fetch_array($query)){
		$rs[posttime]=date("m-d H:i",$rs[posttime]);
		$rs[level]=$levelname[$rs[renzheng]];
		$rs[yz]=$rs[yz]?"�����":"<font color=red>δ���</font>";
		$rs[city_id]="{$area_DB[name][$city_DB[fup][$rs[city_id]]]}/{$city_DB[name][$rs[city_id]]}";
		$rs[levels]=$rs[levels]?"<font color=red>��</font>":"��";

		$rs[agent]=$rs[is_agent]?"<font color='red'>��</font>":"��";
		$rs[vip]=$rs[is_vip]?"<font color='red'>��</font>":"��";
		$listdb[]=$rs;
	}
	require("head.php");
	require("template/company/list.htm");
	require("foot.php");
	
}elseif($jobs=="view"){
	
	
	
}elseif($jobs=="del"){

	
	del_company($rid);

	refreshto("$FROMURL","ɾ���ɹ�",1);
	
}elseif($jobs=="levels"){	
	
	if(!$rid) showerr("δָ��ID");
	$rz=$db->get_one("select * from `{$_pre}company` where rid='$rid'");
	if(!$rz[yz])  showerr("�����Ƽ�û����˵��̼�");
	$levels=$rz[levels]?0:1;
	$db->query("update `{$_pre}company` set `levels`='$levels' where rid='$rid'");
	refreshto("$FROMURL","�����ɹ�",1);
	
}elseif($jobs=="yz"){
	
	if(!$rid) showerr("δָ��ID");
	$rz=$db->get_one("select * from `{$_pre}company` where rid='$rid'");
	if($rz[yz]) $yz=0;
	else $yz=1;
	
	$db->query("update `{$_pre}company` set `yz`='$yz',`yztime`='".time()."' where rid='$rid'");
	
	$array[touid]=$rz[uid];
	$array[fromuid]=0;
	$array[fromer]='ϵͳ��Ϣ';
	if($yz){
		$array[title]='��ϲ�������Ǽǵ��̼��Ѿ�ͨ����ˣ������Է���������Ϣ��';
		$array[content]="{$rz[username]}����!<br>���ȼ����̼��Ѿ�ͨ�����, �����Է���������Ϣ��!,֮ǰ������¼ܵĲ�Ʒ���Ѿ��ϼ����� ";
		$db->query("update `{$_pre}content_sell` set `yz`=1 where `uid`='$rz[uid]';");
		$db->query("update `{$_pre}content_buy` set `yz`=1 where `uid`='$rz[uid]';");
	}else{
		$array[title]='��Ǹ�����Ǽǵ��̼��Ѿ���ȡ����ˣ����ڲ���ʹ�ã�';
		$array[content]="{$rz[username]}����!<br>���Ǽ��̼��Ѿ���ȡ�����, ���ڲ���ʹ��,�������Ĺ�����Ϣ��ȫ���¼�! ";
		//���̼�ȫ����Ʒ�¼�
		$db->query("update `{$_pre}content_sell` set `yz`=0 where `uid`='$rz[uid]';");
		$db->query("update `{$_pre}content_buy` set `yz`=0 where `uid`='$rz[uid]';");
	}
	if(function_exists('pm_msgbox')){
	pm_msgbox($array);
	}
	refreshto("$FROMURL","�����ɹ�",1);
}


function del_company($rid){
	global $db,$webdb,$user_picdir,$_pre,$Imgdirname;
	
	if(!$rid) return false;

	$rz=$db->get_one("select * from `{$_pre}company` where rid='$rid'");
	$array[touid]=$uid=$rz[uid];
	$array[fromuid]=0;
	$array[fromer]='ϵͳ��Ϣ';
	$array[title]='�����̼�ҳ�������ʾ';
	$array[content]="{$rz[username]}����!<br>����$rz[company_name] ��ҳ��������ĳ��ԭ��,����Ա�Ѿ�ɾ��.������������ϵ����Ա��лл! ";
	if(function_exists('pm_msgbox')){
		pm_msgbox($array);
	}
	@unlink(PHP168_PATH.$webdb[updir]."/$Imgdirname/ico/".$rz[picurl]);
	$db->query("delete from `{$_pre}company` where rid='$rid'");

	
	//ҳ�������ļ�
	$db->query("delete from `{$_pre}homepage` where rid='$rid'");
	$db->query("delete from `{$_pre}homepage_article` where rid='$rid'");
	$db->query("delete from `{$_pre}homepage_guestbook` where cuid='$uid'");
	$db->query("delete from `{$_pre}homepage_pic` where rid='$rid'");
	$db->query("delete from `{$_pre}homepage_picsort` where rid='$rid'");
	
	
	//ɾ��ͼƬ
	$dir=$user_picdir."$uid/";
	if(is_dir($dir)){
		if ($handle = opendir($dir)) {
			while (false !== ($file = readdir($handle))) {
				if ($file != "." && $file != "..") {
					@unlink($dir.$file);
				}
			}
		}
		closedir($handle);	
		@rmdir($dir);
	}
	//ɾ������
	$Imgdirname=$Imgdirname?$Imgdirname:"business";
	$query=$db->query("select id from `{$_pre}content_sell` where uid='$uid';");
		while($rs=$db->fetch_array($query)){
		$db->query("delete from `{$_pre}content_1` where id='$rs[id]'");
		$db->query("delete from `{$_pre}content_sell` where id='$rs[id]'");
		$db->query("delete from `{$_pre}sell_fid` where id='$rs[id]'");
		@unlink(PHP168_PATH.$webdb[updir]."/{$Imgdirname}/".$rs[picurl]);
		@unlink(PHP168_PATH.$webdb[updir]."/{$Imgdirname}/".$rs[picurl].".gif");
	}
	$query=$db->query("select id from `{$_pre}content_buy` where uid='$uid';");
		while($rs=$db->fetch_array($query)){
		$db->query("delete from `{$_pre}content_2` where id='$rs[id]'");
		$db->query("delete from `{$_pre}content_buy` where id='$rs[id]'");
		$db->query("delete from `{$_pre}buy_fid` where id='$rs[id]'");
		@unlink(PHP168_PATH.$webdb[updir]."/{$Imgdirname}/".$rs[picurl]);
		@unlink(PHP168_PATH.$webdb[updir]."/{$Imgdirname}/".$rs[picurl].".gif");
	}
	$query=$db->query("select rid from `{$_pre}company` where uid='$uid';");
		while($rs=$db->fetch_array($query)){
		$db->query("delete from `{$_pre}company_fid` where cid='$rs[rid]'");
		$db->query("delete from `{$_pre}content` where rid='$rs[rid]'");
	}


	//���еĶ���������
}


?>