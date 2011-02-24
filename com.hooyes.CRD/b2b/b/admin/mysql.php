<?php
require_once("global.php");
@set_time_limit(0);
$db->query("SET SQL_QUOTE_SHOW_CREATE = 0");
error_reporting(7);
/**
*�г����ݱ�
**/
if($job=='out'){
	$query=$db->query("SHOW TABLE STATUS");
	while( $array=$db->fetch_array($query) ){
		if($choose!='all'){
			if($choose=='out'){
				if(ereg("^($pre)",$array[Name])){
					continue;
				}
			}else{
				if(!ereg("^($_pre)",$array[Name])){
					continue;
				}
			}
		}
		$j++;
		$totalsize=$totalsize+$array['Data_length'];
		$array['Data_length']=number_format($array['Data_length']/1024,3);
		$array[j]=$j;
		$listdb[]=$array;
	}
	$totalsize=number_format($totalsize/(1024*1024),3);
	if(file_exists(PHP168_PATH."cache/bak_mysql.txt"))
	{
		$breakbak=read_file(PHP168_PATH."cache/bak_mysql.txt");
	}
	require(dirname(__FILE__)."/"."head.php");
	require(dirname(__FILE__)."/"."template/mysql/menu.htm");
	require(dirname(__FILE__)."/"."template/mysql/out.htm");
	require(dirname(__FILE__)."/"."foot.php");
	
}
//���ݿ��Ż����޸�
elseif($job=='do'){
	if($step=='yh'){
		$db->query("OPTIMIZE TABLE `$table`");
	}elseif($step=='xf'){
		$db->query("REPAIR TABLE `$table`");
	}
	refreshto($FROMURL,"�����ɹ����������",1);
}

/**
*������������
**/
elseif($action=='out'){
	if(!is_dir(Adminpath."../cache/mysql_bak")){
		mkdir(Adminpath."../cache/mysql_bak");
		chmod(Adminpath."../cache/mysql_bak",0777);
	}
	if(!$tabledb&&!$tabledbreto){
		showerr('��ѡ��һ�����ݱ�');
	}
	if(!$tabledb&&$tabledbreto){
		$detail=explode("|",$tabledbreto);
		$num=count($detail);
		for($i=0;$i<$num-1;$i++){
			$tabledb[]=$detail[$i];
		}
	}
	$rsdb=bak_out($tabledb);
	require(dirname(__FILE__)."/"."head.php");
	require(dirname(__FILE__)."/"."template/mysql/menu.htm");
	require(dirname(__FILE__)."/"."template/mysql/outaction.htm");
	require(dirname(__FILE__)."/"."foot.php");
}

/**
*ѡ��Ҫ���뻹ԭ������
**/
elseif($job=='into'){
	$selectname=bak_time();
	if(file_exists(PHP168_PATH."cache/mysql_insert.txt")){
		echo "<CENTER><table><tr bgcolor=#FF0000><td colspan=5 height=30><div align=center><A HREF=".read_file(PHP168_PATH."cache/mysql_insert.txt")."><b><font color=ffffff>�ϴλ�ԭ���ݱ��ж��Ƿ����,�������</font></b></A></div></td></tr></table></CENTER>";
	}
	require(dirname(__FILE__)."/"."head.php");
	require(dirname(__FILE__)."/"."template/mysql/menu.htm");
	require(dirname(__FILE__)."/"."template/mysql/into.htm");
	require(dirname(__FILE__)."/"."foot.php");
}

/**
*�����뻹ԭ����
**/
elseif($action=='into')
{
	bak_into();
}

/**
*ѡ��Ҫɾ���ı�������
**/
elseif($job=='del'){
	$selectname=bak_time();
	require(dirname(__FILE__)."/"."head.php");
	require(dirname(__FILE__)."/"."template/mysql/menu.htm");
	require(dirname(__FILE__)."/"."template/mysql/del.htm");
	require(dirname(__FILE__)."/"."foot.php");
}

/**
*ɾ��ѡ���ı�������
**/
elseif($action=='del'){
	if(!$baktime){
		showerr('��ѡ��һ��');
	}
	del_file(Adminpath."../cache/mysql_bak/$baktime");
	if(!is_dir(Adminpath."../cache/mysql_bak/$baktime")){
		refreshto("?lfj=mysql&job=del","����ɾ���ɹ�",5);
	}else{
		refreshto("?lfj=mysql&job=del","����ɾ��ʧ��,��ȷ��Ŀ¼����Ϊ0777",5);
	}
}


