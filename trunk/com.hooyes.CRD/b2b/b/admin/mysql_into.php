<?php
require_once(dirname(__FILE__)."/../../inc/common.inc.php");


//������
if($job=='c'&&$newpre){

	if(is_file("$page.sql")){
		update_sql("$page.sql");
		$page++;
		echo '<meta http-equiv="Content-Type" content="text/html; charset=gb2312">';
		echo "���Ժ�:$page<META HTTP-EQUIV=REFRESH CONTENT='0;URL=?job=c&page=$page&newpre=$newpre&module_id=$module_id&module_pre=$module_pre'>";
		exit;
	}else{

		$db->query("DELETE FROM `{$pre}{$newpre}config` WHERE c_key='module_id'");
		$db->query("DELETE FROM `{$pre}{$newpre}config` WHERE c_key='module_pre'");

		$db->query("INSERT INTO `{$pre}{$newpre}config` ( `c_key` , `c_value` , `c_descrip` ) VALUES ('module_id', '$module_id', '')");

		$db->query("INSERT INTO `{$pre}{$newpre}config` ( `c_key` , `c_value` , `c_descrip` ) VALUES ('module_pre', '$module_pre', '')");

		$writefile="<?php\r\n";
		$query = $db->query("SELECT * FROM `{$pre}{$newpre}config`");
		while($rs = $db->fetch_array($query)){
			$rs[c_value]=addslashes($rs[c_value]);
			$writefile.="\$webdb['$rs[c_key]']='$rs[c_value]';\r\n";
		}
		write_file("../php168/config.php",$writefile);

		if(is_writable("index.php")){
			echo '<meta http-equiv="Content-Type" content="text/html; charset=gb2312">';
			echo "<A HREF='../'>��װ���,���������ҳ,��ɾ����װ�ļ�</A>";
			unlink("index.php");
			unlink("0.sql");
		}else{
			echo '<meta http-equiv="Content-Type" content="text/html; charset=gb2312">';
			echo "<A HREF='../'>�������,���ֹ�ɾ�����ļ�index.php</A>";
		}
		exit;
	}
}



if($_GET[job]=='1')
{
	unlink("install.php");
	unlink("0.sql");
	header("location:../");
	exit;
}

if(!is_writable('../php168')){
	showerr("php168Ŀ¼����д,��Ĵ�Ŀ¼��Ŀ¼�µ��ļ�����Ϊ0777");
}
if(!is_writable('../cache')){
	showerr("cacheĿ¼����д,��Ĵ�Ŀ¼��Ŀ¼�µ��ļ�����Ϊ0777");
}

if(!$job)
{
$Mname='php168��������ϵͳ';
$newpre='news_';
print<<<EOT

<html>
<head>
<title>PHP168��װ����</title>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312">
<style type="text/css">
<!--
td{font-size: 12px;}
-->
</style>
</head>

<body bgcolor="#FFFFFF" text="#000000">
<table width="40%" border="0" cellspacing="1" cellpadding="3" align="center" bgcolor="#006666">
  <form name="form1" method="post" action="">
    <tr align="center" bgcolor="#006666"> 
      <td colspan="2"><font color="#FFFFFF"><b>PHP168��װ����</b></font></td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td width="35%">���ݱ�ǰ׺ </td>
      <td width="65%" bgcolor="#FFFFFF"> 
        <input type="text" name="newpre" value="$newpre">
      </td>
    </tr>
    <tr bgcolor="#FFFFFF">
      <td width="35%">��ǰϵͳ����</td>
      <td width="65%" bgcolor="#FFFFFF">
        <input type="text" name="Mname" value="$Mname">
      </td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td colspan="2">ע:ֻ����Сд��ĸ��Сд��ĸ+����</td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td width="35%">&nbsp;</td>
      <td width="65%"> 
        <input type="submit" name="Submit" value="��ʼ��װ">
        <input type="hidden" name="job" value="2">
      </td>
    </tr>
  </form>
</table>
</body>
</html>

EOT;
exit;
}

