using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;


namespace UserClient
{
    class Program
    {
        static void Main(string[] args)
        {
            using (WcfAutoService.AutoServiceClient client = new WcfAutoService.AutoServiceClient())
            {
                string result = client.GetData();
                Console.WriteLine("Wcf调用结果是：{0}",result);
                Console.ReadKey();
            }
        }
    }
}
