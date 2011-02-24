<?php
require("global.php");



if($lfjid)
	{	
		
		if( ereg("^pwbbs",$webdb[passport_type]) )
		{
			@extract($db->get_one("SELECT COUNT(*) AS pmNUM FROM `{$TB_pre}msg` WHERE `touid`='$lfjuid' AND type='rebox' AND ifnew=1"));
		}
		elseif( ereg("^dzbbs",$webdb[passport_type]) )
		{
			@extract($db->get_one("SELECT COUNT(*) AS pmNUM FROM `{$TB_pre}pms` WHERE `msgtoid`='$lfjuid' AND folder='inbox' AND new=1"));
		}
		else
		{
			@extract($db->get_one("SELECT COUNT(*) AS pmNUM FROM `{$pre}pm` WHERE `touid`='$lfjuid' AND type='rebox' AND ifnew='1'"));
		}
		if(!$pmNUM){
			$MSG="<A  HREF=\"$webdb[www_url]/member/pm.php?job=list\"  target='main'>站内消息</A>";
		}else{
			$MSG="<A   HREF=\"$webdb[www_url]/member/pm.php?job=list\" style=\"color:#8BD9A4;\"  target='main'>新的消息({$pmNUM})</a>";

		}
		if($set_notplaynotice==1){
				set_cookie('notplaynotice',1);
				$notplaynotice=1;
			}elseif($set_notplaynotice==2){
				set_cookie('notplaynotice',2);
				$notplaynotice=2;
			}else{
				$notplaynotice=get_cookie('notplaynotice');
			}
			
			if(!$notplaynotice || $notplaynotice==2){
				if($pmNUM){
				$MSG.='<div style="display:none;"><object id="MediaPlayer1" width="350" height="64" classid="CLSID:6BF52A52-394A-11d3-B153-00C04F79FAA6" codebase="http://activex.microsoft.com/activex/controls/mplayer/en/nsmp2inf.cab#Version=6,4,7,1112" align="baseline" border="0" standby="Loading Microsoft Windows Media Player components..." type="application/x-oleobject"><param name="URL" value="images/newmsg.mp3" >
<param name="autoStart" value="true">
<param name="invokeURLs" value="false">
<param name="playCount" value="1">
<param name="loop" value="1">
<param name="defaultFrame" value="datawindow">
<embed src="images/newmsg.mp3" align="baseline" border="0" width="0" height="0" type="application/x-mplayer2"pluginspage="" name="MediaPlayer1" showcontrols="1" showpositioncontrols="0" showaudiocontrols="1" showtracker="1" showdisplay="0" showstatusbar="1" autosize="0" showgotobar="0" showcaptioning="0" autostart="1" autorewind="0" animationatstart="0" transparentatstart="0" allowscan="1" enablecontextmenu="1" clicktoplay="0" defaultframe="datawindow" invokeurls="0"></embed>
</object></div>';
				}
				$MSG.="<a href='?set_notplaynotice=1'>(关闭提示音)</a>";
			}else{
				$MSG.="<a href='?set_notplaynotice=2'>(开启提示音)</a>";
		}

		$rt=$db->get_one("select rid from {$_pre}company where uid='$lfjuid' limit 1");

	}

require(dirname(__FILE__)."/"."template/header.htm");
?>