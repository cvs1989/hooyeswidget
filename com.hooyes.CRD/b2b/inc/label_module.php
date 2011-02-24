<?php
require_once(PHP168_PATH."inc/label_funcation.php");

/**
*获取模板所有的标签
**/
unset($haveCache);
if($jobs=='show')
{	
	//目的是为了兼容其它频道模型
	if(!function_exists('getTpl')){
		function getTpl($a,$b){
			return html($a,$b);
		}
	}
	if(!$_COOKIE[Admin])
	{
		showerr("你无权查看");
	}
	//获取头与尾的标签
	preg_replace('/\$label\[([\'a-zA-Z0-9\_]+)\]/eis',"label_array_hf('\\1')",read_file(getTpl("head",$head_tpl)));
	preg_replace('/\$label\[([\'a-zA-Z0-9\_]+)\]/eis',"label_array_hf('\\1')",read_file(getTpl("foot",$foot_tpl)));
	
	//$label_hf为头部的检索数组,检查头部有多少个标签
	
	is_array($label_hf) || $label_hf=array();
	foreach($label_hf AS $key=>$value)
	{
		$rs=$db->get_one("SELECT * FROM {$pre}label WHERE ch='$ch' AND tag='$key' AND module='$ch_module' AND chtype='99'");

		if($rs[tag])
		{
			$divdb=unserialize($rs[divcode]);
			$label[$key]=add_div($label[$key]?$label[$key]:'&nbsp;',$rs[tag],$rs[type],$divdb[div_w],$divdb[div_h],$divdb[div_bgcolor],'99');
		}
		else
		{
			$label[$key] || $label[$key]=add_div("新标签,无内容",$key,'NewTag','','','','99');
		}
	}

	//获取内容页的标签
	preg_replace('/\$label\[([\'a-zA-Z0-9\_]+)\]/eis',"label_array('\\1')",read_file(getTpl("index",$chdb[main_tpl])));
}
else
{
	$FileName=PHP168_PATH."cache/label_cache/";
	if(!is_dir($FileName)){
		makepath($FileName);
	}
	$FileName.=intval($ch)."_".intval($ch_pagetype)."_".intval($ch_module)."_".intval($ch_fid)."_".intval($city_id).".php";
	//默认缓存3分钟.
	if(!$webdb[label_cache_time]){
		$webdb[label_cache_time]=3;
	}
	if( (time()-filemtime($FileName))<($webdb[label_cache_time]*60) ){
		@include($FileName);
	}
}

