<?php
require(dirname(__FILE__)."/../global.php");
require(dirname(__FILE__)."/../homepage_php/global.php");
require_once(dirname(__FILE__)."/../bd_pics.php");
$atn=$atn?$atn:"info";
$member_style=$webdb[sys_member_style]?$webdb[sys_member_style]:($webdb[member_style]?$webdb[member_style]:"images2");
$linkdb=array(
			"����Ԥ��"=>$Mdomain."/homepage.php?uid=$lfjuid"

			);	
$blank="_blank";
	
if(!$lfjuid) showerr("����Ȩ����");
$uid=$lfjuid;
$rsdb=$db->get_one("SELECT * FROM {$_pre}company WHERE uid='$uid' LIMIT 1");
if(!$rsdb[rid]) showerr("�̼���Ϣδ�Ǽ�,<a href='$Murl/member/post_company.php'>�������Ǽ��̼�</a>��ӵ���Լ�������");
if($rsdb[is_vip] > $timestamp){
	$rsdb[vip]=true;
}else{
	$rsdb[vip]=false;
}
//�̼������ļ�

$homepage=$db->get_one("SELECT * FROM {$_pre}homepage WHERE rid='$rsdb[rid]' LIMIT 1");


if(!$homepage[hid]) { //�����̼���Ϣ

	require(dirname(__FILE__)."/"."head.php");
	require(dirname(__FILE__)."/"."template/active_homepage.htm");
	require(dirname(__FILE__)."/"."foot.php");
	exit;
}



