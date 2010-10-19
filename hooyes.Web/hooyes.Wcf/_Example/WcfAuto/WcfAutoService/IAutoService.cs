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
     契约
     */
    [ServiceContract]
    public interface IAutoService
    {
        [OperationContract]
        string GetData();
    }
}
