<?php
require_once("global.php");
$linkdb=array("Ʒ���б�"=>"?","���Ʒ��"=>"?job=add");
$maxbrandnum=200;
if(!$job && !$action){
	if($keyword){
		$where=" and name like('%$keyword%') ";
	}
	$query=$db->query("select * from `{$_pre}brand` where is_son='0' $where order by listorder desc limit 0,$maxbrandnum");
	while($rs=$db->fetch_array($query)){
		$rs[name]="<strong>$rs[name]</strong>";
		$rs[posttime]=$rs[posttime]?date("Y-m-d H:i:s",$rs[posttime]):"&nbsp;";
		$rs[is_html]=$rs[is_html]?"����":"����";
		$rs[yz]=$rs[yz]?"����֤":"δ��֤";
		$rs[posttype]=!$rs[is_post]?"ϵͳ":"�û�";
		$rs[level]=$rs[level]?"<font color=red>���Ƽ�</font>":"δ�Ƽ�";
		$rs[picurl]=$rs[picurl]?$webdb[www_url].'/'.$webdb[updir].'/brand/'.$rs[picurl]:"";
		$listdb[]=$rs;
		
		//�г���Ʒ��
		$query2=$db->query("select * from `{$_pre}brand` where is_son='1' and fbid='$rs[bid]' order by listorder desc ");
		while($rs2=$db->fetch_array($query2)){
			$rs2[name]="<font color=#ababab>$rs[name] &gt; </font>".$rs2[name];
			$rs2[posttime]=$rs2[posttime]?date("Y-m-d H:i:s",$rs2[posttime]):"&nbsp;";
			$rs2[is_html]=$rs2[is_html]?"����":"����";
			$rs2[is_son]=$rs2[is_son]?"��Ʒ��":"";
			$rs2[yz]=$rs2[yz]?"����֤":"δ��֤";
			$rs2[posttype]=!$rs2[is_post]?"ϵͳ":"�û�";
			$rs2[level]=$rs2[level]?"<font color=red>���Ƽ�</font>":"δ�Ƽ�";
			$rs2[picurl]=$rs2[picurl]?$webdb[www_url].'/'.$webdb[updir].'/brand/'.$rs2[picurl]:"";
			$listdb[]=$rs2;
		}			
		
	}
	require("head.php");
	require("template/brand/list.htm");
	require("foot.php");
	
}elseif($job=='add'){
	
	$brand_option=getbrandoptions();
	
	require("head.php");
	require("template/brand/add.htm");
	require("foot.php");

}elseif($action == 'add'){
	
	//�Ƿ��Ѿ����˼���
	$rs=$db->get_one("select count(*) as num from `{$_pre}brand`");
	if(intval($rs[num])>=$maxbrandnum){
			showerr("��Ǹ����ǰ�汾ֻ�����{$maxbrandnum}��Ʒ��");
	}
	//���ݼ��
	check_postdb();
	$is_son=intval($postdb[fbid])>0?1:0;
	//admin��ӵģ�������
	$yz=1;
	$yz_time=$timestamp;
	$postuid='';
	$postusername='';
	$postrid='';
	$postcompany='';
	$is_post=0;
	if($postdb[config]){
		$postdb[config]=serialize($postdb[config]);
	}
	if(intval($postdb[is_html])<1){
		$postdb[html_name]='';
	}
	//�ϴ�LOGO
	if(is_uploaded_file($_FILES[postfile][tmp_name])){
		$array[name]=is_array($postfile)?$_FILES[postfile][name]:$postfile_name;
		$array[path]=$webdb[updir]."/brand/";
		$array[size]=is_array($postfile)?$_FILES[postfile][size]:$postfile_size;
		$picurl=upfile(is_array($postfile)?$_FILES[postfile][tmp_name]:$postfile,$array);
	}else{
		$picurl='';
	}
	

	$postdb[video]=serialize($postdb[video]);
	//���
	
	$db->query("INSERT INTO `{$_pre}brand` ( `bid` , `name` , `picurl` , `fbid` , `is_son` , `yz` , `yz_time` , `postuid` , `postusername` , `postrid` , `postcompany` , `posttime` , `is_post` , `listorder` , `template` , `config` , `level` , `website` , `description` , `metakeywords` , `metadescription` , `vs_fid` , `is_html` , `html_name`,`video` ,`hits`) 
VALUES ('', '$postdb[name]', '$picurl', '$postdb[fbid]', '$is_son', '$yz', '$yz_time', '$postuid', '$postusername', '$postrid', '$postcompany', '$timestamp', '$is_post', '$postdb[listorder]', '$postdb[template]', '$postdb[config]', '$postdb[level]', '$postdb[website]', '$postdb[description]', '$postdb[metakeywords]', '$postdb[metadescription]', '', '$postdb[is_html]', '$postdb[html_name]','$postdb[video]',0);");
	$bid=$db->insert_id();
	brand_cache();
	refreshto("?job=setsort&bid=$bid","�ɹ�����",1);
	
}elseif($job=='edit'){

	if(!$bid) showerr("�Ƿ�����");
	$rsdb=$db->get_one("select * from `{$_pre}brand` where bid='$bid' limit 1");
	$brand_option=getbrandoptions($rsdb[fbid]);
	$level[$rsdb[level]]=" checked";
	$is_html[$rsdb[is_html]]=" checked";
	$rsdb[oldpicurl]=$rsdb[picurl];
	$rsdb[picurl]=$rsdb[picurl]?$webdb[www_url].'/'.$webdb[updir].'/brand/'.$rsdb[picurl]:"";
	
	$rsdb[video]=unserialize($rsdb[video]);
	$rsdb[video_type][$rsdb[video][type]]=" selected";
	
	require("head.php");
	require("template/brand/add.htm");
	require("foot.php");
	
}elseif($action == 'edit'){

	if(!$bid) showerr("�Ƿ�����");
	//���ݼ��
	check_postdb();
	$is_son=intval($postdb[fbid])>0?1:0;
	if($postdb[config]){
		$postdb[config]=serialize($postdb[config]);
	}
	if(intval($postdb[is_html])<1){
		$postdb[html_name]='';
	}
	
	//�ϴ�LOGO
	if(is_uploaded_file($_FILES[postfile][tmp_name])){
		$array[name]=is_array($postfile)?$_FILES[postfile][name]:$postfile_name;
		$array[path]=$webdb[updir]."/brand/";
		$array[size]=is_array($postfile)?$_FILES[postfile][size]:$postfile_size;
		$picurl=upfile(is_array($postfile)?$_FILES[postfile][tmp_name]:$postfile,$array);
		@unlink($array[path].$oldpicurl);
	}else{
		$picurl=$oldpicurl;
	}
	
	//����
	if($postdb[config]){
		$postdb[config]=serialize($postdb[config]);
	}
	if(intval($postdb[is_html])<1){
		$postdb[html_name]='';
	}
	$postdb[video]=serialize($postdb[video]);

	$db->query("update {$_pre}brand set
	name='$postdb[name]',
	picurl='$picurl',
	fbid='$postdb[fbid]',
	is_son='$is_son',
	listorder='$postdb[listorder]',
	level='$postdb[level]',
	website='$postdb[website]',
	description='$postdb[description]',
	template='$postdb[template]',
	metakeywords='$postdb[metakeywords]',
	metadescription='$postdb[metadescription]',
	is_html='$postdb[is_html]',
	html_name='$postdb[html_name]',
	video='$postdb[video]'
	where bid='$bid'");
	brand_cache();
	refreshto("?","�ɹ�����",1);
	
}elseif($job=='setsort'){
	
	if(!$bid) showerr("�Ƿ�����");
	$rsdb=$db->get_one("select * from `{$_pre}brand` where bid='$bid' limit 1");
	$fup_select=choose_sort(0,0,0,1);
	$vs_fid=explode(",",$rsdb[vs_fid]);

	foreach($vs_fid as $fid){
		$fid=intval($fid);
		if($fid>0){
			$vs_fid_form.="<div id='div_$fid'><input type='checkbox' name='vs_fid[]' value='$fid' checked onclick='removeFid(this);'>".$Fid_db[name][$fid]."</div>";
		}
	}
	require("head.php");
	require("template/brand/setsort.htm");
	require("foot.php");
	
}elseif($action == 'setsort'){	
	
	if(!$bid) showerr("�Ƿ�����");
	$vs_fid=implode(",",$vs_fid);
	$db->query("update {$_pre}brand set vs_fid='$vs_fid'  where bid='$bid' ");
	refreshto("?","���óɹ�",1);

}elseif($action == 'ishtml'){
	
	if(!$bid) showerr("�Ƿ�����");
	$me=$db->get_one("select is_html,html_name from `{$_pre}brand` where bid='$bid' limit 1");
	//$is_html=$me[is_html]?0:1;
	$is_html=1;
	$db->query("update {$_pre}brand set is_html='$is_html' where bid='$bid'");
	brand_cache();
	if($is_html==1){
		brand_html($bid);
	}
	refreshto("?","���óɹ�",1);

}elseif($action == 'delhtml'){
	
	if(!$bid) showerr("�Ƿ�����");
	$db->query("update {$_pre}brand set is_html='0' where bid='$bid'");
	brand_cache();
	refreshto("?","���óɹ�",1);	
}elseif($action == 'yz'){

	if(!$bid) showerr("�Ƿ�����");
	$me=$db->get_one("select yz from `{$_pre}brand` where bid='$bid' limit 1");
	$yz=$me[yz]?0:1;
	$yz_time=$me[yz]?$timestamp:'';
	$db->query("update {$_pre}brand set yz='$yz',yz_time='$yz_time' where bid='$bid'");
	brand_cache();
	refreshto("?","���óɹ�",1);

}elseif($action == 'level'){

	if(!$bid) showerr("�Ƿ�����");
	$me=$db->get_one("select level from `{$_pre}brand` where bid='$bid' limit 1");
	$level=$me[level]?0:1;
	
	$db->query("update {$_pre}brand set level='$level' where bid='$bid'");
	brand_cache();
	refreshto("?","���óɹ�",1);
}elseif($action == 'del'){

	if(!$bid) showerr("�Ƿ�����");
	$me=$db->get_one("select count(*) as num from `{$_pre}brand` where fbid='$bid' limit 1");
	if(intval($me[num])>0){
		showerr("��Ʒ�ƻ������µ�Ʒ�ƣ�����ɾ������ȷʵҪɾ��������ɾ��������Ʒ��");
	}
	$me=$db->get_one("select * from `{$_pre}brand` where bid='$bid' limit 1");
	if($me[picurl]){
		@unlink($webdb[updir]."/brand/".$me[picurl]);	
	}
	if($me[is_html] && file_exists(PHP168_PATH.$me[html_name])){
		@unlink(PHP168_PATH.$me[html_name]);
	}
	
	$db->query("delete from `{$_pre}brand` where bid='$bid' limit 1");
	brand_cache();
	refreshto("?","ɾ���ɹ�",1);
	
}elseif($action == 'listorder'){
	
	if($listorder){
		foreach($listorder as $key=>$val){
			$db->query("update {$_pre}brand set listorder='$val' where bid='$key'");
		}		
	}
	brand_cache();
	refreshto("?","���óɹ�",1);
	
}elseif($action == 'makehtml'){
	
	$shangbid=intval($shangbid);
	if(!$shangbid){
		$shangbid=0;
	}
	
	$rsdb=$db->get_one("select bid,name from {$_pre}brand where bid>$shangbid order by bid asc limit 0,1");
	if(!$rsdb){
		refreshto("?","�������",1);
	}
	$bid=$rsdb[bid];
	$db->query("update {$_pre}brand set is_html='1' where bid='$bid'");
	brand_html($bid);
	$shangbid=$bid;
	refreshto("?action=makehtml&shangbid=".$shangbid,"���óɹ�[{$rsdb[name]}]",1);


}elseif($action=='updatecache'){
	brand_cache();
	refreshto("?","���³ɹ�",1);
}




function getbrandoptions($select=0){
	global $db,$_pre;
	$query=$db->query("select * from `{$_pre}brand` where fbid=0 and is_son=0 order by listorder desc");
	while($rs=$db->fetch_array($query)){
		$ck=$select==$rs[bid]?" selected":"";
		$brand_option.="<option value='$rs[bid]' $ck>$rs[name]</option>";
	}
	return $brand_option;
}
function check_postdb(){
	global $webdb,$postdb;
	
	if(!$postdb[name]) showerr('������Ʒ������');
	if($postdb[website]){
		if(substr(strtolower($postdb[website]),0,5)!='http:'){
			showerr("Ʒ�ƵĹ�����ַ������http://��ͷ");
		}
	}
	if($postdb[template]){
	
		if(!file_exists(PHP168_PATH.$postdb[template])){
		 	showerr("���ṩ��ģ���޷�����");
		}
	}
	/*
	if($postdb[is_html]=='1'){
		if(!$postdb[html_name]){
			showerr("�����뾲̬�ļ�����");
		}

	}
	*/

	return true;
}


function brand_html($bid){
		global $Mdomain;
		$post_data = array();
		$post_data['bid'] = "$bid";	
		$post_data['PHP_AUTH_USER'] = "php168";	
		$post_data['PHP_AUTH_PW'] = "xietian".intval(date("d"));	
		$url=$Mdomain.'/brandview.php';
		$o="";
		foreach ($post_data as $k=>$v)
		{
			$o.= "$k=".urlencode($v)."&";
		}
		$post_data=substr($o,0,-1);
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_URL,$url);
		//Ϊ��֧��cookie
		curl_setopt($ch, CURLOPT_COOKIEJAR, 'cookie.txt');
		curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
		$result = curl_exec($ch);
}
?>