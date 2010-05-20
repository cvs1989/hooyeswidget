<?php
require("../class/connect.php");
require("../class/db_sql.php");
require("../class/functions.php");
require LoadLang("pub/fun.php");
require("../data/dbcache/class.php");
$link=db_connect();
$empire=new mysqlquery();
//验证用户
$lur=is_login();
$logininid=$lur['userid'];
$loginin=$lur['username'];
$loginrnd=$lur['rnd'];
$loginlevel=$lur['groupid'];
$loginadminstyleid=$lur['adminstyleid'];

$page=(int)$_GET['page'];
$start=0;
$line=intval($public_r['hlistinfonum']);//每页显示
$page_line=12;//每页显示链接数
$offset=$page*$line;//总偏移量
$like=",".$loginin.",";
$totalquery="select count(*) as total from {$dbtbpre}enewsqf where checkuser like '%$like%' and !(docheckuser like '%$like%' or notdocheckuser like '%$like%')";
$num=$empire->gettotal($totalquery);
$query="select id,classid from {$dbtbpre}enewsqf where checkuser like '%$like%' and !(docheckuser like '%$like%' or notdocheckuser like '%$like%')";
//$num=$empire->num($query);//取得总条数
$query=$query." order by id desc limit $offset,$line";
$sql=$empire->query($query);
$returnpage=page2($num,$line,$page_line,$start,$page,$search);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link href="adminstyle/<?=$loginadminstyleid?>/adminstyle.css" rel="stylesheet" type="text/css">
<title>管理签发信息</title>
</head>

<body>
<table width="100%" border="0" align="center" cellpadding="3" cellspacing="1">
  <tr>
    <td height="25">位置：<a href="ListAllInfo.php">管理信息</a> &gt; <a href="ListNewsQf.php">管理签发信息</a></td>
  </tr>
</table>

<form name="form1" method="post" action="">
  <table width="100%" border="0" align="center" cellpadding="3" cellspacing="1" class="tableborder">
    <tr class="header"> 
      <td width="5%"><div align="center"></div></td>
      <td width="8%" height="25"> <div align="center">ID</div></td>
      <td width="42%" height="25"> <div align="center">标题</div></td>
      <td width="22%"><div align="center">提交时间</div></td>
      <td width="23%" height="25"> <div align="center">操作</div></td>
    </tr>
  <?php
  while($r=$empire->fetch($sql))
  {
	if($class_r[$r[classid]][tbname])
	{
		$nr=$empire->fetch1("select id,title,newstime from {$dbtbpre}ecms_".$class_r[$r[classid]][tbname]." where id='$r[id]' and classid='$r[classid]' limit 1");
	}
	$do=$r[classid];
	$dob=$class_r[$r[classid]][bclassid];
  ?>
    <tr bgcolor="#FFFFFF" onmouseout="this.style.backgroundColor='#ffffff'" onmouseover="this.style.backgroundColor='#DBEAF5'"> 
      <td><div align="center"> 
          <input name="id" type="checkbox" id="id" value="<?=$r[id]?>">
        </div></td>
      <td height="25"> <div align="center"> 
          <?=$r[id]?>
        </div></td>
      <td height="25"> 
        <?=$nr[title]?><br>
	    栏目:<a href='ListNews.php?bclassid=<?=$class_r[$r[classid]][bclassid]?>&classid=<?=$r[classid]?>'> 
          <?=$class_r[$dob][classname]?>
          </a> > <a href='ListNews.php?bclassid=<?=$class_r[$r[classid]][bclassid]?>&classid=<?=$r[classid]?>'> 
          <?=$class_r[$r[classid]][classname]?>
          </a>
      </td>
      <td><div align="center"><?=date("Y-m-d H:i:s",$nr[newstime])?></div></td>
      <td height="25"> <div align="center">[<a href="ecmsinfo.php?enews=ViewQfNews&classid=<?=$r[classid]?>&id=<?=$r[id]?>" target=_blank>查看内容</a>]&nbsp;&nbsp;&nbsp;[<a href="#ecms" onclick="window.open('DoNewsQf.php?classid=<?=$r[classid]?>&id=<?=$r[id]?>','','width=600,height=520,scrollbars=yes');">签发/退稿</a>]</div></td>
    </tr>
    <?
  }
  ?>
    <tr bgcolor="#FFFFFF"> 
      <td height="25"><div align="center"></div></td>
      <td height="25" colspan="4"> &nbsp;&nbsp; 
        <?=$returnpage?>
      </td>
    </tr>
  </table>
</form>
</body>
</html>
<?
db_close();
$empire=null;
?>
