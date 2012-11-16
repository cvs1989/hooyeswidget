using System;
using System.Configuration;
using System.Data;
using System.Threading;
using com.hooyes.SvrMonitor.Model;

namespace com.hooyes.SvrMonitor.DAL
{
    public class Task
    {
        private static NLog.Logger log = NLog.LogManager.GetCurrentClassLogger();
        public static R CheckDB()
        {
            var r = new R();
            try
            {
                string SQL = ConfigurationManager.AppSettings.Get("SQL_Check"); 
                var obj = SqlHelper.ExecuteScalar(SqlHelper.Local, CommandType.Text, SQL);
                log.Info(obj);
            }
            catch (Exception ex)
            {
                string ToAddress = ConfigurationManager.AppSettings.Get("Mail_ToAddress");
                API.Mail.Send(ToAddress, ex.Message, ex.StackTrace);
                log.Fatal("{0},{1}", ex.Message, ex.StackTrace);
            }
            return r;
        }
        public static void Run()
        {
            CheckDB();
        }
    }
}
