<?php
/**
*用户绑定图片用
**/
function bd_pics($table,$where){
	global $bd_pics_list,$db,$webdb,$lfjid,$lfjuid,$_pre;
	if(!$where) return false;
	
	//if(count($bd_pics_list)){
		$bd_pics_list=implode(",",$bd_pics_list);			
		$query=$db->query("update $table set bd_pics='$bd_pics_list' $where limit 1");
		return true;
	
	//}
	
}


/**
*展示用户绑定的图片
**/

function show_bd_pics($table,$where,$titlelength=0){
	
	global $db,$webdb,$lfjid,$lfjuid,$_pre,$user_picdir,$Mdomain,$Murl;
	
	if(!$where) return "";
	$rsdb=$db->get_one("select bd_pics from  $table  $where limit 1");
	if($rsdb[bd_pics]){
		$show="<div>
		<style>
/*
*网页对话狂
*/

.overlay {
	clear:both;
	position: absolute;
	z-index:999;
	top: 0px;
	left: 0px;
	width:100%;
	background-color:#000000;
	filter:alpha(opacity=50);
	-moz-opacity: 0.6;
	opacity: 0.6;
}
.overlay2 {
	clear:both;
	position: absolute;
	z-index:1000;
	width:200px;
	height:60px;
	border:#F4862C solid 5px;
	background:#ffffff;	
	color:#000000;
	overflow:hidden;
	
}
.overlay2 .Boxtitle{
	clear:both;
	border-bottom:1px #FFB24E solid; line-height:20px; background-color:#FF6A00; color: #ffffff; text-align:right;
}
.overlay2 .Boxcontent{clear:both;color:#FFFFFF; text-align:center; overflow:auto;}
		</style>
		";
		$js="<script src='".$Murl."/window_box.js' language='javascript' type='text/javascript'></script>
		<script language='javascript'>
		function showbigbdpic(url){
			unshowLightBox();
			showLightBox('<iframe src=".$Murl."/showpic.php?url='+url+' iframeframeborder=0  width=600 height=600/>','overlay2',600,600);
		}
		
		</script>";
		$query=$db->query("select * from {$_pre}homepage_pic where pid in($rsdb[bd_pics])");
		while($rs=$db->fetch_array($query)){
			$show.='<li style="width:120px; border:0px solid #cecece; height:'.($titlelength?"140px":"120px").';  background-color:#ffffff; float:left; list-style:none;  margin:5px 5px 5px 5px;text-align:center; cursor:hand; padding:5px;"><img src="'.$webdb[www_url].'/'.$user_picdir.'/'.$rs[uid].'/'.$rs[url].'.gif"   width="120" height="120"  alt="'.$rs[title].'" title="'.$rs[title].'" onclick="showbigbdpic(\''.$webdb[www_url].'/'.$user_picdir.'/'.$rs[uid].'/'.$rs[url].'\')"/>'.($titlelength?get_word($rs[title],$titlelength):"").'</li>';
		}
		return $show.$js."</div>";
	}else{
		return "";
	}

}

?>