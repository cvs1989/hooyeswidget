<?php


require(dirname(__FILE__)."/"."global.php");
$_GET['_fromurl'] && $_fromurl=$_GET['_fromurl'];
//处理同步登录
if($webdb[passport_type])
{
	if($action=="quit")
	{
		if( ereg("^dzbbs",$webdb[passport_type]) )
		{
			//5.0使用$tablepre,5.5使用$cookiepre
			set_cookie("{$cookiepre}auth","");
			set_cookie("{$cookiepre}sid","");
			set_cookie("{$tablepre}auth","");
			set_cookie("{$tablepre}sid","");
			set_cookie("passport","");
			setcookie("adminID","",0,"/");	//同步后台退出
			$login=uc_user_synlogout();
			refreshto("$FROMURL","成功退出$login",1);
			//header("location:$FROMURL");
			//以下是跳到退出前的页面,你可以把上一句删除,把下一句的//去掉即可
			//header("location:$FROMURL");
			exit;
		}
		elseif( ereg("^pwbbs",$webdb[passport_type]) )
		{
			set_cookie(CookiePre().'_winduser',"");
			setcookie("adminID","",0,"/");	//同步后台退出
			if(!$fromurl){
				$fromurl="$webdb[www_url]/";
			}
			header("location:$fromurl");
			//以下是跳到退出前的页面,你可以把上一句删除,把下一句的//去掉即可
			//header("location:$FROMURL");
			exit;
		}
		elseif( ereg("^dvbbs",$webdb[passport_type]) )
		{
			set_cookie("{$cookieprename}userid","");
			set_cookie("{$cookieprename}username","");
			set_cookie("{$cookieprename}password","");
			setcookie("adminID","",0,"/");	//同步后台退出
			header("location:$FROMURL");
			//以下是跳到退出前的页面,你可以把上一句删除,把下一句的//去掉即可
			//header("location:$FROMURL");
			exit;
		}
		else
		{
			setcookie("adminID","",0,"/");	//同步后台退出
			header("location:$TB_url/$TB_quit");
			exit;
		}
	}
}

//退出
if($action=="quit")
{
	set_cookie("passport","");
	setcookie("adminID","",0,"/");	//同步后台退出
	if(!$fromurl){
		$fromurl="$webdb[www_url]/";
	}
	echo "<META HTTP-EQUIV=REFRESH CONTENT='0;URL=$fromurl'>";
	//以下是跳到退出前的页面,你可以把上一句删除,把下一句的//去掉即可
	//header("location:$FROMURL");
	exit;
}
else
{	//登录
	if($lfjid){
		showerr("你已经登录了,请不要重复登录,要重新登录请点击<A HREF='$webdb[www_url]/do/login.php?action=quit'>安全退出</A>");
	}
	if($step==2){
		$login=user_login($username,$password,$cookietime);
		if($login==-1){
			showerr("当前用户不存在,请重新输入");
		}elseif($login==0){
			showerr("密码不正确,点击重新输入");
		}
		if($fromurl&&!eregi("login\.php",$fromurl)&&!eregi("reg\.php",$fromurl)){
			$jumpto=$fromurl;
		}elseif($FROMURL&&!eregi("login\.php",$FROMURL)&&!eregi("reg\.php",$FROMURL)){
			$jumpto=$FROMURL;
		}else{
			$jumpto="$webdb[www_url]/";
		}
		refreshto("$jumpto","登录成功",1);
	}
	require(PHP168_PATH."inc/head.php");
	require(html("login"));
	require(PHP168_PATH."inc/foot.php");
}
?>