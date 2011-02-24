<?php
require(dirname(__FILE__)."/global.php");


$field_db = $module_DB[$mid][field];

if($action=="search")
{
	if(!$webdb[Info_allowGuesSearch]&&!$lfjid)
	{
		showerr("请先登录");
	}

	if(!$type){
		$type='title';
	}

	$keyword=trim($keyword);
	$keyword=str_replace("%",'\%',$keyword);
	$keyword=str_replace("_",'\_',$keyword);

	if(!$keyword)
	{
		showerr("关键字不能为空!");
	}
	if($Fid_db[tableid]&&!$fid){
		showerr("请选择一个栏目!");
	}
	$_erp=$Fid_db[tableid][$fid];

	/*每页显示50条*/
	$rows=50;
	if(!$page)
	{
		$page=1;
	}
	$min=($page-1)*$rows;

	/*没指定模块或模块辅信息表不存在时,将搜索所有信息*/
	if(!$mid||!is_table("{$_pre}content_$mid"))
	{
		if($keyword){
			if(in_array($type,array("title","username")))
			{
				$field="A.$type";
			}
			else
			{
				$field="A.title";
			}
			$_SQL=" $field LIKE '%$keyword%' ";
		}else{
			$_SQL=" 1 ";
		}


		if($fid>0){
			$_SQL.=" AND A.fid='$fid' ";
		}

		foreach( $postdb AS $key=>$value)
		{
			$search_url.="&postdb[{$key}]=$value";
		}

		/*分页*/
		$showpage=getpage("{$_pre}content$_erp A","WHERE  $_SQL","?fid=$fid&keyword=$keyword&action=search&type=$type$search_url",$rows);

		$SQL="SELECT * FROM {$_pre}content$_erp A WHERE $_SQL ORDER BY A.posttime DESC LIMIT $min,$rows ";
	}
	else
	{
		if($keyword){
			if(in_array($type,array("title","username")))
			{
				$field="A.$type";
			}
			elseif( $type && table_field("{$_pre}content_$mid",$type) )
			{
				$field="B.$type";
			}
			else
			{
				$field="A.title";
			}
			$_SQL=" $field LIKE '%$keyword%' ";
		}else{
			$_SQL=" 1 ";
		}

		if($fid>0){
			$_SQL.=" AND A.fid='$fid' ";
		}
	
		$search_url='';
		foreach( $postdb AS $key=>$value)
		{
			if( $value && table_field("{$_pre}content_$mid",$key) )
			{
				$_SQL.=" AND B.`$key`='$value' ";
				$rsdb[$key][$value]=" selected ";
				$value=urlencode($value);
			}
			$search_url.="&postdb[{$key}]=$value";
		}

		//分页功能
		$showpage=getpage("{$_pre}content$_erp A LEFT JOIN {$_pre}content_$mid B ON A.id=B.id","WHERE A.mid='$mid' AND  $_SQL","?mid=$mid&fid=$fid&keyword=$keyword&action=search&type=$type$search_url",$rows);

	

		$SQL="SELECT A.*,B.* FROM {$_pre}content$_erp A LEFT JOIN {$_pre}content_$mid B ON A.id=B.id WHERE A.mid='$mid' AND $_SQL ORDER BY A.posttime DESC LIMIT $min,$rows ";
	}

	$query = $db->query("$SQL");
	while($rs = $db->fetch_array($query))
	{
		$rs[posttime]=date("Y-m-d H:i",$rs[posttime]);
		$rs[content]=@preg_replace('/<([^>]*)>/is',"",$rs[content]);
		$rs[content]=get_word($rs[content],150);
		if(!$rs[username])
		{
			$detail=explode(".",$rs[ip]);
			$rs[username]="$detail[0].$detail[1].$detail[2].*";
		}
		$field_db && $Module_db->showfield($field_db,$rs,'list');
		$listdb[]=$rs;
	}

	if(!$listdb)
	{
		//showerr("很抱歉，没有找到你要查询的内容");
	}
	$typedb[$type]=" checked ";
}

else
{
	$typedb[title]=" checked ";
}

$mid=intval($mid);

$module_select="<select name='mid' onChange=\"window.location.href='?mid='+this.options[this.selectedIndex].value\"><option value='0'  style='color:#aaa;'>所有模型</option>";
foreach($module_db AS $key=>$value){
	$ckk=$mid==$key?' selected ':' ';
	$module_select.="<option value='$key' $ckk>$value</option>";
}
$module_select.="</select>";

if($mid){
	$SQL=" AND mid='$mid' ";
}else{
	$SQL="";
}

$fid_select="<select name='fid' onChange=\"if(this.options[this.selectedIndex].value=='-1'){alert('你不能选择大分类');}\"><option value='0' style='color:#aaa;'>所有栏目</option>";
foreach( $Fid_db[0] AS $key=>$value){
	$fid_select.="<option value='-1' style='color:red;'>$value</option>";
	foreach( $Fid_db[$key] AS $key2=>$value2){
		$ckk=$fid==$key2?' selected ':' ';
		$fid_select.="<option value='$key2' $ckk>&nbsp;&nbsp;|--$value2</option>";
	}
}
$fid_select.="</select>";


require(ROOT_PATH."inc/head.php");
require(getTpl("search_".intval($mid)));
require(ROOT_PATH."inc/foot.php");

//过滤母模板当中的自定义字段
ob_end_clean();
$content=preg_replace("/<!--{choose}-->(.*?)<!--{\/choose}-->/is","",$content);
$content=preg_replace("/<!--{select}-->(.*?)<!--{\/select}-->/is","",$content);
$content=preg_replace("/<!--{template}-->(.*?)<!--{\/template}-->/is","",$content);
echo $content;

?>