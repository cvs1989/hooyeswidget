<?php
require("global.php");
require_once(PHP168_PATH."inc/function.ad.php");

if(!$lfjid){
	showerr("�㻹û��¼");
}

$linkdb=array(
			"���λ�б�"=>"?job=list",
			"�ҹ���Ĺ��"=>"?job=mylist"
			);

$array_adtype=array(
					"word"=>"���ֹ��",
					"pic"=>"ͼƬ���",
					"swf"=>"FLASH���",
					"code"=>"������",
					"duilian"=>"�������"
					);

if($job=='list')
{
	$query = $db->query("SELECT * FROM {$pre}ad WHERE ifsale=1 ORDER BY list DESC");
	while($rs = $db->fetch_array($query))
	{
		if($rs[autoyz]){
			$rs[_ifyz]='�������';
		}else{
			$rs[_ifyz]='��Ҫ���';
		}

		if($_r=$db->get_one("SELECT * FROM {$pre}ad_user WHERE u_endtime>'$timestamp' AND id='$rs[id]'"))
		{
			$_r[u_endtime]=date("Y-m-d H:i",$_r[u_endtime]);
			$rs[state]="{$_r[u_endtime]}����";
			$rs[alert]="alert('ֱ��{$_r[u_endtime]}�Ժ�ſɹ���');return false;";
			$rs[color]="#ccc;";
		}
		elseif($_r=$db->get_one("SELECT * FROM {$pre}ad_user WHERE u_yz=0 AND id='$rs[id]'"))
		{
			$_r[u_endtime]=date("Y-m-d H:i",$_r[u_endtime]);
			$rs[state]="�����˹���,�ȴ���˵���";
			$rs[alert]="alert('�����˹���,�����ڲ��ܹ���');return false;";
			$rs[color]="#ccc;";
		}
		else
		{
			$rs[state]="���ڿɹ���";
			$rs[color]="red;";
		}
		$listdb[]=$rs;
	}
	$lfjdb[money]=intval(get_money($lfjdb[uid]));
	require(dirname(__FILE__)."/"."head.php");
	require(dirname(__FILE__)."/"."template/buyad/list.htm");
	require(dirname(__FILE__)."/"."foot.php");
}

elseif($job=='buy')
{
	$_r=$db->get_one("SELECT * FROM {$pre}ad_user WHERE u_endtime>'$timestamp' AND id='$id'");
	
	if($_r)
	{
		$_r[u_endtime]=date("Y-m-d H:i",$_r[u_endtime]);
		showerr("ֱ��{$_r[u_endtime]}�ſɹ���");
	}

	$rsdb=$db->get_one("SELECT * FROM {$pre}ad WHERE id='$id'");
	if(!$rsdb){
		showerr("���λ������!");
	}
	if($rsdb[autoyz]){
		$rsdb[_ifyz]='�������Ա���,ֱ����ʾ';
	}else{
		$rsdb[_ifyz]='����������ʾ,��Ҫ�ȴ�����Ա���';
	}
	require(dirname(__FILE__)."/"."head.php");
	require(dirname(__FILE__)."/"."template/buyad/buy.htm");
	require(dirname(__FILE__)."/"."foot.php");
}

