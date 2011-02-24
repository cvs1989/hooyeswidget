<?php
/**
测试版时间限制
**/
if(!$timestamp){
	die("致命错误，请不要更改某些系统变量；");
}




function getTpl($html,$tplpath=''){
	global $STYLE;
	if($tplpath&&file_exists($tplpath)){
		return $tplpath;
	}elseif($tplpath&&file_exists(Mpath.$tplpath)){
		return Mpath.$tplpath;
	}elseif(file_exists(Mpath."template/$STYLE/$html.htm")){
		return Mpath."template/$STYLE/$html.htm";
	}else{
		return Mpath."template/default/$html.htm";
	}
}


/**
*获取焦点信息内容
**/
function Get_AdInfo($sortid=0,$rows=10,$leng=40,$cityid='city'){
	global $db,$_pre,$timestamp,$city_id;

	$cityid=='city' && $cityid=$city_id;
	$cityid>0 && $SQL =" AND cityid = '$cityid' ";

	$query = $db->query("SELECT * FROM {$_pre}buyad WHERE sortid='$sortid' AND endtime>$timestamp $SQL ORDER BY money DESC,aid DESC LIMIT $rows");
	while($rs = $db->fetch_array($query)){
		$iddb[]=$rs[id];
	}
	if($iddb){
		$stringid=implode(",",$iddb);
		$SQL="WHERE A.yz=1 AND A.id IN ($stringid)";
		$_listdb=list_content($SQL,$leng);
		foreach( $_listdb AS $key=>$rs){
			$array[$rs[id]]=$rs;
		}
		foreach( $iddb AS $key=>$value){
			$listdb[]=$array[$value];
		}
		return $listdb;
	}
}

/**
*主要提供给城市,区域,地段的选择使用
**/
function select_where($type,$name='fup',$ck='',$fup=''){
	global $db,$area_DB,$city_DB;

	if($type=='province'){
		foreach( $area_DB[name] AS $key=>$value){
			$ckk=$ck==$key?" selected ":" ";
			$show.="<option value='$key' $ckk>$value</option>";
		}
		return "<select id='ID_$type' name=$name><option value='' style='color:#898989'>所有省份</option>$show</select>";
	}
	
	if($type=='city'){
		foreach( $city_DB[$fup] AS $key=>$value){
			$ckk=$ck==$key?" selected ":" ";
			$show.="<option value='$key' $ckk>$value</option>";
		}
		return "<select id='ID_$type' name=$name><option value='' style='color:#898989'>所有城市</option>$show</select>";
	}
	
}
function select_whereV2($type,$name='fup',$ck='',$fup='',$max=10,$fid=10){
	global $db,$area_DB,$city_DB;
    $j=0;
	if($type=='province'){
		foreach( $area_DB[name] AS $key=>$value){
		    if($j>=$max){ break;}
			$ckk=$ck==$key?" selected ":" ";
			$show.="&nbsp;&nbsp;<a href='clist.php?listarea_fup=$key&fid=$fid&ctype=3'>$value</a>";
			$j++;
		}
		return "$show";
	}
	
	if($type=='city'){
		foreach( $city_DB[$fup] AS $key=>$value){
			$ckk=$ck==$key?" selected ":" ";
			$show.="<option value='$key' $ckk>$value</option>";
		}
		return "<select id='ID_$type' name=$name><option value='' style='color:#898989'>所有城市</option>$show</select>";
	}
	
}



/**
*获得品牌下拉
**/
function select_brand($selectName='brand',$selectId='brand',$ckbid='',$fid=''){
	
	global $Brand_db;
	
	if($Brand_db){
		foreach($Brand_db[0] as $bid=>$name){
			
			$ownonlyfid=explode(",",$Brand_db[ownbyfid][$bid]);
			if(!$fid || in_array($fid,$ownonlyfid)){  //要嘛就没有FID限制,要嘛就存在
				$ck=$bid==$ckbid?" selected":"";
				$show.="<option value='$bid' $ck style='color:#000'>&nbsp;+&nbsp;$name</option>";
			}
			
			foreach($Brand_db[$bid] as $bid2=>$name2){
				
				$ownonlyfid=explode(",",$Brand_db[ownbyfid][$bid2]);
				if(!$fid || in_array($fid,$ownonlyfid)){
					$ck2=$bid2==$ckbid?" selected":"";
					$show.="<option value='$bid2' $ck2 style='color:#678'>&nbsp;|----$name $name2</option>";
				}
			}

		}	
	}

	return "<select id='$selectId' name=$selectName><option value=''>请选择品牌</option>$show</select>";
}
/**
*获取用户的来源城市
**/
function get_area($ip){
	global $city_DB;
	require_once(Mpath."inc/ip.php");
	$area=ip_address($ip);
	foreach( $city_DB[name] AS $key2=>$value2)
	{
		$value2=str_replace("市","",$value2);
		$value2=str_replace("区","",$value2);
		$value2=str_replace(" ","",$value2);
		if(strstr($area,$value2)){
			return $key2;
		}
	}
}


/**
*手机号码查询
**/
function get_mob_area($mob){
	$mob=substr($mob,0,7);
	$string=read_file(Mpath."inc/mobilebook.dat");
	$string=strstr($string,$mob);
	$num=strpos($string,"\n");
	$end=substr($string,0,$num);
	list($a,$area)=explode(",",$end);
	return $area;
}

