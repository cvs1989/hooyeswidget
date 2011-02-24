<?php
if($system_type=="photo")
{
	$showfid="请选择导入哪个分类:".$Guidedb->select("{$pre}photo_sort","fid");
}
elseif($system_type=="down")
{
	$showfid="请选择导入哪个分类:".$Guidedb->select("{$pre}down_sort","fid");
}
elseif($system_type=="flash")
{
	$showfid="请选择导入哪个分类:".$Guidedb->select("{$pre}flash_sort","fid");
}
elseif($system_type=="mv")
{
	$showfid="请选择导入哪个分类:".$Guidedb->select("{$pre}mv_sort","fid");
}
elseif($system_type=="music")
{
	$showfid="请选择导入哪个分类:".$Guidedb->select("{$pre}music_sort","fid");
}
elseif($system_type=="phpwind")
{
	$showfid="请选择导入哪个栏目:".phpwind_fid("fid");
}
elseif($system_type=="discuz")
{
	$showfid="请选择导入哪个栏目:".phpwind_fid("fid");
}
else
{
	$showfid="请选择导入哪个栏目:".$Guidedb->select("{$pre}sort","fid");
}

echo "<SCRIPT LANGUAGE=\"JavaScript\">
	<!--
	window.parent.showdiv(\"$showfid\")
	//-->
	</SCRIPT>";
exit;


function phpwind_fid($name='fid'){
	global $db,$TB_pre;
	$query = $db->query("SELECT * FROM {$TB_pre}forums WHERE fup=0");
	while($rs = $db->fetch_array($query)){
		$show.="<option value='$rs[fid]'>$rs[name]</option>";
		$query2 = $db->query("SELECT * FROM {$TB_pre}forums WHERE fup=$rs[fid]");
		while($rs2 = $db->fetch_array($query2)){
			$show.="&nbsp;&nbsp;<option value='$rs2[fid]'>$rs2[name]</option>";
			$query3 = $db->query("SELECT * FROM {$TB_pre}forums WHERE fup=$rs2[fid]");
			while($rs3 = $db->fetch_array($query3)){
				$show.="&nbsp;&nbsp;&nbsp;&nbsp;<option value='$rs3[fid]'>$rs3[name]</option>";
				$query4 = $db->query("SELECT * FROM {$TB_pre}forums WHERE fup=$rs3[fid]");
				while($rs4 = $db->fetch_array($query4)){
					$show.="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<option value='$rs4[fid]'>$rs4[name]</option>";
				}
			}
		}
	}
	$show=str_replace('"',"",$show);
	return "<select name='$name'>$show</select>";
}
?>