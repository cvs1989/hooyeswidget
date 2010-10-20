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
            hooyes.WCF.Service.SinaApiService sa = new WCF.Service.SinaApiService();
           string s= sa.statuses_update("hooyes@sina.com", "rgbjyss", "json", "ok");
           Console.Write(s);

           Console.Read();
        }
    }
}
