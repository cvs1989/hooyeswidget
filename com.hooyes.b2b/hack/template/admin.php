<?php
!function_exists('html') && exit('ERR');
if($job=="edit"&&$Apower[template_list])
{
	$rsdb=$db->get_one("SELECT * FROM `{$pre}template` WHERE id='$id'");
	$rsdb_type[$rsdb[type]]=" checked ";
	$code=read_file(ROOT_PATH.$rsdb[filepath]);

	$usecodeDB[intval($usecode)]=' checked ';

	$code=editor_replace($code);
	

	hack_admin_tpl('edit',$notop);

}
elseif($action=="edit"&&$Apower[template_list])
{
	$rsdb=$db->get_one("SELECT * FROM `{$pre}template` WHERE id='$id'");
	$db->query("UPDATE `{$pre}template` SET name='$postdb[name]',type='$postdb[type]',filepath='$postdb[filepath]',descrip='$postdb[descrip]' WHERE id='$id' ");

	$postdb[code]=stripslashes($postdb[code]);

	if(is_file(ROOT_PATH.$postdb[filepath])&&!is_writable(ROOT_PATH.$postdb[filepath])){
		showmsg("模板文件属性不可写,请修改其为可写权限");
	}
	
	//备份一下
	$code=addslashes(read_file(ROOT_PATH.$rsdb[filepath]));
	$db->query("INSERT INTO `{$pre}template_bak` (`id` , `posttime` , `code` ) VALUES ( '$id', '$timestamp', '$code')");

	unlink(ROOT_PATH.$rsdb[filepath]);	//目的是删除原来不相同的模板
	if(eregi("(\.htm|\.html)$",$postdb[filepath])){
		write_file(ROOT_PATH.$postdb[filepath],$postdb[code]);
	}	
	jump("修改成功","index.php?lfj=template&job=edit&id=$id&notop=1",1);
}
elseif($job=="add"&&$Apower[template_list])
{
	$rsdb[filepath]='template/default/***.htm';
	$makemsg="[<A HREF='#' onclick=\"window.open('index.php?lfj=template&job=maketpl','','width=650,height=500')\" style='color:red;'>新建一个模板</A>]";

	hack_admin_tpl('edit');
}
elseif($action=="add"&&$Apower[template_list])
{
	$db->query("INSERT INTO `{$pre}template` ( `name` , `type` , `filepath` , `descrip` ) VALUES ('$postdb[name]', '$postdb[type]', '$postdb[filepath]', '$postdb[descrip]')");
	jump("添加成功","index.php?lfj=template&job=list",1);
}
elseif($job=="list"&&$Apower[template_list])
{
	!$page && $page=1;
	$rows=40;
	$min=($page-1)*$rows;
	$showpage=getpage("`{$pre}template`","","index.php?lfj=template&job=list",$rows);
	$array=array(1=>"主页",2=>"列表页",3=>"内容页",4=>"标题",5=>"标题+内容",6=>"图片",7=>"网页头部",8=>"网页底部",9=>"独立页",11=>"系统专题页");
	$query=$db->query("SELECT * FROM `{$pre}template` ORDER BY id DESC LIMIT $min,$rows");
	while($rs=$db->fetch_array($query)){
		//if($rs[phpname]=='index.php'){
		//	$rs[phpname]="$rs[phpname]";
		//}
		$r=$db->get_one("SELECT COUNT(*) AS NUM FROM {$pre}template_bak WHERE id='$rs[id]'");
		$rs[NUM]=intval($r[NUM]);
		if($rs[NUM]){
			$rs[NUM]="<A HREF='?lfj=$lfj&job=getback&id=$rs[id]'>$rs[NUM](回档)</A>";
		}
		$rs[typename]=$array[$rs[type]];
		$listdb[]=$rs;
	}

	hack_admin_tpl('list');
}
elseif($job=="list2"&&$Apower[template_list])
{
	require_once("tplname.php");
	$dir=opendir(ROOT_PATH."/template/default/");
	while($file=readdir($dir)){
		if(eregi("^(bencandy_tpl|bigsort_tpl|label_tpl|list_tpl|login_tpl|side_sort|side_tpl|tpl|vote_js)$",$file)){
			$dir2=opendir(ROOT_PATH."/template/default/$file");
			while($file2=readdir($dir2)){
				if($file=='label_tpl'){
					if($file2=='.'||$file2=='..'){
						continue;
					}
					$i=0;
					$dir3=opendir(ROOT_PATH."/template/default/$file/$file2");
					while($file3=readdir($dir3)){
						if($file3=='..'){
							continue;
						}
						$i++;
						if($i>2){
							unset($array["$file/$file2/."]);
						}
						if($file3=='.'||eregi("\.htm$",$file3)){
							$array["$file/$file2/$file3"]=array('name'=>$file3!='.'?$file3:'','filepath'=>"$file/$file2/".($file3!='.'?$file3:'空目录'),'typename'=>($tplName2["$file/$file2/$file3"]?$tplName2["$file/$file2/$file3"]:$tplName2["$file/$file2"]));
						}						
					}
				}elseif(eregi("\.htm$",$file2)){
					$array["$file/$file2"]=array('name'=>$file2,'filepath'=>"$file/$file2",'typename'=>($tplName2["$file/$file2"]?$tplName2["$file/$file2"]:$tplName2["$file"]));
				}
			}
		}
	}
	$listdb=$array;


	hack_admin_tpl('list2');
}
elseif($action=="delete2"&&$Apower[template_list])
{
	if(eregi("\.htm$",$filepath)){
		unlink(ROOT_PATH."template/default/$filepath");
	}
	jump("删除成功",$FROMURL,2);
}
elseif($job=='editcode'&&$Apower[template_list])
{
	$code=read_file(ROOT_PATH."template/default/$filepath");
	$code=editor_replace($code);


	hack_admin_tpl('editcode');
}
elseif($action=='editcode'&&$Apower[template_list])
{
	$code=stripslashes($code);
	if(eregi("(\.htm|\.html)$",$filepath)){
		write_file(ROOT_PATH."template/default/$filepath",$code);
	}
	jump("修改成功",$FROMURL,1);
}
elseif($job=='maketpl2'&&$Apower[template_list])
{
	if(eregi("\.htm",$filecode)){
		$code=read_file(ROOT_PATH."template/default/$filecode");
		$code=editor_replace($code);
	}


	hack_admin_tpl('maketpl2');
}
elseif($action=='maketpl2'&&$Apower[template_list])
{
	$code=stripslashes($code);
	if(!$code){
		showmsg("代码内容不能为空");
	}elseif(!$filepath){
		showmsg("模板文件不能为空");
	}elseif(!eregi("(\.htm|\.html)$",$filepath)){
		showmsg("模板文件只能是.htm .html结尾的网页");
	}elseif(dirname($filepath)=='.'||dirname($filepath)=='\\'||dirname($filepath)=='/'){
		showmsg("目录不存在,或有误");
	}elseif(!is_dir(ROOT_PATH."template/default/".dirname($filepath))){
		showmsg("目录有误");
	}
	write_file(ROOT_PATH."template/default/$filepath",$code);
	jump("创建成功","index.php?lfj=template&job=list2",1);
}
elseif($action=="delete"&&$Apower[template_list])
{
	$rs=$db->get_one("SELECT * FROM `{$pre}template` WHERE id='$id'");

	$db->query("DELETE FROM `{$pre}template` WHERE id='$id'");
	$db->query("DELETE FROM `{$pre}template_bak` WHERE id='$id'");
	if( !strstr($rs[filepath],'template/default/') ){
		unlink(ROOT_PATH.$rs[filepath]);
		del_file( str_replace(".htm","",ROOT_PATH.$rs[filepath]) );
	}
	jump("删除成功","index.php?lfj=template&job=list",2);
}

