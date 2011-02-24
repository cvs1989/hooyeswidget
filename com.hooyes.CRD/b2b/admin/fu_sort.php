<?php
!function_exists('html') && exit('ERR');

if( !is_table("{$pre}fu_article") ){
	$db->query("CREATE TABLE `{$pre}fu_article` (
  `fid` int(7) NOT NULL default '0',
  `aid` int(10) NOT NULL default '0',
  KEY `fid` (`fid`),
  KEY `aid` (`aid`)
  ) TYPE=MyISAM");
}
if( !is_table("{$pre}fu_sort") ){
	$db->query("CREATE TABLE `{$pre}fu_sort` (
  `fid` mediumint(7) unsigned NOT NULL auto_increment,
  `fup` mediumint(7) unsigned NOT NULL default '0',
  `fmid` mediumint(5) NOT NULL default '0',
  `name` varchar(50) NOT NULL default '',
  `class` smallint(4) NOT NULL default '0',
  `sons` smallint(4) NOT NULL default '0',
  `type` tinyint(1) NOT NULL default '0',
  `admin` varchar(100) NOT NULL default '',
  `list` int(10) NOT NULL default '0',
  `listorder` tinyint(2) NOT NULL default '0',
  `passwd` varchar(32) NOT NULL default '',
  `logo` varchar(150) NOT NULL default '',
  `descrip` text NOT NULL,
  `style` varchar(50) NOT NULL default '',
  `template` text NOT NULL,
  `jumpurl` varchar(150) NOT NULL default '',
  `maxperpage` tinyint(3) NOT NULL default '0',
  `metakeywords` varchar(255) NOT NULL default '',
  `metadescription` varchar(255) NOT NULL default '',
  `allowcomment` tinyint(1) NOT NULL default '0',
  `allowpost` varchar(150) NOT NULL default '',
  `allowviewtitle` varchar(150) NOT NULL default '',
  `allowviewcontent` varchar(150) NOT NULL default '',
  `allowdownload` varchar(150) NOT NULL default '',
  `forbidshow` tinyint(1) NOT NULL default '0',
  `config` text NOT NULL,
  `list_html` varchar(255) NOT NULL default '',
  `bencandy_html` varchar(255) NOT NULL default '',
  `domain` varchar(150) NOT NULL default '',
  `domain_dir` varchar(100) NOT NULL default '',
  PRIMARY KEY  (`fid`),
  KEY `fup` (`fup`),
  KEY `fmid` (`fmid`)
  ) TYPE=MyISAM AUTO_INCREMENT=1");
}
//取消固定分类固定模型功能
if($webdb[SortUseOtherModule]){
	unset($only);
}

$Guidedb->only=$only;
$Guidedb->mid=$mid;

