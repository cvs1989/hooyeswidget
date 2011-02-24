<?php
!function_exists('html') && exit('ERR');
if(!$id)
{
	showerr("ID不存在");
}
elseif(!$fid)
{
	showerr("FID不存在");
}
elseif(!$rid)
{
	showerr("RID不存在");
}
elseif(!$i_id)
{
	showerr("i_id不存在");
}

$midDB=$db->get_one("SELECT *,config AS m_config FROM {$pre}article_module WHERE id='$mid'");
if(!$midDB)
{
	showerr(" MID有误 ");
}

$erp=get_id_table($id);
$rsdb=$db->get_one("SELECT A.*,B.* FROM {$pre}article$erp A LEFT JOIN `{$pre}article_content_$mid` B ON A.aid=B.aid WHERE B.aid='$id' AND B.rid='$rid'");
$fidDB=$db->get_one("SELECT * FROM {$pre}sort WHERE fid='$rsdb[fid]'");

if($fidDB[admin]&&$lfjid){
	$detail=explode(",",$fidDB[admin]);
	if( in_array($lfjid,$detail) ){
		$web_admin=1;
	}
}

if($fid!=$rsdb[fid])
{
	showerr("FID有误,不一致");
}

if($fidDB[allowdownload]&&!$web_admin&&$lfjuid!==$rsdb[uid]){
	$detail=explode(",",$fidDB[allowdownload]);
	if( !in_array($groupdb['gid'],$detail) ){
		showerr("你所在的用户组在本栏目无权限下载");
	}
}
if($rsdb[allowdown]&&!$web_admin&&$lfjuid!==$rsdb[uid]){
	$detail=explode(",",$rsdb[allowdown]);
	if( !in_array($groupdb['gid'],$detail) ){
		showerr("你所在的用户组本文设置无权限下载");
	}
}

$m_config=unserialize($midDB[m_config]);

foreach( $m_config[field_db] AS $key=>$rs )
{
	if($rs[allowview]&&$key==$field)
	{
		$detail=explode(",",$rs[allowview]);
		if(!$web_admin&&$lfjuid!==$rsdb[uid]&&!in_array($groupdb['gid'],$detail))
		{
			if(!$lfjid){
				showerr("<font color=red>请先登录!</font>");
			}else{
				showerr("<font color=red>你的权限不够!</font>");
			}
		}
	}
}

$rsdb[$field]=str_replace("\r","",$rsdb[$field]);
$detail=explode("\n",$rsdb[$field]);
unset($rsdb[$field]);
list($url,$true_name,$fen)=explode("@@@",$detail[$ti]);

if( !$web_admin&&$lfjuid!==$rsdb[uid]&&$fen>0 )
{
	$fen=intval($fen);
	if(get_money($lfjuid)<$fen){
		showerr("你的{$webdb[MoneyName]}不足{$fen}{$webdb[MoneyDW]}");
	}else{
		plus_money($lfjuid,-$fen);
	}
}
elseif( !$web_admin&&$lfjuid!==$rsdb[uid]&&$rsdb[money]>0 )
{
	if(get_money($lfjuid)<$rsdb[money]){
		showerr("你的{$webdb[MoneyName]}不足{$rsdb[money]}{$webdb[MoneyDW]}");
	}else{
		plus_money($lfjuid,-$rsdb[money]);
	}
}

//更新点击量
update_hits($mid,$midDB[keywords],$id,$rid,$rsdb[hits_time]);

if(!$true_name){
	$true_name=str_replace(strrchr($url,'.'),'',basename($url));
}
$true_url=tempdir($url);
if(!$webdb[DownLoad_readfile]){
	header("location:$true_url");
	exit;
}

if(file_exists(PHP168_PATH."$webdb[updir]/$url"))
{
	$filetype=substr(strrchr($url,'.'),1);
	ob_end_clean();
	header('Last-Modified: '.gmdate('D, d M Y H:i:s',time()).' GMT');
	header('Pragma: no-cache');
	header('Content-Encoding: none');
	header('Content-Disposition: attachment; filename='."$true_name.$filetype");
	header('Content-type: '.$filetype);
	header('Content-Length: '.filesize(PHP168_PATH."$webdb[updir]/$url"));
	readfile(PHP168_PATH."$webdb[updir]/$url");
	exit;
}else{
	$filetype=substr(strrchr($url,'.'),1);
	ob_end_clean();
	header('Last-Modified: '.gmdate('D, d M Y H:i:s',time()).' GMT');
	header('Pragma: no-cache');
	header('Content-Encoding: none');
	header('Content-Disposition: attachment; filename='."$true_name.$filetype");
	header('Content-type: '.$filetype);
	readfile($true_url);
	exit;
}
//header("location:$true_url");exit;


//下载与播放视频更新点击率
function update_hits($mid,$keyword,$aid,$rid,$time){
	global $lfjid,$db,$pre,$timestamp;
	if($keyword=='download'||$keyword=='mv'){
		if(date("W",$time)!=date("W",$timestamp)){
			$SQL.=",week_hits=1";
		}else{
			$SQL.=",week_hits=week_hits+1";
		}
		if(date("md",$time)!=date("md",$timestamp)){
			$SQL.=",day_hits=1";
		}else{
			$SQL.=",day_hits=day_hits+1";
		}
		if(date("m",$time)!=date("m",$timestamp)){
			$SQL.=",month_hits=1";
		}else{
			$SQL.=",month_hits=month_hits+1";
		}
		$db->query("UPDATE {$pre}article_content_{$mid} SET total_hits=total_hits+1,hits_time='$timestamp',hits_user=''$SQL WHERE aid='$aid' AND rid='$rid'");
	}
}
?>