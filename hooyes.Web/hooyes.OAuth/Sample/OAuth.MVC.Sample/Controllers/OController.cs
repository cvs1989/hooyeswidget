using System;
using System.Collections.Generic;
using System.Linq;
using System.Web;
using System.Web.Mvc;
using OAuth.MVC.Library.Controllers;
namespace OAuth.MVC.Sample.Controllers
{
    public class OController : OAuthController
    {
        //
        // GET: /O/
        public OController() { }
        public ActionResult Index()
        {
            return View();
        }

    }
}
