using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using hooyes.Core;
using hooyes.Core.Mvc;


namespace hooyes.Core.Mvc
{
    public static class CustomExtends
    {
        /// <summary>
        /// 转成Hash MD5
        /// </summary>
        /// <param name="s"></param>
        /// <returns></returns>
        public static string ToMD5(this string s)
        {
            return System.Web.Security.FormsAuthentication.HashPasswordForStoringInConfigFile(s, "MD5").ToLower();
        }
       
    }
    public static class ControllerExtensions
    {
        public static CodeResult Code(this System.Web.Mvc.Controller Contrl,string s)
        {
            return new CodeResult();
        }
    }
}
