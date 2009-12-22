using System;
using System.Collections.Generic;
using System.Text;
using System.Web;

namespace com.hooyes.crc.BLL
{
    public class PageBase : System.Web.UI.Page
    {
        public void RequireLogin()
        {
            if (!IsLogin)
            {
                string CurrentUrl = HttpContext.Current.Request.Url.ToString();
                string UrlIndex = "login.aspx?ref={0}";
                UrlIndex = string.Format(UrlIndex,HttpUtility.UrlEncode(CurrentUrl));
                HttpContext.Current.Response.Redirect(UrlIndex);
            }
        }
        public bool IsLogin
        {
            get
            {
                if (Session["hooyes_CRC_Admin"] != null)
                {
                    return true;
                }
                else
                {
                    return false;
                }
            }
        }
        public string LoginUserName
        {

            get
            {
                return Convert.ToString(Session["hooyes_CRC_Admin"]);
            }

        }
        public bool Logout()
        {
            Session.Abandon();
            return true;
        }
        public void GoThankYouUrl(string thankYouPageRoot, string msg, string UrlBack, string UrlNext, string UrlBackWord, string UrlNextWord)
        {
            string ThankyouUrl = "{0}thankyou.aspx?msg={1}&UrlBack={2}&UrlNext={3}&UrlBackWord={4}&UrlNextWord={5}";
            object[] param = new object[6];
            param[0] = thankYouPageRoot;
            param[1] = HttpUtility.UrlEncode(msg);
            param[2] = HttpUtility.UrlEncode(UrlBack);
            param[3] = HttpUtility.UrlEncode(UrlNext);
            param[4] = HttpUtility.UrlEncode(UrlBackWord);
            param[5] = HttpUtility.UrlEncode(UrlNextWord);

            ThankyouUrl = string.Format(ThankyouUrl, param);

           // HttpContext.Current.Response.Write(ThankyouUrl);
           // HttpContext.Current.Response.Redirect(ThankyouUrl);
            StringBuilder sb=new StringBuilder();
            sb.Append("<script type='text/javascript'>");
            sb.Append("window.onload=function(){");
            sb.Append(" var xHref=document.getElementById('boxHolderHidden').value;");
            sb.AppendFormat("document.getElementById('boxHolder').href='{0}'+xHref;",ThankyouUrl);
            sb.Append("document.getElementById('boxHolder').click();");
            sb.Append("}</script>");

            //script = string.Format(script, ThankyouUrl);

           this.Page.ClientScript.RegisterClientScriptBlock(typeof(System.Web.UI.Page),"script", sb.ToString());
        }
        public void GoThankYouUrl(string msg, string UrlBack, string UrlNext, string UrlBackWord, string UrlNextWord)
        {
            GoThankYouUrl("",msg, UrlBack, UrlNext, UrlBackWord, UrlNextWord);
        }
    }
}
