<?php
!function_exists('html') && exit('ERR');

if($job=="set"&&$Apower[setmakeALLhtml_set])
{
	$NewsMakeHtml[$webdb[NewsMakeHtml]]=' checked ';
	require(dirname(__FILE__)."/"."head.php");
	//require(dirname(__FILE__)."/"."template/html/menu.htm");
	require(dirname(__FILE__)."/"."template/html/set.htm");
	require(dirname(__FILE__)."/"."foot.php");
}
elseif($action=="set"&&($Apower[setmakeALLhtml_set]||$Apower[makehtml_make]))
{
	write_config_cache($webdbs);
	jump("修改成功",$FROMURL);
}
elseif($job=="list"&&$Apower[makehtml_make])
{
	$fid=intval($fid);
	$NewsMakeHtml[$webdb[NewsMakeHtml]]=' checked ';
	$DefaultIndexHtml[intval($webdb[DefaultIndexHtml])]=' checked ';
	$ForbidShowPhpPage[intval($webdb[ForbidShowPhpPage])]=' checked ';
	$sortdb=array();
	if( count($Fid_db[name])>100||$fid ){
		$rows=50;
		$page<1 && $page=1;
		$min=($page-1)*$rows;
		$showpage=getpage("{$pre}sort","WHERE fup='$fid'","index.php?lfj=$lfj&job=$job&only=$only&mid=$mid&fid=$fid",$rows);
		$query = $db->query("SELECT * FROM {$pre}sort WHERE fup='$fid' ORDER BY list DESC,fid ASC LIMIT $min,$rows");
		if($fid){
			$show_guide="<A HREF='index.php?lfj=$lfj&job=$job&only=$only&mid=$mid'>返回顶级目录</A> ".list_sort_guide($fid);
		}
		while($rs = $db->fetch_array($query)){
			//if(!$rs[type]){
			//	$erp=$Fid_db[iftable][$rs[fid]];
			//	@extract($db->get_one("SELECT COUNT(*) AS NUM FROM {$pre}article$erp WHERE fid='$rs[fid]'"));
			//	$rs[NUM]=intval($NUM);
			//}
			if($rs[list_html]){
				$rs[filename]=$rs[list_html];
			}else{
				$rs[filename]=$webdb[list_filename];
			}
			$rs[filename]=preg_replace("/(.*)\/([^\/]+)/is","\\1/",$rs[filename]);
			$fid=$rs[fid];
			$page=1;
			eval("\$rs[filename]=\"$rs[filename]\";");
			if(is_dir(PHP168_PATH.$rs[filename])||is_file(PHP168_PATH.$rs[filename])){
				$rs[havemade]=1;
			}else{
				$rs[havemade]=0;
			}
			$sortdb[]=$rs;
		}
		
	}else{		
		list_2allsort($fid,"sort");
	}	

	$list_record=read_file(PHP168_PATH."cache/makelist_record.php");
	$show_record=read_file(PHP168_PATH."cache/makeShow_record.php");
	$record='';
	if($list_record){
		$record.="<li><A HREF='../do/list_html.php$list_record' style='color:red;font-size:18px;font-weight:bold;'>列表页生成静态,被中断过,请点击继续生成</A></li>";
	}
	if($show_record){
		$record.="<li><A HREF='../do/bencandy_html.php$show_record' style='color:red;font-size:18px;font-weight:bold;'>内容页生成静态,被中断过,请点击继续生成</A></li>";
	}

	if($fid){
		$rsdb=$db->get_one(" SELECT * FROM {$pre}sort WHERE fid='$fid' ");
	}
	$TheSameMakeIndexHtml[intval($webdb[TheSameMakeIndexHtml])]=' checked ';

	require(dirname(__FILE__)."/"."head.php");
	//require(dirname(__FILE__)."/"."template/html/menu.htm");
	require(dirname(__FILE__)."/"."template/html/sort.htm");
	require(dirname(__FILE__)."/"."foot.php");
}
elseif($job=="listsp"&&$Apower[spmakehtml_make])
{
	$sortdb=array();
	list_2allsort($fid,"spsort");

	$list_record=read_file(PHP168_PATH."cache/makelist_record.php");
	$show_record=read_file(PHP168_PATH."cache/makeShow_record.php");
	$record='';
	if($list_record){
		$record.="<li><A HREF='../do/listsp_html.php$list_record' style='color:red;font-size:18px;font-weight:bold;'>专题列表页生成静态,被中断过,请点击继续生成</A></li>";
	}
	if($show_record){
		$record.="<li><A HREF='../do/showsp_html.php$show_record' style='color:red;font-size:18px;font-weight:bold;'>专题内容页生成静态,被中断过,请点击继续生成</A></li>";
	}

	require(dirname(__FILE__)."/"."head.php");
	//require(dirname(__FILE__)."/"."template/html/menu.htm");
	require(dirname(__FILE__)."/"."template/html/sortsp.htm");
	require(dirname(__FILE__)."/"."foot.php");
}
elseif($action=="makehtml"&&$Apower[makehtml_make])
{
	//生成静态完毕
	if($step=='end'){
		echo "<META HTTP-EQUIV=REFRESH CONTENT='0;URL=?lfj=$lfj&job=list'>";
		exit;
	//已生成列表页,继续生成内容页
	}elseif($step=='endListStarBencandy'){
		unset($fiddb);
		require_once("../cache/makeShow1.php");
		echo "请稍候,正在生成内容页<META HTTP-EQUIV=REFRESH CONTENT='0;URL=../do/bencandy_html.php?fid=$fiddb[0]'>";
		exit;
	}

	if(!is_dir('../cache')){
		mkdir('../cache');
		chmod('../cache',0777);
	}

	//来自于栏目管理POST过来的数据,要做处理
	if($posttype=="fromsort"){
		$fiddb2=$fiddb;
		unset($fiddb);
		foreach( $fiddb2 AS $key=>$value){
			$fiddb[]=$key;
		}
	}
	
	$SQL=" ";
	if($_POST[listdb]&&$posttype=="fromarticle"){
		if(!$listdb){
			showmsg("请选择一个文章");
		}
		$ar='';
		foreach( $listdb AS $key=>$value){
			$ar[]=$key;
		}
		if(!$fiddb[0]){
			unset($fiddb);
			$query = $db->query("SELECT A.fid,D.aid FROM {$pre}article_db D LEFT JOIN {$pre}article A ON D.aid=A.aid WHERE D.aid IN (".implode(',',$ar).")");
			while($rs = $db->fetch_array($query)){
				if(!$rs[fid]&&$_rs=get_one_article($rs[aid])){
					$rs=$_rs+$rs;
				}
				$fid_d[$rs[fid]] || $fiddb[]=$rs[fid];
				$fid_d[$rs[fid]]=1;
			}
		}
		$aids=implode(',',$ar);
		$SQL.=" AND aid IN ($aids) ";
		
	}elseif($posttype=="fromarticle"){
		$SQL.=" AND aid IN ($aids) ";
	}

	if(!$fiddb&&!$iii&&$page<2){
		showmsg("请选择一个栏目");
	}

	if($beginTime){
		$beginTime=preg_replace("/([\d]+)-([\d]+)-([\d]+) ([\d]+):([\d]+):([\d]+)/eis","mk_time('\\4','\\5', '\\6', '\\2', '\\3', '\\1')",$beginTime);
		$SQL.=" AND posttime>$beginTime";
	}
	if($endTime){
		$endTime=preg_replace("/([\d]+)-([\d]+)-([\d]+) ([\d]+):([\d]+):([\d]+)/eis","mk_time('\\4','\\5', '\\6', '\\2', '\\3', '\\1')",$endTime);
		$SQL.=" AND posttime<$endTime";
	}
	if(is_numeric($beginId)){
		$SQL.=" AND aid>$beginId ";
	}
	if(is_numeric($endId)){
		$SQL.=" AND aid<$endId ";
	}
	$iii=intval($iii);
	if($iii==0&&$page<2)
	{
		write_config_cache($webdbs);

		$allfid=implode(",",$fiddb);
		write_file("../cache/makeShow0.php",$allfid);
		write_file("../cache/makeShow1.php","<?php\r\n\$weburl='$WEBURL&step=end';\r\n");

		//列表页
		write_file("../cache/makelist.php","<?php\r\n \$allfid='$allfid';\r\n\$weburl='$WEBURL&step=endListStarBencandy';");
		//echo "<META HTTP-EQUIV=REFRESH CONTENT='0;URL=../do/list_html.php?fid=$fiddb[0]'>";
	}
	else
	{
		$allfid=read_file("../cache/makeShow0.php");
		$fiddb=explode(",",$allfid);
	}
	$page || $page=1;

	$rows=3000;
	$min=($page-1)*$rows;
	if($fid=$fiddb[$iii])
	{
		$ck=$ids='';
		$erp=$Fid_db[iftable][$fid];
		$query = $db->query("SELECT pages,aid AS id FROM {$pre}article$erp WHERE fid=$fid $SQL LIMIT $min,$rows");
		while($rs = $db->fetch_array($query))
		{
			!$rs[pages] && $rs[pages]=1;
			for($i=1;$i<=$rs[pages];$i++){
				if($i>1){
					$ids.=",$rs[id]-$i";
				}else{
					$ids.=",$rs[id]";
				}
				$ck++;
				if($ck%20==0){
					write_file("../cache/makeShow1.php","\$fiddb[]='$fid';\$iddb[]='$ids';\r\n",'a');
					$ids='';
				}
			}			
		}
		if($ids){
			write_file("../cache/makeShow1.php","\$fiddb[]='$fid';\$iddb[]='$ids';\r\n",'a');
		}elseif(!$ck&&$page<2){
			write_file("../cache/makeShow1.php","\$fiddb[]='$fid';\$iddb[]='-1';\r\n",'a');
		}

		if($ck){
			$page++;
		}else{
			$iii++;
			$page=0;
		}
		echo "&page=$page&iii=$iii<META HTTP-EQUIV=REFRESH CONTENT='0;URL=?lfj=$lfj&action=$action&maketype=$maketype&page=$page&iii=$iii&beginTime=$beginTime&endTime=$endTime&beginId=$beginId&endId=$endId&posttype=$posttype&aids=$aids'>";
		exit;
	}
	else
	{
		//先执行生成列表页,再去生成内容页
		unset($fiddb);
		unlink(PHP168_PATH."cache/makeShow0.php");
		require_once("../cache/makeShow1.php");
		echo "<META HTTP-EQUIV=REFRESH CONTENT='0;URL=../do/list_html.php?fid=$fiddb[0]'>";
		exit;			
	}
}
//专题页生成静态
elseif($action=="make_SPhtml"&&$Apower[makehtml_make])
{
	//生成静态完毕
	if($step=='end'){
		echo "<META HTTP-EQUIV=REFRESH CONTENT='0;URL=?lfj=$lfj&job=listsp'>";
		exit;
	//已生成列表页,继续生成内容页
	}elseif($step=='endListStarBencandy'){
		unset($fiddb);
		require_once("../cache/makeShow1.php");
		$id_array=explode("-",$iddb[0]);
		echo "请稍候,正在生成内容页<META HTTP-EQUIV=REFRESH CONTENT='0;URL=../do/showsp_html.php?fid=$id_array[0]&id=$id_array[1]'>";
		exit;
	}

	if(!$fiddb&&!$idDB){
		showmsg("请选择一个栏目或一个专题");
	}
	
	if($fiddb){
		$stringFID=implode(",",$fiddb);
		$SQL=" fid IN ($stringFID) ";
	}elseif($idDB){
		$string=implode(",",$idDB);
		$SQL=" id IN ($string) ";
	}
	$str="<?php\r\n\$weburl='$WEBURL&step=end';\r\n";
	$query = $db->query("SELECT id,fid FROM {$pre}special WHERE $SQL LIMIT 3000");
	while($rs = $db->fetch_array($query))
	{
		$str.="\$iddb[]='$rs[fid]-$rs[id]';\r\n";
		if(!$stringFID){
			$fiddb[$rs[fid]]=$rs[fid];
		}
	}
	write_file("../cache/makeShow1.php",$str);
	
	if(!$stringFID){
		$stringFID=implode(",",$fiddb);
	}
	write_file("../cache/makelist.php","<?php\r\n \$allfid='$stringFID';\r\n\$weburl='$WEBURL&step=endListStarBencandy';");
	$fiddb=explode(",",$stringFID);
	echo "<META HTTP-EQUIV=REFRESH CONTENT='0;URL=../do/listsp_html.php?fid=$fiddb[0]'>";
	exit;
}
elseif($job=='del'&&$Apower[makehtml_make])
{
	if(!$fid){
		showmsg("FID不存在");
	}
	if($type=='sp'){
		$fidDB=$db->get_one(" SELECT * FROM {$pre}spsort WHERE fid='$fid' ");
		if($fidDB[list_html]){
			$filename=$fidDB[list_html];
		}else{
			$filename=$webdb[SPlist_filename];
		}
	}else{
		$fidDB=$db->get_one(" SELECT * FROM {$pre}sort WHERE fid='$fid' ");
		if($fidDB[list_html]){
			$filename=$fidDB[list_html];
		}else{
			$filename=$webdb[list_filename];
		}
	}	
	$page=1;
	eval("\$filename=\"$filename\";");
	$dirname=(dirname($filename)!='.')?dirname($filename):$filename;
	if($dirname)
	{
		if($step!=2){
			require(dirname(__FILE__)."/"."head.php");
			if(is_writable(PHP168_PATH."$dirname")){
				echo "<br><br><br><A HREF='index.php?lfj=html&job=del&fid=$fid&type=$type&step=2'><FONT COLOR='red'>".PHP168_PATH."$dirname</FONT>,你确认要删除此目录的文件吗?</A><br><br><br>";
			}else{
				echo '文件不存在或目录不可写';
			}
			require(dirname(__FILE__)."/"."foot.php");
			exit;
		}else{
			del_file(PHP168_PATH."$dirname");
		}
	}
	if($type=='sp'){
		refreshto("?lfj=html&job=listsp","删除成功");
	}else{
		refreshto("?lfj=html&job=list","删除成功");
	}	
}



