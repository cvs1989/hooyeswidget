using System;
using System.Collections.Generic;
using System.Text;
using System.Data;
using System.Data.OleDb;

namespace hooyes.DAL
{
    /// <summary>
    /// �����MSSQL���ݿ�һȥ����һ��Excel�ļ�����
    /// Author: hooyes
    /// </summary>
    public class ExcelHelper
    {
        
        /// <summary>
        /// ����һ��OleDbDataReader����
        /// </summary>
        /// <param name="excelFileFullPath">Excel�ļ���·��</param>
        /// <param name="CommondText">��ѯ��� ���磺Select * from [sheet1$]</param>
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
        /// ����һ��DataSet
        /// </summary>
        /// <param name="excelFileFullPath">Excel�ļ���·��</param>
        /// <param name="CommondText">��ѯ���  ���磺Select * from [sheet1$]</param>
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

        
        //public static int ExecuteNonQuery(string excelFileFullPath, string CommondText)
        //{
        //    OleDbConnection oConn = connection(excelFileFullPath);
        //    oConn.Open();
        //    OleDbCommand oCmd = new OleDbCommand();
        //    oCmd.CommandText = CommondText;
        //    oCmd.Connection = oConn;
        //    return  oCmd.ExecuteNonQuery();
        //}
        private static OleDbConnection connection(string ecellFileFullPath)
        {
            string connstr = @"Provider=Microsoft.ACE.OLEDB.12.0;Data Source={0};Extended Properties=Excel 8.0;";
                connstr = string.Format(connstr, ecellFileFullPath);
                OleDbConnection oConn = new OleDbConnection(connstr);
                return oConn;           

        }
    }
}
