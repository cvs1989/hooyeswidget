<%@ Page Language="C#" EnableViewState="false" AutoEventWireup="true" CodeFile="ModifyInfo.aspx.cs" Inherits="CRC_admin_ModifyInfo" %>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" >
<head id="Head1" runat="server">
    <title>会议注册表</title>
    <script type="text/javascript" src="../hooyes.js/jquery-latest.pack.js"></script>
    <script type="text/javascript" src="../hooyes.js/thickbox-compressed.js"></script>
    <link href="../hooyes.js/thickbox.css" rel="stylesheet" type="text/css" media="screen"  />
    <link href="../hooyes.Css/crc.css" rel="stylesheet" type="text/css" />
    <script type="text/javascript">
    function SelectAapter(n,v){
 var obj=document.getElementsByName(n);
 for(var i=0;i<obj.length;i++){
  if(obj[i].value==v){
    obj[i].checked=true;
    break;
  }
 }
}

    </script>
</head>
<body>
    <form id="form1" runat="server">
    <div class="RegisterTitle"><strong>2010年中国橡胶市场发展论坛(第五届)</strong><br />
     2010年3月22-25日 中国 青岛<br />
     会议注册表
    
    </div>
    <div class="CommonDiv">
    <table class="RegisterTable" cellpadding="0" cellspacing="0" width="100%">
    <tr>
     <td class="RegisterTable_Td">公司名称</td>
     <td class="RegisterTable_Td">
	 <asp:TextBox id="CompanyName" CssClass="RegisterInput" runat="server"></asp:TextBox>
					</td>
     <td class="RegisterTable_Td">邮编</td>
     <td class="RegisterTable_Td">
	 <asp:TextBox id="PostCode" CssClass="RegisterInput" runat="server"></asp:TextBox>
					</td>
    </tr>
    <tr>
     <td class="RegisterTable_Td">公司英文名</td>
     <td class="RegisterTable_Td">
	 <asp:TextBox id="CompanyNameEn" CssClass="RegisterInput" runat="server"></asp:TextBox>
					</td>
     <td class="RegisterTable_Td">
         电话</td>
     <td class="RegisterTable_Td">
	 <asp:TextBox id="Phone" CssClass="RegisterInput" runat="server"></asp:TextBox></td>
    </tr>
        <tr>
     <td class="RegisterTable_Td" style="width: 74px">公司地址</td>
     <td class="RegisterTable_Td">
	 <asp:TextBox id="CompanyAddress" CssClass="RegisterInput" runat="server"></asp:TextBox>
					</td>
     <td class="RegisterTable_Td">传真</td>
     <td class="RegisterTable_Td">
	 <asp:TextBox id="Fax" CssClass="RegisterInput" runat="server"></asp:TextBox></td>
    </tr>
        <tr>
     <td class="RegisterTable_Td" style="width: 74px">联系人</td>
     <td class="RegisterTable_Td">
	 <asp:TextBox id="Contact" CssClass="RegisterInput" runat="server"></asp:TextBox>
					</td>
     <td class="RegisterTable_Td">
         手机</td>
     <td class="RegisterTable_Td">
         <asp:TextBox ID="CellPhone" runat="server" CssClass="RegisterInput"></asp:TextBox></td>
    </tr>
    <tr>
     <td class="RegisterTable_Td" style="width: 74px">WebSite</td>
     <td class="RegisterTable_Td">
	 <asp:TextBox id="WebSite" CssClass="RegisterInput" runat="server"></asp:TextBox>
					</td>
     <td class="RegisterTable_Td">E-Mail</td>
     <td class="RegisterTable_Td">
	 <asp:TextBox id="Email" CssClass="RegisterInput" runat="server"></asp:TextBox></td>
    </tr>
    <tr>
                    <td class="RegisterTable_Td" style="width: 74px">
                        企业类型:
                    </td>
     <td class="RegisterTable_Td">
         <asp:RadioButtonList ID="RadioButtonCompanyType"  RepeatDirection="horizontal" runat="server">
          <asp:ListItem>生产商</asp:ListItem>
          <asp:ListItem>经销商</asp:ListItem>
         </asp:RadioButtonList>
					</td>               <td class="RegisterTable_Td">
                        产品:
                    </td>
     <td class="RegisterTable_Td">
	  <asp:RadioButtonList ID="RadioButtonProductType"  RepeatDirection="horizontal" runat="server">
          <asp:ListItem>原材料</asp:ListItem>
          <asp:ListItem>轮胎</asp:ListItem>
          <asp:ListItem>橡胶制品</asp:ListItem>
         </asp:RadioButtonList>
         </td>
    </tr>
    <tr>
    <td class="RegisterTable_Td" style="width: 74px">
                        交费状态
                    </td>
                    <td  class="RegisterTable_Td">
                        <asp:RadioButtonList ID="RadioButtonListPay" RepeatDirection="horizontal" runat="server">
                        <asp:ListItem Value="1">已交</asp:ListItem>
                        <asp:ListItem Value="0">未交</asp:ListItem>
                        </asp:RadioButtonList>
                    </td>
                    <td class="RegisterTable_Td">
                        发票
                    </td>
                    <td  class="RegisterTable_Td">
                        <asp:RadioButtonList ID="RadioButtonListInvoic" RepeatDirection="horizontal"  runat="server">
                        <asp:ListItem Value="1">需要</asp:ListItem>
                        <asp:ListItem Value="0">不需要</asp:ListItem>
                        </asp:RadioButtonList>
                    </td>
                    
    </tr>
    <tr>
    <td colspan="4" class="RegisterTable_Td">
     <table width="100%">
      <tr>