elseif($action=="buy")
{
	if($atc_day<1)
	{
		showerr("����Ĺ�治��С��һ��");
	}
	$_r=$db->get_one("SELECT * FROM {$pre}ad_user WHERE u_endtime>'$timestamp' AND id='$id'");
	if($_r)
	{
		$_r[u_endtime]=date("Y-m-d H:i",$_r[u_endtime]);
		showerr("ֱ��{$_r[u_endtime]}�ſɹ���");
	}
	
	$rsdb=$db->get_one("SELECT * FROM {$pre}ad WHERE id='$id'");

	$totalmoneycard=$u_moneycard=$rsdb[moneycard]*$atc_day;
	/*
	$lfjdb[moneycard]=intval($lfjdb[moneycard]);
	if($totalmoneycard>$lfjdb[moneycard])
	{
		showerr("��Ľ�Ҳ���$totalmoneycard,����н��:$lfjdb[moneycard]");
	}
	*/
	$lfjdb[money]=intval(get_money($lfjdb[uid]));
	if($totalmoneycard>$lfjdb[money])
	{
		showerr("��Ļ��ֲ���$totalmoneycard,����л���:$lfjdb[money]");
	}
	$cdb=unserialize($rsdb[adcode]);
	if($rsdb[type]=='pic'){
		//�Զ���ͼ,��ͼƬ��С
		$imgdb=getimagesize(PHP168_PATH."$webdb[updir]/$atc_img");
		if( $imgdb[0]>$cdb[width] || $imgdb[1]>$cdb[height] ){
			gdpic(PHP168_PATH."$webdb[updir]/$atc_img",PHP168_PATH."$webdb[updir]/$atc_img",$cdb[width],$cdb[height],array('fix'=>1));
		}
	}
	
	if($rsdb[type]=='word'){
		$cdb[word]=filtrate($atc_word);
		$cdb[linkurl]=filtrate($atc_url);
	}elseif($rsdb[type]=='pic'){
		$cdb[picurl]=filtrate($atc_img);
		$cdb[linkurl]=filtrate($atc_url);
	}elseif($rsdb[type]=='swf'){
		$cdb[flashurl]=filtrate($atc_url);
	}elseif($rsdb[type]=='duilian'){
		$cdb[l_src]=filtrate($l_src);
		$cdb[l_link]=filtrate($l_link);
		$cdb[r_src]=filtrate($r_src);
		$cdb[r_link]=filtrate($r_link);
	}
	$cdb[code]=stripslashes($atc_code);
	$u_code=addslashes(serialize($cdb));

	$u_yz=$rsdb[autoyz];
	if($u_yz)
	{
		$u_begintime=$timestamp-1;
		$u_endtime=$u_begintime+$atc_day*24*3600;
		add_user( $lfjuid,-intval($totalmoneycard) );	//�۳�����
		//$db->query("UPDATE {$pre}memberdata SET moneycard=moneycard-'$totalmoneycard' WHERE uid='$lfjuid'");
	}
	else
	{
		$u_begintime=$u_endtime=0;
	}
	$u_hits=0;
	$db->query("INSERT INTO `{$pre}ad_user` ( `id` , `u_uid` , `u_username` , `u_day` , `u_begintime` , `u_endtime` , `u_hits` , `u_yz` , `u_code` , `u_money` , `u_moneycard` , `u_posttime` ) VALUES ('$id','$lfjuid','$lfjid','$atc_day','$u_begintime','$u_endtime','$u_hits','$u_yz','$u_code','$u_money','$u_moneycard','$timestamp')");
	make_ad_cache();
	refreshto("?job=list","����ɹ�,�㹲֧����{$u_moneycard}����","3");
}

elseif($job=='mylist')
{
	$query = $db->query("SELECT A.*,B.* FROM {$pre}ad_user B LEFT JOIN {$pre}ad A ON A.id=B.id WHERE B.u_uid='$lfjuid' ORDER BY B.u_id DESC");
	while($rs = $db->fetch_array($query))
	{
		if($rs[u_yz]&&($rs[u_endtime]-$timestamp)<24*3600)
		{
			$rs[alert]="alert('���ڻ�һ���ڽ�Ҫ���ڵĹ�治�����޸�');return false;";
			$rs[color]="#ccc;";
		}
		else
		{
			$rs[alert]="";
			$rs[color]="red;";
		}

		if($rs[u_yz]){
			$rs[_ifyz]='�����';
		}else{
			$rs[_ifyz]='<font color=blue>δ���</font>';
		}
		if($rs[u_begintime])
		{
			$rs[u_begintime]=date("Y-m-d H:i",$rs[u_begintime]);
		}
		else
		{
			$rs[u_begintime]='';
		}
		if($rs[u_endtime])
		{
			$rs[u_endtime]=date("Y-m-d H:i",$rs[u_endtime]);
		}
		else
		{
			$rs[u_endtime]='';
		}
		$listdb[]=$rs;
	}
	require(dirname(__FILE__)."/"."head.php");
	require(dirname(__FILE__)."/"."template/buyad/mylist.htm");
	require(dirname(__FILE__)."/"."foot.php");
}
elseif($action=="del")
{
	$db->query("DELETE FROM {$pre}ad_user WHERE u_id='$u_id' AND u_uid='$lfjuid'");
	make_ad_cache();
	refreshto("?job=mylist","ɾ���ɹ�","3");
}
elseif($job=='edit')
{
	$rsdb=$db->get_one("SELECT A.*,B.* FROM {$pre}ad_user B LEFT JOIN {$pre}ad A ON A.id=B.id WHERE B.u_id='$u_id'");
	@extract(unserialize($rsdb[u_code]));
	if($rsdb[autoyz]){
		$rsdb[_ifyz]='�Զ�ͨ�����';
	}else{
		$rsdb[_ifyz]='�ֹ����';
	}
	$id=$rsdb[id];
	require(dirname(__FILE__)."/"."head.php");
	require(dirname(__FILE__)."/"."template/buyad/buy.htm");
	require(dirname(__FILE__)."/"."foot.php");
}

