<?php
!function_exists('html') && exit('ERR');
if($job=="add"&&$Apower[channel_list])
{
	$rsdb[phpname]='index.php';
	$rsdb[htmlname]="index_$timestamp.htm";
	$style_select=select_style('postdb[style]','');
	$sort_fid=$Guidedb->Checkbox("{$pre}sort","fids[]",$rsdb[fids]);

	$tpl_head=select_template("",7,$rsdb[head_tpl]);
	$tpl_head=str_replace("<select","<select onChange='get_obj(\"head_tpl\").value=this.options[this.selectedIndex].value;'",$tpl_head);

	$tpl_foot=select_template("",8,$rsdb[foot_tpl]);
	$tpl_foot=str_replace("<select","<select onChange='get_obj(\"foot_tpl\").value=this.options[this.selectedIndex].value;'",$tpl_foot);

	$tpl_main=select_template("",1,$rsdb[main_tpl]);
	$tpl_main=str_replace("<select","<select onChange='get_obj(\"main_tpl\").value=this.options[this.selectedIndex].value;'",$tpl_main);

	//$tpl_head=select_template("postdb[head_tpl]",7,$rsdb[head_tpl]);
	//$tpl_main=select_template("postdb[main_tpl]",1,$rsdb[main_tpl]);
	//$tpl_foot=select_template("postdb[foot_tpl]",8,$rsdb[foot_tpl]);

	$style_select=select_style('postdb[style]',$rsdb[style]);
	require(dirname(__FILE__)."/"."head.php");
	require(dirname(__FILE__)."/"."template/channel/menu.htm");
	require(dirname(__FILE__)."/"."template/channel/edit.htm");
	require(dirname(__FILE__)."/"."foot.php");
}
elseif($job=='autoadd'&&$Apower[channel_list])
{
	require(dirname(__FILE__)."/"."head.php");
	require(dirname(__FILE__)."/"."template/channel/menu.htm");
	require(dirname(__FILE__)."/"."template/channel/autoadd.htm");
	require(dirname(__FILE__)."/"."foot.php");
}
elseif($action=='autoadd'&&$Apower[channel_list])
{
	if(is_dir(PHP168_PATH.$postdb[path])){
		showmsg("$postdb[path],此目录已经存在了.请换一个吧");
	}
	makepath(PHP168_PATH.$postdb[path]);
	$postdb[phpname]="$postdb[path]/index.php";
	$postdb[htmlname]="$postdb[path]/index.htm";

	$db->query("INSERT INTO `{$pre}channel` ( `type`, `name`, `path`, `phpname`, `htmlname`, `fids`, `style`, `head_tpl`, `main_tpl`, `foot_tpl`, `url`, `logo`, `descrip`, `admin`, `list`,config) VALUES ('1','$postdb[name]','$postdb[path]','$postdb[phpname]','$postdb[htmlname]','$postdb[fids]','$postdb[style]','$postdb[head_tpl]','$postdb[main_tpl]','$postdb[foot_tpl]','$postdb[url]','$postdb[logo]','$postdb[descrip]','$postdb[admin]','$postdb[list]','$config')");

	$rs=$db->get_one("SELECT * FROM `{$pre}channel` ORDER BY id DESC LIMIT 1");
	
	$paths='';
	$detail=explode("/",$postdb[path]);
	foreach( $detail AS $key=>$value){
		if($value){
			$paths.='../';
		}
	}

	write_file(PHP168_PATH."$postdb[path]/index.php","<?php
require_once(\"global.php\");
require_once(THIS_PATH.\"index.php\");
");

	write_file(PHP168_PATH."$postdb[path]/list.php","<?php
require_once(\"global.php\");
require_once(THIS_PATH.\"list.php\");
	");

	write_file(PHP168_PATH."$postdb[path]/bencandy.php","<?php
require_once(\"global.php\");
require_once(THIS_PATH.\"bencandy.php\");
	");

	write_file(PHP168_PATH."$postdb[path]/global.php","<?php
defined(\"THIS_PATH\") || define(\"THIS_PATH\",\"$paths\");
\$ch=$rs[id];
require_once(THIS_PATH.\"global.php\");	
	");

	jump("点击进行下一步,进行详细设置","index.php?lfj=channel&job=edit&id=$rs[id]",1);
}
elseif($job=="edit"&&$Apower[channel_list])
{
	$rsdb=$db->get_one(" SELECT * FROM `{$pre}channel` WHERE id='$id' ");
	$style_select=select_style('postdb[style]',$rsdb[style]);
	$fiddb=explode(",",$rsdb[fids]);
	$sort_fid=$Guidedb->Checkbox("{$pre}sort","fids[]",$fiddb);
	$style_select=select_style('postdb[style]',$rsdb[style]);
	@extract(unserialize($rsdb[config]));
	$orderdb["$order"]=' selected ';

	$fid_str="'".implode("','",$fiddb)."'";

	$query = $db->query("SELECT * FROM {$pre}sort WHERE fid IN ($fid_str)");
	while($rs = $db->fetch_array($query)){
		$fid_name_db[$rs[fid]]="【<A HREF='$webdb[www_url]/list.php?fid=$rs[fid]' target=_blank>$rs[name]</A>】,";
	}
	foreach( $fiddb AS $key=>$value){
		$fid_name.=$fid_name_db[$value];
	}
	//$tpl_head=select_template("postdb[head_tpl]",7,$rsdb[head_tpl]);
	//$tpl_main=select_template("postdb[main_tpl]",1,$rsdb[main_tpl]);
	//$tpl_foot=select_template("postdb[foot_tpl]",8,$rsdb[foot_tpl]);
	$tpl_head=select_template("",7,$rsdb[head_tpl]);
	$tpl_head=str_replace("<select","<select onChange='get_obj(\"head_tpl\").value=this.options[this.selectedIndex].value;'",$tpl_head);

	$tpl_foot=select_template("",8,$rsdb[foot_tpl]);
	$tpl_foot=str_replace("<select","<select onChange='get_obj(\"foot_tpl\").value=this.options[this.selectedIndex].value;'",$tpl_foot);

	$tpl_main=select_template("",1,$rsdb[main_tpl]);
	$tpl_main=str_replace("<select","<select onChange='get_obj(\"main_tpl\").value=this.options[this.selectedIndex].value;'",$tpl_main);

	require(dirname(__FILE__)."/"."head.php");
	require(dirname(__FILE__)."/"."template/channel/menu.htm");
	require(dirname(__FILE__)."/"."template/channel/edit.htm");
	require(dirname(__FILE__)."/"."foot.php");
}
elseif($action=="add"&&$Apower[channel_list])
{

	foreach($fids AS $key=>$fid){
		if(!$fid){
			unset($fids[$key]);
		}
	}
	$postdb[fids]=implode(",",$fids);
	unset($con);
	$con[line]=$atc_line;
	$con[rows]=$atc_rows;
	$con[leng]=$atc_leng;
	$con[order]=$atc_order;
	$con[fid_list]=$atc_fid;
	$config=addslashes(serialize($con));
	$db->query("INSERT INTO `{$pre}channel` ( `sort`, `name`, `path`, `phpname`, `htmlname`, `fids`, `style`, `head_tpl`, `main_tpl`, `foot_tpl`, `url`, `logo`, `descrip`, `admin`, `list`,config) VALUES ('$postdb[sort]','$postdb[name]','$postdb[path]','$postdb[phpname]','$postdb[htmlname]','$postdb[fids]','$postdb[style]','$postdb[head_tpl]','$postdb[main_tpl]','$postdb[foot_tpl]','$postdb[url]','$postdb[logo]','$postdb[descrip]','$postdb[admin]','$postdb[list]','$config')");
	jump("添加成功","index.php?lfj=$lfj&job=list",1);
}
elseif($action=="edit"&&$Apower[channel_list])
{
	$rsdb=$db->get_one(" SELECT * FROM `{$pre}channel` WHERE id='$id' ");
	$old_fid=explode(",",$rsdb[fids]);
	foreach( $old_fid AS $key=>$value){
		$oo_fid[$value]=$key+100;
	}
	
	foreach($fids AS $key=>$fid){
		if(!$fid)
		{
			unset($fids[$key]);
		}
		if($oo_fid[$fid])
		{
			unset($fids[$key]);
			$fids["$oo_fid[$fid]"]=$fid;
		}
	}
	ksort($fids);
	$postdb[fids]=implode(",",$fids);
	unset($con);
	$con[line]=$atc_line;
	$con[rows]=$atc_rows;
	$con[leng]=$atc_leng;
	$con[order]=$atc_order;
	$con[fid_list]=$atc_fid;
	$config=addslashes(serialize($con));
	$db->query("UPDATE `{$pre}channel` SET sort='$postdb[sort]',name='$postdb[name]',path='$postdb[path]',phpname='$postdb[phpname]',htmlname='$postdb[htmlname]',fids='$postdb[fids]',style='$postdb[style]',head_tpl='$postdb[head_tpl]',main_tpl='$postdb[main_tpl]',foot_tpl='$postdb[foot_tpl]',url='$postdb[url]',logo='$postdb[logo]',descrip='$postdb[descrip]',admin='$postdb[admin]',list='$postdb[list]',config='$config' WHERE id='$id' ");
	jump("修改成功","$FROMURL",1);
}
elseif($action=="delete"&&$Apower[channel_list])
{
	if($id==1){
		showmsg("你不能删除主页频道");
	}else{
		$db->query("DELETE FROM `{$pre}channel` WHERE id='$id' ");
		jump("删除成功","$FROMURL",1);
	}
}
elseif($job=="list"&&$Apower[channel_list])
{
	$query=$db->query("SELECT * FROM `{$pre}channel`");
	while($rs=$db->fetch_array($query)){
		if(!$rs[url]){
			$rs[c_type]="内部频道";
		}else{
			$rs[c_type]="<A HREF='$rs[url]' target='_blank'>外部频道</A>";
		}
		if(!$rs[phpname]){
			$rs[phpname]="index.php";
		}
		$listdb[]=$rs;
	}

	require(dirname(__FILE__)."/"."head.php");
	require(dirname(__FILE__)."/"."template/channel/menu.htm");
	require(dirname(__FILE__)."/"."template/channel/list.htm");
	require(dirname(__FILE__)."/"."foot.php");
}
elseif($job=="list_fid")
{
	$sortdb=array();
	if( count($Fid_db[name])>100||$fid ){
		$rows=50;
		$page<1 && $page=1;
		$min=($page-1)*$rows;
		$showpage=getpage("{$pre}sort","WHERE fup='$fid'","index.php?lfj=$lfj&job=$job&only=$only&mid=$mid&fid=$fid",$rows);
		$query = $db->query("SELECT * FROM {$pre}sort WHERE fup='$fid' ORDER BY list DESC,fid ASC LIMIT $min,$rows");
		while($rs = $db->fetch_array($query)){
			//if(!$rs[type]){
			//	$erp=$Fid_db[iftable][$rs[fid]];
			//	@extract($db->get_one("SELECT COUNT(*) AS NUM FROM {$pre}article$erp WHERE fid='$rs[fid]'"));
			//	$rs[NUM]=intval($NUM);
			//}
			$sortdb[]=$rs;
		}
		if($fid){
			$show_guide="<A HREF='index.php?lfj=$lfj&job=$job&only=$only&mid=$mid'>返回顶级目录</A> ".list_sort_guide($fid);
		}
	}else{		
		list_allsort($fid,'sort',1);
	}
	
	$article_show_step[$webdb[labelsort_show_step]]='red;';
	require(dirname(__FILE__)."/"."head.php");
	require(dirname(__FILE__)."/"."template/channel/list_fid.htm");
	require(dirname(__FILE__)."/"."foot.php");
}
elseif($action=="order"&&$Apower[channel_list])
{
	asort($fiddb);
	$keyfid=array_keys($fiddb);
	$postdb[fids]=implode(",",$keyfid);
	unset($con);
	$con[rows]=$atc_rows;
	$con[leng]=$atc_leng;
	$con[order]=$atc_order;

	$config=addslashes(serialize($con));
	$db->query("UPDATE `{$pre}channel` SET fids='$postdb[fids]',config='$config' WHERE id='$id' ");

	jump("修改成功","$FROMURL",0);
}
elseif($job=="order"&&$Apower[channel_list])
{
	$rsdb=$db->get_one(" SELECT * FROM `{$pre}channel` WHERE id='$id' ");
	@extract(unserialize($rsdb[config]));
	
	$orderdb["$order"]=' selected ';

	$fiddb=explode(",",$rsdb[fids]);
	$fid_str="'".implode("','",$fiddb)."'";

	$query = $db->query("SELECT * FROM {$pre}sort WHERE fid IN ($fid_str)");
	while($rs = $db->fetch_array($query)){
		$list_db[$rs[fid]]=$rs;
	}
	foreach( $fiddb AS $key=>$fid){
		$listdb[]=$list_db[$fid];
	}
	require(dirname(__FILE__)."/"."head.php");
	require(dirname(__FILE__)."/"."template/channel/menu.htm");
	require(dirname(__FILE__)."/"."template/channel/order.htm");
	require(dirname(__FILE__)."/"."foot.php");
}
elseif($action=='updatelabel')
{
	if($label_type=='list')
	{
		if(!$fid)
		{
			showmsg("请选择一个栏目");
		}
		
		if($step==2)
		{
			$fidDB=$db->get_one("SELECT * FROM {$pre}sort WHERE fid='$fid'");
			$config=unserialize($fidDB[config]);
			$config[label_list]=$fup;
			$db->query("UPDATE {$pre}sort SET config='".addslashes(serialize($config))."' WHERE fid='$fid' ");
			header("location:$webdb[www_url]/list.php?fid=$fid&jobs=show");
			exit;
		}
		else
		{
			$fidDB=$db->get_one("SELECT * FROM {$pre}sort WHERE fid='$fid'");
			$fidDB[config]=unserialize($fidDB[config]);

			$sort_fup=$Guidedb->Select("{$pre}sort","fup",$fidDB[config][label_list]);

			require(dirname(__FILE__)."/"."head.php");
			require(dirname(__FILE__)."/"."template/channel/choose.htm");
			require(dirname(__FILE__)."/"."foot.php");
		}
	}
	if($label_type=='bencandy')
	{
		if($step==2)
		{
			if(!$aid){
				showmsg("ID不存在,请先在本栏目发表一篇文章,再设置标签");
			}
			
			//处理用户自定义ID后.不一定是原来的栏目.
			$erp=get_id_table($aid);
			@extract($db->get_one("SELECT fid FROM {$pre}article$erp WHERE aid='$aid'"));

			$fidDB=$db->get_one("SELECT * FROM {$pre}sort WHERE fid='$fid'");
			$config=unserialize($fidDB[config]);
			$config[label_bencandy]=$fup;
			$db->query("UPDATE {$pre}sort SET config='".addslashes(serialize($config))."' WHERE fid='$fid' ");
			header("location:$webdb[www_url]/bencandy.php?fid=$fid&aid=$aid&jobs=show");
			exit;
		}
		else
		{
			$erp=$Fid_db[iftable][$fid];
			@extract($db->get_one("SELECT aid FROM {$pre}article$erp WHERE fid='$fid' ORDER BY aid DESC LIMIT 1"));

			$fidDB=$db->get_one("SELECT * FROM `{$pre}sort` WHERE fid='$fid'");
			$fidDB[config]=unserialize($fidDB[config]);

			$sort_fup=$Guidedb->Select("{$pre}sort","fup",$fidDB[config][label_bencandy]);

			require(dirname(__FILE__)."/"."head.php");
			require(dirname(__FILE__)."/"."template/channel/choose.htm");
			require(dirname(__FILE__)."/"."foot.php");
		}
	}
}

elseif($job=='showsort')
{
	unset($webdbs);
	$webdbs[labelsort_show_step]=$step;
	write_config_cache($webdbs);
	jump("修改成功",$FROMURL,0);
}

function list_sort_guide($fup){
	global $db,$pre,$mid,$only,$lfj,$job;
	$rs=$db->get_one("SELECT fup,name FROM {$pre}sort WHERE fid='$fup'");
	if($rs){
		$show=" -> <A HREF='index.php?lfj=$lfj&job=$job&only=$only&mid=$mid&fid=$fup'>$rs[name]</A> ";
		$show=list_sort_guide($rs[fup]).$show;
	}
	return $show;
}
?>