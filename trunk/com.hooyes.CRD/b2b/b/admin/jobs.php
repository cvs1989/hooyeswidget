<?php
require_once("global.php");
@require_once("../php168/all_hrfid.php");


$linkdb=array("人力资源栏目管理"=>"?","职位库"=>"jobs.php?job=zhiwei","人才库"=>"jobs.php?job=rencai","预选数据管理"=>"jobs.php?job=data");


if(!$job){

	$listdb=array();
	list_allsort();
	$sup_select=choose_newssort(0,0,0);
	
}elseif($job=='addsort'){
	if(!$sname) showerr("类目名称不能为空");
	$sname=explode("\r",$sname);
	foreach($sname as $name){
		$name=trim(htmlspecialchars($name));
		$db->query("INSERT INTO `{$_pre}hr_sort` ( `hr_sid` , `sname` , `sup` , `sup_all` , `class` , `hits` , `hot` , `order_sort` ) VALUES (
'', '$name', '$sup', '$sup_all', '$class', '0', '0', '$order_sort');");	
	}	
	hrsid_cache();
	refreshto("?","添加成功");
	
}elseif($job=='betch_sort'){

	foreach($order_sort as $key=>$value){
		$db->query("UPDATE `{$_pre}hr_sort` SET `order_sort` = '$value' WHERE `hr_sid` ='$key'");
	}
	hrsid_cache();
	refreshto("?","修改成功");
	
	
}elseif($job=='setbest'){

	if(!$hr_sid) showerr("操作失败");
	$rt=$db->get_one("SELECT * FROM {$_pre}hr_sort WHERE hr_sid='$hr_sid'");
	$db->query("update {$_pre}hr_sort  set `hot`='".($rt[hot]?"0":"1")."' WHERE hr_sid='$hr_sid'");
	hrsid_cache();
	refreshto("?","操作成功");
	
}elseif($job=='edit'){
	if(!$hr_sid) showerr("操作失败");
	if(!$step){
		$data=$db->get_one("SELECT  *  FROM {$_pre}hr_sort WHERE hr_sid='$hr_sid'");
		$sup_select=choose_newssort(0,0,$data[sup]);
	}else{
		if(!$sname) showerr("类目名称不能为空");
		$db->query("update {$_pre}hr_sort set `sname`='$sname' ,`sup`='$sup',`order_sort`='$order_sort' where hr_sid='$hr_sid' ");
		hrsid_cache();
		refreshto("?","操作成功");
	}
	
}elseif($job=='del'){

	if(!$hr_sid) showerr("操作失败");
	$rt=$db->get_one("SELECT count(*) as num FROM {$_pre}hr_sort WHERE sup='$hr_sid'");
	if($rt[num]>0) showerr("非空类目不能删除");
	
	$db->query("delete from  {$_pre}hr_sort  where hr_sid='$hr_sid' ");
	hrsid_cache();
	refreshto("?","操作成功");
	
}elseif($job=='zhiwei'){
	
	$sup_select=choose_newssort(0,0,$sup);
	if($hr_sid) $sup=intval($hr_sid);
	$page=intval($page);
	if($page<1) $page=1;
	$rows=20;
	$min=($page-1)*$rows;
	$where=" where  1 ";
	if($keyword) $where.=" and ( title like('%$keyword%') or  companyname like('%$keyword%'))";
	if($sup) $where.=" and sid='$sup' ";
	$query=$db->query("select * from `{$_pre}hr_jobs` $where order by posttime desc limit $min,$rows");
	while($rs=$db->fetch_array($query)){
		$rs[posttime]=date("Y-m-d H:i",$rs[posttime]);
		$rs[best]=$rs[best]?"<font color='red'>[已推荐]</font>":"未推荐";
		$rs[check]=$rs[is_check]?"<font color='red'>已审核</a>":"<font color='#455667'>未审核</a>";
		$rs[resume_num]="0";
		$listdb[]=$rs;
	}
	$showpage=getpage("`{$_pre}hr_jobs`",$where,"?job=$job&sup=$sup&keyword=".urlencode($keyword),$rows);
	
	
}elseif($job=='job_setbest'){	
	
	$jobs=$db->get_one("select * from `{$_pre}hr_jobs` where jobs_id='$jobs_id'");
	if(!$jobs) showerr("操作失败");
	$db->query("update   {$_pre}hr_jobs  set best='".($jobs[best]?"0":"1")."' where jobs_id='$jobs_id' ");
	refreshto("?job=zhiwei","操作成功");	
	
}elseif($job=='job_check'){	
	
	$jobs=$db->get_one("select * from `{$_pre}hr_jobs` where jobs_id='$jobs_id'");
	if(!$jobs) showerr("操作失败");
	$db->query("update   {$_pre}hr_jobs  set is_check='".($jobs[is_check]?"0":"1")."' where jobs_id='$jobs_id' ");
	refreshto("?job=zhiwei","操作成功");
	
}elseif($job=='job_del'){	
	
	$rsdb=$db->get_one("select * from   {$_pre}hr_jobs  where jobs_id='$jobs_id'");
	$db->query("delete from    {$_pre}hr_jobs  where jobs_id='$jobs_id' ");
	
	$array[touid]=$rsdb[uid];
	$array[fromuid]=0;
	$array[fromer]='系统消息';
	$array[title]='您的一条招聘信息已经被管理员删除';
	$array[content]="{$rsdb[username]}您好!<br>您提交的$rsdb[title] 已经被管理员删除。详情请联系管理员 ";
	if(function_exists('pm_msgbox')){
		pm_msgbox($array);
	}
	
	refreshto("?job=zhiwei","操作成功");
	
}elseif($job=='rencai'){

	$sup_select=choose_newssort(0,0,$sup);
	if($hr_sid) $sup=intval($hr_sid);
	$page=intval($page);
	if($page<1) $page=1;
	$rows=20;
	$min=($page-1)*$rows;
	$where=" where  1 ";
	if($keyword) $where.=" and ( job_name like('%$keyword%') or  truename like('%$keyword%'))";
	if($sup) $where.=" and sid='$sup' ";
	$query=$db->query("select * from `{$_pre}hr_resume` $where order by posttime desc limit $min,$rows");
	while($rs=$db->fetch_array($query)){
		$rs[posttime]=date("Y-m-d H:i",$rs[posttime]);
		$rs[best]=$rs[best]?"<font color='red'>[已推荐]</font>":"未推荐";
		$rs[check]=$rs[is_check]?"<font color='red'>已审核</a>":"<font color='#455667'>未审核</a>";

		$listdb[]=$rs;
	}
	$showpage=getpage("`{$_pre}hr_resume`",$where,"?job=$job&sup=$sup&keyword=".urlencode($keyword),$rows);

}elseif($job=='resume_setbest'){	
	
	$resume=$db->get_one("select * from `{$_pre}hr_resume` where re_id='$re_id'");
	if(!$resume) showerr("操作失败");
	$db->query("update   {$_pre}hr_resume  set best='".($resume[best]?"0":"1")."' where re_id='$re_id' ");
	refreshto("?job=rencai","操作成功");	
	
}elseif($job=='resume_check'){	
	if(is_array($listdb)){
		$where = " re_id IN (".implode(',',$listdb).") ";
	}else{
		$where = " re_id='$listdb' ";
	}
	if(!is_array($listdb)){
	    $resume=$db->get_one("SELECT * FROM `{$_pre}hr_resume` WHERE $where");
	    if(!$resume) showerr("操作失败");
	    $db->query("UPDATE {$_pre}hr_resume SET is_check='".($resume[is_check]?"0":"1")."' where $where ");
	}else{
		$query = $db->query("SELECT re_id,is_check FROM `{$_pre}hr_resume` WHERE $where ");
		while($row = $db->fetch_array($query)){
			if($db->get_one("SELECT re_id FROM `{$_pre}hr_resume` WHERE re_id='$row[re_id]'")){
				$db->query("UPDATE `{$_pre}hr_resume` SET is_check='".($row['is_check'] ? '0' : '1')."' WHERE re_id='$row[re_id]'");
			}
		}
	}
	refreshto("?job=rencai","操作成功");
	
}elseif($job=='resume_del'){	
	
	$rsdb=$db->get_one("select * from   {$_pre}hr_resume  where re_id='$re_id'");
	
	
	$array[touid]=$rsdb[uid];
	$array[fromuid]=0;
	$array[fromer]='系统消息';
	$array[title]='您的一条招聘信息已经被管理员删除';
	$array[content]="{$rsdb[username]}您好!<br>您提交的{$rsdb[job_name]}($rsdb[truename]) 已经被管理员删除。详情请联系管理员 ";
	if(function_exists('pm_msgbox')){
		pm_msgbox($array);
	}
	
	$db->query("delete from    {$_pre}hr_resume  where re_id='$re_id' ");
	refreshto("?job=rencai","操作成功");	

		

}elseif($job=='data'){
		
		if(!$step){
			if(file_exists("../php168/jobData.php")){
				@require_once("../php168/jobData.php");
			}
			require("head.php");
			require("template/jobs/data.htm");
			require("foot.php");
			exit;

		}else{

			$writefile="<?php\r\n";

			foreach($postData as $key=>$val){
				if($val[name]!=''){
					$writefile.=" \$jobData[$key][value]='".str_replace(array('"',"'"),array('',""),trim(htmlspecialchars($val[value])))."'; \r\n";
					$writefile.=" \$jobData[$key][name]='".str_replace(array('"',"'"),array('',""),trim(htmlspecialchars($val[name])))."'; \r\n";	
					$writefile.=" \$jobData[$key][choose_to]='".$val[choose_to]."'; \r\n";	
					$writefile.=" \$jobData[$key][form_type]='".$val[form_type]."'; \r\n";
					$writefile.=" \$jobData[$key][remarks]='".str_replace(array('"',"'"),array('\"',"\'"),trim(htmlspecialchars($val[remarks])))."'; \r\n\r\n";	
					$writefile.=" /***********************************************************************/\r\n";			
				}
			}			
			write_file("../php168/jobData.php",$writefile." \r\n?>");
			
			refreshto("?job=$job","保存成功");		
		}
}elseif($job=='resetjobdata'){
	@unlink("../php168/jobData.php");
	@copy("../php168/bak_jobData.php","../php168/jobData.php");
	refreshto("?job=data","成功恢复");			
}

	require("head.php");
	require("template/jobs/list.htm");
	require("foot.php");


