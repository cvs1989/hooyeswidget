<?php


	require(PHP168_PATH."php168/all_area.php");


		foreach($area_db[0] as $key=>$p){
		
			
			$area_DB[0][$key]=&$area_db[name][$key];
			$area_DB[name][$key]=&$area_db[name][$key];
			$area_DB[fup][$key]='0';
	
			
			foreach($area_db[$key] as $key2=>$p2){
				$city_DB[$key][$key2]=&$area_db[name][$key2];
				$city_DB[name][$key2]=&$area_db[name][$key2];
				$city_DB[fup][$key2]=$key;
				
			}
		}


		

?>