<?php
require_once("global.php");

$fidDB=$db->get_one("SELECT * FROM {$pre}form_module WHERE id='$mid'");

if(!$fidDB){
	showerr("��������");
}
if($fidDB[allowpost]&&!in_array($groupdb['gid'],explode(',',$fidDB[allowpost]))){
	showerr("�������û�����Ȩ��");
}

if($fidDB[endtime]&&$fidDB[endtime]<$timestamp){
	showerr("�ѵ���ֹ����,�㲻���ٲ���.");
}

if($action=="postnew"&&!$fidDB[repeatpost]){
	if($lfjuid){
		if($db->get_one("SELECT * FROM {$pre}form_content WHERE uid='$lfjuid' AND mid='$mid'")){
			showerr("�㲻���ظ��ύ");
		}
	}else{
		if($db->get_one("SELECT * FROM {$pre}form_content WHERE ip='$onlineip' AND mid='$mid'")){
			showerr("�㲻���ظ��ύ");
		}
	}
}

//ɾ��
if($action=="del"){
	if(!$lfjid){
		showerr("���ȵ�¼");
	}
	$rsdb=$db->get_one("SELECT * FROM {$pre}form_content WHERE id='$id'");
	if($rsdb[uid]!=$lfjuid&&!$web_admin){
		showerr("��ûȨ��!");
	}
	$db->query("DELETE FROM {$pre}form_content WHERE id='$id'");
	$db->query("DELETE FROM `{$pre}form_reply` WHERE id='$id'");
	$rsdb[mid] && $db->query("DELETE FROM {$pre}form_content_$rsdb[mid] WHERE id='$id'");
	refreshto("./","ɾ���ɹ�",1);
}

/**
*ģ����������ļ�
**/
$m_config=unserialize($fidDB[config]);

//$STYLE=$fidDB[style];

/*ģ��*/
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
			$array[updateTable]=1;	//ͳ���û��ϴ����ļ�ռ�ÿռ��С
			$filename=upfile($postfile,$array);
		}
	}
}

