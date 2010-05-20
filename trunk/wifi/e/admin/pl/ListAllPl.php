<?php
require("../../class/connect.php");
require("../../class/db_sql.php");
require("../../class/functions.php");
require "../".LoadLang("pub/fun.php");
require("../../data/dbcache/class.php");
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
CheckLevel($logininid,$loginin,$classid,"pl");
$start=0;
$page=(int)$_GET['page'];
$line=15;//每行显示
$page_line=15;
$offset=$page*$line;
$search='';
$add='';
$and='';
//单个
$id=(int)$_GET['id'];
if($id)
{
	$add.=" where id='$id'";
	$search.="&id=$id";
}
//单个
$classid=(int)$_GET['classid'];
if($classid)
{
	$and=empty($add)?' where ':' and ';
	if($class_r[$classid][islast])
	{
		$add.=$and."classid='$classid'";
	}
	else
	{
		$add.=$and.'('.ReturnClass($class_r[$classid][sonclass]).')';
	}
	$search.="&classid=$classid";
}
//审核
$checked=(int)$_GET['checked'];
if($checked)
{
	$and=empty($add)?' where ':' and ';
	$add.=$and."checked='".($checked==1?0:1)."'";
	$search.="&checked=$checked";
}
//搜索
$keyboard=RepPostVar2($_GET['keyboard']);
if($keyboard)
{
	$and=empty($add)?' where ':' and ';
	$show=(int)$_GET['show'];
	if($show==1)//发表者
	{
		$add.=$and."(username like '%".$keyboard."%')";
	}
	elseif($show==2)//ip
	{
		$add.=$and."(sayip like '%".$keyboard."%')";
	}
	$search.="&keyboard=$keyboard&show=$show";
}
$totalquery="select count(*) as total from {$dbtbpre}enewspl".$add;
$query="select plid,username,saytime,sayip,id,classid,checked,zcnum,fdnum,userid,isgood,stb from {$dbtbpre}enewspl".$add;
//取得总条数
$totalnum=(int)$_GET['totalnum'];
if($totalnum)
{
	$num=$totalnum;
}
else
{
	$num=$empire->gettotal($totalquery);
}
$query.=" order by plid desc limit $offset,$line";
$sql=$empire->query($query);
$search.='&totalnum='.$num;
$returnpage=page2($num,$line,$page_line,$start,$page,$search);
//位置
$url="<a href=ListAllPl.php>管理评论</a>";
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>管理评论</title>
<link href="../adminstyle/<?=$loginadminstyleid?>/adminstyle.css" rel="stylesheet" type="text/css">
<script>
function CheckAll(form)
  {
  for (var i=0;i<form.elements.length;i++)
    {
    var e = form.elements[i];
    if (e.name != 'chkall')
       e.checked = form.chkall.checked;
    }
  }
</script>
</head>

<body>
<table width="100%" border="0" align="center" cellpadding="3" cellspacing="1">
  <tr> 
    <td width="50%">位置： 
      <?=$url?>
    </td>
    <td><div align="right">
        <input type="button" name="Submit5" value="管理评论表情" onclick="self.location.href='plface.php';">&nbsp;&nbsp;&nbsp;
		<input type="button" name="Submit5" value="设置评论过滤字符" onclick="self.location.href='../SetEnews.php';">&nbsp;&nbsp;&nbsp;
		<input type="button" name="Submit5" value="自定义评论字段" onclick="self.location.href='ListPlF.php';">&nbsp;&nbsp;&nbsp;
		<input type="button" name="Submit5" value="管理评论分表" onclick="self.location.href='ListPlDataTable.php';">
      </div></td>
  </tr>
</table>

  <table width="100%" border="0" align="center" cellpadding="3" cellspacing="1">
  <form name="form2" method="get" action="ListAllPl.php">
    <tr> 
      <td>信息ID： 
        <input name="id" type="text" id="id" value="<?=$id?>" size="6">
        关键字： 
        <input name="keyboard" type="text" id="keyboard" value="<?=$keyboard?>"> 
        <select name="show" id="show">
          <option value="1"<?=$show==1?' selected':''?>>发表者</option>
          <option value="2"<?=$show==2?' selected':''?>>IP地址</option>
        </select>
        <select name="checked" id="checked">
          <option value="0"<?=$checked==0?' selected':''?>>不限</option>
          <option value="1"<?=$checked==1?' selected':''?>>已审核</option>
          <option value="2"<?=$checked==2?' selected':''?>>未审核</option>
        </select> 
		<span id="listplclassnav"></span>&nbsp;
        <input type="submit" name="Submit2" value="搜索评论">
      </td>
    </tr>
    <tr>
      <td> </td>
    </tr>
	</form>
  </table>
