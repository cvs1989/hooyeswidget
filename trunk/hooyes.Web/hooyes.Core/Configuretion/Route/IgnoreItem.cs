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
    /// 忽略Routing的项
    /// </summary>
    public class IgnoreItem : ConfigurationElement {
        [ConfigurationProperty("url", IsRequired = true, IsKey = true)]
        public string Url {
            get { return this["url"].ToString(); }
            set { this["url"] = value; }
        }

        [ConfigurationProperty("constraints", IsRequired = false)]
        public ConstraintCollection Constraints {
            get { return this["constraints"] as ConstraintCollection; }
            set { this["constraints"] = value; }
        }
    }
}
