<?php

require_once("global.php");
require_once("menu_add.php");
$list=999;
foreach($menudb as $dafenlei=>$obj){
		
	foreach($obj as $name=>$val){
	
		$keywords=strval(rand(100000,999999)).strval(rand(100000,999999));
		$sql="INSERT INTO `{$pre}hack` (`keywords`, `name`, `isclose`, `author`, `config`, `htmlcode`, `hackfile`, `hacksqltable`, `adminurl`, `about`, `list`, `linkname`, `isbiz`, `class1`, `class2`) VALUES ('$keywords', '$name', 0, '', '', '', '', '', '$val[link]', '', $list, '', 0, 'business', '$dafenlei')";
		echo $sql."<hr>";
		$db->query($sql);
		$list--;
	}

}

?>