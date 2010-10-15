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
    [CustomHandleError]
    public class CustomController:Controller
    {
        public ActionResult index()
        {
            return new CustomActionResult();
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
    }
}
