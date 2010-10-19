using System;
using System.Collections.Generic;
using System.Linq;
using System.Runtime.Serialization;
using System.ServiceModel;
using System.Text;

namespace WcfHalfService
{
    /*
     服务类型
     */
    public class HalfService : IHalfService
    {
        public string GetData()
        {
            return "Hello，Wcf半自动服务建立成功，并调用成功！";
        }
    }
}
