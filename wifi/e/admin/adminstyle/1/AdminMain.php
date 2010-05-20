<?php
if(!defined('InEmpireCMS'))
{
	exit();
}
$r=ReturnLeftLevel($loginlevel);
?>
<HTML>
<HEAD>
<META http-equiv=Content-Type content="text/html; charset=utf-8">
<TITLE>帝国网站管理系统 V6.0 --- Powered by EmpireCMS</TITLE>
<LINK href="adminstyle/1/adminmain.css" rel=stylesheet>
<STYLE>
.flyoutLink A {
	COLOR: black; TEXT-DECORATION: none
}
.flyoutLink A:hover {
	COLOR: black; TEXT-DECORATION: none
}
.flyoutLink A:visited {
	COLOR: black; TEXT-DECORATION: none
}
.flyoutLink A:active {
	COLOR: black; TEXT-DECORATION: none
}
.flyoutMenu {
	BACKGROUND-COLOR: #CDE2F8
}
.flyoutMenu TD.flyoutLink {
	BORDER-RIGHT: #CDE2F8 1px solid; BORDER-TOP: #CDE2F8 1px solid; BORDER-LEFT: #CDE2F8 1px solid; CURSOR: hand; PADDING-TOP: 1px; BORDER-BOTTOM: #CDE2F8 1px solid
}
.flyoutMenu1 {
	BACKGROUND-COLOR: #fbf9f9
}
.flyoutMenu1 TD.flyoutLink1 {
	BORDER-RIGHT: #fbf9f9 1px solid; BORDER-TOP: #fbf9f9 1px solid; BORDER-LEFT: #fbf9f9 1px solid; CURSOR: hand; PADDING-TOP: 1px; BORDER-BOTTOM: #fbf9f9 1px solid
}
</STYLE>
<SCRIPT>
function switchSysBar(){
	if(switchPoint.innerText==3)
	{
		switchPoint.innerText=4
		document.all("frmTitle").style.display="none"
	}
	else
	{
		switchPoint.innerText=3
		document.all("frmTitle").style.display=""
	}
}
function switchSysBarInfo(){
	switchPoint.innerText=3
	document.all("frmTitle").style.display=""
}

function about(){
	window.showModalDialog("adminstyle/1/page/about.htm","ABOUT","dialogwidth:300px;dialogheight:150px;center:yes;status:no;scroll:no;help:no");
}

function over(obj){
		if(obj.className=="flyoutLink")
		{
			obj.style.backgroundColor='#B5C4EC'
			obj.style.borderColor = '#380FA6'
		}
		else if(obj.className=="flyoutLink1")
		{
		    obj.style.backgroundColor='#B5C4EC'
			obj.style.borderColor = '#380FA6'				
		}
}
function out(obj){
		if(obj.className=="flyoutLink")
		{
			obj.style.backgroundColor='#CDE2F8'
			obj.style.borderColor = 'CDE2F8'
		}
		else if(obj.className=="#flyoutLink1")
		{
		    obj.style.backgroundColor='#FBF9F9'
			obj.style.borderColor = '#FBF9F9'				
		}
}

function show(d){
	if(obj=document.all(d))	obj.style.visibility="visible";

}
function hide(d){
	if(obj=document.all(d))	obj.style.visibility="hidden";
}

