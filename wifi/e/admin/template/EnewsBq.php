<?php
require("../../class/connect.php");
require("../../class/db_sql.php");
require("../../class/functions.php");
$link=db_connect();
$empire=new mysqlquery();
$editor=1;
//验证用户
$lur=is_login();
$logininid=$lur['userid'];
$loginin=$lur['username'];
$loginrnd=$lur['rnd'];
$loginlevel=$lur['groupid'];
$loginadminstyleid=$lur['adminstyleid'];

$sql=$empire->query("select bqname,bqsay,funname,bq,issys,bqgs from {$dbtbpre}enewsbq where isclose=0 order by myorder desc,bqid");
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>帝国网站管理系统标签说明</title>
<link href="../adminstyle/<?=$loginadminstyleid?>/adminstyle.css" rel="stylesheet" type="text/css">
<script language="javascript">
window.resizeTo(760,600);
window.focus();
</script>
</head>
<body>
  <table width="100%" border="0" cellspacing="1" cellpadding="3">
    <tr> 
      <td id='bqnav'></td>
    </tr>
  </table>
<br>
<table width="100%" border="0" cellpadding="3" cellspacing="1" class="tableborder">
  <tr> 
    <td colspan="2" class="header">信息标签调用操作类型</td>
  </tr>
  <tr> 
    <td width="50%" bgcolor="#FFFFFF"> <table width="100%" border="0">
        <tr> 
          <td width="12%" rowspan="6" bgcolor="dbeaf5"> <div align="center"><strong>按<br>
              栏<br>
              目<br>
              调<br>
              用</strong></div></td>
          <td width="16%" height="20"><div align="center"><strong>0</strong></div></td>
          <td width="72%">栏目最新信息 <font color="#666666">(栏目ID=栏目ID)</font></td>
        </tr>
        <tr> 
          <td height="20"><div align="center"><strong>1</strong></div></td>
          <td>栏目点击排行 <font color="#666666">(栏目ID=栏目ID)</font></td>
        </tr>
        <tr> 
          <td height="20"><div align="center"><strong>2</strong></div></td>
          <td>栏目推荐信息 <font color="#666666">(栏目ID=栏目ID)</font></td>
        </tr>
        <tr> 
          <td height="20"><div align="center"><strong>9</strong></div></td>
          <td>栏目评论排行 <font color="#666666">(栏目ID=栏目ID)</font></td>
        </tr>
        <tr> 
          <td height="20"><div align="center"><strong>12</strong></div></td>
          <td>栏目头条信息 <font color="#666666">(栏目ID=栏目ID)</font></td>
        </tr>
        <tr> 
          <td height="20"><div align="center"><strong>15</strong></div></td>
          <td>栏目下载排行 <font color="#666666">(栏目ID=栏目ID)</font></td>
        </tr>
      </table></td>
    <td bgcolor="#FFFFFF"> <table width="100%" border="0">
        <tr> 
          <td width="11%" rowspan="6" bgcolor="dbeaf5"> <div align="center"><strong>按<br>
              默<br>
              认<br>
              表<br>
              调<br>
              用</strong></div></td>
          <td width="16%" height="20"><div align="center"><strong>3</strong></div></td>
          <td width="73%">默认表最新信息 <font color="#666666">(栏目ID=0)</font></td>
        </tr>
        <tr> 
          <td height="20"><div align="center"><strong>4</strong></div></td>
          <td>默认表点击排行 <font color="#666666">(栏目ID=0)</font></td>
        </tr>
        <tr> 
          <td height="20"><div align="center"><strong>5</strong></div></td>
          <td>默认表推荐信息 <font color="#666666">(栏目ID=0)</font></td>
        </tr>
        <tr> 
          <td height="20"><div align="center"><strong>10</strong></div></td>
          <td>默认表评论排行 <font color="#666666">(栏目ID=0)</font></td>
        </tr>
        <tr> 
          <td height="20"><div align="center"><strong>13</strong></div></td>
          <td>默认表头条信息 <font color="#666666">(栏目ID=0)</font></td>
        </tr>
        <tr> 
          <td height="20"><div align="center"><strong>16</strong></div></td>
          <td>默认表下载排行 <font color="#666666">(栏目ID=0)</font></td>
        </tr>
      </table></td>
  </tr>
  <tr> 
    <td bgcolor="#FFFFFF"> <table width="100%" border="0">
        <tr> 
          <td width="12%" rowspan="6" bgcolor="dbeaf5"> <div align="center"><strong>按<br>
              专<br>
              题<br>
              调<br>
              用</strong></div></td>
          <td width="16%" height="20"><div align="center"><strong>6</strong></div></td>
          <td width="72%">专题最新信息 <font color="#666666">(栏目ID=专题ID)</font></td>
        </tr>
        <tr> 
          <td height="20"><div align="center"><strong>7</strong></div></td>
          <td>专题点击排行 <font color="#666666">(栏目ID=专题ID)</font></td>
        </tr>
        <tr> 
          <td height="20"><div align="center"><strong>8</strong></div></td>
          <td>专题推荐信息 <font color="#666666">(栏目ID=专题ID)</font></td>
        </tr>
        <tr> 
          <td height="20"><div align="center"><strong>11</strong></div></td>
          <td>专题评论排行 <font color="#666666">(栏目ID=专题ID)</font></td>
        </tr>
        <tr> 
          <td height="20"><div align="center"><strong>14</strong></div></td>
          <td>专题头条信息 <font color="#666666">(栏目ID=专题ID)</font></td>
        </tr>
        <tr> 
          <td height="20"><div align="center"><strong>17</strong></div></td>
          <td>专题下载排行 <font color="#666666">(栏目ID=专题ID)</font></td>
        </tr>
      </table></td>
    <td bgcolor="#FFFFFF"> <table width="100%" border="0">
        <tr> 
          <td width="11%" rowspan="6" bgcolor="dbeaf5"> <div align="center"><strong>按<br>
              数<br>
              据<br>
              表<br>
              调<br>
              用</strong></div></td>
          <td width="16%" height="20"><div align="center"><strong>18</strong></div></td>
          <td width="73%">各表最新信息 <font color="#666666">(栏目ID='表名')</font></td>
        </tr>
        <tr> 
          <td height="20"><div align="center"><strong>19</strong></div></td>
          <td>各表点击排行<font color="#666666"> (栏目ID='表名')</font></td>
        </tr>
        <tr> 
          <td height="20"><div align="center"><strong>20</strong></div></td>
          <td>各表推荐信息 <font color="#666666">(栏目ID='表名')</font></td>
        </tr>
        <tr> 
          <td height="20"><div align="center"><strong>21</strong></div></td>
          <td>各表评论排行 <font color="#666666">(栏目ID='表名')</font></td>
        </tr>
        <tr> 
          <td height="20"><div align="center"><strong>22</strong></div></td>
          <td>各表头条信息 <font color="#666666">(栏目ID='表名')</font></td>
        </tr>
        <tr> 
          <td height="20"><div align="center"><strong>23</strong></div></td>
          <td>各表下载排行 <font color="#666666">(栏目ID='表名')</font></td>
        </tr>
      </table></td>
  </tr>
  <tr> 
    <td bgcolor="#FFFFFF"> <table width="100%" border="0">
        <tr> 
          <td width="12%" rowspan="6" bgcolor="dbeaf5"> <div align="center"><strong>按<br>
              标<br>
              题<br>
              分<br>
              类<br>
              调<br>
              用</strong></div></td>
          <td width="16%" height="20"><div align="center"><strong>25</strong></div></td>
          <td width="72%">标题分类最新信息 <font color="#666666">(栏目ID=标题分类ID)</font></td>
        </tr>
        <tr> 
          <td height="20"><div align="center"><strong>26</strong></div></td>
          <td>标题分类点击排行 <font color="#666666">(栏目ID=标题分类ID)</font></td>
        </tr>
        <tr> 
          <td height="20"><div align="center"><strong>27</strong></div></td>
          <td>标题分类推荐信息 <font color="#666666">(栏目ID=标题分类ID)</font></td>
        </tr>
        <tr> 
          <td height="20"><div align="center"><strong>28</strong></div></td>
          <td>标题分类评论排行 <font color="#666666">(栏目ID=标题分类ID)</font></td>
        </tr>
        <tr> 
          <td height="20"><div align="center"><strong>29</strong></div></td>
          <td>标题分类头条信息 <font color="#666666">(栏目ID=标题分类ID)</font></td>
        </tr>
        <tr> 
          <td height="20"><div align="center"><strong>30</strong></div></td>
          <td>标题分类下载排行 <font color="#666666">(栏目ID=标题分类ID)</font></td>
        </tr>
      </table></td>
    <td bgcolor="#FFFFFF"> <table width="100%" border="0">
        <tr> 
          <td width="12%" rowspan="6" bgcolor="dbeaf5"> <div align="center"><strong>按<br>
              S<br>
              Q<br>
              L<br>
              调<br>
              用</strong></div></td>
          <td width="15%" height="20" rowspan="2"><div align="center"><strong>24</strong></div></td>
          <td width="73%" height="20">按sql查询 <font color="#666666">(栏目ID='sql语句')</font></td>
        </tr>
        <tr> 
          <td height="20"><font color="#666666">数据表前缀可用：“[!db.pre!]&quot;表示</font></td>
        </tr>
        <tr> 
          <td height="20">&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr> 
          <td height="20">&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr> 
          <td height="20">&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr> 
          <td height="20">&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
      </table></td>
  </tr>
