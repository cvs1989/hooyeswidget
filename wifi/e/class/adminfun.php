<?php
//错误登陆记录
function InsertErrorLoginNum($username,$password,$loginauth,$ip,$time){
	global $empire,$public_r,$dbtbpre;
	//COOKIE
	$loginnum=intval(getcvar('loginnum',1));
	$logintime=$time;
	$lastlogintime=getcvar('lastlogintime',1);
	if($lastlogintime&&($logintime-$lastlogintime>$public_r['logintime']*60))
	{
		$loginnum=0;
	}
	$loginnum++;
	esetcookie("loginnum",$loginnum,$logintime+3600*24,1);
	esetcookie("lastlogintime",$logintime,$logintime+3600*24,1);
	//数据库
	$chtime=$time-$public_r['logintime']*60;
	$empire->query("delete from {$dbtbpre}enewsloginfail where lasttime<$chtime");
	$r=$empire->fetch1("select ip from {$dbtbpre}enewsloginfail where ip='$ip' limit 1");
	if($r['ip'])
	{
		$empire->query("update {$dbtbpre}enewsloginfail set num=num+1,lasttime='$time' where ip='$ip' limit 1");
	}
	else
	{
		$empire->query("insert into {$dbtbpre}enewsloginfail(ip,num,lasttime) values('$ip',1,'$time');");
	}
	//日志
	insert_log($username,$password,0,$ip,$loginauth);
}

//验证登录次数
function CheckLoginNum($ip,$time){
	global $empire,$public_r,$dbtbpre;
	//COOKIE验证
	$loginnum=getcvar('loginnum',1);
	$lastlogintime=getcvar('lastlogintime',1);
	if($lastlogintime)
	{
		if($time-$lastlogintime<$public_r['logintime']*60)
		{
			if($loginnum>=$public_r['loginnum'])
			{
				printerror("LoginOutNum","history.go(-1)");
			}
		}
	}
	//数据库验证
	$chtime=$time-$public_r['logintime']*60;
	$num=$empire->gettotal("select count(*) as total from {$dbtbpre}enewsloginfail where ip='$ip' and num>=$public_r[loginnum] and lasttime>$chtime limit 1");
	if($num)
	{
		printerror("LoginOutNum","history.go(-1)");
	}
}

