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
		$query=$db->query("select * from `{$_pre}mysort` where uid='$lfjuid' and ctype=2 order by listorder desc");
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
		$rs[thisaction]=!$rs[yz]?"确定供应关系":"暂停供应关系";
		$rs[yz]=!$rs[yz]?"<font color=red>未确定</font>":"<font color=blue>已经确定</font>";
		$rs[mysort]=$rs[ms_id]?$mysort[$rs[ms_id]]:"未分类";
		$rs[renzheng]=getrenzheng($rs[renzheng]);
		$rs[pm_username]=urlencode($rs[username]);
		$rs[picurl]=getimgdir($rs[picurl],3);
		$listdb[]=$rs;
	}	
	
	$showpage=getpage("{$_pre}vendor A left join {$_pre}company B on B.rid=A.rid",$where,"?&yz=$yz&ms_id=$ms_id&keyword=".urlencode($keyword),$rows);
	
	//额外 急寻开启状态提示
	$vendor_open="";
	$rsdb=$db->get_one("select * from {$_pre}vendor_want where uid='$lfjuid' limit 1");
	if($rsdb[is_show] && $rsdb[endtime]>$timestamp) $vendor_open='<img src="images/want_vendor_open.gif"  border="0"/>';
	
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
	$timestamp=$yz?$timestamp:"0";
	$db->query("update `{$_pre}vendor`  set yz='$yz',yztime='$timestamp'  where vid ='$vid' limit 1");
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
		if(!$content || strlen($content)>1000 ){
			showerr("内容不能为空,且不能超过500个字");
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
		refreshto("?","发送完毕");
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
		$w_title=$rsdb[w_title]?$rsdb[w_title]:$rt[title]."急寻供应商";

	}else{
		
		if(!$w_title ||  strlen($w_title)>40) showerr("标题只能是小于40个字符之内，且不能为空");
		if(!preg_match("/[0-9]{4}-[0-9]{1,2}-[0-9]{1,2} [0-9]{1,2}:[0-9]{1,2}:[0-9]{1,2}/",$starttime))showerr("时间格式不正确，请按照正确格式填写");
		$howlong=intval($howlong);
		if($howlong<1){	showerr("必须在1天或者更多之后停止展示");}
		
		$starttime_1=explode(" ",$starttime);
		$starttime_2=explode("-",$starttime_1[0]);
		$starttime_3=explode(":",$starttime_1[1]);
		$starttime=mktime(intval($starttime_3[0]),intval($starttime_3[1]),intval($starttime_3[2]),$starttime_2[1],$starttime_2[2],$starttime_2[0]);
		$endtime=$starttime+($howlong*24*60*60);

		if(!$wv_id){
			//添加
			$yz=isset($webdb[vendor_want_yz])?$webdb[vendor_want_yz]:1;
			$db->query("INSERT INTO `{$_pre}vendor_want` ( `wv_id` , `uid` , `username` , `rid` , `w_title` , `w_renzheng` , `w_agent` , `w_vip` , `posttime` , `starttime` , `endtime` , `yz` , `is_show` ,`is_levels`) VALUES ('', '$lfjuid', '$lfjid', '$rt[rid]', '$w_title', '$w_renzheng', '$w_agent', '$w_vip', '$timestamp', '$starttime', '$endtime', '$yz', '$is_show',0);");	

		}else{
			//修改
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
	
		refreshto("?","操作成功");
	
	}

}



require(dirname(__FILE__)."/"."head.php");
require(dirname(__FILE__)."/"."template/vendor.htm");
require(dirname(__FILE__)."/"."foot.php");
?>