</table>
<br>
<?
$bqnav="";
while($r=$empire->fetch($sql))
{
	$bqnav.="<option value='".$r['bq']."'>".$r['bqname']."(".$r['bq'].")</option>";
?>
<table width="100%" border="0" align="center" cellpadding="3" cellspacing="1" class="tableborder" id="<?=$r[bq]?>">
  <tr>
    <td class="header"> 
      <table width="100%" border="0" cellspacing="1" cellpadding="3">
        <tr class="header">
          <td width="14%">标签名称：</td>
          <td width="86%"><b><?=$r[bqname]?>&nbsp;(<?=$r[bq]?>)</b></td>
        </tr>
      </table></td>
  </tr>
  <tr>
    <td bgcolor="#FFFFFF">
<table width="100%" border="0" cellspacing="1" cellpadding="3">
        <tr> 
          <td width="6%">格式:</td>
          <td>
<input type=text name="text" size=80 value="<?=stripSlashes($r[bqgs])?>" style="width:100%"></td>
        </tr>
      </table></td>
  </tr>
  <tr>
    <td bgcolor="#FFFFFF">
<table width="98%" border="0" align="center" cellpadding="3" cellspacing="1">
        <tr>
          <td>参数说明：</td>
        </tr>
        <tr> 
          <td><?=stripSlashes($r[bqsay])?></td>
        </tr>
      </table></td>
  </tr>
</table>
<br>
<?
}
$bqnav="<select name='bq' id='bq' onchange=window.location='#'+this.options[this.selectedIndex].value><option value='' selected style='background:#99C4E3'>标签导航</option>".$bqnav."<option value='eloop'>灵动标签 (e:loop)</option></select>";
?>
<table width="100%" border="0" align="center" cellpadding="3" cellspacing="1" class="tableborder" id="eloop">
  <tr>
    <td class="header"> 
      <table width="100%" border="0" cellspacing="1" cellpadding="3">
        <tr class="header">
          <td width="14%">标签名称：</td>
          <td width="86%"><b>灵动标签&nbsp;(e:loop)</b></td>
        </tr>
      </table></td>
  </tr>
  <tr>
    <td bgcolor="#FFFFFF">
