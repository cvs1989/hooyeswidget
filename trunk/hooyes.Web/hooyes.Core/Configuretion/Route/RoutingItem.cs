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
    /// 一个Route规则配置项
    /// </summary>
    public class RoutingItem : ConfigurationElement {
        /// <summary>
        /// Route的名称
        /// </summary>
        [ConfigurationProperty("name", IsRequired = true, IsKey = true)]
        public string Name {
            get { return this["name"].ToString(); }
            set { this["name"] = value; }
        }

        /// <summary>
        /// Route的url
        /// </summary>
        [ConfigurationProperty("url", IsRequired = true, IsKey = true)]
        public string Url {
            get { return this["url"].ToString(); }
            set { this["url"] = value; }
        }

        /// <summary>
        /// Route的默认Controller
        /// </summary>
        [ConfigurationProperty("controller", IsRequired = true)]
        public string Controller {
            get { return this["controller"].ToString(); }
            set { this["controller"] = value; }
        }

        /// <summary>
        /// Route的默认Action
        /// </summary>
        [ConfigurationProperty("action", IsRequired = true)]
        public string Action {
            get { return this["action"].ToString(); }
            set { this["action"] = value; }
        }

        /// <summary>
        /// Route的参数默认值列表
        /// </summary>
        [ConfigurationProperty("parameters", IsRequired = false)]
        public ParameterCollection Paramaters {
            get { return this["parameters"] as ParameterCollection; }
            set { this["parameters"] = value; }
        }
    }
}
