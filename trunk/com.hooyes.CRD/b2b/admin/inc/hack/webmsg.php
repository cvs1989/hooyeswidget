<?php
!function_exists('html') && exit('ERR');
if($job=='list'&&$Apower[hack_list])
{
	$rsdb=$db->get_one("SELECT * FROM {$pre}hack WHERE keywords='$hack' ");
	@extract(unserialize($rsdb[config]));
	foreach( $system AS $key=>$value){
		$Stype[$key]=' checked ';
	}
	$tplcode=stripslashes($tplcode);
	//if(!$tplcode){
		$tplcode=read_file("template/hack/webmsg/webmsgtpl.htm");
	//}
	if(eregi("^pwbbs",$webdb[passport_type]))
	{
		$tplcode=str_replace("</table>",'<tr>
      <td height="7" >��̳����: {$topic_num} ��</td>
</tr>
<tr>
      <td height="7" >���շ���: {$bs[yposts]} ��</td>
</tr>
<tr>
      <td height="7" >����շ���: {$bs[hposts]} ��</td>
</tr>
<tr>
      <td height="7" >�������: {$bs[higholnum]} ��</td>
</tr>
<tr>
      <td height="7" >��ӭ����: {$bs[newmember]}</td>
</tr></table>',$tplcode);
	}
	elseif(eregi("^dzbbs",$webdb[passport_type]))
	{
		$tplcode=str_replace("</table>",'<tr>
      <td height="7" >��̳����: {$topic_num} ��</td>
</tr>
<tr>
      <td height="7" >��̳����: {$post_num} ��</td>
</tr>
<tr>
      <td height="7" >��ӭ����: {$newmember}</td>
</tr></table>',$tplcode);
	}
	require("head.php");
	require("template/hack/webmsg/list.htm");
	require("foot.php");
}
elseif($action=='list'&&$Apower[hack_list])
{
	$postdb['system']=$Stype;
	$db->query("UPDATE {$pre}hack SET config='".AddSlashes(serialize($postdb))."' WHERE keywords='$hack'");
	//$show="<?php
	//		\$tplcode=\"$postdb[tplcode]\";";
	//write_file(PHP168_PATH."cache/hack/webmsg.php",$show);
	jump("���óɹ�","index.php?lfj=hack&hack=$hack&job=getcode",0);
}
elseif($job=='getcode'&&$Apower[hack_list])
{
	$rs=$db->get_one("SELECT * FROM {$pre}hack WHERE keywords='$hack'");
	@extract(unserialize($rs[config]));
	require("head.php");
	require("template/hack/webmsg/getcode.htm");
	require("foot.php");
}
elseif($job=="choose"&&$Apower[hack_list])
{
	$msg=read_file("template/hack/webmsg/$type.htm");
	$msg=AddSlashes($msg);
	$msg=str_replace("\r\n",'\r\n',$msg);

	echo "<SCRIPT LANGUAGE=\"JavaScript\">
	<!--
	window.parent.showcode('$msg');
	//-->
	</SCRIPT>";
}
?>