<form name="form1" method="post" action="../ecmspl.php" onsubmit="return confirm('确认要操作?');">
<input type=hidden name=classid value=<?=$classid?>>
<input type=hidden name=id value=<?=$id?>>
  <input name="isgood" type="hidden" id="isgood" value="1">
  <table width="100%" border="0" align="center" cellpadding="3" cellspacing="1" class="tableborder" style="WORD-BREAK: break-all; WORD-WRAP: break-word">
    <tr class="header"> 
      <td width="4%" height="25"><div align="center">选择</div></td>
      <td width="21%" height="25"><div align="center">网名</div></td>
      <td width="51%" height="25"><div align="center">评论内容(双击内容，进入信息评论页)</div></td>
      <td width="24%" height="25"><div align="center">所属信息</div></td>
    </tr>
    <?php
	while($r=$empire->fetch($sql))
	{
		//副表
		$fr=$empire->fetch1("select saytext from {$dbtbpre}enewspl_data_".$r[stb]." where plid='$r[plid]'");
		if(!empty($r[checked]))
		{$checked=" title='未审核' style='background:#99C4E3'";}
		else
		{$checked="";}
		if($r['userid'])
		{
			$r['username']="<a href='../member/AddMember.php?enews=EditMember&userid=$r[userid]' target='_blank'><b>$r[username]</b></a>";
		}
		if(empty($r['username']))
		{
			$r['username']='匿名';
		}
		if($r[isgood])
		{
			$r[saytime]='<font color=red>'.$r[saytime].'</font>';
		}
		//替换表情
		$saytext=RepPltextFace(stripSlashes($fr['saytext']));
		//信息
		$title='';
		if($class_r[$r[classid]][tbname])
		{
			$infor=$empire->fetch1("select titleurl,groupid,classid,newspath,filename,id,title from {$dbtbpre}ecms_".$class_r[$r[classid]][tbname]." where id='$r[id]' limit 1");
			$titleurl=sys_ReturnBqTitleLink($infor);
			$title="<a href='$titleurl' target='_blank'>$infor[title]</a>";
		}
	?>
    <tr bgcolor="#FFFFFF" onmouseout="this.style.backgroundColor='#ffffff'" onmouseover="this.style.backgroundColor='#DBEAF5'" id=pl<?=$r[plid]?>> 
      <td height="25" valign="top"> <div align="center"> 
          <input name="plid[]" type="checkbox" id="plid" value="<?=$r[plid]?>"<?=$checked?>>
        </div></td>
      <td height="25" valign="top"><div align="center"> 
          <table width="100%" border="0" cellspacing="1" cellpadding="3">
            <tr> 
              <td width="20%">网名</td>
              <td width="80%"> 
                <?=$r[username]?>
              </td>
            </tr>
            <tr> 
              <td>IP</td>
              <td> 
                <?=$r[sayip]?>
              </td>
            </tr>
            <tr> 
              <td>时间</td>
              <td> 
                <?=$r[saytime]?>
              </td>
            </tr>
          </table>
        </div></td>
      <td height="25" valign="top" ondblclick="window.open('../../pl?classid=<?=$r[classid]?>&id=<?=$r[id]?>');"> 
        <?=$saytext?>
      </td>
      <td height="25"><div align="center"> 
          <?=$title?>
        </div></td>
    </tr>
    <?php
	}
	db_close();
	$empire=null;
	?>
    <tr bgcolor="#FFFFFF"> 
      <td height="25"> <div align="center">
          <input type=checkbox name=chkall value=on onclick=CheckAll(this.form)>
        </div></td>
      <td height="25" colspan="3">
        <?=$returnpage?>
        &nbsp;&nbsp; <input type="submit" name="Submit" value="审核评论" onClick="document.form1.enews.value='CheckPl_all';"> 
        &nbsp;&nbsp;&nbsp; <input type="submit" name="Submit3" value="推荐评论" onClick="document.form1.enews.value='DoGoodPl_all';document.form1.isgood.value='1';"> 
        &nbsp;&nbsp;&nbsp; <input type="submit" name="Submit4" value="取消推荐评论" onClick="document.form1.enews.value='DoGoodPl_all';document.form1.isgood.value='0';"> 
        &nbsp;&nbsp;&nbsp; <input type="submit" name="Submit" value="删除" onClick="document.form1.enews.value='DelPl_all';"> 
        <input name="enews" type="hidden" id="enews" value="DelPl_all"></td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td height="25" colspan="4"><font color="#FF0000">备注：多选框为蓝色代表未审核评论，加粗网名为登陆会员，发布时间红色为推荐评论</font></td>
    </tr>
  </table>
</form>
<IFRAME frameBorder="0" id="showclassnav" name="showclassnav" scrolling="no" src="../ShowClassNav.php?ecms=6&classid=<?=$classid?>" style="HEIGHT:0;VISIBILITY:inherit;WIDTH:0;Z-INDEX:1"></IFRAME>
</body>
</html>