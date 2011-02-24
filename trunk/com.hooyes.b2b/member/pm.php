<?php
require(dirname(__FILE__)."/"."global.php");

if(!$lfjid){
	showerr("你还没登录");
}

if(defined("UC_CONNECT")){
	uc_pm_location($lfjuid);
	exit;
}
if( ereg("^pwbbs",$webdb[passport_type]) &&!is_array($db_modes) )
{
	if(is_table("{$TB_pre}msgc")){
		$newpw=1;
	}else{
		$newpw=0;
	}
}

$linkdb=array(
			"收件箱"=>"?job=list",
			"写新消息"=>"?job=send",
			"清空收件箱"=>"?job=clean"
			);
if($job=='list')
{	
	if(!$page){
		$page=1;
	}
	$rows=20;
	$min=($page-1)*$rows;
	if( ereg("^pwbbs",$webdb[passport_type]) &&!is_array($db_modes) )
	{
		if($newpw){
			$SQL="SELECT A.*,B.* FROM {$TB_pre}msg A LEFT JOIN {$TB_pre}msgc B ON A.mid=B.mid WHERE A.`touid`='$lfjuid' AND A.type='rebox' ORDER BY A.mid DESC LIMIT $min,$rows";
		}else{
			$SQL="SELECT * FROM {$TB_pre}msg WHERE `touid`='$lfjuid' AND type='rebox' ORDER BY mid DESC LIMIT $min,$rows";
		}		
		$showpage=getpage("{$TB_pre}msg","WHERE `touid`='$lfjuid' AND type='rebox'","?job=$job",$rows);
	}
	elseif( ereg("^dzbbs",$webdb[passport_type]) )
	{
		$SQL="SELECT pmid AS mid,msgfrom AS username,msgfromid AS fromuid,msgtoid AS touid,new AS ifnew,subject AS title,dateline AS mdate,message AS content FROM {$TB_pre}pms WHERE `msgtoid`='$lfjuid' AND folder='inbox' ORDER BY pmid DESC LIMIT $min,$rows";
		$showpage=getpage("{$TB_pre}pms","WHERE `msgtoid`='$lfjuid' AND folder='inbox'","?job=$job",$rows);
	}
	else
	{
		$SQL="SELECT * FROM `{$pre}pm` WHERE `touid`='$lfjuid' AND type='rebox' ORDER BY mid DESC LIMIT $min,$rows";
		$showpage=getpage("`{$pre}pm`","WHERE `touid`='$lfjuid' AND type='rebox'","?job=$job",$rows);
	}
	
	$query = $db->query($SQL);
	while($rs = $db->fetch_array($query))
	{
		if($rs[ifnew]){
			$rs[state]="<A style='color:red;'>未读</A>";
		}else{
			$rs[state]="已读";
		}
		$rs[mdate]=date("Y-m-d H:i",$rs[mdate]);
		$listdb[]=$rs;
	}
	require(dirname(__FILE__)."/"."head.php");
	require(dirname(__FILE__)."/"."template/pm/list.htm");
	require(dirname(__FILE__)."/"."foot.php");
}
elseif($job=='read')
{
	if( ereg("^pwbbs",$webdb[passport_type]) &&!is_array($db_modes) )
	{
		if($newpw){
			$SQL="SELECT A.*,B.* FROM {$TB_pre}msg A LEFT JOIN {$TB_pre}msgc B ON A.mid=B.mid WHERE A.`touid`='$lfjuid' AND A.type='rebox' AND A.mid='$mid'";
		}else{
			$SQL="SELECT * FROM {$TB_pre}msg WHERE `touid`='$lfjuid' AND type='rebox' AND mid='$mid'";
		}
		
		$db->query("UPDATE {$TB_pre}msg SET `ifnew`=0 WHERE `touid`='$lfjuid' AND type='rebox' AND mid='$mid'");
		$rs=$db->get_one("SELECT * FROM {$TB_pre}msg WHERE `touid`='$lfjuid' AND type='rebox' AND ifnew=1");
		if(!$rs){
			$array=array(
				'uid'=>$lfjuid,
				'username'=>$lfjid,
				'newpm'=>0
			);
			$userDB->edit_pw_member($array);
		}
	}
	else
	{
		$SQL="SELECT * FROM `{$pre}pm` WHERE `touid`='$lfjuid' AND type='rebox' AND mid='$mid'";
		$db->query("UPDATE `{$pre}pm` SET `ifnew`=0 WHERE `touid`='$lfjuid' AND type='rebox' AND mid='$mid'");
	}
	$rsdb=$db->get_one($SQL);
	$rsdb[mdate]=date("Y-m-d H:i",$rsdb[mdate]);

	require_once(ROOT_PATH."inc/encode.php");
	$rsdb[content]=format_text($rsdb[content]);

	require(dirname(__FILE__)."/"."head.php");
	require(dirname(__FILE__)."/"."template/pm/read.htm");
	require(dirname(__FILE__)."/"."foot.php");
}
elseif($job=='del')
{
	if( ereg("^pwbbs",$webdb[passport_type]) &&!is_array($db_modes) )
	{
		if($newpw){
			$SQL="DELETE FROM {$TB_pre}msg WHERE `touid`='$lfjuid' AND type='rebox' AND mid='$mid'";
			if($db->get_one("SELECT * FROM {$TB_pre}msg WHERE `touid`='$lfjuid' AND type='rebox' AND mid='$mid'")){
				$db->query("DELETE FROM {$TB_pre}msgc WHERE mid='$mid'");
			}
		}else{
			$SQL="DELETE FROM {$TB_pre}msg WHERE `touid`='$lfjuid' AND type='rebox' AND mid='$mid'";
		}
		
	}
	elseif( ereg("^dzbbs",$webdb[passport_type]) )
	{
		$SQL="DELETE FROM {$TB_pre}pms WHERE `msgtoid`='$lfjuid' AND folder='inbox' AND pmid='$mid'";
	}
	else
	{
		$SQL="DELETE FROM `{$pre}pm` WHERE `touid`='$lfjuid' AND type='rebox' AND mid='$mid'";
	}

	$db->query($SQL);
	refreshto("pm.php?job=list","删除成功",1);
}
elseif($job=='clean')
{
	if( ereg("^pwbbs",$webdb[passport_type]) &&!is_array($db_modes) )
	{
		$SQL="DELETE FROM {$TB_pre}msg WHERE `touid`='$lfjuid' AND type='rebox' ";
		$array=array(
				'uid'=>$lfjuid,
				'username'=>$lfjid,
				'newpm'=>0
			);
		$userDB->edit_pw_member($array);
	}
	else
	{
		$SQL="DELETE FROM `{$pre}pm` WHERE `touid`='$lfjuid' AND type='rebox' ";
	}
	if(!$step){
		require("head.php");
		echo "<CENTER><br> <br><b><font color=red>你确实要清空收件箱吗？不可恢复！</font><br> <br><A HREF='?job=$job&step=2'>[是]</A>　　　　　<A HREF='?job=list'>[否]</A></b></CENTER>";
		exit;
	}
	$db->query($SQL);
	refreshto("pm.php?job=list","收件箱已清空",1);
}
elseif($job=='send')
{
	if($step==2)
	{
		$rsdb=$userDB->get_passport($touser,'name');
		if(!$rsdb)
		{
			showerr("当前用户不存在");
		}
		if(!$title){
			showerr("标题不能为空");
		}
		if(strlen($array[title])>100){
			showerr("标题太长了!");
		}
		$array[touid]=$rsdb[uid];
		$array[fromuid]=$lfjuid;
		$array[fromer]=$lfjid;
		$array[title]=filtrate($title);
		$array[content]=filtrate($content);
		pm_msgbox($array);
		refreshto("?job=list","发送成功",1);
	}
	if($uid){
		$rsdb = $userDB->get_passport($uid);
		$username = $rsdb[username];
	}
	require(dirname(__FILE__)."/"."head.php");
	require(dirname(__FILE__)."/"."template/pm/send.htm");
	require(dirname(__FILE__)."/"."foot.php");
}

?>