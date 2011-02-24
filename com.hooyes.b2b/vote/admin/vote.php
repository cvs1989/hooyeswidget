<?php
!function_exists('html') && exit('ERR');
if($action=="add"&&ck_power('vote_add')){

	$postdb[begintime]&&$postdb[begintime]=preg_replace("/([\d]+)-([\d]+)-([\d]+) ([\d]+):([\d]+):([\d]+)/eis","mk_time('\\4','\\5', '\\6', '\\2', '\\3', '\\1')",$postdb[begintime]);
	$postdb[endtime]&&$postdb[endtime]=preg_replace("/([\d]+)-([\d]+)-([\d]+) ([\d]+):([\d]+):([\d]+)/eis","mk_time('\\4','\\5', '\\6', '\\2', '\\3', '\\1')",$postdb[endtime]);

	$db->query("INSERT INTO `{$pre}vote_topic` ( `name` , `about` , `type` , `limittime` , `limitip` , `ip` , `posttime` , `user` , `begintime` , `endtime` , `forbidguestvote` ) 
		VALUES (
		'$postdb[name]','$postdb[about]','$postdb[type]','$postdb[limittime]','$postdb[limitip]','$postdb[ip]','$timestamp','$postdb[user]','$postdb[begintime]','$postdb[endtime]','$postdb[forbidguestvote]'
		)");
	$rs=$db->get_one("SELECT * FROM `{$pre}vote_topic` ORDER BY cid DESC LIMIT 1");
	foreach($votedb AS $key=>$value){
		$value[title]&&$db->query("INSERT INTO `{$pre}vote_element` (`cid` , `title` , `votenum`, `img` ) VALUES ('$rs[cid]', '$value[title]', '$value[votenum]', '$value[img]')");
	}
	jump("创建成功","$admin_path&job=list");
}
elseif($job=="add"&&ck_power('vote_add'))
{
	get_admin_html('add');
}
elseif($job=="list"&&ck_power('vote_list'))
{
	$rows=50;
	$page>0 || $page=1;
	$min=($page-1)*$rows;
	$showpage=getpage("`{$pre}vote_topic`","","$admin_path&job=$job","$rows");
	$query = $db->query("SELECT * FROM `{$pre}vote_topic` ORDER BY cid DESC LIMIT $min,$rows");
	while($rs = $db->fetch_array($query)){
		$listdb[]=$rs;
	}

	get_admin_html('list');
}elseif($action=="delete"&&ck_power('vote_list')){
	$db->query("DELETE FROM `{$pre}vote_topic` WHERE cid='$cid'");
	$db->query("DELETE FROM `{$pre}vote_element` WHERE cid='$cid'");
	$db->query("DELETE FROM `{$pre}comment` WHERE cid='$cid'");
	if($aid){
		$erp=get_id_table($aid);
		$db->query("UPDATE {$pre}article$erp SET ifvote=0 WHERE aid='$aid'");
	}
	jump("删除成功","$FROMURL");
}elseif($action=="edit"&&ck_power('vote_list')){

	foreach( $_COOKIE AS $key=>$value)
	{
		if(strstr($key,'vote_limittime')){
			setcookie($key,"0",$timestamp-3600,"/");
		}
	}
	if($postdb[type]<1){
		$postdb[type]=1;
	}
	if($postdb[type]>count($votedb)){
		$postdb[type]=count($votedb);
	}
	$postdb[begintime]&&$postdb[begintime]=preg_replace("/([\d]+)-([\d]+)-([\d]+) ([\d]+):([\d]+):([\d]+)/eis","mk_time('\\4','\\5', '\\6', '\\2', '\\3', '\\1')",$postdb[begintime]);
	$postdb[endtime]&&$postdb[endtime]=preg_replace("/([\d]+)-([\d]+)-([\d]+) ([\d]+):([\d]+):([\d]+)/eis","mk_time('\\4','\\5', '\\6', '\\2', '\\3', '\\1')",$postdb[endtime]);


	$db->query("UPDATE `{$pre}vote_topic` SET name='$postdb[name]',about='$postdb[about]',type='$postdb[type]',limittime='$postdb[limittime]',limitip='$postdb[limitip]',begintime='$postdb[begintime]',endtime='$postdb[endtime]',forbidguestvote='$postdb[forbidguestvote]',ifcomment='$postdb[VoteUseComment]',votetype='$votetype',tplcode='$votetpl' WHERE cid='$cid'");
	foreach($votedb AS $key=>$v){
		if($v[id]){
			$db->query("UPDATE `{$pre}vote_element` SET title='$v[title]',votenum='$v[votenum]',list='$v[list]',img='$v[img]',describes='$v[describes]',url='$v[url]' WHERE id='$v[id]'");
		}else{
			$v[title]&&$db->query("INSERT INTO `{$pre}vote_element` (`cid` , `title` , `votenum`, `img`, `describes`, `url` ) VALUES ('$cid', '$v[title]', '$v[votenum]', '$v[img]', '$v[describes]', '$v[url]')");
		}
	}
	jump("修改成功",$FROMURL);
}elseif($job=="edit"&&ck_power('vote_list')){
	$votetpl0=read_file(Mpath."template/default/vote_js/0.htm");
	$votetpl0=str_replace("\r\n","",$votetpl0);
	$votetpl0=str_replace("'","\'",$votetpl0);

	$votetpl1=read_file(Mpath."template/default/vote_js/1.htm");
	$votetpl1=str_replace("\r\n","",$votetpl1);
	$votetpl1=str_replace("'","\'",$votetpl1);

	$votetpl2=read_file(Mpath."template/default/vote_js/2.htm");
	$votetpl2=str_replace("\r\n","",$votetpl2);
	$votetpl2=str_replace("'","\'",$votetpl2);

	$rsdb=$db->get_one("SELECT * FROM `{$pre}vote_topic` WHERE cid='$cid'");
	$query = $db->query("SELECT * FROM `{$pre}vote_element` WHERE cid='$cid' ORDER BY list DESC");
	$i=0;
	while($rs = $db->fetch_array($query)){
		$i++;
		$listdb[$i]=$rs;
	}
	$nums=count($listdb);
	$rsdb_type[$rsdb[type]]=" checked ";
	$limitip[$rsdb[limitip]]=" checked ";
	$VoteUseComment[intval($rsdb[ifcomment])]=" checked ";
	$forbidguestvote[$rsdb[forbidguestvote]]=" checked ";
	$rsdb[begintime]=$rsdb[begintime]?date("Y-m-d H:i:s",$rsdb[begintime]):'';
	$rsdb[endtime]=$rsdb[endtime]?date("Y-m-d H:i:s",$rsdb[endtime]):'';
	$votetype[$rsdb[votetype]]=' checked ';
	$rsdb[type]==1?$_type[1]=' checked ':$_type[2]=' checked ';

	get_admin_html('edit');
}elseif($action=="delete_vote"&&ck_power('vote_list')){
	$db->query("DELETE FROM `{$pre}vote_element` WHERE id='$id'");
	jump("删除成功",$FROMURL,0);
}elseif($job=="make"&&ck_power('vote_list')){

	get_admin_html('make');
}
?>