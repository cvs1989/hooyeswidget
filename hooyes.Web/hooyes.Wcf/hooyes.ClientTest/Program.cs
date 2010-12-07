using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;

namespace hooyes.ClientTest
{
    class Program
    {
        static void Main(string[] args)
        {
            hooyes.WCF.Service.SinaApiService sa = new WCF.Service.SinaApiService("hooyes@sina.com", "rgbjyss");
           string s= sa.statuses_update("xml","腾讯的客服太搞了，瞎送我一月会员");
           s = sa.statuses_update("xml", "别想太多未来的问题");
           Console.Write(s);

           Console.Read();
        }
        static void Test1(string[] args)
        {
            if (args.Length > 0)
            {
                hooyes.WCF.Service.SinaApiService sa = new WCF.Service.SinaApiService();

               

            }
        }
    }
}
