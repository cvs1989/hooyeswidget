<?php
unset($listdb);
if(!$m){
	$conf[listnum][guestbook]=$conf[listnum][guestbook]?$conf[listnum][guestbook]:4;
	$listdb=get_guestbook($conf[listnum][guestbook]);
	$showpage="";
}else{
	$conf[listnum][Mguestbook]=$conf[listnum][Mguestbook]?$conf[listnum][Mguestbook]:10;
	$listdb=get_guestbook($conf[listnum][Mguestbook]);
	$showpage=getpage("{$_pre}homepage_guestbook A"," WHERE A.cuid='$uid'","?m=$m&uid=$uid",$conf[listnum][Mguestbook]);
}


print <<<EOT
-->   
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="rightinfo">
  <tr>
    <td  class="head">
	<span class='L'></span>
	<span class='T'><a href="$Mdomain/homepage.php?uid=$uid&m=msg">�� �� ��</a>($guestbookNUM)</span>
	<span class='R'></span>
	<span class='more'><a href='$Mdomain/homepage.php?uid=$uid&m=msg#do' >��Ҫ����</a></span>

	
	</td>
  </tr>
  <tr>
    <td class="content">

<table width="98%" border="0" cellspacing="0" cellpadding="5">

<!--
EOT;
foreach( $listdb AS $key=>$rs){
print <<<EOT
--> 
  <tr>
    <td width=150 height=70 style='border-bottom:1px #565656 dotted;border-right:1px #cccccc dotted; padding:5px '><strong>$rs[username]</strong> <br>$rs[posttime]</td>
    <td style='border-bottom:1px #565656 dotted ; padding:5px;'>{$rs[content]}&nbsp;<p>{$rs['delete']}</p></td>
  </tr>	
<!--
EOT;
}
print <<<EOT
-->	

</table>


<!--
EOT;
if($m){
print <<<EOT
-->

<div class="page">$showpage</div>

<!--
EOT;

if($lfjuid){
	$sub_name="�ύ����";$alw_submit="";
}else{
	$sub_name="��¼�󷽿�����";$alw_submit=" disabled='disabled'";
}
print <<<EOT
-->
<a name='do'></a>
<form action="?" method="post" name="msg">
	<p>�ÿ�����:</p>
	<p><textarea name="content" cols="40" rows="5"></textarea></p>
	<p>��ע����������������ݲ��ܳ���500����;</p>
	<p><input name="ssss" type="submit" value="$sub_name" $alw_submit  /></p>
	<input name="uid" type="hidden" value="$uid" /><input name="m" type="hidden" value="$m" /><input name="action" type="hidden" value="msg_post" />
</form>	
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


function get_guestbook($rows){
	global $db,$uid,$_pre,$Mrows,$albumid,$page,$Morder,$Mdesc,$web_admin,$lfjuid,$uid,$webdb,$VlogCfg;
	if($page<1){
		$page=1;
	}
	$min=($page-1)*$rows;
	//$SQL=" AND A.yz='1' ";
	$Mdesc[guestbook] || $Mdesc[guestbook]='DESC';
	$Morder[guestbook] || $Morder[guestbook]="list";
	$query = $db->query("SELECT A.*,M.picurl,M.title FROM {$_pre}homepage_guestbook A LEFT JOIN {$_pre}company M ON A.uid=M.uid  WHERE A.cuid='$uid' ORDER BY A.posttime desc LIMIT $min,$rows");
	while($rs = $db->fetch_array($query)){
		$rs[posttime]=date("y/m/d H:i:s",$rs[posttime]);
		
		$detail=explode(".",$rs[ip]);
		
		$rs[ip]="$detail[0].$detail[1].$detail[2].*";
		if(!$rs[username]){
			$detail=explode(".",$rs[ip]);
			$rs[username]="$detail[0].$detail[1].*.*";
		}
		if($web_admin||$lfjuid==$rs[uid]||$lfjuid==$rs[cuid]){
			$rs['delete']="[<A HREF='?m=msg&uid=$uid&page=$page&action=msg_delete&id=$rs[id]'>ɾ��</A>]";
		}
		
	
		$rs[content]=str_replace("\n","<br>",$rs[content]);
		$listdb[]=$rs;
	}
	return $listdb;
}


?>
