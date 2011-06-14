<%@ Page Language="C#" AutoEventWireup="true" CodeFile="Default.aspx.cs" Inherits="_Default" %>

<!DOCTYPE html>
<html>
<head runat="server">
    <meta name="keywords" content="西狐微博同步,微同步,微博云同步,微博,围脖,围脖云同步,新浪微博同步，腾讯微博同步" />
    <meta name="description" content="将新浪微博同步到腾讯微博，将腾讯微博同步到新浪微博，无需客户端的云同步" />
    <title>微同步 -- 微博云同步</title>
    <link rel="icon" href="img/favicon.ico" type="image/x-icon" />
    <link rel="shortcut icon" href="img/favicon.ico" type="image/x-icon" />
    <link rel="icon" href="img/icon3.gif" type="image/gif" />
    <link href="css/base.css" rel="stylesheet" type="text/css" />
    <script src="js/jquery-1.4.2.min.js" type="text/javascript"></script>
    <script type="text/javascript">
        function A() {
            $("#ConnectSinaBtn").click();
        }
        function B() {
            $("#ConnectQQBtn").click();
        }
        function QQisLogin() {
            $("#QQ_login").hide();
            $("#QQ").show();
            $("#QQ_img").removeAttr("src").attr("src", QQ_Head + "/180");
            $("#QQ_span").html(QQ_NickName);
        }
        function SinaisLogin() {
            $("#Sina_login").hide();
            $("#Sina").show();
            $("#Sina_img").removeAttr("src").attr("src", sina.profile_image_url.replace("/50/", "/180/"));
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
        请分别点击以两个按钮进行授权关联两个微博<br />
        在您建立关联后， 以后在发腾讯微博的时候，“微同步”帮你同时复制一份到你的新浪微博上。<br />
        同理，你在发新浪微博的时候，“微同步”同时帮你复制一份到你的腾讯微博上。无需客户端，云同步，快来体验吧!<br />
        需要帮助? 请微博@hooyes
    </div>
    <div class="MainBox">
        <div class="left">
            <a id="Sina_login" class="tx" href="javascript:void(0)" onclick="A()">
                <img src="img/sinaLogin.png" title="请点击授权，新浪应用名称:悄悄喜欢你" />
            </a>
            <div id="Sina" class="none">
                新浪微博<br />
                <img id="Sina_img" class="avatar" src="img/sinaLogin.png" /><br />
                <span id="Sina_span"></span>
            </div>
        </div>
        <div class="center">
            &nbsp;
        </div>
        <div class="right">
            <a id="QQ_login" class="tx" href="javascript:void(0)" onclick="B()">
                <img src="img/qqLogin.png" title="请点击授权，腾迅应用名称:微同步" />
            </a>
            <div id="QQ" class="none">
                腾讯微博<br />
                <img id="QQ_img" class="avatar" src="img/qqLogin.png" /><br />
                <span id="QQ_span"></span>
            </div>
        </div>
    </div>
    <div class="clear">
    </div>
    <div id="ok" class="navOK none">
        设置完成！您可以发条微博试试看，前往<a href="http://weibo.com" target="_blank">新浪微博</a> &nbsp; <a href="http://t.qq.com"
            target="_blank">腾讯微博</a>
        
    </div>
    <div class="shareTo">
    
        分享到  新浪微博：
        <!-- Sina share -->

        <script type="text/javascript" charset="utf-8">
            (function () {
                var _w = 72, _h = 16;
                var param = {
                    url: location.href,
                    type: '3',
                    count: '1', /**是否显示分享数，1显示(可选)*/
                    appkey: '3472084219', /**您申请的应用appkey,显示分享来源(可选)*/
                    title: '', /**分享的文字内容(可选，默认为所在页面的title)*/
                    pic: '', /**分享图片的路径(可选)*/
                    ralateUid: '1089048321', /**关联用户的UID，分享微博会@该用户(可选)*/
                    rnd: new Date().valueOf()
                }
                var temp = [];
                for (var p in param) {
                    temp.push(p + '=' + encodeURIComponent(param[p] || ''))
                }
                document.write('<iframe allowTransparency="true" frameborder="0" scrolling="no" src="http://hits.sinajs.cn/A1/weiboshare.html?' + temp.join('&') + '" width="' + _w + '" height="' + _h + '"></iframe>')
            })()
        </script>
        
        <!--QQ share-->
        腾讯微博：
        <a href="javascript:void(0)" onclick="postToWb();return false;" >
            <img src="http://v.t.qq.com/share/images/s/weiboicon16.png"   title="分享到腾讯微博" />
            </a>
        <script type="text/javascript">
            function postToWb() {
                var _t = encodeURI(document.title);
                var _url = encodeURIComponent(document.location);
                var _appkey = encodeURI("3ab2872742234704925c33dec507f9bb"); //你从腾讯获得的appkey
                var _pic = encodeURI(''); //（例如：var _pic='图片url1|图片url2|图片url3....）
                var _site = ''; //你的网站地址
                var _u = 'http://v.t.qq.com/share/share.php?url=' + _url + '&appkey=' + _appkey + '&site=' + _site + '&pic=' + _pic + '&title=' + _t;
                window.open(_u, '', 'width=700, height=680, top=0, left=0, toolbar=no, menubar=no, scrollbars=no, location=yes, resizable=no, status=no');
            }
        </script>
    </div>
    <div>
        <asp:Button ID="ConnectSinaBtn" runat="server" CssClass="none" Text="连接Sina" OnClick="ConnectSinaBtn_Click" />
        <asp:Button ID="ConnectQQBtn" runat="server" CssClass="none" Text="连接QQ" OnClick="ConnectQQBtn_Click" />
    </div>
    </form>
    <div class="foot">
        Copyright &copy; 2010 - 2012 <a href="http://www.hooyes.com" target="_blank">hooyes</a>.
        All Rights Reserved.<br />
        <a href="http://www.miibeian.gov.cn/?from=www.hooyes.com" target="_blank">京ICP备05002153号</a>
        西狐 版权所有
        <br />
        官方新浪微博:<a href="http://weibo.com/hooyes" target="_blank">@hooyes</a> 腾讯微博 <a href="http://t.qq.com/hooyes"
            target="_blank">@hooyes</a> QQ:227046</div>
    <div id="cnzzcount_f_hooyes" class="foot" style="display: none">
        <script type="text/javascript" src="http://s22.cnzz.com/stat.php?id=199582&web_id=199582"></script>
    </div>
</body>
<!-- 2011.6.14 hooyes www.hooyes.com -->
</html>
