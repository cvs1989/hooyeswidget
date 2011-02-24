<?php
!function_exists('html') && exit('ERR');

$linkdb=array("核心设置"=>"$admin_path&job=config","置顶信息设置"=>"$admin_path&job=top");

if($job)
{
	$query=$db->query("SELECT * FROM {$_pre}config");
	while( $rs=$db->fetch_array($query) ){
		$webdb[$rs[c_key]]=$rs[c_value];
	}
}
if($job=="label" && ck_power('center_label')){

	echo "<META HTTP-EQUIV=REFRESH CONTENT='0;URL=$Murl/index.php?jobs=show'>";
	exit;
}
elseif($job=="config" && ck_power('center_config'))
{
	$showNoPassComment[intval($webdb[showNoPassComment])]=' checked ';

	$CommentPass_group=group_box("webdbs[CommentPass_group]",explode(",",$webdb[CommentPass_group]));

	$GroupPassYz=group_box("webdbs[GroupPassYz]",explode(",",$webdb[GroupPassYz]));
	$webdb[Info_webOpen]?$Info_webOpen1='checked':$Info_webOpen0='checked';


	$Info_DelEndtime[intval($webdb[Info_DelEndtime])]=' checked ';



	$Info_allowGuesSearch[intval($webdb[Info_allowGuesSearch])]=' checked ';
	$Info_Searchkeyword[intval($webdb[Info_Searchkeyword])]=' checked ';

	$Info_ShowNoYz[intval($webdb[Info_ShowNoYz])]=' checked ';

	$showNoPassComment[intval($webdb[showNoPassComment])]=' checked ';

	$Info_webOpen[intval($webdb[Info_webOpen])]=' checked ';
	
	$order_send_msg[intval($webdb[order_send_msg])]=' checked ';

	$order_send_mail[intval($webdb[order_send_mail])]=' checked ';

	$Info_index_cache[intval($webdb[Info_index_cache])]=' checked ';

	$module_close[intval($webdb[module_close])]=" checked ";

	$Info_HYpost[intval($webdb[Info_HYpost])]=" checked ";

	get_admin_html('config');
}

elseif($action=="config" && ck_power('center_config'))
{

	if($GroupPassYz){
		$webdbs[GroupPassYz]=$webdbs[GroupPassYz];
	}
	if($GroupPostInfo){
		$webdbs[GroupPostInfo]=$webdbs[GroupPostInfo];
	}

	if($Info_GroupCommentYzImg){
		$webdbs[Info_GroupCommentYzImg]=$webdbs[Info_GroupCommentYzImg];
	}
	if($Info_GroupPostYzImg){
		$webdbs[Info_GroupPostYzImg]=$webdbs[Info_GroupPostYzImg];
	}

	module_write_config_cache($webdbs);
	refreshto($FROMURL,"修改成功");
}

elseif($action=="settable")
{
	module_write_config_cache($webdbs);
	refreshto($FROMURL,"设置成功");
}
elseif($job=="settable")
{
	$layout=array();
	$detail=explode("#",$webdb[sort_layout]);
	foreach($detail AS $key=>$value){
		$detail2=explode(",",$value);
		foreach($detail2 AS $fup){
			if(!$Fid_db['0'][$fup]){
				continue;
			}
			$layout[$key][$fup]['name']=$Fid_db['name'][$fup];
			$layout[$key][$fup]['son']=$Fid_db[$fup];
			$ckfup[$fup]=1;
		}
	}
	foreach($Fid_db[0] AS $fup=>$name){
		if(!$ckfup[$fup]){
			$layout[0][$fup]['name']=$Fid_db['name'][$fup];
			$layout[0][$fup]['son']=$Fid_db[$fup];
		}
	}

	get_admin_html('settable');
}
elseif($job=="top")
{
	$Info_NewsMakeHtml[$webdb[Info_NewsMakeHtml]]=' checked ';

	get_admin_html('top');
}


?>