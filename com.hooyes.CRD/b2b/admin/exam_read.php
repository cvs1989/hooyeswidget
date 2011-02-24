<?php
!function_exists('html') && exit('ERR');

$paperType=array("1"=>"��ѡ��","2"=>"��ѡ��","3"=>"�ж���","4"=>"�����","5"=>"������","6"=>"������","7"=>"�����","8"=>"�ʴ���","9"=>"������");


if($job=='list')
{
	$sort_fid=$Guidedb->Select("{$pre}exam_sort","fid",$fid,"index.php?lfj=$lfj&job=$job");
	
	$rows=50;
	$page<1 && $page=1;
	$min=($page-1)*$rows;
	$SQL="";
	if($fid){
		$SQL=" WHERE F.fid='$fid' ";
	}else{
		$SQL=" WHERE 1 ";
	}
	$query = $db->query("SELECT F.* FROM `{$pre}exam_student` ST LEFT JOIN {$pre}exam_form F ON ST.form_id=F.id $SQL GROUP BY ST.form_id LIMIT $min,$rows");
	while($rs = $db->fetch_array($query)){
		@extract($db->get_one("SELECT COUNT(*) AS num FROM `{$pre}exam_student` WHERE form_id='$rs[id]'"));
		$rs[num]=$num;
		$listdb[]=$rs;
	}
	$showpage=getpage("`{$pre}exam_student` ST LEFT JOIN {$pre}exam_form F ON ST.form_id=F.id","$SQL","?lfj=$lfj&job=list","$rows");

	require("head.php");
	require("template/exam_read/list.htm");
	require("foot.php");
}

elseif($job=="liststudent")
{
	$rows=50;
	$page<1 && $page=1;
	$min=($page-1)*$rows;

	$showpage=getpage("`{$pre}exam_student`","WHERE form_id='$id'","?lfj=$lfj&job=$job","$rows");

	$rsdb=$db->get_one("SELECT * FROM `{$pre}exam_form` WHERE id='$id'");

	$query = $db->query("SELECT * FROM `{$pre}exam_student` WHERE form_id='$id' ORDER BY total_fen DESC LIMIT $min,$rows");
	while($rs = $db->fetch_array($query)){
		$rs[posttime]=date("Y-m-d H:i:s",$rs[posttime]);
		$listdb[]=$rs;
	}
	require("head.php");
	require("template/exam_read/liststudent.htm");
	require("foot.php");
}
elseif($action=='delete'){
	$db->query("DELETE FROM `{$pre}exam_student` WHERE form_id='$id'");
	$db->query("DELETE FROM `{$pre}exam_student_title` WHERE form_id='$id'");
	jump("���л�Ա�������������ѱ����","$FROMURL",1);
}
elseif($action=="delete_student"){
	$db->query("DELETE FROM `{$pre}exam_student` WHERE student_id='$student_id'");
	$db->query("DELETE FROM `{$pre}exam_student_title` WHERE student_id='$student_id'");
	jump("ɾ���ɹ�","$FROMURL",0);
}

