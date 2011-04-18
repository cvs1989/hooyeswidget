using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.ServiceModel;
using LeoShi.Soft.OpenSinaAPI;
namespace hooyes.WCF.Service
{
   [ServiceContract]
   public interface ISv
    {
       [OperationContract]
       string GetData(int value);
       [OperationContract]
       void UpLoad(byte[] file);
       [OperationContract]
       BaseHttpRequest CreateHttpRequest(Method method);
    }
}
