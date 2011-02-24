<?php	
!function_exists('html') && exit('ERR');	

ck_power('form_module');
	
if($job=="list")	
{	
	$query = $db->query("SELECT * FROM {$_pre}module ORDER BY list DESC,id ASC");	
	while($rs = $db->fetch_array($query)){	
		$rss=$db->get_one("SELECT count(*) AS NUM FROM {$_pre}content WHERE mid='$rs[id]' ");	
		$rs[NUM]=$rss[NUM];	
		$listdb[]=$rs;	
	}
	get_admin_html('list');
}
elseif($job=="set")
{
	$rsdb=$db->get_one("SELECT * FROM {$_pre}module WHERE id='$id'");
	$group_post=group_box("allowpost",explode(",",$rsdb[allowpost]));

	$group_view=group_box("allowview",explode(",",$rsdb[allowview]));

	$rsdb[endtime]=$rsdb[endtime]==0?'':date("Y-m-d H:i:s",$rsdb[endtime]);

	$rsdb[about]=editor_replace($rsdb[about]);

	$usetitle[$rsdb[usetitle]]=' checked ';
	$repeatpost[$rsdb[repeatpost]]=' checked ';
	
	get_admin_html('set');
}
elseif($action=="set")
{
	$postdb[endtime]	&&	$postdb[endtime]=preg_replace("/([\d]+)-([\d]+)-([\d]+) ([\d]+):([\d]+):([\d]+)/eis","mk_time('\\4','\\5', '\\6', '\\2', '\\3', '\\1')",$postdb[endtime]);
	$allowpost=implode(",",$allowpost);
	$allowview=implode(",",$allowview);
	$db->query("UPDATE {$_pre}module SET allowpost='$allowpost',allowview='$allowview',endtime='$postdb[endtime]',name='$postdb[name]',about='$postdb[about]',usetitle='$postdb[usetitle]',repeatpost='$postdb[repeatpost]',statename='$postdb[statename]' WHERE id='$id'");
	jump("修改成功","$FROMURL",1);
}
elseif($action=="editlist")	
{	
	foreach( $order AS $key=>$value){	
		$db->query("UPDATE {$_pre}module SET list='$value' WHERE id='$key' ");	
	}
	jump("修改成功","$FROMURL",1);
}	
elseif($action=="addmodule")	
{	
	if(!$name){
		showmsg("名称不能为空");
	}
	$rs=$db->get_one("SELECT * FROM `{$_pre}module` WHERE `name`='$name'");
	if($rs){
		showmsg("当前表单名称已经存在了,请更换一个");
	}
	if(!$IS_BIZPhp168){
		@extract($db->get_one("SELECT COUNT(*) AS NUM FROM {$_pre}module"));
		if($NUM>21){
			//showmsg("免费版最多只能创建20个表单模型");
		}
	}
	if($fid){	
		$type=0;	
	}else{	
		$type=1;	
	}	
	$array[field_db][content]=array(	
		"title"=>"附注",	
		"field_name"=>"content",	
		"field_type"=>"mediumtext",	
		"form_type"=>"ieedit",	
		"search"=>"0"	
		);	
	$array[is_html][content]="内容";	
	$config=serialize($array);	
	$db->query("INSERT INTO {$_pre}module (name,config,statename) VALUES ('$name','$config','审核') ");	
	@extract($db->get_one("SELECT id FROM {$_pre}module ORDER BY id DESC LIMIT 0,1"));	
	unset($SQL);	
	if($dbcharset){	
		$SQL=" DEFAULT CHARSET=$dbcharset ";	
	}	
	$SQL="CREATE TABLE `{$_pre}content_{$id}` (
	`id` mediumint(7) NOT NULL auto_increment,
	`uid` mediumint(7) NOT NULL default '0',
	`content` mediumtext NOT NULL,
	PRIMARY KEY  (`id`),
	KEY `uid` (`uid`)
	) TYPE=MyISAM {$SQL} AUTO_INCREMENT=1 ;";	
	$db->query($SQL);	
		
	jump("创建成功<br><a href='$admin_path&job=tpl&id=$id' style='color:red;font-size:25px'>务必注意！！你必须点击生成模板,模块才能生效</a> ","$admin_path&job=listfield&id=$id");	
}	
	
