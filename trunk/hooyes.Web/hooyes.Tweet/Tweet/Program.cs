using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;

namespace Tweet
{
    class Program
    {
        static void Main(string[] args)
        {
            string statusText = "天灰灰，会不会让我忘了你是谁";
            T.QQ(statusText);
            T.Sina(statusText);

            Console.ReadLine();
            //T.SinaRun();
        }
    }
}
