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
            return Content(_oAuth.AuthorizationSinaGet() + "&oauth_callback=http://"+ HttpContext.Request.Url.Host+":" + HttpContext.Request.Url.Port.ToString() + "/oAuth/callback");
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
            return test();
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


    }
}
