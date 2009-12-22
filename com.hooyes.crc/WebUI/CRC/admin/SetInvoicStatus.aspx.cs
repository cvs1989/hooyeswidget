using System;
using System.Data;
using System.Configuration;
using System.Collections;
using System.Web;
using System.Web.Security;
using com.hooyes.crc.DAL;
using com.hooyes.crc.Model;
using com.hooyes.crc.BLL;
public partial class CRC_admin_SetInvoicStatus :PageBase
{
    protected void Page_Load(object sender, EventArgs e)
    {
        RequireLogin();
        try
        {
            Start();
        }
        catch (Exception ex)
        {
            Response.Write("{flag:false,msg:'" + ex.Message + "'}");
        }
    }
    protected void Start()
    {
        string sn = Request["sn"];
        string invoiceStatus = Request["invoice"];
        bool invoice = false;
        if (invoiceStatus.ToLower() == "true")
        {
            invoice = true;
        }
        if (com.hooyes.crc.helper.Validate.GuidString(sn))
        {
            RegisterAdmin reg = new RegisterAdmin();
            bool flag = reg.SetInvoicStatus(sn, invoice);
            ResponseIt();
        }
        else
        {
            Response.Write("{flag:false,msg:'sn error'}");
        }
    }
    protected void ResponseIt()
    {
        string R = "{flag:true,msg:'ok'}";
        Response.Write(R);
    }
}
