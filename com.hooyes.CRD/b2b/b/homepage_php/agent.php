<?php
unset($listdb);
@require(Mpath."php168/companyData.php");
$query=$db->query("select * from {$_pre}agents where uid='$uid' and yz=1 order by posttime desc ");
	while($rs=$db->fetch_array($query)){
		$rs[posttime]=date("Y-m-d H:i",$rs[posttime]);
		if($rs[yz_time])$rs[yz_time]=date("Y-m-d",$rs[yz_time]);
		$rs[ag_cert]=$webdb[www_url]."/".$webdb[updir]."/{$Imgdirname}/ag_cert/$rs[uid]/".$rs[ag_cert];
		$rs[ag_level]=$ag_level_array[$rs[ag_level]];
		$rs[contact_info]=unserialize($rs[contact_info]);

		$listdb[]=$rs;	
	}

print <<<EOT
-->   
<table width="100%" border="0" cellspacing="0" cellpadding="0"  class="rightinfo">
  <tr>
    <td  class="head">
	<span class='L'></span>
	<span class='T'>我是代理商</span>
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
<div  style='padding-left:20px;'>
<div><img src='$Murl/images/$STYLE/is_agent.gif' border=0 style='float:left'>&nbsp;&nbsp;<b style='font-size:14px;'>$rs[ag_name]</b>
({$rs[ag_level]},$rs[yz_time]通过审核,点击这里查看<a href='$rs[ag_cert]' target=_blank><font color=blue>代理证书</font></a>)</div>
<div>
联 系 人：{$rs[contact_info][name]}<br />
联系电话：{$rs[contact_info][tel]}<br />
传真号码：{$rs[contact_info][fax]}<br />
邮箱地址：{$rs[contact_info][email]}<br />
详细地址：{$rs[contact_info][address]}<br />
参考网址：<a href="{$rs[contact_info][url_link]}" target="_blank">{$rs[contact_info][url]}</a>
</div>
</div>

<p>&nbsp;</p><p>&nbsp;</p>

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
