using System;
using System.Data;
using System.Data.OleDb;
using System.Collections.Generic;
using System.Text;
using com.hooyes.crc.Model;
namespace com.hooyes.crc.DAL
{
    public class Auth
    {
        public bool Login(string UserName, string PassWord)
        {
            bool rValue = false;
            int rResult = 0;
            UserName=UserName.Replace("'","");
            PassWord=PassWord.Replace("'","");
            string sql = "select count(1) from crc_admin where UserName=@UserName and Password=@Password";
            rResult = Convert.ToInt32(AccessHelper.ExecuteScalar(AccessHelper.conn, sql,
                new OleDbParameter("@UserName", UserName),
                new OleDbParameter("@Password", PassWord)));
            rValue = (rResult > 0);
            return rValue;
        }
        public bool UpdatePassword(string UserName, string PassWord, string NewPassWord)
        {
            bool rValue = true;
            int rResult = 0;
            if (Login(UserName, PassWord))
            {
                try
                {
                    UserName = UserName.Replace("'", "");
                    PassWord = PassWord.Replace("'", "");
                    string sql = "update crc_admin set Password=@NewPassWord  where UserName=@UserName and Password=@Password";
                    rResult = Convert.ToInt32(AccessHelper.ExecuteNonQuery(AccessHelper.conn, sql,
                        new OleDbParameter("@NewPassWord", NewPassWord),
                        new OleDbParameter("@UserName", UserName),
                        new OleDbParameter("@Password", PassWord)));
                }
                catch
                {
                    rValue = false;
                }
            }
            else
            {
                rValue = false;
            }
            
            return rValue;
        }

    }
}
