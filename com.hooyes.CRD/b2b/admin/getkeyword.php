<?php
!function_exists('html') && exit('ERR');
require_once(PHP168_PATH."inc/artic_function.php");

if($job=="list"&&$Apower[getkeyword_do])
{
	if(!$page){
		$page=1;
	}
	$rows=50;
	$min=($page-1)*$rows;
	$showpage=getpage("{$pre}keyword","","?lfj=$lfj&job=$job",$rows);
	$query = $db->query("SELECT * FROM {$pre}keyword ORDER BY num DESC LIMIT $min,$rows");
	while($rs = $db->fetch_array($query)){
		if($rs[ifhide]){
			$rs[ifshow]='����ʾ';
		}else{
			$rs[ifshow]='��ʾ';
		}
		if($rs[url]){
			$rs[ifurl]='��';
		}else{
			$rs[ifurl]='��';
		}
		$listdb[]=$rs;
	}
	$ifShowKeyword[intval($webdb[ifShowKeyword])]=' checked ';
	require(dirname(__FILE__)."/"."head.php");
	require(dirname(__FILE__)."/"."template/getkeyword/menu.htm");
	require(dirname(__FILE__)."/"."template/getkeyword/list.htm");
	require(dirname(__FILE__)."/"."foot.php");
}
elseif($action=="set"&&$Apower[getkeyword_do])
{
	write_config_cache($webdbs);
	jump("�޸ĳɹ�",$FROMURL);
}
elseif($action=="del"&&$Apower[getkeyword_do])
{
	foreach( $iddb AS $key=>$value){
		$db->query("DELETE FROM {$pre}keyword WHERE id='$value'");
		$db->query("DELETE FROM {$pre}keywordid WHERE id='$value'");
	}
	write_keyword_cache();
	jump("ɾ���ɹ�",$FROMURL,0);
}
elseif($job=="add"&&$Apower[getkeyword_do])
{
	$rsdb['list']=0;
	require(dirname(__FILE__)."/"."head.php");
	require(dirname(__FILE__)."/"."template/getkeyword/menu.htm");
	require(dirname(__FILE__)."/"."template/getkeyword/edit.htm");
	require(dirname(__FILE__)."/"."foot.php");
}
elseif($action=="add"&&$Apower[getkeyword_do])
{
	if($url&&!strstr($url,'://')){
		$url="http://$url";
	}
	$db->query("INSERT INTO `{$pre}keyword` (`keywords` , `list`,`url`,`ifhide` ) VALUES ( '$keywords', '$list','$url','$ifhide')");
	write_keyword_cache();
	jump("��ӳɹ�","index.php?lfj=$lfj&job=list",1);
}
elseif($job=="edit"&&$Apower[getkeyword_do])
{
	$rsdb=$db->get_one("SELECT * FROM {$pre}keyword WHERE id='$id'");
	$ifhide[intval($rsdb[ifhide])]=' checked ';
	require(dirname(__FILE__)."/"."head.php");
	require(dirname(__FILE__)."/"."template/getkeyword/menu.htm");
	require(dirname(__FILE__)."/"."template/getkeyword/edit.htm");
	require(dirname(__FILE__)."/"."foot.php");
}
elseif($action=="edit"&&$Apower[getkeyword_do])
{
	if($url&&!strstr($url,'://')){
		$url="http://$url";
	}
	$db->query("UPDATE `{$pre}keyword` SET `keywords`='$keywords',`num`='$num',`url`='$url',`ifhide`='$ifhide' WHERE id='$id'");
	write_keyword_cache();
	jump("�޸ĳɹ�","$FROMURL",1);
}
elseif($job=="get"&&$Apower[getkeyword_do])
{
	$fid=intval($fid);

	$sortdb=array();
	list_2allsort($fid,"sort");

	$list_record=read_file(PHP168_PATH."cache/makelist_record.php");
	$show_record=read_file(PHP168_PATH."cache/makeShow_record.php");
	$record='';
	if($list_record){
		$record.="<li><A HREF='../do/list_html.php$list_record' style='color:red;font-size:18px;font-weight:bold;'>�б�ҳ���ɾ�̬,���жϹ�,������������</A></li>";
	}
	if($show_record){
		$record.="<li><A HREF='../do/bencandy_html.php$show_record' style='color:red;font-size:18px;font-weight:bold;'>����ҳ���ɾ�̬,���жϹ�,������������</A></li>";
	}

	if($fid){
		$rsdb=$db->get_one(" SELECT * FROM {$pre}sort WHERE fid='$fid' ");
	}
	//$sort_fup=$Guidedb->Select("{$pre}sort","fup",$fid);

	require(dirname(__FILE__)."/"."head.php");
	//require(dirname(__FILE__)."/"."template/getkeyword/menu.htm");
	require(dirname(__FILE__)."/"."template/getkeyword/sort.htm");
	require(dirname(__FILE__)."/"."foot.php");
}
elseif($action=="getword"&&$Apower[getkeyword_do])
{
	//require_once(PHP168_PATH."inc/splitword.php");
	if(!$fiddb&&!$iii&&$page<2){
		showerr("��ѡ��һ����Ŀ");
	}
	if($maketype=="all")
	{
		$beginTime=$endTime=$beginId=$endId='';
	}
	 
	$SQL=" ";

	if($beginTime){
		$_beginTime=preg_replace("/([\d]+)-([\d]+)-([\d]+) ([\d]+):([\d]+):([\d]+)/eis","mk_time('\\4','\\5', '\\6', '\\2', '\\3', '\\1')",$beginTime);
		$SQL.=" AND posttime>$_beginTime";
	}

	if($endTime){
		$_endTime=preg_replace("/([\d]+)-([\d]+)-([\d]+) ([\d]+):([\d]+):([\d]+)/eis","mk_time('\\4','\\5', '\\6', '\\2', '\\3', '\\1')",$endTime);
		$SQL.=" AND posttime<$_endTime";
	}

	if(is_numeric($beginId)){
		$SQL.=" AND aid>$beginId ";
	}
	if(is_numeric($endId)){
		$SQL.=" AND aid<$endId ";
	}
	$iii=intval($iii);
	if($iii==0&&$page<2)
	{
		$allfid=implode(",",$fiddb);
		//write_file("../cache/makeShow0.php",$allfid);
	}
	else
	{
		//$allfid=read_file("../cache/makeShow0.php");
		$fiddb=explode(",",$allfid);
	}
	if(!$page)
	{
		$page=1;
	}
	$rows=500;
	$min=($page-1)*$rows;
	if($fid=$fiddb[$iii])
	{
		$ck=$ids='';
		$erp=$Fid_db[iftable][$fid];
		$query = $db->query("SELECT title,keywords,aid AS id FROM {$pre}article$erp WHERE fid=$fid $SQL LIMIT $min,$rows");
		while($rs = $db->fetch_array($query))
		{
			$ck++;
			if(!$rs[keywords])
			{
				/*
				$keywords=splitword($rs[title]);
				$detail=explode(" ",$keywords);
				foreach( $detail AS $key=>$value){
					if(strlen($value)<4){
						unset($detail[$key]);
					}
				}
				$keywords=implode(" ",$detail);
				$keywords=addslashes($keywords);
				*/
				$keywords=keyword_ck('',$rs[title]);
				$keywords=addslashes($keywords);
				$db->query("UPDATE {$pre}article$erp SET keywords='$keywords' WHERE aid='$rs[id]'");
				$num++;
			}
		}

		if($ck)
		{
			$page++;
		}
		else
		{
			$iii++;
			$page=0;
		}
		echo "���Ժ�,���ڴ�����...<br>&page=$page&iii=$iii<META HTTP-EQUIV=REFRESH CONTENT='0;URL=?lfj=$lfj&action=$action&maketype=$maketype&page=$page&iii=$iii&allfid=$allfid&num=$num&beginTime=$beginTime&endTime=$endTime&beginId=$beginId&endId=$endId'>";
		exit;
	}
	jump("��ȡ���,����ȡ��{$num}ƪ����","index.php?lfj=getkeyword&job=list",10);
}



/*��Ŀ�б�*/
function list_2allsort($fid,$table='sort'){
	global $db,$pre,$sortdb,$webdb;
	$query=$db->query("SELECT * FROM {$pre}$table WHERE fup='$fid' ORDER BY list DESC");
	while( $rs=$db->fetch_array($query) ){
		$icon="";
		for($i=1;$i<$rs['class'];$i++){
			$icon.="&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;";
		}
		if($icon){
			$icon=substr($icon,0,-24);
			$icon.="--";
		}
		if($rs[list_html]){
			$rs[filename]=$rs[list_html];
		}else{
			$rs[filename]=$webdb[list_filename];
		}
		$rs[filename]=preg_replace("/(.*)\/([^\/]+)/is","\\1/",$rs[filename]);
		$fid=$rs[fid];
		eval("\$rs[filename]=\"$rs[filename]\";");
		$rs[config]=unserialize($rs[config]);
		$rs[icon]=$icon;
		$sortdb[]=$rs;

		list_2allsort($rs[fid],$table);
	}
}
?>