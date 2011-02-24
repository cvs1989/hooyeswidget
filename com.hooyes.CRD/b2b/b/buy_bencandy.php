<?php
require("global.php");
require("bd_pics.php");

if(!$id=intval($id)) showerr("没有找到您要访问的页");

$fid = intval($fid);

$params = array(
	'fid' => $fid,
	'id' => $id
);

if($jobs != 'show')
	cache_page(PHP_SELF.combine_params($params));

//choose_domain();	//域名判断
@include(Mpath."php168/guide_fid.php");
@include(Mpath."php168/all_spfid.php");
//require_once(Mpath."inc/ip.php");
require(Mpath."inc/categories.php");
		
$bcategory->cache_read();


if(!$id=intval($id)) showerr("没有找到您要访问的页");

/**
*获取信息正文的内容
**/
$base=$db->get_one("SELECT A.`htmlname`,B.* FROM `{$_pre}content_buy` A INNER JOIN `{$_pre}company` B ON A.uid=B.uid WHERE id='$id'");
if(!$base) showerr("暂时不能提供该信息[原因可能是某些数据已经被移除]");

//如果是已经静态,强制访问静态
/*
if($base[htmlname]!='' && $webdb[bencandyIsHtml] && file_exists(PHP168_PATH.$base[htmlname]) && !$makehtml){
	header("Location:".$webdb[www_url]."/".$base[htmlname]);
}
*/

//重新配置FID
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
*获取栏目与模块配置参数
**/
$fidDB=$bcategory->get_one($fid);

if(!$fidDB[mid]){
	//showerr("MID不存在");
}



$fupId=intval($fidDB[type]?$fid:$fidDB[fup]);
/**
*模块配置参数
**/
//$m_config=unserialize($fidDB[m_config]);


/**
*栏目配置参数
**/
//$fidDB[config]=unserialize($fidDB[config]);


/**
*栏目配置文件用户自定义的变量
**/
$CV=$fidDB[config][field_value];


/**
*栏目当中,用户自定义变量哪些使用了在线编辑器要对他们做附件真实地址作处理
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
		$rsdb[my_price]="<strong><font color=#FF3300>$rsdb[my_price]</font></strong>元/$rsdb[quantity_type]";

}else{
	$rsdb[my_price]='价格面议';
}

//
if($rsdb[overtime]){
	if($rsdb[overtime]<date("Y-m-d")){ $rsdb[overtime]=$rsdb[overtime]."<font color='red'>[过期]</font>";
	$onclick=" onclick=\"alert('已过期的信息不能报价');return false;\"";
	}
}else{
	$rsdb[overtime]="";
}
//通过商家信息得到地区
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
*内容页的风格优先于栏目的风格,栏目的风格优先于系统的风格
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
	die("内容不存在");
}
elseif($rsdb[yz]!=1&&!$web_admin)
{
	if($rsdb[uid]!=$lfjuid){
		if($rsdb[yz]==2){
			showerr("回收站的内容,你无法查看");
		}else{
			showerr("暂时不能提供该信息[原因可能是未审核]");
		}
	}
}

/**
*栏目指定了哪些用户组才能看信息内容
**/
if($fidDB[allowviewcontent])
{
	if( !$web_admin&&!in_array($groupdb[gid],explode(",",$fidDB[allowviewcontent])) )
	{
		$detail=explode(",",$fidDB[admin]);

		if(!$lfjid||!in_array($lfjid,$detail))
		{
			showerr("你所在用户组,无权浏览");
		}
	}
}