<table width="100%" border="0" cellspacing="1" cellpadding="3">
        <tr> 
          <td width="6%">格式:</td>
          <td width="86%"><textarea name="text" cols="80" rows="4" style="width:100%">[e:loop={栏目ID/专题ID,显示条数,操作类型,只显示有标题图片}]
模板代码内容
[/e:loop]</textarea></td>
        </tr>
        <tr>
          <td>例子:</td>
          <td><textarea name="textarea" cols="80" rows="9" style="width:100%">&lt;table width="100%" border="0" cellspacing="1" cellpadding="3"&gt;
[e:loop={栏目ID/专题ID,显示条数,操作类型,只显示有标题图片}]
&lt;tr&gt;&lt;td&gt;
&lt;a href="&lt;?=$bqsr[titleurl]?&gt;" target="_blank"&gt;&lt;?=$bqr[title]?&gt;&lt;/a&gt;
(&lt;?=date('Y-m-d',$bqr[newstime])?&gt;)
&lt;/td&gt;&lt;/tr&gt;
[/e:loop]
&lt;/table&gt;</textarea></td>
        </tr>
      </table></td>
  </tr>
  <tr>
    <td bgcolor="#FFFFFF">
<table width="100%" border="0" align="center" cellpadding="3" cellspacing="1">
        <tr> 
          <td height="23"><strong>标签说明</strong></td>
        </tr>
        <tr> 
          <td height="23">灵动标签是针对一些高级用户而设计的，模板内容为PHP代码，因而更灵活，可以使用php所有处理函数。<font color="#666666">使用本标签，需开启模板支持程序代码(参数设置)。</font></td>
        </tr>
        <tr> 
          <td height="23"><strong>参数</strong></td>
        </tr>
        <tr> 
          <td><table cellspacing="1" cellpadding="3" width="100%" bgcolor="#dbeaf5" border="0">
              <tbody>
                <tr> 
                  <td width="23%"> 
                    <div align="center">参数</div></td>
                  <td>参数说明</td>
                </tr>
                <tr bgcolor="#ffffff"> 
                  <td height="25"><div align="center">栏目ID/专题ID</div></td>
                  <td height="25">查看栏目ID点<a onclick="window.open('../ListClass.php');"><strong><u>这里</u></strong></a>，查看专题ID点<a onclick="window.open('../ListZt.php');"><strong><u>这里</u></strong></a>,当前ID='selfinfo'<br />
                    多个栏目ID与专题ID可用,号格开，如'1,2'</td>
                </tr>
                <tr bgcolor="#ffffff"> 
                  <td height="25"><div align="center">显示条数</div></td>
                  <td height="25">显示前几条记录</td>
                </tr>
                <tr bgcolor="#ffffff"> 
                  <td height="25"><div align="center">操作类型</div></td>
                  <td height="25">具体看操作类型说明</td>
                </tr>
                <tr bgcolor="#ffffff"> 
                  <td height="25"><div align="center">只显示有标题图片</div></td>
                  <td height="25">0为不限制，1为只显示有标题图片的信息</td>
                </tr>
              </tbody>
            </table></td>
        </tr>
        <tr>
          <td><strong>变量说明</strong></td>
        </tr>
        <tr>
          <td height="139">
