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
                            log.Info("credit.start");
                            Update.Credit();
                            log.Info("credit.end");
                            break;
                        case "-commit":
                            log.Info("commit.start");
                            Task.Run();
                            log.Info("commit.end");
                            break;
                        case "-sms":
                            log.Info("sms.start");
                            int Rows = Convert.ToInt32(args[1]);
                            Task.RunSMS(0,0,Rows);
                            log.Info("sms.end");
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
