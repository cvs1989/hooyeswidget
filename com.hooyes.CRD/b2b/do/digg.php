<?php
require_once(dirname(__FILE__)."/"."global.php");


//�б�ҳ����ƪ����
$rows=20;	

$fidDB[listorder]=8;
$listdb=ListThisSort($rows,50);		//����Ŀ�����б�
$showpage=getpage("{$pre}article","WHERE yz=1","?",$rows);	//�����б��ҳ

require(PHP168_PATH."inc/head.php");
require(html("digg"));
require(PHP168_PATH."inc/foot.php");


//α��̬����
if($webdb[NewsMakeHtml]==2)
{
	$content=ob_get_contents();
	ob_end_clean();
	ob_start();
	$content=fake_html($content);
	echo "$content";
}

?>