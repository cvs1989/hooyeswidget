using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;


using System.ServiceModel;

namespace UserClient
{
    [ServiceContract]
    public interface IManulService
    {
        [OperationContract]
        string GetData();
    }
}
