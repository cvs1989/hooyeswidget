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
//验证权限
CheckLevel($logininid,$loginin,$classid,"changedata");
//栏目
$fcfile="../../data/fc/ListEnews.php";
$class="<script src=../../data/fc/cmsclass.js></script>";
if(!file_exists($fcfile))
{$class=ShowClass_AddClass("",0,0,"|-",0,0);}
//刷新表
$retable="";
$selecttable="";
$i=0;
$tsql=$empire->query("select tid,tbname,tname from {$dbtbpre}enewstable order by tid");
while($tr=$empire->fetch($tsql))
{
	$i++;
	if($i%4==0)
	{
		$br="<br>";
	}
	else
	{
		$br="";
	}
	$retable.="<input type=checkbox name=tbname[] value='$tr[tbname]' checked>$tr[tname]&nbsp;&nbsp;".$br;
	$selecttable.="<option value='".$tr[tbname]."'>".$tr[tname]."</option>";
}
//专题
$ztclass="";
$ztsql=$empire->query("select ztid,ztname from {$dbtbpre}enewszt order by ztid desc");
while($ztr=$empire->fetch($ztsql))
{
	$ztclass.="<option value='".$ztr['ztid']."'>".$ztr['ztname']."</option>";
}
//选择日期
$todaydate=date("Y-m-d");
$todaytime=time();
$changeday="<select name=selectday onchange=\"document.reform.startday.value=this.value;document.reform.endday.value='".$todaydate."'\">
<option value='".$todaydate."'>--选择--</option>
<option value='".$todaydate."'>今天</option>
<option value='".ToChangeTime($todaytime,7)."'>一周</option>
<option value='".ToChangeTime($todaytime,30)."'>一月</option>
<option value='".ToChangeTime($todaytime,90)."'>三月</option>
<option value='".ToChangeTime($todaytime,180)."'>半年</option>
<option value='".ToChangeTime($todaytime,365)."'>一年</option>
</select>";
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>更新数据</title>
<link href="../adminstyle/<?=$loginadminstyleid?>/adminstyle.css" rel="stylesheet" type="text/css">
<script src="../ecmseditor/fieldfile/setday.js"></script>
</head>

<body>
<table width="100%" border="0" align="center" cellpadding="3" cellspacing="1">
  <tr> 
    <td width="34%" height="25">位置：<a href="ChangeData.php">数据更新</a></td>
    <td width="66%"><table width="420" border="0" align="right" cellpadding="0" cellspacing="0">
        <tr> 
          <td> <div align="center">[<a href="#ReAllHtml">总体刷新</a>]</div></td>
          <td> <div align="center">[<a href="#ReMoreListHtml">多栏目刷新</a>]</div></td>
          <td> <div align="center">[<a href="#ReIfInfoHtml">按条件刷新内容页</a>]</div></td>
          <td> <div align="center">[<a href="#IfOtherInfo">批量更新相关链接</a>]</div></td>
        </tr>
      </table></td>
  </tr>