/**
**通过子栏目得到全栏目
**/
function getFidAll($fid){
	global $Fid_db;
	if(!$fid) return '';	
	if($Fid_db[fup][$fid]>0){
		$fid_all=getFidAll($Fid_db[fup][$fid]).",".$fid;
	}else{
		$fid_all=$Fid_db[fup][$fid].",".$fid;
	}
	return $fid_all;
}
/**
*得到完整路径
**/
function GuideFid($fid_all,$url='list.php?'){
	global $Fid_db,$Mdomain;
	$array=explode(",",$fid_all);
	foreach($array as $key){
		if($key>0){
			$guide.=" &gt; <a href='$Mdomain/{$url}&fid=$key' />{$Fid_db[name][$key]}</a>";
		}
	}
	return $guide;
}
/**
**通过子栏目得到全栏目news
**/
function getNewsFidAll($fid){
	global $newsFid_db;
	if(!$fid) return '';	
	if($newsFid_db[fup][$fid]>0){
		$fid_all=getNewsFidAll($newsFid_db[fup][$fid]).",".$fid;
	}else{
		$fid_all=$newsFid_db[fup][$fid].",".$fid;
	}
	return $fid_all;
}
/**
*得到完整路径news
**/
function GuideNewsFid($fid_all,$url='?'){
	global $newsFid_db;
	$array=explode(",",$fid_all);
	foreach($array as $key){
		if($key>0){
			$guide.=" &gt; <a href='{$url}&fid=$key' />{$newsFid_db[name][$key]}</a>";
		}
	}
	return $guide;
}
/**
*过滤HTMLJS 标记
**/
function ReplaceHtmlAndJs($document)
{
 $document = trim($document);
 if (strlen($document) <= 0)
 {
  return $document;
 }
 $search = array ("'<script[^>]*?>.*?</script>'si",  // 去掉 javascript
                  "'<[\/\!]*?[^<>]*?>'si",          // 去掉 HTML 标记
                  "'([\r\n])[\s]+'",                // 去掉空白字符
                  "'&(quot|#34);'i",                // 替换 HTML 实体
                  "'&(amp|#38);'i",
                  "'&(lt|#60);'i",
                  "'&(gt|#62);'i",
                  "'&(nbsp|#160);'i"
                 );                    // 作为 PHP 代码运行

 $replace = array ("",
                   "",
                   "\\1",
                   "\"",
                   "&",
                   "<",
                   ">",
                   " "
                  );

 return @preg_replace($search, $replace, $document);
}


/**
得到认证标识
**/
function getrenzheng($re)
{
	global $Murl,$STYLE;
	if($re==1){
		return "<img src='{$Murl}/images/{$STYLE}/jibenrenzheng.gif'  border='0'/>";
	}elseif($re==2){
		return "<img src='{$Murl}/images/{$STYLE}/yinpairenzheng.gif'  border='0'/>";
	}elseif($re==3){
		return "<img src='{$Murl}/images/{$STYLE}/jinpairenzheng.gif'  border='0'/>";
	}else{
		return "<img src='{$Murl}/images/{$STYLE}/meirenzheng.gif'  border='0'/>";
	}
}


function get_services($array){
	global $Mdomain,$timestemp,$Murl;
	if($array[is_agent]) $str.="<a href='$Mdomain/homepage.php?uid=$array[uid]&m=agent' target=_blank><img src='$Murl/images/default/is_agent.gif' border=0></a>&nbsp;";
	if($array[is_vip]>$timestemp && $array[is_vip]>0) $str.="<img src='$Murl/images/default/is_vip.gif' border=0>&nbsp;";
	return $str;
}
/**
*格式化价格
**/
function formartprice($prc)
{
	if($prc<0) return "0.00";
	return number_format($prc,2,'.','');
}


/**
*获取信息内容
**/
function Get_Info($type,$rows=5,$leng=20,$fid=0,$mid=0,$ctype=1,$bid=0){
	global $bcategory,$city_id,$zone_id,$street_id,$webdb,$timestamp;
	if($mid>0){
		$SQL=" AND A.mid='$mid' ";
	}
	if($fid){
		$fidstring="$fid";
		foreach( $bcategory->categories[$fid]['categories'] AS $v){
			$fidstring .=",{$v['fid']}";
		}
		if($fidstring){
			$SQL .=" AND A.fid IN ($fidstring) ";
		}
	}
	
	if($bid){
		$SQL .=" AND A.bid ='$bid' ";
	}

	if($webdb[Info_UseEndtime]){
		$SQL .=" AND (A.endtime=0 OR A.endtime>$timestamp) ";
	}
	
	if($type=='hot'){
		$SQL="WHERE A.yz=1 $SQL ORDER BY A.hits DESC LIMIT $rows";// USE INDEX (A.hits) 
	}elseif($type=='lastview'){
		$SQL=" WHERE A.yz=1 $SQL ORDER BY A.lastview DESC LIMIT $rows";// USE INDEX (A.lastview)
	}elseif($type=='new'){
		$SQL=" WHERE A.yz=1 $SQL ORDER BY A.posttime DESC LIMIT $rows";// USE INDEX (A.list)
	}elseif($type=='level'){
		$SQL="WHERE A.yz=1 AND A.levels=1 $SQL ORDER BY A.list DESC LIMIT $rows";
	}elseif($type=='pic'){
		$SQL="WHERE A.yz=1 AND A.picurl<>'' $SQL ORDER BY A.list DESC LIMIT $rows";
	}else{
		return false;
	}
	
	$listdb=list_content($SQL,$leng,$ctype);
	return $listdb;
}

