<?php
unset($getzhiweilist);
	if($mod_in=='left'){
		$rows=$conf[listnum][hr]?$conf[listnum][hr]:10;
	}else{
		if($m)	$rows=$conf[listnum][Mhr]?$conf[listnum][Mhr]:20;
		else $rows=$conf[listnum][hr]?$conf[listnum][hr]:10;
	}
	$page=intval($page);
	if($page<1) $page=1;
	$min=($page-1)*$rows;
	$where=" where is_check=1 and uid='$uid' and rid='$rsdb[rid]' ";

		$query=$db->query("select * from {$_pre}hr_jobs $where  order by  best desc,posttime desc limit $min,$rows");
		
		while($rs=$db->fetch_array($query)){
			$rs[posttime]=date('Y-m-d',$rs[posttime]);
			$rs[posttime_full]=date('Y-m-d H:i:s',$rs[posttime]);
			$city=explode(",",$rs[city]);
			$rs[cityname]=$area_DB[name][$city[0]]." ".$city_DB[name][$city[1]];
			$rs[companyname]=$rs[companyname]?get_word($rs[companyname],$leng):"&nbsp;";
			$getzhiweilist[]=$rs;
	
		}
		
		
		$showpage=getpage("{$_pre}hr_jobs",$where,"?uid=$uid&m=$m",$rows);
	

print <<<EOT
-->   

 
 <table width="100%" border="0" cellspacing="0" cellpadding="0" class="rightinfo">
  <tr>
    <td  class="head">	<span class='L'></span>
	<span class='T'><a href='?uid=$uid&m=hr'>人才招聘</a></span>
	<span class='R'></span>
	<span class='more'>
<!--
EOT;
if($lfjuid==$uid){
print <<<EOT
-->
	<a href='$Mdomain/member/?main=hr.php?job=mytdlist' target='_blank'>我的人才库</a>  | <a href='$Mdomain/member/?main=hr.php?job=mylist' target='_blank'>管理招聘信息</a> |  <a href='$Mdomain/jobs_post.php?job=jobs' target='_blank'>发布招聘</a> 
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
foreach($getzhiweilist AS $rs){
print <<<EOT
-->
        ·<a href="$Mdomain/jobsshow.php?id=$rs[jobs_id]" target="_blank">$rs[title]</a>($rs[posttime])<br>
<!--
EOT;
}

}else{
print <<<EOT
-->	
<table width="100%" border="0" cellspacing="0" cellpadding="0"  class='hr_list'>
             <tr  class="tr_title">
                <td width="24%" align="left"><strong>职位名称</strong></td>
                <td width="32%" align="left"><strong>职位分类</strong></td>
                <td width="17%" align="left"><strong>地区</strong></td>
                <td width="13%" align="left"><strong>发布时间</strong></td>
				<td width="13%" align="center"><strong>申请该职位</strong></td>
              </tr>
<!--
EOT;
foreach($getzhiweilist AS $rs){
print <<<EOT
-->
              <tr>
                <td align="left"><a href="$Mdomain/jobsshow.php?id=$rs[jobs_id]" target="_blank">$rs[title]</a></td>
                <td align="left"><a href="$Mdomain/jobsshow.php?id=$rs[jobs_id]" target="_blank">$rs[sname]</a></td>
                <td align="left"><a href="$Mdomain/jobsshow.php?id=$rs[jobs_id]" target="_blank">$rs[cityname]</a></td>
                <td align="left"><a href="$Mdomain/jobsshow.php?id=$rs[jobs_id]" target="_blank">$rs[posttime]</a></td>
				<td align="center"><a href="$Mdomain/jobs_post.php?job=postresume&jobs_id=$rs[jobs_id]&sid=$rs[sid]" target="_blank">申请该职位</a></td>
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