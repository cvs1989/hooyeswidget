using System;
using System.Collections.Generic;
using System.Linq;
using System.Web;
using System.Web.UI;
using System.Web.UI.WebControls;
using LeoShi.Soft.OpenSinaAPI;
using Tweet.Core;

public partial class CB_Sina_Mi : System.Web.UI.Page
{
    protected void Page_Load(object sender, EventArgs e)
    {
        var httpRequest = HttpRequestFactory.CreateHttpRequest(Method.GET) as HttpGet;
        if (Request["oauth_verifier"] != null)
        {
            string user_id = "";
            httpRequest.Token = Session["oauth_token"].ToString();
            httpRequest.TokenSecret = Session["oauth_token_secret"].ToString();
            httpRequest.Verifier = Request["oauth_verifier"];
            httpRequest.GetAccessToken(out user_id);
            Session["oauth_token"] = httpRequest.Token;
            Session["oauth_token_secret"] = httpRequest.TokenSecret;
            Session["Sina_user_id"] = user_id;


            DictEntity dt = new DictEntity();
            dt.App = "Sina";
            dt.UserID = user_id;
            dt.Key = "Token";
            dt.Value = httpRequest.Token;
            Dict.Save(dt);
            dt.Key = "TokenSecret";
            dt.Value = httpRequest.TokenSecret;
            Dict.Save(dt);
            //T.SaveRelation();
            //Response.Write(httpRequest.TokenSecret);
            Response.Redirect("Mi.aspx");
        }
    }
}