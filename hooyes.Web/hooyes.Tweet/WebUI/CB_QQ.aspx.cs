using System;
using System.Collections.Generic;
using System.Linq;
using System.Web;
using System.Web.UI;
using System.Web.UI.WebControls;
using LeoShi.Soft.OpenSinaAPI;
using OpenTSDK.Tencent;
public partial class CB_QQ : System.Web.UI.Page
{
    protected void Page_Load(object sender, EventArgs e)
    {
        string verifier = Request.QueryString.Get("oauth_verifier");
        if (!string.IsNullOrEmpty(verifier))
        {
            string appKey = "e40ccbe09c4945e08dc255a98fea1188";
            string appSecret = "9f786428568a9b44036d955b7c6b9196";
            OAuth oauth = new OAuth(appKey, appSecret);
            string name;
            oauth.Token = (string)Session["QQ_oauth_token"];
            oauth.TokenSecret = (string)Session["QQ_oauth_token_secret"];
            if (oauth.GetAccessToken(verifier, out name))
            {
                Session["QQ_oauth_token"] = oauth.Token;
                Session["QQ_oauth_token_secret"] = oauth.TokenSecret;

                Response.Write(oauth.TokenSecret);
            }

        }
    }
}