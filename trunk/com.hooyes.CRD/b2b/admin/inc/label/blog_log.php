<?php
!function_exists('html') && exit('ERR');
$cDB=get_table2($inc);

if($action=='mod'){
	
	if($tplpart_2==''){
	//	$stype='t';
	}
	
	if(strstr($postdb[tplpart_1code],'$picurl')&&strstr($postdb[tplpart_1code],'$content')){
		$stype="cp";
	}elseif(strstr($postdb[tplpart_1code],'$content')){
		$stype="c";
	}elseif(strstr($postdb[tplpart_1code],'$picurl')){
		$stype="p";
	}

	//选择显示两列以上,这里选择Table,否则不一定能显示效果,选择table指外套一个TABLE,选择div指不套多余的代码
	if($colspan>1){
		$DivTpl=0;
	}else{
		$DivTpl=1;
	}

	$Tdb=get_table2($type);
	if($inc=="blog_log")
	{
		$_url='$webdb[www_url]/'.$ModuleDB[blog]['dirname'].'/index.php?file=viewlog&uid=$uid&id=$id';
		$_listurl='$webdb[www_url]/'.$ModuleDB[blog]['dirname'].'/log.php?fid=$fid';
	}
	elseif($inc=="blog_photo")
	{
		$_url='$webdb[www_url]/'.$ModuleDB[blog]['dirname'].'/index.php?file=viewphoto&uid=$uid&id=$id';
		$_listurl='$webdb[www_url]/'.$ModuleDB[blog]['dirname'].'/photo.php?fid=$fid';
	}

	//$blog_view='$webdb[blog_url]/index.php?file=view'.$Tdb['key'].'&uid=$uid&id=$id';
	if($tplpart_1)
	{
		$postdb[tplpart_1]=StripSlashes($tplpart_1);
		$postdb[tplpart_1code]=$postdb[tplpart_1];
		//$postdb[tplpart_1code]=read_file(PHP168_PATH.$tplpart_1);
		$postdb[tplpart_1code]=str_replace('{$url}',$_url,$postdb[tplpart_1code]);
		$postdb[tplpart_1code]=str_replace('$url',$_url,$postdb[tplpart_1code]);

		$postdb[tplpart_1code]=str_replace('{$list_url}',$_listurl,$postdb[tplpart_1code]);
		$postdb[tplpart_1code]=str_replace('$list_url',$_listurl,$postdb[tplpart_1code]);

		$postdb[tplpart_1code]=str_replace('{$blog_view}',$blog_view,$postdb[tplpart_1code]);
		$postdb[tplpart_1code]=str_replace('$blog_view',$blog_view,$postdb[tplpart_1code]);

		if(!$postdb[tplpart_1code]){
			showmsg("模板一路径不对或者是其他原因,模板数据读取失败,请检查之");
		}
		//$rs1=$db->get_one("SELECT type FROM {$pre}template WHERE filepath='$tplpart_1' ");
	}
	if($tplpart_2)
	{
		$postdb[tplpart_2]=StripSlashes($tplpart_2);
		$postdb[tplpart_2code]=$postdb[tplpart_2];
		//$postdb[tplpart_2code]=read_file(PHP168_PATH.$tplpart_2);
		$postdb[tplpart_2code]=str_replace('{$url}',$_url,$postdb[tplpart_2code]);
		$postdb[tplpart_2code]=str_replace('$url',$_url,$postdb[tplpart_2code]);

		$postdb[tplpart_2code]=str_replace('{$list_url}',$_listurl,$postdb[tplpart_2code]);
		$postdb[tplpart_2code]=str_replace('$list_url',$_listurl,$postdb[tplpart_2code]);

		$postdb[tplpart_1code]=str_replace('{$blog_view}',$blog_view,$postdb[tplpart_1code]);
		$postdb[tplpart_1code]=str_replace('$blog_view',$blog_view,$postdb[tplpart_1code]);

		if(!$postdb[tplpart_2code]){
			showmsg("模板二路径不对或者是其他原因,模板数据读取失败,请检查之");
		}
		//$rs2=$db->get_one("SELECT type FROM {$pre}template WHERE filepath='$tplpart_2' ");
	}
	
	//使用在线编辑器后,去掉多余的网址
	$weburl=preg_replace("/(.*)\/([^\/]+)/is","\\1/",$WEBURL);
	$postdb[tplpart_1code]=str_replace($weburl,"",$postdb[tplpart_1code]);
	$postdb[tplpart_2code]=str_replace($weburl,"",$postdb[tplpart_2code]);

	/*判断是否是显示图片类型*/
	$SQL=" WHERE 1 ";

	if($rowspan<1){
		$rowspan=1;
	}
	if($colspan<1){
		$colspan=1;
	}
	$rows=$rowspan*$colspan;
	if(is_numeric($yz)){
		$SQL.=" AND yz=$yz ";
	}
	if(is_numeric($levels)){
		$SQL.=" AND levels=$levels ";
	}
	if($fiddb[0]){
		foreach($fiddb AS $key=>$value){
			if(!is_numeric($value)){
				unset($fiddb[$key]);
			}
		}
		$fids=implode(",",$fiddb);
		$SQL.=" AND fid IN ($fids) ";
	}
	$SQL=" SELECT * FROM $cDB[c] $SQL ORDER BY $order $asc LIMIT $rows ";
//die("$SQL");
	$postdb[RollStyleType]=$RollStyleType;

	$postdb[url]=$_url;
	$postdb[width]=$width;
	$postdb[height]=$height;
	$postdb[content_num]=$content_num;

	$postdb[tplpath]=$tplpath;
	$postdb[DivTpl]=$DivTpl;
	$postdb[fiddb]=$fids;
	$postdb[stype]=$stype;
	$postdb[yz]=$yz;
	$postdb[timeformat]=$timeformat;
	$postdb[order]=$order;
	$postdb[asc]=$asc;
	$postdb[levels]=$levels;
	$postdb[rowspan]=$rowspan;
	$postdb[sql]=$SQL;
	$postdb[colspan]=$colspan;
	$postdb[titlenum]=$titlenum;
	$postdb[titleflood]=$titleflood;

	$postdb[titlenum2]=$titlenum2;
	$postdb[content_num2]=$content_num2;
	
	$code=addslashes(serialize($postdb));
	$div_db[div_w]=$div_w;
	$div_db[div_h]=$div_h;
	$div_db[div_bgcolor]=$div_bgcolor;
	$div=addslashes(serialize($div_db));
	$typesystem=1;
	
	//插入或更新标签库
	do_post();

}else{

	$rsdb=get_label();
	$div=unserialize($rsdb[divcode]);
	@extract($div);
	$codedb=unserialize($rsdb[code]);
	@extract($codedb);
	if(!isset($yz)){
		$yz="all";
	}
	if(!isset($is_com)){
		$is_com="all";
	}
	if(!isset($order)){
		$order="posttime";
	}
	$titleflood=(int)$titleflood;
	$hide=(int)$rsdb[hide];
	if($rsdb[js_time]){
		$js_ck='checked';
	}

	/*默认值*/
	$yz || $yz='all';
	$asc || $asc='DESC';
	$titleflood!=1		&& $titleflood=0;
	$timeformat			|| $timeformat="Y-m-d H:i:s";
	$rowspan			|| $rowspan=5;
	$colspan			|| $colspan=1;
	$titlenum			|| $titlenum=20;
	$div_w				|| $div_w=50;
	$div_h				|| $div_h=30;
	$hide!=1			&& $hide=0;
	$DivTpl!=1			&& $DivTpl=0;
	$stype				|| $stype=4;
	$content_num		|| $content_num=80;

	$div_width && $div_w=$div_width;
	$div_height && $div_h=$div_height;

	$yzdb[$yz]="checked";
	$ascdb[$asc]="checked";
	$orderdb[$order]=" selected ";
	$levelsdb[$levels]=" selected ";
	$titleflooddb["$titleflood"]="checked";
	$hidedb[$hide]="checked";
	$divtpldb[$DivTpl]="checked";
	$stypedb[$stype]=" checked ";
	$fiddb=explode(",",$codedb[fiddb]);
 	$select_news=$Guidedb->Checkbox("{$cDB[sort]}",'fiddb[]',$fiddb);

	$tplpart_1=str_replace("&nbsp;","&amp;nbsp;",$tplpart_1);
	$tplpart_2=str_replace("&nbsp;","&amp;nbsp;",$tplpart_2);

	$getLabelTpl=getLabelTpl($inc,array("common_title","common_content"));

	//幻灯片样式
	$rollpicStyle="<select name='RollStyleType' id='RollStyleType' onChange='rollpictypes(this)'><option value=''>默认</option>";
	$dir=opendir(PHP168_PATH."template/default/rollpic/");
	while($file=readdir($dir)){
		if(eregi("\.htm$",$file)){
			$rollpicStyle.="<option value='$file'>".str_replace(".htm","",$file)."</option>";
		}
	}
	$rollpicStyle.="</select>";

	require("head.php");
	require("template/label/blog_c.htm");
	require("foot.php");

}

function get_table2($type){
	global $pre;

	if($type=="blog_log")
	{
		$array=array("id"=>"1","sort"=>"{$pre}blog_log_sort","c"=>"{$pre}blog_log_article","key"=>"log","name"=>"日志");
	}
	elseif($type=="blog_photo")
	{
		$array=array("id"=>"3","sort"=>"{$pre}blog_photo_sort","c"=>"{$pre}blog_photo_pic","key"=>"photo","name"=>"相片");
	}
	return $array;
}
?>