<?php
require("global.php");

$paperType=array("1"=>"��ѡ��","2"=>"��ѡ��","3"=>"�ж���","4"=>"�����","5"=>"������","6"=>"������","7"=>"�����","8"=>"�ʴ���","9"=>"������");
$numDB=array("��","һ","��","��","��","��","��","��","��","��","ʮ","ʮһ","ʮ��","ʮ��","ʮ��");
$letterDB=array("a","b","c","d","e","f","g");

$rsdb=$db->get_one("SELECT F.*,S.name AS fname FROM {$pre}exam_form F LEFT JOIN {$pre}exam_sort S ON F.fid=S.fid WHERE F.id='$id'");
if(!$rsdb){
	showerr("���ݲ�����!");
}

$config=@unserialize($rsdb[config]);

//SEO
$titleDB[title]		= "$rsdb[name] - $rsdb[fname] - $titleDB[title]";

//�ύ�Ծ�
if($action=="postAnswer")
{
	//�Ծ�Ļ�,����Ҫ�ȵ�¼
	if($rsdb[type]==1){
		if(!$lfjuid){
			showerr("���ȵ�¼!");
		}else{
			@extract($db->get_one("SELECT COUNT(*) AS NUM FROM {$pre}exam_student WHERE form_id='$id' AND student_uid='$lfjuid'"));
			if($NUM>0){
				showerr("���Ѿ��ύ����,�벻Ҫ�ظ��ύ");
			}
		}
	}

	//��ȡÿ�����͵ķ����Ƕ���
	$query = $db->query("SELECT COUNT(A.type) AS num,A.type FROM `{$pre}exam_form_element` E LEFT JOIN {$pre}exam_title A ON E.title_id=A.id WHERE E.form_id='$id' GROUP BY A.type");
	while($rs = $db->fetch_array($query)){
		$_L[$rs[type]]=$config[fendb][$rs[type]]/$rs[num];
	}
	
	//�����û�����.֤ʵ�ѿ���,�ο͵Ļ�,ֻ�����ǲμӵ����
	$student_name=$lfjid?$lfjid:$onlineip;
	$db->query("INSERT INTO `{$pre}exam_student` (`student_uid`, `form_id`, `student_name`,posttime) VALUES ('$lfjuid','$id','$student_name','$timestamp')");
	$student_id=$db->insert_id();
	
	$total_fen=$total_num=0;
	$query = $db->query("SELECT A.* FROM `{$pre}exam_form_element` E LEFT JOIN `{$pre}exam_title` A ON E.title_id=A.id WHERE E.form_id='$id' ");
	while($rs = $db->fetch_array($query)){
		if( $answerdb[$rs[id]]!='' ){
			
			//��ѡ��
			if(is_array($answerdb[$rs[id]])){
				$answer=implode("\n",$answerdb[$rs[id]]);
			}else{
				$answer=$answerdb[$rs[id]];
			}

			$fen=0;
			//�����Ծ����͵���Ŀ,�������͵���Ŀ����ֱ�ӵó�����,
			if( $rsdb[type]==1 && ereg("^(1|2|3|4|5|6)$",$rs[type]) ){
				//�����Ҫ�ر���
				if($rs[type]==4){
					//ÿ���һ���𰸶�Ҫ����
					preg_match_all("/<<<(.*?)>>>/is",$rs[question],$array);
					$each_fen=$_L[$rs[type]]/count($array[1]);
					foreach($array[1] AS $key=>$value){
						if(trim($value)==trim($answerdb[$rs[id]][$key])){
							$fen+=$each_fen;
						}
					}					
				}elseif($rs[type]==2){
					//��ѡ����,���ô𰸵�ʱ��.�����ܴ���,ֻѡ�в��ֵĸ�һ���,һ���д�.������
					if(trim($rs[answer])==implode(",",$answerdb[$rs[id]])){
						$fen=$_L[$rs[type]];
					}elseif($answerdb[$rs[id]]){
						$fen=$_L[$rs[type]]/2;
						$answer_array=explode(",",$rs[answer]);
						foreach( $answerdb[$rs[id]] AS $value){
							if(!in_array($value,$answer_array)){
								$fen=0;							//һ���д�.������
							}
						}
					}
				}elseif(trim($answer)==trim($rs[answer])){
					$fen=$_L[$rs[type]];
				}
				if($fen){
					$total_num++;
					$total_fen+=$fen;
				}
			}

			//��ֹ�в���ȫ����
			$answer = preg_replace('/javascript/i','java script',$answer);
			$answer = preg_replace('/<iframe ([^<>]+)>/i','&lt;iframe \\1>',$answer);
			$db->query("INSERT INTO `{$pre}exam_student_title` ( `student_id`, `title_id`, `form_id`, `answer`, `fen` ) VALUES ('$student_id','$rs[id]','$id','$answer','$fen' )");
		}
	}
	if($total_fen){
		$db->query("UPDATE `{$pre}exam_student` SET total_fen='$total_fen' WHERE student_id='$student_id'");
	}
	if($rsdb[type]==2){
		refreshto("$webdb[www_url]/","лл����뱾�λ����",30);
	}else{
		refreshto("$webdb[www_url]/","���ڱ��ο�����,�����{$total_num}��,�����ܷ���:{$total_fen}��",60);
	}		
}

