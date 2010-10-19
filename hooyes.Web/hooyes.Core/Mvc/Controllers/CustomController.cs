using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Web.Mvc;
using hooyes.Core.Mvc.Models;
using hooyes.Core;
using hooyes.Core.Mvc;
namespace hooyes.Core.Mvc.Controllers
{
    //[CustomHandleError]
    public class CustomController:Controller
    {
        private static CustomViewEngine Cv;
        private static object lockObject = new object();
        private static ViewEngineCollection Vengine = new ViewEngineCollection();
        public CustomController()
        {
            if (Cv == null)
            {
                lock (lockObject)
                {
                    Cv = new CustomViewEngine("static");
                   // Vengine.Add(Cv);
                    ViewEngines.Engines.Add(Cv);
                }
            }
        }
        public ActionResult index()
        {
            return new CustomActionResult();
        }
        public ActionResult Buy()
        {
            return View();
        }
        public ActionResult getCode(string img)
        {
            return new CodeResult();
        }
        public ActionResult md5(string s)
        {
            return Content(s.ToMD5());
        }
        public ActionResult error()
        {
            return ControllerExtensions.Code(this, "ss");
        }

        public ActionResult MovieList(movie m)
        {
            //movie m = new movie();
            //m.title = "ok";
            //m.director = "ok2";
            List<movie> rt = new List<movie>();
            rt.Add(m);

            movie m2 = new movie();
            m2.title = "十面埋伏";
            m2.director = "张艺谋";
            rt.Add(m2);
            return Json(rt, JsonRequestBehavior.AllowGet);
        }
        public ActionResult json2(movie m)
        {
            return Json(m,JsonRequestBehavior.AllowGet);
        }
        [CustomAuthorize(isException=true)]
        public ActionResult Admin()
        {
            return Content("Private content");
        }
        [Authorize]
        public ActionResult A2()
        {
            return Content("it's ok");
        }
    }
}
