<?php
require_once("global.php");


if($job=="set"){
	require("head.php");
	require("template/makehtml/set.htm");
	require("foot.php");

}elseif($action=="deleteindex"){
	
	if(unlink(PHP168_PATH."/index.htm")){   
		refreshto("?job=set","删除成功");
	}elseif(is_file(PHP168_PATH."/index.htm")){
		showerr("删除失败,请修改文件属性为可写");
	}else{
		showerr("删除失败,文件不存在");
	}

}elseif($job=="bencandy"){

	if($webdb[bencandyIsHtml]){

		$select_news=$Guidedb->Select("{$pre}business_sort",'fiddb[]',$fiddb,'','0','',1,'20');
		require("head.php");
	    require("template/makehtml/make.htm");
		require("foot.php");
	}else{
		showerr("您还没有开启内容页静态开关，请在“<a href='center.php?job=config'><font color=red>系统设置</font></a>”启用内容也静态功能!");
	}

}elseif($action=="make"){

	if(!$fiddb[0]){
		showerr("请选择一个栏目");
	}
	$i=0;
	foreach($fiddb AS $key=>$fid){
		if(!$fid){
			unset($fiddb[$key]);
			continue;
		}
		$phpcode.="\$tmpfiddb[$i]=$fid; \r\n";
		$i++;
	}
	write_file("tmpfiddb.php","<?php \r\n".$phpcode." ?>");

	jump("正在开始生成静态","?job=makedo&showNum=$showNum",1);


}elseif($job=="makedo"){

	$index=intval($index);
	$index=$index?$index:0;
	$showNum=$showNum?$showNum:10;

	if(file_exists("tmpfiddb.php")){
		require("tmpfiddb.php");
	}else{
		showerr("临时文件出错！");
	}
	
	if($tmpfiddb){
		if(!$tmpfiddb[$index]){
			jump("全部生成完毕","?job=bencandy",1);
		}
		$page=$page?$page:1;
		$min=($page-1)*$showNum;
		$over=true;
		$query=$db->query("select id,title from {$_pre}content where fid=".$tmpfiddb[$index]." limit $min,$showNum");
		while($rs=$db->fetch_array($query)){
			$rt=@file_get_contents($Mdomain."/bencandy.php?fid={$rs[fid]}&id=$rs[id]&makehtml=back");
			$over=false;
		}
		
		if(!$over){
			jump("已经生成<font color=red>".$Fid_db[name][$tmpfiddb[$index]]."</font>第{$page}批，请稍等...","?job=makedo&showNum=$showNum&index=$index&page=".($page+1),1);
		}else{
			jump("生成<font color=red>".$Fid_db[name][$tmpfiddb[$index]]."</font>完毕，请稍等...","?job=makedo&showNum=$showNum&index=".($index+1),1);
		}
	}else{
		jump("无可用栏目","?job=bencandy",1);
	}

	
}


?>