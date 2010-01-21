using System;
using System.Data;
using System.Data.OleDb;
using System.Collections.Generic;
using System.Text;
using com.hooyes.crc.Model;

namespace com.hooyes.crc.DAL
{
    public class Register
    {
        public bool insert(Model.CRCapply model)
        {
            bool rValue = true;
            try
            {
                string sql = @"insert into CRC_apply(sn,CompanyName,CompanyNameEn,CompanyAddress,Contact,PostCode,Phone,CellPhone,Fax,WebSite,Email,CompanyType,ProductType,Suggestion,Vistors,Pay,Invoice)
                             values(@sn,@CompanyName,@CompanyNameEn,@CompanyAddress,@Contact,@PostCode,@Phone,@CellPhone,@Fax,@WebSite,@Email,@CompanyType,@ProductType,@Suggestion,Vistors,Pay,Invoice)";

                AccessHelper.ExecuteNonQuery(AccessHelper.conn, sql,
                    new OleDbParameter("@sn",model.sn),
                    new OleDbParameter("@CompanyName", model.CompanyName),
                    new OleDbParameter("@CompanyNameEn",model.CompanyNameEn),
                    new OleDbParameter("@CompanyAddress", model.CompanyAddress),
                    new OleDbParameter("@Contact",model.Contact),
                    new OleDbParameter("@PostCode",model.PostCode),
                    new OleDbParameter("@Phone",model.Phone),
                    new OleDbParameter("@CellPhone",model.CellPhone),
                    new OleDbParameter("@Fax",model.Fax),
                    new OleDbParameter("@WebSite",model.WebSite),
                    new OleDbParameter("@Email",model.Email),
                    new OleDbParameter("@CompanyType", model.CompanyType),
                    new OleDbParameter("@ProductType", model.ProductType),
                    new OleDbParameter("@Suggestion",model.Suggestion),
                    new OleDbParameter("@Vistors",model.Vistors),
                    new OleDbParameter("@Pay",model.Pay),
                    new OleDbParameter("@Invoice",model.Invoice)
                   // new OleDbParameter("@RegisterTime",model.RegisterTime)
                    );
            }
            catch
            {
                rValue = false;
            }
            return rValue;
        }
        public bool update(Model.CRCapply model)
        {
            bool rValue = true;
            try
            {
                string sql = @"update CRC_apply set CompanyName=@CompanyName,CompanyNameEn=@CompanyNameEn,CompanyAddress=@CompanyAddress,Contact=@Contact,PostCode=@PostCode,Phone=@Phone,CellPhone=@CellPhone,Fax=@Fax,WebSite=@WebSite,Email=@Email,CompanyType=@CompanyType,ProductType=@ProductType,Suggestion=@Suggestion,Vistors=@Vistors,Pay=@Pay,Invoice=@Invoice
                             where sn=@sn";                            
                AccessHelper.ExecuteNonQuery(AccessHelper.conn, sql,
                    new OleDbParameter("@CompanyName", model.CompanyName),
                    new OleDbParameter("@CompanyNameEn", model.CompanyNameEn),
                    new OleDbParameter("@CompanyAddress", model.CompanyAddress),
                    new OleDbParameter("@Contact", model.Contact),
                    new OleDbParameter("@PostCode", model.PostCode),
                    new OleDbParameter("@Phone", model.Phone),
                    new OleDbParameter("@CellPhone",model.CellPhone),
                    new OleDbParameter("@Fax", model.Fax),
                    new OleDbParameter("@WebSite", model.WebSite),
                    new OleDbParameter("@Email", model.Email),
                    new OleDbParameter("@CompanyType",model.CompanyType),
                    new OleDbParameter("@ProductType", model.ProductType),
                    new OleDbParameter("@Suggestion", model.Suggestion),
                    new OleDbParameter("@Vistors",model.Vistors),
                    new OleDbParameter("@Pay",model.Pay),
                    new OleDbParameter("@Invoice",model.Invoice),
                    new OleDbParameter("@sn", model.sn)
                    );
            }
            catch
            {
                rValue = false;
            }
            return rValue;
        }
        public bool delete(string sn)
        {
            bool rValue = true;
            try
            {
                string sql = @"delete from CRC_apply where sn=@sn";
                AccessHelper.ExecuteNonQuery(AccessHelper.conn, sql,
                    new OleDbParameter("@sn", sn));
            }
            catch
            {
                rValue = false;
            }
            return rValue;
        }
        public CRCapply model(string sn)
        {
            CRCapply model = new CRCapply();
            string sql = "select * from CRC_apply where sn=@sn";
            OleDbDataReader dr=AccessHelper.ExecuteReader(AccessHelper.conn,sql,
               new OleDbParameter("@sn", sn));
            if (dr.Read())
            {
                model.ID = Convert.ToDecimal(dr["ID"]);
                model.sn = Convert.ToString(dr["sn"]);
                model.CompanyAddress = Convert.ToString(dr["CompanyAddress"]);
                model.CompanyName = Convert.ToString(dr["CompanyName"]);
                model.CompanyNameEn = Convert.ToString(dr["CompanyNameEn"]);
                model.WebSite = Convert.ToString(dr["WebSite"]);
                model.Phone = Convert.ToString(dr["Phone"]);
                model.CellPhone = Convert.ToString(dr["CellPhone"]);
                model.Fax = Convert.ToString(dr["Fax"]);
                model.PostCode = Convert.ToString(dr["PostCode"]);
                model.Suggestion = Convert.ToString(dr["Suggestion"]);
                model.Contact = Convert.ToString(dr["Contact"]);
                model.Email = Convert.ToString(dr["Email"]);
                model.Vistors = Convert.ToString(dr["Vistors"]);
                model.RegisterTime = Convert.ToDateTime(dr["RegisterTime"]);
                model.ProductType = Convert.ToString(dr["ProductType"]);
                model.CompanyType = Convert.ToString(dr["CompanyType"]);
                model.Pay = Convert.ToBoolean(dr["Pay"]);
                model.Invoice = Convert.ToBoolean(dr["Invoice"]);

            }
            dr.Close();
            dr.Dispose();

            return model;
        }
        public List<CRCapply> ListModel()
        {

            List<CRCapply> lt = new List<CRCapply>();
            string sql = "select * from CRC_apply order by ID desc";
            OleDbDataReader dr = AccessHelper.ExecuteReader(AccessHelper.conn, sql);
            while (dr.Read())
            {
                CRCapply model = new CRCapply();
                model.ID = Convert.ToDecimal(dr["ID"]);
                model.sn = Convert.ToString(dr["sn"]);
                model.CompanyAddress = Convert.ToString(dr["CompanyAddress"]);
                model.CompanyName = Convert.ToString(dr["CompanyName"]);
                model.CompanyNameEn = Convert.ToString(dr["CompanyNameEn"]);
                model.WebSite = Convert.ToString(dr["WebSite"]);
                model.Phone = Convert.ToString(dr["Phone"]);
                model.CellPhone = Convert.ToString(dr["CellPhone"]);
                model.Fax = Convert.ToString(dr["Fax"]);
                model.PostCode = Convert.ToString(dr["PostCode"]);
                model.Suggestion = Convert.ToString(dr["Suggestion"]);
                model.Contact = Convert.ToString(dr["Contact"]);
                model.Email = Convert.ToString(dr["Email"]);
                model.Vistors = Convert.ToString(dr["Vistors"]);
                model.RegisterTime = Convert.ToDateTime(dr["RegisterTime"]);
                model.ProductType = Convert.ToString(dr["ProductType"]);
                model.CompanyType = Convert.ToString(dr["CompanyType"]);
                model.Pay = Convert.ToBoolean(dr["Pay"]);
                model.Invoice = Convert.ToBoolean(dr["Invoice"]);
                lt.Add(model);
            }
            dr.Close();
            dr.Dispose();

            return lt;
        }
        public List<CRCapply> ListModel(int PageSize, int CurrentPage)
        {
            int RecordsCount = count();
            PageSize = (PageSize > 0) ? PageSize : 1;
            int PagesCount = RecordsCount / PageSize;
            PagesCount = ((RecordsCount % PageSize) == 0) ? PagesCount : PagesCount + 1;
            CurrentPage = (CurrentPage > PagesCount) ? PagesCount : CurrentPage;
            List<CRCapply> lt = new List<CRCapply>();
            StringBuilder sb = new StringBuilder();
            sb.AppendFormat("select top {0} * from CRC_apply ", PageSize);
            if (CurrentPage > 1)
            {
                sb.AppendFormat(" where ID not in(select top {0} id from CRC_apply order by id desc)", (CurrentPage - 1) * PageSize);
            }
            sb.Append(" order by ID desc");
            string sql = sb.ToString();
            OleDbDataReader dr = AccessHelper.ExecuteReader(AccessHelper.conn, sql);
            while (dr.Read())
            {
                CRCapply model = new CRCapply();
                model.ID = Convert.ToDecimal(dr["ID"]);
                model.sn = Convert.ToString(dr["sn"]);
                model.CompanyAddress = Convert.ToString(dr["CompanyAddress"]);
                model.CompanyName = Convert.ToString(dr["CompanyName"]);
                model.CompanyNameEn = Convert.ToString(dr["CompanyNameEn"]);
                model.WebSite = Convert.ToString(dr["WebSite"]);
                model.Phone = Convert.ToString(dr["Phone"]);
                model.CellPhone = Convert.ToString(dr["CellPhone"]);
                model.Fax = Convert.ToString(dr["Fax"]);
                model.PostCode = Convert.ToString(dr["PostCode"]);
                model.Suggestion = Convert.ToString(dr["Suggestion"]);
                model.Contact = Convert.ToString(dr["Contact"]);
                model.Email = Convert.ToString(dr["Email"]);
                model.Vistors = Convert.ToString(dr["Vistors"]);
                model.RegisterTime = Convert.ToDateTime(dr["RegisterTime"]);
                model.ProductType = Convert.ToString(dr["ProductType"]);
                model.CompanyType = Convert.ToString(dr["CompanyType"]);
                model.Pay = Convert.ToBoolean(dr["Pay"]);
                model.Invoice = Convert.ToBoolean(dr["Invoice"]);
                lt.Add(model);
            }
            dr.Close();
            dr.Dispose();

            return lt;
        }
        public List<CRCapply> ListModel(int PageSize, int CurrentPage, string keyWord)
        {
            if (!string.IsNullOrEmpty(keyWord))
            {
                keyWord = keyWord.Replace("'", "");
                int RecordsCount = count(keyWord);
                PageSize = (PageSize > 0) ? PageSize : 1;
                int PagesCount = RecordsCount / PageSize;
                PagesCount = ((RecordsCount % PageSize) == 0) ? PagesCount : PagesCount + 1;
                CurrentPage = (CurrentPage > PagesCount) ? PagesCount : CurrentPage;
                List<CRCapply> lt = new List<CRCapply>();
                StringBuilder sb = new StringBuilder();
                sb.AppendFormat("select top {0} * from CRC_apply ", PageSize);
                sb.AppendFormat(" where CompanyName like '%{0}%'", keyWord);
                if (CurrentPage > 1)
                {
                    sb.AppendFormat(" and ID not in(select top {0} id from CRC_apply order by id desc)", (CurrentPage - 1) * PageSize);
                }
                sb.Append(" order by ID desc");
                string sql = sb.ToString();
                OleDbDataReader dr = AccessHelper.ExecuteReader(AccessHelper.conn, sql);
                while (dr.Read())
                {
                    CRCapply model = new CRCapply();
                    model.ID = Convert.ToDecimal(dr["ID"]);
                    model.sn = Convert.ToString(dr["sn"]);
                    model.CompanyAddress = Convert.ToString(dr["CompanyAddress"]);
                    model.CompanyName = Convert.ToString(dr["CompanyName"]);
                    model.CompanyNameEn = Convert.ToString(dr["CompanyNameEn"]);
                    model.WebSite = Convert.ToString(dr["WebSite"]);
                    model.Phone = Convert.ToString(dr["Phone"]);
                    model.CellPhone = Convert.ToString(dr["CellPhone"]);
                    model.Fax = Convert.ToString(dr["Fax"]);
                    model.PostCode = Convert.ToString(dr["PostCode"]);
                    model.Suggestion = Convert.ToString(dr["Suggestion"]);
                    model.Contact = Convert.ToString(dr["Contact"]);
                    model.Email = Convert.ToString(dr["Email"]);
                    model.Vistors = Convert.ToString(dr["Vistors"]);
                    model.RegisterTime = Convert.ToDateTime(dr["RegisterTime"]);
                    model.ProductType = Convert.ToString(dr["ProductType"]);
                    model.CompanyType = Convert.ToString(dr["CompanyType"]);
                    model.Pay = Convert.ToBoolean(dr["Pay"]);
                    model.Invoice = Convert.ToBoolean(dr["Invoice"]);
                    lt.Add(model);
                }
                dr.Close();
                dr.Dispose();

                return lt;
            }
            else
            {
                return ListModel(PageSize, CurrentPage);
            }
        }
        public int count()
        {
            int rValue = 0;
            string sql = "select count(1) from CRC_apply";
            rValue = Convert.ToInt32(AccessHelper.ExecuteScalar(AccessHelper.conn, sql));
            return rValue;
        }
        public int count(string keyWord)
        {
            if (!string.IsNullOrEmpty(keyWord))
            {
                keyWord = keyWord.Replace("'", "");
                int rValue = 0;
                string sql = "select count(1) from CRC_apply where CompanyName like '%{0}%'";
                sql = string.Format(sql, keyWord);
                rValue = Convert.ToInt32(AccessHelper.ExecuteScalar(AccessHelper.conn, sql));
                return rValue;
            }
            else
            {
                return count();
            }
        }
        /// <summary>
        /// 检测公司名称是否已存在
        /// </summary>
        /// <param name="CompanyName"></param>
        /// <returns></returns>
        public bool exist(string CompanyName)
        {
            bool Rvalue = false;
            if (!string.IsNullOrEmpty(CompanyName))
            {
                CompanyName = CompanyName.Replace("'", "");

                int xValue = 0;
                string sql = "select count(1) from CRC_apply where CompanyName = '{0}'";
                sql = string.Format(sql, CompanyName);
                xValue = Convert.ToInt32(AccessHelper.ExecuteScalar(AccessHelper.conn, sql));
                Rvalue = (xValue > 0);
            }
            else
            {
                Rvalue = true;
            }
            return Rvalue;
        }

    }
}
