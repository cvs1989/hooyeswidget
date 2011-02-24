<?php
require_once("global.php");
$mid=intval($mid);
$fidDB=$db->get_one("SELECT * FROM {$_pre}module WHERE id='$mid'");

if(!$fidDB){
	showerr("资料有误");
}
if($fidDB[allowpost]&&!in_array($groupdb['gid'],explode(',',$fidDB[allowpost]))){
	showerr("你所在用户组无权限");
}

if($fidDB[endtime]&&$fidDB[endtime]<$timestamp){
	showerr("已到截止日期,你不能再操作.");
}

if($action=="postnew"&&!$fidDB[repeatpost]){
	if($lfjuid){
		if($db->get_one("SELECT * FROM {$_pre}content WHERE uid='$lfjuid' AND mid='$mid'")){
			showerr("你不能重复提交");
		}
	}else{
		if($db->get_one("SELECT * FROM {$_pre}content WHERE ip='$onlineip' AND mid='$mid'")){
			showerr("你不能重复提交");
		}
	}
}

//删除
if($action=="del"){
	if(!$lfjid){
		showerr("请先登录");
	}
	$rsdb=$db->get_one("SELECT * FROM {$_pre}content WHERE id='$id'");
	if($rsdb[uid]!=$lfjuid&&!$web_admin){
		showerr("你没权限!");
	}
	$db->query("DELETE FROM {$_pre}content WHERE id='$id'");
	$db->query("DELETE FROM `{$pre}form_reply` WHERE id='$id'");
	$rsdb[mid] && $db->query("DELETE FROM {$_pre}content_$rsdb[mid] WHERE id='$id'");
	refreshto("$webdb[www_url]/","删除成功",1);
}

/**
*模块参数配置文件
**/
$m_config=unserialize($fidDB[config]);

//$STYLE=$fidDB[style];

/*模板*/
$FidTpl=unserialize($fidDB[template]);

if($_FILES){
	foreach( $_FILES AS $key=>$value ){
		$i=(int)substr($key,10);
		if(is_array($value)){
			$postfile=$value['tmp_name'];
			$array[name]=$value['name'];
			$array[size]=$value['size'];
		}else{
			$postfile=$$key;
			$array[name]=${$key.'_name'};
			$array[size]=${$key.'_size'};
		}
		if($ftype[$i]=='in'&&$array[name]){

			$array[path]=$webdb[updir]."/form";
			$array[updateTable]=1;	//统计用户上传的文件占用空间大小
			$filename=upfile($postfile,$array);
		}
	}
}

