using System;
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
        /// 注册报警邮件收件人
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
       
        /// <summary>
        /// 100 mins toll free + 950 1st month fee free
        /// </summary>
        public static string PromotionExtraCreditFormalDesc
        {
            get
            {
                string config = ConfigurationManager.AppSettings.Get("PromotionExtraCreditFormalDesc");
                if (string.IsNullOrEmpty(config))
                {
                    config = "100 mins toll free + 950 1st month fee free";
                }
                return config;
            }
        }
        /// <summary>
        /// 100分钟免费呼叫
        /// </summary>
        public static decimal PromotionExtraCreditTrial
        {
            get
            {
                decimal ivalue = 1.99m;
                string config = ConfigurationManager.AppSettings.Get("PromotionExtraCreditTrial");
                if (!string.IsNullOrEmpty(config))
                {
                    ivalue = Convert.ToDecimal(config);
                }
                return ivalue;
            }
        }
        /// <summary>
        /// 100 mins toll free
        /// </summary>
        public static string PromotionExtraCreditTrialDesc
        {
            get
            {
                string config = ConfigurationManager.AppSettings.Get("PromotionExtraCreditTrialDesc");
                if (string.IsNullOrEmpty(config))
                {
                    config = "100 mins toll free";
                }
                return config;
            }
        }
        /// <summary>
        /// 推荐返给被推荐人的积分数
        /// </summary>
        public static int RefferPoints
        {
            get
            {
                int ivalue = 199;
                string config = ConfigurationManager.AppSettings.Get("RefferPoints");
                if (!string.IsNullOrEmpty(config))
                {
                    ivalue = Convert.ToInt32(config);
                }
                return ivalue;
            }
        }
        /// <summary>
        /// 每天最大注册数限制[默认值100],CC不受此数限制
        /// </summary>
        public static int DayMaxSignup
        {
            get
            {
                int ivalue = 100;
                string config = ConfigurationManager.AppSettings.Get("DayMaxSignup");
                if (!string.IsNullOrEmpty(config))
                {
                    ivalue = Convert.ToInt32(config);
                }
                return ivalue;
            }
        }
        /// <summary>
        /// 时间偏移量
        /// </summary>
        public static double TimeOffset
        {
            get
            {
                double ivalue = 0;
                string config = ConfigurationManager.AppSettings.Get("TimeOffset");
                if (!string.IsNullOrEmpty(config))
                {
                    ivalue = Convert.ToDouble(config);
                }
                return ivalue;
            }
        }
        /// <summary>
        /// 默认IVR
        /// </summary>
        public static string DefaultIVR
        {
            get
            {
                string config = ConfigurationManager.AppSettings.Get("DefaultIVR");
                if (string.IsNullOrEmpty(config))
                {
                    config = "zh_CN";
                }
                return config;
            }
        }
        /// <summary>
        /// acctid 默认语言
        /// </summary>
        public static string DefaultAcctLanguage
        {
            get
            {
                string config = ConfigurationManager.AppSettings.Get("DefaultAcctLanguage");
                if (string.IsNullOrEmpty(config))
                {
                    config = "zh_CN";
                }
                return config;
            }
        }
    }
}
