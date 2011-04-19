using System;
using System.Collections.Generic;
using System.Text;
using System.IO;
using com.hooyes.widget;
using LeoShi.Soft.OpenSinaAPI;
using System.Web;
using OpenTSDK.Tencent.API;
using OpenTSDK.Tencent;
using System.Diagnostics;
namespace com.hooyes.log.core
{
    /// <summary>
    /// Widget Create By hooyes .
    /// </summary>
    public class fn
    {
        private string Root = AppDomain.CurrentDomain.BaseDirectory;
        public bool LogToTxt(string msg,string pic)
        {
            string fileName = DateTime.Now.ToString("yyyyMM")+".log";
            string fileRoot = "log";
            fileRoot = Path.Combine(Root, fileRoot);
            DirectoryInfo di = new DirectoryInfo(fileRoot);
            if (!di.Exists)
            {
                di.Create();
            }
            fileName = Path.Combine(fileRoot, fileName);
            StreamWriter sw = new StreamWriter(fileName, true);
            string msgFormat = DateTime.Now +" " + msg;
            sw.WriteLine(msgFormat);
            sw.Close();
            return true;
        }
        public bool LogToGoogle(string msg, string pic)
        {
            try
            {
                string Data = "content=" + msg;
                http.PostDataToUrl(Data, "http://hooyeslog.appspot.com/api");
                return true;
            }
            catch
            {
                return false;
            }
        }
        public bool QQ(string statusText, string pic)
        {
            //实例化OAuth对象
            string appKey = "3ab2872742234704925c33dec507f9bb";
            string appSecret = "f6cd03eb8734e8f64b98bef6ce8d546a";
            OAuth oauth = new OAuth(appKey, appSecret);
            oauth.Token = "91c8a7555f694dd3bd7a55178dfa952d";            //Access Token
            oauth.TokenSecret = "2e01aa675faf5764d59564537f142f51";      //Access Secret

            //根据OAuth对象实例化API接口
            //Timeline api = new Timeline(oauth);
            //var data = api.GetPublicTimeline(0, 10);

            Twitter twitter = new Twitter(oauth);
            var data2 = twitter.Add(statusText,pic, "8.8.8.8");
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
            string r=string.Empty;
            if (!string.IsNullOrEmpty(pic))
            {
                var httpRequest = HttpRequestFactory.CreateHttpRequest(Method.POST) as HttpPost;
                httpRequest.Token = "e7ddeb2263a81443cdf2dc7c4cb9fda2";// Session["oauth_token"].ToString();
                httpRequest.TokenSecret = "39df48c9cc65369a7cfe75cde2abf2b8";// Session["oauth_token_secret"].ToString();
                string url = "http://api.t.sina.com.cn/statuses/upload.xml?";
                r = httpRequest.RequestWithPicture(url, "status=" + HttpUtility.UrlPathEncode(statusText), pic);
            }
            else
            {
                string url = "http://api.t.sina.com.cn/statuses/update.json?";
                var httpRequest = HttpRequestFactory.CreateHttpRequest(Method.POST);
                httpRequest.Token = "e7ddeb2263a81443cdf2dc7c4cb9fda2";// Session["oauth_token"].ToString();
                httpRequest.TokenSecret = "39df48c9cc65369a7cfe75cde2abf2b8";// Session["oauth_token_secret"].ToString();
                r = httpRequest.Request(url, "status=" + HttpUtility.UrlEncode(statusText));
            }

            return true;
            //Console.Read();
        }

        public bool Empty(string msg, string pic)
        {
            return true;
        }
         
    }
}
