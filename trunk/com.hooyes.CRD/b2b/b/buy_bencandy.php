<?php
require("global.php");
require("bd_pics.php");

if(!$id=intval($id)) showerr("û���ҵ���Ҫ���ʵ�ҳ");

$fid = intval($fid);

$params = array(
	'fid' => $fid,
	'id' => $id
);

if($jobs != 'show')
	cache_page(PHP_SELF.combine_params($params));

//choose_domain();	//�����ж�
@include(Mpath."php168/guide_fid.php");
@include(Mpath."php168/all_spfid.php");
//require_once(Mpath."inc/ip.php");
require(Mpath."inc/categories.php");
		
$bcategory->cache_read();


if(!$id=intval($id)) showerr("û���ҵ���Ҫ���ʵ�ҳ");

/**
*��ȡ��Ϣ���ĵ�����
**/
$base=$db->get_one("SELECT A.`htmlname`,B.* FROM `{$_pre}content_buy` A INNER JOIN `{$_pre}company` B ON A.uid=B.uid WHERE id='$id'");
if(!$base) showerr("��ʱ�����ṩ����Ϣ[ԭ�������ĳЩ�����Ѿ����Ƴ�]");

//������Ѿ���̬,ǿ�Ʒ��ʾ�̬
/*
if($base[htmlname]!='' && $webdb[bencandyIsHtml] && file_exists(PHP168_PATH.$base[htmlname]) && !$makehtml){
	header("Location:".$webdb[www_url]."/".$base[htmlname]);
}
*/

//��������FID
$fid=$fid?$fid:$base[fid];

$base[picurl]=$webdb[www_url].'/'.$webdb[updir].'/'.$Imgdirname.'/ico/'.$base[picurl];
$base[qy_pro_ser]=get_word($base[qy_pro_ser],50);
$base[renzheng]=getrenzheng($base[renzheng]);
$ctype=$base[ctype];

$base[qq]=getOnlinecontact('qq',$base[qq],'<br>');
$base[msn]=getOnlinecontact('msn',$base[msn],'<br>');
$base[skype]=getOnlinecontact('skype',$base[skype],'<br>');
$base[ww]=getOnlinecontact('ww',$base[ww],'<br>');
$base[services]=get_services($base);
/*
*��ȡ��Ŀ��ģ�����ò���
**/
$fidDB=$bcategory->get_one($fid);

if(!$fidDB[mid]){
	//showerr("MID������");
}



$fupId=intval($fidDB[type]?$fid:$fidDB[fup]);
/**
*ģ�����ò���
**/
//$m_config=unserialize($fidDB[m_config]);


/**
*��Ŀ���ò���
**/
//$fidDB[config]=unserialize($fidDB[config]);


/**
*��Ŀ�����ļ��û��Զ���ı���
**/
$CV=$fidDB[config][field_value];


/**
*��Ŀ����,�û��Զ��������Щʹ�������߱༭��Ҫ��������������ʵ��ַ������
**/
/*$_array=array_flip($fidDB[config][is_html]);

foreach( $fidDB[config][field_db] AS $key=>$rs)
{
	if(in_array($key,$_array))
	{
		$CV[$key]=En_TruePath($CV[$key],0);
	}
	elseif($rs[form_type]=='upfile')
	{
		$CV[$key]=tempdir($CV[$key]);
	}
}*/


$db->query("UPDATE {$_pre}content_buy SET hits=hits+1,lastview='$timestamp' WHERE id=$id");



$rsdb=$db->get_one("SELECT A.*,B.* FROM `{$_pre}content_buy` A INNER JOIN `{$_pre}content_2` B ON A.id=B.id WHERE A.id='$id'");
$rsdb[picurl]=$rsdb[picurl]?$webdb[www_url].'/'.$webdb[updir].'/'.$Imgdirname.'/'.$rsdb[picurl]:"";

$parents = $bcategory->get_parents($fid);
$guidefid = '';

