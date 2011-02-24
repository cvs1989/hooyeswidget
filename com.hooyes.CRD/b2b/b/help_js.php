<?php
require("global.php");


if(!$job){
//调用方法
//<script language="javascript" type="text/javascript" src="$Mdomain/help_js.php?keyw=关键字&fid=文档类目ID&listnum=显示条数&length=显示标题长度"></script>
//表
		$where=" where  1  ";
		$listnum=$listnum?$listnum:5;
		$length=$length?$length:30;
		if($fid)$where.=" and fid='$fid' ";
		if($keyw) $where.=" and title like('%$keyw%') ";
		$query=$db->query("select * from {$_pre}news $where order by levels desc limit 0,$listnum");
		while($rs=$db->fetch_array($query)){
			$rs[title]=get_word($rs[title],$length);
			$str.="<div><a href='$Mdomain/help.php?id=$rs[id]' target='_blank' style='height:20px;line-height:20px;background:url($Murl/images/default/help_js_ico.gif) no-repeat left;padding-left:12px;'>$rs[title]</a></div>";
	}
	//$str 只能有'单引号，不能出现双引号和换行
	echo "document.write(\"{$str}\");";
	exit;
}
?>