<?php
require(dirname(__FILE__)."/../global.php");
require(dirname(__FILE__)."/../homepage_php/global.php");
require_once(dirname(__FILE__)."/../bd_pics.php");
$atn=$atn?$atn:"info";
$member_style=$webdb[sys_member_style]?$webdb[sys_member_style]:($webdb[member_style]?$webdb[member_style]:"images2");
$linkdb=array(
			"商铺预览"=>$Mdomain."/homepage.php?uid=$lfjuid"

			);	
$blank="_blank";
	
if(!$lfjuid) showerr("您无权操作");
$uid=$lfjuid;
$rsdb=$db->get_one("SELECT * FROM {$_pre}company WHERE uid='$uid' LIMIT 1");
if(!$rsdb[rid]) showerr("商家信息未登记,<a href='$Murl/member/post_company.php'>点击这里登记商家</a>，拥有自己的商铺");
if($rsdb[is_vip] > $timestamp){
	$rsdb[vip]=true;
}else{
	$rsdb[vip]=false;
}
//商家配置文件

$homepage=$db->get_one("SELECT * FROM {$_pre}homepage WHERE rid='$rsdb[rid]' LIMIT 1");


if(!$homepage[hid]) { //激活商家信息

	require(dirname(__FILE__)."/"."head.php");
	require(dirname(__FILE__)."/"."template/active_homepage.htm");
	require(dirname(__FILE__)."/"."foot.php");
	exit;
}



//是否需要显示完善公司资料的判断
if(!$rsdb[picurl] || 
   !$rsdb[fid] || 
   !$rsdb[city_id] || 
   !$rsdb[my_trade] || 
   !$rsdb[qy_cate] || 
   !$rsdb[qy_saletype] || 
   !$rsdb[qy_createtime] || 
   !$rsdb[qy_pro_ser] ||
   !$rsdb[my_buy] ||
   !$rsdb[qy_regplace]){

	$companyinfoedit_notice=1;
}

