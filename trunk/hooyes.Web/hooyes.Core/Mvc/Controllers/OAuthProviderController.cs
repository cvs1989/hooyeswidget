using System;
using System.Collections.Generic;
using System.Text;
using System.Web;
using System.Web.Mvc;
using System.Web.Routing;
using System.Web.Security;
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
            string Temp = "oauth_token=80ed21e32a145971e84bccacf2b0afbe&oauth_token_secret=926e4159c6ca594511189c999e6a39c5";
            return Content(Temp);
        }
        public ActionResult access_token()
        {
            MembershipUser u2;
            u2 = Membership.GetUser("hooyes");
            string Temp = "What";
            if (u2 != null)
            {
                Temp = "oauth_token=eef83555d2bf838a57abddd9605076b0&oauth_token_secret=7f568686aa42f121fbe7c9235103827d&user_id="+u2.UserName;
            }
            return Content(Temp);
        }
        public RedirectResult authorize(string oauth_callback)
        {
            string app = HttpContext.Request.ApplicationPath;
            if (app != "/")
            {
                app += "/";
            }
            return Redirect(app+"Account/LogOn?ReturnUrl=" + oauth_callback);
        }
       
    }
}
