using System;
using System.Data;
using System.Configuration;
using System.Collections.Generic;
using System.Web;
using System.Web.Security;
using System.Web.UI;
using System.Web.UI.WebControls;
using System.Web.UI.WebControls.WebParts;
using System.Web.UI.HtmlControls;
using System.Text;
using com.hooyes.crc.DAL;
using com.hooyes.crc.Model;
using com.hooyes.crc.BLL;

public partial class CRC_List : System.Web.UI.Page
{
    protected void Page_Load(object sender, EventArgs e)
    {
       
        if (!IsPostBack)
        {
            InitPage("");
            KeyWordInput.Attributes.Add("onkeydown", "return  GetFocus();");
            Welcome();
        }
    }
    /// <summary>
    /// 初始化
    /// </summary>
    /// <param name="xKeyWord"></param>
    protected void InitPage(string xKeyWord)
    {
        int page = Convert.ToInt32(Request.QueryString.Get("page"));
        page = (page <= 0) ? 1 : page;
        string keyWord = Request.QueryString.Get("keyWord");
        keyWord = (string.IsNullOrEmpty(xKeyWord)) ? keyWord : xKeyWord;
        Register reg = new Register();
        int CurrentPage = page;
        int PageSize = 20;
        int RecordsCount = reg.count(keyWord);
        PageSize = (PageSize > 0) ? PageSize : 1;
        int PagesCount = RecordsCount / PageSize;
        PagesCount = ((RecordsCount % PageSize) == 0) ? PagesCount : PagesCount + 1;
        CurrentPage = (CurrentPage > PagesCount) ? PagesCount : CurrentPage;
        List<CRCapply> xList = new List<CRCapply>();
        xList = reg.ListModel(PageSize, CurrentPage, keyWord);
        string HTMLTemplate = @"
     <tr>
     <td class='ListTableTdA1' width='1%'></td>
     <td class='ListTableTdB'>{1}</td>
     
     </tr>";
        StringBuilder sb = new StringBuilder();
        sb.Append("<table class='ListTable'>");
        //sb.Append(@"<tr class='ListHead'><td></td><td>公司名称</td></tr>");
        object[] param = new object[7];
        for (int i = 0; i < xList.Count; i++)
        {
            param[0] = i;
            param[1] = string.IsNullOrEmpty(xList[i].CompanyNameEn) ? xList[i].CompanyName : xList[i].CompanyNameEn;
            param[2] = xList[i].sn;
            sb.AppendFormat(HTMLTemplate, param);
        }
        sb.Append("</table>");
        xLiteral1.Text = sb.ToString();
        //分页导航
        string PageIndexUrl = null;
        StringBuilder PageIndexUrlSb = new StringBuilder();
        PageIndexUrlSb.Append("list.aspx?");
        PageIndexUrlSb.AppendFormat("keyWord={0}&", HttpUtility.UrlEncode(keyWord));
        PageIndexUrlSb.Append("page");
        PageIndexUrl = PageIndexUrlSb.ToString();
        pageLiteral1.Text = com.hooyes.crc.helper.Page.ShowPageEN(PageIndexUrl, "", RecordsCount, PageSize, CurrentPage, true, "");

        //显示关键字导航
        ShowTip(keyWord);
    }
    /// <summary>
    /// 提示
    /// </summary>
    /// <param name="msg"></param>
    protected void ShowTip(string msg)
    {
        StringBuilder sb = new StringBuilder();
        sb.Append("&nbsp;>> <a href='list.aspx'>All List</a>");
        if (!string.IsNullOrEmpty(msg))
        {
            sb.AppendFormat(" >> Key Word“<span class='highlight'> {0} </span>”", msg);
        }
        KeyWordInput.Text = msg;
        tipDiv.InnerHtml = sb.ToString();
        tipDiv.Visible = true;
    }
    /// <summary>
    /// 删除
    /// </summary>
    /// <param name="sender"></param>
    /// <param name="e"></param>
    protected void hooyesDeleteBtn_Click(object sender, EventArgs e)
    {
        try
        {
            string sn = Request["sn"].ToString();
            string[] vSn = sn.Split(',');
            Register reg = new Register();
            foreach (string guidString in vSn)
            {
                reg.delete(guidString);
            }
        }
        catch
        {
        }
        string keyWord = KeyWordInput.Text;
        InitPage(keyWord);

    }
    /// <summary>
    /// 搜索
    /// </summary>
    /// <param name="sender"></param>
    /// <param name="e"></param>
    protected void ButtonSearch_Click(object sender, EventArgs e)
    {

        string keyWord = KeyWordInput.Text;
        string IndexPageUrl = "list.aspx?keyWord={0}";
        IndexPageUrl = string.Format(IndexPageUrl, HttpUtility.UrlEncode(keyWord));
        Response.Redirect(IndexPageUrl);
    }
    protected void Welcome()
    {
        string msg = "";
        msg = string.Format(msg, "");
        WelComeLiteral1.Text = msg;
    }
}