if($atn=='mydomain'){

	if(!$webdb[vipselfdomain]){
		//showerr("系统关闭了二级域名功能");
	}

	if(!$step){
		
		$myonly='http://'.$HTTP_HOST."/";
		$re = "/http:\/\/.*?([^\.]+\.(com\.cn|org\.cn|net\.cn|[^\.]+))\//";
		if(preg_match($re, $myonly)){
				preg_match_all($re, $myonly, $res,PREG_PATTERN_ORDER);
				$webdomain = $res[1][0];
		}


	}else{
		$host=trim($host);   
		//检测
		if(!preg_match("/^[a-z\d]{2,12}$/",$host))  showerr("二级域名只能使字母或者数字，长度在2-12个字符之间,且全部小写");
		$limitmain=explode(",",$webdb[vipselfdomaincannot]);
		if(in_array($host,$limitmain)) showerr("此二级域名为系统限制域名，不能使用，请换一个重试");

		$rt=$db->get_one("SELECT COUNT(*) AS num FROM  {$_pre}company WHERE host='$host' AND  rid!='$rsdb[rid]'");
		if($rt[num]>0) showerr("[ $host ]已经被别人使用了，请换一个重试");
		//更新
		$db->query("UPDATE {$_pre}company SET
				`host`='$host'
				WHERE rid='$rsdb[rid]' ");
		refreshto("?uid=$uid&atn=$atn","设置成功");
	}

}elseif($atn=='base'){
	
	if(!$step){
		
		
		//商家配置文件
		$conf=$db->get_one("SELECT * FROM {$_pre}homepage WHERE rid='$rsdb[rid]' LIMIT 1");
		
		//列表设置
		$conf[listnum]=unserialize($conf[listnum]);	
		
		//模块设置 left
		$conf[index_left]=explode(",",$conf[index_left]);
		foreach($conf[index_left] as $key){
		
			if($key) $index_left.="<option value='$key'>".$tpl_left[$key]."</option>";
		}
		foreach($tpl_left as $key=>$val){
		
			$index_left_hx.="<option value='$key'>$val</option>";
		}
		
		//模块设置 right
		$conf[index_right]=explode(",",$conf[index_right]);
		foreach($conf[index_right] as $key){
			if($key) $index_right.="<option value='$key' >".$tpl_right[$key]."</option>";
		}
		foreach($tpl_right as $key=>$val){
		
			$index_right_hx.="<option value='$key'>$val</option>";
		}
		
		
		
		$bodytpl[$conf[bodytpl]]=" checked";
		//风格
		$homepage_style="default";
		if($conf[style] && is_dir($tpl_dir.$conf[style])) $homepage_style=$conf[style];
		
		//得到我的分类
		$query=$db->query("SELECT * FROM `{$_pre}mysort` WHERE uid='$lfjuid' AND ctype=1 ORDER BY listorder DESC");
		while($rs=$db->fetch_array($query)){
			 $sell_mysort[$rs[ms_id]]=$rs[sortname];
		}
		$query=$db->query("SELECT * FROM `{$_pre}mysort` WHERE uid='$lfjuid' and ctype=2 ORDER BY listorder desc");
		while($rs=$db->fetch_array($query)){
			 $buy_mysort[$rs[ms_id]]=$rs[sortname];
		}

		//认证开放
		$renzheng_show[$conf[renzheng_show]]=" checked";

	}else{
		
		$conf[listnum]=serialize($conf[listnum]);
		if(count($conf[index_left])<1){
			showerr("商铺左边栏目不能为空");
		}
		$conf[index_left]=implode(",",$conf[index_left]);
		if(count($conf[index_right])<1){
			showerr("商铺右边栏目不能为空");
		}
		$conf[index_right]=implode(",",$conf[index_right]);
				
		//检测VIP

		if(substr($conf[style],0,3) == 'vip'){
			if(!$rsdb[vip]) showerr("还不是VIP商家,不能使用此模板，建议您开通VIP商家服务 [ <a href='$Murl/member/vip.php'>点击这里开通VIP</a> ] ");
		}

		$db->query("UPDATE {$_pre}homepage SET
		`style`='$conf[style]',
		`index_left`='$conf[index_left]',
		`index_right`='$conf[index_right]',
		`listnum`='$conf[listnum]',
		`bodytpl`='$conf[bodytpl]',
		renzheng_show='$conf[renzheng_show]'
		WHERE hid='$conf[hid]'");
		refreshto("?uid=$uid&atn=$atn","设置成功");
	
	}
	
}elseif($atn=='banner'){
		
	
		if(!$step){
		

			$conf=$db->get_one("SELECT * FROM {$_pre}homepage WHERE rid='$rsdb[rid]' LIMIT 1");
			$conf[banner_show]=$webdb[www_url]."/".$webdb[updir]."/{$Imgdirname}/banner/".$conf[banner];
		}else{
				//图片处理
			if($del_banner==1){
				@unlink(PHP168_PATH.$webdb[updir]."/{$Imgdirname}/banner/".$conf[oldfile]);
				$banner="";
			}else{
				$banner=$conf[oldfile];
				if(is_uploaded_file($_FILES[postfile][tmp_name])){
					$array[name]=is_array($postfile)?$_FILES[postfile][name]:$postfile_name;
					$array[path]=$webdb[updir]."/{$Imgdirname}/banner/";
					if(!is_dir($array[path])) @mkdir($array[path]);
					$array[size]=is_array($postfile)?$_FILES[postfile][size]:$postfile_size;
					if($array[size]>$webdb[homepage_banner_size]*1024) showerr("图片文件不能超过$webdb[homepage_banner_size] K");
					$picurl_t=upfile(is_array($postfile)?$_FILES[postfile][tmp_name]:$postfile,$array);
					if($picurl_t){//更换图片
						$banner=$picurl_t;
						@unlink(PHP168_PATH.$array[path].$conf[oldfile]);
					}		
				}
				
				
			}
			$db->query("UPDATE {$_pre}homepage SET
				`banner`='$banner'
				WHERE hid='$conf[hid]'");
		refreshto("?uid=$uid&atn=$atn","设置成功");
			
		}
		
}elseif($atn=='info'){
		require(Mpath."inc/categories.php");
		
		$bcategory->cache_read();
		
		if(!$step){
			$categories = $bcategory->unsets(true);
			@extract($intro=$db->get_one("SELECT * FROM `{$_pre}company` WHERE `uid`='$uid'"));
			$query = $db->query("SELECT fid FROM `{$_pre}company_fid` WHERE cid = {$intro['rid']}");
			$fids = array(); while($arr = $db->fetch_array($query)) $fids[] = $arr['fid'];
			
			$rsdb[logo]=$webdb[www_url].'/'.$webdb[updir]."/$Imgdirname/ico/".$intro[picurl];
			$my_trade['$my_trade']=" selected";
			$qy_cate['$qy_cate']=" selected";
			$qy_saletype['$qy_saletype']=" selected";
			
			//得到分类
			//$fid_all=explode("|",$intro[fid_all]);
			
			
			
			//得到地区
			
			$city_options=select_where('city',"'city_id'   style='width:100px;'",$city_id,$province_id);
			$area_choose=select_where('province',"'province_id'  onchange='showcity(this)' style='width:100px;'",$province_id,0);
			$area_choose=$area_choose."<span id='city_span'>$city_options</span>";


			//真实地址还原
			$content=En_TruePath($content,0);
			
			$webdb[maxCompanyFidNum]=$webdb[maxCompanyFidNum]?$webdb[maxCompanyFidNum]:10;

		}else{
		
			$picurl=$oldfile;
			if(is_uploaded_file($_FILES[postfile][tmp_name])){
				$array[name]=is_array($postfile)?$_FILES[postfile][name]:$postfile_name;
				$array[path]=$webdb[updir]."/{$Imgdirname}/ico/";
				if(!is_dir($array[path])) @mkdir(PHP168_PATH.$array[path]);
				$array[size]=is_array($postfile)?$_FILES[postfile][size]:$postfile_size;
				if($array[size]>$webdb[homepage_ico_size]*1024) showerr("图片文件不能超过$webdb[homepage_ico_size] K");
				$picurl_t=upfile(is_array($postfile)?$_FILES[postfile][tmp_name]:$postfile,$array);
				if($picurl_t){//更换图片
					$picurl=$picurl_t;
					echo "删除原来LOGO $conf[oldfile]";
					@unlink(PHP168_PATH.$array[path].$oldfile);

					$Newpicpath=PHP168_PATH."$array[path]/logo_{$picurl}";
					gdpic(PHP168_PATH."$array[path]/$picurl",$Newpicpath,120,120);
					if(file_exists($Newpicpath)){
						@unlink(PHP168_PATH."$array[path]/{$picurl}");
						$picurl="logo_{$picurl}";
					}

					
				}	
				
			}
		
		
			if(strlen($title) <5 || strlen($title)>60 ) showerr("公司名称必须在5-30个字之间");
				
			if(count($fids)<1)showerr("至少选择一个分类");

			if(!$city_id) showerr("所属地区必须选择");

			$intro=$db->get_one("SELECT rid FROM `{$_pre}company` WHERE `uid`='$uid'");
			$db->query("DELETE FROM `{$_pre}company_fid` WHERE cid = {$intro['rid']}");
			
			//插入多条关系
			$values = $comma = '';
			foreach($fids as $key){
				$key = intval($key);
				if($key){
					$values .= $comma ."({$intro['rid']}, $key)";
					$comma = ',';
				}
				//$fid_all[$key]=getFidAll($key);
				//$fname[$key]=$Fid_db[name][$key];
			}
			
			$db->query("INSERT INTO `{$_pre}company_fid` VALUES $values");
			

			$db->query("UPDATE `{$_pre}company` SET 
			`title`='$title',
			`picurl`='$picurl',
			`fname`='$fname',
			`province_id`='{$province_id}',
			`city_id`='{$city_id}',
			`my_trade`='$my_trade',
			`qy_cate`='$qy_cate',
			`qy_regmoney`='$qy_regmoney',
			`qy_saletype`='$qy_saletype',
			`qy_createtime`='$qy_createtime',
			`qy_pro_ser`='$qy_pro_ser',
			`my_buy`='$my_buy',
			`qy_regplace`='$qy_regplace'
			WHERE uid='$uid'");
			
	

			
			refreshto("?uid=$uid&atn=$atn","修改成功");
		
		}
		
}elseif($atn=='info2'){
		
		if(!$step){
			@extract($intro=$db->get_one("SELECT * FROM `{$_pre}company` WHERE `uid`='$uid'"));
			//真实地址还原
			$content=En_TruePath($content,0);
			$rsdb[bd_pics]=$intro[bd_pics];
		}else{
			if(!$content){
			showerr("内容不能为空");
			}
			
			$content = preg_replace('/javascript/i','java script',$content);//过滤js代码
			$content = preg_replace('/<iframe ([^<>]+)>/i','&lt;iframe \\1>',$content);//过滤框架代码
			$content=En_TruePath($content,1);
			$db->query("UPDATE `{$_pre}company` SET 
			`content`='$content'
			WHERE uid='$uid'");
			//更新绑定图片	
			bd_pics("{$_pre}company","WHERE  uid='$uid'");
			
			refreshto("?uid=$uid&atn=$atn","修改成功");
			
		}
}elseif($atn=='contactus'){
		
		if(!$step){
			
			$address=$db->get_one("select A.*  from `{$_pre}company` A  where A.`uid`='$uid'");
			$address[city_id]=$area_DB[name][$address[province_id]]." ".$city_DB[$address[province_id]][$address[city_id]];
	
		}else{
			
		
			$db->query("UPDATE `{$_pre}company` set
			`qy_contact`='$qy_contact',
			`qy_contact_zhiwei`='$qy_contact_zhiwei',
			`qy_contact_tel`='$qy_contact_tel',
			`qy_contact_fax`='$qy_contact_fax',
			`qy_contact_mobile`='$qy_contact_mobile',
			`qy_website`='$qy_website',
			`qy_contact_email`='$qy_contact_email',
			`qy_postnum`='$qy_postnum',
			`qy_address`='$qy_address',
			`qq`='$qq',
			`msn`='$msn',
			`skype`='$skype',
			`ww`='$ww'
			WHERE uid='$uid'");
			
			
			refreshto("?uid=$uid&atn=$atn","修改成功");
			
		}	
		
}elseif($atn=='news'){
	
	$rows=10;
	if($page<1){
		$page=1;
	}
	$min=($page-1)*$rows;
	$rsdb=$db->get_one("SELECT * FROM {$_pre}company WHERE uid='$uid' LIMIT 1");
	if(!$rsdb[rid]) showerr("商家信息未登记");
	$where=" WHERE rid='$rsdb[rid]' ";
	
	$query=$db->query("SELECT * FROM {$_pre}homepage_article $where ORDER BY posttime DESC LIMIT $min,$rows");
	
	while($rs=$db->fetch_array($query)){
		$rs[posttime]=date("Y-m-d",$rs[posttime]);
		$rs[yz]=!$rs[yz]?"<font color=red>审核中</font>":"已通过";
		$listdb[]=$rs;
	}	
	
	$showpage=getpage("{$_pre}homepage_article",$where,"?uid=$uid&atn=$atn",$rows);
	
	
}elseif($atn=='postnews'){
	
	if(!$step){
	
		if($id){
			$news=$db->get_one("select * from {$_pre}homepage_article where id='$id'");
			
			//真实地址还原
			$news[content]=En_TruePath($news[content],0);
		}
		$rsdb[bd_pics]=$news[bd_pics];
	}else{
			if(strlen($title)<10 || strlen($title)>60) showerr("标题只能在5-30个字");
			if(!$content) showerr("内容不能为空");
			if(strlen($content)>50000) showerr("内容过长，最多50000字符(包括HTML代码)");
			
			$content = preg_replace('/javascript/i','java script',$content);//过滤js代码
			$content = preg_replace('/<iframe ([^<>]+)>/i','&lt;iframe \\1>',$content);//过滤框架代码
			$content =En_TruePath($content,1);
			$rsdb=$db->get_one("select * from {$_pre}company where uid='$uid' limit 1");
			if(!$rsdb[rid]) showerr("商家信息未登记");
			if($id){
			
				$db->query("update `{$_pre}homepage_article` set `title`='$title',`content`='$content',yz=0 where id='$id'");
			
			}else{
				$yz=0;
				$db->query("INSERT INTO `{$_pre}homepage_article` ( `id` , `title` , `content` , `hits` , `posttime` , `list` , `uid` , `username` , `rid` , `titlecolor` , `fonttype` , `picurl` , `ispic` , `yz` , `levels` , `keywords` ) VALUES ('', '$title', '$content', '0', '".time()."', '0', '$lfjuid', '$lfjid', '$rsdb[rid]', '', '0', '', '0', '$yz', '0', '');");
				$id=$db->insert_id();
			}
			//更新绑定图片	
			bd_pics("{$_pre}homepage_article"," where id='$id' ");

			refreshto("?uid=$uid&atn=news","提交成功");
	}
}elseif($atn=='delnews'){	
	
	$rsdb=$db->get_one("SELECT * FROM `{$_pre}homepage_article` WHERE id='$id' ");
	if($rsdb[uid]!=$uid)
	{
		showerr("你无权删除别人的新闻");
	}
	//删除附件
	delete_attachment($rsdb[uid],$rsdb[content]);
	//删除附件
	delete_attachment($rsdb[uid],tempdir($rsdb[picurl]) );
	$db->query("DELETE FROM `{$_pre}homepage_article` WHERE id='$id' AND uid='$uid'");
	refreshto("?uid=$uid&atn=news","删除成功");
	
}elseif($atn=='friendlink'){
	
	$rsdb=$db->get_one("select * from {$_pre}company where uid='$uid' limit 1");
	if(!$rsdb[rid]) showerr("商家信息未登记");
	//商家配置文件
	$conf=$db->get_one("select * from {$_pre}homepage where rid='$rsdb[rid]' limit 1");
	
	if($step){
		if(!$fl_username)showerr("请输入对方通行证帐号");
		$flink=$db->get_one("select * from {$_pre}company where username='$fl_username' limit 1");
		if(!$flink[rid]) showerr("没有找到对应的商家");
		if($fl_username==$rsdb[username])  showerr("不能添加自己");
		if(strpos($conf[friendlink],$fl_username)!==false)  showerr("已经添加过此商家了");
		$conf[friendlink]=$conf[friendlink].",".$fl_username;	

		
		$db->query("update {$_pre}homepage  set `friendlink`='$conf[friendlink]' where rid='$rsdb[rid]'");
		refreshto("?uid=$uid&atn=$atn","添加成功");
	}
	
	if($del){
		
		$conf[friendlink]=explode(",",$conf[friendlink]);
		foreach($conf[friendlink] as $k){
			if($k==$del){
				$k=false;
			}else{
				$kk[]=$k;
			}
		}	
		$conf[friendlink]=implode(",",$kk);	
		$db->query("update {$_pre}homepage  set `friendlink`='$conf[friendlink]' where rid='$rsdb[rid]'");
		refreshto("?uid=$uid&atn=$atn","删除成功");
	}
	
	$conf[friendlink]=explode(",",$conf[friendlink]);
	foreach($conf[friendlink] as $k){
		$kk[]="'".$k."'";
	}
	$conf[friendlink]=implode(",",$kk);
	$query=$db->query("select rid,username,uid,title from {$_pre}company where username in(".$conf[friendlink].")");
	while($rs=$db->fetch_array($query)){
		$rs[username_code]=urlencode($rs[username]); //为了删除好
		$list_friendlink[]=$rs;
	}
}elseif($atn=='pic'){
	
		if($edit_psid){
			$rsdb=$db->get_one("select * from {$_pre}homepage_picsort where uid='$lfjuid' and psid='$edit_psid' limit 1");
		}
		$webdb[company_picsort_Max]=$webdb[company_picsort_Max]?$webdb[company_picsort_Max]:10;
		$query=$db->query("select * from {$_pre}homepage_picsort where uid='$lfjuid' order by orderlist desc limit 0,$webdb[company_picsort_Max];");
		while($rs=$db->fetch_array($query)){
			$listdb[]=$rs;
		}
		
}elseif($atn=='save_picsort'){
	$name=htmlspecialchars($name);
	$remarks=htmlspecialchars($remarks);
	$orderlist=intval($orderlist);
	if(strlen($name)<2 || strlen($name)>16)showerr("图集只能名称只能是1-8个汉字");
	if(strlen($remarks)>100)showerr("描述最多50个字");
	if($psid){ //更新
		$db->query("update `{$_pre}homepage_picsort` set 
		`name`='$name',
		`remarks`='$remarks',
		`orderlist`='$orderlist'
		where psid='$psid'");
	}else{ //添加
		$webdb[company_picsort_Max]=$webdb[company_picsort_Max]?$webdb[company_picsort_Max]:10;
		$mypicsortnum=$db->get_one("select count(*) as num from {$_pre}homepage_picsort where uid='$lfjuid' ");
		if($mypicsortnum[num]>=$webdb[company_picsort_Max])	showerr("您的图集数量已经到最大数目{$webdb[company_picsort_Max]}");	
		
		$db->query("INSERT INTO `{$_pre}homepage_picsort` ( `psid` , `psup` , `name` , `remarks` , `uid` , `username` , `rid` , `level` , `posttime` , `orderlist` ) 
VALUES ('', '0', '$name', '$remarks', '$lfjuid', '$lfjid', '{$rsdb[rid]}', '0', '$timestamp', '$orderlist');");
	}
	refreshto("?uid=$uid&atn=pic","保存成功");
	
}elseif($atn=='del_picsort'){
	if(!$psid) showerr("操作失败");
	
	$mypics=$db->get_one("select count(*) as num from {$_pre}homepage_pic where psid='$psid'");
	if($mypics[num]>0) showerr("非空图集，不能删除");
	
	$db->query("delete from {$_pre}homepage_picsort where psid='$psid' limit 1");
	refreshto("?uid=$uid&atn=pic","删除成功");
}elseif($atn=='piclist'){

	if(!$psid) showerr("操作失败");
	$rsdb=$db->get_one("select * from {$_pre}homepage_picsort where psid='$psid' limit 1");
	if(!$rsdb) showerr("系统错误");
	$rows=14;
	if($page<1){
		$page=1;
	}
	$min=($page-1)*$rows;
	$query=$db->query("select * from {$_pre}homepage_pic where uid='$lfjuid' and psid='$psid' order by orderlist desc limit $min,$rows;");
	while($rs=$db->fetch_array($query)){
			$rs[posttime]=date("Y-m-d H:i:s",$rs[posttime]);
			$rs[url]=$webdb[www_url]."/".$user_picdir.$rs[uid]."/".$rs[url];
			$listdb[]=$rs;
	}
	$showpage=getpage("{$_pre}homepage_pic"," where uid='$lfjuid' and psid='$psid'","?atn=$atn&uid=$uid&psid=$psid",$rows);

}elseif($atn=='pic_upload'){	
	
	$webdb[company_picsort_Max]=$webdb[company_picsort_Max]?$webdb[company_picsort_Max]:10;
	$query=$db->query("select * from {$_pre}homepage_picsort where uid='$lfjuid' order by orderlist desc limit 0,$webdb[company_picsort_Max];");
	while($rs=$db->fetch_array($query)){
			$listdb[]=$rs;
	}
	$webdb[company_upload_max]=$webdb[company_upload_max]?$webdb[company_upload_max]:8;
	$webdb[company_uploadnum_max]=$webdb[company_uploadnum_max]?$webdb[company_uploadnum_max]:100;
	
	@extract($db->get_one("select count(*) as myuploadedpicnum from {$_pre}homepage_pic where uid='$lfjuid';"));
	
	
}elseif($atn=='pic_upload_save'){	

	$psids="psid_".$ind;
	$psid=$$psids;

	
	if($psid){
	
		if(is_uploaded_file($_FILES[postfile][tmp_name])){
			$array[name]=is_array($postfile)?$_FILES[postfile][name]:$postfile_name;
			$title=$title?$title:$array[name];
			$myname_str=explode(".",strtolower($array[name]));
			$myname=$myname_str[(count($myname_str)-1)];
			if(!in_array($myname,array('gif','jpg'))) $msg="{$array[name]}图片只能是gif或者jpg的格式";		
			$array[path]=$user_picdir.$lfjuid;//商家图片另存
			$array[size]=is_array($postfile)?$_FILES[postfile][size]:$postfile_size;
			$webdb[company_uploadsize_max]=$webdb[company_uploadsize_max]?$webdb[company_uploadsize_max]:100;
			if($array[size]>$webdb[company_uploadsize_max]*1024)	$msg="{$array[name]}图片超过最大{$webdb[company_uploadsize_max]}K限制";
			
			if($msg==''){
				$picurl=upfile(is_array($postfile)?$_FILES[postfile][tmp_name]:$postfile,$array);
				//供应或求购生成缩略图
				
				if($picurl){
						
						$Newpicpath=PHP168_PATH."$array[path]/{$picurl}.gif";
						gdpic(PHP168_PATH."$array[path]/$picurl",$Newpicpath,120,120);
						if(!file_exists($Newpicpath)){
							copy(PHP168_PATH."$array[path]/{$picurl}",$Newpicpath);
						}else{
							$picurl=$picurl;
						}
						$msg="{$array[name]}上传成功";
						$title=get_word($title,32);
						$db->query("INSERT INTO `{$_pre}homepage_pic` ( `pid` , `psid` , `uid` , `username` , `rid` , `title` , `url` , `level` , `yz` , `posttime` , `isfm` , `orderlist`  ) VALUES ('', '$psid', '$lfjuid', '$lfjid', '$rsdb[rid]', '$title', '$picurl', '0', '{$webdb[auto_userpostpic]}', '$timestamp', '0', '0');");
						
				}else{
					$msg="{$array[name]}上传失败，请稍候再试。";
				}
			}	
			
			//插入数据库哦
	
		
		}else{
			$msg="不是要上传的文件,跳过上传";
		}

	}else{
		$msg="请先选择一个图集"; 
	}
	echo "<SCRIPT LANGUAGE=\"JavaScript\">
			<!--
			parent.document.getElementById(\"userpostpic_$ind\").innerHTML='<strong>[{$ind}]</strong> $msg';
			parent.userpost_pic_do(".($ind+1).");			
			//-->
			</SCRIPT>";exit;
			
}elseif($atn=='pic_edit'){	

	if(count($pids)<1) showerr("至少选择一项");
	$pids=implode(",",$pids);
	$query=$db->query("select * from {$_pre}homepage_pic where pid in($pids) order by orderlist desc");
	while($rs=$db->fetch_array($query)){
		$rs[posttime]=date("Y-m-d H:i:s",$rs[posttime]);
		$rs[url]=$webdb[www_url]."/".$user_picdir.$rs[url];
		$listdb[]=$rs;
	}
}elseif($atn=='pic_edit_save'){	

	if(count($pids)<1) showerr("至少选择一项");
	foreach($pids as $pid){
		if($pid){
			//执行
			$db->query("update {$_pre}homepage_pic set title='".get_word(htmlspecialchars($title[$pid]),32)."',orderlist='".intval($orderlist[$pid])."' where pid='$pid' limit 1");
		}
	}
	refreshto("?atn=piclist&psid=$psid&uid=$uid","保存成功");
		
}elseif($atn=='pic_del'){	
	
	if(count($pids)<1) showerr("至少选择一项");
	foreach($pids as $pid){
		if($pid){
			$rt=$db->get_one("select url from {$_pre}homepage_pic where pid='$pid'");
			@unlink(PHP168_PATH.$user_picdir.$lfjuid."/".$rt[url]);
			@unlink(PHP168_PATH.$user_picdir.$lfjuid."/".$rt[url].".gif");
			$db->query("delete from {$_pre}homepage_pic where pid='$pid'");
		}
	}
	refreshto("?atn=piclist&psid=$psid&uid=$uid","删除成功");

}elseif($atn=='pic_fm'){	
	if(count($pids)<1) showerr("请选择一张图片");
	if(!$psid) showerr("请指定一个图集");
	foreach($pids as $pid){
		if($pid){
			$rt=$db->get_one("select url from {$_pre}homepage_pic where pid='$pid'");
			$db->query("update {$_pre}homepage_picsort set faceurl='$rt[url]' where psid='$psid'");
			break;
		}
	}
	refreshto("?atn=piclist&psid=$psid&uid=$uid","设置成功");
	
	
}elseif($atn=='gz'){ //设置公章
		
	
		if(!$step){
			$conf=$db->get_one("select * from {$_pre}company where uid='$uid' limit 1");
			if(!$conf[rid]) showerr("商家信息未登记");
			$conf[gz_show]=$webdb[www_url]."/".$webdb[updir]."/{$Imgdirname}/gz/".$conf[gz];
		}else{
				//图片处理
			if($del_gz==1){
				@unlink(PHP168_PATH.$webdb[updir]."/{$Imgdirname}/gz/".$conf[oldfile]);
				$gz="";
			}else{
				$gz=$conf[oldfile];
				if(is_uploaded_file($_FILES[postfile][tmp_name])){
					$array[name]=is_array($postfile)?$_FILES[postfile][name]:$postfile_name;
					$array[path]=$webdb[updir]."/{$Imgdirname}/gz/";
					if(!is_dir($array[path])) @mkdir($array[path]);
					$array[size]=is_array($postfile)?$_FILES[postfile][size]:$postfile_size;
					if($array[size]>2*1024*1024) showerr("图片文件不能超过2M");
					$picurl=upfile(is_array($postfile)?$_FILES[postfile][tmp_name]:$postfile,$array);
					if($picurl){//更换图片
																
						$Newpicpath=PHP168_PATH."$array[path]/{$picurl}.gif";
						gdpic(PHP168_PATH."$array[path]/$picurl",$Newpicpath,120,120);
						if(!file_exists($Newpicpath)){
							showerr('您上传的图片无法制作公章，请重新处理您的公章图片');
						}else{
							$gz=$picurl.".gif";
							@unlink(PHP168_PATH.$array[path].$picurl);
							@unlink(PHP168_PATH.$array[path].$conf[oldfile]);
						}
						
						
					}		
				}
				
				
			}
			
			$db->query("update {$_pre}company set	`gz`='$gz'	where rid='$conf[rid]'");
			
		    refreshto("?uid=$uid&atn=$atn","设置成功");
			
		}
}


require(dirname(__FILE__)."/"."head.php");
require(dirname(__FILE__)."/"."template/homepage_ctrl.htm");
require(dirname(__FILE__)."/"."foot.php");

?>