/**
*�ӱ����ϴ�SQL�ı���������
**/
elseif($job=='sql'&&$Apower[mysql_sql]){
	require(dirname(__FILE__)."/"."head.php");
	require(dirname(__FILE__)."/"."template/mysql/menu.htm");
	require(dirname(__FILE__)."/"."template/mysql/sql.htm");
	require(dirname(__FILE__)."/"."foot.php");
}

/**
*�������ϴ���SQL����
**/
elseif($action=='sql'&&$Apower[mysql_sql]){
	if($t==2){
		$sqlfile=PHP168_PATH."$webdb[updir]/$upsql";
		$db->insert_file($sqlfile);
		@unlink($sqlfile);
	}elseif($t==1){
		$sql=StripSlashes($sql);
		write_file(PHP168_PATH."cache/$timestamp.sql",$sql);
		$db->insert_file(PHP168_PATH."cache/$timestamp.sql");
		unlink(PHP168_PATH."cache/$timestamp.sql");
		//$db->query("$sql");
	}
	refreshto("?lfj=mysql&job=sql","�����ҳ�Ϸ�û�������룬������ɹ�",10);
	
}

function show_field($table){
	global $db;
	$query=$db->query(" SELECT * FROM $table limit 0,1");
	$num=mysql_num_fields($query);
	for($i=0;$i<$num;$i++){
		$f_db=mysql_fetch_field($query,$i);
		$field=$f_db->name;
		$show.="`$field`,";
	}
	$show.=")";
	$show=str_replace(",)","",$show);
	return $show;
}


function create_table($table){
	global $db,$repair,$mysqlversion,$Charset,$baktype;
	$show="DROP TABLE IF EXISTS $table;\n";
	if($repair){
		$db->query("OPTIMIZE TABLE `$table`");
	}
	$array=$db->get_one("SHOW CREATE TABLE $table");

	if(!$mysqlversion){
		$show.=$array['Create Table'].";\n\n";
		return $show;
	}

	$array['Create Table']=preg_replace("/DEFAULT CHARSET=([0-9a-z]+)/is","",$array['Create Table']);

	if($mysqlversion=='new'&&$baktype!='s'){
		$Charset || $Charset='latin1';
		$array['Create Table'].="DEFAULT CHARSET=$Charset";
	}
	$show.=$array['Create Table'].";\n\n";
	return $show;
}


function bak_table($table,$start=0,$row=3000){
	global $db;
	$limit=" limit $start,$row ";
	//$field=show_field($table);
	$query=$db->query(" SELECT * FROM $table $limit ");
	$num=mysql_num_fields($query);
	while ($array=mysql_fetch_row($query)){
		$rows='';
		for($i=0;$i<$num;$i++){
			$rows.="'".mysql_escape_string($array[$i])."',";
		}
		$rows.=")";
		$rows=str_replace(",)","",$rows);
		//$show.="INSERT INTO `$table` ($field) VALUES ($rows);\n";
		$show.="INSERT INTO `$table` VALUES ($rows);\n";
	}
	return $show;
}


function create_table_all($tabledb){
	global $db,$pre,$_pre,$baktype,$webdb;

	foreach($tabledb as $table){
		$show.=create_table($table)."\n";
	}
	

	if($baktype=='n')
	{
		$show.="DELETE FROM `{$pre}label` WHERE module='$webdb[module_id]';\n";
		//$show.="DELETE FROM `{$pre}module` WHERE id='$webdb[module_id]';\n";
	}
	//$show.="DELETE FROM `{$pre}module` WHERE pre='$webdb[module_pre]';\n";
	//$show.="DELETE FROM `{$pre}label` WHERE module='-90';\n";

	$query = $db->query("SELECT * FROM {$pre}label WHERE module='$webdb[module_id]'");
	$num=mysql_num_fields($query);

	while ($array=mysql_fetch_row($query)){
		$rows='';
		for($i=0;$i<$num;$i++){
			if($i==0)
			{
				unset($array[$i]);
			}
			$field_array=mysql_fetch_field($query,$i);
			if($baktype=='s')
			{
				if($field_array->name=='module')
				{
					$array[$i]=-90;
				}
				if($field_array->name=='code')
				{
					$_a=@unserialize($array[$i]);
					if(is_array($_a)){
						$_a[sql]=str_replace(" `$_pre"," `p8_news_",$_a[sql]);
						$_a[sql]=str_replace(" $_pre"," p8_news_",$_a[sql]);
						$_a[sql]=str_replace(" `$pre"," `p8_",$_a[sql]);
						$_a[sql]=str_replace(" $pre"," p8_",$_a[sql]);
						$array[$i]=serialize($_a);
					}
				}
			}
			//$rows.="'".mysql_escape_string($array[$i])."',";
			$rows.="`".$field_array->name."`='".mysql_escape_string($array[$i])."',";
		}
		$rows.=")";
		$rows=str_replace(",)","",$rows);
		//$show.="INSERT INTO `{$pre}label` VALUES ($rows);\n";
		$show.="INSERT INTO `{$pre}label` SET $rows;\n";
	}
	$show.="\n\n\n\n";
/*
	$query = $db->query("SELECT * FROM {$pre}module WHERE id='$webdb[module_id]'");
	$num=mysql_num_fields($query);
	while ($array=mysql_fetch_row($query)){
		$rows='';
		for($i=0;$i<$num;$i++){
			if($baktype=='s'&&$i==0)
			{
				unset($array[$i]);
			}
			$rows.="'".mysql_escape_string($array[$i])."',";
		}
		$rows.=")";
		$rows=str_replace(",)","",$rows);
		$show.="INSERT INTO `{$pre}module` VALUES ($rows);\n";
	}
*/
	return $show;
}