/**
*获取信息内容
**/
function list_content($SQL,$leng=40,$ctype=1){
	global $db,$_pre,$Mdomain,$webdb;
	if($ctype == 1){
		$query=$db->query("SELECT A.title,A.fname,A.posttime,A.picurl,A.titlecolor,A.fonttype,A.uid,A.id,A.fid,A.ctype,A.hits,A.my_price,B.quantity_type FROM {$_pre}content_sell A INNER JOIN {$_pre}content_1 B on B.id=A.id $SQL  ");
	
	}else if($ctype == 2){
		$query=$db->query("SELECT A.title,A.fname,A.posttime,A.picurl,A.titlecolor,A.fonttype,A.uid,A.id,A.fid,A.ctype,A.hits,A.my_price,B.quantity_type FROM {$_pre}content_buy A INNER JOIN {$_pre}content_2 B on B.id=A.id $SQL  ");
	}
	while( $rs=$db->fetch_array($query) ){
		
		$rs[title]=get_word($rs[full_title]=$rs[title],$leng);
		$rs[posttime]=date("Y-m-d",$rs[full_time]=$rs[posttime]);
		if($rs[picurl]){
			$rs[picurl]=getimgdir($rs[picurl],$rs[ctype]);
			}
		if($rs[ctype]==1)	$rs[ctypename] ="供应";
		elseif($rs[ctype]==2)	$rs[ctypename] ="求购";
		else $rs[ctypename] ="";

		if($rs[my_price]){
				$rs[my_price]=formartprice($rs[my_price]);
				$rs[my_price]="<strong><font color=#FF3300>$rs[my_price]</font></strong>元/$rs[quantity_type]";
		}else{
				$rs[my_price]='价格面议';
		}

		if($webdb[bencandyIsHtml] && $rs[htmlname] && file_exists(PHP168_PATH.$rs[htmlname]) ){
			$rs[url]=$webdb[www_url]."/".$rs[htmlname];
		}else{
			$rs[url]=$Mdomain."/".($ctype == 1 ? 'sell' : 'buy')."_bencandy.php?fid=$rs[fid]&id=$rs[id]";
		}
		
		//$rs[area]="{$area_DB[name][$city_DB[fup][$rs[city_id]]]}/{$city_DB[name][$rs[city_id]]}";
		$listdb[]=$rs;
	}
	return $listdb;
}

/*
*得到图片目录
*/
function getimgdir($img,$ctype=1){
global $webdb,$Imgdirname;
	
		
	   if($ctype=="company"){
			return $webdb[www_url].'/'.$webdb[updir].'/'.$Imgdirname.'/ico/'.$img;
		}elseif($ctype=='brand'){
			return $webdb[www_url].'/'.$webdb[updir].'/brand/'.$img;
		}else{
			return $webdb[www_url].'/'.$webdb[updir].'/'.$Imgdirname.'/'.$img;
		}
	
	return "";
}


