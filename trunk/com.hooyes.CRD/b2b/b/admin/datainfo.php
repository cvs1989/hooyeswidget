<?php
$day=abs(intval($day));
$day=$day?$day:100000000;
$posttime24=$timestamp-($day*24*60*60);
$daysel[$day]=" style='color:red' ";

//内供应
@extract($db->get_one("select count(id) as data24_ctype1 from {$_pre}content_sell where posttime > $posttime24" ));
@extract($db->get_one("select count(id) as data24_ctype1_unyz from {$_pre}content_sell where yz<1 and posttime > $posttime24"));

//内求购
@extract($db->get_one("select count(id) as data24_ctype2 from {$_pre}content_buy where posttime > $posttime24" ));
@extract($db->get_one("select count(id) as data24_ctype2_unyz from {$_pre}content_buy where yz<1 and posttime > $posttime24"));

//内会员
@extract($db->get_one("select count(uid) as data24_user from {$pre}memberdata where regdate  > $posttime24 "));
@extract($db->get_one("select count(uid) as data24_user_unyz from {$pre}memberdata where regdate  > $posttime24 and yz<1 "));

//内商家登记
@extract($db->get_one("select count(rid) as data24_company from {$_pre}company where posttime > $posttime24"));
@extract($db->get_one("select count(rid) as data24_company_unyz from {$_pre}company where posttime > $posttime24 and yz<1"));

//24认证
@extract($db->get_one("select count(id) as data24_renzheng1 from {$_pre}renzheng where post_time > $posttime24 and level=1 ")); 
@extract($db->get_one("select count(id) as data24_renzheng2 from {$_pre}renzheng where post_time > $posttime24 and level=2 ")); 
@extract($db->get_one("select count(id) as data24_renzheng3 from {$_pre}renzheng where post_time > $posttime24 and level=3 ")); 
//内agents
@extract($db->get_one("select count(ag_id) as data24_agent from {$_pre}agents where posttime > $posttime24 "));   
@extract($db->get_one("select count(vo_id) as data24_vip from {$_pre}viphis where posttime > $posttime24 "));

//职位库
@extract($db->get_one("select count(jobs_id) as data24_jobs from {$_pre}hr_jobs  where posttime > $posttime24 "));
@extract($db->get_one("select count(jobs_id) as data24_jobs_unyz from {$_pre}hr_jobs  where posttime > $posttime24 and is_check<1 "));

//才人库
@extract($db->get_one("select count(re_id) as data24_resume from {$_pre}hr_resume  where posttime > $posttime24 "));
@extract($db->get_one("select count(re_id) as data24_resume_unyz from {$_pre}hr_resume  where posttime > $posttime24 and is_check<1 "));

//展会
@extract($db->get_one("select count(zh_id) as data24_zh from {$_pre}zh_content  where posttime > $posttime24 "));
@extract($db->get_one("select count(zh_id) as data24_zh_unyz from {$_pre}zh_content  where posttime > $posttime24 and yz<1 "));
//展览馆
@extract($db->get_one("select count(sr_id) as data24_zlg from {$_pre}zh_showroom   where posttime > $posttime24 "));
@extract($db->get_one("select count(sr_id) as data24_zlg_unyz from {$_pre}zh_showroom   where posttime > $posttime24 and yz<1 "));

//询价单
@extract($db->get_one("select count(id) as form1 from {$_pre}form1   where posttime > $posttime24 " ));
//报价单
@extract($db->get_one("select count(id) as form2 from {$_pre}form2   where posttime > $posttime24 " ));

//举报
@extract($db->get_one("select count(rid) as report  from {$_pre}report    where posttime > $posttime24 " ));
//评论
@extract($db->get_one("select count(cid) as comments   from {$_pre}comments     where posttime > $posttime24 " ));


//tuiguang
@extract($db->get_one("select count(tg_myid) as tuiguang  from {$_pre}tg    where posttime > $posttime24 " ));

?>