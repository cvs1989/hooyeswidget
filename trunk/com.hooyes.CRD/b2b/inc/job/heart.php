<?php
!function_exists('html') && exit('ERR');
//处理跨域问题
if($webdb[cookieDomain]){
	echo "<SCRIPT LANGUAGE=\"JavaScript\">document.domain = \"$webdb[cookieDomain]\";</SCRIPT>";
}

unset($max,$numdb,$ckk,$_d);
$erp=get_id_table($id);
$rsdb=$db->get_one("SELECT heart FROM {$pre}article$erp WHERE aid='$id'");
$ar=explode("~~",$rsdb[heart]);
foreach($ar AS $key=>$value){
	list($title,$num)=explode("|",$value);
	//投一票
	if($type=='vote'&&$title==$Vtitle&&!$_COOKIE["heart_$id"]){
		$num++;
		$ckk++;
	}
	$numdb[$title]=$num;
}
//投一票
if($type=='vote'&&!$ckk&&!$_COOKIE["heart_$id"]){
	$numdb[$Vtitle]=1;
}
arsort($numdb);	
$max=0;
foreach($numdb AS $key=>$value){
	if(!$max&&$value){
		$max=$value;
		$heightdb[$key]=80;
	}else{
		$heightdb[$key]=ceil(80*$value/$max);
	}
	$_d[]="$key|$value";
}
//投一票
if($type=='vote'){
	if(!get_cookie("heart_$id")){
		$webdb[heart_time]>0 || $webdb[heart_time]=10;
		set_cookie("heart_$id",1,$webdb[heart_time]*60);
		$string=implode('~~',$_d);
		$string=addslashes($string);
		$db->query("UPDATE {$pre}article$erp SET heart='$string' WHERE aid='$id'");
	}else{
		showerr("请不要重复投");
	}
}

$show='';
$detail=explode("\r\n",$webdb[ArticleHeart]);
foreach($detail AS $key=>$value){
	list($title,$img)=explode("|",$value);
	$height=$heightdb[$title]+1;
	$num=intval($numdb[$title]);
	if($type!='vote'&&$webdb[heart_noRecord]&&!get_cookie("heart_$id")){
		$num='';
		$height=1;
	}
$show.=<<<EOT
<table border="0" cellspacing="0" cellpadding="0"  style='width:60px;height:200px;float:left;margin-right:5px;'>
  <tr>
    <td style="VERTICAL-ALIGN: bottom;"> 
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td align="center">$num</td>
        </tr>
        <tr>
          <td align="center" style="height:{$height}px;background:url($webdb[www_url]/images/default/heart/100.gif) repeat-y center;"></td>
        </tr>
        <tr>
          <td align="center" style="padding:7px 0 4px 0;"><img src="$webdb[www_url]/images/default/heart/$img"></td>
        </tr>
        <tr>
          <td align="center">$title</td>
        </tr>
        <tr>
          <td align="center"><input onclick="vote_heart('$title');" style="border:0px;" type="radio" name="radiobutton" value="radiobutton"></td>
        </tr>
      </table>
    </td>
  </tr>
</table>
EOT;
}
$show=str_replace(array("\n","\r","'"),array("","","\'"),$show);
echo "<SCRIPT LANGUAGE=\"JavaScript\">
	parent.document.getElementById('article_heart').innerHTML='$show';
	</SCRIPT>";
?>