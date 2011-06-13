using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Data;
using System.Data.SqlClient;
using com.hooyes.DBUtility;

namespace Tweet
{
    public class db
    {
        public static long MaxTimeline(string UserID, long MaxTimeline)
        {
            string sqlSelect = "select count(UserID) from OAuth_Timeline where UserID=@UserID";
            string sql = "INSERT INTO [dbo].[OAuth_Timeline]([UserID], [MaxTimeline]) VALUES(@UserID, @MaxTimeline)";
            int count = (int)SqlHelper.ExecuteScalar(SqlHelper.Local, CommandType.Text, sqlSelect,
                 new SqlParameter("@UserID", UserID));
            if (count > 0)
            {
                sql = "update OAuth_Timeline set MaxTimeline=@MaxTimeline where UserID=@UserID";
            }
            int x = SqlHelper.ExecuteNonQuery(SqlHelper.Local, CommandType.Text, sql,
                new SqlParameter("@UserID", UserID),
                new SqlParameter("@MaxTimeline", MaxTimeline));

            return (long)x;
        }

        public static long MaxTimeline(string UserID)
        {
            long r = 0;
            string sqlSelect = "select MaxTimeline from OAuth_Timeline where UserID=@UserID";
           object r1 = SqlHelper.ExecuteScalar(SqlHelper.Local, CommandType.Text, sqlSelect,
                new SqlParameter("@UserID", UserID));
           r = (long)Convert.ToDecimal(r1);
            return r;
        }

    }
}
