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
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Configuration;

namespace LevenBlog.Core.Configuretion.Route {
    /// <summary>
    /// 路由规则配置项集合
    /// </summary>
    public class RoutingCollection : ConfigurationElementCollection {
        public RoutingItem this[int index] {
            get {
                return base.BaseGet(index) as RoutingItem;
            }

            set {
                if (base.BaseGet(index) != null) {
                    base.BaseRemoveAt(index);
                }

                this.BaseAdd(index, value);
            }
        }

        protected override ConfigurationElement CreateNewElement() {
            return new RoutingItem();
        }

        protected override object GetElementKey(ConfigurationElement element) {
            return ((RoutingItem)element).Name;
        }

        [ConfigurationProperty("default", IsRequired = true)]
        public string Default {
            get { return Convert.ToString(this["default"]); }
            set { this["default"] = value; }
        }

        [ConfigurationProperty("enable", IsRequired = true, DefaultValue = true)]
        public bool Enable {
            get { return Boolean.Parse(this["enable"].ToString()); }
            set { this["enable"] = value; }
        }

        public RoutingCollection() {
            this.AddElementName = "route";
        }
    }
}
