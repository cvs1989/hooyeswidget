using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Web.Mvc;
namespace hooyes.Core.Mvc
{
   public class CustomViewEngine:WebFormViewEngine
   {
       public CustomViewEngine()
           :this("default")
       {
       }
       public CustomViewEngine(string SkinName)
       {
            //string SkinName="t";
            MasterLocationFormats = new[]{
                string.Format("~/skins/{0}/{{1}}/{{0}}.master", SkinName),
                string.Format("~/skins/{0}/shared/{{0}}.master", SkinName)
            };

            ViewLocationFormats = new[]{
                string.Format("~/Views/{0}/{{0}}.html", SkinName),
                //string.Format("~/skins/{0}/{{1}}/{{0}}.ascx", SkinName),
                //string.Format("~/skins/{0}/shared/{{0}}.aspx", SkinName),
                //string.Format("~/skins/{0}/shared/{{0}}.ascx", SkinName)
            };
            PartialViewLocationFormats = ViewLocationFormats;
       }
       public override ViewEngineResult FindView(ControllerContext controllerContext, string viewName, string masterName, bool useCache)
       {
           return base.FindView(controllerContext, viewName, masterName, useCache);
       }
       public override ViewEngineResult FindPartialView(ControllerContext controllerContext, string partialViewName, bool useCache)
       {
           return base.FindPartialView(controllerContext, partialViewName, useCache);
       }
    }
}
