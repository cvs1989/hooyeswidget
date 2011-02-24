<?php
//ио╢╚м╪©Б

	$webdb[company_picsort_Max]=$webdb[company_picsort_Max]?$webdb[company_picsort_Max]:10;
	$query=$db->query("SELECT * FROM {$_pre}picsort WHERE uid='$uid' ORDER BY orderlist DESC LIMIT 0,$webdb[company_picsort_Max];");
	while($rs=$db->fetch_array($query)){
	$listdb[]=$rs;
	}
	$webdb[company_upload_max]=$webdb[company_upload_max]?$webdb[company_upload_max]:8;
	$webdb[company_uploadnum_max]=$webdb[company_uploadnum_max]?$webdb[company_uploadnum_max]:100;
	
	@extract($db->get_one("SELECT COUNT(*) AS myuploadedpicnum FROM {$_pre}pic WHERE uid='$uid';"));


?>