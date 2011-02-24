<?php
require_once("global.php");

//功能判断

$linkdb=array( );

if(!$action){
		
	$rows=20;
	$page=intval($page);
	if($page<1) $page=1;
	$min=($page-1)*$rows;
	$where=" WHERE 1";
		
	if($keyword) $where.=" and ( A.title like('%$keyword%') or B.title like('%$keyword%') ) ";
		
	$query=$db->query("select A.*,B.title as company_name from {$_pre}homepage_article A left join {$_pre}company B on B.rid=A.rid  $where order by A.posttime desc limit $min,$rows");
	
	while($rs=$db->fetch_array($query)){
		$rs[posttime] =date("Y-m-d",$rs[posttime]);
	
		$rs[title]    =get_word($rs[title_full]=$rs[title],60);
		$rs[levels_a] =$rs[levels]?"zhunlevels":"zhlevels";
		$rs[levels]   =$rs[levels]?"<font color=red>已推荐</font>":"未推荐";
		$rs[yz_a]     =$rs[yz]?"zhunyz":"zhyz";
		$rs[yz]       =!$rs[yz]?"<font color=red>未审核</font>":"已审核";
		
		$listdb[]=$rs;
	}
	
	$showpage=getpage("{$_pre}homepage_article A left join {$_pre}company B on B.rid=A.rid  ",$where,"?keyword=".urlencode($keyword),$rows);

	require("head.php");
	require("template/article/list.htm");
	require("foot.php");
	
}elseif($action=='zhyz'){
	
	if($id)  $listdb['only']=$id;
	if($listdb)	$ids=implode(',',$listdb);
	if($ids){
		
		$db->query("update `{$_pre}homepage_article` set yz=1 where id in($ids) ");
		refreshto("?","操作成功");
	}else{
		showerr("操作项目不明确");
	}	
	
	
}elseif($action=='zhunyz'){
	
	if($id)  $listdb['only']=$id;
	if($listdb)	$ids=implode(',',$listdb);
	if($ids){
		
		$db->query("update `{$_pre}homepage_article` set yz=0   where id in($ids) ");
		refreshto("?","操作成功");
	}else{
		showerr("操作项目不明确");
	}	


}elseif($action=='zhlevels'){

	if($id)  $listdb['only']=$id;
	if($listdb)	$ids=implode(',',$listdb);
	if($ids){
		
		$db->query("update `{$_pre}homepage_article` set levels=1  where id in($ids) ");
		refreshto("?","操作成功");
	}else{
		showerr("操作项目不明确");
	}	

}elseif($action=='zhunlevels'){

	if($id)  $listdb['only']=$id;
	if($listdb)	$ids=implode(',',$listdb);
	if($ids){
		
		$db->query("update `{$_pre}homepage_article` set levels=0  where id in($ids) ");
		refreshto("?","操作成功");
	}else{
		showerr("操作项目不明确");
	}	
}elseif($action=='del'){

	if($id)  $listdb['only']=$id;
	if($listdb)	$ids=implode(',',$listdb);
	if($ids){
		
		$db->query("delete from  `{$_pre}homepage_article`  where id in($ids) ");
		refreshto("?","操作成功");
	}else{
		showerr("操作项目不明确");
	}
}

//******************************************输出

?>