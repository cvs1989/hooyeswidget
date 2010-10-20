using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;

namespace hooyes.API
{
    public class Sina
    {
        SinaApiSv.SinaApiServiceClient client = new SinaApiSv.SinaApiServiceClient();
        
        public string update(string userid, string passwd, string format, string status)
        {
            return client.statuses_update(userid, passwd, format, status);
        }
        public string update(string message)
        {
            return update("hooyes@sina.com","rgbjyss","json",message);
        }
        public string user_timeline()
        {
            string a=client.user_timeline("hooyes@sina.com", "rgbjyss", "json");
            return a;
        }
    }
}
