<?php
require("../class/connect.php");
require("../class/db_sql.php");
require("../class/functions.php");
require("../class/user.php");
$link=db_connect();
$empire=new mysqlquery();
//验证用户
$lur=is_login();
$logininid=$lur['userid'];
$loginin=$lur['username'];
$loginrnd=$lur['rnd'];
$loginlevel=(int)$lur['groupid'];
$loginadminstyleid=$lur['adminstyleid'];
//我的状态
$gr=$empire->fetch1("select groupname from {$dbtbpre}enewsgroup where groupid='$loginlevel'");
//管理员统计
$adminnum=$empire->gettotal("select count(*) as total from {$dbtbpre}enewsuser");
$date=date("Y-m-d");
$noplnum=$empire->gettotal("select count(*) as total from {$dbtbpre}enewspl where checked=1");
//未审核会员
$nomembernum=$empire->gettotal("select count(*) as total from ".$user_tablename." where ".$user_checked."=0");
//过期广告
$outtimeadnum=$empire->gettotal("select count(*) as total from {$dbtbpre}enewsad where endtime<'$date'");
//系统信息
if (function_exists('ini_get')){
        $onoff = ini_get('register_globals');
    } else {
        $onoff = get_cfg_var('register_globals');
    }
    if ($onoff){
        $onoff="打开";
    }else{
        $onoff="关闭";
    }
    if (function_exists('ini_get')){
        $upload = ini_get('file_uploads');
    } else {
        $upload = get_cfg_var('file_uploads');
    }
    if ($upload){
        $upload="可以";
    }else{
        $upload="不可以";
    }
//开启
$register_ok="开启";
if($public_r[register_ok])
{$register_ok="关闭";}
$addnews_ok="开启";
if($public_r[addnews_ok])
{$addnews_ok="关闭";}
//版本
@include("../class/EmpireCMS_version.php");
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>帝国网站管理系统</title>
<link href="adminstyle/<?=$loginadminstyleid?>/adminstyle.css" rel="stylesheet" type="text/css">
</head>

