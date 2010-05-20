<?php
require("../../class/connect.php");
require("../../class/db_sql.php");
require("../../class/functions.php");
require "../".LoadLang("pub/fun.php");
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
//验证权限
CheckLevel($logininid,$loginin,$classid,"user");

//------------------------增加用户
function AddUser($username,$password,$repassword,$groupid,$adminclass,$checked,$styleid,$loginuserid,$loginusername){global $empire,$class_r,$dbtbpre;
	if(!$username||!$password||!$repassword)
	{printerror("EmptyUsername","history.go(-1)");}
	if($password!=$repassword)
	{printerror("NotRepassword","history.go(-1)");}
	//操作权限
	CheckLevel($loginuserid,$loginusername,$classid,"user");
	$num=$empire->gettotal("select count(*) as total from {$dbtbpre}enewsuser where username='$username' limit 1");
	if($num)
	{printerror("ReUsername","history.go(-1)");}
	//管理目录
	for($i=0;$i<count($adminclass);$i++)
	{
		//大栏目
		if(empty($class_r[$adminclass[$i]][islast]))
		{
			if(empty($class_r[$adminclass[$i]][sonclass])||$class_r[$adminclass[$i]][sonclass]=="|")
			{
				continue;
			}
			else
			{
				$andclass=substr($class_r[$adminclass[$i]][sonclass],1);
			}
			$insert_class.=$andclass;
		}
		else
		{
			$insert_class.=$adminclass[$i]."|";
		}
    }
	$insert_class="|".$insert_class;
	$styleid=(int)$styleid;
	$groupid=(int)$groupid;
	$checked=(int)$checked;
	$filelevel=(int)$_POST['filelevel'];
	$rnd=make_password(20);
	$salt=make_password(8);
	$password=md5(md5($password).$salt);
	$truename=htmlspecialchars($_POST['truename']);
	$email=htmlspecialchars($_POST['email']);
	$sql=$empire->query("insert into {$dbtbpre}enewsuser(username,password,rnd,groupid,adminclass,checked,styleid,filelevel,salt,loginnum,lasttime,lastip,truename,email) values('$username','$password','$rnd',$groupid,'$insert_class',$checked,$styleid,'$filelevel','$salt',0,0,'','$truename','$email');");
	$userid=$empire->lastid();
	if($sql)
	{
		//操作日志
		insert_dolog("userid=".$userid."<br>username=".$username);
		printerror("AddUserSuccess","AddUser.php?enews=AddUser");
	}
	else
	{printerror("DbError","history.go(-1)");}
}

//------------------------修改用户
function EditUser($userid,$username,$password,$repassword,$groupid,$adminclass,$oldusername,$checked,$styleid,$loginuserid,$loginusername){
	global $empire,$class_r,$dbtbpre;
	$userid=(int)$userid;
	if(!$userid||!$username)
	{printerror("EnterUsername","history.go(-1)");}
	//操作权限
	CheckLevel($loginuserid,$loginusername,$classid,"user");
	//修改用户名
	if($oldusername<>$username)
	{
		$num=$empire->gettotal("select count(*) as total from {$dbtbpre}enewsuser where username='$username' and userid<>$userid limit 1");
		if($num)
		{printerror("ReUsername","history.go(-1)");}
		//修改信息
		//$nsql=$empire->query("update {$dbtbpre}enewsnews set username='$username' where username='$oldusername'");
		//修改日志
		$lsql=$empire->query("update {$dbtbpre}enewslog set username='$username' where username='$oldusername'");
		$lsql=$empire->query("update {$dbtbpre}enewsdolog set username='$username' where username='$oldusername'");
	}
	//修改密码
	if($password)
	{
		if($password!=$repassword)
		{printerror("NotRepassword","history.go(-1)");}
		$salt=make_password(8);
		$password=md5(md5($password).$salt);
		$add=",password='$password',salt='$salt'";
	}
	//管理目录
	for($i=0;$i<count($adminclass);$i++)
	{
		//大栏目
		if(empty($class_r[$adminclass[$i]][islast]))
		{
			if(empty($class_r[$adminclass[$i]][sonclass])||$class_r[$adminclass[$i]][sonclass]=="|")
			{
				continue;
			}
			else
			{
				$andclass=substr($class_r[$adminclass[$i]][sonclass],1);
			}
			$insert_class.=$andclass;
		}
		else
		{
			$insert_class.=$adminclass[$i]."|";
		}
    }
	$insert_class="|".$insert_class;
	$styleid=(int)$styleid;
	$groupid=(int)$groupid;
	$checked=(int)$checked;
	$filelevel=(int)$_POST['filelevel'];
	$truename=htmlspecialchars($_POST['truename']);
	$email=htmlspecialchars($_POST['email']);
	$sql=$empire->query("update {$dbtbpre}enewsuser set username='$username',groupid=$groupid,adminclass='$insert_class',checked=$checked,styleid=$styleid,filelevel='$filelevel',truename='$truename',email='$email'".$add." where userid='$userid'");
	if($_POST['oldadminclass']<>$insert_class)
	{
		DelFiletext('../../data/fc/ListEnews'.$userid.'.php');
	}
	if($sql)
	{
		//操作日志
		insert_dolog("userid=".$userid."<br>username=".$username);
		printerror("EditUserSuccess","ListUser.php");
	}
	else
	{printerror("DbError","history.go(-1)");}
}

