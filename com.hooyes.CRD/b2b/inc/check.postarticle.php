<?php
//�ύ��ʱ��������
if($step=='post')
{
	//��֤��У��
	if($groupdb[PostArticleYzImg]&&!$web_admin)
	{
		if(!yzimg($yzimg))
		{
			showerr("��֤�벻����");
		}
		else
		{
			set_cookie("yzImgNum","0");
		}
	}
	
	if($mid&&!$article_moduleDB[$mid]){
		showerr("��ǰģ�Ͳ�����!");
	}
	if($mid&&$mid!=$fidDB[fmid]){
		$rs = $db->get_one("SELECT * FROM {$pre}article_module WHERE id='$mid'");
		if(!$web_admin&&!in_array($groupdb[gid],explode(",",$rs[allowpost]))){
			//showerr("����Ȩ�ڱ�ģ�ͷ�������");
		}
	}	
	
	if($job=='postnew'||$job=='edit'){
		if(!$postdb[title]){
			showerr("���ⲻ��Ϊ��");
		}elseif(strlen($postdb[title])>120){
			showerr("���ⲻ�ܴ���120���ֽ�");
		}
	}
	if(strlen($postdb[keywords])>80){
		showerr("�ؼ��ֲ��ܴ���80���ֽ�");
	}
	if(strlen($postdb[subhead])>120){
		showerr("�����ⲻ�ܴ���120���ֽ�");
	}
	if(strlen($postdb[smalltitle])>80){
		showerr("�̱��ⲻ�ܴ���80���ֽ�");
	}
	if(strlen($postdb[author])>25){
		showerr("���߲��ܴ���25���ֽ�");
	}
	if(strlen($postdb[copyfrom])>80){
		showerr("��Դ��վ���ܴ���80���ֽ�");
	}
	if($postdb[htmlname] && !eregi("(\.htm|\.html)$",$postdb[htmlname]) ){
		showerr("�Զ����ļ���ֻ����htm��html��׺���ļ�");
	}
	$erp=$Fid_db[iftable][$fid];
	if($job=='postnew'&&$webdb[ForbidRepeatTitle]&&$db->get_one("SELECT * FROM {$pre}article$erp WHERE title='$postdb[title]' AND fid='$fid'")){
		showerr("ϵͳ��������Ŀ���ظ��ı���,���������!");
	}
	//һЩȨ�޹��ܵ�����
	article_more_set_ckecked($job);

	//����һЩ�ú��Ĵ���
	$postdb[title]		=	filtrate($postdb[title]);
	$postdb[subhead]	=	filtrate($postdb[subhead]);
	$postdb[keywords]	=	filtrate($postdb[keywords]);
	$postdb[smalltitle]	=	filtrate($postdb[smalltitle]);
	$postdb[picurl]		=	filtrate($postdb[picurl]);
	$postdb[description]=	filtrate($postdb[description]);
	$postdb[author]		=	filtrate($postdb[author]);
	$postdb[copyfrom]	=	filtrate($postdb[copyfrom]);
	$postdb[copyfromurl]=	filtrate($postdb[copyfromurl]);
	
	//��Ի����������Ĵ���
	$postdb[content]=str_replace("=\\\"../$webdb[updir]/","=\\\"$webdb[www_url]/$webdb[updir]/",$postdb[content]);

	if(!$groupdb[PostNoDelCode]){
		$postdb[content]	=	preg_replace('/javascript/i','java script',$postdb[content]);
		$postdb[content]	=	preg_replace('/<iframe ([^<>]+)>/i','&lt;iframe \\1>',$postdb[content]);
	}

	//���Զ���ģ������ݽ����ж�
	if($mid)
	{
		query_article_module($mid,'',$post_db,'');
	}

	//�ɼ��ⲿͼƬ
	$postdb[content]	=	get_outpic($postdb[content],$fid,$GetOutPic);

	//ȥ����������
	$DelLink && $postdb[content] = preg_replace("/<a([^<>]*) href=\\\\\"([^\"]+)\\\\\"/is","<a",$postdb[content]);

	//����Ŀ¼ת��
	$downloadDIR="article/$fid";
	if($webdb[ArticleDownloadDirTime]){
		$downloadDIR.="/".date($webdb[ArticleDownloadDirTime],$timestamp);
	}
	$postdb[content]=move_attachment($lfjuid,$postdb[content],$downloadDIR,'PostArticle');

	//����̫���ͼƬҪ�������Զ����ű���
	$postdb[content]=str_replace("<img ","<img onload=\'if(this.width>600)makesmallpic(this,600,1800);\' ",$postdb[content]);
	
	//��ȡ����
	$file_db=get_content_attachment($postdb[content]);
	
	//�����ڱ���ͼƬʱ,�ͻ�ȡԶ�̵�ͼƬ��Ϊ����ͼ
	if(!$file_db){
		preg_match_all("/http:\/\/([^ '\"<>]+)\.(gif|jpg|png)/is",$postdb[content],$array);
		$file_db=$array[0];
	}

	//������������ͼʱ,��ȡͼƬ,���ϵͳ���������Զ�,��������
	if($webdb[autoGetSmallPic]&&!$postdb[picurl]&&($job=="postnew"||$job=="edit"))
	{
		//����ͼƬ,���û������ͼ,�ͻ�ȡ��һ��
		if($file_db){
			foreach( $file_db AS $key=>$value){
				if((eregi("jpg$",$value)||eregi("gif$",$value)||eregi("png$",$value))&&!eregi("ewebeditor\/",$value)){
					$postdb[picurl]=$value;
					break;
				}
			}
		}
	}

	//ͼƬƵ��ת��ͼƬĿ¼
	if($mid==100){
		foreach($post_db[photourl][url] AS $key=>$value){
			if(!$value||eregi("://",$value)){
				continue;
			}
			if(!$postdb[picurl]){
				copy(PHP168_PATH."$webdb[updir]/$value",PHP168_PATH."$webdb[updir]/{$value}.jpg");
				$postdb[picurl]="{$value}.jpg";
			}
			move_attachment($lfjuid,tempdir($value),"photo/$fid");
			//û���ж��Ƿ�ת��Ŀ¼�ɹ�
			$post_db[photourl][url][$key]="photo/$fid/".basename($value);
		}
	//����Ƶ�����ת��
	}elseif($mid==101){
		foreach($post_db[softurl][url] AS $key=>$value){
			if(!$value||eregi("://",$value)){
				continue;
			}
			move_attachment($lfjuid,tempdir($value),"download/$fid");
			//û���ж��Ƿ�ת��Ŀ¼�ɹ�
			$post_db[softurl][url][$key]="download/$fid/".basename($value);
		}
	//��ƵƵ����Ƶת��
	}elseif($mid==102){
		foreach($post_db[mvurl][url] AS $key=>$value){
			if(!$value||eregi("://",$value)){
				continue;
			}
			move_attachment($lfjuid,tempdir($value),"mv/$fid");
			//û���ж��Ƿ�ת��Ŀ¼�ɹ�
			$post_db[mvurl][url][$key]="mv/$fid/".basename($value);
		}
	}

	/*����ͼ����*/
	if( $postdb[picurl] && $postdb[picurl]!=$rsdb[picurl] )
	{
		//ͼƬĿ¼ת��
		move_attachment($lfjuid,tempdir($postdb[picurl]),"article/$fid",'small');
		if(file_exists(PHP168_PATH."$webdb[updir]/article/$fid/".basename($postdb[picurl]))){
			$postdb[picurl]="article/$fid/".basename($postdb[picurl]);
		}

		if(file_exists(PHP168_PATH."$webdb[updir]/$postdb[picurl]")&&$postdb[automakesmall]&&$webdb[if_gdimg])
		{
			//����Ǵ�����������ȡ��ͼƬ,��Ҫ����Ϊ��һ��,����Ӱ�쵽ԭ����
			if(strstr($postdb[content],$postdb[picurl]))
			{
				$smallpic=str_replace(".","_",$postdb[picurl]).".gif";
			}
			else
			{
				$smallpic="$postdb[picurl]";
			}
			$Newpicpath=PHP168_PATH."$webdb[updir]/$smallpic";

			$picWidth>500 && $picWidth=300;
			$picWidth<50 && $picWidth=300;

			$picHeight>500 && $picHeight=225;
			$picHeight<50 && $picHeight=225;
			gdpic(PHP168_PATH."$webdb[updir]/$postdb[picurl]",$Newpicpath,$picWidth?$picWidth:300,$picHeight?$picHeight:225,$webdb[autoCutSmallPic]?array('fix'=>1):'');
			if( file_exists($Newpicpath) )
			{
				$postdb[picurl]=$smallpic;

				//FTP�ϴ��ļ���Զ�̷�����
				if($webdb[ArticleDownloadUseFtp]){
					ftp_upfile($Newpicpath,$postdb[picurl]);
				}
			}
		}
	}
	
	//FTP�ϴ��ļ���Զ�̷�����
	if($webdb[ArticleDownloadUseFtp]&&$file_db){
		foreach($file_db AS $key=>$value){
			if(is_file(PHP168_PATH."$webdb[updir]/$value")){
				ftp_upfile(PHP168_PATH."$webdb[updir]/$value",$value);
			}			
		}
	}

	//���ϵͳ�����Զ���ȡ�ؼ��ֵĻ�,ֻ�е��û�û���ùؼ���,���Զ���ȡ.
	if($job=='postnew'&&$webdb[autoGetKeyword]&&!$postdb[keywords]){
		$postdb[keywords] = keyword_ck($postdb[title]);
		
	}

	//���������Դ
	if($postdb[copyfrom] && $postdb[addcopyfrom] && $web_admin)
	{
		if(!$db->get_one("SELECT * FROM {$pre}copyfrom WHERE name='$postdb[copyfrom]' ") ){
			$db->query("INSERT INTO `{$pre}copyfrom` (`name` , `list`,uid ) VALUES ('$postdb[copyfrom]', '$timestamp','$lfjdb[uid]')");
		}
	}

	

	//���˲���������
	$postdb[content]	=	replace_bad_word($postdb[content]);
	$postdb[title]		=	replace_bad_word($postdb[title]);
	$postdb[author]		=	replace_bad_word($postdb[author]);
	$postdb[keywords]	=	replace_bad_word($postdb[keywords]);
	$postdb[copyfrom]	=	replace_bad_word($postdb[copyfrom]);
	$postdb[description]=	replace_bad_word($postdb[description]);

	$postdb[picurl]		&&	$postdb[ispic]=1;

	//�Ը�����ַ������,��ֹ����������,�޷�����
	$postdb[content]	=	En_TruePath($postdb[content]);
}
//�޸��뷢��,δ�ύǰ
else
{
	//���ϵͳ����Ŀ�������۵Ļ�,������ǿ�ƽ�������
	$forbidcomment=" ";
	if($job=='postnew'){
		if(!$webdb[showComment]||($fidDB&&!$fidDB[allowcomment])){
			$forbidcomment=" checked ";
		}
	}elseif($rsdb[forbidcomment]){
		$forbidcomment=" checked ";
	}
	
	$fonttype=$rsdb[fonttype]==1?" checked ":"";
	if($job=='edit'){
		$yz=$rsdb[yz]==1?" checked ":"";
	}else{
		$yz=" checked ";
	}
	
	if($rsdb["list"]>$timestamp)
	{
		$top=" checked ";
	}
	if($rsdb["levels"])
	{
		$levels=" checked ";
	}
	if($rsdb["target"])
	{
		$target=" checked ";
	}

	$style_select=select_style('postdb[style]',$rsdb[style]);
	
	unset($keywords,$copyfroms,$moduledb,$specials,$baseSpecial);
	
	$query = $db->query("SELECT * FROM {$pre}special ORDER BY list DESC LIMIT 500");
	while($rs = $db->fetch_array($query)){
		if($rs[yz]!=1&&$rs[uid]!=$lfjuid){
			continue;
		}
		if($rs[allowpost]&&!$web_admin){
			if( !in_array($groupdb['gid'],explode(",",$rs[allowpost])) ){
				if(!$lfjuid||$rs[uid]!=$lfjuid ){
					continue;
				}				
			}
		}
		$checked='';
		if($aid&&in_array($aid,explode(",",$rs[aids])))
		{
			$checked=' checked ';
		}
		if($rs[ifbase]){
			$baseSpecial.="<input type='checkbox' name='postdb[special][]' value='$rs[id]' $checked>$rs[title] ";
		}else{
			$specials.="<input type='checkbox' name='postdb[special][]' value='$rs[id]' $checked>$rs[title]<br>";
		}		
	}

	$query=$db->query("SELECT * FROM {$pre}keyword ORDER BY num DESC LIMIT 30");
	while($rs=$db->fetch_array($query)){
		$keywords.="<option value='$rs[keywords]' >$rs[keywords]</option>";
	}
	$query=$db->query("SELECT * FROM {$pre}copyfrom ORDER BY list DESC ");
	while($rs=$db->fetch_array($query)){
		$copyfroms.="<option value='$rs[name]'>$rs[name]</option>";
	}
	
	if($mid===''){
		$mid=$fidDB[fmid];
	}

	//���û�ѡ�����ĸ��Զ��������
	$query = $db->query("SELECT * FROM {$pre}article_module  WHERE ifclose=0 ORDER BY list DESC");
	while($rs = $db->fetch_array($query)){
		//if(!$web_admin&&!in_array($groupdb[gid],explode(",",$rs[allowpost]))){
		//	if($rs[id]==$mid&&$mid!=$fidDB[fmid]){
		//		$mid=0;
		//	}
		//	continue;
		//}
		$moduledb[]=$rs;
	}

	$mid=intval($mid);
	$moduledb_color[$mid]='red';

	$group_allowdown=group_box("postdb[allowdown]",explode(",",$rsdb[allowdown]));
	$group_allowview=group_box("postdb[allowview]",explode(",",$rsdb[allowview]));

	$tpl_list=@unserialize($fidDB[template]);
	$tpl_show=@unserialize($rsdb[template]);


	$value_tpl_head=$tpl_show[head]?$tpl_show[head]:$tpl_list[head];
	$value_tpl_foot=$tpl_show[foot]?$tpl_show[foot]:$tpl_list[foot];
	$value_tpl_show=$tpl_show[bencandy]?$tpl_show[bencandy]:$tpl_list[bencandy];
	$tpl_head=select_template("",7,$value_tpl_head);
	$tpl_head=str_replace("<select","<select onChange='get_obj(\"head_tpl\").value=this.options[this.selectedIndex].value;'",$tpl_head);
	$tpl_foot=select_template("",8,$value_tpl_foot);
	$tpl_foot=str_replace("<select","<select onChange='get_obj(\"foot_tpl\").value=this.options[this.selectedIndex].value;'",$tpl_foot);
	$tpl_show=select_template("",3,$value_tpl_show);
	$tpl_show=str_replace("<select","<select onChange='get_obj(\"main_tpl\").value=this.options[this.selectedIndex].value;'",$tpl_show);

	$rsdb[posttime]		&&	$rsdb[posttime]=date("Y-m-d H:i:s",$rsdb[posttime]);
	$rsdb[begintime]	&&	$rsdb[begintime]=date("Y-m-d H:i:s",$rsdb[begintime]);
	$rsdb[endtime]		&&	$rsdb[endtime]=date("Y-m-d H:i:s",$rsdb[endtime]);

	//��ַ��ԭ
	$rsdb[content]=En_TruePath($rsdb[content],0);
	$rsdb[content]=str_replace(array("'","<",">"),array("&#39;","&lt;","&gt;"),$rsdb[content]);
	
	//�޸�����ʱ,��Ҫ��ȡ�Զ���ģ�������
	if($mid&&$job!='postnew'&&$job!='post_more')
	{
		$_rsdb=$db->get_one("SELECT * FROM `{$pre}article_content_$mid` WHERE rid='$rsdb[rid]'");
		if($_rsdb){
			$rsdb+=$_rsdb;
		}
		$i_id=$_rsdb[id];
		set_module_table_value($mid,1);
	}
	elseif($mid&&$job=='postnew')
	{
		set_module_table_value($mid,0);
	}

	//ҳ����ʾ����
	if(!$web_admin&&!$groupdb[SetArticleTpl])
	{
		$readonly=' readonly ';
	}

	//ͶƱ��
	if($job=='postnew'){
		$votedb[_type][1]=$votedb[_limitip][0]=$votedb[_forbidguestvote][0]=$votedb[_votetype][0]=' checked ';
		$listdb=array('1'=>'','2'=>'','3'=>'');
	}elseif($job=='edit'&&$rsdb[ifvote]){
		$votedb=$db->get_one("SELECT * FROM `{$pre}vote_config` WHERE aid='$aid'");
		$query = $db->query("SELECT * FROM `{$pre}vote` WHERE cid='$votedb[cid]' ORDER BY list DESC");
		$i=0;
		while($rs = $db->fetch_array($query)){
			$i++;
			$votelistdb[$i]=$rs;
		}
		$votedb[_type][$votedb[type]]=" checked ";
		$votedb[_limitip][$votedb[limitip]]=" checked ";
		$votedb[_forbidguestvote][$votedb[forbidguestvote]]=" checked ";
		$votedb[_votetype][$votedb[votetype]]=' checked ';

		$votedb[begintime]	=	$votedb[begintime]?date("Y-m-d H:i:s",$votedb[begintime]):'';
		$votedb[endtime]	=	$votedb[endtime]?date("Y-m-d H:i:s",$votedb[endtime]):'';
	}

	if($aid){
		$query = $db->query("SELECT * FROM {$pre}fu_article WHERE aid='$aid'");
		while($rs = $db->fetch_array($query)){
			$fu_fiddb[]=$rs[fid];
		}
	}
	$fu_sort=$Guidedb->Checkbox("{$pre}fu_sort",'fu_fiddb[]',$fu_fiddb);
	if($mid&&!$article_moduleDB[$mid]){
		showerr("��ǰģ�Ͳ�����!");
	}
}
?>