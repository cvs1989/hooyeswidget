<?php


require(dirname(__FILE__)."/"."global.php");
$_GET['_fromurl'] && $_fromurl=$_GET['_fromurl'];
//����ͬ����¼
if($webdb[passport_type])
{
	if($action=="quit")
	{
		if( ereg("^dzbbs",$webdb[passport_type]) )
		{
			//5.0ʹ��$tablepre,5.5ʹ��$cookiepre
			set_cookie("{$cookiepre}auth","");
			set_cookie("{$cookiepre}sid","");
			set_cookie("{$tablepre}auth","");
			set_cookie("{$tablepre}sid","");
			set_cookie("passport","");
			setcookie("adminID","",0,"/");	//ͬ����̨�˳�
			$login=uc_user_synlogout();
			refreshto("$FROMURL","�ɹ��˳�$login",1);
			//header("location:$FROMURL");
			//�����������˳�ǰ��ҳ��,����԰���һ��ɾ��,����һ���//ȥ������
			//header("location:$FROMURL");
			exit;
		}
		elseif( ereg("^pwbbs",$webdb[passport_type]) )
		{
			set_cookie(CookiePre().'_winduser',"");
			setcookie("adminID","",0,"/");	//ͬ����̨�˳�
			if(!$fromurl){
				$fromurl="$webdb[www_url]/";
			}
			header("location:$fromurl");
			//�����������˳�ǰ��ҳ��,����԰���һ��ɾ��,����һ���//ȥ������
			//header("location:$FROMURL");
			exit;
		}
		elseif( ereg("^dvbbs",$webdb[passport_type]) )
		{
			set_cookie("{$cookieprename}userid","");
			set_cookie("{$cookieprename}username","");
			set_cookie("{$cookieprename}password","");
			setcookie("adminID","",0,"/");	//ͬ����̨�˳�
			header("location:$FROMURL");
			//�����������˳�ǰ��ҳ��,����԰���һ��ɾ��,����һ���//ȥ������
			//header("location:$FROMURL");
			exit;
		}
		else
		{
			setcookie("adminID","",0,"/");	//ͬ����̨�˳�
			header("location:$TB_url/$TB_quit");
			exit;
		}
	}
}

//�˳�
if($action=="quit")
{
	set_cookie("passport","");
	setcookie("adminID","",0,"/");	//ͬ����̨�˳�
	if(!$fromurl){
		$fromurl="$webdb[www_url]/";
	}
	echo "<META HTTP-EQUIV=REFRESH CONTENT='0;URL=$fromurl'>";
	//�����������˳�ǰ��ҳ��,����԰���һ��ɾ��,����һ���//ȥ������
	//header("location:$FROMURL");
	exit;
}
else
{	//��¼
	if($lfjid){
		showerr("���Ѿ���¼��,�벻Ҫ�ظ���¼,Ҫ���µ�¼����<A HREF='$webdb[www_url]/do/login.php?action=quit'>��ȫ�˳�</A>");
	}
	if($step==2){
		$login=user_login($username,$password,$cookietime);
		if($login==-1){
			showerr("��ǰ�û�������,����������");
		}elseif($login==0){
			showerr("���벻��ȷ,�����������");
		}
		if($fromurl&&!eregi("login\.php",$fromurl)&&!eregi("reg\.php",$fromurl)){
			$jumpto=$fromurl;
		}elseif($FROMURL&&!eregi("login\.php",$FROMURL)&&!eregi("reg\.php",$FROMURL)){
			$jumpto=$FROMURL;
		}else{
			$jumpto="$webdb[www_url]/";
		}
		refreshto("$jumpto","��¼�ɹ�",1);
	}
	require(PHP168_PATH."inc/head.php");
	require(html("login"));
	require(PHP168_PATH."inc/foot.php");
}
?>