//-----------------------删除用户
function DelUser($userid,$loginuserid,$loginusername){
	global $empire,$dbtbpre;
	$userid=(int)$userid;
	if(!$userid)
	{printerror("NotDelUserid","history.go(-1)");}
	//操作权限
	CheckLevel($loginuserid,$loginusername,$classid,"user");
	$r=$empire->fetch1("select username from {$dbtbpre}enewsuser where userid='$userid'");
	$sql=$empire->query("delete from {$dbtbpre}enewsuser where userid='$userid'");
	if($sql)
	{
		//操作日志
		insert_dolog("userid=".$userid."<br>username=".$r[username]);
		printerror("DelUserSuccess","ListUser.php");
	}
	else
	{printerror("DbError","history.go(-1)");}
}

$enews=$_POST['enews'];
if(empty($enews))
{$enews=$_GET['enews'];}
if($enews)
{
	include('../../data/dbcache/class.php');
}
//增加用户
if($enews=="AddUser")
{
	$username=$_POST['username'];
	$password=$_POST['password'];
	$repassword=$_POST['repassword'];
	$groupid=$_POST['groupid'];
	$adminclass=$_POST['adminclass'];
	$checked=$_POST['checked'];
	$styleid=$_POST['styleid'];
	AddUser($username,$password,$repassword,$groupid,$adminclass,$checked,$styleid,$logininid,$loginin);
}
//修改用户
elseif($enews=="EditUser")
{
	$userid=$_POST['userid'];
	$username=$_POST['username'];
	$password=$_POST['password'];
	$repassword=$_POST['repassword'];
	$groupid=$_POST['groupid'];
	$adminclass=$_POST['adminclass'];
	$oldusername=$_POST['oldusername'];
	$checked=$_POST['checked'];
	$styleid=$_POST['styleid'];
	EditUser($userid,$username,$password,$repassword,$groupid,$adminclass,$oldusername,$checked,$styleid,$logininid,$loginin);
}
//删除用户
elseif($enews=="DelUser")
{
	$userid=$_GET['userid'];
	DelUser($userid,$logininid,$loginin);
}

