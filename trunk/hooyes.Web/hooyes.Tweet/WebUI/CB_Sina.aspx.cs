using System;
using System.Collections.Generic;
using System.Linq;
using System.Web;
using System.Web.UI;
using System.Web.UI.WebControls;
using LeoShi.Soft.OpenSinaAPI;

public partial class CB_Sina : System.Web.UI.Page
{
    protected void Page_Load(object sender, EventArgs e)
    {
        var httpRequest = HttpRequestFactory.CreateHttpRequest(Method.GET) as HttpGet;
        if (Request["oauth_verifier"] != null)
        {
            httpRequest.Token = Session["oauth_token"].ToString();
            httpRequest.TokenSecret = Session["oauth_token_secret"].ToString();
            httpRequest.Verifier = Request["oauth_verifier"];
            httpRequest.GetAccessToken();
            Session["oauth_token"] = httpRequest.Token;
            Session["oauth_token_secret"] = httpRequest.TokenSecret;

            Response.Write(httpRequest.TokenSecret);
        }
    }
}