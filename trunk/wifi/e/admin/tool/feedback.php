<?php
require("../../class/connect.php");
require("../../class/db_sql.php");
require("../../class/functions.php");
require("../../class/com_functions.php");
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
CheckLevel($logininid,$loginin,$classid,"feedback");
$enews=$_GET['enews'];
if(empty($enews))
{
	$enews=$_POST['enews'];
}
if($enews=="DelFeedback")
{
	$id=$_GET['id'];
	$bid=$_GET['bid'];
	DelFeedback($id,$bid,$logininid,$loginin);
}
elseif($enews=="DelFeedback_all")
{
	$id=$_POST['id'];
	$bid=$_POST['bid'];
	DelFeedback_all($id,$bid,$logininid,$loginin);
}
include "../".LoadLang("pub/fun.php");
$page=(int)$_GET['page'];
$start=0;
$line=25;//每页显示条数
$page_line=12;//每页显示链接数
$offset=$page*$line;//总偏移量
$query="select id,bid,title,saytime,userid,username from {$dbtbpre}enewsfeedback";
$totalquery="select count(*) as total from {$dbtbpre}enewsfeedback";
//选择分类
$bid=(int)$_GET['bid'];
if($bid)
{
	$query.=" where bid='$bid'";
	$totalquery.=" where bid='$bid'";
	$search="&bid=$bid";
}
$num=$empire->gettotal($totalquery);//取得总条数
$query=$query." order by id desc limit $offset,$line";
$sql=$empire->query($query);
$returnpage=page2($num,$line,$page_line,$start,$page,$search);
$url="<a href=feedback.php>管理信息反馈</a>";
$gbclass=ReturnGbookClass($bid,1);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>管理信息反馈</title>
<link href="../adminstyle/<?=$loginadminstyleid?>/adminstyle.css" rel="stylesheet" type="text/css">
</head>

<body>
<table width="100%" border="0" align="center" cellpadding="3" cellspacing="1">
  <tr> 
    <td width="50%">位置： 
      <?=$url?>
    </td>
    <td><div align="right"> 
        <input type="button" name="Submit5" value="管理反馈分类" onclick="self.location.href='FeedbackClass.php';">
        &nbsp;&nbsp; 
        <input type="button" name="Submit52" value="管理反馈字段" onclick="self.location.href='ListFeedbackF.php';">
      </div></td>
  </tr>
</table>
<table width="100%" border="0" align="center" cellpadding="3" cellspacing="1">
  <tr>
    <td><div align="center">
        <p>选择反馈分类: 
          <select name="bid" id="bid" onchange=window.location='feedback.php?bid='+this.options[this.selectedIndex].value>
            <option value="0">显示全部反馈</option>
            <?=$gbclass?>
          </select>
        </p>
        </div></td>
  </tr>
</table>
<form name="form1" method="post" action="feedback.php" onsubmit="return confirm('确认要删除?');">
  <table width="100%" border="0" align="center" cellpadding="3" cellspacing="1" class=tableborder>
    <tr class=header> 
      <td width="7%" height="23"><div align="center">ID</div></td>
      <td width="43%" height="23"><div align="center">标题(点击查看)</div></td>
      <td width="20%" height="23"><div align="center">所属分类</div></td>
      <td width="18%" height="23"><div align="center">发布时间</div></td>
      <td width="12%" height="23"><div align="center">操作</div></td>
    </tr>
    <?
  while($r=$empire->fetch($sql))
  {
  	$br=$empire->fetch1("select bname from {$dbtbpre}enewsfeedbackclass where bid='$r[bid]'");
  	$username="游客";
  	if($r['userid'])
  	{
    	$username="<a href='../member/AddMember.php?enews=EditMember&userid=".$r['userid']."' target=_blank>".$r['username']."</a>";
  	}
  ?>
    <tr bgcolor="#FFFFFF"> 
      <td height="25"><div align="center"> 
          <?=$r[id]?>
        </div></td>
      <td height="25"><div align="left"><a href=#ecms onclick="window.open('ShowFeedback.php?id=<?=$r[id]?>','','width=650,height=600,scrollbars=yes,top=70,left=100');"> 
          <?=$r[title]?>
          </a>&nbsp;(<?=$username?>)</div></td>
      <td height="25"><div align="center"><a href="feedback.php?bid=<?=$r[bid]?>"> 
          <?=$br[bname]?>
          </a></div></td>
      <td height="25"><div align="center"> 
          <?=$r[saytime]?>
        </div></td>
      <td height="25"><div align="center">[<a href="feedback.php?enews=DelFeedback&id=<?=$r[id]?>&bid=<?=$r[bid]?>" onclick="return confirm('确认要删除?');">删除</a>
          <input name="id[]" type="checkbox" id="id[]" value="<?=$r[id]?>">
          ]</div></td>
    </tr>
    <?
  }
  ?>
    <tr bgcolor="#FFFFFF"> 
      <td height="25" colspan="5">&nbsp; 
        <?=$returnpage?>
        &nbsp;&nbsp;&nbsp;
        <input type="submit" name="Submit" value="批量删除">
        <input name="bid" type="hidden" id="bid" value="<?=$bid?>">
        <input name="enews" type="hidden" id="enews" value="DelFeedback_all"></td>
    </tr>
  </table>
</form>
</body>
</html>
<?
db_close();
$empire=null;
?>
