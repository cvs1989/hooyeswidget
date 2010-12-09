using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Web.Mvc;
using System.Web.Security;
using hooyes.OAuth.Client;

namespace hooyes.OAuth.Client
{
   public class OAuthAuthorizeAttribute : FilterAttribute, IAuthorizationFilter
    {
        public void OnAuthorization(AuthorizationContext filterContext)
        {
            if (MemCache.Get("_token") != null && MemCache.Get("_tokenSecret") != null && MemCache.Get("oauth_verifier") != null && MemCache.Get("_user_id")!=null)
            {

            }
            else
            {
                filterContext.Result = new RedirectToRouteResult(new System.Web.Routing.RouteValueDictionary(new { controller = "Home", action = "Fobbiden" }));
            }
        }
    }
}
