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
using NLog;

namespace Tweet
{
   public  class Task
    {
       private static Logger log = LogManager.GetCurrentClassLogger();
       public static void RunTestLog()
       {
           //try
           //{
           //    SqlHelper.ExecuteNonQuery("FIEFE", System.Data.CommandType.Text, "feij");
           //}
           //catch (Exception ex)
           //{
           //    log.Warn("{0},{1}",ex.Message, ex.StackTrace);
           //}
       }
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
                   //各取数据出来
                   long maxTimeline = Main.ContainsKey("MaxTimeline") ? (long)Convert.ToDecimal(Main["MaxTimeline"]) : 0;
                   decimal MaxId = Sub.ContainsKey("MaxId") ? Convert.ToDecimal(Sub["MaxId"]) : 0;
                   var data = api.GetBroadcast_timeline(PageFlag.First, 0, Constant.QQRequestNum);
                   XmlDocument sinaT = Task.GetSinaUserTimeline(Sub["Token"], Sub["TokenSecret"], MaxId.ToString());

                   long maxTimelineTemp = maxTimeline;
                   decimal MaxIdTemp = 0;

                   #region QQ to Sina
                   

                   if (data.Tweets.Length > 0)
                   {
                       
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
                   #endregion

                   #region Sina TO QQ

                   if (sinaT != null)
                   {
                       long maxTimelineTemp2 = maxTimelineTemp;
                       decimal MaxIdTemp2 = MaxIdTemp;
                       XmlNodeList x = sinaT.SelectNodes("/statuses/status");

                       DictEntity dt = new DictEntity();
                       dt.App = rt.App;
                       dt.Key = "MaxTimeline";
                       dt.UserID = rt.UserID;

                       DictEntity dt2 = new DictEntity();
                       dt2.App = rt.SubApp;
                       dt2.Key = "MaxId";
                       dt2.UserID = rt.SubUserID;

                       foreach (XmlNode xn in x)
                       {
                           decimal id = Util.GetXmlNodeValue<decimal>(xn.SelectSingleNode("id"));
                           string text = Util.GetXmlNodeValue<string>(xn.SelectSingleNode("text")); ;

                           TweetOperateResult QQresult = Task.QQ(text, Main["Token"], Main["TokenSecret"]);

                           if (maxTimelineTemp2 < QQresult.Timestamp)
                           {
                               maxTimelineTemp2 = QQresult.Timestamp;
                           }
                           if (MaxIdTemp2 < (long)id)
                           {
                               MaxIdTemp2 = id;
                           }
                           

                       }
                       if (maxTimelineTemp2 > maxTimelineTemp)
                       {
                           dt.Value = maxTimelineTemp2.ToString();
                           Dict.Save(dt);
                       }
                       if (MaxIdTemp2 > MaxIdTemp)
                       {
                           dt2.Value = MaxIdTemp2.ToString();
                           Dict.Save(dt2);
                       }
                   }
                   #endregion
               }
               catch (Exception ex)
               {
                   Console.WriteLine(ex.Message);
                   log.Warn("{0},{1}", ex.Message, ex.StackTrace);
                   
               }

               #endregion
           }

       }
       public static TweetOperateResult QQ(string statusText, string Token, string TokenSecret)
       {
           //实例化OAuth对象
           string appKey = "e40ccbe09c4945e08dc255a98fea1188";
           string appSecret = "9f786428568a9b44036d955b7c6b9196";
           OAuth oauth = new OAuth(appKey, appSecret);
           oauth.Token = Token;            //Access Token
           oauth.TokenSecret = TokenSecret;      //Access Secret

          

           Twitter twitter = new Twitter(oauth);
           //var data2 = twitter.Add("铁盒的钥匙我找不到!沉在盒子里的是你给我的快乐，我很想记得，可是我记不得", @"C:\Users\hooyes\Pictures\58.gif", "127.0.0.1");
           var data2 = twitter.Add(statusText, "207.6.14.22");
           if (data2.Ret == 0)
           {
               //删除刚发的微博
               Console.WriteLine("Q:{0}", data2.Timestamp);
           }
           else
           {
               Console.WriteLine("发布失败");
           }
           return data2;

           //Console.ReadLine();
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
       public static XmlDocument GetSinaUserTimeline(string Token, string TokenSecret, string since_id)
       {
           XmlDocument xml = new XmlDocument();
           string url = "http://api.t.sina.com.cn/statuses/user_timeline.xml";
           var httpRequest = HttpRequestFactory.CreateHttpRequest(Method.POST);
           httpRequest.Token = Token;
           httpRequest.TokenSecret = TokenSecret;
           
           var data = httpRequest.Request(url, "since_id=" + since_id);

        
           xml.LoadXml(data);

           return xml;
       }
    }
}