/**�����ύ���·�������**/
if($action=="postnew")
{
	foreach( $m_config[field_db] AS $key=>$rs )
	{
		if( $rs[mustfill]==1 && $postdb[$rs[field_name]]=='' )
		{
			showerr("{$rs[title]}����Ϊ��");
		}

		if( ($rs[mustfill]==2||$rs[form_type]=='pingfen') && $postdb[$rs[field_name]] )
		{
			//showerr("{$rs[title]}����˽���ύ����");
		}

		if($rs[field_type]=='int'&&$postdb[$rs[field_name]]&&!ereg("^[0-9]+$",$postdb[$rs[field_name]]))
		{
			showerr("{$rs[title]}ֻ��Ϊ����");
		}

		if($rs[field_type]=='varchar')
		{
			$rs[field_leng]=$rs[field_leng]?$rs[field_leng]:255;
			if(strlen( $postdb[$rs[field_name]] )>$rs[field_leng])
			{
				showerr("{$rs[title]}���ܳ���{$rs[field_leng]}���ַ�,һ�����ֵ��������ַ�");
			}
		}

		if($rs[field_type]=='int')
		{
			$rs[field_leng]=$rs[field_leng]?$rs[field_leng]:10;
			if(strlen( $postdb[$rs[field_name]] )>$rs[field_leng])
			{
				showerr("{$rs[title]}���ܳ���{$rs[field_leng]}���ַ�");
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

	
	/*��ʹ�������߱༭�����ֶ��ύ�ĸ�����ַ������*/
	foreach( $m_config[is_html] AS $key=>$value)
	{
		$postdb[$key]=str_replace("<img ","<img onload=\'if(this.width>600)makesmallpic(this,600,800);\' ",$postdb[$key]);
		//ͼƬĿ¼ת��
		$postdb[$key]=move_attachment($lfjdb[uid],$postdb[$key],"form");
		//��ȡԶ��ͼƬ
		$postdb[$key]=get_out_pic($postdb[$key],$GetOutPic);
		$postdb[$key] = En_TruePath($postdb[$key]);
		$postdb[$key] = preg_replace('/javascript/i','java script',$postdb[$key]);//����js����
		$postdb[$key] = preg_replace('/<iframe ([^<>]+)>/i','&lt;iframe \\1>',$postdb[$key]);//���˿�ܴ���
	}
	
	$_array=array_flip($m_config[is_html]);
	
	/**
	*�ύ����������Ǹ�ѡ��,��Ҫ������,����������߱༭����,ҲҪ������,��Ȼ,ʹ�����߱༭������Σ�յ�
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

	$db->query("INSERT INTO `{$pre}form_content` (`title`, `mid`, `hits`, `posttime`, `list`, `uid`, `username`, `titlecolor`, `yz`, `ip`) VALUES ('$postdb[title]','$fidDB[id]','0','$timestamp','$timestamp','$lfjuid','$lfjid','','0','$onlineip')");

	$rs=$db->get_one("SELECT * FROM `{$pre}form_content` ORDER BY id DESC LIMIT 1");
	$id=$rs[id];

	unset($sqldb);
	$sqldb['id']="id='$id'";
	$sqldb['uid']="uid='$lfjuid'";

	/*����жϸ���Ϣ��Ҫ������Щ�ֶε�����*/
	$array = table_field("{$pre}form_content_$fidDB[id]");
	foreach( $array AS $key=>$value)
	{
		if($value=="id"||$value=="uid")
		{
			continue;
		}
		isset($postdb[$value]) && $sqldb["$value"]="`{$value}`='{$postdb[$value]}'";
	}
	
	$sql=implode(",",$sqldb);
	$db->query("INSERT INTO `{$pre}form_content_$fidDB[id]` SET $sql");
	
	//����֧��
	if($postdb[paytype]=='olpay'&&$postdb[paymoney]>0){
		$pay_code=mymd5("form\t$postdb[paymoney]\t$id\t$mid");
		echo "<META HTTP-EQUIV=REFRESH CONTENT='0;URL=$webdb[www_url]/do/olpay.php?pay_code=$pay_code'>";
		exit;
	}
	
	refreshto("bencandy_form.php?mid=$mid&id=$id","���ύ�ɹ� ",5);
}

/*�༭����*/
elseif($job=="edit")
{
	$rsdb=$db->get_one("SELECT A.*,B.* FROM `{$pre}form_content` A LEFT JOIN `{$pre}form_content_$fidDB[id]` B ON A.id=B.id WHERE A.id='$id'");

	if($rsdb[uid]!=$lfjuid&&!$web_admin)
	{
		showerr("����Ȩ�޸�");
	}
	
	/*�Ը�����ַ����ԭ*/
	foreach( $m_config[is_html] AS $key=>$value)
	{
		$rsdb[$key]=str_replace("'","&#39;",$rsdb[$key]);
		$rsdb[$key]=En_TruePath($rsdb[$key],0);
	}

	/*��Ĭ�ϱ���������*/
	set_table_value($m_config[field_db]);

	$atc="edit";

	require(PHP168_PATH."inc/head.php");
	require(PHP168_PATH."php168/form_tpl/post_$fidDB[id].htm");
	require(PHP168_PATH."inc/foot.php");
}

/*�����ύ���������޸�*/
elseif($action=="edit")
{

	$rsdb=$db->get_one("SELECT A.*,B.* FROM `{$pre}form_content` A LEFT JOIN `{$pre}form_content_$fidDB[id]` B ON A.id=B.id WHERE A.id='$id' ");

	if($rsdb[uid]!=$lfjuid&&!$web_admin)
	{
		showerr("����Ȩ�޸�");
	}

	foreach( $m_config[field_db] AS $key=>$rs )
	{
		if( $rs[mustfill]==1 && $postdb[$rs[field_name]]==='' )
		{
			showerr("{$rs[title]}����Ϊ��");
		}

		if( ($rs[mustfill]==2||$rs[form_type]=='pingfen') && $postdb[$rs[field_name]] )
		{
			//showerr("{$rs[title]}����˽���ύ����");
		}

		if($rs[field_type]=='int'&&$postdb[$rs[field_name]]&&!ereg("^[0-9]+$",$postdb[$rs[field_name]]))
		{
			showerr("{$rs[title]}ֻ��Ϊ����");
		}

		if($rs[field_type]=='varchar')
		{
			$rs[field_leng]=$rs[field_leng]?$rs[field_leng]:255;
			if(strlen( $postdb[$rs[field_name]] )>$rs[field_leng])
			{
				showerr("{$rs[title]}���ܳ���{$rs[field_leng]}���ַ�,һ�����ֵ��������ַ�");
			}
		}
		if($rs[field_type]=='int')
		{
			$rs[field_leng]=$rs[field_leng]?$rs[field_leng]:10;
			if(strlen( $postdb[$rs[field_name]] )>$rs[field_leng])
			{
				showerr("{$rs[title]}���ܳ���{$rs[field_leng]}���ַ�");
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
	
	
	/*��ʹ�������߱༭�����ֶ��ύ�ĸ�����ַ������*/
	foreach( $m_config[is_html] AS $key=>$value)
	{
		$postdb[$key]=str_replace("<img ","<img onload=\'if(this.width>600)makesmallpic(this,600,800);\' ",$postdb[$key]);
		//ͼƬĿ¼ת��
		$postdb[$key]=move_attachment($lfjdb[uid],$postdb[$key],"$form");

		//��ȡԶ��ͼƬ
		$postdb[$key]=get_out_pic($postdb[$key],$GetOutPic);

		$postdb[$key]=En_TruePath($postdb[$key]);
		$postdb[$key] = preg_replace('/javascript/i','java script',$postdb[$key]);//����js����
		$postdb[$key] = preg_replace('/<iframe ([^<>]+)>/i','&lt;iframe \\1>',$postdb[$key]);//���˿�ܴ���
	}
	
	$_array=array_flip($m_config[is_html]);

	/**
	*�ύ����������Ǹ�ѡ��,��Ҫ������,����������߱༭����,ҲҪ������,��Ȼ,ʹ�����߱༭������Σ�յ�
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
	
	$db->query("UPDATE `{$pre}form_content` SET title='$postdb[title]' WHERE id='$id'");


	/*����жϸ���Ϣ��Ҫ������Щ�ֶε�����*/
	unset($sqldb);
	$array = table_field("{$pre}form_content_$fidDB[id]");
	foreach( $array AS $key=>$value)
	{
		if($value=="id"||$value=="uid")
		{
			continue;
		}

		/*�������Ҫ���ж�,��Ȼ�Ļ�,һЩ���ο����õ��ֶ����ֵ�Ϳ��ܱ����*/
		isset($postdb[$value]) && $sqldb[]="`$value`='{$postdb[$value]}'";
	}
	$sql=implode(",",$sqldb);

	/*���¸���Ϣ��*/
	$db->query("UPDATE `{$pre}form_content_$fidDB[id]` SET $sql WHERE id='$id' ");
	refreshto("bencandy_form.php?mid=$mid&id=$id","�޸ĳɹ�");	
}
else
{
	//URL����������
	if(is_array($rsdb)){
		foreach( $rsdb AS $key=>$value){
			$rsdb[$key]=filtrate($value);
		}
		$lfjdb && $rsdb=$rsdb+$lfjdb;
	}elseif($lfjdb){
		$rsdb=$lfjdb;
	}
	/*ģ������ʱ,��Щ�ֶ���Ĭ��ֵ*/
	foreach( $m_config[field_db] AS $key=>$rs)
	{
		if($rs[form_value])
		{
			$rsdb[$key]=$rs[form_value];
		}
	}
	
	//Ԥ��ֵ
	set_table_value($m_config[field_db]);


	$atc="postnew";

	require(PHP168_PATH."inc/head.php");
	require(PHP168_PATH."php168/form_tpl/post_$fidDB[id].htm");
	require(PHP168_PATH."inc/foot.php");
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


//�ɼ��ⲿͼƬ
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
		if(!is_dir(PHP168_PATH."$webdb[updir]/form")){
			makepath(PHP168_PATH."$webdb[updir]/form");
		}
		$ck=0;
		if( @copy($value,PHP168_PATH."$webdb[updir]/form/$name") ){
			$ck=1;
		}elseif($filestr=file_get_contents($value)){
			$ck=1;
			write_file(PHP168_PATH."$webdb[updir]/form/$name",$filestr);
		}
	
		/*��ˮӡ*/
		if($ck&&$webdb[is_waterimg]&&$webdb[if_gdimg])
		{
			include_once(PHP168_PATH."inc/waterimage.php");
			$uploadfile=PHP168_PATH."$webdb[updir]/form/$name";
			imageWaterMark($uploadfile,$webdb[waterpos],PHP168_PATH.$webdb[waterimg]);
		}

		if($ck){
			$str=str_replace("$value","http://www_php168_com/Tmp_updir/form/$name",$str);
		}
	}
	return $str;
}

?>