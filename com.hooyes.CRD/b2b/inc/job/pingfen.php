<?php
!function_exists('html') && exit('ERR');
/*针对每条信息30分钟才允许评分一次*/
$time=30*60;

$pingfenID="pingfenID_$id";
if($_COOKIE[$pingfenID])
{
	showerr("半小时内,不能重复操作!!!");
}
set_cookie($pingfenID,"1",$time);

$_web=preg_replace("/http:\/\/([^\/]+)\/(.*)/is","http://\\1",$WEBURL);
if(!ereg("^$_web",$FROMURL))
{
	showerr("系统设置不能从外部提交数据");
}

$fidDB=$db->get_one("SELECT config AS m_config FROM {$pre}article_module WHERE id='$mid'");
if(!$fidDB)
{
	showerr(" 有误 ");
}

$rsdb=$db->get_one("SELECT * FROM `{$pre}article_content_$mid` WHERE aid='$id' AND rid='$rid'");

if($fid!=$rsdb[fid])
{
	showerr("FID有误,不一致");
}

$m_config=unserialize($fidDB[m_config]);

if(!$rsdb)
{
	showerr("资料有问题");
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