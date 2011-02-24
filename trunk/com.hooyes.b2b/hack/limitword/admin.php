<?php
!function_exists('html') && exit('ERR');
if($job=="list"&&$Apower[limitword_list])
{
	!$page&&$page=1;
	$rows=20;
	$min=($page-1)*$rows;
	
	$showpage=getpage("{$pre}limitword","","index.php?lfj=$lfj&job=$job",$rows);
	$query=$db->query(" SELECT * FROM {$pre}limitword ORDER BY id DESC LIMIT $min,$rows ");
	while($rs=$db->fetch_array($query)){
		$listdb[]=$rs;
	}
	$kill_badword[intval($webdb[kill_badword])]=' checked ';

	hack_admin_tpl('list');
}
elseif($action=="set"&&$Apower[limitword_list])
{
	write_config_cache($webdbs);
	jump("设置成功","$FROMURL",1);
}
elseif($action=="delete"&&$Apower[limitword_list])
{
	if(!$iddb){
		showmsg("请选择一个");
	}
	foreach($iddb AS $key=>$rs){
		$db->query("DELETE FROM {$pre}limitword WHERE id='$key'");
	}
	jump("删除成功","$FROMURL",1);
}
elseif($action=="addall"&&$Apower[limitword_list])
{
	if(!$oldword){
		showmsg("旧字符不能为空");
	}
	$oldword=str_replace('"','',$oldword);
	$oldword=str_replace("'","",$oldword);
	$newword=str_replace('"','',$newword);
	$newword=str_replace("'","",$newword);
	$detail=explode("\r\n",$oldword);
	$detail2=explode("\r\n",$newword);
	foreach( $detail AS $key=>$value){
		if($value&&!$rs=$db->get_one("SELECT * FROM `{$pre}limitword` WHERE `oldword` ='$value' "))
		{
			$detail2[$key] || $detail2[$key]=$dword;
			$db->query("INSERT INTO {$pre}limitword SET newword='{$detail2[$key]}',oldword='$value' ");
		}
	}
	write_limitword_cache();
	jump("添加成功","index.php?lfj=limitword&job=list",1);
}
elseif($action=="add"&&$Apower[limitword_list])
{
	if(!$oldword){
		showmsg("旧字符不能为空");
	}
	$db->query("INSERT INTO {$pre}limitword SET newword='$newword',oldword='$oldword' ");
	write_limitword_cache();
	jump("添加成功","index.php?lfj=limitword&job=list",1);
}
elseif($action=="edit"&&$Apower[limitword_list])
{
	$db->query("UPDATE {$pre}limitword SET newword='$newword',oldword='$oldword' WHERE id='$id' ");
	write_limitword_cache();
	jump("修改成功","$FROMURL",1);
}
elseif($job=="edit"&&$Apower[limitword_list])
{
	$rsdb=$db->get_one(" SELECT * FROM {$pre}limitword WHERE id='$id' ");


	hack_admin_tpl('edit');
}
elseif($job=="add"&&$Apower[limitword_list])
{

	hack_admin_tpl('edit');
}
elseif($job=="addall"&&$Apower[limitword_list])
{
	hack_admin_tpl('addall');
}
elseif($job=="replace"&&$Apower[limitword_replace])
{

	hack_admin_tpl('replace',false);
}
elseif($action=="replace"&&$Apower[limitword_replace])
{
	if(!$field_db){
		showmsg("请选择一个要替换的项目");
	}
	if(!$oldword){
		showmsg("原字符不能为空");
	}
	if($page<1){
		$page=1;
	}
	$rows=500;
	$min=($page-1)*$rows;
	$ck=0;
	$id='id';
	if($Table=="article"){
		$id='aid';
	}
	$query = $db->query("SELECT * FROM {$pre}{$Table} ORDER BY $id ASC LIMIT $min,$rows");
	while($rs = $db->fetch_array($query)){
		$ck++;
		$SQL='';
		foreach( $field_db AS $key=>$field){
			if($rs[$field]){
				$value=str_replace($oldword,$newword,$rs[$field]);
				$value=addslashes($value);
				$SQL[]="$field='$value'";
			}
		}
		if($SQL){
			$cknum++;
			$_SQL=implode(",",$SQL);
			$db->query("UPDATE {$pre}{$Table} SET $_SQL WHERE $id='{$rs[$id]}'");
		}
	}

	$query = $db->query("SELECT * FROM {$pre}reply ORDER BY rid ASC LIMIT $min,$rows");
	while($rs = $db->fetch_array($query)){
		$ck++;
		$SQL='';
		foreach( $field_db AS $key=>$field){
			if($rs[$field]){
				$value=str_replace($oldword,$newword,$rs[$field]);
				$value=addslashes($value);
				$SQL[]="$field='$value'";
			}
		}
		if($SQL){
			$cknum++;
			$_SQL=implode(",",$SQL);
			$db->query("UPDATE {$pre}reply SET $_SQL WHERE rid='{$rs[rid]}'");
		}
	}

	if($ck){
		$page++;
		$oldword=urlencode($oldword);
		$newword=urlencode($newword);
		foreach( $field_db AS $key=>$value){
			$field_str.="&field_db[]=$value";
		}
		echo "lfj=$lfj&action=$action&oldword=$oldword&newword=$newword&Table=$Table$field_str&page=$page<META HTTP-EQUIV=REFRESH CONTENT='0;URL=?lfj=$lfj&action=$action&oldword=$oldword&newword=$newword&Table=$Table$field_str&page=$page&cknum=$cknum'>";
		exit;
	}else{
		jump("处理完毕,成功替换 {$cknum} 处","?lfj=$lfj&job=replace",5);
	}
}


?>