function JumpToLeftMenu(url){
	document.getElementById("left").src=url;
}
function JumpToMain(url){
	document.getElementById("main").src=url;
}
</SCRIPT>
</HEAD>
<BODY bgColor="#CDE2F8" leftMargin=0 topMargin=0>
<TABLE width="100%" height="100%" border=0 cellpadding="0" cellSpacing=0>
<tr>
<td height="60">

  <TABLE width="100%" height="60" border=0 cellpadding="0" cellSpacing=0 background="adminstyle/1/images/topbg.gif">
    <TBODY>
      <TR> 
        <TD width="1%"></TD>
		<TD width="99%" height=60> 
			<TABLE width=780 border=0 cellpadding="0" cellSpacing=0>
                <TBODY>
                  <TR align=middle> 
                    <TD width=80 onmouseover="this.style.backgroundColor='#8CBDEF'" onmouseout="this.style.backgroundColor=''" onclick="JumpToLeftMenu('adminstyle/1/left.php?ecms=system');" style="CURSOR: hand"> 
                      <table width="100%" border="0" cellspacing="0" cellpadding="0">
                        <tr> 
                          <td><div align="center"><IMG height=32 src="adminstyle/1/images/system.gif" width=32 border=0 title="系统设置"></div></td>
                        </tr>
                        <tr> 
                          <td height="23"><div align="center"><font color="#FFFFFF">系统设置</font></div></td>
                        </tr>
                      </table></TD>
                    <TD width=80 onmouseout="this.style.backgroundColor=''" onmouseover="this.style.backgroundColor='#8CBDEF'" onclick="switchSysBarInfo();JumpToLeftMenu('ListEnews.php');" style="CURSOR: hand"> 
                      <table width="100%" border="0" cellspacing="0" cellpadding="0">
                        <tr> 
                          <td><div align="center"><IMG height=32 src="adminstyle/1/images/info.gif" width=32 border=0 title="信息管理"></div></td>
                        </tr>
                        <tr> 
                          <td height="23"><div align="center"><font color="#FFFFFF">信息管理</font></div></td>
                        </tr>
                      </table></TD>
                    <TD width=80 onmouseout="this.style.backgroundColor=''" onmouseover="this.style.backgroundColor='#8CBDEF'" onclick="JumpToLeftMenu('adminstyle/1/left.php?ecms=classdata');" style="CURSOR: hand"> 
                      <table width="100%" border="0" cellspacing="0" cellpadding="0">
                        <tr> 
                          <td><div align="center"><img height=32 src="adminstyle/1/images/class.gif" width=32 border=0 title="栏目管理"></div></td>
                        </tr>
                        <tr> 
                          <td height="23"><div align="center"><font color="#FFFFFF">栏目管理</font></div></td>
                        </tr>
                      </table></TD>
                    <TD width=80 onmouseout="this.style.backgroundColor=''" onmouseover="this.style.backgroundColor='#8CBDEF'" onclick="JumpToLeftMenu('adminstyle/1/left.php?ecms=template');" style="CURSOR: hand"> 
                      <table width="100%" border="0" cellspacing="0" cellpadding="0">
                        <tr> 
                          <td><div align="center"><img src="adminstyle/1/images/template.gif" width="32" height="32" border="0" title="模板管理"></div></td>
                        </tr>
                        <tr> 
                          <td height="23"><div align="center"><font color="#FFFFFF">模板管理</font></div></td>
                        </tr>
                      </table></TD>
                    <TD width=80 onmouseout="this.style.backgroundColor=''" onmouseover="this.style.backgroundColor='#8CBDEF'" onclick="JumpToLeftMenu('adminstyle/1/left.php?ecms=usercp');" style="CURSOR: hand"> 
                      <table width="100%" border="0" cellspacing="0" cellpadding="0">
                        <tr> 
                          <td><div align="center"><IMG height=32 src="adminstyle/1/images/usercp.gif" width=32
            border=0 title="用户面板"></div></td>
                        </tr>
                        <tr> 
                          <td height="23"><div align="center"><font color="#FFFFFF">用户面板</font></div></td>
                        </tr>
                      </table></TD>
                    <TD width=80 onmouseout="this.style.backgroundColor=''" onmouseover="this.style.backgroundColor='#8CBDEF'" onclick="JumpToLeftMenu('adminstyle/1/left.php?ecms=tool');" style="CURSOR: hand"> 
                      <table width="100%" border="0" cellspacing="0" cellpadding="0">
                        <tr> 
                          <td><div align="center"><IMG height=32 src="adminstyle/1/images/tool.gif" width=32 border=0 title="插件管理"></div></td>
                        </tr>
                        <tr> 
                          <td height="23"><div align="center"><font color="#FFFFFF">插件管理</font></div></td>
                        </tr>
                      </table></TD>
                    <TD width=80 onmouseout="this.style.backgroundColor=''" onmouseover="this.style.backgroundColor='#8CBDEF'" onclick="JumpToLeftMenu('adminstyle/1/left.php?ecms=other');" style="CURSOR: hand"> 
                      <table width="100%" border="0" cellspacing="0" cellpadding="0">
                        <tr> 
                          <td><div align="center"><IMG height=32 src="adminstyle/1/images/other.gif" width=32 border=0 title="其他管理"></div></td>
                        </tr>
                        <tr> 
                          <td height="23"><div align="center"><font color="#FFFFFF">其他管理</font></div></td>
                        </tr>
                      </table></TD>
                    <TD width="351"><font color="#ffffff">当前用户： <a href="user/EditPassword.php" target="main"><font color="#ffffff"><b><?=$loginin?></b></font></a> &nbsp;&nbsp;&nbsp;&nbsp;( <a href="#ecms" onclick="if(confirm('确认要退出?')){JumpToMain('ecmsadmin.php?enews=exit');}"><font color="#ffffff">退出登陆</font></a> )</font></TD>
                  </TR>
                </TBODY>
              </TABLE>
        
      </TD>
      </TR>
    </TBODY>
  </TABLE>