if(!$haveCache){
	unset($label_rubbish);
	//屏蔽了错误.考虑到有时切换其他系统的时候SQL语句有错
	$query=$db->query("SELECT * FROM {$pre}label WHERE ch='$ch' AND pagetype='$ch_pagetype' AND module='$ch_module' AND fid='$ch_fid' AND chtype='0'");
	while( $rs=$db->fetch_array($query) ){
		//页面没有的标签.即多余的旧标签,做个提示
		if($jobs=='show' && !is_array($label["$rs[tag]"]) ){
			$label_rubbish[$rs[lid]]=$rs[tag];		
			continue;
		}
		//读数据库的标签
		if( $rs[typesystem] )
		{
			$_array=unserialize($rs[code]);
			$value=($rs[type]=='special')?Get_sp($_array):Get_Title($_array);
			if(strstr($value,"(/mv)")){
				$value=get_label_mv($value);
			}
			if($_array[c_rolltype])
			{
				$value="<marquee direction='$_array[c_rolltype]' scrolldelay='1' scrollamount='1' onmouseout='if(document.all!=null){this.start()}' onmouseover='if(document.all!=null){this.stop()}' height='$_array[roll_height]'>$value</marquee>";
			}
		}
		//代码标签
		elseif( $rs[type]=='code' )
		{
			$value=stripslashes($rs[code]);
			//纠正一下不完整的javascript代码,不必做权限判断,普通用户也能删除
			if(eregi("<SCRIPT",$value)&&!eregi("<\/SCRIPT",$value)){
				if($delerror){
					$db->query("UPDATE `{$pre}label` SET code='' WHERE lid='$rs[lid]'");
				}else{
					die("<A HREF='$WEBURL?&delerror=1'>此“{$rs[tag]}”标签有误,点击删除之!</A><br>$value");
				}			
			}
			//真实地址还原
			$value=En_TruePath($value,0);
		}
		//单张图片
		elseif( $rs[type]=='pic' )
		{	
			unset($width,$height);
			$picdb=unserialize($rs[code]);
			$picdb[imgurl]=tempdir("$picdb[imgurl]");
			$picdb[width] && $width=" width='$picdb[width]'";
			$picdb[height] && $height=" height='$picdb[height]'";
			if($picdb['imglink'])
			{
				$value="<a href='$picdb[imglink]' target=_blank><img src='$picdb[imgurl]' $width $height border='0' /></a>";
			}
			else
			{
				$value="<img src='$picdb[imgurl]' $width $height  border='0' />";
			}
		}
		//单个FLASH
		elseif( $rs[type]=='swf' )
		{
			$flashdb=unserialize($rs[code]);
			$flashdb[flashurl]=tempdir($flashdb[flashurl]);
			$flashdb[width] && $width=" width='$flashdb[width]'";
			$flashdb[height] && $height=" height='$flashdb[height]'";
			$value="<object type='application/x-shockwave-flash' data='$flashdb[flashurl]' $width $height wmode='transparent'><param name='movie' value='$flashdb[flashurl]' /><param name='wmode' value='transparent' /></object>";
		}
		//普通幻灯片
		elseif( $rs[type]=='rollpic' )
		{
			$value=rollPic_flash(unserialize($rs[code]));
		}
		//其它形式的
		else
		{
			$value=stripslashes($rs[code]);
			//真实地址还原
			$value=En_TruePath($value,0);
		}

		//更新标签时显示的页面
		if($jobs=='show')
		{
			if(!$value)
			{
				$value='&nbsp;';
			}
			$divdb=unserialize($rs[divcode]);
			$value=add_div($value,$rs[tag],$rs[system_type]?$rs[system_type]:$rs[type],$divdb[div_w],$divdb[div_h],$divdb[div_bgcolor]);
		}
		//有些标签设置了暂时隐藏
		elseif($rs[hide])
		{
			$value='';
		}
		$label[$rs[tag]]=$value;
	}
}


/**
*后台更新标签
**/
if($jobs=='show')
{
	unlink($FileName);	//把缓存文件删除掉,前台重新载入新资料

	if($label_rubbish){
		if($delete_label_rubbish){
			//$db->query("DELETE FROM {$pre}label WHERE tag IN ('".implode("','",$label_rubbish)."')");
		}else{
			//echo "<CENTER><br><br>提醒:::<A HREF='$WEBURL&delete_label_rubbish=1'>有 ".count($label_rubbish)." 个冗余的标签,你是否要删除它,点击即可删除:".implode(",",$label_rubbish)."</A><br><br><br><br></CENTER>";
		}		
	}
	$label || $label=array();
	foreach($label AS $key=>$value)
	{
		//如果是旧标签的话.$value已经是具体数值了,或者为空了,而不是数组
		if(is_array($value))
		{
			$label[$key]=add_div("新标签,内容暂无:$key",$key,'NewTag');
		}
	}

	$fromurl=urlencode($WEBURL);
	$label[$key].="<SCRIPT LANGUAGE='JavaScript'>
					<!--
					var admin_url='$webdb[admin_url]';
					var ch='$ch';
					var ch_fid='$ch_fid';
					var ch_pagetype='$ch_pagetype';
					var ch_module='$ch_module';
					var fromurl='$fromurl';
					var mystyle='$STYLE';
					//-->
					</SCRIPT>
					<SCRIPT LANGUAGE='JavaScript' src='$webdb[www_url]/images/default/label.js'></SCRIPT>";
}
else
{
	//写缓存
	if( (time()-filemtime($FileName))>($webdb[label_cache_time]*60) ){
		$_shows="<?php\r\n\$haveCache=1;\r\n";
		foreach($label AS $key=>$value){
			$value=addslashes($value);
			$_shows.="\$label['$key']=stripslashes('$value');\r\n";
		}
		write_file($FileName,$_shows.'?>');
	}	
}
?>