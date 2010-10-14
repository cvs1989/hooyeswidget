#region Copyright and License
/*==============================================================================
 *  Copyright (c) www.51mvc.com Corporation.  All rights reserved.
 * ===============================================================================
 * This code and information is provided "as is" without warranty of any kind,
 * either expressed or implied, including but not limited to the implied warranties
 * of merchantability and fitness for a particular purpose.
 * ===============================================================================
 * Licensed under the GNU General Public License (GPL) v2
 * http://www.51mvc.com/
 * Create By lulu(QLeelulu)  - http://QLeelulu.com
 * ==============================================================================*/
#endregion
using System;
using System.Web.Routing;
using System.Web.Mvc;
using System.Configuration;

namespace LevenBlog.Core.Configuretion.Route {
    public static class RouteCollectionExtensions {
        private static string deaultpage;
        private static string extendName;
        public static string GetDefaultPage(this System.Web.Routing.RouteCollection routes) {
            return deaultpage;
        }

        public static string GetExtendName(this System.Web.Routing.RouteCollection routes) {
            return extendName;
        }
        /// <summary>
        /// 根据配置的Routing规则来加载Routing规则
        /// </summary>
        public static void RegisterRoutes(this System.Web.Routing.RouteCollection routes, RouteConfigurationSection section) {
            if (!section.Short.Enable && !section.Map.Enable) {
                throw new ConfigurationErrorsException("Short与Map必须至少有一个开启.");
            }
            extendName = section.Extend;
            if (section.Short != null && section.Short.Enable) {
                deaultpage = section.Short.Default.Replace("$0", section.Extend);
            } else {
                deaultpage = section.Map.Default.Replace("$0", section.Extend);
            }
            // Manipulate the Ignore List
            foreach (IgnoreItem ignoreItem in section.Ignore) {
                RouteValueDictionary ignoreConstraints = new RouteValueDictionary();

                foreach (Constraint constraint in ignoreItem.Constraints)
                    ignoreConstraints.Add(constraint.Name, constraint.Value);

                routes.IgnoreRoute(ignoreItem.Url, ignoreConstraints);
            }
            // Maniplute the short Routing Table
            if (section.Short != null && section.Short.Enable) {
                foreach (RoutingItem item in section.Short) {
                    RouteValueDictionary defaults = new RouteValueDictionary();
                    RouteValueDictionary constraints = new RouteValueDictionary();

                    if (item.Controller != string.Empty)
                        defaults.Add("controller", item.Controller);

                    if (item.Action != string.Empty)
                        defaults.Add("action", item.Action);

                    foreach (Parameter param in item.Paramaters) {
                        defaults.Add(param.Name, param.Value);
                        if (!string.IsNullOrEmpty(param.Constraint)) {
                            constraints.Add(param.Name, param.Constraint);
                        }
                    }
                    routes.MapRoute(item.Name, item.Url.Replace("$0", section.Extend), defaults, constraints);
                }
            }

            // Manipluate the Routing Table
            foreach (RoutingItem routingItem in section.Map) {
                RouteValueDictionary defaults = new RouteValueDictionary();
                RouteValueDictionary constraints = new RouteValueDictionary();

                if (routingItem.Controller != string.Empty)
                    defaults.Add("controller", routingItem.Controller);

                if (routingItem.Action != string.Empty)
                    defaults.Add("action", routingItem.Action);

                foreach (Parameter param in routingItem.Paramaters) {
                    defaults.Add(param.Name, param.Value);
                    if (!string.IsNullOrEmpty(param.Constraint)) {
                        constraints.Add(param.Name, param.Constraint);
                    }
                }

                routes.MapRoute(routingItem.Name, routingItem.Url.Replace("$0", section.Extend), defaults, constraints);
            }
        }

        public static void IgnoreRoute
            (this RouteCollection routes, string url, RouteValueDictionary constraints) {
            if (routes == null) {
                throw new ArgumentNullException("routes");
            }
            if (url == null) {
                throw new ArgumentNullException("url");
            }
            IgnoreRoute ignore = new IgnoreRoute(url);
            ignore.Constraints = constraints;
            routes.Add(ignore);
        }

        /// <summary>
        /// 框架的这个方法的defaults、constraints参数都是Object类型的，只好重写
        /// </summary>
        public static void MapRoute(
            this RouteCollection routes,
            string name,
            string url,
            RouteValueDictionary defaults,
            RouteValueDictionary constraints) {
            if (routes == null) {
                throw new ArgumentNullException("routes");
            }
            if (url == null) {
                throw new ArgumentNullException("url");
            }
            System.Web.Routing.Route route = new System.Web.Routing.Route(url, new MvcRouteHandler());
            route.Defaults = defaults;
            route.Constraints = constraints;
            routes.Add(name, route);
        }

        public static RouteConfigurationSection GetSection() {
            RouteConfigurationSection section =
                (RouteConfigurationSection)ConfigurationManager.GetSection("routeConfiguration");
            return section;
        }
    }


    sealed class IgnoreRoute : System.Web.Routing.Route {
        public IgnoreRoute(string url)
            : base(url, new StopRoutingHandler()) {
        }

        public override VirtualPathData GetVirtualPath(RequestContext requestContext, RouteValueDictionary values) {
            return null;
        }
    }
}
