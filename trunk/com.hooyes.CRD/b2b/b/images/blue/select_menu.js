var AJAX_S={
	http_request:false,
	DivObj:null,
	waitstate:null,
	success:null,
	get:function (divid,url) {
		AJAX_S.http_request = false;
		AJAX_S.DivObj = document.getElementById(divid);
		if(window.XMLHttpRequest) { //Mozilla 浏览器
			AJAX_S.http_request = new XMLHttpRequest();
			if (AJAX_S.http_request.overrideMimeType) {//设置MiME类别
				AJAX_S.http_request.overrideMimeType('text/xml');
			}
		}else if (window.ActiveXObject) { // IE浏览器
			try {
				AJAX_S.http_request = new ActiveXObject("Msxml2.XMLHTTP");
			} catch (e) {
				try {
					AJAX_S.http_request = new ActiveXObject("Microsoft.XMLHTTP");
				} catch (e) {}
			}
		}
		if (!AJAX_S.http_request) {
			window.alert("不能创建XMLHttpRequest对象实例.");
			return false;
		}
		AJAX_S.http_request.onreadystatechange = AJAX_S.processRequest;
		AJAX_S.http_request.open("GET", url+"&"+Math.random(), false);
		AJAX_S.http_request.send(null);
	},
    processRequest:function () {
        if (AJAX_S.http_request.readyState == 4) {
            if (AJAX_S.http_request.status == 200) {
				if(AJAX_S.DivObj!=null){
					AJAX_S.DivObj.innerHTML=AJAX_S.http_request.responseText;
				}
            } else {
                alert("您所请求的页面有异常。");
            }
        }else{
			if(AJAX_S.DivObj!=null){
				AJAX_S.DivObj.innerHTML='<hr>请等待...<hr>';
			}
		}
    }
}

function showfid_S(showThisId,obj,fuid,inputid,type)
{
	//主要是处理赋值后要切换.就要隐藏一些存在的选项
	oo=document.body.getElementsByTagName("span");
	ppck=0;
	for(var i=0;i<oo.length;i++){
		if(oo[i].id==showThisId){
			ppck=1;
		}
		if(ppck==1&&oo[i].getAttribute("divname")==fuid){
			oo[i].style.display='none';
		}
	}
	
	/*
	if (document.getElementById(showThisId)!=null)
	{
		if(document.getElementById(showThisId).innerHTML!='')
		{
			document.getElementById(showThisId).style.display='';
		}
	}
	*/

	for(i=1;i<obj.options.length;i++){
		obj.options[i].style.color='';
		if(i==obj.selectedIndex){
			obj.options[i].style.color='red';
		}
	}
	fid=parseInt(obj.options[obj.selectedIndex].value);
	if(fid>0)
	{
		document.getElementById(inputid).value=fid;
	}
	else
	{
		document.getElementById(inputid).value='';
	}
	if(fid<0){
		fid=-fid;
		get_div_S(showThisId,fuid,inputid,type,fid,'');
	}
	if (document.getElementById(showThisId)!=null)
	{
		if(document.getElementById(showThisId).innerHTML!='')
		{
			document.getElementById(showThisId).style.display='';
		}
	}
}

function get_div_S(showThisId,fuid,inputid,type,fid,ckfid){
	AJAX_S.get(showThisId,file+"?fuid="+fuid+"&inputid="+inputid+"&type="+type+"&fid="+fid+"&ckfid="+ckfid);
}