//登陆
function login($username,$password,$key,$post){
	global $empire,$public_r,$dbtbpre,$do_loginauth;
	eCheckAccessIp(1);//禁止IP
	$username=RepPostVar($username);
	$password=RepPostVar($password);
	if(!$username||!$password)
	{
		printerror("EmptyKey","index.php");
	}
	//验证码
	$keyvname='checkkey';
	if(!$public_r['adminloginkey'])
	{
		ecmsCheckShowKey($keyvname,$key,0,1);
	}
	if(strlen($username)>30||strlen($password)>30)
	{
		printerror("EmptyKey","index.php");
	}
	$loginip=egetip();
	$logintime=time();
	CheckLoginNum($loginip,$logintime);
	//认证码
	if($do_loginauth&&$do_loginauth!=$post['loginauth'])
	{
		InsertErrorLoginNum($username,$password,1,$loginip,$logintime);
		printerror("ErrorLoginAuth","index.php");
	}
	$user_r=$empire->fetch1("select userid,password,salt from {$dbtbpre}enewsuser where username='".$username."' and checked=0 limit 1");
	if(!$user_r['userid'])
	{
		InsertErrorLoginNum($username,$password,0,$loginip,$logintime);
		printerror("LoginFail","index.php");
	}
	$ch_password=md5(md5($password).$user_r['salt']);
	if($user_r['password']!=$ch_password)
	{
		InsertErrorLoginNum($username,$password,0,$loginip,$logintime);
		printerror("LoginFail","index.php");
	}
	//取得随机密码
	$rnd=make_password(20);
	$sql=$empire->query("update {$dbtbpre}enewsuser set rnd='$rnd',loginnum=loginnum+1,lastip='$loginip',lasttime='$logintime' where username='$username' limit 1");
	$r=$empire->fetch1("select groupid,userid,styleid from {$dbtbpre}enewsuser where username='$username' limit 1");
	//样式
	if(empty($r[styleid]))
	{
		$stylepath=$public_r['defadminstyle']?$public_r['defadminstyle']:1;
	}
	else
	{
		$styler=$empire->fetch1("select path,styleid from {$dbtbpre}enewsadminstyle where styleid='$r[styleid]'");
		if(empty($styler[styleid]))
		{
			$stylepath=$public_r['defadminstyle']?$public_r['defadminstyle']:1;
		}
		else
		{
			$stylepath=$styler['path'];
		}
	}
	//设置备份
	$cdbdata=0;
	$bnum=$empire->gettotal("select count(*) as total from {$dbtbpre}enewsgroup where groupid='$r[groupid]' and dodbdata=1");
	if($bnum)
	{
		$cdbdata=1;
		$set5=esetcookie("ecmsdodbdata","empirecms",0,1);
    }
	else
	{
		$set5=esetcookie("ecmsdodbdata","",0,1);
	}
	
	ecmsEmptyShowKey($keyvname,1);//清空验证码
	$set4=esetcookie("loginuserid",$r[userid],0,1);
	$set1=esetcookie("loginusername",$username,0,1);
	$set2=esetcookie("loginrnd",$rnd,0,1);
	$set3=esetcookie("loginlevel",$r[groupid],0,1);
	$set5=esetcookie("eloginlic","empirecmslic",0,1);
	$set6=esetcookie("loginadminstyleid",$stylepath,0,1);
	//COOKIE加密验证
	DoECookieRnd($r[userid],$username,$rnd,$cdbdata,$r[groupid],intval($stylepath));
	//最后登陆时间
	$set4=esetcookie("logintime",$logintime,0,1);
	//写入日志
	insert_log($username,'',1,$loginip,0);
	if($set1&&$set2&&$set3)
	{
		//操作日志
	    insert_dolog("");
		if($post['adminwindow'])
		{
		?>
			<script>
			AdminWin=window.open("admin.php","EmpireCMS","scrollbars");
			AdminWin.moveTo(0,0);
			AdminWin.resizeTo(screen.width,screen.height-30);
			self.location.href="blank.php";
			</script>
		<?
		exit();
		}
		else
		{
			printerror("LoginSuccess","admin.php");
		}
	}
	else
	{
		printerror("NotCookie","index.php");
	}
}

//写入登录日志
function insert_log($username,$password,$status,$loginip,$loginauth){
	global $empire,$do_theloginlog,$dbtbpre;
	if($do_theloginlog)
	{
		return "";
	}
	$password=RepPostVar($password);
	$loginauth=RepPostVar($loginauth);
	if($password)
	{
		$password=preg_replace("/^(.{".round(strlen($password) / 4)."})(.+?)(.{".round(strlen($password) / 6)."})$/s", "\\1***\\3", $password);
	}
	$username=RepPostVar($username);
	$logintime=date("Y-m-d H:i:s");
	$sql=$empire->query("insert into {$dbtbpre}enewslog(username,loginip,logintime,status,password,loginauth) values('$username','$loginip','$logintime','$status','$password','$loginauth');");
}

//退出登陆
function loginout($userid,$username,$rnd){
	global $empire,$dbtbpre;
	$userid=(int)$userid;
	if(!$userid||!$username)
	{
		printerror("NotLogin","history.go(-1)");
	}
	$set1=esetcookie("loginuserid","",0,1);
	$set2=esetcookie("loginusername","",0,1);
	$set3=esetcookie("loginrnd","",0,1);
	$set4=esetcookie("loginlevel","",0,1);
	//取得随机密码
	$rnd=make_password(20);
	$sql=$empire->query("update {$dbtbpre}enewsuser set rnd='$rnd' where userid='$userid'");
	//操作日志
	insert_dolog("");
	printerror("ExitSuccess","index.php");
}
?>