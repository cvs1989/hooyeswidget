var MAX_ITEMS=20,taotao_pane_id="id_apiPane",KEY_WORD_YJ="[假]";
var taotao_sr=new Array('网页','QQ','QQ空间','QQ签名','短信','手机上网','MSN','MSN签名','手机QQ','彩信','订阅',"",'QQ状态');
function LK(str){return document.createElement(str);}
function LL(str){return document.getElementById(str);}
function LG(str,target){
var re=new RegExp("http(s)?://([a-z0-9?.=%&-_;#\\/])+","ig");
if(target=='_blank'){str=str.replace(re,"<a target='_blank' href='$&'>$&</a>");}
else{str=str.replace(re,"<a href='$&'>$&</a>");}
return str;}
function LH(str){
if(typeof(str)=="string"){str=str.replace(/[\r\n\t\0]/g,"");return str;}}
function LE(str,target){str=LH(str);
var re=/(\[URL=(.[^\[]*)\])(.*?)(\[\/URL\])/ig;
if(target=='_blank'){str=str.replace(re,"<A HREF=$2 TARGET=_blank>$3</A>");}
else{str=str.replace(re,"<A HREF=$2>$3</A>");}return str;}
function initApi(){
if(typeof taotao_qq=='undefined'){alert("we can not get qq num,show nothing...!");return;}
if(typeof taotao_num=='undefined'||taotao_num<=0||taotao_num>20){taotao_num=20;}
if(typeof taotao_type=='undefined'||taotao_type <0||taotao_type >1){taotao_type=0;}}
function LI(url){g_cximg=null;g_cximg=new Array();var re=/\[IMG\](.+?)\[\/IMG\]/ig,tt =url.match(re);g_ubbcon=url.replace( re,'' );
for( var i=0; i<tt.length; ++i ){g_cximg[g_cximg.length]=tt[i].replace(re,"$1");}}
function LF(str){if(str.substr(0,3)==KEY_WORD_YJ){str='<font style="color:red">'+KEY_WORD_YJ+'</font>'+str.substr(3,str.length-3);}return str;}
function doApi(obj){if(obj.ret != 0 ){pane.innerHTML ="对不起！暂时无法获取信息,请稍候重试...";return;}
var qq=obj.ui.qq,name=obj.ui.name,usn=obj.ui.usn,rank=obj.ui.rank,lrank=obj.ui.lrank,rec=obj.rec;
var arr=obj.posts,i=0,data,pane=LL(taotao_pane_id),li,span,a,bD=false;
for(i=0; i<arr.length; i++){data=arr[i];li=LK("li");li.style.wordBreak="break-all";li.style.height="auto";li.style.overflowY ="auto";
if(obj.type==1){a=LK('a');a.style.marginRight="8px";a.innerText=data.nm;a.textContent=data.nm;a.href="http://www.taotao.com/v1/space/"+data.qq;li.appendChild(a);
if(data.pqq){li.appendChild(document.createTextNode("@"));a=LK('a');a.style.marginRight="8px";a.innerText=data.pnm;a.textContent=data.pnm;a.href="http://www.taotao.com/v1/space/"+data.pqq;li.appendChild(a);}}span=LK("span");if(data.sr==10){span.innerHTML=LE(data.cn,"_blank");}else if(data.sr==9){LI(data.cn);var cxlen=g_cximg.length;span.innerHTML =g_ubbcon+'&nbsp;<a target="_blank" href="http://www.taotao.com/caixin.shtml?qq='+qq+"&tid="+data.id+'">点击查看彩信</a>';}else{span.innerHTML=LF(LG(data.cn,"_blank"));}li.appendChild(span);span=LK("span");span.style.marginLeft="8px";a=LK('a');a.style.color="#929091";if(parseInt(data.pqq)>0){a.href='http://www.taotao.com/v1/reply/t.'+data.pid+'/u.'+data.pqq;}
else{a.href='http://www.taotao.com/v1/reply/t.'+data.id+'/u.'+qq+"/?www.hooyes.com";}
a.target='_blank';
a.innerHTML=LJ(data.time);
li.className=hooyesCss(data.time);
li.appendChild(span);span.appendChild(a);span=LK("span");span.style.marginLeft="4px";span.innerText= "通过 "+taotao_sr[data.sr];span.textContent="通过 "+taotao_sr[data.sr];li.appendChild(span);pane.appendChild(li);}}function LJ(t){if( t.charAt(1) != ',' ){return t ;}if( t.length<2 ){return "";}var n=t.charAt( 0 ),v =t.substr( 2,t.length ),s='';if( n==1 ){s='约&nbsp;'+v+'&nbsp;秒前';}else if( n==2 ){s='约&nbsp;'+v+'&nbsp;分钟前';}else if( n==3 ){s='约&nbsp;'+v+'&nbsp;小时前';}else if( n==4 ){s='约&nbsp;1&nbsp;天前';}else if( n==5 ){s='约&nbsp;3&nbsp;天前';}else if( n==6 ){s='约&nbsp;1&nbsp;周前';}else if( n==7 ){s=v;}return s;}
function hooyesCss(t){if( t.charAt(1) != ',' ){return 'blue' ;}if( t.length<2 ){return  'blue';}var n=t.charAt( 0 ),v =t.substr( 2,t.length ),s='';if(n<3){s='red'}else{s='blue'} return s;}
function inclApi(){
var src="http://www.taotao.com/cgi-bin/msgj?qq="+taotao_qq+"&num="+taotao_num+"&t="+taotao_type;
var htm='<div>'+'<ul id='+taotao_pane_id+' style="margin-bottom:5px">'+'</ul>';
//+'<table cellpadding="0" cellspacing="0" border="0" width="100%">'+'<tr>'+'<td align="left">'+'<a href="http://www.taotao.com/v1/space/'+taotao_qq+'" style="color:#0278c2;float:left;display:block;">更多唠叨</a>'+'</td>'+'<td align="right">'+'<a href="http://www.taotao.com" title="滔滔" target="_blank"  style="width:96px;height:20px;cursor:pointer;float:left;display:block;*filter:progid:DXImageTransform.Microsoft.AlphaImageLoader(src=\'http://www.taotao.com/images/logo.png\',sizingMethod=\'image\');background:url(http://www.taotao.com/images/logo.png) no-repeat left top !important;*background:none;"></a>'+'</td>'+'</tr>'+'</table>'+'</div>';
htm=htm+"</div>";
//window.document.write(htm); write in page by hooyes
window.document.write('<sc'+'ript type="text/javascript" charset="utf-8" src="'+src+'"></'+'script>');}
initApi();
inclApi();