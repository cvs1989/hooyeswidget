<?php
require("global.php");

/**
*��ҳ���ɾ�̬
**/
if($job=="makeindex")
{
	if($webdb[Info_MakeIndexHtmlTime]>0)
	{
		$time=$webdb[Info_MakeIndexHtmlTime]*60;
		if((time()-@filemtime("index.htm"))>$time)
		{
			echo "<div style='display:none'><iframe src=index.php?MakeIndex=1></iframe></div>";
		}
	}
}

/*�û������ֶεĹ���*/
elseif($action=='pingfen')
{
	/*���ÿ����Ϣ30���Ӳ���������һ��*/
	$time=$timestamp+30*60;

	$pingfenID="pingfenID_$id";
	if($_COOKIE[$pingfenID])
	{
		showerr("��Сʱ��,�����ظ�����!!!");
	}
	setcookie($pingfenID,"1",$time,"/");

	$_web=preg_replace("/http:\/\/([^\/]+)\/(.*)/is","http://\\1",$WEBURL);
	if($webdb[Info_forbidOutPost]&&!ereg("^$_web",$FROMURL))
	{
		showerr("ϵͳ���ò��ܴ��ⲿ�ύ����");
	}

	$rsdb=$db->get_one("SELECT M.config AS m_config,C.mid FROM {$_pre}content C INNER JOIN {$_pre}module M ON C.mid=M.id WHERE C.id='$id' ");

	if(!$rsdb[mid])
	{
		showerr("��ID������");
	}
	$m_config=unserialize($rsdb[m_config]);
	$array=$m_config[field_db];
	
	foreach( $postdb AS $key=>$value)
	{
		if($array[$key][form_type]=='pingfen')
		{
			$db->query("UPDATE {$_pre}content_{$rsdb[mid]} SET `$key`=`$key`+'$value' WHERE id='$id' ");
		}
	}
	header("location:$FROMURL");
	exit;
}
elseif($job=="report")
{
	if(!$lfjuid){
		showerr("�οͲ��ܾٱ�,���ȵ�¼");
	}
	if($step==2)
	{
		if($ctype == 1){
			$tbl = 'content_sell';
		}else if($ctype == 2){
			$tbl = 'content_buy';
		}else{
			showerr("������Ĳ���");
		}
		$rs=$db->get_one("SELECT * FROM {$_pre}report WHERE uid='$lfjuid' ORDER BY rid DESC LIMIT 1");
		if( ($timestamp-$rs[posttime])<60 ){
			showerr("1����,�벻Ҫ�ظ��ٱ�");
		}
		$rs=$db->get_one("SELECT title FROM {$_pre}$tbl WHERE id='$id'");
		$content=filtrate($content);
		$db->query("INSERT INTO `{$_pre}report` (`id`, `uid`, `username`, ctype, title, `posttime`, `onlineip`, `type`,`content`) VALUES ('$id','$lfjuid','$lfjid', $ctype, '$rs[title]', '$timestamp','$onlineip','$type','$content')");
		
		refreshto($FROMURL,"�ٱ��ɹ�",1);
	}
	require(Mpath."php168/report.php");
	@include(Mpath."php168/guide_fid.php");
	
	$typedb[1]=' checked ';

	require(Mpath."inc/head.php");
	require(getTpl("report"));
	require(Mpath."inc/foot.php");
}
elseif($job=="getshop")
{
	if(!$lfjuid){
		showerr("�㻹û�е�¼,���ȵ�¼");
	}
	$rs=$db->get_one("SELECT * FROM {$_pre}getshop WHERE uid='$lfjuid' AND id='$id'");
	if( ($timestamp-$rs[posttime])<60 ){
		showerr("�벻Ҫ�ظ�����");
	}
	$rs=$db->get_one("SELECT * FROM {$_pre}content WHERE uid='$lfjuid' AND id='$id'");
	if( ($timestamp-$rs[posttime])<60 ){
		showerr("�Ѿ�����ĵ�����,�㲻��Ҫ����");
	}
	if($step==2)
	{
		$rs=$db->get_one("SELECT * FROM {$_pre}getshop WHERE uid='$lfjuid' ORDER BY rid DESC LIMIT 1");
		if( ($timestamp-$rs[posttime])<60 ){
			showerr("1����,�벻Ҫ����");
		}
		$content=filtrate($content);
		$telephone=filtrate($telephone);
		$linkman=filtrate($linkman);
		$db->query("INSERT INTO `{$_pre}getshop` (`id`, `fid`, `uid`, `username`, `posttime`, `onlineip`, `content`, `linkman`, `telephone`) VALUES ('$id','$fid','$lfjuid','$lfjid','$timestamp','$onlineip','$content','$linkman','$telephone')");
		refreshto("bencandy.php?fid=$fid&id=$id","�����������,���ύ�ɹ�,���ǻᾡ�촦��.",5);
	}
	@include(Mpath."php168/guide_fid.php");
	
	require(Mpath."inc/head.php");
	require(getTpl("getshop"));
	require(Mpath."inc/foot.php");
}
//�ղ�
elseif($job=='collect')
{
	if(!$lfjid){
		showerr("���ȵ�¼");
	}elseif(!$id){
		showerr("ID������");
	}
	if($db->get_one("SELECT * FROM `{$_pre}collection` WHERE `id`='$id' AND uid='$lfjuid' and ctype=$ctype")){
		showerr("�벻Ҫ�ظ��ղر�����Ϣ",1); 
	}
	if(!$web_admin){
		if($webdb[Info_CollectArticleNum]<1){
			$webdb[Info_CollectArticleNum]=50;
		}
		$rs=$db->get_one("SELECT COUNT(*) AS NUM FROM `{$_pre}collection` WHERE uid='$lfjuid'");
		if($rs[NUM]>=$webdb[Info_CollectArticleNum]){
			showerr("�����ֻ���ղ�{$webdb[Info_CollectArticleNum]}����Ϣ",1);
		}
	}
	
	$db->query("INSERT INTO `{$_pre}collection` (  `id` , `uid` , `posttime`,`ctype`) VALUES ('$id','$lfjuid','$timestamp','$ctype')");

	refreshto("$Mdomain/b/member/?main=collection.php","�ղسɹ�!",1);
}
elseif($job=="getjob_sort")
{
	if($sid){
		$step=!$step?1:$step;
		$step++;
		@include(Mpath."php168/all_hrfid.php");
		
		if(!is_array($hrFid_db[$sid]) || count($hrFid_db[$sid])<1){
			echo "<SCRIPT LANGUAGE=\"JavaScript\">
		<!--
		
		parent.document.getElementById(\"show_jobs_sort_$step\").innerHTML='$show';
		//-->
		</SCRIPT>";exit;
		}
		$show="<select name='job_sort[{$step}]' onchange='choose_jobSort(this.options[this.selectedIndex].value,$step)'> ";
		foreach($hrFid_db[$sid] as $key=>$val){
					$show.="<option value='$key'>$val</option>";
		}
		$show.="</select>";
	
		$show=str_replace("\r","",$show);
		$show=str_replace("\n","",$show);
		$show=str_replace("'","\'",$show);
		$show.="<span id=show_jobs_sort_".($step+1)."></span>";
		echo "<SCRIPT LANGUAGE=\"JavaScript\">
		<!--
		
		parent.document.getElementById(\"show_jobs_sort_$step\").innerHTML='$show';
		//-->
		</SCRIPT>";
	}else{
		echo "<SCRIPT LANGUAGE=\"JavaScript\">
		<!--
		parent.document.getElementById(\"show_jobs_sort_1\").innerHTML='';
		//-->
		</SCRIPT>";
	}
}
elseif($job=="getcity")
{
	if($fup){
		$show=select_where("city","'postdb[city_id]'",$fid,$fup);
		$show=str_replace("\r","",$show);
		$show=str_replace("\n","",$show);
		$show=str_replace("'","\'",$show);
		echo "<SCRIPT LANGUAGE=\"JavaScript\">
		<!--
		parent.document.getElementById(\"{$typeid}showcity\").innerHTML='$show';
		//-->
		</SCRIPT>";
	}else{
		echo "<SCRIPT LANGUAGE=\"JavaScript\">
		<!--
		parent.document.getElementById(\"{$typeid}showcity\").innerHTML='';
		//-->
		</SCRIPT>";
	}
}
elseif($job=="getcity2"){
	
	if($fup){
		$show=select_where("city","'$name' style='width:100px'",$ck,$fup);
		$show=str_replace("\r","",$show);
		$show=str_replace("\n","",$show);
		$show=str_replace("'","\'",$show);
		echo "<SCRIPT LANGUAGE=\"JavaScript\">
		<!--
		parent.document.getElementById(\"$showspan\").innerHTML='$show';
		//-->
		</SCRIPT>";
	}else{
		echo "<SCRIPT LANGUAGE=\"JavaScript\">
		<!--
		parent.document.getElementById(\"$showspan\").innerHTML='';
		//-->
		</SCRIPT>";
	}
}
elseif($job=='update'){
	if(!$lfjuid){
		showerr('���ȵ�¼');
	}
	$rs=$db->get_one("SELECT * FROM {$_pre}content WHERE id='$id'");
	if($rs[uid]!=$lfjuid){
		showerr('����Ȩ��');
	}
	if($timestamp-$rs[posttime]<3600){
		showerr('�����ϴθ���ʱ��1Сʱ��,�ſ��Խ���ˢ��!');
	}
	if($rs['list']>$timestamp){
		$list=$rs['list'];
	}else{
		$list=$timestamp;
	}
	$db->query("UPDATE {$_pre}content SET list='$list',posttime='$timestamp' WHERE id='$id'");
	refreshto("$FROMURL","ˢ�³ɹ�",1);
}
elseif($job=="gettg")
{
	
	
		echo '<meta http-equiv="Content-Type" content="text/html; charset=gb2312">';
		echo "��ʱû��";
	
}
?>