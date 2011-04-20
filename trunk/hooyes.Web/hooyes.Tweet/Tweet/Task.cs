using System;
using System.Collections.Generic;
using System.Text;
using OpenTSDK.Tencent.API;
using LeoShi.Soft.OpenSinaAPI;
using System.Web;
using OpenTSDK.Tencent;
using OpenTSDK.Tencent.Objects;
using Tweet.Core;
using System.Xml;

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
               #region  单个用户
               try
               {
                   Dictionary<string, string> Main = new Dictionary<string, string>();
                   Dictionary<string, string> Sub = new Dictionary<string, string>();

                   Main = Dict.Get(rt.App, rt.UserID);
                   Sub = Dict.Get(rt.SubApp, rt.SubUserID);

                   OAuth oauth = new OAuth(appKey, appSecret);
                   oauth.Token = Main["Token"];// //"91c8a7555f694dd3bd7a55178dfa952d";            //Access Token
                   oauth.TokenSecret = Main["TokenSecret"];// "2e01aa675faf5764d59564537f142f51";      //Access Secret

                   Timeline api = new Timeline(oauth);
                   var data = api.GetBroadcast_timeline(PageFlag.First, 0, 5);

                   long maxTimeline = (long)Convert.ToDecimal(Main["MaxTimeline"]);

                   if (data.Tweets.Length > 0)
                   {
                       long maxTimelineTemp = maxTimeline;
                       decimal MaxIdTemp = 0;
                       //Master
                       DictEntity dt = new DictEntity();
                       dt.App = rt.App;
                       dt.Key = "MaxTimeline";
                       dt.UserID = rt.UserID;

                       DictEntity dt2 = new DictEntity();
                       dt2.App = rt.SubApp;
                       dt2.Key = "MaxId";
                       dt2.UserID = rt.SubUserID;

                       for (int i = data.Tweets.Length; i > 0; i--)
                       {
                           try
                           {
                               OpenTSDK.Tencent.Objects.Tweet tw = data.Tweets[i - 1];
                               if (tw.Timestamp > maxTimeline)
                               {
                                   string Token = Sub["Token"];
                                   string TokenSecret = Sub["TokenSecret"];
                                   decimal Tid = Sina(tw.Origtext, Token, TokenSecret);
                                   //dt.Value = tw.Timestamp.ToString();
                                   if (maxTimelineTemp < tw.Timestamp)
                                   {
                                       maxTimelineTemp = tw.Timestamp;
                                   }
                                   if (MaxIdTemp < Tid)
                                   {
                                       MaxIdTemp = Tid;
                                   }
                               }
                           }
                           catch (Exception ex)
                           {
                               Console.WriteLine(ex.Message);
                           }
                       }
                       if (maxTimeline < maxTimelineTemp)
                       {
                           dt.Value = maxTimelineTemp.ToString();
                           Dict.Save(dt);
                       }
                       if (MaxIdTemp > 0)
                       {
                           dt2.Value = MaxIdTemp.ToString();
                           Dict.Save(dt2);
                       }
                   }
               }
               catch (Exception ex)
               {
                   Console.WriteLine(ex.Message);
               }

               #endregion
           }

       }
       public static decimal Sina(string statusText, string Token, string TokenSecret)
       {
           var httpRequest = HttpRequestFactory.CreateHttpRequest(Method.POST);
           httpRequest.Token = Token; 
           httpRequest.TokenSecret = TokenSecret;
           var url = "http://api.t.sina.com.cn/statuses/update.xml?";
           var data = httpRequest.Request(url, "status=" + HttpUtility.UrlEncode(statusText));

           XmlDocument xml = new XmlDocument();
           xml.LoadXml(data);

           XmlNode xn = xml.SelectSingleNode("/status/id");
          
           decimal id = Util.GetXmlNodeValue<decimal>(xn);
           Console.WriteLine("sid:{0}",id);
           return id;
           //Console.Read();
       }
    }
}
