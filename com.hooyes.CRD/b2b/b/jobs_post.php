<?php
require_once("global.php");




if($id)$id=intval($id);
if($sid)$sid=intval($sid);
if($jobs_id)$jobs_id=intval($jobs_id);
if($re_id)$re_id=intval($re_id);
/**/
@require_once(Mpath."php168/all_hrfid.php");
@require_once(Mpath."php168/jobData.php");
	/**************************************************招聘*****/	
	/**************************************************招聘*****/
if($job=='jobs'){
		
		if(!$lfjid ){ 
			if(!$web_admin) showerr("您还没有登陆，请登陆....");
			exit;
		}

		$rt=$db->get_one("select * from `{$_pre}company` where uid='$lfjuid'");
		
		$myjobs=$db->get_one("select count(*) as num from {$_pre}hr_jobs where `rid`='$rt[rid]';");
		$webdb[usersJobsMax]=$webdb[usersJobsMax]?$webdb[usersJobsMax]:20;
		if($myjobs[num] >= $webdb[usersJobsMax]) 
		{
			//超过最大
			showerr("抱歉，您已经有$myjobs[num]条招聘信息了，不能再添加了。");
		}


		if(!$step){
			$hrDB[title]="人才招聘频道 -> 人才招聘";
			$jobs_sort_1="<select name='job_sort[1]' onchange='choose_jobSort(this.options[this.selectedIndex].value,1)'> <option value='0'>请选择</option>";
			foreach($hrFid_db[0] as $key=>$val){
				$jobs_sort_1.="<option value='$key'>$val</option>";
			}
			$jobs_sort_1.="</select><span id='show_jobs_sort_2'></span>";
			$province_fid=select_where("province","'postdb[province_id]'  onChange=\"choose_where(this.options[this.selectedIndex].value,'','')\" ");	
			$jobs_form_template=create_jobData_form('job','',true);
			if($rt[rid]){
			$company_contact=ReplaceHtmlAndJs(str_replace("<br>","\r\n",create_contact($rt)));
			$companyintro   =@preg_replace('/<([^<]*)>/is',"",$rt[content]);
			$companyname    =$rt[title];
			}else{
			$company_contact="联系人：\r\n电  话：\r\n传  真：\r\n邮  箱：\r\n主  页：";
			$companyintro="";
			}
			//设置提交
			$step=1;
		}else{
			
			if(!$webdb[allowUserPostJob]){				
				if(!$rt[title]) $msg="请先登记商家资料再发布";
			}else{
				if($companyname) $rt[title]=$companyname;
				if($companyintro) $rt[content]=htmlspecialchars($companyintro);
			    if($company_contact) $company_contact=htmlspecialchars($company_contact);
				if(!$rt[title]){ if(!$company_contact) $msg="请准确的填写招聘单位的联系方式<br>";}
			}
			//检查数据
		
			
			if(count($job_sort)<2) $msg="请选择职位发布的类目<br>";
					
			$sid_all=implode(",",$job_sort);
			$sid=$job_sort[(count($job_sort)-1)];
			$title=htmlspecialchars($title);
			$city=$postdb[province_id].",".$postdb[city_id];
			$other_data=serialize($other_data);
			
			$hr_work=htmlspecialchars($hr_work);
			$hr_info=htmlspecialchars($hr_info);
			$is_check=$webdb[checkUserPostJob];
			$sname=$hrFid_db[name][$sid];
			
			//处理数据
			
			if(!$msg){
				$db->query("INSERT INTO `{$_pre}hr_jobs` ( `jobs_id` , `rid` , `companyname` , `companyintro`,`company_contact` , `uid` , `title` , `sid` , `sid_all` , `sname` , `city` , `other_data` , `hr_work` , `hr_info` , `is_check` , `best` , `hits` , `posttime` ) 
VALUES (
'', '$rt[rid]', '$rt[title]', '$rt[content]','$company_contact', '$lfjuid', '$title', '$sid', '$sid_all', '$sname', '$city', '$other_data', '$hr_work', '$hr_info', '$is_check', '0', '0', '".time()."'
);");
			$jobs_id=$db->insert_id();
			//奖励积分
			if($webdb[post_add_money]) plus_money($lfjuid,$webdb[post_add_money]);
					
			echo "<SCRIPT LANGUAGE=\"JavaScript\">
			<!--
			parent.location='$Mdomain/jobsshow.php?id=$jobs_id';	
			parent.location.href='$Mdomain/jobsshow.php?id=$jobs_id';		
			//-->
			</SCRIPT>";exit;
			
			}
			echo "<SCRIPT LANGUAGE=\"JavaScript\">
			<!--
			parent.document.getElementById(\"Submit\").disabled=false;
			parent.document.getElementById(\"showmsg\").innerHTML='$msg';			
			//-->
			</SCRIPT>";exit;
		}
		
		
	/**************************************************简历*****/	
	/**************************************************简历*****/	
}elseif($job=='resume'){
		$myResume=$db->get_one("select count(*) as num from {$_pre}hr_resume where uid='$lfjuid';");
		$webdb[usersRresumeMax]=$webdb[usersRresumeMax]?$webdb[usersRresumeMax]:3;
		if($myResume[num] >= $webdb[usersRresumeMax]) 
		{
			//超过最大简历
			showerr("抱歉，您已经有$myResume[num]份简历了，不能再添加了。");
		}
		if(!$step){


			$hrDB[title]="人才招聘频道 -> 简历中心";
			$jobs_sort_1="<select name='job_sort[1]' onchange='choose_jobSort(this.options[this.selectedIndex].value,1)'> <option value='0'>请选择</option>";
			foreach($hrFid_db[0] as $key=>$val){
				$jobs_sort_1.="<option value='$key'>$val</option>";
			}
			$jobs_sort_1.="</select><span id='show_jobs_sort_2'></span>";
			$province_fid=select_where("province","'postdb[province_id]'  onChange=\"choose_where(this.options[this.selectedIndex].value,'','')\" ");	
			$province_fid_adc=select_where("province","'postdb[province_id]'  onChange=\"choose_where(this.options[this.selectedIndex].value,'','adc_')\" ");	
			
			$jobs_form_template=create_jobData_form('resume','',false);
			$step=1;
		}else{
			
			//检查数据
			if(count($job_sort)<2) $msg="请选择职位发布的类目<br>";
			$sid_all=implode(",",$job_sort);
			$sid=$job_sort[(count($job_sort)-1)];
			
			$truename=htmlspecialchars($truename);
			if(!$truename)$msg="真实姓名不能为空<br>";
			$eng_name=htmlspecialchars($eng_name);
			$address=htmlspecialchars($address);
			$phones=htmlspecialchars($phones);
			if(!$phones) $msg="联系电话不能为空<br>";
			if(!$email)  $msg="邮箱地址簿不能为空<br>";
			if(!preg_match("/.*@.*/",$email)) $msg="邮箱地址簿不符合格式<br>";
			$myinfo  =htmlspecialchars($myinfo);
			$work_his=htmlspecialchars($work_his);
			$edu_his =htmlspecialchars($edu_his);
			$sname   =$hrFid_db[name][$sid];
			$city=$postdb[province_id].",".$postdb[city_id];
			$is_check=$webdb[checkUserPostResume];
			$job_name=htmlspecialchars($job_name);
			$other_data=serialize($other_data);
			//照片上传
			if(is_uploaded_file($_FILES[postfile][tmp_name])){
				$array[name]=is_array($postfile)?$_FILES[postfile][name]:$postfile_name;
				$array[path]=$webdb[updir]."/{$Imgdirname_resume}/";
				$array[size]=is_array($postfile)?$_FILES[postfile][size]:$postfile_size;
				$webdb[iconMaxUserPostResume]=$webdb[iconMaxUserPostResume]?$webdb[iconMaxUserPostResume]:50;
				if($array[size]>($webdb[iconMaxUserPostResume]*1024))$msg="图片大小不能超过$webdb[iconMaxUserPostResume]k<br>";
				$icon=upfile_func2(is_array($postfile)?$_FILES[postfile][tmp_name]:$postfile,$array);
				if(substr($icon,0,3)=='ERR')	{
					$msg.=str_replace("ERR-","",$icon)."<br>";
				}else{
					if($icon){//更换图片
									@unlink(PHP168_PATH."/".$array[path].$old_icon);
					}else{
									$icon=$old_icon;
					}
				}
				
			}else{
				$icon="";
			}
			//处理数据
			
			if(!$msg){
				$db->query("INSERT INTO `{$_pre}hr_resume` ( `re_id` , `sid` , `sid_all` , `uid` ,`username`, `posttime` , `hits` , `best` , `is_check` , `truename` , `eng_name`,`icon` , `city` , `address` , `phones` , `email` , `website` , `job_name`,`other_data` , `myinfo` , `work_his` , `edu_his` ) VALUES (
'', '$sid', '$sid_all', '$lfjuid','$lfjid', '".time()."', '0', '0', '$is_check', '$truename', '$eng_name','$icon', '$city', '$address', '$phones', '$email', '$website','$job_name', '$other_data', '$myinfo', '$work_his', '$edu_his');");
				
				$re_id=$db->insert_id();

					
				if($jobs_id){
				
					echo "<SCRIPT LANGUAGE=\"JavaScript\">
					<!--
					parent.location='$Mdomain/jobs_post.php.php?job=postresume2&re_id=$re_id&jobs_id=$jobs_id';	
					parent.location.href='$Mdomain/jobs_post.php?job=postresume2&re_id=$re_id&jobs_id=$jobs_id';		
					//-->
					</SCRIPT>";
								
				}else{
					echo "<SCRIPT LANGUAGE=\"JavaScript\">
					<!--
					parent.location='$Mdomain/resumeshow.php?id=$re_id';	
					parent.location.href='$Mdomain/resumeshow.php?id=$re_id';		
					//-->
					</SCRIPT>";
					exit;
				}
			}
			echo "<SCRIPT LANGUAGE=\"JavaScript\">
			<!--
			parent.document.getElementById(\"showmsg\").innerHTML='$msg';	
			parent.document.getElementById('postSubmit').disabled=false;
			//-->
			</SCRIPT>";
			exit;
		}
		

}elseif($job=='postresume'){
	$hrDB[title]="人才招聘频道 -> 投递简历";
	//得到我的简历.每个人又可能有多个简历
	if($lfjuid){
		$query=$db->query("select * from {$_pre}hr_resume where uid='$lfjuid' and is_check=1 order by posttime desc;");
		
		while($rs=$db->fetch_array($query)){
			$rs[title]=$rs[truename]." ".$rs[job_name];
			$rs[posttime]=date("Y-m-d H:i:s",$rs[posttime]);
			$resumeList[]=$rs;
		}
	}
	if(!$resumeList) {
		header("Location:jobs_post.php?job=resume&jobs_id=$jobs_id");
	}


}elseif($job=='postresume2'){	
	
	
	if(!$jobs_id) showerr("抱歉，页面出错啦!");
	if(!$re_id)   showerr("请选中一份简历!");
	//得到招聘信息
	$jobs=$db->get_one("select * from {$_pre}hr_jobs where jobs_id='$jobs_id' and is_check=1");
	if(!is_array($jobs)) showerr("抱歉，无法查找招聘信息.");
	if($jobs[uid]==$lfjuid) showerr("抱歉，不能投递自己的招聘信息.");
	//简历
	$re=$db->get_one("select * from {$_pre}hr_resume where re_id='$re_id' and is_check=1");
	if(!is_array($re))   showerr("抱歉，无法查找您选择的简历信息.");
	
	//检查是否已经投递过，在规定时间内
	$postresumeMaxDay=$webdb[postresumeMaxDay]!=''?$webdb[postresumeMaxDay]:7;
	$tdhis=$db->get_one("select count(*) as num from {$_pre}hr_td where re_id='$re_id' and jobs_id='$jobs_id' and  td_type='add' and posttime >".($postresumeMaxDay*24*60*60));
	if($tdhis[num]>0){
		showerr("抱歉，{$postresumeMaxDay}天内不能对同一条招聘信息发送同一份简历.");
	}
	//投递操作
	$db->query("INSERT INTO `{$_pre}hr_td` ( `td_id`,`td_type` ,`rid`, `jobs_id` , `re_id` , `jobs_uid` , `re_uid` , `posttime` , `jobs_title` , `re_title` ) VALUES ('','td','$jobs[rid]', '$jobs_id', '$re_id', '$jobs[uid]', '$re[uid]', '".time()."', '$jobs[title]', '$re[job_name]($re[truename])'  );");
	
		
	refreshto("jobsshow.php?id=$jobs_id","简历投递成功",5);
	exit;


}elseif($job=='add_td'){	
	if(!$lfjid ){ 
			if(!$web_admin) showerr("您还没有登陆，请登陆....");
			exit;
		}

	if(!$re_id)   showerr("抱歉，页面出错啦!");

	//简历
	$re=$db->get_one("select * from {$_pre}hr_resume where re_id='$re_id' and is_check=1");
	if(!is_array($re))   showerr("抱歉，无法查找简历信息.");
	if($re[uid]==$lfjuid)showerr("抱歉，不能把自己的简历加入到自己的人才库.");
	//检查是否已经加入过，在规定时间内
	$postresumeMaxDay=$webdb[postresumeMaxDay]!=''?$webdb[postresumeMaxDay]:7;
	$tdhis=$db->get_one("select count(*) as num from {$_pre}hr_td where re_id='$re_id' and jobs_uid='$lfjuid' and  td_type='add' and posttime >".($postresumeMaxDay*24*60*60));
	if($tdhis[num]>0){
		showerr("抱歉，{$postresumeMaxDay}天内不能对同一简历重复添加.");
	}
	
	//投递操作
	$db->query("INSERT INTO `{$_pre}hr_td` ( `td_id` ,`td_type`,`rid`, `jobs_id` , `re_id` , `jobs_uid` , `re_uid` , `posttime` , `jobs_title` , `re_title` ) VALUES ('','add','', '$jobs_id', '$re_id', '$lfjuid', '$re[uid]', '".time()."', '', '$re[job_name]($re[truename])'  );");
	
		
	refreshto("resumeshow.php?id=$re_id","添加成功");
	exit;
	
}elseif($job=='update_posttime'){
	if(!$lfjid ){ 
		if(!$web_admin) showerr("您还没有登陆，请登陆....");
		exit;
	}
	if(!$re_id) showerr("非法访问");
	$updateresumeMaxHour=$webdb[updateresumeMaxHour]!=''?$webdb[updateresumeMaxHour]:24;
	$re=$db->get_one("select * from {$_pre}hr_resume where re_id='$re_id' and is_check=1");
	if(!is_array($re))   showerr("抱歉，无法查找您选择的简历信息.");
	if($re[posttime]>(time() - $updateresumeMaxHour*60*60)){
		 showerr("抱歉，{$updateresumeMaxHour}小时内您的简历只能更新一次.");
	}else{
		$re=$db->query("update {$_pre}hr_resume  set `posttime`='".time()."' where re_id='$re_id'");
	}
	
			
	refreshto("resumeshow.php?id=$re_id","更新成功");
	exit;

}elseif($job=='update_posttime_jobs'){
	if(!$lfjid ){ 
		if(!$web_admin) showerr("您还没有登陆，请登陆....");
		exit;
	}
	if(!$jobs_id) showerr("非法访问");
	$updateresumeMaxHour=$webdb[updateresumeMaxHour]!=''?$webdb[updateresumeMaxHour]:24;
	$re=$db->get_one("select * from {$_pre}hr_jobs where jobs_id='$jobs_id' and is_check=1");
	if(!is_array($re))   showerr("抱歉，无法查找您选择的简历信息.");
	if($re[posttime]>(time() - $updateresumeMaxHour*60*60)){
		 showerr("抱歉，{$updateresumeMaxHour}小时内您的职位信息只能更新一次.");
	}else{
		$re=$db->query("update {$_pre}hr_jobs  set `posttime`='".time()."' where jobs_id='$jobs_id'");
	}			
	refreshto("jobsshow.php?id=$jobs_id","更新成功");
	exit;	

}elseif($job=='edit_jobs'){
	if(!$lfjid ){ 
		if(!$web_admin) showerr("您还没有登陆，请登陆....");
		exit;
	}
	if(!$jobs_id) showerr("你访问的页面不存在");
	
	$data=$db->get_one("select * from {$_pre}hr_jobs where jobs_id='$jobs_id'");
	if($data[uid]!=$lfjuid && !$web_admin) showerr("您无权修改");

	if(!$step){
		foreach($data as $key=>$val){
			$$key=$val;
		}
		
	
		$sid_all=explode(",",$sid_all);
		$jobs_sort_1="<select name='job_sort[1]' onchange='choose_jobSort(this.options[this.selectedIndex].value,1)'> <option value='0'>请选择</option>";
			foreach($hrFid_db[0] as $key=>$val){
				$jobs_sort_1.="<option value='$key' ".($key==$sid_all[0]?" selected":"").">$val</option>";
			}
		$jobs_sort_1.="</select><span id='show_jobs_sort_2'>".choose_jobsort($sid_all[0],$sid_all[1],"sid[]",2)."</span>";
		
		
		
		//地区
		$city=explode(",",$city);
		$province_fid=select_where("province","'postdb[province_id]]'  onChange=\"choose_where(this.options[this.selectedIndex].value,'','')\" ",$city[0],0);	
		$city_fid    =select_where("city","'postdb[city_id]'  onChange=\"choose_where(this.options[this.selectedIndex].value,'','1')\"",$city[1],$city[0]);
	
	
		
		
		$companyintro=@preg_replace('/<([^<]*)>/is',"",$companyintro);
		
		//模型
		$other_data=unserialize($other_data);
		$jobs_form_template=create_jobData_form('job',$other_data,true);
		
		//设置步骤
		$step=1;
	}else{
		//检查数据
			
			if(count($job_sort)<2)     $msg.="请选择职位发布的类目<br>";
			$sid_all=implode(",",$job_sort);
			$sid=$job_sort[(count($job_sort)-1)];
			$title=htmlspecialchars($title);
			$city=$postdb[province_id].",".$postdb[city_id];
			$other_data=serialize($other_data);
			
			$hr_work=htmlspecialchars($hr_work);
			$hr_info=htmlspecialchars($hr_info);
			$is_check=$webdb[checkUserPostJob];
			$sname=$hrFid_db[name][$sid];	
					
			if(!$companyname)     $msg.="单位名称不能为空<br>";
			if(!$companyintro)    $msg.="单位介绍不能为空<br>";
			if(!$company_contact) $msg.="请准确的填写招聘单位的联系方式<br>";
			if(!$title)           $msg.="职位名称不能为空<br>";
			if(!$hr_work)         $msg.="岗位职责必须填写<br>";
			if(!$hr_info)         $msg.="能力要求必须填写<br>";
			
			if(!$msg){
				$db->query("UPDATE {$_pre}hr_jobs  set
				`companyname`='$companyname',
				`companyintro`='$companyintro',
				`company_contact`='$company_contact',
				`title`='$title',
				`sid`='$sid',
				`sid_all`='$sid_all',
				`sname`='$sname',
				`city`='$city',
				`other_data`='$other_data',
				`hr_work`='$hr_work',
				`hr_info`='$hr_info',
				`posttime`='".time()."'				
				where jobs_id='$jobs_id'");
			
			
				echo "<SCRIPT LANGUAGE=\"JavaScript\">
				<!--
				parent.location='$Mdomain/jobsshow.php?id=$jobs_id';	
				parent.location.href='$Mdomain/jobsshow.php?id=$jobs_id';		
				//-->
				</SCRIPT>";exit;
				
			}else{
				echo "<SCRIPT LANGUAGE=\"JavaScript\">
				<!--
				parent.document.getElementById(\"Submit\").disabled=false;
				parent.document.getElementById(\"showmsg\").innerHTML='$msg';			
				//-->
				</SCRIPT>";exit;
			}	
			
			
	
	}
}elseif($job=='del_jobs'){
	if(!$lfjid ){ 
		if(!$web_admin) showerr("您还没有登陆，请登陆....");
		exit;
	}
	if(!$jobs_id) showerr("你访问的页面不存在");
	$data=$db->get_one("select * from {$_pre}hr_jobs where jobs_id='$jobs_id'");
	if($data[uid]!=$lfjuid && !$web_admin) showerr("您无权删除");

	$db->query("DELETE from  {$_pre}hr_jobs  where jobs_id='$jobs_id'");
	refreshto("jobs.php?","删除成功");
	exit;
}elseif($job=='edit_resume'){
	if(!$lfjid ){ 
		if(!$web_admin) showerr("您还没有登陆，请登陆....");
		exit;
	}
	if(!$re_id) showerr("你访问的页面不存在");
	$data=$db->get_one("select * from {$_pre}hr_resume where re_id='$re_id'");
	if($data[uid]!=$lfjuid && !$web_admin) showerr("您无权修改");
	if(!$step){
		
		foreach($data as $key=>$val){
			$$key=$val;
		}
		
	
		$sid_all=explode(",",$sid_all);
		$jobs_sort_1="<select name='job_sort[1]' onchange='choose_jobSort(this.options[this.selectedIndex].value,1)'> <option value='0'>请选择</option>";
			foreach($hrFid_db[0] as $key=>$val){
				$jobs_sort_1.="<option value='$key' ".($key==$sid_all[0]?" selected":"").">$val</option>";
			}
		$jobs_sort_1.="</select><span id='show_jobs_sort_2'>".choose_jobsort($sid_all[0],$sid_all[1],"sid[]",2)."</span>";
		
		
		
		//地区
		$city=explode(",",$city);
		$province_fid=select_where("province","'postdb[province_id]]'  onChange=\"choose_where(this.options[this.selectedIndex].value,'','')\" ",$city[0],0);	
		$city_fid    =select_where("city","'postdb[city_id]'  onChange=\"choose_where(this.options[this.selectedIndex].value,'','1')\"",$city[1],$city[0]);
	
		
		//模型
		$other_data=unserialize($other_data);
		$jobs_form_template=create_jobData_form('resume',$other_data,false);
		
		
		//照片
		$icon_url=$webdb[www_url]."/".$webdb[updir]."/{$Imgdirname_resume}/".$icon;
		//设置步骤
		$step=1;
	}else{
		//检查数据
			
			if(count($job_sort)<2)     $msg.="请选择职位发布的类目<br>";
			$sid_all=implode(",",$job_sort);
			$sid=$job_sort[(count($job_sort)-1)];
			$job_name=htmlspecialchars($job_name);
			$city=$postdb[province_id].",".$postdb[city_id];
			$other_data=serialize($other_data);
			
			$truename=htmlspecialchars($truename);
			$en_name=htmlspecialchars($en_name);
			$phones=htmlspecialchars($phones);
			$is_check=$webdb[checkUserPostJob];
			$sname=$hrFid_db[name][$sid];	
					
			if(!$truename)         $msg.="真实姓名不能为空<br>";
			if(!$phones)    	  $msg.="联系电话不能为空<br>";
			if(!$myinfo)          $msg.="自我介绍不能为空<br>";
			//照片上传
			if(is_uploaded_file($_FILES[postfile][tmp_name])){
				$array[name]=is_array($postfile)?$_FILES[postfile][name]:$postfile_name;
				$array[path]=$webdb[updir]."/{$Imgdirname_resume}/";
				$array[size]=is_array($postfile)?$_FILES[postfile][size]:$postfile_size;
				if($array[size]>($webdb[iconMaxUserPostResume]*1024))$msg="图片大小不能超过$webdb[iconMaxUserPostResume]k<br>";
				$icon=upfile_func2(is_array($postfile)?$_FILES[postfile][tmp_name]:$postfile,$array);
				if(substr($icon,0,3)=='ERR')	{
					$msg.=str_replace("ERR-","",$icon)."<br>";	
				}else{
					if($icon){//更换图片
									@unlink(PHP168_PATH."/".$array[path].$old_icon);
					}else{
									$icon=$old_icon;
					}
				}
				
			}else{
				$icon=$old_icon;
			}
			
			
			
			if(!$msg){
				$db->query("UPDATE {$_pre}hr_resume  set
				`sid`='$sid',
				`sid_all`='$sid_all',
				`truename`='$truename',
				`eng_name`='$eng_name',
				`icon`='$icon',
				`city`='$city',
				`address`='$address',
				`phones`='$phones',
				`email`='$email',
				`website`='$website',
				`job_name`='$job_name',
				`other_data`='$other_data',
				`myinfo`='$myinfo',
				`work_his`='$work_his',
				`edu_his`='$edu_his',
				`posttime`='".time()."'				
				where re_id='$re_id'");
			
			
				echo "<SCRIPT LANGUAGE=\"JavaScript\">
				<!--
				parent.location='$Mdomain/resumeshow.php?id=$re_id';	
				parent.location.href='$Mdomain/resumeshow.php?id=$re_id';		
				//-->
				</SCRIPT>";exit;
				
			}else{
				echo "<SCRIPT LANGUAGE=\"JavaScript\">
				<!--
				parent.document.getElementById(\"Submit\").disabled=false;
				parent.document.getElementById(\"showmsg\").innerHTML='$msg';			
				//-->
				</SCRIPT>";exit;
			}	
			
			
	
	}	
}elseif($job=='del_resume'){
	if(!$lfjid ){ 
		if(!$web_admin) showerr("您还没有登陆，请登陆....");
		exit;
	}
	if(!$re_id) showerr("你访问的页面不存在");

		
	//删除图片 
	$resume_data=$db->get_one("select * from {$_pre}hr_resume  where re_id='$re_id'");
	if($resume_data[uid]!=$lfjuid && !$web_admin) showerr("您无权删除");
	@unlink(PHP168_PATH."/".$webdb[updir]."/{$Imgdirname_resume}/".$resume_data[icon]);	
	$db->query("DELETE  from  {$_pre}hr_resume  where re_id='$re_id'");
	refreshto("jobs.php?","删除成功");
	exit;	
}






//SEO
$titleDB[title]			= filtrate(strip_tags("$hrDB[title]  - $webdb[Info_webname]"));
$titleDB[keywords]		= filtrate(strip_tags("$fidDB[metakeywords]  $webdb[Info_metakeywords]"));
$titleDB[description]	= get_word(strip_tags($fidDB[descrip]),200).filtrate(strip_tags("$fidDB[metadescription] $webdb[Info_metadescription]"));






require(Mpath."inc/head.php");
require(getTpl("jobs_post"));
require(Mpath."inc/foot.php");

function choose_jobsort($fup=0,$ck='',$name="sid[]",$step=2){
	global $hrFid_db,$sid_all;	
	if(is_array($hrFid_db[$fup])){
	
		$select="<select name='job_sort[{$step}]' onchange='choose_jobSort(this.options[this.selectedIndex].value,$step)'><option value='0'>请选择</option>";
		foreach($hrFid_db[$fup] as $key=>$val){
			$val=trim($val);
			$select.="<option ".($key==$ck?" selected":"")." value='$key'>$val</option>";
		}
		$select.="</select><span id='show_jobs_sort_".(++$step)."'>".choose_jobsort($ck,$sid_all[($step-1)],"sid[]",$step)."</span>";
		return $select;
		
	}else{
		return "";
	}
	
	
}



?>