<?php
!function_exists('html') && exit('ERR');

$paperType=array("1"=>"��ѡ��","2"=>"��ѡ��","3"=>"�ж���","4"=>"�����","5"=>"������","6"=>"������","7"=>"�����","8"=>"�ʴ���","9"=>"������");

//������Ŀ
if($job=="make"&&$Apower[exam_title])
{
	$sort_fid=$Guidedb->Select("{$pre}exam_sort","fid");

	$ifsharedb[1]=' checked ';
	
	require("head.php");
	require("template/exam_title/menu.htm");
	require("template/exam_title/make.htm");
	require("foot.php");
}
//������Ŀ
elseif($action=="make"&&$Apower[exam_title])
{
	if(!$fid)
	{
		showerr("��ѡ�����");
	}
	$db->query("INSERT INTO `{$pre}exam_title` ( `fid`, `type`, `question`, `config`, `answer`, `uid`, `username`, `ifshare`) VALUES ('$fid','$ctype','$atc_question','$atc_config','$atc_answer','$userdb[uid]','$userdb[username]','$atc_ifshare')");
 	jump("�����ɹ�,��������",$FROMURL,1);
}

//�г�������Ŀ
elseif($job=="list"&&$Apower[exam_title])
{
	$sort_fid=$Guidedb->Select("{$pre}exam_sort","fid",$fid,"index.php?lfj=$lfj&job=$job");

	if($fid){
		$SQL=" WHERE A.fid='$fid' ";
	}else{
		$SQL=" WHERE 1 ";
	}

	$select_exam="<select name='form_id'>";
	$query = $db->query("SELECT * FROM `{$pre}exam_form` A $SQL");
	while($rs = $db->fetch_array($query)){
		$select_exam.="<option value='$rs[id]'>$rs[name]</option>";
	}
	$select_exam.="</select>";

	
	if($page<1){
		$page=1;
	}
	$rows=50;
	$min=($page-1)*$rows;

	$query = $db->query("SELECT A.*,S.name AS fname FROM `{$pre}exam_title` A LEFT JOIN `{$pre}exam_sort` S ON A.fid=S.fid $SQL ORDER BY A.id DESC LIMIT $min,$rows");
	while($rs = $db->fetch_array($query)){
		$listdb[]=$rs;
	}
	
	$showpage=getpage("`{$pre}exam_title` A","$SQL","index.php?lfj=exam_title&job=list&fid=$fid","$rows");

	require("head.php");
	require("template/exam_title/menu.htm");
	require("template/exam_title/list.htm");
	require("foot.php");
}

//���������Ŀ���Ծ�
elseif($action=="list"&&$Apower[exam_title])
{
	if(!$listdb){
		showerr("��ѡ��һ��");
	}
	foreach( $listdb AS $key=>$value){
		if($ctype=='del'){
			$db->query("DELETE FROM `{$pre}exam_title` WHERE id='$value'");
		}else{
			//���������Ŀ���Ծ�
			if(!$form_id){
				showerr("��ѡ��һ���Ծ�");
			}
			$rs=$db->get_one("SELECT * FROM `{$pre}exam_form_element` WHERE title_id='$value' AND `form_id`='$form_id'");
			if(!$rs){
				$db->query("INSERT INTO `{$pre}exam_form_element` ( `form_id`, `title_id` ) VALUES ( '$form_id','$value' )");
			}
		}
	}
	refreshto($FROMURL,"�����ɹ�");
}

//ɾ����Ŀ
elseif($action=="delete"&&$Apower[exam_title])
{
	$db->query(" DELETE FROM `{$pre}exam_title` WHERE id='$id' ");
	$db->query(" DELETE FROM `{$pre}exam_form_element` WHERE title_id='$id' ");
	$db->query(" DELETE FROM `{$pre}exam_student_title` WHERE title_id='$id' ");
	refreshto($FROMURL,"ɾ���ɹ�");
}

//�޸���Ŀ
elseif($job=="edit"&&$Apower[exam_title])
{

	$rsdb=$db->get_one("SELECT * FROM {$pre}exam_title WHERE id='$id'");
	$sort_fid=$Guidedb->Select("{$pre}exam_sort","fid",$rsdb[fid]);
	$ifsharedb[$rsdb[ifshare]]=' checked ';

	require("head.php");
	require("template/exam_title/menu.htm");
	require("template/exam_title/make.htm");
	require("foot.php");
}
//�޸���Ŀ
elseif($action=='edit'&&$Apower[exam_title])
{
	$db->query("UPDATE `{$pre}exam_title` SET fid='$fid',type='$ctype',question='$atc_question',config='$atc_config',answer='$atc_answer',ifshare='$atc_ifshare' WHERE id='$id'");
	refreshto($FROMURL,"�޸ĳɹ�");
}

?>