using System;
using System.Collections.Generic;
using System.Text;
using System.Data;
using System.Data.SqlClient;
using com.hooyes.lms.Svc.Model;
using com.hooyes.lms.API;


namespace com.hooyes.lms.Svc.DAL
{
    public class Login
    {
        private static NLog.Logger log = NLog.LogManager.GetCurrentClassLogger();
        public static R Check(string loginID, string loginPWD)
        {
            R r = new R();
            try
            {
                SqlParameter[] param =
                {
                    new SqlParameter("@R",0),
                    new SqlParameter("@Code",0),
                    new SqlParameter("@Message",string.Empty),
                    new SqlParameter("@LoginID",loginID),
                    new SqlParameter("@LoginPWD",loginPWD)
                };
                param[0].Direction = ParameterDirection.ReturnValue;
                param[1].Direction = ParameterDirection.Output;
                param[2].Direction = ParameterDirection.Output;
                int _r = SqlHelper.ExecuteNonQuery(SqlHelper.Local, CommandType.StoredProcedure, "Check_Login", param);
                r.Code = Convert.ToInt32(param[1].Value);
                r.Message = Convert.ToString(param[2].Value);
                r.Value = Convert.ToInt32(param[0].Value);
                if (r.Code == 200)
                {
                    r = CheckAPI(loginID, loginPWD);
                }
            }
            catch (Exception ex)
            {
                r.Code = 300;
                r.Message = ex.Message;
                r.Value = 0;
                log.FatalException(ex.Message, ex);
            }
            return r;
        }
        public static R CheckAPI(string loginID, string loginPWD)
        {
            R r = new R();
            var param = new ProveParams();
            param.certId = loginPWD;
            param.orderId = loginID;
            var prove = Teach.TeachProveAction(param);
            if (prove.proveValue == "prove000" || prove.proveValue == "prove003")
            {
                var member = new Member();
                member.MID = 0;
                member.Name = prove.personName;
                member.Year = Convert.ToInt32(prove.yearValue);
                member.IDCard = loginPWD;
                member.IDSN = loginID;
                member.Level = -1;
                member.Type = -1;
                r = Update.Member(member);
            }
            else
            {
                r.Code = 202;
                r.Message = string.Format("{0}",prove.proveValue);
                r.Value = -1;
                log.Warn("{0},{1},{2}", loginID, loginPWD, prove.proveValue);
            }

            return r;
        }
    }
}
