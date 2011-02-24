<?php
//更新公司横幅设置

if(!$step){

	$conf=$db->get_one("SELECT * FROM {$_pre}home WHERE uid='$uid' LIMIT 1");
	$conf[banner_show]=tempdir($conf[banner]);

}else{
		//图片处理
	if($del_banner==1){
		delete_attachment($uid,tempdir($conf[oldfile]));
		$banner="";
	}else{
		$banner=$conf[oldfile];
		if($postfile){
			$array[name]=is_array($postfile)?$_FILES[postfile][name]:$postfile_name;
			$array[path]=$webdb[updir]."/homepage/banner/";
			if(!is_dir($array[path])) @mkdir($array[path]);
			$array[size]=is_array($postfile)?$_FILES[postfile][size]:$postfile_size;
			//if($array[size]>$webdb[homepage_banner_size]*1024) showerr("图片文件不能超过$webdb[homepage_banner_size] K");
			$picurl_t=upfile(is_array($postfile)?$_FILES[postfile][tmp_name]:$postfile,$array);
			if($picurl_t){//更换图片
				$banner="homepage/banner/$picurl_t";
				delete_attachment($uid,tempdir($conf[oldfile]));
			}		
		}		
	}
	$db->query("UPDATE {$_pre}home SET
		`banner`='$banner'
		WHERE uid='$uid'");

	refreshto("?uid=$uid&atn=$atn","设置成功");	
}
?>