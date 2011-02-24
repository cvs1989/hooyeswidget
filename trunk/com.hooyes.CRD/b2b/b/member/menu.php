<?php



$menudb["会员信息管理"]["用户基本信息"]["link"]="$webdb[www_url]/member/userinfo.php?job=edit";
$menudb["会员信息管理"]["用户基本信息"]["power"]="1";
$menudb["会员信息管理"]["身份验证"]["link"]="$webdb[www_url]/member/yz.php?job=mob";
$menudb["会员信息管理"]["身份验证"]["power"]="1";



$menudb["公司信息管理"]["模板设置"]["link"]="homepage_ctrl.php?atn=base&uid=$lfjuid";
$menudb["公司信息管理"]["模板设置"]["power"]="1";
$menudb["公司信息管理"]["横幅设置"]["link"]="homepage_ctrl.php?atn=banner&uid=$lfjuid";
$menudb["公司信息管理"]["横幅设置"]["power"]="1";
$menudb["公司信息管理"]["公司资料"]["link"]="homepage_ctrl.php?atn=info&uid=$lfjuid";
$menudb["公司信息管理"]["公司资料"]["power"]="1";
$menudb["公司信息管理"]["联系方式"]["link"]="homepage_ctrl.php?atn=contactus&uid=$lfjuid";
$menudb["公司信息管理"]["联系方式"]["power"]="1";
$menudb["公司信息管理"]["企业新闻"]["link"]="homepage_ctrl.php?atn=news&uid=$lfjuid";
$menudb["公司信息管理"]["企业新闻"]["power"]="1";




$menudb["供求管理"]["发布供应信息"]["link"]="post_sell.php";
$menudb["供求管理"]["发布供应信息"]["power"]="1";
$menudb["供求管理"]["管理供应信息"]["link"]="selllist.php";
$menudb["供求管理"]["管理供应信息"]["power"]="1";
$menudb["供求管理"]["发布求购信息"]["link"]="post_buy.php";
$menudb["供求管理"]["发布求购信息"]["power"]="1";
$menudb["供求管理"]["管理求购信息"]["link"]="buylist.php";
$menudb["供求管理"]["管理求购信息"]["power"]="1";
$menudb["供求管理"]["设置信息分类"]["link"]="myinfosort.php";
$menudb["供求管理"]["设置信息分类"]["power"]="1";
$menudb["供求管理"]["我的品牌"]["link"]="brand.php";
$menudb["供求管理"]["我的品牌"]["power"]="1";



$menudb["我的功能中心"]["图库管理"]["link"]="homepage_ctrl.php?atn=pic&uid=$lfjuid";
$menudb["我的功能中心"]["图库管理"]["power"]="1";
$menudb["我的功能中心"]["公章管理"]["link"]="homepage_ctrl.php?atn=gz&uid=$lfjuid";
$menudb["我的功能中心"]["公章管理"]["power"]="1";
$menudb["我的功能中心"]["账户充值"]["link"]="$webdb[www_url]/member/money.php?job=list";
$menudb["我的功能中心"]["账户充值"]["power"]="1";
$menudb["我的功能中心"]["我要投稿"]["link"]="$webdb[www_url]/member/post.php?";
$menudb["我的功能中心"]["我要投稿"]["power"]="1";
$menudb["我的功能中心"]["管理我的稿件"]["link"]="$webdb[www_url]/member/myarticle.php?job=myarticle";
$menudb["我的功能中心"]["管理我的稿件"]["power"]="1";
$menudb["我的功能中心"]["客户留言"]["link"]="../homepage.php?uid=$lfjuid&m=msg";
$menudb["我的功能中心"]["客户留言"]["power"]="1";
$menudb["我的功能中心"]["我的收藏"]["link"]="collection.php";
$menudb["我的功能中心"]["我的收藏"]["power"]="1";



