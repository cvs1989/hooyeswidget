<?php
require_once(dirname(__FILE__)."/"."global.php");
!$aid && $aid = intval($id);
$id = $aid;
$page<1 && $page=1;

if(!$id&&!$aid&&$webdb[NewsMakeHtml]==2){
	//α��̬����
	Explain_HtmlUrl();
	!$aid && $aid = intval($id);
}

//$Cache_FileName=PHP168_PATH."cache/bencandy_cache/".floor($id/3000)."/{$id}_{$page}.php";
if(!$jobs&&$webdb[bencandy_cache_time]&&(time()-filemtime($Cache_FileName))<($webdb[bencandy_cache_time]*60)){
	echo read_file($Cache_FileName);
	exit;
}

@include(PHP168_PATH."php168/guide_fid.php");		//��Ŀ�����ļ�




/**
*��ȡ����
**/
$min=intval($page)-1;
$erp=$Fid_db[iftable][$fid]?$Fid_db[iftable][$fid]:'';
$rsdb=$db->get_one("SELECT R.*,A.* FROM {$pre}article$erp A LEFT JOIN {$pre}reply$erp R ON A.aid=R.aid WHERE A.aid=$aid ORDER BY R.topic DESC,R.orderid ASC LIMIT $min,1");

if(!$rsdb){
	showerr("���ݲ�����!");
}elseif($fid!=$rsdb[fid]){
	showerr("FID����");
}

/**
*��Ŀ�����ļ�
**/
$fidDB=$db->get_one("SELECT S.*,M.alias AS M_alias,M.keywords AS M_keyword,M.config AS M_config FROM {$pre}sort S LEFT JOIN {$pre}article_module M ON S.fmid=M.id WHERE S.fid='$fid'");
$fidDB[M_alias] || $fidDB[M_alias]='����';
$fidDB[M_config]=unserialize($fidDB[M_config]);
$fidDB[config]=unserialize($fidDB[config]);
$FidTpl=unserialize($fidDB[template]);

$fupId=intval($fidDB[type]?$fid:$fidDB[fup]);

//��ֹ���ʶ�̬ҳ
if($webdb[ForbidShowPhpPage]&&!$NeedCheck&&!$jobs){
	if($webdb[NewsMakeHtml]==2&&ereg("=[0-9]+$",$WEBURL)){		//α��̬
		eval("\$url=\"$webdb[bencandy_filename2]\";");
		header("location:$webdb[www_url]/$url");
		exit;
	}elseif($webdb[NewsMakeHtml]==1){							//�澲̬
		$detail=get_html_url();
		if(is_file(PHP168_PATH.$detail[_showurl])){
			header("location:$detail[showurl]");
			exit;
		}
	}
}

/**
*���¼��
**/
check_article($rsdb);

//ͳ�Ƶ������
$db->query("UPDATE {$pre}article$erp SET hits=hits+1,lastview='$timestamp' WHERE aid='$aid'");

//SEO
$titleDB[title]		= filtrate(strip_tags("$rsdb[title] - $fidDB[name] - $webdb[webname]"));
$titleDB[keywords]	= filtrate($rsdb[keywords]);
$rsdb[description] || $rsdb[description]=get_word(preg_replace("/(<([^<]+)>|	|&nbsp;|\n)/is","",$rsdb[content]),250);
$titleDB[description] = filtrate($rsdb[description]);



//���·��
$STYLE = $rsdb[style] ? $rsdb[style] : ($fidDB[style] ? $fidDB[style] : $STYLE);

//�����Ŀ����ģ��
if(is_file(html("$webdb[SideSortStyle]"))){
	$sortnameTPL=html("$webdb[SideSortStyle]");
}else{
	$sortnameTPL=html("side_sort/0");
}

/**
*ģ��ѡ��
**/
//���ƴ�������,�����ҳģ��
if($rsdb[iframeurl])
{
	$head_tpl="template/default/none.htm";
	$main_tpl="template/default/none.htm";
	$foot_tpl="template/default/iframe.htm";
}
else
{
	$showTpl=unserialize($rsdb[template]);
	$head_tpl=$showTpl[head]?$showTpl[head]:$FidTpl['head'];
	$main_tpl=$showTpl[bencandy]?$showTpl[bencandy]:$FidTpl['bencandy'];
	$foot_tpl=$showTpl[foot]?$showTpl[foot]:$FidTpl['foot'];
}

