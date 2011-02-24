<?php
require("global.php");
if($lfjuid){
	header("location:homepage.php?uid=$lfjuid".($m?"&m=$m":"").($ctrl?"&ctrl=$ctrl":""));
}else{
	showerr("您还没有登陆，请先登陆");
}