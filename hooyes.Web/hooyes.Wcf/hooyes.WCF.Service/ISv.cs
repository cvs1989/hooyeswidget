using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.ServiceModel;
namespace hooyes.WCF.Service
{
   [ServiceContract]
   public interface ISv
    {
       [OperationContract]
       string GetData(int value);
    }
}
