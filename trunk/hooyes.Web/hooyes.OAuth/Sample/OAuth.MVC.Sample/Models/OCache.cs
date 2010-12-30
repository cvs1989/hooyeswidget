using System;
using System.Collections.Generic;
using System.Web;
using com.hooyes.DBUtility;
using OAuth.Core;
using System.Data.SQLite;
using NLog;

namespace OAuth.MVC.Sample.Models
{
    public class OCache
    {
        private static Logger log = LogManager.GetCurrentClassLogger();
        public static void save(RequestToken token)
        {
            string sql="insert into oAuthToken(ConsumerKey,RequestToken) values(@ConsumerKey,@RequestToken)";
            int r= SQLiteHelper.ExecuteSql(sql,
                new SQLiteParameter("@ConsumerKey", token.ConsumerKey),
                new SQLiteParameter("@RequestToken", token.Token));

            log.Info(sql);
        }
        public static RequestToken get(string token)
        {
            RequestToken r = new RequestToken();
            AccessToken at=new AccessToken();
            string sql="select * from oAuthToken where requesttoken=@requesttoken";
            SQLiteDataReader dr = SQLiteHelper.ExecuteReader(sql,
                new SQLiteParameter("@requesttoken", token));

            dr.Close();

            r.AccessToken = at;
            at.Token = Convert.ToString(dr["AccessSecret"]);

            return r;
        }
    }
}