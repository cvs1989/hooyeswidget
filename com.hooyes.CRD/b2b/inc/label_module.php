<?php
require_once(PHP168_PATH."inc/label_funcation.php");

/**
*��ȡģ�����еı�ǩ
**/
unset($haveCache);
if($jobs=='show')
{	
	//Ŀ����Ϊ�˼�������Ƶ��ģ��
	if(!function_exists('getTpl')){
		function getTpl($a,$b){
			return html($a,$b);
		}
	}
	if(!$_COOKIE[Admin])
	{
		showerr("����Ȩ�鿴");
	}
	//��ȡͷ��β�ı�ǩ
	preg_replace('/\$label\[([\'a-zA-Z0-9\_]+)\]/eis',"label_array_hf('\\1')",read_file(getTpl("head",$head_tpl)));
	preg_replace('/\$label\[([\'a-zA-Z0-9\_]+)\]/eis',"label_array_hf('\\1')",read_file(getTpl("foot",$foot_tpl)));
	
	//$label_hfΪͷ���ļ�������,���ͷ���ж��ٸ���ǩ
	
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
			$label[$key] || $label[$key]=add_div("�±�ǩ,������",$key,'NewTag','','','','99');
		}
	}

	//��ȡ����ҳ�ı�ǩ
	preg_replace('/\$label\[([\'a-zA-Z0-9\_]+)\]/eis',"label_array('\\1')",read_file(getTpl("index",$chdb[main_tpl])));
}
else
{
	$FileName=PHP168_PATH."cache/label_cache/";
	if(!is_dir($FileName)){
		makepath($FileName);
	}
	$FileName.=intval($ch)."_".intval($ch_pagetype)."_".intval($ch_module)."_".intval($ch_fid)."_".intval($city_id).".php";
	//Ĭ�ϻ���3����.
	if(!$webdb[label_cache_time]){
		$webdb[label_cache_time]=3;
	}
	if( (time()-filemtime($FileName))<($webdb[label_cache_time]*60) ){
		@include($FileName);
	}
}

if(!$haveCache){
	unset($label_rubbish);
	//�����˴���.���ǵ���ʱ�л�����ϵͳ��ʱ��SQL����д�
	$query=$db->query("SELECT * FROM {$pre}label WHERE ch='$ch' AND pagetype='$ch_pagetype' AND module='$ch_module' AND fid='$ch_fid' AND chtype='0'");
	while( $rs=$db->fetch_array($query) ){
		//ҳ��û�еı�ǩ.������ľɱ�ǩ,������ʾ
		if($jobs=='show' && !is_array($label["$rs[tag]"]) ){
			$label_rubbish[$rs[lid]]=$rs[tag];		
			continue;
		}
		//�����ݿ�ı�ǩ
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
		//�����ǩ
		elseif( $rs[type]=='code' )
		{
			$value=stripslashes($rs[code]);
			//����һ�²�������javascript����,������Ȩ���ж�,��ͨ�û�Ҳ��ɾ��
			if(eregi("<SCRIPT",$value)&&!eregi("<\/SCRIPT",$value)){
				if($delerror){
					$db->query("UPDATE `{$pre}label` SET code='' WHERE lid='$rs[lid]'");
				}else{
					die("<A HREF='$WEBURL?&delerror=1'>�ˡ�{$rs[tag]}����ǩ����,���ɾ��֮!</A><br>$value");
				}			
			}
			//��ʵ��ַ��ԭ
			$value=En_TruePath($value,0);
		}
		//����ͼƬ
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
		//����FLASH
		elseif( $rs[type]=='swf' )
		{
			$flashdb=unserialize($rs[code]);
			$flashdb[flashurl]=tempdir($flashdb[flashurl]);
			$flashdb[width] && $width=" width='$flashdb[width]'";
			$flashdb[height] && $height=" height='$flashdb[height]'";
			$value="<object type='application/x-shockwave-flash' data='$flashdb[flashurl]' $width $height wmode='transparent'><param name='movie' value='$flashdb[flashurl]' /><param name='wmode' value='transparent' /></object>";
		}
		//��ͨ�õ�Ƭ
		elseif( $rs[type]=='rollpic' )
		{
			$value=rollPic_flash(unserialize($rs[code]));
		}
		//������ʽ��
		else
		{
			$value=stripslashes($rs[code]);
			//��ʵ��ַ��ԭ
			$value=En_TruePath($value,0);
		}

		//���±�ǩʱ��ʾ��ҳ��
		if($jobs=='show')
		{
			if(!$value)
			{
				$value='&nbsp;';
			}
			$divdb=unserialize($rs[divcode]);
			$value=add_div($value,$rs[tag],$rs[system_type]?$rs[system_type]:$rs[type],$divdb[div_w],$divdb[div_h],$divdb[div_bgcolor]);
		}
		//��Щ��ǩ��������ʱ����
		elseif($rs[hide])
		{
			$value='';
		}
		$label[$rs[tag]]=$value;
	}
}


/**
*��̨���±�ǩ
**/
if($jobs=='show')
{
	unlink($FileName);	//�ѻ����ļ�ɾ����,ǰ̨��������������

	if($label_rubbish){
		if($delete_label_rubbish){
			//$db->query("DELETE FROM {$pre}label WHERE tag IN ('".implode("','",$label_rubbish)."')");
		}else{
			//echo "<CENTER><br><br>����:::<A HREF='$WEBURL&delete_label_rubbish=1'>�� ".count($label_rubbish)." ������ı�ǩ,���Ƿ�Ҫɾ����,�������ɾ��:".implode(",",$label_rubbish)."</A><br><br><br><br></CENTER>";
		}		
	}
	$label || $label=array();
	foreach($label AS $key=>$value)
	{
		//����Ǿɱ�ǩ�Ļ�.$value�Ѿ��Ǿ�����ֵ��,����Ϊ����,����������
		if(is_array($value))
		{
			$label[$key]=add_div("�±�ǩ,��������:$key",$key,'NewTag');
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
	//д����
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