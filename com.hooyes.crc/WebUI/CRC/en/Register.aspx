<%@ Page Language="C#" EnableViewState="false" AutoEventWireup="true" CodeFile="Register.aspx.cs" Inherits="CRC_Register" %>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" >
<head runat="server">
    <title>5th China Rubber Conference &amp; World Rubber Summit</title>
    <script type="text/javascript" src="../hooyes.js/jquery-latest.pack.js"></script>
    <script type="text/javascript" src="../hooyes.js/thickbox-compressed.js"></script>
    <link href="../hooyes.js/thickbox.css" rel="stylesheet" type="text/css" media="screen"  />
    <link href="hooyes.Css/crc.css" rel="stylesheet" type="text/css" />
<link href="style.css" rel="stylesheet" type="text/css" />
</head>
<body>

<!--头部文件-->
<table border="0" cellpadding="0" cellspacing="0" bgcolor="#8CB621">
<tr>
 <td class="GlobalLeft" valign="top">
 <!--#include file="PageTop.html"-->
 <!--页面中心部份 开始-->
<table width="755" border="0" cellpadding="0" cellspacing="0">
        <tr> 
          <td width="57"><img src="img/top1_r14_c1.gif" width="57" height="20"></td>
          <td width="836" background="img/top1_r14_c3.gif">&nbsp;</td>
          <td width="65"><img src="img/top1_r14_c5.gif" width="65" height="20"></td>
        </tr>
        <tr> 
          <td background="img/top1_r15_c1.gif">&nbsp;</td>
          <td valign="top" bgcolor="#BBD479">  <!--中部导航-->
          <!--#include file="PageNavigation.html"-->
            <table width="60%" border="0" cellspacing="0" cellpadding="0">
              <p></p>
              <tr> 
                <td width="5%"><img src="img/item.jpg" width="15" height="25"></td>
                <td width="93%" class="title-1">- CRC 2010 &gt;&gt; Registration</td>
              </tr>
            </table>
            <hr>
            <!--中心主体内容 开始-->
            <table width="100%" border="1" cellpadding="0" cellspacing="0" bordercolor="#FF9900">
              <tr>
                <td bgcolor="#FFFFFF" class="text">* Please download the <a href="Form_1.doc">Application 
                  Form</a> or complete the form as below and submit it online.</td>
              </tr>
            </table> 
            <form id="form1" runat="server">
              <div class="text">
                <div align="center"> 
                  <p><strong>Registration Form of CRC 2010 & WRS 2010</strong><br />
                    Mar.15-18 2010 Qingdao China<br />
                  </p>
                </div>
              </div>
              <div class="CommonDiv CenterPart">
