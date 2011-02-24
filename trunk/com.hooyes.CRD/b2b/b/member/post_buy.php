<?php
require(dirname(__FILE__)."/../global.php");
require_once(dirname(__FILE__)."/../bd_pics.php");
require(Mpath.'inc/categories.php');

if(!$Admin){
		if(!$lfjuid || !$lfjid){ showerr("你还没有登录,请先登陆");}
}

$ctype = 2;

$rt=$db->get_one("SELECT uid,rid,title FROM `{$_pre}company` c INNER JOIN `{$_pre}company_fid` cf ON c.rid = cf.cid WHERE uid='$lfjuid'");
if(!$rt[rid]){
	if($lfjuid)	showerr("抱歉，发布产品之前，请先完善商家资料,<a href='$Murl/member/homepage_ctrl.php?atn=info'> <strong>点此</strong> </a>完善");
	else  showerr("你还没有登录,请先登陆");
}

//商家配置文件

$homepage=$db->get_one("SELECT * FROM {$_pre}homepage WHERE rid='$rt[rid]' LIMIT 1");
if(!$homepage[hid]) { //激活商家信息
	showerr("您的商铺还没有激活，点击这里 [ <a href='$Mdomain/myhomepage.php' target='_blank'>激活</a> ]");
}

//TEMP 
$webdb[upfileMaxSize]=$webdb[postfileMaxSize]?$webdb[postfileMaxSize]:500;

