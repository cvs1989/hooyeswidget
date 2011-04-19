using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using OpenTSDK.Tencent;
using OpenTSDK.Tencent.API;
using OpenTSDK.Tencent.Objects;
using System.Xml;

namespace OpenTSDK.Tester
{
    class Program
    {
        static void Main(string[] args)
        {
            Console.WriteLine("----------------------------");
            //Console.Write("请输入您的App_Key:");
            //Console.WriteLine
            string appKey = "3ab2872742234704925c33dec507f9bb";// Console.ReadLine();
           // Console.Write("请输入您的App_Secret:");
            string appSecret = "f6cd03eb8734e8f64b98bef6ce8d546a";// Console.ReadLine();
            //Tencent.Run(appKey, appSecret);
            Tencent.Test();
        }
    }
}
