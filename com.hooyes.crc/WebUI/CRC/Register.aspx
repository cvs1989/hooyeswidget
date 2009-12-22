<%@ page language="C#" enableviewstate="false" autoeventwireup="true" inherits="CRC_Register" CodeFile="Register.aspx.cs" %>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" >
<head runat="server">
    <title>第五届中国橡胶市场发展论坛暨世界橡胶高峰论坛</title>
    <script type="text/javascript" src="hooyes.js/jquery-latest.pack.js"></script>
    <script type="text/javascript" src="hooyes.js/thickbox-compressed.js"></script>
    <link href="hooyes.js/thickbox.css" rel="stylesheet" type="text/css" media="screen"  />
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
                <td width="93%" class="title-1">- CRC 2010 &gt;&gt; 报名参会</td>
              </tr>
            </table>
            <hr>
            <!--中心主体内容 开始-->
            <table width="100%" border="1" cellpadding="0" cellspacing="0" bordercolor="#FF9900">
              <tr> 
                <td bgcolor="#FFFFFF" class="text">* 请下载<a href="form_1.doc">参会注册表</a>或填写下表进行网上提交！</td>
              </tr>
            </table> 
            <form class="text" id="form1" runat="server">
              <div class="RegisterTitle CenterPart"> 
                <p><strong><br />
                  2010年中国橡胶市场发展论坛（第五届）</strong><br />
                  2010年3月22-25日 &#8226; 中国-青岛<br />
                  <br />
                </p>
              </div>
    <a name="register"></a>
    <div class="CommonDiv CenterPart">
    <table class="RegisterTable" cellpadding="0" cellspacing="0" width="100%">
    <tr>
                    <td class="RegisterTable_Td">公司名称：</td>
     <td class="RegisterTable_Td">
	 <asp:TextBox id="CompanyName" CssClass="required RegisterInput" runat="server"></asp:TextBox>
					</td>               <td class="RegisterTable_Td" style="width: 100px" >邮编：</td>
     <td class="RegisterTable_Td">
	 <asp:TextBox id="PostCode" CssClass="required RegisterInput" runat="server"></asp:TextBox>
					</td>
    </tr>
    <tr>
                    <td class="RegisterTable_Td">英文名称：</td>
     <td class="RegisterTable_Td">
	 <asp:TextBox id="CompanyNameEn" CssClass="RegisterInput" runat="server"></asp:TextBox>
					</td>               <td class="RegisterTable_Td" style="width: 100px"> 电话：</td>
     <td class="RegisterTable_Td">
	 <asp:TextBox id="Phone" CssClass="required RegisterInput" runat="server"></asp:TextBox></td>
    </tr>
        <tr>
                    <td class="RegisterTable_Td" style="width: 74px">公司地址：</td>
     <td class="RegisterTable_Td">
	 <asp:TextBox id="CompanyAddress" CssClass="required RegisterInput" runat="server"></asp:TextBox>
					</td>               <td class="RegisterTable_Td" style="width: 100px">传真：</td>
     <td class="RegisterTable_Td">
	 <asp:TextBox id="Fax" CssClass="RegisterInput" runat="server"></asp:TextBox></td>
    </tr>
        <tr>
                    <td class="RegisterTable_Td" style="width: 74px">联系人：</td>
     <td class="RegisterTable_Td">
	 <asp:TextBox id="Contact" CssClass="required RegisterInput" runat="server"></asp:TextBox>
					</td>               <td class="RegisterTable_Td" style="width: 100px"> 手机：</td>
     <td class="RegisterTable_Td">
         <asp:TextBox ID="CellPhone" runat="server" CssClass="RegisterInput"></asp:TextBox></td>
    </tr>
    <tr>
                    <td class="RegisterTable_Td" style="width: 74px">WebSite: 
                    </td>
     <td class="RegisterTable_Td">
	 <asp:TextBox id="WebSite" CssClass="RegisterInput" runat="server"></asp:TextBox>
					</td>               <td class="RegisterTable_Td" style="width: 100px">Email: </td>
     <td class="RegisterTable_Td">
	 <asp:TextBox id="Email" CssClass="requiredEmail RegisterInput" runat="server"></asp:TextBox></td>
    </tr>
    <tr>
                    <td class="RegisterTable_Td" style="width: 74px">
                        企业类型:
                    </td>
     <td class="RegisterTable_Td">
         <asp:RadioButtonList ID="RadioButtonCompanyType"  RepeatDirection="horizontal" runat="server">
          <asp:ListItem  Selected="true">生产商</asp:ListItem>
          <asp:ListItem>经销商</asp:ListItem>
         </asp:RadioButtonList>
					</td>               <td class="RegisterTable_Td" style="width: 100px" >
                        产品类型:
                    </td>
     <td class="RegisterTable_Td">
	  <asp:RadioButtonList ID="RadioButtonProductType"  RepeatDirection="horizontal" runat="server">
          <asp:ListItem Selected="true">原材料</asp:ListItem>
          <asp:ListItem>轮胎</asp:ListItem>
          <asp:ListItem>橡胶制品</asp:ListItem>
         </asp:RadioButtonList>
         </td>
    </tr>
    <tr>
    <td colspan="4" class="RegisterTable_Td">
     <table width="100%">
                        <tr>
                          <td class="text">姓名</td>                          <td class="text">性别</td>                          <td class="text">职务</td>                          <td class="text">电话</td>                          <td class="text">手机</td>
