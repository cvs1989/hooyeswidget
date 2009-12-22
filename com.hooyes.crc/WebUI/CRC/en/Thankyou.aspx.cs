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
using System.Text;
public partial class CRC_Thankyou : System.Web.UI.Page
{
    protected void Page_Load(object sender, EventArgs e)
    {
        InitPage();
    }
    protected void InitPage()
    {
        string msg = Request.QueryString.Get("msg");
        string UrlBack = Request.QueryString.Get("UrlBack");
        string UrlNext = Request.QueryString.Get("UrlNext");
        string UrlBackWord = Request.QueryString.Get("UrlBackWord");
        string UrlNextWord = Request.QueryString.Get("UrlNextWord");

        StringBuilder sb = new StringBuilder();

        sb.AppendFormat("<div class='ThankyouTitle'><img src='img/89.gif' />{0}</div>", msg);
        sb.AppendFormat("<div class='ThankyouUrl'><span class='UrlBack'><input onclick='javascript:parent.window.location.href=\"{0}\"'type='button' value='{2}' /></span><span class='UrlNext'><input onclick='{1}' type='button' value='{3}' /></span>", UrlBack, UrlNext, UrlBackWord, UrlNextWord);
        vLiteral1.Text = sb.ToString();
    }
}