/**
得到商家列表
**/
function get_companylist($fid=0,$levels=0,$rows=10,$leng=50,$content_leng=0,$order=" order by posttime desc "){
	global $db,$_pre, $bcategory;
	$SQL=" WHERE yz=1";
	if($fid){
		$fid_join = " INNER JOIN {$_pre}company_fid sf ON A.rid = sf.cid ";
		$fid_str = $fid;
		if(isset($bcategory->categories[$fid]['categories'])){
			$fid = $fid .','. implode(',', $bcategory->get_children_ids($fid));
		}
		$SQL.=" AND sf.fid IN($fid_str) ";
	}
	
	if($levels){
		$SQL.=" AND levels='1'";
		$order=" ORDER BY renzheng DESC";
	}
	if($content_leng){
		 $cols=" , content";
	}
	$query=$db->query("SELECT rid,title,fname,posttime,picurl,levels,uid $cols FROM {$_pre}company A $SQL $order  limit 0,$rows");
	$query=$db->query("SELECT rid,title,fname,posttime,picurl,levels,uid $cols FROM {$_pre}company  $SQL $order  limit 0,$rows");
	while( $rs=$db->fetch_array($query) ){
		
		$rs[title]=get_word($rs[full_title]=$rs[title],$leng);
		$rs[posttime]=date("Y-m-d",$rs[full_time]=$rs[posttime]);
		$rs[posttime_short]=date("m/d",$rs[full_time]);
		$rs[fname]=get_word($rs[full_fname]=$rs[fname],8,0);
		if($rs[picurl]){
			$rs[picurl]=getimgdir($rs[picurl],3);
		}
		if($content_leng>0){
			$rs[content]=get_word(ReplaceHtmlAndJs($rs[content]),$content_leng,0);
		}
		$listdb[]=$rs;
	}
	return $listdb;
}
function get_companylistV2($fid=0,$levels=0,$rows=10,$leng=50,$content_leng=0,$order=" order by posttime desc "){
	global $db,$_pre, $bcategory;
	$SQL=" WHERE yz=1";
	if($fid){
		$fid_join = " INNER JOIN {$_pre}company_fid sf ON A.rid = sf.cid ";
		$fid_str = $fid;
		if(isset($bcategory->categories[$fid]['categories'])){
			$fid = $fid .','. implode(',', $bcategory->get_children_ids($fid));
		}
		$SQL.=" AND sf.fid IN($fid_str) ";
	}
	
	if($levels){
		$SQL.=" AND levels='1'";
		$order=" ORDER BY renzheng DESC";
	}
	if($content_leng){
		 $cols=" , content";
	}
	$querySQL="SELECT rid,title,fname,posttime,picurl,levels,uid,qy_pro_ser,qy_website $cols FROM {$_pre}company A $SQL $order  limit 0,$rows";
	if($fid){
	$querySQL="SELECT rid,title,fname,posttime,picurl,levels,uid,qy_pro_ser,qy_website $cols FROM {$_pre}company A $fid_join $SQL $order  limit 0,$rows";
	}
	
	$query=$db->query($querySQL);
	
	//$query=$db->query("SELECT rid,title,fname,posttime,picurl,levels,uid $cols FROM {$_pre}company  $SQL $order  limit 0,$rows");
	while( $rs=$db->fetch_array($query) ){
		
		$rs[title]=get_word($rs[full_title]=$rs[title],$leng);
		$rs[posttime]=date("Y-m-d",$rs[full_time]=$rs[posttime]);
		$rs[posttime_short]=date("m/d",$rs[full_time]);
		$rs[fname]=get_word($rs[full_fname]=$rs[fname],8,0);
		$rs[qy_pro_ser]=get_word($rs[full_qy_pro_ser]=$rs[qy_pro_ser],50);
		if($rs[picurl]){
			$rs[picurl]=getimgdir($rs[picurl],3);
		}
		if($content_leng>0){
			$rs[content]=get_word(ReplaceHtmlAndJs($rs[content]),$content_leng,0);
		}
		$listdb[]=$rs;
	}
	return $listdb;
}


/*
*以下_人才招聘用****************************************************************************************************
*/
//得到职位的列表
function getzhiweilist($hr_sid=0,$hot=0,$rows=8,$leng=25)
{
	global $db,$_pre,$area_DB,$city_DB;
	$listdb=array();
	$where=" where is_check=1 ";
	if(strpos($hr_sid,':') !== false){
		$ids=explode(":",$hr_sid);
		$hr_sid=$ids[0];
		$uid=$ids[1];
	}

	if($hr_sid>0 && is_numeric($hr_sid)){ $where.=" and concat(',',`sid_all`,',') like('%,$hr_sid,%') ";}
	if($hot) $where.=" and `best`=1 ";
	if($uid) $where.=" and `uid`=".intval($uid);
	
	
	$query=$db->query("select * from {$_pre}hr_jobs $where order by `best` desc,posttime desc limit 0,$rows");
	while($rs=$db->fetch_array($query)){
		$rs[posttime]=date('Y-m-d',$rs[posttime]);
		$rs[posttime_full]=date('Y-m-d H:i:s',$rs[posttime]);
		$rs[title]=get_word($rs[title_full]=$rs[title],$leng);
		$city=explode(",",$rs[city]);
		$rs[cityname]=$area_DB[name][$city[0]]." ".$city_DB[name][$city[1]];
		$rs[companyname]=$rs[companyname]?get_word($rs[companyname],$leng):"&nbsp;";
		$joblistdb[]=$rs;

	}
	return $joblistdb;

}
//得到简历列表
function getrencailist($hr_sid=0,$hot=0,$rows=8,$leng=25)
{
	global $db,$_pre,$area_DB,$city_DB;
	$listdb=array();
	$where=" where  is_check=1 ";
	if($hr_sid>0 && is_numeric($hr_sid)){$where.=" and concat(',',`sid_all`,',') like('%,$hr_sid,%') ";}
	if($hot) $where.=" and `best`=1 ";
	
	$query=$db->query("select * from {$_pre}hr_resume $where limit 0,$rows");
	while($rs=$db->fetch_array($query)){
		$rs[posttime]=date('Y-m-d',$rs[posttime]);
		$rs[posttime_full]=date('Y-m-d H:i:s',$rs[posttime]);
		$city=explode(",",$rs[city]);
		$rs[cityname]=$area_DB[name][$city[0]]." ".$city_DB[name][$city[1]];
		$joblistdb[]=$rs;

	}
	return $joblistdb;

}

//生成人力资源模块的自定义字段视图,参数是一个key对应值得一维数组
function create_jobData_view($choose_to,$value=array())
{
	global $jobData,$STYLE, $Murl;
	$html="<!-- 自定义人力资源模型 start-->\r\n";
	foreach($jobData as $key=>$array){
		if($array[choose_to]=='0' || $array[choose_to]==$choose_to ){
					
			$html.="<tr> <!-- $array[name] -->\r\n";
			$html.="    <td  class='cols_name'><img src='$Murl/images/$STYLE/arrow_list.gif' />$array[name]：</td>\r\n";
			$showvalue="";
			if(is_array($value[$key])){
				$showvalue=nl2br(join(" ",$value[$key]));
			}else{
				$showvalue=nl2br($value[$key]);
			}
						
			$html.="   <td  class='cols_value'>{$showvalue}&nbsp;</td>\r\n";
			$html.="  </tr>";
		
		}
	}
	
	return $html."<!-- 自定义人力资源模型 end-->\r\n";		
	
}

