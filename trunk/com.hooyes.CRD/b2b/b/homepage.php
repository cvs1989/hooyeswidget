<?php
require("global.php");
require("bd_pics.php");

//引入动作文件
require(Mpath."/homepage_php/global.php");
require(Mpath."/inc/categories.php");

$bcategory->cache_read();
$bcategory->unsets();

//检测用户
$uid=intval($uid);



if(!$uid) showerr("抱歉,没有找到您要访问的页面！");

$rsdb=$db->get_one("SELECT * FROM {$_pre}company WHERE uid='$uid' LIMIT 1");


if(!$rsdb[rid])
{
	//判断是不是自己应该登记商家
	if($uid==$lfjuid) showerr("您还没有登记商家，<a href='$Mdomain/member/?main=post_company.php'><b>点击这里</b></a>登记");
	else showerr("商家信息未登记");
}
if($uid!=$lfjuid){//如果不是自己的商铺就提示不能看
	if(!$rsdb[yz])  showerr("暂时还不能提供该商家信息");
}
//商家配置文件
$conf=$db->get_one("SELECT * FROM {$_pre}homepage where rid='$rsdb[rid]' LIMIT 1");
if(!$conf[hid]) { //激活商家信息
	caretehomepage($rsdb);
}

//公司名称,有banner时候隐藏
if(!$conf[banner]) $rsdb[company_name_big]=$rsdb[title];
else $conf[banner]=" style='background:url(".$webdb[www_url]."/".$webdb[updir]."/{$Imgdirname}/banner/".$conf[banner].");'";

//风格
$homepage_style="default";
if($conf[style] && is_dir($tpl_dir.$conf[style])) $homepage_style=$conf[style];

//模块
$conf[bodytpl]=$conf[bodytpl]?$conf[bodytpl]:"left";

//数据处理
$rsdb[logo]=$webdb[www_url].'/'.$webdb[updir]."/$Imgdirname/ico/".$rsdb[picurl];
$rsdb[renzheng]=getrenzheng($rsdb[renzheng]);
$conf[listnum]=unserialize($conf[listnum]);

$conf[index_left]=explode(",",$conf[index_left]);
$conf[index_right]=explode(",",$conf[index_right]);

//SEO
$titleDB[title]			= filtrate(strip_tags("$rsdb[title]  - $webdb[Info_webname]"));
$titleDB[keywords]		= filtrate(strip_tags("$webdb[Info_metakeywords]"));
$titleDB[description]	= strip_tags( $webdb[Info_metadescription]);


//访客


if($lfjuid)
{
	if($lfjuid!=$conf[uid]){
		$conf[visitor]="{$lfjuid}\t{$lfjid}\t{$timestamp}\r\n$conf[visitor]";
	}
}
else
{
	$conf[visitor]="0\t{$onlineip}\t{$timestamp}\r\n$conf[visitor]";
}

$detail=explode("\r\n",$conf[visitor]);
foreach( $detail AS $key=>$value)
{
	if($key>0&&(strstr($value,"{$lfjuid}\t{$lfjid}\t")||strstr($value,"0\t$onlineip")))
	{
		unset($detail[$key]);
	}
	if($key>20||!$value)
	{
		unset($detail[$key]);
	}
}
$conf[visitor]=implode("\r\n",$detail);

$db->query("UPDATE {$_pre}homepage SET hits=hits+1,visitor='$conf[visitor]' WHERE uid='$uid' ");
$db->query("UPDATE {$_pre}company  set hits=hits+1 WHERE uid='$uid'");



//输出
require(getTpl("homepage_head"));
require(getTpl("homepage"));
require(Mpath."inc/foot.php");
?>