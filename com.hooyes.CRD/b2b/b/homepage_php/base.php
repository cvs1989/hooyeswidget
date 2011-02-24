<?php
$rsdb[posttime]=date("Y-m-d",$rsdb[posttime]);
$rsdb[province_id]=$area_DB[name][$rsdb[province_id]];
$rsdb[city_id]=$city_DB[name][$rsdb[city_id]];
$rsdb[services]=get_services($rsdb);

print <<<EOT
-->   
<table width="100%" border="0" cellspacing="0" cellpadding="0"  class="leftinfo">
  <tr>
    <td class="head" ><span class='L'></span>
	<span class='T'>商家档案</span>
	<span class='R'></span>
	<span  style='float:right; padding-right:10px;font-weight:100;' class='more'>
<!--
EOT;
if($lfjuid==$uid){
print <<<EOT
-->
	<a href='$Mdomain/member/?main=homepage_ctrl.php?atn=info&uid=$uid' target='_blank'>管理</a> 
<!--
EOT;
}
print <<<EOT
-->

	</span></td>
  </tr>
  <tr>
    <td class="content base">
		<center><a href="?uid=$uid"><img src="$rsdb[logo]"  border="0" width="120"   height="120"  class="logo" onerror="this.src='$Murl/images/default/nopic.jpg';"/></a>      </center>
		
		<center><B>$rsdb[company_name_big]</B></center>
		<center>$rsdb[services]</center>
		<center>$rsdb[province_id] $rsdb[city_id]</center>
		<center><a href="?uid=$uid&m=certification">$rsdb[renzheng]</a></center>
		<center>通行证：$rsdb[username]</center>
		<center>登记时间：$rsdb[posttime]</center>
		<center>		
		<a href='$Mdomain/job.php?job=collect&id=$rsdb[rid]&ctype=3' ><img src='$Murl/images/homepage_style/addcoll.gif' border=0 alt="收藏本商铺"></a>
		<a href='$Mdomain/member/?main=pm.php?job=send&username=$rsdb[username]' target="_blank"><img src='$Murl/images/homepage_style/sendmsg.gif' border=0 alt='发送站内信'></a>	</center>
		
		
	</td>
  </tr>
</table>

 
<!--
EOT;

unset($listdb);
$query=$db->query("select * from {$_pre}cankao where uid='$uid' and yz=1  limit 0,3");

while($rs=$db->fetch_array($query)){
	$listdb[]=$rs;
}
if($listdb || $lfjuid==$uid){
print <<<EOT
-->

<table width="100%" border="0" cellspacing="0" cellpadding="0"  class="leftinfo">
  <tr>
    <td class="head" ><span class='L'></span>
	<span class='T'><a href='?uid=$uid&m=ck'>站外参考资料</a></span>
	<span class='R'></span>
	<span  style='float:right; padding-right:10px;font-weight:100;' class='more'>
<!--
EOT;
if($lfjuid==$uid){
print <<<EOT
-->
	<a href='$Mdomain/member/?main=cankao.php' target='_blank'>管理</a> 
<!--
EOT;
}
print <<<EOT
-->

	</span></td>
  </tr>
  <tr>
    <td class="content">
<!--
EOT;
foreach($listdb as $rs){
print <<<EOT
-->
 <p><font color="blue">$rs[title]</font><br /><a href="$rs[url]" target="_blank">$rs[url]</a></p>
<!--
EOT;
}
unset($listdb);
print <<<EOT
-->


	</td>
  </tr>
</table>
 
<!--
EOT;
}
//此行必存
?>