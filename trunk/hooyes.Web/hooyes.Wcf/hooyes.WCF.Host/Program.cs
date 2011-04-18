using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.ServiceModel;
namespace hooyes.WCF.Host
{
    class Program
    {
        static void Main(string[] args)
        {
            SinaOpen();
        }
        static void HostOpen()
        {
            ServiceHost host = new ServiceHost(typeof(WCF.Service.Sv));
            host.Open();
            Console.WriteLine("WCF Service is running");
            Console.ReadKey();
            host.Close();
        }
        static void SinaOpen()
        {
            ServiceHost host = new ServiceHost(typeof(WCF.Service.SinaApiService));
            host.Open();
            Console.WriteLine("t service is running...");
            Console.ReadKey();
            host.Close();
        }
    }
}
