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
    /// 忽略Routing的项集合
    /// </summary>
    public class IgnoreCollection : ConfigurationElementCollection {
        public IgnoreItem this[int index] {
            get {
                return base.BaseGet(index) as IgnoreItem;
            }
            set {
                if (base.BaseGet(index) != null) {
                    base.BaseRemoveAt(index);
                }
                this.BaseAdd(index, value);
            }
        }

        protected override ConfigurationElement CreateNewElement() {
            return new IgnoreItem();
        }

        protected override object GetElementKey(ConfigurationElement element) {
            return ((IgnoreItem)element).Url;
        }
    }
}
