<?php

if($id){
			
			$data=$db->get_one("select * from {$_pre}homepage_article where id='$id'");
			//��ʵ��ַ��ԭ
			$data[content]=En_TruePath($data[content],0);
			$data[posttime] =date("Y-m-d",$data[posttime] );

//�õ��󶨵�ͼƬ
$show_bd_pics=show_bd_pics("{$_pre}homepage_article"," where id=$id");

if($data[uid]!=$lfjuid && !$data[yz]){

	
print <<<EOT
-->   
    
��Ϣ���ڽ����������...
<!--
EOT;
	}else{

print <<<EOT
-->   
    
<table width="100%" border="0" cellspacing="0" cellpadding="0"  class="rightinfo">
  <tr>
    <td class="head"><span class='L'></span>
	<span class='T'>��˾����</span>
	<span class='R'></span>
	<span class='more'>
<!--
EOT;
if($lfjuid==$uid){
print <<<EOT
-->
	<a href='$Mdomain/member/?main=homepage_ctrl.php?atn=postnews&uid=$uid&id=$id' target='_blank'>�༭</a> | <a href='$Mdomain/member/?main=homepage_ctrl.php?atn=delnews&uid=$uid&id=$id' target='_blank'>ɾ��</a> 
<!--
EOT;
}
print <<<EOT
-->
	</span></td>
  </tr>
  <tr>
    <td  class="content">
<center style='font-size:16px;'><strong>$data[title]</strong></center>
<center style='border-bottom:1px #454646 dotted'>ʱ�䣺$data[posttime] �����$data[hits]�� </center>
<br>
<div>$show_bd_pics</div>
<div>$data[content]</div>

	</td>
  </tr>
</table>

 
<!--
EOT;

$db->query("update `{$_pre}homepage_article` set hits=hits + 1  where id='$id'");

	}		


}

?>
