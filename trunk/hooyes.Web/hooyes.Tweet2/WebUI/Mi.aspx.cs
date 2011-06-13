using System;
using System.Collections.Generic;
using System.Linq;
using System.Web;
using System.Web.UI;
using System.Web.UI.WebControls;
using LeoShi.Soft.OpenSinaAPI;
using Tweet.Core;

public partial class Mi : System.Web.UI.Page
{
    protected void Page_Load(object sender, EventArgs e)
    {
        initPage();
    }
    protected void ConnectSinaBtn_Click(object sender, EventArgs e)
    {
        var httpRequest = HttpRequestFactory.CreateHttpRequest(Method.GET) as HttpGet;
        httpRequest.GetRequestToken();
        string url = httpRequest.GetAuthorizationUrl();
        Session["oauth_token"] = httpRequest.Token;
        Session["oauth_token_secret"] = httpRequest.TokenSecret;
        string callback = Constant.AppCallBackDomain + "/CB_Sina_Mi.aspx";
        Response.Redirect(url + "&oauth_callback=" + callback);


    }
    protected void BtnSumbit_Click(object sender, EventArgs e)
    {
        if (Session["Sina_user_id"] != null)
        {
            DictEntity dt = new DictEntity();
            dt.App = "Sina";
            dt.UserID = Convert.ToString(Session["Sina_user_id"]);
            dt.Key = Convert.ToString(Session["Sina_user_id"]);
            dt.Value = TextBox1.Text.Replace("'", "");

            Dict.Save(dt);

            Response.Redirect("Mi.aspx");
        }
    }
    protected void initPage()
    {
        string json = "";
        if (Session["Sina_user_id"] != null)
        {
            DictEntity dt = new DictEntity();
            dt.App = "Sina";
            dt.UserID = Convert.ToString(Session["Sina_user_id"]);
            dt.Key = Convert.ToString(Session["Sina_user_id"]);
            //dt.Value = TextBox1.Text;

            string v = Dict.Get<string>(dt);
            json = "isLogin:{1},love:'{0}'";
            json = string.Format(json, v,"true");
        }
        else
        {
            json = "isLogin:{1},love:'{0}'";
            json = string.Format(json,"","false");
        }
        json = "{" + json + "}";
        string script = @"<script>
         var json={0};
        </script>";
        script = string.Format(script, json);
        ClientScript.RegisterClientScriptBlock(this.GetType(), "xx", script);

        if (HttpContext.Current.Session["Sina_user_id"] != null)
        {
            
            var httpRequest = HttpRequestFactory.CreateHttpRequest(Method.POST);
            httpRequest.Token = Convert.ToString(Session["oauth_token"]);
            httpRequest.TokenSecret = Convert.ToString(Session["oauth_token_secret"]);
            string url = "http://api.t.sina.com.cn/users/show.json?";
            string r = httpRequest.Request(url, "id=" + (string)HttpContext.Current.Session["Sina_user_id"]);
            string script2 = @"<script>
                  var sina={0};
                </script>";
            script2 = string.Format(script2, r);
            ClientScript.RegisterClientScriptBlock(this.Page.GetType(), "Sina", script2);
        }

    }
}