$page=(int)$_GET['page'];
$start=0;
$line=25;//每页显示条数
$page_line=12;//每页显示链接数
$offset=$page*$line;//总偏移量
$url="<a href=ListUser.php>管理用户</a>";
//排序
$mydesc=(int)$_GET['mydesc'];
$desc=$mydesc?'asc':'desc';
$orderby=(int)$_GET['orderby'];
if($orderby==1)//用户名
{
	$order="username ".$desc.",userid desc";
	$usernamedesc=$mydesc?0:1;
}
elseif($orderby==2)//用户组
{
	$order="groupid ".$desc.",userid desc";
	$groupiddesc=$mydesc?0:1;
}
elseif($orderby==3)//状态
{
	$order="checked ".$desc.",userid desc";
	$checkeddesc=$mydesc?0:1;
}
elseif($orderby==4)//登陆次数
{
	$order="loginnum ".$desc.",userid desc";
	$loginnumdesc=$mydesc?0:1;
}
elseif($orderby==5)//最后登陆
{
	$order="lasttime ".$desc.",userid desc";
	$lasttimedesc=$mydesc?0:1;
}
else//用户ID
{
	$order="userid ".$desc;
	$useriddesc=$mydesc?0:1;
}
$search="&orderby=$orderby&mydesc=$mydesc";
$query="select * from {$dbtbpre}enewsuser";
$num=$empire->num($query);//取得总条数
$query=$query." order by ".$order." limit $offset,$line";
$sql=$empire->query($query);
$returnpage=page2($num,$line,$page_line,$start,$page,$search);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>管理用户</title>
<link href="../adminstyle/<?=$loginadminstyleid?>/adminstyle.css" rel="stylesheet" type="text/css">
</head>

<body>
<table width="100%" border="0" align="center" cellpadding="3" cellspacing="1">
  <tr> 
    <td width="50%">位置： 
      <?=$url?>
    </td>
    <td><div align="right">
        <input type="button" name="Submit5" value="增加用户" onclick="self.location.href='AddUser.php?enews=AddUser';">
      </div></td>
  </tr>
</table>
<br>
<table width="100%" border="0" align="center" cellpadding="3" cellspacing="1" class="tableborder">
  <tr class="header"> 
    <td width="6%" height="25"><div align="center"><a href="ListUser.php?orderby=0&mydesc=<?=$useriddesc?>">ID</a></div></td>
    <td width="24%" height="25"><div align="center"><a href="ListUser.php?orderby=1&mydesc=<?=$usernamedesc?>">用户名</a></div></td>
    <td width="17%"><div align="center"><a href="ListUser.php?orderby=2&mydesc=<?=$groupiddesc?>">等级</a></div></td>
    <td width="6%"><div align="center"><a href="ListUser.php?orderby=3&mydesc=<?=$checkeddesc?>">状态</a></div></td>
    <td width="8%"><div align="center"><a href="ListUser.php?orderby=4&mydesc=<?=$loginnumdesc?>">登陆次数</a></div></td>
    <td width="25%"><div align="center"><a href="ListUser.php?orderby=5&mydesc=<?=$lasttimedesc?>">最后登陆</a></div></td>
    <td width="14%" height="25"><div align="center">操作</div></td>
  </tr>
  <?
  while($r=$empire->fetch($sql))
  {
	$gr=$empire->fetch1("select groupname from {$dbtbpre}enewsgroup where groupid='$r[groupid]'");
  if($r[checked])
  {$zt="禁用";}
  else
  {$zt="开启";}
  $lasttime='---';
  if($r[lasttime])
  {
  	$lasttime=date("Y-m-d H:i:s",$r[lasttime]);
  }
  ?>
  <tr bgcolor="ffffff"> 
    <td height="25"><div align="center"> 
        <?=$r[userid]?>
      </div></td>
    <td height="25"><div align="center"> 
        <?=$r[username]?>
      </div></td>
    <td><div align="center"> 
        <?=$gr[groupname]?>
      </div></td>
    <td><div align="center"> 
        <?=$zt?>
      </div></td>
    <td><div align="center"><?=$r[loginnum]?></div></td>
    <td>
      时间：<?=$lasttime?>
      <br>
      IP&nbsp;&nbsp;&nbsp;：<?=$r[lastip]?>
    </td>
    <td height="25"><div align="center">[<a href="AddUser.php?enews=EditUser&userid=<?=$r[userid]?>">修改</a>] 
        [<a href="ListUser.php?enews=DelUser&userid=<?=$r[userid]?>" onclick="return confirm('确认要删除？');">删除</a>]</div></td>
  </tr>
  <?
  }
  ?>
  <tr bgcolor="ffffff"> 
    <td height="25" colspan="7"> 
      <?=$returnpage?>
    </td>
  </tr>
</table>
</body>
</html>
<?
db_close();
$empire=null;
?>
