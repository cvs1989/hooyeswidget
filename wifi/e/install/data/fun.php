<?php
//取得随机数
function InstallMakePassword($pw_length){
	$low_ascii_bound=65;
	$upper_ascii_bound=90;
	$notuse=array(58,59,60,61,62,63,64,73,79,91,92,93,94,95,96,108,111);
	while($i<$pw_length)
	{
		mt_srand((double)microtime()*1000000);
		$randnum=mt_rand($low_ascii_bound,$upper_ascii_bound);
		if(!in_array($randnum,$notuse))
		{
			$password1=$password1.chr($randnum);
			$i++;
		}
	}
	return $password1;
}
//函数是否存在
function HaveFun($fun){
	if(function_exists($fun))
	{
		$word="支持";
	}
	else
	{
		$word="不支持";
	}
	return $word;
}
//返回符号
function ReturnResult($st){
	if($st==1)
	{
		$w="√";
	}
	elseif($st==2)
	{
		$w="---";
	}
	else
	{
		$w="<font color=red>×</font>";
	}
	return $w;
}
//取得php版本
function GetPhpVer(){
	$r['ver']=PHP_VERSION;
	if($r['ver'])
	{
		$r['result']=($r['ver']<"4.2.3")?ReturnResult(0):ReturnResult(1);
	}
	else
	{
		$r['ver']="---";
		$r['result']=ReturnResult(2);
	}
	return $r;
}
//取得php运行模式
function GetPhpMod(){
	$mod=strtoupper(php_sapi_name());
	if(empty($mod))
	{
		$mod="---";
	}
	return $mod;
}
//是否运行于安全模式
function GetPhpSafemod(){
	$phpsafemod=get_cfg_var("safe_mode");
	if($phpsafemod==1)
	{
		$r['word']="是";
		$r['result']=ReturnResult(0);
	}
	else
	{
		$r['word']="否";
		$r['result']=ReturnResult(1);
	}
	return $r;
}
//是否支持mysql
function CanMysql(){
	$r['can']=HaveFun("mysql_connect");
	$r['result']=$r[can]=="支持"?ReturnResult(1):ReturnResult(0);
	return $r;
}
//取得mysql版本
function GetMysqlVer(){
	$r['ver']=@mysql_get_server_info();
	if(empty($r['ver']))
	{
		$r['ver']="---";
		$r['result']=ReturnResult(2);
	}
	else
	{
		$r['result']=ReturnResult(1);
	}
	return $r;
}
//取得mysql版本(数据库)
function GetMysqlVerForDb(){
	$sql=mysql_query("select version() as version");
	$r=mysql_fetch_array($sql);
	return ReturnMysqlVer($r['version']);
}
//返回mysql版本
function ReturnMysqlVer($dbver){
	if(empty($dbver))
	{
		return '';
	}
	if($dbver>='6.0')
	{
		$dbver='6.0';
	}
	elseif($dbver>='5.0')
	{
		$dbver='5.0';
	}
	elseif($dbver>='4.1')
	{
		$dbver='4.1';
	}
	else
	{
		$dbver='4.0';
	}
	return $dbver;
}
//取得操作系统
function GetUseSys(){
	$phpos=explode(" ",php_uname());
	$sys=$phpos[0]."&nbsp;".$phpos[1];
	if(empty($phpos[0]))
	{
	$sys="---";
	}
	return $sys;
}
//是否支持zend
function GetZend(){
	@ob_start();
	@include("data/zend.php");
	$string=@ob_get_contents();
	@ob_end_clean();
	if($string=="www.phome.net"||strstr($string,"bytes in"))
	{
		$r['word']="支持";
		$r['result']=ReturnResult(1);
	}
	else
	{
		$r['word']="不支持";
		$r['result']=ReturnResult(0);
	}
	return $r;
}
//检查上传
function CheckTranMode(){
	@ob_start();
	@include("../class/connect.php");
	@include("../class/functions.php");
	$string=@ob_get_contents();
	@ob_end_clean();
	if(strstr($string,"bytes in"))
	{
		echo"您没有二进制上传文件！请重新二进制上传文件，然后再安装。";
		exit();
	}
}
//是否支持采集
function GetCj(){
	$cj=get_cfg_var("allow_url_fopen");
	if($cj==1)
	{
		$r['word']="支持";
		$r['result']=ReturnResult(1);
	}
	else
	{
		$r['word']="不支持";
		$r['result']=ReturnResult(0);
	}
	return $r;
}
//测试采集
function TestCj(){
	$r=@file("http://www.163.com");
	if($r[5])
	{
		echo"<br>测试结果：<b>支持采集</b>";
	}
	else
	{
		echo"<br>测试结果：<b>不支持采集</b>";
	}
	exit();
}
//是否支持gd库
function GetGd(){
	$r['can']=HaveFun("gd_info");
	$r['result']=$r[can]=="支持"?ReturnResult(1):ReturnResult(0);
	return $r;
}
//是否支持ICONV库
function GetIconv(){
	$r['can']=HaveFun("iconv");
	$r['result']=$r[can]=="支持"?ReturnResult(1):ReturnResult(0);
	return $r;
}

