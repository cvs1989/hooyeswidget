<?php
if($job=='js'){
	$time=time()-@filemtime(PHP168_PATH."cache/hack/oicq.php");
	if($time>3600){
		$rs=$db->get_one("SELECT * FROM {$pre}hack WHERE keywords='$hack'");
		$rs[htmlcode]=AddSlashes($rs[htmlcode]);
		echo "document.write(\"$rs[htmlcode]\")";
		write_file(PHP168_PATH."cache/hack/oicq.php","<?php\r\n\$htmlcode=\"$rs[htmlcode]\";");
	}else{
		include(PHP168_PATH."cache/hack/oicq.php");
		echo "document.write(\"$htmlcode\")";
	}
}elseif($job=='test'){
	echo "Ð§¹û:<hr><SCRIPT src='hack.php?hack=$hack&job=js'></SCRIPT>";
}
?>