<?php
require("global.php");

if($job=='postmsg'){
	
	if(!yzimg($yzimg))showParentmsg("��֤�벻��ȷ");
	$content=ReplaceHtmlAndJs($content);
	if(!$content) showParentmsg("�������ݲ���Ϊ��");
	if(strlen($content)<10)  showParentmsg("������������5������");
	if(strlen($content)>200) showParentmsg("�������������100������");
	
	if(!$sender) showParentmsg("�����뷢��������");
	if(!$email) showParentmsg("�����������ַ");
	if(strpos($email,"@")===false){
		 showParentmsg("�����ַ�����ϸ�ʽ");
	}

	//����

	$title="�����µĲ�Ʒ��Ϣ���ԣ�����:$sender";

	
	$content=$content."
	
������Ϣ��
	$about
	$FROMURL
	
��ϵ��ʽ��
    ������$sender
    �绰��$tel
    ���䣺$email
";
			if($touid){
				$array[touid]=$touid;
				$array[fromuid]=0;
				$array[fromer]="ϵͳ��Ϣ";
				$array[title]=filtrate($title);
				$array[content]=filtrate($content);
				pm_msgbox($array);
				showParentmsg("���Գɹ�,лл��",1);
			}else{
				showParentmsg("ϵͳ��æ�����Ժ�����!");
			}
}


function showParentmsg($msg,$parent_refresh=0){
	global $webdb;
	echo "<SCRIPT LANGUAGE=\"JavaScript\">
			<!--
			parent.document.getElementById('yzimg').src = '{$webdb[www_url]}/do/yzimg.php?'+Math.random();
			alert(\"$msg \");
			parent.document.getElementById('postSubmit').disabled=false;	
			
			";
	if($parent_refresh){
	echo "try{
		parent.refresh();
	}catch(e){
		parent.location=parent.location;
	}";
	}
	echo "
			//-->
			</SCRIPT>";exit;
}
?>