function bak_out($tabledb){
	global $db,$pre,$rowsnum,$tableid,$page,$timestamp,$step,$rand_dir,$lfj,$baksize,$_pre,$baktype;
	//��û���������Ŀ¼֮ǰ
	if(!$rand_dir){
		/*�صش�����Щ���������ܴ���Ŀ¼�����,��ʱ�����ֹ�����mysqlĿ¼*/
		if( file_exists(Adminpath."../cache/mysql_bak/mysql") )
		{
			if( !is_writable(Adminpath."../cache/mysql_bak/mysql") ){
				showerr(Adminpath."../cache/mysql_bak/mysqlĿ¼����д,�������Ϊ0777");
			}
			$rand_dir="mysql";
			copy("mysql_into.php",Adminpath."../cache/mysql_bak/$rand_dir/index.php");
			$show=create_table_all($tabledb);	//�������ݱ�ṹ
			$db->query("TRUNCATE TABLE {$pre}bak");
			bak_dir('../php168');		//���ݻ���
		}else{
			if($baktype=='s'){
				$rand_dir="install";
			}else{
				$_dpre=strtolower($_pre);
				$rand_dir=$_dpre.date("Y-m-d.",time()).strtolower(rands(3));
			}
			
			$show=create_table_all($tabledb);	//�������ݱ�ṹ
			if( !file_exists(Adminpath."../cache/mysql_bak") ){
				if( !@mkdir(Adminpath."../cache/mysql_bak",0777) ){
					showerr(Adminpath."../cache/mysql_bakĿ¼���ܴ���");
				}
			}
			if(	!is_dir(Adminpath."../cache/mysql_bak/$rand_dir")&&!@mkdir(Adminpath."../cache/mysql_bak/$rand_dir",0777)	)
			{
				showerr(Adminpath."../cache/mysql_bak/$rand_dir,Ŀ¼����д,�������Ϊ0777");
			}
			$dir=opendir(Adminpath."../cache/mysql_bak/$rand_dir");
			while($file=readdir($dir)){
				if(ereg("sql$",$file)||ereg("php$",$file)){
					unlink(Adminpath."../cache/mysql_bak/$rand_dir/$file");
				}
			}
			if($baktype=='s') copy("mysql_into.php",Adminpath."../cache/mysql_bak/$rand_dir/index.php");
			//$db->query("TRUNCATE TABLE {$pre}bak");
			//bak_dir('../php168');		//���ݻ���
		}
	}
	!$rowsnum && $rowsnum=500;	//ÿ�ζ�ȡ����������
	//��pageָ����ÿ������ʱ��.��Ҫ�����תҳ���ȡ
	if(!$page)
	{
		$page=1;
	}
	$min=($page-1)*$rowsnum;
	$tableid=intval($tableid);

	//$show.=$tablerows=bak_table($tabledb[$tableid],$min,$rowsnum);
	//��ǰ����ȡ������ʱ,�����˱���һҳȡ����,�������һ�����0��ʼ

	if( $tablerows=bak_table($tabledb[$tableid],$min,$rowsnum) )
	{
		$show.=$tablerows;
		unset($tablerows);	//�ͷ��ڴ�
		$page++;
	}
	else
	{
		$page=0;
		$tableid++;
	}

	//�־��Ǵ�0��ʼ��
	$step=intval($step);
	$filename="$step.sql";
	
	if($baktype=='s'){
		$show=str_replace("DROP TABLE IF EXISTS {$_pre}","DROP TABLE IF EXISTS p8_news_",$show);
		$show=str_replace("CREATE TABLE {$_pre}","CREATE TABLE p8_news_",$show);
		
		$show=str_replace("INSERT INTO `{$_pre}","INSERT INTO `p8_news_",$show);
		$show=str_replace("INSERT INTO {$_pre}","INSERT INTO p8_news_",$show);

		$show=str_replace("INSERT INTO `{$pre}","INSERT INTO `p8_",$show);
		$show=str_replace("INSERT INTO {$pre}","INSERT INTO p8_",$show);
	}
	write_file(Adminpath."../cache/mysql_bak/".$rand_dir."/".$filename,$show,'a+');

	//�����ָ��ÿ���С.��Ĭ��Ϊ1M
	$baksize=$baksize?$baksize:1024;
	
	//���ļ�����ȷ��С�־���
	$step=cksize(Adminpath."../cache/mysql_bak/".$rand_dir."/".$filename,$step,1024*$baksize);
	
	//��������ڱ�ʱ.����,�������
	if($tabledb[$tableid])
	{
		foreach($tabledb as $value)
		{
			$Table.="$value|";
		}
		//��¼����.��ֹ��;����ʧ��
		write_file(PHP168_PATH."cache/bak_mysql.txt","?lfj=$lfj&action=out&page=$page&rowsnum=$rowsnum&tableid=$tableid&rand_dir=$rand_dir&step=$step&tabledbreto=$Table&baksize=$baksize&baktype=$baktype");

		echo "<CENTER>�ѱ��� <font color=red>$step</font> ��, ������ <font color=blue>{$page}</font> ��ǰ���ڱ������ݿ� <font color=red>$tabledb[$tableid]</font></CENTER>";

print<<<EOT
<form name="form1" method="post" action="?lfj=$lfj&action=out&page=$page&rowsnum=$rowsnum&tableid=$tableid&rand_dir=$rand_dir&step=$step&baksize=$baksize&baktype=$baktype">
  <input type="hidden" name="tabledbreto" value="$Table">
</form>
<SCRIPT LANGUAGE="JavaScript">
<!--
function autosub(){
	document.form1.submit();
}
autosub();
//-->
</SCRIPT>
EOT;
		//echo "<META HTTP-EQUIV=REFRESH CONTENT='0;URL=index.php?lfj=$lfj&action=out&page=$page&rowsnum=$rowsnum&tableid=$tableid&rand_dir=$rand_dir&step=$step&tabledbreto=$Table&baksize=$baksize'>";
		exit;
	}
	else
	{
		$dir=opendir(Adminpath."../cache/mysql_bak/$rand_dir");
		while($file=readdir($dir)){
			if($file!='.'&&$file!='..'&&$file!='index.php')
			{
				$totalsize+=$sqlfilesize=@filesize(Adminpath."../cache/mysql_bak/$rand_dir/$file");
				$rs[sqlsize][]=number_format($sqlfilesize/1024,3);
			}
			
		}
		$totalsize=number_format($totalsize/1048576,3);
		@unlink(PHP168_PATH."cache/bak_mysql.txt");
		$rs[totalsize]=$totalsize;
		$rs[timedir]=$rand_dir;
		if( !@is_writable(Adminpath."../cache/mysql_bak/$rand_dir/0.sql") ){
			showerr("����ʧ�ܣ�����cache/mysql_bak/Ŀ¼�´���һ��Ŀ¼mysqlȻ���������Ϊ0777,�����Ŀ¼�Ѵ��ڣ���ɾ���������´�������������Ϊ0777");
		}
		return $rs;
	}
}