</table>
<table width="100%" border="0" align="center" cellpadding="3" cellspacing="6">
  <tr id=ReAllHtml> 
    <td width="69%" valign="top"> <table width="100%" border="0" align="center" cellpadding="3" cellspacing="1" class="tableborder">
        <tr class="header"> 
          <td height="25" colspan="2"> <div align="center">页面刷新管理</div></td>
        </tr>
        <tr> 
          <td width="50%" height="25" bgcolor="#FFFFFF"> <table width="100%" border="0" align="center" cellpadding="3" cellspacing="1">
              <tr> 
                <td height="48"> 
                  <div align="center"> 
                    <input type="button" name="Submit2" value="刷新首页" onclick="self.location.href='../ecmschtml.php?enews=ReIndex'">
                  </div></td>
              </tr>
              <tr> 
                <td height="48"> 
                  <div align="center"> 
                    <input type="button" name="Submit22" value="刷新所有信息栏目页" onclick="window.open('../ecmschtml.php?enews=ReListHtml_all&from=ReHtml/ChangeData.php','','');">
                    <br>
                    <font color="#666666">(包括栏目，专题列表)</font></div></td>
              </tr>
              <tr> 
                <td height="48"> 
                  <div align="center"> 
                    <table width="100%" border="0" cellspacing="1" cellpadding="0">
                      <form action="ecmschtml.php" method="post" name="dorehtml" id="dorehtml">
                        <tr> 
                          <td><div align="center"> 
                              <input type="button" name="Submit3" value="刷新所有信息内容页面" onclick="var toredohtml=0;if(document.dorehtml.havehtml.checked==true){toredohtml=1;}window.open('DoRehtml.php?enews=ReNewsHtml&start=0&havehtml='+toredohtml+'&from=ReHtml/ChangeData.php','','');">
                            </div></td>
                        </tr>
                        <tr> 
                          <td height="25" valign="top"> 
                            <div align="center">全部刷新 
                              <input name="havehtml" type="checkbox" id="havehtml" value="1">
                            </div></td>
                        </tr>
                      </form>
                    </table>
                    
                  </div></td>
              </tr>
              <tr> 
                <td height="48"> 
                  <div align="center"> 
                    <input type="button" name="Submit4" value="刷新所有信息JS调用" onclick="window.open('../ecmschtml.php?enews=ReAllNewsJs&from=ReHtml/ChangeData.php','','');">
                  </div></td>
              </tr>
            </table></td>
          <td width="50%" valign="top" bgcolor="#FFFFFF"> <table width="100%" border="0" cellpadding="3" cellspacing="1">
              <tr> 
                <td height="37"> <div align="center"> 
                    <input type="button" name="Submit422" value="批量刷新投票JS" onclick="window.open('../tool/ListVote.php?enews=ReVoteJs_all&from=../ReHtml/ChangeData.php','','');">
                  </div></td>
              </tr>
              <tr> 
                <td height="37"> <div align="center"> 
                    <input type="button" name="Submit4222" value="批量刷新广告JS" onclick="window.open('../tool/ListAd.php?enews=ReAdJs_all&from=../ReHtml/ChangeData.php','','');">
                  </div></td>
              </tr>
              <tr> 
                <td height="37"> <div align="center"> 
                    <input type="button" name="Submit422232" value="批量更新动态页面" onclick="self.location.href='../ecmschtml.php?enews=ReDtPage';">
                  </div></td>
              </tr>
              <tr> 
                <td height="37"><div align="center"> 
                    <input type="button" name="Submit4222322" value="批量更新反馈表单" onclick="self.location.href='../tool/FeedbackClass.php?enews=ReMoreFeedbackClassFile';">
                  </div></td>
              </tr>
              <tr>
                <td height="37"><div align="center">
                    <table width="100%" border="0" cellspacing="1" cellpadding="0">
                      <form action="../ecmsmod.php" method="GET" name="dochangemodform" id="dochangemodform">
					  <input type=hidden name=enews value="ChangeAllModForm">
                        <tr> 
                          <td><div align="center"> 
                              <input type="submit" name="Submit3" value="批量更新模型表单">
                            </div></td>
                        </tr>
                        <tr> 
                          <td height="25"> <div align="center">更新栏目导航<input name="ChangeClass" type="checkbox" id="ChangeClass" value="1">
                            </div></td>
                        </tr>
                      </form>
                    </table>
                  </div></td>
              </tr>
            </table></td>
        </tr>
      </table></td>
    <td width="31%" valign="top"> <table width="100%" border="0" align="center" cellpadding="3" cellspacing="1" class="tableborder">
        <tr> 
          <td height="25" class="header"> <div align="center">更新缓存数据</div></td>
        </tr>
        <tr> 
          <td height="25" bgcolor="#FFFFFF" onMouseOver="this.style.backgroundColor='#EFEFEF'" onMouseOut="this.style.backgroundColor='#FFFFFF'"> 
            <div align="center"><a href="../enews.php?enews=ChangeEnewsData">更新数据库缓存</a></div></td>
        </tr>
        <tr> 
          <td height="25" bgcolor="#FFFFFF" onMouseOver="this.style.backgroundColor='#EFEFEF'" onMouseOut="this.style.backgroundColor='#FFFFFF'"><div align="center"><a href="../ecmschtml.php?enews=ReClassPath">恢复栏目目录</a></div></td>
        </tr>
        <tr> 
          <td height="25" bgcolor="#FFFFFF" onMouseOver="this.style.backgroundColor='#EFEFEF'" onMouseOut="this.style.backgroundColor='#FFFFFF'"><div align="center"><a href="../ecmsclass.php?enews=DelFcListClass">删除栏目缓存文件</a></div></td>
        </tr>
        <tr> 
          <td height="25" bgcolor="#FFFFFF" onMouseOver="this.style.backgroundColor='#EFEFEF'" onMouseOut="this.style.backgroundColor='#FFFFFF'"><div align="center"><a href="../ecmsclass.php?enews=ChangeSonclass">更新栏目关系</a></div></td>
        </tr>
        <tr>
          <td height="25" bgcolor="#FFFFFF" onMouseOver="this.style.backgroundColor='#EFEFEF'" onMouseOut="this.style.backgroundColor='#FFFFFF'"><div align="center"><a href="../ecmscom.php?enews=ClearTmpFileData" onclick="return confirm('清除前请确认用户没有正在采集、批量刷新页面与远程发布，确认?');">清除临时文件和数据</a></div></td>
        </tr>
      </table>
      <br>
      <table width="100%" border="0" align="center" cellpadding="3" cellspacing="1" class="tableborder">
        <tr> 
          <td height="25" class="header"> <div align="center">自定义页面刷新</div></td>
        </tr>
        <tr> 
          <td height="25" bgcolor="#FFFFFF" onMouseOver="this.style.backgroundColor='#EFEFEF'" onMouseOut="this.style.backgroundColor='#FFFFFF'"> <div align="center"><a href="../ecmschtml.php?enews=ReUserpageAll&from=ReHtml/ChangeData.php" target="_blank">刷新所有自定义页面</a></div></td>
        </tr>
        <tr> 
          <td height="25" bgcolor="#FFFFFF" onMouseOver="this.style.backgroundColor='#EFEFEF'" onMouseOut="this.style.backgroundColor='#FFFFFF'"><div align="center"><a href="../ecmschtml.php?enews=ReUserlistAll&from=ReHtml/ChangeData.php" target="_blank">批量刷新自定义列表</a></div></td>
        </tr>
        <tr> 
          <td height="25" bgcolor="#FFFFFF" onMouseOver="this.style.backgroundColor='#EFEFEF'" onMouseOut="this.style.backgroundColor='#FFFFFF'"><div align="center"><a href="../ecmschtml.php?enews=ReUserjsAll&from=ReHtml/ChangeData.php" target="_blank">批量刷新自定义JS</a></div></td>
        </tr>
      </table> </td>
  </tr>
  <tr id=ReMoreListHtml> 
    <td width="69%" valign="top"> <table width="100%" border="0" align="center" cellpadding="3" cellspacing="1" class="tableborder">
        <form name="form2" method="post" action="../ecmschtml.php">
          <tr class="header"> 
            <td height="25"> <div align="center"><strong>刷新多栏目页面 </strong></div></td>
          </tr>
          <tr> 
            <td height="25" bgcolor="#FFFFFF"> <div align="center"> 
                <table width="100%" border="0" cellspacing="1" cellpadding="3">
                  <tr> 
                    <td><div align="center"> 
                        <select name="classid[]" size="12" multiple id="classid[]" style="width:310">
                          <?=$class?>
                        </select>
                      </div></td>
                  </tr>
                  <tr> 
                    <td><div align="center"> 
                        <input type="submit" name="Submit8" value="开始刷新">
                        <strong> 
                        <input name="enews" type="hidden" id="enews3" value="GoReListHtmlMore">
                        <input name="gore" type="hidden" id="enews4" value="0">
                        <input name="from" type="hidden" id="gore" value="ReHtml/ChangeData.php">
                        </strong></div></td>
                  </tr>
                  <tr> 
                    <td><div align="center">多个用ctrl/shift选择</div></td>
                  </tr>
                </table>
              </div></td>
          </tr>
        </form>
      </table></td>
    <td><table width="100%" border="0" align="center" cellpadding="3" cellspacing="1" class="tableborder">
        <form name="form2" method="post" action="../ecmschtml.php">
          <tr class="header"> 
            <td height="25"> <div align="center"><strong>刷新多专题页面 </strong></div></td>
          </tr>
          <tr> 
            <td height="25" bgcolor="#FFFFFF"> <div align="center"> 
                <table width="100%" border="0" cellspacing="1" cellpadding="3">
                  <tr> 
                    <td><div align="center"> 
                        <select name="classid[]" size="12" multiple id="select2" style="width:250">
                          <?=$ztclass?>
                        </select>
                      </div></td>
                  </tr>
                  <tr> 
                    <td><div align="center"> 
                        <input type="submit" name="Submit82" value="开始刷新">
                        <strong> 
                        <input name="enews" type="hidden" id="enews5" value="GoReListHtmlMore">
                        <input name="gore" type="hidden" id="gore" value="1">
                        <input name="from" type="hidden" id="from" value="ReHtml/ChangeData.php">
                        </strong></div></td>
                  </tr>
                  <tr> 
                    <td><div align="center">多个用ctrl/shift选择</div></td>
                  </tr>
                </table>
              </div></td>
          </tr>
        </form>
      </table></td>
  </tr>
