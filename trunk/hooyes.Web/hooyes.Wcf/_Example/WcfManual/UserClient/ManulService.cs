using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;

using System.ServiceModel;

namespace UserClient
{
    public partial class ManulService : System.ServiceModel.ClientBase<IManulService>, IManulService
    {
        public string GetData()
        {
            return base.Channel.GetData();
        }
    }
}
