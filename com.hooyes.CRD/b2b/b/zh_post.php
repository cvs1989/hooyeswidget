<?php
require_once("global.php");
require_once("bd_pics.php");
require_once(Mpath."inc/categories.php");
$bcategory->cache_read();

if(!$lfjid ){ 
	if(!$web_admin) showerr("����û�е�½�����½....");
	exit;
}else{
	$rsdb=$db->get_one("select * from {$_pre}company where uid='$lfjuid' limit 1");
	if(!$rsdb[rid]) showerr("�̼���Ϣδ�Ǽ�,<a href='$Mdomain/member/?main=post_company.php'>�������Ǽ��̼�</a>��ӵ���Լ�������֮����ܷ���");
}

if($job=='postzh'){ $zhDB[title]="����չ����Ϣ";
	
	
	if(!$step){
		$bcategory->unsets();
		$sid_options=getsid_options(0);
		$starttime=date("Y-m-d");
		$endtime  =date("Y-m-d",(time()+7*24*60*60));
		$province_fid=select_where("province","'postdb[province_id]'  onChange=\"choose_where(this.options[this.selectedIndex].value,'','');settime_listzlg(this.options[this.selectedIndex].value);\" ");	
		$step=1;
	}else{
		//�̼�
		$company=$db->get_one("select * from {$_pre}company where uid='$lfjuid'");
		if(!$company) $msg.="�㻹û�еǼ��̼���Ϣ�����ȵǼǣ�<br>";
		//������Ϣ���
		if(!$sid) $msg.="��ѡ��չ�����<br>";
		$title=htmlspecialchars($title);
		if(!$title || strlen($title)>80 || strlen($title)<5 ) $msg.="չ�����Ʊ�����5-40����֮��<br>";
		if(!$postdb[province_id] || !$postdb[city_id]) $msg.="��ѡ��չ��ص�<br>";
		if(!$showroom)	 $msg.="��ѡ��չ����<br>";
		if(!preg_match("/[0-9]{4}-[0-9]{1,2}-[0-9]{1,2}/",$starttime)) $msg.="չ�Ὺʼʱ�䲻���Ϲ涨��ʽ:��-��-��<br>";
		if(!preg_match("/[0-9]{4}-[0-9]{1,2}-[0-9]{1,2}/",$endtime))   $msg.="չ�����ʱ�䲻���Ϲ涨��ʽ:��-��-��<br>";
		if($endtime<$starttime) $msg.="չ�����ʱ�������ڻ��ߵ��ڿ�ʼʱ��<br>";
		$organizers=htmlspecialchars($organizers);
		$co_organizers=htmlspecialchars($co_organizers);
		if(!$organizers || strlen($organizers)<5) $msg.="���쵥λ����Ϊ�գ�����5����<br>"; 
		
		//������Ϣ���
		$info=htmlspecialchars($info);
		$goods=htmlspecialchars($goods);
		$costs=htmlspecialchars($costs);
		$contact=htmlspecialchars($contact);
		$remarks=htmlspecialchars($remarks);
		
		if(strlen($info)<10 || strlen($info)>20000) $msg.="չ���������10���ַ�,���20000�ַ�<br>";
		if(strlen($contact)<10 || strlen($contact)>20000) $msg.="��ϵ��ʽ����10���ַ�,���20000�ַ�<br>";
		//�Զ�����Ϣ���
		
		//ͼƬ
		//��Ƭ�ϴ�
		if(is_uploaded_file($_FILES[postfile][tmp_name]) && !$msg){
				$array[name]=is_array($postfile)?$_FILES[postfile][name]:$postfile_name;
				$array[path]=$webdb[updir]."/zh/";
				$array[size]=is_array($postfile)?$_FILES[postfile][size]:$postfile_size;
				$webdb[iconMaxUserPostZh]=$webdb[iconMaxUserPostZh]?$webdb[iconMaxUserPostZh]:50;
				if($array[size]>($webdb[iconMaxUserPostZh]*1024))$msg.="ͼƬ��С���ܳ���$webdb[iconMaxUserPostZh]k<br>";
				$picurl=upfile_func2(is_array($postfile)?$_FILES[postfile][tmp_name]:$postfile,$array);
				if(substr($picurl,0,3)=='ERR')	{
					$msg.=str_replace("ERR-","",$picurl)."<br>";	
				}
		}else{
				$picurl="";
		}
		
		
		if(!$msg){
				//�õ�ĳЩ����
				$showroom_name=$db->get_one("select sr_id,title from {$_pre}zh_showroom where sr_id='$showroom'");
				$yz=$webdb[postzhCheck];
				if($yz) $yz_time=time();
				

				$rid=$company[rid];
				$starttime=explode("-",$starttime);
				$starttime=mktime(0,0,0,$starttime[1],$starttime[2],$starttime[0]);
				$endtime=explode("-",$endtime);
				$endtime=mktime(0,0,0,$endtime[1],$endtime[2],$endtime[0]);
				$sname  =$bcategory->categories[$sid]['name'];
				//ִ��
				$db->query("INSERT INTO `{$_pre}zh_content` ( `zh_id` , `sid` , `sname` , `title` , `province_id` , `city_id` , `picurl` , `levels` , `levels_pic` , `yz` , `yz_time` , `posttime` , `starttime` , `endtime` , `uid` , `username` , `rid` , `organizers` , `co_organizers` , `showroom` , `showroom_name` , `contact` , `goods` , `costs` , `info` , `remarks` , `hits` ) 
VALUES (
'', '$sid', '$sname', '$title', '$postdb[province_id]', '$postdb[city_id]', '$picurl', '$levels', '$levels_pic', '$yz', '$yz_time', '".time()."', '$starttime', '$endtime', '$lfjuid', '$lfjid', '$rid', '$organizers', '$co_organizers', '$showroom', '$showroom_name[title]', '$contact', '$goods', '$costs', '$info', '$remarks', '0');");
					
				//�����Զ�������
				$zh_id=$db->insert_id();
				if(is_array($diydata)){
					foreach($diydata as $i=>$array)	{
						if(trim($array[title])!=''){
							$db->query("INSERT INTO `{$_pre}zh_content_1` ( `mzh_id_` , `zh_id` , `ind` , `title` , `content` , `listorder` , `listtype` ) VALUES (
'', '$zh_id', '$i', '$array[title]', '$array[content]', '0', '1');");
						}
					}				
				}

				//���°�ͼƬ	
				bd_pics("{$_pre}zh_content"," where zh_id ='$zh_id' ");
				//��������
				if($webdb[post_add_money]) plus_money($lfjuid,$webdb[post_add_money]);

				echo "<SCRIPT LANGUAGE=\"JavaScript\">
				<!--
				parent.location='$Mdomain/member/?main=zh.php?job=zh';	
				parent.location.href='$Mdomain/member/?main=zh.php?job=zh';		
				//-->
				</SCRIPT>";exit;
				
		}else{
				echo "<SCRIPT LANGUAGE=\"JavaScript\">
				<!--
				parent.document.getElementById(\"showmsg\").innerHTML='$msg';
				parent.document.getElementById(\"postSubmit\").disabled=false;
				//-->
				</SCRIPT>";exit;
		}
		
	}
	
	
}elseif($job=='editzh'){$zhDB[title]="�༭չ����Ϣ";
	
	$zh_id=intval($zh_id);
	if(!$zh_id) showerr("�Ƿ�����");
	if(!$step){
		$rsdb=$db->get_one("select * from `{$_pre}zh_content` where zh_id='$zh_id'");
		if(!$rsdb) showerr("�޷�������Ҫ�༭����Ϣ");		
		if(!$web_admin && $rsdb[uid]!=$lfjuid) showerr("����Ȩ����");

		if(!@extract($rsdb)){
			if(is_array($rsdb)){
			foreach($rsdb as $key=>$val){
				$$key=$val;
			}
			}
		}
		
		$sid_options=getsid_options($sid);
		
		$showroom_options="";
		
		if($province_id){
			$query=$db->query("select sr_id,title from {$_pre}zh_showroom where province_id='$province_id'");
			while($rs=$db->fetch_array($query)){
				$ck=$rs[sr_id]==$showroom?" selected":"";
				$showroom_options.="<option value='$rs[sr_id]' $ck>$rs[title]</option>";
			}
		}
		
		$province_fid=select_where("province","'postdb[province_id]'  onChange=\"choose_where(this.options[this.selectedIndex].value,'','');settime_listzlg(this.options[this.selectedIndex].value);\" ",$province_id);	
		
		$city_id=select_where("city","'postdb[city_id]' ",$city_id,$province_id);
		
		if($picurl)$picurl_show  =$webdb[www_url]."/".$webdb[updir]."/zh/".$picurl;
		
		$starttime=date("Y-m-d",$starttime);
		$endtime=date("Y-m-d",$endtime);
		
		//$diydata=
		$query=$db->query("select * from `{$_pre}zh_content_1` where zh_id='$zh_id' order by ind asc");
		
		while($rs=$db->fetch_array($query)){
			$diydata[$rs[ind]]=$rs;
		}
		$step=1;
		
		
	}else{
		//������Ϣ���
		if(!$sid) $msg.="��ѡ��չ�����<br>";
		$title=htmlspecialchars($title);
		if(!$title || strlen($title)>80 || strlen($title)<5 ) $msg.="չ�����Ʊ�����5-40����֮��<br>";
		if(!$postdb[province_id] || !$postdb[city_id]) $msg.="��ѡ��չ��ص�<br>";
		if(!$showroom)	 $msg.="��ѡ��չ����<br>";
		if(!preg_match("/[0-9]{4}-[0-9]{1,2}-[0-9]{1,2}/",$starttime)) $msg.="չ�Ὺʼʱ�䲻���Ϲ涨��ʽ:��-��-��<br>";
		if(!preg_match("/[0-9]{4}-[0-9]{1,2}-[0-9]{1,2}/",$endtime))   $msg.="չ�����ʱ�䲻���Ϲ涨��ʽ:��-��-��<br>";
		if($endtime<$starttime) $msg.="չ�����ʱ�������ڻ��ߵ��ڿ�ʼʱ��<br>";
		$organizers=htmlspecialchars($organizers);
		$co_organizers=htmlspecialchars($co_organizers);
		if(!$organizers || strlen($organizers)<5) $msg.="���쵥λ����Ϊ�գ�����5����<br>"; 
		
		//������Ϣ���
		$info=htmlspecialchars($info);
		$goods=htmlspecialchars($goods);
		$costs=htmlspecialchars($costs);
		$contact=htmlspecialchars($contact);
		$remarks=htmlspecialchars($remarks);
		
		if(strlen($info)<10 || strlen($info)>20000) $msg.="չ���������10���ַ�,���20000�ַ�<br>";
		if(strlen($contact)<10 || strlen($contact)>20000) $msg.="��ϵ��ʽ����10���ַ�,���20000�ַ�<br>";
		//�Զ�����Ϣ���
		
		//ͼƬ
		//��Ƭ�ϴ�
		if(is_uploaded_file($_FILES[postfile][tmp_name])){
				$array[name]=is_array($postfile)?$_FILES[postfile][name]:$postfile_name;
				$array[path]=$webdb[updir]."/zh/";
				$array[size]=is_array($postfile)?$_FILES[postfile][size]:$postfile_size;
				$webdb[iconMaxUserPostZh]=$webdb[iconMaxUserPostZh]?$webdb[iconMaxUserPostZh]:50;
				if($array[size]>($webdb[iconMaxUserPostZh]*1024))$msg.="ͼƬ��С���ܳ���$webdb[iconMaxUserPostZh]k<br>";
				$picurl=upfile_func2(is_array($postfile)?$_FILES[postfile][tmp_name]:$postfile,$array);
				if(substr($picurl,0,3)=='ERR')	{
					$msg.=str_replace("ERR-","",$picurl)."<br>";
				}else{
					@unlink(PHP168_PATH.$array[path]."/".$oldpicurl);
				}
		}else{
				$picurl=$oldpicurl;
		}
		
		if(!$msg){
				//�õ�ĳЩ����
				$showroom_name=$db->get_one("select sr_id,title from {$_pre}zh_showroom where sr_id='$showroom'");
							
				$starttime=explode("-",$starttime);
				$starttime=mktime(0,0,0,$starttime[1],$starttime[2],$starttime[0]);
				$endtime=explode("-",$endtime);
				$endtime=mktime(0,0,0,$endtime[1],$endtime[2],$endtime[0]);
				$sname  =$Fid_db[name][$sid];
				//ִ��
				$db->query("update `{$_pre}zh_content` set
				`sid`='$sid',
				`sname`='$sname',
				`title`='$title',
				`province_id`='$postdb[province_id]',
				`city_id`='$postdb[city_id]',
				`picurl`='$picurl',
				`posttime`='".time()."',
				`starttime`='$starttime',
				`endtime`='$endtime',
				`organizers`='$organizers',
				`co_organizers`='$co_organizers',
				`showroom`='$showroom',
				`showroom_name`='$showroom_name[title]',
				`contact`='$contact',
				`goods`='$goods',
				`costs`='$costs',
				`info`='$info',
				`remarks`='$remarks'		
				
				where zh_id='$zh_id'");
					
				//�����Զ�������
				
				if(is_array($diydata)){
					//��ɾ����ǰ��
					$db->query("delete from `{$_pre}zh_content_1` where zh_id='$zh_id' ");
					//�����µ�
					foreach($diydata as $i=>$array)	{
						if(trim($array[title])!=''){
							$db->query("INSERT INTO `{$_pre}zh_content_1` ( `mzh_id_` , `zh_id` , `ind` , `title` , `content` , `listorder` , `listtype` ) VALUES (
'', '$zh_id', '$i', '$array[title]', '$array[content]', '0', '1');");
						}
					}				
				}
				
				//���°�ͼƬ	
				bd_pics("{$_pre}zh_content"," where zh_id ='$zh_id' ");

				echo "<SCRIPT LANGUAGE=\"JavaScript\">
				<!--
				parent.location='$Mdomain/zhshow.php?zh_id=$zh_id';	
				parent.location.href='$Mdomain/zhshow.php?zh_id=$zh_id';		
				//-->
				</SCRIPT>";exit;
				
		}else{
				echo "<SCRIPT LANGUAGE=\"JavaScript\">
				<!--
				parent.document.getElementById(\"postSubmit\").disabled=false;
				parent.document.getElementById(\"showmsg\").innerHTML='$msg';			
				//-->
				</SCRIPT>";exit;
		}
		
	}
	


}elseif($job=='postzlg'){$zhDB[title]="�Ǽ�չ����";

	if(!$step){
		$province_fid=select_where("province","'postdb[province_id]'  onChange=\"choose_where(this.options[this.selectedIndex].value,'','');\" ");
		$step=1;
	}else{
	
		//�̼�
		$company=$db->get_one("select * from {$_pre}company where uid='$lfjuid'");
		if(!$company) $msg.="�㻹û�еǼ��̼���Ϣ�����ȵǼǣ�<br>";

		$title=htmlspecialchars($title);
		if(!$title || strlen($title)>80 || strlen($title)<5 ) $msg.="չ�������Ʊ�����5-40����֮��<br>";
		if(!$postdb[province_id] || !$postdb[city_id]) $msg.="��ѡ��չ���ݵص�<br>";
		
		$address=htmlspecialchars($address);
		if(!$address || strlen($address)>200 || strlen($address)<5 ) $msg.="չ������ϸ��ַ������5-100����֮��<br>";
		
		$contact=htmlspecialchars($contact);
		if(strlen($contact)<10 || strlen($contact)>20000) $msg.="��ϵ��ʽ����10���ַ�,���20000�ַ�<br>";
		
		$content=htmlspecialchars($content);
		if(strlen($content)<10 || strlen($content)>20000) $msg.="��ϸ��������10���ַ�,���20000�ַ�<br>";
		//��Ƭ�ϴ�
		if(is_uploaded_file($_FILES[postfile][tmp_name])  && !$msg){
				$array[name]=is_array($postfile)?$_FILES[postfile][name]:$postfile_name;
				$array[path]=$webdb[updir]."/zh/";
				$array[size]=is_array($postfile)?$_FILES[postfile][size]:$postfile_size;
				$webdb[iconMaxUserPostZh]=$webdb[iconMaxUserPostZh]?$webdb[iconMaxUserPostZh]:50;
				if($array[size]>($webdb[iconMaxUserPostZh]*1024))$msg.="ͼƬ��С���ܳ���$webdb[iconMaxUserPostZh]k<br>";
				$picurl=upfile_func2(is_array($postfile)?$_FILES[postfile][tmp_name]:$postfile,$array);
				if(substr($picurl,0,3)=='ERR')	{
					$msg.=str_replace("ERR-","",$picurl)."<br>";
				}
	
		}else{
				$picurl="";
		}
		
		if(!$msg){
				//�õ�ĳЩ����
				$rid=$company[rid];
				$yz=$webdb[postzhCheck];
				if($yz) $yz_time=time();
				//ִ��
				$db->query("INSERT INTO `{$_pre}zh_showroom` ( `sr_id` ,`yz`,`yz_time`,`posttime`, `province_id` , `city_id` , `title` , `picurl` , `content` , `levels` , `rid` , `uid` , `username` , `address` , `website` , `contact` ) 
VALUES (
'', '$yz', '$yz_time','".time()."', '$postdb[province_id]', '$postdb[city_id]', '$title', '$picurl', '$content', '$levels', '$rid', '$lfjuid', '$lfjid', '$address', '$website', '$contact'
);");
				
				//���°�ͼƬ	
				$sr_id=$db->insert_id();
				bd_pics("{$_pre}zh_showroom"," where sr_id ='$sr_id' ");
				//��������
				if($webdb[post_add_money]) plus_money($lfjuid,$webdb[post_add_money]);			

				echo "<SCRIPT LANGUAGE=\"JavaScript\">
				<!--
				parent.location='$Mdomain/member/?main=zh.php?job=zlg';	
				parent.location.href='$Mdomain/member/?main=zh.php?job=zlg';		
				//-->
				</SCRIPT>";exit;
				
		}else{
				echo "<SCRIPT LANGUAGE=\"JavaScript\">
				<!--
				parent.document.getElementById(\"postSubmit\").disabled=false;
				parent.document.getElementById(\"showmsg\").innerHTML='$msg';			
				//-->
				</SCRIPT>";exit;
		}
	
	}
}elseif($job=='editzlg'){
	$sr_id=intval($sr_id);
	if(!$sr_id)  showerr("�Ƿ�����");
	if(!$step){

		$rsdb=$db->get_one("select * from `{$_pre}zh_showroom` where sr_id='$sr_id'");
		if(!$rsdb) showerr("�޷�������Ҫ�༭����Ϣ");		
		if(!$web_admin && $rsdb[uid]!=$lfjuid) showerr("����Ȩ����");
		if(!@extract($rsdb)){
			if(is_array($rsdb)){
			foreach($rsdb as $key=>$val){
				$$key=$val;
			}
			}
		}
		
		$province_fid=select_where("province","'postdb[province_id]'  onChange=\"choose_where(this.options[this.selectedIndex].value,'','');\" ",$province_id,0);
		
		$city_id=select_where("city","'postdb[city_id]' ",$city_id,$province_id);
		
		if($picurl)$picurl_show  =$webdb[www_url]."/".$webdb[updir]."/zh/".$picurl;
		
		$step=1;
	}else{
	
		$title=htmlspecialchars($title);
		if(!$title || strlen($title)>80 || strlen($title)<5 ) $msg.="չ�������Ʊ�����5-40����֮��<br>";
		if(!$postdb[province_id] || !$postdb[city_id]) $msg.="��ѡ��չ���ݵص�<br>";
		
		$address=htmlspecialchars($address);
		if(!$address || strlen($address)>200 || strlen($address)<5 ) $msg.="չ������ϸ��ַ������5-100����֮��<br>";
		
		$contact=htmlspecialchars($contact);
		if(strlen($contact)<10 || strlen($contact)>20000) $msg.="��ϵ��ʽ����10���ַ�,���20000�ַ�<br>";
		
		$content=htmlspecialchars($content);
		if(strlen($content)<10 || strlen($content)>20000) $msg.="��ϸ��������10���ַ�,���20000�ַ�<br>";
		//��Ƭ�ϴ�
		if(is_uploaded_file($_FILES[postfile][tmp_name])){
				$array[name]=is_array($postfile)?$_FILES[postfile][name]:$postfile_name;
				$array[path]=$webdb[updir]."/zh/";
				$array[size]=is_array($postfile)?$_FILES[postfile][size]:$postfile_size;
				$webdb[iconMaxUserPostZh]=$webdb[iconMaxUserPostZh]?$webdb[iconMaxUserPostZh]:50;
				if($array[size]>($webdb[iconMaxUserPostZh]*1024))$msg.="ͼƬ��С���ܳ���$webdb[iconMaxUserPostZh]k<br>";
				$picurl=upfile(is_array($postfile)?$_FILES[postfile][tmp_name]:$postfile,$array);
				$picurl=upfile_func2(is_array($postfile)?$_FILES[postfile][tmp_name]:$postfile,$array);
				if(substr($picurl,0,3)=='ERR')	{
					$msg.=str_replace("ERR-","",$picurl)."<br>";
				}else{
					@unlink($array[path].$oldpicurl);
				}
		}else{
				$picurl=$oldpicurl;
		}
		
		if(!$msg){
				
				$db->query("update `{$_pre}zh_showroom` set 
				`province_id`='$postdb[province_id]',
				`city_id`='$postdb[city_id]',
				`title`='$title',
				`picurl`='$picurl',
				`content`='$content',
				`address`='$address',
				`website`='$website',
				`contact`='$contact'				
				where sr_id=$sr_id");
				
				//���°�ͼƬ	
			
				bd_pics("{$_pre}zh_showroom"," where sr_id ='$sr_id' ");
				echo "<SCRIPT LANGUAGE=\"JavaScript\">
				<!--
				parent.location='$Mdomain/zh_showroom.php?sr_id=$sr_id';	
				parent.location.href='$Mdomain/zh_showroom.php?sr_id=$sr_id';		
				//-->
				</SCRIPT>";exit;
				
		}else{
				echo "<SCRIPT LANGUAGE=\"JavaScript\">
				<!--
				parent.document.getElementById(\"postSubmit\").disabled=false;
				parent.document.getElementById(\"showmsg\").innerHTML='$msg';			
				//-->
				</SCRIPT>";exit;
		}
	
	}


}elseif($job=="get_zlglist"){
	$html="<select name='$idname'><option value=0>��ѡ��չ����</option>";
	$province_fid=intval($province_fid);
	if($province_fid){
		$query=$db->query("select sr_id,title from {$_pre}zh_showroom where province_id='$province_fid'");
		while($rs=$db->fetch_array($query)){
			$html.="<option value='$rs[sr_id]'>$rs[title]</option>";
		}
	}
	$html.="</select>";
	echo "<SCRIPT LANGUAGE=\"JavaScript\">
		<!--
		parent.document.getElementById(\"{$showlist}\").innerHTML=\"$html\";
		//-->
		</SCRIPT>";
}


//SEO
$titleDB[title]			= filtrate(strip_tags("$zhDB[title]  - $webdb[Info_webname]"));
$titleDB[keywords]		= filtrate(strip_tags("$fidDB[metakeywords]  $webdb[Info_metakeywords]"));
$titleDB[description]	= get_word(strip_tags($fidDB[descrip]),200).filtrate(strip_tags("$fidDB[metadescription] $webdb[Info_metadescription]"));






require(Mpath."inc/head.php");
require(getTpl("zh_post"));
require(Mpath."inc/foot.php");

function getsid_options($sid=0){
	global $bcategory;
	$options="";
	foreach($bcategory->categories as $v){
		if($v['fid']==$sid) $ck=" selected"; else $ck="";
		$options.="<option value='$v[fid]' $ck>".$v['name']."</option>";
	}
	return $options;
}
?>