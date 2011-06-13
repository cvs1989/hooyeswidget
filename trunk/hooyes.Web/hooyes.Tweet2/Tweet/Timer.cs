using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Timers;
using System.Configuration;

namespace Tweet
{
    public class sTimer
    {
        public sTimer()
        {

        }
        public void TT(object o, ElapsedEventArgs e)
        {
            //
            Task.Run();
            
        }
        public void Clock(object o, ElapsedEventArgs e)
        {
            Console.Title = e.SignalTime.ToString();
        }
        public static void Run()
        {
            double Interval=1;
            if (!string.IsNullOrEmpty(ConfigurationManager.AppSettings.Get("Timer")))
            {
                Interval = Convert.ToDouble(ConfigurationManager.AppSettings.Get("Timer"));
            }
            //Log.Info("run");
            sTimer s = new sTimer();
            Timer aTimer = new Timer();

            aTimer.Elapsed += new ElapsedEventHandler(s.TT);
            aTimer.Elapsed += new ElapsedEventHandler(s.Clock);
            aTimer.Interval = 1000*Interval;
            aTimer.Enabled = true;
            Console.WriteLine("-------------------------------");
            Console.WriteLine(" Schedule is now runing...V2");
            Console.WriteLine("-------------------------------");
           // Console.WriteLine("Down schedule " + Constant.scheduleDown);
           // Console.WriteLine("Up schedule " + Constant.scheduleUp);
           
            //Console.ReadLine();
            HoldOn();


        }
        protected static void HoldOn()
        {
            ConsoleKeyInfo cki = Console.ReadKey();
            if (cki.Key == ConsoleKey.Escape)
            {
                Console.WriteLine("Press Enter to exit");
            }
            else
            {
                Console.WriteLine("Press Esc To Exit");
                HoldOn();
            }
        }
    }
}