/**处理提交的新发表内容**/
if($action=="postnew")
{
	foreach( $m_config[field_db] AS $key=>$rs )
	{
		if( $rs[mustfill]==1 && $postdb[$rs[field_name]]=='' )
		{
			showerr("{$rs[title]}不能为空");
		}

		if( ($rs[mustfill]==2||$rs[form_type]=='pingfen') && $postdb[$rs[field_name]] )
		{
			//showerr("{$rs[title]}不能私自提交内容");
		}

		if($rs[field_type]=='int'&&$postdb[$rs[field_name]]&&!ereg("^[-0-9]+$",$postdb[$rs[field_name]]))
		{
			showerr("{$rs[title]}只能为数字");
		}

		if($rs[field_type]=='varchar')
		{
			$rs[field_leng]=$rs[field_leng]?$rs[field_leng]:255;
			if(strlen( $postdb[$rs[field_name]] )>$rs[field_leng])
			{
				showerr("{$rs[title]}不能超过{$rs[field_leng]}个字符,一个汉字等于两个字符");
			}
		}

		if($rs[field_type]=='int')
		{
			$rs[field_leng]=$rs[field_leng]?$rs[field_leng]:10;
			if(strlen( $postdb[$rs[field_name]] )>$rs[field_leng])
			{
				showerr("{$rs[title]}不能超过{$rs[field_leng]}个字符");
			}
		}

		if($rs[form_type]=='upmorefile')
		{
			unset($_array);
			foreach( $postdb[$rs[field_name]][url] AS $key=>$value)
			{
				if(!$value){
					continue;
				}
				$_array[]="$value@@@{$postdb[$rs[field_name]][name][$key]}@@@{$postdb[$rs[field_name]][fen][$key]}";
			}
			$postdb[$rs[field_name]]=implode("\n",$_array);
		}
	}

	
	/*对使用了在线编辑器的字段提交的附件地址作处理*/
	foreach( $m_config[is_html] AS $key=>$value)
	{
		$postdb[$key]=str_replace("<img ","<img onload=\'if(this.width>600)makesmallpic(this,600,800);\' ",$postdb[$key]);
		//图片目录转移
		$postdb[$key]=move_attachment($lfjdb[uid],$postdb[$key],"form");
		//获取远程图片
		$postdb[$key]=get_out_pic($postdb[$key],$GetOutPic);
		$postdb[$key] = En_TruePath($postdb[$key]);
		$postdb[$key] = preg_replace('/javascript/i','java script',$postdb[$key]);//过滤js代码
		$postdb[$key] = preg_replace('/<iframe ([^<>]+)>/i','&lt;iframe \\1>',$postdb[$key]);//过滤框架代码
	}
	
	$_array=array_flip($m_config[is_html]);
	
	/**
	*提交的内容如果是复选框,就要做处理,如果不是在线编辑器的,也要做过滤,显然,使用在线编辑器是有危险的
	**/
	foreach( $postdb AS $key=>$value)
	{
		if(is_array($value))
		{
			$postdb[$key]=implode("/",$value);
		}
		elseif(!@in_array($key,$_array))
		{
			$postdb[$key]=filtrate($value);
		}
	}

	$db->query("INSERT INTO `{$_pre}content` (`title`, `mid`, `hits`, `posttime`, `list`, `uid`, `username`, `titlecolor`, `yz`, `ip`) VALUES ('$postdb[title]','$fidDB[id]','0','$timestamp','$timestamp','$lfjuid','$lfjid','','0','$onlineip')");

	$rs=$db->get_one("SELECT * FROM `{$_pre}content` ORDER BY id DESC LIMIT 1");
	$id=$rs[id];

	unset($sqldb);
	$sqldb['id']="id='$id'";
	$sqldb['uid']="uid='$lfjuid'";

	/*检查判断辅信息表要插入哪些字段的内容*/
	$array = table_field("{$_pre}content_$fidDB[id]");
	foreach( $array AS $key=>$value)
	{
		if($value=="id"||$value=="uid")
		{
			continue;
		}
		isset($postdb[$value]) && $sqldb["$value"]="`{$value}`='{$postdb[$value]}'";
	}
	
	$sql=implode(",",$sqldb);
	$db->query("INSERT INTO `{$_pre}content_$fidDB[id]` SET $sql");
	
	//在线支付
	if($postdb[paytype]=='olpay'&&$postdb[paymoney]>0){
		$pay_code=str_replace('+','%2B', mymd5("form\t$postdb[paymoney]\t$id\t$mid"));
		echo "<META HTTP-EQUIV=REFRESH CONTENT='0;URL=$webdb[www_url]/do/olpay.php?pay_code=$pay_code'>";
		exit;
	}
	
	refreshto("bencandy_form.php?mid=$mid&id=$id","表单提交成功 ",5);
}

/*编辑内容*/
elseif($job=="edit")
{
	$rsdb=$db->get_one("SELECT A.*,B.* FROM `{$_pre}content` A LEFT JOIN `{$_pre}content_$fidDB[id]` B ON A.id=B.id WHERE A.id='$id'");

	if($rsdb[uid]!=$lfjuid&&!$web_admin)
	{
		showerr("你无权修改");
	}
	
	/*对附件地址作还原*/
	foreach( $m_config[is_html] AS $key=>$value)
	{
		$rsdb[$key]=editor_replace($rsdb[$key]);
		$rsdb[$key]=En_TruePath($rsdb[$key],0);
	}

	/*表单默认变量作处理*/
	set_table_value($m_config[field_db]);

	$atc="edit";

	require(ROOT_PATH."inc/head.php");
	require("data/form_tpl/post_$fidDB[id].htm");
	require(ROOT_PATH."inc/foot.php");
}