function bak_time(){
	global $_pre;
	if(is_table("{$_pre}config")){
		$ckk=$_pre;
	}
	$show="<select  name='baktime'><option value='' selected>��ѡ�񱸷��ļ�</option>";
	if(!is_dir(Adminpath."../cache/mysql_bak/")){
		mkdir(Adminpath."../cache/mysql_bak/");
		chmod(Adminpath."../cache/mysql_bak/",0777);
	}
	$dir=opendir(Adminpath."../cache/mysql_bak/");
	while( $file=readdir($dir) ){
		if( is_dir(Adminpath."../cache/mysql_bak/$file")&&$file!='.'&&$file!='..'&&(!$ckk||eregi("^$ckk",$file)) ){
			$show.="<option value='$file'>$file</option>";
		}
	}
	$show.="</select>";
	return $show;
}

function bak_into(){
	global $step,$baktime,$db,$pre,$_pre,$webdb;
	$step=intval($step);
	$file=Adminpath."../cache/mysql_bak/$baktime/{$step}.sql";
	if( file_exists($file) ){
		$db->insert_file($file);
	}
	$step++;
	if( file_exists(PHP168_PATH."cache/mysql_bak/$baktime/{$step}.sql") ){
		write_file(PHP168_PATH."cache/mysql_insert.txt","?lfj=mysql&action=into&baktime=$baktime&step=$step");
		echo "�ѵ���� {$step} ��<META HTTP-EQUIV=REFRESH CONTENT='0;URL=?lfj=mysql&action=into&baktime=$baktime&step=$step'>";
		exit;
	}else{

		@unlink(PHP168_PATH."cache/mysql_insert.txt");

		//$rs=$db->get_one("SELECT * FROM `{$pre}module` WHERE pre='$webdb[module_pre]'");
		//$db->query("UPDATE `{$pre}label` SET module='$rs[id]' WHERE module='-90'");

		//module_cache();

		/*
		$db->query("DELETE FROM `{$_pre}config` WHERE c_key='module_id'");

		$db->query("INSERT INTO `{$_pre}config` ( `c_key` , `c_value` , `c_descrip` ) VALUES ('module_id', '$rs[id]', '')");
		
		write_config_cache('');
		*/

		refreshto('?lfj=mysql&job=into',"�������",'5');
	}
}

