﻿<%@ Page Language="C#" EnableViewState="false" AutoEventWireup="true" CodeFile="List.aspx.cs" Inherits="CRC_List" %>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" >
<head id="Head1" runat="server">
    <title>5th China Rubber Conference &amp; World Rubber Summit</title>
    <link href="hooyes.Css/crc.css" rel="stylesheet" type="text/css" />
<link href="style.css" rel="stylesheet" type="text/css" />
</head>
<body>
<!--头部文件-->
<table border="0" cellpadding="0" cellspacing="0" bgcolor="#8CB621">
<tr>
 <td class="GlobalLeft" valign="top">
 <!--#include file="PageTop.html"-->
 <!--页面中心部份 开始-->
<table width="755" border="0" cellpadding="0" cellspacing="0">
        <tr> 
          <td width="57"><img src="img/top1_r14_c1.gif" width="57" height="20"></td>
          <td width="836" background="img/top1_r14_c3.gif">&nbsp;</td>
          <td width="65"><img src="img/top1_r14_c5.gif" width="65" height="20"></td>
        </tr>
        <tr> 
          <td background="img/top1_r15_c1.gif">&nbsp;</td>
          <td valign="top" bgcolor="#BBD479">  <!--中部导航-->
          <!--#include file="PageNavigation.html"-->
            <table width="60%" border="0" cellspacing="0" cellpadding="0">
              <p></p>
              <tr> 
                <td width="5%"><img src="img/item.jpg" width="15" height="25"></td>
                <td width="93%" class="title-1">- CRC 2010 &gt;&gt; Registered 
                  Attendees </td>
              </tr>
            </table>
            <hr>
            <!--中心主体内容 开始-->
            <form id="form1" runat="server">
    <div class="CommonDiv CenterPart CenterPart"  >
        <asp:Literal ID="WelComeLiteral1" runat="server"></asp:Literal>
    </div>          
              <div class="CommonDiv CenterPart CenterPart"> <span class="text"><img src="../img/magnifier.gif" width="18" height="20" /> 
                Key Word(s): </span> 
                <asp:TextBox ID="KeyWordInput" runat="server"></asp:TextBox> 
       <asp:Button ID="ButtonSearch" runat="server" Text="Go" OnClick="ButtonSearch_Click"  /></div>
         <div class="tipDiv CenterPart" id="tipDiv" runat="server" visible="false"></div>
    <div class="CommonDiv CenterPart CenterPart" style="margin-bottom:0px;" >
         <asp:Literal ID="xLiteral1" runat="server"></asp:Literal>
    </div>
    <hr />
    <div class="CommonDiv CenterPart CenterPart" id="">
        <asp:Literal ID="pageLiteral1" runat="server"></asp:Literal>
    </div>
 
    </form>
    <script type="text/javascript">
 
 function GetFocus(){
    if(event.keyCode==13){
    document.getElementById("<%=ButtonSearch.ClientID%>").click();
     return false;
    }
 }
 
 </script>
          <!--中心主体内容 结束-->
            </td>
          <td background="img/top1_r15_c5.gif">&nbsp;</td>
        </tr>
        <tr> 
          <td><img src="img/top1_r16_c1.gif" width="57" height="19"></td>
          <td background="img/top1_r16_c2.gif">&nbsp;</td>
          <td><img src="img/top1_r16_c5.gif" width="65" height="19"></td>
        </tr>
      </table>
 <!--页面中心部份 结束-->
 <!--页脚文件-->
 <!--#include file="PageBottom.html"-->
 </td>
 <td class="GlobalRight" width="245" valign="top">
 <!--右侧文件-->
 <!--#include file="PageRight.html"-->
 </td>
 </tr>
 </table>
 
</body>
</html>