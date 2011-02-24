<?php
require_once("global.php");


$linkdb=array(
			"全部信息"=>"?job=list",
			"只供应"=>"?job=list&ctype=1",
			"只求购"=>"?job=list&ctype=2",
			"已审核的信息"=>"?job=list&type=yz&fid=$fid",
			"未审核的信息"=>"?job=list&type=unyz&fid=$fid",
			
			"推荐信息"=>"?job=list&type=levels&fid=$fid",
			);

$fid=intval($fid);
$fup_select=choose_sort(0,0,0,$ctype);
if($job=="list")
{
	$SQL=" 1 ";
	if($fid>0){
		$SQL.=" AND fid=$fid ";
	}
	if($type=="yz"){
		$SQL.=" AND yz=1 ";
	}
	elseif($type=="unyz"){
		$SQL.=" AND yz=0 ";
	}
	elseif($type=="del"){
		$SQL.=" AND yz=2 ";
	}
	elseif($type=="levels"){
		$SQL.=" AND levels=1 ";
	}
	elseif($type=="unlevels"){
		$SQL.=" AND levels=0 ";
	}
	elseif($type=="title"){
		$SQL.=" AND binary title LIKE '%$keyword%' ";
	}
	elseif($type=="username"){
		$SQL.=" AND binary username LIKE '%$keyword%' ";
	}

	$rows=50;
	if($page<1){
		$page=1;
	}
	$min=($page-1)*$rows;
	$order=" posttime";
	$desc="DESC";
	$showpage=getpage("{$_pre}content_sell","WHERE $SQL","?job=list&fid=$fid&type=$type&ctype=$ctype&keyword=".urlencode($keyword),$rows,"");
	//$sort_fid=$Guidedb->Select("{$_pre}sort","fid",$fid,"?job=list");
	$query=$db->query("SELECT * FROM {$_pre}content_sell WHERE $SQL ORDER BY $order $desc LIMIT $min,$rows");
	while($rs=$db->fetch_array($query))
	{
		if(!$rs[yz]){
			$rs[ischeck]="<A HREF='?id=$rs[id]&jobs=yz' style='color:black;'>未审核</A>";
		}elseif( $rs[yz]==1){
			$rs[ischeck]="<A HREF='?id=$rs[id]&jobs=unyz' style='color:blue;'>已审核</A>";
		}
		if(!$rs[levels]){
			$rs[iscom]="<A HREF='?id=$rs[id]&jobs=com&levels=1' style=''>未推荐</A>";
		}else{
			$rs[iscom]="<A HREF='?id=$rs[id]&jobs=uncom&levels=0' style='color:red;'>已推荐</A>";
		}
		$rs[title2]=urlencode($rs[title]);
		$rs[posttime]=date("Y-m-d H:i:s",$rs[posttime]);

		if($rs[ctype]==1)$rs[ctype_t]="<font color='red'>[供应]</font>";
		elseif($rs[ctype]==2)$rs[ctype_t]="<font color='blue'>[求购]</font>";

		$listdb[$rs[id]]=$rs;
	}
	require("head.php");
	require("template/list/list.htm");
	require("foot.php");
	
}elseif($jobs=="yz"){
	if(is_array($listdb) && count($listdb)>0){
		$ids=implode(",",$listdb);
		$db->query("update `{$_pre}content_sell` set yz=1 ,yztime='".time()."' where id in($ids);");		
	}
	if($id){
		$db->query("update `{$_pre}content_sell` set yz=1 ,yztime='".time()."' where id ='$id';");	
	}
	refreshto("$FROMURL","操作成功",1);
}elseif($jobs=="unyz"){
	if(is_array($listdb) && count($listdb)>0){
		$ids=implode(",",$listdb);
		$db->query("update `{$_pre}content_sell` set yz=0 ,yztime='' where id in($ids);");		
	}
	if($id){
		$db->query("update `{$_pre}content_sell` set yz=0 ,yztime='' where id ='$id';");	
	}
	refreshto("$FROMURL","操作成功",1);
	
}elseif($jobs=="com"){
	if(is_array($listdb) && count($listdb)>0){
		$ids=implode(",",$listdb);
		$db->query("update `{$_pre}content_sell` set levels=1 ,levelstime='".time()."' where id in($ids);");		
	}
	if($id){
		$db->query("update `{$_pre}content_sell` set levels=1 ,levelstime='".time()."' where id ='$id';");	
	}
	refreshto("$FROMURL","操作成功",1);

}elseif($jobs=="uncom"){
	if(is_array($listdb) && count($listdb)>0){
		$ids=implode(",",$listdb);
		$db->query("update `{$_pre}content_sell` set levels=0 ,levelstime='' where id in($ids);");		
	}
	if($id){
		$db->query("update `{$_pre}content_sell` set levels=0 ,levelstime='' where id ='$id';");	
	}
	refreshto("$FROMURL","操作成功",1);

}elseif($jobs=="move"){
	if(!$new_fid) showerr("请选择要移到的分类");

	$fidinfo=$db->get_one("select fup,name,fid from `{$_pre}sort` where fid='$new_fid'");
	if($fidinfo[fup]==0) showerr("不可以移到顶级分类");
	
	
	if(is_array($listdb) && count($listdb)>0){
		$ids=implode(",",$listdb);
		$db->query("update `{$_pre}content_sell` set fid='$new_fid',`fname`='{$Fid_db[name][$new_fid]}' where id in($ids);");		
	}
	refreshto("$FROMURL","操作成功",1);

}elseif($jobs=="del"){

	if(is_array($listdb) && count($listdb)>0){
		foreach($listdb as $id){
			if(!$id || !is_numeric($id)) continue;
			$rsdb=$db->get_one("select * from `{$_pre}content_sell` where id='$id';");
			if(!$rsdb) continue;
			extract($rsdb);
			if($db->query("delete from `{$_pre}content_2` where id='$id'")){
				$db->query("delete from `{$_pre}content_sell` where id='$id'");
				@unlink(PHP168_PATH.$webdb[updir]."/{$Imgdirname}/".$picurl);
				@unlink(PHP168_PATH.$webdb[updir]."/{$Imgdirname}/".$picurl.".gif");
			}
		}	
	}
	//清除一下缓存
	$path=Adminpath."../php168/list_cache/";
	del_file_listcache($path);
	refreshto("?job=list","删除成功",1);
}


?>