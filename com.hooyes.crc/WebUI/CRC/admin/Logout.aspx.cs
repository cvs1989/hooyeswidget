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
using com.hooyes.crc.BLL;
public partial class CRC_admin_Logout : PageBase
{
    protected void Page_Load(object sender, EventArgs e)
    {
        Logout();
    }
}