unset($listdb);
$query = $db->query("SELECT T.* FROM `{$pre}exam_form_element` E LEFT JOIN `{$pre}exam_title` T ON E.title_id=T.id WHERE E.form_id='$id' ORDER BY E.list DESC,E.element_id ASC ");
while($rs = $db->fetch_array($query)){
	//������Щ����ѱ�ɾ��
	if(!$rs[id]){
		continue;
	}
	$rs[showcontent]='';
	//��ѡ�����ж���
	if($rs[type]==1||$rs[type]==3){
		$detail=explode("\r\n",$rs[config]);
		foreach( $detail AS $key=>$value){
			if($value===''){
				continue;
			}
			$black=strlen($value)>20?'<br>':'&nbsp;&nbsp;&nbsp;';
			$rs[showcontent].="<input type='radio' name='answerdb[$rs[id]]' value='$value'  style='border:0px;'> {$letterDB[$key]}��{$value} $black";
		}
	//��ѡ��
	}elseif($rs[type]==2){
		$detail=explode("\r\n",$rs[config]);
		foreach( $detail AS $key=>$value){
			if($value===''){
				continue;
			}
			$black=strlen($value)>20?'<br>':'&nbsp;&nbsp;&nbsp;';
			$rs[showcontent].="<input type='checkbox' name='answerdb[{$rs[id]}][]' value='$value' style='border:0px;'> {$letterDB[$key]}��{$value} $black";
		}
	//�����
	}elseif($rs[type]==4){
		$rs[question]=preg_replace("/<<<(.*?)>>>/is","<input type='text' name='answerdb[$rs[id]][]' style='border:0px;border-bottom:1px solid #ccc;width:70px;'>",$rs[question]);
	//������
	}elseif($rs[type]==5){
		$detail=explode("\r\n",$rs[config]);
		foreach( $detail AS $key=>$value){
			if($value===''){
				continue;
			}
			$rs[showcontent].="{$letterDB[$key]}��$value<BR>";
		}
		$rs[showcontent].="��:<input type='text' name='answerdb[$rs[id]]' style='border:0px;border-bottom:1px solid #ccc;width:270px;'>";
	//�����
	}elseif($rs[type]==7){
		$rs[showcontent].="��:<input type='text' name='answerdb[$rs[id]]' style='border:0px;border-bottom:1px solid #ccc;width:370px;'>";
	//�ʴ���,������
	}elseif($rs[type]==8||$rs[type]==9){
		$rs[showcontent].="��:<textarea name='answerdb[$rs[id]]' cols='65' rows='10'></textarea>";
	//��������,�������
	}else{
		$rs[showcontent].="��:<input type='text' name='answerdb[$rs[id]]' style='border:0px;border-bottom:1px solid #ccc;width:170px;'>";
	}
	$listdb[$rs[type]][]=$rs;
}
ksort($listdb);

require(PHP168_PATH."inc/head.php");
require(html("exam_write"));
require(PHP168_PATH."inc/foot.php");
?>