<td width="5%"></td>
      </tr>
            <tr>
<td>
   <input name="vName" id="Text1" class="required RegisterInputShort" type="text" /></td>
<td>
    <select id="Select1" name="vGender">
        <option selected="selected" value="">请选择</option>
        <option value="female">女</option>
        <option value="male">男</option>
    </select>
</td>
<td>
    <input name="vTitle" id="Text7" class="RegisterInputShort" type="text" /></td>
<td>
    <input name="vPhone" id="Text10" class="RegisterInputShort" type="text" /></td>
<td>
    <input name="vCellPhone" id="Text13" class="RegisterInputShort" type="text" /></td>
    <td></td>
      </tr>
     <tr>
<td>
   <input name="vName" id="Text2" class="RegisterInputShort" type="text" /></td>
<td><select id="Select2" name="vGender">
    <option selected="selected" value="">请选择</option>
    <option value="female">女</option>
    <option value="male">男</option>
</select>
</td>
<td>
    <input name="vTitle" id="Text8" class="RegisterInputShort" type="text" /></td>
<td>
    <input name="vPhone" id="Text11" class="RegisterInputShort" type="text" /></td>
<td>
    <input name="vCellPhone" id="Text14" class="RegisterInputShort" type="text" /></td>
    <td></td>
      </tr>


      
     </table>
     <div id="VistorDync"></div>
     <a href="javascript:void(0)" id="VistorDyncBtn" onclick="AddVistors()">增加一名人员</a>
    </td>
    </tr>
    <tr>
                    <td colspan="4" class="RegisterTable_Td"> <strong><em>注：会务组以收到注册表及注册费为准</em></strong> 
                      <em><br />
                      <strong>报名截止时间</strong>：2010年2月20日 </em><br />
                      <strong>---------------------------------------------------<br />
                      协会帐户：</strong><br />
                      帐户名称：中国橡胶工业协会 <br />
                      开户银行：北京工行地安门支行六铺炕分理处 <br />
                      银行帐号：020002230901402314 <br />
                      注明：2010 中国橡胶市场发展论坛 </td>
    </tr>
    <tr>
    <td colspan="4" class="RegisterTable_Td">
    <b> 您对本次会议的建议:</b> <br />
	<asp:TextBox id="Suggestion" runat="server" CssClass="SuggestionInput CenterPart" TextMode="MultiLine"></asp:TextBox>
    </td>
    </tr>



    </table>
    <div id="btn" class="RegisterBtn">
		<asp:Button id="hooyesRegisterBtn" runat="server" Text="提交注册" OnClientClick="return ValidateForm(this)" OnClick="hooyesRegisterBtn_Click" />
       
		
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
alert("最多添加"+AddCount+"个人");
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
 if(!rValue){ alert("请填写完整信息");}
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
