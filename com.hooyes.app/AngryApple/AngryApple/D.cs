using System;
using System.Data;
using System.Windows.Forms;


namespace com.hooyes.app.AngryApple
{
    public class D
    {
        public static R A(string f,decimal SN)
        {
            var r = new R();
            string SQL = "select * from [sheet1$]";
            var dr = E.ExcuteReader(f, SQL);
            while (dr.Read())
            {

                try
                {
                    if (dr["身份证号"] != DBNull.Value && dr["报名序号"] != DBNull.Value)
                    {
                        var m = new SR.M1();
                        m.IDCard = dr["身份证号"].ToString();
                        m.IDSN = dr["报名序号"].ToString();
                        m.Year = Convert.ToInt32(dr["教育年份"].ToString());
                        m.sType = string.Empty;
                        m.Phone = string.Empty;
                        m.Name = string.Empty;
                        m.SN = SN;
                        m.Type = 1;
                        var r1 = I.S(m);
                        log.Info("{0},{1}", r1.Code, r1.Message);
                    }
                }
                catch (Exception ex1)
                {
                    log.Info("{0},{1}", ex1.Message, ex1.StackTrace);
                }

            }
            dr.Close();
            dr.Dispose();

            return r;
        }
        public static DataTable B(string f, decimal SN)
        {

            var dt = new DataTable();
            dt.Columns.Add("身份证号", System.Type.GetType("System.String"));
            dt.Columns.Add("报名序号", System.Type.GetType("System.String"));
            dt.Columns.Add("教育年份", System.Type.GetType("System.String"));
            dt.Columns.Add("手机", System.Type.GetType("System.String"));
            dt.Columns.Add("状态");
            string SQL = "select * from [sheet1$]";
            var dr = E.ExcuteReader(f, SQL);
            while (dr.Read())
            {
                
                try
                {
                    if (dr["身份证号"] != DBNull.Value && dr["报名序号"] != DBNull.Value)
                    {
                        var m = new SR.M1();
                        m.IDCard = dr["身份证号"].ToString();
                        m.IDSN = dr["报名序号"].ToString();
                        m.Year = Convert.ToInt32(dr["教育年份"].ToString());
                        m.sType = string.Empty;
                        m.Phone = dr["手机"].ToString();
                        m.Name = string.Empty;
                        m.SN = SN;
                        m.Type = 1;
                        var r1 = I.S(m);

                        var row = dt.NewRow();
                        row["身份证号"] = dr["身份证号"].ToString();
                        row["报名序号"] = dr["报名序号"].ToString();
                        row["教育年份"] = dr["教育年份"].ToString();
                        row["手机"] = dr["手机"].ToString();
                        row["状态"] = U.N2S(r1.Code);
                        dt.Rows.Add(row);

                        if (r1.Code != 0)
                        {
                            log.Info("{0},{1}", r1.Code, r1.Message);
                        }
                    }
                }
                catch (Exception ex1)
                {
                    log.Info("{0},{1}", ex1.Message, ex1.StackTrace);
                }

            }
            dr.Close();
            dr.Dispose();

            return dt;
        }
    }
}
