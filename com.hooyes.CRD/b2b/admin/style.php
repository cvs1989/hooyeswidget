<?php
!function_exists('html') && exit('ERR');

if($job=="addstyle"&&$Apower[style_editstyle])
{
	$style_select=select_style('keywords');
	$CssCode="/******������Ĭ�ϵ�����,����԰���Ҫ�����޸�********/\r\n\r\n\r\n";
	$CssCode.=read_file(PHP168_PATH."images/default/style.css");
	require(dirname(__FILE__)."/"."head.php");
	require(dirname(__FILE__)."/"."template/style/menu.htm");
	require(dirname(__FILE__)."/"."template/style/style.htm");
	require(dirname(__FILE__)."/"."foot.php");
}
elseif($action=="addstyle"&&$Apower[style_editstyle])
{
	if(!$postdb[keywords]){
		showmsg("�ؼ��ֲ���Ϊ��");
	}elseif(!$postdb[name]){
		showmsg("������Ʋ���Ϊ��");
	}
	if( is_dir(PHP168_PATH."php168/style/$postdb[keywords].php")||is_dir(PHP168_PATH."template/$postdb[keywords]/")||is_dir(PHP168_PATH."images/$postdb[keywords]/") )
	{
		showmsg("�ؼ����Ѿ�������,�뻻һ���ؼ��ְ�");
	}
	$show="<?php	";
	foreach($postdb AS $key=>$value){
		$show.="
		\$styledb['$key']='$value';";
	}
	write_file(PHP168_PATH."php168/style/$postdb[keywords].php",$show.'?>');
	makepath(PHP168_PATH."images/$postdb[keywords]");
	makepath(PHP168_PATH."template/$postdb[keywords]");
	write_file(PHP168_PATH."images/$postdb[keywords]/style.css",stripslashes("$CssCode\r\n"));
	copy(PHP168_PATH."template/default/head.htm",PHP168_PATH."template/$postdb[keywords]/head.htm");
	copy(PHP168_PATH."template/default/foot.htm",PHP168_PATH."template/$postdb[keywords]/foot.htm");
	copy(PHP168_PATH."template/default/index.htm",PHP168_PATH."template/$postdb[keywords]/index.htm");
	copy(PHP168_PATH."template/default/list.htm",PHP168_PATH."template/$postdb[keywords]/list.htm");
	copy(PHP168_PATH."template/default/bencandy.htm",PHP168_PATH."template/$postdb[keywords]/bencandy.htm");
	$dir=opendir(PHP168_PATH."images/default/");
	while($file=readdir($dir)){
		if(eregi("(png|jpg|gif)$",$file)){
			copy(PHP168_PATH."images/default/$file",PHP168_PATH."images/$postdb[keywords]/$file");
		}
	}
	jump("��ӳɹ�","index.php?lfj=style&job=editstyle&keywords=$postdb[keywords]");
}
elseif($job=="editstyle"&&$Apower[style_editstyle])
{
	$style_select=select_style('keywords',$keywords,"index.php?lfj=style&job=editstyle");
	@include(PHP168_PATH."php168/style/$keywords.php");
	$rsdb=$styledb;
	$keywords_check=" readonly ";
	$CssCode=read_file(PHP168_PATH."images/$styledb[keywords]/style.css");
	require(dirname(__FILE__)."/"."head.php");
	require(dirname(__FILE__)."/"."template/style/menu.htm");
	require(dirname(__FILE__)."/"."template/style/style.htm");
	require(dirname(__FILE__)."/"."foot.php");
}
elseif($action=="editstyle"&&$Apower[style_editstyle])
{
	//�˴���Ŀ�����൱���滻������
	include(PHP168_PATH."php168/style/$postdb[keywords].php");
	foreach($postdb AS $key=>$value){
		if($value!=$styledb[$key]){
			$chang_style.=chang_style($key,$value);
		}
		$styledb[$key]=$value;
	}

	$show="<?php	";
	foreach($styledb AS $key=>$value){
		$show.="
		\$styledb['$key']='$value';";
	}

	write_file(PHP168_PATH."php168/style/$postdb[keywords].php",$show."?>");
	write_file(PHP168_PATH."images/$postdb[keywords]/style.css",stripslashes("$CssCode"));
	jump("�޸ĳɹ�","index.php?lfj=style&job=editstyle&keywords=$postdb[keywords]");
}
elseif($action=="deletestyle"&&$Apower[style_editstyle])
{
	if($keywords=='default'){
		showmsg("Ĭ�ϵķ����ɾ��");
	}
	if( $keywords && unlink(PHP168_PATH."php168/style/$keywords.php") ){
		del_file(PHP168_PATH."images/$keywords/");
		del_file(PHP168_PATH."template/$keywords/");
		jump("ɾ���ɹ�","index.php?lfj=style&job=deletestyle");
	}else{
		showmsg("ɾ��ʧ��,��ȷ���ļ����Կ�д".PHP168_PATH."php168/style/$keywords.php");
	}
	
}
elseif($job=="deletestyle"&&$Apower[style_editstyle])
{
	$style_select=select_style('keywords');
	require(dirname(__FILE__)."/"."head.php");
	require(dirname(__FILE__)."/"."template/style/menu.htm");
	require(dirname(__FILE__)."/"."template/style/deletestyle.htm");
	require(dirname(__FILE__)."/"."foot.php");
}
elseif($job=='edittpl'&&$Apower[style_editstyle])
{
	unset($array,$listdb);
	include("tplname.php");
	$keywords || $keywords=$STYLE;
	$style_select=select_style('keywords',$keywords,"index.php?lfj=$lfj&job=$job");
	$dir=opendir(PHP168_PATH."template/$keywords/");
	while($file=readdir($dir)){
		if(eregi("htm$",$file)){
			 $array[$file]=array("name"=>$tplName[$file],"file"=>$file);
		}
	}
	foreach( $tplName AS $key=>$value){
		$array[$key] && $listdb[]=$array[$key];
	}
	$listdb=$listdb?($listdb+$array):$array;
	require(dirname(__FILE__)."/"."head.php");
	require(dirname(__FILE__)."/"."template/style/menu.htm");
	require(dirname(__FILE__)."/"."template/style/tpl.htm");
	require(dirname(__FILE__)."/"."foot.php");
}
elseif($job=='editcode'&&$Apower[style_editstyle])
{
	$code=read_file(PHP168_PATH."template/$keywords/$filename");
	$code=str_replace("<","&lt;",$code);
	$code=str_replace(">","&gt;",$code);
	$code=str_replace(">","&gt;",$code);
	$code=str_replace("'","&#39;",$code);
	$code=str_replace('"',"&quot;",$code);
	$code=str_replace('&nbsp;',"&amp;nbsp;",$code);

	require(dirname(__FILE__)."/"."head.php");
	require(dirname(__FILE__)."/"."template/style/menu.htm");
	require(dirname(__FILE__)."/"."template/style/editcode.htm");
	require(dirname(__FILE__)."/"."foot.php");
}
elseif($action=='editcode'&&$Apower[style_editstyle])
{
	$code=stripslashes($code);
	if(eregi(".htm$",$filename)){
		write_file(PHP168_PATH."template/$keywords/$filename",$code);
	}
	jump("�޸ĳɹ�",$FROMURL,1);
}