elseif($action=="edit")
{
	if($atc_day<1)
	{
		showerr("����Ĺ�治��С��һ��");
	}
	
	$rsdb=$db->get_one("SELECT A.*,B.* FROM {$pre}ad_user B LEFT JOIN {$pre}ad A ON A.id=B.id WHERE B.u_id='$u_id'");

	if($rsdb[u_endtime]<$timestamp)
	{
		showerr("���ڹ�治�����޸�");
	}
	elseif((($rsdb[u_endtime]-$timestamp)<24*3600)&&$atc_day<$rsdb[u_day])
	{
		showerr("�����ڽ�Ҫ���ڵĹ�治�ܰ����ڸ�С");
	}
	//$rsdb=$db->get_one("SELECT * FROM {$pre}ad WHERE id='$id'");

	$totalmoneycard=$u_moneycard=$rsdb[moneycard]*$atc_day;
	//$lfjdb[moneycard]=intval($lfjdb[moneycard]);
	$lfjdb[money]=intval(get_money($lfjdb[uid]));
	
	$cdb=unserialize($rsdb[adcode]);
	
	if($rsdb[type]=='word'){
		$cdb[word]=filtrate($atc_word);
		$cdb[linkurl]=filtrate($atc_url);
	}elseif($rsdb[type]=='pic'){
		$cdb[picurl]=filtrate($atc_img);
		$cdb[linkurl]=filtrate($atc_url);
	}elseif($rsdb[type]=='swf'){
		$cdb[flashurl]=filtrate($atc_url);
	}elseif($rsdb[type]=='duilian'){
		$cdb[l_src]=filtrate($l_src);
		$cdb[l_link]=filtrate($l_link);
		$cdb[r_src]=filtrate($r_src);
		$cdb[r_link]=filtrate($r_link);
	}
	$cdb[code]=stripslashes($atc_code);
	$u_code=addslashes(serialize($cdb));

	$u_yz=$rsdb[autoyz];
	if($rsdb[autoyz])
	{
		$u_begintime=$rsdb[u_begintime];
		$u_endtime=$rsdb[u_endtime]+($atc_day-$rsdb[u_day])*3600*24;

		if(!$rsdb[u_yz])
		{
			if($totalmoneycard>$lfjdb[money])
			{
				showerr("��Ļ��ֲ���$totalmoneycard,����л���:$lfjdb[money]");
			}
			
			//$db->query("UPDATE {$pre}memberdata SET moneycard=moneycard-'$totalmoneycard' WHERE uid='$lfjuid'");
			add_user($lfjuid,-intval($totalmoneycard));	//�۳�����
		}
		else
		{
			if( $totalmoneycard>($lfjdb[money]+$rsdb[u_money]) )
			{
				showerr("��Ļ��ֲ���,����л���:$lfjdb[money]");
			}
			//$db->query("UPDATE {$pre}memberdata SET moneycard=moneycard-'$totalmoneycard'+'$rsdb[u_money]' WHERE uid='$lfjuid'");
			add_user($lfjuid,-intval($rsdb[u_money]-$totalmoneycard));	//�۳�����
		}			
	}
	else
	{
		if($totalmoneycard>$lfjdb[money])
		{
			showerr("��Ļ��ֲ���$totalmoneycard,����л���:$lfjdb[money]");
		}
		$u_begintime=$u_endtime=0;
	}

	$u_hits=0;
	$db->query("UPDATE `{$pre}ad_user` SET `u_day`='$atc_day',`u_begintime`='$u_begintime',`u_endtime`='$u_endtime',`u_yz`='$u_yz',`u_code`='$u_code',`u_money`='$u_money',`u_moneycard`='$u_moneycard' WHERE u_id='$u_id' AND u_uid='$lfjuid'");
	
	make_ad_cache();
	refreshto("?job=mylist","�޸ĳɹ�","3");
}


?>