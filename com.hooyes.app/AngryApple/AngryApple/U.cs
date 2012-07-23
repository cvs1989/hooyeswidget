using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.IO;

namespace com.hooyes.app.AngryApple
{
    public class U
    {
        public static decimal CreateSN()
        {
            string SN = DateTime.Now.ToString("yyyyMMddhhmmss");
            return Convert.ToDecimal(SN);
        }
        public static string N2S(int N)
        {
            string r = string.Empty;
            switch (N)
            {
                case 101:
                    r = "未经授权";
                    break;
                case 0:
                    r = "登录成功";
                    break;
                case 1:
                    r = "已学完";
                    break;
                case 200:
                    r = "正在学习中";
                    break;
                case 202:
                    r = "序号或身份证错";
                    break;
                default:
                    break;
            }
            return r;
        }
    }
}