//没有动作只是就默认发布内容
if(!$action){

		if(!$lfjuid || !$lfjid){ showerr("你还没有登录,请先登陆");}

	
	
		//得到我选择过的分类
		if(count($userpost_fid_history) < 11 && !isset($userpost_fid_history[$fid]))
		set_cookie('userpost_fid_history['. $fid .']', 1);
	
		$bcategory->cache_read();
		

		
		//$province_fid=select_where("province","'postdb[province_id]'  onChange=\"choose_where(this.options[this.selectedIndex].value,'','')\" ");	
		//$fid_all=getFidAll($fid);
		//$guidefid=GuideFid($fid_all,"list.php?ctype=$ctype");
		
		//得到我的分类
		$ms_id_num=0;
		$webdb[maxMysort]=$webdb[maxMysort]?$webdb[maxMysort]:10;
		$query=$db->query("select * from {$_pre}mysort where uid='$lfjuid' and ctype='$ctype' order by listorder desc;");
		while($rs=$db->fetch_array($query)){
			 $ms_id_options.="<option value='$rs[ms_id]'>$rs[sortname]</option> \r\n";
			 $ms_id_num++;
		}

		//得到参数表单
		$Parameters_postform=parameters_postform($fid);

		
	
		//得到品牌
		$select_brand=select_brand('postdb[bid]','postdb[bid]');
	

	
	
//添加内容
}elseif($action=='add'){

	if(!$lfjuid || !$lfjid){ showerr_post("你还没有登录,请先登陆");}

	
	if(!$fid) showerr_post("类目不能为空，请重新选择类目");

	if(is_array($Fid_db[$fid])){
		 showerr_post("当前分类还有子分类，请选择最详细的分类");
	}
	
	
	//记录选择过的分类
	$userpost_fid_history=get_cookie("userpost_fid_history"); 
	if(strpos($userpost_fid_history,$fid)===false)	$userpost_fid_history=$fid.",".$userpost_fid_history;
	$userpost_fid_history=explode(",",$userpost_fid_history);
	$userpost_fid_history=count($userpost_fid_history)>10?array_slice($userpost_fid_history,10):$userpost_fid_history;
	$userpost_fid_history=implode(",",$userpost_fid_history);
	set_cookie('userpost_fid_history',$userpost_fid_history);
	
	$bcategory->cache_read();

	//非图片数据处理

		//检查数据
		//print_r($_POST);exit;
		if(!$fid) showerr_post("请选择类目");
		
		//if(!$fid_all)$fid_all=getFidAll($fid);
		
		//if(!$fid_all)showerr_post("不可以在此类目下发布内容");
		
		if(!$postdb[title]) showerr_post("请输入求购标题");

		if(!$postdb[overtime]) showerr_post("有效期必须选择");
		
		foreach($postdb as $key=>$val){//全部数据处理
			if($key!='content')	$postdb[$key]=ReplaceHtmlAndJs($val);
		}
	
	
	//}
	
	//图片处理
	if(is_uploaded_file($_FILES[postfile][tmp_name])){
		$array[name]=is_array($postfile)?$_FILES[postfile][name]:$postfile_name;
		
		if($ctype==3)$array[path]=$webdb[updir]."/{$Imgdirname}/ico/";//商家图片另存
		else $array[path]=$webdb[updir]."/{$Imgdirname}/";
		
		$array[size]=is_array($postfile)?$_FILES[postfile][size]:$postfile_size;
		
		if(($array[size]/1024) > $webdb[postfileMaxSize]){
			showerr_post("您上传的缩略图文件超过{$webdb[postfileMaxSize]}K限制，请处理后再上传。");
		}
		$picurl=upfile_func2(is_array($postfile)?$_FILES[postfile][tmp_name]:$postfile,$array);
		if(substr($picurl,0,3)=='ERR')	{
			showerr_post(str_replace("ERR-","",$picurl));	
		}
				
		if($picurl){//更换图片
						@unlink(PHP168_PATH.$array[path].$oldfile);
		}else{
						$picurl=$oldfile;
		}
		//供应或求购生成缩略图
		if($picurl){
				$Newpicpath=PHP168_PATH."$array[path]/{$picurl}.gif";
				gdpic(PHP168_PATH."$array[path]/$picurl",$Newpicpath,120,120);
				if(!file_exists($Newpicpath)){
					copy(PHP168_PATH."$array[path]/{$picurl}",$Newpicpath);
				}else{
					$picurl=$picurl;
				}
		}
		
		
	}else{
		$picurl="";
	}
	
	//品牌处理
	if($postdb[bid]){
		$postdb[brandname]="";
	}else{
		if($postdb[brandname]){
			if(strlen($postdb[brandname])>20){
				showerr_post("输入的品牌名称不能超过10个汉字，20个字母");
			}
		}
	}
	
	//自己分类处理
	if($ms_id){
		//new_sortname
		$ms_id = intval($ms_id);
		if($ms_id=='new'){
			if(!$new_sortname || strlen($new_sortname)>20){
				showerr_post("我的分类名称不能为空，且小于20个字符");
			}
			$db->query("INSERT INTO `{$_pre}mysort` ( `ms_id` , `uid` , `sortname` , `fup` , `listorder` , `ctype` , `hits` , `best` )
	VALUES ('', '$lfjuid', '$new_sortname', '$fup', '$listorder', '$ctype', '0', '0');");
			$ms_id=$db->insert_id();
		}
	}

	//写入数据库
	$postdb[yz]=$webdb[postauto_yz]?$webdb[postauto_yz]:0;
	$yz=$postdb[yz];
	
		$db->query("INSERT INTO `{$_pre}content_buy` SET
			title = '$postdb[title]',
			mid = '$fidDB[mid]',
			province_id = '$postdb[province_id]',
			albumname = '',
			fid = '$fid',
			ms_id = '$ms_id',
			fname = '{$bcategory->categories[$fid]['name']}',
			bid = '$postdb[bid]',
			brandname = '$postdb[brandname]',
			info = '',
			hits = '',
			comments = '',
			posttime = '$timestamp',
			list = '$postdb[list]',
			uid = '$lfjdb[uid]',
			username = '$lfjdb[username]',
			titlecolor = '',
			fonttype = '',
			picurl = '$picurl',
			ispic = '$postdb[ispic]',
			yz = '$postdb[yz]',
			yzer = '',
			yztime = '',
			levels = '',
			levelstime = '',
			keywords = '$postdb[keywords]',
			jumpurl = '$postdb[jumpurl]',
			iframeurl = '$postdb[iframeurl]',
			style = '$postdb[style]',
			head_tpl = '$postdb[head_tpl]',
			main_tpl = '$postdb[main_tpl]',
			foot_tpl = '$postdb[foot_tpl]',
			target = '$postdb[target]',
			ishtml = '$postdb[ishtml]',
			ip = '$onlineip',
			lastfid = '0',
			money = '$postdb[money]',
			passwd = '$postdb[passwd]',
			editer = '',
			edittime = '',
			begintime = '$postdb[begintime]',
			endtime = '$postdb[endtime]',
			config = '',
			lastview = '$postdb[lastview]',
			city_id = '$postdb[city_id]',
			zone_id = '$postdb[zone_id]',
			street_id = '$postdb[street_id]',
			editpwd = '$postdb[editpwd]',
			showday = '$postdb[showday]',
			telephone = '$postdb[telephone]',
			mobphone = '$postdb[mobphone]',
			email = '$postdb[email]',
			oicq = '$postdb[oicq]',
			msn = '$postdb[msn]',
			maps = '$postdb[maps]',
			owner =  '$owner',
			ctype = '$ctype',
			my_price = '$postdb[my_price]'
		");
		$id=$db->insert_id();
		
		if($id){
			$db->query("INSERT INTO `{$_pre}content_2` ( `id` , `fid` , `uid` , `content` , `overtime` , `quantity_num` , `quantity_type` ) VALUES (
 '$id', '$fid', '$lfjuid', '$postdb[content]', '$postdb[overtime]', '$postdb[quantity_num]', '$postdb[quantity_type]');");
			//更新绑定图片	
			
			$db->query("INSERT INTO {$_pre}buy_fid VALUES($id, $fid)");
			
			bd_pics("{$_pre}content_buy"," where id='$id' ");
		}
		//参数模型处理
		parameters_savedata($fid,$id);
		//发送信息给供应商
		postmsgtomy('vendor',$id);	
		//奖励积分
		if($webdb[post_add_money]) plus_money($lfjuid,$webdb[post_add_money]);
		//完成
		parent_goto("?action=ok2&fid=$fid&id=$id","");//操作成功
		exit;
	
	//}
	
//发布成功
}elseif($action=='ok'){

		
		$msg="恭喜您，发布成功啦!";
		$do[0]['text']="点击查看详情";$do[0]['target']=" target=_blank";
		$do[0]['link']="$Mdomain/buy_bencandy.php?fid=$fid&id=$id";

		$do[1]['text']="点击继续发布新商品";
		$do[1]['link']="?ctype=$ctype";
		
		$do[2]['text']="点击进入我的主页";$do[2]['target']=" target=_blank";
		$do[2]['link']="$Mdomain/homepage.php?uid=".$lfjuid;
		
}elseif($action=='ok2'){

		$msg="恭喜您，发布成功啦!";
		$do[0]['text']="点击查看详情";$do[0]['target']=" target=_blank";
		$do[0]['link']="$Mdomain/buy_bencandy.php?fid=$fid&id=$id";
		
		
		$do[1]['text']="点击继续发布新求购";
		$do[1]['link']="?ctype=$ctype";
		
		$do[2]['text']="点击进入我的主页";$do[2]['target']=" target=_blank";
		$do[2]['link']="$Mdomain/homepage.php?uid=".$lfjuid;


}elseif($action=='del'){
	
	if(!$id || !is_numeric($id)) showerr("数据出错，禁止访问");
	$rsdb=$db->get_one("select * from `{$_pre}content_buy` where id='$id';");
	if(!$rsdb) showerr("ID不存在");
	if(!$fromadmin){
		if($rsdb[uid]!=$lfjuid && !$Admin) showerr("你们没有权限修改");
	}
	//删除静态
	if(file_exists(PHP168_PATH.$rsdb[htmlname])){
		@unlink(PHP168_PATH.$rsdb[htmlname]);
	}
	//删除参数
	parameters_deldata($id);
	extract($rsdb);
	if($db->query("DELETE FROM `{$_pre}content_2` where id='$id'")){
		$db->query("DELETE FROM `{$_pre}content_buy` where id='$id'");
		$db->query("DELETE FROM `{$_pre}buy_fid` where id='$id'");
		@unlink(PHP168_PATH.$webdb[updir]."/{$Imgdirname}/".$picurl);
		@unlink(PHP168_PATH.$webdb[updir]."/{$Imgdirname}/".$picurl.".gif");
	}

	//if(!$fromadmin){
	//	refreshto("$Mdomain/list.php?fid=$fid&ctype=$ctype","删除成功");
	//}else{
		refreshto($FROMURL,"删除成功 ");
	//}
	exit;	
	
}elseif($action=='edit'){
	
	if(!$id || !is_numeric($id))showerr("数据出错，禁止访问");
	$rsdb=$db->get_one("SELECT * FROM `{$_pre}content_buy` WHERE id='$id';");
	if(!$rsdb) showerr("ID不存在");
	if($rsdb[uid]!=$lfjuid && !$Admin) showerr("你们没有权限修改");
	if($fid){ 
		//重新选择FID后
		$rsdb[fid]=$fid;
	}
	extract($rsdb);
	
	$bcategory->cache_read();
		
	$parents = $bcategory->get_parents($fid);
	$guidefid = '';

	foreach($parents as $v) $guidefid .= ' &gt; '. $v['name'];
	$guidefid .= ' &gt; '. $bcategory->categories[$rsdb['fid']]['name'];
	
	$picurl_show=getimgdir($picurl,$ctype);
	
	//得到详细内容
	$baseinfo=$db->get_one("SELECT * FROM `{$_pre}content_2` WHERE id='$id'");
	$rsdb[sent_limit]['$baseinfo[sent_limit]']=" selected";
	$rsdb=array_merge($rsdb,$baseinfo);

	//得到我的分类
	$ms_id_num=0;
	$webdb[maxMysort]=$webdb[maxMysort]?$webdb[maxMysort]:10;
	$query=$db->query("SELECT * FROM {$_pre}mysort WHERE uid='$rsdb[uid]' AND ctype='$ctype' ORDER BY listorder DESC");
	while($rs=$db->fetch_array($query)){
			 $sel=$rs[ms_id]==$rsdb[ms_id]?" selected":"";
			 $ms_id_options.="<option value='$rs[ms_id]' $sel>$rs[sortname]</option> \r\n";
			 $ms_id_num++;
	}
	
	//得到参数表单
	$Parameters_postform=parameters_postform($fid,$id);
	//得到品牌
	$select_brand=select_brand('postdb[bid]','postdb[bid]',$rsdb[bid],0); //$fid  暂时显示全部品牌，0替换为$fid即可

}elseif($action=='edit_save'){
	
	if(!$id || !is_numeric($id))showerr_post("数据出错，禁止访问");
	$rsdb=$db->get_one("select * from `{$_pre}content_buy` where id='$id';");
	if(!$rsdb) showerr_post("ID不存在");
	if($rsdb[uid]!=$lfjuid &&!$Admin) showerr_post("你们没有权限修改");
	$ctype=$rsdb['ctype'];

	
	if(!$fid) showerr_post("请选择类目");
	
	$bcategory->cache_read();
	
	//if(!$fid_all)$fid_all=getFidAll($fid);
	//if(!$fid_all)showerr_post("不可以在此类目下发布内容");
	if(!$postdb[title]) showerr_post("请输入商品全称");
	if(strlen($postdb[title])>64) showerr_post("标题过长了");
	if($postdb[my_price]) $postdb[my_price] = formartprice($postdb[my_price]);
	
		
		if(!$postdb[overtime]) showerr_post("有效期必须选择");		
		foreach($postdb as $key=>$val){//全部数据处理
			if($key!='content')	$postdb[$key]=ReplaceHtmlAndJs($val);
		}
		//图片处理
	if(is_uploaded_file($_FILES[postfile][tmp_name])){
		$array[name]=is_array($postfile)?$_FILES[postfile][name]:$postfile_name;
		$array[path]=$webdb[updir]."/{$Imgdirname}/";
		$array[size]=is_array($postfile)?$_FILES[postfile][size]:$postfile_size;
		$picurl=upfile_func2(is_array($postfile)?$_FILES[postfile][tmp_name]:$postfile,$array);
		if(substr($picurl,0,3)=='ERR')	{
			showerr_post(str_replace("ERR-","",$picurl));	
		}
		if($picurl){//更换图片
						@unlink(PHP168_PATH.$array[path].$oldfile);
						@unlink(PHP168_PATH.$array[path].$oldfile.".gif");
		}else{
						$picurl=$oldfile;
		}
		//供应或求购生成缩略图
		if($picurl && ($ctype==1 || $ctype==2)){
				$Newpicpath=PHP168_PATH."$array[path]/{$picurl}.gif";
				gdpic(PHP168_PATH."$array[path]/$picurl",$Newpicpath,120,120);
				if(!file_exists($Newpicpath)){
					copy(PHP168_PATH."$array[path]/{$picurl}",$Newpicpath);
				}else{
					$picurl=$picurl;
				}
		}
		//商家LOGO
		//编辑无商家
	}else{
		$picurl=$oldfile;
	}
	
	//品牌处理
	if($postdb[bid]){
		$postdb[brandname]="";
	}else{
		if($postdb[brandname]){
			if(strlen($postdb[brandname])>20){
				showerr_post("输入的品牌名称不能超过10个汉字，20个字母");
			}
		}
	}

	//自己分类处理
	if($ms_id){
		//new_sortname
		if($ms_id=='new'){
			if(!$new_sortname || strlen($new_sortname)>20){
				showerr_post("我的分类名称不能为空，且小于20个字符");
			}
			$db->query("INSERT INTO `{$_pre}mysort` ( `ms_id` , `uid` , `sortname` , `fup` , `listorder` , `ctype` , `hits` , `best` )
	VALUES ('', '$lfjuid', '$new_sortname', '$fup', '$listorder', '$ctype', '0', '0');");
			$ms_id=$db->insert_id();
		}
	}
	
	//处理数据
	$db->query("UPDATE `{$_pre}content_buy` SET
	`title`='$postdb[title]',
	`fid`='$fid',
	`fname`='{$bcategory->categories[$fid]['name']}',
	`ms_id`='$ms_id',
	`bid`='$postdb[bid]',
	`brandname`='$postdb[brandname]',
	`picurl`='$picurl',
	`my_price`='$postdb[my_price]',
	`posttime`='".$timestamp."'
	WHERE id='$id'");
	extract($postdb);
	
	//更新绑定图片	
	bd_pics("{$_pre}content_buy"," WHERE id='$id' ");
	
		$db->query("UPDATE `{$_pre}content_2` SET 
		`fid`='$fid',
		`content`='$content',
		`overtime`='$overtime',
		`quantity_num`='$quantity_num',
		`quantity_type`='$quantity_type'
		WHERE id='$id'");
		
		$db->query("UPDATE {$_pre}sell_fid SET fid = $fid WHERE id = $id");
		
		$backurl="$Murl/member/buylist.php";
	//}
	
	//参数模型处理
	parameters_savedata($fid,$id);

	parent_goto($backurl,"已成功保存");//编辑成功
	exit;	
	


//更新发布时间
}elseif($action=="updateposttime"){
	
	if(!$id && !is_numeric($id)) showerr("数据出错，禁止访问");
	$rsdb=$db->get_one("select * from `{$_pre}content_buy` where id='$id';");
	if(!$rsdb) showerr("ID不存在");
	if($rsdb[uid]!=$lfjuid) showerr("你们没有权限操作");
	$db->query("update `{$_pre}content_buy` set
	`posttime`='".$timestamp."'
	where id='$id'");
	
	refreshto($FROMURL,"更新成功 ");
}



//postmsgtomy发信息
function postmsgtomy($type='buyer',$id){
	global $db,$webdb,$lfjuid,$lfjid,$_pre,$Mdomain;
	
	$rsdb=$db->get_one("select * from {$_pre}content_buy where id='$id'");	
	if($type=='buyer'){
		
		$myinfo=$db->get_one("select * from {$_pre}company where uid='$lfjuid' limit 1");
		
		if(!$myinfo) return false;
		
		$query=$db->query("select * from {$_pre}vendor where uid='$lfjuid' and yz=1");
		while($rs=$db->fetch_array($query)){
			//站内信
			$title="【供应】$rsdb[title]($myinfo[title])";
			$content="本公司最新发布了供应信息：$rsdb[title] ，点击链接查看详情；
$Mdomain/buy_bencandy.php?id=$rsdb[id]&fid=$rsdb[fid]
点此询价:
$Mdomain/action.php?action=form1&ids=$rsdb[id]
				
--------此信息来自：$myinfo[title] $Mdomain/homage.php?uid=$lfjuid 
($webdb[webname] $webdb[Info_webname] $webdb[www_url]/$Mdomain)";
			
			$array[touid]=$rs[owner_uid];
			$array[fromuid]=$rs[uid];
			$array[fromer]=$rs[username];
			$array[title]=filtrate($title);
			$array[content]=filtrate($content);
			pm_msgbox($array);
			//邮件
			easy_sent_email($rs[owner_uid],$title,$content);
			
			
		}
		
	}elseif($type=='vendor'){
		
		$myinfo=$db->get_one("select * from {$_pre}company where uid='$lfjuid' limit 1");
		
		if(!$myinfo) return false;
		
		$query=$db->query("select * from {$_pre}vendor where owner_uid='$lfjuid' and yz=1");
		while($rs=$db->fetch_array($query)){
			//站内信
			$title="【供应】$rsdb[title]($myinfo[title])";
			$content="本公司最新发布了求购信息：$rsdb[title] ，点击链接查看详情；
$Mdomain/buy_bencandy.php?id=$rsdb[id]&fid=$rsdb[fid]
点此报价:
$Mdomain/action.php?action=form2&ids=$rsdb[id]
				
------此信息来自：$myinfo[title] $Mdomain/homage.php?uid=$lfjuid 
($webdb[webname] $webdb[Info_webname]  $webdb[www_url]/$Mdomain)";
			
			$array[touid]=$rs[uid];
			$array[fromuid]=$rs[owner_uid];
			$array[fromer]=$rs[owner_username];
			$array[title]=filtrate($title);
			$array[content]=filtrate($content);
			pm_msgbox($array);
			//邮件
			easy_sent_email($rs[uid],$array[title],$array[content]);
			
			
			
		}
		
		
	}
	
}
function showerr_post($msg,$html_id=''){
		echo "<SCRIPT LANGUAGE=\"JavaScript\">
			<!--
			alert(\"$msg\");
			parent.document.getElementById('post_showmsg').innerHTML=\"<strong>$msg</strong>\";	
			parent.document.getElementById('postSubmit').disabled=false;	
			//-->
			</SCRIPT>";exit;
}
function parent_goto($url,$msg=''){

	//生成html
	@file_get_contents($url."&makehtml=back");

	echo "<SCRIPT LANGUAGE=\"JavaScript\">
			<!--
			";
	if($msg!=''){
		echo "alert('$msg');";
	}
	echo    "
			
			parent.location='$url';	
			parent.location.href='$url';	
		
			//-->
			</SCRIPT>";exit;
}

$member_style=$webdb[sys_member_style]?$webdb[sys_member_style]:($webdb[member_style]?$webdb[member_style]:"images2");

		
$bcategory->cache_read();
$categories = $bcategory->unsets(true);

require(dirname(__FILE__)."/"."head.php");
//require(getTpl("post_".$ctype));
require(dirname(__FILE__)."/"."template/post_2.htm");
require(dirname(__FILE__)."/"."foot.php");

exit;
require(Mpath."inc/head.php");
require(Mpath."inc/foot.php");
?>