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
                 alert('Your company have been register \rPlease Contact us to update infomation');
                 </script>";
                this.Page.ClientScript.RegisterClientScriptBlock(typeof(System.Web.UI.Page), "script", js);
            }
            else
            {
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
                model.CompanyNameEn = CompanyName.Text;// CompanyNameEn.Text;
                model.WebSite = WebSite.Text;
                model.Phone = Phone.Text;
                model.CellPhone = Phone.Text;// CellPhone.Text;
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
                    GoThankYouUrl(",Successfully registered! Please make the payment now, and all registration is to be confirmed on the receipt of your conference fee. ", Request.Url.PathAndQuery, "javascript:parent.tb_remove();", "Register another one", "Close The Window");
                }
            }
        }
    }
}