function chang_style($key,$value){
	if($key=="bodybgcolor"){
		$show="\r\nbody{\r\n\tbackground:$value;\r\n}";
	}elseif($key=="bodyBgImg"){
		$show="\r\nbody{\r\n\tbackground:url($value) center 50%;\r\n}";
	}elseif($key=="wrapWidth"){
		$show="\r\n.wrap{\r\n\twidth:$value;\r\n}";
	}elseif($key=="fontColor"){
		$show="\r\nTD,a,a:visited,a:hover{\r\n\tcolor:$value;\r\n}";
	}elseif($key=="tableBorderColor"){
		$show="\r\n.dragTable{\r\n\tborder:1px $value solid;\r\n}";
	}elseif($key=="tableBgcolor"){
		$show="\r\n.dragTable{\r\n\tbackground:$value;\r\n}";
	}elseif($key=="tableHeadBgColor"){
		$show="\r\n.dragTable .head{\r\n\tbackground:$value;\r\n}";
	}elseif($key=="tableHeadFontColor"){
		$show="\r\n.dragTable .head,.dragTable .head a{\r\n\tcolor:$value;\r\n}";
	}elseif($key=="tableHeadBgImg"){
		$show="\r\n.dragTable .head{\r\n\tbackground:url($value);\r\n}";
	}
	return $show;
}
?>