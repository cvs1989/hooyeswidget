<?php
$pmword = $pmNUM ? "<a href=\"pm.php?job=list\" style=\"color:red;\">�����µ���Ϣ,��ע�����!!</a>" : "<a href=\"pm.php?job=list\" style=\"color:#888;\">����ʱû������Ϣ!</a>";
//�յ���ѯ�۵�
$rt=$db->get_one("SELECT COUNT(*) AS num FROM {$pre}sell_join A LEFT JOIN {$pre}sell_content B ON A.cid=B.id WHERE B.uid='$lfjuid'");
$data[form1_num]=$rt[num];
//�յ��ı��۵�
$rt=$db->get_one("SELECT COUNT(*) AS num FROM {$pre}buy_join A LEFT JOIN {$pre}buy_content B ON A.cid=B.id WHERE B.uid='$lfjuid'");
$data[form2_num]=$rt[num];
//������ѯ�۵�
$rt=$db->get_one("SELECT COUNT(*) AS num FROM {$pre}sell_join WHERE uid='$lfjuid'");
$data[form3_num]=$rt[num];
//�����ı��۵�
$rt=$db->get_one("SELECT COUNT(*) AS num FROM {$pre}buy_join WHERE uid='$lfjuid'");
$data[form4_num]=$rt[num];
//�����Ĺ�Ӧ��Ϣ
$rt=$db->get_one("SELECT COUNT(*) AS num FROM {$pre}sell_content WHERE uid='$lfjuid'");
$data[sell_num]=$rt[num];
//����������Ϣ
$rt=$db->get_one("SELECT COUNT(*) AS num FROM {$pre}buy_content WHERE uid='$lfjuid'");
$data[buy_num]=$rt[num];
//������ְλ��Ϣ
$rt=$db->get_one("SELECT COUNT(*) AS num FROM {$pre}hr_content WHERE uid='$lfjuid'");
$data[hr_num]=$rt[num];
//�ղصļ���
$rt=$db->get_one("SELECT COUNT(*) AS num FROM {$pre}hr_memberdb A LEFT JOIN {$pre}hr_join B ON A.memberuid=B.uid LEFT JOIN {$pre}hr_content_2 C ON A.memberuid=C.uid WHERE A.companyuid='$lfjuid'");
$data[in_num]=$rt[num];
//�����ְλ
$rt=$db->get_one("SELECT COUNT(*) AS num FROM {$pre}hr_apply A LEFT JOIN {$pre}hr_content B ON A.cid=B.id WHERE A.uid='$lfjuid'");
$data[post_num]=$rt[num];
//ӦƸ��
$rt=$db->get_one("SELECT COUNT(*) AS num FROM {$pre}hr_apply A LEFT JOIN {$pre}hr_content B ON A.cid=B.id WHERE B.uid='$lfjuid'");
$data[yp_num]=$rt[num];
//չ��
$rt=$db->get_one("SELECT COUNT(*) AS num FROM {$pre}showroom_content WHERE uid=$lfjuid");
$data[zh_num]=$rt[num];
//չ��
$rt=$db->get_one("SELECT COUNT(*) AS num FROM {$pre}showroom_room WHERE uid=$lfjuid");
$data[zlg_num]=$rt[num];

?>