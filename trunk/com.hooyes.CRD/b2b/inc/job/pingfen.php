<?php
!function_exists('html') && exit('ERR');
/*���ÿ����Ϣ30���Ӳ���������һ��*/
$time=30*60;

$pingfenID="pingfenID_$id";
if($_COOKIE[$pingfenID])
{
	showerr("��Сʱ��,�����ظ�����!!!");
}
set_cookie($pingfenID,"1",$time);

$_web=preg_replace("/http:\/\/([^\/]+)\/(.*)/is","http://\\1",$WEBURL);
if(!ereg("^$_web",$FROMURL))
{
	showerr("ϵͳ���ò��ܴ��ⲿ�ύ����");
}

$fidDB=$db->get_one("SELECT config AS m_config FROM {$pre}article_module WHERE id='$mid'");
if(!$fidDB)
{
	showerr(" ���� ");
}

$rsdb=$db->get_one("SELECT * FROM `{$pre}article_content_$mid` WHERE aid='$id' AND rid='$rid'");

if($fid!=$rsdb[fid])
{
	showerr("FID����,��һ��");
}

$m_config=unserialize($fidDB[m_config]);

if(!$rsdb)
{
	showerr("����������");
}

$array=$m_config[field_db]; 

foreach( $postdb AS $key=>$value)
{
	if($array[$key][form_type]=='pingfen')
	{
		$db->query("UPDATE {$pre}article_content_{$mid} SET `$key`=`$key`+'$value' WHERE id='$i_id' ");
	}
}
header("location:$FROMURL");
?>