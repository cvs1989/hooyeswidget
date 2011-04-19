using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using OpenTSDK.Tencent;
using OpenTSDK.Tencent.API;
using LeoShi.Soft.OpenSinaAPI;
using System.Web;
using System.Diagnostics;
using OpenTSDK.Tencent.Objects;

namespace Tweet
{
    public class T
    {
        public static void QQ(string statusText)
        {
            //实例化OAuth对象
            string appKey = "3ab2872742234704925c33dec507f9bb";
            string appSecret = "f6cd03eb8734e8f64b98bef6ce8d546a";
            OAuth oauth = new OAuth(appKey, appSecret);
            oauth.Token = "91c8a7555f694dd3bd7a55178dfa952d";            //Access Token
            oauth.TokenSecret = "2e01aa675faf5764d59564537f142f51";      //Access Secret

            //根据OAuth对象实例化API接口
            Timeline api = new Timeline(oauth);
            var data = api.GetPublicTimeline(0, 10);

            Twitter twitter = new Twitter(oauth);
            //var data2 = twitter.Add("铁盒的钥匙我找不到!沉在盒子里的是你给我的快乐，我很想记得，可是我记不得", @"C:\Users\hooyes\Pictures\58.gif", "127.0.0.1");
            var data2 = twitter.Add(statusText, "207.6.14.22");
            if (data2.Ret == 0)
            {
                //删除刚发的微博
                Console.WriteLine("发布成功");
            }
            else
            {
                Console.WriteLine("发布失败");
            }

            //Console.ReadLine();
        }
        public static void Sina(string statusText)
        {
            var httpRequest = HttpRequestFactory.CreateHttpRequest(Method.POST);
            httpRequest.Token = "e7ddeb2263a81443cdf2dc7c4cb9fda2";// Session["oauth_token"].ToString();
            httpRequest.TokenSecret = "39df48c9cc65369a7cfe75cde2abf2b8";// Session["oauth_token_secret"].ToString();
            var url = "http://api.t.sina.com.cn/statuses/update.json?";
            var r= httpRequest.Request(url, "status=" + HttpUtility.UrlEncode(statusText));

            Console.Write("OK");
            //Console.Read();
        }

        public static void QQTimeLine()
        {
            //实例化OAuth对象
            string appKey = "3ab2872742234704925c33dec507f9bb";
            string appSecret = "f6cd03eb8734e8f64b98bef6ce8d546a";
            OAuth oauth = new OAuth(appKey, appSecret);
            oauth.Token = "91c8a7555f694dd3bd7a55178dfa952d";            //Access Token
            oauth.TokenSecret = "2e01aa675faf5764d59564537f142f51";      //Access Secret

            //根据OAuth对象实例化API接口
            Timeline api = new Timeline(oauth);
            var data = api.GetBroadcast_timeline(PageFlag.First, 0, 1);

            long maxTimeline = db.MaxTimeline("hooyes");
            if (data.Tweets.Length > 0)
            {
                if (data.Tweets[0].Timestamp > maxTimeline)
                {
                    Sina(data.Tweets[0].Origtext);
                    maxTimeline=data.Tweets[0].Timestamp;
                    db.MaxTimeline("hooyes", maxTimeline);
                }
            }
            
        }
        public static void RunSina()
        {
            var httpRequest = HttpRequestFactory.CreateHttpRequest(Method.GET) as HttpGet;
            httpRequest.GetRequestToken();
            string url = httpRequest.GetAuthorizationUrl();
            Process.Start(url);
            Console.Write("授权码：");
            string verifier = Console.ReadLine();
            httpRequest.Verifier = verifier;
            httpRequest.GetAccessToken();
            Console.WriteLine("获取Access Token成功。值如下：");
            Console.WriteLine("TokenKey={0}", httpRequest.Token);
            Console.WriteLine("TokenSecret={0}", httpRequest.TokenSecret);
           
        }


        public static void RunQQ(string appKey, string appSecret)
        {
            OAuth oauth = new OAuth(appKey, appSecret);

            //获取请求Token
            if (oauth.GetRequestToken(null))
            {
                Console.WriteLine("获取Request Token成功。值如下：");
                Console.WriteLine("TokenKey={0}", oauth.Token);
                Console.WriteLine("TokenSecret={0}", oauth.TokenSecret);
                Console.WriteLine("正在请求授权, 请在授权后,将页面提示的授权码码输入下面并继续……");
                Process.Start("https://open.t.qq.com/cgi-bin/authorize?oauth_token=" + oauth.Token);
                Console.Write("授权码：");
                string verifier = Console.ReadLine();
                string name;
                if (oauth.GetAccessToken(verifier, out name))
                {
                    Console.WriteLine("获取Access Token成功。值如下：");
                    Console.WriteLine("TokenKey={0}", oauth.Token);
                    Console.WriteLine("TokenSecret={0}", oauth.TokenSecret);
                    Console.WriteLine("微博帐户名={0}", name);
                }
                else
                {
                    Console.WriteLine("获取Access Token时出错，错误信息： {0}", oauth.LastError);
                }
            }
            else
            {
                Console.WriteLine("获取Request Token时出错，错误信息： {0}", oauth.LastError);
            }

            if (oauth.LastError != null)
            {
                Console.Read();
                return;
            }
            Twitter twitter = new Twitter(oauth);
            var data = twitter.Add("#TXOpenTSDK# 测试发带图片的微博....", @"C:\Users\hooyes\Pictures\58.gif", "127.0.0.1");
            if (data.Ret == 0)
            {
                //删除刚发的微博
                data = twitter.Delete(((TweetOperateResult)data).TweetId);
            }
            Console.WriteLine(data.Ret);
            Console.Read();
        }
    }
}
