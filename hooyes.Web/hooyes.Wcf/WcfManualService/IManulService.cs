using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;

using System.ServiceModel;

namespace WcfManualService
{
    //契约
    [ServiceContract]
    public interface IManulService
    {
        //操作
        [OperationContract]
        string GetData();
    }
}
