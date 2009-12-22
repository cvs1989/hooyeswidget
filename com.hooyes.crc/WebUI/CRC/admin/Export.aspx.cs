using System;
using System.Collections.Generic;
using System.Web;
using System.Text;
using com.hooyes.crc.DAL;
using com.hooyes.crc.Model;
using com.hooyes.crc.BLL;


public partial class CRC_admin_Export :PageBase
{
    protected void Page_Load(object sender, EventArgs e)
    {
        RequireLogin();
        List<com.hooyes.crc.Model.CRCapply> Company = new List<CRCapply>();
        com.hooyes.crc.DAL.RegisterAdmin reg = new RegisterAdmin();
        Company = reg.ListModel();
        StringBuilder sb = new StringBuilder();
        foreach (CRCapply cr in Company)
        {
            string Vistors = cr.Vistors;
            string[] vSpace = { "|#|" };
            string[] vComma = { "," };
            string[] vS = Vistors.Split(vSpace, StringSplitOptions.None);
            string[] vName = vS[0].Split(vComma, StringSplitOptions.None);
            string[] vGender = vS[1].Split(vComma, StringSplitOptions.None);
            string[] vTitle = vS[2].Split(vComma, StringSplitOptions.None);
            string[] vPhone = vS[3].Split(vComma, StringSplitOptions.None);
            string[] vCellPhone = vS[4].Split(vComma, StringSplitOptions.None);
            for (int i = 0; i < vName.Length; i++)
            {
                if (!string.IsNullOrEmpty(vName[i]))
                {
                    string CSVLine = vName[i] + "," + Sex(vGender[i]) + "," + vTitle[i] + "," + vPhone[i] + "," + vCellPhone[i];
                    CSVLine += "," + NoComma(cr.CompanyName) + "," + NoComma(cr.CompanyNameEn) + "," + NoComma(cr.Contact) + "," + NoComma(cr.CompanyType) + "," + NoComma(cr.ProductType) + "," + NoComma(cr.Email) + "," + NoComma(cr.Phone) + "," + NoComma(cr.CellPhone) + "," + NoComma(cr.Fax) + "," + NoComma(cr.CompanyAddress) + "," + NoComma(cr.PostCode);
                    CSVLine += "," + NoComma(cr.WebSite) + "," + NoComma(cr.Suggestion);
                    CSVLine+= "," + Invoice(cr.Invoice)+","+Pay(cr.Pay)+","+ NoComma(cr.RegisterTime.ToString("yyyy-MM-dd"));
                    sb.AppendLine(CSVLine);
                }
            }

        }
       Export(sb.ToString());
    }
    protected string Sex(string str)
    {
        if (!string.IsNullOrEmpty(str))
        {
            if (str.ToLower() == "male")
            {
                return "男";
            }
            else
            {
                return "女";
            }
        }
        else
        {
            return str;
        }
    }
    protected string Pay(bool b)
    {
        return b ? "已付费" : "未付费";
    }
    protected string Invoice(bool b)
    {
        return b ? "需要发票" : "不需要发票";
    }
    protected string NoComma(string str)
    {
        if (!string.IsNullOrEmpty(str))
        {
            return str.Replace(",", "，");
        }
        else
        {
            return str;
        }
    }
    protected void Export(string str)
    {
        string fileName = DateTime.Now.ToString("CRCyyyyMMdd")+".csv";
        Response.Clear();
        Response.Buffer = true;
        EnableViewState = false;
        Response.Charset = "utf-8";
        Response.ContentEncoding = System.Text.Encoding.GetEncoding("GB2312");
        Response.AppendHeader("Content-disposition", "attachment;filename=" + HttpUtility.UrlEncode(fileName, Encoding.UTF8));
        Response.Write(str);
        Response.Flush();
        Response.End();
        Response.Close();
    }
}
