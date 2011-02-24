-- «®“∆∑÷¿‡
INSERT INTO c_sell_sort(fid, fup, name, mid, class, sons, type, admin, list, listorder, passwd, logo, descrip, style, template, jumpurl, maxperpage, metatitle, metakeywords, metadescription, allowcomment, allowpost, allowviewtitle, allowviewcontent, allowdownload, forbidshow, config, index_show, contents, tableid, dir_name, ifcolor) 
SELECT
fid, fup, name, mid, class, sons, type, admin, list, listorder, passwd, logo, descrip, style, template, jumpurl, maxperpage, '' metatitle, metakeywords, metadescription, allowcomment, allowpost, allowviewtitle, allowviewcontent, allowdownload, forbidshow, config, index_show, contents,'' tableid,'' dir_name,0 ifcolor
FROM p8_business_sort order by fid
-- end


SELECT SQL_CALC_FOUND_ROWS B.*,A.*,C.title AS companyname,C.renzheng FROM c_sell_content A LEFT JOIN c_sell_content_0 B ON A.id=B.id 
LEFT JOIN c_hy_company C ON A.uid=C.uid WHERE A.fid='1001' ORDER BY A.list DESC LIMIT 0,15

select * from c_sell_sort limit 1
go
select * from p8_business_sort order by fid asc limit 1
select
fid, fup, name, mid, class, sons, type, admin, list, listorder, passwd, logo, descrip, style, template, jumpurl, maxperpage, '' metatitle, metakeywords, metadescription, allowcomment, allowpost, allowviewtitle, allowviewcontent, allowdownload, forbidshow, config, index_show, contents,'' tableid,'' dir_name,0 ifcolor
from p8_business_sort order by fid