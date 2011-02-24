<?php
require_once("global.php");

//功能判断

$linkdb=array("全部供求关系"=>"?","急寻供应商"=>"?action=want");



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
		$rs[yz]=$rs[yz]?"正常":"建立中";
		$listdb[]=$rs;
	}
	
	$showpage=getpage("{$_pre}vendor A left join {$_pre}company B on B.rid=A.owner_rid right join {$_pre}company C on C.rid=A.rid",$where,"?keyword=".urlencode($keyword)."&stype=$stype",$rows);

	

}elseif($action=='del'){

	$rsdb=$db->get_one("select A.*,B.title as owner_title,C.title from {$_pre}vendor A left join {$_pre}company B on B.rid=A.owner_rid right join {$_pre}company C on C.rid=A.rid   WHERE A.vid='$vid' and  B.rid <> C.rid");
	
	$array[touid]=$rsdb[uid];
	$array2[touid]=$rsdb[owner_uid];
	$array2[fromuid]=$array[fromuid]=0;
	$array2[fromer]=$array[fromer]='系统消息';
	$array2[title]=$array[title]='供求关系解除通知';
	$array[content]="{$rsdb[owner_username]}您好!<br>你与$rsdb[title] 供求关系已经被管理员解除,如果有不明请联系! ";
	$array2[content]="{$rsdb[username]}您好!<br>你与$rsdb[owner_title] 供求关系已经被管理员解除,如果有不明请联系! ";

	if(function_exists('pm_msgbox')){
	pm_msgbox($array);
	pm_msgbox($array2);
	}

	$db->query("delete from  {$_pre}vendor where vid='$vid' ");

	refreshto("?","操作成功");

}elseif($action=='want'){ //急寻供应商

	$rows=20;
	$page=intval($page);
	if($page<1) $page=1;
	$min=($page-1)*$rows;

	$query=$db->query("select * from {$_pre}vendor_want where 1 order by posttime desc limit $min,$rows");
	$showpage=getpage("{$_pre}vendor_want"," where 1 ","?action=$action",$rows);
	while($rs=$db->fetch_array($query)){
		$rs[tiaojian].=$rs[w_renzheng]?"认证用户 ":"";
		$rs[tiaojian].=$rs[w_agent]?"代理商 ":"";
		$rs[tiaojian].=$rs[w_vip]?"VIP商家 ":"";

		$rs[starttime]=date("Y-m-d H:i:s",$rs[starttime]);
		$rs[endtime]=date("Y-m-d H:i:s",$rs[endtime]);

		$rs[is_show]=$rs[is_show]?"开启":"隐藏";

		$rs[is_levels]=$rs[is_levels]?"<font color=red>推荐中</font>":"未推荐";

		$listdb[]=$rs;
	}

}elseif($action=='close_want'){
	
	if(!$wv_id) showerr("非法访问");
	$db->query("update {$_pre}vendor_want set is_show=0 where wv_id='$wv_id';");
	refreshto("?action=want","操作成功");
}elseif($action=='open_want'){
	
	if(!$wv_id) showerr("非法访问");
	$db->query("update {$_pre}vendor_want set is_show=1 where wv_id='$wv_id';");
	refreshto("?action=want","操作成功");

}elseif($action=='del_want'){
	
	if(!$wv_id) showerr("非法访问");
	$db->query("delete from  {$_pre}vendor_want  where wv_id='$wv_id';");
	refreshto("?action=want","操作成功");

}elseif($action=='levels_want'){
	
	if(!$wv_id) showerr("非法访问");
	$rsdb=$db->get_one("select * from {$_pre}vendor_want where  wv_id='$wv_id'");
	$is_levels=$rsdb[is_levels]?0:1;
	$db->query("update {$_pre}vendor_want set is_levels=$is_levels where wv_id='$wv_id';");
	refreshto("?action=want","操作成功");
}

//******************************************输出
require("head.php");
require("template/vendor/list.htm");
require("foot.php");
	

?>