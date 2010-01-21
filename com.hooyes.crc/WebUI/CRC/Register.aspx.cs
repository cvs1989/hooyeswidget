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
using com.hooyes.crc.Model;
using com.hooyes.crc.DAL;
using com.hooyes.crc.BLL;
public partial class CRC_Register : PageBase
{
    protected void Page_Load(object sender, EventArgs e)
    {

    }
    protected void hooyesRegisterBtn_Click(object sender, EventArgs e)
    {
        Register reg = new Register();

        if (!string.IsNullOrEmpty(CompanyName.Text))
        {
            if (reg.exist(CompanyName.Text))
            {
                string js = @"<script type='text/javascript'>
                 alert('您填写的公司名称已经注册过了\r若需要更新信息请联系我们');
                 </script>";
                this.Page.ClientScript.RegisterClientScriptBlock(typeof(System.Web.UI.Page), "script", js);
            }
            else
            {
                #region 注册
                StringBuilder sb = new StringBuilder();

                string vName = Request["vName"].ToString();
                string vGender = Request["vGender"].ToString();
                string vTitle = Request["vTitle"].ToString();
                string vPhone = Request["vPhone"].ToString();
                string vCellPhone = Request["vCellPhone"].ToString();

                string spacor = "|#|";

                sb.Append(vName);
                sb.Append(spacor);
                sb.Append(vGender);
                sb.Append(spacor);
                sb.Append(vTitle);
                sb.Append(spacor);
                sb.Append(vPhone);
                sb.Append(spacor);
                sb.Append(vCellPhone);

                string Vistors = sb.ToString();



                CRCapply model = new CRCapply();

                model.sn = Guid.NewGuid().ToString();
                model.CompanyAddress = CompanyAddress.Text;
                model.CompanyName = CompanyName.Text;
                model.CompanyNameEn = CompanyNameEn.Text;
                model.WebSite = WebSite.Text;
                model.Phone = Phone.Text;
                model.CellPhone = CellPhone.Text;
                model.Fax = Fax.Text;
                model.PostCode = PostCode.Text;
                model.Suggestion = Suggestion.Text;
                model.Contact = Contact.Text;
                model.Email = Email.Text;
                model.CompanyType = RadioButtonCompanyType.SelectedValue;
                model.ProductType = RadioButtonProductType.SelectedValue;
                model.Vistors = Vistors;


                bool flag = reg.insert(model);
                if (flag)
                {
                    GoThankYouUrl("，注册成功！请及时电汇会议注册费以确认参会！ ", Request.Url.PathAndQuery, "javascript:parent.tb_remove();", "继续注册", "关闭窗口");
                }
                #endregion
            }
        }
    }
}
