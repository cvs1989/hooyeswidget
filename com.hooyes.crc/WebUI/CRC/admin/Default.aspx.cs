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

public partial class CRC_admin_Default : PageBase
{
    protected void Page_Load(object sender, EventArgs e)
    {
        RequireLogin();
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
        RegisterAdmin reg = new RegisterAdmin();
        int CurrentPage = page;
        int PageSize =20;
        int RecordsCount = reg.count(keyWord);
        PageSize = (PageSize > 0) ? PageSize : 1;
        int PagesCount = RecordsCount / PageSize;
        PagesCount = ((RecordsCount % PageSize) == 0) ? PagesCount : PagesCount + 1;
        CurrentPage = (CurrentPage > PagesCount) ? PagesCount : CurrentPage;
        List<CRCapply> xList = new List<CRCapply>();
        xList = reg.ListModel(PageSize, CurrentPage, keyWord);
        string HTMLTemplate = @"
     <tr>
     <td class='ListTableTdA'><input name='sn' id=""Checkbox{0}"" value='{2}' type=""checkbox"" /></td>
     <td class='ListTableTdB'><a href='ModifyInfo.aspx?sn={2}' target='_blank'>{1}</a></td>
     <td class='ListTableTdE'><span rel='{2}' pay='{6}' class='{5}'>{3}</span></td>
     <td class='ListTableTdF'><span rel='{2}' invoice='{7}' class='{8}'>{4}</span></td>
     <td class='ListTableTdC'><a href='Delete.aspx?sn={2}' onclick='return confirm(""确定要删除{1}吗?"")' >删除</a></td>
     <td class='ListTableTdD'><a href='ModifyInfo.aspx?sn={2}' target='_blank'>编缉</a></td>
     </tr>";
        StringBuilder sb = new StringBuilder();
        sb.Append("<table class='ListTable'>");
        sb.Append(@"<tr class='ListHead'><td><input id=""CheckboxAllC"" onclick='JSCheckAll(this)' type=""checkbox"" />全选</td><td class='AdminTdA'>公司名称</td>
        <td>交费状态</td><td>开发票</td>
        <td>删除</td><td>编辑</td></tr>");
        object[] param = new object[9];
        for (int i = 0; i < xList.Count; i++)
        {
            param[0] = i;
            param[1] = xList[i].CompanyName;
            param[2] = xList[i].sn;
            param[3] = xList[i].Pay ? "已付" : "未付";
            param[4] = xList[i].Invoice ? "是" : "否";
            param[5] = xList[i].Pay ? "pay" : "unpay";
            param[6] = xList[i].Pay;
            param[7] = xList[i].Invoice;
            param[8] = xList[i].Invoice ? "invoice" : "uninvoice";
            sb.AppendFormat(HTMLTemplate, param);
        }
        sb.Append("</table>");
        xLiteral1.Text = sb.ToString();
        //分页导航
        string PageIndexUrl = null;
        StringBuilder PageIndexUrlSb = new StringBuilder();
        PageIndexUrlSb.Append("default.aspx?");
        PageIndexUrlSb.AppendFormat("keyWord={0}&", HttpUtility.UrlEncode(keyWord));
        PageIndexUrlSb.Append("page");
        PageIndexUrl = PageIndexUrlSb.ToString();
        pageLiteral1.Text = com.hooyes.crc.helper.Page.ShowPage(PageIndexUrl, "", RecordsCount, PageSize, CurrentPage, true, "个");

        //显示关键字导航
        ShowTip(keyWord);

        //test
        //reg.SetPayStatus("893a0934-7f8e-480e-9095-7d02383437f2", false);
        // reg.SetInvoicStatus("893a0934-7f8e-480e-9095-7d02383437f2", true);
    }
    /// <summary>
    /// 提示
    /// </summary>
    /// <param name="msg"></param>
    protected void ShowTip(string msg)
    {
        StringBuilder sb = new StringBuilder();
        sb.Append("<a href='default.aspx'>全部列表</a>");
        if (!string.IsNullOrEmpty(msg))
        {
            sb.AppendFormat(" >> 关键字' <span class='highlight'> {0} </span> '", msg);
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
        string IndexPageUrl = "default.aspx?keyWord={0}";
        IndexPageUrl = string.Format(IndexPageUrl, HttpUtility.UrlEncode(keyWord));
        Response.Redirect(IndexPageUrl);
    }
    protected void Welcome()
    {
        string  msg="您好,管理员{0}";
        msg = string.Format(msg, LoginUserName);
        WelComeLiteral1.Text = msg;
    }
}
