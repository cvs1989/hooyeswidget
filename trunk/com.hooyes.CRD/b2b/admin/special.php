<?php
!function_exists('html') && exit('ERR');
//����ר���б�
if($job=="list"&&$Apower[special_list])
{
	if(!table_field("{$pre}special","yz") ){
		$db->query("ALTER TABLE `{$pre}special` ADD `yz` TINYINT( 1 ) NOT NULL");
		$db->query("ALTER TABLE `{$pre}special` ADD INDEX ( `yz` )");
		$db->query("update `p8_special` set yz=1");
	}
	$rows=30;
	!$page && $page=1;
	$min=($page-1)*$rows;
	$rsdb=$db->get_one("SELECT * FROM {$pre}spsort WHERE fid='$fid' ");
	$aids=explode(",",$rsdb[aids]);
	$aids=implode("\r\n",$aids);
	if($fid){
		$SQL=" WHERE SP.fid='$fid' ";
	}else{
		$SQL=' WHERE 1 ';
	}
	if($yz=='yes'){
		$SQL.=' AND SP.yz=1 ';
	}elseif($yz=='no'){
		$SQL.=' AND SP.yz=0 ';
	}
	if($com=='yes'){
		$SQL.=' AND SP.levels=1 ';
	}elseif($com=='no'){
		$SQL.=' AND SP.levels=0 ';
	}
	$showpage=getpage("{$pre}special SP LEFT JOIN {$pre}spsort S ON SP.fid=S.fid","$SQL","?lfj=$lfj&job=$job&fid=$fid&yz=$yz&com=$com",$rows);
	$query = $db->query("SELECT SP.*,S.name FROM {$pre}special SP LEFT JOIN {$pre}spsort S ON SP.fid=S.fid $SQL ORDER BY list DESC LIMIT $min,$rows");
	while($rs = $db->fetch_array($query)){
		$rs[tidnum]=$rs[tids]?count(explode(',',$rs[tids])):0;
		$rs[aidnum]=$rs[aids]?count(explode(',',$rs[aids])):0;
		$rs[posttime]=date("Y-m-d",$rs[posttime]);
		if($rs[levels]){
			$rs[com]="<a href='?lfj=$lfj&job=com&levels=0&id=$rs[id]' style='color:red;'><img alt='���Ƽ�Ϊ����,�����ȡ������' src='../images/default/good_ico.gif'></a>";
		}else{
			$rs[com]="<a href='?lfj=$lfj&job=com&levels=1&id=$rs[id]' style=''><img alt='�Ǿ���,������Ƽ�Ϊ����' src='../member/images/nogood_ico.gif'></a>";
		}
		if($rs[yz]){
			$rs[_yz]="<a href='?lfj=$lfj&job=yz&yz=0&id=$rs[id]' style='color:red;'><img alt='�����,�����ȡ�����' src='../member/images/check_yes.gif'></a>";
		}else{
			$rs[_yz]="<a href='?lfj=$lfj&job=yz&yz=1&id=$rs[id]' style=''><img alt='δ���,�����ͨ�����' src='../member/images/check_no.gif'></a>";
		}
		$listdb[]=$rs;
	}
	$sort_fid=$Guidedb->Select("{$pre}spsort","fid",$fid);
	require(dirname(__FILE__)."/"."head.php");
	require(dirname(__FILE__)."/"."template/special/menu.htm");
	if($onlyshow=='style'){
		require(dirname(__FILE__)."/"."template/special/list_style.htm");
	}elseif($onlyshow=='label'){
		require(dirname(__FILE__)."/"."template/special/list_label.htm");
	}else{
		require(dirname(__FILE__)."/"."template/special/list.htm");
	}	
	require(dirname(__FILE__)."/"."foot.php");
}
//����ר��
elseif($action=="addsp"&&$Apower[special_list])
{
	if(!$title){
		showmsg("ר�����Ʋ���Ϊ��");
	}
	if(!$fid){
		showmsg("��ѡ��һ������");
	}
	$db->query("INSERT INTO `{$pre}special` ( `fid` , `title` , `picurl` , `content` , `aids` , `uid` , `username` , `posttime` , `list` , `yz`) VALUES ('$fid','$title','$picurl','$content','$aids','$userdb[uid]','$userdb[username]','$timestamp','$timestamp','1')");
	@extract($db->get_one("SELECT id FROM {$pre}special ORDER BY id DESC LIMIT 0,1"));
	jump("�����ɹ�","index.php?lfj=$lfj&job=editsp&id=$id");
}
//�޸�ר������
elseif($job=="editsp"&&$Apower[special_list])
{
	$rsdb=$db->get_one("SELECT * FROM {$pre}special WHERE id='$id'");
	$rsdb[config]=unserialize($rsdb[config]);
	$sort_fid=$Guidedb->Select("{$pre}spsort","postdb[fid]",$rsdb[fid],"");
	$style_select=select_style('postdb[style]',$rsdb[style]);
	$group_post=group_box("postdb[allowpost]",explode(",",$rsdb[allowpost]));

	$tpl=unserialize($rsdb[template]);
	$tpl_head=select_template("",7,$tpl[head]);
	$tpl_head=str_replace("<select","<select onChange='get_obj(\"tpl_head\").value=this.options[this.selectedIndex].value;'",$tpl_head);

	$tpl_foot=select_template("",8,$tpl[foot]);
	$tpl_foot=str_replace("<select","<select onChange='get_obj(\"tpl_foot\").value=this.options[this.selectedIndex].value;'",$tpl_foot);

	$tpl_list=select_template("",10,$tpl['list']);
	$tpl_list=str_replace("<select","<select onChange='get_obj(\"tpl_list\").value=this.options[this.selectedIndex].value;'",$tpl_list);

	$tpl_bencandy=select_template("",11,$tpl[bencandy]);
	$tpl_bencandy=str_replace("<select","<select onChange='get_obj(\"tpl_bencandy\").value=this.options[this.selectedIndex].value;'",$tpl_bencandy);

	$allowpost=group_box("postdb[allowpost]",explode(",",$rsdb[allowpost]));

	$ifbase[$rsdb[ifbase]]=' checked ';

	require(dirname(__FILE__)."/"."head.php");
	require(dirname(__FILE__)."/"."template/special/menu.htm");
	require(dirname(__FILE__)."/"."template/special/editsp.htm");
	require(dirname(__FILE__)."/"."foot.php");
}
//�޸�ר������
elseif($action=="editsp"&&$Apower[special_list])
{
	if(!$postdb[fid]){
		showerr("��ѡ��һ������");
	}
	if(!$postdb[title]){
		showerr("���Ʋ���Ϊ��");
	}
	
	$postdb[template]=@serialize($postdb[tpl]);
	$postdb[allowpost]=@implode(",",$postdb[allowpost]);

	$db->query("UPDATE `{$pre}special` SET fid='$postdb[fid]',title='$postdb[title]',picurl='$postdb[picurl]',content='$postdb[content]',style='$postdb[style]',template='$postdb[template]',list='$postdb[list]',banner='$postdb[banner]',allowpost='$postdb[allowpost]',ifbase='$postdb[ifbase]',htmlname='$postdb[htmlname]',keywords='$postdb[keywords]' WHERE id='$id' ");
	get_htmltype();
	jump("�޸ĳɹ�!","$FROMURL");
}
//ɾ��ר��
elseif($action=="delete"&&$Apower[special_list])
{
	if($id){
		$idDB[]=$id;
	}
	if(!$idDB){
		showmsg("��ѡ��һ��ר��");
	}
	$query = $db->query("SELECT * FROM `{$pre}special` WHERE id IN (".implode(",",$idDB).")");
	while($rs = $db->fetch_array($query)){
		delete_attachment($rs[uid], tempdir($rs[picurl]) );
		delete_attachment($rs[uid], tempdir($rs[banner]) );
		$fidDB=$db->get_one("SELECT * FROM {$pre}spsort WHERE fid='$rs[fid]'");
		$array=get_SPhtml_url($fidDB,$rs[id],$rs[posttime]);
		$array[showurl]=str_replace("$webdb[www_url]/",PHP168_PATH,$array[showurl]);
		$array[listurl]=str_replace("$webdb[www_url]/",PHP168_PATH,$array[listurl]);
		@unlink($array[showurl]);
		@unlink($array[listurl]);
		$db->query(" DELETE FROM `{$pre}special` WHERE id='$rs[id]' ");
		$db->query(" DELETE FROM `{$pre}label` WHERE ch='0' AND pagetype='11' AND module='0' AND fid='$rs[id]' AND chtype='0' ");
	}

	jump("ɾ���ɹ�",$FROMURL);
}
//�Ƽ�ר��
elseif($job=="com"&&$Apower[special_list]){
	$db->query("UPDATE `{$pre}special` SET levels='$levels',levelstime='$timestamp' WHERE id='$id' ");
	jump("����ɹ�",$FROMURL,0);
}
//���ר��
elseif($job=="yz"&&$Apower[special_list]){
	$db->query("UPDATE `{$pre}special` SET yz='$yz' WHERE id='$id' ");
	jump("����ɹ�",$FROMURL,0);
}
//ר�����̳���ӹ���
elseif($job=="edit_bbs"&&$Apower[special_list])
{
	$rsdb=$db->get_one("SELECT * FROM {$pre}special WHERE id='$id'");
	require(dirname(__FILE__)."/"."head.php");
	require(dirname(__FILE__)."/"."template/special/menu.htm");
	require(dirname(__FILE__)."/"."template/special/edit_bbs.htm");
	require(dirname(__FILE__)."/"."foot.php");
}
//��̳����
elseif($job=="show_BBSiframe"&&$Apower[special_list]){

	$rsdb=$db->get_one("SELECT * FROM {$pre}special WHERE id='$id'");
	if(!$rsdb){
		showerr("���ϲ�����",1);
	}

	//ר���ڵ���������
	if($act=="order")
	{
		unset($array);
		foreach( $listdb AS $aid=>$list){
			$list=$list*1000000+$aid;
			$array[$list]=$aid;
		}
		ksort($array);
		$rsdb[tids]=implode(",",$array);
		$db->query("UPDATE {$pre}special SET tids='$rsdb[tids]' WHERE id='$id'");
	}
	
	//������ӵ�ר��
	if($act=="add"&&$aid)
	{
		unset($_detail);
		$detail=explode(",",$rsdb[tids]);
		if(!in_array($aid,$detail)){
			if($detail[0]==''){unset($detail[0]);}
			$_detail[a]=$aid;
			$rsdb[tids]=$string=implode(",",array_merge($_detail,$detail));
			$db->query("UPDATE {$pre}special SET tids='$string' WHERE id='$id'");
		}
	}

	//�Ƴ�ר���������
	if($act=="del"&&$aid)
	{
		$detail=explode(",",$rsdb[tids]);
		foreach( $detail AS $key=>$value){
			if($value==$aid){
				unset($detail[$key]);
			}
		}
		$rsdb[tids]=$string=implode(",",$detail);
		$db->query("UPDATE {$pre}special SET tids='$string' WHERE id='$id'");
	}
	
	//$type=='all'��ʼ���г�ר���������,$type=="list_atc"ɾ�������ʱ�г�ר���������
	if($type=="list_atc"||$type=='all')
	{
		unset($_listdb,$show);
		$detail=explode(",",$rsdb[tids]);
		$string=0;
		foreach( $detail AS $key=>$value){
			if($value>0){
				$string.=",$value";
			}
		}
		if(ereg("^pwbbs",$webdb[passport_type])){
			$query = $db->query("SELECT * FROM {$TB_pre}threads WHERE tid IN ($string)");
			while($rs = $db->fetch_array($query)){
				$rs[subject]="<a href='$webdb[passport_url]/read.php?tid=$rs[tid]' target=_blank>$rs[subject]</a>";
				$_listdb[$rs[tid]]=$rs;
			}
		}
		$aidsdb=explode(",",$rsdb[tids]);
		$NUM=0;
		foreach($aidsdb AS $key=>$value){
			$NUM++;
			if($_listdb[$value]){
				$show.="<tr align='center' class='trA' onmouseover=\"this.className='trB'\" onmouseout=\"this.className='trA'\"> 
                <td width='5%' style='border-bottom:1px dotted #ccc;'>{$_listdb[$value][tid]}</td>
                <td width='74%' style='border-bottom:1px dotted #ccc;' align='left'>{$_listdb[$value][subject]}</td>
				<td width='10%' style='border-bottom:1px dotted #ccc;'><input type='text' name='listdb[{$value}]' size='5' value='{$NUM}0'></td>
                <td width='11%' style='border-bottom:1px dotted #ccc;'><A HREF='index.php?lfj=$lfj&job=show_BBSiframe&id=$id&type=list_atc&act=del&aid={$_listdb[$value][tid]}' target='spiframe'>�Ƴ�</A></td>
              </tr>";
			}
		}
		$show="<table width='100%' border='0' cellspacing='1' cellpadding='3'>
              <tr align='center'> 
                <td width='5%' bgcolor='#eeeeee'>ID</td>
                <td width='74%' bgcolor='#eeeeee'>�� ��</td>
				  <td width='10%' bgcolor='#eeeeee'>����ֵ</td>
                <td width='11%' bgcolor='#eeeeee'>�Ƴ� </td>
				  $show
              </tr>
			  
            </table>";
		$show=str_replace("\r","",$show);
		$show=str_replace("\n","",$show);
		$show=str_replace('"','\"',$show);
		echo "<SCRIPT LANGUAGE='JavaScript'>
		<!--
		parent.document.getElementById('sp_atclist').innerHTML=\"$show\";
		//-->
		</SCRIPT>";
	}

	//
	if($type=='myatc'||$type=='all')
	{
		$detail=explode(",",$rsdb[tids]);
		$show='';
		if($page<1){
			$page=1;
		}
		$rows=15;
		$min=($page-1)*$rows;
		if($keywords){//����ʱ
			$SQL=" BINARY subject LIKE '%$keywords%' ";
		}elseif($ismy){
			$SQL=" authorid='$lfjuid' ";
		}else{
			$SQL=' 1 ';
		}
		
		if($fid>0){
			$SQL.=" AND fid='$fid' ";
		}
		$showpage=getpage("{$TB_pre}threads","WHERE $SQL","",$rows);
		$query = $db->query("SELECT * FROM {$TB_pre}threads WHERE $SQL ORDER BY tid DESC LIMIT $min,$rows");
		if(ereg("^pwbbs",$webdb[passport_type])){			
			while($rs = $db->fetch_array($query)){
				$add="&nbsp;";
				if(!in_array($rs[tid],$detail)){
					$add="<A HREF='index.php?lfj=$lfj&job=show_BBSiframe&id=$id&type=list_atc&act=add&aid={$rs[tid]}' target='spiframe' onclick=closedo(this)>���</A>";
				}
				$show.="<tr align='center' class='trA' onmouseover=\"this.className='trB'\" onmouseout=\"this.className='trA'\"> 
					<td width='5%' style='border-bottom:1px dotted #ccc;'>{$rs[tid]}</td>
					<td width='84%' style='border-bottom:1px dotted #ccc;' align='left'><a href='$webdb[passport_url]/read.php?tid=$rs[tid]' target=_blank>$rs[subject]</a></td>
					<td width='11%' style='border-bottom:1px dotted #ccc;'>&nbsp;$add</td>
				  </tr>";
			}
		}elseif(ereg("^dzbbs",$webdb[passport_type])){
			while($rs = $db->fetch_array($query)){
				$add="&nbsp;";
				if(!in_array($rs[tid],$detail)){
					$add="<A HREF='index.php?lfj=$lfj&job=show_BBSiframe&id=$id&type=list_atc&act=add&aid={$rs[tid]}' target='spiframe' onclick=closedo(this)>���</A>";
				}
				$show.="<tr align='center' class='trA' onmouseover=\"this.className='trB'\" onmouseout=\"this.className='trA'\"> 
					<td width='5%' style='border-bottom:1px dotted #ccc;'>{$rs[tid]}</td>
					<td width='84%' style='border-bottom:1px dotted #ccc;' align='left'><a href='$webdb[passport_url]/viewthread.php?tid=$rs[tid]' target=_blank>$rs[subject]</a></td>
					<td width='11%' style='border-bottom:1px dotted #ccc;'>&nbsp;$add</td>
				  </tr>";
			}
		}
		
		$show="<table width='100%' border='0' cellspacing='1' cellpadding='3'>
              <tr align='center'> 
                <td width='5%' bgcolor='#eeeeee'>ID</td>
                <td width='84%' bgcolor='#eeeeee'>�� ��</td>
                <td width='11%' bgcolor='#eeeeee'>���</td>
				  $show
              </tr>
			  
            </table>";
		$show=str_replace("\r","",$show);
		$show=str_replace("\n","",$show);
		$show=str_replace('"','\"',$show);
		echo "<SCRIPT LANGUAGE='JavaScript'>
		<!--
		parent.document.getElementById('show_myatc').innerHTML=\"$show\";
		//-->
		</SCRIPT>";		
		$showpage=str_replace("\r","",$showpage);
		$showpage=str_replace("\n","",$showpage);
		$showpage=str_replace('"',"",$showpage);
		$showpage=str_replace("href=&page=","target='spiframe' href=index.php?lfj=$lfj&job=show_BBSiframe&id=$id&type=myatc&ismy=$ismy&keywords=".urlencode($keywords)."&page=",$showpage);
		echo "<SCRIPT LANGUAGE='JavaScript'>
		<!--
		parent.document.getElementById('show_myatc_page').innerHTML=\"$showpage\";
		//-->
		</SCRIPT>";

		//��̳��Ŀ
		$sort_fid=$Guidedb->Select_PW("fid",$fid);

		$sort_fid=str_replace("\r","",$sort_fid);
		$sort_fid=str_replace("\n","",$sort_fid);
		$sort_fid=str_replace('"',"",$sort_fid);
		$ismy?$color_me='red':$color_all='red';
		$sort_fid=str_replace("<select name='fid'","[<A target='spiframe'  HREF='index.php?lfj=$lfj&job=show_BBSiframe&id=$id&type=myatc&fid=$fid&ismy=1' style='color:$color_me;'>�ҵ�����</A>] [<A target='spiframe'  HREF='index.php?lfj=$lfj&job=show_BBSiframe&id=$id&type=myatc&fid=$fid' style='color:$color_all;'>��������</A>] <select onChange='fid_jumpMenu(this)'",$sort_fid);
		echo "<SCRIPT LANGUAGE='JavaScript'>
		<!--
		parent.document.getElementById('show_myatc_fid').innerHTML=\"$sort_fid\";
		//-->
		</SCRIPT>";
	}	
}
//ר��������¹���
elseif($job=="edit_atc"&&$Apower[special_list])
{
	$rsdb=$db->get_one("SELECT * FROM {$pre}special WHERE id='$id'");
	require(dirname(__FILE__)."/"."head.php");
	require(dirname(__FILE__)."/"."template/special/menu.htm");
	require(dirname(__FILE__)."/"."template/special/edit_atc.htm");
	require(dirname(__FILE__)."/"."foot.php");
}