/**
*对信息内容的在线编辑器提交的附件真实路径作处理,textarea普通编辑器的文本格式也要做处理
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
			$rsdb[$key]="<font color=red>权限不够,无法查看!</font>";
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
	$rsdb[$key] && $rsdb[$key]="{$rsdb[$key]} $mob_area <A HREF='mob.php?vid=$mobnum' target='_blank'>查询更多</A>";
}

$rsdb[_mobphone]=$rsdb[mobphone];
$rsdb[_telephone]=$rsdb[telephone];
$rsdb[_msn]=$rsdb[msn];
$rsdb[_oicq]=$rsdb[oicq];
$rsdb[_email]=$rsdb[email];

if($webdb[Info_ForbidGuesViewContact]&&!$lfjid){
	$rsdb[telephone]=$rsdb[mobphone]=$rsdb[oicq]=$rsdb[msn]=$rsdb[email]="游客无权查看";
}elseif($webdb[Info_ImgShopContact]){
	$rsdb[telephone] && $rsdb[telephone]="<img src='$Mdomain/img.php?vid=".base64_encode($rsdb[telephone])."'>";
	$mob_area=get_mob_area($rsdb[mobphone]);
	$rsdb[mobphone] && $rsdb[mobphone]="<img src='$Mdomain/img.php?vid=".base64_encode($rsdb[mobphone])."'> $mob_area <A HREF='mob.php?vid=".base64_encode($rsdb[mobphone])."' target='_blank'>查询更多</A>";
	$rsdb[oicq] && $rsdb[oicq]="<img src='$Mdomain/img.php?vid=".base64_encode($rsdb[oicq])."'>";
	$rsdb[msn] && $rsdb[msn]="<img src='$Mdomain/img.php?vid=".base64_encode($rsdb[msn])."'>";
	$rsdb[email] && $rsdb[email]="<img src='$Mdomain/img.php?vid=".base64_encode($rsdb[email])."'>";
}else{	
	$mob_area=get_mob_area($rsdb[mobphone]);
	$rsdb[mobphone].=" $mob_area <A HREF='mob.php?vid=".base64_encode($rsdb[mobphone])."' target='_blank'>查询更多</A>";
}

if($webdb[Info_ShowSearchContact]){
	$rsdb[telephone].=" <a href='$Mdomain/search.php?action=search&type=telephone&keyword=$rsdb[_telephone]' target='_blank'>查找</a>";
	$rsdb[mobphone].=" <a href='$Mdomain/search.php?action=search&type=mobphone&keyword=$rsdb[_mobphone]' target='_blank'>查找</a>";
	$rsdb[oicq].=" <a href='$Mdomain/search.php?action=search&type=oicq&keyword=$rsdb[_oicq]' target='_blank'>查找</a>";
	$rsdb[msn].=" <a href='$Mdomain/search.php?action=search&type=msn&keyword=$rsdb[_msn]' target='_blank'>查找</a>";
	$rsdb[email].=" <a href='$Mdomain/search.php?action=search&type=email&keyword=$rsdb[_email]' target='_blank'>查找</a>";
}

$rsdb[posttime]=date("Y-m-d H:i:s",$rsdb[posttime]);

$rsdb[picurl] && $rsdb[picurl]=tempdir($rsdb[picurl]);

/**
*模板优先级做处理
**/
/*$FidTpl=unserialize($fidDB[template]);		//栏目模板
$showTpl=unserialize($rsdb[template]);		//内容模板
$head_tpl=$showTpl[head]?$showTpl[head]:$FidTpl['head'];
$main_tpl=$showTpl[bencandy]?$showTpl[bencandy]:$FidTpl['bencandy'];
$foot_tpl=$showTpl[foot]?$showTpl[foot]:$FidTpl['foot'];*/


/**
*为获取标签参数
**/
$chdb[main_tpl]=getTpl("bencandy_{$base[ctype]}",$main_tpl);

/**
*标签
**/
$ch_fid	= intval($fidDB[config][label_bencandy]);	//是否定义了栏目专用标签
$ch_pagetype = 3;									//2,为list页,3,为bencandy页
$ch_module = $webdb[module_id]?$webdb[module_id]:99;//系统特定ID参数,每个系统不能雷同
$ch = 0;											//不属于任何专题
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
	$userdb[level]="游客";
}


$rsdb[ipfrom]=ipfrom($rsdb[ip]);

if(!$rsdb[username]){
	$rsdb[username]="*游客*";
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
*得到相关资讯
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







//得到绑定的图片
$show_bd_pics=show_bd_pics("{$_pre}content_buy"," where id=$id",10);
if($show_bd_pics) $show_bd_pics="<hr style='border:1px dotted #ccc;height:1px;margin-top:20px'>".$show_bd_pics;

//得到参数
$parameters_data=parameters_show($id);



//品牌设置

$rsdb[brandname]=$rsdb[bid]?"<a href='".$Mdomain."/brandview.php?bid=".$rsdb[bid]." ' target='_blank'>".$Brand_db[name][$rsdb[bid]]."</a>":$rsdb[brandname];
$rsdb[brandname]=$rsdb[brandname]?$rsdb[brandname]:"&nbsp;";
//输出


require(Mpath."inc/head.php");
require(getTpl("bencandy_2",$main_tpl));
//require(getTpl("bencandy_0",$main_tpl));
require(Mpath."inc/foot.php");

if($jobs != 'show')
	cache_page_save();
/**
*伪静态作处理
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
	//$shows="{$title}:<select name='$name' id='$name'><option value=''>请选择</option>$shows</select>";
	return $shows;
}

?>