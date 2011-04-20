using System;
using System.Collections.Generic;
using System.Text;
using OpenTSDK.Tencent.API;
using LeoShi.Soft.OpenSinaAPI;
using System.Web;
using OpenTSDK.Tencent;
using OpenTSDK.Tencent.Objects;
using Tweet.Core;

namespace Tweet
{
   public  class Task
    {
       public static void Run()
       {
           //实例化OAuth对象
           string appKey = "e40ccbe09c4945e08dc255a98fea1188";
           string appSecret = "9f786428568a9b44036d955b7c6b9196";

           List<RelationEntity> lt = Relation.Get();

           foreach (RelationEntity rt in lt)
           {
               Dictionary<string, string> Main = new Dictionary<string, string>();
               Dictionary<string, string> Sub = new Dictionary<string, string>();

               Main = Dict.Get(rt.App, rt.UserID);
               Sub = Dict.Get(rt.SubApp, rt.SubUserID);
              
               OAuth oauth = new OAuth(appKey, appSecret);
               oauth.Token = Main["Token"];// //"91c8a7555f694dd3bd7a55178dfa952d";            //Access Token
               oauth.TokenSecret =Main["TokenSecret"];// "2e01aa675faf5764d59564537f142f51";      //Access Secret
               
               Timeline api = new Timeline(oauth);
               var data = api.GetBroadcast_timeline(PageFlag.First, 0, 1);
               
               long maxTimeline =(long) Convert.ToDecimal(Main["MaxTimeline"]);
               if (data.Tweets.Length > 0)
               {
                   if (data.Tweets[0].Timestamp > maxTimeline)
                   {
                       string Token = Sub["Token"];
                       string TokenSecret = Sub["TokenSecret"];

                       Sina(data.Tweets[0].Origtext, Token, TokenSecret);
                       maxTimeline = data.Tweets[0].Timestamp;
                       //db.MaxTimeline("hooyes", maxTimeline);
                   }
               }
           }

       }
       public static void Sina(string statusText, string Token, string TokenSecret)
       {
           var httpRequest = HttpRequestFactory.CreateHttpRequest(Method.POST);
           httpRequest.Token = Token; //"e7ddeb2263a81443cdf2dc7c4cb9fda2";// Session["oauth_token"].ToString();
           httpRequest.TokenSecret = TokenSecret;// "39df48c9cc65369a7cfe75cde2abf2b8";// Session["oauth_token_secret"].ToString();
           var url = "http://api.t.sina.com.cn/statuses/update.json?";
           var r = httpRequest.Request(url, "status=" + HttpUtility.UrlEncode(statusText));

           Console.Write("OK");
           //Console.Read();
       }
    }
}
