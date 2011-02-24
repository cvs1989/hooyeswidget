<?php
$vendor=$db->get_one("select * from {$_pre}vendor where (owner_uid='$uid' and uid='$lfjuid') or  (owner_uid='$lfjuid' and uid='$uid') limit 1");

print <<<EOT
-->   
<table width="100%" border="0" cellspacing="0" cellpadding="0"  class="rightinfo">
  <tr>
    <td class="head" ><span class='L'></span>
	<span class='T'>商家档案 &gt; 认证资料</span>
	<span class='R'></span>
	<span class='more'></span></td>
  </tr>
  <tr>
    <td class="content base">
	当前最高认证：
	$rsdb[renzheng]
	<hr style='height:1px;'>
<!--
EOT;
	
	if($lfjuid){
if(!$vendor[vid] && $uid!=$lfjuid && !$conf[renzheng_show]){
print <<<EOT
-->
	 
<table width="90%" border="0" cellspacing="5" cellpadding="5" align=center style="border:1px #f9f9f9 solid;">
  <tr>
    <td  align=center width='60'><img src="$Murl/images/homepage_style/notice.gif" border=0></td>
	 <td height=25 align=left><strong>抱歉，此信息只对其供应商或者采购商开放！</strong></td>
  </tr>

</table>
<!--
EOT;
}else{

	if($vendor[yz] || $vendor[owner_uid]==$lfjuid || $uid==$lfjuid || $conf[renzheng_show]){
print <<<EOT
-->
<strong>已经通过的认证：</strong><br>
<!--
EOT;

$query=$db->query("select * from {$_pre}renzheng where uid='$uid' and yz=1   order by level asc");
while($rs=$db->fetch_array($query)){
	$rs[post_time]=date("Y-m-d",$rs[post_time]);
	$rs[yztime]=date("Y-m-d",$rs[yz_time])."通过认证";
	$rs[content]=unserialize($rs[content]);
	$rs[files]=unserialize($rs[files]);
	$rs[status]=$rs[yz]?"<img src='$Murl/images/homepage_style/gougou.gif' border=0>":"&nbsp;";
	$renzhengdb[]=$rs;
}
$renzheng3docname=explode(" ",$webdb[renzheng3doc]);
print <<<EOT
-->
<style>.rzlist td{ padding:5px;}</style>
<table width="100%"  cellspacing="1" cellpadding="5"  bgcolor="#cccccc"  class='rzlist'>
  <tr>
    <td width="21%" height="120" bgcolor="#FFFFFF" align=center><p><img src='{$Murl}/images/{$STYLE}/jibenrenzheng.gif'  border='0'/></p> <p>初级认证</p> {$renzhengdb[0][yztime]}</td>
    <td width="57%" bgcolor="#FFFFFF">
	企业法人：{$renzhengdb[0][content][faren]}<br>
	身份证号：{$renzhengdb[0][content][sfz_num]} <br>
	联系电话：{$renzhengdb[0][content][telphone]}
	</td>
    <td width="22%" bgcolor="#FFFFFF">&nbsp;{$renzhengdb[0][status]}</td>
  </tr>

  <tr>
    <td height="120" bgcolor="#FFFFFF"  align=center><p><img src='{$Murl}/images/{$STYLE}/yinpairenzheng.gif'  border='0'/></p> <p>高级认证</p> {$renzhengdb[1][yztime]}</td>
    <td bgcolor="#FFFFFF">
<!--
EOT;
if($renzhengdb[1]){
print <<<EOT
-->
<table width="100%" cellspacing="0" cellpadding="0">
  <tr>
    <td width='33%' >营业执照</td>
    <td  width='33%'>税务登记证</td>
    <td  width='33%'>组织机构代码证</td>
  </tr>
  <tr>
    <td><a href='$webdb[www_url]/{$renzhengdb[1][files][yyzz]}' target='_blank'><img src="$webdb[www_url]/{$renzhengdb[1][files][yyzz]}"  width="60"  height=60 border=0></a></td>
    <td><a href='$webdb[www_url]/{$renzhengdb[1][files][swdj]}' target='_blank'><img src="$webdb[www_url]/{$renzhengdb[1][files][swdj]}"  width="60"   height=60 border=0></a></td>
    <td><a href='$webdb[www_url]/{$renzhengdb[1][files][jgdm]}' target='_blank'><img src="$webdb[www_url]/{$renzhengdb[1][files][jgdm]}"  width="60"  height=60  border=0></a></td>
  </tr>
</table>
<!--
EOT;
}else{
print <<<EOT
-->
无认证资料
<!--
EOT;
}
print <<<EOT
-->

	</td>
    <td bgcolor="#FFFFFF">&nbsp;{$renzhengdb[1][status]}</td>
  </tr>
 
  <tr>
    <td height="120" bgcolor="#FFFFFF"  align=center><p><img src='{$Murl}/images/{$STYLE}/jinpairenzheng.gif'  border='0'/></p> <p>实力认证</p>{$renzhengdb[2][yztime]} </td>
    <td bgcolor="#FFFFFF">
<!--
EOT;
if($renzhengdb[2]){
print <<<EOT
-->
<table width="100%" cellspacing="0" cellpadding="0">
   <tr>
            <td width='100'>{$renzheng3docname[0]}:</td>
            <td><a href='$webdb[www_url]/{$renzhengdb[2][files][doc1]}' target='_blank'>点击下载</a>
           </td>
          </tr>
          <tr>
            <td>{$renzheng3docname[1]}:</td>
            <td><a href='$webdb[www_url]/{$renzhengdb[2][files][doc2]}' target='_blank'>点击下载</a>
            </td>
          </tr>
          <tr>
            <td>{$renzheng3docname[2]}:</td>
            <td><a href='$webdb[www_url]/{$renzhengdb[2][files][doc3]}' target='_blank'>点击下载</a>
            </td>
          </tr>
</table>
<!--
EOT;
}else{
print <<<EOT
-->
无认证资料
<!--
EOT;
}
print <<<EOT
-->
	
	</td>
    <td bgcolor="#FFFFFF">&nbsp;{$renzhengdb[2][status]}</td>
  </tr>
</table>

<!--
EOT;
	
	}else{
	//等待		
print <<<EOT
-->
<table width="90%" border="0" cellspacing="5" cellpadding="5" align=center style="border:1px #f9f9f9 solid;">
  <tr>
    <td  align=center width='60'><img src="$Murl/images/homepage_style/notice.gif" border=0></td>
	 <td height=25 align=left><strong>抱歉，您与此商家的供求关系还在确认期间，暂时不能查看此商家的认证信息！</strong><br>
	 温馨提示：<br>
	 1.您新发起的供应商申请且未确认期间，对方可以看到您自己的认证信息。<br>
	 2.等供应关系确认之后认证信息互相开放。
	 </td>
  </tr>
</table>
<!--
EOT;
	}
	
	
}
	
	}else{

print <<<EOT
-->
	
	<table width="90%" border="0" cellspacing="5" cellpadding="5" align=center style="border:1px #f9f9f9 solid;">
  <tr>
    <td  align=center width='60'><img src="$Murl/images/homepage_style/notice.gif" border=0></td>
	 <td height=25 align=left><strong>抱歉，此信息只对登录用户开放！</strong></td>
  </tr>

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
//此行比存
?>