//类目俄列表
function choose_newssort($hr_sid,$class,$ck=0)
{
	global $db,$_pre;
	for($i=0;$i<$class;$i++){
		$icon.="&nbsp;&nbsp;&nbsp;&nbsp;";
	}
	$class++;          //AND type=1
	$query = $db->query("SELECT * FROM {$_pre}hr_sort WHERE sup='$hr_sid' ORDER BY order_sort asc,hr_sid asc LIMIT 500");
	while($rs = $db->fetch_array($query)){
		$ckk=$ck==$rs[hr_sid]?' selected ':'';
		$style=$rs[sup]==0?" style='color:red; background-color:#f0f0f0'":"";
		$fup_select.="<option value='$rs[hr_sid]' $ckk  $style>$icon|-$rs[sname]</option>";
		$fup_select.=choose_newssort($rs[hr_sid],$class,$ck);
	}
	return $fup_select;
}

/*栏目列表*/
function list_allsort($hr_sid ,$Class){
	global $db,$_pre,$listdb;
	$Class++;
	
	$query=$db->query("SELECT S.* FROM {$_pre}hr_sort S  where S.sup='$hr_sid'  ORDER BY S.order_sort asc,S.hr_sid asc");
	while( $rs=$db->fetch_array($query) ){
		$icon="";
		for($i=1;$i<$Class;$i++){
			$icon.="&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;";
		}
		if($rs['class']!=$Class){
			$db->query("UPDATE {$_pre}hr_sort SET class='$Class' WHERE hr_sid='$rs[hr_sid]'");
		}
		if($icon){
			$icon=substr($icon,0,-24);
			$icon.="--";
		}
		$rs[icon]=$icon;
		$rs[best]=$rs[hot]?"<font color='red'>是</font>":"<font color='#676767'>否</font>";
		$style=$rs[sup]==0?" style='color:red; background-color:#f0f0f0'":"";
		if($rs[sup]==0) $rs[sname]="<strong $style>$rs[sname]</strong>";
		$listdb[]=$rs;
		list_allsort($rs[hr_sid],$Class);
	}
}
?>