if(is_table("{$pre}{$newpre}config")&&$job!=3)
{
print<<<EOT

<html>
<head>
<title>PHP168��װ����</title>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312">
<style type="text/css">
<!--
td{font-size: 12px;}
-->
</style>
</head>

<body bgcolor="#FFFFFF" text="#000000">
<table width="40%" border="0" cellspacing="1" cellpadding="3" align="center" bgcolor="#006666">
  <form name="form1" method="post" action="">
    <tr align="center" bgcolor="#006666"> 
      <td colspan="2"><font color="#FFFFFF"><b>PHP168��װ����</b></font></td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td colspan="2"><font color="#FF0000"><b>����!ϵͳ��⵽�������ݱ�ǰ׺�Ѵ���,�����һ��,��������ԭ����!!</b></font></td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td width="35%">���ݱ�ǰ׺ </td>
      <td width="65%" bgcolor="#FFFFFF"> 
        <input type="text" name="newpre" value="$newpre">
      </td>
    </tr>
    <tr bgcolor="#FFFFFF">
      <td width="35%">��ǰϵͳ����</td>
      <td width="65%" bgcolor="#FFFFFF"><input type="text" name="Mname" value="$Mname"></td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td colspan="2">ע:ֻ����Сд��ĸ��Сд��ĸ+����</td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td>&nbsp;</td>
      <td> 
        <input type="radio" name="job" value="2" checked>
        ���滻�Ѵ��ڵ� 
        <input type="radio" name="job" value="3">
        ǿ���滻�Ѵ��ڵ�</td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td width="35%">&nbsp;</td>
      <td width="65%"> 
        <input type="submit" name="Submit" value="��ʼ��װ">
      </td>
    </tr>
  </form>
</table>
</body>
</html>

EOT;
exit;
}

if(!$newpre){
	showerr("���ݱ�ǰ׺������");
}elseif( !ereg("_$",$newpre) ){
	showerr("���ݱ�ǰ׺Ҫ���»���_��β");
}elseif( !ereg("^[a-z]",$newpre) ){
	showerr("���ݱ�ǰ׺Ҫ��Сд��ĸ��ͷ");
}elseif( !ereg("^([_a-z0-9]+)$",$newpre) ){
	showerr("���ݱ�ǰ׺ֻ������ĸ������");
}

if( !is_table("{$pre}module") )
{
	$SQL="CREATE TABLE `{$pre}module` (
  `id` mediumint(5) NOT NULL auto_increment,
  `type` tinyint(1) NOT NULL default '0',
  `name` varchar(30) NOT NULL default '',
  `pre` varchar(20) NOT NULL default '',
  `dirname` varchar(30) NOT NULL default '',
  `domain` varchar(100) NOT NULL default '',
  `admindir` varchar(20) NOT NULL default '',
  `unite_admin` tinyint(1) NOT NULL default '0',
  `config` text NOT NULL,
  `list` mediumint(5) NOT NULL default '0',
  `admingroup` varchar(150) NOT NULL default '',
  `adminmember` text NOT NULL,
  PRIMARY KEY  (`id`)
) TYPE=MyISAM AUTO_INCREMENT=30 ";
	if($dbcharset && mysql_get_server_info() > '4.0')
	{
		$sql=str_replace("TYPE=MyISAM","TYPE=MyISAM DEFAULT CHARSET=$dbcharset ",$sql);
	}
	$db->query($SQL);
}


$db->query(" DELETE FROM `{$pre}module` WHERE `pre`='$newpre' ");


$dirname = preg_replace("/(.*)\/([^\/]+)\/install/is","\\2",str_replace("\\","/",dirname(__FILE__)));

$db->query("INSERT INTO `{$pre}module` (`id`, `type`, `name`, `pre`, `dirname`, `domain`, `admindir`, `unite_admin`, `config`) VALUES ('', 1, '$Mname', '$newpre', '$dirname', '', '', 1, '')");



//��������
update_sql("0.sql");

@extract($db->get_one("SELECT id AS Mid FROM `{$pre}module` ORDER BY id DESC LIMIT 1"));

$db->query("DELETE FROM `{$pre}{$newpre}config` WHERE c_key='module_id'");
$db->query("DELETE FROM `{$pre}{$newpre}config` WHERE c_key='module_pre'");

$db->query("INSERT INTO `{$pre}{$newpre}config` ( `c_key` , `c_value` , `c_descrip` ) VALUES ('module_id', '$Mid', '')");

$db->query("INSERT INTO `{$pre}{$newpre}config` ( `c_key` , `c_value` , `c_descrip` ) VALUES ('module_pre', '$newpre', '')");

