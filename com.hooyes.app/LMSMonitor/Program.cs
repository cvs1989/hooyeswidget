using System;
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
                if (args.Length > 0)
                {
                    string cmd = args[0].ToLower();
                    switch (cmd)
                    {
                        case "-credit":
                            log.Info("credit");
                            Update.Credit();
                            break;
                        case "-commit":
                            log.Info("commit");
                            Task.Run();
                            break;
                        default:
                            log.Info("cmd error");
                            break;
                    }
                }
                else
                {
                    log.Info("no args");
                }
                
            }
            catch (Exception ex)
            {
                log.FatalException("Main", ex);
            }
        }
    }
}
