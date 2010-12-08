using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Web;


namespace hooyes.Core.OAuth
{
    public class MemCache
    {
        public static void Save(string key, object value)
        {
            HttpContext.Current.Session[key] = value;
        }
        public static object Get(string key)
        {
            return HttpContext.Current.Session[key];
        }
        public static void remove(string key)
        {
            HttpContext.Current.Session.Remove(key);
        }
        public static void clear()
        {
            HttpContext.Current.Session.RemoveAll();
        }
    }
}
