using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Web.Mvc;
namespace hooyes.Core.Mvc
{
   public class CustomHandleError:HandleErrorAttribute
    {
       public CustomHandleError()
           : base()
       {
           View = "error";
       }
       public override void OnException(ExceptionContext filterContext)
       {
           throw new Exception("custom error");
           //base.OnException(filterContext);
       }

    }
}
