<?php
!function_exists('html') && exit('ERR');
$db->query("UPDATE `{$pre}special` SET hits=hits+1,lastview='$timestamp' WHERE id='$id'");
$rs=$db->get_one(" SELECT hits FROM {$pre}special WHERE id='$id' ");
echo "document.getElementById('hits').innerHTML='$rs[hits]';";	
?>