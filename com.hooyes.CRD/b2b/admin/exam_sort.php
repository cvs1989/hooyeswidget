<?php
!function_exists('html') && exit('ERR');
if($job=="listsort"&&$Apower[exam_sort])
{
	$fid=intval($fid);	
	$sortdb=array();
	list_allsort($fid,$table='exam_sort');

	$sort_fup=$Guidedb->Select("{$pre}exam_sort","fup",$fid);

	require(dirname(__FILE__)."/"."head.php");
	require(dirname(__FILE__)."/"."template/exam_sort/menu.htm");
	require(dirname(__FILE__)."/"."template/exam_sort/sort.htm");
	require(dirname(__FILE__)."/"."foot.php");
}
elseif($action=="addsort"&&$Apower[exam_sort])
{
	if($fup){
		$rs=$db->get_one("SELECT name,class FROM {$pre}exam_sort WHERE fid='$fup' ");
		$class=$rs['class'];
		$db->query("UPDATE {$pre}exam_sort SET sons=sons+1 WHERE fid='$fup'");
		$type=0;
	}else{
		
		$class=0;
	}
	$type=1;	/*�����־*/
	$class++;
	$db->query("INSERT INTO {$pre}exam_sort (name,fup,class,type,allowcomment) VALUES ('$name','$fup','$class','$type',1) ");
	@extract($db->get_one("SELECT fid FROM {$pre}exam_sort ORDER BY fid DESC LIMIT 0,1"));
	
	mod_sort_class("{$pre}exam_sort",0,0);		//����class
	mod_sort_sons("{$pre}exam_sort",0);			//����sons

	jump("�����ɹ�","index.php?lfj=$lfj&job=editsort&fid=$fid");
}

//�޸���Ŀ��Ϣ
elseif($job=="editsort"&&$Apower[exam_sort])
{
	$rsdb=$db->get_one("SELECT * FROM {$pre}exam_sort WHERE fid='$fid'");
	$rsdb[config]=unserialize($rsdb[config]);
	$sort_fup=$Guidedb->Select("{$pre}exam_sort","postdb[fup]",$rsdb[fup]);
	$style_select=select_style('postdb[style]',$rsdb[style]);
	//$group_post=group_box("postdb[allowpost]",explode(",",$rsdb[allowpost]));
	//$group_viewcontent=group_box("postdb[allowviewcontent]",explode(",",$rsdb[allowviewcontent]));
	$typedb[$rsdb[type]]=" checked ";

	require(dirname(__FILE__)."/"."head.php");
	require(dirname(__FILE__)."/"."template/exam_sort/menu.htm");
	require(dirname(__FILE__)."/"."template/exam_sort/editsort.htm");
	require(dirname(__FILE__)."/"."foot.php");
}
elseif($action=="editsort"&&$Apower[exam_sort])
{
	//��鸸��Ŀ�Ƿ�������
	check_fup("{$pre}exam_sort",$postdb[fid],$postdb[fup]);
	//$postdb[allowpost]=@implode(",",$postdb[allowpost]);
	//$postdb[allowviewcontent]=@implode(",",$postdb[allowviewcontent]);
	unset($SQL);

	$rs_fid=$db->get_one("SELECT * FROM {$pre}exam_sort WHERE fid='$postdb[fid]'");
	//���������������ط�Ҳ�޸Ĺ����ֵ.�����ǩ��
	$rs_fid[config]=unserialize($rs_fid[config]);
	$postdb[config]=addslashes( serialize($rs_fid[config]) );

	if($rs_fid[fup]!=$postdb[fup])
	{
		$rs_fup=$db->get_one("SELECT class FROM {$pre}exam_sort WHERE fup='$postdb[fup]' ");
		$newclass=$rs_fup['class']+1;
		$db->query("UPDATE {$pre}exam_sort SET sons=sons+1 WHERE fup='$postdb[fup]' ");
		$db->query("UPDATE {$pre}exam_sort SET sons=sons-1 WHERE fup='$rs_fid[fup]' ");
		$SQL=",class=$newclass";
	}

	$db->query("UPDATE {$pre}exam_sort SET fup='$postdb[fup]',name='$postdb[name]',type='$postdb[type]',admin='$postdb[admin]',passwd='$postdb[passwd]',logo='$postdb[logo]',descrip='$postdb[descrip]',style='$postdb[style]',template='$postdb[template]',jumpurl='$postdb[jumpurl]',listorder='$postdb[listorder]',maxperpage='$postdb[maxperpage]',allowcomment='$postdb[allowcomment]',allowpost='$postdb[allowpost]',allowviewtitle='$postdb[allowviewtitle]',allowviewcontent='$postdb[allowviewcontent]',allowdownload='$postdb[allowdownload]',forbidshow='$postdb[forbidshow]',metakeywords='$postdb[metakeywords]',config='$postdb[config]'$SQL WHERE fid='$postdb[fid]' ");

	mod_sort_class("{$pre}exam_sort",0,0);		//����class
	mod_sort_sons("{$pre}exam_sort",0);			//����sons
	jump("�޸ĳɹ�","$FROMURL");
}

elseif($action=="delete"&&$Apower[exam_sort])
{
	$db->query(" DELETE FROM `{$pre}exam_sort` WHERE fid='$fid' ");
	mod_sort_class("{$pre}exam_sort",0,0);		//����class
	mod_sort_sons("{$pre}exam_sort",0);			//����sons
	jump("ɾ���ɹ�",$FROMURL);
}
elseif($action=="editlist"&&$Apower[exam_sort])
{
	foreach( $order AS $key=>$value){
		$db->query("UPDATE {$pre}exam_sort SET list='$value' WHERE fid='$key' ");
	}
	mod_sort_class("{$pre}exam_sort",0,0);		//����class
	mod_sort_sons("{$pre}exam_sort",0);			//����sons
	jump("�޸ĳɹ�","$FROMURL",1);
}
/**
*�޸���վ��Ŀ
**/
elseif($job=='save'&&$Apower[exam_sort])
{
	$errsort=sort_error("{$pre}exam_sort",'fid');
 	$sort_fup=$Guidedb->Select("{$pre}exam_sort","fup",$rsdb[fup]);
	require(dirname(__FILE__)."/"."head.php");
	require(dirname(__FILE__)."/"."template/exam_sort/menu.htm");
	require(dirname(__FILE__)."/"."template/exam_sort/save.htm");
	require(dirname(__FILE__)."/"."foot.php");
}

/**
*�����޸�������Ŀ
**/
elseif($action=='save'&&$Apower[exam_sort]){
	if(!$fid){
		showmsg("��ѡ��һ����Ŀ");
	}
	$db->query("UPDATE {$pre}exam_sort SET fid='$fup' WHERE fid='$fid' ");
	mod_sort_class("{$pre}exam_sort",0,0);			//����class
	mod_sort_sons("{$pre}exam_sort",0);			//����sons
	jump("����Ŀ�����ɹ�","$FROMURL",1);
}

?>