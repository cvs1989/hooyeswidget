<?php
require("global.php");
require("bd_pics.php");

//���붯���ļ�
require(Mpath."/homepage_php/global.php");
require(Mpath."/inc/categories.php");

$bcategory->cache_read();
$bcategory->unsets();

//����û�
$uid=intval($uid);



if(!$uid) showerr("��Ǹ,û���ҵ���Ҫ���ʵ�ҳ�棡");

$rsdb=$db->get_one("SELECT * FROM {$_pre}company WHERE uid='$uid' LIMIT 1");


if(!$rsdb[rid])
{
	//�ж��ǲ����Լ�Ӧ�õǼ��̼�
	if($uid==$lfjuid) showerr("����û�еǼ��̼ң�<a href='$Mdomain/member/?main=post_company.php'><b>�������</b></a>�Ǽ�");
	else showerr("�̼���Ϣδ�Ǽ�");
}
if($uid!=$lfjuid){//��������Լ������̾���ʾ���ܿ�
	if(!$rsdb[yz])  showerr("��ʱ�������ṩ���̼���Ϣ");
}
//�̼������ļ�
$conf=$db->get_one("SELECT * FROM {$_pre}homepage where rid='$rsdb[rid]' LIMIT 1");
if(!$conf[hid]) { //�����̼���Ϣ
	caretehomepage($rsdb);
}

//��˾����,��bannerʱ������
if(!$conf[banner]) $rsdb[company_name_big]=$rsdb[title];
else $conf[banner]=" style='background:url(".$webdb[www_url]."/".$webdb[updir]."/{$Imgdirname}/banner/".$conf[banner].");'";

//���
$homepage_style="default";
if($conf[style] && is_dir($tpl_dir.$conf[style])) $homepage_style=$conf[style];

//ģ��
$conf[bodytpl]=$conf[bodytpl]?$conf[bodytpl]:"left";

//���ݴ���
$rsdb[logo]=$webdb[www_url].'/'.$webdb[updir]."/$Imgdirname/ico/".$rsdb[picurl];
$rsdb[renzheng]=getrenzheng($rsdb[renzheng]);
$conf[listnum]=unserialize($conf[listnum]);

$conf[index_left]=explode(",",$conf[index_left]);
$conf[index_right]=explode(",",$conf[index_right]);

//SEO
$titleDB[title]			= filtrate(strip_tags("$rsdb[title]  - $webdb[Info_webname]"));
$titleDB[keywords]		= filtrate(strip_tags("$webdb[Info_metakeywords]"));
$titleDB[description]	= strip_tags( $webdb[Info_metadescription]);


//�ÿ�


if($lfjuid)
{
	if($lfjuid!=$conf[uid]){
		$conf[visitor]="{$lfjuid}\t{$lfjid}\t{$timestamp}\r\n$conf[visitor]";
	}
}
else
{
	$conf[visitor]="0\t{$onlineip}\t{$timestamp}\r\n$conf[visitor]";
}

$detail=explode("\r\n",$conf[visitor]);
foreach( $detail AS $key=>$value)
{
	if($key>0&&(strstr($value,"{$lfjuid}\t{$lfjid}\t")||strstr($value,"0\t$onlineip")))
	{
		unset($detail[$key]);
	}
	if($key>20||!$value)
	{
		unset($detail[$key]);
	}
}
$conf[visitor]=implode("\r\n",$detail);

$db->query("UPDATE {$_pre}homepage SET hits=hits+1,visitor='$conf[visitor]' WHERE uid='$uid' ");
$db->query("UPDATE {$_pre}company  set hits=hits+1 WHERE uid='$uid'");



//���
require(getTpl("homepage_head"));
require(getTpl("homepage"));
require(Mpath."inc/foot.php");
?>