using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Web.Mvc;
using System.Web.Security;
namespace hooyes.Core.Mvc
{
    public class CustomAuthorizeAttribute: FilterAttribute, IAuthorizationFilter
    {
        public bool isException { get; set; }
        #region IAuthorizationFilter Members
        public void OnAuthorization(AuthorizationContext filterContext)
        {
             //ViewResult view = new ViewResult();
             //filterContext.Result = view;
            if (!isException)
            {
                filterContext.Result = new RedirectToRouteResult(new System.Web.Routing.RouteValueDictionary(new { controller = "Home", action = "Fobbiden" }));
            }
        }
        #endregion
        public CustomAuthorizeAttribute()
        {
        }
        public CustomAuthorizeAttribute(bool isException)
        {
            this.isException = isException;
        }
    }
}
