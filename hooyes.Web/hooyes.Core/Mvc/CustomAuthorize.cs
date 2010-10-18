using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Web.Mvc;

namespace hooyes.Core.Mvc
{
    public class CustomAuthorize:FilterAttribute,IAuthorizationFilter
    {
        public bool isException { get; set; }
        public void OnAuthorization(AuthorizationContext filterContext)
        {
            throw new NotImplementedException();
        }
        public CustomAuthorize()
        {

        }
        public CustomAuthorize(bool isException)
        {
            this.isException = isException;
        }
    }
}
