<?php
require(dirname(__FILE__)."/"."global.php");
if(!$action){
	$page=$page?$page:1;
	$rows=20;
	$min=($page-1)*$rows;
	if($hopereply_time) $sql=" and hopereply_time>='$hopereply_time' ";
	
	$query=$db->query("SELECT A.*,B.title as owner,B.uid,B.gz FROM `{$_pre}form1` A  INNER JOIN `{$_pre}company` B ON B.uid=A.from_uid WHERE A.owner_uid='$lfjuid' $sql LIMIT $min,$rows");
	while($rs = $db->fetch_array($query)){
		$rs[posttime]=date("Y-m-d H:i",$rs[posttime]);
		$rs[reply]=$rs[is_reply]?"已回复":"<font color=red>点击回复</font>";
		$rs[gz]=$rs[gz]?"[公章]":"";
		$listdb[]=$rs;
	}
	$showpage=getpage("`{$_pre}form1` A  INNER JOIN `{$_pre}company` B ON B.uid=A.from_uid","WHERE A.owner_uid='$lfjuid' $sql","?hopereply_time=$hopereply_time",$rows);
	$hopereply_time_s=date("Y-m-d");
}elseif($action=='view'){
	if(!$id) showerr("参数错误");
	$rt=$db->get_one("SELECT A.*,B.title as owner FROM `{$_pre}form1` A INNER JOIN `{$_pre}company` B ON B.uid=A.from_uid  WHERE id='$id'");
	$rt[posttime]=date("Y-m-d H:i",$rt[posttime]);
	$contact_info=unserialize($rt[contact_info]);
	$rt[reply_time]=$rt[reply_time]?date("Y-m-d,H:i:s 已回复",$rt[reply_time]):"";
	//print_r($contact_info);
}elseif($action=='reply'){
	if(!$id) showerr("参数错误");
	if(!$reply_content) showerr("回复内容不能为空");
	$db->query("UPDATE `{$_pre}form1` SET reply_content='$reply_content',reply_cankao='$reply_cankao',is_reply='1',reply_time='".time()."' WHERE id='$id'");
	refreshto("?","回复成功",1);
	
}elseif($action=='del'){
	
	
	if(is_array($listdb) && count($listdb)>0){
		foreach($listdb as $key){
			$db->query("DELETE FROM {$_pre}form1  WHERE id='$key' LIMIT 1");
		}
		refreshto("?","操作成功",1);
	}else{
		showerr("删除项目不明.");
	}
}elseif($action=='view_print'){

	if(!$id && !$listdb) showerr("参数错误");
	if($id){
		$listdb[]=$id;
	}
	$ids=implode(",",$listdb);

	$query=$db->query("SELECT A.*,B.title as owner,B.gz,C.title as formtitle,C.fid FROM `{$_pre}form1` A  INNER JOIN `{$_pre}company` B ON B.uid=A.from_uid 
	INNER JOIN `{$_pre}content_sell` C ON C.id=A.info_id 
	 where A.id in($ids)");
	while($rs=$db->fetch_array($query)){
	
		$rs[posttime]=date("Y-m-d H:i",$rs[posttime]);
		$contact_info=unserialize($rs[contact_info]);
		$rs[gz]=$rs[gz]?$webdb[www_url]."/".$webdb[updir]."/{$Imgdirname}/gz/".$rs[gz]:"";
		if(!$rs[formtitle]) $rs[formtitle]=get_word($rs[title],62);
		
		$printlistdb[]=$rs;
	}
	


	require(dirname(__FILE__)."/"."template/myform1_print.htm");
	exit;
		
}


require(dirname(__FILE__)."/"."head.php");
require(dirname(__FILE__)."/"."template/myform1.htm");
require(dirname(__FILE__)."/"."foot.php");
?>