<table class="RegisterTable" cellpadding="0" cellspacing="0" width="100%">
    <tr>
                    <td class="RegisterTable_Td"> Company: </td>
     <td class="RegisterTable_Td">
	 <asp:TextBox id="CompanyName" CssClass="required RegisterInput" runat="server"></asp:TextBox>
					</td>
                    <td class="RegisterTable_Td"> PostCode: </td>
     <td class="RegisterTable_Td">
	 <asp:TextBox id="PostCode" CssClass="RegisterInput" runat="server"></asp:TextBox>
					</td>
    </tr>
    <tr>
                    <td class="RegisterTable_Td"> Address: </td>
     <td class="RegisterTable_Td">
	 <asp:TextBox id="CompanyAddress" CssClass="RegisterInput" runat="server"></asp:TextBox></td>
                    <td class="RegisterTable_Td"> Tel: </td>
     <td class="RegisterTable_Td">
	 <asp:TextBox id="Phone" CssClass="required RegisterInput" runat="server"></asp:TextBox></td>
    </tr>
        <tr>
                    <td class="RegisterTable_Td" style="width: 74px"> Contact: 
                    </td>
     <td class="RegisterTable_Td">
	 <asp:TextBox id="Contact" CssClass="required RegisterInput" runat="server"></asp:TextBox></td>
                    <td class="RegisterTable_Td"> Fax: </td>
     <td class="RegisterTable_Td">
	 <asp:TextBox id="Fax" CssClass="RegisterInput" runat="server"></asp:TextBox></td>
    </tr>
        <tr>
                    <td class="RegisterTable_Td" style="width: 74px">WebSite: 
                    </td>
     <td class="RegisterTable_Td">
	 <asp:TextBox id="WebSite" CssClass="RegisterInput" runat="server"></asp:TextBox></td>
                    <td class="RegisterTable_Td"> Email: </td>
     <td class="RegisterTable_Td">
	 <asp:TextBox id="Email" CssClass="requiredEmail RegisterInput" runat="server"></asp:TextBox></td>
    </tr>
    <tr style="display:none">
     <td class="RegisterTable_Td" style="width: 74px"></td>
     <td class="RegisterTable_Td">&nbsp;
         </td>
     <td class="RegisterTable_Td"></td>
     <td class="RegisterTable_Td">
	 </td>
    </tr>
    <tr>
                    <td class="RegisterTable_Td" style="width: 74px">
                        type of business:
                    </td>
     <td class="RegisterTable_Td">
         <asp:RadioButtonList ID="RadioButtonCompanyType"  RepeatDirection="horizontal" runat="server">
          <asp:ListItem  Value="生产商" Selected="true">manufacturer</asp:ListItem>
          <asp:ListItem Value="经销商">dealer</asp:ListItem>
         </asp:RadioButtonList>
					</td>               <td class="RegisterTable_Td" style="width: 100px" >
                        product type:
                    </td>
     <td class="RegisterTable_Td">
	  <asp:RadioButtonList ID="RadioButtonProductType"  RepeatDirection="horizontal" runat="server">
          <asp:ListItem Value="原材料" Selected="true">Raw materials</asp:ListItem>
          <asp:ListItem Value="轮胎">tyre</asp:ListItem>
          <asp:ListItem Value="橡胶制品">rubber products</asp:ListItem>
         </asp:RadioButtonList>
         </td>
    </tr>
    <tr>
    <td colspan="4" class="RegisterTable_Td">
     <table width="100%">
                        <tr> 
                          <td class="text"> <div align="center">Name</div></td>
                          <td class="text"> <div align="center">Sex</div></td>
                          <td class="text"> <div align="center">Job Title</div></td>
                          <td class="text"> <div align="center">Tel</div></td>
                          <td class="text"> <div align="center">Mobile Phone</div></td>
                          <td width="5%"></td>
                        </tr>
                        <tr> 
                          <td> <input name="vName" id="Text1" class="required RegisterInputShort" type="text" /></td>
                          <td> <select id="Select1" name="vGender">
                              <option selected="selected" value="">Select</option>
                              <option value="female">female</option>
                              <option value="male">male</option>
                            </select> </td>
                          <td> <input name="vTitle" id="Text7" class="RegisterInputShort" type="text" /></td>
                          <td> <input name="vPhone" id="Text10" class="RegisterInputShort" type="text" /></td>
                          <td> <input name="vCellPhone" id="Text13" class="RegisterInputShort" type="text" /></td>
                          <td></td>
                        </tr>
                        <tr> 
                          <td> <input name="vName" id="Text2" class="RegisterInputShort" type="text" /></td>
                          <td><select id="Select2" name="vGender">
                              <option selected="selected" value="">Select</option>
                              <option value="female">female</option>
                              <option value="male">male</option>
                            </select> </td>
                          <td> <input name="vTitle" id="Text8" class="RegisterInputShort" type="text" /></td>
                          <td> <input name="vPhone" id="Text11" class="RegisterInputShort" type="text" /></td>
                          <td> <input name="vCellPhone" id="Text14" class="RegisterInputShort" type="text" /></td>
                          <td></td>
                        </tr>
                      </table>
     <div id="VistorDync"></div>
     <a href="javascript:void(0)" id="VistorDyncBtn" onclick="AddVistors()">Add one more</a>
    </td>
    </tr>
                  <tr> 
                    <td colspan="4" class="RegisterTable_Td"><strong>* All registration 
                      is to be confirmed on the receipt of your conference fee. 
                      </strong> 
                      <p><em>Bank Details (RMB Account):</em><br />
                        Bank Name: Industrial &amp; Commercial Bank of China<br />
                        LIUPUKANG Banking Office<br />
                        Bank A/C: BJ111- 016 0200022309014402314<br />
                        Beneficiary’s Name: China Rubber Industry Association<br />
                      </p></td>
    </tr>
    <tr>
    <td colspan="4" class="RegisterTable_Td">
    <b>Your advices for our conference:</b> <br />
	<asp:TextBox id="Suggestion" runat="server" CssClass="SuggestionInput CenterPart" TextMode="MultiLine"></asp:TextBox>
    </td>
    </tr>



    </table>
    <div id="btn" class="RegisterBtn">
		<asp:Button id="hooyesRegisterBtn" runat="server" Text="Submit" OnClientClick="return ValidateForm()" OnClick="hooyesRegisterBtn_Click" />
		
				</div>
    </div>
    </form>
    
