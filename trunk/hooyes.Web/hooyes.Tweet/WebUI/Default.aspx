<%@ Page Language="C#" AutoEventWireup="true" CodeFile="Default.aspx.cs" Inherits="_Default" %>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
<head runat="server">
    <title></title>
    <style type="text/css">
    .none{display:none;}
     img{ border:none;}
    .nav{ width:800px;margin:0 auto; margin-bottom:20px; }
    .MainBox{ width:810px; margin:0 auto; height:230px; }
    .MainBox .left{ float:left; width:250px;}
    .MainBox .center{ float:left; width:300px;}
    .MainBox .right{ float:left;width:250px;}
     .navOK{ width:800px;margin:0 auto; margin-bottom:20px; height:30px; }
     .clear{ clear:both;}
    </style>
    <script src="js/jquery-1.4.2.min.js" type="text/javascript"></script>
    <script type="text/javascript">
        function A() {
            //alert(1);
            $("#ConnectSinaBtn").click();
        }
        function B() {
            $("#ConnectQQBtn").click();
        }

        function QQisLogin() {
            $("#QQ_login").hide();
            $("#QQ").show();
            $("#QQ_img").removeAttr("src").attr("src", QQ_Head+"/50");
            $("#QQ_span").html(QQ_NickName);
        }
        function SinaisLogin() {
            $("#Sina_login").hide();
            $("#Sina").show();
            $("#Sina_img").removeAttr("src").attr("src", sina.profile_image_url);
            $("#Sina_span").html(sina.screen_name);
        }
        function finish() {
            $("#ok").show();
        }
    </script>
</head>
<body>
    <form id="form1" runat="server">
   
    <div class="nav">
    在您建立绑定后， 以后在发腾讯微博的时候，同时“微同步”帮你复制一份到你的新浪微博上哦。
    </div>
    <div class="MainBox">
   <div class="left"><a id="Sina_login" href="javascript:void(0)" onclick="A()">
       <img src="img/sinaLogin.png" />
     </a>
     <div id="Sina" class="none">
     新浪微博：<br />
         <img id="Sina_img" src="" /><br />
         <span id="Sina_span"></span>
       </div>
     </div>
   <div class="center">
       <img src="img/bg_conn.gif" />
   </div>  
   <div class="right"><a id="QQ_login" href="javascript:void(0)" onclick="B()"><img src="img/qqLogin.png" />
     </a>
     <div id="QQ" class="none">
       腾讯微博：<br />
       <img id="QQ_img" src="" /><br />
       <span id="QQ_span"></span>
       </div>
     </div>
    </div>
    <div class="clear"></div>
    <div id="ok" class="navOK none">设置完成！您可以发条微博试试看</div>
    <div>
     
     
        <asp:Button ID="ConnectSinaBtn" runat="server" CssClass="none" Text="连接Sina" 
            onclick="ConnectSinaBtn_Click" />
        <asp:Button ID="ConnectQQBtn" runat="server" CssClass="none" Text="连接QQ" 
            onclick="ConnectQQBtn_Click" />
    </div>
    </form>
</body>
</html>
