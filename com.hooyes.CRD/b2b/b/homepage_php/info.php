<?php

if(!$m){
	$rsdb[content]=@preg_replace('/<([^>]*)>/is',"",$rsdb[content]);
	$rsdb[content]=get_word($rsdb[content],200)."<div style='text-align:right'><a href='?uid=$uid&m=info'><font color=blue>查看更多>>></font></a></div>";
	

	$rsdb[qy_contact_email] =str_replace("@","#",$rsdb[qy_contact_email]);
	$rsdb[qq]=getOnlinecontact('qq',$rsdb[qq]);
	$rsdb[msn]=getOnlinecontact('msn',$rsdb[msn]);
	$rsdb[skype]=getOnlinecontact('skype',$rsdb[skype]);
	$rsdb[ww]=getOnlinecontact('ww',$rsdb[ww]);
	
}
$rsdb[fname]=str_replace("|",",",$rsdb[fname]);
//得到绑定的图片
$show_bd_pics=show_bd_pics("{$_pre}company"," where uid=$uid",10);

print <<<EOT
-->   
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="rightinfo">
  <tr>
    <td  class="head">
	<span class='L'></span>
	<span class='T'><a href="$Mdomain/homepage.php?uid=$uid&m=info">商家简介</a></span>
	<span class='R'></span>
	<span class='more'>
<!--
EOT;
if($lfjuid==$uid){
print <<<EOT
-->
	<a href='$Mdomain/member/?main=homepage_ctrl.php?&atn=info2' target='_blank'>修改公司介绍</a>
<!--
EOT;
}
print <<<EOT
--></span>
	</td>
  </tr>
  <tr>
    <td class="content">


<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td rowspan=2 width='150' align=center><img src="$Murl/images/homepage_style/info.gif"  border="0"  class="logo" onerror="this.src='$Murl/images/default/nopic.jpg';"/></td> <td>
	<b style=' font-size:14px;'><font color='#146DBF'>$rsdb[title]</font> 概况</b><br>
	<div  style='line-height:25px;'>$rsdb[content]</div>
	</td>
  </tr>
  <tr>
   
    <td>
	<table width="100%" border="0" cellspacing="1" cellpadding="10" algin=center style='margin-top:10px;'>
  <tr>
    <td width="20%" align="left">&nbsp;主营分类：</td>
    <td width="30%" align="left">$rsdb[fname]&nbsp;</td>
    <td width="20%" align="left">&nbsp;所属地区：</td>
    <td width="30%" align="left">$rsdb[province_id] $rsdb[city_id] &nbsp;</td>
  </tr>
   <tr>
    <td width="20%" align="left">&nbsp;所属行业：</td>
    <td width="30%" align="left">$rsdb[my_trade]&nbsp;</td>
    <td width="20%" align="left">&nbsp;企业类型：</td>
    <td width="30%" align="left">$rsdb[qy_cate]&nbsp;</td>
  </tr>
  <tr>
    <td align="left">&nbsp;注册资本：</td>
    <td align="left">$rsdb[qy_regmoney]万&nbsp;</td>
    <td align="left">&nbsp;经营模式：</td>
    <td align="left">$rsdb[qy_saletype]&nbsp;</td>
  </tr>
  <tr>
    <td align="left">&nbsp;注册地址：</td>
    <td align="left">$rsdb[qy_regplace]&nbsp;</td>
    <td align="left">&nbsp;成立时间：</td>
    <td align="left">$rsdb[qy_createtime]&nbsp;</td>
  </tr>
    <tr>
    <td align="left">&nbsp;主营产品或服务：</td>
    <td align="left" >$rsdb[qy_pro_ser]&nbsp;</td>
  
    <td align="left">&nbsp;主要采购产品：</td>
    <td align="left" >$rsdb[my_buy]&nbsp;</td>
</tr>
  
</table>

	
	</td>
  </tr>
</table>

	
	

	
	
	
	
	</td>
  </tr>
</table>
<!--
EOT;
if(!$m){
if($lfjuid || $webdb[company_lxfs]){
print <<<EOT
-->
	<div style="margin-top:5px;margin-bottom:5px;padding:5px;border:1px solid #FFCC7F;line-height:30px;font-size:14px; background-color:#FFFFE5">
	<TABLE>
	<TR>
		<TD height=30>联系电话：$rsdb[qy_contact_tel]</TD>
	</TR>
	<TR>
		<TD height=30>传真：$rsdb[qy_contact_fax]&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;电子邮件：$rsdb[qy_contact_email] (请手动将‘＃’换成‘@’)</TD>
	</TR>
	<TR>
			<TD height=30>联系地址：$rsdb[qy_address]</TD>
		</TR>	
	<TR>
		<TD height=30>商铺地址：$Mdomain/homepage.php?uid=$uid </TD>
	</TR>

	<TR>
		<TD height=30><b>在线沟通工具:<b></TD>
	</TR>
	<TR>
		<TD height=30>客服QQ：$rsdb[qq]</TD>
	</TR>
	<TR>
		<TD height=30>客服MSN：$rsdb[msn]</TD>
	</TR>
	<TR>
		<TD height=30>客服旺旺：$rsdb[ww]</TD>
	</TR>
	
	</TABLE>
	</div>
<!--
EOT;
}}
if($show_bd_pics){
print <<<EOT
-->
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="rightinfo">
  <tr>
    <td  class="head">
	<span class='L'></span>
	<span class='T'><a href="$Mdomain/homepage.php?uid=$uid&m=info">企业资质</a></span>
	<span class='R'></span>
	<span class='more'></span>

	</td>
  </tr>
  <tr>
    <td class="content">
	$show_bd_pics
	</td></tr>
	</table>
<!--
EOT;
}
print <<<EOT
-->
<!--
EOT;
?>
