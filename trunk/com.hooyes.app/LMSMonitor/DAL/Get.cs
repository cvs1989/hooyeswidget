using System;
using System.Collections.Generic;
using System.Data;
using System.Data.SqlClient;
using com.hooyes.lms.Svc.Model;

namespace com.hooyes.lms.Svc.DAL
{
    public class Get
    {
        private static NLog.Logger log = NLog.LogManager.GetCurrentClassLogger();
        public static List<MSubmit> MSubmitList()
        {
            var l = new List<MSubmit>();
            try
            {
                SqlParameter[] param =
                {
                    
                };
                var dr = SqlHelper.ExecuteReader(SqlHelper.Local, CommandType.StoredProcedure, "S_Get_SubmitList", param);
                while (dr.Read())
                {
                    try
                    {
                        var m = new MSubmit();
                        m.MID = Convert.ToInt32(dr["MID"]);
                        m.Year = Convert.ToInt32(dr["Year"]);
                        m.IDCard = Convert.ToString(dr["IDCard"]);
                        m.IDSN = Convert.ToString(dr["IDSN"]);
                        m.RegDate = Convert.ToDateTime(dr["RegDate"]);
                        m.Score = Convert.ToInt32(dr["Score"]);
                        m.Compulsory = Convert.ToDecimal(dr["Compulsory"]);
                        m.Elective = Convert.ToDecimal(dr["Elective"]);
                        m.Status = Convert.ToInt32(dr["Status"]);
                        l.Add(m);
                    }
                    catch (Exception ex1)
                    {
                        log.Fatal("{0},{1}", ex1.Message, ex1.StackTrace);
                    }
                }

                dr.Close();
            }
            catch (Exception ex)
            {

                log.Fatal(ex.Message);
                log.FatalException(ex.Message, ex);
            }
            return l;
        }
        public static List<Member> MSubmitList()
        {
            var l = new List<MSubmit>();
            try
            {
                SqlParameter[] param =
                {
                    
                };
                var dr = SqlHelper.ExecuteReader(SqlHelper.Local, CommandType.StoredProcedure, "S_Get_SubmitList", param);
                while (dr.Read())
                {
                    try
                    {
                        var m = new MSubmit();
                        m.MID = Convert.ToInt32(dr["MID"]);
                        m.Year = Convert.ToInt32(dr["Year"]);
                        m.IDCard = Convert.ToString(dr["IDCard"]);
                        m.IDSN = Convert.ToString(dr["IDSN"]);
                        m.RegDate = Convert.ToDateTime(dr["RegDate"]);
                        m.Score = Convert.ToInt32(dr["Score"]);
                        m.Compulsory = Convert.ToDecimal(dr["Compulsory"]);
                        m.Elective = Convert.ToDecimal(dr["Elective"]);
                        m.Status = Convert.ToInt32(dr["Status"]);
                        l.Add(m);
                    }
                    catch (Exception ex1)
                    {
                        log.Fatal("{0},{1}", ex1.Message, ex1.StackTrace);
                    }
                }

                dr.Close();
            }
            catch (Exception ex)
            {

                log.Fatal(ex.Message);
                log.FatalException(ex.Message, ex);
            }
            return l;
        }
    }
}