</table>
<form action="DoRehtml.php" method="get" name="reform" target="_blank" onsubmit="return confirm('确认要刷新?');">
  <table width="100%" border="0" align="center" cellpadding="3" cellspacing="1" class="tableborder" id=ReIfInfoHtml>
    <input name="from" type="hidden" id="from" value="ReHtml/ChangeData.php">
    <tr class="header"> 
      <td height="25"> <div align="center">按条件刷新信息内容页面</div></td>
    </tr>
    <tr> 
      <td height="25" bgcolor="#FFFFFF"> <div align="center"> 
          <table width="100%" border="0" align="center" cellpadding="3" cellspacing="1">
            <tr> 
              <td height="25">刷新数据表：</td>
              <td height="25">
                <?=$retable?>
              </td>
            </tr>
            <tr> 
              <td height="25">刷新栏目</td>
              <td height="25"><select name="classid" id="classid">
                  <option value="0">所有栏目</option>
                  <?=$class?>
                </select>
                <font color="#666666"> (如选择父栏目，将刷新所有子栏目)</font></td>
            </tr>
            <tr> 
              <td width="23%" height="25"> <input name="retype" type="radio" value="0" checked>
                按时间刷新：</td>
              <td width="77%" height="25">从 
                <input name="startday" type="text" size="12" onclick="setday(this)">
                到 
                <input name="endday" type="text" size="12" onclick="setday(this)">
                之间的数据 
                <?=$changeday?>
                <font color="#666666"> (不填将刷新所有页面)</font></td>
            </tr>
            <tr> 
              <td height="25"> <input name="retype" type="radio" value="1">
                按ID刷新：</td>
              <td height="25">从 
                <input name="startid" type="text" id="startid" value="0" size="6">
                到 
                <input name="endid" type="text" id="endid" value="0" size="6">
                之间的数据 <font color="#666666">(两个值为0将刷新所有页面)</font></td>
            </tr>
            <tr>
              <td height="25">全部刷新：</td>
              <td height="25"><input name="havehtml" type="checkbox" id="havehtml" value="1">
                是<font color="#666666"> (不选择将不刷新已生成过的信息)</font></td>
            </tr>
            <tr> 
              <td height="25">&nbsp;</td>
              <td height="25"><input type="submit" name="Submit6" value="开始刷新"> 
                <input type="reset" name="Submit7" value="重置"> <input name="enews" type="hidden" id="enews" value="ReNewsHtml"> 
              </td>
            </tr>
          </table>
        </div></td>
    </tr>
  </table>
