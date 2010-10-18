using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;

using System.ServiceModel;
using System.ServiceModel.Channels;
using System.ServiceModel.Description;


namespace UserClient
{
    class Program
    {
        static void Main(string[] args)
        {
            /*
            编写服务客户端方式
            */
            //地址为服务器EndPoint地址，而不是元数据地址。
            string address = "http://localhost:8002/";
            ChannelFactory<IManulService> channel =
                new ChannelFactory<IManulService>(
                    new BasicHttpBinding(),
                    new EndpointAddress(
                        new Uri(address)));
            IManulService proxy = channel.CreateChannel();
            Console.WriteLine("WCF调用结果为：{0}", proxy.GetData());
            
            Console.ReadKey();
        }

        /// <summary>
        /// 编写服务客户端方式。需要定义契约。
        /// </summary>
        static void UseMa()
        {

            //地址为服务器EndPoint地址，而不是元数据地址。
            string address = "http://localhost:8002/";
            ChannelFactory<IManulService> factory =
                new ChannelFactory<IManulService>(
                    new BasicHttpBinding(),
                    new EndpointAddress(
                        new Uri(address)));
            IManulService proxy = factory.CreateChannel();
            Console.WriteLine("WCF调用结果为：{0}", proxy.GetData());
        }

        static void UseWcf()
        {
            /*
             动态下载服务元数据
             */
            MetadataExchangeClient metaExchangeClient =
                new MetadataExchangeClient(
                    new Uri("http://localhost:8002/ManualService"),
                    MetadataExchangeClientMode.HttpGet
                );
            //下载元数据
            MetadataSet metadataSet = metaExchangeClient.GetMetadata();
            WsdlImporter importer = new WsdlImporter(metadataSet);
            ServiceEndpointCollection endpointCollection = importer.ImportAllEndpoints();
            IManulService manulProxy = null;
            foreach (ServiceEndpoint endPointItem in endpointCollection)
            {
                manulProxy = new ChannelFactory<IManulService>(
                    endPointItem.Binding,
                    endPointItem.Address
                    ).CreateChannel();
                ((IChannel)manulProxy).Open();
                Console.WriteLine("WCF调用结果为：{0}",
                    manulProxy.GetData());
                ((IChannel)manulProxy).Close();
            }
        }


    }
}
