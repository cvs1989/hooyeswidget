<?php
require_once(PHP168_PATH."inc/artic_function.php");
header('Content-Type: text/html; charset=gb2312'); 
if(!$lfjid)
{
	die("<A HREF='$webdb[www_url]/do/login.php' onclick=\"clickEdit.cancel('clickEdit_$TagId')\">请先登录</A>");
}
if($act=="do"){
	if(!$lfjuid){
		$power=0;
	}elseif($web_admin){
		$power=2;
		$rs=$db->get_one("SELECT S.admin,S.fid,A.* FROM {$pre}article$erp A LEFT JOIN {$pre}sort S ON A.fid=S.fid WHERE A.aid='$id'");
	}else{
		$rs=$db->get_one("SELECT S.admin,S.fid,A.* FROM {$pre}article$erp A LEFT JOIN {$pre}sort S ON A.fid=S.fid WHERE A.aid='$id'");
		$detail=@explode(",",$rs[admin]);
		if($rs[uid]==$lfjuid){
			$power=1;
		}elseif($lfjid&&@in_array($lfjid,$detail)){
			$power=2;
		}else{
			$power=0;
		}
	}
	if($power==0){
		die("你无权操作");
	}
	if($step==2){
		if($action=="delete")
		{
			do_work($id,$action);

			//静态页处理
			make_article_html("list.php?fid=$rs[fid]",'del',$rs);
			refreshto("list.php?fid=$rs[fid]","删除成功",1);
		}
		elseif($power==2)
		{
			do_work($id,$action);

			//静态页处理
			if($action!="com"&&$action!="uncom")
			{
				make_article_html("list.php?fid=$rs[fid]",'',$rs);
			}
			refreshto("$FROMURL","操作成功",1);
		}
	}
	else
	{
		$rs=$db->get_one("SELECT * FROM {$pre}article$erp WHERE aid='$id'");
		echo "<A HREF=\"$webdb[www_url]/member/post.php?job=postnew&fid=$fid\">新发表</A><br><A HREF=\"$webdb[www_url]/member/index.php?main=post.php?job=manage&aid=$id\">修改</A><br><A HREF=\"$webdb[www_url]/do/job.php?job=$job&act=$act&step=2&action=delete&id=$id\" onclick=\"return confirm('你确认要删除吗?');\">删除</A><br>";
		if($rs[levels]&&$power==2){
			echo "(已推荐)<A HREF=\"$webdb[www_url]/do/job.php?job=$job&act=$act&step=2&action=uncom&levels=0&id=$id\">取消推荐</A><br>";
		}elseif($power==2){
			echo "(未推荐)<A HREF=\"$webdb[www_url]/do/job.php?job=$job&act=$act&step=2&action=com&levels=1&id=$id\">推荐</A><br>";
		}
		if($rs[yz]&&$power==2){
			echo "(已审核)<A HREF=\"$webdb[www_url]/do/job.php?job=$job&act=$act&step=2&action=unyz&yz=0&id=$id\">取消审核</A><br>";
		}elseif($power==2){
			echo "(未审核)<A HREF=\"$webdb[www_url]/do/job.php?job=$job&act=$act&step=2&action=yz&yz=1&id=$id\">审核</A><br>";
		}
		if($rs['list']>$timestamp&&$power==2){
			echo "(已置顶)<A HREF=\"\"><A HREF=\"$webdb[www_url]/do/job.php?job=$job&act=$act&step=2&action=untop&top=$timestamp&id=$id\">取消置顶</A></A><br>";
		}elseif($power==2){
			$times=3600*24*30;
			echo "(未置顶)<A HREF=\"$webdb[www_url]/do/job.php?job=$job&act=$act&step=2&action=top&toptime=$times&id=$id\">置顶</A><br>";
		}
	}
}
?>