<table cellspacing="1" cellpadding="3" width="100%" bgcolor="#dbeaf5" border="0">
              <tbody>
                <tr> 
                  <td height="25"><div align="center">数组或变量</div></td>
                  <td height="25">说明</td>
                </tr>
                <tr> 
                  <td width="23%" height="25" bgcolor="#ffffff"> <div align="center">$bqr</div></td>
                  <td height="25" bgcolor="#ffffff">$bqr[字段名]：显示字段的内容</td>
                </tr>
                <tr> 
                  <td height="25" bgcolor="#ffffff"> <div align="center">$bqsr</div></td>
                  <td height="25" bgcolor="#ffffff">$bqsr[titleurl]：标题链接<br>
                    $bqsr[classname]：栏目名称<br>
                    $bqsr[classurl]：栏目链接</td>
                </tr>
                <tr> 
                  <td height="25" bgcolor="#ffffff"><div align="center">$bqno</div></td>
                  <td height="25" bgcolor="#ffffff">$bqno：为调用序号</td>
                </tr>
                <tr>
                  <td height="25" bgcolor="#ffffff"><div align="center">$public_r</div></td>
                  <td height="25" bgcolor="#ffffff">$public_r[newsurl]：网站地址</td>
                </tr>
              </tbody>
            </table></td>
        </tr>
        <tr> 
          <td><strong>常用函数介绍</strong></td>
        </tr>
        <tr> 
          <td>文字截取：<strong>esub(字符串,截取长度)</strong>，例子：esub($bqr[title],30)截取标题前30个字符<br>
            时间格式：<strong>date('格式字串',时间字段)</strong>，例子：date('Y-m-d',$bqr[newstime])时间显示格式为&quot;2008-10-01&quot;</td>
        </tr>
      </table></td>
  </tr>
</table>
<script>
window.bqnav.innerHTML="<?=$bqnav?>";
</script>
</body>
</html>
<?
db_close();
$empire=null;
?>
