using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;

using System.ServiceModel;

namespace HalfServiceHost
{
    class Program
    {
        static void Main(string[] args)
        {
            using (ServiceHost host=new ServiceHost(typeof(WcfHalfService.HalfService)))
            {
                host.Open();
                Console.WriteLine("WCF半自动方法服务已启动，按任意键退出！");
                Console.ReadKey();
                host.Close();
            }
        }
    }
}