//修改栏目信息	
elseif($job=="listfield")	
{	
	$rsdb=$db->get_one("SELECT * FROM {$_pre}module WHERE id='$id'");	
	
	$select_style=select_style('Info_style',$rsdb[style]);	
	
	$array=unserialize($rsdb[config]);	
	
	$listdb=$array[field_db];	
	
	get_admin_html('listfield');
}	
	
elseif($action=="listfield")	
{	
	$db->query(" UPDATE {$_pre}module SET name='$name',style='$Info_style' WHERE id='$id' ");	
		
	jump("修改成功","$FROMURL");	
}	
elseif($action=="editorder")	
{	
	$rsdb=$db->get_one("SELECT * FROM {$_pre}module WHERE id='$id' ");	
	$array=unserialize($rsdb[config]);	
	$field_db=$array[field_db];	
	
	foreach( $field_db AS $key=>$value){	
		$postdb[$key]=intval($postdb[$key]);	
		$field_db[$key][orderlist]=$postdb[$key];	
		$_listdb[$postdb[$key]]=$field_db[$key];	
	}	
	krsort($_listdb);	
	foreach( $_listdb AS $key=>$rs){	
		$listdb[$rs[field_name]]=$rs;	
	}	
	if(is_array($listdb)){	
		$field_db=$listdb+$field_db;	
	}	
	$array[field_db]=$field_db;	
	
	
	$config=addslashes(serialize($array));	
	$db->query("UPDATE {$_pre}module SET config='$config' WHERE id='$id' ");	
	jump("修改成功<br><a href='$admin_path&job=tpl&id=$id' style='color:red;font-size:25px'>务必注意！！你必须点击生成模板,模块才能生效</a> ","$admin_path&job=listfield&id=$id",10);	
}	
elseif($job=="editfield")	
{	
	$rsdb=$db->get_one("SELECT * FROM {$_pre}module WHERE id='$id' ");	
	$array=unserialize($rsdb[config]);	
	$_rs=$array[field_db][$field_name];	
	if($_rs[field_name]=='content'){	
		$readonly=" readony ";	
	}	
	$_rs[field_leng]<1 && $_rs[field_leng]='';	
	$search[$_rs[search]]=" checked ";	
	$mustfill[$_rs[mustfill]]=" checked ";	
	$form_type[$_rs[form_type]]=" selected ";	
	$field_type[$_rs[field_type]]=" selected ";
	$listshow[intval($_rs[listshow])]=" checked ";	
	$group_view=group_box("postdb[allowview]",explode(",",$_rs[allowview]));	
	
	get_admin_html('editfield');
}	
elseif($action=="editfield")	
{	
	$postdb[allowview]=implode(",",$postdb[allowview]);	
	
	$rsdb=$db->get_one("SELECT * FROM {$_pre}module WHERE id='$id' ");	
	
	$array=unserialize($rsdb[config]);	
	
	$field_array=$array[field_db][$field_name];	
	
	if(!ereg("^([a-z])([a-z0-9_]+)$",$postdb[field_name])){	
		showmsg("字段ID不符合规则");	
	}	
	if(table_field("{$_pre}content",$postdb[field_name])||table_field("{$_pre}content_$id",$postdb[field_name])){	
		if($postdb[field_name]!=$field_name){	
			showmsg("此字段ID已受保护或已存在,请更换一个");	
		}	
			
	}	
	if($postdb[field_name]!=$field_name&&$field_name=="content"){	
		//showmsg("此字段ID已受保护,你不能更换其字段ID名");	
	}	
	$postdb[field_leng]=intval($postdb[field_leng]);	
	
	if($postdb[field_type]=='int')	
	{	
		if( $postdb[field_leng]>10 || $postdb[field_leng]<1 ){	
			$postdb[field_leng]=10;	
		}	
		$db->query("ALTER TABLE `{$_pre}content_$id` CHANGE `{$field_array[field_name]}` `{$postdb[field_name]}` INT( $postdb[field_leng] ) NOT NULL");	
	}	
	elseif($postdb[field_type]=='varchar')	
	{	
		if( $postdb[field_leng]>255 || $postdb[field_leng]<1 ){	
			$postdb[field_leng]=255;	
		}	
		$db->query("ALTER TABLE `{$_pre}content_$id` CHANGE `{$field_array[field_name]}` `{$postdb[field_name]}` VARCHAR ( $postdb[field_leng] ) NOT NULL");	
	}	
	elseif($postdb[field_type]=='mediumtext')	
	{	
		$db->query("ALTER TABLE `{$_pre}content_$id` CHANGE `{$field_array[field_name]}` `{$postdb[field_name]}` MEDIUMTEXT NOT NULL");	
	}	
	unset($array[field_db][$field_name]);	
	$array[field_db]["{$postdb[field_name]}"]=$postdb;	
	if($postdb[search]){	
		$array[search_db][$field_name]=$postdb[title];	
	}else{	
		unset($array[search_db][$field_name]);	
	}
	
	if($postdb[listshow]){	
		$array[listshow_db][$field_name]=$postdb[title];	
	}else{	
		unset($array[listshow_db][$field_name]);	
	}

	if($postdb[form_type]=='ieedit'){	
		$array[is_html][$field_name]=$postdb[title];	
	}else{	
		unset($array[is_html][$field_name]);	
	}	
	if($postdb[form_type]=='upfile'){	
		$array[is_upfile][$field_name]=$postdb[title];	
	}else{	
		unset($array[is_upfile][$field_name]);	
	}

	//排序
	foreach( $array[field_db] AS $key=>$value ){
		$_listdb[intval($value[orderlist])]=$value;
	}
	krsort($_listdb);
	unset($listdb);
	foreach( $_listdb AS $key=>$rs){
		$listdb[$rs[field_name]]=$rs;
	}
	$array[field_db]=$listdb+$array[field_db];


	$config=addslashes(serialize($array));	
	$db->query("UPDATE {$_pre}module SET config='$config' WHERE id='$id' ");	
	jump("修改成功<br><a href='$admin_path&job=tpl&id=$id' style='color:red;font-size:25px'>务必注意！！你必须点击生成模板,模块才能生效</a> ","$admin_path&job=editfield&id=$id&field_name=$postdb[field_name]",10);	
}	
elseif($job=="addfield")	
{	
	$rsdb=$db->get_one("SELECT * FROM {$_pre}module WHERE id='$id' ");	
	//$group_view=group_box("postdb[allowview]",explode(",",$rsdb[allowview]));	
	$_rs[field_type]='mediumtext';	
	$field_type[$_rs[field_type]]=" selected ";	
	$_rs[field_name]="my_".rand(1,999);	
	$_rs[title]="我的字段$_rs[field_name]";	
	$mustfill[0]=$search[0]=' checked ';
	$listshow[intval($_rs[listshow])]=" checked ";	

	get_admin_html('editfield');
}	
elseif($action=="addfield")	
{	
	$postdb[allowview]=implode(",",$postdb[allowview]);	
	if(!ereg("^([a-z])([a-z0-9_]+)$",$postdb[field_name])){	
		showmsg("字段ID不符合规则");	
	}	
	if(table_field("{$_pre}content",$postdb[field_name])||table_field("{$_pre}content_$id",$postdb[field_name])){	
		showmsg("此字段ID已受保护或已存在,请更换一个");	
	}	
	$postdb[field_leng]=intval($postdb[field_leng]);	
	
	if($postdb[field_type]=='int')	
	{	
		if( $postdb[field_leng]>10 || $postdb[field_leng]<1 ){	
			$postdb[field_leng]=10;	
		}	
		$db->query("ALTER TABLE `{$_pre}content_$id` ADD `{$postdb[field_name]}` INT( $postdb[field_leng] ) NOT NULL");	
	}	
	elseif($postdb[field_type]=='varchar')	
	{	
		if( $postdb[field_leng]>255 || $postdb[field_leng]<1 ){	
			$postdb[field_leng]=255;	
		}	
		$db->query("ALTER TABLE `{$_pre}content_$id` ADD `{$postdb[field_name]}` VARCHAR( $postdb[field_leng] ) NOT NULL");	
	}	
	elseif($postdb[field_type]=='mediumtext')	
	{	
		$db->query("ALTER TABLE `{$_pre}content_$id` ADD `{$postdb[field_name]}` MEDIUMTEXT NOT NULL");	
	}	
	
	$rsdb=$db->get_one("SELECT * FROM {$_pre}module WHERE id='$id' ");	
	$field_name=$postdb[field_name];	
	$array=unserialize($rsdb[config]);	
	$array[field_db][$field_name]=$postdb;	
	if($postdb[search]){	
		$array[search_db][$field_name]=$postdb[title];	
	}else{	
		unset($array[search_db][$field_name]);	
	}
	if($postdb[listshow]){	
		$array[listshow_db][$field_name]=$postdb[title];	
	}else{	
		unset($array[listshow_db][$field_name]);	
	}

	if($postdb[form_type]=='ieedit'){	
		$array[is_html][$field_name]=$postdb[title];	
	}else{	
		unset($array[is_html][$field_name]);	
	}	
	if($postdb[form_type]=='upfile'){	
		$array[is_upfile][$field_name]=$postdb[title];	
	}else{	
		unset($array[is_upfile][$field_name]);	
	}	
	$config=addslashes(serialize($array));	
	$db->query("UPDATE {$_pre}module SET config='$config' WHERE id='$id' ");	
	jump("添加成功<br><a href='$admin_path&job=tpl&id=$id' style='color:red;font-size:25px'>务必注意！！你必须点击生成模板,模块才能生效</a>","$admin_path&job=listfield&id=$id",10);	
}	
elseif($action=="delfield")	
{	
	if($field_name=="content"){	
		//showmsg("受保护字段,你不能删除");	
	}	
	$rsdb=$db->get_one("SELECT * FROM {$_pre}module WHERE id='$id' ");	
	$array=unserialize($rsdb[config]);	
	unset($array[field_db][$field_name]);	
	$config=addslashes(serialize($array));	
	$db->query("UPDATE {$_pre}module SET config='$config' WHERE id='$id' ");	
	$db->query("ALTER TABLE `{$_pre}content_$id` DROP `$field_name`");	
	jump("删除成功<br><a href='$admin_path&job=tpl&id=$id' style='color:red;font-size:25px'>务必注意！！你必须点击生成模板,模块才能生效</a> ",$FROMURL);	
}	
elseif($job=='tpl')	
{	
	if($automaketpl){	//批量生成模板
		$autojump="autopost();";
		$page=intval($page);
		$rsdb=$db->get_one("SELECT * FROM {$_pre}module LIMIT $page,1 ");
		$id=$rsdb[id];
		if(!$id){
			jump("模板生成完毕","$admin_path&job=list",3);
		}
		$page++;
	}else{
		$rsdb=$db->get_one("SELECT * FROM {$_pre}module WHERE id='$id' ");
	}
		
	$array=unserialize($rsdb[config]);	
	$tpl_L1=$tpl_L2=$tpl_sarch2=$tpl_sarch1='';
	foreach( $array[field_db] AS $key=>$rs){	
		$tpl_p.=make_post_table($rs);	
		$tpl_s.=make_show_table($rs);	
		if($array[search_db][$key]){	
			if($rs[form_type]=="select"||$rs[form_type]=="radio"||$rs[form_type]=="checkbox"){	
				$show=make_search_table($rs);	
				$tpl_sarch2.="<tr><td>{$rs[title]}:</td><td>$show</td></tr>";	
			}else{	
				$tpl_sarch1.=make_search_table($rs);	
			}	
		}
		if($array[listshow_db][$key]){
			$tpl_L1.="<td align='center'>{$rs[title]}</td>";
			$tpl_L2.="<td align='center'>\$rs[{$rs[field_name]}] {$rs[form_units]}</td>";
		}
	}
	//前台列表页模板
	if(is_file(Adminpath."template/form_module/tpl/list_$id.htm")){
		$list_tpl_file=Adminpath."template/form_module/tpl/list_$id.htm";
	}else{
		$list_tpl_file=Adminpath."template/form_module/tpl/list.htm";
	}
	$list_tpl=str_replace(array('$TempLate1','$TempLate2',"<",">"),array($tpl_L1,$tpl_L2,"&lt;","&gt;"),read_file($list_tpl_file));
	
	//后台列表页模板
	if(is_file(Adminpath."template/form_module/tpl/admin_list_$id.htm")){
		$admin_list_tpl_file=Adminpath."template/form_module/tpl/admin_list_$id.htm";
	}else{
		$admin_list_tpl_file=Adminpath."template/form_module/tpl/admin_list.htm";
	}		
	$admin_list_tpl=str_replace(array('$TempLate1','$TempLate2',"<",">"),array($tpl_L1,$tpl_L2,"&lt;","&gt;"),read_file($admin_list_tpl_file));
	
	//搜索页模板
	if(is_file(Adminpath."template/form_module/tpl/post_$id.htm")){
		$search_tpl_file=Adminpath."template/form_module/tpl/search_$id.htm";
	}else{
		$search_tpl_file=Adminpath."template/form_module/tpl/search.htm";
	}
	$search_tpl=str_replace(array('$TempLate1','$TempLate2',"<",">"),array($tpl_sarch1,$tpl_sarch2,"&lt;","&gt;"),read_file($search_tpl_file));

	//前台发布页模板,没有后台发布页
	if(is_file(Adminpath."template/form_module/tpl/post_$id.htm")){
		$post_tpl_file=Adminpath."template/form_module/tpl/post_$id.htm";
	}else{
		$post_tpl_file=Adminpath."template/form_module/tpl/post.htm";
	}
	$post_tpl=str_replace(array('$TempLate',"<",">"),array($tpl_p,"&lt;","&gt;"),read_file($post_tpl_file));
	
	//前台内容页模板
	if(is_file(Adminpath."template/form_module/tpl/bencandy_$id.htm")){
		$show_tpl_file=Adminpath."template/form_module/tpl/bencandy_$id.htm";
	}else{
		$show_tpl_file=Adminpath."template/form_module/tpl/bencandy.htm";
	}		
	$show_tpl=str_replace(array('$TempLate',"<",">"),array($tpl_s,"&lt;","&gt;"),read_file($show_tpl_file));
	
	//后台内容页模板:
	if(is_file(Adminpath."template/form_module/tpl/admin_bencandy_$id.htm")){
		$admin_show_tpl_file=Adminpath."template/form_module/tpl/admin_bencandy_$id.htm";
	}else{
		$admin_show_tpl_file=Adminpath."template/form_module/tpl/admin_bencandy.htm";
	}		
	$admin_show_tpl=str_replace(array('$TempLate',"<",">"),array($tpl_s,"&lt;","&gt;"),read_file($admin_show_tpl_file));
	
	get_admin_html('tpl');
}	
elseif($action=='tpl')	
{	
	$tpl_post=stripslashes($tpl_post);
	$tpl_list=stripslashes($tpl_list);
	$tpl_show=stripslashes($tpl_show);

	$tpl_search=stripslashes($tpl_search);

	$admin_tpl_list=stripslashes($admin_tpl_list);
	$admin_tpl_show=stripslashes($admin_tpl_show);
	 
	if(!is_dir(ROOT_PATH."$dirname/data/form_tpl")){	
		makepath(ROOT_PATH."$dirname/data/form_tpl");	
	}	
	write_file(ROOT_PATH."$dirname/data/form_tpl/post_$id.htm",$tpl_post);

	write_file(ROOT_PATH."$dirname/data/form_tpl/search_$id.htm",$tpl_search);

	write_file(ROOT_PATH."$dirname/data/form_tpl/bencandy_$id.htm",$tpl_show);
	write_file(ROOT_PATH."$dirname/data/form_tpl/list_$id.htm",$tpl_list);

	write_file(ROOT_PATH."$dirname/data/form_tpl/admin_bencandy_$id.htm",$admin_tpl_show);
	write_file(ROOT_PATH."$dirname/data/form_tpl/admin_list_$id.htm",$admin_tpl_list);

	if(!is_writable(ROOT_PATH."$dirname/data/form_tpl/post_$id.htm")){	
		showmsg("/$dirname/data/form_tpl/post_$id.htm模板生成失败,有可能是目录权限不可写,请手工创建一个,复制代码进去");	
	}
	if(!is_writable(ROOT_PATH."$dirname/data/form_tpl/search_$id.htm")){	
		showmsg("/$dirname/data/form_tpl/search_$id.htm模板生成失败,有可能是目录权限不可写,请手工创建一个,复制代码进去");	
	}
	if(!is_writable(ROOT_PATH."$dirname/data/form_tpl/bencandy_$id.htm")){	
		showmsg("/$dirname/data/form_tpl/bencandy_$id.htm模板生成失败,有可能是目录权限不可写,请手工创建一个,复制代码进去");	
	}
	if(!is_writable(ROOT_PATH."$dirname/data/form_tpl/list_$id.htm")){	
		showmsg("/$dirname/data/form_tpl/list_$id.htm模板生成失败,有可能是目录权限不可写,请手工创建一个,复制代码进去");	
	}

	if(!is_writable(ROOT_PATH."$dirname/data/form_tpl/admin_bencandy_$id.htm")){	
		showmsg("/$dirname/data/form_tpl/admin_bencandy_$id.htm模板生成失败,有可能是目录权限不可写,请手工创建一个,复制代码进去");	
	}
	if(!is_writable(ROOT_PATH."$dirname/data/form_tpl/admin_list_$id.htm")){	
		showmsg("/$dirname/data/form_tpl/admin_list_$id.htm模板生成失败,有可能是目录权限不可写,请手工创建一个,复制代码进去");	
	}
	if($automaketpl){
		jump("请稍候,正在生成下一个模板","$admin_path&job=$action&page=$page&automaketpl=$automaketpl",1);
	}else{
		jump("模板生成完毕","$admin_path&job=listfield&id=$id");
	}		
}	
elseif($action=="delete")	
{	
	$rs=$db->get_one("SELECT count(*) AS num FROM {$_pre}content WHERE mid='$id' ");	
	if($rs[num]){	
		showmsg("本模块频道已有内容了,请先删除内容");	
	}	
	$db->query(" DELETE FROM `{$_pre}module` WHERE id='$id' ");	
	$id>0 && $db->query(" DROP TABLE IF EXISTS `{$_pre}content_{$id}`");	

	unlink(ROOT_PATH."$dirname/template/default/post_$id.htm");	
	unlink(ROOT_PATH."$dirname/data/form_tpl/bencandy_$id.htm");	
	unlink(ROOT_PATH."$dirname/data/form_tpl/search_$id.htm");

	jump("删除成功","$admin_path&job=list");	
}	
	
	
	

	
	
	
	
