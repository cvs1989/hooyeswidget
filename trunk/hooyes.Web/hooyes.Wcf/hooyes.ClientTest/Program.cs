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
           // hooyes.WCF.Service.SinaApiService sa = new WCF.Service.SinaApiService("hooyes@sina.com", "x");
           //string s= sa.statuses_update("xml","我打算建一个新浪微博的WCF怎么样？");
           ////s = sa.statuses_update("xml", "别想太多未来的问题");

            hooyes.WCF.Service.Sv sa = new WCF.Service.Sv();

           // //var httpRequest = sa.CreateHttpRequest(
           // httpRequest.GetRequestToken();
           // string url = httpRequest.GetAuthorizationUrl();
           //Console.Write(s);

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