//提示信息
function InstallShowMsg($msg,$url=''){
	if(empty($url))
	{
		echo"<script>alert('".$msg."');history.go(-1);</script>";
	}
	else
	{
		echo"<script>alert('".$msg."');self.location.href='$url';</script>";
	}
	exit();
}
//返回目录权限结果
function ReturnPathLevelResult($path){
	$testfile=$path."/test.test";
	$fp=@fopen($testfile,"wb");
	if($fp)
	{
		@fclose($fp);
		@unlink($testfile);
		return 1;
	}
	else
	{
		return 0;
	}
}
//返回文件权限结果
function ReturnFileLevelResult($filename){
	return is_writable($filename);
}
//检测目录权限
function CheckFileMod($filename,$smallfile=""){
	$succ="√";
	$error="<font color=red>×</font>";
	if(!file_exists($filename)||($smallfile&&!file_exists($smallfile)))
	{
		return $error;
	}
	if(is_dir($filename))//目录
	{
		if(!ReturnPathLevelResult($filename))
		{
			return $error;
		}
		//子目录
		if($smallfile)
		{
			if(is_dir($smallfile))
			{
				if(!ReturnPathLevelResult($smallfile))
				{
					return $error;
				}
			}
			else//文件
			{
				if(!ReturnFileLevelResult($smallfile))
				{
					return $error;
				}
			}
		}
	}
	else//文件
	{
		if(!ReturnFileLevelResult($filename))
		{
			return $error;
		}
		if($smallfile)
		{
			if(!ReturnFileLevelResult($smallfile))
			{
				return $error;
			}
		}
	}
	return $succ;
}
//建表
function DoCreateTable($sql,$mysqlver,$dbcharset){
	$type=strtoupper(preg_replace("/^\s*CREATE TABLE\s+.+\s+\(.+?\).*(ENGINE|TYPE)\s*=\s*([a-z]+?).*$/isU","\\2",$sql));
	$type=in_array($type,array('MYISAM','HEAP'))?$type:'MYISAM';
	return preg_replace("/^\s*(CREATE TABLE\s+.+\s+\(.+?\)).*$/isU","\\1",$sql).
		($mysqlver>='4.1'?" ENGINE=$type DEFAULT CHARSET=$dbcharset":" TYPE=$type");
}
//运行SQL
function DoRunQuery($sql,$mydbchar,$mydbtbpre,$mydbver){
	$sql=str_replace("\r","\n",str_replace(' `phome_',' `'.$mydbtbpre,$sql));
	$ret=array();
	$num=0;
	foreach(explode(";\n",trim($sql)) as $query)
	{
		$queries=explode("\n",trim($query));
		foreach($queries as $query)
		{
			$ret[$num].=$query[0]=='#'||$query[0].$query[1]=='--'?'':$query;
		}
		$num++;
	}
	unset($sql);
	foreach($ret as $query)
	{
		$query=trim($query);
		if($query)
		{
			if(substr($query,0,12)=='CREATE TABLE')
			{
				$name=preg_replace("/CREATE TABLE `([a-z0-9_]+)` .*/is","\\1",$query);
				echo"建立数据表: <b>".$name."</b> 完毕......<br>";
				mysql_query(DoCreateTable($query,$mydbver,$mydbchar)) or die(mysql_error()."<br>".$query);
			}
			else
			{
				mysql_query($query) or die(mysql_error()."<br>".$query);
			}
		}
	}
}
//取得随机数
function ins_make_password($pw_length){
	$low_ascii_bound=50;
	$upper_ascii_bound=122;
	$notuse=array(58,59,60,61,62,63,64,73,79,91,92,93,94,95,96,108,111);
	while($i<$pw_length)
	{
		mt_srand((double)microtime()*1000000);
		$randnum=mt_rand($low_ascii_bound,$upper_ascii_bound);
		if(!in_array($randnum,$notuse))
		{
			$password1=$password1.chr($randnum);
			$i++;
		}
	}
	return $password1;
}
//初使化管理员
function FirstAdmin($add){
	if(!trim($add['username'])||!trim($add['password']))
	{
		InstallShowMsg('请输入管理员用户名与密码');
	}
	if($add['password']!=$add['repassword'])
	{
		InstallShowMsg('两次输入的密码不一致，请重新输入');
	}
	//链接数据库
	@include("../class/config.php");
	$dbver=InstallConnectDb($phome_use_dbver,$phome_db_server,$phome_db_port,$phome_db_username,$phome_db_password,$phome_db_dbname,$phome_db_char,$phome_db_dbchar);
	$salt=ins_make_password(8);
	$password=md5(md5($add['password']).$salt);
	$rnd=ins_make_password(20);
	$sql=mysql_query("INSERT INTO `".$dbtbpre."enewsuser`(userid,username,password,rnd,adminclass,groupid,checked,styleid,filelevel,salt,loginnum,lasttime,lastip,truename,email) VALUES (1,'$add[username]','$password','$rnd','',1,0,1,0,'$salt',0,0,'','','');");
	mysql_close();
	//认证码
	RepEcmsConfigLoginauth($add);
	if($sql)
	{
		echo"初始化管理员账号完毕!<script>self.location.href='changedata.php?defaultdata=$add[defaultdata]';</script>";
		exit();
	}
	else
	{
		InstallShowMsg('初使化管理员不成功，意外出错，请重新安装一次.');
	}
}
//导入测试数据
function InstallDefaultData($add){
	//链接数据库
	@include("../class/config.php");
	$dbver=InstallConnectDb($phome_use_dbver,$phome_db_server,$phome_db_port,$phome_db_username,$phome_db_password,$phome_db_dbname,$phome_db_char,$phome_db_dbchar);
	//执行SQL语句
	DoRunQuery(ReturnInstallSql(1),$phome_db_dbchar,$dbtbpre,$phome_use_dbver);
	mysql_close();
	echo"导入测试数据完毕!<script>self.location.href='index.php?enews=firstadmin&f=5&defaultdata=$add[defaultdata]';</script>";
	exit();
}
//链接数据库
function InstallConnectDb($phome_use_dbver,$phome_db_server,$phome_db_port,$phome_db_username,$phome_db_password,$phome_db_dbname,$phome_db_char,$phome_db_dbchar){
	$dblocalhost=$phome_db_server;
	//端口
	if($phome_db_port)
	{
		$dblocalhost.=":".$phome_db_port;
	}
	$link=@mysql_connect($dblocalhost,$phome_db_username,$phome_db_password);
	if(!$link)
	{
		InstallShowMsg('您的数据库用户名或密码有误，链接不上MYSQL数据库');
	}
	//mysql版本
	if($phome_use_dbver=='auto')
	{
		$phome_use_dbver=GetMysqlVerForDb();
		if(!$phome_use_dbver)
		{
			InstallShowMsg('系统无法自动识别MYSQL版本，请手动选择MYSQL版本');
		}
	}
	//编码
	if($phome_use_dbver>='4.1')
	{
		$q='';
		if($phome_db_char)
		{
			$q='character_set_connection='.$phome_db_char.',character_set_results='.$phome_db_char.',character_set_client=binary';
		}
		if($phome_use_dbver>='5.0')
		{
			$q.=(empty($q)?'':',').'sql_mode=\'\'';
		}
		if($q)
		{
			@mysql_query('SET '.$q);
		}
	}
	$db=@mysql_select_db($phome_db_dbname);
	//数据库不存在
	if(!$db)
	{
		if($phome_use_dbver>='4.1')
		{
			$createdb=@mysql_query("CREATE DATABASE IF NOT EXISTS ".$phome_db_dbname." DEFAULT CHARACTER SET ".$phome_db_dbchar);
		}
		else
		{
			$createdb=@mysql_query("CREATE DATABASE IF NOT EXISTS ".$phome_db_dbname);
		}
		if(!$createdb)
		{
			InstallShowMsg('您输入的数据库名不存在');
		}
		@mysql_select_db($phome_db_dbname);
	}
	return $phome_use_dbver;
}
//配置数据库
function SetDb($add){
	global $version;
	if(!$add['mydbver']||!$add['mydbhost']||!$add['mydbname']||!$add['mydbtbpre']||!$add['mycookievarpre']||!$add['myadmincookievarpre'])
	{
		InstallShowMsg('带*项不能为空');
	}
	//链接数据库
	$dbver=InstallConnectDb($add['mydbver'],$add['mydbhost'],$add['mydbport'],$add['mydbusername'],$add['mydbpassword'],$add['mydbname'],$add['mysetchar'],$add['mydbchar']);
	if($add['mydbver']=='auto')
	{
		$add['mydbver']=$dbver;
	}
	//初使化网站信息
	$siteurl=ReturnEcmsSiteUrl();
	//配置文件
	RepEcmsConfig($add,$siteurl);
	//执行SQL语句
	DoRunQuery(ReturnInstallSql(0),$add['mydbchar'],$add['mydbtbpre'],$add['mydbver']);
	@mysql_query("update ".$add['mydbtbpre']."enewspublic set newsurl='$siteurl',fileurl='".$siteurl."d/file/',softversion='$version' limit 1");
	@mysql_query("update ".$add['mydbtbpre']."enewsshoppayfs set payurl='".$siteurl."e/payapi/ShopPay.php?paytype=tenpay' where payid=3");
	@mysql_close();
	if(empty($add['defaultdata']))
	{
		InstallDelArticleTxtFile();
		echo"配置数据库完毕!<script>self.location.href='index.php?enews=firstadmin&f=5&defaultdata=$add[defaultdata]';</script>";
	}
	else
	{
		echo"正进入测试数据导入......<script>self.location.href='index.php?enews=defaultdata&f=4&ok=1&defaultdata=$add[defaultdata]';</script>";
	}
	exit();
}
//处理配置文件
function RepEcmsConfig($add,$siteurl){
	global $headerchar;
	//初使化配置文件
	$fp=@fopen("data/config.php","r");
	if(!$fp)
	{
		InstallShowMsg('请检查 /e/install/data/config.php 文件是否存在!');
	}
	$data=@fread($fp,filesize("data/config.php"));
	fclose($fp);
	$data=str_replace('<!--dbtype.phome.net-->',$add['mydbtype'],$data);
	$data=str_replace('<!--dbver.phome.net-->',$add['mydbver'],$data);
	$data=str_replace('<!--host.phome.net-->',$add['mydbhost'],$data);
	$data=str_replace('<!--port.phome.net-->',$add['mydbport'],$data);
	$data=str_replace('<!--username.phome.net-->',$add['mydbusername'],$data);
	$data=str_replace('<!--password.phome.net-->',$add['mydbpassword'],$data);
	$data=str_replace('<!--name.phome.net-->',$add['mydbname'],$data);
	$data=str_replace('<!--char.phome.net-->',$add['mysetchar'],$data);
	$data=str_replace('<!--dbchar.phome.net-->',$add['mydbchar'],$data);
	$data=str_replace('<!--tbpre.phome.net-->',$add['mydbtbpre'],$data);
	$data=str_replace('<!--cookiepre.phome.net-->',$add['mycookievarpre'],$data);
	$data=str_replace('<!--admincookiepre.phome.net-->',$add['myadmincookievarpre'],$data);
	$data=str_replace('<!--headerchar.phome.net-->',$headerchar,$data);
	$data=str_replace('<!--cookiernd.phome.net-->',ins_make_password(30),$data);
	$data=str_replace('<!--qcookiernd.phome.net-->',ins_make_password(30),$data);
	$data=str_replace('<!--ecms.newsurl-->',$siteurl,$data);
	$data=str_replace('<!--ecms.fileurl-->',$siteurl."d/file/",$data);
	//写入配置文件
	$fp1=@fopen("../class/config.php","w");
	if(!$fp1)
	{
		InstallShowMsg(' /e/class/config.php 文件权限没有设为0777，配置数据库不成功');
	}
	@fputs($fp1,$data);
	@fclose($fp1);
}
//处理认证码
function RepEcmsConfigLoginauth($add){
	global $headerchar;
	//初使化配置文件
	$fp=@fopen("../class/config.php","r");
	if(!$fp)
	{
		InstallShowMsg('请检查 /e/class/config.php 文件是否存在!');
	}
	$data=@fread($fp,filesize("../class/config.php"));
	fclose($fp);
	$data=str_replace('<!--loginauth.phome.net-->',$add['loginauth'],$data);
	//写入配置文件
	$fp1=@fopen("../class/config.php","w");
	if(!$fp1)
	{
		InstallShowMsg(' /e/class/config.php 文件权限没有设为0777，配置不成功');
	}
	@fputs($fp1,$data);
	@fclose($fp1);
}
//返回SQL语句
function ReturnInstallSql($defaultdata=1){
	if($defaultdata==0)
	{
		$sqlfile="data/empirecms.com.sql";
	}
	else
	{
		$sqlfile="data/empirecms.data.sql";
	}
	$fp=fopen($sqlfile,'r');
	$sql=fread($fp,filesize($sqlfile));
	fclose($fp);
	if(empty($sql))
	{
		InstallShowMsg(' /e/install/'.$sqlfile.' 文件丢失,安装不成功','index.php?enews=setdb&f=4');
	}
	return $sql;
}
//取得网站地址
function ReturnEcmsSiteUrl(){
	return str_replace('e/install/index.php','',$_SERVER['PHP_SELF']);
}
//删除存文本文件
function InstallDelArticleTxtFile(){
	@include("../class/delpath.php");
	$DelPath="../../d/txt/2008";
	$wm_chief=new del_path();
	$wm_chief_ok=$wm_chief->wm_chief_delpath($DelPath);
	return $wm_chief_ok;
}
?>