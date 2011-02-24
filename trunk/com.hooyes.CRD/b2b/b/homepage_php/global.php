<?php

//各种动作
if($action=="msg_post"){ //留言
	//检测多少时间内不能重复留言
	$Omsg=$db->get_one("select max(posttime) as posttime  from {$_pre}homepage_guestbook` where cuid='$uid' and uid='$lfjuid' ");
	//echo "select max(posttime)  from {$_pre}homepage_guestbook` where cuid='$uid' and uid='$lfjuid' ";
	//print_r($Omsg);
	//echo "<hr>".time();
	//echo "<hr>".(intval($Omsg[posttime]) + 6);
	
	if($Omsg[posttime]){
		if( intval($Omsg[posttime]) + 60 > time() ){
			showerr("1分钟内不能再次留言");
		}
	}
	//
	if(!$content){
		showerr("内容不能为空");
	}
	if(strlen($content)>1000){
		showerr("内容不能超过500个字");
	}
	$content=filtrate($content);
	$yz=1;
	$db->query("INSERT INTO `{$_pre}homepage_guestbook` (`cuid`,  `uid` , `username` , `ip` , `content` , `yz` , `posttime` , `list` ) 
	VALUES (
	'$uid','$lfjuid','$lfjid','$onlineip','$content','$yz','".time()."','".time()."')
	");
	refreshto("?m=msg&uid=$uid&page=$page","谢谢你的留言",1);

}elseif($action=="msg_delete") //删除留言
{
	if($web_admin){
		$db->query("DELETE FROM `{$_pre}homepage_guestbook` WHERE id='$id'");
	}else{
		$db->query("DELETE FROM `{$_pre}homepage_guestbook` WHERE id='$id' AND (uid='$lfjuid' OR cuid='$lfjuid' )");
	}
	refreshto("?m=msg&uid=$uid&page=$page","删除成功",0);


/*
* 申请成为某家单位的供应商!
* 参数是：某家单位的.uid,username
* 条件。必须登陆
* 添加后跳转
*/
}elseif($action=='add_vendor'){
	$owner_uid=intval($owner_uid);
	if(!$owner_uid || !$owner_username || !$owner_rid){
		showerr("操作失败，请稍后重试！");exit;
	}
	if($owner_uid==$uid){
		showerr("操作失败，不能向自己申请！");exit;
	}
	//检测是否已经是供应商了
	$yj=$db->get_one("select count(*) as num from `{$_pre}vendor` where uid='$lfjuid' and owner_uid='$owner_uid'");
	if($yj[num]>0) showerr("您已经是申请过成为对方的供应商了");
	//检测是否自己是审核后商家
	$rsdb=$db->get_one("select * from {$_pre}company where uid='$lfjuid' limit 1");
	if(!$rsdb[rid]) showerr("您还没有登记商家，点此<a href='post.php?ctype=3' style='color:red'>【登记商家】</a>");
	if(!$rsdb[yz]) showerr("您登记的商家信息现在不可用，可能在审核阶段，请稍后再试");
	if($webdb[vendorRenzheng]){
		if(!$rsdb[renzheng]) showerr("您登记的商家信息还没有提供认证信息，不能申请作为别人的供应商,请先在会员中心提供认证资料.");
	}
	//执行
	
	$db->query("INSERT INTO `{$_pre}vendor` ( `vid` , `owner_uid` , `owner_username`,`owner_rid` , `ms_id` , `uid` , `username` , `rid` , `remarks` , `posttime` , `yz` , `yztime` , `levels` ) 
VALUES ('', '$owner_uid', '$owner_username','$owner_rid', '', '$lfjuid', '$lfjid', '$rsdb[rid]', '$remarks', '".$timestamp."', '0', '0', '0');");
	
	$array[touid]=$owner_uid;
	$array[fromuid]=0;
	$array[fromer]='系统消息';
	$array[title]="供应商申请通知($rsdb[title])";
	$array[content]="{$owner_username}您好!<br><br>用户{$lfjid}($rsdb[title]) 已经向您发起供应商资格申请，<a href=$Mdomain/homepage.php?uid=$lfjuid target=_blank>点此查看对方主页</a>；";
	if(function_exists('pm_msgbox')){
		pm_msgbox($array);
	}
	refreshto("?uid=$owner_uid&m=showmsg&t=regvendor","申请成功，等待回应",0);	
	exit;

}elseif($action=='add_vendor2'){

	$gy_uid=intval($gy_uid);
	if(!$gy_uid || !$gy_username || !$gy_rid){
		showerr("操作失败，请稍后重试！");exit;
	}
	if($gy_uid==$uid){
		showerr("操作失败，不能向自己申请！");exit;
	}
	//检测是否已经是采购了
	$yj=$db->get_one("select count(*) as num from `{$_pre}vendor` where uid='$gy_uid' and owner_uid='$lfjuid'");
	if($yj[num]>0) showerr("您已经是申请过成为对方的采购商了");
	
	//检测是否自己是审核后商家
	$rsdb=$db->get_one("select * from {$_pre}company where uid='$lfjuid' limit 1");
	if(!$rsdb[rid]) showerr("您还没有登记商家，点此<a href='post.php?ctype=3' style='color:red'>【登记商家】</a>");
	if(!$rsdb[yz]) showerr("您登记的商家信息现在不可用，可能在审核阶段，请稍后再试");
	if($webdb[vendorRenzheng]){
		if(!$rsdb[renzheng]) showerr("您登记的商家信息还没有提供认证信息，不能申请作为别人的供应商,请先在会员中心提供认证资料.");
	}

	//执行
	
	$db->query("INSERT INTO `{$_pre}vendor` ( `vid` , `owner_uid` , `owner_username`,`owner_rid` , `ms_id` , `uid` , `username` , `rid` , `remarks` , `posttime` , `yz` , `yztime` , `levels` ) 
VALUES ('', '$lfjuid', '$lfjid','$rsdb[rid]', '', '$gy_uid', '$gy_username', '$gy_rid', '$remarks', '".$timestamp."', '1', '$timestamp', '0');");
	
	$array[touid]=$gy_uid;
	$array[fromuid]=0;
	$array[fromer]='系统消息';
	$array[title]="采购商申请通知($rsdb[title])";
	$array[content]="{$gy_username}您好!<br><br>用户{$lfjid}($rsdb[title]) 已经向您发起采购商资格申请，<a href=$Mdomain/homepage.php?uid=$lfjuid target=_blank>点此查看对方主页</a>；";
	if(function_exists('pm_msgbox')){
		pm_msgbox($array);
	}
	refreshto("?uid=$gy_uid&m=showmsg&t=regvendor2","申请成功，等待回应",0);	
	exit;

}


/////////////////////////
//初始变量：
$tpl_left=array(
'base'=>"商家档案",
'sort'=>"信息分类",
'tongji'=>"统计信息",
'news'=>"新闻动态",
'friendlink'=>"友情链接"
);

$tpl_right=array(
'info'=>"商家简介",
'selllist'=>"供应信息",
'buylist'=>"求购信息",
'zh'=>"展会信息",
'hr'=>"人才招聘",
'msg'=>"留 言 本",
'visitor'=>"访客足迹"
);
/*****首页筛选条件****/
//排序,处理时替换_为空格
$myorderby['A.posttime desc']="最新发布在前";
$myorderby['A.posttime asc']="最后发布在前";
$myorderby['B.my_price desc']="价格高在前";
$myorderby['B.my_price asc']="价格低在前";

/***风格存放目录 *****/
$tpl_dir=Mpath."/images/homepage_style/";



$webdb[homepage_banner_size]=$webdb[homepage_banner_size]?$webdb[homepage_banner_size]:80;
$webdb[homepage_ico_size]=$webdb[homepage_ico_size]?$webdb[homepage_ico_size]:50;
$webdb[friendlinkmax]=$webdb[friendlinkmax]?$webdb[friendlinkmax]:20;
//的到风格数组
if(is_dir($tpl_dir))
{

	if ($handle = opendir($tpl_dir)) {
		while (false !== ($file = readdir($handle))) {
			if ($file != "." && $file != "..") {
				if(is_dir($tpl_dir.$file)){
					if(file_exists($tpl_dir.$file."/style.php")){
						@require($tpl_dir.$file."/style.php");
					}
				}
			}
		}
	}
	closedir($handle);	

}else{

	die("网站设置有误，请联系管理员!");
}


//激活商家
function caretehomepage($rsdb){
	global $db,$webdb,$_pre,$tpl_left,$tpl_right,$ctrl,$atn,$timestamp;
	
	foreach($tpl_left as $key=>$val){
		$index_left[]=$key;
	}
	$index_left=implode(",",$index_left);
	
	foreach($tpl_right as $key=>$val){
		if(in_array($key,array('info','selllist','buylist'))){  //控制那些模块可以初始化
			$index_right[]=$key;
		}
	}
	$index_right=implode(",",$index_right);
	
	$listnum=array(
	'selllist'=>10,'buylist'=>10,'guestbook'=>4,'visitor'=>10,'newslist'=>10,'hr'=>10,'zh'=>10,'friendlink'=>10,
	'Mselllist'=>10,'Mbuylist'=>10,'Mguestbook'=>10,'Mvisitor'=>40,'Mnewslist'=>10,'Mhr'=>20,'Mzh'=>20);
	$listnum=serialize($listnum);

	$db->query("INSERT INTO `{$_pre}homepage` ( `hid` , `rid` , `uid` , `username` , `style` , `index_left` , `index_right` ,`listnum`,`banner`, `bodytpl`,`renzheng_show`,`friendlink` , `visitor` ) 
VALUES (
'', '$rsdb[rid]', '$rsdb[uid]', '$rsdb[username]', 'default', '$index_left', '$index_right','$listnum','','left','0', '', '');
");
	

	//初始化图库
	$db->query("INSERT INTO `{$_pre}homepage_picsort` ( `psid` , `psup` , `name` , `remarks` , `uid` , `username` , `rid` , `level` , `posttime` , `orderlist` ) VALUES 
	('', '0', '产品图库', '记录产品多方面图片资料', '$rsdb[uid]', '$rsdb[username]', '{$rsdb[rid]}', '0', '$timestamp', '2'),
	('', '0', '资质说明', '荣誉证书，获奖证书，营业执照', '$rsdb[uid]', '$rsdb[username]', '{$rsdb[rid]}', '0', '$timestamp', '1');   
	");
	
	//跳转
	$url="?uid=$rsdb[uid]";
	

	echo "商家页面激活成功....<META HTTP-EQUIV=REFRESH CONTENT='0;URL=$url'>";
	exit;
}




//类目俄列表
function choose_sort($fid,$class,$ck=0,$ctype)
{
	global $db,$_pre;
	for($i=0;$i<$class;$i++){
		$icon.="&nbsp;&nbsp;&nbsp;&nbsp;";
	}
	$class++;          //AND type=1
	$query = $db->query("SELECT * FROM {$_pre}sort WHERE fup='$fid'   ORDER BY list DESC LIMIT 500");
	while($rs = $db->fetch_array($query)){
		$ckk=$ck==$rs[fid]?' selected ':'';
		$fup_select.="<option value='$rs[fid]' $ckk >$icon|-$rs[name]</option>";
		$fup_select.=choose_sort($rs[fid],$class,$ck,$ctype);
	}
	return $fup_select;
}

?>