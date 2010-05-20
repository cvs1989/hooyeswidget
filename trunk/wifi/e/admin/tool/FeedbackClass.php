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

$enews=$_POST['enews'];
if(empty($enews))
{$enews=$_GET['enews'];}
if($enews)
{
	include("../../class/com_functions.php");
}
if($enews=="ReMoreFeedbackClassFile")
{
	ReMoreFeedbackClassFile(0,$logininid,$loginin);
}
//验证权限
CheckLevel($logininid,$loginin,$classid,"feedbackf");
include "../".LoadLang("pub/fun.php");
if($enews=="AddFeedbackClass")
{
	AddFeedbackClass($_POST,$logininid,$loginin);
}
elseif($enews=="EditFeedbackClass")
{
	EditFeedbackClass($_POST,$logininid,$loginin);
}
elseif($enews=="DelFeedbackClass")
{
	DelFeedbackClass($_GET,$logininid,$loginin);
}
$page=(int)$_GET['page'];
$start=0;
$line=30;//每页显示条数
$page_line=23;//每页显示链接数
$offset=$page*$line;//总偏移量
$query="select bid,bname from {$dbtbpre}enewsfeedbackclass";
$totalquery="select count(*) as total from {$dbtbpre}enewsfeedbackclass";
$num=$empire->gettotal($totalquery);//取得总条数
$query=$query." order by bid desc limit $offset,$line";
$sql=$empire->query($query);
$returnpage=page2($num,$line,$page_line,$start,$page,$search);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title></title>
<link href="../adminstyle/<?=$loginadminstyleid?>/adminstyle.css" rel="stylesheet" type="text/css">
</head>

<body>
<table width="100%" border="0" align="center" cellpadding="3" cellspacing="1">
  <tr> 
    <td width="50%">位置：<a href="feedback.php">管理信息反馈</a>&nbsp;&gt;&nbsp;<a href="FeedbackClass.php">管理反馈分类</a></td>
    <td><div align="right">
        <input type="button" name="Submit5" value="增加反馈分类" onclick="self.location.href='AddFeedbackClass.php?enews=AddFeedbackClass';">&nbsp;&nbsp;
        <input type="button" name="Submit52" value="管理反馈字段" onclick="self.location.href='ListFeedbackF.php';">
      </div></td>
  </tr>
</table>
<br>
<table width="100%" border="0" align="center" cellpadding="3" cellspacing="1" class="tableborder">
  <tr class="header"> 
    <td width="6%"><div align="center">ID</div></td>
    <td width="32%" height="25"><div align="center">分类名称</div></td>
    <td width="42%"><div align="center">反馈提交地址</div></td>
    <td width="20%" height="25"><div align="center">操作</div></td>
  </tr>
  <?
  while($r=$empire->fetch($sql))
  {
  	$gourl=$public_r[newsurl]."e/tool/feedback/?bid=".$r[bid];
  ?>
    <tr bgcolor="#FFFFFF"> 
      <td><div align="center"> 
          <?=$r[bid]?>
        </div></td>
      <td height="25"> <div align="center"> 
          <a href="<?=$gourl?>" target="_blank"><?=$r[bname]?></a>
        </div></td>
      <td><div align="center"> 
          <input name="textfield" type="text" size="38" value="<?=$gourl?>">
          [<a href="<?=$gourl?>" target="_blank">访问</a>]</div></td>
      <td height="25"><div align="center">[<a href="AddFeedbackClass.php?enews=EditFeedbackClass&bid=<?=$r[bid]?>">修改</a>] 
        [<a href="AddFeedbackClass.php?enews=AddFeedbackClass&bid=<?=$r[bid]?>&docopy=1">复制</a>] [<a href="FeedbackClass.php?enews=DelFeedbackClass&bid=<?=$r[bid]?>" onclick="return confirm('确认要删除?');">删除</a>] </div></td>
    </tr>
  <?
  }
  db_close();
  $empire=null;
  ?>
    <tr bgcolor="#FFFFFF"> 
      <td>&nbsp;</td>
      <td height="25" colspan="3"><?=$returnpage?></td>
    </tr>
</table>
</body>
</html>
