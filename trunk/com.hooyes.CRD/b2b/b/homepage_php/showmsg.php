<?php

print <<<EOT
--> 

<table width="100%" border="0" cellspacing="0" cellpadding="0"  class="rightinfo">
  <tr>
    <td  class="head">
		<span class='L'></span>
	<span class='T'>��Ϣ��ʾ</span>
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
if($t=='regvendor'){ //���빩Ӧ�̳ɹ�
print <<<EOT
-->	 
	 <b style='font-size:16px;color:Red'>��ϲ���������Ϊ��Ӧ�̳ɹ����ȴ�ȷ����</b>
	<br><strong>���빩Ӧ��֮��</strong>��<br>
	1.���ڹ�Ӧ�����ɹ����ɹ���Ϣ������ʾ���������۲����ƣ�<br>
	2.���ڲɹ��ţ���Ӧ�̹�Ӧ��Ϣ������ʾ������ѯ�۲����ƣ�<br>
	3.���ٱ�ݻ������ţ�<br>
	4.˫����֤��Ϣ���࿪�ţ��˴˸��˽�Է���		 
<!--
EOT;
}elseif($t=="regvendor2"){
print <<<EOT
-->	 
	 <b style='font-size:16px;color:Red'>��ϲ���������Ϊ�ɹ��̳ɹ����Ѿ�ȷ����</b>
	<br><strong>����ɹ���֮��</strong>��<br>
	1.���ڹ�Ӧ�����ɹ����ɹ���Ϣ������ʾ���������۲����ƣ�<br>
	2.���ڲɹ��ţ���Ӧ�̹�Ӧ��Ϣ������ʾ������ѯ�۲����ƣ�<br>
	3.���ٱ�ݻ������ţ�<br>
	4.˫����֤��Ϣ���࿪�ţ��˴˸��˽�Է���		 
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
