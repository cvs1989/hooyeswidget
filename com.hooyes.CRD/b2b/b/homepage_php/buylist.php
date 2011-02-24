<?php
unset($listdb);
if($lfjuid!=$uid){
	$SQL="WHERE A.uid='$uid' AND A.yz='1'";
}else{
	$SQL="WHERE A.uid='$uid'";
}
if($fid){
	$SQL.=" and A.`fid`='$fid' ";
}
if($ms_id){$SQL.=" and ms_id='$ms_id'";}

if(!$m){
	$conf[listnum][buylist]=$conf[listnum][buylist]?$conf[listnum][buylist]:10;
	//定义排序
	if($conf[listnum][buy_orderby]) $orderby=$conf[listnum][buy_orderby];
	else $orderby=" A.id desc";
	//定义我的类目
	if($conf[listnum][buy_mysort]) $SQL.=" and A.ms_id='{$conf[listnum][buy_mysort]}'";
	//关键字筛选
	if($conf[listnum][buy_keyword]) $SQL.=" and A.title like('%{$conf[listnum][buy_keyword]}%')";
	
	$listdb=get_listbuy($conf[listnum][buylist]);
}else{
	$conf[listnum][Mbuylist]=$conf[listnum][Mbuylist]?$conf[listnum][Mbuylist]:20;
	
	//定义排序
	if($conf[listnum][Mbuy_orderby]) $orderby=$conf[listnum][Mbuy_orderby];
	else $orderby=" A.id desc";
	
	$listdb=get_listbuy($conf[listnum][Mbuylist]);
	$showpage=getpage("{$_pre}content_buy A"," $SQL","?m=$m&uid=$uid",$conf[listnum][Mbuylist]);
}

if($m && $page<2 && $uid!=$lfjuid && $lfjuid){
	@extract($db->get_one("select count(*) as vendor_num from `{$_pre}vendor` where owner_uid='$uid' and yz=1"));
	$rsdb[username]=urlencode($rsdb[username]);

print <<<EOT
--> 
<table width="100%" border="0" cellspacing="0" cellpadding="0"  class="rightinfo">
  <tr>
    <td  class="head">
	<span class='L'></span>
	<span class='T'><a href="$Mdomain/homepage.php?uid=$uid&m=buylist">供应商</a></span>
	<span class='R'></span>
	<span class='more'></span>

	</td>
  </tr>
  <tr>
    <td  class="content">
<table width="90%" border="0" cellspacing="5" cellpadding="5" align=center style="border:1px #f9f9f9 solid;">
  <tr>
    <td rowspan=2 align=center width='60'><img src="$Murl/images/homepage_style/notice.gif" border=0></td>
	 <td height=25 align=left><a href='?action=add_vendor&owner_uid=$rsdb[uid]&owner_username=$rsdb[username]&owner_rid=$rsdb[rid]&uid=$lfjuid' >
	 <img src="$Murl/images/homepage_style/20080101173332766.jpg" border=0></a> </td>
  </tr>
  <tr>
   
    <td height=40>如果你可以长期向此商家供货，可以申请成为此商家的供应商，成为供应商后即可快速便捷的进行供求信息交换；(该商家已经有<strong><font color=red>{$vendor_num}</font></strong>家供应商。)<br>
	<strong>加入供应商之后</strong>：<br>
	1.对于供应方：采购方采购信息发布提示；<br>
	2.对于采购放：供应商供应信息发布提示；<br>
	3.快速便捷互发短信；<br>
	4.随时随地关注对方动态；不放过任何商机；
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
	<span class='L'></span>
	<span class='T'><a href="$Mdomain/homepage.php?uid=$uid&m=buylist">求购信息</a></span>
	<span class='R'></span>
	<span class='more'>
<!--
EOT;
if($lfjuid==$uid){
print <<<EOT
-->
	<a href='$Mdomain/member/?main=buylist.php' target='_blank'>管理</a> |  <a href='$Mdomain/member/?main=post_buy.php' target='_blank'>发布</a>
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
	<a href="$rs[url]" target='_blank'><img src="$rs[picurl].gif" style="border:1px #CCCCCC solid" width="100"  onerror="this.src='$Murl/images/default/nopic.jpg'"><br>
	<span class='t'>$rs[title]</span><br>
	$rs[my_price]</a>
</div>
<!--
EOT;
}if($m){
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


function get_listbuy($rows){
	global $db,$uid,$lfjuid,$_pre,$Mrows,$albumid,$page,$orderby,$webdb,$VlogCfg,$SQL,$titlelength,$contentlength,$Mdomain;
	$VlogCfg[content_leng]==0 && $VlogCfg[content_leng]=120;

	if($page<1){
		$page=1;
	}
	$min=($page-1)*$rows;
	

	
	$query = $db->query("SELECT B.*,A.* FROM `{$_pre}content_buy` A INNER JOIN `{$_pre}content_2` B ON B.id=A.id  $SQL ORDER BY $orderby LIMIT $min,$rows");
	while($rs = $db->fetch_array($query)){
		$rs[posttime]=date("Y-m-d",$rs[posttime]);
		$titlelength=$titlelength?$titlelength:50;
		$rs[title]=get_word($rs[title],$titlelength);
		if($VlogCfg[content_leng]>0){
			$rs[content]=@preg_replace('/<([^>]*)>/is',"",$rs[content]);	//把HTML代码过滤掉
			$contentlength=$contentlength?$contentlength:200;
			$rs[content]=get_word($rs[content],$contentlength);
		}else{
			$rs[content]='';
		}
		
		if($rs[viewtype]){
			$rs[content]='';
		}
		if($lfjuid==$rs[uid]){
			$rs[del]='删除';
			$rs[edit]='修改';
		}
		$rs[my_price]=$rs[my_price]>0?"<strong><font color=red>$rs[my_price]</font></strong>元/$rs[quantity_type]":"<font color='#898989'>价格面议</font>";

			
		
		if($webdb[bencandyIsHtml] && $rs[htmlname] && file_exists(PHP168_PATH.$rs[htmlname]) ){
			$rs[url]=$webdb[www_url]."/".$rs[htmlname];
		}else{
			$rs[url]=$Mdomain."/buy_bencandy.php?fid=$rs[fid]&id=$rs[id]";
		}

		$listdb[]=$rs;
	}

		
	return $listdb;
}
?>