function module_cache(){
	global $db,$pre;
	$show="<?php\r\n";
	$query = $db->query("SELECT * FROM {$pre}module ORDER BY list DESC");
	while($rs = $db->fetch_array($query))
	{
		$rs[name]=addslashes($rs[name]);

		$show.="
			\$ModuleDB['{$rs[pre]}']=array('name'=>'$rs[name]',
			'dirname'=>'$rs[dirname]',
			'domain'=>'$rs[domain]',
			'admindir'=>'$rs[admindir]',
			'type'=>'$rs[type]',
			'id'=>'$rs[id]'
			);
			";
	}
	write_file(PHP168_PATH."php168/module.php",$show);
}

function bak_dir($path){
	global $db,$filedb,$pre;
	if (file_exists($path)){
		if(is_file($path)){
			$files=read_file($path);
			$files=mysql_escape_string($files);
			$db->query("INSERT INTO {$pre}bak (bak_dir,bak_txt) VALUES ('$path','$files') ");
		} else{
			$handle = opendir($path);
			while ($file = readdir($handle)) {
				if( ($file!=".") && ($file!="..") && ($file!="") ){
					if (is_dir("$path/$file")){
						bak_dir("$path/$file");
					} else{
						$files=read_file("$path/$file");
						$files=mysql_escape_string($files);
						if("mysql_config.php"!=$file){
							$db->query("INSERT INTO {$pre}bak (bak_dir,bak_txt) VALUES ('$path/$file','$files') ");
						}
					}
				}
			}
			closedir($handle);
		}
	}
}

/*���ݵķ־��ļ����̶���С������*/
function cksize($lastSqlFile,$step,$size){
	if( @filesize($lastSqlFile)<($size+10*1024) )
	{
		return $step;
	}
	//����һ��������ɵĴ���ָ����С��SQL�ļ�������
	copy($lastSqlFile,"{$lastSqlFile}.bak");
	$filePre=str_replace(basename($lastSqlFile),"",$lastSqlFile);
	$readfile=read_file("{$lastSqlFile}.bak");
	$detail=explode("\n",$readfile);
	unset($readfile); //�ͷ��ڴ�
	foreach($detail AS $key=>$value){
		$NewSql.="$value\n";
		if(strlen($NewSql)>$size){
			write_file("$filePre/$step.sql",$NewSql);
			$step++;
			$NewSql='';
		}
	}
	//���µ���д�����ļ�,��ʱstep�Ѿ��ۼӹ���
	if($NewSql){
		write_file("$filePre/$step.sql",$NewSql);
	}
	@unlink("{$lastSqlFile}.bak");
	return $step;
}

?>