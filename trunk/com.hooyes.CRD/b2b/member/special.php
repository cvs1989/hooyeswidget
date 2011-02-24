<?php
require("global.php");
require(PHP168_PATH."inc/class.inc.php");
$Guidedb=new Guide_DB;
if(!$lfjuid){
	showerr("你还没有登录");
}

$linkdb=array(
			"专题管理"=>"?job=listsp",
			"创建专题"=>"?job=addsp",
			);

if($job=='addsp'&&!$web_admin)
{
	if($groupdb[CreatSpecialNum]<1){
		//$groupdb[CreatSpecialNum]=10;
		showerr("你所在用户组不能创建{$groupdb[CreatSpecialNum]}专题");
	}	
	$rs=$db->get_one("SELECT COUNT(*) AS NUM FROM `{$pre}special` WHERE uid='$lfjuid'");
	if($rs[NUM]>=$groupdb[CreatSpecialNum]){
		showerr("你所在用户组最多只能创建{$groupdb[CreatSpecialNum]}个专题",1);
	}
}

if($job=='editsp'||$job=='addsp'){
	if($step==2){
		if(!$postdb[title]){
			showerr("名称不能为空");
		}elseif(!$postdb[fid]){
			showerr("分类不能为空");
		}
		if(strlen($postdb[title])>150){
			showerr("名称不能大于150个字节");
		}elseif(strlen($postdb[content])>10000){
			showerr("内容不能大于10000个字节");
		}
		if($postdb[picurl]&&!eregi("(jpg|gif|png)$",$postdb[picurl])){
			showerr("封面只能是JPG,PNG,GIF格式的图片");
		}
		
		/*缩略图处理*/
		if( $postdb[picurl] && !strstr($postdb[picurl],"http://") )
		{
			//图片目录转移
			move_attachment($lfjdb[uid],tempdir($postdb[picurl]),"special/$postdb[fid]");
			if(file_exists(PHP168_PATH."$webdb[updir]/special/$postdb[fid]/".basename($postdb[picurl]))){
				$postdb[picurl]="special/$postdb[fid]/".basename($postdb[picurl]);
			}
			$water_info = getimagesize(PHP168_PATH."$webdb[updir]/$postdb[picurl]");
			if($webdb[if_gdimg]&&$water_info[0]>150)
			{
				gdpic(PHP168_PATH."$webdb[updir]/$postdb[picurl]",PHP168_PATH."$webdb[updir]/$postdb[picurl]",200,150);
			}
		}
		$postdb[title]		=	filtrate($postdb[title]);
		$postdb[content]	=	filtrate($postdb[content]);
		$postdb[picurl]		=	filtrate($postdb[picurl]);
		$postdb[banner]		=	filtrate($postdb[banner]);
		$postdb[allowpost]=@implode(",",$postdb[allowpost]);
	}	
}