//ר��������¹���
elseif($job=="show_iframe"&&$Apower[special_list]){

	$rsdb=$db->get_one("SELECT * FROM {$pre}special WHERE id='$id'");
	if(!$rsdb){
		showerr("���ϲ�����",1);
	}
	if($act=="order")
	{
		unset($array);
		foreach( $listdb AS $aid=>$list){
			$list=$list*1000000+$aid;
			$array[$list]=$aid;
		}
		ksort($array);
		$rsdb[aids]=implode(",",$array);
		$db->query("UPDATE {$pre}special SET aids='$rsdb[aids]' WHERE id='$id'");
	}
	if($act=="add"&&$aid)
	{
		unset($_detail);
		$detail=explode(",",$rsdb[aids]);
		if(!in_array($aid,$detail)){
			if($detail[0]==''){unset($detail[0]);}
			$_detail[a]=$aid;
			$rsdb[aids]=$string=implode(",",array_merge($_detail,$detail));
			$db->query("UPDATE {$pre}special SET aids='$string' WHERE id='$id'");
		}
	}
	if($act=="del"&&$aid)
	{
		$detail=explode(",",$rsdb[aids]);
		foreach( $detail AS $key=>$value){
			if($value==$aid){
				unset($detail[$key]);
			}
		}
		$rsdb[aids]=$string=implode(",",$detail);
		$db->query("UPDATE {$pre}special SET aids='$string' WHERE id='$id'");
	}
	
	//�г���ר���µ�����,$type=='all',��ʼ��ʱ.$type=="list_atc",�������»��Ƴ�����ʱ
	if($type=="list_atc"||$type=='all')
	{
		unset($_listdb,$show);
		$detail=explode(",",$rsdb[aids]);
		$string=0;
		foreach( $detail AS $key=>$value){
			if($value>0){
				$string.=",$value";
			}
		}
		$query = $db->query("SELECT A.*,D.aid FROM {$pre}article_db D LEFT JOIN {$pre}article A ON D.aid=A.aid WHERE D.aid IN ($string)");
		while($rs = $db->fetch_array($query)){
			if(!$rs[title]&&$_rs=get_one_article($rs[aid])){
				$rs=$_rs+$rs;
			}
			$rs[subject]="<a href='../bencandy.php?fid=$rs[fid]&id=$rs[aid]' target=_blank>$rs[title]</a>";
			$_listdb[$rs[aid]]=$rs;
		}
		$aidsdb=explode(",",$rsdb[aids]);
		$NUM=0;
		foreach($aidsdb AS $key=>$value){
			$NUM++;
			if($_listdb[$value]){
				$show.="<tr align='center' class='trA' onmouseover=\"this.className='trB'\" onmouseout=\"this.className='trA'\"> 
                <td width='5%' style='border-bottom:1px dotted #ccc;'>{$_listdb[$value][aid]}</td>
                <td width='74%' style='border-bottom:1px dotted #ccc;' align='left'>{$_listdb[$value][subject]}</td>
					<td width='10%' style='border-bottom:1px dotted #ccc;'><input type='text' name='listdb[{$value}]' size='5' value='{$NUM}0'></td>
                <td width='11%' style='border-bottom:1px dotted #ccc;'><A HREF='index.php?lfj=$lfj&job=show_iframe&id=$id&type=list_atc&act=del&aid={$_listdb[$value][aid]}' target='spiframe'>�Ƴ�</A></td>
              </tr>";
			}
		}
		$show="<table width='100%' border='0' cellspacing='1' cellpadding='3'>
              <tr align='center'> 
                <td width='5%' bgcolor='#eeeeee'>ID</td>
                <td width='74%' bgcolor='#eeeeee'>�� ��</td>
				  <td width='10%' bgcolor='#eeeeee'>����ֵ</td>
                <td width='11%' bgcolor='#eeeeee'>�Ƴ� </td>
				  $show
              </tr>
			  
            </table>";
		$show=str_replace("\r","",$show);
		$show=str_replace("\n","",$show);
		$show=str_replace('"','\"',$show);
		echo "<SCRIPT LANGUAGE='JavaScript'>
		<!--
		parent.document.getElementById('sp_atclist').innerHTML=\"$show\";
		//-->
		</SCRIPT>";
	}

	//$type=='all',��ʼ��ʱ,$type=='myatc'ɸѡʱ
	if($type=='myatc'||$type=='all')
	{
		$SQL='';
		$detail=explode(",",$rsdb[aids]);
		$show='';
		if($page<1){
			$page=1;
		}
		$rows=15;
		$min=($page-1)*$rows;
		
		if($search_type&&$keywords){//����ʱ
			if($search_type=='title'){
				$SQL=" BINARY title LIKE '%$keywords%' ";
			}elseif($search_type=='keyword'){
				$SQL=" BINARY keywords LIKE '%$keywords%' ";
			}
		}elseif($ismy){//ֻ�г��ҵ�����
			$SQL=" uid='$lfjuid' ";
		}else{
			$SQL=' 1 ';
		}
		
		if($fid>0){
			$SQL.=" AND fid='$fid' ";
		}
		$showpage=getpage("{$pre}article","WHERE $SQL","",$rows);
		$query = $db->query("SELECT * FROM {$pre}article WHERE $SQL ORDER BY list DESC LIMIT $min,$rows");
		while($rs = $db->fetch_array($query)){
			$add="&nbsp;";
			if(!in_array($rs[aid],$detail)){
				$add="<A HREF='index.php?lfj=$lfj&job=show_iframe&id=$id&type=list_atc&act=add&aid={$rs[aid]}' target='spiframe' onclick=closedo(this)>���</A>";
			}
			$show.="<tr align='center' class='trA' onmouseover=\"this.className='trB'\" onmouseout=\"this.className='trA'\"> 
                <td width='5%' style='border-bottom:1px dotted #ccc;'>{$rs[aid]}</td>
                <td width='84%' style='border-bottom:1px dotted #ccc;' align='left'><A HREF='../bencandy.php?fid=$rs[fid]&id=$rs[aid]' target=_blank>{$rs[title]}</A></td>
                <td width='11%' style='border-bottom:1px dotted #ccc;'>&nbsp;$add</td>
              </tr>";
		}
		$show="<table width='100%' border='0' cellspacing='1' cellpadding='3'>
              <tr align='center'> 
                <td width='5%' bgcolor='#eeeeee'>ID</td>
                <td width='84%' bgcolor='#eeeeee'>�� ��</td>
                <td width='11%' bgcolor='#eeeeee'>���</td>
				  $show
              </tr>
			  
            </table>";
		$show=str_replace("\r","",$show);
		$show=str_replace("\n","",$show);
		$show=str_replace('"','\"',$show);
		echo "<SCRIPT LANGUAGE='JavaScript'>
		<!--
		parent.document.getElementById('show_myatc').innerHTML=\"$show\";
		//-->
		</SCRIPT>";		
		$showpage=str_replace("\r","",$showpage);
		$showpage=str_replace("\n","",$showpage);
		$showpage=str_replace('"',"",$showpage);
		$showpage=str_replace("href=&page=","target=spiframe href=index.php?lfj=$lfj&job=show_iframe&id=$id&type=myatc&ismy=$ismy&search_type=$search_type&keywords=".urlencode($keywords)."&page=",$showpage);
		echo "<SCRIPT LANGUAGE='JavaScript'>
		<!--
		parent.document.getElementById('show_myatc_page').innerHTML=\"$showpage\";
		//-->
		</SCRIPT>";

		$sort_fid=$Guidedb->Select("{$pre}sort","fid",$fid,"");
		$sort_fid=str_replace("\r","",$sort_fid);
		$sort_fid=str_replace("\n","",$sort_fid);
		$sort_fid=str_replace('"',"",$sort_fid);
		$ismy?$color_me='red':$color_all='red';

		$sort_fid=str_replace("<select name='fid'","[<A target='spiframe'  HREF='index.php?lfj=$lfj&job=show_iframe&id=$id&type=myatc&fid=$fid&ismy=1' style='color:$color_me;'>�ҵ�����</A>] [<A target='spiframe'  HREF='index.php?lfj=$lfj&job=show_iframe&id=$id&type=myatc&fid=$fid' style='color:$color_all;'>��������</A>]<select onChange='fid_jumpMenu(this)'",$sort_fid);
		echo "<SCRIPT LANGUAGE='JavaScript'>
		<!--
		parent.document.getElementById('show_myatc_fid').innerHTML=\"$sort_fid\";
		//-->
		</SCRIPT>";
	}
}
?>