<?php
unset($listdb);
if($lfjuid!=$uid){
	$SQL="WHERE A.uid='$uid' AND A.yz='1' ";
}else{
	$SQL="WHERE A.uid='$uid' ";
}
if($fid){
	$SQL.=" and A.`fid`='$fid' ";
}
if($ms_id){$SQL.=" and ms_id='$ms_id'";}



if(!$m){
	$conf[listnum][selllist]=$conf[listnum][selllist]?$conf[listnum][selllist]:10;
	//��������
	if($conf[listnum][sell_orderby]) $orderby=$conf[listnum][sell_orderby];
	else $orderby=" A.id desc";
	//�����ҵ���Ŀ
	if($conf[listnum][sell_mysort]) $SQL.=" and A.ms_id='{$conf[listnum][sell_mysort]}'";
	//�ؼ���ɸѡ
	if($conf[listnum][sell_keyword]) $SQL.=" and A.title like('%{$conf[listnum][sell_keyword]}%')";
	
	$listdb=get_listsell($conf[listnum][selllist]);
	
}else{
	$conf[listnum][Mselllist]=$conf[listnum][Mselllist]?$conf[listnum][Mselllist]:20;
	//��������
	if($conf[listnum][Msell_orderby]) $orderby=$conf[listnum][Msell_orderby];
	else $orderby=" A.id desc";
	
	$listdb=get_listsell($conf[listnum][Mselllist]);
	$showpage=getpage("{$_pre}content_sell A"," $SQL","?m=$m&uid=$uid&ms_id=$ms_id",$conf[listnum][Mselllist]);
}

if($m && $page<2 && $uid!=$lfjuid && $lfjuid){
	@extract($db->get_one("select count(*) as vendor_num from `{$_pre}vendor` where uid='$uid' and yz=1"));
	$rsdb[owner_username]=urlencode($rsdb[owner_username]);

print <<<EOT
--> 
<table width="100%" border="0" cellspacing="0" cellpadding="0"  class="rightinfo">
  <tr>
    <td  class="head">
	<span class='L'></span>
	<span class='T'><a href="$Mdomain/homepage.php?uid=$uid&m=buylist">�ɹ���</a></span>
	<span class='R'></span>
	<span class='more'></span>

	</td>
  </tr>
  <tr>
    <td  class="content">
<table width="90%" border="0" cellspacing="5" cellpadding="5" align=center style="border:1px #f9f9f9 solid;">
  <tr>
    <td rowspan=2 align=center width='60'><img src="$Murl/images/homepage_style/notice.gif" border=0></td>
	 <td height=25 align=left><a href='?action=add_vendor2&gy_uid=$rsdb[uid]&gy_username=$rsdb[username]&gy_rid=$rsdb[rid]&myuid=$lfjuid' >
	 <img src="$Murl/images/homepage_style/2008010117333276699.jpg" border=0></a> </td>
  </tr>
  <tr>
   
    <td height=40>�������Գ�������̼Ҳɹ������������Ϊ���̼ҵĲɹ��̣���Ϊ�ɹ��̺󼴿ɿ��ٱ�ݵĽ��й�����Ϣ������(���̼��Ѿ���<strong><font color=red>{$vendor_num}</font></strong>�Ҳɹ��̡�)<br>
	<strong>����ɹ���֮��</strong>��<br>
	1.���ڹ�Ӧ�����ɹ����ɹ���Ϣ������ʾ��<br>
	2.���ڲɹ��ţ���Ӧ�̹�Ӧ��Ϣ������ʾ��<br>
	3.���ٱ�ݻ������ţ�<br>
	4.��ʱ��ع�ע�Է���̬�����Ź��κ��̻���
	</td>
  </tr>
</table>
	</td>
  </tr>
</table>
<!--
EOT;
}



print <<<EOT
-->   
<table width="100%" border="0" cellspacing="0" cellpadding="0"  class="rightinfo">
  <tr>
    <td  class="head">
	<span class=L></span>
	<span class=T><a href="$Mdomain/homepage.php?uid=$uid&m=selllist">��Ӧ��Ϣ</a></span>
	<span class=R></span>
	<span class='more'>
<!--
EOT;
if($lfjuid==$uid){
print <<<EOT
-->
	<a href='$Mdomain/member/?main=selllist.php'  target='_blank'>����</a> | <a href='$Mdomain/member/?main=post_sell.php' target='_blank'>����</a>
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
foreach( $listdb AS $key=>$rs){
$rs[picurl]=$webdb[www_url].'/'.$webdb[updir].'/'.$Imgdirname.'/'.$rs[picurl];
print <<<EOT
--> 


<div class='show_windows'>
	
	<p><a href="$rs[url]" target='_blank'><img src="$rs[picurl].gif" style="border:1px #CCCCCC solid" width="100"  onerror="this.src='$Murl/images/default/nopic.jpg'"></a></p>
	<p class='t'><a href="$rs[url]" target='_blank'>$rs[title]</a></p><p>$rs[my_price]</p>
	</a>
</div>
<!--
EOT;
}
if($m){
print <<<EOT
-->

<div class="page">$showpage</div>

<!--
EOT;
}
print <<<EOT
--> 
    &nbsp;</td>
  </tr>
</table>
<!--
EOT;

function get_listsell($rows){
	global $db,$uid,$lfjuid,$_pre,$Mrows,$albumid,$page,$orderby,$webdb,$SQL,$titlelength,$contentlength,$Murl,$Mdomain;
	

	if($page<1){
		$page=1;
	}
	$min=($page-1)*$rows;
	

	
	$query = $db->query("SELECT * FROM `{$_pre}content_sell` A INNER JOIN `{$_pre}content_1` B ON B.id=A.id  $SQL ORDER BY $orderby LIMIT $min,$rows");
	
	while($rs = $db->fetch_array($query)){
		$rs[posttime]=date("Y-m-d",$rs[posttime]);
		$titlelength=$titlelength?$titlelength:50;
		$rs[title]=get_word($rs[title],$titlelength);
		
		
		$rs[content]=@preg_replace('/<([^>]*)>/is',"",$rs[content]);	//��HTML������˵�
		$contentlength=$contentlength?$contentlength:200;
		$rs[content]=get_word($rs[content],$contentlength);
			
		if($rs[viewtype]){		
			$rs[content]='';
		}
		if($lfjuid==$rs[uid]){
			$rs[del]='ɾ��';
			$rs[edit]='�޸�';
		}
		$rs[my_price]=$rs[my_price]>0?"<img src='$Murl/images/default/icn_ps.gif' border=0><strong><font color=red>$rs[my_price]</font></strong>Ԫ/$rs[quantity_type]":"<font color='#898989'>�۸�����</font>";
		
		$rs[quantity_max]=$rs[quantity_max]?$rs[quantity_max]:"����";
		
		
		if($webdb[bencandyIsHtml] && $rs[htmlname] && file_exists(PHP168_PATH.$rs[htmlname]) ){
			$rs[url]=$webdb[www_url]."/".$rs[htmlname];
		}else{
			$rs[url]=$Mdomain."/sell_bencandy.php?fid=$rs[fid]&id=$rs[id]";
		}

		$listdb[]=$rs;
	}
		
	return $listdb;
}
?>
