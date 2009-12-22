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

public partial class CRC_admin_Login : System.Web.UI.Page
{
    protected void Page_Load(object sender, EventArgs e)
    {

    }
    protected void hooyesLoginBtn_Click(object sender, EventArgs e)
    {
        string UID = TextBoxUID.Text;
        string PWD = TextBoxPWD.Text;
        if (new Auth().Login(UID, PWD))
        {
            Session["hooyes_CRC_Admin"] = UID;
            goTargert();
        }
        else
        {

        }

    }
    protected void goTargert()
    {
        string refUrl = Request.QueryString.Get("ref");
        if (string.IsNullOrEmpty(refUrl))
        {
            refUrl = "Default.aspx";
        }
        Response.Redirect(refUrl);
    }
}
