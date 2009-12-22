using System;
using System.Data;
using System.Data.OleDb;
using System.Configuration;
using System.Collections;
using System.Web;
using System.Web.Security;
using System.Web.UI;
using System.Web.UI.WebControls;
using System.Web.UI.WebControls.WebParts;
using System.Web.UI.HtmlControls;
using System.Xml;
using com.hooyes.crc;
using com.hooyes.crc.DAL;
using com.hooyes.crc.Model;
using com.hooyes.crc.BLL;

public partial class CRC_Default : PageBase
{
    protected void Page_Load(object sender, EventArgs e)
    {


        Register reg = new Register();

        Response.Write(reg.count("北'京"));

        GoThankYouUrl("感谢您注册!", "register.aspx", "admin/default.aspx", "继续注册", "进入管理端");


    }
}
