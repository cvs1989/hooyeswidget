<?php
!function_exists('html') && exit('ERR');
if($action=='mod'){

	
	$div_db[div_w]=$div_w;
	$div_db[div_h]=$div_h;
	$div_db[div_bgcolor]=$div_bgcolor;
	$div=addslashes(serialize($div_db));
	$typesystem=0;

	//�������±�ǩ��
	do_post();


}


$rsdb=get_label();
$rsdb[hide]?$hide_1='checked':$hide_0='checked';
if($rsdb[js_time]){
	$js_time='checked';
}

@extract(unserialize($rsdb[divcode]));
$div_width && $div_w=$div_width;
$div_height && $div_h=$div_height;

$rsdb[code]="<script language=\"javascript\">
function chkinput(f){var tmp=f.name.value;if(!tmp){alert(\"����д��Ҫ��ѯ������!\");return false;}var tmp2=f.tiaojian.value;if(!tmp2){alert(\"��ѡ����Ҫ��ѯ������!\");return false;}return true;}function chkinput2(f){var tmp=f.user.value;if(!tmp){alert(\"�ʺŲ���Ϊ��!\");return false;}var tmp2=f.pass.value;if(!tmp2){alert(\"���벻��Ϊ��!\");return false;}var tmp3=f.site.value;if(!tmp3){alert(\"��û��ѡ������!\");return false;}return true;}function MM_openBrWindow(theURL,winName,features){window.open(theURL,winName,features);}
</script><script language=\"javascript\">
<!--
function clearpass(){document.loginmail.pass.value=\"\";}//--></script>
<div align=center id='mail_login'>
  <center>
    <table height=1 cellSpacing=0 cellPadding=0 width=100% border=0 valign=top>
      <tr>
        <form name=loginmail onsubmit=\"return chkinput2(this);\" action=http://www.hao123.com/sendmail.php method=post>
          <td align=left width=600 height=1><font color=#CC6600>������ٵ�½:<br>
            </font>�ʺţ� 
            <input style=FONT-SIZE:12px tabIndex=1 size=15 name=user>
            <br>
            ���룺 
            <input style=FONT-SIZE:12px tabindex=3 type=password size=15
name=pass>
            <br>
            ���䣺 
            <select
tabIndex=2 size=1 name=site>
              <option value selected>��ѡ������</option>
              <option value=21cn.com>@21cn.com</option>
              <option value=163.net>@163.net</option>
              <option value=tom.com>@tom.com</option>
              <option value=\"163.com\">@163.com</option>
              <option value=vip.163.com>@vip.163.com</option>
              <option value=sohu.com>@sohu.com</option>
              <option value=263.net>@263.net</option>
              <option value=sina.com>@sina.com</option>
              <option value=vip.sina.com>@vip.sina.com</option>
              <option value=mail.china.com>@mail.china.com</option>
              <option value=china.com>@china.com</option>
              <option value=netease.com>@netease.com</option>
              <option value=yeah.net>@yeah.net</option>
              <option value=etang.com>@etang.com</option>
              <option value=126.com>@126.com</option>
              <option value=\"cn.yahoo.com\">@yahoo.com.cn</option>
              <option value=\"xinhuanet.com\">@xinhuanet.com</option>
              <option value=eyou.com>@eyou.com</option>
              <option value=email.com.cn>@email.com.cn</option>
              <option value=ynmail.com>@ynmail.com</option>
              <option value=citiz.net>@citiz.net</option>
              <option value=\"hotmail.com\">@hotmail.com</option>
              <option value=\"56.com\">@56.com</option>
              <option value=\"gmail.com\">@gmail.com</option>
            </select>
            <br>
            <input style=FONT-SIZE:12px tabindex=4 type=button value=ע������
name=Submit22 onClick=\"window.open('http://reg.126.com/reg1.jsp?from=')\">
            <input style=FONT-SIZE:12px tabIndex=4 type=submit value=���ٵ�¼
name=Submit2 onclick=setTimeout('clearpass()',1000)>
          </td>
        </form>
      </tr>
    </table>
  </center>
</div>
";


require("head.php");
require("template/label/hack_code.htm");
require("foot.php");
?>