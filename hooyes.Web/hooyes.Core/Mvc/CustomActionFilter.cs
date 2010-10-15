using System;
using System.Collections.Generic;
using System.Linq;
using System.Web;
using System.Web.Mvc;
using System.IO;

namespace hooyes.Core.Mvc
{
    public class CustomActionFilterAttribute : ActionFilterAttribute 
    {
        public override void OnActionExecuting(ActionExecutingContext filterContext)
        {
            //执行Action 之前 
            StreamWriter sw = File.AppendText("E:/test.txt");
            sw.WriteLine("OnActionExecuting");
            sw.Close();
            base.OnActionExecuting(filterContext);
        }

        public override void OnActionExecuted(ActionExecutedContext filterContext)
        {
            //执行Action 之后 
            if (filterContext.Exception != null)
            {
                throw new Exception("===============");
                StreamWriter sw = File.AppendText("E:/test.txt");
                sw.WriteLine(filterContext.Exception.Message);
                sw.Close();
            }
            base.OnActionExecuted(filterContext);
        }

        public override void OnResultExecuted(ResultExecutedContext filterContext)
        {
            //返回result 之前 
            StreamWriter sw = File.AppendText("E:/test.txt");
            sw.WriteLine("OnResultExecuted");
            sw.Close();
            base.OnResultExecuted(filterContext);
        }

        public override void OnResultExecuting(ResultExecutingContext filterContext)
        {
            //返回result 之后
            StreamWriter sw = File.AppendText("E:/test.txt");
            sw.WriteLine("OnResultExecuting");
            sw.Close();
            base.OnResultExecuting(filterContext);
        }
    }
}