function make_post_table($rs){	
	if($rs[mustfill]=='2'||$rs[form_type]=='pingfen'){	
		return ;	
	}elseif($rs[mustfill]=='1'){	
		$mustfill='<font color=red>(必填)</font>';	
	}	
	if($rs[form_type]=='text')	
	{	
		$rs[field_inputleng]>0 || $rs[field_inputleng]=10;
		$show="<tr> <td >{$rs[title]}:$mustfill<br>{$rs[form_title]}</td> <td > <input type='text' name='postdb[{$rs[field_name]}]' id='atc_{$rs[field_name]}' size='$rs[field_inputleng]' value='\$rsdb[{$rs[field_name]}]'> $rs[form_units]</td></tr>";	
	}
	elseif($rs[form_type]=='time')	
	{	
		$show="<tr> <td >{$rs[title]}:$mustfill<br>{$rs[form_title]}</td> <td > <input  onclick=\"setday(this,1)\" type='text' name='postdb[{$rs[field_name]}]' id='atc_{$rs[field_name]}' size='20' value='\$rsdb[{$rs[field_name]}]'> $rs[form_units]</td></tr>";	
	}
	elseif($rs[form_type]=='upfile')	
	{	
		$show="<tr> <td >{$rs[title]}:$mustfill<br>{$rs[form_title]}</td> <td > <input type='text' name='postdb[{$rs[field_name]}]' id='atc_{$rs[field_name]}' size='50' value='\$rsdb[{$rs[field_name]}]'> $rs[form_units]<br><iframe frameborder=0 height=23 scrolling=no src='\$webdb[www_url]/do/upfile.php?fn=upfile&dir=\$_pre\$fid&label=atc_{$rs[field_name]}' width=310></iframe> </td></tr>";	
	}	
	elseif($rs[form_type]=='upmorefile')	
	{	
		$show="<tr> <td >{$rs[title]}:$mustfill<br>{$rs[form_title]}<br>增加 <input type='text' size='3' name='nums_{$rs[field_name]}' value='2'> 项 <input type='button' name='Submit2' value='增加' onClick='showinput_{$rs[field_name]}()'></td> <td ><!--	
EOT;
\$num=count(\$rsdb[{$rs[field_name]}][url]);	
\$num||\$num=1;	
for( \$i=0; \$i<\$num ;\$i++ ){	
print <<<EOT
--> 名称: <input type=\"text\" name=\"postdb[{$rs[field_name]}][name][]\" id=\"atc_{$rs[field_name]}_name\$i\" size=\"15\" value=\"{\$rsdb[{$rs[field_name]}][name][\$i]}\">	
 消耗{\$webdb[MoneyName]}: <input type=\"text\" name=\"postdb[{$rs[field_name]}][fen][]\" id=\"atc_{$rs[field_name]}_fen\$i\" size=\"3\" value=\"{\$rsdb[{$rs[field_name]}][fen][\$i]}\">	
 地址: 	
                    <input type=\"text\" name=\"postdb[{$rs[field_name]}][url][]\" id=\"atc_{$rs[field_name]}_url\$i\" size=\"30\" value=\"{\$rsdb[{$rs[field_name]}][url][\$i]}\">	
                    [<a href='javascript:' onClick='window.open(\"\$webdb[www_url]/do/upfile.php?fn=upfile_{$rs[field_name]}&dir=\$_pre\$fid&label=\$i\",\"\",\"width=350,height=50,top=200,left=400\")'><font color=\"#FF0000\">点击上传文件</font></a>] 	
                    <br><!--	
EOT;
}
print <<<EOT
--><div id=\"input_{$rs[field_name]}\"></div>	
<script LANGUAGE=\"JavaScript\">	
totalnum_{$rs[field_name]}=0;	
function showinput_{$rs[field_name]}(){	
	var str=document.getElementById(\"input_{$rs[field_name]}\").innerHTML;	
	var num=2;	
	num=document.FORM.nums_{$rs[field_name]}.value;	
	for(var i=1;i<=num;i++){	
		totalnum_{$rs[field_name]}=totalnum_{$rs[field_name]}+i+\$num-1;	
	    str+='名称: <input type=\"text\" name=\"postdb[{$rs[field_name]}][name][]\" id=\"atc_{$rs[field_name]}_name'+totalnum_{$rs[field_name]}+'\" size=\"15\"> 消耗{\$webdb[MoneyName]}: <input type=\"text\" name=\"postdb[{$rs[field_name]}][fen][]\" id=\"atc_{$rs[field_name]}_fen'+totalnum_{$rs[field_name]}+'\" size=\"3\"> 地址: <input type=\"text\" name=\"postdb[{$rs[field_name]}][url][]\" id=\"atc_{$rs[field_name]}_url'+totalnum_{$rs[field_name]}+'\" size=\"30\" > [<a href=\'javascript:\' onClick=\'window.open(\"\$webdb[www_url]/do/upfile.php?fn=upfile_{$rs[field_name]}&dir=\$_pre\$fid&label='+totalnum_{$rs[field_name]}+'\",\"\",\"width=350,height=50,top=200,left=400\")\'><font color=\"#FF0000\">点击上传文件</font></a>]<br>';	
	}	
	document.getElementById(\"input_{$rs[field_name]}\").innerHTML=str;	
}	
	
function upfile_{$rs[field_name]}(url,name,size,label){	
	document.getElementById(\"atc_{$rs[field_name]}_url\"+label).value=url;	
	arr=name.split('.');	
	document.getElementById(\"atc_{$rs[field_name]}_name\"+label).value=arr[0];	
}	
</SCRIPT></td></tr>";	
	}	
	elseif($rs[form_type]=='textarea')	
	{	
		$show="<tr><td >{$rs[title]}:$mustfill<br>{$rs[form_title]}</td><td ><textarea name='postdb[{$rs[field_name]}]' id='atc_{$rs[field_name]}' cols='70' rows='8'>\$rsdb[{$rs[field_name]}]</textarea>$rs[form_units]</td></tr>";	
	}	
	elseif($rs[form_type]=='ieedit')	
	{	
		$show="<tr><td >{$rs[title]}:$mustfill<br>{$rs[form_title]}</td><td ><iframe id='eWebEditor1' src='\$webdb[www_url]/ewebeditor/ewebeditor.php?id=atc_{$rs[field_name]}&style=standard&&etype=1' frameborder='0' scrolling='no' width='90%' height='250'></iframe>$rs[form_units]<input name='postdb[{$rs[field_name]}]' id='atc_{$rs[field_name]}' type='hidden' value='\$rsdb[{$rs[field_name]}]'></td></tr>";	
	}	
	elseif($rs[form_type]=='select')	
	{	
		$detail=explode("\r\n",$rs[form_set]);	
		foreach( $detail AS $key=>$value){	
			if($value===''){	
				continue;	
			}	
			list($v1,$v2)=explode("|",$value);	
			$v2 || $v2=$v1;	
			$_show.="<option value='$v1' {\$rsdb[{$rs[field_name]}]['{$v1}']}>$v2</option>";	
		}	
		$show="<tr> <td >{$rs[title]}:$mustfill<br>{$rs[form_title]}</td><td > <select name='postdb[{$rs[field_name]}]' id='atc_{$rs[field_name]}'>$_show</select>$rs[form_units]</td> </tr>";	
	}	
	elseif($rs[form_type]=='radio')	
	{	
		$detail=explode("\r\n",$rs[form_set]);	
		foreach( $detail AS $key=>$value){	
			if($value===''){	
				continue;	
			}	
			list($v1,$v2)=explode("|",$value);	
			$v2 || $v2=$v1;	
			$_show.="<input style='border:0px;' type='radio' name='postdb[{$rs[field_name]}]' value='$v1' {\$rsdb[{$rs[field_name]}]['{$v1}']}>$v2";	
		}	
		$show="<tr> <td >{$rs[title]}:$mustfill<br>{$rs[form_title]}</td> <td >$_show$rs[form_units]</td></tr>";	
	}	
	elseif($rs[form_type]=='checkbox')	
	{	
		$detail=explode("\r\n",$rs[form_set]);	
		foreach( $detail AS $key=>$value){	
			if($value===''){	
				continue;	
			}	
			list($v1,$v2)=explode("|",$value);	
			$v2 || $v2=$v1;	
			$_show.="<input style='border:0px;' type='checkbox' name='postdb[{$rs[field_name]}][]' value='$v1' {\$rsdb[{$rs[field_name]}]['{$v1}']}>{$v2}&nbsp;&nbsp;";	
		}	
		$show="<tr> <td >{$rs[title]}:$mustfill<br>{$rs[form_title]}</td> <td >$_show$rs[form_units]</td></tr>";	
	}	
	return $show;	
}	
	
function make_show_table($rs){	
	if($rs[mustfill]=='2'){
		return ;	
	}	
	if($rs[form_type]=='pingfen'){	
		$detail=explode("\r\n",$rs[form_set]);	
		foreach( $detail AS $key=>$value){	
			if($value===''){	
				continue;	
			}	
			list($v1,$v2)=explode("|",$value);	
			$v2 || $v2=$v1;	
			$selected=$v1==$rs[form_value]?' selected ':'';	
			$_show.="<option value='$v1' {\$rsdb[{$rs[field_name]}]['{$v1}']} $selected>$v2</option>";	
		}	
		$show="<select name='postdb[{$rs[field_name]}]' id='atc_{$rs[field_name]}'>$_show</select>&nbsp;<input type='submit' value='post'><input type='hidden' name='id' value='\$id'>";	
	}	
	$show="<tr> <td style='border-bottom:1px dotted #ccc;'>{$rs[title]}:</td> <td  style='border-bottom:1px dotted #ccc;'><table width='100%' border='0' cellspacing='0' cellpadding='0' style='TABLE-LAYOUT: fixed;WORD-WRAP: break-word;'><tr><td>{\$rsdb[{$rs[field_name]}]}&nbsp;{$rs[form_units]}&nbsp;&nbsp;$show</td></tr></table></td></tr>";	
	if($rs[form_type]=='pingfen'){	
		//$show="<form method='post' action='job.php?action=pingfen'>$show</form>";	
	}	
	return $show;	
}	
	
function make_search_table($rs)	
{	
	if($rs[form_type]=="select"||$rs[form_type]=="radio"||$rs[form_type]=="checkbox")	
	{	
		$detail=explode("\r\n",$rs[form_set]);	
		foreach( $detail AS $key=>$value){	
			if(!$value){	
				continue;	
			}	
			list($v1,$v2)=explode("|",$value);	
			$v2 || $v2=$v1;	
			$_show.="<option value='$v1' {\$rsdb[{$rs[field_name]}]['{$v1}']}>$v2</option>";	
		}	
		$show="<select name='postdb[{$rs[field_name]}]' id='atc_{$rs[field_name]}'><option value=''>不限</option>$_show</select>";			
	}	
	else	
	{	
		$show="&nbsp;<input type='radio' name='type' value='{$rs[field_name]}' \$typedb[{$rs[field_name]}]>{$rs[title]} ";	
	}	
	return $show;	
}	
?>