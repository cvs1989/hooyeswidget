<?php
if($job=='js'){
	$mtime=time()-@filemtime(PHP168_PATH."cache/hack/webmsg.php");
	if( $mtime>($cktime*60) ){
		$rs=$db->get_one("SELECT * FROM {$pre}hack WHERE keywords='$hack'");
		@extract(unserialize($rs[config]));

		@extract($db->get_one("SELECT COUNT(*) AS article_num FROM {$pre}article"));
		@extract($db->get_one("SELECT COUNT(*) AS sort_num FROM {$pre}sort"));
		@extract($db->get_one("SELECT COUNT(*) AS comment_num FROM {$pre}comment"));
		@extract($db->get_one("SELECT COUNT(*) AS guestbook_num FROM {$pre}guestbook"));
		if(!eregi("^pwbbs",$webdb[passport_type]))
		{
			@extract($db->get_one("SELECT COUNT(*) AS member_num FROM $TB[table]"));
		}
		if(eregi("^pwbbs",$webdb[passport_type]))
		{
			@extract($db->get_one("SELECT COUNT(*) AS topic_num FROM {$TB_pre}threads"));
			$bs=$db->get_one("SELECT * FROM {$TB_pre}bbsinfo");
			$member_num=$bs[totalmember];
		}
		elseif(eregi("^dzbbs",$webdb[passport_type]))
		{
			@extract($db->get_one("SELECT COUNT(*) AS topic_num FROM {$TB_pre}threads"));
			@extract($db->get_one("SELECT COUNT(*) AS post_num FROM {$TB_pre}posts"));
			@extract($db->get_one("SELECT username AS newmember FROM {$TB_pre}members ORDER BY uid DESC LIMIT 1"));
		}

		//$tplcode=AddSlashes($tplcode);
		eval("\$tplcode=\"$tplcode\";");
		$tplcode=str_replace("\n","",$tplcode);
		$tplcode=str_replace("\r","",$tplcode);
		$tplcode=str_replace("'","",$tplcode);
		echo "document.write('$tplcode')";
		$tplcode=AddSlashes($tplcode);
		write_file(PHP168_PATH."cache/hack/webmsg.php","<?php\r\n\$tplcode=\"$tplcode\";");
	}else{
		include(PHP168_PATH."cache/hack/webmsg.php");
		echo "document.write('$tplcode')";
	}
}elseif($job=='test'){
	echo "Ð§¹û:<hr><SCRIPT src='hack.php?hack=$hack&job=js&cktime=$cktime'></SCRIPT>";
}
?>