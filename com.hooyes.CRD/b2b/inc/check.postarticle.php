<?php
//提交表单时候作处理
if($step=='post')
{
	//验证码校对
	if($groupdb[PostArticleYzImg]&&!$web_admin)
	{
		if(!yzimg($yzimg))
		{
			showerr("验证码不符合");
		}
		else
		{
			set_cookie("yzImgNum","0");
		}
	}
	
	if($mid&&!$article_moduleDB[$mid]){
		showerr("当前模型不存在!");
	}
	if($mid&&$mid!=$fidDB[fmid]){
		$rs = $db->get_one("SELECT * FROM {$pre}article_module WHERE id='$mid'");
		if(!$web_admin&&!in_array($groupdb[gid],explode(",",$rs[allowpost]))){
			//showerr("你无权在本模型发表内容");
		}
	}	
	
	if($job=='postnew'||$job=='edit'){
		if(!$postdb[title]){
			showerr("标题不能为空");
		}elseif(strlen($postdb[title])>120){
			showerr("标题不能大于120个字节");
		}
	}
	if(strlen($postdb[keywords])>80){
		showerr("关键字不能大于80个字节");
	}
	if(strlen($postdb[subhead])>120){
		showerr("副标题不能大于120个字节");
	}
	if(strlen($postdb[smalltitle])>80){
		showerr("短标题不能大于80个字节");
	}
	if(strlen($postdb[author])>25){
		showerr("作者不能大于25个字节");
	}
	if(strlen($postdb[copyfrom])>80){
		showerr("来源网站不能大于80个字节");
	}
	if($postdb[htmlname] && !eregi("(\.htm|\.html)$",$postdb[htmlname]) ){
		showerr("自定义文件名只能是htm或html后缀的文件");
	}
	$erp=$Fid_db[iftable][$fid];
	if($job=='postnew'&&$webdb[ForbidRepeatTitle]&&$db->get_one("SELECT * FROM {$pre}article$erp WHERE title='$postdb[title]' AND fid='$fid'")){
		showerr("系统不允许本栏目有重复的标题,请更换标题!");
	}
	//一些权限功能的设置
	article_more_set_ckecked($job);

	//过滤一些用害的代码
	$postdb[title]		=	filtrate($postdb[title]);
	$postdb[subhead]	=	filtrate($postdb[subhead]);
	$postdb[keywords]	=	filtrate($postdb[keywords]);
	$postdb[smalltitle]	=	filtrate($postdb[smalltitle]);
	$postdb[picurl]		=	filtrate($postdb[picurl]);
	$postdb[description]=	filtrate($postdb[description]);
	$postdb[author]		=	filtrate($postdb[author]);
	$postdb[copyfrom]	=	filtrate($postdb[copyfrom]);
	$postdb[copyfromurl]=	filtrate($postdb[copyfromurl]);
	
	//针对火狐浏览器做的处理
	$postdb[content]=str_replace("=\\\"../$webdb[updir]/","=\\\"$webdb[www_url]/$webdb[updir]/",$postdb[content]);

	if(!$groupdb[PostNoDelCode]){
		$postdb[content]	=	preg_replace('/javascript/i','java script',$postdb[content]);
		$postdb[content]	=	preg_replace('/<iframe ([^<>]+)>/i','&lt;iframe \\1>',$postdb[content]);
	}

	//对自定义模块表单数据进行判断
	if($mid)
	{
		query_article_module($mid,'',$post_db,'');
	}

	//采集外部图片
	$postdb[content]	=	get_outpic($postdb[content],$fid,$GetOutPic);

	//去除超级链接
	$DelLink && $postdb[content] = preg_replace("/<a([^<>]*) href=\\\\\"([^\"]+)\\\\\"/is","<a",$postdb[content]);

	//附件目录转移
	$downloadDIR="article/$fid";
	if($webdb[ArticleDownloadDirTime]){
		$downloadDIR.="/".date($webdb[ArticleDownloadDirTime],$timestamp);
	}
	$postdb[content]=move_attachment($lfjuid,$postdb[content],$downloadDIR,'PostArticle');

	//对于太大的图片要做处理自动缩放比例
	$postdb[content]=str_replace("<img ","<img onload=\'if(this.width>600)makesmallpic(this,600,1800);\' ",$postdb[content]);
	
	//获取附件
	$file_db=get_content_attachment($postdb[content]);
	
	//不存在本地图片时,就获取远程的图片做为缩略图
	if(!$file_db){
		preg_match_all("/http:\/\/([^ '\"<>]+)\.(gif|jpg|png)/is",$postdb[content],$array);
		$file_db=$array[0];
	}

	//当不存在缩略图时,获取图片,如果系统设置允许自动,才做处理
	if($webdb[autoGetSmallPic]&&!$postdb[picurl]&&($job=="postnew"||$job=="edit"))
	{
		//发表图片,如果没有缩略图,就获取第一张
		if($file_db){
			foreach( $file_db AS $key=>$value){
				if((eregi("jpg$",$value)||eregi("gif$",$value)||eregi("png$",$value))&&!eregi("ewebeditor\/",$value)){
					$postdb[picurl]=$value;
					break;
				}
			}
		}
	}

	//图片频道转移图片目录
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
			//没有判断是否转移目录成功
			$post_db[photourl][url][$key]="photo/$fid/".basename($value);
		}
	//下载频道软件转移
	}elseif($mid==101){
		foreach($post_db[softurl][url] AS $key=>$value){
			if(!$value||eregi("://",$value)){
				continue;
			}
			move_attachment($lfjuid,tempdir($value),"download/$fid");
			//没有判断是否转移目录成功
			$post_db[softurl][url][$key]="download/$fid/".basename($value);
		}
	//视频频道视频转移
	}elseif($mid==102){
		foreach($post_db[mvurl][url] AS $key=>$value){
			if(!$value||eregi("://",$value)){
				continue;
			}
			move_attachment($lfjuid,tempdir($value),"mv/$fid");
			//没有判断是否转移目录成功
			$post_db[mvurl][url][$key]="mv/$fid/".basename($value);
		}
	}

	/*缩略图处理*/
	if( $postdb[picurl] && $postdb[picurl]!=$rsdb[picurl] )
	{
		//图片目录转移
		move_attachment($lfjuid,tempdir($postdb[picurl]),"article/$fid",'small');
		if(file_exists(PHP168_PATH."$webdb[updir]/article/$fid/".basename($postdb[picurl]))){
			$postdb[picurl]="article/$fid/".basename($postdb[picurl]);
		}

		if(file_exists(PHP168_PATH."$webdb[updir]/$postdb[picurl]")&&$postdb[automakesmall]&&$webdb[if_gdimg])
		{
			//如果是从文章内容提取的图片,需要重命为另一张,否则影响到原来的
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

				//FTP上传文件到远程服务器
				if($webdb[ArticleDownloadUseFtp]){
					ftp_upfile($Newpicpath,$postdb[picurl]);
				}
			}
		}
	}
	
	//FTP上传文件到远程服务器
	if($webdb[ArticleDownloadUseFtp]&&$file_db){
		foreach($file_db AS $key=>$value){
			if(is_file(PHP168_PATH."$webdb[updir]/$value")){
				ftp_upfile(PHP168_PATH."$webdb[updir]/$value",$value);
			}			
		}
	}

	//如果系统设置自动提取关键字的话,只有当用户没设置关键字,才自动提取.
	if($job=='postnew'&&$webdb[autoGetKeyword]&&!$postdb[keywords]){
		$postdb[keywords] = keyword_ck($postdb[title]);
		
	}

	//添加作者来源
	if($postdb[copyfrom] && $postdb[addcopyfrom] && $web_admin)
	{
		if(!$db->get_one("SELECT * FROM {$pre}copyfrom WHERE name='$postdb[copyfrom]' ") ){
			$db->query("INSERT INTO `{$pre}copyfrom` (`name` , `list`,uid ) VALUES ('$postdb[copyfrom]', '$timestamp','$lfjdb[uid]')");
		}
	}

	

	//过滤不健康的字
	$postdb[content]	=	replace_bad_word($postdb[content]);
	$postdb[title]		=	replace_bad_word($postdb[title]);
	$postdb[author]		=	replace_bad_word($postdb[author]);
	$postdb[keywords]	=	replace_bad_word($postdb[keywords]);
	$postdb[copyfrom]	=	replace_bad_word($postdb[copyfrom]);
	$postdb[description]=	replace_bad_word($postdb[description]);

	$postdb[picurl]		&&	$postdb[ispic]=1;

	//对附件地址做处理,防止更换域名后,无法访问
	$postdb[content]	=	En_TruePath($postdb[content]);
}
//修改与发表,未提交前
else
{
	//如果系统与栏目禁用评论的话,则文章强制禁用评论
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

	//让用户选择发在哪个自定义表单那里
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

	//地址还原
	$rsdb[content]=En_TruePath($rsdb[content],0);
	$rsdb[content]=str_replace(array("'","<",">"),array("&#39;","&lt;","&gt;"),$rsdb[content]);
	
	//修改文章时,需要读取自定义模块的数据
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

	//页面显示设置
	if(!$web_admin&&!$groupdb[SetArticleTpl])
	{
		$readonly=' readonly ';
	}

	//投票项
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
		showerr("当前模型不存在!");
	}
}
?>