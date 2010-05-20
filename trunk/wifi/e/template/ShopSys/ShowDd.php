<?php
if(!defined('InEmpireCMS'))
{
	exit();
}
?>
<?php
//显示商品信息
function ShowBuyproduct($buycar,$payby){
	global $empire,$dbtbpre;
	$record="!";
	$field="|";
	$r=explode($record,$buycar);
	$alltotal=0;
	$alltotalfen=0;
	echo"<table width='100%' border=0 align=center cellpadding=3 cellspacing=1>
          <tr class='header'> 
            <td width='9%' height=23> <div align=center>序号</div></td>
            <td width='43%'> <div align=center>商品名称</div></td>
            <td width='19%'> <div align=center>单价</div></td>
            <td width='10%'> <div align=center>数量</div></td>
            <td width='19%'> <div align=center>小计</div></td>
          </tr>";
	$j=0;
	for($i=0;$i<count($r)-1;$i++)
	{
		$j++;
		$pr=explode($field,$r[$i]);
		$productid=$pr[1];
		$fr=explode(",",$pr[1]);
		//ID
		$classid=(int)$fr[0];
		$id=(int)$fr[1];
		//数量
		$num=(int)$pr[2];
		if(empty($num))
		{
			$num=1;
		}
		//单价
		$price=$pr[3];
		$thistotal=$price*$num;
		$buyfen=$pr[4];
		$thistotalfen=$buyfen*$num;
		if($payby==1)
		{
			$showprice=$buyfen." 点";
			$showthistotal=$thistotalfen." 点";
		}
		else
		{
			$showprice=$price." 元";
			$showthistotal=$thistotal." 元";
		}
		//产品名称
		$title=stripSlashes($pr[5]);
		//返回链接
		$titleurl="../../public/InfoUrl/?classid=$classid&id=$id";
		$alltotal+=$thistotal;
		$alltotalfen+=$thistotalfen;
		echo"<tr>
	<td align=center>".$j."</td>
	<td align=center><a href='".$titleurl."' target=_blank>".$title."</a></td>
	<td align=right><b>￥".$showprice."</b></td>
	<td align=right>".$num."</td>
	<td align=right>".$showthistotal."</td>
	</tr>";
    }
	//支付点数付费
	if($payby==1)
	{
		$a="<tr> 
      <td colspan=5><div align=right>合计点数:<strong>".$alltotalfen."</strong></div></td>
      <td>&nbsp;</td>
    </tr>
	</table>";
	}
	else
	{
	echo"<tr> 
      <td colspan=5><div align=right>合计:<strong>￥".$alltotal."</strong></div></td>
      <td>&nbsp;</td>
    </tr>
  </table>";
	}
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link href="../../data/images/css.css" rel="stylesheet" type="text/css">
<title>查看订单</title>
<script>
function PrintDd()
{
	pdiv.style.display="none";
	window.print();
}
</script>
</head>

<body>
<table width="98%" border="0" align="center" cellpadding="3" cellspacing="1">
  <tr> 
    <td width="61%" height="27" bgcolor="#FFFFFF"><strong>订单号: 
      <?=$r[ddno]?>
      </strong></td>
    <td width="39%" bgcolor="#FFFFFF"><strong>下单时间: 
      <?=$r[ddtime]?>
      </strong></td>
  </tr>
  <tr> 
    <td height="23" colspan="2" bgcolor="#EFEFEF"><strong>商品信息</strong></td>
  </tr>
  <tr> 
    <td colspan="2"> 
      <?
	  ShowBuyproduct($r[buycar],$r[payby]);
	  ?>
    </td>
  </tr>
  <tr> 
    <td height="23" colspan="2" bgcolor="#EFEFEF"><strong>订单信息</strong></td>
  </tr>
  <tr> 
    <td height="23" colspan="2"><table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr> 
          <td width="12%" height="25"> 
            <div align="right">订单号：</div></td>
          <td width="32%"><strong> 
            <?=$r[ddno]?>
            </strong></td>
          <td width="14%"> 
            <div align="right">订单状态：</div></td>
          <td width="41%"><strong> 
            <?=$ha?>
            </strong>/<strong> 
            <?=$ou?>
            </strong>/<strong> 
            <?=$ch?>
            </strong> 
            <?=$topay?>
          </td>
        </tr>
        <tr> 
          <td height="25"> 
            <div align="right">下单时间：</div></td>
          <td><strong> 
            <?=$r[ddtime]?>
            </strong></td>
          <td><div align="right">商品总金额：</div></td>
          <td><strong>
            <?=$alltotal?>
            </strong></td>
        </tr>
        <tr> 
          <td height="25"> 
            <div align="right">配送方式：</div></td>
          <td><strong>
            <?=$r[psname]?>
            </strong></td>
          <td><div align="right">商品运费：</div></td>
          <td><strong>
            <?=$pstotal?>
            </strong></td>
        </tr>
        <tr> 
          <td height="25"> 
            <div align="right">支付方式：</div></td>
          <td><strong>
            <?=$payfsname?>
            </strong></td>
          <td><div align="right">发票金额：</div></td>
          <td><?=$r[fptotal]?></td>
        </tr>
        <tr> 
          <td height="25"> 
            <div align="right">需要发票：</div></td>
          <td><?=$fp?></td>
          <td><div align="right">订单总金额：</div></td>
          <td><strong>
            <?=$mytotal?>
            </strong></td>
        </tr>
        <tr> 
          <td height="25"> 
            <div align="right">发票抬头：</div></td>
          <td colspan="3"><strong> 
            <?=$r[fptt]?>
            </strong></td>
        </tr>
      </table></td>
  </tr>
  <tr> 
    <td height="23" colspan="2" bgcolor="#EFEFEF"><strong>订货人信息</strong></td>
  </tr>
  <tr> 
    <td colspan="2"><table width="100%%" border="0" cellspacing="1" cellpadding="3">
        <tr> 
          <td width="20%" height="25">真实姓名:</td>
          <td width="80%"> 
            <?=$r[truename]?>
          </td>
        </tr>
        <tr> 
          <td height="25">OICQ:</td>
          <td> 
            <?=$r[oicq]?>
          </td>
        </tr>
        <tr> 
          <td height="25">MSN:</td>
          <td> 
            <?=$r[msn]?>
          </td>
        </tr>
        <tr> 
          <td height="25">固定电话:</td>
          <td> 
            <?=$r[call]?>
          </td>
        </tr>
        <tr> 
          <td height="25">移动电话:</td>
          <td> 
            <?=$r[phone]?>
          </td>
        </tr>
        <tr> 
          <td height="25">联系邮箱:</td>
          <td> 
            <?=$r[email]?>
          </td>
        </tr>
        <tr> 
          <td height="25">联系地址:</td>
          <td> 
            <?=$r[address]?>
          </td>
        </tr>
        <tr> 
          <td height="25">邮编:</td>
          <td> 
            <?=$r[zip]?>
          </td>
        </tr>
        <tr> 
          <td height="25">备注:</td>
          <td> 
            <?=nl2br($r[bz])?>
          </td>
        </tr>
      </table></td>
  </tr>
  <tr> 
    <td height="23" colspan="2" bgcolor="#EFEFEF"><strong>收货人信息</strong></td>
  </tr>
  <tr> 
    <td colspan="2"><table width="100%%" border="0" cellspacing="1" cellpadding="3">
        <tr> 
          <td width="20%" height="25">真实姓名:</td>
          <td width="80%"> 
            <?=$r[g_truename]?>
          </td>
        </tr>
        <tr> 
          <td height="25">OICQ:</td>
          <td> 
            <?=$r[g_oicq]?>
          </td>
        </tr>
        <tr> 
          <td height="25">MSN:</td>
          <td> 
            <?=$r[g_msn]?>
          </td>
        </tr>
        <tr> 
          <td height="25">固定电话:</td>
          <td> 
            <?=$r[g_call]?>
          </td>
        </tr>
        <tr> 
          <td height="25">移动电话:</td>
          <td> 
            <?=$r[g_phone]?>
          </td>
        </tr>
        <tr> 
          <td height="25">联系邮箱:</td>
          <td> 
            <?=$r[g_email]?>
          </td>
        </tr>
        <tr> 
          <td height="25">联系地址:</td>
          <td> 
            <?=$r[g_address]?>
          </td>
        </tr>
        <tr> 
          <td height="25">邮编:</td>
          <td> 
            <?=$r[g_zip]?>
          </td>
        </tr>
      </table></td>
  </tr>
  <tr> 
    <td colspan="2"><div align="center"> 
        <table width="98%" border="0" align="center" cellpadding="3" cellspacing="1" id="pdiv">
          <tr> 
            <td><div align="center">
                <input type="button" name="Submit" value=" 打 印 " onclick="javascript:PrintDd();">
              </div></td>
          </tr>
        </table>
      </div></td>
  </tr>
</table>
</body>
</html>