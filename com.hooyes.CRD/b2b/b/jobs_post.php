<?php
require_once("global.php");




if($id)$id=intval($id);
if($sid)$sid=intval($sid);
if($jobs_id)$jobs_id=intval($jobs_id);
if($re_id)$re_id=intval($re_id);
/**/
@require_once(Mpath."php168/all_hrfid.php");
@require_once(Mpath."php168/jobData.php");
	/**************************************************��Ƹ*****/	
	/**************************************************��Ƹ*****/
if($job=='jobs'){
		
		if(!$lfjid ){ 
			if(!$web_admin) showerr("����û�е�½�����½....");
			exit;
		}

		$rt=$db->get_one("select * from `{$_pre}company` where uid='$lfjuid'");
		
		$myjobs=$db->get_one("select count(*) as num from {$_pre}hr_jobs where `rid`='$rt[rid]';");
		$webdb[usersJobsMax]=$webdb[usersJobsMax]?$webdb[usersJobsMax]:20;
		if($myjobs[num] >= $webdb[usersJobsMax]) 
		{
			//�������
			showerr("��Ǹ�����Ѿ���$myjobs[num]����Ƹ��Ϣ�ˣ�����������ˡ�");
		}


		if(!$step){
			$hrDB[title]="�˲���ƸƵ�� -> �˲���Ƹ";
			$jobs_sort_1="<select name='job_sort[1]' onchange='choose_jobSort(this.options[this.selectedIndex].value,1)'> <option value='0'>��ѡ��</option>";
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
			$company_contact="��ϵ�ˣ�\r\n��  ����\r\n��  �棺\r\n��  �䣺\r\n��  ҳ��";
			$companyintro="";
			}
			//�����ύ
			$step=1;
		}else{
			
			if(!$webdb[allowUserPostJob]){				
				if(!$rt[title]) $msg="���ȵǼ��̼������ٷ���";
			}else{
				if($companyname) $rt[title]=$companyname;
				if($companyintro) $rt[content]=htmlspecialchars($companyintro);
			    if($company_contact) $company_contact=htmlspecialchars($company_contact);
				if(!$rt[title]){ if(!$company_contact) $msg="��׼ȷ����д��Ƹ��λ����ϵ��ʽ<br>";}
			}
			//�������
		
			
			if(count($job_sort)<2) $msg="��ѡ��ְλ��������Ŀ<br>";
					
			$sid_all=implode(",",$job_sort);
			$sid=$job_sort[(count($job_sort)-1)];
			$title=htmlspecialchars($title);
			$city=$postdb[province_id].",".$postdb[city_id];
			$other_data=serialize($other_data);
			
			$hr_work=htmlspecialchars($hr_work);
			$hr_info=htmlspecialchars($hr_info);
			$is_check=$webdb[checkUserPostJob];
			$sname=$hrFid_db[name][$sid];
			
			//��������
			
			if(!$msg){
				$db->query("INSERT INTO `{$_pre}hr_jobs` ( `jobs_id` , `rid` , `companyname` , `companyintro`,`company_contact` , `uid` , `title` , `sid` , `sid_all` , `sname` , `city` , `other_data` , `hr_work` , `hr_info` , `is_check` , `best` , `hits` , `posttime` ) 
VALUES (
'', '$rt[rid]', '$rt[title]', '$rt[content]','$company_contact', '$lfjuid', '$title', '$sid', '$sid_all', '$sname', '$city', '$other_data', '$hr_work', '$hr_info', '$is_check', '0', '0', '".time()."'
);");
			$jobs_id=$db->insert_id();
			//��������
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
		
		
	/**************************************************����*****/	
	/**************************************************����*****/	
}elseif($job=='resume'){
		$myResume=$db->get_one("select count(*) as num from {$_pre}hr_resume where uid='$lfjuid';");
		$webdb[usersRresumeMax]=$webdb[usersRresumeMax]?$webdb[usersRresumeMax]:3;
		if($myResume[num] >= $webdb[usersRresumeMax]) 
		{
			//����������
			showerr("��Ǹ�����Ѿ���$myResume[num]�ݼ����ˣ�����������ˡ�");
		}
		if(!$step){


			$hrDB[title]="�˲���ƸƵ�� -> ��������";
			$jobs_sort_1="<select name='job_sort[1]' onchange='choose_jobSort(this.options[this.selectedIndex].value,1)'> <option value='0'>��ѡ��</option>";
			foreach($hrFid_db[0] as $key=>$val){
				$jobs_sort_1.="<option value='$key'>$val</option>";
			}
			$jobs_sort_1.="</select><span id='show_jobs_sort_2'></span>";
			$province_fid=select_where("province","'postdb[province_id]'  onChange=\"choose_where(this.options[this.selectedIndex].value,'','')\" ");	
			$province_fid_adc=select_where("province","'postdb[province_id]'  onChange=\"choose_where(this.options[this.selectedIndex].value,'','adc_')\" ");	
			
			$jobs_form_template=create_jobData_form('resume','',false);
			$step=1;
		}else{
			
			//�������
			if(count($job_sort)<2) $msg="��ѡ��ְλ��������Ŀ<br>";
			$sid_all=implode(",",$job_sort);
			$sid=$job_sort[(count($job_sort)-1)];
			
			$truename=htmlspecialchars($truename);
			if(!$truename)$msg="��ʵ��������Ϊ��<br>";
			$eng_name=htmlspecialchars($eng_name);
			$address=htmlspecialchars($address);
			$phones=htmlspecialchars($phones);
			if(!$phones) $msg="��ϵ�绰����Ϊ��<br>";
			if(!$email)  $msg="�����ַ������Ϊ��<br>";
			if(!preg_match("/.*@.*/",$email)) $msg="�����ַ�������ϸ�ʽ<br>";
			$myinfo  =htmlspecialchars($myinfo);
			$work_his=htmlspecialchars($work_his);
			$edu_his =htmlspecialchars($edu_his);
			$sname   =$hrFid_db[name][$sid];
			$city=$postdb[province_id].",".$postdb[city_id];
			$is_check=$webdb[checkUserPostResume];
			$job_name=htmlspecialchars($job_name);
			$other_data=serialize($other_data);
			//��Ƭ�ϴ�
			if(is_uploaded_file($_FILES[postfile][tmp_name])){
				$array[name]=is_array($postfile)?$_FILES[postfile][name]:$postfile_name;
				$array[path]=$webdb[updir]."/{$Imgdirname_resume}/";
				$array[size]=is_array($postfile)?$_FILES[postfile][size]:$postfile_size;
				$webdb[iconMaxUserPostResume]=$webdb[iconMaxUserPostResume]?$webdb[iconMaxUserPostResume]:50;
				if($array[size]>($webdb[iconMaxUserPostResume]*1024))$msg="ͼƬ��С���ܳ���$webdb[iconMaxUserPostResume]k<br>";
				$icon=upfile_func2(is_array($postfile)?$_FILES[postfile][tmp_name]:$postfile,$array);
				if(substr($icon,0,3)=='ERR')	{
					$msg.=str_replace("ERR-","",$icon)."<br>";
				}else{
					if($icon){//����ͼƬ
									@unlink(PHP168_PATH."/".$array[path].$old_icon);
					}else{
									$icon=$old_icon;
					}
				}
				
			}else{
				$icon="";
			}
			//��������
			
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
	$hrDB[title]="�˲���ƸƵ�� -> Ͷ�ݼ���";
	//�õ��ҵļ���.ÿ�����ֿ����ж������
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
	
	
	if(!$jobs_id) showerr("��Ǹ��ҳ�������!");
	if(!$re_id)   showerr("��ѡ��һ�ݼ���!");
	//�õ���Ƹ��Ϣ
	$jobs=$db->get_one("select * from {$_pre}hr_jobs where jobs_id='$jobs_id' and is_check=1");
	if(!is_array($jobs)) showerr("��Ǹ���޷�������Ƹ��Ϣ.");
	if($jobs[uid]==$lfjuid) showerr("��Ǹ������Ͷ���Լ�����Ƹ��Ϣ.");
	//����
	$re=$db->get_one("select * from {$_pre}hr_resume where re_id='$re_id' and is_check=1");
	if(!is_array($re))   showerr("��Ǹ���޷�������ѡ��ļ�����Ϣ.");
	
	//����Ƿ��Ѿ�Ͷ�ݹ����ڹ涨ʱ����
	$postresumeMaxDay=$webdb[postresumeMaxDay]!=''?$webdb[postresumeMaxDay]:7;
	$tdhis=$db->get_one("select count(*) as num from {$_pre}hr_td where re_id='$re_id' and jobs_id='$jobs_id' and  td_type='add' and posttime >".($postresumeMaxDay*24*60*60));
	if($tdhis[num]>0){
		showerr("��Ǹ��{$postresumeMaxDay}���ڲ��ܶ�ͬһ����Ƹ��Ϣ����ͬһ�ݼ���.");
	}
	//Ͷ�ݲ���
	$db->query("INSERT INTO `{$_pre}hr_td` ( `td_id`,`td_type` ,`rid`, `jobs_id` , `re_id` , `jobs_uid` , `re_uid` , `posttime` , `jobs_title` , `re_title` ) VALUES ('','td','$jobs[rid]', '$jobs_id', '$re_id', '$jobs[uid]', '$re[uid]', '".time()."', '$jobs[title]', '$re[job_name]($re[truename])'  );");
	
		
	refreshto("jobsshow.php?id=$jobs_id","����Ͷ�ݳɹ�",5);
	exit;


}elseif($job=='add_td'){	
	if(!$lfjid ){ 
			if(!$web_admin) showerr("����û�е�½�����½....");
			exit;
		}

	if(!$re_id)   showerr("��Ǹ��ҳ�������!");

	//����
	$re=$db->get_one("select * from {$_pre}hr_resume where re_id='$re_id' and is_check=1");
	if(!is_array($re))   showerr("��Ǹ���޷����Ҽ�����Ϣ.");
	if($re[uid]==$lfjuid)showerr("��Ǹ�����ܰ��Լ��ļ������뵽�Լ����˲ſ�.");
	//����Ƿ��Ѿ���������ڹ涨ʱ����
	$postresumeMaxDay=$webdb[postresumeMaxDay]!=''?$webdb[postresumeMaxDay]:7;
	$tdhis=$db->get_one("select count(*) as num from {$_pre}hr_td where re_id='$re_id' and jobs_uid='$lfjuid' and  td_type='add' and posttime >".($postresumeMaxDay*24*60*60));
	if($tdhis[num]>0){
		showerr("��Ǹ��{$postresumeMaxDay}���ڲ��ܶ�ͬһ�����ظ����.");
	}
	
	//Ͷ�ݲ���
	$db->query("INSERT INTO `{$_pre}hr_td` ( `td_id` ,`td_type`,`rid`, `jobs_id` , `re_id` , `jobs_uid` , `re_uid` , `posttime` , `jobs_title` , `re_title` ) VALUES ('','add','', '$jobs_id', '$re_id', '$lfjuid', '$re[uid]', '".time()."', '', '$re[job_name]($re[truename])'  );");
	
		
	refreshto("resumeshow.php?id=$re_id","��ӳɹ�");
	exit;
	
}elseif($job=='update_posttime'){
	if(!$lfjid ){ 
		if(!$web_admin) showerr("����û�е�½�����½....");
		exit;
	}
	if(!$re_id) showerr("�Ƿ�����");
	$updateresumeMaxHour=$webdb[updateresumeMaxHour]!=''?$webdb[updateresumeMaxHour]:24;
	$re=$db->get_one("select * from {$_pre}hr_resume where re_id='$re_id' and is_check=1");
	if(!is_array($re))   showerr("��Ǹ���޷�������ѡ��ļ�����Ϣ.");
	if($re[posttime]>(time() - $updateresumeMaxHour*60*60)){
		 showerr("��Ǹ��{$updateresumeMaxHour}Сʱ�����ļ���ֻ�ܸ���һ��.");
	}else{
		$re=$db->query("update {$_pre}hr_resume  set `posttime`='".time()."' where re_id='$re_id'");
	}
	
			
	refreshto("resumeshow.php?id=$re_id","���³ɹ�");
	exit;

}elseif($job=='update_posttime_jobs'){
	if(!$lfjid ){ 
		if(!$web_admin) showerr("����û�е�½�����½....");
		exit;
	}
	if(!$jobs_id) showerr("�Ƿ�����");
	$updateresumeMaxHour=$webdb[updateresumeMaxHour]!=''?$webdb[updateresumeMaxHour]:24;
	$re=$db->get_one("select * from {$_pre}hr_jobs where jobs_id='$jobs_id' and is_check=1");
	if(!is_array($re))   showerr("��Ǹ���޷�������ѡ��ļ�����Ϣ.");
	if($re[posttime]>(time() - $updateresumeMaxHour*60*60)){
		 showerr("��Ǹ��{$updateresumeMaxHour}Сʱ������ְλ��Ϣֻ�ܸ���һ��.");
	}else{
		$re=$db->query("update {$_pre}hr_jobs  set `posttime`='".time()."' where jobs_id='$jobs_id'");
	}			
	refreshto("jobsshow.php?id=$jobs_id","���³ɹ�");
	exit;	

}elseif($job=='edit_jobs'){
	if(!$lfjid ){ 
		if(!$web_admin) showerr("����û�е�½�����½....");
		exit;
	}
	if(!$jobs_id) showerr("����ʵ�ҳ�治����");
	
	$data=$db->get_one("select * from {$_pre}hr_jobs where jobs_id='$jobs_id'");
	if($data[uid]!=$lfjuid && !$web_admin) showerr("����Ȩ�޸�");

	if(!$step){
		foreach($data as $key=>$val){
			$$key=$val;
		}
		
	
		$sid_all=explode(",",$sid_all);
		$jobs_sort_1="<select name='job_sort[1]' onchange='choose_jobSort(this.options[this.selectedIndex].value,1)'> <option value='0'>��ѡ��</option>";
			foreach($hrFid_db[0] as $key=>$val){
				$jobs_sort_1.="<option value='$key' ".($key==$sid_all[0]?" selected":"").">$val</option>";
			}
		$jobs_sort_1.="</select><span id='show_jobs_sort_2'>".choose_jobsort($sid_all[0],$sid_all[1],"sid[]",2)."</span>";
		
		
		
		//����
		$city=explode(",",$city);
		$province_fid=select_where("province","'postdb[province_id]]'  onChange=\"choose_where(this.options[this.selectedIndex].value,'','')\" ",$city[0],0);	
		$city_fid    =select_where("city","'postdb[city_id]'  onChange=\"choose_where(this.options[this.selectedIndex].value,'','1')\"",$city[1],$city[0]);
	
	
		
		
		$companyintro=@preg_replace('/<([^<]*)>/is',"",$companyintro);
		
		//ģ��
		$other_data=unserialize($other_data);
		$jobs_form_template=create_jobData_form('job',$other_data,true);
		
		//���ò���
		$step=1;
	}else{
		//�������
			
			if(count($job_sort)<2)     $msg.="��ѡ��ְλ��������Ŀ<br>";
			$sid_all=implode(",",$job_sort);
			$sid=$job_sort[(count($job_sort)-1)];
			$title=htmlspecialchars($title);
			$city=$postdb[province_id].",".$postdb[city_id];
			$other_data=serialize($other_data);
			
			$hr_work=htmlspecialchars($hr_work);
			$hr_info=htmlspecialchars($hr_info);
			$is_check=$webdb[checkUserPostJob];
			$sname=$hrFid_db[name][$sid];	
					
			if(!$companyname)     $msg.="��λ���Ʋ���Ϊ��<br>";
			if(!$companyintro)    $msg.="��λ���ܲ���Ϊ��<br>";
			if(!$company_contact) $msg.="��׼ȷ����д��Ƹ��λ����ϵ��ʽ<br>";
			if(!$title)           $msg.="ְλ���Ʋ���Ϊ��<br>";
			if(!$hr_work)         $msg.="��λְ�������д<br>";
			if(!$hr_info)         $msg.="����Ҫ�������д<br>";
			
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
		if(!$web_admin) showerr("����û�е�½�����½....");
		exit;
	}
	if(!$jobs_id) showerr("����ʵ�ҳ�治����");
	$data=$db->get_one("select * from {$_pre}hr_jobs where jobs_id='$jobs_id'");
	if($data[uid]!=$lfjuid && !$web_admin) showerr("����Ȩɾ��");

	$db->query("DELETE from  {$_pre}hr_jobs  where jobs_id='$jobs_id'");
	refreshto("jobs.php?","ɾ���ɹ�");
	exit;
}elseif($job=='edit_resume'){
	if(!$lfjid ){ 
		if(!$web_admin) showerr("����û�е�½�����½....");
		exit;
	}
	if(!$re_id) showerr("����ʵ�ҳ�治����");
	$data=$db->get_one("select * from {$_pre}hr_resume where re_id='$re_id'");
	if($data[uid]!=$lfjuid && !$web_admin) showerr("����Ȩ�޸�");
	if(!$step){
		
		foreach($data as $key=>$val){
			$$key=$val;
		}
		
	
		$sid_all=explode(",",$sid_all);
		$jobs_sort_1="<select name='job_sort[1]' onchange='choose_jobSort(this.options[this.selectedIndex].value,1)'> <option value='0'>��ѡ��</option>";
			foreach($hrFid_db[0] as $key=>$val){
				$jobs_sort_1.="<option value='$key' ".($key==$sid_all[0]?" selected":"").">$val</option>";
			}
		$jobs_sort_1.="</select><span id='show_jobs_sort_2'>".choose_jobsort($sid_all[0],$sid_all[1],"sid[]",2)."</span>";
		
		
		
		//����
		$city=explode(",",$city);
		$province_fid=select_where("province","'postdb[province_id]]'  onChange=\"choose_where(this.options[this.selectedIndex].value,'','')\" ",$city[0],0);	
		$city_fid    =select_where("city","'postdb[city_id]'  onChange=\"choose_where(this.options[this.selectedIndex].value,'','1')\"",$city[1],$city[0]);
	
		
		//ģ��
		$other_data=unserialize($other_data);
		$jobs_form_template=create_jobData_form('resume',$other_data,false);
		
		
		//��Ƭ
		$icon_url=$webdb[www_url]."/".$webdb[updir]."/{$Imgdirname_resume}/".$icon;
		//���ò���
		$step=1;
	}else{
		//�������
			
			if(count($job_sort)<2)     $msg.="��ѡ��ְλ��������Ŀ<br>";
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
					
			if(!$truename)         $msg.="��ʵ��������Ϊ��<br>";
			if(!$phones)    	  $msg.="��ϵ�绰����Ϊ��<br>";
			if(!$myinfo)          $msg.="���ҽ��ܲ���Ϊ��<br>";
			//��Ƭ�ϴ�
			if(is_uploaded_file($_FILES[postfile][tmp_name])){
				$array[name]=is_array($postfile)?$_FILES[postfile][name]:$postfile_name;
				$array[path]=$webdb[updir]."/{$Imgdirname_resume}/";
				$array[size]=is_array($postfile)?$_FILES[postfile][size]:$postfile_size;
				if($array[size]>($webdb[iconMaxUserPostResume]*1024))$msg="ͼƬ��С���ܳ���$webdb[iconMaxUserPostResume]k<br>";
				$icon=upfile_func2(is_array($postfile)?$_FILES[postfile][tmp_name]:$postfile,$array);
				if(substr($icon,0,3)=='ERR')	{
					$msg.=str_replace("ERR-","",$icon)."<br>";	
				}else{
					if($icon){//����ͼƬ
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
		if(!$web_admin) showerr("����û�е�½�����½....");
		exit;
	}
	if(!$re_id) showerr("����ʵ�ҳ�治����");

		
	//ɾ��ͼƬ 
	$resume_data=$db->get_one("select * from {$_pre}hr_resume  where re_id='$re_id'");
	if($resume_data[uid]!=$lfjuid && !$web_admin) showerr("����Ȩɾ��");
	@unlink(PHP168_PATH."/".$webdb[updir]."/{$Imgdirname_resume}/".$resume_data[icon]);	
	$db->query("DELETE  from  {$_pre}hr_resume  where re_id='$re_id'");
	refreshto("jobs.php?","ɾ���ɹ�");
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
	
		$select="<select name='job_sort[{$step}]' onchange='choose_jobSort(this.options[this.selectedIndex].value,$step)'><option value='0'>��ѡ��</option>";
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