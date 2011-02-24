<?php
$passport_admin=1;	//允许整站后台调用,0不允许,1允许


$menudb["每日数据"]["今日数据"]["link"]="data.php?day=1";
$menudb["每日数据"]["今日数据"]["power"]="1";
$menudb["每日数据"]["数据汇总"]["link"]="data.php?day=100000000";
$menudb["每日数据"]["数据汇总"]["power"]="1";



$menudb["基本管理"]["系统设置"]["link"]="center.php?job=config";
$menudb["基本管理"]["系统设置"]["power"]="1";


$menudb["基本管理"]["首页静态设置"]["link"]="makehtml.php?job=set";
$menudb["基本管理"]["首页静态设置"]["power"]="1";

$menudb["基本管理"]["内容页生成静态"]["link"]="makehtml.php?job=bencandy";
$menudb["基本管理"]["内容页生成静态"]["power"]="1";


$menudb["基本管理"]["首页变更"]["link"]="setIndex.php";
$menudb["基本管理"]["首页变更"]["power"]="1";




$menudb["标签管理"]["首页标签"]["link"]="../index.php?jobs=show";
$menudb["标签管理"]["首页标签"]["power"]="1";

$menudb["标签管理"]["供应主页标签"]["link"]="../sell.php?jobs=show";
$menudb["标签管理"]["供应主页标签"]["power"]="1";

$menudb["标签管理"]["供应列表页标签"]["link"]="../sell_list.php?jobs=show";
$menudb["标签管理"]["供应列表页标签"]["power"]="1";

$menudb["标签管理"]["求购主页标签"]["link"]="../buy.php?jobs=show";
$menudb["标签管理"]["求购主页标签"]["power"]="1";

$menudb["标签管理"]["求购列表页标签"]["link"]="../buy_list.php?jobs=show";
$menudb["标签管理"]["求购列表页标签"]["power"]="1";



$menudb["标签管理"]["商家主页标签"]["link"]="../company.php?jobs=show";
$menudb["标签管理"]["商家主页标签"]["power"]="1";


$menudb["标签管理"]["商家列表页标签"]["link"]="../clist.php?jobs=show";
$menudb["标签管理"]["商家列表页标签"]["power"]="1";




$menudb["标签管理"]["资讯中心首页标签"]["link"]="../news.php?jobs=show";
$menudb["标签管理"]["资讯中心首页标签"]["power"]="1";

$menudb["标签管理"]["人才招聘首页标签"]["link"]="../jobs.php?jobs=show";
$menudb["标签管理"]["人才招聘首页标签"]["power"]="1";

$menudb["标签管理"]["展会首页标签"]["link"]="../zh.php?jobs=show";
$menudb["标签管理"]["展会首页标签"]["power"]="1";
$menudb["标签管理"]["帮助中心标签"]["link"]="../help.php?jobs=show";
$menudb["标签管理"]["帮助中心标签"]["power"]="1";




$menudb["会员管理"]["会员管理"]["link"]="../../admin/index.php?lfj=member&job=list";
$menudb["会员管理"]["会员管理"]["power"]="1";
$menudb["会员管理"]["评论管理"]["link"]="comments.php";
$menudb["会员管理"]["评论管理"]["power"]="1";
$menudb["会员管理"]["举报信息管理"]["link"]="report.php?job=list";
$menudb["会员管理"]["举报信息管理"]["power"]="1";



$menudb["分类管理"]["系统分类"]["link"]="sort.php?job=listsort&ctype=3";
$menudb["分类管理"]["系统分类"]["power"]="1";
$menudb["分类管理"]["参数模型"]["link"]="parameters.php";
$menudb["分类管理"]["参数模型"]["power"]="1";
$menudb["分类管理"]["职位人才分类"]["link"]="jobs.php";
$menudb["分类管理"]["职位人才分类"]["power"]="1";
$menudb["分类管理"]["城市地区管理"]["link"]="../../admin/index.php?lfj=area&job=list";
$menudb["分类管理"]["城市地区管理"]["power"]="1";



