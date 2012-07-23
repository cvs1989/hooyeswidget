using System;
using System.Collections.Generic;
using System.Text;
using System.Web.Script.Serialization;

namespace com.hooyes.lms.API
{
    public class Teach
    {
        private static NLog.Logger log = NLog.LogManager.GetCurrentClassLogger();
        public static ProveAction TeachProveAction(ProveParams param)
        {
            ProveAction r = new ProveAction();
            try
            {
                if (string.IsNullOrEmpty(param.schoolId))
                {
                    param.schoolId = C.SCHOOLID;
                    param.schoolPas = C.SCHOOLPAS;
                }
                string url = C.SCHOOLURL + "/servlet/TeachEscapeProveAction";
                string data = "schoolId={0}&schoolPas={1}&certId={2}&orderId={3}";
                data = string.Format(data, param.schoolId, param.schoolPas, param.certId, param.orderId);
                string s = http.Send(data, url);
                JavaScriptSerializer jss = new JavaScriptSerializer();
                r = jss.Deserialize<ProveAction>(s);
            }
            catch (Exception ex)
            {
                log.Fatal(ex.Message);
            }
            return r;
        }
        public static AnnalAction TeachAnnalAction(AnnalParams param)
        {
            AnnalAction r = new AnnalAction();
            try
            {
                if (string.IsNullOrEmpty(param.schoolId))
                {
                    param.schoolId = C.SCHOOLID;
                    param.schoolPas = C.SCHOOLPAS;
                }
                string url = C.SCHOOLURL + "/servlet/TeachEscapeAnnalAction";
                string data = "schoolId={0}&schoolPas={1}&certId={2}&orderId={3}";
                data = data + "&credits={4}&classHour={5}&startTeachDate={6}&endTeachDate={7}&isPass={8}";
                data = string.Format(data, param.schoolId, param.schoolPas, param.certId, param.orderId
                    ,param.credits
                    ,param.classHour
                    ,param.startTeachDate
                    ,param.endTeachDate
                    ,param.isPass);
                string s = http.Send(data, url);
                JavaScriptSerializer jss = new JavaScriptSerializer();
                r = jss.Deserialize<AnnalAction>(s);
            }
            catch (Exception ex)
            {
                log.Fatal(ex.Message);
            }
            return r;
        }
        public static AdminServelt TeachAdminAnnalServelt(ProveParams param)
        {
            AdminServelt r = new AdminServelt();
            try
            {
                if (string.IsNullOrEmpty(param.schoolId))
                {
                    param.schoolId = C.SCHOOLID;
                    param.schoolPas = C.SCHOOLPAS;
                }
                string url = C.SCHOOLURL + "/servlet/CompTeachAdminAnnalServelt";
                string data = "schoolId={0}&schoolPas={1}&certId={2}&orderId={3}";
                data = string.Format(data, param.schoolId, param.schoolPas, param.certId, param.orderId);
                string s = http.Send(data, url);
                JavaScriptSerializer jss = new JavaScriptSerializer();
                r = jss.Deserialize<AdminServelt>(s);
            }
            catch (Exception ex)
            {
                log.Fatal(ex.Message);
            }
            return r;
        }
    }
}