elseif($job=="maketpl"&&$Apower[template_list])
{
	if(!$filepath){
		$filepath="template/_default/$timestamp.htm";
	}


	hack_admin_tpl('maketpl',$notop);
}
elseif($action=="maketpl"&&$Apower[template_list])
{
	$path=dirname($postdb[filepath]);
	if(!is_dir(ROOT_PATH.$path)){
		makepath(ROOT_PATH.$path);
	}
	if(is_file(ROOT_PATH.$postdb[filepath])){
		showmsg("此模板已存在了");
	}
	elseif(!eregi("(\.htm|\.html)$",$postdb[filepath])){
		showmsg('模板只能是.htm .html文件!');
	}
	if($ifupfile)
	{
		$array[name]=is_array($postfile)?$_FILES[postfile][name]:$postfile_name;
		$filetype=strtolower(strrchr($array[name],"."));
		if($filetype!='.zip')
		{
			showerr("只能上传ZIP格式的压缩包",1);
		}
		write_file(ROOT_PATH.$postdb[filepath],' ');
		if(!is_writable(ROOT_PATH.$postdb[filepath])){
			echo "文件写入失败,请确认目录可写:".ROOT_PATH.$postdb[filepath];
			die();
		}

		
		$array[path]=$webdb[updir]."/";
		$array[size]=is_array($postfile)?$_FILES[postfile][size]:$postfile_size;
		/*
		if(($array[size]+$lfjdb[usespace])>($webdb[totalSpace]*1048576+$groupdb[totalspace]*1048576+$lfjdb[totalspace]))
		{
			showerr("你的空间不足,上传失败,<A HREF='?uid=$lfjuid'>点击查看你的空间容量信息</A>");
		}
		
		$array[updateTable]=1;	//统计用户上传的文件占用空间大小
		*/
		$filename=upfile(is_array($postfile)?$_FILES[postfile][tmp_name]:$postfile,$array);
		
		rename(ROOT_PATH."$webdb[updir]/$filename",dirname(ROOT_PATH.$postdb[filepath])."/$filename");
		
		require_once(ROOT_PATH."inc/class.z.php");
		$z = new Zip;
		$z->Extract(dirname(ROOT_PATH.$postdb[filepath])."/$filename",dirname(ROOT_PATH.$postdb[filepath])."/tmp");
		@unlink(dirname(ROOT_PATH.$postdb[filepath])."/$filename");
		@unlink(ROOT_PATH.$postdb[filepath]);

		//查找模板在哪个目录,有的可能是二级目录
		$ck=0;
		$dir=opendir(dirname(ROOT_PATH.$postdb[filepath])."/tmp");
		while($file=readdir($dir)){
			if($file=='.'||$file=='..'){
				continue;
			}elseif(eregi("(\.htm|\.html)$",$file)){
				$ck=1;
				rename(dirname(ROOT_PATH.$postdb[filepath])."/tmp/$file",ROOT_PATH.$postdb[filepath]);
				$truedir=dirname(ROOT_PATH.$postdb[filepath])."/tmp/";
			}elseif(eregi("(\.php|\.php3|\.asp|\.aspx)$",$file)){
				unlink( dirname(ROOT_PATH.$postdb[filepath])."/tmp/$file" );
			}
		}
		closedir($dir);

		//处理有的压缩包是二级目录
		if($ck==0){
			$dir=opendir(dirname(ROOT_PATH.$postdb[filepath])."/tmp");
			while($file=readdir($dir)){
				if($file=='.'||$file=='..'){
					continue;
				}else{
					$dir2=opendir(dirname(ROOT_PATH.$postdb[filepath])."/tmp/$file");
					while($file2=readdir($dir2)){
						if($file2=='.'||$file2=='..'){
							continue;
						}elseif(eregi("(\.htm|\.html)$",$file2)){
							$ck=1;
							rename(dirname(ROOT_PATH.$postdb[filepath])."/tmp/$file/$file2",ROOT_PATH.$postdb[filepath]);
							$truedir=dirname(ROOT_PATH.$postdb[filepath])."/tmp/$file/";
						}elseif(eregi("(\.php|\.php3|\.asp|\.aspx)$",$file2)){
							unlink( dirname(ROOT_PATH.$postdb[filepath])."/tmp/$file/$file2" );
						}
					}
					closedir($dir2);
				}
			}
			closedir($dir);
		}

		//图片目录转移
		$imgpath=str_replace(".htm","",ROOT_PATH.$postdb[filepath]);
		makepath($imgpath);
		$dir=opendir($truedir);
		while($file=readdir($dir)){
			if($file=='.'||$file=='..'){
				continue;
			}
			rename("$truedir/$file","$imgpath/$file");
		}
		closedir($dir);

		//删除临时创建的目录
		del_file(dirname(ROOT_PATH.$postdb[filepath])."/tmp");

		//处理图片路径
		$file=read_file(ROOT_PATH.$postdb[filepath]);		$file=preg_replace("/^([^>]+)>(.*)/is","\\1>\r\n\r\n<!--这一行是系统自动添加的代码,目的是为了处理图片的路径--><base href='$webdb[www_url]/".str_replace(".htm","/",$postdb[filepath])."'>\r\n\r\n\\2",$file);	
		write_file(ROOT_PATH.$postdb[filepath],$file);
	}
	else
	{
		if(!$postdb[code]){
			showerr("内容不能为空",1);
		}
		$postdb[code]=stripslashes($postdb[code]);

		if(eregi("(\.htm|\.html)$",$postdb[filepath]) ){
			write_file(ROOT_PATH.$postdb[filepath],$postdb[code]);
		}
		
		if(!is_writable(ROOT_PATH.$postdb[filepath])){
			echo "文件写入失败,请确认目录可写:".ROOT_PATH.$postdb[filepath];
			die();
		}
	}

	$db->query("INSERT INTO `{$pre}template` (`name` , `type` , `filepath`) VALUES ('$postdb[name]', '$postdb[type]', '$postdb[filepath]')");

	if($obj_id){
		echo "<SCRIPT LANGUAGE=\"JavaScript\">
		if(window.self==top){
			window.opener.document.getElementById('$obj_id').value='$postdb[filepath]';
			alert('创建成功');
		}
		</SCRIPT>";
	}
	echo "<SCRIPT LANGUAGE=\"JavaScript\">
	<!--
		if(window.self==top){
			window.self.close();
		}else{
			window.location.href='index.php?lfj=template&job=list';
		}
		
	//-->
	</SCRIPT>";
	exit;
}
elseif($job=='getback'&&$Apower[template_list])
{
	$rsdb=$db->get_one("SELECT * FROM `{$pre}template` WHERE id='$id'");
	$query = $db->query("SELECT * FROM {$pre}template_bak WHERE id='$id' ORDER BY id DESC");
	while($rs = $db->fetch_array($query)){
		$rs[posttime]=date("Y-m-d H:i:s",$rs[posttime]);
		$listdb[]=$rs;
	}


	hack_admin_tpl('getback');
}
elseif($action=='getback'&&$Apower[template_list])
{
	$rsdb=$db->get_one("SELECT A.*,B.* FROM {$pre}template_bak A LEFT JOIN `{$pre}template` B ON A.id=B.id WHERE A.bid='$bid'");

	if(is_file(ROOT_PATH.$rsdb[filepath])&&!is_writable(ROOT_PATH.$rsdb[filepath])){
		showmsg("模板文件属性不可写,请修改其为可写权限");
	}
	
	if( eregi("(\.htm|\.html)$",$rsdb[filepath]) ){
		write_file(ROOT_PATH.$rsdb[filepath],$rsdb[code]);
	}
	
	jump("回档成功","index.php?lfj=template&job=list",1);
}
elseif($action=='delback'&&$Apower[template_list])
{
	$db->query("DELETE FROM {$pre}template_bak WHERE bid='$bid'");
	jump("删除成功","$FROMURL",0);
}
elseif($action=='delallback'&&$Apower[template_list])
{
	$db->query("DELETE FROM {$pre}template_bak WHERE id='$id'");
	jump("删除成功","index.php?lfj=template&job=list",1);
}
elseif($job=='viewback'&&$Apower[template_list])
{
	$rsdb=$db->get_one("SELECT A.*,B.* FROM {$pre}template_bak A LEFT JOIN `{$pre}template` B ON A.id=B.id WHERE A.bid='$bid'");

	$code=editor_replace($code);

	$usecodeDB[intval($usecode)]=' checked ';

	$rsdb[posttime]=date("Y-m-d H:i:s",$rsdb[posttime]);

	hack_admin_tpl('viewback');
}