//�ύ�����Ծ�
elseif($action=="postRead")
{
	//��Աû������Ŀ,�Ͳ���������.
	$total_fen=0;
	foreach($fenDB AS $st_id=>$fen){	
		$db->query("UPDATE `{$pre}exam_student_title` SET `fen`='{$fen}',`comment`='{$commentDB[$st_id]}' WHERE `st_id`='$st_id'");
		$total_fen+=$fen;
	}
	$db->query("UPDATE `{$pre}exam_student` SET `total_fen`='$total_fen' WHERE `student_id`='$student_id'");
	jump("�޸ĳɹ�",$FROMURL,100);
}
//�����Ծ�
elseif($job=="editpaper")
{
	$paperType=array("1"=>"��ѡ��","2"=>"��ѡ��","3"=>"�ж���","4"=>"�����","5"=>"������","6"=>"������","7"=>"�����","8"=>"�ʴ���","9"=>"������");
	$numDB=array("��","һ","��","��","��","��","��","��","��","��","ʮ","ʮһ","ʮ��","ʮ��","ʮ��");
	$letterDB=array("a","b","c","d","e","f","g");

	$rsdb=$db->get_one("SELECT F.*,S.name AS fname,student_uid,student_name,total_fen,posttime  FROM {$pre}exam_student ST LEFT JOIN {$pre}exam_form F ON ST.form_id=F.id LEFT JOIN {$pre}exam_sort S ON F.fid=S.fid  WHERE ST.student_id='$student_id'");
	$config=@unserialize($rsdb[config]);

	$rsdb[posttime]=date("Y-m-d H:i:s",$rsdb[posttime]);

	$query = $db->query("SELECT T.* FROM `{$pre}exam_form_element` E LEFT JOIN `{$pre}exam_title` T ON E.title_id=T.id WHERE E.form_id='$id' ORDER BY E.list DESC,E.element_id ASC ");
	while($rs = $db->fetch_array($query)){
		//������Щ����ѱ�ɾ��
		if(!$rs[id]){
			continue;
		}
		$rss=$db->get_one("SELECT * FROM `{$pre}exam_student_title` WHERE student_id='$student_id' AND title_id='$rs[id]'");
		$rs[st_id]=$rss[st_id];
		$rs[_fen]=$rss[fen];
		$rs[_comment]=$rss[comment];

		$rs[showcontent]='';
		//��ѡ�����ж���
		if($rs[type]==1||$rs[type]==3){
			$detail=explode("\r\n",$rs[config]);
			foreach( $detail AS $key=>$value){
				if($value===''){
					continue;
				}
				$black=strlen($value)>20?'<br>':'&nbsp;&nbsp;&nbsp;';
				$ckk=$rss[answer]==$value?" checked ":" ";
				$rs[showcontent].="<input type='radio' name='answerdb[$rs[id]]' value='$value'  style='border:0px;' disabled $ckk> {$letterDB[$key]}��{$value} $black";
			}
		//��ѡ��
		}elseif($rs[type]==2){
			$detail=explode("\r\n",$rs[config]);
			$_detail=explode("\n",$rss[answer]);
			foreach( $detail AS $key=>$value){
				if($value===''){
					continue;
				}
				$black=strlen($value)>20?'<br>':'&nbsp;&nbsp;&nbsp;';
				$ckk=in_array($value,$_detail)?" checked ":" ";
				$rs[showcontent].="<input type='checkbox' name='answerdb[$rs[id]][]' value='$value' style='border:0px;' $ckk onclick='return false;'> {$letterDB[$key]}��{$value} $black";
			}
		//�����
		}elseif($rs[type]==4){
			preg_match_all("/<<<(.*?)>>>/is",$rs[question],$array);
			$rs[answer]=implode(" �� ",$array[1]);
			$rs[question]=preg_replace("/<<<(.*?)>>>/eis","input_value('$rs[id]','$rss[answer]')",$rs[question]);
		//������
		}elseif($rs[type]==5){
			$detail=explode("\r\n",$rs[config]);
			foreach( $detail AS $key=>$value){
				if($value===''){
					continue;
				}
				$rs[showcontent].="{$letterDB[$key]}��$value<BR>";
			}
			$rs[showcontent].="��:<input type='text' name='answerdb[$rs[id]]' style='border:0px;border-bottom:1px solid #ccc;width:270px;' value='$rss[answer]' readonly>";
		//�����
		}elseif($rs[type]==7){
			$rs[showcontent].="��:<input type='text' name='answerdb[$rs[id]]' style='border:0px;border-bottom:1px solid #ccc;width:370px;' value='$rss[answer]' readonly>";
		//�ʴ���,������
		}elseif($rs[type]==8||$rs[type]==9){
			$rs[showcontent].="��:<textarea name='answerdb[$rs[id]]' cols='65' rows='10' readonly>$rss[answer]</textarea>";
		//�����
		}else{
			$rs[showcontent].="��:<input type='text' name='answerdb[$rs[id]]' value='$rss[answer]' style='border:0px;border-bottom:1px solid #ccc;width:170px;' readonly>";
		}
		$listdb[$rs[type]][]=$rs;
	}
	ksort($listdb);
	require("head.php");
	require("template/exam_read/editpaper.htm");
	require("foot.php");
}


function input_value($id,$value)
{
	global $_valuedb;
	$_valuedb[$id]=intval($_valuedb[$id]);
	$detail=explode("\n",$value);
	$v=$detail[$_valuedb[$id]];
	$_valuedb[$id]++;
	return "<input type='text' name='answerdb[$id][]' style='border:0px;border-bottom:1px solid #ccc;width:70px;text-align:center;' value='$v' readonly>";
}

?>