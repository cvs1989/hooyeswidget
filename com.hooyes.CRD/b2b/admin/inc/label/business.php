<?php
!function_exists('html') && exit('ERR');

if($action=='mod'){	
	
	//ģ��1�ǻ�����.ģ����Ǹ�����.ʵ�ֶ�����Ч��
	$postdb[tplpart_1code]=StripSlashes($tplpart_1);
	$postdb[tplpart_2code]=StripSlashes($tplpart_2);

	//ʹ�����߱༭����,ȥ���������ַ
	$weburl=preg_replace("/(.*)\/([^\/]+)/is","\\1/",$WEBURL);
	$postdb[tplpart_1code]=str_replace($weburl,"",$postdb[tplpart_1code]);
	$postdb[tplpart_2code]=str_replace($weburl,"",$postdb[tplpart_2code]);
 
	//���һЩ�Զ����ģ���������ж�
	if(strstr($postdb[tplpart_1code],'$picurl')&&strstr($postdb[tplpart_1code],'$content')){
		$stype="cp";
	}elseif(strstr($postdb[tplpart_1code],'$content')){
		$stype="c";
	}elseif(strstr($postdb[tplpart_1code],'$picurl')){
		$stype="p";
	}

	//ѡ����ʾ��������,����ѡ��Table,����һ������ʾЧ��,ѡ��tableָ����һ��TABLE,ѡ��divָ���׶���Ĵ���
	if($colspan>1){
		$DivTpl=0;
	}else{
		$DivTpl=1;
	}

	if($rowspan<1){
		$rowspan=1;
	}
	if($colspan<1){
		$colspan=1;
	}
	$rows=$rowspan*$colspan;
	
	if($yz==1){
		$SQL=" WHERE A.yz=1 ";
	}else{
		$SQL=" WHERE 1 ";
	}
	if($levels==1){
		$SQL.=" AND A.levels=1 ";
	}
	
	if($fiddb[0]){
		foreach($fiddb AS $key=>$value){
			if(!is_numeric($value)){
				unset($fiddb[$key]);
			}
		}
		$fids=implode(",",$fiddb);
		if($fids){
			if($ctype == 3){
				$fid_join = " INNER JOIN {$pre}business_company_fid cf ON cf.cid = A.rid ";
				$SQL.=" AND cf.fid IN ($fids) ";
			}else{
				$SQL.=" AND A.fid IN ($fids) ";
			}
		}
	}
	

	//$stype=="r"�õ�Ƭ,$picurl��ʾͼƬ,�����ģ����ͼƬ�Ļ�.����ģ��Ҳ�Զ�Ҫ��ͼƬ
	if($stype=="r"||strstr($postdb[tplpart_1code],'$picurl')){
		$SQL.=" AND A.picurl !='' ";
	}
	
	//�ر���,����ǻõ�Ƭ�Ļ�,Ҫȡ������ģ��
	$stype=="r" && $postdb[tplpart_2code]='';
	
	if(strstr($postdb[tplpart_1code],'$price') && $ctype!=0 && $ctype != 3){
		$SQL.=" and A.my_price >0";
	}
	//
	
	if($ctype==1){
		$SQL=" SELECT A.* ,concat( 'business/', A.picurl ) AS picurl,A.my_price as price ,B.my_ptype as sn,B.quantity_type
		 FROM {$pre}business_content_sell A  left join {$pre}business_content_1 B on B.id=A.id
		  $SQL $SQLPIC   ORDER BY $order $asc LIMIT $rows ";
	}else if($ctype==2){
		$SQL=" SELECT A.* ,concat( 'business/', A.picurl ) AS picurl,A.my_price as price ,B.quantity_type
		 FROM {$pre}business_content_buy A  left join {$pre}business_content_2 B on B.id=A.id
		  $SQL $SQLPIC   ORDER BY $order $asc LIMIT $rows ";
	}else if($ctype==3){
		//�̼ҵ�
		$order = $order == 'A.id' ? " A.yz $asc, A.posttime " : $order;
		$SQL=" SELECT A.*, A.rid AS id, concat( 'business/ico/', A.picurl ) AS picurl
		 FROM {$pre}business_company A $fid_join
		  $SQL $SQLPIC   ORDER BY $order $asc LIMIT $rows ";
	}else if($ctype==4){
		//��Ƹ
		$SQL=" SELECT jobs_id AS id, title , companyname as username , posttime 
		 FROM {$pre}business_hr_jobs ORDER BY posttime desc LIMIT $rows ";
	}else if($ctype==5){
		//��ְ
		$SQL=" SELECT  re_id AS id, truename as username , posttime ,job_name
		 FROM {$pre}business_hr_resume ORDER BY posttime desc LIMIT $rows ";
	}else if($ctype==6){
		//�Ƽ���Ʒ
		$SQL=" SELECT A.* ,concat( 'business/', A.picurl ) AS picurl,A.my_price as price 
		 FROM {$pre}business_content_sell A 
		  where A.yz=1 AND A.picurl !='' ORDER BY $order $asc LIMIT $rows ";
	}else if($ctype==7){
		//չ����Ϣ
		$SQL=" SELECT  A.* ,A.zh_id AS id,
		concat(year(from_unixtime(starttime)),'-',month(from_unixtime(starttime)),'-',dayofmonth(from_unixtime(starttime))) as starttime,
		concat(year(from_unixtime(endtime)),'-',month(from_unixtime(endtime)),'-',dayofmonth(from_unixtime(endtime))) as endtime FROM {$pre}business_zh_content A ORDER BY posttime desc LIMIT $rows ";
	}else if($ctype==8){
		//���ƻ�Ա
		$SQL="select A.uid as id, A.title as title, A.qy_regplace as area, A.content as content, A.username as username from {$pre}business_company A where A.renzheng > 0  order by posttime desc limit $rows";
	}else if($ctype==9){
		//������
		$SQL="select A.uid as id, A.title as title, A.qy_regplace as area, A.content as content, A.username as username from {$pre}business_company A where A.is_agent > 0  order by posttime desc limit $rows";
	}else if($ctype==10){
		//vip��Ա
		$SQL="select A.uid as id, A.title as title, A.qy_regplace as area, A.content as content, A.username as username from {$pre}business_company A where A.is_vip > 0  order by posttime desc limit $rows";
	}
		  //echo $SQL;
	
	$postdb[SYS]='normal';	
	$postdb[SYS_type]='business';
	$postdb[rolltype]=$rolltype;
	$postdb[rolltime]=$rolltime;
	$postdb[roll_height]=$roll_height;
	$postdb[width]=$width;
	$postdb[height]=$height;
	
	$postdb[newhour]=$newhour;
	$postdb[hothits]=$hothits;
	$postdb[amodule]=$amodule;
	$postdb[tplpath]=$tplpath;
	$postdb[DivTpl]=$DivTpl;
	$postdb[fiddb]=$fids;
	$postdb[stype]=$stype;
	$postdb[yz]=$yz;
	$postdb[ctype]=$ctype;
	$postdb[hidefid]=$hidefid;
	$postdb[timeformat]=$timeformat;
	$postdb[order]=$order;
	$postdb[asc]=$asc;
	$postdb[levels]=$levels;
	$postdb[rowspan]=$rowspan;
	$postdb[sql]=$SQL;			//��ģ��
	$postdb[sql2]=$SQL2;		//����ģ��
	$postdb[colspan]=$colspan;
	$postdb[content_num]=$content_num;
	$postdb[content_num2]=$content_num2;
	$postdb[titlenum]=$titlenum;
	$postdb[titlenum2]=$titlenum2;
	$postdb[titleflood]=$titleflood;

	$postdb[c_rolltype]=$c_rolltype;
	//print_r($postdb);exit;
	$code=addslashes(serialize($postdb));
	$div_db[div_w]=$div_w;
	$div_db[div_h]=$div_h;
	$div_db[div_bgcolor]=$div_bgcolor;
	$div=addslashes(serialize($div_db));
	$typesystem=1;
	//�������±�ǩ��
	do_post();

}else{

	$rsdb=get_label();
	$div=unserialize($rsdb[divcode]);
	@extract($div);
	$codedb=unserialize($rsdb[code]);
	$sort_type = 'fid';
	@extract($codedb);
	
	if(!isset($levels)){
		$levels="all";
	}
	if(!isset($order)){
		$order="list";
	}
	$titleflood=(int)$titleflood;
	$hide=(int)$rsdb[hide];
	if($rsdb[js_time]){
		$js_ck='checked';
	}

	/*Ĭ��ֵ*/
	$yz=='all' || $yz=1;
	$asc || $asc='DESC';
	$titleflood!=1		&& $titleflood=0;
	$timeformat			|| $timeformat="Y-m-d H:i:s";
	$rowspan			|| $rowspan=5;
	$colspan			|| $colspan=1;
	$titlenum			|| $titlenum=20;
	$content_num		|| $content_num=80;
	$div_w				|| $div_w=50;
	$div_h				|| $div_h=30;
	$hide!=1			&& $hide=0;
	$DivTpl!=1			&& $DivTpl=0;
	$stype				|| $stype=4;

	$width				|| $width=250;
	$height				|| $height=187;
	$roll_height		|| $roll_height=50;
	
	$div_width && $div_w=$div_width;
	$div_height && $div_h=$div_height;
	$yzdb[$yz]="checked";
	$ascdb[$asc]="checked";
	$orderdb[$order]=" selected ";
	$levelsdb[$levels]=" checked ";
	$titleflooddb["$titleflood"]="checked";
	$hidedb[$hide]="checked";
	$divtpldb[$DivTpl]="checked";
	$Cctype[$ctype]="checked";
	$_hidefid[intval($hidefid)]=" checked ";
	$fiddb=explode(",",$codedb[fiddb]);
	
	$c_rolltype || $c_rolltype=0;
	$newhour	|| $newhour=24;
	$hothits	|| $hothits=100;

	$titlenum2			|| $titlenum2=40;
	$content_num2		|| $content_num2=120;
	$rolltime			|| $rolltime=3;

	$_rolltype[$rolltype]=' selected ';

	$c_rolltypedb[$c_rolltype]=" checked ";

 	$select_news=$Guidedb->Checkbox("{$pre}business_sort",'fiddb[]',$fiddb);
	
	$tplpart_1=str_replace("&nbsp;","&amp;nbsp;",$tplpart_1);
	$tplpart_2=str_replace("&nbsp;","&amp;nbsp;",$tplpart_2);
 
	$getLabelTpl=getLabelTpl($inc);
	
	
	require("head.php");
	require("template/label/business.htm");
	require("foot.php");
}
?>