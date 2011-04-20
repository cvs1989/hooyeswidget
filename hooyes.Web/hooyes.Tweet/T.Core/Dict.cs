using System;
using System.Collections.Generic;
using System.Text;
using System.Data.SqlClient;
using System.Data;

namespace Tweet.Core
{
    public class Dict
    {
        public static int Save(DictEntity dict)
        {
            string sqlSelect = "select count(ID) from OAuth_Dict where App=@App and [Key]=@Key and UserID=@UserID";
            string sql = @"INSERT INTO [dbo].[OAuth_Dict]([App], [Key], [Value], [UserID],[UpdateTime]) 
                                  VALUES(@App, @Key, @Value, @UserID,getdate())";
            int count = (int)SqlHelper.ExecuteScalar(SqlHelper.Local, CommandType.Text, sqlSelect,
                 new SqlParameter("@App", dict.App)
                 , new SqlParameter("@Key", dict.Key)
                 , new SqlParameter("@UserID", dict.UserID)
                 );
            if (count > 0)
            {
                sql = "update OAuth_Dict set [Value]=@Value,UpdateTime=getdate() where App=@App and [Key]=@Key and UserID=@UserID";
            }
            int x = SqlHelper.ExecuteNonQuery(SqlHelper.Local, CommandType.Text, sql,
               new SqlParameter("@App", dict.App)
                 , new SqlParameter("@Key", dict.Key)
                 , new SqlParameter("@UserID", dict.UserID)
                 , new SqlParameter("@Value", dict.Value)
                 );

            return x;

        }
        public static int Delete(DictEntity dict)
        {
            string sqlDelete = "delete from OAuth_Dict where App=@App and [Key]=@Key and UserID=@UserID";
            int x = SqlHelper.ExecuteNonQuery(SqlHelper.Local, CommandType.Text, sqlDelete,
               new SqlParameter("@App", dict.App)
                 , new SqlParameter("@Key", dict.Key)
                 , new SqlParameter("@UserID", dict.UserID)
                 );

            return x;
        }
        public static T Get<T>(DictEntity dict)
        {
            string sqlSelect = "select [Value] from OAuth_Dict where App=@App and [Key]=@Key and UserID=@UserID";
            object obj = SqlHelper.ExecuteScalar(SqlHelper.Local, CommandType.Text, sqlSelect,
                new SqlParameter("@App", dict.App)
                , new SqlParameter("@Key", dict.Key)
                , new SqlParameter("@UserID", dict.UserID)
                );
            return (T)obj;
        }
        public static Dictionary<string, string> Get(string App, string UserID)
        {
            Dictionary<string, string> dt = new Dictionary<string, string>();
            string sqlSelect = "select [key],[value] from OAuth_Dict where App=@App and UserID=@UserID";
            SqlDataReader dr = SqlHelper.ExecuteReader(SqlHelper.Local, CommandType.Text, sqlSelect,
                new SqlParameter("@App", App)
                , new SqlParameter("@UserID",UserID)
                );
            while (dr.Read())
            {
                if (dr["Key"] != DBNull.Value)
                {
                    string v = string.Empty;
                    if (dr["Value"] != DBNull.Value)
                        v = Convert.ToString(dr["Value"]);
                    dt.Add(Convert.ToString(dr["Key"]), v);
                }
            }
            dr.Close();
            return dt;
        }
    }
}
