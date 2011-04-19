using System;
using System.Collections.Generic;
using System.Linq;
using System.Web;
using System.Web.UI;
using System.Web.UI.WebControls;
using LeoShi.Soft.OpenSinaAPI;
using OpenTSDK.Tencent;

public partial class _Default : System.Web.UI.Page
{
    protected void Page_Load(object sender, EventArgs e)
    {

    }
    protected void tbtn_Click(object sender, EventArgs e)
    {
        T t = new T();
        t.QQ(Text1.Text, null);
        t.Sina(Text1.Text, null);
    }
    protected void ConnectSinaBtn_Click(object sender, EventArgs e)
    {
        var httpRequest = HttpRequestFactory.CreateHttpRequest(Method.GET) as HttpGet;
        httpRequest.GetRequestToken();
        string url = httpRequest.GetAuthorizationUrl();
        Session["oauth_token"] = httpRequest.Token;
        Session["oauth_token_secret"] = httpRequest.TokenSecret;
        Response.Redirect(url + "&oauth_callback=http://t.hooyes.com/CB_Sina.aspx");
    }
    protected void ConnectQQBtn_Click(object sender, EventArgs e)
    {
        string callback = "http://t.hooyes.com/CB_QQ.aspx";
       // string callback = "http://localhost:8786/WebUI/CB_QQ.aspx";
        string appKey = "e40ccbe09c4945e08dc255a98fea1188";
        string appSecret = "9f786428568a9b44036d955b7c6b9196";
        OAuth oauth = new OAuth(appKey, appSecret);
        if (oauth.GetRequestToken(callback))
        {
            Session["QQ_oauth_token"] = oauth.Token;
            Session["QQ_oauth_token_secret"] = oauth.TokenSecret;
            string url = "https://open.t.qq.com/cgi-bin/authorize?oauth_token=" + oauth.Token;
            Response.Redirect(url);
        }
    }
}