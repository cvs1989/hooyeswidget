<?php
!function_exists('html') && exit('ERR');

$paperType=array("1"=>"单选题","2"=>"多选题","3"=>"判断题","4"=>"填空题","5"=>"排序题","6"=>"计算题","7"=>"简答题","8"=>"问答题","9"=>"作文题");

//创建表单
if($job=="make"&&$Apower[exam_form])
{
	$sort_fid=$Guidedb->Select("{$pre}exam_sort","fid");


	$ifsharedb[0]=' checked ';
	$typedb[1]=' checked ';
	
	require("head.php");
	require("template/exam_form/menu.htm");
	require("template/exam_form/make.htm");
	require("foot.php");
}
//创建表单
elseif($action=="make"&&$Apower[exam_form])
{
	if(!$fid){
		showerr("请选择分类");
	}
	$db->query("INSERT INTO `{$pre}exam_form` (`type`, `fid`, `name`, `uid`, `username`, `ifshare`) VALUES ('$atc_type','$fid','$atc_name','$userdb[uid]','$userdb[username]','$atc_ifshare')");
 	jump("创建成功","?lfj=$lfj&job=list",1);
}

//列出表单信息
elseif($job=="list"&&$Apower[exam_form])
{
	if($page<1){
		$page=1;
	}
	$rows=50;
	$min=($page-1)*$rows;
	$SQL='';

	$query = $db->query("SELECT A.*,S.name AS fname FROM `{$pre}exam_form` A LEFT JOIN `{$pre}exam_sort` S ON A.fid=S.fid $SQL ORDER BY A.id DESC LIMIT $min,$rows");
	while($rs = $db->fetch_array($query)){
		$rs[formtype]=$rs[type]==1?'试卷':'调查表';
		$listdb[]=$rs;
	}
	
	$showpage=getpage("`{$pre}exam_form`","$SQL","?lfj=$lfj&job=$job","$rows");

	require("head.php");
	require("template/exam_form/menu.htm");
	require("template/exam_form/list.htm");
	require("foot.php");
}

//删除表单
elseif($action=="delete"&&$Apower[exam_form])
{
	$db->query(" DELETE FROM `{$pre}exam_form` WHERE id='$id' ");
	$db->query(" DELETE FROM `{$pre}exam_form_element` WHERE form_id='$id' ");
	$db->query(" DELETE FROM `{$pre}exam_student_title` WHERE form_id='$id' ");
	$db->query(" DELETE FROM `{$pre}exam_student` WHERE form_id='$id' ");
	jump("删除成功",$FROMURL,1);
}

//修改表单
elseif($job=="edit"&&$Apower[exam_form])
{

	$rsdb=$db->get_one("SELECT * FROM {$pre}exam_form WHERE id='$id'");

	$sort_fid=$Guidedb->Select("{$pre}exam_sort","fid",$rsdb[fid]);

	$ifsharedb[$rsdb[ifshare]]=' checked ';
	$typedb[$rsdb[type]]=' checked ';

	require("head.php");
	require("template/exam_form/menu.htm");
	require("template/exam_form/make.htm");
	require("foot.php");
}

//修改表单
elseif($action=='edit'&&$Apower[exam_form])
{
	$db->query("UPDATE `{$pre}exam_form` SET type='$atc_type',fid='$fid',name='$atc_name',ifshare='$atc_ifshare' WHERE id='$id'");
	jump("修改成功",$FROMURL,1);
}

//表单题库管理
elseif($job=="manage"&&$Apower[exam_form])
{
	$rsdb=$db->get_one("SELECT F.*,S.name AS fname FROM `{$pre}exam_form` F LEFT JOIN {$pre}exam_sort S ON F.fid=S.fid WHERE F.id='$id'");
	$config=@unserialize($rsdb[config]);

	$query = $db->query("SELECT E.element_id,E.list,T.*,S.name AS fname FROM `{$pre}exam_form_element` E LEFT JOIN `{$pre}exam_title` T ON E.title_id=T.id LEFT JOIN `{$pre}exam_sort` S ON T.fid=S.fid WHERE E.form_id='$id' ORDER BY E.list DESC,E.element_id ASC ");
	while($rs = $db->fetch_array($query)){
		
		//分数处理,不同的类型,如单选,多选,填空,可以控制不同的分数
		$num[$rs[type]]++;
		$subjectDB[$rs[type]]=array(
			'name'=>$paperType[$rs[type]],
			'num'=>$num[$rs[type]],
			'fen'=>$config[fendb][$rs[type]]
		);
		$listdb[]=$rs;
	}
	ksort($subjectDB);
	
	require("head.php");
	require("template/exam_form/menu.htm");
	require("template/exam_form/manage.htm");
	require("foot.php");
}

//表单题目排序
elseif($action=="manage"&&$Apower[exam_form])
{
	foreach( $orderdb AS $key=>$value){
		$db->query("UPDATE `{$pre}exam_form_element` SET list='$value' WHERE element_id='$key'");
	}
	jump("修改成功",$FROMURL,1);
}

//表单分数设置
elseif($action=="fen"&&$Apower[exam_form])
{
	$rsdb=$db->get_one("SELECT * FROM `{$pre}exam_form` WHERE id='$id'");
	$config=@unserialize($rsdb[config]);
	$config[fendb]=$listdb;
	$str_config=addslashes(serialize($config));
	$db->query("UPDATE `{$pre}exam_form` SET config='$str_config' WHERE id='$id'");
	jump("修改成功",$FROMURL,1);
}

//表单移除某些题目
elseif($action=="remove"&&$Apower[exam_form])
{
	$db->query("DELETE FROM `{$pre}exam_form_element` WHERE element_id='$element_id'");
	$db->query("DELETE FROM `{$pre}exam_student_title` WHERE title_id='$title_id'");
	jump("移除成功",$FROMURL);
}

?>