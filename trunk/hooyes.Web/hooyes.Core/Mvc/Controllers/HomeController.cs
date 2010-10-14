using System;
using System.Collections.Generic;
using System.Linq;
using System.Web;
using System.Web.Mvc;
using hooyes.Core.Mvc.Models;

namespace hooyes.Web.Controllers
{
    [HandleError]
    public class HomeController : Controller
    {
        public ActionResult Index()
        {
            ViewData["Message"] = "Welcome to ASP.NET MVC!";

            return View();
        }

        public ActionResult About()
        {
            return View();
        }
        public ActionResult AboutUs()
        {
            return View("ViewName");
        }

        public ActionResult Sum(int a, int b)
        {
            int c = a + b;
            return Content(c.ToString());
        }
        public ActionResult MovieList(movie m)
        {
            //movie m = new movie();
            //m.title = "ok";
            //m.director = "ok2";
            List<movie> rt = new List<movie>();
            rt.Add(m);

            movie m2 = new movie();
            m2.title = "ok";
            m2.director = "sssssssss";
            rt.Add(m2);
            return Json(rt, JsonRequestBehavior.AllowGet);
        }
    }
}
