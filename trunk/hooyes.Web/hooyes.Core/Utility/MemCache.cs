using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Web;


namespace hooyes.Core.Utility
{
    public class MemCache
    {
        private static string prefix = "xx";
        public static void Save(string key, object value)
        {
            HttpContext.Current.Session[prefix + key] = value;
        }
        public static object Get(string key)
        {
            return HttpContext.Current.Session[prefix + key];
        }
        public static void remove(string key)
        {
            HttpContext.Current.Session.Remove(prefix + key);
        }
        public static void clear()
        {
            HttpContext.Current.Session.RemoveAll();
        }
    }
}