/*栏目列表*/
function list_2allsort($fid,$table='sort'){
	global $db,$pre,$sortdb,$webdb;
	$query=$db->query("SELECT * FROM {$pre}$table WHERE fup='$fid' ORDER BY list DESC");
	while( $rs=$db->fetch_array($query) ){
		$icon="";
		for($i=1;$i<$rs['class'];$i++){
			$icon.="&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;";
		}
		if($icon){
			$icon=substr($icon,0,-24);
			$icon.="--";
		}
		if($table=='spsort'){
			if($rs[list_html]){
				$rs[filename]=$rs[list_html];
			}else{
				$rs[filename]=$webdb[SPlist_filename];
			}
		}else{
			if($rs[list_html]){
				$rs[filename]=$rs[list_html];
			}else{
				$rs[filename]=$webdb[list_filename];
			}
			$rs[filename]=preg_replace("/(.*)\/([^\/]+)/is","\\1/",$rs[filename]);			
		}
		$fid=$rs[fid];
		$page=1;
		eval("\$rs[filename]=\"$rs[filename]\";");
		if(is_dir(PHP168_PATH.$rs[filename])||is_file(PHP168_PATH.$rs[filename])){
			$rs[havemade]=1;
		}else{
			$rs[havemade]=0;
		}
		$rs[config]=unserialize($rs[config]);
		$rs[icon]=$icon;
		$sortdb[]=$rs;

		list_2allsort($rs[fid],$table);
	}
}

function list_sort_guide($fup){
	global $db,$pre,$mid,$only,$lfj,$job;
	$rs=$db->get_one("SELECT fup,name FROM {$pre}sort WHERE fid='$fup'");
	if($rs){
		$show=" -> <A HREF='index.php?lfj=$lfj&job=$job&only=$only&mid=$mid&fid=$fup'>$rs[name]</A> ";
		$show=list_sort_guide($rs[fup]).$show;
	}
	return $show;
}
?>