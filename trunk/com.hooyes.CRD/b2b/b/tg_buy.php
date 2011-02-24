<?php
require("global.php");
require(Mpath.'inc/categories.php');

	if(!$lfjuid) showerr("你还没有登陆，请登陆");
	$ctype=intval($ctype);
	$ctype=$ctype?$ctype:1;
		//得到我所有信息
	$rows=10;
	$page=intval($page);
	if($page<1) $page=1;
	$min=($page-1)*$rows;

	$where=" WHERE tg_uid='$lfjuid' ";
	$query=$db->query("SELECT * FROM {$_pre}tg_buy  $where ORDER BY tg_myid DESC LIMIT $min,$rows");
	while($rs=$db->fetch_array($query)){
		$overtime=intval( $rs[tg_posttime] + ($rs[tg_howlong]*60*60)    );
		if($overtime > $timestamp) $mytg_ids[]=$rs[tg_id]; 
		
		if($rs[tg_id] && $rs[tg_ctype]){
			$ctype_db=$db->get_one("SELECT A.picurl,A.my_price,B.quantity_type FROM {$_pre}content_buy A INNER JOIN {$_pre}content_2 B on B.id=A.id  WHERE A.id='$rs[tg_id]'; ");
			if($ctype_db) $rs=$rs+$ctype_db;
		}
		

		if($rs[my_price]){
				$rs[my_price]=formartprice($rs[my_price]);
				$rs[my_price]="<strong><font color=#FF3300>$rs[my_price]</font></strong>元/$rs[quantity_type]";
		}else{
				$rs[my_price]='价格面议';
		}
		if(!$ctype_db) $rs[my_price]="未知";
		
		if($rs[picurl])	$rs[picurl_min]=getimgdir($rs[picurl],$rs[tg_ctype]).".gif";
		$rs[total]=round($rs[tg_howmuch]*$rs[tg_howlong],1);
		
		if($rs[yz]){
			if(   ( $rs[tg_posttime]+$rs[tg_howlong]*60*60 ) > $timestamp){
				$rs[status]="已提交";
				$rs[yz]    =$rs[yz]?"推广中":"<font color=red>等待审核</font>";
			}else{
				$rs[status]="已结束";
				$rs[yz]    ="-";
			}
		}else{
			$rs[status]="已提交";
			$rs[yz]    =$rs[yz]?"&nbsp;":"<font color=red>等待审核</font>";
		}
		
		$rs[posttime]=$rs[posttime]?date("Y-m-d H:i:s",$rs[posttime]):"";

		$rs[tg_posttime]=$rs[tg_posttime]?date("Y-m-d H:i:s",$rs[tg_posttime]):"未开始";
		
		
		$mytglist[]=$rs;
		
	}
	
	$showpage=getpage("{$_pre}tg_buy",$where,"?action=tg",$rows);

	$webdb[tg_permoney]=$webdb[tg_permoney]?$webdb[tg_permoney]:3;
	
	$bcategory->cache_read();
	
	if($action=='tg'){ //状态显示
	
	}elseif($action=='tgnew'){ //推广新的。选择
			$where=" WHERE tg_uid='$lfjuid' AND (`tg_posttime`+(`tg_howlong`*60*60)) > $timestamp";
			$query=$db->query("SELECT * FROM {$_pre}tg_buy  $where ORDER BY tg_myid DESC ");
			while($rs=$db->fetch_array($query)){
				$overtime=intval( $rs[tg_posttime] + ($rs[tg_howlong]*60*60)    );
				if($overtime > $timestamp) $mytg_ids[]=$rs[tg_id]; 

			}
		//得到供应列表
		$rows=20;
		if($page<1){
			$page=1;
		}
		$min=($page-1)*$rows;
		$where=" WHERE A.uid='$lfjuid'  and A.yz=1   ";
		if($keyword) $where.=" AND A.title LIKE('%$keyword%')";
		$query=$db->query("SELECT A.*  FROM {$_pre}content_buy A  $where  LIMIT $min,$rows");
		
		while($rs=$db->fetch_array($query)){
			$rs[posttime]=date("Y-m-d",$rs[posttime]);
			$rs[picurl_min]=getimgdir($rs[picurl],$rs[ctype]).".gif";
			if(in_array($rs[id],$mytg_ids)) $rs[status]="<font color=red>推广中</font>";
			else $rs[status]="<input name='tg_$rs[id]' onclick=\"window.location='?action=posttgnew&fid=$rs[fid]&id=$rs[id]&ctype=$rs[ctype]';\" value='推广' type='button'>";
			$listdb[]=$rs;
		}	
		
		$showpage=getpage("{$_pre}content_buy A",$where,"?action=$action&keyword=".urlencode($keyword),$rows);

	}elseif($action=='posttgnew'){//推广新的。设置
		
		if(!$id || !$ctype)showerr("没有找到你要访问的页");		
		
		$rsdb=$db->get_one("SELECT * FROM {$_pre}content_buy WHERE id='$id' AND yz=1 LIMIT 1");
		if(!$rsdb[id])showerr("此信息不存在或者并未通过验证");
		
		
		
		$rsdb[tg_price]=$webdb[tg_permoney];
		$rsdb[picurl_min]=getimgdir($rsdb[picurl],$ctype).".gif";
		$parents = $bcategory->get_parents($rsdb['fid']);
		$parents[] = $bcategory->categories[$rsdb['fid']];
		
		foreach($parents as $v){
			$fid_allpath[]='<input name="to_fid" value="'. $v['fid'] .'" type="radio">'. $v['name'] .' <a href="?action=tg_top&fid='. $v['fid'] .'" target="_blank" >[排名]</a>';
		}
		/* $rsdb[fid_all]=explode(",",$rsdb[fid_all]);
		foreach($rsdb[fid_all] as $key){
			if($key>0) $fid_allpath[]="<input name='to_fid' value='$key' type='radio'>".$Fid_db[name][$key]." <a href='?action=tg_top&fid=$key' target='_blank' >[排名]</a>";
		} */
		$fid_allpath=implode(" > ",$fid_allpath)." <input name='to_fid' value='$rsdb[fid]' type='radio' checked>默认 <a href='?action=tg_top&fid=$rsdb[fid]' target='_blank' >[排名]</a>";

		$mymoney=get_money($rsdb[uid]);

	}elseif($action=='savetgnew'){//推广新的。设置
	
		if(!$id) showerr("没有找到你要访问的页");
		$rsdb=$db->get_one("SELECT * FROM {$_pre}content_buy WHERE id='$id' AND yz=1 LIMIT 1");
		if(!$rsdb[id]) showerr("此信息不存在或者并未通过验证");
		$tg_title=htmlspecialchars($tg_title);
		if(strlen($tg_title) > 48) showerr("标题不能长于24个汉字");
		if(strlen($tg_title) < 10) showerr("标题不能短于5个汉字");
		//看看是否已经在推广
		$tg_rsdb=$db->get_one("SELECT * FROM {$_pre}tg_buy WHERE tg_id='$id' AND tg_uid='$rsdb[uid]' AND (`tg_posttime`+(`tg_howlong`*60*60)) > $timestamp ");
		if($tg_rsdb[tg_id]) showerr("此信息已经在推广中");
		//计算将要扣除的积分
		$tg_howmuch=floatval($tg_howmuch);
		$tg_tatol=round($tg_howlong * $tg_howmuch,0);
		
		//扣除积分先
		$mymoney=get_money($rsdb[uid]);
		
		if($mymoney < $tg_tatol) showerr("账户积分不足支付本次推广消耗.<br>点击此处【<a href='member/?main=$webdb[www_url]/member/money.php?job=list' target='_blank'>充值</a>】换积分，点击此处查看<a href='help.php?help_key=充值' target='_blank'>怎么充值</a>？");
		plus_money($rsdb[uid],-$tg_tatol);
		
		//设置是否审核
		if($webdb[autoyz_tg]){
			$yz=1;$tg_posttime=$timestamp;
		}else{
			$yz=0;$tg_posttime=0;
		}
		
		//设置推广
		$db->query("INSERT INTO `{$_pre}tg_buy` ( `tg_myid` , `tg_id` , `tg_title` , `tg_price` , `tg_fid`, `tg_uid` , `tg_ctype` , `tg_posttime` , `tg_howmuch` , `tg_howlong`,`yz`,`posttime` ) VALUES ('', '$id', '$tg_title', '$rsdb[my_price]', '$to_fid', '$rsdb[uid]', '$rsdb[ctype]', '$tg_posttime', '$tg_howmuch', '$tg_howlong','$yz','$timestamp');");
		
		header("location:tg_buy.php?action=savetgnewok");
		echo "<META HTTP-EQUIV=REFRESH CONTENT='0;URL=tg_buy.php?action=savetgnewok'>";
	}elseif($action=='tg_top'){
		//$toplistdb
		$fid=intval($fid);
		
		$where="WHERE  (`tg_posttime`+(`tg_howlong`*60*60)) > $timestamp ";
		if($fid){
			
			$bcategory->cache_read();
			if($bcategory->categories[$fid]['categories']){
				$fid_path = $fid .','. implode(',', $bcategory->get_children_ids($fid));
				$has_sub = true;
			}else{
				$fid_path = $fid;
			}
			$where.=" AND tg_fid IN ($fid_path) ";
			//$where.=" AND concat(',',tg_fid_all,',') LIKE('%,$fid,%')";
		}
		$query=$db->query("SELECT * FROM {$_pre}tg_buy $where ORDER BY tg_howmuch DESC LIMIT 0,100");
		while($rs=$db->fetch_array($query)){
			$rs[yz]=$rs[yz]?"推广中":"等待审核";
			$toplistdb[]=$rs;
		}
	}

	//输出
	require(Mpath."inc/head.php");
	require(getTpl("tg_buy"));
	require(Mpath."inc/foot.php");

	






?>