<script type="text/javascript">
var ContainerDivID="VistorDync";
var AddCount=2;
var AddCountMax=10;
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
alert("Limit "+AddCount+"");
return false;
}
var sb=new StringBuilder();
sb.AppendFormat("<div id=\"HOOYESDIV{0}\"><table width='100%'><tr>",AddCount);
sb.AppendFormat("<td><input name=\"vName\" id=\"Text200{0}\" class=\"RegisterInputShort\" type=\"text\" \/><\/td>",AddCount);
sb.AppendFormat("<td><select id=\"Select400{0}\" name=\"vGender\">",AddCount);
sb.Append("    <option selected=\"selected\" value=\"\">Select<\/option>");
sb.Append("    <option value=\"female\">female<\/option>");
sb.Append("    <option value=\"male\">male<\/option>");
sb.Append("<\/select><\/td>");
sb.AppendFormat("<td><input name=\"vTitle\" id=\"Text301{0}\" class=\"RegisterInputShort\" type=\"text\" \/><\/td>",AddCount);
sb.AppendFormat("<td><input name=\"vPhone\" id=\"Text502{0}\" class=\"RegisterInputShort\" type=\"text\" \/><\/td>",AddCount);
sb.AppendFormat("<td><input name=\"vCellPhone\" id=\"Text603{0}\" class=\"RegisterInputShort\" type=\"text\" \/><\/td>",AddCount);
sb.AppendFormat("<td width=\"5%\"><a href='javascript:void(0)' onclick=\"Remove('HOOYESDIV{0}')\">Delete<\/a><\/td>",AddCount)
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
  //表单验证 adpter by hooyes 2009.12.13
 function ValidateForm(){
 var rValue=true;
 $.each($(".required"),function(i,v){
    if(this.value==""){
    $(this).css("border","1px solid red");
    rValue=false;
    }
 }).blur(function(){
    if(this.value!=""){
      $(this).css("border","0px");
    }else{
      $(this).css("border","1px solid red");
    }
 });
 $.each($(".requiredEmail"),function(i,v){
    if(!(/^([A-Za-z])+([A-Za-z0-9]|[-]|[_]|[.])*([A-Za-z0-9])+@([-A-Za-z0-9])+\..+$/.test(this.value))){
    $(this).css("border","1px solid red");
    rValue=false;
    }
 }).blur(function(){
    if((/^([A-Za-z])+([A-Za-z0-9]|[-]|[_]|[.])*([A-Za-z0-9])+@([-A-Za-z0-9])+\..+$/.test(this.value))){
      $(this).css("border","0px");
    }else{
      $(this).css("border","1px solid red");
    }
 });
 if(!rValue){ alert("Please complete the form ");}
 return rValue;
 }
</script>
 <!--中心主体内容 结束-->
            </td>
          <td background="img/top1_r15_c5.gif">&nbsp;</td>
        </tr>
        <tr> 
          <td><img src="img/top1_r16_c1.gif" width="57" height="19"></td>
          <td background="img/top1_r16_c2.gif">&nbsp;</td>
          <td><img src="img/top1_r16_c5.gif" width="65" height="19"></td>
        </tr>
      </table>
 <!--页面中心部份 结束-->
 <!--页脚文件-->
 <!--#include file="PageBottom.html"-->
 </td>
 <td class="GlobalRight" width="245" valign="top">
 <!--右侧文件-->
 <!--#include file="PageRight.html"-->
 </td>
 </tr>
 </table>
 <a href="" id="boxHolder" class="thickbox"></a>
<input type="hidden" id="boxHolderHidden" value="&KeepThis=true&TB_iframe=true&height=150&width=400" />
</body>
</html>
