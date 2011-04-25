<%@ Page Language="C#" AutoEventWireup="true" CodeFile="SB.aspx.cs" Inherits="SB" %>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
<head id="Head1" runat="server">
    <title>悄悄讨厌你</title>
    <link href="css/base.css" rel="stylesheet" type="text/css" />
    
</head>
<body>
    <form id="form1" runat="server">
    
 <div class="formBox none" id="Container">
 
    <div class="boxTitle">输入你最讨厌的人的腾讯微博帐号...</div>
    <div id="A_1">
    <div class="appTips"><asp:TextBox ID="TextBox1" MaxLength="20" CssClass="sl" runat="server"></asp:TextBox>
            <asp:Button ID="BtnSumbit" runat="server" OnClientClick="return I()" CssClass="button" Text="提交" 
                onclick="BtnSumbit_Click" />
        </div>
 </div>
    <div id="A_2">
    <div class="appTips">请静静的等待...现在还没有人讨厌你
    <div><a href="javascript:void(0);" onclick="C()">换个对象</a></div>
    </div>
    </div>

    <div id="A_3">
    <div class="appTips">
   <a id="QQ_login" href="javascript:void(0)" onclick="B()"><img src="img/qqLogin.png" />
     </a>
       <asp:Button ID="ConnectQQBtn" runat="server" CssClass="none" Text="连接QQ" 
            onclick="ConnectQQBtn_Click" />
    </div>
    </div>
    
    <div class="appTips">把你自己最讨厌对象的腾讯微博帐号悄悄地输入到系统，如果她（他）有一天也在系统中输入你的腾讯微博帐号，系统就会悄悄出面调解你们的矛盾，大家和气生财。</div>
</div>
    
  
    
    </form>
<script src="js/jquery-1.4.2.min.js" type="text/javascript"></script>
<script type="text/javascript">
    //var json = { isLogin: false, love: '' };
    function B() {
        $("#ConnectQQBtn").click();
    }
    function I() {
        var v = $("#TextBox1").val();
        if (v == "" || v == null) {
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
            if (json.love == "") {
                $("#A_1").show();
                $("#A_2").hide();
            } else {
                $("#A_1").hide();
                $("#A_2").show();
            }
            $("#A_3").hide();
        } else {
            $("#A_1").hide();
            $("#A_2").hide();
            $("#A_3").show();
        }
    }

    Page_Load();

    $("#Container").fadeIn("slow");
</script>
</body>
</html>