if($job=="addsort"&&$Apower[fu_sort_power])
{
	if($fup){
		$rsdb=$db->get_one(" SELECT * FROM {$pre}fu_sort WHERE fid='$fup' ");
		$typedb[0]=' checked ';
	}else{
		$typedb[1]=' checked ';
	}
	
	$sort_fup=$Guidedb->Select("{$pre}fu_sort","fup",$fup);
	
	if($only){
		$readonly2=' onbeforeactivate="return false" onfocus="this.blur()" onmouseover="this.setCapture()" onmouseout="this.releaseCapture()" ';
	}
	/*
	$module_id="<select name='postdb[fmid]' $readonly2><option value='0'>文章模型</option>";
	$query = $db->query("SELECT * FROM {$pre}article_module  WHERE ifclose=0 ORDER BY list DESC");
	while($rs = $db->fetch_array($query)){
		$ckk=$mid==$rs[id]?' selected ':'';
		$module_id.="<option value='$rs[id]' $ckk>$rs[name]</option>";
	}
	$module_id.=" </select>";*/

	

	require(dirname(__FILE__)."/"."head.php");
	require(dirname(__FILE__)."/"."template/fu_sort/menu.htm");
	require(dirname(__FILE__)."/"."template/fu_sort/addsort.htm");
	require(dirname(__FILE__)."/"."foot.php");
}
elseif($job=="listsort"&&$Apower[fu_sort_power])
{
	if($only&&$mid===''){
		$listdb[]=array('id'=>0,'name'=>'文章模型');
		$query = $db->query("SELECT * FROM {$pre}article_module ORDER BY list DESC");
		while($rs = $db->fetch_array($query)){
			$listdb[]=$rs;
		}
		foreach( $listdb AS $key=>$rs){
			@extract($db->get_one("SELECT COUNT(*) AS NUM FROM {$pre}fu_sort WHERE fmid='$rs[id]'"));
			$rs[NUM]=intval($NUM);
			$listdb[$key]=$rs;
		}
		
 
		require(dirname(__FILE__)."/"."head.php");
		require(dirname(__FILE__)."/"."template/fu_sort/choose_sort.htm");
		require(dirname(__FILE__)."/"."foot.php");
		exit;
	}
	$fid=intval($fid);
	
	$sortdb=array();
	if( count($Fid_db[name])>100||$fid ){
		$rows=50;
		$page<1 && $page=1;
		$min=($page-1)*$rows;
		$showpage=getpage("{$pre}fu_sort","WHERE fup='$fid'","index.php?lfj=$lfj&job=$job&only=$only&mid=$mid&fid=$fid",$rows);
		$query = $db->query("SELECT * FROM {$pre}fu_sort WHERE fup='$fid' ORDER BY list DESC,fid ASC LIMIT $min,$rows");
		while($rs = $db->fetch_array($query)){
			if(!$rs[type]){
				$erp=$Fid_db[iftable][$rs[fid]];
				@extract($db->get_one("SELECT COUNT(*) AS NUM FROM {$pre}article$erp WHERE fid='$rs[fid]'"));
				$rs[NUM]=intval($NUM);
			}
			$sortdb[]=$rs;
		}
		if($fid){
			$show_guide="<A HREF='index.php?lfj=$lfj&job=$job&only=$only&mid=$mid'>返回顶级目录</A> ".list_sort_guide($fid);
		}
	}else{		
		list_allsort2($fid,'fu_sort',1);
	}


	if($fid){
		$rsdb=$db->get_one(" SELECT * FROM {$pre}fu_sort WHERE fid='$fid' ");
	}
	$sort_fup=$Guidedb->Select("{$pre}fu_sort","fup",$fid);
	$article_show_step[$webdb[article_show_step]]='red;';
	require(dirname(__FILE__)."/"."head.php");
	require(dirname(__FILE__)."/"."template/fu_sort/menu.htm");
	require(dirname(__FILE__)."/"."template/fu_sort/sort.htm");
	require(dirname(__FILE__)."/"."foot.php");
}
elseif($job=='showsort'&&$Apower[fu_sort_power])
{
	$webdbs[article_show_step]=$step;
	write_config_cache($webdbs);
	jump("修改成功",$FROMURL,0);
}
elseif($action=="addsort"&&$Apower[fu_sort_power])
{
	if(!$name){
		showerr("名称不能为空");
	}
	if($fup){
		$rs=$db->get_one("SELECT * FROM {$pre}fu_sort WHERE fid='$fup' ");
		if($rs[type]!=1){
			showerr("只有大分类下,才可创建");
		}
		$class=$rs['class'];
		$db->query("UPDATE {$pre}fu_sort SET sons=sons+1 WHERE fid='$fup'");
		//$type=0;
	}else{
		//$type=1;	/*分类标志*/
		$class=0;
	}
	$class++;
	$detail=explode("\r\n",$name);
	foreach( $detail AS $key=>$name){
		if(!$name){
			continue;
		}
		$name=filtrate($name);
		$db->query("INSERT INTO `{$pre}fu_sort` (name,fup,class,type,allowcomment,fmid) VALUES ('$name','$fup','$class','$postdb[type]',1,'$postdb[fmid]') ");
	}
	@extract($db->get_one("SELECT fid FROM {$pre}fu_sort ORDER BY fid DESC LIMIT 0,1"));
	
	mod_sort_class("{$pre}fu_sort",0,0);		//更新class
	mod_sort_sons("{$pre}fu_sort",0);			//更新sons
	/*更新导航缓存*/
	cache_guide();
	jump("创建成功","index.php?lfj=$lfj&job=editsort&fid=$fid&only=$only&mid=$mid");
}

