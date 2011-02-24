<?php
require(dirname(__FILE__)."/".'global.php');

/**
*对用户的后台操作做记录
**/
if($action)
{
	unset($logdb);
	$logdb[]="$userdb[uid]\t$userdb[username]\t$timestamp\t$onlineip\t$FROMURL\t$WEBURL";
	@include(PHP168_PATH."cache/admin_logs.php");
	$writefile="<?php	\r\n";
	$jj=0;
	foreach($logdb AS $key=>$value){
		$jj++;
		$value=addslashes($value);
		$writefile.="\$logdb[]='$value';\r\n";
		if($jj>200){
			break;
		}
	}
	write_file(PHP168_PATH."cache/admin_logs.php",$writefile);
	//删除缓存文件
	delete_cache_file($fid,$aid);
}

if($iframe==1)
{
	require(dirname(__FILE__)."/".'template/index.htm');
}
elseif($iframe=='left')
{	
	if( !table_field("{$pre}hack",'class1') ){
		@include(dirname(__FILE__)."/".'add_menu.php');
	}

	if($Smenu){
		$Smenu_color[$Smenu]='chooseModule';
	}else{
		require("menu.php");
		$Smenu_color['www']='chooseModule';
	}
	$mainlink=showmenu($menudb);	/*左边菜单*/

	unset($menu_GpartDB);
	
	if(!$ForceEnter&&$part=="often"){		
		group_menu();			//用户组自定义菜单
	}

	require(dirname(__FILE__)."/"."template/left.htm");
}
elseif($iframe=='head')
{
	require("menu.php");

	if($ForceEnter){
		$show="";
	}else{
		//$show="'快捷菜单|index.php?lfj=center&job=map|index.php?iframe=left&Smenu=&part=often',";
	}	

	foreach($base_menuName  AS $key=>$value){
		$base_menuDB[$key]="'$value|index.php?lfj=center&job=map|index.php?iframe=left&Smenu=&part=$key',";
	}
	foreach( $base_menuDB AS $key1=>$value1){
		foreach( $menu_partDB[$key1] AS $key2=>$value2){
			foreach( $menudb[$value2] AS $key=>$array){
				if($Apower[$array[power]]||$userdb[groupid]==3||$ForceEnter)
				{
					$_ckk[$key1]=1;
				}
			}
		}
		if($_ckk[$key1]){
			$show.=$base_menuDB[$key1];
		}
	}
	
	$query = $db->query("SELECT * FROM {$pre}module ORDER BY list DESC");
	while($rs = $db->fetch_array($query)){
		if($userdb[groupid]!=3&&!$ForceEnter){
			$detail=explode("\r\n",$rs[adminmember]);
			if(!$userdb[username]||!in_array($userdb[username],$detail))
			{
				continue;
			}
		}
		$show.="'{$rs[name]}|index.php?lfj=center&job=map|index.php?iframe=left&Smenu=$rs[pre]&part=',";
	}

	$show=substr($show,0,-1);
	require_once(dirname(__FILE__)."/"."template/header.htm");
}
else
{
	if(!$lfj){
		header("location:./index.php?iframe=1");exit;
	}
	$lfj=str_replace("/","",$lfj);
	
	require(dirname(__FILE__)."/"."$lfj.php");
	
	echo dirname(__FILE__)."/"."$lfj.php";
}
?>