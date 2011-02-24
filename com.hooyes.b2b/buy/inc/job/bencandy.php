<?php
header('Content-Type: text/html; charset=gb2312'); 
if(!$lfjid)
{
	die("<A HREF='$webdb[www_url]/do/login.php' onclick=\"clickEdit.cancel('clickEdit_$TagId')\">请先登录</A>");
}
if($act=="do"){
	if(!$lfjuid){
		$power=0;
	}elseif($web_admin){
		$power=2;
	}else{
		$rs=$db->get_one("SELECT * FROM {$_pre}content WHERE id='$id'");
		if($rs[uid]==$lfjuid){
			$power=1;
		}else{
			$power=0;
		}
	}
	if($power==0){
		die("你无权操作");
	}

	echo "<A target='_blank' HREF=\"$webdb[www_url]/member/?main=$Murl/member/post.php?job=postnew&fid=$fid\">新发表</A><br><A target='_blank' HREF=\"$webdb[www_url]/member/?main=$Murl/member/post.php?job=edit&fid=$fid&id=$id\">修改</A><br><A HREF=\"$webdb[www_url]/member/?main=$Murl/member/post.php?action=del&fid=$fid&id=$id\" onclick=\"return confirm('你确认要删除吗?');\">删除</A><br>";

}
?>