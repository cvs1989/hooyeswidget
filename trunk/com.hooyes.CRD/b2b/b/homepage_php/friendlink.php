<?php
if($conf[friendlink]){
	$conf[friendlink]=explode(",",$conf[friendlink]);
	foreach($conf[friendlink] as $k){
		$kk[]="'".$k."'";
		if(count($kk) >= $conf[listnum][friendlink]) break;
	}
	$conf[friendlink]=implode(",",$kk);
	$query=$db->query("select rid,uid,title from {$_pre}company where username in(".$conf[friendlink].")");
	while($rs=$db->fetch_array($query)){
		$list_friendlink.="<li>·<a href='$Mdomain/homepage.php?uid=$rs[uid]' target='_blank'>$rs[title]</a>";
	}
}else{
	$list_friendlink="暂无";
}
print <<<EOT
-->   

<table width="100%" border="0" cellspacing="0" cellpadding="0"  class="leftinfo">
  <tr>
    <td  class="head"><span class='L'></span>
	<span class='T'>友情链接</span>
	<span class='R'></span>
	<span class='more' style='float:right; padding-right:10px;font-weight:100;'>
<!--
EOT;
if($lfjuid==$uid){
print <<<EOT
-->
	<a href='$Mdomain/member/?main=homepage_ctrl.php?atn=friendlink' target='_blank'>管理</a>
<!--
EOT;
}
print <<<EOT
-->		
	</span></td>
  </tr>
  <tr>
    <td  class="content"> $list_friendlink</td>
  </tr>
</table>

 
<!--
EOT;
?>
