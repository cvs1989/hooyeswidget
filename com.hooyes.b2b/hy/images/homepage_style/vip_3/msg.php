<?php
unset($listdb);
if(!$m){
	$conf[listnum][guestbook]=$conf[listnum][guestbook]?$conf[listnum][guestbook]:4;
	$listdb=get_guestbook($conf[listnum][guestbook]);
	$showpage="";
}else{
	$conf[listnum][Mguestbook]=$conf[listnum][Mguestbook]?$conf[listnum][Mguestbook]:10;
	$listdb=get_guestbook($conf[listnum][Mguestbook]);
	$showpage=getpage("{$_pre}guestbook A"," WHERE A.cuid='$uid'","?m=$m&uid=$uid",$conf[listnum][Mguestbook]);
}

function get_guestbook($rows){
	global $db,$uid,$_pre,$pre,$Mrows,$albumid,$page,$Morder,$Mdesc,$web_admin,$lfjuid,$uid,$webdb,$VlogCfg;
	if($page<1){
		$page=1;
	}
	$min=($page-1)*$rows;
	//$SQL=" AND A.yz='1' ";
	$Mdesc[guestbook] || $Mdesc[guestbook]='DESC';
	$Morder[guestbook] || $Morder[guestbook]="list";
	$query = $db->query("SELECT A.*,M.picurl,M.title FROM {$_pre}guestbook A LEFT JOIN {$_pre}company M ON A.uid=M.uid  WHERE A.cuid='$uid' ORDER BY A.posttime desc LIMIT $min,$rows");
	while($rs = $db->fetch_array($query)){
		$rs[posttime]=date("y/m/d H:i:s",$rs[posttime]);

		$detail=explode(".",$rs[ip]);

		$rs[ip]="$detail[0].$detail[1].$detail[2].*";
		if(!$rs[username]){
			$detail=explode(".",$rs[ip]);
			$rs[username]="$detail[0].$detail[1].*.*";
		}
		if($web_admin||$lfjuid==$rs[uid]||$lfjuid==$rs[cuid]){
			$rs['delete']="[<A HREF='?m=msg&uid=$uid&page=$page&action=msg_delete&id=$rs[id]'>删除</A>]";
		}
		$rs[content]=str_replace("\n","<br>",$rs[content]);
		$listdb[]=$rs;
	}
	return $listdb;
}
?>
<!--
<?php
print <<<EOT
-->   
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="rightinfo">
  <tr>
    <td  class="head">
	<span class='L'></span>
	<span class='T'><a href="$Mdomain/homepage.php?uid=$uid&m=msg">留 言 本</a></span>
	<span class='R'></span>
	<span class='more'><a href='$Mdomain/homepage.php?uid=$uid&m=msg#do' >我要留言</a></span>

	
	</td>
  </tr>
  <tr>
    <td class="content">

<!--
EOT;
foreach( $listdb AS $key=>$rs){
print <<<EOT
--> 
  <p><span class="user">{$rs[username]}:</span> {$rs[content]}</p>
  <div class="msg"><span class="time">$rs[posttime]</span><span class="del">{$rs['delete']}</span></div>
<!--
EOT;
}
print <<<EOT
-->	

<!--
EOT;
if($m){
print <<<EOT
-->

<div class="page">$showpage</div>

<!--
EOT;

if($lfjuid){
	$sub_name="提交留言";$alw_submit="";
}else{
	$sub_name="登录后方可留言";$alw_submit=" disabled='disabled'";
}
print <<<EOT
-->
<a name='do'></a>
<form action="?" method="post" name="msg">
	<p>访客留言:</p>
	<p>验证码: <input type="text" name="yzimg" size="8"> <SCRIPT LANGUAGE="JavaScript">
<!--
document.write('<img border="0" name="imageField" onclick="this.src=this.src+Math.random();" src="$webdb[www_url]/do/yzimg.php?'+Math.random()+'">');
//-->
</SCRIPT></p>
	<p><textarea name="content" cols="40" rows="5"></textarea></p>
	<p>请注意文明用语，留言内容不能超过500个字;</p>
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
  <tr>
    <td  class="foot"></td>
  </tr>
</table>

 
<!--
EOT;
?>
-->