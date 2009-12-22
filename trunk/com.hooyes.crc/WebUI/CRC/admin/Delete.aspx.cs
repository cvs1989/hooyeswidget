using System;
using System.Data;
using System.Configuration;
using System.Collections;
using System.Web;
using System.Web.Security;
using System.Web.UI;
using System.Web.UI.WebControls;
using System.Web.UI.WebControls.WebParts;
using System.Web.UI.HtmlControls;
using com.hooyes.crc.DAL;
using com.hooyes.crc.BLL;
public partial class CRC_admin_Delete : PageBase
{
    protected void Page_Load(object sender, EventArgs e)
    {
        RequireLogin();
        delete();
    }
    protected void delete()
    {
        string sn = Request.QueryString.Get("sn");
        if (com.hooyes.crc.helper.Validate.GuidString(sn))
        {
            RegisterAdmin reg = new RegisterAdmin();
            bool flag = reg.delete(sn);
            goBack();
        }
        else
        {
            Response.Write("sn error");
        }
    }
    protected void goBack()
    {
        string refer = Request.UrlReferrer.ToString();
        Response.Redirect(refer);
    }
}