$menudb["行业商家"]["商家资料"]["link"]="company.php";
$menudb["行业商家"]["商家资料"]["power"]="1";
$menudb["行业商家"]["商家新闻"]["link"]="article.php";
$menudb["行业商家"]["商家资料"]["power"]="1";
$menudb["行业商家"]["代理商家"]["link"]="agents.php";
$menudb["行业商家"]["代理商家"]["power"]="1";
$menudb["行业商家"]["VIP商家"]["link"]="vip.php";
$menudb["行业商家"]["VIP商家"]["power"]="1";
$menudb["行业商家"]["商家认证"]["link"]="renzheng.php";
$menudb["行业商家"]["商家认证"]["power"]="1";






$menudb["产品供应"]["产品供应"]["link"]="selllist.php?job=list";
$menudb["产品供应"]["产品供应"]["power"]="1";
$menudb["产品供应"]["未审核"]["link"]="selllist.php?job=list&type=unyz";
$menudb["产品供应"]["未审核"]["power"]="1";
$menudb["产品供应"]["已审核"]["link"]="selllist.php?job=list&type=yz";
$menudb["产品供应"]["已审核"]["power"]="1";
$menudb["产品供应"]["推荐产品"]["link"]="selllist.php?job=list&type=levels";
$menudb["产品供应"]["推荐产品"]["power"]="1";


$menudb["产品求购"]["产品求购"]["link"]="buylist.php?job=list";
$menudb["产品求购"]["产品求购"]["power"]="1";
$menudb["产品求购"]["未审核"]["link"]="buylist.php?job=list&type=unyz";
$menudb["产品求购"]["未审核"]["power"]="1";
$menudb["产品求购"]["已审核"]["link"]="buylist.php?job=list&type=yz";
$menudb["产品求购"]["已审核"]["power"]="1";
$menudb["产品求购"]["推荐产品"]["link"]="buylist.php?job=list&type=levels";
$menudb["产品求购"]["推荐产品"]["power"]="1";


$menudb["行业展会"]["展会列表"]["link"]="zh.php";
$menudb["行业展会"]["展会列表"]["power"]="1";

$menudb["行业展会"]["添加展会"]["link"]="../zh_post.php?job=postzh";
$menudb["行业展会"]["添加展会"]["power"]="1";
$menudb["行业展会"]["添加展会"]["up"]="1";

$menudb["行业展会"]["待审展会"]["link"]="zh.php?type=0";
$menudb["行业展会"]["待审展会"]["power"]="1";

$menudb["行业展会"]["已审展会"]["link"]="zh.php?type=1";
$menudb["行业展会"]["已审展会"]["power"]="1";
$menudb["行业展会"]["已审展会"]["up"]="1";

$menudb["行业展会"]["推荐展会"]["link"]="zh.php?levels=1";
$menudb["行业展会"]["推荐展会"]["power"]="1";
$menudb["行业展会"]["推荐展会"]["line"]="1";

$menudb["行业展会"]["展馆列表"]["link"]="zh.php?action=zlglist";
$menudb["行业展会"]["展馆列表"]["power"]="1";

$menudb["行业展会"]["添加展馆"]["link"]="../zh_post.php?job=postzlg";
$menudb["行业展会"]["添加展馆"]["power"]="1";
$menudb["行业展会"]["添加展馆"]["up"]="1";

$menudb["行业展会"]["待审展馆"]["link"]="zh.php?type=0&job=postzlg";
$menudb["行业展会"]["待审展馆"]["power"]="1";

$menudb["行业展会"]["已审展馆"]["link"]="zh.php?type=1&job=postzlg";
$menudb["行业展会"]["已审展馆"]["power"]="1";
$menudb["行业展会"]["已审展馆"]["up"]="1";

$menudb["行业展会"]["推荐展馆"]["link"]="zh.php?levels=1&job=postzlg";
$menudb["行业展会"]["推荐展馆"]["power"]="1";



$menudb["行业资讯"]["资讯列表"]["link"]="../../admin/index.php?lfj=artic&job=listartic&mid=0&only=1";
$menudb["行业资讯"]["资讯列表"]["power"]="1";

$menudb["行业资讯"]["添加资讯"]["link"]="../../admin/index.php?lfj=post&job=postnew&mid=0&only=1";
$menudb["行业资讯"]["添加资讯"]["power"]="1";

