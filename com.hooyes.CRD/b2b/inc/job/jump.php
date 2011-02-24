<?php
!function_exists('html') && exit('ERR');
if($pagetype=='list'||$pagetype=='list_label'){
	$rs=$db->get_one("SELECT fid FROM {$pre}sort WHERE type!=2 ORDER BY type DESC,fid ASC LIMIT 1");
	if($pagetype=='list'){
		header("location:$webdb[www_url]/list.php?fid=$rs[fid]");exit;
	}elseif($pagetype=='list_label'){
		header("location:$webdb[www_url]/list.php?fid=$rs[fid]&jobs=show");exit;
	}		
}elseif($pagetype=='bencandy'||$pagetype=='bencandy_label'){
	$rs=$db->get_one("SELECT aid,fid FROM {$pre}article WHERE yz=1 AND mid=0 ORDER BY aid DESC LIMIT 1");
	if($pagetype=='bencandy'){
		header("location:$webdb[www_url]/bencandy.php?fid=$rs[fid]&aid=$rs[aid]");exit;
	}elseif($pagetype=='bencandy_label'){
		header("location:$webdb[www_url]/bencandy.php?fid=$rs[fid]&aid=$rs[aid]&jobs=show");exit;
	}		
}
?>