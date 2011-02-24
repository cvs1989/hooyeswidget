<?php
//处理POST上传图片

$psids="psid_".$ind;
$psid=$$psids;

	
$r=$db->get_one("SELECT COUNT(*) AS NUM FROM `{$_pre}pic` WHERE uid='$uid'");
if(!$web_admin&&$r[NUM]>$groupdb[post_photo_num]){

	if(!$groupdb[post_photo_num]){
		$msg=("你所在用户组不允许发布图片,你还需要发布的话,请升级");
	}else{
		$msg=("你所在用户组最多允许发布{$groupdb[post_sell_num]}张图片,你还需要发布的话,请升级,或者是删除一些旧的.");
	}

}elseif($psid){
	
	if(is_uploaded_file($_FILES[postfile][tmp_name])){
		$array[name]=is_array($postfile)?$_FILES[postfile][name]:$postfile_name;
		$title=$title?$title:$array[name];
		$myname_str=explode(".",strtolower($array[name]));
		$myname=$myname_str[(count($myname_str)-1)];
		if(!in_array($myname,array('gif','jpg'))) $msg="{$array[name]}图片只能是gif或者jpg的格式";		
		$array[path]="$webdb[updir]/homepage/pic/".ceil($uid/1000)."/$uid";//商家图片另存
		$array[size]=is_array($postfile)?$_FILES[postfile][size]:$postfile_size;
		$webdb[company_uploadsize_max]=$webdb[company_uploadsize_max]?$webdb[company_uploadsize_max]:100;
		//if($array[size]>$webdb[company_uploadsize_max]*1024)	$msg="{$array[name]}图片超过最大{$webdb[company_uploadsize_max]}K限制";
		
		if($msg==''){
			$picurl=upfile(is_array($postfile)?$_FILES[postfile][tmp_name]:$postfile,$array);
			//供应或求购生成缩略图
			
			if($picurl){
					
				$Newpicpath=ROOT_PATH."$array[path]/{$picurl}.gif";
				gdpic(ROOT_PATH."$array[path]/$picurl",$Newpicpath,120,120);
				if(!file_exists($Newpicpath)){
					copy(ROOT_PATH."$array[path]/{$picurl}",$Newpicpath);
				}
				$picurl="homepage/pic/".ceil($uid/1000)."/$uid/$picurl";
				$msg="{$array[name]}上传成功";
				$title=get_word($title,32);
				$db->query("INSERT INTO `{$_pre}pic` ( `pid` , `psid` , `uid` , `username` ,  `title` , `url` , `level` , `yz` , `posttime` , `isfm` , `orderlist`  ) VALUES ('', '$psid', '$uid', '$lfjid', '$title', '$picurl', '0', '{$webdb[auto_userpostpic]}', '$timestamp', '0', '0');");
					
			}else{
				$msg="{$array[name]}上传失败，请稍候再试。";
			}
		}	

	}else{
		$msg="不是要上传的文件,跳过上传";
	}

}else{

	$msg="请先选择一个图集"; 

}

echo "<SCRIPT LANGUAGE=\"JavaScript\">
	<!--
	parent.document.getElementById(\"userpostpic_$ind\").innerHTML='<strong>[{$ind}]</strong> $msg';
	parent.userpost_pic_do(".($ind+1).");			
	//-->
	</SCRIPT>";exit;

?>