/*处理提交的内容做修改*/
elseif($action=="edit")
{

	$rsdb=$db->get_one("SELECT A.*,B.* FROM `{$_pre}content` A LEFT JOIN `{$_pre}content_$fidDB[id]` B ON A.id=B.id WHERE A.id='$id' ");

	if($rsdb[uid]!=$lfjuid&&!$web_admin)
	{
		showerr("你无权修改");
	}

	foreach( $m_config[field_db] AS $key=>$rs )
	{
		if( $rs[mustfill]==1 && $postdb[$rs[field_name]]==='' )
		{
			showerr("{$rs[title]}不能为空");
		}

		if( ($rs[mustfill]==2||$rs[form_type]=='pingfen') && $postdb[$rs[field_name]] )
		{
			//showerr("{$rs[title]}不能私自提交内容");
		}

		if($rs[field_type]=='int'&&$postdb[$rs[field_name]]&&!ereg("^[-0-9]+$",$postdb[$rs[field_name]]))
		{
			showerr("{$rs[title]}只能为数字");
		}

		if($rs[field_type]=='varchar')
		{
			$rs[field_leng]=$rs[field_leng]?$rs[field_leng]:255;
			if(strlen( $postdb[$rs[field_name]] )>$rs[field_leng])
			{
				showerr("{$rs[title]}不能超过{$rs[field_leng]}个字符,一个汉字等于两个字符");
			}
		}
		if($rs[field_type]=='int')
		{
			$rs[field_leng]=$rs[field_leng]?$rs[field_leng]:10;
			if(strlen( $postdb[$rs[field_name]] )>$rs[field_leng])
			{
				showerr("{$rs[title]}不能超过{$rs[field_leng]}个字符");
			}
		}
		if($rs[form_type]=='upmorefile')
		{
			unset($_array);
			foreach( $postdb[$rs[field_name]][url] AS $key=>$value)
			{
				if(!$value){
					continue;
				}
				$_array[]="$value@@@{$postdb[$rs[field_name]][name][$key]}@@@{$postdb[$rs[field_name]][fen][$key]}";
			}
			$postdb[$rs[field_name]]=implode("\n",$_array);
		}
	}
	
	
	/*对使用了在线编辑器的字段提交的附件地址作处理*/
	foreach( $m_config[is_html] AS $key=>$value)
	{
		$postdb[$key]=str_replace("<img ","<img onload=\'if(this.width>600)makesmallpic(this,600,800);\' ",$postdb[$key]);
		//图片目录转移
		$postdb[$key]=move_attachment($lfjdb[uid],$postdb[$key],"$form");

		//获取远程图片
		$postdb[$key]=get_out_pic($postdb[$key],$GetOutPic);

		$postdb[$key]=En_TruePath($postdb[$key]);
		$postdb[$key] = preg_replace('/javascript/i','java script',$postdb[$key]);//过滤js代码
		$postdb[$key] = preg_replace('/<iframe ([^<>]+)>/i','&lt;iframe \\1>',$postdb[$key]);//过滤框架代码
	}
	
	$_array=array_flip($m_config[is_html]);

	/**
	*提交的内容如果是复选框,就要做处理,如果不是在线编辑器的,也要做过滤,显然,使用在线编辑器是有危险的
	**/
	foreach( $postdb AS $key=>$value){
		if(is_array($value))
		{
			$postdb[$key]=implode("/",$value);
		}
		elseif(!@in_array($key,$_array)&&$key!='template')
		{
			$postdb[$key]=filtrate($value);
		}
	}
	
	$db->query("UPDATE `{$_pre}content` SET title='$postdb[title]' WHERE id='$id'");


	/*检查判断辅信息表要插入哪些字段的内容*/
	unset($sqldb);
	$array = table_field("{$_pre}content_$fidDB[id]");
	foreach( $array AS $key=>$value)
	{
		if($value=="id"||$value=="uid")
		{
			continue;
		}

		/*这里必须要做判断,不然的话,一些二次开发用的字段里的值就可能被清空*/
		isset($postdb[$value]) && $sqldb[]="`$value`='{$postdb[$value]}'";
	}
	$sql=implode(",",$sqldb);

	/*更新辅信息表*/
	$db->query("UPDATE `{$_pre}content_$fidDB[id]` SET $sql WHERE id='$id' ");
	refreshto("bencandy_form.php?mid=$mid&id=$id","修改成功");	
}
else
{
	//URL变量做处理
	if(is_array($rsdb)){
		foreach( $rsdb AS $key=>$value){
			$rsdb[$key]=filtrate($value);
		}
		$lfjdb && $rsdb=$rsdb+$lfjdb;
	}elseif($lfjdb){
		$rsdb=$lfjdb;
	}
	/*模块设置时,有些字段有默认值*/
	foreach( $m_config[field_db] AS $key=>$rs)
	{
		if($rs[form_value])
		{
			$rsdb[$key]=$rs[form_value];
		}
	}
	
	//预设值
	set_table_value($m_config[field_db]);


	$atc="postnew";

	require(ROOT_PATH."inc/head.php");
	require("data/form_tpl/post_$fidDB[id].htm");
	require(ROOT_PATH."inc/foot.php");
}


