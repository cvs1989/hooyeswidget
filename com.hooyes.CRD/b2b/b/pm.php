<?php
require("global.php");

if($job=='postmsg'){
	
	if(!yzimg($yzimg))showParentmsg("验证码不正确");
	$content=ReplaceHtmlAndJs($content);
	if(!$content) showParentmsg("留言内容不能为空");
	if(strlen($content)<10)  showParentmsg("留言内容最少5个汉字");
	if(strlen($content)>200) showParentmsg("留言内容最最多100个汉字");
	
	if(!$sender) showParentmsg("请输入发信者姓名");
	if(!$email) showParentmsg("请输入邮箱地址");
	if(strpos($email,"@")===false){
		 showParentmsg("邮箱地址不符合格式");
	}

	//发送

	$title="您有新的产品信息留言！来自:$sender";

	
	$content=$content."
	
关于信息：
	$about
	$FROMURL
	
联系方式：
    姓名：$sender
    电话：$tel
    邮箱：$email
";
			if($touid){
				$array[touid]=$touid;
				$array[fromuid]=0;
				$array[fromer]="系统消息";
				$array[title]=filtrate($title);
				$array[content]=filtrate($content);
				pm_msgbox($array);
				showParentmsg("留言成功,谢谢！",1);
			}else{
				showParentmsg("系统繁忙，请稍后再试!");
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