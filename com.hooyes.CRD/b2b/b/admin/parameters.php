<?php
require_once("global.php");

//功能判断

$linkdb=array(
"参数模型管理"=>"?",
);

$cols_type=array(
'text'=>"单行文本框[短]",
'langtext'=>"单行文本框[长]",
'textarea'=>"多行文本框",
'radio'=>"单选框",
'checkbox'=>"多选框",
'select'=>"下拉列表"
);


if(!$job && !$action){

	$query = $db->query("SELECT * FROM {$_pre}parameters_module ORDER BY listorder DESC");
	while($rs = $db->fetch_array($query)){
		$listdb[]=$rs;
	}


	require("head.php");
	require("template/parameters/list.htm");
	require("foot.php");

}elseif($action=='orderlist'){

	if($listorder){
		foreach($listorder as $mid=>$val){
			$db->query("update {$_pre}parameters_module  set listorder='$val' where mid='$mid'");
		}		
	}
	refreshto("?","更新成功");

}elseif($action=='add'){
	
	if(!$name) showerr("请先输入模型的名称");
	$db->query("INSERT INTO `{$_pre}parameters_module` ( `mid` , `name` , `alias` , `listorder` , `config` )VALUES ('', '$name', '$name', '0', '');");
	$mid=$db->insert_id();
	refreshto("?job=edit&mid=$mid","创建成功",0);


}elseif($job=="edit"){

	if(!$mid) showerr("参数错误");
	$rsdb=$db->get_one("select * from `{$_pre}parameters_module` where mid='$mid' ");

	require("head.php");
	require("template/parameters/edit.htm");
	require("foot.php");


}elseif($action=='edit'){

	if(!$mid) showerr("参数错误");
	$db->query("update {$_pre}parameters_module set
	name='$name',
	alias='$alias',
	listorder='$val'
	where mid='$mid'");
	refreshto("?","更新成功");

}elseif($job=="del"){

	if(!$mid) showerr("参数错误");
	$db->query("delete from {$_pre}parameters_module where mid='$mid'");

	//是否同时删除数据
	if($deldata){
		$db->query("delete from {$_pre}parameters where mid='$mid'");
	}
	refreshto("?","删除成功");

}elseif($job=='colslist'){

	if(!$mid) showerr("参数错误");
	$middb=$db->get_one("select * from `{$_pre}parameters_module` where mid='$mid' ");
	//得到属性集合
	
	$cols=unserialize($middb[config]);

	require("head.php");
	require("template/parameters/colslist.htm");
	require("foot.php");


}elseif($action=='addcolslist'){

	
	if(!$mid) showerr("参数错误");


	if($cols){

		foreach($cols as $key=>$rs){
			if($rs[name] && $rs[type]){
				$yes_cols[]=$rs;
			}
		}
		$db->query("update {$_pre}parameters_module  set config='".serialize($yes_cols)."' where mid='$mid'");
	}
	refreshto("?job=colslist&mid=$mid","设置成功");

}



?>