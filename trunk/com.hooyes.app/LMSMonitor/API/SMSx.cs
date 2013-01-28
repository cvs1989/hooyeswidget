using System;
using System.Collections.Generic;
using System.Text;
using com.hooyes.lms.LMSMonitor.SMS;
using com.hooyes.lms.Svc.Model;

namespace com.hooyes.lms.API
{
    public class SMSx
    {
        private static NLog.Logger log = NLog.LogManager.GetCurrentClassLogger();
        public static R PushToPhone(string phone, string text)
        {
            var r = new R();
            try
            {
                com.hooyes.lms.LMSMonitor.SMS.SendMessageSoapClient client = new SendMessageSoapClient();
                MySoapHeader header = new MySoapHeader();
                header.PassWord = C.SMSTOKEN;
                string s = client.sendmsg(header, phone, "1", "7", text, "远程学员");
                log.Info(s);
                r.Code = 0;
                r.Message = s;
            }
            catch (Exception ex)
            {
                r.Code = 300;
                r.Message = ex.Message;
                log.Warn("{0},{1}", ex.Message, ex.Source);
            }
            return r;
        }
    }
}
