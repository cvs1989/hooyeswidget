<%@ Page Language="C#" AutoEventWireup="true" CodeFile="Default.aspx.cs" Inherits="_Default" %>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
<head runat="server">
    <title>微同步  -- 微博云同步</title>
    <style type="text/css">
    body{font-size:14px; }
    .none{display:none;}
     img{ border:none;}
    .nav{ width:800px;margin:0 auto; margin-bottom:20px; margin-top:30px; border:1px solid #BBE1F1;
 background-color: #EEFAFF; font-size:14px; padding:10px; line-height:22px;
 border-radius: 5px; 
 border: 5px solid #FFFFFF;
 box-shadow: 1px 1px 5px #333333;
 }
    .MainBox{ width:810px; margin:0 auto; height:230px; margin-top:60px; }
    .MainBox .left{ float:left; width:250px;}
    .MainBox .center{ float:left; width:300px;}
    .MainBox .right{ float:left;width:250px;}
     .navOK{font-size:14px; width:800px;margin:0 auto; margin-bottom:20px; height:50px;border:1px solid #FFCC00; line-height:50px;background-color: #FFFFF7 }
     .clear{ clear:both;}
     .avatar{ border:solid 5px #BBE1F1; padding:5px;}
     .foot { font-size:12px;
	TEXT-ALIGN: center; PADDING-BOTTOM: 0px; LINE-HEIGHT: 24px; MARGIN: 0px; PADDING-LEFT: 0px; WIDTH: 100%; PADDING-RIGHT: 0px; FLOAT: left; COLOR: #9e9e9e; CLEAR: both; PADDING-TOP: 18px
}
.foot A:link {
	COLOR: #9e9e9e; TEXT-DECORATION: none
}
.foot A:visited {
	COLOR: #9e9e9e; TEXT-DECORATION: none
}
.foot A:active {
	COLOR: #9e9e9e; TEXT-DECORATION: none
}
.foot A:hover {
	TEXT-DECORATION: underline
}
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
            $("#QQ_img").removeAttr("src").attr("src", QQ_Head+"/180");
            $("#QQ_span").html(QQ_NickName);
        }
        function SinaisLogin() {
            $("#Sina_login").hide();
            $("#Sina").show();
            $("#Sina_img").removeAttr("src").attr("src", sina.profile_image_url.replace("/50/","/180/"));
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
    在您建立绑定后， 以后在发腾讯微博的时候，同时“微同步”帮你复制一份到你的新浪微博上。<br />
    同理，你在发新浪微博的时候，同时“微同步”帮你复制一份到你的腾讯微博上。无需客户端，云同步，快来体验吧!
    </div>
    <div class="MainBox">
   <div class="left"><a id="Sina_login" href="javascript:void(0)" onclick="A()">
       <img src="img/sinaLogin.png" />
     </a>
     <div id="Sina" class="none">
     新浪微博<br />
         <img id="Sina_img" class="avatar" src="" /><br />
         <span id="Sina_span"></span>
       </div>
     </div>
   <div class="center">
       <img src="img/bg_conn.gif" />
   </div>  
   <div class="right"><a id="QQ_login" href="javascript:void(0)" onclick="B()"><img src="img/qqLogin.png" />
     </a>
     <div id="QQ" class="none">
       腾讯微博<br />
       <img id="QQ_img" class="avatar" src="" /><br />
       <span id="QQ_span"></span>
       </div>
     </div>
    </div>
    <div class="clear"></div>
    <div id="ok" class="navOK none">设置完成！您可以发条微博试试看
    <a href="http://weibo.com" target="_blank">Sina</a>
    &nbsp;
    <a href="http://t.qq.com" target="_blank">QQ</a>
    </div>
    <div>
     
     
        <asp:Button ID="ConnectSinaBtn" runat="server" CssClass="none" Text="连接Sina" 
            onclick="ConnectSinaBtn_Click" />
        <asp:Button ID="ConnectQQBtn" runat="server" CssClass="none" Text="连接QQ" 
            onclick="ConnectQQBtn_Click" />
    </div>
    </form>

    <div class="foot">Copyright &copy; 2010 - 2012 HOOYES. All Rights Reserved.<br />

<a href="http://www.miibeian.gov.cn/?from=www.hooyes.com" target="_blank">京ICP备05002153号</a> 西狐 <a href="http://www.hooyes.com" target="_blank">版权所有</a> 
<br />官方新浪微博:<a href="http://weibo.com/hooyes" target="_blank">@hooyes</a> 腾讯微博 <a href="http://t.qq.com/hooyes" target="_blank">@hooyes</a>
QQ:227046</div>


<div id="cnzzcount_f_hooyes" class="foot" style="display:none">
<script type="text/javascript" src="http://s22.cnzz.com/stat.php?id=199582&web_id=199582" language="JavaScript"></script>
</div>

</body>
</html>
