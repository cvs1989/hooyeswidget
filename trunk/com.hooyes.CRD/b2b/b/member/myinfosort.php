<?php
require(dirname(__FILE__)."/"."global.php");

if(!$action){
	$query=$db->query("select * from {$_pre}mysort where uid='$lfjuid' order by listorder desc;");
	while($rs=$db->fetch_array($query)){
		$mysort[$rs[ctype]][]=$rs;
	}
	
}elseif($action=='add'){
	if(!$sortname || strlen($sortname)>20)	showerr("�������Ʋ���Ϊ�գ���С��20���ַ�");
	extract($db->get_one("select count(*) as maxnum from {$_pre}mysort where uid='$lfjuid' "));
	$webdb[maxMysort]=$webdb[maxMysort]?$webdb[maxMysort]:10;
	if($maxnum > $webdb[maxMysort])howerr("��Ǹ����ֻ�����{$webdb[maxMysort]}����Ϣ����");
	$db->query("INSERT INTO `{$_pre}mysort` ( `ms_id` , `uid` , `sortname` , `fup` , `listorder` , `ctype` , `hits` , `best` )
	VALUES ('', '$lfjuid', '$sortname', '$fup', '$listorder', '$ctype', '0', '0');");
	refreshto("?","��ӳɹ�",1);
	
}elseif($action=='update'){
	if(!$sortname || strlen($sortname)>20)	showerr("�������Ʋ���Ϊ�գ���С��20���ַ�");
	if(!$edit_ms_id) showerr("�Ƿ�����");
	$db->query("update `{$_pre}mysort` set 	`sortname`='$sortname',	`listorder`='$listorder'	where ms_id='$edit_ms_id' and uid='$lfjuid'");
	refreshto("?","�༭�ɹ�",1);
	
}elseif($action=='del'){
	
	if(!$del_ms_id) showerr("�Ƿ�����");
	/* $hava=$db->get_one("select count(*) as  maxnum from {$_pre}content where ms_id='$del_ms_id'");
	if($hava[maxnum]>0){
	showerr("�ǿշ��࣬����ɾ��,�ȴ���˷����е���Ϣ");
	} */
	$db->query("delete from `{$_pre}mysort` where ms_id='$del_ms_id'");
	refreshto("?","ɾ���ɹ�",1);
}


require(dirname(__FILE__)."/"."head.php");
require(dirname(__FILE__)."/"."template/myinfosort.htm");
require(dirname(__FILE__)."/"."foot.php");
?>