//����V6ǰ�İ汾
if(!$rsdb[ishtml])
{
	//������ʵ��ַ��ԭ
	$rsdb[content] = En_TruePath($rsdb[content],0);
	//UBB����
	require_once(PHP168_PATH."inc/encode.php");
	$rsdb[content] = format_text($rsdb[content]);
}
else
{
	//������ʵ��ַ��ԭ
	$rsdb[content] = En_TruePath($rsdb[content],0,1);

	require_once(PHP168_PATH."inc/encode.php");
	//�ļ�����
	//<div><a style="COLOR: red" href="http://1.com/upload_files/other/1_20070729020722_YmI=.rar" target=_blank p8name="p8download">�������</a></div>
	$rsdb[content]=preg_replace("/<IMG src=\"([^\"]+)\" border=0><A href=\"([^\"]+)\" target=_blank>([^<>]+)<\/A>/eis","encode_fileurl('\\1','\\2','\\3')",$rsdb[content]);
	$rsdb[content]=preg_replace("/<([^<>]+)href=\"([^\"]+)\"([^<>]+)p8name=\"p8download\"([^<>]*)>([^<>]+)<\/A>/eis","encode_fileurl('','\\2','\\5')",$rsdb[content]);
}

$rsdb[content]=show_keyword($rsdb[content]);	//ͻ����ʾ�ؼ���

$IS_BIZ && AvoidGather();	//���ɼ�����

$rsdb[posttime] = date("Y-m-d H:i:s",$rsdb[posttime]);

if($rsdb[copyfromurl]&&!strstr($rsdb[copyfromurl],"http://")){
	$rsdb[copyfromurl]="http://$rsdb[copyfromurl]";
}

//���·ֲ�
$showpage = getpage("","","bencandy.php?fid=$fid&aid=$aid",1,$rsdb[pages]);

/**
*��һƪ����һƪ,�Ƚ�Ӱ���ٶ�
**/
$nextdb=$db->get_one("SELECT title,aid,fid FROM {$pre}article$erp WHERE aid<'$id' AND fid='$fid' ORDER BY aid DESC LIMIT 1");
$nextdb[subject]=get_word($nextdb[title],34);
$backdb=$db->get_one("SELECT title,aid,fid FROM {$pre}article$erp WHERE aid>'$id' AND fid='$fid' ORDER BY aid ASC LIMIT 1");
$backdb[subject]=get_word($backdb[title],34);


/**
*Ϊ��ȡ��ǩ����
**/
$chdb[main_tpl]=html("bencandy",$main_tpl);

/**
*��ǩ
**/
$ch_fid	= intval($fidDB[config][label_bencandy]);	//�Ƿ�������Ŀר�ñ�ǩ
$ch_pagetype = 3;									//2,Ϊlistҳ,3,Ϊbencandyҳ
$ch_module = 0;										//����ģ��,Ĭ��Ϊ0
$ch = 0;											//�������κ�ר��
require(PHP168_PATH."inc/label_module.php");

//�����Զ���ģ��$fidDB[config]
if($rsdb[mid]){
	if($rsdb[mid]!=$fidDB[fmid]){
		@extract($db->get_one("SELECT config AS m_config FROM {$pre}article_module WHERE id='$rsdb[mid]'"));
		$M_config=unserialize($m_config);
	}else{
		$M_config=$fidDB[M_config];
	}
	
	$_rsdb=$db->get_one("SELECT * FROM `{$pre}article_content_$rsdb[mid]` WHERE aid='$id' AND rid='$rsdb[rid]'");
	if($_rsdb){
		$rsdb=$rsdb+$_rsdb;
		show_module_content($M_config);
	}
}

$rsdb[picurl]=tempdir($rsdb[picurl]);

$webdb[AutoTitleNum] && $rsdb[pages]>1 && $rsdb[title]=Set_Title_PageNum($rsdb[title],$page);

if($rsdb[keywords]){
	unset($array);
	$detail=explode(" ",$rsdb[keywords]);
	foreach( $detail AS $key=>$value){
		$_value=urlencode($value);
		$array[]="<A HREF='$webdb[www_url]/do/search.php?type=keyword&keyword=$_value' target=_blank>$value</A>";
	}
	$rsdb[keywords]=implode(" ",$array);
}

//���˲�������
$rsdb[content]=replace_bad_word($rsdb[content]);
$rsdb[title]=replace_bad_word($rsdb[title]);
$rsdb[subhead]=replace_bad_word($rsdb[subhead]);

//��ģ����չ�ӿ�
@include(PHP168_PATH."inc/bencandy_{$rsdb[mid]}.php");

