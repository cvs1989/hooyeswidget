<?php
$vendor=$db->get_one("select * from {$_pre}vendor where (owner_uid='$uid' and uid='$lfjuid') or  (owner_uid='$lfjuid' and uid='$uid') limit 1");

print <<<EOT
-->   
<table width="100%" border="0" cellspacing="0" cellpadding="0"  class="rightinfo">
  <tr>
    <td class="head" ><span class='L'></span>
	<span class='T'>�̼ҵ��� &gt; ��֤����</span>
	<span class='R'></span>
	<span class='more'></span></td>
  </tr>
  <tr>
    <td class="content base">
	��ǰ�����֤��
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
	 <td height=25 align=left><strong>��Ǹ������Ϣֻ���乩Ӧ�̻��߲ɹ��̿��ţ�</strong></td>
  </tr>

</table>
<!--
EOT;
}else{

	if($vendor[yz] || $vendor[owner_uid]==$lfjuid || $uid==$lfjuid || $conf[renzheng_show]){
print <<<EOT
-->
<strong>�Ѿ�ͨ������֤��</strong><br>
<!--
EOT;

$query=$db->query("select * from {$_pre}renzheng where uid='$uid' and yz=1   order by level asc");
while($rs=$db->fetch_array($query)){
	$rs[post_time]=date("Y-m-d",$rs[post_time]);
	$rs[yztime]=date("Y-m-d",$rs[yz_time])."ͨ����֤";
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
    <td width="21%" height="120" bgcolor="#FFFFFF" align=center><p><img src='{$Murl}/images/{$STYLE}/jibenrenzheng.gif'  border='0'/></p> <p>������֤</p> {$renzhengdb[0][yztime]}</td>
    <td width="57%" bgcolor="#FFFFFF">
	��ҵ���ˣ�{$renzhengdb[0][content][faren]}<br>
	���֤�ţ�{$renzhengdb[0][content][sfz_num]} <br>
	��ϵ�绰��{$renzhengdb[0][content][telphone]}
	</td>
    <td width="22%" bgcolor="#FFFFFF">&nbsp;{$renzhengdb[0][status]}</td>
  </tr>

  <tr>
    <td height="120" bgcolor="#FFFFFF"  align=center><p><img src='{$Murl}/images/{$STYLE}/yinpairenzheng.gif'  border='0'/></p> <p>�߼���֤</p> {$renzhengdb[1][yztime]}</td>
    <td bgcolor="#FFFFFF">
<!--
EOT;
if($renzhengdb[1]){
print <<<EOT
-->
<table width="100%" cellspacing="0" cellpadding="0">
  <tr>
    <td width='33%' >Ӫҵִ��</td>
    <td  width='33%'>˰��Ǽ�֤</td>
    <td  width='33%'>��֯��������֤</td>
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
����֤����
<!--
EOT;
}
print <<<EOT
-->

	</td>
    <td bgcolor="#FFFFFF">&nbsp;{$renzhengdb[1][status]}</td>
  </tr>
 
  <tr>
    <td height="120" bgcolor="#FFFFFF"  align=center><p><img src='{$Murl}/images/{$STYLE}/jinpairenzheng.gif'  border='0'/></p> <p>ʵ����֤</p>{$renzhengdb[2][yztime]} </td>
    <td bgcolor="#FFFFFF">
<!--
EOT;
if($renzhengdb[2]){
print <<<EOT
-->
<table width="100%" cellspacing="0" cellpadding="0">
   <tr>
            <td width='100'>{$renzheng3docname[0]}:</td>
            <td><a href='$webdb[www_url]/{$renzhengdb[2][files][doc1]}' target='_blank'>�������</a>
           </td>
          </tr>
          <tr>
            <td>{$renzheng3docname[1]}:</td>
            <td><a href='$webdb[www_url]/{$renzhengdb[2][files][doc2]}' target='_blank'>�������</a>
            </td>
          </tr>
          <tr>
            <td>{$renzheng3docname[2]}:</td>
            <td><a href='$webdb[www_url]/{$renzhengdb[2][files][doc3]}' target='_blank'>�������</a>
            </td>
          </tr>
</table>
<!--
EOT;
}else{
print <<<EOT
-->
����֤����
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
	//�ȴ�		
print <<<EOT
-->
<table width="90%" border="0" cellspacing="5" cellpadding="5" align=center style="border:1px #f9f9f9 solid;">
  <tr>
    <td  align=center width='60'><img src="$Murl/images/homepage_style/notice.gif" border=0></td>
	 <td height=25 align=left><strong>��Ǹ��������̼ҵĹ����ϵ����ȷ���ڼ䣬��ʱ���ܲ鿴���̼ҵ���֤��Ϣ��</strong><br>
	 ��ܰ��ʾ��<br>
	 1.���·���Ĺ�Ӧ��������δȷ���ڼ䣬�Է����Կ������Լ�����֤��Ϣ��<br>
	 2.�ȹ�Ӧ��ϵȷ��֮����֤��Ϣ���࿪�š�
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
	 <td height=25 align=left><strong>��Ǹ������Ϣֻ�Ե�¼�û����ţ�</strong></td>
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
//���бȴ�
?>