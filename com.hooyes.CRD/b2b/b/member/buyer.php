<?php
require(dirname(__FILE__)."/"."global.php");

$rt=$db->get_one("select renzheng,title,uid,rid from `{$_pre}company` where uid='$lfjuid'");
if(!$rt){	//无商家信息
	showerr("抱歉，您还没有登记商家信息。<br>点击这里【<a href='$Murl/post_company.php?' target=_blank>登记商家</a>】");
}
$homepage=$db->get_one("select * from {$_pre}homepage where rid='$rt[rid]' limit 1");
if(!$homepage[hid]) { //激活商家信息
	showerr("您的商铺还没有激活，点击这里 [ <a href='$Mdomain/myhomepage.php' target='_blank'>激活</a> ]");
}
if(!$job){
	//得到我自己的分类
		$query=$db->query("select * from `{$_pre}mysort` where uid='$lfjuid' and ctype=1 order by listorder desc");
		while($rs=$db->fetch_array($query)){
			 $ck=$ms_id==$rs[ms_id]?" selected":"";
			 $ms_id_options.="<option value='$rs[ms_id]' $ck>$rs[sortname]</option>";
			 $mysort[$rs[ms_id]]=$rs[sortname];
		}
	//得到我的供应列表
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
		$rs[yz]=!$rs[yz]?"<font color=red>等待确定</font>":"<font color=blue>已经确定</font>";
		$rs[mysort]=$rs[ms_id]?$mysort[$rs[ms_id]]:"未分类";
		$rs[renzheng]=getrenzheng($rs[renzheng]);
		$rs[pm_username]=urlencode($rs[username]);
		$rs[picurl]=getimgdir($rs[picurl],3);
		$listdb[]=$rs;
	}	
	
	$showpage=getpage("{$_pre}vendor A left join {$_pre}company B on B.rid=A.owner_rid",$where,"?&yz=$yz&ms_id=$ms_id&keyword=".urlencode($keyword),$rows);
	
}elseif($job=='betch_move'){

	if(!is_array($id_db) || count($id_db)<1){
		showerr("请先选中至少一个项目");
	}
	if(!$to_ms_id) {
		showerr("请先选择要移到的分类");
	}
	$id_db=implode(",",$id_db);
	
	if($id_db){
		$db->query("update `{$_pre}vendor` set  ms_id='$to_ms_id' where vid in($id_db)");
	}
	refreshto("?","操作成功");
}elseif($job=='yz'){
	
	if(!$vid)showerr("请先选中至少一个项目");
	$yz=intval($yz);
	$db->query("update `{$_pre}vendor`  set yz='$yz'  where vid ='$vid' limit 1");
	refreshto("?","操作成功");
	
}elseif($job=='del'){
	if(!$vid)showerr("请先选中至少一个项目");
	$db->query("delete  from `{$_pre}vendor`   where vid ='$vid' limit 1");
	refreshto("?","操作成功");

}elseif($job=='betch_pm'){
	if($step){
		if(!$title){
			showerr("标题不能为空");
		}
		if(!$content  || strlen($content)>1000 ){
			showerr("内容不能为空,且不能超过500个字");
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
		refreshto("?","发送完毕");
	}

}



require(dirname(__FILE__)."/"."head.php");
require(dirname(__FILE__)."/"."template/buyer.htm");
require(dirname(__FILE__)."/"."foot.php");
?>