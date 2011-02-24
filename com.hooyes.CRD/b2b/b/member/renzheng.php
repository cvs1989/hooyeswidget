<?php
require(dirname(__FILE__)."/"."global.php");

$rt=$db->get_one("select renzheng,title,uid from `{$_pre}company` where uid='$lfjuid'");

if(!$action){
	
	$renzhengDB[1][yz_]='<br>未提交';
	$renzhengDB[2][yz_]='<br>未提交';
	$renzhengDB[3][yz_]='<br>未提交';
	
	$query=$db->query("select * from `{$_pre}renzheng` where uid='$lfjuid' order by `level` asc");
	while($rs=$db->fetch_array($query)){
		if($rs[yz]){
			$rs[yz_]="<strong><font color=blue>已通过</font></strong> <br>(".date("Y-m-d,H:i:s",$rs[yz_time]).")";
		}else{
			$rs[yz_]="<font color='red'>审核中...</font><br>(".date("Y-m-d,H:i:s",$rs[post_time]).")<br>管理员将在1-2个工作日内处理,如果想重新提交请联系管理员撤出已经提交的内容";
		}
		$rs[content]=unserialize($rs[content]);
		$rs[files]=unserialize($rs[files]);
		foreach($rs[files] as $key=>$file){
			if($file) $rs[files][$key]="<a href='".$webdb[www_url]."/".$file."' target='_blank'>点击查看</a>";
			else $rs[files][$key]='';
		}
		
		$renzhengDB[$rs[level]]=$rs;
	}
	
}elseif($action=='renzheng'){
	if($level==1){
		if(!$company_name) showerr("商家名称必须填写，建议使用默认名称");
		if(!$content[faren]) showerr("企业法人必须填写");
		if(!$content[sfz_num]) showerr("企业法人的身份证号码必须填写");
		if(!$content[telphone]) showerr("联系电话必须填写,可以使固话，小灵通，和手机号码");
		if(!preg_match("/\d{15,18}/",$content[sfz_num])) showerr("企业法人的身份证号码只能填写15或者18数字");
		$content=serialize($content);
		$files="";
		
	}elseif($level==2){
		if(!$company_name) showerr("商家名称必须填写，建议使用默认名称");
		//$rz=$db->get_one("select * from `{$_pre}renzheng` where uid='$lfjuid' and level='1'");
		//if(!$rz) showerr("抱歉，您还没有通过初级认证，请先通过初级认证再申请此认证。");
		//上传营业执照
		if(is_uploaded_file($_FILES[yyzz][tmp_name])){
			$array[name]=is_array($yyzz)?$_FILES[yyzz][name]:$yyzz_name;
			$array[path]=$webdb[updir]."/{$Imgdirname}/renzheng/$lfjuid/";
			$array[size]=is_array($yyzz)?$_FILES[yyzz][size]:$yyzz_size;
			$files[yyzz]=upfile(is_array($yyzz)?$_FILES[yyzz][tmp_name]:$yyzz,$array);
			$files[yyzz]=$array[path].$files[yyzz];
		}else{
			showerr("请上传营业执照扫描件或者照片,格式为jpg,gif");
		}
		//上传税务登记证
		if(is_uploaded_file($_FILES[swdj][tmp_name])){
			$array[name]=is_array($swdj)?$_FILES[swdj][name]:$swdj_name;
			$array[path]=$webdb[updir]."/{$Imgdirname}/renzheng/$lfjuid/";
			$array[size]=is_array($swdj)?$_FILES[swdj][size]:$swdj_size;
			$files[swdj]=upfile(is_array($swdj)?$_FILES[swdj][tmp_name]:$swdj,$array);
			$files[swdj]=$array[path].$files[swdj];
		}else{
			@unlink(PHP168_PATH.$files[yyzz]);
			showerr("请上传税务登记证扫描件或者照片,格式为jpg,gif");
		}
				
		//组织结构代码证
		if(is_uploaded_file($_FILES[jgdm][tmp_name])){
			$array[name]=is_array($jgdm)?$_FILES[jgdm][name]:$jgdm_name;
			$array[path]=$webdb[updir]."/{$Imgdirname}/renzheng/$lfjuid/";
			$array[size]=is_array($jgdm)?$_FILES[jgdm][size]:$jgdm_size;
			$files[jgdm]=upfile(is_array($jgdm)?$_FILES[jgdm][tmp_name]:$jgdm,$array);
			$files[jgdm]=$array[path].$files[jgdm];
		}else{
			@unlink(PHP168_PATH.$files[yyzz]);
			@unlink(PHP168_PATH.$files[swdj]);
			showerr("请上传营组织结构代码证扫描件或者照片,格式为jpg,gif");
		}
					
		$files=serialize($files);
		
	}elseif($level==3){
		if(!$company_name) showerr("商家名称必须填写，建议使用默认名称");
		//$rz=$db->get_one("select * from `{$_pre}renzheng` where uid='$lfjuid' and level='2'");
		//if(!$rz) showerr("抱歉，您还没有通过高级认证，请先通过高级认证再申请此认证。");
		
		//1
		if(is_uploaded_file($_FILES[doc1][tmp_name])){
			$array[name]=is_array($doc1)?$_FILES[doc1][name]:$doc1_name;
			$array[path]=$webdb[updir]."/{$Imgdirname}/renzheng/$lfjuid/";
			$array[size]=is_array($doc1)?$_FILES[doc1][size]:$doc1_size;
			$files[doc1]=upfile(is_array($doc1)?$_FILES[doc1][tmp_name]:$doc1,$array);
			$files[doc1]=$array[path].$files[doc1];
		}else{
			showerr("每个选项都必须选择");
		}
		//2
		if(is_uploaded_file($_FILES[doc2][tmp_name])){
			$array[name]=is_array($doc2)?$_FILES[doc2][name]:$doc2_name;
			$array[path]=$webdb[updir]."/{$Imgdirname}/renzheng/$lfjuid/";
			$array[size]=is_array($doc2)?$_FILES[doc2][size]:$doc2_size;
			$files[doc2]=upfile(is_array($doc2)?$_FILES[doc2][tmp_name]:$doc2,$array);
			$files[doc2]=$array[path].$files[doc2];
		}else{
			@unlink(PHP168_PATH.$files[doc1]);
			showerr("每个选项都必须选择");
		}
				
		//3
		if(is_uploaded_file($_FILES[doc3][tmp_name])){
			$array[name]=is_array($doc3)?$_FILES[doc3][name]:$doc3_name;
			$array[path]=$webdb[updir]."/{$Imgdirname}/renzheng/$lfjuid/";
			$array[size]=is_array($doc3)?$_FILES[doc3][size]:$doc3_size;
			$files[doc3]=upfile(is_array($doc3)?$_FILES[doc3][tmp_name]:$doc3,$array);
			$files[doc3]=$array[path].$files[doc3];
		}else{
			@unlink(PHP168_PATH.$files[doc1]);
			@unlink(PHP168_PATH.$files[doc2]);
			showerr("每个选项都必须选择");
		}
					
		$files=serialize($files);
		
	}else{
		showerr("错误的操作");
	}
	$rt=$db->get_one("select renzheng,title,uid from `{$_pre}company` where uid='$lfjuid'");
	if($rt){	
		//判断是否新提交
		$rz=$db->get_one("select * from `{$_pre}renzheng` where uid='$lfjuid' and level='$level'");
		if($rz){
			$db->query("UPDATE `{$_pre}renzheng` SET `company_name`='$company_name',`post_time`='".time()."',`content`='$content',`files`='$files' where uid='$lfjuid' and level='$level'");
		}else{
			$db->query("INSERT INTO `{$_pre}renzheng` ( `id` , `uid` , `username` , `company_name` , `level` , `post_time` , `yz` , `yz_time` , `content` , `files` ) VALUES ('', '$lfjuid', '$lfjid', '$company_name', '$level', '".time()."', '0', '0', '$content', '$files');");
		}
	
		refreshto("?","提交成功，请等待审核",1);
	}else{
		showerr("您的商家信息部可用，请先确认是否登记商家，或者商家信息是否被锁定");
	}
}
$renzheng3docname=explode(" ",$webdb[renzheng3doc]);


require(dirname(__FILE__)."/"."head.php");
require(dirname(__FILE__)."/"."template/renzheng.htm");
require(dirname(__FILE__)."/"."foot.php");
?>