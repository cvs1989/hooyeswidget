<?php

$query=$db->query("SELECT * FROM {$_pre}mysort WHERE uid='$uid' AND ctype='1' ORDER BY listorder DESC;");
while($rs=$db->fetch_array($query)){
			 $sel=$rs[ms_id]==$ms_id?"<font color=red>".$rs[sortname]."</font>":$rs[sortname];
			 $ms_id_options1.="<li>��<a href='?uid=$uid&m=selllist&ms_id=$rs[ms_id]'>$sel</a> &nbsp;</li>";
			 if($rs[ms_id]==$ms_id) $mysortname=$rs[sortname];
}
if(!$ms_id_options1)$ms_id_options1="<li>��<a href='?uid=$uid&m=selllist'>�޷���</a> &nbsp;</li>";
	
$query=$db->query("SELECT * FROM {$_pre}mysort WHERE uid='$uid' AND ctype='2' ORDER BY listorder DESC;");
while($rs=$db->fetch_array($query)){
			 $sel=$rs[ms_id]==$ms_id?"<font color=red>".$rs[sortname]."</font>":$rs[sortname];
			 $ms_id_options2.="<li>��<a href='?uid=$uid&m=buylist&ms_id=$rs[ms_id]'>$sel</a> &nbsp;</li>";
			 if($rs[ms_id]==$ms_id) $mysortname=$rs[sortname];
}
if(!$ms_id_options2)$ms_id_options2="<li>��<a href='?uid=$uid&m=buylist'>�޷���</a> &nbsp;</li>";

print <<<EOT
--> 

<table width="100%" border="0" cellspacing="0" cellpadding="0"  class="leftinfo">
  <tr>
    <td  class="head">
		<span class='L'></span>
	<span class='T'>��Ϣ����</span>
	<span class='R'></span>
	<span class='more' style='float:right;  padding-right:10px;font-weight:100;'>
<!--
EOT;
if($lfjuid==$uid){
print <<<EOT
-->
	<a href='$Mdomain/member/?main=myinfosort.php' target='_blank'>����</a> 
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
	    <strong><a href='?uid=$uid&m=selllist'>��Ӧ</a></strong>��
		$ms_id_options1
		<br><strong><a href='?uid=$uid&m=buylist'>��</a></strong>��
		$ms_id_options2
	</td>
  </tr>
</table>
 
<!--
EOT;
?>
