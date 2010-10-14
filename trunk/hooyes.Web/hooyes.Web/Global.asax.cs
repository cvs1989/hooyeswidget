using System;
using System.Collections.Generic;
using System.Linq;
using System.Web;
using System.Web.Mvc;
using System.Web.Routing;
using System.Configuration;
using LevenBlog.Core.Configuretion.Route;

namespace hooyes.Web
{
    // Note: For instructions on enabling IIS6 or IIS7 classic mode, 
    // visit http://go.microsoft.com/?LinkId=9394801

    public class MvcApplication : System.Web.HttpApplication
    {
        public static void RegisterRoutes(RouteCollection routes)
        {

            //RouteConfigurationSection section =
            //    (RouteConfigurationSection)ConfigurationManager.GetSection("routeConfiguration");

            //if (null == section)
            //{
            //    throw new Exception("Route规则未配置");
            //}

            //RouteTable.Routes.RegisterRoutes(section);

            routes.IgnoreRoute("{resource}.axd/{*pathInfo}");

            routes.MapRoute(
                "Default", // Route name
                "{controller}/{action}/{a}/{b}/{id}", // URL with parameters
                new { controller = "Home", action = "Index",a=UrlParameter.Optional,b=UrlParameter.Optional, id = UrlParameter.Optional } // Parameter defaults
            );

        }

        protected void Application_Start()
        {
            AreaRegistration.RegisterAllAreas();

            RegisterRoutes(RouteTable.Routes);
        }
    }
}