//生成人力资源模块的自定义字段表单,参数是一个key对应值得一维数组,值为选中项目
function create_jobData_form($choose_to,$value=array(),$nolimit=false)
{
	global $jobData;
	$html="<!-- 自定义人力资源模型 start-->\r\n";
	foreach($jobData as $key=>$array){
		if($array[choose_to]=='0' || $array[choose_to]==$choose_to ){
					
			$html.="<tr> <!-- $array[name] -->\r\n";
			$html.="    <td align=right>$array[name]：</td>\r\n";
			$forminput="";
			if($array[form_type]=='select'){
				
				$optons=explode("\n",$array[value]);
				if($nolimit)  $optons[]="不限";
				$forminput="   <select name=other_data[$key]>\r\n";
				foreach($optons as $option){
					$option=trim($option);
					$forminput.="       <option value='$option' ".($option==$value[$key]?" selected":"").">$option</option>\r\n";
				}
				
				$forminput.="  </select>";
				
			}elseif($array[form_type]=='checkbox'){
			
				$optons=explode("\n",$array[value]);
				if($nolimit)  $optons[]="不限";
				foreach($optons as $option){
					$option=trim($option);
					$forminput.="       <input name='other_data[$key][]' type='checkbox' value='$option' ".(in_array($option,$value[$key])?" checked":"").">$option &nbsp;\r\n";
				}
						
			}elseif($array[form_type]=='radio'){
			
				$optons=explode("\n",$array[value]);
				if($nolimit)  $optons[]="不限";
				foreach($optons as $option){
					$option=trim($option);
					$forminput.="       <input name='other_data[$key]' type='radio' value='$option' ".($option==$value[$key]?" checked":"").">$option &nbsp;\r\n";
				}
			
			}elseif($array[form_type]=='text'){
				
				$forminput="    <input type='text' name='other_data[$key]' value='$value[$key]' >";
			
			}elseif($array[form_type]=='textarea'){
			
				$forminput="    <textarea  name='other_data[$key]'>$value[$key]</textarea>";
			
			}
			$html.="   <td align=left>{$forminput}&nbsp;<br>{$array[remarks]}</td>\r\n";
			$html.="  </tr>";
		
		}
	}
	
	return $html."<!-- 自定义人力资源模型 end-->\r\n";
}
//组合商家招聘时候的联系方式
function create_contact($companyinfo){

	$html ="联系人：".$companyinfo[qy_contact].($companyinfo[qy_contact_zhiwei]?"($companyinfo[qy_contact_zhiwei])":"");
	$html.="\r\n"."电 &nbsp;话：".$companyinfo[qy_contact_tel].($companyinfo[qy_contact_mobile]?" ".$companyinfo[qy_contact_mobile]:"");
	$html.=($companyinfo[qy_contact_fax]?"\r\n"."传 &nbsp;真：".$companyinfo[qy_contact_fax]:"");
	$html.="\r\n"."邮 &nbsp;箱：".$companyinfo[qy_contact_email];
	$html.="\r\n"."主 &nbsp;页：".$companyinfo[qy_website];
    if($companyinfo[qq]) $html.="\r\n"."QQ：".str_replace(","," ",$companyinfo[qq]);
	if($companyinfo[msn]) $html.="\r\n"."MSN：".str_replace(",","",$companyinfo[msn]);
	if($companyinfo[skype]) $html.="\r\n"."skype：".str_replace(",","",$companyinfo[skype]);
	return $html;
}