/* $Murl = $webdb['www_url'].'/b';
$head_tpl = PHP168_PATH.'b/template/'. $STYLE .'/head.htm'; */
require(PHP168_PATH."inc/head.php");
if($rsdb[mid]&&file_exists(html("bencandy_$rsdb[mid]",$main_tpl))){
	require(html("bencandy_$rsdb[mid]",$main_tpl));
}else{
	require(html("bencandy",$main_tpl));
}
require(PHP168_PATH."inc/foot.php");

/*����α��̬*/
if($webdb[NewsMakeHtml]==2)
{
	$content=ob_get_contents();
	ob_end_clean();
	ob_start();
	$content=fake_html($content);
	echo "$content";
}

if(!$jobs&&$webdb[bencandy_cache_time]&&(time()-filemtime($Cache_FileName))>($webdb[bencandy_cache_time]*60)){
	
	if(!is_dir(dirname($Cache_FileName))){
		makepath(dirname($Cache_FileName));
	}
	$content.="<SCRIPT LANGUAGE='JavaScript' src='$webdb[www_url]/do/job.php?job=updatehits&aid=$id'></SCRIPT>";
	write_file($Cache_FileName,$content);
}elseif($jobs=='show'){
	@unlink($Cache_FileName);
}

/**
*���¼��
**/
function check_article($rsdb){
	global $fidDB,$web_admin,$groupdb,$timestamp,$lfjid,$lfjuid,$fid,$id,$aid,$buy,$lfjdb,$webdb,$pre,$db;
	if(!$rsdb)
	{
		showerr("���²�����");
	}
	if( $fidDB[allowviewcontent]&&!in_array($fidDB[M_keyword],array('mv','download')) )
	{
		if( !$web_admin&&!in_array($groupdb[gid],explode(",",$fidDB[allowviewcontent])) )
		{
			showerr("�������û��鲻���������������");
		}
	}

	if( $rsdb[allowview]&&!in_array($fidDB[M_keyword],array('mv','download')) )
	{
		if( !$web_admin&&!in_array($groupdb[gid],explode(",",$rsdb[allowview])) )
		{
			showerr("����,�������û��鲻���������������");
		}
	}

	//�����˿�ʼ�����������
	if($rsdb[begintime]&&$timestamp<$rsdb[begintime])
	{
		$rsdb[begintime]=date("Y-m-d H:i:s",$rsdb[begintime]);
		if($web_admin){
			 Remind_msg("����ֻ�е��ˡ�{$rsdb[begintime]}���Ǹ�ʱ��ſ��Բ鿴,��Ϊ���ǹ���Ա,���Կ��Բ鿴,�������ǲ��ܲ鿴��");
		}else{
			showerr("<font color='red' ><u>�ܱ�Ǹ,�����������˱�������ֻ�е��ˡ�{$rsdb[begintime]}���Ǹ�ʱ��ſ��Բ鿴</u></font>");
		}
	}

	//������ʧЧ�����������
	if($rsdb[endtime]&&$timestamp>$rsdb[endtime])
	{
		$rsdb[endtime]=date("Y-m-d H:i:s",$rsdb[endtime]);
		if($web_admin){
			 Remind_msg("�����������鿴�����ǡ�{$rsdb[endtime]}��,��Ϊ���ǹ���Ա,���Կ��Բ鿴,�������ǲ��ܲ鿴��");
		}else{
			showerr("<font color='red' ><u>�ܱ�Ǹ,�����������˱����������鿴�����ǡ�{$rsdb[endtime]}���������ѳ�����������ޣ����Բ��ܲ鿴</u></font>");
		}
	}

	if($rsdb[yz]==2){
		if($web_admin){
			 Remind_msg("����վ�����ݲ����Բ鿴,��Ϊ���ǹ���Ա,���Կ��Բ鿴,�������ǲ��ܲ鿴��");
		}else{
			showerr("����վ�������㲻���Բ鿴");
		}
	}
	//δ���
	if($rsdb[yz]==0&&(!$lfjid||$lfjuid!=$rsdb[uid]))
	{
		if($web_admin){
			 Remind_msg("���Ļ�ûͨ����֤,��Ϊ���ǹ���Ա,���Կ��Բ鿴,�������ǲ��ܲ鿴��");
		}else{
			showerr("<font color='red' ><u>�ܱ�Ǹ,���Ļ�ûͨ����֤,�㲻�ܲ鿴</u></font>");
		}
	}

	//��ת������
	if($rsdb[jumpurl])
	{
		echo "ҳ��������ת�У����Ժ�...<META HTTP-EQUIV=REFRESH CONTENT='0;URL=$rsdb[jumpurl]'>";
		exit;
	}

	//��������
	if($rsdb[passwd])
	{
		if($web_admin)
		{
			 Remind_msg("��������������,��Ϊ���ǹ���Ա,���Կ��Բ鿴,�������ǲ��ܲ鿴��");
		}
		else
		{
			if( $_POST[password] && $_POST[TYPE] == 'article'  )
			{
				if( $_POST[password] != $rsdb[passwd] )
				{
					echo "<A HREF=\"?fid=$fid&aid=$aid\">���벻��ȷ,�������</A>";
					exit;
				}
				else
				{
					setcookie("article_passwd_$id",$rsdb[passwd]);
					$_COOKIE["article_passwd_$id"]=$rsdb[passwd];
				}
			}
			if( $_COOKIE["article_passwd_$id"] != $rsdb[passwd] )
			{
				echo "<CENTER><form name=\"form1\" method=\"post\" action=\"\">��������������:<input type=\"password\" name=\"password\"><input type=\"hidden\" name=\"TYPE\" value=\"article\"><input type=\"submit\" name=\"Submit\" value=\"�ύ\"></form></CENTER>";
				exit;
			}
		}
	}

	//��Ŀ����
	if( $makehtml!=2 && $fidDB[passwd] )
	{
		if($web_admin)
		{
			 Remind_msg("����Ŀ����������,��Ϊ���ǹ���Ա,���Կ��Բ鿴,�������ǲ��ܲ鿴��");
		}
		else
		{
			if( $_POST[password] && $_POST[TYPE] == 'sort' )
			{
				if( $_POST[password] != $fidDB[passwd] )
				{
					echo "<A HREF=\"?fid=$fid&aid=$aid\">���벻��ȷ,�������</A>";
					exit;
				}
				else
				{
					setcookie("sort_passwd_$fid",$fidDB[passwd]);
					$_COOKIE["sort_passwd_$fid"]=$fidDB[passwd];
				}
			}
			if( $_COOKIE["sort_passwd_$fid"] != $fidDB[passwd] )
			{
				echo "<CENTER><form name=\"form1\" method=\"post\" action=\"\">��������Ŀ����:<input type=\"password\" name=\"password\"><input type=\"hidden\" name=\"TYPE\" value=\"sort\"><input type=\"submit\" name=\"Submit\" value=\"�ύ\"></form></CENTER>";
				exit;
			}
		}
	}

	//���ִ���
	if( $rsdb[money]=abs($rsdb[money])&&!in_array($fidDB[M_keyword],array('mv','download')) ){
		if(!$lfjuid)
		{
			showerr("���ȵ�¼,��Ҫ֧��{$rsdb[money]}{$webdb[MoneyName]}���ܲ鿴");
		}
		elseif($web_admin)
		{
			 Remind_msg("�����������շ�,��Ϊ���ǹ���Ա,���Կ��Բ鿴,�������ǲ��ܲ鿴��");
		}
		elseif($lfjuid==$rsdb[uid])
		{
			 Remind_msg("�����������շ�,��Ϊ���Ƿ�����,���Կ��Բ鿴,�������ǲ��ܲ鿴��");
		}
		elseif( !strstr($rsdb[buyuser],",$lfjid,") )
		{
			$lfjdb[money]=get_money($lfjuid);
			if($lfjdb[money]<$rsdb[money])
			{
				showerr("���{$webdb[MoneyName]}����$rsdb[money]");
			}
			elseif($buy==1)
			{
				add_user($lfjuid,"-$rsdb[money]");
				add_user($rsdb[uid],"$rsdb[money]");
				$rsdb[buyuser]=$rsdb[buyuser]?",{$lfjid}{$rsdb[buyuser]}":",$lfjid,";
				$erp=get_id_table($id);
				$db->query("UPDATE {$pre}article$erp SET buyuser='$rsdb[buyuser]' WHERE aid=$id");
				refreshto("?fid=$fid&id=$id","����ɹ�,��ո�������{$webdb[MoneyName]}{$rsdb[money]}{$webdb[MoneyDW]}",3);
			}
			else
			{
				showerr("����Ҫ����{$webdb[MoneyName]}{$rsdb[money]}{$webdb[MoneyDW]}����Ȩ�޲鿴,�Ƿ����<br><br>[<A HREF='?fid=$fid&id=$id&buy=1'>��Ҫ����</A>]");
			}
		}
	}
}
?>