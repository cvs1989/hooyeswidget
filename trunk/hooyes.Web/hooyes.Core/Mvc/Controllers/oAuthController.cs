using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Web;
using System.Web.Mvc;
using System.Web.Security;
using hooyes.Core.OAuth;

namespace hooyes.Core.Mvc.Controllers
{
    public class oAuthController: Controller
    {
        oAuthSina _oAuth = new oAuthSina();
        public ActionResult GetAuthUrl()
        {
            AuthLink Auth = new AuthLink();
            Auth.AuthUrl = _oAuth.AuthorizationSinaGet() + "&oauth_callback=" + callbackURL();
            //Response.Redirect(_oAuth.AuthorizationSinaGet() + "&oauth_callback=" + callbackURL());
            return Json(Auth, JsonRequestBehavior.AllowGet);
        }
        public ActionResult AccessTokenGet()
        {
            //_oAuth.Token = Session["oauth_token"].ToString();
            //_oAuth.Verifier =Session["oauth_verifier"].ToString();

            _oAuth.AccessTokenGet(_oAuth.Token);
            return Content("");
        }

        public ActionResult CallBack(string oauth_token, string oauth_verifier)
        {
            //Session["oauth_token"] = oauth_token;
            //Session["oauth_verifier"] = oauth_verifier;
            MemCache.Save("oauth_verifier", oauth_verifier);
            AccessTokenGet();
            return Rdjs();
        }
        public ActionResult test()
        {
            if (MemCache.Get("_token") != null)
            {
                if (MemCache.Get("oauth_verifier") != null)
                {
                    return Content("oauth_verifier:"+MemCache.Get("oauth_verifier").ToString()+"::::Token:" + MemCache.Get("_token").ToString() + "::::Secret:" + MemCache.Get("_tokenSecret").ToString());
                }
                else
                {
                    return Content("::::Token:" + MemCache.Get("_token").ToString() + "::::Secret:" + MemCache.Get("_tokenSecret").ToString());
                }
            }
            else
            {
                return Content("空");
            }
        }
        public ActionResult cl()
        {
            MemCache.clear();
            return Content("ok");
        }
        public ActionResult Post(string msg)
        {
            SinaApiService sina = new SinaApiService();

           string rv= sina.statuses_update("json", msg);
           return Content(rv);
        }
        [OAuthAuthorize]
        public ActionResult Protect()
        {
            return Content("受保护的操作");
        }
        public string callbackURL()
        {
            int port = HttpContext.Request.Url.Port;
            string sport = "";
            if (port != 80)
            {
                sport =":"+ port.ToString();
            }
            string app = HttpContext.Request.ApplicationPath;
            if (app != "/")
            {
                app += "/";
            }
            string url = "http://" + HttpContext.Request.Url.Host + sport +app + "oAuth/callback";

            return url;
        }

        public ActionResult Rdjs()
        {
            string x= @"
          <script type='text/javascript'>
           parent.$.colorbox.close();
          </script>
            ";
            return Content(x);
        }

        public ActionResult getToken()
        {
            cToken ct = new cToken();
            if (MemCache.Get("_user_id") != null)
            {
                ct.UserID = (string)MemCache.Get("_user_id");
            }
            return Json(ct);
        }


    }
    public class cToken{
        public string UserID { get; set; }
    }
    public class AuthLink
    {
        public string AuthUrl { get; set; }
    }
}
