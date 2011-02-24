<?php
$pmword = $pmNUM ? "<a href=\"pm.php?job=list\" style=\"color:red;\">你有新的消息,请注意查收!!</a>" : "<a href=\"pm.php?job=list\" style=\"color:#888;\">你暂时没有新消息!</a>";
//收到的询价单
$rt=$db->get_one("SELECT COUNT(*) AS num FROM {$pre}sell_join A LEFT JOIN {$pre}sell_content B ON A.cid=B.id WHERE B.uid='$lfjuid'");
$data[form1_num]=$rt[num];
//收到的报价单
$rt=$db->get_one("SELECT COUNT(*) AS num FROM {$pre}buy_join A LEFT JOIN {$pre}buy_content B ON A.cid=B.id WHERE B.uid='$lfjuid'");
$data[form2_num]=$rt[num];
//发出的询价单
$rt=$db->get_one("SELECT COUNT(*) AS num FROM {$pre}sell_join WHERE uid='$lfjuid'");
$data[form3_num]=$rt[num];
//发出的报价单
$rt=$db->get_one("SELECT COUNT(*) AS num FROM {$pre}buy_join WHERE uid='$lfjuid'");
$data[form4_num]=$rt[num];
//发布的供应信息
$rt=$db->get_one("SELECT COUNT(*) AS num FROM {$pre}sell_content WHERE uid='$lfjuid'");
$data[sell_num]=$rt[num];
//发布的求购信息
$rt=$db->get_one("SELECT COUNT(*) AS num FROM {$pre}buy_content WHERE uid='$lfjuid'");
$data[buy_num]=$rt[num];
//发布的职位信息
$rt=$db->get_one("SELECT COUNT(*) AS num FROM {$pre}hr_content WHERE uid='$lfjuid'");
$data[hr_num]=$rt[num];
//收藏的简历
$rt=$db->get_one("SELECT COUNT(*) AS num FROM {$pre}hr_memberdb A LEFT JOIN {$pre}hr_join B ON A.memberuid=B.uid LEFT JOIN {$pre}hr_content_2 C ON A.memberuid=C.uid WHERE A.companyuid='$lfjuid'");
$data[in_num]=$rt[num];
//申请的职位
$rt=$db->get_one("SELECT COUNT(*) AS num FROM {$pre}hr_apply A LEFT JOIN {$pre}hr_content B ON A.cid=B.id WHERE A.uid='$lfjuid'");
$data[post_num]=$rt[num];
//应聘单
$rt=$db->get_one("SELECT COUNT(*) AS num FROM {$pre}hr_apply A LEFT JOIN {$pre}hr_content B ON A.cid=B.id WHERE B.uid='$lfjuid'");
$data[yp_num]=$rt[num];
//展会
$rt=$db->get_one("SELECT COUNT(*) AS num FROM {$pre}showroom_content WHERE uid=$lfjuid");
$data[zh_num]=$rt[num];
//展馆
$rt=$db->get_one("SELECT COUNT(*) AS num FROM {$pre}showroom_room WHERE uid=$lfjuid");
$data[zlg_num]=$rt[num];

?>