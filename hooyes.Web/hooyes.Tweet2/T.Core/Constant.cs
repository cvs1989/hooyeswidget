﻿using System;
using System.Collections.Generic;
using System.Text;
using System.Configuration;

namespace Tweet.Core
{
    /// <summary>
    /// 常量 及可配置有参数
    /// </summary>
    public class Constant
    {
        
        /// <summary>
        /// 回调的域名
        /// </summary>
        public static string AppCallBackDomain
        {
            //= "hqiu@italkbb.com";
            get
            {
                string config = ConfigurationManager.AppSettings.Get("AppCallBackDomain");
                if (string.IsNullOrEmpty(config))
                {
                    config = "http://t.hooyes.com";
                }
                return config;
            }
        }

        public static int QQRequestNum
        {
            get
            {
                int ivalue = 2;
                string config = ConfigurationManager.AppSettings.Get("QQRequestNum");
                if (!string.IsNullOrEmpty(config))
                {
                    ivalue = Convert.ToInt32(config);
                }
                return ivalue;
            }
        }
       
        /// <summary>
        /// 图片临时路径 E:\
        /// </summary>
        public static string ImagesTempRoot
        {
            get
            {
                string config = ConfigurationManager.AppSettings.Get("ImagesTempRoot");
                if (string.IsNullOrEmpty(config))
                {
                    config = "E:\\";
                }
                return config;
            }
        }

        public static string app_key_sina
        {
            get
            {
                string config = ConfigurationManager.AppSettings.Get("app_key_sina");
                if (string.IsNullOrEmpty(config))
                {
                    config = "1910711819";
                }
                return config;
            }
        }

        public static string app_secret_sina
        {
            get
            {
                string config = ConfigurationManager.AppSettings.Get("app_secret_sina");
                if (string.IsNullOrEmpty(config))
                {
                    config = "8a6914d6c4634c211a1dce7a3fa057a4";
                }
                return config;
            }
        }

        public static string app_key_QQ
        {
            get
            {
                string config = ConfigurationManager.AppSettings.Get("app_key_QQ");
                if (string.IsNullOrEmpty(config))
                {
                    config = "3ab2872742234704925c33dec507f9bb";
                }
                return config;
            }
        }

        public static string app_secret_QQ
        {
            get
            {
                string config = ConfigurationManager.AppSettings.Get("app_secret_QQ");
                if (string.IsNullOrEmpty(config))
                {
                    config = "f6cd03eb8734e8f64b98bef6ce8d546a";
                }
                return config;
            }
        }
    }
}
