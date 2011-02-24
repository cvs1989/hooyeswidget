-- 迁移分类
INSERT INTO c_hy_sort(fid, fup, name, mid, class, sons, type, admin, list, listorder, passwd, logo, descrip, style, template, jumpurl, maxperpage, metatitle, metakeywords, metadescription, allowcomment, allowpost, allowviewtitle, allowviewcontent, allowdownload, forbidshow, config, index_show, contents, tableid, dir_name, ifcolor) 
   SELECT
fid, fup, name, mid, class, sons, type, admin, list, listorder, passwd, logo, descrip, style, template, jumpurl, maxperpage, '' metatitle, metakeywords, metadescription, allowcomment, allowpost, allowviewtitle, allowviewcontent, allowdownload, forbidshow, config, index_show, contents,'' tableid,'' dir_name,0 ifcolor
FROM p8_business_sort order by fid
-- end
select * from c_sell_sort where fup=0 and fid>1000
select fid, fup, name, mid,list from c_sell_sort where fup=0 and fid>1000

update c_hy_sort set list=1100-fid where fup=0 and fid>1000


=========================================================
-- 迁移分类
INSERT INTO c_buy_sort(fid, fup, name, mid, class, sons, type, admin, list, listorder, passwd, logo, descrip, style, template, jumpurl, maxperpage, metatitle, metakeywords, metadescription, allowcomment, allowpost, allowviewtitle, allowviewcontent, allowdownload, forbidshow, config, index_show, contents, tableid, dir_name, ifcolor) 
   SELECT
fid, fup, name, mid, class, sons, type, admin, list, listorder, passwd, logo, descrip, style, template, jumpurl, maxperpage, '' metatitle, metakeywords, metadescription, allowcomment, allowpost, allowviewtitle, allowviewcontent, allowdownload, forbidshow, config, index_show, contents,'' tableid,'' dir_name,0 ifcolor
FROM p8_business_sort order by fid
-- end
update c_buy_sort set list=1100-fid where fup=0 and fid>1000


select    id   ,  fid  ,   uid from  c_sell_content_2

