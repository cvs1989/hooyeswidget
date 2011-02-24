<?php
!function_exists('html') && exit('ERR');
if($job=="list"&&$Apower[article_group_config])
{
	$query=$db->query("SELECT * FROM `{$pre}group` WHERE gid!=3 AND gid!=4 ORDER BY gptype DESC,levelnum ASC");
	while( $rs=$db->fetch_array($query) ){
		if($rs[gptype]){
			$listdb_1[]=$rs;
			$rs[ifSystem]='高级系统组';
		}else{
			$listdb_0[]=$rs;
			$rs[ifSystem]='普通会员组';
		}
		$listdb[]=$rs;
	}
	require(dirname(__FILE__)."/"."head.php");
	require(dirname(__FILE__)."/"."template/article_group/list.htm");
	require(dirname(__FILE__)."/"."foot.php");
}

elseif($action=="edit"&&$Apower[article_group_config])
{
	$query = $db->query("SELECT * FROM {$pre}sort");
	while($rs = $db->fetch_array($query)){
		$detail=explode(",",$rs[allowpost]);
		if(@in_array($gid,$detail)){
			if(!in_array($rs[fid],$fiddb)){
				foreach( $detail AS $key=>$value){
					if($value==$gid){
						unset($detail[$key]);
					}
				}
				$allowpost=implode(",",$detail);
				$db->query("UPDATE {$pre}sort SET allowpost='$allowpost' WHERE fid='$rs[fid]' ");
			}
		}
		elseif(in_array($rs[fid],$fiddb))
		{
			$detail[]=$gid;
			$allowpost=implode(",",$detail);
			$db->query("UPDATE {$pre}sort SET allowpost='$allowpost' WHERE fid='$rs[fid]' ");
		}
	}
	$query = $db->query("SELECT * FROM {$pre}article_module WHERE ifclose=0");
	while($rs = $db->fetch_array($query)){
		$detail=explode(",",$rs[allowpost]);
		if(@in_array($gid,$detail)){
			if(!in_array($rs[id],$module_db)){
				foreach( $detail AS $key=>$value){
					if($value==$gid){
						unset($detail[$key]);
					}
				}
				$allowpost=implode(",",$detail);
				$db->query("UPDATE {$pre}article_module SET allowpost='$allowpost' WHERE id='$rs[id]' ");
			}
		}
		elseif(in_array($rs[id],$module_db))
		{
			$detail[]=$gid;
			$allowpost=implode(",",$detail);
			$db->query("UPDATE {$pre}article_module SET allowpost='$allowpost' WHERE id='$rs[id]' ");
		}
	}

	$rsdb=$db->get_one(" SELECT powerdb FROM `{$pre}group` WHERE gid='$gid' ");
	$power_db=@unserialize($rsdb[powerdb]);
	if(is_array($power_db))
	{
		$powerdb=array_merge($power_db,$powerdb);
	}

	$_powerdb=addslashes(@serialize($powerdb));
	$db->query(" UPDATE `{$pre}group` SET powerdb='$_powerdb' WHERE gid='$gid' ");
	write_group_cache();
	jump("修改成功","?lfj=$lfj&job=edit&gid=$gid");
}

elseif($job=="edit"&&$Apower[article_group_config])
{
	$rsdb=$db->get_one(" SELECT * FROM `{$pre}group` WHERE gid='$gid' ");
	$powerdb=@unserialize($rsdb[powerdb]);

	$PassContribute[intval($powerdb[PassContribute])]=" checked ";
	$EditPassPower[intval($powerdb[EditPassPower])]=' checked ';
	$SearchArticleType[intval($powerdb[SearchArticleType])]=' checked ';
	$SetTileColor[intval($powerdb[SetTileColor])]=' checked ';
	$SetSellArticle[intval($powerdb[SetSellArticle])]=' checked ';
	$SetSmallTitle[intval($powerdb[SetSmallTitle])]=' checked ';
	$SetSpecialArticle[intval($powerdb[SetSpecialArticle])]=' checked ';
	$SetArticleKeyword[intval($powerdb[SetArticleKeyword])]=' checked ';
	$PostArticleYzImg[intval($powerdb[PostArticleYzImg])]=' checked ';
	$SelectArticleTpl[intval($powerdb[SelectArticleTpl])]=' checked ';
	$SetArticleTpl[intval($powerdb[SetArticleTpl])]=' checked ';
	$SelectArticleStyle[intval($powerdb[SelectArticleStyle])]=' checked ';
	$SetArticlePosttime[intval($powerdb[SetArticlePosttime])]=' checked ';
	$SetArticleViewtime[intval($powerdb[SetArticleViewtime])]=' checked ';
	$SetArticleHitNum[intval($powerdb[SetArticleHitNum])]=' checked ';
	$SetArticlePassword[intval($powerdb[SetArticlePassword])]=' checked ';
	$SetArticleDownGroup[intval($powerdb[SetArticleDownGroup])]=' checked ';
	$SetArticleViewGroup[intval($powerdb[SetArticleViewGroup])]=' checked ';
	$SetArticleJumpurl[intval($powerdb[SetArticleJumpurl])]=' checked ';
	$SetArticleIframeurl[intval($powerdb[SetArticleIframeurl])]=' checked ';
	$SetArticleDescription[intval($powerdb[SetArticleDescription])]=' checked ';
	$SetArticleTopCom[intval($powerdb[SetArticleTopCom])]=' checked ';
	$CommentArticleYzImg[intval($powerdb[CommentArticleYzImg])]=' checked ';
	$SetHtmlName[intval($powerdb[SetHtmlName])]=' checked ';
	$SetVote[intval($powerdb[SetVote])]=' checked ';
	$PostNoDelCode[intval($powerdb[PostNoDelCode])]=' checked ';
	$PassContributeSP[intval($powerdb[PassContributeSP])]=' checked ';

	$query = $db->query("SELECT * FROM {$pre}sort");
	while($rs = $db->fetch_array($query)){
		if($rs[allowpost]){
			$detail=explode(",",$rs[allowpost]);
			if(in_array($gid,$detail)){
				$fiddb[]=$rs[fid];
			}
		}
	}
	$sort_fid=$Guidedb->Select("{$pre}sort",'fiddb[]',$fiddb,'','0','',1,'20');

	$query = $db->query("SELECT * FROM {$pre}article_module  WHERE ifclose=0");
	while($rs = $db->fetch_array($query)){
		if($rs[allowpost]){
			$detail=explode(",",$rs[allowpost]);
			if(in_array($gid,$detail)){
				$m_db[]=$rs[id];
			}
		}
	}

	$query = $db->query("SELECT * FROM {$pre}article_module  WHERE ifclose=0 ORDER BY list DESC");
	while($rs = $db->fetch_array($query)){
		$rs[checked]=' ';
		if(in_array($rs[id],$m_db)){
			$rs[checked]=' checked ';
		}
		$module_db[]=$rs;
	}

	require(dirname(__FILE__)."/"."head.php");
	require(dirname(__FILE__)."/"."template/article_group/mod.htm");
	require(dirname(__FILE__)."/"."foot.php");
}

?>