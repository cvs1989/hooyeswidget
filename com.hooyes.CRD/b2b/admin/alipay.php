<?php
!function_exists('html') && exit('ERR');
if($job=="set"&&$Apower[alipay_set])
{
	$Money2card[$webdb[Money2card]]=' checked ';
	require(dirname(__FILE__)."/"."head.php");
	require(dirname(__FILE__)."/"."template/alipay/menu.htm");
	require(dirname(__FILE__)."/"."template/alipay/set.htm");
	require(dirname(__FILE__)."/"."foot.php");
}
elseif($action=='set'&&$Apower[alipay_set])
{
	write_config_cache($webdbs);
	jump("�޸ĳɹ�","$FROMURL","1");
}
elseif($job=='list'&&$Apower[alipay_set])
{
	if(!$page){
		$page=1;
	}
	$rows=50;
	$min=($page-1)*$rows;
	$showpage=getpage("`{$pre}olpay`"," ","?lfj=$lfj&job=$job","$rows");
	$query = $db->query("SELECT * FROM `{$pre}olpay` ORDER BY id DESC LIMIT $min,$rows");
	while($rs = $db->fetch_array($query)){
		$rs[posttime]=date("Y-m-d H:i:s",$rs[posttime]);

		if($rs[ifpay]){
			$rs[ifpay]='<font color=red>��֧��<font>';
			$rs[setpay]='';
		}else{
			
			$rs[ifpay]='δ֧��';
			$rs[setpay]="<A HREF='?lfj=$lfj&action=setpay&id=$rs[id]'>��ֵ</A>";
		}
		$listdb[]=$rs;
	}
	require(dirname(__FILE__)."/"."head.php");
	require(dirname(__FILE__)."/"."template/alipay/menu.htm");
	require(dirname(__FILE__)."/"."template/alipay/list.htm");
	require(dirname(__FILE__)."/"."foot.php");
}
elseif($action=="delete"&&$Apower[alipay_set])
{
	if($id){
		$db->query("DELETE FROM `{$pre}olpay` WHERE id='$id'");
	}else{
		foreach( $listdb AS $key=>$id){
			$db->query("DELETE FROM `{$pre}olpay` WHERE id='$id'");
		}
	}
	jump("ɾ���ɹ�","$FROMURL","1");
}

elseif($action=='setpay'&&$Apower[alipay_set])
{
	$rt = $db->get_one("SELECT * FROM {$pre}olpay WHERE id='$id'");
	if(!$rt){
		showmsg('ϵͳ��û�����ĳ�ֵ�������޷���ɳ�ֵ��');
	}
	if($rt['ifpay'] == 1){
		showmsg('�ö����Ѿ���ֵ�ɹ���');
	}
	$db->query("UPDATE {$pre}olpay SET ifpay='1' WHERE id='$rt[id]'");

	$num=$rt[money]*$webdb[alipay_scale];

	$db->query("UPDATE {$pre}memberdata SET moneycard=moneycard+'$num' WHERE uid='$rt[uid]'");
	jump("��ֵ�ɹ�","$FROMURL","1");
}

?>