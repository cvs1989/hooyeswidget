<?php
require("../../class/connect.php");
require("../../class/db_sql.php");
require("../../class/functions.php");
$link=db_connect();
$empire=new mysqlquery();
$editor=1;
//验证用户
$lur=is_login();
$logininid=$lur['userid'];
$loginin=$lur['username'];
$loginrnd=$lur['rnd'];
$loginlevel=$lur['groupid'];
$loginadminstyleid=$lur['adminstyleid'];

//修改密码
function EditPassword($userid,$username,$oldpassword,$password,$repassword,$styleid,$oldstyleid,$add){
	global $empire,$dbtbpre,$gr;
	$styleid=(int)$styleid;
	$oldstyleid=(int)$oldstyleid;
	$username=RepPostVar($username);
	$oldpassword=RepPostVar($oldpassword);
	$password=RepPostVar($password);
	$truename=RepPostStr($add[truename]);
	$email=RepPostStr($add[email]);
	if(!$userid||!$username)
	{
		printerror("EmptyOldPassword","history.go(-1)");
	}
	//修改密码
	$a='';
	if($oldpassword)
	{
		if(!$username||!$oldpassword)
		{
			printerror("EmptyOldPassword","history.go(-1)");
		}
		if(!trim($password)||!trim($repassword))
		{
			printerror("EmptyNewPassword","history.go(-1)");
		}
		if($password<>$repassword)
		{
			printerror("NotRepassword","history.go(-1)");
		}
		$user_r=$empire->fetch1("select userid,password,salt from {$dbtbpre}enewsuser where username='".$username."' limit 1");
		if(!$user_r['userid'])
		{
			printerror("OldPasswordFail","history.go(-1)");
		}
		$ch_oldpassword=md5(md5($oldpassword).$user_r['salt']);
		if($user_r['password']!=$ch_oldpassword)
		{
			printerror("OldPasswordFail","history.go(-1)");
		}
		$salt=make_password(8);
		$password=md5(md5($password).$salt);
		$a=",password='$password',salt='$salt'";
	}
	//风格
	if($gr['dochadminstyle'])
	{
		$a.=",styleid='$styleid'";
	}
	$sql=$empire->query("update {$dbtbpre}enewsuser set truename='$truename',email='$email'".$a." where username='$username'");
	if($sql)
	{
		//操作日志
		insert_dolog("");
		//改变风格
		if($styleid!=$oldstyleid)
		{
			$styler=$empire->fetch1("select path from {$dbtbpre}enewsadminstyle where styleid='$styleid'");
			if($styler['path'])
			{
				$set=esetcookie("loginadminstyleid",$styler['path'],0,1);
			}
			echo"Edit password success!<script>parent.location.href='../admin.php';</script>";
			exit();
		}
		else
		{
			printerror("EditPasswordSuccess","EditPassword.php");
		}
	}
	else
	{printerror("DbError","history.go(-1)");}
}

$gr=$empire->fetch1("select dochadminstyle from {$dbtbpre}enewsgroup where groupid='$loginlevel'");

$enews=$_POST['enews'];
if(empty($enews))
{$enews=$_GET['enews'];}
//修改密码
if($enews=="EditPassword")
{
	$oldpassword=$_POST['oldpassword'];
	$password=$_POST['password'];
	$repassword=$_POST['repassword'];
	$styleid=(int)$_POST['styleid'];
	$oldstyleid=(int)$_POST['oldstyleid'];
	EditPassword($logininid,$loginin,$oldpassword,$password,$repassword,$styleid,$oldstyleid,$_POST);
}

$r=$empire->fetch1("select userid,styleid,truename,email from {$dbtbpre}enewsuser where userid='$logininid'");
if($gr['dochadminstyle'])
{
	//后台样式
	$stylesql=$empire->query("select styleid,stylename,path from {$dbtbpre}enewsadminstyle order by styleid");
	$style="";
	while($styler=$empire->fetch($stylesql))
	{
		if($r[styleid]==$styler[styleid])
		{$sselect=" selected";}
		else
		{$sselect="";}
		$style.="<option value=".$styler[styleid].$sselect.">".$styler[stylename]."</option>";
	}
}
db_close();
$empire=null;
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link href="../adminstyle/<?=$loginadminstyleid?>/adminstyle.css" rel="stylesheet" type="text/css">
<title>修改资料</title>
</head>

<body>
<table width="100%" border="0" align="center" cellpadding="3" cellspacing="1">
  <tr>
    <td>位置：<a href="EditPassword.php">修改个人资料</a></td>
  </tr>
</table>
<form name="form1" method="post" action="EditPassword.php">
  <table width="100%" border="0" align="center" cellpadding="3" cellspacing="1" class="tableborder">
    <tr class="header"> 
      <td height="25" colspan="2">修改资料 
        <input name="enews" type="hidden" id="enews" value="EditPassword"> </td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td width="27%" height="25">用户名：</td>
      <td width="73%" height="25"> 
        <?=$loginin?>
      </td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td height="25">旧密码：</td>
      <td height="25"><input name="oldpassword" type="password" id="oldpassword" size="32">
        <font color="#666666">(不修改密码,请留空) </font></td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td height="25">新密码：</td>
      <td height="25"><input name="password" type="password" id="password" size="32">
        <font color="#666666">(不修改密码,请留空) </font></td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td height="25">重复新密码：</td>
      <td height="25"><input name="repassword" type="password" id="repassword" size="32">
        <font color="#666666">(不修改密码,请留空) </font></td>
    </tr>
    <tr bgcolor="#FFFFFF">
      <td height="25">姓名：</td>
      <td height="25"><input name="truename" type="text" id="truename" value="<?=$r[truename]?>" size="32"></td>
    </tr>
    <tr bgcolor="#FFFFFF">
      <td height="25">邮箱：</td>
      <td height="25"><input name="email" type="text" id="email" value="<?=$r[email]?>" size="32"></td>
    </tr>
    <?php
	if($gr['dochadminstyle'])
	{
	?>
    <tr bgcolor="#FFFFFF"> 
      <td height="25">操作界面：</td>
      <td height="25"><select name="styleid" id="styleid">
          <?=$style?>
        </select> <input type="button" name="Submit6222322" value="管理后台样式" onclick="window.open('../template/AdminStyle.php');"> 
        <input name="oldstyleid" type="hidden" id="oldstyleid" value="<?=$r[styleid]?>"> 
      </td>
    </tr>
    <?php
	}
	?>
    <tr bgcolor="#FFFFFF"> 
      <td height="25">&nbsp;</td>
      <td height="25"><input type="submit" name="Submit" value="提交"> <input type="reset" name="Submit2" value="重置"></td>
    </tr>
  </table>
</form>
</body>
</html>
