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
	<span class='T'>ͳ����Ϣ</span>
	<span class='R'></span>
	<span class='more'></span>
	
	</td>
  </tr>
  <tr>
    <td  class="content">
 <li>����Ӧ��Ϣ:<a href='?m=selllist&uid=$uid'><b><font color=red>{$sellNUM}</b></font></a> ��</li>
			<li>������Ϣ:<a href='?m=buylist&uid=$uid'><b><font color=red>{$buyNUM}</b></font></a> ��</li>
			<li>���ÿ����Թ�:{$guestbookNUM} ��</li>
			<li>��ҳ������:{$rsdb[hits]} ��</li>
<!--
EOT;
if($uid==$lfjuid){
print <<<EOT
--> 
			<li>��<A HREF="$Mdomain/member/?main=pm.php?job=list" target="_blank" style='color:$pmNUM_color;'>�µĶ���Ϣ:{$pmNUM} ��</A></li>
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