//修改栏目信息
elseif($job=="editsort"&&$Apower[fu_sort_power])
{
	$postdb[fid] && $fid=$postdb[fid];
	$rsdb=$db->get_one("SELECT * FROM {$pre}fu_sort WHERE fid='$fid'");
	$rsdb[config]=unserialize($rsdb[config]);
	//$sort_fid=$Guidedb->Select("{$pre}fu_sort","postdb[fid]",$fid,"index.php?lfj=$lfj&job=$job");
	$Guidedb->getfup=1;
	$sort_fup=$Guidedb->Select("{$pre}fu_sort","postdb[fup]",$rsdb[fup]);

	$style_select=select_style('postdb[style]',$rsdb[style]);
	$group_post=group_box("postdb[allowpost]",explode(",",$rsdb[allowpost]));
	$group_viewtitle=group_box("postdb[allowviewtitle]",explode(",",$rsdb[allowviewtitle]));
	$group_viewcontent=group_box("postdb[allowviewcontent]",explode(",",$rsdb[allowviewcontent]));
	$group_download=group_box("postdb[allowdownload]",explode(",",$rsdb[allowdownload]));
	$typedb[$rsdb[type]]=" checked ";

	$forbidshow[intval($rsdb[forbidshow])]=" checked ";
	$allowcomment[intval($rsdb[allowcomment])]=" checked ";

	$tpl=unserialize($rsdb[template]);
	$tpl_head=select_template("",7,$tpl[head]);
	$tpl_head=str_replace("<select","<select onChange='get_obj(\"tpl_head\").value=this.options[this.selectedIndex].value;'",$tpl_head);

	$tpl_foot=select_template("",8,$tpl[foot]);
	$tpl_foot=str_replace("<select","<select onChange='get_obj(\"tpl_foot\").value=this.options[this.selectedIndex].value;'",$tpl_foot);

	$tpl_type=$rsdb[type]==2?9:2;
	$tpl_list=select_template("",$tpl_type,$tpl['list']);
	$tpl_list=str_replace("<select","<select onChange='get_obj(\"tpl_list\").value=this.options[this.selectedIndex].value;'",$tpl_list);

	$tpl_bencandy=select_template("",3,$tpl[bencandy]);
	$tpl_bencandy=str_replace("<select","<select onChange='get_obj(\"tpl_bencandy\").value=this.options[this.selectedIndex].value;'",$tpl_bencandy);

	$listorder[$rsdb[listorder]]=" selected ";


	$sonListorder[$rsdb[config][sonListorder]]=" selected ";
	$ListShowType[$rsdb[config][ListShowType]]=" selected ";
	$ListShowBigType[$rsdb[config][ListShowBigType]]=" selected ";



	
	$rsdb[descrip]=En_TruePath($rsdb[descrip],0);

	require_once(PHP168_PATH."inc/pinyin.php");
	$htmldirname=change2pinyin($rsdb[name],1);
	
	if($only){
		$readonly2=' onbeforeactivate="return false" onfocus="this.blur()" onmouseover="this.setCapture()" onmouseout="this.releaseCapture()" ';
	}
	$module_id="<select name='postdb[fmid]' $readonly2><option value='0'>文章模型</option>";
	$query = $db->query("SELECT * FROM {$pre}article_module  WHERE ifclose=0 ORDER BY list DESC");
	while($rs = $db->fetch_array($query)){
		$ckk=$rsdb[fmid]==$rs[id]?' selected ':'';
		$module_id.="<option value='$rs[id]' $ckk>$rs[name]</option>";
	}
	$module_id.=" </select>";

	if($rsdb[type]==1){
		$getLabelTpl=getLabelTpl('template/default/fu_bigsort_tpl');
	}elseif($rsdb[type]==0){
		$getLabelTpl=getLabelTpl('template/default/list_tpl');
	}
	

	require(dirname(__FILE__)."/"."head.php");
	require(dirname(__FILE__)."/"."template/fu_sort/menu.htm");
	if($rsdb[type]==2){
		$rsdb[descrip]=str_replace("'","&#39;",$rsdb[descrip]);
		
		$tpl['list'] || $tpl['list']="template/default/alonepage.htm";
		require(dirname(__FILE__)."/"."template/fu_sort/editsort2.htm");
	}else{
		$rsdb[descrip]=str_replace("<","&lt;",$rsdb[descrip]);
		$rsdb[descrip]=str_replace(">","&gt;",$rsdb[descrip]);
		require(dirname(__FILE__)."/"."template/fu_sort/editsort.htm");
	}
	require(dirname(__FILE__)."/"."foot.php");
}
elseif($action=="editsort"&&$Apower[fu_sort_power])
{
	if($postdb[type]!=2&&$postdb[tpl]['list']=='template/default/alonepage.htm'){
		$postdb[tpl]['list']='';
	}
	//检查父栏目是否有问题
	check_fup("{$pre}fu_sort",$postdb[fid],$postdb[fup]);
	$postdb[allowpost]=@implode(",",$postdb[allowpost]);
	$postdb[allowviewtitle]=@implode(",",$postdb[allowviewtitle]);
	$postdb[allowviewcontent]=@implode(",",$postdb[allowviewcontent]);
	$postdb[allowdownload]=@implode(",",$postdb[allowdownload]);
	$postdb[template]=@serialize($postdb[tpl]);
	unset($SQL);

	$rs_fid=$db->get_one("SELECT * FROM {$pre}fu_sort WHERE fid='$postdb[fid]'");
	//这样处理是其他地方也修改过这个值.比如标签里
	$rs_fid[config]=unserialize($rs_fid[config]);
	$rs_fid[config][sonTitleRow]=$sonTitleRow;
	$rs_fid[config][sonTitleLeng]=$sonTitleLeng;
	$rs_fid[config][cachetime]=$cachetime;
	$rs_fid[config][sonListorder]=$sonListorder;
	$rs_fid[config][listContentNum]=$listContentNum;
	$rs_fid[config][ListShowType]=$ListShowType;
	$rs_fid[config][ListShowBigType]=$ListShowBigType;
	$postdb[config]=addslashes( serialize($rs_fid[config]) );

	if($rs_fid[fup]!=$postdb[fup])
	{
		$rs_fup=$db->get_one("SELECT class FROM {$pre}fu_sort WHERE fup='$postdb[fup]' ");
		$newclass=$rs_fup['class']+1;
		$db->query("UPDATE {$pre}fu_sort SET sons=sons+1 WHERE fup='$postdb[fup]' ");
		$db->query("UPDATE {$pre}fu_sort SET sons=sons-1 WHERE fup='$rs_fid[fup]' ");
		$SQL=",class=$newclass";
	}
	/*缺少对版主有效用户名的检测*/
	$postdb[admin]=str_Replace("，",",",$postdb[admin]);

	if($postdb[admin])
	{
		$detail=explode(",",$postdb[admin]);

		foreach( $detail AS $key=>$value)
		{
			if(!$value)
			{
				unset($detail[$key]);
			}
			else
			{
				$rs=$db->get_one("SELECT D.groupid,D.uid FROM {$pre}memberdata D LEFT JOIN $TB[table] M ON D.uid=M.$TB[uid] WHERE M.$TB[username]='$value'");

				if(!$rs)
				{
					showmsg("你设置的版主:$value,帐号不存在,或者还没激活帐号.请检查之");
				}
				elseif($rs[groupid]!=3&&$rs[groupid]!=5&&$rs[groupid]!=4)
				{
					//$db->query("UPDATE {$pre}memberdata SET groupid='5' WHERE uid='$rs[uid]' ");
				}
			}
		}

		$detail && $postdb[admin]=','.implode(',',$detail).',';
	}

	
	$postdb[descrip]=En_TruePath($postdb[descrip]);

	$postdb[name]=filtrate($postdb[name]);

	$db->query("UPDATE {$pre}fu_sort SET fup='$postdb[fup]',name='$postdb[name]',type='$postdb[type]',admin='$postdb[admin]',passwd='$postdb[passwd]',logo='$postdb[logo]',descrip='$postdb[descrip]',style='$postdb[style]',template='$postdb[template]',jumpurl='$postdb[jumpurl]',listorder='$postdb[listorder]',maxperpage='$postdb[maxperpage]',allowcomment='$postdb[allowcomment]',allowpost='$postdb[allowpost]',allowviewtitle='$postdb[allowviewtitle]',allowviewcontent='$postdb[allowviewcontent]',allowdownload='$postdb[allowdownload]',forbidshow='$postdb[forbidshow]',config='$postdb[config]',list_html='$postdb[list_html]',bencandy_html='$postdb[bencandy_html]',fmid='$postdb[fmid]',domain='$postdb[domain]',metakeywords='$postdb[metakeywords]',domain_dir='$postdb[domain_dir]'$SQL WHERE fid='$postdb[fid]' ");

	
	mod_sort_class("{$pre}fu_sort",0,0);		//更新class
	mod_sort_sons("{$pre}fu_sort",0);			//更新sons
	/*更新导航缓存*/
	cache_guide();
	//get_htmltype();
	jump("修改成功","$FROMURL");
}
elseif($job=='batch_edit'&&$Apower[fu_sort_power])
{
	if(!$fiddb){
		showmsg("请选择一个栏目");
	}
	$sort_fup=$Guidedb->Select("{$pre}fu_sort","postdb[fup]",$rsdb[fup]);
	$style_select=select_style('postdb[style]',$rsdb[style]);
	$group_post=group_box("postdb[allowpost]",explode(",",$rsdb[allowpost]));
	$group_viewtitle=group_box("postdb[allowviewtitle]",explode(",",$rsdb[allowviewtitle]));
	$group_viewcontent=group_box("postdb[allowviewcontent]",explode(",",$rsdb[allowviewcontent]));
	$group_download=group_box("postdb[allowdownload]",explode(",",$rsdb[allowdownload]));
	$typedb[$rsdb[type]]=" checked ";

	$forbidshow[intval($rsdb[forbidshow])]=" checked ";

	$tpl=unserialize($rsdb[template]);
	//$tpl_head=select_template("postdb[tpl][head]",7,$tpl[head]);
	//$tpl_foot=select_template("postdb[tpl][foot]",8,$tpl[foot]);
	//$tpl_list=select_template("postdb[tpl][list]",2,$tpl['list']);
	//$tpl_bencandy=select_template("postdb[tpl][bencandy]",3,$tpl[bencandy]);
	$tpl_head=select_template("",7,$tpl[head]);
	$tpl_head=str_replace("<select","<select onChange='get_obj(\"tpl_head\").value=this.options[this.selectedIndex].value;'",$tpl_head);

	$tpl_foot=select_template("",8,$tpl[foot]);
	$tpl_foot=str_replace("<select","<select onChange='get_obj(\"tpl_foot\").value=this.options[this.selectedIndex].value;'",$tpl_foot);

	$tpl_list=select_template("",2,$tpl['list']);
	$tpl_list=str_replace("<select","<select onChange='get_obj(\"tpl_list\").value=this.options[this.selectedIndex].value;'",$tpl_list);

	$tpl_bencandy=select_template("",3,$tpl[bencandy]);
	$tpl_bencandy=str_replace("<select","<select onChange='get_obj(\"tpl_bencandy\").value=this.options[this.selectedIndex].value;'",$tpl_bencandy);

	$listorder[$rsdb[listorder]]=" selected ";

	$module_id="<select name='postdb[fmid]'><option value='0'>文章模型</option>";
	$query = $db->query("SELECT * FROM {$pre}article_module  WHERE ifclose=0 ORDER BY list DESC");
	while($rs = $db->fetch_array($query)){
		$ckk=$rsdb[fmid]==$rs[id]?' selected ':'';
		$module_id.="<option value='$rs[id]' $ckk>$rs[name]</option>";
	}
	$module_id.=" </select>";

	require(dirname(__FILE__)."/"."head.php");
	require(dirname(__FILE__)."/"."template/fu_sort/menu.htm");
	require(dirname(__FILE__)."/"."template/fu_sort/batch_edit.htm");
	require(dirname(__FILE__)."/"."foot.php");	
}
elseif($action=='batch_edit'&&$Apower[fu_sort_power])
{
	if(!$ifchang&&!$db_index_showtitle&&!$db_sonTitleRow&&!$db_sonTitleLeng&&!$db_cachetime){
		showmsg("请选择要修改哪个属性");
	}
	$postdb[allowpost]=@implode(",",$postdb[allowpost]);
	$postdb[allowviewtitle]=@implode(",",$postdb[allowviewtitle]);
	$postdb[allowviewcontent]=@implode(",",$postdb[allowviewcontent]);
	$postdb[allowdownload]=@implode(",",$postdb[allowdownload]);
	$postdb[template]=@serialize($postdb[tpl]);

	/*缺少对版主有效用户名的检测*/
	$postdb[admin]=str_Replace("，",",",$postdb[admin]);
	
	foreach( $fiddb AS $fid=>$name){
		
		unset($SQL);
		$postdb[fid]=$fid;
		//检查父栏目是否有问题
		$ifchang[fup] && check_fup("{$pre}fu_sort",$postdb[fid],$postdb[fup]);
		$ifchang[fup] && $rs_fid=$db->get_one("SELECT * FROM {$pre}fu_sort WHERE fid='$postdb[fid]'");
		if($ifchang[fup] && $rs_fid[fup]!=$postdb[fup])
		{
			$rs_fup=$db->get_one("SELECT class FROM {$pre}fu_sort WHERE fup='$postdb[fup]' ");
			$newclass=$rs_fup['class']+1;
			$db->query("UPDATE {$pre}fu_sort SET sons=sons+1 WHERE fup='$postdb[fup]' ");
			$db->query("UPDATE {$pre}fu_sort SET sons=sons-1 WHERE fup='$rs_fid[fup]' ");
			$SQL=",class=$newclass";
		}

		if($ifchang[admin]&&$postdb[admin])
		{
			$detail=explode(",",$postdb[admin]);
			foreach( $detail AS $key=>$value)
			{
				if(!$value)
				{
					unset($detail[$key]);
				}
				else
				{
					$rs=$db->get_one("SELECT D.groupid,D.uid FROM {$pre}memberdata D LEFT JOIN $TB[table] M ON D.uid=M.$TB[uid] WHERE M.$TB[username]='$value'");

					if(!$rs)
					{
						showmsg("你设置的版主:$value,帐号不存在,或者还没激活帐号.请检查之");
					}
					elseif($rs[groupid]!=3&&$rs[groupid]!=5&&$rs[groupid]!=4)
					{
						//$db->query("UPDATE {$pre}memberdata SET groupid='5' WHERE uid='$rs[uid]' ");
					}
				}
			}
			$detail && $postdb[admin]=','.implode(',',$detail).',';
		}

		//处理首页显示的栏目
		if($db_index_showtitle)
		{
			$rsC=$db->get_one("SELECT * FROM {$pre}channel WHERE id=1 ");
			$detail=explode(",","$rsC[fids],$postdb[fid]");
			foreach( $detail AS $key=>$value){
				//处理重复的FID
				if($ckarray["$value"])
				{
					unset($detail[$key]);
				}
				if(!$index_showtitle&&$value==$postdb[fid])
				{
					unset($detail[$key]);
				}
				$ckarray["$value"]=1;
			}
			$fids=implode(',',$detail);
			$db->query("UPDATE {$pre}channel SET fids='$fids' WHERE id='1' ");
		}

		if($db_sonTitleRow||$db_sonTitleLeng||$db_cachetime){
			$rs_fid=$db->get_one("SELECT config FROM {$pre}fu_sort WHERE fid='$postdb[fid]'");

			//这样处理是其他地方也修改过这个值.比如标签里
			$rs_fid[config]=unserialize($rs_fid[config]);
			$db_sonTitleRow && $rs_fid[config][sonTitleRow]=$sonTitleRow;
			$db_sonTitleLeng && $rs_fid[config][sonTitleLeng]=$sonTitleLeng;
			$db_cachetime && $rs_fid[config][cachetime]=$cachetime;
			$postdb[config]=addslashes( serialize($rs_fid[config]) );
			$ifchang[config]=1;
		}
		
		foreach( $ifchang AS $key=>$value){
			$SQL.=",$key='{$postdb[$key]}'";
		}
		$SQL && $db->query("UPDATE {$pre}fu_sort SET fid='$postdb[fid]'$SQL WHERE fid='$postdb[fid]' ");
	
		//修改管理员后,检查是否去掉管理员.要把他的用户组处理掉
		if($ifchang[admin])
		{
			$rs_fid=$db->get_one("SELECT admin FROM {$pre}fu_sort WHERE fid='$postdb[fid]'");
			$old_admin=$rs_fid[admin];
			$detail=explode(",",$old_admin);
			$detail_new=explode(",",$postdb[admin]);
		
			//对提交前的一一检查
			foreach( $detail AS $key=>$value)
			{
				if( $value&&!@in_array($value,$detail_new) )
				{
					$rs=$db->get_one("SELECT D.groupid,D.uid FROM {$pre}memberdata D LEFT JOIN $TB[table] M ON D.uid=M.$TB[uid] WHERE M.$TB[username]='$value'");
					if( $rs[groupid]!=3&&$rs[groupid]!=4 )
					{
						$rss=$db->get_one("SELECT admin FROM {$pre}fu_sort WHERE BINARY admin LIKE '%,$value,%' ");
						if(!$rss){
							//$db->query("UPDATE {$pre}memberdata SET groupid='8' WHERE uid='$rs[uid]' ");
						}
					}
				}
			}
		}
	}
	mod_sort_class("{$pre}fu_sort",0,0);		//更新class
	mod_sort_sons("{$pre}fu_sort",0);			//更新sons
	/*更新导航缓存*/
	cache_guide();
	jump("修改成功","index.php?lfj=$lfj&job=listsort");
}
//创建栏目频道
elseif($job=='creat_channel'&&$Apower[fu_sort_power])
{
	require(dirname(__FILE__)."/"."head.php");
	require(dirname(__FILE__)."/"."template/fu_sort/menu.htm");
	require(dirname(__FILE__)."/"."template/fu_sort/creat_channel.htm");
	require(dirname(__FILE__)."/"."foot.php");
}
elseif($action=='creat_channel'&&$Apower[fu_sort_power])
{
	if(!eregi("^([a-z0-9_/]+)$",$channelDir)){
		showmsg("频道目录字符只能是:a-z0-9_/");
	}
	if(is_dir(PHP168_PATH.$channelDir)){
		showmsg("$channelDir,此目录已经存在了.请换一个吧");
	}
	makepath(PHP168_PATH.$channelDir);
	$paths='';
	$detail=explode("/",$channelDir);
	foreach( $detail AS $key=>$value){
		if($value){
			$paths.='../';
		}
	}
	write_file(PHP168_PATH."$channelDir/index.php","<?php
\$fid='$fid';
if(is_file('index.htm')){header('location:index.htm');exit;}
require_once(\"list.php\");
");

	write_file(PHP168_PATH."$channelDir/list.php","<?php
require_once(\"global.php\");
require_once(THIS_PATH.\"list.php\");
	");

	write_file(PHP168_PATH."$channelDir/bencandy.php","<?php
require_once(\"global.php\");
require_once(THIS_PATH.\"bencandy.php\");
	");

	write_file(PHP168_PATH."$channelDir/global.php","<?php
defined(\"THIS_PATH\") || define(\"THIS_PATH\",\"$paths\");
require_once(THIS_PATH.\"global.php\");	
	");

	$rs_fid=$db->get_one("SELECT config FROM {$pre}fu_sort WHERE fid='$fid'");

	$rs_fid[config]=unserialize($rs_fid[config]);
	$rs_fid[config][channelDir]=$channelDir;
	$rs_fid[config][channelDomain]=$channelDomain;
	$config=addslashes( serialize($rs_fid[config]) );
	$db->query("UPDATE {$pre}fu_sort SET config='$config' WHERE fid='$fid'");
	jump("[创建成功] [<A HREF='$webdb[www_url]/$channelDir' target=_blank>浏览此频道</A>]","index.php?lfj=$lfj&job=listsort",20);
}
elseif($job=="label"&&$Apower[fu_sort_power])
{
	$erp=$Fid_db[iftable][$fid];
	$rsdb=$db->get_one("SELECT * FROM {$pre}article$erp WHERE fid='$fid' LIMIT 1");
	require(dirname(__FILE__)."/"."head.php");
	require(dirname(__FILE__)."/"."template/fu_sort/menu.htm");
	require(dirname(__FILE__)."/"."template/fu_sort/label.htm");
	require(dirname(__FILE__)."/"."foot.php");
}
//修改栏目频道
elseif($job=='edit_channel'&&$Apower[fu_sort_power])
{
	$rs_fid=$db->get_one("SELECT config FROM {$pre}fu_sort WHERE fid='$fid'");
	@extract(unserialize($rs_fid[config]));
	require(dirname(__FILE__)."/"."head.php");
	require(dirname(__FILE__)."/"."template/fu_sort/menu.htm");
	require(dirname(__FILE__)."/"."template/fu_sort/edit_channel.htm");
	require(dirname(__FILE__)."/"."foot.php");
}
elseif($action=='edit_channel'&&$Apower[fu_sort_power])
{
	$rs_fid=$db->get_one("SELECT config FROM {$pre}fu_sort WHERE fid='$fid'");
	$rs_fid[config]=unserialize($rs_fid[config]);

	if( $channelDir && !eregi("^([a-z0-9_/]+)$",$channelDir) ){
		showmsg("频道目录字符只能是:a-z0-9_/");
	}

	//当用户重命名后
	if( $channelDir && ($channelDir!=$rs_fid[config][channelDir]) ){
		if(is_dir(PHP168_PATH.$channelDir)){
			showmsg("$channelDir,此目录已经存在了.请换一个吧");
		}
		makepath(PHP168_PATH.$channelDir);

		$paths='';
		$detail=explode("/",$channelDir);
		foreach( $detail AS $key=>$value){
			if($value){
				$paths.='../';
			}
		}
		write_file(PHP168_PATH."$channelDir/index.php","<?php
\$fid='$fid';
if(is_file('index.htm')){header('location:index.htm');exit;}
require_once(\"list.php\");
		");

		write_file(PHP168_PATH."$channelDir/list.php","<?php
require_once(\"global.php\");
require_once(THIS_PATH.\"list.php\");
		");

		write_file(PHP168_PATH."$channelDir/bencandy.php","<?php
require_once(\"global.php\");
require_once(THIS_PATH.\"bencandy.php\");
		");

		write_file(PHP168_PATH."$channelDir/global.php","<?php
defined(\"THIS_PATH\") || define(\"THIS_PATH\",\"$paths\");
require_once(THIS_PATH.\"global.php\");	
		");
		
	}

	$rs_fid[config][channelDir]=$channelDir;
	$rs_fid[config][channelDomain]=$channelDomain;
	$config=addslashes( serialize($rs_fid[config]) );
	$db->query("UPDATE {$pre}fu_sort SET config='$config' WHERE fid='$fid'");
	jump("[修改成功] [<A HREF='$webdb[www_url]/$channelDir' target=_blank>浏览此频道</A>]","$FROMURL",20);
}
elseif($action=="delete"&&$Apower[fu_sort_power])
{
	$db->query("DELETE FROM {$pre}fu_sort WHERE fid='$fid'");
	$db->query("DELETE FROM {$pre}fu_article WHERE fid='$fid'");
	
	mod_sort_class("{$pre}fu_sort",0,0);		//更新class
	mod_sort_sons("{$pre}fu_sort",0);			//更新sons
	/*更新导航缓存*/
	cache_guide();
	jump("删除成功","index.php?lfj=$lfj&job=listsort&only=$only&mid=$mid");
}
elseif($action=="editlist"&&$Apower[fu_sort_power])
{
	foreach( $order AS $key=>$value){
		$db->query("UPDATE {$pre}fu_sort SET list='$value' WHERE fid='$key' ");
	}
	mod_sort_class("{$pre}fu_sort",0,0);		//更新class
	mod_sort_sons("{$pre}fu_sort",0);			//更新sons
	/*更新导航缓存*/
	cache_guide();
	jump("修改成功","$FROMURL",1);
}
/**
*修复网站栏目
**/
elseif($job=='save'&&$Apower[fu_sort_power])
{
	$errsort=sort_error("{$pre}fu_sort",'fid');
 	$sort_fup=$Guidedb->Select("{$pre}fu_sort","fup",$rsdb[fup]);
	require(dirname(__FILE__)."/"."head.php");
	require(dirname(__FILE__)."/"."template/fu_sort/menu.htm");
	require(dirname(__FILE__)."/"."template/fu_sort/save.htm");
	require(dirname(__FILE__)."/"."foot.php");
}

/**
*处理修复出错栏目
**/
elseif($action=='save'&&$Apower[fu_sort_power]){
	if(!$fid){
		showmsg("请选择一个栏目");
	}
	$db->query("UPDATE {$pre}fu_sort SET fup='$fup' WHERE fid='$fid' ");
	mod_sort_class("{$pre}fu_sort",0,0);			//更新class
	mod_sort_sons("{$pre}fu_sort",0);			//更新sons
	/*更新导航缓存*/
	cache_guide();
	jump("本栏目修正成功","$FROMURL",1);
}

/**
*合拼网站栏目
**/
elseif($job=='toget'&&$Apower[fu_sort_power])
{
	$selectname_1=$Guidedb->Select("{$pre}fu_sort",'ofid');
	$selectname_2=$Guidedb->Select("{$pre}fu_sort",'nfid');
	require(dirname(__FILE__)."/"."head.php");
	require(dirname(__FILE__)."/"."template/fu_sort/menu.htm");
	require(dirname(__FILE__)."/"."template/fu_sort/toget.htm");
	require(dirname(__FILE__)."/"."foot.php");
}

/**
*合拼网站栏目
**/
elseif($action=='toget'&&$Apower[fu_sort_power]){
	if(!$ofid){
		showmsg("请选择一个源栏目");
	}elseif(!$nfid){
		showmsg("请选择一个目标栏目");
	}
	if($ofid==$nfid){
		showmsg("出错了，栏目本身不能合并为自己,请选择合并到其他栏目去吧");
	}
	$erp=$Fid_db[iftable][$ofid];
	$db->query("UPDATE {$pre}article$erp SET fid='$nfid',fname='{$Fid_db[name][$nfid]}' WHERE fid='$ofid'");
	$db->query("UPDATE {$pre}reply$erp SET fid='$nfid' WHERE fid='$ofid'");
	$db->query("UPDATE {$pre}comment SET fid='$nfid' WHERE fid='$ofid'");
	$db->query("DELETE FROM {$pre}fu_sort WHERE fid='$ofid'");
	mod_sort_class("{$pre}fu_sort",0,0);		//更新class
	mod_sort_sons("{$pre}fu_sort",0);			//更新sons
	/*更新导航缓存*/
	cache_guide();
	jump("操作完毕","$FROMURL",1);
}/*
elseif($job=="config"&&$Apower[sort_config])
{
	$webdb[viewNoPassArticle]==='0' || $webdb[viewNoPassArticle]=1;
	$viewNoPassArticle[$webdb[viewNoPassArticle]]=" checked ";

	$webdb[ifContribute]==='0' || $webdb[ifContribute]=1;
	$ifContribute[$webdb[ifContribute]]=" checked ";

	$webdb[autoGetSmallPic]=(int)$webdb[autoGetSmallPic];
	$autoGetSmallPic[$webdb[autoGetSmallPic]]=" checked ";

	$allowGuestSearch[$webdb[allowGuestSearch]]=" checked ";

	$adminPostEditType[$webdb[adminPostEditType]]=" checked ";
	$ListShowIcon[intval($webdb[ListShowIcon])]=" checked ";
	$webdb[newArticleTime] || $webdb[newArticleTime]=24;
	$webdb[hotArticleNum] || $webdb[hotArticleNum]=100;

	require(dirname(__FILE__)."/"."head.php");
	require(dirname(__FILE__)."/"."template/fu_sort/config.htm");
	require(dirname(__FILE__)."/"."foot.php");
}
elseif($action=="config"&&$Apower[sort_config])
{
	setcookie("editType",'',$timestamp-9999999);
	write_config_cache($webdbs);
	jump("修改成功",$FROMURL);
}*/



/**
*更新导航缓存
**/
function cache_guide(){
	global $Guidedb,$pre;
	//$Guidedb->FidSonCache("{$pre}fu_sort");
	$Guidedb->GuideFidCache("{$pre}fu_sort",'fu_guide_fid.php');
	All_fid_cache2();
}


//获取标签模板
function getLabelTpl($path='template/default/list_tpl'){
	global $webdb,$rsdb;
	$pictitledb[]=$f1="默认模板";
	if($rsdb[fmid]&&is_file(PHP168_PATH."$path/mod_{$rsdb[fmid]}.htm")){
		$picurldb[]=$f2="$webdb[www_url]/$path/mod_{$rsdb[fmid]}.jpg";
	}else{
		$picurldb[]=$f2="$webdb[www_url]/$path/0.jpg";
	}	
	$select="<option value='$f2'>$f1</option>";
	$dir=opendir(PHP168_PATH.$path);
	while($file=readdir($dir)){
		if(eregi("\.htm$",$file)&&!eregi("^mod_([0-9]+)\.htm$",$file)&&$file!='0.htm'){
			$pictitledb[]=str_replace(".htm","",$file);
			$picurldb[]=$f2="$webdb[www_url]/$path/".str_replace(".htm",".jpg",$file);
			$select.="<option value='$f2'>".str_replace(".htm","",$file)."</option>";
		}
	}

	$picurldb=implode('","',$picurldb);
	$pictitledb=implode('","',$pictitledb);
	$myurl=str_replace(array(".","/"),array("\.","\/"),$webdb[www_url]);
$show=<<<EOT
<table  border="0" cellspacing="0" cellpadding="0">
<tr><td style="padding-left:20px;padding-bottom:10px;"><select id="selectTyls" onChange="selectTpl(this)">
    $select<option value='-2' style='color:red;'>新建一个</option>
  </select> [<a href="#LOOK" onclick="show_MorePic(-1)">上一个</a>] 
      【<span id="upfile_PicNum">1/2</span>】[<a href="#LOOK" onclick="show_MorePic(1)">下一个</a>]  
       


	
</td></tr>
  <tr>
    <td height="30" style="padding-left:20px;"><div id="showpicdiv" class="showpicdiv" style="width:10px;height:3px;"><A style="border:2px solid #fff;display:block;" HREF="javascript::" id="showPicID" target="_blank"><img border="0" onerror="this.src=replace_img(this.src);" onload="this.height='200'" id="upfile_PicUrl"></A></div></td>

    

  </tr>
</table>

	
<SCRIPT LANGUAGE="JavaScript">
var ImgLinks= new Array("$picurldb");
var ImgTitle= new Array("$pictitledb");
function replace_img(url){
	//如果图片不存在,就去官方获取图片,如果还是不存在,就使用默认的无图片.
	reg=/http:\/\/down2\.php168\.com/g
	if(reg.test(url)){
		return "$webdb[www_url]/images/default/nopic.jpg";
	}
	re   = /$myurl/g;
	links = url.replace(re, "http://down2.php168.com");
	return links;
}
</SCRIPT>
EOT;
	return $show;
}


function list_sort_guide($fup){
	global $db,$pre,$mid,$only,$job,$lfj;
	$rs=$db->get_one("SELECT fup,name FROM {$pre}fu_sort WHERE fid='$fup'");
	if($rs){
		$show=" -> <A HREF='index.php?lfj=$lfj&job=$job&only=$only&mid=$mid&fid=$fup'>$rs[name]</A> ";
		$show=list_sort_guide($rs[fup]).$show;
	}
	return $show;
}


/*栏目列表*/
function list_allsort2($fid,$table='sort',$getnum=''){
	global $db,$pre,$sortdb,$Fid_db;
	$query=$db->query("SELECT * FROM {$pre}$table where fup='$fid' ORDER BY list DESC");
	while( $rs=$db->fetch_array($query) ){
		$icon="";
		for($i=1;$i<$rs['class'];$i++){
			$icon.="&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;";
		}
		if($icon){
			$icon=substr($icon,0,-24);
			$icon.="--";
		}
		$rs[config]=unserialize($rs[config]);
		$rs[icon]=$icon;
		$NUM=0;
		if($getnum&&!$rs[type]){
			@extract($db->get_one("SELECT COUNT(*) AS NUM FROM {$pre}fu_article WHERE fid='$rs[fid]'"));
			$rs[NUM]=intval($NUM);
		}
		$sortdb[]=$rs;

		list_allsort2($rs[fid],$table,$getnum);
	}
}

function All_fid_cache2(){
	global $db,$pre,$webdb;
	//隐藏栏目
	//$detail=explode(",",$webdb['hideFid']);
	$show="<?php\r\nunset(\$Fid_db);\r\n";
	$query = $db->query("SELECT S.fid,S.fup,S.name,M.iftable,M.id AS Mid FROM {$pre}fu_sort S LEFT JOIN {$pre}article_module M ON S.fmid=M.id ORDER BY S.list DESC");
	while($rs = $db->fetch_array($query)){
		if(in_array($rs[fid],$detail)){
			//continue;
		}
		//$_s=$rs[iftable]?"\$Fid_db[iftable][{$rs[fid]}]='$rs[iftable]';":'';
		$rs[name]=addslashes($rs[name]);
		$show.="\$Fu_Fid_db[{$rs[fup]}][{$rs[fid]}]='$rs[name]';
		\$Fu_Fid_db[name][{$rs[fid]}]='$rs[name]';
		$_s";
	}
	write_file("../php168/fu_all_fid.php",$show.'?>');
}

?>