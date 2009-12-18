using System;
using System.Collections.Generic;
using System.Text;
using System.IO;
namespace com.hooyes.log.core
{
    /// <summary>
    /// Widget 
    /// </summary>
    public class fn
    {
        private string Root = AppDomain.CurrentDomain.BaseDirectory;
        public bool LogToTxt(string msg)
        {
            string fileName = DateTime.Now.ToString("yyyyMM")+".log";
            string fileRoot = "log";
            fileRoot = Path.Combine(Root, fileRoot);
            DirectoryInfo di = new DirectoryInfo(fileRoot);
            if (!di.Exists)
            {
                di.Create();
            }
            fileName = Path.Combine(fileRoot, fileName);
            StreamWriter sw = new StreamWriter(fileName, true);
            string msgFormat = DateTime.Now +" " + msg;
            sw.WriteLine(msgFormat);
            sw.Close();
            return true;
        }
        
    }
}
