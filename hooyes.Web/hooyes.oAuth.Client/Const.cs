using System;
using System.Collections.Generic;
using System.Text;
using System.Configuration;

namespace hooyes.OAuth.Client
{
    public class Const
    {
        public static string REQUEST_TOKEN
        {
            get
            {
                string rv = ConfigurationManager.AppSettings["oAuth_REQUEST_TOKEN"];
                if (string.IsNullOrEmpty(rv)) 
                {
                    rv = "http://api.t.sina.com.cn/oauth/request_token";
                }
                return rv;
            }
        }
        public static string AUTHORIZE
        {
            get
            {
                string rv = ConfigurationManager.AppSettings["oAuth_AUTHORIZE"];
                if (string.IsNullOrEmpty(rv))
                {
                    rv = "http://api.t.sina.com.cn/oauth/authorize";
                }
                return rv;
            }
        }
        public static string ACCESS_TOKEN
        {
            get
            {
                string rv = ConfigurationManager.AppSettings["oAuth_ACCESS_TOKEN"];
                if (string.IsNullOrEmpty(rv))
                {
                    rv = "http://api.t.sina.com.cn/oauth/access_token";
                }
                return rv;
            }
        }
        public static string ConsumerKey
        {
            get
            {
                string rv = ConfigurationManager.AppSettings["oAuth_ConsumerKey"];
                if (string.IsNullOrEmpty(rv))
                {
                    rv = "3472084219";
                }
                return rv;
            }
        }
        public static string ConsumerSecret
        {
            get
            {
                string rv = ConfigurationManager.AppSettings["oAuth_ConsumerSecret"];
                if (string.IsNullOrEmpty(rv))
                {
                    rv = "54cae7d23876c298eaaf5cb0d14d0cd9";
                }
                return rv;
            }
        }
        public static string CachePrefix
        {
            get
            {
                string rv = ConfigurationManager.AppSettings["oAuth_CachePrefix"];
                if (string.IsNullOrEmpty(rv))
                {
                    rv = "oAuth_";
                }
                return rv;
            }
        }
    }
}
