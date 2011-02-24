<?php
if($job=='js'){
	@include(PHP168_PATH."cache/hack/search.php");
	$searchcode=str_replace("\r","",$searchcode);
	$searchcode=str_replace("\n","",$searchcode);
	echo "document.write('$searchcode');";
}elseif($job=='test'){
	//@include(PHP168_PATH."cache/hack/search.php");
	//eval("\$membercode=\"$membercode\";");
	echo "<SCRIPT src=\"hack.php?hack=$hack&job=js\"></SCRIPT>";
}