$writefile="<?php\r\n";
$query = $db->query("SELECT * FROM `{$pre}{$newpre}config`");
while($rs = $db->fetch_array($query)){
	$rs[c_value]=addslashes($rs[c_value]);
	$writefile.="\$webdb['$rs[c_key]']='$rs[c_value]';\r\n";
}
write_file("../php168/config.php",$writefile);


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



write_file("../php168/all_fid.php",'');
write_file("../php168/all_spfid.php",'');

$query = $db->query("SELECT * FROM {$pre}label WHERE module='-90'");
while($rs = $db->fetch_array($query)){
	$_a=@unserialize($rs[code]);
	if(is_array($_a)){
		$_a[sql]=str_replace(" `p8_news_"," `$pre$newpre",$_a[sql]);
		$_a[sql]=str_replace(" p8_news_"," $pre$newpre",$_a[sql]);

		$_a[sql]=str_replace(" `p8_"," `$pre",$_a[sql]);
		$_a[sql]=str_replace(" p8_"," $pre",$_a[sql]);

		$_a[wninfo]=$newpre;

		$_a[url]=preg_replace("/webdb\[www_url\]\/([-a-z0-9_]+)\//","webdb[www_url]/vvv/",$_a[url]);
		$_a[tplpart_1code]=preg_replace("/webdb\[www_url\]\/([-a-z0-9_]+)\//","webdb[www_url]/$dirname/",$_a[tplpart_1code]);
		$_a[tplpart_2code]=preg_replace("/webdb\[www_url\]\/([-a-z0-9_]+)\//","webdb[www_url]/$dirname/",$_a[tplpart_2code]);
		
		$_ss=serialize($_a);
		$_ss=addslashes($_ss);

		$rs[type]=preg_replace("/Info_([-a-z0-9_]+)/","Info_$newpre",$rs[type]);
		$db->query("UPDATE {$pre}label SET code='$_ss',type='$rs[type]' WHERE lid='$rs[lid]'");
	}
}
$db->query(" UPDATE {$pre}label SET module='$Mid' WHERE module='-90' ");

if(is_file("1.sql")){
	echo "<META HTTP-EQUIV=REFRESH CONTENT='0;URL=?job=c&page=1&newpre=$newpre&module_id=$Mid&module_pre=$newpre'>";
	exit;
}

if(is_writable("index.php")){
	echo '<meta http-equiv="Content-Type" content="text/html; charset=gb2312">';
	echo "<A HREF='../'>��װ���,���������ҳ,��ɾ����װ�ļ�</A>";
	unlink("index.php");
	unlink("0.sql");
}else{
	echo '<meta http-equiv="Content-Type" content="text/html; charset=gb2312">';
	echo "<A HREF='../'>�������,���ֹ�ɾ�����ļ�index.php</A>";
}

function update_sql($file)
{
	global $db,$pre,$dbcharset,$newpre;
	$readfiles=read_file($file);
	$detail=explode("\n",$readfiles);
	$count=count($detail);
	for($j=0;$j<$count;$j++){
		$ck=substr($detail[$j],0,4);
		if( ereg("#",$ck)||ereg("--",$ck) ){
			continue;
		}
		$array[]=$detail[$j];
	}
	$read=implode("\n",$array); 
	$sql=str_replace("\r",'',$read);
	$detail=explode(";\n",$sql);
	$count=count($detail);
	for($i=0;$i<$count;$i++){
		$sql=str_replace("\r",'',$detail[$i]);
		$sql=str_replace("\n",'',$sql);
		$sql=trim($sql);
		if($sql){
			$sql=str_replace("DROP TABLE IF EXISTS p8_news_","DROP TABLE IF EXISTS {$pre}{$newpre}",$sql);
			$sql=str_replace("CREATE TABLE p8_news_","CREATE TABLE {$pre}{$newpre}",$sql);
			$sql=str_replace("INSERT INTO p8_news_","INSERT INTO {$pre}{$newpre}",$sql);
			$sql=str_replace("INSERT INTO `p8_news_","INSERT INTO `{$pre}{$newpre}",$sql);
			$sql=str_replace("INSERT INTO p8_","INSERT INTO {$pre}",$sql);
			$sql=str_replace("INSERT INTO `p8_","INSERT INTO `{$pre}",$sql);
			if( $dbcharset && mysql_get_server_info() >= '4.1' ){
				$sql=str_replace("TYPE=MyISAM","TYPE=MyISAM DEFAULT CHARSET=$dbcharset ",$sql);
			}
			$db->query($sql);
			$check++;
		}
	}
	return $check;
}

?>