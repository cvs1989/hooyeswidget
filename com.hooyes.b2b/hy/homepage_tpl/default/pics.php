<?php
$webdb[company_picsort_Max]=$webdb[company_picsort_Max]?$webdb[company_picsort_Max]:10;
$query=$db->query("SELECT * FROM {$_pre}picsort WHERE uid='$uid' ORDER BY orderlist DESC LIMIT 0,$webdb[company_picsort_Max];");
while($rs=$db->fetch_array($query)){
	$picsortlistdb[]=$rs;
}

if($psid){
	$rows=36;
	if($page<1){
		$page=1;
	}
	$min=($page-1)*$rows;
	$query=$db->query("SELECT * FROM {$_pre}pic WHERE uid='$uid' AND psid='$psid' ORDER BY orderlist DESC LIMIT $min,$rows;");
	$i=0;
	while($rs=$db->fetch_array($query)){
	$rs[posttime]=date("Y-m-d H:i:s",$rs[posttime]);
	$rs[url]=tempdir($rs[url]);
	if($pid==$rs[pid]){
		$thispic=$rs;
	}
	$picslistdb[$i]=$rs;
	$i++;
	}
	$showpage=getpage("{$_pre}pic"," where uid='$uid' and psid='$psid'","?uid=$uid&m=pics&psid=$psid",$rows);
	
	//得到上下图片
	foreach($picslistdb as $i=>$rs){
		if($rs[pid]==$pid){
			$thispic[uppid]  =$picslistdb[($i-1)][pid];
			$thispic[nextpid]=$picslistdb[($i+1)][pid];
		}
	}
}
if($pid){
	$thispicurl=str_replace($webdb[www_url],ROOT_PATH,$thispic[url]);
	if(file_exists($thispicurl)){
		$mysize=getimagesize($thispicurl);
		$w=$mysize[0];
		$w_2=intval($w/2);
		$h=$mysize[1];
		$upurl  =$thispic[uppid]?"?uid=$uid&m=pics&psid=$thispic[psid]&pid=$thispic[uppid]":"javascript:alert('已经是本页第一张了');";
		$nexturl=$thispic[nextpid]?"?uid=$uid&m=pics&psid=$thispic[psid]&pid=$thispic[nextpid]":"javascript:alert('已经是本页最后张了');";
	}
}
?>

<!--
<?php
print <<<EOT
--> 

<table width="100%" border="0" cellspacing="0" cellpadding="0"  class="rightinfo">
  <tr>
    <td  class="head">
<span class='L'></span>
	<span class='T'>公司图库</span>
	<span class='R'></span>
	<span class='more'>
<!--
EOT;
if($lfjuid==$uid){
print <<<EOT
-->
	<a href='$webdb[www_url]/member/?main=$Murl/member/homepage_ctrl.php?atn=pic_upload'  target='_blank'>上传图片</a> | <a href='$webdb[www_url]/member/?main=$Murl/member/homepage_ctrl.php?atn=pic'  target='_blank'>管理</a> 
<!--
EOT;
}
print <<<EOT
-->
	</span>
	</td>
  </tr>
  <tr>
    <td  class="content">


<!--
EOT;
if(!$psid){ 
print <<<EOT
-->	 

<!--
EOT;
foreach($picsortlistdb as $rs){
$rs[faceurl]=tempdir($rs[faceurl]);
print <<<EOT
-->
              <div style="float:left; width:130px; height:130px; text-align:center; padding:5px; margin:5px;"><a href="?uid=$uid&psid=$rs[psid]&m=pics&psid=$rs[psid]"><img src="$rs[faceurl].gif"  style="border:2px #CCCCCC solid;" width="90" height="90" onerror="this.src='$Murl/images/default/userpicsortdefault.gif';"/><br />             <strong>$rs[name]</strong></a><strong></strong> 
              </div>
<!--
EOT;
}
print <<<EOT
-->	
	
<!--
EOT;
}else{
print <<<EOT
-->	 
&nbsp;&nbsp;选择其他图集：<select name="psid" size="1" id="psid" onchange="window.location='?uid=$uid&m=pics&psid='+this.options[this.selectedIndex].value;">
<!--
EOT;
foreach($picsortlistdb as $rs){
$ck=$rs[psid]==$psid?" selected":"";
print <<<EOT
-->
                <option value="$rs[psid]" $ck>$rs[name]</option>
<!--
EOT;
}
print <<<EOT
-->
              </select>
<!--
EOT;
if($pid&&$mysize){
print <<<EOT
-->

<div style="margin:10px;text-align:center">
<style>
.showpic_cur_left{cursor:url($Murl/images/default/3.cur);}
.showpic_cur_right{cursor:url($Murl/images/default/2.cur);}
</style>
<img src='$thispic[url]' border=0  usemap="#Map"  id='showpic_cur' onload='if(this.width>700){this.width=700;}'  /><br>
$thispic[title]
<map name="Map" id="Map">
<area shape="rect" coords="0,0,$w_2,$h" href="$upurl"  onmousemove="document.getElementById('showpic_cur').className='showpic_cur_left';" title='上一张' />
<area shape="rect" coords="$w_2,0,$w,$h" href="$nexturl" onmousemove="document.getElementById('showpic_cur').className='showpic_cur_right';" title='下一张'/>
</map>
</div>
<!--
EOT;
}
print <<<EOT
-->

<div style="margin-top:10px;">

<!--
EOT;
foreach($picslistdb as $rs){
$color=$rs[pid]==$pid?"red":"#cccccc";
print <<<EOT
-->
                  <span style="float:left; wdith:90px; height:90px; word-wrap: break-word; word-break: normal; text-align:center; margin:10px 10px 0px 10px; border:1px $color solid; padding:5px;"><a href="?uid=$rs[uid]&m=pics&psid=$rs[psid]&pid=$rs[pid]"><img src="$rs[url].gif"  width="90" height="90"  border=0 onerror="this.src='$Murl/images/default/userpicdefault.gif';" alt='$rs[title]'/></a>
                  
                  </span>
<!--
EOT;
}
print <<<EOT
-->

</div><div style="text-align:center;clear:both;"><b>$showpage</b></div>
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
-->