<?php
require_once("global.php");

//功能判断

$linkdb=array("独立模块管理"=>"?","添加独立模块"=>"?action=add");

if(!$action){

	$rows=20;
	$page=intval($page);
	if($page<1) $page=1;
	$min=($page-1)*$rows;
	$where=" WHERE 1";
	
	$query=$db->query("select * from {$_pre}diypage A $where order by A.order_sort desc limit $min,$rows");
	
	while($rs=$db->fetch_array($query)){
		$rs[type_v]=$rs[type]?"系统":"自定义";
		$rs[isshow]=$rs[isshow]?"显示":"不显示";
		$listdb[]=$rs;
	}
	
	$showpage=getpage("{$_pre}diypage A ",$where,"?",$rows);


}elseif($action=='add' || $action=='edit'){

	if(!$step){
		if($diyid){
			$diy=$db->get_one("select * from {$_pre}diypage where diyid='$diyid'");
		}
		$diy[type]=$diy[type]?"系统":"自定义";
		
		$isshow[$diy[isshow]]=" checked";
	
		if($diy[filename]){
			$diy[content]=read_file("../template/$webdb[style]/".$diy[filename]);
		}
		$diy[filename]=$diy[filename]?str_replace(array('diy_','.htm'),array('',''),$diy[filename]):time();

	}else{
		
		if(!$diy[name]) showerr("模块名称不能为空");
		if(strlen($diy[name])>20 ) showerr("页面名称不能大于10个汉字");
		if(!$diy[content]) showerr("模块代码不能为空");
		

		if($diyid){
			
			$diy_old=$db->get_one("select filename from {$_pre}diypage where diyid='$diyid'");
			$diy[filename]=$diy_old[filename];
			$sql="update `{$_pre}diypage` set
			`name`='$diy[name]',
			`isshow`='$diy[isshow]',
			`order_sort`='$diy[order_sort]',
			`jumpto`='$diy[jumpto]'
			where diyid='$diyid' ";
		}else{

			$diy[filename]="diy_".$diy[filename].".htm";
			if(file_exists("../template/$webdb[style]/".$diy[filename])){
				showerr("模板文件名称重复");
			}
			$sql="INSERT INTO `{$_pre}diypage` ( `diyid` , `type` , `name` , `filename`,`jumpto` , `isshow` , `order_sort` , `hits` ) VALUES ('', '0', '$diy[name]', '$diy[filename]', '$diy[jumpto]','$diy[isshow]', '$diy[order_sort]', '0');";		
		}
		
		write_file("../template/$webdb[style]/".$diy[filename],str_replace(array("\'",'\"'),array("'",'"'),$diy[content]));
	
		$db->query($sql);
		diypage_cache();
		refreshto("?","操作成功");
	}

}elseif($action=='show'){

	$rsdb=$db->get_one("select * from {$_pre}diypage where diyid='$diyid';");
	if($rsdb[type]) showerr("系统类型独立模块只能编辑模板，其他操作不允许");
	if($rsdb[isshow]) $isshow=0;
	else $isshow=1;
	$db->query("update {$_pre}diypage set isshow='$isshow' where diyid='$diyid' ");
	diypage_cache();
	refreshto("?","操作成功");

}elseif($action=='del'){

	$rsdb=$db->get_one("select * from {$_pre}diypage where diyid='$diyid';");
	if($rsdb[type]){
		showerr("系统类型独立模块只能编辑模板，其他操作不允许");exit;
	}
	@unlink("../template/$webdb[style]/".$rsdb[filename]);
	$db->query("delete from  {$_pre}diypage where diyid='$diyid' ");
	diypage_cache();
	refreshto("?","操作成功");

}elseif($action=='betch_order'){
	
	if(!$order_sort) showerr("无排序项目");

	foreach($order_sort as $key=>$val){
		$db->query("update {$_pre}diypage set order_sort='$val' where diyid='$key' ");
	}
	diypage_cache();
	refreshto("?","操作成功");
}

//******************************************输出
require("head.php");
require("template/diypage/list.htm");
require("foot.php");
	

?>