using System;
using System.Data;
using System.Data.OleDb;

namespace com.hooyes.app.AngryApple
{
    /// <summary>
    /// Author: hooyes
    /// </summary>
    public class E
    {
        
        /// <summary>
        /// 返回一个OleDbDataReader对象
        /// </summary>
        /// <param name="excelFileFullPath">Excel文件完路径</param>
        /// <param name="CommondText">查询语句 例如：Select * from [sheet1$]</param>
        /// <returns></returns>
        public static OleDbDataReader ExcuteReader(string excelFileFullPath, string CommondText)
        {
            try
            {
                OleDbConnection oConn = connection(excelFileFullPath);
                oConn.Open();
                OleDbCommand oCmd = new OleDbCommand();
                oCmd.CommandText = CommondText;
                oCmd.Connection = oConn;
                OleDbDataReader oReader = oCmd.ExecuteReader();
                return oReader;
            }
            catch (Exception ex)
            {
                throw new Exception(ex.Message);
            }
            finally
            {
                // oConn.Close();
            }
        }
        /// <summary>
        /// 返回一个DataSet
        /// </summary>
        /// <param name="excelFileFullPath">Excel文件完路径</param>
        /// <param name="CommondText">查询语句  例如：Select * from [sheet1$]</param>
        /// <returns></returns>
        public static DataSet ExcuteDataset(string excelFileFullPath, string CommondText)
        {
            try
            {
                DataSet ds = new DataSet();
                OleDbConnection oConn = connection(excelFileFullPath);
                oConn.Open();
                OleDbCommand oCmd = new OleDbCommand();
                oCmd.CommandText = CommondText;
                oCmd.Connection = oConn;
                OleDbDataAdapter da = new OleDbDataAdapter();
                da.SelectCommand = oCmd;
                da.Fill(ds);
                return ds;
            }
            catch (Exception ex)
            {
                throw new Exception(ex.Message);
            }
            finally
            {
                //oConn.Close();
            }
        }

        public static DataTable getExcelSheets(string excelFileFullPath)
        {
            try
            {
                DataTable dt = new DataTable();
                OleDbConnection oConn = connection(excelFileFullPath);
                oConn.Open();
                dt = oConn.GetOleDbSchemaTable(OleDbSchemaGuid.Tables, new object[] { null, null, null, "TABLE" });
                return dt;
            }
            catch (Exception ex)
            {
                throw new Exception(ex.Message);
            }
            finally
            {
                //oConn.Close();
            }
        }

        private static OleDbConnection connection(string ecellFileFullPath)
        {
            string connstr = @"Provider=Microsoft.ACE.OLEDB.12.0;Data Source={0};Extended Properties=Excel 8.0;";
                connstr = string.Format(connstr, ecellFileFullPath);
                OleDbConnection oConn = new OleDbConnection(connstr);
                return oConn;           

        }
    }
}
