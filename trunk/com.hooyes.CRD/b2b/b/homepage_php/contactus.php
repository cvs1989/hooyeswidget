<?php
echo $rsdb[province_id];
$rsdb[qq]=getOnlinecontact('qq',$rsdb[qq]);
$rsdb[msn]=getOnlinecontact('msn',$rsdb[msn]);
$rsdb[skype]=getOnlinecontact('skype',$rsdb[skype]);
$rsdb[ww]=getOnlinecontact('ww',$rsdb[ww]);
$rsdb[qy_contact_email] =str_replace("@","#",$rsdb[qy_contact_email]);

print <<<EOT
-->

	
	<table width="100%" border="0" cellspacing="0" cellpadding="0"  class="rightinfo">
  <tr>
    <td  class="head">
	<span class=L></span>
	<span class=T><a href="$Mdomain/homepage.php?uid=$uid&m=contactus">��ϵ����</a></span>
	<span class=R></span>
	<span class='more'>
<!--
EOT;
if($lfjuid==$uid){
print <<<EOT
-->
	<a href='$Mdomain/member/?main=homepage_ctrl.php?atn=contactus' target='_blank'>�޸�</a>
<!--
EOT;
}
print <<<EOT
-->	
	</span>

	</td>
  </tr>
  <tr>
    <td  class="content">

<!--
EOT;
if($lfjuid || $webdb[company_lxfs]){
print <<<EOT
-->	
	<div style="line-height:180%;margin:5px 0px 0px 10px;clear:both;color:#454545">
	<table width="97%" border="0" cellpadding="5" cellspacing="1" bgcolor="#CCCCCC" style="clear:both; line-height:200%; color:#454545">
  <tr>
    <td width="15%" align="center" bgcolor="#F9f9f9" style='color:#454545';>��λ���ƣ�</td>
    <td  align="left" bgcolor="#FFFFFF" style='color:#454545'>&nbsp;$rsdb[title]</td>
	<td width="15%" align="center" bgcolor="#F9F9F9" style='color:#454545'> ְλ</td>
    <td width="35%" align="left" bgcolor="#FFFFFF" style='color:#454545'>&nbsp;$rsdb[qy_contact_zhiwei]</td>
  </tr>
  <tr>
    <td align="center" bgcolor="#F9f9f9" style='color:#454545'>�� ϵ �ˣ�</td>
    <td width="35%" align="left" bgcolor="#FFFFFF" style='color:#454545'>&nbsp;$rsdb[qy_contact]</td>
    <td width="15%" align="center" bgcolor="#F9F9F9" style='color:#454545'> �绰���룺</td>
    <td width="35%" align="left" bgcolor="#FFFFFF" style='color:#454545'>&nbsp;$rsdb[qy_contact_tel]</td>
  </tr>
  <tr>
    <td align="center" bgcolor="#F9f9f9" style='color:#454545'>������룺</td>
    <td align="left" bgcolor="#FFFFFF" style='color:#454545'>&nbsp;$rsdb[qy_contact_fax]</td>
    <td align="center" bgcolor="#F9F9F9" style='color:#454545'> �ƶ����룺</td>
    <td align="left" bgcolor="#FFFFFF" style='color:#454545'>&nbsp;$rsdb[qy_contact_mobile]</td>
  </tr>
  <tr>
    <td align="center" bgcolor="#F9f9f9" style='color:#454545'>��λ��ҳ��</td>
    <td align="left" bgcolor="#FFFFFF" style='color:#454545'>&nbsp;<a href='$rsdb[qy_website]' target='_blank' style='color:#454545'>$rsdb[qy_website]</a></td>
    <td align="center" bgcolor="#F9F9F9" style='color:#454545'>�����ַ��</td>
    <td align="left" bgcolor="#FFFFFF" style='color:#454545'>&nbsp;$rsdb[qy_contact_email]<br>(���ֶ������������ɡ�@��)</td>
  </tr>
  <tr>
    <td align="center" bgcolor="#F9f9f9" style='color:#454545'>���ڵ�����</td>
    <td align="left" bgcolor="#FFFFFF" style='color:#454545'>&nbsp;$rsdb[province_id] $rsdb[city_id]</td>
    <td align="center" bgcolor="#F9F9F9" style='color:#454545'>�������룺</td>
    <td align="left" bgcolor="#FFFFFF" style='color:#454545'>&nbsp;$rsdb[qy_postnum]</td>
  </tr>
  <tr>
    <td align="center" bgcolor="#F9f9f9" style='color:#454545'>��ϸ��ַ��</td>
    <td colspan="3" align="left" bgcolor="#FFFFFF" style='color:#454545' >&nbsp;$rsdb[qy_address]</td>
  </tr>
  <tr>
    <td align="center" bgcolor="#F9f9f9" style='color:#454545'>���߽�����</td>
    <td colspan="3" align="left" bgcolor="#FFFFFF" style='padding:10px;'>
	<p style='color:#454545;height:20px;'>Q Q:$rsdb[qq]</p>
	<p style='color:#454545;height:20px;'>MSN:$rsdb[msn]</p>
	<p style='color:#454545;height:20px;'>��������:$rsdb[ww]</p>
	</td>
  </tr>
</table>
<!--
EOT;
}else{
print <<<EOT
-->	
��Ǹ��ֻ�е�¼����ܲ鿴����ϵ��ʽ��

<!--
EOT;
}
print <<<EOT
-->	
<br>
	
	</td>
  </tr>
</table>

 
<!--
EOT;
?>
