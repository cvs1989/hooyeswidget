using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;

using System.ServiceModel;
using System.ServiceModel.Description;

namespace WcfManualHost
{
    class Program
    {
        static void Main(string[] args)
        {
            //定义服务
            using (ServiceHost host = new ServiceHost(typeof(WcfManualService.ManulService)))
            {
                //定义绑定
                System.ServiceModel.Channels.Binding httpBinding = new BasicHttpBinding();
                //定义终结点
                host.AddServiceEndpoint(typeof(WcfManualService.IManulService), httpBinding, "http://localhost:8002/");
                if (host.Description.Behaviors.Find<System.ServiceModel.Description.ServiceMetadataBehavior>() == null)
                {
                    //定义行为
                    ServiceMetadataBehavior behavior = new ServiceMetadataBehavior();
                    behavior.HttpGetEnabled = true;
                    behavior.HttpGetUrl = new Uri("http://localhost:8002/ManualService");
                    host.Description.Behaviors.Add(behavior);

                    host.Opened += delegate
                    {
                        Console.WriteLine("WCF手工服务已运行，按任意建退出！");
                    };
                    //运行
                    host.Open();
                    Console.ReadKey();
                    //关闭
                    host.Close();
                }
            }
        }
    }
}
