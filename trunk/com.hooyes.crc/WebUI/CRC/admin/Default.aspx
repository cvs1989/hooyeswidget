<%@ Page Language="C#" EnableViewState="false" AutoEventWireup="true" CodeFile="Default.aspx.cs" Inherits="CRC_admin_Default" %>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" >
<head runat="server">
    <title>admin</title>
    <link href="../../CRC/hooyes.Css/crc.css" rel="stylesheet" type="text/css" />
</head>
<body>
    <form id="form1" runat="server">
    <div class="CommonDiv"  >
        <asp:Literal ID="WelComeLiteral1" runat="server"></asp:Literal>
    </div>
   
    <div class="CommonDiv">
         输入关键字：<asp:TextBox ID="KeyWordInput" runat="server"></asp:TextBox> 
       <asp:Button ID="ButtonSearch" runat="server" Text="查找" OnClick="ButtonSearch_Click"  /></div>
         <div class="tipDiv" id="tipDiv" runat="server" visible="false"></div>
    <div class="CommonDiv" style="margin-bottom:0px;" >
         <asp:Literal ID="xLiteral1" runat="server"></asp:Literal>
    </div>
    <div class="CommonDiv" >
        <asp:Button ID="hooyesDeleteBtn" runat="server" Text="批量删除" OnClick="hooyesDeleteBtn_Click" OnClientClick="return ConfirmDelete()"  /></div>
    <div class="CommonDiv" id="">
        <asp:Literal ID="pageLiteral1" runat="server"></asp:Literal>
    </div>
    <div class="CommonDiv" id="footer" style="text-align:center; font-size:12px;" >&copy 2009 Powered by hooyes</div>
    </form>
 <script type="text/javascript" src="../hooyes.js/jquery-latest.pack.js"></script>
 <script type="text/javascript">
 //通用方法 
 function JSCheckAll(t){
   var tar=document.getElementsByName("sn");
   for(var i=0;i<tar.length;i++){
     document.getElementsByName("sn")[i].checked=t.checked;
   }
 } 
 function ConfirmDelete(){
    var flag=false;
    var tar=document.getElementsByName("sn");
   for(var i=0;i<tar.length;i++){
     if(tar[i].checked==true){
      flag=true;
      break;
     }
   }
  if(flag){
  return confirm('确定要删除选中的公司吗');
  }else{
  alert("未选中任何公司");
  return false;
  }
 }
 function GetFocus(){
    if(event.keyCode==13){
    document.getElementById("<%=ButtonSearch.ClientID%>").click();
     return false;
    }
 }
 //Ajax 相关
   function SetPayStatus(sn,v,jq){
    var tUrl="SetPayStatus.aspx";
    var param="sn="+sn+"&pay="+v;
    $.ajax({
     url:tUrl,
     type:'POST',
     data:param,
     dataType:'json',
     success:function(data){
      if(data.flag){
       jq.html(v ? "已付" : "未付");
       jq.attr("pay",v.toString());
       //jq.css('color',v?'green':'Gray');
       jq.css('background-color',v?'green':'#FFF');
      }
     }
    });
   }
   function SetInvoicStatus(sn,v,jq){
    var tUrl="SetInvoicStatus.aspx";
    var param="sn="+sn+"&invoice="+v;
    $.ajax({
     url:tUrl,
     type:'POST',
     data:param,
     dataType:'json',
     success:function(data){
      if(data.flag){
       jq.html(v ? "是" : "否");
       jq.attr("invoice",v.toString());
       //jq.css('color',v?'green':'Gray');
       jq.css('background-color',v?'green':'#FFF');
      }
     }
    });
   }
   //初始化
   $(function(){
     $(".pay").click(function(){
   　var sn=$(this).attr("rel");
   　var v=!eval($(this).attr("pay").toLowerCase());
   　SetPayStatus(sn,v,$(this));
   })
   $(".unpay").click(function(){
   　var sn=$(this).attr("rel");
   　var v=!eval($(this).attr("pay").toLowerCase());
   　SetPayStatus(sn,v,$(this));
   })
   $(".invoice").click(function(){
   　var sn=$(this).attr("rel");
   　var v=!eval($(this).attr("invoice").toLowerCase());
   　SetInvoicStatus(sn,v,$(this));
   })
   $(".uninvoice").click(function(){
   　var sn=$(this).attr("rel");
   　var v=!eval($(this).attr("invoice").toLowerCase());
   　SetInvoicStatus(sn,v,$(this));
   })
   });
   //Create by hooyes 2009.12.12
  </script>
  
</body>
</html>
