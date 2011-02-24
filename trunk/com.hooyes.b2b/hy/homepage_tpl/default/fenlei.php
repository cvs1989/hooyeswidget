<?php
unset($array);
$page>1 || $page=1;
$rows=20;
$min=($page-1)*$rows;
$query = $db->query("SELECT SQL_CALC_FOUND_ROWS * FROM {$pre}fenlei_content WHERE uid='$uid' ORDER BY id DESC LIMIT $min,$rows");
$RS=$db->get_one("SELECT FOUND_ROWS()");
while($rs = $db->fetch_array($query)){
	$rs[posttime]=date("Y-m-d H:i",$rs[posttime]);
	$array[]=$rs;
}

$showpage=getpage("","","?m=$m&uid=$uid",$rows,$RS['FOUND_ROWS()']);
?>

<!--
<?php
print <<<EOT
-->
<style>
.tblist td{
	line-height:25px;
}
.tblist .titletd{
	padding-left:10px;
}
</style>
<table width="100%" border="0" cellspacing="1" cellpadding="3" bgcolor="#CCCCCC" class="tblist">
  <tr align="center" bgcolor="#EBEBEB"> 
    <td width="59%">标题</td>
    <td width="16%">发布日期</td>
    <td width="17%">栏目</td>
    <td width="8%">浏览</td>
  </tr>
  <!--
EOT;
foreach($array AS $rs){
print <<<EOT
-->
  <tr bgcolor="#FFFFFF"> 
    <td width="59%" class="titletd"><a href="$webdb[www_url]/f/bencandy.php?fid=$rs[fid]&id=$rs[id]&city_id=$rs[city_id]" target="_blank">$rs[title]</a></td>
    <td width="16%" align="center">$rs[posttime]</td>
    <td width="17%" align="center"><a href="$webdb[www_url]/f/list.php?fid=$rs[fid]&city_id=$rs[city_id]" target="_blank">$rs[fname]</a></td>
    <td width="8%" align="center">$rs[hits]</td>
  </tr>
  <!--
EOT;
}
print <<<EOT
-->
  <tr bgcolor="#FFFFFF" align="center"> 
    <td colspan="4">$showpage</td>
  </tr>
</table>
<!--
EOT;
?>
-->