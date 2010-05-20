<?php
error_reporting(E_ALL ^ E_NOTICE);
$classid=(int)$_GET['classid'];
$tempid=(int)$_GET['tempid'];
?>
<meta http-equiv="Content-Type"  content="text/html;  charset=utf-8">
<body>
<form id="gotosearch" name="gotosearch" method="post" action="../../e/search/index.php">
<input type="hidden" name="show" value="<?=$_GET['show']?>">
<input type="hidden" name="classid" value="<?=$classid?>">
<input type="hidden" name="tbname" value="<?=$_GET['tbname']?>">
<input type="hidden" name="tempid" value="<?=$tempid?>">
<input type="hidden" name="starttime" value="<?=$_GET['starttime']?>">
<input type="hidden" name="endtime" value="<?=$_GET['endtime']?>">
<input type="hidden" name="startprice" value="<?=$_GET['startprice']?>">
<input type="hidden" name="endprice" value="<?=$_GET['endprice']?>">
<input type="hidden" name="orderby" value="<?=$_GET['orderby']?>">
<input type="hidden" name="myorder" value="<?=$_GET['myorder']?>">
<input type="hidden" name="keyboard" value="<?=$_GET['keyboard']?>">
<input type="hidden" name="allsame" value="<?=$_GET['allsame']?>">
<input type="hidden" name="getvar" value="1">
</form>
<script>
document.getElementById('gotosearch').submit();
</script>
</body>