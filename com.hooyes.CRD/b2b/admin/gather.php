<?php
!function_exists('html') && exit('ERR');
set_time_limit(0);
error_reporting(0);

$erp=$Fid_db[iftable][$fid];
if($job=="copysina"&&$Apower[gather_copysina])
{
	if($step==2)
	{
		$sort_fid=$Guidedb->Select("{$pre}sort",'fid',$fid);
		$writefile=PHP168_PATH."cache/$y_$m_$d_.htm";
		if( !file_exists($writefile)&&$y_ ){
			if( date( "Ymd",time() )>"$y_$m_$d_"&&$y_&&$m_&&$d_ ){
				if(!copy("http://news.sina.com.cn/hotnews/{$y_}{$m_}{$d_}.shtml",$writefile)){

					if($filesss=file("http://news.sina.com.cn/hotnews/{$y_}{$m_}{$d_}.shtml")){
						$filesss=implode("",$filesss);
					}else{
						$filesss=sockOpenUrl("http://news.sina.com.cn/hotnews/{$y_}{$m_}{$d_}.shtml");
					}
					write_file($writefile,$filesss);
				}
			}else{
				if(!copy("http://news.sina.com.cn/hotnews/",$writefile)){
					if($filesss=file("http://news.sina.com.cn/hotnews/")){
						$filesss=implode("",$filesss);
					}else{
						$filesss=sockOpenUrl("http://news.sina.com.cn/hotnews/");
					}
					
					write_file($writefile,$filesss);
				}
			}
		}
		$file=read_file($writefile);
		unlink($writefile);
		$listdb=SinaTitle($newtype,$file);
	}
	/*ȡ��*/
	for($i=1;$i<13;$i++){
		if($i<10){
			$j="0$i";
		}else{
			$j=$i;
		}
		$selected='';
		if($m_==$j){
			$selected='selected';
		}elseif(date( "m",time() )==$j&&!$m_){
			$selected='selected';
		}
		$showmonth.="<option value='$j' $selected>$j</option>";
	}
	/*ȡ��*/
	for($i=1;$i<32;$i++){
		if($i<10){
			$j="0$i";
		}else{
			$j=$i;
		}
		$selected='';
		if($d_==$j){
			$selected='selected';
		}elseif(date( "d",time() )==$j&&!$d_){
			$selected='selected';
		}
		$showday.="<option value='$j' $selected>$j</option>";
	}
	require(dirname(__FILE__)."/"."head.php");
	require(dirname(__FILE__)."/"."template/gather/sina.htm");
	require(dirname(__FILE__)."/"."foot.php");
}
elseif($action=="copysina"&&$Apower[gather_copysina])
{
	if(!$fid){
		showmsg("��ѡ��Ҫ�����ĸ���Ŀ");
	}elseif(!is_array($newsdb)&&!$page){
		showmsg("��ѡ��һƪ����");
	}
	if(file_exists(PHP168_PATH."cache/copysina.php")){
		include_once(PHP168_PATH."cache/copysina.php");
	}
	if(!$page){	/*�ո��ύ��ʱ��.д���ı�.������һҳ����*/
		$ii=0;
		foreach( $newsdb AS $key=>$value ){
			$ii++;
			$value=str_replace('"',"'",$value);
			$writes.="
						\$titledb[$ii]['url']=\"$key\";
						\$titledb[$ii]['title']=\"$value\";
						";
		}
		write_file(PHP168_PATH."cache/copysina.php",'<?'.$writes);
		if(!is_writable(PHP168_PATH."cache/copysina.php") ){
			showmsg("���޸��ļ�����Ϊ0777-".PHP168_PATH."cache/copysina.php");
		}
		include(PHP168_PATH."cache/copysina.php");
		$page++;
	}else{	/*�Ѿ��Ǵӵڶ�ҳ��ʼ��*/
		$page++;
		include(PHP168_PATH."cache/copysina.php");
	}
	if($titledb[$page]){
		if(@copy("{$titledb[$page][url]}",PHP168_PATH."cache/copysina_content.htm")){
			$file=@file(PHP168_PATH."cache/copysina_content.htm");
		}else{
			$file=@file("{$titledb[$page][url]}");
		}
		if($file){
			$file=implode('',$file);
			$file=str_Replace("<center><IMG SRC","<center><img src",$file);
			$file=str_replace("<center><img src","\r\n<center><img src",$file);
		}else{
			$file=sockOpenUrl("{$titledb[$page][url]}");
			$file=str_Replace("<center><IMG SRC","<center><img src",$file);
			$file=str_replace("<center><img src","\r\n<center><img src",$file);
		}
		$file=preg_replace("/����˴��鿴ȫ��(.*?)ͼƬ/is","",$file);
		$file=preg_replace("/<img([^<]+) style='border:0'>/is","",$file);
		$file=str_Replace('<div align="center"><IMG SRC',"<center><img src",$file);
		$file=str_replace('<div align="center"><img src',"\r\n<center><img src",$file);
		$file=str_replace('<center><img',"\r\n<center><img",$file);

		$file=preg_replace("/(.*)<!-- google_ad_section_start -->(.*)<!-- google_ad_section_end -->(.*)/is","\\2",$file);


		$file=explode("\n",$file);
		$atc_picnews=$picurl='';
		/*ͼƬ����*/
		for($i=0;$i<count($file);$i++){
			if( (eregi("<p>",$file[$i])&& !eregi("<tr><td",$file[$i]))||(eregi("<!--NEWSZW_HZH_END-->",$file[$i])&& eregi("</p>",$file[$i]))||ereg("><img",$file[$i])){
				$file2[$i]=$file[$i];
				//if( ereg("<center><img",$file[$i]) ){
				if( ereg("><img",$file[$i]) ){
					//$rss=$db->get_one("SELECT aid FROM {$pre}article$erp ORDER BY aid DESC LIMIT 0,1");
					if( ereg(".jpg",$file[$i]) ){
						$file[$i]=strstr($file[$i],"http://");
						$atc_picnews=substr($file[$i],0,strpos($file[$i],"jpg")+3);
					}else{
						$file[$i]=strstr($file[$i],"http://");
						$atc_picnews=substr($file[$i],0,strpos($file[$i],"JPG")+3);
					}
					if($copypic==1){
						if( !is_dir(PHP168_PATH."$webdb[updir]/article") ){
							if( !@mkdir(PHP168_PATH."$webdb[updir]/article",0777) ){
								die("���ֹ�����Ŀ¼".PHP168_PATH."$webdb[updir]/article".",��������Ϊ0777");
							}
						}elseif(!is_writable(PHP168_PATH."$webdb[updir]/article")){
							die("���ֹ��޸�Ŀ¼".PHP168_PATH."$webdb[updir]/article".",����Ϊ0777");
						}
						if( !is_dir(PHP168_PATH."$webdb[updir]/article/$fid") ){
							if( !@mkdir(PHP168_PATH."$webdb[updir]/article/$fid",0777) ){
								die("���ֹ�����Ŀ¼".PHP168_PATH."$webdb[updir]/article/$fid".",��������Ϊ0777");
							}
						}elseif(!is_writable(PHP168_PATH."$webdb[updir]/article/$fid")){
							die("���ֹ��޸�Ŀ¼".PHP168_PATH."$webdb[updir]/article/$fid".",����Ϊ0777");
						}
						$name="$lfjdb[uid]_sina_".date("YmdHis",$timestamp).'_'.$i.rand(1,999).".jpg";

						if( @copy($atc_picnews,PHP168_PATH."$webdb[updir]/article/$fid/$name") ){

							$file2[$i]=str_replace("$atc_picnews",tempdir("article/$fid/$name"),$file2[$i]);
							$file2[$i]=En_TruePath($file2[$i]);
							if(!$picurl){
								$smallpic="article/$fid/$name.gif";
								$Newpicpath=PHP168_PATH."$webdb[updir]/$smallpic";
								if($webdb[if_gdimg]){
									gdpic(PHP168_PATH."$webdb[updir]/article/$fid/$name",$Newpicpath,300,220,$webdb[autoCutSmallPic]?array('fix'=>1):'');
								}
								if(is_file($Newpicpath)){
									$picurl=$smallpic;
								}else{
									$picurl="article/$fid/$name";
								}
							}

							//��ˮӡ
							if($webdb[is_waterimg]&&($webdb[if_gdimg]))
							{
								include_once(PHP168_PATH."inc/waterimage.php");
								$uploadfile=PHP168_PATH."$webdb[updir]/article/$fid/$name";
								imageWaterMark($uploadfile,$webdb[waterpos],PHP168_PATH.$webdb[waterimg]);
							}
						}
					}elseif(!$picurl){
						$picurl=$atc_picnews;
					}
				}
			}
		}

		$lfj_concent=implode("",$file2);

		/*�������������ݹ��˴���*/
		$lfj_concent=str_replace("table","br",$lfj_concent);
		$lfj_concent=str_replace("tr","br",$lfj_concent);
		$lfj_concent=str_replace("td","br",$lfj_concent);
		$lfj_concent=str_replace("<!--"," ",$lfj_concent);
		$lfj_concent=str_replace("-->"," ",$lfj_concent);
		$lfj_concent=str_replace("NEWSZW_HZH_END"," ",$lfj_concent);
		$lfj_concent=str_replace("NEWSZW_HZH_BEGIN"," ",$lfj_concent);
		$lfj_concent=str_replace('<style type="text/css">'," ",$lfj_concent);
		
		if( ereg("���˼��",$lfj_concent) ){
			//$numss=strpos($lfj_concent,'<style>');
			//$lfj_concent=substr($lfj_concent,0,$numss);
		}
		if(!$copy_ck){
			$lfj_concent="$lfj_concent<br><br><br>����ת���� ������:<A HREF={$titledb[$page]['url']} target=_blank>{$titledb[$page]['url']}</A>";
		}	$lfj_concent=str_Replace("����վ�ɽ���֪ʶ����<br>��Ȩ�齢��֪ʶ���У�δ��������ת��","",$lfj_concent);
		$lfj_concent=str_Replace("wapdump begin","",$lfj_concent);
		$lfj_concent=str_Replace(" wapdump end ","",$lfj_concent);
		$lfj_concent=str_Replace("(blog)","",$lfj_concent);
		$lfj_concent=str_Replace("��ҳ","",$lfj_concent);
		$lfj_concent=mysql_escape_string($lfj_concent);

		/*�ж��Ƿ���ͼƬר��*/
		if(!eregi('gif',$atc_picnews)&&!eregi('jpg',$atc_picnews) ){
			$atc_picnews='';
		}

		$timestamp=$timestamp-$page*100;
		$rsfid=$db->get_one("SELECT name,fmid FROM {$pre}sort WHERE fid='$fid' ");
		$yz=1;
		unset($download);
		if($copypic==1){
			/*
			$query=$db->query(" SELECT * FROM {$pre}download WHERE aid='$aid' ");
			while($rs=$db->fetch_array($query) ){
				$download.="$rs[did]|$rs[fid]|$rs[aid]|$rs[rid]|$rs[fileurl]|$rs[filename]|$rs[filesize]|$rs[loadnums]\n";
			}
			*/
		}

		if($picurl){
			$ispic=1;
		}else{
			$ispic=0;
		}
		$db->query("INSERT INTO `{$pre}article$erp` (`title`, `smalltitle`, `mid`, `fid`, `fname`, `info`, `hits`, `pages`, `comments`, `posttime`, `list`, `uid`, `username`, `author`, `copyfrom`, `copyfromurl`, `titlecolor`, `titleicon`, `picurl`, ispic, `yz`, `yzer`, `yztime`, `levels`, `levelstime`, `keywords`, `jumpurl`, `style`, `template`, `target`, `ip`, `lastfid`, `money`, `passwd`, `editer`, `edittime`, `begintime`, `endtime`,  `description`) VALUES ('{$titledb[$page][title]}','$smalltitle','$rsfid[fmid]','$fid','$rsfid[name]','$info','$hits','1','$comments','$timestamp','$timestamp','$userdb[uid]','$userdb[username]','$author','$copyfrom','$copyfromurl','$titlecolor','$titleicon','$picurl','$ispic','$yz','$yzer','$yztime','$levels','$levelstime','$keywords','$jumpurl','$style','$template','$target','$ip','$lastfid','$money','$passwd','$editer','$edittime','$begintime','$endtime','$description')");
		
		$lfj_concent=str_replace("center","left",$lfj_concent);
		$rs=$db->get_one("SELECT aid FROM `{$pre}article$erp` ORDER BY aid DESC LIMIT 1");

		$db->query("INSERT INTO `{$pre}article_db` (`aid`) VALUES ('$rs[aid]')");

		$db->query("INSERT INTO `{$pre}reply$erp` (  `aid` , `fid` ,`uid` ,  `content` ,`ishtml`,topic) VALUES ( '$rs[aid]', '$fid','$userdb[uid]', '$lfj_concent','1',1)");
		
		echo "<CENTER>�ѵ��� <font color=red>$page</font> ��, ���⣺{$titledb[$page][title]}<br><img src='{$atc_picnews}' onerror=\"this.src='images/lineheight.gif'\"></CENTER>";
		echo "<META HTTP-EQUIV=REFRESH CONTENT='0;URL=index.php?lfj=gather&action=copysina&page=$page&fid=$fid&copypic=$copypic'>";
		exit;
	}else{
		$page--;
		unlink(PHP168_PATH."cache/copysina.php");
		unlink(PHP168_PATH."cache/copysina_content.htm");
		if(!$titledb[$page]&&$page<2){
			showmsg("<div align='center'>�������ʧ��,��ȷ��".PHP168_PATH."cacheĿ¼�������ļ�����Ϊ0777<br><A HREF=?lfj=copynews&job=add>���²ɼ�����</A></div>");
		}else{
			jump("�������,���ι����� <font color='#FF0000'><strong>{$page}</strong></font>  ��<br>�������Ϊ���ε��벻�ɹ�,���Զ༸��,����ȷ��".PHP168_PATH."cacheĿ¼�������ļ�����Ϊ0777<br><A HREF=?lfj=copynews&job=add>�����ɼ�����</A></div>","index.php?lfj=gather&job=copysina",10);
		}
	}

}
elseif($job=="add_title"&&$Apower[gather_list])
{
	$link_noinclude_word="<";
	$page_step=1;
	$webname="���ɼ�����վ����";
	$sort_fid=$Guidedb->Select("{$pre}gather_sort","fid",$fid);
	$rulepage='0';
	require(dirname(__FILE__)."/"."head.php");
	require(dirname(__FILE__)."/"."template/gather/edit_title.htm");
	require(dirname(__FILE__)."/"."foot.php");
}
elseif($action=="add_title"&&$Apower[gather_list])
{
	if($rulepage==0&&!$listmoreurl){
		showmsg("Ҫ�ɼ�����ַ����Ϊ��");
	}elseif($rulepage&&!$listurl){
		showmsg("Ҫ�ɼ���ͨ����ַ����Ϊ��");
	}
	if(!$title_rule){
		showmsg("�Զ��������﷨������Ϊ��");
	}elseif(!strstr($title_rule,'{url=')||!strstr($title_rule,'{title=')){
		showmsg("��������д�Զ��������﷨");
	}
	if( !$db->get_one("SELECT * FROM `{$pre}gather_rule` WHERE posttime='$posttime' ") ){
		$db->query("INSERT INTO `{$pre}gather_rule` ( `type` , `filetype` , `webname` , `listurl` , `firstpage` , `page_begin` , `page_end` , `page_step` , `listmoreurl` , `link_include_word` , `link_noinclude_word` , `link_replace_word` , `title_replace_word` , `list_begin_code` , `list_end_code` , `list_begin_preg` , `list_end_preg` , `gatherthesame` , `title_minleng` , `show_end_preg` , `show_begin_code` , `show_end_code` , `show_replace_word` , `show_morepage` , `posttime` , `list`,title_rule,charset_type,fid ) 
		VALUES (
		'$type','$filetype','$webname','$listurl','$firstpage','$page_begin','$page_end','$page_step','$listmoreurl','$link_include_word','$link_noinclude_word','$link_replace_word','$title_replace_word','$list_begin_code','$list_end_code','$list_begin_preg','$list_end_preg','$gatherthesame','$title_minleng','$show_end_preg','$show_begin_code','$show_end_code','$show_replace_word','$show_morepage','$posttime','$timestamp','$title_rule','$charset_type','$fid'
		)");
	}else{
		$db->query("UPDATE `{$pre}gather_rule` SET webname='$webname',listurl='$listurl',firstpage='$firstpage',page_begin='$page_begin',page_end='$page_end',page_step='$page_step',listmoreurl='$listmoreurl',link_include_word='$link_include_word',link_noinclude_word='$link_noinclude_word',link_replace_word='$link_replace_word',title_replace_word='$title_replace_word',list_begin_code='$list_begin_code',list_end_code='$list_end_code',list_begin_preg='$list_begin_preg',list_end_preg='$list_end_preg',title_minleng='$title_minleng',title_rule='$title_rule',charset_type='$charset_type',fid='$fid' WHERE posttime='$posttime'");
	}
	$rs=$db->get_one("SELECT id FROM {$pre}gather_rule WHERE posttime='$posttime' ");
	if($testgather){
		jump("���ڲ��Բɼ�����,�����ĵȴ�","index.php?lfj=$lfj&action=gather_title&id=$rs[id]&showurl=1",1);
	}
	jump("������һ��","index.php?lfj=$lfj&job=edit_content&id=$rs[id]",1);
}
elseif($job=="edit_title"&&$Apower[gather_list])
{
	$rsdb=$db->get_one("SELECT * FROM {$pre}gather_rule WHERE id='$id'");

	$sort_fid=$Guidedb->Select("{$pre}gather_sort","fid",$rsdb[fid]);

	$rsdb[title_rule]=str_replace("&nbsp;","&amp;nbsp;",$rsdb[title_rule]);

	@extract($rsdb);
	if($listurl){
		$rulepage='1';
	}else{
		$rulepage='0';
	}
	$charset_typedb[intval($rsdb[charset_type])]=' checked ';
	require(dirname(__FILE__)."/"."head.php");
	require(dirname(__FILE__)."/"."template/gather/edit_title.htm");
	require(dirname(__FILE__)."/"."foot.php");
}
elseif($action=="edit_title"&&$Apower[gather_list])
{
	$db->query("UPDATE `{$pre}gather_rule` SET webname='$webname',listurl='$listurl',firstpage='$firstpage',page_begin='$page_begin',page_end='$page_end',page_step='$page_step',listmoreurl='$listmoreurl',link_include_word='$link_include_word',link_noinclude_word='$link_noinclude_word',link_replace_word='$link_replace_word',title_replace_word='$title_replace_word',list_begin_code='$list_begin_code',list_end_code='$list_end_code',list_begin_preg='$list_begin_preg',list_end_preg='$list_end_preg',title_minleng='$title_minleng',title_rule='$title_rule',charset_type='$charset_type',fid='$fid' WHERE id='$id'");
	if($testgather){
		jump("���ڲ��Բɼ�����,�����ĵȴ�","index.php?lfj=$lfj&action=gather_title&id=$id&showurl=1",1);
	}
	jump("������һ��������ϸ����","index.php?lfj=$lfj&job=edit_content&id=$id",1);
}
elseif($job=="edit_content"&&$Apower[gather_list])
{
	$rsdb=$db->get_one("SELECT * FROM {$pre}gather_rule WHERE id='$id'");
	
	$rsdb[content_rule]=str_replace("&nbsp;","&amp;nbsp;",$rsdb[content_rule]);

	@extract($rsdb);
	
	$type || $type='iframe';
	$typedb[$type]=" checked ";
	$gatherthesame=intval($gatherthesame);
	$gatherthesamedb[$gatherthesame]=" checked ";
	$show_spe2page=intval($show_spe2page);
	$show_spe2pagedb[$show_spe2page]=" checked ";
	require(dirname(__FILE__)."/"."head.php");
	require(dirname(__FILE__)."/"."template/gather/edit_content.htm");
	require(dirname(__FILE__)."/"."foot.php");
}
elseif($action=="edit_content"&&$Apower[gather_list])
{
	if($content_rule){
		if( strstr($content_rule,"*}{*}")||strstr($content_rule,"{*}{") ){
			showmsg("���������ͨ������ֶ�ͨ���֮��,����������ͨ���֮��,����Ҫ��HTML�������");
		}
	}
	$fixsystem || $fixsystem='article';
	$db->query("UPDATE {$pre}gather_rule SET  type='$type',gatherthesame='$gatherthesame',show_begin_preg='$show_begin_preg',show_end_preg='$show_end_preg',show_endfile_preg='$show_endfile_preg',show_begin_code='$show_begin_code',show_end_code='$show_end_code',show_replace_word='$show_replace_word',show_morepage='$show_morepage',show_firstpage='$show_firstpage',copypic='$copypic',sort='$sort',file_type='$file_type',file_minleng='$file_minleng',file_minsize='$file_minsize',file_includeword='$file_includeword',file_noincludeword='$file_noincludeword',file_explode='$file_explode',file_picwidth='$file_picwidth',fixsystem='$fixsystem',file_star_string='$file_star_string',content_rule='$content_rule',show_spe2page='$show_spe2page' WHERE id='$id'");
	if($testgather){
		jump("�����ĵȴ�,�Ȳɼ�����,���ٵ��ѡ����Բɼ�","index.php?lfj=gather&action=gather_title&id=$id&testgather=$testgather",1);
	}
	if($content_rule){
		preg_match_all("/\{([a-z\*=]+)\}/is",$content_rule,$array);
		foreach($array[1] AS $key=>$value){
			if($value!='*'){
				$v0=$array[1][$key-1];
				$v1=$array[1][$key+1];
				if( ($v0&&$v0!='*')||($v1&&$v1!='*') ){
					if( ($v0&&$v0!='*') ){
						$MSG="��{{$value}}���롰{{$v0}}��";
					}else{
						$MSG="��{{$value}}���롰{{$v1}}��";
					}
					jump("<font color=red>����::�������ύ,���п��ܲɼ����ɹ�,��Ϊ��������ֶ�ͨ���{$MSG}֮��,û��ʹ�ò��������ͨ���{*},��������Ǳ���Ҫʹ�õ�</font>","index.php?lfj=gather&job=list",60);
				}
			}
		}
	}
	jump("�޸ĳɹ�","index.php?lfj=gather&job=list",1);
}
elseif($action=="gather_title"&&$Apower[gather_list])
{
	$rsdb=$db->get_one("SELECT * FROM {$pre}gather_rule WHERE id='$id'");
	$page=intval($page);
	if(!$page){
		if($rsdb[listmoreurl]){
			$detail=explode("\r\n",$rsdb[listmoreurl]);
			foreach( $detail AS $key=>$value){
				$allurldb[]="\$urldb[]='$value';";
			}
			$allurl=implode("\r\n",$allurldb);
		}else{
			$rsdb[page_step] || $rsdb[page_step]=1;
			
			for($i=$rsdb[page_begin];$i<=$rsdb[page_end];$i++ ){
				if($rsdb[page_begin]==0){
					$II=($i-1)*$rsdb[page_step];
				}else{
					$II=($i-1)*$rsdb[page_step]+1;
				}
				
				$value=str_replace("[page]","$II",$rsdb[listurl]);
				if($i==1&&$rsdb[firstpage]){
					$value=$rsdb[firstpage];
				}
				$allurldb[]="\$urldb[]='$value';";
			}//print_R($allurldb);  die($allurldb);
			$allurl=implode("\r\n",$allurldb);
		}
		write_file(PHP168_PATH."cache/gather_morepage.php","<?php\r\n".$allurl);
		write_file(PHP168_PATH."cache/gather_title.php","<?php\r\n");
		if($rsdb[list_begin_preg]){
			write_file(PHP168_PATH."cache/gather_list.begin_preg.php","<?php\r\n$rsdb[list_begin_preg]");
		}
		if($rsdb[list_end_preg]){
			write_file(PHP168_PATH."cache/gather_list.end_preg.php","<?php\r\n$rsdb[list_end_preg]");
		}
		if($rsdb[show_begin_preg]){
			write_file(PHP168_PATH."cache/gather_show.begin_preg.php","<?php\r\n$rsdb[show_begin_preg]");
		}
		if($rsdb[show_end_preg]){
			write_file(PHP168_PATH."cache/gather_show.end_preg.php","<?php\r\n$rsdb[show_end_preg]");
		}
		if($rsdb[show_endfile_preg]){
			write_file(PHP168_PATH."cache/gather_show.endfile_preg.php","<?php\r\n$rsdb[show_endfile_preg]");
		}
	}
	@include(PHP168_PATH."cache/gather_morepage.php");
	$listurl=$urldb[$page];
	if($code=file_get_contents($listurl))
	{
	}
	elseif($code=file($listurl))
	{
		$code=implode("",$code);
	}
	elseif(copy($listurl,PHP168_PATH."cache/gather_cache.php"))
	{
		$code=read_file(PHP168_PATH."cache/gather_cache.php");
	}
	elseif($code=sockOpenUrl($listurl))
	{
	}
	else
	{
		echo("�ɼ�ʧ��<br><br><br><br><br><br><br><br><br><br>");
	}

	//UTF8->GBK
	if($rsdb[charset_type]==1){
		require_once(PHP168_PATH."inc/class.chinese.php");
		$cnvert = new Chinese("UTF8","GB2312",$code,PHP168_PATH."./inc/gbkcode/");
		$code = $cnvert->ConvertIT();
	}

	//��ͷ����
	if($rsdb[list_begin_preg]){
		$htmlcode=$code;	//�����ⲿ�������ı����Ƚ�ͳһ
		include(PHP168_PATH."cache/gather_list.begin_preg.php");
		$code=$htmlcode;
	}
	
	//��ȡ��ĳ���ַ���ʼ����β������
	if($rsdb[list_begin_code]){
		$code=strstr($code,$rsdb[list_begin_code]);
	}

	//ȥ��ĳ���ַ����������
	if($rsdb[list_end_code]){
		$end_content=strstr($code,$rsdb[list_end_code]);
		$code=str_replace($end_content,"",$code);
	}

	//�滻һЩ�ַ�
	if($rsdb[title_replace_word]){
		$detail=explode("\r\n",$rsdb[title_replace_word]);
		foreach($detail AS $key=>$value){
			list($oldword,$newword)=explode("|",$value);
			$code=str_replace($oldword,$newword,$code);
		}
	}

	//�û��Զ�������
	if($rsdb[title_rule])
	{
		//�ѿհ׶�ȥ��,���㴦��
		$rsdb[title_rule]=clean_blank($rsdb[title_rule]);
		$code=clean_blank($code);

		//��ȡ������Ĺ�������
		preg_match_all("/\{(.*?)\}/is",$rsdb[title_rule],$array);
		
		//��ȡ����
		foreach( $array[1] AS $key=>$value){
			if( !ereg("^NO",$value)&&!ereg("^\*",$value) ){
				$detail=explode("=",$value);
				$ruledb[++$key]=$detail[0];
			}
		}
		
		//��ȡ�������ʹ�õĹ���
		$rule = get_rule($rsdb[title_rule]);

		//�Բɼ������ݸ����������У��
		preg_match_all("/$rule/is",$code,$array2);

		//��ȡ���õ�����
		foreach( $ruledb AS $key=>$value){
			foreach( $array2[$key] AS $key2=>$value2){
				$listdb[$value][]=$value2;
			}
		}
		
		//url���µ�ַ����Ҫ�е�
		$detail_content=$listdb[url];
	}

	else
	{
		$code=str_replace("HREF=","href=",$code);
		$code=str_replace("</A>","</a>",$code);
		$code=str_replace("href='","href=",$code);
		$code=str_replace('href="','href=',$code);
		$detail_content=explode("href=",$code);	
	}


	unset($i,$_url,$_title);
	foreach($detail_content AS $key_content=>$value_content){
		if($rsdb[title_rule])
		{
			$url=$value_content;
			$title=$listdb[title][$key_content];
			$picurl=$listdb[picurl][$key_content];
		}
		else
		{
			if($key_content==0){
				continue;
			}
			$url=preg_replace("/([^'\" >]+)(.*)/is","\\1",$value_content);//echo $url; die();
			$s1_title=strstr($value_content,">");
			$s2_title=strstr($value_content,"</a>");
			$s3_title=str_replace($s2_title,"",$s1_title);
			$title=str_replace(">","",$s3_title);
			$title=substr($s3_title,1,strlen($s3_title)-1);
			$title=preg_replace("/<([^<>]+)>(.*)<([^<>]+)>/is","\\2",$title);
		}


		//������URL�в��ܰ����е��ַ�
		if($rsdb[link_noinclude_word]){
			$detail=explode("\r\n",$rsdb[link_noinclude_word]);
			foreach($detail AS $key=>$value){
				if(!$value){
					continue;
				}
				if(strstr($title,$value)||strstr($url,$value)){
					unset($url,$title);
				}
			}
		}

		//URL�б�������е��ַ�
		if($rsdb[link_include_word]){
			$detail=explode("\r\n",$rsdb[link_include_word]);
			foreach($detail AS $key=>$value){
				if(!$value){
					continue;
				}
				if(!strstr($url,$value)){
					unset($url,$title);
				}
			}
		}

		//���ⲻ��С�ڶ�����
		if($rsdb[title_minleng]){
			if(strlen($title)<$rsdb[title_minleng]+1){
				unset($url,$title);
			}
		}

		//�����еĵ�ַҪ�滻
		if($rsdb[link_replace_word]){
			$__detail=explode("\r\n",$rsdb[link_replace_word]);
			foreach( $__detail AS $__key=>$__value){
				$__de=explode("|",$__value);
				$url=str_replace($__de[0],$__de[1],$url);
			}
		}

		//�Ե�ַ�������Խ��д���
		if($url&&$title){
			if(!ereg("://",$url)){
				if(ereg("^/",$url)){
					$url=preg_replace("/http:\/\/([^\/]+)(.*)/is","http://\\1$url",$listurl);
				}else{
					$url=str_replace(basename($listurl),"",$listurl).$url;
				}
				
			}
			if($picurl&&!ereg("://",$picurl)){
				if(ereg("^/",$picurl)){
					$picurl=preg_replace("/http:\/\/([^\/]+)(.*)/is","http://\\1$picurl",$listurl);
				}else{
					$picurl=str_replace(basename($listurl),"",$listurl).$picurl;
				}
				
			}

			$url=str_replace("'","&#39;",$url);
			$picurl=str_replace("'","&#39;",$picurl);
			$title=str_replace("'","&#39;",$title);
			$_url[]=$url;
			$_title[]=$title;
			if($picurl)
			{
				$UT[]="\$urldb[]='$url\t$title@@$picurl';";
			}
			else
			{
				$UT[]="\$urldb[]='$url\t$title';";
			}
		}
	}
	$writefile=implode("\r\n",$UT);

	//��β����
	if($rsdb[list_end_preg])
	{
		$htmlcode=$writefile;	//�����ⲿ�������ı����Ƚ�ͳһ
		include(PHP168_PATH."cache/gather_list.end_preg.php");
		$writefile=$htmlcode;	//�����ⲿ�������ı����Ƚ�ͳһ
	}

	write_file(PHP168_PATH."cache/gather_title.php","\r\n".$writefile,'a');
	$page++;
	
	if($urldb[$page]){
		unset($urldb);
		include(PHP168_PATH."cache/gather_title.php");
		echo "$listurl<br>���ڲɼ���[{$page}]ҳ�ı�����������ַ,���Ժ�...<hr>";
		foreach( $urldb AS $key=>$value){
			if($key>50){
				break;
			}
			echo "$value<br>";
		}
		echo "<META HTTP-EQUIV=REFRESH CONTENT='0;URL=index.php?lfj=$lfj&action=$action&id=$id&showurl=$showurl&testgather=$testgather&page=$page'>";
		exit;
	}else{
		echo "<META HTTP-EQUIV=REFRESH CONTENT='0;URL=index.php?lfj=$lfj&showurl=$showurl&testgather=$testgather&job=list_title&id=$id'>";
		exit;
	}
}
elseif($job=="list_title"&&$Apower[gather_list])
{
	$rs=$ruledb=$db->get_one("SELECT * FROM {$pre}gather_rule WHERE id='$id'");
	if($rs[type]=="jump"){
		$msg="ע��:��ǰ�����ļ����õĲ���:����������ת���ⲿ��ַ,ʹ������󲿷���Ч";
	}elseif($rs[type]=="iframe"){
		$msg="ע��:��ǰ�����ļ����õĲ���:�����������ⲿ��ַ,���ƴ��졢�滢,ʹ������󲿷���Ч";
	}
	if($testgather){
		$autosub="autosub();";
	}
	include(PHP168_PATH."cache/gather_title.php");
	require(dirname(__FILE__)."/"."head.php");
	require(dirname(__FILE__)."/"."template/gather/list_title.htm");
	require(dirname(__FILE__)."/"."foot.php");
}
elseif($action=="list_title"&&$Apower[gather_list])
{
	include(PHP168_PATH."cache/gather_title.php");
	/*
	foreach( $urldb AS $key=>$value){
		if($postdb[$key]){
			$UT[]="\$urldb[]='$value';";
		}
	}
	*/
	//������
	$num=count($urldb)-1;
	for($i=$num;$i>=0;$i--){
		if($postdb[$i]){
			$UT[]="\$urldb[]='{$urldb[$i]}';";
		}
	}
	
	$writefile=implode("\r\n",$UT);
	write_file(PHP168_PATH."cache/gather_title.php","<?php\r\n".$writefile);
	$action='gather_content';
	echo "<META HTTP-EQUIV=REFRESH CONTENT='1;URL=?lfj=$lfj&action=$action&id=$id&system_type=$system_type&GetFile=$GetFile&file_dir=$file_dir&makesmallpic=$makesmallpic&showpic=$showpic&username=$username&fid=$fid&testgather=$testgather&page=$page'>";
	exit;
}
elseif($action=="gather_content"&&$Apower[gather_list])
{
	unset($urldb);
	$rsdb=$db->get_one("SELECT * FROM {$pre}gather_rule WHERE id='$id'");
	@include(PHP168_PATH."cache/gather_title.php");
	$page=intval($page);
	//$morepage����0ʱ.�����ҳ
	list($curl,$title,$morepage)=explode("\t",$urldb[$page]);

	if($show_content=file_get_contents($curl))
	{
	}
	elseif($show_content=file($curl))
	{
		$show_content=implode("",$show_content);
	}
	elseif(copy($curl,PHP168_PATH."cache/gather_cache.php"))
	{
		$show_content=read_file(PHP168_PATH."cache/gather_cache.php");
	}
	elseif($show_content=sockOpenUrl($curl))
	{
	}
	else
	{
		echo("��������ȡ����Զ���ļ���Ϣ,����ɼ�ʧ��$curl<br><br><br><br><br><br><br><br><br><br>");
	}
	
	//UTF8->GBK
	if($rsdb[charset_type]==1){
		require_once(PHP168_PATH."inc/class.chinese.php");
		$cnvert = new Chinese("UTF8","GB2312",$show_content,PHP168_PATH."./inc/gbkcode/");
		$show_content = $cnvert->ConvertIT();
	}

	if($rsdb[type]=='iframe'){//�����滢
		$iframeurl=$curl;
	}else{
		//��ͷ��������
		if($rsdb[show_begin_preg]){
			$htmlcode=$show_content;
			include(PHP168_PATH."cache/gather_show.begin_preg.php");
			$show_content=$htmlcode;
		}

		//��һƪ���¶�ҳ�Ĵ���,ֻ���ڵ�һҳ��ʱ����.�ڶ�ҳ�Ͳ���Ҫ��
		if($rsdb[show_morepage]&&!$morepage){
			$i=1;
			unset($moreurl_db);
			do{
				$i++;
				//����ҳ���һҳ�Ĳ�֮ͬ�����滻�õ���ҳ����ʵ��ַ
				
				if($rsdb[show_firstpage]){
					$nexturl=str_replace($rsdb[show_firstpage],str_replace("[page]",$i,$rsdb[show_morepage]),$curl);
				}else{
					$nexturl=preg_replace("/(.*)([^\d]+)([\d]*)$/is","\\1\\2",$curl).str_replace("[page]",$i,$rsdb[show_morepage]);
				}
				
				
				//��һЩ�������վ����.�����һҳ��index.htm�ڶ�ҳ��Ȼ��index_1.htm
				if($i==2&&$rsdb[show_spe2page]){
					$tsurl=str_replace($rsdb[show_firstpage],str_replace("[page]",1,$rsdb[show_morepage]),$curl);
					if( strstr($show_content,basename($tsurl)) ){
						$moreurl_db[$page][]="$tsurl\t$title\t1";
					}
				}
				if(strstr($show_content,basename($nexturl))){
					$moreurl_db[$page][]="$nexturl\t$title\t$i";
				}else{
					$i=0;
				}
			}
			while($i!=0);
			if(is_array($moreurl_db[$page])){
				//��ԭҪ�ɼ������������´���,��Ϊ�����˷�ҳ
				foreach($urldb AS $key=>$value){
					$_urlDB[]="\$urldb[]='$value';";
					if($page==$key&&is_array($moreurl_db[$key])){
						foreach($moreurl_db[$key] AS $key2=>$value2){
							$_urlDB[]="\$urldb[]='$value2';";
						}
					}
					
				}
				$write_file=implode("\r\n",$_urlDB);
				write_file(PHP168_PATH."cache/gather_title.php","<?php\r\n$write_file");
				unset($urldb);
				include(PHP168_PATH."cache/gather_title.php");
			}
		}

		//�û��Զ�������,������������
		if($rsdb[content_rule])
		{

			//�ѿհ׶�ȥ��,���㴦��
			$rsdb[content_rule]=clean_blank($rsdb[content_rule]);
			$show_content=clean_blank($show_content);

			//��ȡ������Ĺ�������
			preg_match_all("/\{(.*?)\}/is",$rsdb[content_rule],$array);
		
			//��ȡ����
			foreach( $array[1] AS $key=>$value){
				if( !ereg("^NO",$value)&&!ereg("^\*",$value) ){
					$detail=explode("=",$value);
					$ruledb[++$key]=$detail[0];
				}
			}
		
			//��ȡ�������ʹ�õĹ���
			$rule = get_rule($rsdb[content_rule]);

			//�Բɼ������ݸ����������У��
			preg_match_all("/$rule/is",$show_content,$array2);
			
	
			//��ȡ���õ�����
			foreach( $ruledb AS $key=>$value){
				foreach( $array2[$key] AS $key2=>$value2){
					$listdb[$value][]=$value2;
				}
			}
			
			//���û��Զ���ı�����ȡ����
			foreach( $listdb AS $key=>$value){
				$$key=$value[0];
				$gather_module_valeDB[$key]=$value[0];	//Ϊ����ģ���ֶβɼ�������
			}

			if($content)
			{
				$show_content=$content;
			}
			//��Ҫ�Ǵ������ֻ��л��Ĺ��.�����½س�������
			elseif($content1)
			{
				$show_content=$content1.$content2;
			}
		}


		//��������ǰ����Ч����
		if($rsdb[show_begin_code]){
			$show_content=strstr($show_content,$rsdb[show_begin_code]);
			$num_1=strlen($rsdb[show_begin_code]);
			$num_2=strlen($show_content);
			$show_content=substr($show_content,$num_1,$num_2);
		}
		//�������º����Ч����
		if($rsdb[show_end_code]){
			$end_content=strstr($show_content,$rsdb[show_end_code]);
			$show_content=str_replace($end_content,"",$show_content);
		}
		//���������в��뿴��������
		if($rsdb[show_replace_word]){
			$detail=explode("\r\n",$rsdb[show_replace_word]);
			foreach($detail AS $key=>$value){
				list($oldword,$newword)=explode("|",$value);
				$show_content=str_replace($oldword,$newword,$show_content);
			}
		}
		//���½�β��������
		if($rsdb[show_end_preg]){
			$htmlcode=$show_content;
			include(PHP168_PATH."cache/gather_show.end_preg.php");
			$show_content=$htmlcode;
		}
	}
	
	//��ȡ�ļ�.�ļ��и��,ͼƬһ��src=,����Ĭ����ͼƬ
	if(!$rsdb[file_explode]){
		$rsdb[file_explode]='src=';
		$show_content=str_replace("SRC=","src=",$show_content);
		if( !$rsdb[file_type] && $rsdb[fixsystem]=='article' ){
			$rsdb[file_type]="jpg|gif|png";
		}
	}
	$Filedb=GetFileUrl($rsdb,$show_content,$curl);
	//��β����,����ֱ��ͨ�������ȡ�ļ���ַ,�������ȡ�ļ��Ļ�.���������н�β�������е���ͬ
	if($rsdb[show_endfile_preg]){
		$htmlcode=$show_content;
		include(PHP168_PATH."cache/gather_show.endfile_preg.php");
		$show_content=$htmlcode;
	}
	//�ļ����ػ�
	if( $Filedb && $GetFile && $fid ){
		$dir_id=$file_dir?$file_dir:"article/$fid";
		if(!is_dir(PHP168_PATH."$webdb[updir]/$dir_id")){
			makepath(PHP168_PATH."$webdb[updir]/$dir_id");
		}
		if($GetFile){
			foreach($Filedb AS $key2=>$fileurl){
				$Filedb[$key2]="$dir_id/$lfjdb[uid]_".rands(6).basename($fileurl);
				if(strstr($Filedb[$key2],'?')){
					$Filedb[$key2]=str_replace("?","_____",$Filedb[$key2]);
				}
				$file_Type=strrchr($Filedb[$key2],".");
				if(strlen($file_Type)>5){
					$Filedb[$key2].=".rar";
				}
				if( $getfilecontent=sockOpenUrl($fileurl) ){
					write_file(PHP168_PATH."$webdb[updir]/{$Filedb[$key2]}",$getfilecontent);
				}else{
					copy($fileurl,PHP168_PATH."$webdb[updir]/{$Filedb[$key2]}");
				}
			}
		}
	}
	//�ɼ������ͼƬ
	$Filedb || $Filedb=array();
	foreach($Filedb AS $key2=>$fileurl){
		if(eregi(".jpg$",$fileurl)||eregi(".gif$",$fileurl)){
			//echo "<img src=".tempdir($fileurl)."><br>";
		}
		echo "<A HREF='$fileurl' target=_blank>$fileurl</A><hr>";
	}
	
	$detail_title=explode("@@",$title);
	if($detail_title[1]==''){
		$title=$detail_title[0];
	}

	$content=$show_content;
	//�����ĸ�ϵͳ����ѡ��
	if(!$system_type||!file_exists("inc/gather/system.$system_type.php")){
		$system_type="article";
	}
	//�����Ե�ʱ��.���
	if(!$testgather ){
		include("inc/gather/system.$system_type.php");
	}
	
	$page++;
	if($urldb[$page]){
		$p=$page-1;
		//ֻ��ʾ���ַ����û��鿴�ɼ����
		$testgather || $content=get_word($content,1000);
		$content=filtrate($content);
		echo "����:{$author} ��Դ:{$copyfrom}<hr>";
		echo "���ڲɼ���[$page]ҳ,�����ĵȴ�...<A HREF={$urldb[$p]} target=_blank>{$urldb[$p]}</A><hr>$content";
		echo "<META HTTP-EQUIV=REFRESH CONTENT='1;URL=?lfj=$lfj&action=$action&id=$id&system_type=$system_type&GetFile=$GetFile&file_dir=$file_dir&makesmallpic=$makesmallpic&showpic=$showpic&username=$username&fid=$fid&testgather=$testgather&page=$page'>";
		exit;
	}else{
		$num=count($urldb);
		if($testgather){
			jump("���Բɼ����,ģ���ܹ��ɼ���{$num}ƪ,��ʵû�������ݿ�","index.php?lfj=$lfj&job=list",20);
		}else{
			jump("�ɼ����,�ܹ��ɼ���{$num}ƪ","index.php?lfj=$lfj&job=list",10);
		}
	}
	
}
elseif($job=="list"&&$Apower[gather_list])
{
	if(!$page){
		$page=1;
	}
	$rows=20;
	$min=($page-1)*$rows;
	$SQL="";
	if($fid){
		$SQL=" WHERE A.fid='$fid' ";
	}
	$query = $db->query("SELECT A.*,B.name FROM {$pre}gather_rule A LEFT JOIN {$pre}gather_sort B ON A.fid=B.fid $SQL ORDER BY A.id DESC LIMIT $min,$rows");
	while($rs = $db->fetch_array($query)){
		$rs[posttime]=date("Y-m-d",$rs[posttime]);
		$listdb[]=$rs;
	}
	$showpage=getpage("{$pre}gather_rule A",$SQL,"index.php?lfj=gather&fid=$fid&job=list",$rows);
	$sort_fid=$Guidedb->Select("{$pre}gather_sort","fid",$fid,"index.php?lfj=gather&job=list");
	require(dirname(__FILE__)."/"."head.php");
	require(dirname(__FILE__)."/"."template/gather/list.htm");
	require(dirname(__FILE__)."/"."foot.php");
}
elseif($job=="addrulesql"&&$Apower[gather_list])
{
	require(dirname(__FILE__)."/"."head.php");
	require(dirname(__FILE__)."/"."template/gather/addrulesql.htm");
	require(dirname(__FILE__)."/"."foot.php");
}
elseif($action=='addrulesql'&&$Apower[gather_list])
{	
	if(strstr($sqlcode,"'")){
		$sqlcode=StripSlashes($sqlcode);
	}else{
		$sqlcode=urldecode($sqlcode);
	}
	$sqlcode=str_replace('p8_gather_rule',"{$pre}gather_rule",$sqlcode);
	$db->query($sqlcode);
	jump("����ղ�ҳ��û�б���,�ǹ�ϲ��,����ɹ�","index.php?lfj=gather&job=list",1);
}
elseif($job=='sharerulesql'&&$Apower[gather_list])
{
	$rsdb=$db->get_one("SELECT * FROM {$pre}gather_rule WHERE id='$id'");
	foreach($rsdb AS $key=>$value){
		$rsdb[$key]=mysql_escape_string($value);
	}
	extract($rsdb);
	$SQL="INSERT INTO `p8_gather_rule` (`id`, `type`, `fixsystem`, `filetype`, `webname`, `listurl`, `firstpage`, `page_begin`, `page_end`, `page_step`, `title_minleng`, `listmoreurl`, `link_include_word`, `link_noinclude_word`, `link_replace_word`, `title_replace_word`, `list_begin_code`, `list_end_code`, `list_begin_preg`, `list_end_preg`, `gatherthesame`, `show_begin_preg`, `show_end_preg`, `show_endfile_preg`, `show_begin_code`, `show_end_code`, `show_replace_word`, `show_morepage`, `show_firstpage`, `show_spe2page`, `posttime`, `list`, `copypic`, `sort`, `file_type`, `file_minleng`, `file_minsize`, `file_includeword`, `file_noincludeword`, `file_explode`, `file_picwidth`, `file_star_string`, `title_rule`, `content_rule`, `title_morepage_rull`, `content_morepage_rull`, `charset_type`) VALUES ('','$type','$fixsystem','$filetype','$webname','$listurl','$firstpage','$page_begin','$page_end','$page_step','$title_minleng','$listmoreurl','$link_include_word','$link_noinclude_word','$link_replace_word','$title_replace_word','$list_begin_code','$list_end_code','$list_begin_preg','$list_end_preg','$gatherthesame','$show_begin_preg','$show_end_preg','$show_endfile_preg','$show_begin_code','$show_end_code','$show_replace_word','$show_morepage','$show_firstpage','$show_spe2page','$posttime','$list','$copypic','$sort','$file_type','$file_minleng','$file_minsize','$file_includeword','$file_noincludeword','$file_explode','$file_picwidth','$file_star_string','$title_rule','$content_rule','$title_morepage_rull','$content_morepage_rull','$charset_type');";
	$SQL=urlencode($SQL);
	require(dirname(__FILE__)."/"."head.php");
	require(dirname(__FILE__)."/"."template/gather/sharerulesql.htm");
	require(dirname(__FILE__)."/"."foot.php");
}
elseif($action=="deleterule"&&$Apower[gather_list])
{
	$db->query("DELETE FROM {$pre}gather_rule WHERE id='$id'");
	jump("ɾ���ɹ�","index.php?lfj=gather&job=list",1);
}
elseif($job=="showfid"&&$Apower[gather_list])
{
	include_once(dirname(__FILE__)."/"."inc/gather/show_system_fid.php");
}
function SinaTitle($word,$file){
	if(strstr($file,"<h2>��������</h2>")){
		$file=strstr($file,"<h2>��������</h2>");
	}
	$word=str_replace("\/","/",$word);
	$word=str_replace('\"','"',$word);
	$file=strstr($file,$word);
	$detail=explode("<!-- loopblk end -->",$file);
	preg_match_all("/<td class=\"ConsTi\"><a href=\"([^\"]+)\" target=\"_blank\" title=\"([^\"]+)\">([^<>]+)<\/a><\/td>/is",$detail[0],$array);

	foreach( $array[3] AS $key=>$value){
		$rs[url]=$array[1][$key];
		$rs[title]=$value;
		$rs[j]=++$j;
		$listdb[]=$rs;
	}
	return $listdb;
}

function GetFileUrl($rsdb,$show_content,$curl){
	global $oldFileDB;
	$detail=explode($rsdb[file_explode],$show_content);
	foreach( $detail AS $key=>$value){
		$i++;
		if($i==1){
			continue;
		}
		//��ȡ�ļ��ĵ�ַ
		$fileurl=$oldFileurl=preg_replace("/(['\" ]*)([^'\" >]+)(.*)/is","\\2",$value);
		if(!$fileurl){
			continue;
		}
		//�ļ���ַ�Ľ�β�ַ���,ͼƬһ����jpg
		if($rsdb[file_type]){
			$CK=0;
			$detail2=explode("|",$rsdb[file_type]);
			foreach( $detail2 AS $key2=>$value2){
				if($value2 && eregi("{$value2}$",$fileurl)){
					$CK=1;
				}
			}
			if(!$CK){
				continue;
			}
		}
		//�ļ���ַ�Ŀ�ͷ�ַ���
		if($rsdb[file_star_string]){
			$CK=0;
			$detail2=explode("|",$rsdb[file_star_string]);
			foreach( $detail2 AS $key2=>$value2){
				if($value2 && eregi("^{$value2}",$fileurl)){
					$CK=1;
				}
			}
			if(!$CK){
				continue;
			}
		}
		//��ַ�б���������ַ�
		if($rsdb[file_includeword]){
			$CK=0;
			$detail2=explode("\r\n",$rsdb[file_includeword]);
			foreach( $detail2 AS $key2=>$value2){
				if( $value2&&strstr($fileurl,$value2) ){
					$CK=1;
				}
			}
			if(!$CK){
				continue;
			}
		}
		//��ַ�в��ܰ������ַ�
		if($rsdb[file_noincludeword]){
			$CK=0;
			$detail2=explode("\r\n",$rsdb[file_noincludeword]);
			foreach( $detail2 AS $key2=>$value2){
				if( $value2&&strstr($fileurl,$value2) ){
					$CK=1;
				}
			}
			if($CK){
				continue;
			}
		}
		//���ļ���ַ�����Ե�ַ����
		if(!ereg("^http://",$fileurl)){
			if(ereg("^/",$fileurl)){
				$fileurl=preg_replace("/http:\/\/([^\/]+)(.*)/is","http://\\1$fileurl",$curl);
			}else{
				$fileurl=str_replace(basename($curl),"",$curl).$fileurl;
			}	
		}
		/*�ж��ļ��Ĵ�С���ܵ��ڶ���*/
		if($rsdb[file_minsize]){
			copy($fileurl,PHP168_PATH."cache/gather_.file");
			if(filesize(PHP168_PATH."cache/gather_.file")<$rsdb[file_minsize]){
				continue;
			}
		}
		$fileDB[]=$fileurl;
		$oldFileDB[]=$oldFileurl;
	}
	return $fileDB;
}


function get_rule($string){
	$string=str_replace('\\','\\\\',$string);
	$string=str_replace("(","\(",$string);
	$string=str_replace(")","\)",$string);
	$string=str_replace("[","\[",$string);
	$string=str_replace("]","\]",$string);
	$string=str_replace('"','\"',$string);
	$string=str_replace('.','\.',$string);
	$string=str_replace('?','\?',$string);
	$string=str_replace('$','\$',$string);
	$string=str_replace('^','\^',$string);
	$string=str_replace('/','\/',$string);
	$string=str_replace('+','\+',$string);
	$string=preg_replace("/\{(.*?)\}/eis","replace_preg('\\1')",$string);
	return $string;
}

function replace_preg($string){
	$string=str_replace('\"','"',$string);
	$rule=$string;
	
	if(ereg("^NO",$rule)){
		$detail=explode("NO",$rule);
		return "([^{$detail[1]}]*)";
	}elseif($rule=='*'){
		return "(.*?)";
	}elseif($rule=='**'){
		return "(.*)";
	}else{
		$detail=explode("=",$string);
		$rule=$detail[1];
		if(ereg("^NO",$rule)){
			$detail=explode("NO",$rule);
			return "([^{$detail[1]}]*)";
		}elseif($rule=='*'){
			return "(.*?)";
		}elseif($rule=='**'){
			return "(.*)";
		}
	}
}

function clean_blank($str){
	$str=preg_replace("/([\r\n]*)/is","",$str);
	$str=preg_replace("/>([ \t]*)</is","><",$str);
	$str=preg_replace("/^([ ]*)/is","",$str);
	$str=preg_replace("/([ ]*)$/is","",$str);
	return $str;
}


function get_time($time){
	global $timestamp;
	preg_match_all("/([\d]+)/is",$time,$array);

	//ȱ������ʱ�����ֵĻ�,�ͷ��ص�ǰʱ��
	if( !$array[0][0] || !$array[0][1] || !$array[0][2] ){
		return $timestamp;
	}

	$y=intval($array[0][0]);
	$m=intval($array[0][1]);
	$d=intval($array[0][2]);
	$h=intval($array[0][3]?$array[0][3]:'00');
	$i=intval($array[0][4]?$array[0][4]:'00');
	$s=intval($array[0][5]?$array[0][5]:'00');

	$time=preg_replace("/([\d]+)-([\d]+)-([\d]+) ([\d]+):([\d]+):([\d]+)/eis","mk_time('\\4','\\5', '\\6', '\\2', '\\3', '\\1')","$y-$m-$d $h:$i:$s");

	return $time;
}

?>