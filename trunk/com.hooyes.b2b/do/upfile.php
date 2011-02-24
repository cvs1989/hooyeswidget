<?php
require_once (dirname(__FILE__)."/".'global.php');
if(!$lfjid){
	echo '<meta http-equiv="Content-Type" content="text/html; charset=gb2312">
		 <body bgcolor="#FFFFFF" text="#000000" leftmargin="0" topmargin="0">';
	die("<A HREF='$webdb[www_url]/do/login.php' target='_blank'  onclick='window.self.close();'>请先在前台登录</A>");
}
?>
<html>
<head>
<title>Powered by qibosoft.com</title>
<meta name='keywords' content=''>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312">
<style type="text/css">
*{
	margin: 0;
	padding: 0;
	font-size: 12px;
}
</style>
</head>
<body bgcolor="#FFFFFF" text="#000000" leftmargin="0" topmargin="0">
<?php
if($postfile){

	//其中..与/开头都是不允许的
	if( !ereg("^[0-9a-z_/]+$",$dir)||ereg("^/",$dir) ){
		$dir="other";
	}
	$array[name]=is_array($postfile)?$_FILES[postfile][name]:$postfile_name;
	$array[path]=$webdb[updir]."/".$dir;
	$array[size]=is_array($postfile)?$_FILES[postfile][size]:$postfile_size;
	
	$array[updateTable]=1;	//统计用户上传的文件占用空间大小
	$filename=upfile(is_array($postfile)?$_FILES[postfile][tmp_name]:$postfile,$array);
	//删除用户反复上传的图片
	if($ISone){
		delete_attachment($lfjuid,tempdir("$oldfile"));
	}
	$newfile="$dir/$filename";
	echo "上传成功,<A HREF=?fn=$fn&dir=$dir&label=$_GET[label]&ISone=$_GET[ISone]&oldfile=$newfile>你可以继续或重新上传</A>";
	$fn || $fn="upfile";
	$weburl=tempdir($newfile);
	echo "<script>
				if(self==top){
					window.opener.$fn('$newfile','$array[name]','$array[size]','$_GET[label]','$weburl');
					window.self.close();
				}else{
					window.parent.$fn('$newfile','$array[name]','$array[size]','$_GET[label]','$weburl');
				}
		 </script>";
			
	exit;
}
?>
<form name="form1" method="post" action="" enctype="multipart/form-data">
  <input id="postfile" type="file" name="postfile" style="height:20px; background-color:#EBEBEB; border:1 solid black;" onMouseOver ="this.style.backgroundColor='#F0F0F0'" onMouseOut ="this.style.backgroundColor='#FAFAFA'"  onblur="post('')">
  <input  type="submit" name="Submit" value="上传文件" style="height:20px; background-color:#EBEBEB; border:1 solid black;" onMouseOver ="this.style.backgroundColor='#F0F0F0'" onMouseOut ="this.style.backgroundColor='#FAFAFA'" >
  <input type="hidden" name="action" value="uploadfile">
</form>
<SCRIPT LANGUAGE="JavaScript">
<!--
function post(va){
	
	if(document.getElementById("postfile").value!=''){
		document.form1.submit();
	}
	
}
//-->
</SCRIPT>
</body>
</html>