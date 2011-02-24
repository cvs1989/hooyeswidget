<?php

print <<<EOT
--> 

<table width="100%" border="0" cellspacing="0" cellpadding="0"  class="rightinfo">
  <tr>
    <td  class="head">
		<span class='L'></span>
	<span class='T'>信息提示</span>
	<span class='R'></span>
	<span class='more'></span>
	</td>
  </tr>
  <tr>
    <td  class="content">
<table width="90%" border="0" cellspacing="5" cellpadding="5" align=center style="border:1px #f9f9f9 solid;">
  <tr>
    <td rowspan=2 align=center width='60'><img src="$Murl/images/homepage_style/notice.gif" border=0></td>
	 <td height=25 align=left>

<!--
EOT;
if($t=='regvendor'){ //申请供应商成功
print <<<EOT
-->	 
	 <b style='font-size:16px;color:Red'>恭喜您，申请成为供应商成功，等待确定。</b>
	<br><strong>加入供应商之后</strong>：<br>
	1.对于供应方：采购方采购信息发布提示，批量报价不限制；<br>
	2.对于采购放：供应商供应信息发布提示，批量询价不限制；<br>
	3.快速便捷互发短信；<br>
	4.双方认证信息互相开放，彼此更了解对方。		 
<!--
EOT;
}elseif($t=="regvendor2"){
print <<<EOT
-->	 
	 <b style='font-size:16px;color:Red'>恭喜您，申请成为采购商成功，已经确定。</b>
	<br><strong>加入采购商之后</strong>：<br>
	1.对于供应方：采购方采购信息发布提示，批量报价不限制；<br>
	2.对于采购放：供应商供应信息发布提示，批量询价不限制；<br>
	3.快速便捷互发短信；<br>
	4.双方认证信息互相开放，彼此更了解对方。		 
<!--
EOT;
}
print <<<EOT
-->	
	 </td>
  </tr>

</table>
 
	</td>
  </tr>
</table>
 
<!--
EOT;
?>