<td>姓名</td>
<td>性别</td>
<td>职务</td>
<td>电话</td>
<td>手机</td>
<td width="5%"></td>
      </tr></table>
       <div id="VistorDync">
         <asp:Literal ID="vLiteral1" runat="server"></asp:Literal>
      </div>
     <a href="javascript:void(0)" id="VistorDyncBtn" onclick="AddVistors()">增加一名人员</a>
    </td>
    </tr>
    <tr>
    <td colspan="4" class="RegisterTable_Td">
    <strong>注:会务组以收到注册表及注册费为准</strong>
    <br /><strong>报名截止时间</strong>:2010年2月20日
    <br /><strong>协会帐户:
    </strong>
    <br />帐户名称:中国橡胶工业协会
    <br />开户银行:北京工行地安门支行六铺炕分理处
    <br />银行帐号:020002230901402314
    <br />注明:2010 中国橡胶市场发展论坛
    </td>
    </tr>
    <tr>
    <td colspan="4" class="RegisterTable_Td">
    <b> 您对本次会议的建议:</b> <br />
	<asp:TextBox id="Suggestion" runat="server" CssClass="SuggestionInput" TextMode="MultiLine"></asp:TextBox>
    </td>
    </tr>



    </table>
    <div id="btn" class="RegisterBtn">
		<asp:Button id="hooyesRegisterBtn" runat="server" Text="提交修改" OnClick="hooyesRegisterBtn_Click" />
		
				</div>
    </div>
    </form>
    <script type="text/javascript">
var ContainerDivID="VistorDync";
var AddCount=1;
var AddCountMax=20;
function StringBuilder(){this.hooyesStr="";}
StringBuilder.prototype.Append=function(str){this.hooyesStr+=str;}
StringBuilder.prototype.AppendFormat=function(){
   if(arguments.length>1){
     var TString=arguments[0];
     if(arguments[1] instanceof Array){
      //arguments[1] is Array 
      for(var i=0;i<arguments[1].length;i++){
     var jIndex=i;
     var re=eval("/\\{"+jIndex+"\\}/g;");
     TString= TString.replace(re,arguments[1][i]); 
     }
     }else{
     //arguments[1] is Common Argument
     for(var i=1;i<arguments.length;i++){
     var jIndex=i-1;
     var re=eval("/\\{"+jIndex+"\\}/g;");
     TString= TString.replace(re,arguments[i]); 
     }
     }
     this.Append(TString);
   }
   if(arguments.length==1){this.Append(arguments[0]);}
}
StringBuilder.prototype.ToString=function(){return this.hooyesStr;}
function AddVistors(){
if(AddCount>=AddCountMax){
alert("最多追加"+AddCount+"个人");
return false;
}
var sb=new StringBuilder();
sb.AppendFormat("<div id=\"HOOYESDIV{0}\"><table width='100%'><tr>",AddCount);
sb.AppendFormat("<td><input name=\"vName\" id=\"Text200{0}\" class=\"RegisterInputShort\" type=\"text\" \/><\/td>",AddCount);
sb.AppendFormat("<td><select id=\"Select400{0}\" name=\"vGender\">",AddCount);
sb.Append("    <option selected=\"selected\" value=\"\">请选择<\/option>");
sb.Append("    <option value=\"female\">女<\/option>");
sb.Append("    <option value=\"male\">男<\/option>");
sb.Append("<\/select><\/td>");
sb.AppendFormat("<td><input name=\"vTitle\" id=\"Text301{0}\" class=\"RegisterInputShort\" type=\"text\" \/><\/td>",AddCount);
sb.AppendFormat("<td><input name=\"vPhone\" id=\"Text502{0}\" class=\"RegisterInputShort\" type=\"text\" \/><\/td>",AddCount);
sb.AppendFormat("<td><input name=\"vCellPhone\" id=\"Text603{0}\" class=\"RegisterInputShort\" type=\"text\" \/><\/td>",AddCount);
sb.AppendFormat("<td width=\"5%\"><a href='javascript:void(0)' onclick=\"Remove('HOOYESDIV{0}')\">删除<\/a><\/td>",AddCount)
sb.Append("<\/tr></table></div>");
insertHtml(document.getElementById(ContainerDivID),sb.ToString());
AddCount++;
}
function Remove(s){
 var oo=document.getElementById(s);
 document.getElementById(ContainerDivID).removeChild(oo);
 AddCount--;
}
function insertHtml(el, html){
        if(el.insertAdjacentHTML){
                    el.insertAdjacentHTML('BeforeEnd', html);
                    return el.lastChild;
        }else{
        var range = el.ownerDocument.createRange();
        var frag;
                if(el.lastChild){
                    range.setStartAfter(el.lastChild);
                    frag = range.createContextualFragment(html);
                    el.appendChild(frag);
                    return el.lastChild;
                }else{
                    el.innerHTML = html;
                    return el.lastChild;
                }
          
            }
 }

</script>
<a href="" id="boxHolder" class="thickbox"></a>
<input type="hidden" id="boxHolderHidden" value="&KeepThis=true&TB_iframe=true&height=150&width=400" />

</body>
</html>
