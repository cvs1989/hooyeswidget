<%@ Page Language="C#" AutoEventWireup="true" CodeFile="Mi.aspx.cs" Inherits="Mi" %>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
<head runat="server">
    <title>悄悄喜欢你</title>
    <link href="css/base.css" rel="stylesheet" type="text/css" />
</head>
<body>
    <form id="form1" runat="server">
    
 <div class="formBox none" id="Container">
 
    <div class="boxTitle">输入你悄悄喜欢的人的新浪微博帐号...</div>
    <div id="A_1">
    <div class="appTips">
    <div id="Acct">
     <img id="Sina_img" class="avatar" src="" /><br />
         <span id="Sina_span"></span>
   <a href="javascript:void(0);" onclick="A()">换个帐号登录</a>&nbsp;
   <a href="Mi_logout.aspx">退出</a>
   <br />
   </div>
    <asp:TextBox ID="TextBox1" MaxLength="20" CssClass="sl" runat="server"></asp:TextBox>
            <asp:Button ID="BtnSumbit" runat="server" OnClientClick="return I()" CssClass="button" Text="提交" 
                onclick="BtnSumbit_Click" />
                
        </div>
 </div>
    <div id="A_2">
    <div class="appTips">请静静的等待...或许哪一天你真的等到了你的真爱
    <div><a href="javascript:void(0);" onclick="C()">换个对象</a></div>
    </div>
    </div>

    <div id="A_3">
    <div class="appTips auth">
   使用本功能请使用新浪微博授权<br /><a id="Sina_login" href="javascript:void(0)" onclick="A()" >
       <img src="img/sinaLogin.png" />
     </a>
       <asp:Button ID="ConnectSinaBtn" runat="server" CssClass="none" Text="连接Sina" 
            onclick="ConnectSinaBtn_Click" />
    </div>
    </div>
    
    <div class="appTips">把你自己暗恋对象的<b>新浪微博帐号</b>悄悄地输入到系统，如果她（他）有一天也在系统中输入你的<b>新浪微博帐号</b>，系统就会就把真相大白告诉你们两个，祝天下有情人终成眷属！
    <b>您同否同时有新浪微博和腾讯微博？</b><a href="Default.aspx" title="">试试微博同步</a>,无需下载客户端，云同步哦。
    </div>
</div>
    
  
    
    </form>
<script src="js/jquery-1.4.2.min.js" type="text/javascript"></script>
<script type="text/javascript">
    //var json = { isLogin: false, love: '' };
    function A() {
            //alert(1);
            $("#ConnectSinaBtn").click();
        }
        function I() {
            if (!json.isLogin) {
                alert("请点击下方新浪微博授权后使用");
                $("#A_3").addClass("Foc");
                return false;
            }
        var v = $("#TextBox1").val();
        if (v == "" || v==null) {
            alert("你要输入个帐号哦");
            return false;
        }
        return true;
    }
    function C() {
        $("#A_1").show();
        $("#A_2").hide();
    }
    function Page_Load() {

        if (json.isLogin) {
            SinaisLogin();
            if (json.love == "") {
                $("#A_1").show();
                $("#A_2").hide();
            } else {
                $("#A_1").hide();
                $("#A_2").show();
            }
            $("#A_3").hide();
        } else {
        $("#Acct").hide();
            $("#A_1").show();
            $("#A_2").hide();
            $("#A_3").show();
        }
    }
    function SinaisLogin() {
        //$("#Sina_login").hide();
        //$("#Sina").show();
        if (sina.screen_name) {
            $("#Sina_img").removeAttr("src").attr("src", sina.profile_image_url);
            $("#Sina_span").html(sina.screen_name);
        }
    }

    Page_Load();


    $("#Container").fadeIn("slow");
</script>
<div id="cnzzcount_f_hooyes" class="foot" style="display:none">
<script type="text/javascript" src="http://s22.cnzz.com/stat.php?id=199582&web_id=199582" language="JavaScript"></script>
</div>
</body>
</html>
