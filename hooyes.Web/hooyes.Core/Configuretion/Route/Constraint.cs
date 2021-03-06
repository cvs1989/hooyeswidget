﻿#region Copyright and License
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

namespace LevenBlog.Core.Configuretion.Route
{
    public class Constraint : ConfigurationElement
    {
        [ConfigurationProperty("name", IsRequired = true, IsKey = true)]
        public string Name {
            get { return this["name"].ToString(); }
            set { this["name"] = value; }
        }

        [ConfigurationProperty("value", IsRequired = true)]
        public string Value {
            get { return this["value"].ToString(); }
            set { this["value"] = value; }
        }
    }
}