foreach($parents as $v) $guidefid .= ' &gt; <a href="buy_list.php?ctype='. $ctype .'&fid='. $v['fid'] .'">'. $v['name'] .'</a>';
$guidefid .= ' &gt; <a href="buy_list.php?ctype='. $ctype .'&fid='. $bcategory->categories[$fid]['fid'] .'">'. $bcategory->categories[$fid]['name'] .'</a>';

if($rsdb[my_price]){
		$rsdb[my_price]=formartprice($rsdb[my_price]);
		$rsdb[my_price]="<strong><font color=#FF3300>$rsdb[my_price]</font></strong>Ԫ/$rsdb[quantity_type]";

}else{
	$rsdb[my_price]='�۸�����';
}

//
if($rsdb[overtime]){
	if($rsdb[overtime]<date("Y-m-d")){ $rsdb[overtime]=$rsdb[overtime]."<font color='red'>[����]</font>";
	$onclick=" onclick=\"alert('�ѹ��ڵ���Ϣ���ܱ���');return false;\"";
	}
}else{
	$rsdb[overtime]="";
}
//ͨ���̼���Ϣ�õ�����
$rsdb[showarea]=$area_DB[name][$city_DB[fup][$base[city_id]]]." ".$city_DB[name][$base[city_id]];



if($rsdb[idcard_img]&&$rsdb[idcard_yz]){
	$rsdb[idcard_img]=tempdir($rsdb[idcard_img]);
	$rsdb[idcard_show]=" <a href='javascript:' ><img src='$rsdb[idcard_img]' border='0' width='30' height='30'></a> ";
}
if($rsdb[permit_img]&&$rsdb[permit_yz]){
	$rsdb[permit_img]=tempdir($rsdb[permit_img]);
	$rsdb[permit_show]=" <a href='javascript:' ><img src='$rsdb[permit_img]' border='0' width='30' height='30'></a> ";
}
if($rsdb[othercard_img]&&$rsdb[othercard_yz]){
	$rsdb[othercard_img]=tempdir($rsdb[othercard_img]);
	$rsdb[othercard_show]=" <a href='javascript:' ><img src='$rsdb[othercard_img]' border='0' width='30' height='30'></a> ";
}

/**
*����ҳ�ķ����������Ŀ�ķ��,��Ŀ�ķ��������ϵͳ�ķ��
**/
if($rsdb[style])
{
	$STYLE=$rsdb[style];
}
elseif($fidDB[style])
{
	$STYLE=$fidDB[style];
}

//SEO
$titleDB[title]			= filtrate(strip_tags("$rsdb[title] - $fidDB[name] - $webdb[Info_webname]"));
$titleDB[keywords]		= filtrate(strip_tags("$rsdb[keywords] $webdb[Info_metakeywords]"));
$titleDB[description]	= get_word(strip_tags($rsdb[content]),200).filtrate(strip_tags("$fidDB[metadescription] $webdb[Info_metadescription]"));

if(!$rsdb)
{
	die("���ݲ�����");
}
elseif($rsdb[yz]!=1&&!$web_admin)
{
	if($rsdb[uid]!=$lfjuid){
		if($rsdb[yz]==2){
			showerr("����վ������,���޷��鿴");
		}else{
			showerr("��ʱ�����ṩ����Ϣ[ԭ�������δ���]");
		}
	}
}

/**
*��Ŀָ������Щ�û�����ܿ���Ϣ����
**/
if($fidDB[allowviewcontent])
{
	if( !$web_admin&&!in_array($groupdb[gid],explode(",",$fidDB[allowviewcontent])) )
	{
		$detail=explode(",",$fidDB[admin]);

		if(!$lfjid||!in_array($lfjid,$detail))
		{
			showerr("�������û���,��Ȩ���");
		}
	}
}


/**
*����Ϣ���ݵ����߱༭���ύ�ĸ�����ʵ·��������,textarea��ͨ�༭�����ı���ʽҲҪ������
**/
require_once(PHP168_PATH."inc/encode.php");

