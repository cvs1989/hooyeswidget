﻿using System;
using System.Collections.Generic;
using System.Linq;
using System.Web;
using System.Web.UI;
using System.Web.UI.WebControls;
using LeoShi.Soft.OpenSinaAPI;
using OpenTSDK.Tencent;
using OpenTSDK.Tencent.Objects;
using Tweet.Core;

public partial class _Default : System.Web.UI.Page
{
    protected void Page_Load(object sender, EventArgs e)
    {
        if (!IsPostBack)
        {
            if (HttpContext.Current.Session["QQ_user_id"] != null && HttpContext.Current.Session["Sina_user_id"] != null)
            {
                WebUtility.SaveRelation();
                string script = @"<script>
                 finish();
                </script>";
                ClientScript.RegisterStartupScript(this.Page.GetType(), "Finish", script);
            }
            if (HttpContext.Current.Session["QQ_user_id"] != null)
            {
                string appKey = "e40ccbe09c4945e08dc255a98fea1188";
                string appSecret = "9f786428568a9b44036d955b7c6b9196";
                OAuth oauth = new OAuth(appKey, appSecret);
                //string name;
                oauth.Token = (string)Session["QQ_oauth_token"];
                oauth.TokenSecret = (string)Session["QQ_oauth_token_secret"];   //Access Secret
                OpenTSDK.Tencent.API.User api = new OpenTSDK.Tencent.API.User(oauth);

                UserProfileData<UserProfile> data = api.GetProfile();


                if (data != null)
                {
                    string script = @"<script>
                  var QQ_NickName='{0}';
                  var QQ_Head='{1}';
                  QQisLogin();
                </script>";
                    script = string.Format(script, data.Profile.NickName, data.Profile.Head);
                    ClientScript.RegisterStartupScript(this.Page.GetType(), "QQ", script);

                }
                //根据OAuth对象实例化API接口
            }

            if (HttpContext.Current.Session["Sina_user_id"] != null)
            {
                var httpRequest = HttpRequestFactory.CreateHttpRequest(Method.POST);
                httpRequest.Token = "e7ddeb2263a81443cdf2dc7c4cb9fda2";// Session["oauth_token"].ToString();
                httpRequest.TokenSecret = "39df48c9cc65369a7cfe75cde2abf2b8";// Session["oauth_token_secret"].ToString();
                var url = "http://api.t.sina.com.cn/users/show.json?";
                var r = httpRequest.Request(url, "id=" + (string)HttpContext.Current.Session["Sina_user_id"]);
                string script = @"<script>
                  var sina={0};
                  SinaisLogin();
                </script>";
                script = string.Format(script, r);
                ClientScript.RegisterStartupScript(this.Page.GetType(), "Sina", script);
            }
        }
    }
 
    protected void ConnectSinaBtn_Click(object sender, EventArgs e)
    {
        var httpRequest = HttpRequestFactory.CreateHttpRequest(Method.GET) as HttpGet;
        httpRequest.GetRequestToken();
        string url = httpRequest.GetAuthorizationUrl();
        Session["oauth_token"] = httpRequest.Token;
        Session["oauth_token_secret"] = httpRequest.TokenSecret;
        string callback = Constant.AppCallBackDomain + "/CB_Sina.aspx";
        Response.Redirect(url + "&oauth_callback=" + callback);
    }
    protected void ConnectQQBtn_Click(object sender, EventArgs e)
    {
        string callback =Constant.AppCallBackDomain+ "/CB_QQ.aspx";
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