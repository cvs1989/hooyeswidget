<?php
unset($listdb);
if($mod_in=='left'){
	$rows=$conf[listnum][zh]?$conf[listnum][zh]:10;
}else{
	if($m) $rows=$conf[listnum][Mzh]?$conf[listnum][Mzh]:20;
	else $rows=$conf[listnum][zh]?$conf[listnum][zh]:10;
}
	$page=intval($page);
	if($page<1) $page=1;
	$min=($page-1)*$rows;

	$where=" WHERE A.yz=1 and uid='$uid' and rid='$rsdb[rid]'";
		
	$query=$db->query("select * from {$_pre}zh_content A $where order by A.levels desc,posttime desc limit $min,$rows");
	
	while($rs=$db->fetch_array($query)){
		$rs[posttime] =date("Y-m-d",$rs[posttime]);
		$rs[starttime]=date("Y-m-d",$rs[starttime]);
		$rs[endtime]  =date("Y-m-d",$rs[endtime]);
		$rs[title]    =get_word($rs[title_full]=$rs[title],60);

		$rs[title]    =$rs[color]?"<font color='$rs[color]'>$rs[title]</font>":$rs[title];
		$rs[title]    =$rs[levels]||$rs[levels_pic]?"<b>$rs[title]</b>":$rs[title];

		$rs[showroom_name]    =get_word($rs[showroom_name_full]=$rs[showroom_name],50);

		$rs[content]  =get_word($rs[content],200);
		if($rs[picurl]) 	$rs[picurl]   =$webdb[www_url]."/".$webdb[updir]."/zh/".$rs[picurl];
		$rs[area]     =$area_DB[name][$rs[province_id]]." ".$city_DB[name][$rs[city_id]];
		$listdb[]=$rs;
	}
	
$showpage=getpage("{$_pre}zh_content A",$where,"?uid=$uid&m=$m",$rows);


print <<<EOT
-->   

 
 <table width="100%" border="0" cellspacing="0" cellpadding="0" class="rightinfo">
  <tr>
    <td  class="head">	<span class='L'></span>
	<span class='T'>展会信息</span>
	<span class='R'></span>
	<span class='more'>
<!--
EOT;
if($lfjuid==$uid){
print <<<EOT
-->
	<a href='$Mdomain/member/?main=zh.php?job=zh' target='_blank'>我发布的展会</a> | 
	<a href='$Mdomain/member/?main=zh.php?job=zlg' target='_blank'>我发布的展览馆</a> | 
	<a href='$Mdomain/zh_post.php?job=postzh' target='_blank'>发布</a>
<!--
EOT;
}
print <<<EOT
-->
	</span>
	</td>
  </tr>
  <tr>
    <td class="content">

<!--
EOT;
if($mod_in=='left'){
foreach($listdb AS $rs){
print <<<EOT
-->
        ·[$rs[starttime]] <a href="$Mdomain/zhshow.php?zh_id=$rs[zh_id]" target="_blank" title="$rs[title_full]" class="bigfont">$rs[title]</a><br>
	
<!--
EOT;
}

}else{
print <<<EOT
-->		
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="hr_list">
          <tr>
            <td width="160"><strong>展会名称</strong></td>
            <td width="20%"><strong>展览馆</strong></td>
            <td width="15%"><strong>地区</strong></td>
            <td width="10%"><strong>开展时间</strong></td>
            <td width="10%"><strong>结束时间</strong></td>
          </tr>
          <!--
EOT;

$i=1;
foreach($listdb as $rs){
print <<<EOT
-->
          <tr>
            <td height="25"><a href="$Mdomain/zhshow.php?zh_id=$rs[zh_id]" target="_blank" title="$rs[title_full]" class="bigfont"><img src="images/$STYLE/title_icon3.gif" border="0" /> $rs[title]</a></td>
            <td><a href="$Mdomain/zh_showroom.php?sr_id=$rs[showroom]" target="_blank">$rs[showroom_name]</a></td>
            <td>$rs[area]</td>
            <td>$rs[starttime]</td>
            <td>$rs[endtime]</td>
          </tr>
          <!--
EOT;
$i++;
}
print <<<EOT
-->
        </table>
<!--
EOT;
if($m){
print <<<EOT
-->	
<div class='page'>$showpage</div>	
<!--
EOT;
}
}
print <<<EOT
-->	
	</td>
  </tr>
</table>

 
 
<!--
EOT;
?>