foreach( $m_config[field_db] AS $key=>$rs )
{
	if($rs[form_type]=='textarea')
	{
		$rsdb[$key]=format_text($rsdb[$key]);
		$rsdb[$key]=highlight_keyword($rsdb[$key]);
	}
	elseif($rs[form_type]=='ieedit')
	{
		$rsdb[$key]=En_TruePath($rsdb[$key],0);
		$rsdb[$key]=highlight_keyword($rsdb[$key]);
	}
	elseif($rs[form_type]=='upfile')
	{
		$rsdb[$key]=tempdir($rsdb[$key]);
	}
	elseif($rs[form_type]=='select'||$rs[form_type]=='radio'||$rs[form_type]=='checkbox')
	{
		if(strstr($rs[form_set],"|")){
			$rs[form_set]=str_replace("\r","",$rs[form_set]);
			$detail=explode("\n",$rs[form_set]);
			foreach( $detail AS $key2=>$value2){
				list($_key,$_value)=explode("|",$value2);
				$_key==$rsdb[$key] && $rsdb[$key]=$_value;
			}
		}
	}
	if($rs[allowview])
	{
		$detail=explode(",",$rs[allowview]);
		if(!$web_admin&&$lfjuid!=$rsdb[uid]&&!in_array($groupdb['gid'],$detail))
		{
			$rsdb[$key]="<font color=red>Ȩ�޲���,�޷��鿴!</font>";
		}
	}
}

$rsdb[ipaddress]=base64_encode($rsdb[ip]);

foreach( $m_config[imgShow_db] AS $key=>$value )
{
	$rs_db[$key]=$rsdb[$key];
	$rsdb[$key]=base64_encode($rsdb[$key]);
	$rsdb[$key] && $rsdb[$key]="<img src='$Mdomain/img.php?vid=$rsdb[$key]'>";
}
foreach( $m_config[IfMobPhone_db] AS $key=>$value )
{
	if($rs_db[$key]){
		$mobnum=$rs_db[$key];
	}else{
		$mobnum=$rsdb[$key];
	}
	$mob_area=get_mob_area($mobnum);
	$mobnum=base64_encode($mobnum);
	$rsdb[$key] && $rsdb[$key]="{$rsdb[$key]} $mob_area <A HREF='mob.php?vid=$mobnum' target='_blank'>��ѯ����</A>";
}

$rsdb[_mobphone]=$rsdb[mobphone];
$rsdb[_telephone]=$rsdb[telephone];
$rsdb[_msn]=$rsdb[msn];
$rsdb[_oicq]=$rsdb[oicq];
$rsdb[_email]=$rsdb[email];

if($webdb[Info_ForbidGuesViewContact]&&!$lfjid){
	$rsdb[telephone]=$rsdb[mobphone]=$rsdb[oicq]=$rsdb[msn]=$rsdb[email]="�ο���Ȩ�鿴";
}elseif($webdb[Info_ImgShopContact]){
	$rsdb[telephone] && $rsdb[telephone]="<img src='$Mdomain/img.php?vid=".base64_encode($rsdb[telephone])."'>";
	$mob_area=get_mob_area($rsdb[mobphone]);
	$rsdb[mobphone] && $rsdb[mobphone]="<img src='$Mdomain/img.php?vid=".base64_encode($rsdb[mobphone])."'> $mob_area <A HREF='mob.php?vid=".base64_encode($rsdb[mobphone])."' target='_blank'>��ѯ����</A>";
	$rsdb[oicq] && $rsdb[oicq]="<img src='$Mdomain/img.php?vid=".base64_encode($rsdb[oicq])."'>";
	$rsdb[msn] && $rsdb[msn]="<img src='$Mdomain/img.php?vid=".base64_encode($rsdb[msn])."'>";
	$rsdb[email] && $rsdb[email]="<img src='$Mdomain/img.php?vid=".base64_encode($rsdb[email])."'>";
}else{	
	$mob_area=get_mob_area($rsdb[mobphone]);
	$rsdb[mobphone].=" $mob_area <A HREF='mob.php?vid=".base64_encode($rsdb[mobphone])."' target='_blank'>��ѯ����</A>";
}