////展会频道用
function list_zh($data=array()){
	global $db,$_pre,$Fid_db,$area_DB,$city_DB,$webdb;
	$where=" WHERE A.yz=1";
	if($data['sid']) $where .=" and A.sid='$data[sid]' ";
	if($data[province_id]) $where.=" and A.province_id='$data[province_id]'";
	if($data[city_id]) $where.=" and A.city_id='$data[city_id]'";
	if($data[showroom]) $where.=" and showroom='$data[showroom]' ";
	if($data[starttime]){ 
	$starttime = explode("-",$data[starttime]);
	$yue_chu=mktime(0,0,0,$starttime[1],1,$starttime[0]);
	$yue_di=mktime(0,0,0,$starttime[1],31,$starttime[0]);
	$where.=" and (A.starttime > '$yue_chu' and A.starttime < '$yue_di')";
	}
	if($data[keyword]) $where.=" and (A.title like('%$data[keyword]%')    or   A.showroom_name like('%$data[keyword]%'))";
	if($data[pic]) $where.=" and A.picurl <> '' ";
	
	if($data[levels]){ 
		if($data[levels]=='pic') $where.="and  A.levels_pic=1";
		else $where.="and  A.levels=1";
	}
	if(!$data[orderlist]) $order=" order by A.levels desc,A.starttime desc"; else $order=$data[orderlist];
	if(!$data[limit]) $limit=" limit 0,10"; else $limit=" limit 0,$data[limit]";
	if(!$data[titlelength]) $data[titlelength]=40; 
	if(!$data[contentlength]) $data[contentlength]=200; 
	
	$query=$db->query("select * from {$_pre}zh_content A $where $order $limit");
	
	while($rs=$db->fetch_array($query)){
		$rs[posttime] =date("Y-m-d",$rs[posttime]);
		$rs[starttime]=date("Y-m-d",$rs[starttime]);
		$rs[endtime]  =date("Y-m-d",$rs[endtime]);
		$rs[title]    =get_word($rs[title_full]=$rs[title],$data[titlelength]);
		$rs[title]    =$rs[color]?"<font color='$rs[color]'>$rs[title]</font>":$rs[title];
		$rs[title]    =$rs[levels]||$rs[levels_pic]?"<b>$rs[title]</b>":$rs[title];
		$rs[showroom_name]    =get_word($rs[showroom_name_full]=$rs[showroom_name],$data[titlelength]);
		$rs[content]  =get_word($rs[content],$data[contentlength]);
		if($rs[picurl]) 	$rs[picurl]   =$webdb[www_url]."/".$webdb[updir]."/zh/".$rs[picurl];
		$rs[area]     =$area_DB[name][$rs[province_id]]." ".$city_DB[name][$rs[city_id]];
		$listdb[]=$rs;
	}
	return $listdb;
}
/**
整理一个可以发送欢迎界面的HTML内容
**/
function get_reg_sent_html($username,$uid){
	return "欢迎$username,注册；";


}
/*
*简单发送邮件(无提示发送)
*/
function easy_sent_email($uid,$title,$content){
	global $db,$webdb,$pre;
	if(!$id || !$title || !$content) return false;
	
	if($webdb[MailType]=='smtp')
	{
		if(!$webdb[MailServer]||!$webdb[MailPort]||!$webdb[MailId]||!$webdb[MailPw])
		{
			return false;
		}
		require_once(PHP168_PATH."inc/class.mail.php");
		$smtp = new smtp($webdb[MailServer],$webdb[MailPort],true,$webdb[MailId],$webdb[MailPw]);
		$smtp->debug = false;
	}
	$rs = $db->get_one("SELECT email FROM {$pre}memberdata WHERE uid=$uid LIMIT 1");
	if(!$email) return false;

	if($webdb[MailType]=='smtp'){
		$smtp->sendmail($rs[email],$webdb[MailId], $title, $content, "HTML");			
	}else{
		@mail($rs[email], $title, $content);
	}
	return true;
}

/**
*上传文件
**/
function upfile_func2($upfile,$array){
	global $db,$lfjuid,$pre,$webdb,$groupdb,$lfjdb;

	$filename=$array[name];

	$path=makepath(PHP168_PATH.$array[path]);

	if($path=='false')
	{
		return "ERR-不能创建目录$array[path]，上传失败";
	}
	elseif(!is_writable($path))
	{
		return "ERR-目录不可写$path";
	}

	$size=abs($array[size]);

	$filetype=strtolower(strrchr($filename,"."));

	if(!$upfile)
	{
		return  "ERR-文件不存在，上传失败";
	}
	elseif(!$filetype)
	{
		return "ERR-文件不存在，或文件无后缀名,上传失败";
	}
	else
	{
		if($filetype=='.php'||$filetype=='.asp'||$filetype=='.aspx'||$filetype=='.jsp'||$filetype=='.cgi'){
			return  "ERR-系统不允许上传可执行文件,上传失败" ;
		}

		if( $groupdb[upfileType] && !in_array($filetype,explode(" ",$groupdb[upfileType])) )
		{
			return  "ERR-你所上传的文件格式为:$filetype,而你所在用户组仅允许上传的文件格式为:$groupdb[upfileType]" ;
		}
		elseif( !in_array($filetype,explode(" ",$webdb[upfileType])) )
		{
			return  "ERR-你所上传的文件格式为:$filetype,而系统仅允许上传的文件格式为:$webdb[upfileType]";
		}

		if( $groupdb[upfileMaxSize] && ($groupdb[upfileMaxSize]*1024)<$size )
		{
			return "ERR-你所上传的文件大小为:".($size/1024)."K,而你所在用户组仅允许上传的文件大小为:{$groupdb[upfileMaxSize]}K";
		}
		if( !$groupdb[upfileMaxSize] && $webdb[upfileMaxSize] && ($webdb[upfileMaxSize]*1024)<$size )
		{
			return  "ERR-你所上传的文件大小为:".($size/1024)."K,而系统仅允许上传的文件大小为:{$webdb[upfileMaxSize]}K";
		}
	}
	$oldname=preg_replace("/(.*)\.([^.]*)/is","\\1",$filename);
	if(eregi("(.jpg|.png|.gif)$",$filetype)){
		$tempname="{$lfjuid}_".date("YmdHms_",time()).rands(5).$filetype;
	}else{
		$tempname="{$lfjuid}_".date("YmdHms_",time()).base64_encode(urlencode($oldname)).$filetype;
	}
	$newfile="$path/$tempname";
	if(@move_uploaded_file($upfile,$newfile))
	{
		@chmod($newfile, 0777);
		$ck=2;
	}
    if(!$ck)
	{
		if(@copy($upfile,$newfile))
		{
			@chmod($newfile, 0777);
			$ck=2;
		}
	}
	if($ck)
	{
		if($array[updateTable])
		{
			if(($array[size]+$lfjdb[usespace])>($webdb[totalSpace]*1048576+$groupdb[totalspace]*1048576+$lfjdb[totalspace])){
				unlink($newfile);
				return "ERR-你的空间不足,上传失败";
			}

			$db->query("UPDATE {$pre}memberdata SET usespace=usespace+'$size' WHERE uid='$lfjuid' ");
		}
		return $tempname;
	}
	else
	{
		return "ERR-请检查空间问题,上传失败";
	}
}

