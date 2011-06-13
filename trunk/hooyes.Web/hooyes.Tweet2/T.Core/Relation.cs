using System;
using System.Collections.Generic;
using System.Text;
using System.Data.SqlClient;
using System.Data;

namespace Tweet.Core
{
    public class Relation
    {
        public static int Save(RelationEntity dict)
        {
            string sqlSelect = "select count(ID) from OAuth_Relation where App=@App and UserID=@UserID and  SubApp=@SubApp";
            string sql = @"INSERT INTO [dbo].[OAuth_Relation]([App], [UserID],[SubApp], [SubUserID],[UpdateTime],[Enabled],[UpdateCount]) 
                                  VALUES(@App, @UserID,@SubApp, @SubUserID,getdate(),1,0)";
            int count = (int)SqlHelper.ExecuteScalar(SqlHelper.Local, CommandType.Text, sqlSelect,
                 new SqlParameter("@App", dict.App)
                 , new SqlParameter("@UserID", dict.UserID)
                 , new SqlParameter("@SubApp", dict.SubApp)
                 //, new SqlParameter("@SubUserID", dict.SubUserID)
                 );
            if (count > 0)
            {
                sql = "update OAuth_Relation set SubUserID=@SubUserID,Enabled=1,UpdateCount=UpdateCount+1,UpdateTime=getdate()  where App=@App and UserID=@UserID and SubApp=@SubApp ";
            }
            int x = SqlHelper.ExecuteNonQuery(SqlHelper.Local, CommandType.Text, sql,
                new SqlParameter("@App", dict.App)
                 , new SqlParameter("@UserID", dict.UserID)
                 , new SqlParameter("@SubApp", dict.SubApp)
                , new SqlParameter("@SubUserID", dict.SubUserID)
                 );

            return x;

        }
        public static int Delete(RelationEntity dict)
        {
            string sqlDelete = "delete from OAuth_Relation where App=@App and UserID=@UserID and  SubApp=@SubApp";
            int x = SqlHelper.ExecuteNonQuery(SqlHelper.Local, CommandType.Text, sqlDelete,
                 new SqlParameter("@App", dict.App)
                 , new SqlParameter("@UserID", dict.UserID)
                 , new SqlParameter("@SubApp", dict.SubApp)
                 );

            return x;
        }
        public static int Enabled(RelationEntity dict)
        {
            string sqlDelete = "update OAuth_Relation set Enabled=@Enabled,UpdateCount=UpdateCount+1,UpdateTime=getdate() where App=@App and UserID=@UserID and  SubApp=@SubApp";
            int x = SqlHelper.ExecuteNonQuery(SqlHelper.Local, CommandType.Text, sqlDelete,
                 new SqlParameter("@App", dict.App)
                 , new SqlParameter("@UserID", dict.UserID)
                 , new SqlParameter("@SubApp", dict.SubApp)
                 , new SqlParameter("@Enabled",dict.Enabled)
                 );

            return x;
        }
        public static RelationEntity Get(RelationEntity dict)
        {
            RelationEntity Entity=new RelationEntity();
            string sqlSelect = "select * from OAuth_Relation where App=@App and UserID=@UserID and  SubApp=@SubApp";
            SqlDataReader dr = SqlHelper.ExecuteReader(SqlHelper.Local, CommandType.Text, sqlSelect,
                 new SqlParameter("@App", dict.App)
                 , new SqlParameter("@UserID", dict.UserID)
                 , new SqlParameter("@SubApp", dict.SubApp)
                );
            if (dr.Read())
            {
                Entity.ID = Convert.ToInt32(dr["ID"]);
                if (dr["App"] != DBNull.Value)
                Entity.App = Convert.ToString(dr["App"]);
                if (dr["UserID"] != DBNull.Value)
                Entity.UserID = Convert.ToString(dr["UserID"]);
                if (dr["SubApp"] != DBNull.Value)
                Entity.SubApp = Convert.ToString(dr["SubApp"]);
                if (dr["SubUserID"] != DBNull.Value)
                Entity.SubUserID = Convert.ToString(dr["SubUserID"]);
                
            }
            dr.Close();
            return Entity;
        }
        public static List<RelationEntity> Get()
        {
            List<RelationEntity> lt = new List<RelationEntity>();
            string sqlSelect = "select * from OAuth_Relation where Enabled=1 order by id asc";
            SqlDataReader dr = SqlHelper.ExecuteReader(SqlHelper.Local, CommandType.Text, sqlSelect
                );
            while (dr.Read())
            {
                RelationEntity Entity = new RelationEntity();
                Entity.ID = Convert.ToInt32(dr["ID"]);
                if (dr["App"] != DBNull.Value)
                    Entity.App = Convert.ToString(dr["App"]);
                if (dr["UserID"] != DBNull.Value)
                    Entity.UserID = Convert.ToString(dr["UserID"]);
                if (dr["SubApp"] != DBNull.Value)
                    Entity.SubApp = Convert.ToString(dr["SubApp"]);
                if (dr["SubUserID"] != DBNull.Value)
                    Entity.SubUserID = Convert.ToString(dr["SubUserID"]);

                lt.Add(Entity);

            }
            dr.Close();
            return lt;
        }

    }
}
