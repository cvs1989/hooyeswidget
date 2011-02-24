<?php

$query=$db->query("SELECT * FROM {$_pre}mysort WHERE uid='$uid' AND ctype='1' ORDER BY listorder DESC;");
while($rs=$db->fetch_array($query)){
			 $sel=$rs[ms_id]==$ms_id?"<font color=red>".$rs[sortname]."</font>":$rs[sortname];
			 $ms_id_options1.="<li>·<a href='?uid=$uid&m=selllist&ms_id=$rs[ms_id]'>$sel</a> &nbsp;</li>";
			 if($rs[ms_id]==$ms_id) $mysortname=$rs[sortname];
}
if(!$ms_id_options1)$ms_id_options1="<li>·<a href='?uid=$uid&m=selllist'>无分类</a> &nbsp;</li>";
	
$query=$db->query("SELECT * FROM {$_pre}mysort WHERE uid='$uid' AND ctype='2' ORDER BY listorder DESC;");
while($rs=$db->fetch_array($query)){
			 $sel=$rs[ms_id]==$ms_id?"<font color=red>".$rs[sortname]."</font>":$rs[sortname];
			 $ms_id_options2.="<li>·<a href='?uid=$uid&m=buylist&ms_id=$rs[ms_id]'>$sel</a> &nbsp;</li>";
			 if($rs[ms_id]==$ms_id) $mysortname=$rs[sortname];
}
if(!$ms_id_options2)$ms_id_options2="<li>·<a href='?uid=$uid&m=buylist'>无分类</a> &nbsp;</li>";

print <<<EOT
--> 

<table width="100%" border="0" cellspacing="0" cellpadding="0"  class="leftinfo">
  <tr>
    <td  class="head">
		<span class='L'></span>
	<span class='T'>信息分类</span>
	<span class='R'></span>
	<span class='more' style='float:right;  padding-right:10px;font-weight:100;'>
<!--
EOT;
if($lfjuid==$uid){
print <<<EOT
-->
	<a href='$Mdomain/member/?main=myinfosort.php' target='_blank'>管理</a> 
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
	    <strong><a href='?uid=$uid&m=selllist'>供应</a></strong>：
		$ms_id_options1
		<br><strong><a href='?uid=$uid&m=buylist'>求购</a></strong>：
		$ms_id_options2
	</td>
  </tr>
</table>
 
<!--
EOT;
?>
