using System;
using System.Collections.Generic;
using System.Linq;
using System.Web;
using LeoShi.Soft.OpenSinaAPI;
using OpenTSDK.Tencent.API;
using OpenTSDK.Tencent;
using Tweet.Core;


/// <summary>
/// Summary description for T
/// </summary>
public class T
{

	public T()
	{
        
		//
		// TODO: Add constructor logic here
		//
	}
    public bool QQ(string statusText, string pic)
    {
        //实例化OAuth对象
        string appKey = "e40ccbe09c4945e08dc255a98fea1188";
        string appSecret = "9f786428568a9b44036d955b7c6b9196";
        OAuth oauth = new OAuth(appKey, appSecret);
        oauth.Token =(string) HttpContext.Current.Session["QQ_oauth_token"];            //Access Token
        oauth.TokenSecret = (string)HttpContext.Current.Session["QQ_oauth_token_secret"];     //Access Secret

        //根据OAuth对象实例化API接口
        //Timeline api = new Timeline(oauth);
        //var data = api.GetPublicTimeline(0, 10);

        Twitter twitter = new Twitter(oauth);
        var data2 = twitter.Add(statusText, pic,HttpContext.Current.Request.UserHostAddress );
        //var data2 = twitter.Add(statusText, "207.6.14.22");
        if (data2.Ret == 0)
        {
            //删除刚发的微博
            // Console.WriteLine("发布成功");
        }
        else
        {
            // Console.WriteLine("发布失败");
        }

        return true;

        //Console.ReadLine();
    }
    public bool Sina(string statusText, string pic)
    {

        //var url = "http://api.t.sina.com.cn/statuses/update.json?";
        string r = string.Empty;
        if (!string.IsNullOrEmpty(pic))
        {
            var httpRequest = HttpRequestFactory.CreateHttpRequest(Method.POST) as HttpPost;
            httpRequest.Token = (string)HttpContext.Current.Session["oauth_token"];// Session["oauth_token"].ToString();
            httpRequest.TokenSecret = (string)HttpContext.Current.Session["oauth_token_secret"];// Session["oauth_token_secret"].ToString();
            string url = "http://api.t.sina.com.cn/statuses/upload.xml?";
            r = httpRequest.RequestWithPicture(url, "status=" + HttpUtility.UrlPathEncode(statusText), pic);
        }
        else
        {
            string url = "http://api.t.sina.com.cn/statuses/update.json?";
            var httpRequest = HttpRequestFactory.CreateHttpRequest(Method.POST);
            httpRequest.Token = (string)HttpContext.Current.Session["oauth_token"];// Session["oauth_token"].ToString();
            httpRequest.TokenSecret = (string)HttpContext.Current.Session["oauth_token_secret"];// Session["oauth_token_secret"].T
            r = httpRequest.Request(url, "status=" + HttpUtility.UrlEncode(statusText));
        }

        return true;
        //Console.Read();
    }

    public static bool SaveRelation()
    {
        if (HttpContext.Current.Session["QQ_user_id"] != null && HttpContext.Current.Session["Sina_user_id"] != null)
        {
            RelationEntity et = new RelationEntity();
            et.App = "QQ";
            et.UserID = (string)HttpContext.Current.Session["QQ_user_id"];
            et.SubApp = "Sina";
            et.SubUserID = (string)HttpContext.Current.Session["Sina_user_id"];
            // RelationEntity et = new RelationEntity();
            Relation.Save(et);
        }
        return true;
    }
}