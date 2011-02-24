<?php
require_once(dirname(__FILE__)."/global.php");
require_once(dirname(__FILE__)."/../inc/homepage/global.php");
require_once(dirname(__FILE__)."/../bd_pics.php");

$post_company=1;//使得类目选择时可以多选
$lfjdb[money]=get_money($lfjuid);
//$groupdb['allow_get_homepage']=1;	//可以控制哪些用户组可以申请商铺
if(!$uid){
	$uid=$lfjuid;
}

if($action=='apply'){

	$rt=$db->get_one("SELECT * FROM `{$_pre}company` WHERE uid='$lfjuid'");
	if(!$rt){
		showerr("<A HREF='$Murl/member/post_company.php'>请先登记企业信息</A>");
	}elseif($rt[if_homepage]){
		showerr("请不要重复申请");
	}
	if(!$web_admin){
		if(!$groupdb['allow_get_homepage']){
			showerr("很抱歉,你所在用户组不能申请企业商铺,你要申请企业商铺,请先升级你的用户组,<A HREF='$webdb[www_url]/member/buygroup.php?job=list'>升级你的用户组</A>");
		}elseif($lfjdb[money]<$webdb[creat_home_money]){
			showerr("很抱歉,你不能申请企业商铺,因为创建企业商铺需要{$webdb[MoneyName]}{$webdb[creat_home_money]}{$webdb[MoneyDW]},而你的{$webdb[MoneyName]}仅有{$lfjdb[money]}{$webdb[MoneyDW]}<br>你可以选择在线充值来增加你的{$webdb[MoneyName]},<A HREF='/member/money.php?job=list'>立即充值</A>");
		}
	}
	if(!$web_admin&&$webdb[creat_home_money]){
		add_user($lfjuid,-abs($webdb[creat_home_money]),'创建店铺扣分');
	}

	$company=$db->get_one("SELECT * FROM {$_pre}company WHERE uid='$lfjuid' LIMIT 1");
	caretehomepage($company,false);
	refreshto("?action=ok","",0);

}elseif($action=='add'||$action=='edit'){

	if($action=='add')$uid=$lfjuid;
	
	if($postdb[if_homepage]&&!$web_admin){

		if(!$groupdb['allow_get_homepage']){
			showerr_post("很抱歉,你所在用户组不能申请商家主页,你要申请商家主页,请先升级你的用户组");
		}elseif($lfjdb[money]<$webdb[creat_home_money]){
			showerr_post("很抱歉,你不能申请商家主页,因为创建商家主页需要{$webdb[MoneyName]}{$webdb[creat_home_money]}{$webdb[MoneyDW]},而你的{$webdb[MoneyName]}仅有{$lfjdb[money]}{$webdb[MoneyDW]}<br>你可以选择在线充值来增加你的{$webdb[MoneyName]}");
		}		
	}
	$rt=$db->get_one("SELECT * FROM `{$_pre}company` WHERE uid='$uid'");
	if($action=='edit'&&$rt[uid]!=$lfjuid&&!$web_admin){
		showerr('你没权限');
	}
	if($rt[if_homepage]){
		showerr_post("出错了,你已经登记过了!!");
	}
	elseif($rt){
		$action='edit';
	}
	if(count($fids)<1)showerr_post("至少选择一个分类");
	
	//插入分类表
	$ifids = $fnamedb = array();
	foreach($fids as $key){
		$key = intval($key);
		if($key){
			$ifids[] = $key;
			$fnamedb[]=$Fid_db['name'][$key];
		}
	}
	$fname=implode(',',$fnamedb);

	//if(!$postdb[city_id]) showerr_post("请选择市"); 		
		
	if(!$postdb[title]) showerr_post("请输入公司全称");
	if(strlen($postdb[title])<10){
		showerr_post("公司全称小于5个字不规范!");
	}
	if(eregi("^([a-z0-9]+)$",$postdb[title])){
		showerr_post("公司名称不规范!");
	}
	if(!$postdb[qy_regmoney]) showerr_post("请输入公司注册资本");
	if(!$postdb[content]) showerr_post("详细商家介绍不能为空");
	$postdb[content]=nl2br($postdb[content]);
	if(!$postdb[qy_contact_tel]) showerr_post("指定联系人电话不能为空");
	if(!$postdb[qy_contact]) showerr_post("指定联系人不能为空");
	if(!$postdb[qy_contact_email]) showerr_post("指定联系人邮箱地址不能为空");
	foreach($postdb as $key=>$val){//全部数据处理
			$postdb[$key]=ReplaceHtmlAndJs($val);
	}

	if(!ereg("^[-a-zA-Z0-9_\.]+\@([0-9A-Za-z][0-9A-Za-z-]+\.)+[A-Za-z]{2,5}$",$postdb[qy_contact_email])){
		showerr_post('邮箱不符合规则');
	}

	if($postdb[qy_regmoney]<3){
		showerr_post('注册资本不能小于3万');
	}

	if($postdb[qy_website]&&!ereg("^http:",$postdb[qy_website])){
		showerr_post('网址有误');
	}

	//图片处理
	if(is_uploaded_file($_FILES[postfile][tmp_name])){
		if($action=='edit'){
			delete_attachment($uid,tempdir($rt[picurl]));
		}
		$array[name]=is_array($postfile)?$_FILES[postfile][name]:$postfile_name;
		$dirid=ceil($uid/1000);
		$array[path]=$webdb[updir]."/homepage/logo/$dirid/";
				
		$array[size]=is_array($postfile)?$_FILES[postfile][size]:$postfile_size;
		$pic_name=upfile(is_array($postfile)?$_FILES[postfile][tmp_name]:$postfile,$array);
		
		$picurl="homepage/logo/$dirid/{$pic_name}";

		$sizedb=getimagesize(ROOT_PATH."$array[path]/$pic_name");

		if($sizedb[0]>300||$sizedb[1]>300){
			$Newpicpath=ROOT_PATH."$array[path]/logo_{$pic_name}";
			gdpic(ROOT_PATH."$array[path]/{$pic_name}",$Newpicpath,300,225);
			gdpic(ROOT_PATH."$array[path]/{$pic_name}",$Newpicpath.".jpg",225,300);
			gdpic(ROOT_PATH."$array[path]/{$pic_name}",$Newpicpath.".jpg.jpg",300,300);
			gdpic(ROOT_PATH."$array[path]/{$pic_name}",$Newpicpath.".jpg.jpg.jpg",300,100);
			if(file_exists($Newpicpath)){
				delete_attachment($uid,tempdir("homepage/logo/$dirid/{$pic_name}"));
				$picurl="homepage/logo/$dirid/logo_{$pic_name}";
			}
		}else{
			$Newpicpath=ROOT_PATH."$array[path]/{$pic_name}";
			copy(ROOT_PATH."$array[path]/{$pic_name}",$Newpicpath.".jpg");
			copy(ROOT_PATH."$array[path]/{$pic_name}",$Newpicpath.".jpg.jpg");
			copy(ROOT_PATH."$array[path]/{$pic_name}",$Newpicpath.".jpg.jpg.jpg");
		}
		
	}else{
		$picurl=$rt[picurl];
	}

	
	if($action=='edit'){
		$db->query("DELETE FROM {$_pre}company WHERE uid='$rt[uid]'");
		$db->query("DELETE FROM {$_pre}company_fid WHERE uid='$rt[uid]'");
	}
	
	$username=$action=='edit'?$rt[username]:$lfjid;
	$yz=1;
	$db->query("INSERT INTO `{$_pre}company` ( `title` , `fname` , `uid` , `username` , `posttime` , `listorder` , `picurl` , `yz` , `yzer` , `yztime` , `content` , `province_id` , `city_id` ,`zone_id` ,`street_id` , `qy_cate` , `qy_saletype` , `qy_regmoney` , `qy_createtime` , `qy_regplace` , `qy_address` , `qy_postnum` , `qy_pro_ser` , `my_buy` , `my_trade` , `qy_contact`,`qy_contact_zhiwei` , `qy_contact_sex` , `qy_contact_tel` , `qy_contact_mobile` , `qy_contact_fax` , `qy_contact_email` , `qy_website` , `qq` , `msn` , `skype`, `if_homepage` ) 
	VALUES (
	 '$postdb[title]', '$fname', '$uid', '$username', '".$timestamp."', '0', '$picurl', '$yz', '', '".$timestamp."', '$postdb[content]', '$postdb[province_id]', '$city_id','$postdb[zone_id]','$postdb[street_id]', '$postdb[qy_cate]', '$postdb[qy_saletype]', '$postdb[qy_regmoney]', '$postdb[qy_createtime]', '$postdb[qy_regplace]', '$postdb[qy_address]', '$postdb[qy_postnum]', '$postdb[qy_pro_ser]', '$postdb[my_buy]', '$postdb[my_trade]', '$postdb[qy_contact]', '$postdb[qy_contact_zhiwei]', '$postdb[qy_contact_sex]', '$postdb[qy_contact_tel]', '$postdb[qy_contact_mobile]', '$postdb[qy_contact_fax]', '$postdb[qy_contact_email]', '$postdb[qy_website]', '$postdb[qq]', '$postdb[msn]', '$postdb[skype]', '$postdb[if_homepage]');");
	
	$id = $db->insert_id();
	
	foreach($ifids as $v){
		$db->query("INSERT INTO {$_pre}company_fid (uid, fid) VALUES ($uid, $v)");
	}

	//if($action=='add'){
		//$SQL="";
		//if($lfjdb[groupid]==8){
			//$SQL=",groupid=9";
		//}
		//$db->query("UPDATE {$pre}memberdata SET grouptype=1$SQL WHERE uid='$uid'");
	//}

	//申请主页
	if(!$rt[if_homepage]&&$postdb[if_homepage]){
		if(!$web_admin&&$webdb[creat_home_money]){
			add_user($lfjuid,-abs($webdb[creat_home_money]),'创建商铺扣分');
		}

		//初始化主页
		$company=$db->get_one("SELECT * FROM {$_pre}company WHERE uid='$uid' LIMIT 1");
		caretehomepage($company,false);
		parent_goto("?action=ok","");
	}else{
		parent_goto("?job=view&uid=$uid","");
	}
}elseif($action=='ok'){
		
		$msg="恭喜您，商家主页申请成功啦!";
		$do[0]['text']="点击查看商铺详情";$do[0]['target']=" target=_blank";
		$do[0]['link']="$webdb[www_url]/home/?uid=$uid";
		//$do[1]['text']="点此进入商铺管理面板";
		//$do[1]['link']="homepage_ctrl.php";
		//自动激活商家页面
		//@sockOpenUrl('$Mdomain/homepage.php?uid=$lfjuid');
		//file_get_contents('$Mdomain/homepage.php?uid=$lfjuid');

}elseif($job=='view'){
	$rsdb=$db->get_one("SELECT * FROM `{$_pre}company` WHERE uid='$uid'");
	if(!$rsdb){
		showerr("企业信息不存在!");
	}elseif($rsdb[if_homepage]){
		parent_goto("$webdb[www_url]/home/?uid=$uid","");
	}
	$rsdb[picurl]=tempdir($rsdb[picurl]);
	@include(ROOT_PATH."f/data/zone/$rsdb[city_id].php");
}else{
	
	$rsdb=$db->get_one("SELECT * FROM `{$_pre}company` WHERE uid='$uid'");
	if($rsdb[if_homepage]){
		header("location:homepage_ctrl.php?atn=info&uid=$uid");
		exit;
	}
	elseif($rsdb){
		$do_type='edit';
		$rsdb[_my_trade][$rsdb[my_trade]]=' selected ';
		$rsdb[_qy_cate][$rsdb[qy_cate]]=' selected ';
		$rsdb[_qy_saletype][$rsdb[qy_saletype]]=' selected ';
		$query = $db->query("SELECT fid FROM `{$_pre}company_fid` WHERE uid = {$rsdb['uid']}");
		$fids = array(); while($arr = $db->fetch_array($query)) $fids[] = $arr['fid'];

	}else{
		$do_type='add';
	}
	$webdb[maxCompanyFidNum]=$webdb[maxCompanyFidNum]?$webdb[maxCompanyFidNum]:10;
	//得到地区
	//$city_fid=select_where("{$_pre}city","'postdb[city_id]'  onChange=\"choose_where('getzone',this.options[this.selectedIndex].value,'','1','')\"",$rsdb[city_id]);
}





$member_style=$webdb[sys_member_style]?$webdb[sys_member_style]:"images2";

$if_homepage=(!$web_admin&&($lfjdb[money]<$webdb[creat_home_money]||!$groupdb['allow_get_homepage']))?'':' checked ';

require(dirname(__FILE__)."/"."head.php");
require(dirname(__FILE__)."/"."template/post_company.htm");
require(dirname(__FILE__)."/"."foot.php");


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

?>