<?php
require("global.php");
header('Content-type: text/html; charset=gbk');
if($lfjuid){
	$show='  
	<table width="100%" align="center" cellpadding="0" cellspacing="0" class="tb" >
        <tr>
          <td align="left">�û���<strong><font color="#990000">'.$lfjid.' </font></strong>��ӭ����,[<a href="'.$webdb[www_url].'/do/login.php?action=quit">�˳���½</a>]</td>
          </tr>
        <tr>
          <td height="20" align="left">
		 �� <a href="'.$Mdomain.'/member/?main=post_sell.php">������Ӧ</a> 
		 �� <a href="'.$Mdomain.'/member/?main=post_buy.php">������</a> 
		 �� <a href="'.$Mdomain.'/jobs_post.php?job=jobs">������Ƹ</a>		  </td>
          </tr>
        <tr>
          <td height="20" align="left">
		  ��  <a href="'.$Mdomain.'/zh_post.php?job=postzh">����չ��</a> 
		 �� <a href="'.$Mdomain.'/member">��������</a> 
		  �� <a href="'.$Mdomain.'/myhomepage.php">�ҵ���ҳ</a>		  </td>
          </tr>
      </table>';
}else{
	$show='
	<form name="form1" id="form1" method="post" action="'.$webdb[www_url].'/do/login.php" style="margin:0px">
	  <table width="100%" align="center" cellpadding="0" cellspacing="0">
        <tr>
          <td width="27%" align="left">�û�����</td>
          <td width="40%" align="left">
            <input type="text"   value="" name="username" class="ipt" size="10"/>
       
          </td>
          <td width="33%" rowspan="2" align="center"><input type="submit" name="Submit"  value=" " class="spt" /></td>
        </tr>
        <tr>
          <td align="left">��&nbsp;&nbsp;�룺</td>
          <td align="left"><input name="password" type="password" class="ipt"  id="password"   value="" size="10"/></td>
        </tr>
        <tr>
          <td colspan="3" align="center">
		   <a href="'.$webdb[www_url].'/reg.php" target="_blank" style="background:url('.$Murl.'/images/'. $STYLE .'/onlyindex/005.jpg) left no-repeat;line-height:20px;padding-left:15px;">���ע��</a>		  &nbsp;&nbsp;&nbsp;
		  <a href="'.$webdb[www_url].'/do/sendpwd.php" target="_blank"  style="background:url('.$Murl.'/images/'. $STYLE .'/onlyindex/006.jpg) left no-repeat;line-height:20px;padding-left:15px;">��������</a>		  </td>
        </tr>
      </table><input type="hidden" name="step" value="2"><input class="radio" type="hidden" name="cookietime" value="86400" >
        </form>	';
	
}
	$show = str_replace(array("\r", "\n", "'"), array('', '', "\'"), $show);
	echo "<SCRIPT type=\"text/javascript\">
		parent.window.document.getElementById('{$showdiv}').innerHTML='$show';
		</SCRIPT>";
	exit;
?>