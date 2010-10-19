using System;
using System.Collections.Generic;
using System.Linq;
using System.Runtime.Serialization;
using System.ServiceModel;
using System.ServiceModel.Web;
using System.Text;

namespace WcfAutoService
{
    /*
     服务
     */
    public class AutoService : IAutoService
    {
        public string GetData()
        {
            return "Hello，Wcf自动服务调用成功！";
        }
    }
}
