using System;
using System.Collections.Generic;
using System.Text;
using System.Web;
using System.Web.Mvc;
using System.Web.Routing;
using System.Web.Security;
using System.Data.SQLite;
using com.hooyes.DBUtility;
using hooyes.Core.Utility;
namespace hooyes.Core.Mvc.Controllers
{
    public class OAuthProviderController:Controller
    {
        MembershipUser u;
        public OAuthProviderController()
        {
           u = Membership.GetUser();
        }
        public ActionResult request_token()
        {
            string ResponseToken = Guid.NewGuid().ToString();
            string Temp = "oauth_token={0}&oauth_token_secret=926e4159c6ca594511189c999e6a39c5";
            Temp = string.Format(Temp, ResponseToken);
            return Content(Temp);
        }
        public ActionResult access_token(string oauth_token)
        {
            string sql = "select username from users where oauth_token='{0}'";
            sql = string.Format(sql, oauth_token);
            string u2 = "";

            SQLiteDataReader dr = SQLiteHelper.ExecuteReader(sql);
            if (dr.Read())
            {
                u2 = Convert.ToString(dr["username"]);
            }
            dr.Close();
            string Temp = "What";
            if (!string.IsNullOrEmpty(u2))
            {
                string ResponseToken = Guid.NewGuid().ToString();
                Temp = "oauth_token={0}&oauth_token_secret=7f568686aa42f121fbe7c9235103827d&user_id="+u2;
                Temp = string.Format(Temp, ResponseToken);
                string xsql = "update users set oauth_token='{0}' where username='{1}'";
                xsql = string.Format(xsql, ResponseToken, u2);
                SQLiteHelper.ExecuteSql(xsql);
            }
            return Content(Temp);
        }
        public RedirectResult authorize(string oauth_token,string oauth_callback)
        {
            MemCache.Save("oauth_token", oauth_token);
            string app = HttpContext.Request.ApplicationPath;
            if (app != "/")
            {
                app += "/";
            }
            return Redirect(app+"Account/LogOn?ReturnUrl=" + oauth_callback);
        }
        public ActionResult T()
        {
            string token = "18578788-2937-421b-93d7-38eb8755b89a";
            string sql="select username from users where oauth_token='{0}'";
            sql=string.Format(sql,token);
            string x = "";

            SQLiteDataReader dr = SQLiteHelper.ExecuteReader(sql);
            if (dr.Read())
            {
                x = Convert.ToString(dr["username"]);
            }
            dr.Close();

            
            return Content(x.ToString());
        }
       
    }
}
