using System;
using System.Data;
using System.Data.SqlClient;
using com.hooyes.lms.Svc.Model;

namespace com.hooyes.lms.Svc.DAL
{
    public class Update
    {
        private static NLog.Logger log = NLog.LogManager.GetCurrentClassLogger();
       
        public static R Member(Member member)
        {
            var m = new R();
            try
            {
                SqlParameter[] param =
                {
                    new SqlParameter("@MID",member.MID),
                    new SqlParameter("@Name",member.Name),
                    new SqlParameter("@IDCard",member.IDCard),
                    new SqlParameter("@IDSN",member.IDSN),
                    new SqlParameter("@Year",member.Year),
                    new SqlParameter("@Type",member.Type),
                    new SqlParameter("@Level",member.Level)
                };
                param[0].Direction = ParameterDirection.InputOutput;
                var r = SqlHelper.ExecuteNonQuery(SqlHelper.Local, CommandType.StoredProcedure, "S_Update_Member", param);
                m.Code = 0;
                m.Value = Convert.ToInt32(param[0].Value);
                m.Message = "sucess";
            }
            catch (Exception ex)
            {
                m.Code = 300;
                m.Message = ex.Message;
                log.Fatal(ex.Message);
                log.FatalException(ex.Message, ex);
            }
            return m;
        }

        public static R Report(Report re)
        {
            var m = new R();
            try
            {
                SqlParameter[] param =
                {
                    new SqlParameter("@MID",re.MID),
                    new SqlParameter("@Score",re.Score),
                    new SqlParameter("@Compulsory",re.Compulsory),
                    new SqlParameter("@Elective",re.Elective),
                    new SqlParameter("@Status",re.Status),
                    new SqlParameter("@Memo",re.Memo)
                };

                var r = SqlHelper.ExecuteNonQuery(SqlHelper.Local, CommandType.StoredProcedure, "Update_Report", param);
                m.Code = 0;
                m.Message = "sucess";
                log.Info("MID:{0},code:{1}", re.MID, m.Code);
            }
            catch (Exception ex)
            {
                m.Code = 300;
                m.Message = ex.Message;
                log.Fatal(ex.Message);
                log.FatalException(ex.Message, ex);
            }
            return m;
        }

        public static R Credit()
        {
            var m = new R();
            try
            {
                SqlParameter[] param =
                {
                    
                };
                
                var r = SqlHelper.ExecuteNonQuery(SqlHelper.Local, CommandType.StoredProcedure, "S_M_Task_MemberCredit", param);
                m.Code = 0;
                //m.Value = Convert.ToInt32(param[0].Value);
                m.Message = "sucess";
            }
            catch (Exception ex)
            {
                m.Code = 300;
                m.Message = ex.Message;
                log.Fatal(ex.Message);
                log.FatalException(ex.Message, ex);
            }
            return m;
        }
    }
}