<body>
<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr> 
    <td><div align="center"><strong> 
        <h3>欢迎使用帝国网站管理系统(Empire CMS)</h3>
        </strong></div></td>
  </tr>
  <tr> 
    <td><table width="98%" border="0" align="center" cellpadding="3" cellspacing="1" class="tableborder">
        <tr class="header"> 
          <td height="25">我的状态</td>
        </tr>
        <tr> 
          <td height="25" bgcolor="#FFFFFF"> <table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr> 
                <td><div align="center">登陆者:&nbsp;<b> 
                    <?=$loginin?>
                    </b></div></td>
                <td><div align="center">所属用户组:&nbsp;<b> 
                    <?=$gr[groupname]?>
                    </b></div></td>
              </tr>
            </table>
          </td>
        </tr>
      </table></td>
  </tr>
  <tr> 
    <td></td>
  </tr>
  <tr> 
    <td><table width="98%" border="0" align="center" cellpadding="3" cellspacing="1" class="tableborder">
        <tr class="header"> 
          <td width="100%" height="25"> 
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td width="50%"><strong><a href="#ecms">快捷菜单</a></strong></td>
                <td><div align="right"><a href="http://www.dotool.cn" target="_blank"><strong>站长工具</strong></a></div></td>
              </tr>
            </table></td>
        </tr>
        <tr> 
          <td height="25" bgcolor="#FFFFFF"><strong>信息操作</strong>：&nbsp;&nbsp;<a href="AddInfoChClass.php">增加信息</a>&nbsp;&nbsp; 
            <a href="ListAllInfo.php">管理信息</a>&nbsp;&nbsp; <a href="ListAllInfo.php?showspecial=4&sear=1">审核信息</a> 
            &nbsp;&nbsp; <a href="ListNewsQf.php">签发信息</a>&nbsp;&nbsp; <a href="pl/ListAllPl.php">评论管理</a>&nbsp;&nbsp; 
            <a href="ReHtml/ChangeData.php">数据更新中心</a></td>
          &nbsp;&nbsp; </tr>
        <tr> 
          <td height="25" bgcolor="#FFFFFF"><strong>栏目操作</strong>：&nbsp;&nbsp;<a href="ListClass.php">管理栏目</a>&nbsp;&nbsp; 
            <a href="ListZt.php">管理专题</a>&nbsp;&nbsp; <a href="ListInfoClass.php">管理采集</a> 
            &nbsp;&nbsp; <a href="file/ListFile.php?type=9">附件管理</a>&nbsp;&nbsp; 
            <a href="SetEnews.php">系统参数设置</a></td>
        </tr>
        <tr> 
          <td height="25" bgcolor="#FFFFFF"><strong>用户操作</strong>：&nbsp;&nbsp;<a href="member/ListMember.php?sear=1&schecked=1">审核会员</a>&nbsp;&nbsp; 
            <a href="member/ListMember.php">管理会员</a> &nbsp; <a href="user/ListLog.php">管理登陆日志</a> 
            &nbsp;&nbsp; <a href="user/ListDolog.php">管理操作日志</a>&nbsp;&nbsp; <a href="user/EditPassword.php">修改个人资料</a>&nbsp;&nbsp; 
            <a href="user/UserTotal.php">用户发布统计</a></td>
        </tr>
        <tr> 
          <td height="25" bgcolor="#FFFFFF"><strong>反馈管理</strong>：&nbsp;&nbsp;<a href="tool/gbook.php">管理留言</a>&nbsp;&nbsp; 
            <a href="tool/feedback.php">管理反馈信息</a>&nbsp;&nbsp;<a href="DownSys/ListError.php">管理错误报告</a>&nbsp;&nbsp; 
            <a href="ShopSys/ListDd.php">管理订单</a>&nbsp;&nbsp;<a href="pay/ListPayRecord.php">管理支付记录</a>&nbsp;&nbsp; 
            <a href="PathLevel.php">查看目录权限状态</a></td>
        </tr>
      </table></td>
  </tr>
  <tr> 
    <td><table width="98%" border="0" align="center" cellpadding="3" cellspacing="1">
        <tr> 
          <td height="42"> <div align="center"><strong><font color="#0000FF" size="3">帝国网站管理系统全面开源 
              － 最安全、最稳定的开源CMS系统</font></strong></div></td>
        </tr>
      </table></td>
  </tr>
  <tr> 
    <td><table width="98%" border="0" align="center" cellpadding="3" cellspacing="1" class="tableborder">
        <tr class="header"> 
          <td height="25"> 
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td width="50%"><a href="#"><strong>系统信息</strong></a></td>
                <td><div align="right"><a href="http://www.phome.net/ebak2008os/" target="_blank"><strong>帝国MYSQL备份王下载</strong></a></div></td>
              </tr>
            </table></td>
        </tr>
        <tr> 
          <td bgcolor="#FFFFFF"><table width="100%" border="0" cellpadding="3" cellspacing="1">
              <tr bgcolor="#FFFFFF"> 
                <td width="50%" height="25">服务器软件: 
                  <?=$_SERVER['SERVER_SOFTWARE']?>
                </td>
                <td height="25">操作系统: <? echo defined('PHP_OS')?PHP_OS:'未知';?></td>
              </tr>
              <tr bgcolor="#FFFFFF"> 
                <td height="25">PHP版本: <? echo @phpversion();?></td>
                <td height="25">MYSQL版本: <? echo @mysql_get_server_info();?></td>
              </tr>
              <tr bgcolor="#FFFFFF"> 
                <td height="25">全局变量: 
                  <?=$onoff?>
                </td>
                <td height="25">上传文件: 
                  <?=$upload?>
                </td>
              </tr>
              <tr bgcolor="#FFFFFF"> 
                <td height="25">登陆者IP: <? echo egetip();?></td>
                <td height="25">当前时间: <? echo date("Y-m-d H:i:s");?></td>
              </tr>
              <tr bgcolor="#FFFFFF"> 
                <td height="25">程序版本: <a href="http://www.phome.net" target="_blank"><strong>EmpireCMS 
                  v
                  <?=EmpireCMS_VERSION?>
                  </strong></a> <font color="#666666">(
                  <?=EmpireCMS_LASTTIME?>
                  )</font></td>
                <td height="25">使用域名: 
                  <?=$_SERVER['HTTP_HOST']?>
                </td>
              </tr>
              <tr bgcolor="#FFFFFF"> 
                <td width="50%" height="25">会员注册: 
                  <?=$register_ok?>
                </td>
                <td height="25">会员投稿: 
                  <?=$addnews_ok?>
                </td>
              </tr>
              <tr bgcolor="#FFFFFF"> 
                <td height="25">未审核评论: <a href="pl/ListAllPl.php">
                  <?=$noplnum?>
                  </a> 条&nbsp;&nbsp;,&nbsp;&nbsp;未审核会员: <a href="member/ListMember.php?sear=1&schecked=1">
                  <?=$nomembernum?>
                  </a> 人</td>
                <td height="25">管理员个数：<a href="user/ListUser.php">
                  <?=$adminnum?>
                  </a> 人</td>
              </tr>
              <tr bgcolor="#FFFFFF">
                <td height="25">过期广告：<a href="tool/ListAd.php?time=1"><?=$outtimeadnum?></a> 个</td>
                <td height="25">&nbsp;</td>
              </tr>
            </table></td>
        </tr>
      </table></td>
  </tr>
  <tr> 
    <td>&nbsp;</td>
  </tr>
  <tr> 
    <td><table width="98%" border="0" align="center" cellpadding="3" cellspacing="1" class="tableborder">
        <tr class="header"> 
          <td height="25" colspan="2">官方信息</td>
        </tr>
        <tr> 
          <td width="43%" bgcolor="#FFFFFF"> 
            <table width="100%" border="0" cellpadding="3" cellspacing="1">
              <tr bgcolor="#FFFFFF"> 
                <td width="30%" height="25">帝国官方主页: </td>
                <td width="70%" height="25"><a href="http://www.phome.net" target="_blank">http://www.phome.net</a></td>
              </tr>
              <tr bgcolor="#FFFFFF"> 
                <td height="25">帝国官方论坛: </td>
                <td height="25"><a href="http://bbs.phome.net" target="_blank">http://bbs.phome.net</a></td>
              </tr>
              <tr bgcolor="#FFFFFF"> 
                <td height="25">合作伙伴计划: </td>
                <td height="25"><a href="http://www.phome.net/partner/" target="_blank">http://www.phome.net/partner/</a></td>
              </tr>
              <tr bgcolor="#FFFFFF"> 
                <td height="25">公司网站：</td>
                <td height="25"><a href="http://www.digod.com" target="_blank">http://www.digod.com</a></td>
              </tr>
            </table>
          </td>
          <td width="57%" height="125" valign="top" bgcolor="#FFFFFF"> 
            <IFRAME frameBorder="0" name="getinfo" scrolling="no" src="ginfo.php" style="HEIGHT:100%;VISIBILITY:inherit;WIDTH:100%;Z-INDEX:2"></IFRAME></td>
        </tr>
      </table></td>
  </tr>
  <tr> 
    <td>&nbsp;</td>
  </tr>
  <tr> 
    <td><table width="98%" border="0" align="center" cellpadding="3" cellspacing="1" class="tableborder">
        <tr class="header"> 
          <td height="25">EmpireCMS 开发团队</td>
        </tr>
        <tr> 
          <td bgcolor="#FFFFFF"><table width="100%" border="0" cellpadding="3" cellspacing="1">
              <tr bgcolor="#FFFFFF"> 
                <td width="125" height="25">版权所有</td>
                <td><a href="http://www.digod.com" target="_blank">帝兴软件开发有限公司</a></td>
              </tr>
              <tr bgcolor="#FFFFFF"> 
                <td height="25">开发与支持团队</td>
                <td>wm_chief、amt、帝兴、小游、zeedy</td>
              </tr>
              <tr bgcolor="#FFFFFF"> 
                <td height="25">默认模板设计</td>
                <td>禾火木风</td>
              </tr>
              <tr bgcolor="#FFFFFF"> 
                <td height="25">论坛管理</td>
                <td>禾火木风、yingnt、hicode、sooden</td>
              </tr>
              <tr bgcolor="#FFFFFF"> 
                <td height="25">特别感谢</td>
                <td>老鬼、小林、天浪歌、TryLife、5starsgeneral</td>
              </tr>
            </table></td>
        </tr>
      </table></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
</table>
</body>
</html>
<?php
db_close();
$empire=null;
?>