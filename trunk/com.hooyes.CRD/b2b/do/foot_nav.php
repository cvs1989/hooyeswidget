<?php
require_once(dirname(__FILE__)."/".'./global.php');
@include(PHP168_PATH."php168/guide_fid.php");
$forum_ups=$GuideFid[$fid];
$forum_ups=str_replace("list.php?","$webdb[www_url]/list.php?",$forum_ups);
require_once(html("foot_nav"));
?>