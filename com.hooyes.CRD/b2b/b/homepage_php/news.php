<?php
unset($listdb);
	if($m) $rows=$conf[listnum][Mnewslist]?$conf[listnum][Mnewslist]:20;
	else $rows=$conf[listnum][newslist]?$conf[listnum][newslist]:5;
	
	$rows=10;
	if($page<1){
		$page=1;
	}
	$min=($page-1)*$rows;
	$where=" where rid='$rsdb[rid]' and yz=1 ";
	$query=$db->query("select * from {$_pre}homepage_article $where order by posttime desc limit $min,$rows");
	
	while($rs=$db->fetch_array($query)){
		$rs[posttime]=date("Y-m-d",$rs[posttime]);
		$rs[content]=@preg_replace('/<([^>]*)>/is',"",$rs[content]);	//��HTML�������
		$rs[content]=get_word(str_replace("&nbsp;","",$rs[content]),200);
		$listdb[]=$rs;
	}	
	
	$showpage=getpage("{$_pre}homepage_article",$where,"?uid=$uid&atn=$atn",$rows);
	$mod_in=$mod_in?$mod_in:'right';

print <<<EOT
-->   

<table width="100%" border="0" cellspacing="0" cellpadding="0"  class="{$mod_in}info">
  <tr>
    <td  class="head">
<span class='L'></span>
	<span class='T'>��˾����</span>
	<span class='R'></span>
	<span class='more'>
<!--
EOT;
if($lfjuid==$uid && $mod_in=='right'){
print <<<EOT
-->
	<a href='$Mdomain/member/?main=homepage_ctrl.php?atn=news' target='_blank'>��������</a> | <a href='$Mdomain/member/?main=homepage_ctrl.php?atn=postnews' target='_blank'>��������</a> 
<!--
EOT;
}
print <<<EOT
-->
	</span></td>
  </tr>
  <tr>
    <td  class="content">
<!--
EOT;
if($mod_in=='left'){

foreach($listdb as $rs){
$rs[title]=get_word($rs[title_full]=$rs[title],30);
print <<<EOT
-->

 ��<a href="homepage.php?uid=$uid&m=newsview&id=$rs[id]" target="_blank" title='$rs[posttime]-$rs[title_full]'>$rs[title]</a><br>


<!--
EOT;
}

}else{
print <<<EOT
-->
<table width="100%" border="0" cellspacing="0" cellpadding="0">
<!--
EOT;
foreach($listdb as $rs){

print <<<EOT
-->

  <tr>
    <td >��$rs[posttime]��<a href="homepage.php?uid=$uid&m=newsview&id=$rs[id]" target="_blank">$rs[title]</a></td>
  </tr>
  <tr>
    <td style='color:#989898' height=50>$rs[content]</td>
  </tr>


<!--
EOT;
}
print <<<EOT
-->
</table>
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
