<?php

@extract($db->get_one("SELECT COUNT(*) AS sellNUM       FROM {$_pre}content             WHERE uid='$uid' and `ctype`=1"));
@extract($db->get_one("SELECT COUNT(*) AS buyNUM        FROM {$_pre}content             WHERE uid='$uid' and `ctype`=2"));
@extract($db->get_one("SELECT COUNT(*) AS guestbookNUM  FROM {$_pre}homepage_guestbook  WHERE cuid='$uid'" ));


if( ereg("^pwbbs",$webdb[passport_type]) )
{
	@extract($db->get_one("SELECT COUNT(*) AS pmNUM FROM `{$TB_pre}msg` WHERE `touid`='$uid' AND type='rebox' AND ifnew=1"));
}
elseif( ereg("^dzbbs",$webdb[passport_type]) )
{
	@extract($db->get_one("SELECT COUNT(*) AS pmNUM FROM `{$TB_pre}pms` WHERE `msgtoid`='$uid' AND folder='inbox' AND new=1"));
}
else
{
	@extract($db->get_one("SELECT COUNT(*) AS pmNUM FROM `{$pre}pm` WHERE `touid`='$uid' AND type='rebox' AND ifnew='1'"));
}

if($pmNUM)
{
	$pmNUM_color='red';
}

print <<<EOT
-->   
<table width="100%" border="0" cellspacing="0" cellpadding="0"  class="leftinfo">
  <tr>
    <td  class="head">
	<span class='L'></span>
	<span class='T'>统计信息</span>
	<span class='R'></span>
	<span class='more'></span>
	
	</td>
  </tr>
  <tr>
    <td  class="content">
 <li>·供应信息:<a href='?m=selllist&uid=$uid'><b><font color=red>{$sellNUM}</b></font></a> 条</li>
			<li>·求购信息:<a href='?m=buylist&uid=$uid'><b><font color=red>{$buyNUM}</b></font></a> 条</li>
			<li>·访客留言共:{$guestbookNUM} 条</li>
			<li>·页面点击量:{$rsdb[hits]} 次</li>
<!--
EOT;
if($uid==$lfjuid){
print <<<EOT
--> 
			<li>·<A HREF="$Mdomain/member/?main=pm.php?job=list" target="_blank" style='color:$pmNUM_color;'>新的短消息:{$pmNUM} 条</A></li>
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
?>
