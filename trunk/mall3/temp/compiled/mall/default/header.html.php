<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<base href="<?php echo $this->_var['site_url']; ?>/" />

<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7 charset=<?php echo $this->_var['charset']; ?>" />
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $this->_var['charset']; ?>" />
<title><?php echo htmlspecialchars($this->_var['page_title']); ?></title>
<meta name="description" content="<?php echo htmlspecialchars($this->_var['page_description']); ?>" />
<meta name="keywords" content="<?php echo htmlspecialchars($this->_var['page_keywords']); ?>" />
<meta name="author" content="hooyes" />
<meta name="copyright" content="hooyes. All Rights Reserved" />
<link href="<?php echo $this->res_base . "/" . 'css/v2.0.css'; ?>" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="index.php?act=jslang"></script>
<script type="text/javascript" src="<?php echo $this->lib_base . "/" . 'jquery.js'; ?>" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo $this->lib_base . "/" . 'ecmall.js'; ?>" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo $this->res_base . "/" . 'js/nav.js'; ?>" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo $this->res_base . "/" . 'js/select.js'; ?>" charset="utf-8"></script>
<script type="text/javascript">
//<!CDATA[
var SITE_URL = "<?php echo $this->_var['site_url']; ?>";
var PRICE_FORMAT = '<?php echo $this->_var['price_format']; ?>';

$(function(){
    var select_list = document.getElementById("select_list");
    var float_list = document.getElementById("float_list");
    select_list.onmouseover = function () {
        float_list.style.display = "block";
    };
    select_list.onmouseout = function () {
        float_list.style.display = "none";
    };
});
//]]>
</script>

<?php echo $this->_var['_head_tags']; ?>
<!--<editmode></editmode>-->
</head>

<body>
<div class="xBanner">
   <div class="L">免费注册发布企业，产品，供求信息</div>
   <div class="R">
		
        <ul class="subnav">
		    <li class="line"> <p class="link1">
            您好,<?php echo htmlspecialchars($this->_var['visitor']['user_name']); ?>
            <?php if (! $this->_var['visitor']['user_id']): ?>
            [<a href="<?php echo url('app=member&act=login&ret_url=' . $this->_var['ret_url']. ''); ?>">登录</a>]
            [<a href="<?php echo url('app=member&act=register&ret_url=' . $this->_var['ret_url']. ''); ?>">注册</a>]
            <?php else: ?>
            [<a href="<?php echo url('app=member&act=logout'); ?>">退出</a>]
            <?php endif; ?>
        </p></li>
            <li id="select_list">
                <a class="z_index" href="<?php echo url('app=member'); ?>">用户中心</a>
                <ul id="float_list">
                    <div class="adorn1"></div>
                    <div class="adorn2"></div>
                    <li><a href="<?php echo url('app=buyer_order'); ?>">我的订单</a></li>
                    <li><a href="<?php echo url('app=buyer_groupbuy'); ?>">我的团购</a></li>
                    <li><a href="<?php echo url('app=my_question'); ?>">我的咨询</a></li>
                </ul>
            </li>
            <li class="line"><a href="<?php echo url('app=message&act=newpm'); ?>">站内消息<?php if ($this->_var['new_message']): ?>(<?php echo $this->_var['new_message']; ?>)<?php endif; ?></a></li>
            <li class="line"><a href="<?php echo url('app=article&code=' . $this->_var['acc_help']. ''); ?>">帮助中心</a></li>
            <?php $_from = $this->_var['navs']['header']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'nav');if (count($_from)):
    foreach ($_from AS $this->_var['nav']):
?>
            <li class="line"><a href="<?php echo $this->_var['nav']['link']; ?>"<?php if ($this->_var['nav']['open_new']): ?> target="_blank"<?php endif; ?>><?php echo htmlspecialchars($this->_var['nav']['title']); ?></a></li>
            <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
			<li class="line"><a href="<?php echo url('lang=chs'); ?>">中文</a></li>
			<li class="line"><a href="<?php echo url('lang=en'); ?>">English</a></li>
        </ul>
   
		</div>
</div>
<div id="head">
    <h1 title="<?php echo $this->_var['site_title']; ?>"><a href="index.php"><img alt="<?php echo $this->_var['site_title']; ?>" src="<?php echo $this->_var['site_logo']; ?>" /></a></h1>
    <div class="menu"></div>
</div>

<ul id="nav">
    <div class="nav1"></div>
    <div class="nav2"></div>
    <li><a class="<?php if ($this->_var['index']): ?>link<?php else: ?>hover<?php endif; ?>" href="index.php"><span>首页</span></a></li>
    <?php $_from = $this->_var['navs']['middle']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'nav');if (count($_from)):
    foreach ($_from AS $this->_var['nav']):
?>
    <li><a class="<?php if (! $this->_var['index'] && $this->_var['nav']['link'] == $this->_var['current_url']): ?>link<?php else: ?>hover<?php endif; ?>" href="<?php echo $this->_var['nav']['link']; ?>"<?php if ($this->_var['nav']['open_new']): ?> target="_blank"<?php endif; ?>><span><?php echo htmlspecialchars($this->_var['nav']['title']); ?></span></a></li>
    <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
</ul>

<div class="search">
    <div class="search1"></div>
    <div class="search2"></div>
    <div class="wrap">
        <form method="GET" action="<?php echo url('app=search'); ?>">
            <div class="border">
                <div class="select_js">
                    <p>搜索企业</p>
                    <div class="ico"></div>
                    <ul>
					    <li ectype="store">搜索企业</li>
                        <li ectype="index">搜索产品</li>
                        <!--<li ectype="groupbuy">搜索团购</li> -->
                    </ul>
                    <input type="hidden" name="act" value="store" />
                </div>
                <input type="text" name="keyword" class="text2" />
            </div>
            <input type="hidden" name="app" value="search" />
            <input type="submit" name="Submit" value="搜索" class="btn" />
        </form>
<!--        <p><a href="<?php echo url('app=category'); ?>">产品分类</a><br /><a href="<?php echo url('app=category&act=store'); ?>">企业分类</a></p> -->
    </div>
    <div class="nav none">
        <div class="nav1"></div>
        <div class="nav2"></div>
        <a href="<?php echo url('app=cart'); ?>" class="buy">购物车 <strong id="cart_goods_kinds"><?php echo $this->_var['cart_goods_kinds']; ?></strong> 种产品</a>
        <a href="<?php echo url('app=my_favorite'); ?>" class="buyline">收藏夹</a>
        <a href="<?php echo url('app=buyer_order'); ?>" class="buyline">我的订单</a>
    </div>
</div>
