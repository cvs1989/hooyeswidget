<?php
include("../class/connect.php");

//取得随机数
function domake_password($pw_length){
	global $public_r;
	if($public_r['keytog']==1)//字母
	{
		$low_ascii_bound=65;
		$upper_ascii_bound=90;
		$notuse=array(91);
	}
	elseif($public_r['keytog']==2)//数字+字母
	{
		$low_ascii_bound=50;
		$upper_ascii_bound=90;
		$notuse=array(58,59,60,61,62,63,64,73,79);
	}
	else//数字
	{
		$low_ascii_bound=48;
		$upper_ascii_bound=57;
		$notuse=array(58);
	}
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

//显示验证码
function ShowKey($v){
	$vname=ecmsReturnKeyVarname($v);
	$key=strtolower(domake_password(4));
	ecmsSetShowKey($vname,$key);
	//是否支持gd库
	if(function_exists("imagejpeg")) 
	{
		header ("Content-type: image/jpeg");
		$img=imagecreate(47,20);
		$blue=imagecolorallocate($img,102,102,102);
		$white=ImageColorAllocate($img,255,255,255);
		$black=ImageColorAllocate($img,71,71,71);
		imagefill($img,0,0,$blue);
		imagestring($img,5,6,3,$key,$white);
		for($i=0;$i<90;$i++) //加入干扰象素
		{
			imagesetpixel($img,rand()%70,rand()%30,$black);
		}
		imagejpeg($img);
		imagedestroy($img);
	}
	elseif (function_exists("imagepng"))
	{
		header ("Content-type: image/png");
		$img=imagecreate(47,20);
		$blue=imagecolorallocate($img,102,102,102);
		$white=ImageColorAllocate($img,255,255,255);
		$black=ImageColorAllocate($img,71,71,71);
		imagefill($img,0,0,$blue);
		imagestring($img,5,6,3,$key,$white);
		for($i=0;$i<90;$i++) //加入干扰象素
		{
			imagesetpixel($img,rand()%70,rand()%30,$black);
		}
		imagepng($img);
		imagedestroy($img);
	}
	elseif (function_exists("imagegif")) 
	{
		header("Content-type: image/gif");
		$img=imagecreate(47,20);
		$blue=imagecolorallocate($img,102,102,102);
		$white=ImageColorAllocate($img,255,255,255);
		$black=ImageColorAllocate($img,71,71,71);
		imagefill($img,0,0,$blue);
		imagestring($img,5,6,3,$key,$white);
		for($i=0;$i<90;$i++) //加入干扰象素
		{
			imagesetpixel($img,rand()%70,rand()%30,$black);
		}
		imagegif($img);
		imagedestroy($img);
	}
	elseif (function_exists("imagewbmp")) 
	{
		header ("Content-type: image/vnd.wap.wbmp");
		$img=imagecreate(47,20);
		$blue=imagecolorallocate($img,102,102,102);
		$white=ImageColorAllocate($img,255,255,255);
		$black=ImageColorAllocate($img,71,71,71);
		imagefill($img,0,0,$blue);
		imagestring($img,5,6,3,$key,$white);
		for($i=0;$i<90;$i++) //加入干扰象素
		{
			imagesetpixel($img,rand()%70,rand()%30,$black);
		}
		imagewbmp($img);
		imagedestroy($img);
	}
	else
	{
		ecmsSetShowKey($vname,'ecms');
		echo ReadFiletext("../data/images/ecms.jpg");
	}
}

//返回变量名
function ecmsReturnKeyVarname($v){
	if($v=='login')//登陆
	{
		$name='checkloginkey';
	}
	elseif($v=='reg')//注册
	{
		$name='checkregkey';
	}
	elseif($v=='info')//信息
	{
		$name='checkinfokey';
	}
	elseif($v=='spacefb')//空间反馈
	{
		$name='checkspacefbkey';
	}
	elseif($v=='spacegb')//空间留言
	{
		$name='checkspacegbkey';
	}
	elseif($v=='gbook')//留言
	{
		$name='checkgbookkey';
	}
	elseif($v=='feedback')//反馈
	{
		$name='checkfeedbackkey';
	}
	elseif($v=='getpassword')//取回密码
	{
		$name='checkgetpasskey';
	}
	else//评论pl
	{
		$name='checkplkey';
	}
	return $name;
}

$v=$_GET['v'];
ShowKey($v);
?>