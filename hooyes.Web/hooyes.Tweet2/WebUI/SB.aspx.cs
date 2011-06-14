using System;
using System.Collections.Generic;
using System.Linq;
using System.Web;
using System.Web.UI;
using System.Web.UI.WebControls;
using LeoShi.Soft.OpenSinaAPI;
using Tweet.Core;
using OpenTSDK.Tencent;

public partial class SB : System.Web.UI.Page
{
    protected void Page_Load(object sender, EventArgs e)
    {
        Response.Redirect("default.aspx");
        //initPage();
    }
    protected void ConnectQQBtn_Click(object sender, EventArgs e)
    {
        string callback = Constant.AppCallBackDomain + "/CB_QQ_Mi.aspx";
        string appKey = Constant.app_key_QQ;
        string appSecret = Constant.app_secret_QQ;
        OAuth oauth = new OAuth(appKey, appSecret);
        if (oauth.GetRequestToken(callback))
        {
            Session["QQ_oauth_token"] = oauth.Token;
            Session["QQ_oauth_token_secret"] = oauth.TokenSecret;
            string url = "https://open.t.qq.com/cgi-bin/authorize?oauth_token=" + oauth.Token;
            Response.Redirect(url);
        }
    }
    protected void BtnSumbit_Click(object sender, EventArgs e)
    {
        DictEntity dt = new DictEntity();
        dt.App = "QQ";
        dt.UserID = Convert.ToString(Session["QQ_user_id"]);
        dt.Key = Convert.ToString(Session["QQ_user_id"]);
        dt.Value = TextBox1.Text.Replace("'", "");

        Dict.Save(dt);

        Response.Redirect("SB.aspx");
    }
    protected void initPage()
    {
        string json = "";
        if (Session["QQ_user_id"] != null)
        {
            DictEntity dt = new DictEntity();
            dt.App = "QQ";
            dt.UserID = Convert.ToString(Session["QQ_user_id"]);
            dt.Key = Convert.ToString(Session["QQ_user_id"]);
            //dt.Value = TextBox1.Text;

            string v = Dict.Get<string>(dt);
            json = "isLogin:{1},love:'{0}'";
            json = string.Format(json, v, "true");
        }
        else
        {
            json = "isLogin:{1},love:'{0}'";
            json = string.Format(json, "", "false");
        }
        json = "{" + json + "}";
        string script = @"<script>
         var json={0};
        </script>";
        script = string.Format(script, json);
        ClientScript.RegisterClientScriptBlock(this.GetType(), "xx", script);

    }
}