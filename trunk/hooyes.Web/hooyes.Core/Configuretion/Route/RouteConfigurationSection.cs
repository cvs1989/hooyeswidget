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
using System.Configuration;

namespace LevenBlog.Core.Configuretion.Route {
    /// <summary>
    /// Routing配置节
    /// </summary>
    public class RouteConfigurationSection : ConfigurationSection {
        public RouteConfigurationSection() {
        }

        [ConfigurationProperty("ignore", IsRequired = false)]
        public IgnoreCollection Ignore {
            get { return (IgnoreCollection)(this["ignore"]); }
            set { this["ignore"] = value; }
        }


        [ConfigurationProperty("map", IsRequired = false)]
        public RoutingCollection Map {
            get { return (RoutingCollection)(this["map"]); }
            set { this["map"] = value; }
        }

        [ConfigurationProperty("short", IsRequired = false)]
        public RoutingCollection Short {
            get { return (RoutingCollection)this["short"]; }
            set { this["short"] = value; }
        }

        [ConfigurationProperty("extend", IsRequired = true)]
        public string Extend {
            get { return this["extend"].ToString(); }
            set { this["extend"] = value; }
        }
    }
}