//�Ƿ���Ҫ��ʾ���ƹ�˾���ϵ��ж�
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
		//showerr("ϵͳ�ر��˶�����������");
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
		//���
		if(!preg_match("/^[a-z\d]{2,12}$/",$host))  showerr("��������ֻ��ʹ��ĸ�������֣�������2-12���ַ�֮��,��ȫ��Сд");
		$limitmain=explode(",",$webdb[vipselfdomaincannot]);
		if(in_array($host,$limitmain)) showerr("�˶�������Ϊϵͳ��������������ʹ�ã��뻻һ������");

		$rt=$db->get_one("SELECT COUNT(*) AS num FROM  {$_pre}company WHERE host='$host' AND  rid!='$rsdb[rid]'");
		if($rt[num]>0) showerr("[ $host ]�Ѿ�������ʹ���ˣ��뻻һ������");
		//����
		$db->query("UPDATE {$_pre}company SET
				`host`='$host'
				WHERE rid='$rsdb[rid]' ");
		refreshto("?uid=$uid&atn=$atn","���óɹ�");
	}

}elseif($atn=='base'){
	
	if(!$step){
		
		
		//�̼������ļ�
		$conf=$db->get_one("SELECT * FROM {$_pre}homepage WHERE rid='$rsdb[rid]' LIMIT 1");
		
		//�б�����
		$conf[listnum]=unserialize($conf[listnum]);	
		
		//ģ������ left
		$conf[index_left]=explode(",",$conf[index_left]);
		foreach($conf[index_left] as $key){
		
			if($key) $index_left.="<option value='$key'>".$tpl_left[$key]."</option>";
		}
		foreach($tpl_left as $key=>$val){
		
			$index_left_hx.="<option value='$key'>$val</option>";
		}
		
		//ģ������ right
		$conf[index_right]=explode(",",$conf[index_right]);
		foreach($conf[index_right] as $key){
			if($key) $index_right.="<option value='$key' >".$tpl_right[$key]."</option>";
		}
		foreach($tpl_right as $key=>$val){
		
			$index_right_hx.="<option value='$key'>$val</option>";
		}
		
		
		
		$bodytpl[$conf[bodytpl]]=" checked";
		//���
		$homepage_style="default";
		if($conf[style] && is_dir($tpl_dir.$conf[style])) $homepage_style=$conf[style];
		
		//�õ��ҵķ���
		$query=$db->query("SELECT * FROM `{$_pre}mysort` WHERE uid='$lfjuid' AND ctype=1 ORDER BY listorder DESC");
		while($rs=$db->fetch_array($query)){
			 $sell_mysort[$rs[ms_id]]=$rs[sortname];
		}
		$query=$db->query("SELECT * FROM `{$_pre}mysort` WHERE uid='$lfjuid' and ctype=2 ORDER BY listorder desc");
		while($rs=$db->fetch_array($query)){
			 $buy_mysort[$rs[ms_id]]=$rs[sortname];
		}

		//��֤����
		$renzheng_show[$conf[renzheng_show]]=" checked";

	}else{
		
		$conf[listnum]=serialize($conf[listnum]);
		if(count($conf[index_left])<1){
			showerr("���������Ŀ����Ϊ��");
		}
		$conf[index_left]=implode(",",$conf[index_left]);
		if(count($conf[index_right])<1){
			showerr("�����ұ���Ŀ����Ϊ��");
		}
		$conf[index_right]=implode(",",$conf[index_right]);
				
		//���VIP

		if(substr($conf[style],0,3) == 'vip'){
			if(!$rsdb[vip]) showerr("������VIP�̼�,����ʹ�ô�ģ�壬��������ͨVIP�̼ҷ��� [ <a href='$Murl/member/vip.php'>������￪ͨVIP</a> ] ");
		}

		$db->query("UPDATE {$_pre}homepage SET
		`style`='$conf[style]',
		`index_left`='$conf[index_left]',
		`index_right`='$conf[index_right]',
		`listnum`='$conf[listnum]',
		`bodytpl`='$conf[bodytpl]',
		renzheng_show='$conf[renzheng_show]'
		WHERE hid='$conf[hid]'");
		refreshto("?uid=$uid&atn=$atn","���óɹ�");
	
	}
	
}elseif($atn=='banner'){
		
	
		if(!$step){
		

			$conf=$db->get_one("SELECT * FROM {$_pre}homepage WHERE rid='$rsdb[rid]' LIMIT 1");
			$conf[banner_show]=$webdb[www_url]."/".$webdb[updir]."/{$Imgdirname}/banner/".$conf[banner];
		}else{
				//ͼƬ����
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
					if($array[size]>$webdb[homepage_banner_size]*1024) showerr("ͼƬ�ļ����ܳ���$webdb[homepage_banner_size] K");
					$picurl_t=upfile(is_array($postfile)?$_FILES[postfile][tmp_name]:$postfile,$array);
					if($picurl_t){//����ͼƬ
						$banner=$picurl_t;
						@unlink(PHP168_PATH.$array[path].$conf[oldfile]);
					}		
				}
				
				
			}
			$db->query("UPDATE {$_pre}homepage SET
				`banner`='$banner'
				WHERE hid='$conf[hid]'");
		refreshto("?uid=$uid&atn=$atn","���óɹ�");
			
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
			
			//�õ�����
			//$fid_all=explode("|",$intro[fid_all]);
			
			
			
			//�õ�����
			
			$city_options=select_where('city',"'city_id'   style='width:100px;'",$city_id,$province_id);
			$area_choose=select_where('province',"'province_id'  onchange='showcity(this)' style='width:100px;'",$province_id,0);
			$area_choose=$area_choose."<span id='city_span'>$city_options</span>";


			//��ʵ��ַ��ԭ
			$content=En_TruePath($content,0);
			
			$webdb[maxCompanyFidNum]=$webdb[maxCompanyFidNum]?$webdb[maxCompanyFidNum]:10;

		}else{
		
			$picurl=$oldfile;
			if(is_uploaded_file($_FILES[postfile][tmp_name])){
				$array[name]=is_array($postfile)?$_FILES[postfile][name]:$postfile_name;
				$array[path]=$webdb[updir]."/{$Imgdirname}/ico/";
				if(!is_dir($array[path])) @mkdir(PHP168_PATH.$array[path]);
				$array[size]=is_array($postfile)?$_FILES[postfile][size]:$postfile_size;
				if($array[size]>$webdb[homepage_ico_size]*1024) showerr("ͼƬ�ļ����ܳ���$webdb[homepage_ico_size] K");
				$picurl_t=upfile(is_array($postfile)?$_FILES[postfile][tmp_name]:$postfile,$array);
				if($picurl_t){//����ͼƬ
					$picurl=$picurl_t;
					echo "ɾ��ԭ��LOGO $conf[oldfile]";
					@unlink(PHP168_PATH.$array[path].$oldfile);

					$Newpicpath=PHP168_PATH."$array[path]/logo_{$picurl}";
					gdpic(PHP168_PATH."$array[path]/$picurl",$Newpicpath,120,120);
					if(file_exists($Newpicpath)){
						@unlink(PHP168_PATH."$array[path]/{$picurl}");
						$picurl="logo_{$picurl}";
					}

					
				}	
				
			}
		
		
			if(strlen($title) <5 || strlen($title)>60 ) showerr("��˾���Ʊ�����5-30����֮��");
				
			if(count($fids)<1)showerr("����ѡ��һ������");

			if(!$city_id) showerr("������������ѡ��");

			$intro=$db->get_one("SELECT rid FROM `{$_pre}company` WHERE `uid`='$uid'");
			$db->query("DELETE FROM `{$_pre}company_fid` WHERE cid = {$intro['rid']}");
			
			//���������ϵ
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
			
	

			
			refreshto("?uid=$uid&atn=$atn","�޸ĳɹ�");
		
		}
		
}elseif($atn=='info2'){
		
		if(!$step){
			@extract($intro=$db->get_one("SELECT * FROM `{$_pre}company` WHERE `uid`='$uid'"));
			//��ʵ��ַ��ԭ
			$content=En_TruePath($content,0);
			$rsdb[bd_pics]=$intro[bd_pics];
		}else{
			if(!$content){
			showerr("���ݲ���Ϊ��");
			}
			
			$content = preg_replace('/javascript/i','java script',$content);//����js����
			$content = preg_replace('/<iframe ([^<>]+)>/i','&lt;iframe \\1>',$content);//���˿�ܴ���
			$content=En_TruePath($content,1);
			$db->query("UPDATE `{$_pre}company` SET 
			`content`='$content'
			WHERE uid='$uid'");
			//���°�ͼƬ	
			bd_pics("{$_pre}company","WHERE  uid='$uid'");
			
			refreshto("?uid=$uid&atn=$atn","�޸ĳɹ�");
			
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
			
			
			refreshto("?uid=$uid&atn=$atn","�޸ĳɹ�");
			
		}	
		
}elseif($atn=='news'){
	
	$rows=10;
	if($page<1){
		$page=1;
	}
	$min=($page-1)*$rows;
	$rsdb=$db->get_one("SELECT * FROM {$_pre}company WHERE uid='$uid' LIMIT 1");
	if(!$rsdb[rid]) showerr("�̼���Ϣδ�Ǽ�");
	$where=" WHERE rid='$rsdb[rid]' ";
	
	$query=$db->query("SELECT * FROM {$_pre}homepage_article $where ORDER BY posttime DESC LIMIT $min,$rows");
	
	while($rs=$db->fetch_array($query)){
		$rs[posttime]=date("Y-m-d",$rs[posttime]);
		$rs[yz]=!$rs[yz]?"<font color=red>�����</font>":"��ͨ��";
		$listdb[]=$rs;
	}	
	
	$showpage=getpage("{$_pre}homepage_article",$where,"?uid=$uid&atn=$atn",$rows);
	
	
}elseif($atn=='postnews'){
	
	if(!$step){
	
		if($id){
			$news=$db->get_one("select * from {$_pre}homepage_article where id='$id'");
			
			//��ʵ��ַ��ԭ
			$news[content]=En_TruePath($news[content],0);
		}
		$rsdb[bd_pics]=$news[bd_pics];
	}else{
			if(strlen($title)<10 || strlen($title)>60) showerr("����ֻ����5-30����");
			if(!$content) showerr("���ݲ���Ϊ��");
			if(strlen($content)>50000) showerr("���ݹ��������50000�ַ�(����HTML����)");
			
			$content = preg_replace('/javascript/i','java script',$content);//����js����
			$content = preg_replace('/<iframe ([^<>]+)>/i','&lt;iframe \\1>',$content);//���˿�ܴ���
			$content =En_TruePath($content,1);
			$rsdb=$db->get_one("select * from {$_pre}company where uid='$uid' limit 1");
			if(!$rsdb[rid]) showerr("�̼���Ϣδ�Ǽ�");
			if($id){
			
				$db->query("update `{$_pre}homepage_article` set `title`='$title',`content`='$content',yz=0 where id='$id'");
			
			}else{
				$yz=0;
				$db->query("INSERT INTO `{$_pre}homepage_article` ( `id` , `title` , `content` , `hits` , `posttime` , `list` , `uid` , `username` , `rid` , `titlecolor` , `fonttype` , `picurl` , `ispic` , `yz` , `levels` , `keywords` ) VALUES ('', '$title', '$content', '0', '".time()."', '0', '$lfjuid', '$lfjid', '$rsdb[rid]', '', '0', '', '0', '$yz', '0', '');");
				$id=$db->insert_id();
			}
			//���°�ͼƬ	
			bd_pics("{$_pre}homepage_article"," where id='$id' ");

			refreshto("?uid=$uid&atn=news","�ύ�ɹ�");
	}
}elseif($atn=='delnews'){	
	
	$rsdb=$db->get_one("SELECT * FROM `{$_pre}homepage_article` WHERE id='$id' ");
	if($rsdb[uid]!=$uid)
	{
		showerr("����Ȩɾ�����˵�����");
	}
	//ɾ������
	delete_attachment($rsdb[uid],$rsdb[content]);
	//ɾ������
	delete_attachment($rsdb[uid],tempdir($rsdb[picurl]) );
	$db->query("DELETE FROM `{$_pre}homepage_article` WHERE id='$id' AND uid='$uid'");
	refreshto("?uid=$uid&atn=news","ɾ���ɹ�");
	
}elseif($atn=='friendlink'){
	
	$rsdb=$db->get_one("select * from {$_pre}company where uid='$uid' limit 1");
	if(!$rsdb[rid]) showerr("�̼���Ϣδ�Ǽ�");
	//�̼������ļ�
	$conf=$db->get_one("select * from {$_pre}homepage where rid='$rsdb[rid]' limit 1");
	
	if($step){
		if(!$fl_username)showerr("������Է�ͨ��֤�ʺ�");
		$flink=$db->get_one("select * from {$_pre}company where username='$fl_username' limit 1");
		if(!$flink[rid]) showerr("û���ҵ���Ӧ���̼�");
		if($fl_username==$rsdb[username])  showerr("��������Լ�");
		if(strpos($conf[friendlink],$fl_username)!==false)  showerr("�Ѿ���ӹ����̼���");
		$conf[friendlink]=$conf[friendlink].",".$fl_username;	

		
		$db->query("update {$_pre}homepage  set `friendlink`='$conf[friendlink]' where rid='$rsdb[rid]'");
		refreshto("?uid=$uid&atn=$atn","��ӳɹ�");
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
		refreshto("?uid=$uid&atn=$atn","ɾ���ɹ�");
	}
	
	$conf[friendlink]=explode(",",$conf[friendlink]);
	foreach($conf[friendlink] as $k){
		$kk[]="'".$k."'";
	}
	$conf[friendlink]=implode(",",$kk);
	$query=$db->query("select rid,username,uid,title from {$_pre}company where username in(".$conf[friendlink].")");
	while($rs=$db->fetch_array($query)){
		$rs[username_code]=urlencode($rs[username]); //Ϊ��ɾ����
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
	if(strlen($name)<2 || strlen($name)>16)showerr("ͼ��ֻ������ֻ����1-8������");
	if(strlen($remarks)>100)showerr("�������50����");
	if($psid){ //����
		$db->query("update `{$_pre}homepage_picsort` set 
		`name`='$name',
		`remarks`='$remarks',
		`orderlist`='$orderlist'
		where psid='$psid'");
	}else{ //���
		$webdb[company_picsort_Max]=$webdb[company_picsort_Max]?$webdb[company_picsort_Max]:10;
		$mypicsortnum=$db->get_one("select count(*) as num from {$_pre}homepage_picsort where uid='$lfjuid' ");
		if($mypicsortnum[num]>=$webdb[company_picsort_Max])	showerr("����ͼ�������Ѿ��������Ŀ{$webdb[company_picsort_Max]}");	
		
		$db->query("INSERT INTO `{$_pre}homepage_picsort` ( `psid` , `psup` , `name` , `remarks` , `uid` , `username` , `rid` , `level` , `posttime` , `orderlist` ) 
VALUES ('', '0', '$name', '$remarks', '$lfjuid', '$lfjid', '{$rsdb[rid]}', '0', '$timestamp', '$orderlist');");
	}
	refreshto("?uid=$uid&atn=pic","����ɹ�");
	
}elseif($atn=='del_picsort'){
	if(!$psid) showerr("����ʧ��");
	
	$mypics=$db->get_one("select count(*) as num from {$_pre}homepage_pic where psid='$psid'");
	if($mypics[num]>0) showerr("�ǿ�ͼ��������ɾ��");
	
	$db->query("delete from {$_pre}homepage_picsort where psid='$psid' limit 1");
	refreshto("?uid=$uid&atn=pic","ɾ���ɹ�");
}elseif($atn=='piclist'){

	if(!$psid) showerr("����ʧ��");
	$rsdb=$db->get_one("select * from {$_pre}homepage_picsort where psid='$psid' limit 1");
	if(!$rsdb) showerr("ϵͳ����");
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
			if(!in_array($myname,array('gif','jpg'))) $msg="{$array[name]}ͼƬֻ����gif����jpg�ĸ�ʽ";		
			$array[path]=$user_picdir.$lfjuid;//�̼�ͼƬ���
			$array[size]=is_array($postfile)?$_FILES[postfile][size]:$postfile_size;
			$webdb[company_uploadsize_max]=$webdb[company_uploadsize_max]?$webdb[company_uploadsize_max]:100;
			if($array[size]>$webdb[company_uploadsize_max]*1024)	$msg="{$array[name]}ͼƬ�������{$webdb[company_uploadsize_max]}K����";
			
			if($msg==''){
				$picurl=upfile(is_array($postfile)?$_FILES[postfile][tmp_name]:$postfile,$array);
				//��Ӧ������������ͼ
				
				if($picurl){
						
						$Newpicpath=PHP168_PATH."$array[path]/{$picurl}.gif";
						gdpic(PHP168_PATH."$array[path]/$picurl",$Newpicpath,120,120);
						if(!file_exists($Newpicpath)){
							copy(PHP168_PATH."$array[path]/{$picurl}",$Newpicpath);
						}else{
							$picurl=$picurl;
						}
						$msg="{$array[name]}�ϴ��ɹ�";
						$title=get_word($title,32);
						$db->query("INSERT INTO `{$_pre}homepage_pic` ( `pid` , `psid` , `uid` , `username` , `rid` , `title` , `url` , `level` , `yz` , `posttime` , `isfm` , `orderlist`  ) VALUES ('', '$psid', '$lfjuid', '$lfjid', '$rsdb[rid]', '$title', '$picurl', '0', '{$webdb[auto_userpostpic]}', '$timestamp', '0', '0');");
						
				}else{
					$msg="{$array[name]}�ϴ�ʧ�ܣ����Ժ����ԡ�";
				}
			}	
			
			//�������ݿ�Ŷ
	
		
		}else{
			$msg="����Ҫ�ϴ����ļ�,�����ϴ�";
		}

	}else{
		$msg="����ѡ��һ��ͼ��"; 
	}
	echo "<SCRIPT LANGUAGE=\"JavaScript\">
			<!--
			parent.document.getElementById(\"userpostpic_$ind\").innerHTML='<strong>[{$ind}]</strong> $msg';
			parent.userpost_pic_do(".($ind+1).");			
			//-->
			</SCRIPT>";exit;
			
}elseif($atn=='pic_edit'){	

	if(count($pids)<1) showerr("����ѡ��һ��");
	$pids=implode(",",$pids);
	$query=$db->query("select * from {$_pre}homepage_pic where pid in($pids) order by orderlist desc");
	while($rs=$db->fetch_array($query)){
		$rs[posttime]=date("Y-m-d H:i:s",$rs[posttime]);
		$rs[url]=$webdb[www_url]."/".$user_picdir.$rs[url];
		$listdb[]=$rs;
	}
}elseif($atn=='pic_edit_save'){	

	if(count($pids)<1) showerr("����ѡ��һ��");
	foreach($pids as $pid){
		if($pid){
			//ִ��
			$db->query("update {$_pre}homepage_pic set title='".get_word(htmlspecialchars($title[$pid]),32)."',orderlist='".intval($orderlist[$pid])."' where pid='$pid' limit 1");
		}
	}
	refreshto("?atn=piclist&psid=$psid&uid=$uid","����ɹ�");
		
}elseif($atn=='pic_del'){	
	
	if(count($pids)<1) showerr("����ѡ��һ��");
	foreach($pids as $pid){
		if($pid){
			$rt=$db->get_one("select url from {$_pre}homepage_pic where pid='$pid'");
			@unlink(PHP168_PATH.$user_picdir.$lfjuid."/".$rt[url]);
			@unlink(PHP168_PATH.$user_picdir.$lfjuid."/".$rt[url].".gif");
			$db->query("delete from {$_pre}homepage_pic where pid='$pid'");
		}
	}
	refreshto("?atn=piclist&psid=$psid&uid=$uid","ɾ���ɹ�");

}elseif($atn=='pic_fm'){	
	if(count($pids)<1) showerr("��ѡ��һ��ͼƬ");
	if(!$psid) showerr("��ָ��һ��ͼ��");
	foreach($pids as $pid){
		if($pid){
			$rt=$db->get_one("select url from {$_pre}homepage_pic where pid='$pid'");
			$db->query("update {$_pre}homepage_picsort set faceurl='$rt[url]' where psid='$psid'");
			break;
		}
	}
	refreshto("?atn=piclist&psid=$psid&uid=$uid","���óɹ�");
	
	
}elseif($atn=='gz'){ //���ù���
		
	
		if(!$step){
			$conf=$db->get_one("select * from {$_pre}company where uid='$uid' limit 1");
			if(!$conf[rid]) showerr("�̼���Ϣδ�Ǽ�");
			$conf[gz_show]=$webdb[www_url]."/".$webdb[updir]."/{$Imgdirname}/gz/".$conf[gz];
		}else{
				//ͼƬ����
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
					if($array[size]>2*1024*1024) showerr("ͼƬ�ļ����ܳ���2M");
					$picurl=upfile(is_array($postfile)?$_FILES[postfile][tmp_name]:$postfile,$array);
					if($picurl){//����ͼƬ
																
						$Newpicpath=PHP168_PATH."$array[path]/{$picurl}.gif";
						gdpic(PHP168_PATH."$array[path]/$picurl",$Newpicpath,120,120);
						if(!file_exists($Newpicpath)){
							showerr('���ϴ���ͼƬ�޷��������£������´������Ĺ���ͼƬ');
						}else{
							$gz=$picurl.".gif";
							@unlink(PHP168_PATH.$array[path].$picurl);
							@unlink(PHP168_PATH.$array[path].$conf[oldfile]);
						}
						
						
					}		
				}
				
				
			}
			
			$db->query("update {$_pre}company set	`gz`='$gz'	where rid='$conf[rid]'");
			
		    refreshto("?uid=$uid&atn=$atn","���óɹ�");
			
		}
}


require(dirname(__FILE__)."/"."head.php");
require(dirname(__FILE__)."/"."template/homepage_ctrl.htm");
require(dirname(__FILE__)."/"."foot.php");

?>