function set_table_value($field_db){
	global $rsdb;
	foreach( $field_db AS $key=>$rs){
		if($rs[form_type]=='select'){
			$detail=explode("\r\n",$rs[form_set]);
			foreach( $detail AS $_key=>$value){
				list($v1,$v2)=explode("|",$value);
				if($rsdb[$key]==$v1){
					unset($rsdb[$key]);
					$rsdb[$key]["$v1"]=' selected ';
				}
			}
		}elseif($rs[form_type]=='radio'){
			$detail=explode("\r\n",$rs[form_set]);
			foreach( $detail AS $_key=>$value){
				list($v1,$v2)=explode("|",$value);
				if($rsdb[$key]==$v1){
					unset($rsdb[$key]);
					$rsdb[$key]["$v1"]=' checked ';
				}
			}
		}elseif($rs[form_type]=='checkbox'){
			$_d=explode("/",$rsdb[$key]);
			unset($rsdb[$key]);
			$detail=explode("\r\n",$rs[form_set]);
			foreach( $detail AS $_key=>$value){
				list($v1,$v2)=explode("|",$value);
				if( @in_array($v1,$_d) ){
					$rsdb[$key]["$v1"]=' checked ';
				}
			}
		}elseif($rs[form_type]=='upmorefile'){
			$detail=explode("\n",$rsdb[$key]);
			unset($rsdb[$key]);
			foreach( $detail AS $_key=>$value){
				list($url,$name,$fen)=explode("@@@",$value);
				$rsdb[$key][name][]=$name;
				$rsdb[$key][url][]=$url;
				$rsdb[$key][fen][]=$fen;
			}
		}
	}
}


//采集外部图片
function get_out_pic($str,$getpic=1){
	global $webdb,$_pre;
	if(!$getpic){
		return $str;
	}
	preg_match_all("/http:\/\/([^ '\"<>]+)\.(gif|jpg|png)/is",$str,$array);
	$filedb=$array[0];
	foreach( $filedb AS $key=>$value){
		if( strstr($value,$webdb[www_url]) ){
			continue;
		}
		$listdb["$value"]=$value;
	}
	unset($filedb);
	foreach( $listdb AS $key=>$value){
		$filedb[]=$value;
		$name=rands(5)."__".basename($value);
		if(!is_dir(ROOT_PATH."$webdb[updir]/form")){
			makepath(ROOT_PATH."$webdb[updir]/form");
		}
		$ck=0;
		if( @copy($value,ROOT_PATH."$webdb[updir]/form/$name") ){
			$ck=1;
		}elseif($filestr=file_get_contents($value)){
			$ck=1;
			write_file(ROOT_PATH."$webdb[updir]/form/$name",$filestr);
		}
	
		/*加水印*/
		if($ck&&$webdb[is_waterimg]&&$webdb[if_gdimg])
		{
			include_once(ROOT_PATH."inc/waterimage.php");
			$uploadfile=ROOT_PATH."$webdb[updir]/form/$name";
			imageWaterMark($uploadfile,$webdb[waterpos],ROOT_PATH.$webdb[waterimg]);
		}

		if($ck){
			$str=str_replace("$value","http://www_qibosoft_com/Tmp_updir/form/$name",$str);
		}
	}
	return $str;
}

?>