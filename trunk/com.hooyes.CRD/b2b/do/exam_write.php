<?php
require("global.php");

$paperType=array("1"=>"单选题","2"=>"多选题","3"=>"判断题","4"=>"填空题","5"=>"排序题","6"=>"计算题","7"=>"简答题","8"=>"问答题","9"=>"作文题");
$numDB=array("零","一","二","三","四","五","六","七","八","九","十","十一","十二","十三","十四");
$letterDB=array("a","b","c","d","e","f","g");

$rsdb=$db->get_one("SELECT F.*,S.name AS fname FROM {$pre}exam_form F LEFT JOIN {$pre}exam_sort S ON F.fid=S.fid WHERE F.id='$id'");
if(!$rsdb){
	showerr("数据不存在!");
}

$config=@unserialize($rsdb[config]);

//SEO
$titleDB[title]		= "$rsdb[name] - $rsdb[fname] - $titleDB[title]";

//提交试卷
if($action=="postAnswer")
{
	//试卷的话,必须要先登录
	if($rsdb[type]==1){
		if(!$lfjuid){
			showerr("请先登录!");
		}else{
			@extract($db->get_one("SELECT COUNT(*) AS NUM FROM {$pre}exam_student WHERE form_id='$id' AND student_uid='$lfjuid'"));
			if($NUM>0){
				showerr("你已经提交过了,请不要重复提交");
			}
		}
	}

	//获取每种题型的分数是多少
	$query = $db->query("SELECT COUNT(A.type) AS num,A.type FROM `{$pre}exam_form_element` E LEFT JOIN {$pre}exam_title A ON E.title_id=A.id WHERE E.form_id='$id' GROUP BY A.type");
	while($rs = $db->fetch_array($query)){
		$_L[$rs[type]]=$config[fendb][$rs[type]]/$rs[num];
	}
	
	//插入用户资料.证实已考试,游客的话,只考虑是参加调查表单
	$student_name=$lfjid?$lfjid:$onlineip;
	$db->query("INSERT INTO `{$pre}exam_student` (`student_uid`, `form_id`, `student_name`,posttime) VALUES ('$lfjuid','$id','$student_name','$timestamp')");
	$student_id=$db->insert_id();
	
	$total_fen=$total_num=0;
	$query = $db->query("SELECT A.* FROM `{$pre}exam_form_element` E LEFT JOIN `{$pre}exam_title` A ON E.title_id=A.id WHERE E.form_id='$id' ");
	while($rs = $db->fetch_array($query)){
		if( $answerdb[$rs[id]]!='' ){
			
			//多选题
			if(is_array($answerdb[$rs[id]])){
				$answer=implode("\n",$answerdb[$rs[id]]);
			}else{
				$answer=$answerdb[$rs[id]];
			}

			$fen=0;
			//对于试卷类型的题目,这种类型的题目可以直接得出分数,
			if( $rsdb[type]==1 && ereg("^(1|2|3|4|5|6)$",$rs[type]) ){
				//填空题要特别处理
				if($rs[type]==4){
					//每答对一个答案都要给分
					preg_match_all("/<<<(.*?)>>>/is",$rs[question],$array);
					$each_fen=$_L[$rs[type]]/count($array[1]);
					foreach($array[1] AS $key=>$value){
						if(trim($value)==trim($answerdb[$rs[id]][$key])){
							$fen+=$each_fen;
						}
					}					
				}elseif($rs[type]==2){
					//多选题中,设置答案的时候.排序不能打乱,只选中部分的给一半分,一旦有错.不给分
					if(trim($rs[answer])==implode(",",$answerdb[$rs[id]])){
						$fen=$_L[$rs[type]];
					}elseif($answerdb[$rs[id]]){
						$fen=$_L[$rs[type]]/2;
						$answer_array=explode(",",$rs[answer]);
						foreach( $answerdb[$rs[id]] AS $value){
							if(!in_array($value,$answer_array)){
								$fen=0;							//一旦有错.不给分
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

			//防止有不安全代码
			$answer = preg_replace('/javascript/i','java script',$answer);
			$answer = preg_replace('/<iframe ([^<>]+)>/i','&lt;iframe \\1>',$answer);
			$db->query("INSERT INTO `{$pre}exam_student_title` ( `student_id`, `title_id`, `form_id`, `answer`, `fen` ) VALUES ('$student_id','$rs[id]','$id','$answer','$fen' )");
		}
	}
	if($total_fen){
		$db->query("UPDATE `{$pre}exam_student` SET total_fen='$total_fen' WHERE student_id='$student_id'");
	}
	if($rsdb[type]==2){
		refreshto("$webdb[www_url]/","谢谢你参与本次活动调查",30);
	}else{
		refreshto("$webdb[www_url]/","你在本次考查中,共答对{$total_num}题,所得总分是:{$total_fen}分",60);
	}		
}

unset($listdb);
$query = $db->query("SELECT T.* FROM `{$pre}exam_form_element` E LEFT JOIN `{$pre}exam_title` T ON E.title_id=T.id WHERE E.form_id='$id' ORDER BY E.list DESC,E.element_id ASC ");
while($rs = $db->fetch_array($query)){
	//处理有些题库已被删除
	if(!$rs[id]){
		continue;
	}
	$rs[showcontent]='';
	//单选题与判断题
	if($rs[type]==1||$rs[type]==3){
		$detail=explode("\r\n",$rs[config]);
		foreach( $detail AS $key=>$value){
			if($value===''){
				continue;
			}
			$black=strlen($value)>20?'<br>':'&nbsp;&nbsp;&nbsp;';
			$rs[showcontent].="<input type='radio' name='answerdb[$rs[id]]' value='$value'  style='border:0px;'> {$letterDB[$key]}、{$value} $black";
		}
	//多选题
	}elseif($rs[type]==2){
		$detail=explode("\r\n",$rs[config]);
		foreach( $detail AS $key=>$value){
			if($value===''){
				continue;
			}
			$black=strlen($value)>20?'<br>':'&nbsp;&nbsp;&nbsp;';
			$rs[showcontent].="<input type='checkbox' name='answerdb[{$rs[id]}][]' value='$value' style='border:0px;'> {$letterDB[$key]}、{$value} $black";
		}
	//填空题
	}elseif($rs[type]==4){
		$rs[question]=preg_replace("/<<<(.*?)>>>/is","<input type='text' name='answerdb[$rs[id]][]' style='border:0px;border-bottom:1px solid #ccc;width:70px;'>",$rs[question]);
	//排序题
	}elseif($rs[type]==5){
		$detail=explode("\r\n",$rs[config]);
		foreach( $detail AS $key=>$value){
			if($value===''){
				continue;
			}
			$rs[showcontent].="{$letterDB[$key]}、$value<BR>";
		}
		$rs[showcontent].="答:<input type='text' name='answerdb[$rs[id]]' style='border:0px;border-bottom:1px solid #ccc;width:270px;'>";
	//简答题
	}elseif($rs[type]==7){
		$rs[showcontent].="答:<input type='text' name='answerdb[$rs[id]]' style='border:0px;border-bottom:1px solid #ccc;width:370px;'>";
	//问答题,作文题
	}elseif($rs[type]==8||$rs[type]==9){
		$rs[showcontent].="答:<textarea name='answerdb[$rs[id]]' cols='65' rows='10'></textarea>";
	//其它题型,如计算题
	}else{
		$rs[showcontent].="答:<input type='text' name='answerdb[$rs[id]]' style='border:0px;border-bottom:1px solid #ccc;width:170px;'>";
	}
	$listdb[$rs[type]][]=$rs;
}
ksort($listdb);

require(PHP168_PATH."inc/head.php");
require(html("exam_write"));
require(PHP168_PATH."inc/foot.php");
?>