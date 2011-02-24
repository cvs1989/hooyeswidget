<?php
//require("../b/member/main.php");
header("Location:../b/member/main.php");
exit;

require_once(dirname(__FILE__)."/"."global.php");
@include_once(PHP168_PATH."php168/all_area.php");
if(!$lfjid){
	showerr("你还没登录");
}

if($lfjdb[sex]==1){
	$lfjdb[sex]='男';
}elseif($lfjdb[sex]==2){
	$lfjdb[sex]='女';
}else{
	$lfjdb[sex]='保密';
}

$group_db=$db->get_one("SELECT totalspace,grouptitle FROM {$pre}group WHERE gid='$lfjdb[groupid]' ");

//用户已使用空间
$lfjdb[usespace]=number_format($lfjdb[usespace]/(1024*1024),3);

//系统允许使用空间
$space_system=number_format($webdb[totalSpace],0);

//用户组允许使用空间
$space_group=number_format($group_db[totalspace],0);

//用户本身具有的空间
$space_user=number_format($lfjdb[totalspace]/(1024*1024),0);

//用户余下可用空间大小
$onlySpace=number_format($webdb[totalSpace]+$group_db[totalspace]+$lfjdb[totalspace]/(1024*1024)-$lfjdb[usespace],3);

$lfjdb[lastvist]=date("Y-m-d H:i:s",$lfjdb[lastvist]);
$lfjdb[regdate]=date("Y-m-d H:i:s",$lfjdb[regdate]);
$lfjdb[money]=get_money($lfjdb[uid]);

if($lfjdb[C][endtime]&&$lfjdb[groupid]!=8){
	$lfjdb[C][endtime]=date("Y-m-d",$lfjdb[C][endtime]);
	$lfjdb[C][endtime]="于{$lfjdb[C][endtime]}截止";
}else{
	$lfjdb[C][endtime]='长期有效';
}

/*
if( ereg("^pwbbs",$webdb[passport_type]) ){
	@extract($db->get_one("SELECT COUNT(*) AS pmNUM FROM {$TB_pre}msg WHERE `touid`='$lfjuid' AND type='rebox' AND ifnew=1"));
}elseif( ereg("^dzbbs",$webdb[passport_type]) ){
	if($webdb[passport_type]=='dzbbs7'){
		$pmNUM=uc_pm_checknew($lfjuid);
	}else{
		@extract($db->get_one("SELECT COUNT(*) AS pmNUM FROM {$TB_pre}pms WHERE `msgtoid`='$lfjuid' AND folder='inbox' AND new=1"));
	}			
}else{
	@extract($db->get_one("SELECT COUNT(*) AS pmNUM FROM `{$pre}pm` WHERE `touid`='$lfjuid' AND type='rebox' AND ifnew='1'"));
}
*/
unset($fusername,$fuid,$vname,$vuid,$GuestbookNum,$CommentNum,$Lognum,$PhotoNum);

//是否整合博客
if(is_table("{$pre}blog_config")){
	$useblog=1;
	//extract($db->get_one("SELECT fuid,fusername FROM {$pre}blog_friend WHERE uid='$lfjuid' ORDER BY id DESC LIMIT 1"));
	extract($db->get_one("SELECT visitor FROM {$pre}blog_config WHERE uid='$lfjuid' "));
	$detail=explode("\r\n",$visitor);
	foreach( $detail AS $key=>$value){
		list($vuid,$vname,$vtime)=explode("\t",$value);
		if($vuid)break;
	}
	extract($db->get_one(" SELECT COUNT(*) AS GuestbookNum FROM {$pre}blog_guestbook WHERE cuid='$lfjuid' "));
	extract($db->get_one(" SELECT COUNT(*) AS CommentNum FROM {$pre}blog_comments WHERE cuid='$lfjuid' "));
	extract($db->get_one(" SELECT COUNT(*) AS Lognum FROM {$pre}blog_log_article WHERE uid='$lfjuid' "));
	extract($db->get_one(" SELECT COUNT(*) AS PhotoNum FROM {$pre}blog_photo_pic WHERE uid='$lfjuid' "));
}else{
	extract($db->get_one(" SELECT COUNT(*) AS CommentNum FROM {$pre}comment C LEFT JOIN {$pre}article A ON C.aid=A.aid WHERE A.uid='$lfjuid' "));
	extract($db->get_one(" SELECT COUNT(*) AS ArticleNum FROM {$pre}article WHERE uid='$lfjuid' AND mid=0 "));
	extract($db->get_one(" SELECT COUNT(*) AS PhotoNum FROM {$pre}article WHERE uid='$lfjuid' AND mid=100 "));
	extract($db->get_one(" SELECT COUNT(*) AS DownNum FROM {$pre}article WHERE uid='$lfjuid' AND mid=101 "));
	extract($db->get_one(" SELECT COUNT(*) AS MvNum FROM {$pre}article WHERE uid='$lfjuid' AND mid=102 "));
}

unset($articleDB,$i);
$query = $db->query("SELECT * FROM {$pre}article WHERE uid='$lfjuid' AND yz!=2 ORDER BY aid DESC LIMIT 14");
while($rs = $db->fetch_array($query)){
	$i++;
	$rs[cl]=$i%2==0?'t2':'t1';
	$rs[posttime]=date("Y-m-d H:i:s",$rs[posttime]);
	$articleDB[]=$rs;
}

unset($imgDB);
$query = $db->query("SELECT * FROM {$pre}article WHERE uid='$lfjuid' AND yz!=2 AND ispic=1 ORDER BY aid DESC LIMIT 6");
while($rs = $db->fetch_array($query)){
	$rs[picurl]=tempdir($rs[picurl]);
	$rs[title]=get_word($rs[title],18);
	$imgDB[]=$rs;
}

require(dirname(__FILE__)."/"."head.php");
require(dirname(__FILE__)."/"."template/map.htm");
require(dirname(__FILE__)."/"."foot.php");

?>