if($webdb[Info_ShowSearchContact]){
	$rsdb[telephone].=" <a href='$Mdomain/search.php?action=search&type=telephone&keyword=$rsdb[_telephone]' target='_blank'>����</a>";
	$rsdb[mobphone].=" <a href='$Mdomain/search.php?action=search&type=mobphone&keyword=$rsdb[_mobphone]' target='_blank'>����</a>";
	$rsdb[oicq].=" <a href='$Mdomain/search.php?action=search&type=oicq&keyword=$rsdb[_oicq]' target='_blank'>����</a>";
	$rsdb[msn].=" <a href='$Mdomain/search.php?action=search&type=msn&keyword=$rsdb[_msn]' target='_blank'>����</a>";
	$rsdb[email].=" <a href='$Mdomain/search.php?action=search&type=email&keyword=$rsdb[_email]' target='_blank'>����</a>";
}

$rsdb[posttime]=date("Y-m-d H:i:s",$rsdb[posttime]);

$rsdb[picurl] && $rsdb[picurl]=tempdir($rsdb[picurl]);

/**
*ģ�����ȼ�������
**/
/*$FidTpl=unserialize($fidDB[template]);		//��Ŀģ��
$showTpl=unserialize($rsdb[template]);		//����ģ��
$head_tpl=$showTpl[head]?$showTpl[head]:$FidTpl['head'];
$main_tpl=$showTpl[bencandy]?$showTpl[bencandy]:$FidTpl['bencandy'];
$foot_tpl=$showTpl[foot]?$showTpl[foot]:$FidTpl['foot'];*/


/**
*Ϊ��ȡ��ǩ����
**/
$chdb[main_tpl]=getTpl("bencandy_{$base[ctype]}",$main_tpl);

/**
*��ǩ
**/
$ch_fid	= intval($fidDB[config][label_bencandy]);	//�Ƿ�������Ŀר�ñ�ǩ
$ch_pagetype = 3;									//2,Ϊlistҳ,3,Ϊbencandyҳ
$ch_module = $webdb[module_id]?$webdb[module_id]:99;//ϵͳ�ض�ID����,ÿ��ϵͳ������ͬ
$ch = 0;											//�������κ�ר��
require(PHP168_PATH."inc/label_module.php");


if($rsdb[uid]){
	$userdb=$db->get_one("SELECT * FROM {$pre}memberdata WHERE uid='$rsdb[uid]'");
	$userdb[username]=$rsdb[username];
	$userdb[regdate]=date("y-m-d H:i",$userdb[regdate]);
	$userdb[lastvist]=date("y-m-d H:i",$userdb[lastvist]);
	$userdb[icon]=tempdir($userdb[icon]);
	@include(PHP168_PATH."php168/level.php");
	$userdb[level]=$ltitle[$userdb[groupid]];
}else{
	$userdb[username]=preg_replace("/([\d]+)\.([\d]+)\.([\d]+)\.([\d]+)/is","\\1.\\2.*.*",$rsdb[ip]);
	$userdb[level]="�ο�";
}


$rsdb[ipfrom]=ipfrom($rsdb[ip]);

if(!$rsdb[username]){
	$rsdb[username]="*�ο�*";
}else{
	$rsdb[username]="$rsdb[username]";
}

/*
$foods='';
if($fidDB[mid]==1){
	$query = $db->query("SELECT A.num,B.word FROM {$_pre}keywordid A LEFT JOIN {$_pre}keyword B ON A.wid=B.wid WHERE B.type=1 AND A.id='$id' ORDER BY A.num DESC");
	while($rs = $db->fetch_array($query)){
		$_value=urlencode($rs[word]);
		$foods.=" <A HREF='search.php?action=search&type=keyword&keyword=$_value' target=_blank>$rs[word]</A>({$rs[num]}) ";
	}
}
*/
if($rsdb[keywords]){
	unset($array);
	$detail=explode(" ",$rsdb[keywords]);
	foreach( $detail AS $key=>$value){
		$_value=urlencode($value);
		$array[]="<A HREF='search.php?action=search&type=keyword&keyword=$_value' target=_blank>$value</A>";
	}
	$rsdb[keywords]=implode(" ",$array);
}


