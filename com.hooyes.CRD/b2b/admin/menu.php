<?php
$base_menuName=array('business'=>'�������','base'=>'ϵͳ����','article'=>'���¹���','member'=>'��Ա����','other'=>'��������');


@include(PHP168_PATH."php168/hack.php");

if($ForceEnter||$GLOBALS[ForceEnter]){
	foreach( $menu_partDB AS $key1=>$value1){
		if($key1=='base'){
			continue;
		}
		foreach( $value1 AS $key2=>$value2){
			$menu_partDB['base'][]=$value2;
		}
	}
}

?>