</td></tr>
<tr><td height="22">

  <TABLE width="100%" height="22" border=0 cellpadding="0" cellSpacing=0>
    <TBODY>
      <TR> 
        <TD class=flyoutMenu width="1%"> </TD>   
		<TD width="99%" height="22"> 
			<TABLE class=flyoutMenu width=685 border=0>
                <TBODY>
                  <TR align=middle> 
                    <TD class="flyoutLink" onmouseover="over(this)" onmouseout="out(this)" 
          onclick="JumpToMain('AddInfoChClass.php');">增加信息</TD>
                    <TD class=flyoutLink 
          onclick="JumpToMain('ListAllInfo.php');" onmouseover="over(this)" onmouseout="out(this)">管理信息</TD>
                    <TD class="flyoutLink" onmouseover="over(this)" onmouseout="out(this)" 
          onclick="JumpToMain('ListAllInfo.php?showspecial=4&sear=1');">审核信息</TD>
                    <TD class="flyoutLink" onmouseover="over(this)" onmouseout="out(this)"
          onclick="JumpToMain('ListNewsQf.php');">签发信息</TD>
                    <TD class="flyoutLink" onmouseover="over(this)" onmouseout="out(this)"
          onclick="JumpToMain('pl/ListAllPl.php');">评论管理</TD>
                    <TD class="flyoutLink" onmouseover="over(this)" onmouseout="out(this)"
          onclick="JumpToMain('ReHtml/ChangeData.php');">数据更新</TD>
                    <TD class="flyoutLink" onmouseover="over(this)" onmouseout="out(this)"
          onclick="JumpToMain('infotop.php');">排行统计</TD>
                    <TD class="flyoutLink" onmouseover="over(this)" onmouseout="out(this)"
          onclick="JumpToMain('main.php');">后台首页</TD>
                    <TD class="flyoutLink" onmouseover="over(this)" onmouseout="out(this)"
          onclick="window.open('../../');">网站首页</TD>
                    <TD class="flyoutLink" onmouseover="over(this)" onmouseout="out(this)"
          onclick="window.open('map.php','','width=1200,height=550,scrollbars=auto,resizable=yes,top=190,left=120');">后台地图</TD>
                    <TD class="flyoutLink" onmouseover="over(this)" onmouseout="out(this)"
          onclick="window.open('http://bbs.phome.net/listthread-23-cms-page-0.html');">在线升级</TD>
                  </TR>
                </TBODY>
              </TABLE>
      </TD>
      </TR>
    </TBODY>
  </TABLE>

</td></tr>
<tr><td height="100%" bgcolor="#ffffff">

  <TABLE width="100%" height="100%" cellpadding="0" cellSpacing=0 border=0 borderColor="#ff0000">
  <TBODY>
    <TR> 
      <TD width="123" valign="top" bgcolor="#CDE2F8">
		<IFRAME frameBorder="0" id="dorepage" name="dorepage" scrolling="no" src="DoTimeRepage.php" style="HEIGHT:0;VISIBILITY:inherit;WIDTH:0;Z-INDEX:1"></IFRAME>
      </TD>
      <TD noWrap id="frmTitle">
		<IFRAME frameBorder="0" id="left" name="left" scrolling="auto" src="ListEnews.php" style="HEIGHT:100%;VISIBILITY:inherit;WIDTH:200px;Z-INDEX:2"></IFRAME>
      </TD>
      <TD>
		<TABLE border=0 cellPadding=0 cellSpacing=0 height="100%" bgcolor="#CDE2F8">
          <TBODY>
            <tr> 
              <TD onclick="switchSysBar()" style="HEIGHT:100%;"> <font style="COLOR:666666;CURSOR:hand;FONT-FAMILY:Webdings;FONT-SIZE:9pt;"> 
                <SPAN id="switchPoint" title="打开/关闭左边导航栏">3</SPAN></font> 
          </TBODY>
        </TABLE>
      </TD>
      <TD width="100%">
		<TABLE height="100%" cellSpacing=0 cellPadding=0 width="100%" align="right" border=0>
          <TBODY>
            <TR> 
              <TD align=right>
				<IFRAME id="main" name="main" style="WIDTH: 100%; HEIGHT: 100%" src="main.php" frameBorder=0></IFRAME>
              </TD>
            </TR>
          </TBODY>
        </TABLE>
      </TD>
    </TR>
  </TBODY>
  </TABLE>

</td></tr>
</TABLE>

</BODY>
</HTML>