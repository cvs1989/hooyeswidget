using System;
using System.Collections.Generic;
using System.Linq;
using System.Web;
using System.Web.UI;
using System.Web.UI.WebControls;
using LeoShi.Soft.OpenSinaAPI;
using OpenTSDK.Tencent;
using Tweet.Core;
using OpenTSDK.Tencent.API;
using OpenTSDK.Tencent.Objects;
public partial class CB_QQ_Mi : System.Web.UI.Page
{
    protected void Page_Load(object sender, EventArgs e)
    {
        string verifier = Request.QueryString.Get("oauth_verifier");
        if (!string.IsNullOrEmpty(verifier))
        {
            string appKey = Constant.app_key_QQ;
            string appSecret = Constant.app_secret_QQ;
            OAuth oauth = new OAuth(appKey, appSecret);
            string name;
            oauth.Token = (string)Session["QQ_oauth_token"];
            oauth.TokenSecret = (string)Session["QQ_oauth_token_secret"];
            if (oauth.GetAccessToken(verifier, out name))
            {
                Session["QQ_oauth_token"] = oauth.Token;
                Session["QQ_oauth_token_secret"] = oauth.TokenSecret;
                Session["QQ_user_id"] = name;




                DictEntity dt = new DictEntity();
                dt.App = "QQ";
                dt.UserID = name;
                dt.Key = "Token";
                dt.Value = oauth.Token;
                Dict.Save(dt);
                dt.Key = "TokenSecret";
                dt.Value = oauth.TokenSecret;
                Dict.Save(dt);

                ////MaxTimeline
                //Timeline api = new Timeline(oauth);
                //var data = api.GetBroadcast_timeline(PageFlag.First, 0, 1);
                //if (data.Tweets.Length > 0)
                //{
                //    OpenTSDK.Tencent.Objects.Tweet tw = data.Tweets[0];
                //    if (tw.Timestamp > 0)
                //    {
                //        DictEntity dtT = new DictEntity();
                //        dtT.App = "QQ";
                //        dtT.Key = "MaxTimeline";
                //        dtT.UserID = name;
                //        dtT.Value = tw.Timestamp.ToString();
                //        Dict.Save(dtT);
                //    }
                //}

                //T.SaveRelation();


                //Response.Write(oauth.TokenSecret);
                Response.Redirect("SB.aspx");
            }

        }
    }
}