<?php
require("global.php");
require(Mpath.'inc/categories.php');

	if(!$lfjuid) showerr("�㻹û�е�½�����½");
	$ctype=intval($ctype);
	$ctype=$ctype?$ctype:1;
		//�õ���������Ϣ
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
				$rs[my_price]="<strong><font color=#FF3300>$rs[my_price]</font></strong>Ԫ/$rs[quantity_type]";
		}else{
				$rs[my_price]='�۸�����';
		}
		if(!$ctype_db) $rs[my_price]="δ֪";
		
		if($rs[picurl])	$rs[picurl_min]=getimgdir($rs[picurl],$rs[tg_ctype]).".gif";
		$rs[total]=round($rs[tg_howmuch]*$rs[tg_howlong],1);
		
		if($rs[yz]){
			if(   ( $rs[tg_posttime]+$rs[tg_howlong]*60*60 ) > $timestamp){
				$rs[status]="���ύ";
				$rs[yz]    =$rs[yz]?"�ƹ���":"<font color=red>�ȴ����</font>";
			}else{
				$rs[status]="�ѽ���";
				$rs[yz]    ="-";
			}
		}else{
			$rs[status]="���ύ";
			$rs[yz]    =$rs[yz]?"&nbsp;":"<font color=red>�ȴ����</font>";
		}
		
		$rs[posttime]=$rs[posttime]?date("Y-m-d H:i:s",$rs[posttime]):"";

		$rs[tg_posttime]=$rs[tg_posttime]?date("Y-m-d H:i:s",$rs[tg_posttime]):"δ��ʼ";
		
		
		$mytglist[]=$rs;
		
	}
	
	$showpage=getpage("{$_pre}tg_buy",$where,"?action=tg",$rows);

	$webdb[tg_permoney]=$webdb[tg_permoney]?$webdb[tg_permoney]:3;
	
	$bcategory->cache_read();
	
	if($action=='tg'){ //״̬��ʾ
	
	}elseif($action=='tgnew'){ //�ƹ��µġ�ѡ��
			$where=" WHERE tg_uid='$lfjuid' AND (`tg_posttime`+(`tg_howlong`*60*60)) > $timestamp";
			$query=$db->query("SELECT * FROM {$_pre}tg_buy  $where ORDER BY tg_myid DESC ");
			while($rs=$db->fetch_array($query)){
				$overtime=intval( $rs[tg_posttime] + ($rs[tg_howlong]*60*60)    );
				if($overtime > $timestamp) $mytg_ids[]=$rs[tg_id]; 

			}
		//�õ���Ӧ�б�
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
			if(in_array($rs[id],$mytg_ids)) $rs[status]="<font color=red>�ƹ���</font>";
			else $rs[status]="<input name='tg_$rs[id]' onclick=\"window.location='?action=posttgnew&fid=$rs[fid]&id=$rs[id]&ctype=$rs[ctype]';\" value='�ƹ�' type='button'>";
			$listdb[]=$rs;
		}	
		
		$showpage=getpage("{$_pre}content_buy A",$where,"?action=$action&keyword=".urlencode($keyword),$rows);

	}elseif($action=='posttgnew'){//�ƹ��µġ�����
		
		if(!$id || !$ctype)showerr("û���ҵ���Ҫ���ʵ�ҳ");		
		
		$rsdb=$db->get_one("SELECT * FROM {$_pre}content_buy WHERE id='$id' AND yz=1 LIMIT 1");
		if(!$rsdb[id])showerr("����Ϣ�����ڻ��߲�δͨ����֤");
		
		
		
		$rsdb[tg_price]=$webdb[tg_permoney];
		$rsdb[picurl_min]=getimgdir($rsdb[picurl],$ctype).".gif";
		$parents = $bcategory->get_parents($rsdb['fid']);
		$parents[] = $bcategory->categories[$rsdb['fid']];
		
		foreach($parents as $v){
			$fid_allpath[]='<input name="to_fid" value="'. $v['fid'] .'" type="radio">'. $v['name'] .' <a href="?action=tg_top&fid='. $v['fid'] .'" target="_blank" >[����]</a>';
		}
		/* $rsdb[fid_all]=explode(",",$rsdb[fid_all]);
		foreach($rsdb[fid_all] as $key){
			if($key>0) $fid_allpath[]="<input name='to_fid' value='$key' type='radio'>".$Fid_db[name][$key]." <a href='?action=tg_top&fid=$key' target='_blank' >[����]</a>";
		} */
		$fid_allpath=implode(" > ",$fid_allpath)." <input name='to_fid' value='$rsdb[fid]' type='radio' checked>Ĭ�� <a href='?action=tg_top&fid=$rsdb[fid]' target='_blank' >[����]</a>";

		$mymoney=get_money($rsdb[uid]);

	}elseif($action=='savetgnew'){//�ƹ��µġ�����
	
		if(!$id) showerr("û���ҵ���Ҫ���ʵ�ҳ");
		$rsdb=$db->get_one("SELECT * FROM {$_pre}content_buy WHERE id='$id' AND yz=1 LIMIT 1");
		if(!$rsdb[id]) showerr("����Ϣ�����ڻ��߲�δͨ����֤");
		$tg_title=htmlspecialchars($tg_title);
		if(strlen($tg_title) > 48) showerr("���ⲻ�ܳ���24������");
		if(strlen($tg_title) < 10) showerr("���ⲻ�ܶ���5������");
		//�����Ƿ��Ѿ����ƹ�
		$tg_rsdb=$db->get_one("SELECT * FROM {$_pre}tg_buy WHERE tg_id='$id' AND tg_uid='$rsdb[uid]' AND (`tg_posttime`+(`tg_howlong`*60*60)) > $timestamp ");
		if($tg_rsdb[tg_id]) showerr("����Ϣ�Ѿ����ƹ���");
		//���㽫Ҫ�۳��Ļ���
		$tg_howmuch=floatval($tg_howmuch);
		$tg_tatol=round($tg_howlong * $tg_howmuch,0);
		
		//�۳�������
		$mymoney=get_money($rsdb[uid]);
		
		if($mymoney < $tg_tatol) showerr("�˻����ֲ���֧�������ƹ�����.<br>����˴���<a href='member/?main=$webdb[www_url]/member/money.php?job=list' target='_blank'>��ֵ</a>�������֣�����˴��鿴<a href='help.php?help_key=��ֵ' target='_blank'>��ô��ֵ</a>��");
		plus_money($rsdb[uid],-$tg_tatol);
		
		//�����Ƿ����
		if($webdb[autoyz_tg]){
			$yz=1;$tg_posttime=$timestamp;
		}else{
			$yz=0;$tg_posttime=0;
		}
		
		//�����ƹ�
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
			$rs[yz]=$rs[yz]?"�ƹ���":"�ȴ����";
			$toplistdb[]=$rs;
		}
	}

	//���
	require(Mpath."inc/head.php");
	require(getTpl("tg_buy"));
	require(Mpath."inc/foot.php");

	






?>