<?php
!function_exists('html') && exit('ERR');
if(!$web_admin){
	showerr('你无权操作');
}
if($step){
	$array[content]=($content);//$array[content]=filtrate($content);
	$array[uid]=$lfjuid;
	$array[username]=$lfjid;
	$array[posttime]=$timestamp;
	$str=addslashes(serialize($array));
	$db->query("UPDATE {$pre}guestbook SET reply='$str' WHERE id='$id'");
	refreshto("$webdb[www_url]/do/guestbook.php","留言成功",1);
}else{
	$rsdb=$db->get_one("SELECT * FROM {$pre}guestbook WHERE id='$id'");
	extract(unserialize($rsdb[reply]));
}
require(PHP168_PATH."inc/head.php");
print<<<EOT


<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center" class="dragTable">  <tr>     <td class="head">       <h3 class="L"></h3>      <span class="TAG">回复留言</span>       <h3 class="R"></h3>    </td>  </tr>  <tr>     <form name="form1" method="post" action="">      <td class="middle" align="center">         <textarea name="content" cols="100" rows="10">$content</textarea>        <br>      <input type="button" name="Submit" value="返回" onclick="window.location.href='$webdb[www_url]/do/guestbook.php'">  <input type="submit" name="Submit" value="回复">        <input type="hidden" name="id" value="$id">        <input type="hidden" name="step" value="2">      </td>    </form>  </tr>  <tr>     <td class="foot">       <h3 class="L"></h3>      <h3 class="R"></h3>    </td>  </tr></table>


EOT;
require(PHP168_PATH."inc/foot.php");
?>