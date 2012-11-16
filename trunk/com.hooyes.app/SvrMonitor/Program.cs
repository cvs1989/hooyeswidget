using System;
using System.Collections.Generic;
using System.Text;

namespace com.hooyes.SvrMonitor
{
    class Program
    {
        private static NLog.Logger log = NLog.LogManager.GetCurrentClassLogger();
        static void Main(string[] args)
        {
            log.Info("Check Start...");
            DAL.Task.CheckDB();
            log.Info("Check End...");
        }
    }
}
