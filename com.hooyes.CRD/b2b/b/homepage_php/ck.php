<?php
unset($listdb);
$query=$db->query("select * from {$_pre}cankao where uid='$uid' and yz=1 ");
	while($rs=$db->fetch_array($query)){
		$listdb[]=$rs;	
	}

print <<<EOT
-->   
<table width="100%" border="0" cellspacing="0" cellpadding="0"  class="rightinfo">
  <tr>
    <td  class="head">
	<span class='L'></span>
	<span class='T'>վ��ο�����</span>
	<span class='R'></span>
	<span class='more'></span>
	
	</td>
  </tr>
  <tr>
    <td  class="content">


<!--
EOT;
foreach($listdb as $rs){
print <<<EOT
-->
<divstyle='padding-left:20px;'>
<div><a href="{$rs[url]}" target="_blank"><b style='font-size:14px;'><font color="blue">$rs[title]</font></b></a>
�ο���ַ��<a href="{$rs[url]}" target="_blank">{$rs[url]}</a>	
</div>

<div><font color='#676767'>$rs[description]</font></div>
</div>

<p>&nbsp;</p>

<!--
EOT;
}
print <<<EOT
-->
	
	</td>
  </tr>
</table>

   
 
<!--
EOT;
unset($listdb);
?>