$menudb["商务会员"]["VIP会员"]["link"]="vip.php";
$menudb["商务会员"]["VIP会员"]["power"]="1";
$menudb["商务会员"]["我是代理商"]["link"]="agents.php";
$menudb["商务会员"]["我是代理商"]["power"]="1";
$menudb["商务会员"]["商铺二级域名"]["link"]="homepage_ctrl.php?atn=mydomain&uid=$lfjuid";
$menudb["商务会员"]["商铺二级域名"]["power"]="1";
$menudb["商务会员"]["认证情况"]["link"]="renzheng.php";
$menudb["商务会员"]["认证情况"]["power"]="1";
$menudb["商务会员"]["站外参考资料"]["link"]="cankao.php";
$menudb["商务会员"]["站外参考资料"]["power"]="1";




$menudb["产品信息推广"]["供应信息推广"]["link"]="../tg_sell.php?action=tgnew";
$menudb["产品信息推广"]["供应信息推广"]["power"]="1";
$menudb["产品信息推广"]["求购信息推广"]["link"]="../tg_sell.php?action=tgnew";
$menudb["产品信息推广"]["求购信息推广"]["power"]="1";



$menudb["供应链应用"]["招聘供应商"]["link"]="vendor.php?job=want_vendor";
$menudb["供应链应用"]["招聘供应商"]["power"]="1";

$menudb["供应链应用"]["管理供应商"]["link"]="vendor.php";
$menudb["供应链应用"]["管理供应商"]["power"]="1";

$menudb["供应链应用"]["我的采购客户"]["link"]="buyer.php";
$menudb["供应链应用"]["我的采购客户"]["power"]="1";



$menudb["询价单服务"]["发出的询价单"]["link"]="form1.php";
$menudb["询价单服务"]["发出的询价单"]["power"]="1";

$menudb["询价单服务"]["收到的询价单"]["link"]="myform1.php";
$menudb["询价单服务"]["收到的询价单"]["power"]="1";

$menudb["报价单服务"]["发出的报价单"]["link"]="form2.php";
$menudb["报价单服务"]["发出的报价单"]["power"]="1";

$menudb["报价单服务"]["收到的报价单"]["link"]="myform2.php";
$menudb["报价单服务"]["收到的报价单"]["power"]="1";





$menudb["评论管理"]["对我的评论"]["link"]="comment.php?job=list";
$menudb["评论管理"]["对我的评论"]["power"]="1";

$menudb["评论管理"]["发表的评论"]["link"]="comment.php?job=mylist";
$menudb["评论管理"]["发表的评论"]["power"]="1";





/*展会*/
$menudb["展会管理"]["管理展会"]["link"]="zh.php?job=zh";
$menudb["展会管理"]["管理展会"]["power"]="1";

$menudb["展会管理"]["发布展会"]["link"]="../zh_post.php?job=postzh";
$menudb["展会管理"]["发布展会"]["power"]="1";


$menudb["展会管理"]["管理展览馆"]["link"]="zh.php?job=zlg";
$menudb["展会管理"]["管理展览馆"]["power"]="1";

$menudb["展会管理"]["登记展览馆"]["link"]="../zh_post.php?job=postzlg";
$menudb["展会管理"]["登记展览馆"]["power"]="1";


/*人才招聘*/

$menudb["人才招聘"]["发布招聘"]["link"]="../jobs_post.php?job=jobs";
$menudb["人才招聘"]["发布招聘"]["power"]="1";

$menudb["人才招聘"]["已发布招聘"]["link"]="hr.php?job=mylist";
$menudb["人才招聘"]["已发布招聘"]["power"]="1";

$menudb["人才招聘"]["收到的求职"]["link"]="hr.php?job=myyplist";
$menudb["人才招聘"]["收到的求职"]["power"]="1";

$menudb["人才招聘"]["我的人才库"]["link"]="hr.php?job=mytdlist";
$menudb["人才招聘"]["我的人才库"]["power"]="1";

$menudb["个人求职"]["创建简历"]["link"]="../jobs_post.php?job=resume";
$menudb["个人求职"]["创建简历"]["power"]="1";

$menudb["个人求职"]["我的简历"]["link"]="hr.php?job=myresume";
$menudb["个人求职"]["我的简历"]["power"]="1";

$menudb["个人求职"]["求职经历"]["link"]="hr.php?job=mytds";
$menudb["个人求职"]["求职经历"]["power"]="1";




?>