</form>
<form action="../ecmscom.php" method="get" name="form1" target="_blank" onsubmit="return confirm('确认要更新?');">
  <table width="100%" border="0" align="center" cellpadding="3" cellspacing="1" class="tableborder" id=IfOtherInfo>
    <input name="from" type="hidden" id="from" value="ReHtml/ChangeData.php">
    <tr class="header"> 
      <td height="25"> <div align="center">批量更新相关链接</div></td>
    </tr>
    <tr> 
      <td height="25" bgcolor="#FFFFFF"> <div align="center"> 
          <table width="100%" border="0" align="center" cellpadding="3" cellspacing="1">
            <tr> 
              <td height="25">数据表：</td>
              <td height="25"> <select name="tbname" id="tbname">
                  <option value=''>------ 选择数据表 ------</option>
                  <?=$selecttable?>
                </select>
                (*) </td>
            </tr>
            <tr> 
              <td height="25">栏目</td>
              <td height="25"><select name="classid">
                  <option value="0">所有栏目</option>
                  <?=$class?>
                </select>
                <font color="#666666">(如选择父栏目，将更新所有子栏目)</font></td>
            </tr>
            <tr> 
              <td width="23%" height="25"> <input name="retype" type="radio" value="0" checked>
                按时间更新：</td>
              <td width="77%" height="25">从 
                <input name="startday" type="text" size="12" onclick="setday(this)">
                到 
                <input name="endday" type="text" size="12" onclick="setday(this)">
                之间的信息 <font color="#666666">(不填将更新所有信息)</font></td>
            </tr>
            <tr> 
              <td height="25"> <input name="retype" type="radio" value="1">
                按ID更新：</td>
              <td height="25">从 
                <input name="startid" type="text" value="0" size="6">
                到 
                <input name="endid" type="text" value="0" size="6">
                之间的信息 <font color="#666666">(两个值为0将更新所有信息)</font></td>
            </tr>
            <tr> 
              <td height="25">&nbsp;</td>
              <td height="25"><input type="submit" name="Submit62" value="开始更新"> 
                <input type="reset" name="Submit72" value="重置"> <input name="enews" type="hidden" value="ChangeInfoOtherLink"> 
              </td>
            </tr>
            <tr> 
              <td height="25" colspan="2"><font color="#666666">友情提醒：此功能比较耗资源，非必要时请勿用。</font></td>
            </tr>
          </table>
        </div></td>
    </tr>
  </table>
</form>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p><br>
</p>
</body>
</html>
<?
db_close();
$empire=null;
?>
