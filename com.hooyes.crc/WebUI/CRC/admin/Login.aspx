<%@ Page Language="C#" EnableViewState="false" AutoEventWireup="true" CodeFile="Login.aspx.cs" Inherits="CRC_admin_Login" %>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" >
<head runat="server">
    <title>Login</title>
    <link href="../../CRC/hooyes.Css/crc.css" rel="stylesheet" type="text/css" />
</head>
<body>
    <form id="form1" runat="server">
    <div class="CommonDiv">管理员登录</div>
    <div class="CommonDiv">
      <table class="LoginTable">
      <tr><td class="LoginTableA">用户名:</td>
      <td class="LoginTableB">
          <asp:TextBox ID="TextBoxUID" CssClass="LoginTableInput" runat="server"></asp:TextBox>
          </td>
      </tr>
      <tr><td>密码:</td>
      <td>
          <asp:TextBox ID="TextBoxPWD" CssClass="LoginTableInput" TextMode="password" runat="server"></asp:TextBox>
          </td>
      </tr>
      <tr>
      <td colspan="2">
          <asp:Button ID="hooyesLoginBtn" runat="server" Text="登录" OnClick="hooyesLoginBtn_Click" OnClientClick="return ValidateLogin()"  /></td>
      </tr>
      </table>
    </div>
    </form>
    <script type="text/javascript">
     function ValidateLogin(){
      var rValue=false;
      var UID=document.getElementById("<%=TextBoxUID.ClientID %>").value;
      var PWD=document.getElementById("<%=TextBoxPWD.ClientID %>").value;
      if(UID==""){
      alert("请输入用户名");
      document.getElementById("<%=TextBoxUID.ClientID %>").focus();
      return false;
      }
      if(PWD==""){
      alert("请输入密码");
      document.getElementById("<%=TextBoxPWD.ClientID %>").focus();
      return false;
      }
      rValue=true;
      return rValue;
     
     }
    </script>
</body>
</html>