if($job=='listsp')
{
	$rows=10;
	if($page<1){
		$page=1;
	}
	$min=($page-1)*$rows;
	$showpage=getpage("{$pre}special","WHERE uid='$lfjuid'","?job=listsp",$rows);
	$query = $db->query("SELECT S.*,F.name AS fname FROM {$pre}special S LEFT JOIN {$pre}spsort F ON S.fid=F.fid WHERE S.uid='$lfjuid' ORDER BY id DESC LIMIT $min,$rows");
	while($rs = $db->fetch_array($query)){
		$detail=explode(",",$rs[aids]);
		$rs[NUM]=count($detail);
		$rs[picurl]=tempdir($rs[picurl]);
		$rs[content]=get_word($rs[content],200);
		$rs[posttime]=date("Y-m-d H:i:s",$rs[posttime]);
		if($rs[yz]){
			$rs[_yz]="<img alt='已审核' src='images/check_yes.gif'>";
		}else{
			$rs[_yz]="<img alt='未审核' src='images/check_no.gif'></a>";
		}
		$listdb[]=$rs;
	}
	require(dirname(__FILE__)."/"."head.php");
	require(dirname(__FILE__)."/"."template/special/listsp.htm");
	require(dirname(__FILE__)."/"."foot.php");
}
elseif($job=="editsp")
{
	$rsdb=$db->get_one("SELECT * FROM {$pre}special WHERE uid=$lfjuid AND id='$id'");
	if($step==2){
		if($rsdb[picurl]&&$rsdb[picurl]!=$postdb[picurl]){
			delete_attachment($lfjdb[uid],$rsdb[picurl]);
		}
		$db->query("UPDATE {$pre}special SET title='$postdb[title]',fid='$postdb[fid]',picurl='$postdb[picurl]',content='$postdb[content]',allowpost='$postdb[allowpost]',banner='$postdb[banner]' WHERE uid=$lfjuid AND id='$id'");
		refreshto("special.php?job=listsp","修改成功",1);
	}
	$group_post=group_box("postdb[allowpost]",explode(",",$rsdb[allowpost]));
	$sort_fid=$Guidedb->Select("{$pre}spsort","postdb[fid]",$rsdb[fid],"");
	$MSG='修改专题';


	require(dirname(__FILE__)."/"."head.php");
	require(dirname(__FILE__)."/"."template/special/editsp.htm");
	require(dirname(__FILE__)."/"."foot.php");
}
elseif($job=='delsp')
{
	$rsdb=$db->get_one("SELECT * FROM {$pre}special WHERE uid='$lfjuid' AND id='$id'");
	delete_attachment($lfjdb[uid],$rsdb[picurl]);
	$db->query("DELETE FROM {$pre}special WHERE uid=$lfjuid AND id='$id'");
	refreshto("special.php?job=listsp","删除成功",1);
}
elseif($job=='addsp')
{
	if($step==2){
		$yz=($groupdb[PassContributeSP]||$web_admin)?1:0;
		$db->query("INSERT INTO `{$pre}special` ( `fid` , `title` , `keywords` , `style` , `template` , `picurl` , `content` , `aids` ,`uid` , `username` , `posttime` , `list`, `allowpost`, `yz`, `banner` ) VALUES ('$postdb[fid]','$postdb[title]','$keywords','$style','','$postdb[picurl]','$postdb[content]','$aids','$lfjuid','$lfjid','$timestamp','$timestamp','$postdb[allowpost]','$yz','$postdb[banner]')");
		refreshto("special.php?job=listsp","创建专辑成功",1);
	}
	$group_post=group_box("postdb[allowpost]",explode(",",$rsdb[allowpost]));
	$MSG='创建专辑';
	$sort_fid=$Guidedb->Select("{$pre}spsort","postdb[fid]",$rsdb[fid],"");
	require(dirname(__FILE__)."/"."head.php");
	require(dirname(__FILE__)."/"."template/special/editsp.htm");
	require(dirname(__FILE__)."/"."foot.php");
}
elseif($job=="edit_atc")
{
	$rsdb=$db->get_one("SELECT * FROM {$pre}special WHERE uid='$lfjuid' AND id='$id'");
	require(dirname(__FILE__)."/"."head.php");
	require(dirname(__FILE__)."/"."template/special/edit_atc.htm");
	require(dirname(__FILE__)."/"."foot.php");
}
elseif($job=="edit_bbs")
{
	$rsdb=$db->get_one("SELECT * FROM {$pre}special WHERE uid='$lfjuid' AND id='$id'");
	require(dirname(__FILE__)."/"."head.php");
	require(dirname(__FILE__)."/"."template/special/edit_bbs.htm");
	require(dirname(__FILE__)."/"."foot.php");
}
elseif($job=="show_iframe"){

	$rsdb=$db->get_one("SELECT * FROM {$pre}special WHERE uid='$lfjuid' AND id='$id'");
	if(!$rsdb){
		showerr("资料不存在",1);
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
		$db->query("UPDATE {$pre}special SET aids='$rsdb[aids]' WHERE uid='$lfjuid' AND id='$id'");
	}
	if($act=="add"&&$aid)
	{
		unset($_detail);
		$detail=explode(",",$rsdb[aids]);
		if(count($detail)>100){
			showerr("记录已到上限!",1);
		}
		if(!in_array($aid,$detail)){
			if($detail[0]==''){unset($detail[0]);}
			$_detail[a]=$aid;
			$rsdb[aids]=$string=implode(",",array_merge($_detail,$detail));
			$db->query("UPDATE {$pre}special SET aids='$string' WHERE uid='$lfjuid' AND id='$id'");
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
		$db->query("UPDATE {$pre}special SET aids='$string' WHERE uid='$lfjuid' AND id='$id'");
	}
	
	//列出本专题下的文章,$type=='all',初始化时.$type=="list_atc",增加文章或移除文章时
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
		$query = $db->query("SELECT * FROM {$pre}article WHERE aid IN ($string)");
		while($rs = $db->fetch_array($query)){
			$rs[subject]="<a href='../bencandy.php?fid=$rs[fid]&id=$rs[aid]' target=_blank>$rs[title]</a>";
			$_listdb[$rs[aid]]=$rs;
		}
		$aidsdb=explode(",",$rsdb[aids]);
		$NUM=0;
		foreach($aidsdb AS $key=>$value){
			$NUM++;
			if($_listdb[$value]){
				$show.="<tr align='center' class='trA' onmouseover=\"this.className='trB'\" onmouseout=\"this.className='trA'\"> 
                <td width='5%'>{$_listdb[$value][aid]}</td>
                <td width='74%' align='left'>{$_listdb[$value][subject]}</td>
					<td width='10%'><input type='text' name='listdb[{$value}]' size='5' value='{$NUM}0'></td>
                <td width='11%'><A HREF='special.php?job=show_iframe&id=$id&type=list_atc&act=del&aid={$_listdb[$value][aid]}' target='spiframe'>移除</A></td>
              </tr>";
			}
		}
		$show="<table width='100%' border='0' cellspacing='1' cellpadding='3'>
              <tr align='center' bgcolor='#eeeeee'> 
                <td width='5%'>ID</td>
                <td width='74%'>标 题</td>
				  <td width='10%'>排序值</td>
                <td width='11%'>移除 </td>
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

	//$type=='all',初始化时,$type=='myatc'筛选时
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
		
		if($search_type&&$keywords){//搜索时
			if($search_type=='title'){
				$SQL=" BINARY title LIKE '%$keywords%' ";
			}elseif($search_type=='keyword'){
				$SQL=" BINARY keywords LIKE '%$keywords%' ";
			}
		}elseif($ismy){//只列出我的文章
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
				$add="<A HREF='special.php?job=show_iframe&id=$id&type=list_atc&act=add&aid={$rs[aid]}' target='spiframe' onclick=closedo(this)>添加</A>";
			}
			$show.="<tr align='center' class='trA' onmouseover=\"this.className='trB'\" onmouseout=\"this.className='trA'\"> 
                <td width='5%'>{$rs[aid]}</td>
                <td width='84%' align='left'><A HREF='../bencandy.php?fid=$rs[fid]&id=$rs[aid]' target=_blank>{$rs[title]}</A></td>
                <td width='11%'>&nbsp;$add</td>
              </tr>";
		}
		$show="<table width='100%' border='0' cellspacing='1' cellpadding='3'>
              <tr align='center' bgcolor='#eeeeee'> 
                <td width='5%'>ID</td>
                <td width='84%'>标 题</td>
                <td width='11%' bgcolor='#eeeeee'>添加</td>
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
		$showpage=str_replace("href=&page=","target=spiframe href=special.php?job=show_iframe&id=$id&type=myatc&ismy=$ismy&search_type=$search_type&keywords=".urlencode($keywords)."&page=",$showpage);
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

		$sort_fid=str_replace("<select name='fid'","[<A target='spiframe'  HREF='special.php?job=show_iframe&id=$id&type=myatc&fid=$fid&ismy=1' style='color:$color_me;'>我的文章</A>] [<A target='spiframe'  HREF='special.php?job=show_iframe&id=$id&type=myatc&fid=$fid' style='color:$color_all;'>所有文章</A>]<select onChange='fid_jumpMenu(this)'",$sort_fid);
		echo "<SCRIPT LANGUAGE='JavaScript'>
		<!--
		parent.document.getElementById('show_myatc_fid').innerHTML=\"$sort_fid\";
		//-->
		</SCRIPT>";
	}
}
//论坛贴子
elseif($job=="show_BBSiframe"){

	$rsdb=$db->get_one("SELECT * FROM {$pre}special WHERE uid='$lfjuid' AND id='$id'");
	if(!$rsdb){
		showerr("资料不存在",1);
	}

	//专题内的贴子排序
	if($act=="order")
	{
		unset($array);
		foreach( $listdb AS $aid=>$list){
			$list=$list*1000000+$aid;
			$array[$list]=$aid;
		}
		ksort($array);
		$rsdb[tids]=implode(",",$array);
		$db->query("UPDATE {$pre}special SET tids='$rsdb[tids]' WHERE uid='$lfjuid' AND id='$id'");
	}
	
	//添加贴子到专题
	if($act=="add"&&$aid)
	{
		unset($_detail);
		$detail=explode(",",$rsdb[tids]);
		if(count($detail)>100){
			showerr("记录已到上限!",1);
		}
		if(!in_array($aid,$detail)){
			if($detail[0]==''){unset($detail[0]);}
			$_detail[a]=$aid;
			$rsdb[tids]=$string=implode(",",array_merge($_detail,$detail));
			$db->query("UPDATE {$pre}special SET tids='$string' WHERE uid='$lfjuid' AND id='$id'");
		}
	}

	//移除专题里的贴子
	if($act=="del"&&$aid)
	{
		$detail=explode(",",$rsdb[tids]);
		foreach( $detail AS $key=>$value){
			if($value==$aid){
				unset($detail[$key]);
			}
		}
		$rsdb[tids]=$string=implode(",",$detail);
		$db->query("UPDATE {$pre}special SET tids='$string' WHERE uid='$lfjuid' AND id='$id'");
	}
	
	//$type=='all'初始化列出专题里的贴子,$type=="list_atc"删除与添加时列出专题里的贴子
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
                <td width='5%'>{$_listdb[$value][tid]}</td>
                <td width='74%' align='left'>{$_listdb[$value][subject]}</td>
					<td width='10%'><input type='text' name='listdb[{$value}]' size='5' value='{$NUM}0'></td>
                <td width='11%'><A HREF='special.php?job=show_BBSiframe&id=$id&type=list_atc&act=del&aid={$_listdb[$value][tid]}' target='spiframe'>移除</A></td>
              </tr>";
			}
		}
		$show="<table width='100%' border='0' cellspacing='1' cellpadding='3'>
              <tr align='center' bgcolor='#eeeeee'> 
                <td width='5%'>ID</td>
                <td width='74%'>标 题</td>
				  <td width='10%'>排序值</td>
                <td width='11%'>移除 </td>
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
		if($keywords){//搜索时
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
					$add="<A HREF='special.php?job=show_BBSiframe&id=$id&type=list_atc&act=add&aid={$rs[tid]}' target='spiframe' onclick=closedo(this)>添加</A>";
				}
				$show.="<tr align='center' class='trA' onmouseover=\"this.className='trB'\" onmouseout=\"this.className='trA'\"> 
					<td width='5%'>{$rs[tid]}</td>
					<td width='84%' align='left'><a href='$webdb[passport_url]/read.php?tid=$rs[tid]' target=_blank>$rs[subject]</a></td>
					<td width='11%'>&nbsp;$add</td>
				  </tr>";
			}
		}elseif(ereg("^dzbbs",$webdb[passport_type])){
			while($rs = $db->fetch_array($query)){
				$add="&nbsp;";
				if(!in_array($rs[tid],$detail)){
					$add="<A HREF='special.php?job=show_BBSiframe&id=$id&type=list_atc&act=add&aid={$rs[tid]}' target='spiframe' onclick=closedo(this)>添加</A>";
				}
				$show.="<tr align='center' class='trA' onmouseover=\"this.className='trB'\" onmouseout=\"this.className='trA'\"> 
					<td width='5%'>{$rs[tid]}</td>
					<td width='84%' align='left'><a href='$webdb[passport_url]/viewthread.php?tid=$rs[tid]' target=_blank>$rs[subject]</a></td>
					<td width='11%'>&nbsp;$add</td>
				  </tr>";
			}
		}
		
		$show="<table width='100%' border='0' cellspacing='1' cellpadding='3'>
              <tr align='center' bgcolor='#eeeeee'> 
                <td width='5%'>ID</td>
                <td width='84%'>标 题</td>
                <td width='11%' bgcolor='#eeeeee'>添加</td>
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
		$showpage=str_replace("href=&page=","target='spiframe' href=special.php?job=show_BBSiframe&id=$id&type=myatc&ismy=$ismy&keywords=".urlencode($keywords)."&page=",$showpage);
		echo "<SCRIPT LANGUAGE='JavaScript'>
		<!--
		parent.document.getElementById('show_myatc_page').innerHTML=\"$showpage\";
		//-->
		</SCRIPT>";

		//论坛栏目
		$sort_fid=$Guidedb->Select_PW("fid",$fid);

		$sort_fid=str_replace("\r","",$sort_fid);
		$sort_fid=str_replace("\n","",$sort_fid);
		$sort_fid=str_replace('"',"",$sort_fid);
		$ismy?$color_me='red':$color_all='red';
		$sort_fid=str_replace("<select name='fid'","[<A target='spiframe'  HREF='special.php?job=show_BBSiframe&id=$id&type=myatc&fid=$fid&ismy=1' style='color:$color_me;'>我的贴子</A>] [<A target='spiframe'  HREF='special.php?job=show_BBSiframe&id=$id&type=myatc&fid=$fid' style='color:$color_all;'>所有贴子</A>] <select onChange='fid_jumpMenu(this)'",$sort_fid);
		echo "<SCRIPT LANGUAGE='JavaScript'>
		<!--
		parent.document.getElementById('show_myatc_fid').innerHTML=\"$sort_fid\";
		//-->
		</SCRIPT>";
	}	
}

function group_box($name="postdb[group]",$ckdb=array(),$type=''){
	global $db,$pre;
	if($type==1){
		$SQL=" WHERE gptype=1 AND gid NOT IN(2,3,4) ";
	}
	$query=$db->query("SELECT * FROM {$pre}group $SQL ORDER BY gid ASC");
	while($rs=$db->fetch_array($query))
	{
		$checked=in_array($rs[gid],$ckdb)?"checked":"";
		$show.="<input type='checkbox' name='{$name}[]' value='{$rs[gid]}' $checked>&nbsp;{$rs[grouptitle]}&nbsp;&nbsp;";
	}
	return $show;
}
?>