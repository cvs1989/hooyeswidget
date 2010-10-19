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
            HostOpen();
        }
        static void HostOpen()
        {
            ServiceHost host = new ServiceHost(typeof(WCF.Service.Sv));
            host.Open();
            Console.WriteLine("service");
            Console.ReadKey();
            host.Close();
        }
    }
}