$menudb["行业资讯"]["资讯页标签"]["link"]="../news.php?jobs=show";
$menudb["行业资讯"]["资讯页标签"]["power"]="1";

$menudb["行业资讯"]["资讯栏目"]["link"]="../../admin/index.php?lfj=sort&job=listsort&only=&mid=";
$menudb["行业资讯"]["资讯栏目"]["power"]="1";






$menudb["人才招聘"]["招聘首页标签"]["link"]="../jobs.php?jobs=show";
$menudb["人才招聘"]["招聘首页标签"]["power"]="1";

$menudb["人才招聘"]["人力资源栏目管理"]["link"]="jobs.php";
$menudb["人才招聘"]["人力资源栏目管理"]["power"]="1";

$menudb["人才招聘"]["职位库管理"]["link"]="jobs.php?job=zhiwei";
$menudb["人才招聘"]["职位库管理"]["power"]="1";

$menudb["人才招聘"]["人才库管理"]["link"]="jobs.php?job=rencai";
$menudb["人才招聘"]["人才库管理"]["power"]="1";

$menudb["人才招聘"]["预选数据管理"]["link"]="jobs.php?job=data";
$menudb["人才招聘"]["预选数据管理"]["power"]="1";



$menudb["商家服务"]["站外参考"]["link"]="cankao.php";
$menudb["商家服务"]["站外参考"]["power"]="1";
$menudb["商家服务"]["供应链管理"]["link"]="vendor.php";
$menudb["商家服务"]["供应链管理"]["power"]="1";





$menudb["盈利模型管理"]["供求竞价推广"]["link"]="tg.php?";
$menudb["盈利模型管理"]["供求竞价推广"]["power"]="1";

$menudb["盈利模型管理"]["VIP商家"]["link"]="vip.php?";
$menudb["盈利模型管理"]["VIP商家"]["power"]="1";

$menudb["盈利模型管理"]["充值设置"]["link"]="../../admin/index.php?lfj=alipay&job=set";
$menudb["盈利模型管理"]["充值设置"]["power"]="1";

$menudb["盈利模型管理"]["充值记录"]["link"]="../../admin/index.php?lfj=alipay&job=list";
$menudb["盈利模型管理"]["充值记录"]["power"]="1";



$menudb["自定义模块"]["模型管理"]["link"]="diypage.php";
$menudb["自定义模块"]["模型管理"]["power"]="1";
$menudb["自定义模块"]["模型管理"]["line"]="1";

$menudb["自定义模块"]["添加模块"]["link"]="diypage.php?action=add";
$menudb["自定义模块"]["添加模块"]["power"]="1";
$menudb["自定义模块"]["添加模块"]["up"]="1";
$menudb["自定义模块"]["添加模块"]["line"]="1";

if(is_object($db)){
	

	$query=$db->query("select * from {$_pre}diypage where type=1 order by order_sort desc ");
	while($rs=$db->fetch_array($query)){
		$menudb["自定义模块"][$rs[name]]["link"]="../page.php?diyid=$rs[diyid]&jobs=show";
		$menudb["自定义模块"][$rs[name]]["power"]="1";

		
	}

}



$menudb["用户帮助文档"]["帮助文档"]["link"]="news.php?job=news";
$menudb["用户帮助文档"]["帮助文档"]["power"]="1";

$menudb["用户帮助文档"]["添加文档"]["link"]="news.php?job=addnews";
$menudb["用户帮助文档"]["添加文档"]["power"]="1";

$menudb["用户帮助文档"]["栏目管理"]["link"]="news.php";
$menudb["用户帮助文档"]["栏目管理"]["power"]="1";


$menudb["数据库管理"]["备份数据"]["link"]="mysql.php?lfj=mysql&job=out";
$menudb["数据库管理"]["备份数据"]["power"]="1";

$menudb["数据库管理"]["还原数据"]["link"]="mysql.php?lfj=mysql&job=into";
$menudb["数据库管理"]["还原数据"]["power"]="1";

$menudb["数据库管理"]["删除备份数据"]["link"]="mysql.php?lfj=mysql&job=del";
$menudb["数据库管理"]["删除备份数据"]["power"]="1";

?>
