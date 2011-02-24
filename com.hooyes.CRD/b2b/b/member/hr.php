<?php
require(dirname(__FILE__)."/"."global.php");

if($job=='mylist'){ //我的职位列表

	$page=intval($page);
	if($page<1) $page=1;
	$rows=20;
	$min=($page-1)*$rows;
	$where=" where uid='$lfjuid' ";
	if($keyword) $where.=" and title like('%$keyword%')";
	$query=$db->query("select * from `{$_pre}hr_jobs` $where order by posttime desc limit $min,$rows");
	while($rs=$db->fetch_array($query)){
		$rs[posttime]=date("Y-m-d H:i",$rs[posttime]);
		$rs[best]=$rs[best]?"<font color='red'>[已被推荐]</font>":"";
		$rs[resume_num]=get_resume_num($rs[jobs_id]);
		$rs[check]=$rs[is_check]?"已审核":"未审核";
		$rs[resume_num]="0";
		$listdb[]=$rs;
	}
	$showpage=getpage("`{$_pre}hr_jobs`",$where,"?job=$job&keyword=".urlencode($keyword),$rows);

}elseif($job=='del_jobs'){ //删除职位

	if(!$jobs_id) showerr("参数不存在");	
	$db->query("DELETE from  {$_pre}hr_jobs  where jobs_id='$jobs_id'");
	refreshto("?job=mylist","删除成功");
	exit;

}elseif($job=='myyplist'){ //收到的简历
	
	$page=intval($page);
	if($page<1) $page=1;
	$rows=20;
	$min=($page-1)*$rows;

	$where=" where jobs_uid='$lfjuid' and td_type='td' ";
	if($jobs_id) $where.=" and jobs_id =`$jobs_id` ";
	$query=$db->query("select * from `{$_pre}hr_td` $where order by posttime desc limit $min,$rows");
	while($rs=$db->fetch_array($query)){
		$rs[posttime]=date("Y-m-d H:i",$rs[posttime]);
		$listdb[]=$rs;
	}
	$showpage=getpage("`{$_pre}hr_td`",$where,"?job=$job",$rows);

}elseif($job=='add_mytdlist'){ //把收到的简历放入人才库
	
	if(!$td_id) showerr("参数不存在");	
	$db->query("update   `{$_pre}hr_td`  set td_type='add'  where td_id='$td_id'");
	refreshto($FROMURL,"已经放入人才库");
	exit;

}elseif($job=='del_mytd'){ //删除投递或人才库内容
	
	if(!$td_id) showerr("参数不存在");	
	$db->query("DELETE from  `{$_pre}hr_td`  where td_id='$td_id'");
	refreshto($FROMURL,"删除成功");
	exit;

}elseif($job=='mytdlist'){ //我才人库

	$page=intval($page);
	if($page<1) $page=1;
	$rows=20;
	$min=($page-1)*$rows;

	$where=" where jobs_uid='$lfjuid' and td_type='add'  ";

	$query=$db->query("select * from `{$_pre}hr_td` $where order by posttime desc limit $min,$rows");
	while($rs=$db->fetch_array($query)){
		$rs[posttime]=date("Y-m-d H:i",$rs[posttime]);
		$listdb[]=$rs;
	}
	$showpage=getpage("`{$_pre}hr_td`",$where,"?job=$job",$rows);


}elseif($job=='myresume'){ //我的简历

	
	$where=" where uid='$lfjuid' ";

	$query=$db->query("select * from `{$_pre}hr_resume` $where order by posttime desc");
	while($rs=$db->fetch_array($query)){
		$rs[posttime]=date("Y-m-d H:i",$rs[posttime]);
		$rs[check]=$rs[is_check]?"已审核":"未审核";
		$listdb[]=$rs;
	}

}elseif($job=='del_myresume'){ //删除我的简历
	
	if(!$re_id) showerr("参数不存在");	
	$db->query("DELETE from  `{$_pre}hr_resume`  where re_id='$re_id'");
	refreshto($FROMURL,"删除成功");
	exit;
}elseif($job=='mytds'){ //我的求职经历

	$page=intval($page);
	if($page<1) $page=1;
	$rows=20;
	$min=($page-1)*$rows;

	$where=" where A.re_uid='$lfjuid' and A.jobs_id>0 and A.jobs_uid>0 ";

	$query=$db->query("select A.*,B.companyname,B.uid from `{$_pre}hr_td` A left join `{$_pre}hr_jobs` B on A.jobs_id=B.jobs_id  $where order by A.posttime desc limit $min,$rows");
	while($rs=$db->fetch_array($query)){
		$rs[posttime]=date("Y-m-d H:i",$rs[posttime]);
		$listdb[]=$rs;
	}
	$showpage=getpage("`{$_pre}hr_td` A ",$where,"?job=$job",$rows);

}


function get_resume_num($jobs_id){
	global $_pre,$db;
	$num=$db->get_one("select count(*) as num from `{$_pre}hr_td` where `jobs_id`='$jobs_id' and `jobs_uid`>0 and td_type='td'");
	return $num[num];
}




require(dirname(__FILE__)."/"."head.php");
require(dirname(__FILE__)."/"."template/hr.htm");
require(dirname(__FILE__)."/"."foot.php");
?>