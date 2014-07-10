using System;
using System.Collections.Generic;
using System.Text;
using System.Configuration;
using System.IO;

namespace BackupFiles
{
    public class Task
    {
        private static string SourcePath = ConfigurationManager.AppSettings.Get("SourcePath");
        private static string TargetPath = ConfigurationManager.AppSettings.Get("TargetPath");
        private static string FileExtension = ConfigurationManager.AppSettings.Get("FileExtension");
        private static int FilesCount = Convert.ToInt32(ConfigurationManager.AppSettings.Get("FilesCount"));
        private static string AppRoot = AppDomain.CurrentDomain.BaseDirectory;
        public static void Copy()
        {

            DateTime StartDatetime = GetStartDatetime();
            var SDi = new DirectoryInfo(SourcePath);
            var Files = SDi.GetFiles(FileExtension);
            if (Files.Length > 0)
            {
                Array.Sort<FileInfo>(Files, new FileLastTimeComparer());
                var f = Files[0];
                if (f.LastWriteTime > StartDatetime)
                {
                    var TargerName = Path.Combine(TargetPath, f.Name);
                    f.CopyTo(TargerName, true);
                    WriteStartDatetime(f.LastWriteTime);
                }
            }
        }

        public static void Clear()
        {
            var TDi = new DirectoryInfo(TargetPath);
            var Files = TDi.GetFiles(FileExtension);
            Array.Sort<FileInfo>(Files, new FileLastTimeComparer());
            if (Files.Length > FilesCount)
            {
                for (var i = FilesCount; i < Files.Length; i++)
                {
                    Files[i].Delete();
                }
            }

        }

        public static DateTime GetStartDatetime()
        {
            DateTime dt = DateTime.Now;
            try
            {
                string configFile = Path.Combine(AppRoot, "Time.ini");
                if (!File.Exists(configFile))
                {
                    WriteStartDatetime(DateTime.Now);
                }
                var SR = new StreamReader(configFile);
                string DateString = SR.ReadLine();
                SR.Close();
                dt = Convert.ToDateTime(DateString);
            }
            catch
            {
                dt = DateTime.Now;
                WriteStartDatetime(dt);
               
            }
            return dt;
        }

        public static void WriteStartDatetime(DateTime dt)
        {
           
            try
            {
                string configFile = Path.Combine(AppRoot, "Time.ini");
                var SW = new StreamWriter(configFile,false);
                SW.WriteLine(dt.ToString());
                SW.Close();
            }
            catch
            {
               
            }
            
        }
    }

    public class FileLastTimeComparer : IComparer<FileInfo>
    {

        public int Compare(FileInfo x, FileInfo y)
        {
            return y.LastWriteTime.CompareTo(x.LastWriteTime);
        }
    }
}
