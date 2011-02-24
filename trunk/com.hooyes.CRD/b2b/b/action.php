<?php
require("global.php");
@require_once(Mpath."php168/form_data.php");

if(!$action) showerr("未知操作");

if($action=='form1'){/////////////////////////////////////////////询价
	/*判断ID*/
	if(!$ids) showerr("未指定操作项");
	if(is_array($ids)){
		$ids_str=implode(",",$ids);
		$action_name="批量询价";
	}else{
	    $ids_str=$ids;
		if(!is_numeric($ids_str)) showerr("参数不符合");
		$action_name="单个询价";
	}
	$ids_str=str_replace(array(",,",",,,",),array(",",","),$ids_str);
	$ids_str=str_replace(array(",,",",,,",),array(",",","),$ids_str); //防止
	
	/*读出对应数据*/
	if($ids_str){
	$query=$db->query("SELECT A.title,A.id,A.fid,A.uid,A.username ,B.title AS owner_name FROM {$_pre}content_sell A LEFT JOIN {$_pre}company B ON B.uid=A.uid WHERE A.id in($ids_str)");
	}	
	while($rs=$db->fetch_array($query)){
		$rs[title_short]=get_word($rs[title],70);
		$listdb[]=$rs;
	}
	$listnum=count($listdb);
	$wantinfo_htm=create_wantinfo($wantinfo);
	$addinfo_htm=create_addinfo($addinfo);
	
	if($lfjuid){
		$contact_info=$db->get_one("SELECT * FROM `{$_pre}company` WHERE uid='$lfjuid'");
		$contact_info[qq]=explode(",",$contact_info[qq]);
		$contact_info[qq]=$contact_info[qq][0];
	}
	require(Mpath."inc/head.php");
	require(getTpl("form1"));
	require(Mpath."inc/foot.php");

}elseif($action=='subform1'){ ////////////////////////////////////保存询价

	if(!$ids_str) showerr("请指定询价接受方.");
	if(!$contact_info[truename]) showerr("请输入联系人姓名");
	if(!$contact_info[company_name]) showerr("请输入公司名称");
	if(!$contact_info[qy_contact_tel]) showerr("请输入固定电话");
	if(!$contact_info[qy_contact_email]) showerr("请输入邮箱地址");
	
	if($quantity){
		if(!is_numeric($quantity))showerr("数量必须是整数数字");
	}
	$quantity    =abs(intval($quantity));
	$hope_price  =floatval($hope_price);
	$add_content =htmlspecialchars($add_content);
	if($hopereply_time){if(!preg_match("/[0-9]{4}-[0-9]{1,2}-[0-9]{1,2}/",$hopereply_time)) showerr("希望回复的日期不符合格式,格式为:".date("Y-m-d"));}
	$get_info    =implode(',',$get_info);
	foreach($contact_info as $key=>$val){$contact_info[$key]=htmlspecialchars($val);}
	$contact_info=serialize($contact_info);

	//检查每个用户最多发布
	
	$rt=$db->get_one("SELECT COUNT(*) AS num FROM `{$_pre}form1` WHERE ".($lfjuid?"`owner_uid`='$lfjuid'":"`from_ip`='$onlineip'")." AND `posttime` > ".(24*60*60).";");
	if($rt[num]>=$webdb[freeSentform1]) showerr("24小时内您只能发布{$webdb[freeSentform1]}条询价单");


	foreach($titles as $key=>$title){
		$title=htmlspecialchars($title);
		$title=get_word($title,120);
		$db->query("INSERT INTO `{$_pre}form1` ( `id` , `info_id` , `owner_uid` , `owner_username` , `from_uid` , `from_username` ,`from_ip`, `title` , `quantity` , `hope_price` , `get_info` , `add_content` , `hopereply_time` , `posttime` , `is_reply` , `reply_content` , `reply_time` , `contact_info` ) VALUES ('', '$key', '$owner_uid[$key]', '$owner_username[$key]', '$lfjuid', '$lfjid','$onlineip', '$title', '$quantity', '$hope_price', '$get_info', '$add_content', '$hopereply_time', '".time()."', '0', '', '0', '$contact_info');");
		$db->query("UPDATE `{$_pre}content_1` SET `xunjia_num`=`xunjia_num`+1 WHERE id='$key'");//更新报价数量
	}

	echo '<meta http-equiv="Content-Type" content="text/html; charset=gb2312">';
	echo "<script>alert('成功发送条".count($titles)."询价单');
	if(!window.close()){window.location='./';}
	</script>";

}elseif($action=='form2'){ ///////////////////////////////////////报价
	
	/*判断ID*/
	if(!$ids) showerr("未指定操作项");
	if(is_array($ids)){
		$ids_str=implode(",",$ids);
		$action_name="批量报价";
	}else{
	    $ids_str=$ids;
		$action_name="单个报价";
	}
	/*读出对应数据*/
	if($ids_str){
	$query=$db->query("select A.title,A.id,A.fid,A.uid,A.username ,B.title as owner_name from {$_pre}content_buy A left join {$_pre}company B on B.uid=A.uid where A.id in($ids_str)");
	}	
	while($rs=$db->fetch_array($query)){
		$rs[title_short]=get_word($rs[title],70);
		$listdb[]=$rs;
	}
	$listnum=count($listdb);
	
	if($lfjuid){
		$contact_info=$db->get_one("select * from `{$_pre}company` where uid='$lfjuid'");
		$contact_info[qq]=explode(",",$contact_info[qq]);
		$contact_info[qq]=$contact_info[qq][0];
	}
	require(Mpath."inc/head.php");
	require(getTpl("form2"));
	require(Mpath."inc/foot.php");

}elseif($action=='subform2'){ ////////////////////////////////////保存报价单
	
	if(!$ids_str) showerr("请指定询价接受方.");
	if(!$put_price) showerr("供货价格必须填写.");
	if(!$contact_info[truename]) showerr("请输入联系人姓名");
	if(!$contact_info[company_name]) showerr("请输入公司名称");
	if(!$contact_info[qy_contact_tel]) showerr("请输入固定电话");
	if(!$contact_info[qy_contact_email]) showerr("请输入邮箱地址");
	
	if($quantity){
		if(!is_numeric($quantity))showerr("供货总量必须是整数数字");
	}
	$quantity    =abs(intval($quantity));
	$put_price  =floatval($put_price);
	$add_content =htmlspecialchars($add_content);
	if($hopereply_time){if(!preg_match("/[0-9]{4}-[0-9]{1,2}-[0-9]{1,2}/",$hopereply_time)) showerr("希望回复的日期不符合格式,格式为:".date("Y-m-d"));}
	$get_info    =implode(',',$get_info);
	foreach($contact_info as $key=>$val){$contact_info[$key]=htmlspecialchars($val);}
	$contact_info=serialize($contact_info);
	//检查每个用户最多发布
	$rt=$db->get_one("select count(*) as num from `{$_pre}form2` where ".($lfjuid?"`owner_uid`='$lfjuid'":"`from_ip`='$onlineip'")." and `posttime` > ".(24*60*60).";");
	if($rt[num]>=$webdb[freeSentform2]) showerr("24小时内您只能发布{$webdb[freeSentform1]}条报价单");
	foreach($titles as $key=>$title){
		$title=htmlspecialchars($title);
		$title=get_word($title,120);
		$db->query("INSERT INTO `{$_pre}form2` ( `id` , `info_id` , `owner_uid` , `owner_username` , `from_uid` , `from_username`,`from_ip` , `title` , `quantity` , `put_price` ,  `add_content` ,`cankao`, `hopereply_time` , `posttime` , `is_reply` , `reply_content` , `reply_time` , `contact_info` ) VALUES ('', '$key', '$owner_uid[$key]', '$owner_username[$key]', '$lfjuid', '$lfjid','$onlineip', '$title', '$quantity', '$put_price',  '$add_content','$cankao', '$hopereply_time', '".time()."', '0', '', '0', '$contact_info');");
		$db->query("update `{$_pre}content_2` set `baojia_num`=`baojia_num`+1 where id='$key'");//更新报价数量
		
	}
	echo '<meta http-equiv="Content-Type" content="text/html; charset=gb2312">';
	echo "<script>alert('成功发送条".count($titles)."报价单');
	if(!window.close()){window.location='./';}
	</script>";

}elseif($action=='form3'){ ///////////////////////////////////////商家对比
	
	/*判断ID*/
	if(!$ids_str){
	if(!$ids) showerr("未指定操作项");
		$ids_str=implode(",",$ids);
		$action_name="对比商家";
	}else{
		$ids_str=str_replace($removeid,'0',$ids_str);
	}
	/*读出对应数据*/
	if($ids_str){
	$query=$db->query("select A.* from {$_pre}company A  where A.rid in($ids_str)");
	}	
	while($rs=$db->fetch_array($query)){
	
		$rs[picurl]=getimgdir($rs[picurl],3);
		$rs[picurl]="<img src='".$rs[picurl]."' border=0 width='120' onerror=\"this.style.display='none';\">";
		$rs[title]="<a href='$Mdomain/homepage.php?uid=$rs[uid]' target='_blank'><b>".get_word($rs[title],70)."</b></a>";
	
		$rs[content]=@preg_replace('/<([^>]*)>/is',"",$rs[content]);	//把HTML代码过滤掉
		$rs[content]=get_word($rs[content],200);
		$rs[fid_all]=GuideFid($rs[fid_all]);
		$rs[fid_all]=@preg_replace('/<([^>]*)>/is',"",$rs[fid_all]);
		$rs[renzheng]=getrenzheng($rs[renzheng]);
		$rs[posttime]="登记时间:".date("Y-m-d",$rs[posttime]);
		$rs[city_id]="所在地区：{$area_DB[name][$city_DB[fup][$rs[city_id]]]}/{$city_DB[name][$rs[city_id]]}";
		$rs[qy_cate]="商家类型：".$rs[qy_cate];
		$rs[qy_saletype]="服务类型：".$rs[qy_saletype];
		$rs[qy_regmoney]="注册资本：".$rs[qy_regmoney]."万元";
		$rs[qy_createtime]="成立公司：".$rs[qy_createtime];
		$rs[qy_regplace]="注册地区：".$rs[qy_regplace];

		$rs[username]="系统帐号：".$rs[username];
		
		$listdb[]=$rs;
		$showlist=array("title","content","fid_all","username","renzheng","posttime","picurl","city_id","qy_cate","qy_saletype","qy_regmoney","qy_createtime","qy_regplace");
		foreach($rs as $key=>$val){
			if(in_array($key,$showlist)){
			
			$data[$key][]=$val;
			}
		}
		
	}
	$listnum=count($listdb);
	
	
	require(Mpath."inc/head.php");
	require(getTpl("form3"));
	require(Mpath."inc/foot.php");
}elseif($action=='subform3'){ ////////////////////////////////////批量收藏

	if(!$lfjid){
		showerr("请先登录");
	}elseif(!$ids_str){
		showerr("ID不存在");
	}
	$ids=explode(",",$ids_str);
	$ctype=3;//强制是3
	if(is_array($ids)){
		
		foreach($ids as $id){
			if($db->get_one("SELECT * FROM `{$_pre}collection` WHERE `id`='$id' AND uid='$lfjuid'")){
				showerr("请不要重复收藏本条信息",1); 
			}
			if(!$web_admin){
				if($webdb[Info_CollectArticleNum]<1){
					$webdb[Info_CollectArticleNum]=50;
				}
				$rs=$db->get_one("SELECT COUNT(*) AS NUM FROM `{$_pre}collection` WHERE uid='$lfjuid'");
				if($rs[NUM]>=$webdb[Info_CollectArticleNum]){
					showerr("你最多只能收藏{$webdb[Info_CollectArticleNum]}条信息",1);
				}
			}	
			$db->query("INSERT INTO `{$_pre}collection` (  `id` , `uid` , `posttime`,`ctype`) VALUES ('$id','$lfjuid','$timestamp','$ctype')");
		}
	}

	refreshto("$Mdomain/member/?main=collection.php","收藏成功!",1);
}else{////////////////////////////////////////////////////////////ERROR

	showerr("非法操作");
}


function create_wantinfo($wantinfo){
	if(is_array($wantinfo)){
		foreach($wantinfo as $val){
			$htm.="<label><input type='checkbox' name='get_info[]' value='$val'>$val</label> \r\n ";
		}	
	}	
	return $htm;
}
function create_addinfo($addinfo){
	if(is_array($addinfo)){
		$htm.="<select name='autoSelect' id='autoSelect' onchange=\"changeaddContent(this);\">\r\n<option value=''>(懒得打字？“快速填写”帮您忙！) </option>\r\n";
		foreach($addinfo as $val){
			$htm.="<option value='$val'>$val</option> \r\n";
		}	
		$htm.="<select>\r\n";
	}	
	return $htm;
}
?>