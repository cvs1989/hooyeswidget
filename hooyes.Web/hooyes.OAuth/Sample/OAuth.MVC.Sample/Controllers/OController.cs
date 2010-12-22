using System;
using System.Collections.Generic;
using System.Linq;
using System.Web;
using System.Web.Mvc;
using OAuth.MVC.Library.Controllers;
using OAuth.Core.Interfaces;
using OAuth.Core;
using OAuth.Core.Provider;
using System.Web.Routing;
namespace OAuth.MVC.Sample.Controllers
{
    public class OController : OAuthController
    {
        //
        // GET: /O/
        //public OController(RequestContext context)
        //{
        //    base.Initialize(context);
        //}
        public ActionResult Index()
        {
            TokenRepository tr=new TokenRepository();
            SampleMemoryTokenStore sbTokenStore=new SampleMemoryTokenStore(tr);
            IOAuthContextBuilder oAuthContextBuilder=new OAuthContextBuilder();
            IOAuthProvider oAuthProvider=new OAuthProvider(sbTokenStore);
            OAuthController o = new OAuthController(oAuthContextBuilder, oAuthProvider);

           //RequestContext context = ;
            return o.RequestToken();
        }

    }
}