//得到新的供应关系
function getnewvendorlist($rows=4,$length=20){
	global $db,$_pre;	

	$query=$db->query("select A.*,B.title,C.title  as ownere_title from {$_pre}vendor A left join {$_pre}company B on B.rid=A.rid  left join  {$_pre}company C on C.rid=A.owner_rid   where A.yz=1 order by A.yztime desc limit 0,$rows");
	while($rs=$db->fetch_array($query)){
		$rs[yztime]=date("m-d",$rs[yztime]);
		$rs[title]=get_word($rs[title],$length);
		
		$rs[ownere_title]=get_word($rs[ownere_title],$length);
		$newvendorlistdb[]=$rs;	
	}
	return $newvendorlistdb;
}
//得到急寻供应商的商家
function getwantvendorlist($rows=10,$length=40){
	global $db,$_pre,$timestamp;
	$query=$db->query("select * from {$_pre}vendor_want 
	where yz=1 and is_show=1 and starttime<$timestamp and endtime > $timestamp 
	order by is_levels desc,endtime asc limit 0,$rows");
	while($rs=$db->fetch_array($query)){
		$rs[tiaojian].=$rs[w_renzheng]?"认证用户 ":"";
		$rs[tiaojian].=$rs[w_agent]?"代理商 ":"";
		$rs[tiaojian].=$rs[w_vip]?"VIP商家 ":"";
		$rs[starttime]=date("Y-m-d H:i:s",$rs[starttime]);
		$rs[endtime]=date("Y-m-d H:i:s",$rs[endtime]);
		$rs[is_levels]=$rs[is_levels]?"<font color=red>荐</font>":"";
		$listdb[]=$rs;
	}

	return $listdb;
}



//得到交流在线图片
function getOnlinecontact($type,$number,$jiange=" ")
{
	global $webdb,$Mdomain;
	if(!$type) $type='qq';
	$number=explode(',',$number);
	$return="";
	foreach($number as $id){
		if($id!=''){
			if($type=='qq'){
				$return.='<a target="blank" href="http://wpa.qq.com/msgrd?V=1&Uin='.$id.'&Site='.$webdb[webname].'&Menu=yes"><img border="0" SRC=http://wpa.qq.com/pa?p=1:'.$id.':10 alt="点击这里与我联系" align="absmiddle"></a> '.$id.$jiange;
			}elseif($type=='msn'){
				$return.='<A HREF="msnim:chat?contact='.$id.'"><IMG SRC="'.$Mdomain.'/images/default/msg_ico.gif" align="absmiddle" border="0" ALT="MSN Online Status Indicator"> '.$id.'</A>'.$jiange;
			}elseif($type=='skype'){
				$return.='<a href="skype:'.$id.'?call" onclick="return skypeCheck();"><img src=http://mystatus.skype.com/smallclassic/'.$id.' style="border: none;" alt="Call me!" /></a> '.$id.$jiange;
			}elseif($type=='ww'){
				$return.='<a target="_blank" href="http://amos1.taobao.com/msg.ww?v=2&uid='.urlencode($id).'&s=2" ><img border="0" src="http://amos1.taobao.com/online.ww?v=2&uid='.urlencode($id).'&s=2" alt="点击这里给我发消息" /> '.$id.'</a>'.$jiange;
			}
		}
	}
	return $return;
}


/*
* 生成参数表单
*/
function parameters_postform($fid,$aid=0){
	global $db,$bcategory,$_pre;
	
	if(!$fid) return "";
	$mid=$bcategory->categories[$fid]['mid'];
	
	if($mid<1 || !$mid) return "";
	
	$rsdb=$db->get_one("SELECT config FROM `{$_pre}parameters_module` WHERE mid='$mid' ");
	
	$rsdb[config]=unserialize($rsdb[config]);

	if($aid){ //parameters
	
	$value=$db->get_one("SELECT para_name,para_value FROM `{$_pre}parameters` WHERE aid='$aid'");
	$value=unserialize($value[para_value]);
	//print_r($value);
	/*	
		$query=$db->query("select para_name,para_value from `{$_pre}parameters` where aid='$aid'");
		while($rs=$db->fetch_array($query)){
			$value[$rs[para_name]]=$rs[para_value];
		}
	*/

	}
	foreach($rsdb[config] as $rs){

		$str.='<div style="line-height:200%;padding:5px;"><b>'.$rs[name].':</b> &nbsp;'.parameters_cols($rs,$value[$rs[name]]).'<font color=#898989>'.$rs[remarks].'</font></div>';
	}
	
	return $str;

}

/*
* 字段表单项
*/
function parameters_cols($rs,$value){
	$str="";
	$rand=rand(100,999);
	if($rs[type]=='text'){

		$str.="<input name='parametersDB[".$rs[name]."]' type='input' value='$value' size=20 id='text_".$rand."'> ";

		$str.=makefastintput($rs[value],"text_".$rand);

	}elseif($rs[type]=='langtext'){

		$str.="<input name='parametersDB[".$rs[name]."]' type='input' value='$value' size=40  id='langtext_".$rand."'> ";

	}elseif($rs[type]=='textarea'){

		$value=$value?$value:$rs[value];
		$str.="<textarea name='parametersDB[".$rs[name]."]'  cols=30 rows=5 >$value</textarea> ";
		
	}elseif($rs[type]=='radio'){

		$rs[value]=explode("\r\n",$rs[value]);

		foreach($rs[value] as $v){
			$v=trim($v);
			$ckk=$v==trim($value)?" checked":"";
			if($v!='') $str.="<input type='radio' name='parametersDB[".$rs[name]."]' value='$v' $ckk>$v &nbsp;&nbsp;";
		}

	}elseif($rs[type]=='checkbox'){
		
		$rs[value]=explode("\r\n",$rs[value]);
		
		//$value=unserialize($value);		
		
		foreach($rs[value] as $v){
			$v=trim($v);
			$ckk=in_array($v,$value)?" checked":"";
			if($v!='') $str.="<input type='checkbox' name='parametersDB[".$rs[name]."][]' value='$v' $ckk>$v &nbsp;&nbsp;";
		}

	}elseif($rs[type]=='select'){
		
		$rs[value]=explode("\r\n",$rs[value]);
		$str.="<select name='parametersDB[".$rs[name]."]' ><option value='' style='color:#898989'>请 选 择</option>";
		foreach($rs[value] as $v){
			$v=trim($v);
			$ckk=$v==trim($value)?" selected":"";
			if($v!='') $str.="<option value='$v' $ckk>$v</option>";
		}
		$str.="</select>";
		
	}
	return $str;
}
/*
*生成快输入的下拉列表
*/
function makefastintput($value,$name='myname')
{
	if(trim($value)!='' && $name){
		$value=explode("\r\n",$value );
		if(count($value)){
				$str.="<select name='name$rand' onchange=\"if(this.options[this.selectedIndex].value!=''){document.getElementById('$name').value=this.options[this.selectedIndex].value;this.selectedIndex=0;}\" ><option value='' style='color:#898989'>快捷选择输入</option>";
				foreach($value as $v){
					$v=trim($v);
					if($v!='') $str.="<option value='$v'>$v</option>";
				}
				$str.="</select>";
		}
	}
	return $str;
}
/*
* 保存参数表单
*/
function parameters_savedata($fid,$id){
	global $db,$parametersDB,$_pre,$bcategory;

	if(!$fid || !$id) return false;
	
	$mid=$bcategory->categories[$fid]['mid'];
	if(!$mid) return false;

	if($parametersDB){
		$value=serialize($parametersDB);
		$have=$db->get_one("SELECT COUNT(*) AS num FROM `{$_pre}parameters` WHERE aid='$id'");
		if($have[num]>0){ 
			$db->query("UPDATE  `{$_pre}parameters`  SET `para_value`='$value'  WHERE aid='$id' ");
		}else{
			$db->query("INSERT INTO `{$_pre}parameters` ( `pdif` , `aid` , `fid` , `mid` , `para_name` , `para_value` )
VALUES ('', '$id', '$fid', '$mid', ' ', '$value');");
		}			
		/*
		$db->query("delete from `{$_pre}parameters` where aid='$id' ");
		foreach($parametersDB as $name=>$value){
			if(is_array($value)){
				$value=serialize($value);
			}
			$db->query("INSERT INTO `{$_pre}parameters` ( `pdif` , `aid` , `fid` , `mid` , `para_name` , `para_value` )
VALUES ('', '$id', '$fid', '$mid', '$name', '$value');");

		}
		*/

	}
	return true;

}
/*
* 删除参数表单
*/
function parameters_deldata($id){
	global $db,$parametersDB,$_pre;

	if(!$id) return false;
	$db->query("DELETE FROM `{$_pre}parameters` WHERE aid='$id' ");
	return true;

}
/*
*内容页提取参数
*/
function parameters_show($aid){
	global $db,$_pre;
	if($aid){
	
		$value=$db->get_one("SELECT para_name,para_value FROM `{$_pre}parameters` WHERE aid='$aid'");
		if($value){
			$value=unserialize($value[para_value]);
			if(count($value)>0){
				$str.="<table width='100%'cellspacing=\"1\" cellpadding=\"5\"><tr>";
				$i=1;
				foreach($value as $name => $val){
					if(is_array($val)) $val=implode(" ",$val);
					$str.="<td align=right width=100  >{$name}：</td><td width='auto'>$val</td>";
					if($i%2==0){
						$str.="</tr><tr>";
					}
					$i++;

				}
				$i--;
				if($i%2!=0){
					$str.="<td></td><td></td>";
				}
				$str.="</tr></table>";
			}
		}

	}
	$str=$str?$str:"暂无参数";
	return $str;
}

?>
