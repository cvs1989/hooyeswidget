<?php
require("global.php");
if($lfjuid){
	header("location:homepage.php?uid=$lfjuid".($m?"&m=$m":"").($ctrl?"&ctrl=$ctrl":""));
}else{
	showerr("����û�е�½�����ȵ�½");
}