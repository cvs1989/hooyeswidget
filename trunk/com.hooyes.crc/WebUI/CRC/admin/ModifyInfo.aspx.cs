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
using com.hooyes.crc.DAL;
using com.hooyes.crc.Model;
using com.hooyes.crc.BLL;


public partial class CRC_admin_ModifyInfo : PageBase
{
    protected void Page_Load(object sender, EventArgs e)
    {
        RequireLogin();
        if (!IsPostBack)
        {
            InitPage();
        }
    }
    protected void InitPage()
    {
        string sn = Request.QueryString.Get("sn");

        if (com.hooyes.crc.helper.Validate.GuidString(sn))
        {


            CRCapply model = new CRCapply();
            RegisterAdmin reg = new RegisterAdmin();
            model = reg.model(sn);
            CompanyAddress.Text = model.CompanyAddress;
            CompanyName.Text = model.CompanyName;
            CompanyNameEn.Text = model.CompanyNameEn;
            WebSite.Text = model.WebSite;
            Phone.Text = model.Phone;
            CellPhone.Text = model.CellPhone;
            Fax.Text = model.Fax;
            PostCode.Text = model.PostCode;
            Suggestion.Text = model.Suggestion;
            Contact.Text = model.Contact;
            Email.Text = model.Email;
            //单选框初始化
            RadioButtonListPay.SelectedIndex = (model.Pay) ? 0 : 1;
            RadioButtonListInvoic.SelectedIndex = (model.Invoice) ? 0 : 1;
            string TypeScript = "<script type=\"text/javascript\">SelectAapter('{0}','{1}');</script>";
            

            this.Page.ClientScript.RegisterStartupScript(typeof(Page), "script1", string.Format(TypeScript,"RadioButtonCompanyType", model.CompanyType));
            this.Page.ClientScript.RegisterStartupScript(typeof(Page), "script2", string.Format(TypeScript, "RadioButtonProductType", model.ProductType));


            //Vistors

            string Vistors = model.Vistors;
            string[] vSpace ={ "|#|" };
            string[] vComma ={ "," };
            string[] vS = Vistors.Split(vSpace, StringSplitOptions.None);
            string[] vName = vS[0].Split(vComma, StringSplitOptions.None);
            string[] vGender = vS[1].Split(vComma, StringSplitOptions.None);
            string[] vTitle = vS[2].Split(vComma, StringSplitOptions.None);
            string[] vPhone = vS[3].Split(vComma, StringSplitOptions.None);
            string[] vCellPhone = vS[4].Split(vComma, StringSplitOptions.None);

            // HTML
            StringBuilder sb = new StringBuilder();

            string HTMLTemplate = @"<div id=""HOOYESDIVCCC{7}""><table width='100%'>
<tr><td><input name=""vName""  class=""RegisterInputShort"" type=""text"" value='{0}' /></td><td>
<select  name=""vGender""><option  value="""">请选择</option><option {5} value=""female"">女</option>
<option {6} value=""male"">男</option>
</select>
</td>
<td><input name=""vTitle"" class=""RegisterInputShort"" type=""text"" value='{2}' /></td><td>
<input name=""vPhone""  class=""RegisterInputShort"" type=""text"" value='{3}' /></td><td>
<input name=""vCellPhone""  class=""RegisterInputShort"" type=""text"" value='{4}' /></td><td width='5%'><a href='javascript:void(0)' onclick=""Remove('HOOYESDIVCCC{7}')"">删除</a></td></tr></table></div>";
            object[] param = new object[8];
            for (int i = 0; i < vName.Length; i++)
            {

                param[0] = vName[i];
                param[1] = vGender[i];
                param[2] = vTitle[i];
                param[3] = vPhone[i];
                param[4] = vCellPhone[i];
                param[5] = "";
                param[6] = "";
                param[7] = i;
                // Response.Write(param[1]);
                if (vGender[i] == "female")
                {
                    param[5] = "selected=\"selected\"";
                }
                if (vGender[i] == "male")
                {

                    param[6] = "selected=\"selected\"";
                }
                sb.AppendFormat(HTMLTemplate, param);

            }


            vLiteral1.Text = sb.ToString();



        }
        else
        {
            Response.Write("sn 参数不正确");
            Response.End();
        }
    }
    protected void hooyesRegisterBtn_Click(object sender, EventArgs e)
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

        model.sn =Request["sn"].ToString();
        //Response.Write(model.sn);
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

        model.Pay = (RadioButtonListPay.SelectedValue == "1");
        model.Invoice = (RadioButtonListInvoic.SelectedValue == "1");


        RegisterAdmin reg = new RegisterAdmin();

        bool flag = reg.update(model);
        if (flag)
        {
            GoThankYouUrl("../", "修改成功!", Request.Url.PathAndQuery, "javascript:parent.tb_remove();", " 查  看 ", "关闭窗口");
        }
    }
}
