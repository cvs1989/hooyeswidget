using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.IO;
using System.Diagnostics;

namespace com.hooyes.app.AngryApple
{
    public class log
    {
        public static void Info(string Message)
        {
            StackTrace st = new StackTrace(true); 
            Write(st,Message);
        }
        public static void Info(string Message, params object[] arg)
        {
            StackTrace st = new StackTrace(true); 
            Message = string.Format(Message, arg);
            Write(st, Message);
        }

        private static void Write(StackTrace st,string Message)
        {
            string File = Path.Combine(AppDomain.CurrentDomain.BaseDirectory, "log.log");
            StreamWriter sw = new StreamWriter(File, true);
            string clsName = st.GetFrame(1).GetMethod().Name;
            Message = string.Format("{0} {1} {2}", DateTime.Now, clsName, Message);
            sw.WriteLine(Message);
            sw.Close();
            sw.Dispose();
        }
    }
}
