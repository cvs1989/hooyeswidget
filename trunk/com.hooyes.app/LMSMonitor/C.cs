using System;
using System.Collections.Generic;
using System.Text;
using System.Configuration;

namespace com.hooyes.lms
{
    public class C
    {
        public static readonly string SCHOOLID = ConfigurationManager.AppSettings.Get("schoolId");
        public static readonly string SCHOOLPAS = ConfigurationManager.AppSettings.Get("schoolPas");
        public static readonly string SCHOOLURL = ConfigurationManager.AppSettings.Get("schoolUrl");
    }
}
