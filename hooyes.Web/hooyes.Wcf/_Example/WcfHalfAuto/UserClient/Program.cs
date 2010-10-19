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
            using (WcfHalfServices.HalfServiceClient client = new WcfHalfServices.HalfServiceClient())
            {
                string result = client.GetData();
                Console.WriteLine("Wcf调用结果是：{0}", result);
                Console.ReadKey();
            }
        }
    }
}
