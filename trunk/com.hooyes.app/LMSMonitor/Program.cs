﻿using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using com.hooyes.lms.Svc.DAL;

namespace LMSMonitor
{
    class Program
    {
        private static NLog.Logger log = NLog.LogManager.GetCurrentClassLogger();
        static void Main(string[] args)
        {
            try
            {
                log.Info("credit");
                Update.Credit();
                log.Info("run");
                Task.Run();
            }
            catch (Exception ex)
            {
                log.FatalException("Main", ex);
            }
        }
    }
}
