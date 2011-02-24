<?php
require("global.php");
/*******************************************
来自页面的请求
*******************************************/
	$rows=intval($rows);
	$rows=$rows?$rows:10;
	$strlen=$strlen?$strlen:38;
	header('Content-type: text/javascript; charset=gbk');
	header('Cache-Control: no-cache, must-revalidate');
	header('Content-Encoding: none');
if($from=='index'){
	
	$params = array(
		'from' => 'index'
	);
	
	cache_page(PHP_SELF.combine_params($params));
	
	$query=$db->query("SELECT A.*,B.picurl,B.htmlname,B.fid,B.ctype,B.my_price,C.quantity_type FROM {$_pre}tg_buy A 
	INNER JOIN {$_pre}content_buy B ON B.id=A.tg_id
	INNER JOIN {$_pre}content_2 C ON C.id=B.id
	WHERE A.yz=1 AND (A.`tg_posttime`+(A.`tg_howlong`*60*60)) > $timestamp ORDER BY A.tg_howmuch DESC LIMIT 0,$rows");
	$i=1;
	while($rs=$db->fetch_array($query)){
		if($rs[my_price]){
				$rs[my_price]=formartprice($rs[my_price]);
				$rs[my_price]="<strong><font color=#FF3300>$rs[my_price]</font></strong>元/$rs[quantity_type]";
			}else{
				$rs[my_price]='价格面议';
			}

		if($webdb[bencandyIsHtml] && $rs[htmlname]){
			$rs[url]=$webdb[www_url]."/".$rs[htmlname];
		}else{
			$rs[url]=$Mdomain."/buy_bencandy.php?fid=$rs[fid]&id=$rs[tg_id]";
		}
		if($i<4){
			if($rs[picurl]) {
				$rs[picurl_min]=getimgdir($rs[picurl],$rs[ctype]).".gif";
			}else{
				$rs[picurl_min]=$Murl."/images/default/nopic.jpg";
			}
			$show.="<div title='".$rs[tg_title]."' style=' float:left; width:92px; height:125px; padding:5px; margin:5px;text-align:center'><a href='{$rs[url]}' target='_blank'><img src='$rs[picurl_min]' border=0  style='width:60px; height:60px; border:1px #CCCCCC solid;' /><br>".get_word($rs[tg_title],$strlen-10)."</a><br>($rs[my_price])</div>";
		}else{
		
			$show.="<div title='".$rs[tg_title]."' style='clear:both;line-height:25px;border-bottom:1px #cccccc dotted'>·<a href='{$rs[url]}' target='_blank'>".get_word($rs[tg_title],$strlen)."</a>($rs[my_price])</div>";
		}
		
		$i++;
	}

	echo "document.getElementById('showtg_2').innerHTML=\"$show\";";

	cache_page_save();

}elseif($from=='list'){
	if(!$showdiv){exit;}
	$rows=$rows?$rows:8;
	$page=$page<1?1:$page;
	$min=($page-1)*$rows;
	$fid=intval($fid);
	
	$params = array(
		'from' => 'list',
		'rows' => $rows,
		'page' => $page,
		'fid' => $fid
	);
	
	cache_page(PHP_SELF.combine_params($params));
	
	if($fid)
	{
		require(Mpath.'inc/categories.php');
		$bcategory->cache_read();
		if($bcategory->categories[$fid]['categories']){
			$fid_path = $fid .','. implode(',', $bcategory->get_children_ids($fid));
			$has_sub = true;
		}else{
			$fid_path = $fid;
		}
		
		$fidwhere=" AND A.tg_fid IN ($fid_path) ";
		//$fidwhere="and concat(',',A.tg_fid_all,',') like('%,$fid,%') ";
	}
	$query=$db->query("SELECT A.*,B.picurl,B.htmlname,B.fid,B.ctype,B.my_price,C.quantity_type FROM {$_pre}tg_buy A 
	INNER JOIN {$_pre}content_buy B ON B.id=A.tg_id
	INNER JOIN {$_pre}content_2 C ON C.id=B.id
	WHERE A.yz=1 AND (A.`tg_posttime`+(A.`tg_howlong`*60*60)) > $timestamp  $fidwhere ORDER BY A.tg_howmuch DESC LIMIT $min,$rows");
	//

	while($rs=$db->fetch_array($query)){
		if($rs[my_price]){
				$rs[my_price]=formartprice($rs[my_price]);
				$rs[my_price]="<strong><font color=#FF3300>$rs[my_price]</font></strong>元/$rs[quantity_type]";
			}else{
				$rs[my_price]='价格面议';
			}

		
			if($rs[picurl]) {
				$rs[picurl_min]=getimgdir($rs[picurl],$rs[ctype]).".gif";
			}else{
				$rs[picurl_min]=$Murl."/images/default/nopic.jpg";
			}

			if($webdb[bencandyIsHtml] && $rs[htmlname]){
				$rs[url]=$webdb[www_url]."/".$rs[htmlname];
			}else{
				$rs[url]=$Mdomain."/buy_bencandy.php?fid=$rs[fid]&id=$rs[tg_id]";
			}

			$show.="<table title='".$rs[tg_title]."' style=' float:left; width:190px; height:90px; padding:5px; margin-right:5px;text-align:left'>			<tr><td><a href='{$rs[url]}' target='_blank'><img src='$rs[picurl_min]' border=0  style='width:80px; height:80px; border:1px #CCCCCC solid;' /></a></td><td><a href='{$rs[url]}' target='_blank'>".get_word($rs[tg_title],$strlen-10)."</a><br>($rs[my_price])</td></tr></table>";
		
		
	}
	if(!$show){
		echo "document.getElementById('{$showdiv}').innerHTML='等待您的加入';";
	}else{
		echo "document.getElementById('{$showdiv}').innerHTML=\"$show\";";
	}
	
	cache_page_save();

}elseif($from=='sortindex'){
	
	if(!$showdiv){exit;}
	$rows=$rows?$rows:8;
	$page=$page<1?1:$page;
	$min=($page-1)*$rows;
	
	$params = array(
		'from' => 'sortindex',
		'rows' => $rows,
		'page' => $page
	);
	
	cache_page(PHP_SELF.combine_params($params));
	
	$query=$db->query("SELECT A.*,B.picurl,B.htmlname,B.fid,B.ctype,B.my_price,C.quantity_type FROM {$_pre}tg_buy A 
	INNER JOIN {$_pre}content_buy B ON B.id=A.tg_id
	INNER JOIN {$_pre}content_2 C ON C.id=B.id
	WHERE A.yz=1 AND (A.`tg_posttime`+(A.`tg_howlong`*60*60)) > $timestamp  ORDER BY A.tg_howmuch DESC LIMIT $min,$rows");
	//

	while($rs=$db->fetch_array($query)){
		if($rs[my_price]){
				$rs[my_price]=formartprice($rs[my_price]);
				$rs[my_price]="<strong><font color=#FF3300>$rs[my_price]</font></strong>元/$rs[quantity_type]";
			}else{
				$rs[my_price]='价格面议';
			}

			
			if($webdb[bencandyIsHtml] && $rs[htmlname]){
				$rs[url]=$webdb[www_url]."/".$rs[htmlname];
			}else{
				$rs[url]=$Mdomain."/buy_bencandy.php?fid=$rs[fid]&id=$rs[tg_id]";
			}


			if($rs[picurl]) {
				$rs[picurl_min]=getimgdir($rs[picurl],$rs[ctype]).".gif";
			}else{
				$rs[picurl_min]=$Murl."/images/default/nopic.jpg";
			}
			$show.="<div title='".$rs[tg_title]."' style=' float:left; width:88px; height:125px; padding:5px; margin-right:5px;text-align:center'><a href='{$rs[url]}' target='_blank'><img src='$rs[picurl_min]' border=0  style='width:60px; height:60px; border:1px #CCCCCC solid;' /><br>".get_word($rs[tg_title],$strlen-10)."</a><br>($rs[my_price])</div>";
		
		
	}
	
	if(!$show){
		echo "document.getElementById('{$showdiv}').style.display='none';";
	}else{
		echo "document.getElementById('{$showdiv}').innerHTML=\"$show\";";
	}
	
	cache_page_save();
}	
?>