<?php
require_once("global.php");


if($job=="set"){
	require("head.php");
	require("template/makehtml/set.htm");
	require("foot.php");

}elseif($action=="deleteindex"){
	
	if(unlink(PHP168_PATH."/index.htm")){   
		refreshto("?job=set","ɾ���ɹ�");
	}elseif(is_file(PHP168_PATH."/index.htm")){
		showerr("ɾ��ʧ��,���޸��ļ�����Ϊ��д");
	}else{
		showerr("ɾ��ʧ��,�ļ�������");
	}

}elseif($job=="bencandy"){

	if($webdb[bencandyIsHtml]){

		$select_news=$Guidedb->Select("{$pre}business_sort",'fiddb[]',$fiddb,'','0','',1,'20');
		require("head.php");
	    require("template/makehtml/make.htm");
		require("foot.php");
	}else{
		showerr("����û�п�������ҳ��̬���أ����ڡ�<a href='center.php?job=config'><font color=red>ϵͳ����</font></a>����������Ҳ��̬����!");
	}

}elseif($action=="make"){

	if(!$fiddb[0]){
		showerr("��ѡ��һ����Ŀ");
	}
	$i=0;
	foreach($fiddb AS $key=>$fid){
		if(!$fid){
			unset($fiddb[$key]);
			continue;
		}
		$phpcode.="\$tmpfiddb[$i]=$fid; \r\n";
		$i++;
	}
	write_file("tmpfiddb.php","<?php \r\n".$phpcode." ?>");

	jump("���ڿ�ʼ���ɾ�̬","?job=makedo&showNum=$showNum",1);


}elseif($job=="makedo"){

	$index=intval($index);
	$index=$index?$index:0;
	$showNum=$showNum?$showNum:10;

	if(file_exists("tmpfiddb.php")){
		require("tmpfiddb.php");
	}else{
		showerr("��ʱ�ļ�����");
	}
	
	if($tmpfiddb){
		if(!$tmpfiddb[$index]){
			jump("ȫ���������","?job=bencandy",1);
		}
		$page=$page?$page:1;
		$min=($page-1)*$showNum;
		$over=true;
		$query=$db->query("select id,title from {$_pre}content where fid=".$tmpfiddb[$index]." limit $min,$showNum");
		while($rs=$db->fetch_array($query)){
			$rt=@file_get_contents($Mdomain."/bencandy.php?fid={$rs[fid]}&id=$rs[id]&makehtml=back");
			$over=false;
		}
		
		if(!$over){
			jump("�Ѿ�����<font color=red>".$Fid_db[name][$tmpfiddb[$index]]."</font>��{$page}�������Ե�...","?job=makedo&showNum=$showNum&index=$index&page=".($page+1),1);
		}else{
			jump("����<font color=red>".$Fid_db[name][$tmpfiddb[$index]]."</font>��ϣ����Ե�...","?job=makedo&showNum=$showNum&index=".($index+1),1);
		}
	}else{
		jump("�޿�����Ŀ","?job=bencandy",1);
	}

	
}


?>