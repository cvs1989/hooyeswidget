using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;

namespace WcfManualService{

    public class ManulService : IManulService
    {
        #region IManulService Members

        public string GetData()
        {
            return "Hello! 纯手工WCF服务调用成功！";
        }

        #endregion
    }
}
