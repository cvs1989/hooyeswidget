using System;
using System.Collections.Generic;
using System.Text;
using System.Net;
using System.Net.Mail;
using System.Configuration;

namespace com.hooyes.SvrMonitor.API
{
   public class Mail
    {
       private static NLog.Logger log = NLog.LogManager.GetCurrentClassLogger();
       public static void Send(string Addresses,string Subject, string Body)
       {
           string FromAddress = ConfigurationManager.AppSettings.Get("Mail_FromAddress");
           string FromName = ConfigurationManager.AppSettings.Get("Mail_FromName");
           string SmtpUserName = ConfigurationManager.AppSettings.Get("Mail_SmtpUserName");
           string SmtpUserPWD = ConfigurationManager.AppSettings.Get("Mail_SmtpUserPWD");
           string SmtpHost = ConfigurationManager.AppSettings.Get("Mail_SmtpHost");
           int SmtpPort = Convert.ToInt32(ConfigurationManager.AppSettings.Get("Mail_SmtpPort"));
           bool SmtpEnableSsl = Convert.ToBoolean(ConfigurationManager.AppSettings.Get("Mail_SmtpEnableSsl"));
           MailMessage MM = new MailMessage();
           MM.To.Add(Addresses); //收件人地址，可多个用逗号隔开
           MM.Subject = Subject;
           MM.Body = Body;
           MM.From = new MailAddress(FromAddress, FromName);
           SmtpClient mClient = new SmtpClient();
           mClient.Credentials = new NetworkCredential(SmtpUserName, SmtpUserPWD); //用户名密码

           mClient.Port = SmtpPort;
           mClient.Host = SmtpHost; //邮件smtp服务器
           mClient.EnableSsl = SmtpEnableSsl;//是否用SSL加密

           try
           {
               mClient.Send(MM);
           }
           catch (SmtpException ex)
           {
               log.Fatal("{0},{1}", ex.Message, ex.StackTrace);
           }
       }
    }
}
