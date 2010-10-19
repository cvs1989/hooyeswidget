using System;
using System.Collections.Generic;
using System.Linq;
using System.Runtime.Serialization;
using System.ServiceModel;
using System.Text;

namespace WcfHalfService
{
    /*
    契约
    */
    [ServiceContract]
    public interface IHalfService
    {
        [OperationContract]
        string GetData();
    }
}