/*
*�õ������Ѷ
*/
$webdb[company_news_shownum]=$webdb[company_news_shownum]?$webdb[company_news_shownum]:5;
$query = $db->query("SELECT * FROM {$_pre}homepage_article WHERE uid='{$rsdb[uid]}' and yz=1 ORDER BY posttime desc LIMIT 0,$webdb[company_news_shownum]");

while($rs=$db->fetch_array($query)){
	$rs[posttime]=date("Y-m-d",$rs[posttime]);
	$rs[title]=get_word($rs[title_full]=$rs[title],36);
	if($rs[picurl]){
			$rs[picurl]=tempdir($rs[picurl]);
		}
	$newslistdb[]=$rs;
}







//�õ��󶨵�ͼƬ
$show_bd_pics=show_bd_pics("{$_pre}content_buy"," where id=$id",10);
if($show_bd_pics) $show_bd_pics="<hr style='border:1px dotted #ccc;height:1px;margin-top:20px'>".$show_bd_pics;

//�õ�����
$parameters_data=parameters_show($id);



//Ʒ������

$rsdb[brandname]=$rsdb[bid]?"<a href='".$Mdomain."/brandview.php?bid=".$rsdb[bid]." ' target='_blank'>".$Brand_db[name][$rsdb[bid]]."</a>":$rsdb[brandname];
$rsdb[brandname]=$rsdb[brandname]?$rsdb[brandname]:"&nbsp;";
//���


require(Mpath."inc/head.php");
require(getTpl("bencandy_2",$main_tpl));
//require(getTpl("bencandy_0",$main_tpl));
require(Mpath."inc/foot.php");

if($jobs != 'show')
	cache_page_save();
/**
*α��̬������
**/
if($webdb[bencandyIsHtml]&&$makehtml)
{
	$content=ob_get_contents();
	ob_end_clean();
	ob_start();
	$bencandyFormatHtml=$webdb[bencandyFormatHtml];
	$bencandyFormatHtml=str_replace( array('{$fid}','{$id}'),array($rsdb[fid],$id),$bencandyFormatHtml ) ;
	write_file(PHP168_PATH.$bencandyFormatHtml,$content);
	if($makehtml=='back'){
		echo "ok";
		$db->query("update {$_pre}content set htmlname='{$bencandyFormatHtml}' where id=$id");
	}else{
		echo "$content";
	}
}

function get__area($city_id,$zone_id,$street_id){
	global $city_DB,$fid;
	if(!$city_id){
		return ;
	}
	if($zone_id||$street_id){
		include(Mpath."php168/zone/{$city_id}.php");
	}
	$rs[]="<A HREF='list.php?fid=$fid&city_id=$city_id'>{$city_DB[name][$city_id]}</A>";
	$zone_id && $rs[]="<A HREF='list.php?fid=$fid&city_id=$city_id&zone_id=$zone_id'>{$zone_DB[name][$zone_id]}</A>";
	$street_id && $rs[]="<A HREF='list.php?fid=$fid&city_id=$city_id&zone_id=$zone_id&street_id=$street_id'>{$street_DB[name][$street_id]}</A>";
	$show=implode(" > ",$rs);
	return $show;	
}

function setfen($name,$title,$set){
	$detail=explode("\r\n",$set);
	foreach( $detail AS $key=>$value){
		$d=explode("=",$value);
		$shows.="<option value='$d[0]' style='color:blue;'>$d[1]</option>";
	}
	$shows=" <select name='$name' id='$name'><option value=''>-{$title}-</option>$shows</select>";
	//$shows="{$title}:<select name='$name' id='$name'><option value=''>��ѡ��</option>$shows</select>";
	return $shows;
}

?>